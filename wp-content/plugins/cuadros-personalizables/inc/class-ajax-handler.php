<?php
/**
 * Clase para manejar las peticiones AJAX
 * 
 * @package CuadrosPersonalizables
 */

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

class CuadrosPersonalizables_Ajax_Handler {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Endpoints para usuarios logueados
        add_action('wp_ajax_save_personalization', array($this, 'save_personalization'));
        add_action('wp_ajax_load_personalization', array($this, 'load_personalization'));
        add_action('wp_ajax_reset_personalization', array($this, 'reset_personalization'));
        add_action('wp_ajax_get_personalizacion_base64', array($this, 'get_personalizacion_base64'));
        
        // Endpoints para usuarios no logueados
        add_action('wp_ajax_nopriv_save_personalization', array($this, 'save_personalization'));
        add_action('wp_ajax_nopriv_load_personalization', array($this, 'load_personalization'));
        add_action('wp_ajax_nopriv_reset_personalization', array($this, 'reset_personalization'));
        add_action('wp_ajax_nopriv_get_personalizacion_base64', array($this, 'get_personalizacion_base64'));
    }
    
/**
 * Mejoras para class-ajax-handler.php
 * Las siguientes modificaciones aseguran el guardado correcto de imágenes
 * y la correcta devolución de URLs en lugar de datos base64
 */

/**
 * Guardar personalización vía AJAX
 */
public function save_personalization() {
    // Verificar nonce de seguridad
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'personalizador_nonce')) {
        wp_send_json_error('Error de seguridad. Recarga la página e intenta nuevamente.');
        return;
    }
    
    // Obtener y validar datos
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $image_data = isset($_POST['image_data']) ? wp_unslash($_POST['image_data']) : '';
    $image_state = isset($_POST['image_state']) ? wp_unslash($_POST['image_state']) : '';
    
    // Verificar que tenemos datos válidos
    if (!$product_id) {
        wp_send_json_error('Error: ID de producto no válido');
        return;
    }
    
    // Asegurarse de que image_data sea una cadena base64 válida
    if (empty($image_data) || strpos($image_data, 'data:image') !== 0) {
        wp_send_json_error('Error: Datos de imagen inválidos o con formato incorrecto');
        return;
    }
    
    // Verificar que la cadena base64 no esté corrupta
    $base64_data = substr($image_data, strpos($image_data, ',') + 1);
    $decoded = base64_decode($base64_data, true);
    if ($decoded === false) {
        wp_send_json_error('Error: La imagen está corrupta. Por favor, inténtalo nuevamente.');
        return;
    }
    
    if (empty($image_state)) {
        wp_send_json_error('Error: Estado de imagen vacío');
        return;
    }
    
    // Verificar producto
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('El producto especificado no existe');
        return;
    }
    
    // Convertir base64 a archivo y guardar
    $image_url = $this->save_image_to_file($image_data, $product_id);
    if (!$image_url) {
        wp_send_json_error('Error al guardar la imagen en el servidor.');
        return;
    }
    
    // Procesar el estado JSON con mejor manejo de errores
    try {
        // Limpiar caracteres especiales que podrían causar problemas
        $image_state = preg_replace('/[[:cntrl:]]/', '', $image_state);
        
        // Decodificar para verificar validez
        $decoded_state = json_decode($image_state, true);
        
        // Verificar si hubo error al decodificar
        if (json_last_error() !== JSON_ERROR_NONE) {
            $json_error = json_last_error_msg();
            error_log("Error JSON al guardar personalización: " . $json_error);
            wp_send_json_error('Error al procesar el formato JSON: ' . $json_error);
            return;
        }
        
        // Verificar campos mínimos requeridos
        $required_fields = ['left', 'top', 'scaleX', 'scaleY', 'areaSimple'];
        foreach ($required_fields as $field) {
            if (!isset($decoded_state[$field])) {
                wp_send_json_error('Datos de personalización incompletos');
                return;
            }
        }
        
        // Todo parece correcto, guardar en la base de datos
        if (!class_exists('CuadrosPersonalizables_DB')) {
            wp_send_json_error('Módulo de base de datos no disponible');
            return;
        }
        
        $db = CuadrosPersonalizables_DB::get_instance();
        // Ahora pasamos image_url en lugar de image_data
        $id = $db->save_personalization($product_id, $image_url, $image_state);
        
        if ($id) {
            wp_send_json_success(array(
                'id' => $id,
                'image_url' => $image_url, // Devolvemos la URL en lugar de data
                'message' => 'Personalización guardada exitosamente'
            ));
        } else {
            wp_send_json_error('Error al guardar en base de datos');
        }
        
    } catch (Exception $e) {
        error_log('Excepción al guardar personalización: ' . $e->getMessage());
        wp_send_json_error('Error del sistema: ' . $e->getMessage());
    }
}

/**
 * Cargar personalización vía AJAX
 */
public function load_personalization() {
    // Verificar nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'personalizador_nonce')) {
        wp_send_json_error('Error de seguridad.');
    }
    
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $personalization_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if (!$product_id && !$personalization_id) {
        wp_send_json_error('ID de producto o personalización no válido.');
    }
    
    // Cargar de la base de datos
    if (!class_exists('CuadrosPersonalizables_DB')) {
        wp_send_json_error('Error del sistema: Módulo de base de datos no disponible.');
    }
    
    $db = CuadrosPersonalizables_DB::get_instance();
    
    // Si se proporcionó un ID específico, usar ese
    $personalization = null;
    if ($personalization_id) {
        $personalization = $db->get_personalization_by_id($personalization_id);
    } else {
        $personalization = $db->get_personalization($product_id);
    }
    
    if ($personalization) {
        // Añadir información de navegador actual para comparación/debugging
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
        $screen_info = isset($_POST['screen_info']) ? sanitize_text_field($_POST['screen_info']) : '';
        
        // Decodificar el estado para añadir metadatos
        $decoded_state = json_decode($personalization->image_state, true);
        
        // Si no se pudo decodificar, crear un objeto vacío
        if (!$decoded_state) {
            $decoded_state = array();
        }
        
        // Añadir información de carga
        if (!isset($decoded_state['meta'])) {
            $decoded_state['meta'] = array();
        }
        
        $decoded_state['meta']['loaded_at'] = current_time('mysql');
        $decoded_state['meta']['load_user_agent'] = $user_agent;
        $decoded_state['meta']['load_screen_info'] = $screen_info;
        
        // Reconvertir a JSON
        $personalization->image_state = json_encode($decoded_state, JSON_PRETTY_PRINT);
        
        // Actualizar el registro para fines de seguimiento
        $db->update_personalization_metadata($personalization->id, 
                                           array('loaded_at' => current_time('mysql')));
        
        // MODIFICACIÓN: Priorizar image_url si existe
        $image_data = '';
        if (!empty($personalization->image_url)) {
            // Si tenemos una URL, usarla
            $image_data = $personalization->image_url;
        } else if (!empty($personalization->image_data)) {
            // Compatibilidad con versiones antiguas
            $image_data = $personalization->image_data;
        }
        
        wp_send_json_success(array(
            'id' => $personalization->id,
            'image_data' => $image_data,
            'image_state' => $personalization->image_state,
            'created_at' => $personalization->created_at,
            'updated_at' => $personalization->updated_at
        ));
    } else {
        wp_send_json_error('No se encontró ninguna personalización guardada para este producto.');
    }
}
/**
 * Convierte y guarda una imagen base64 como archivo
 * 
 * @param string $base64_data La imagen en formato base64
 * @param int $product_id ID del producto
 * @return string|false La URL de la imagen guardada o false si hay error
 */
private function save_image_to_file($base64_data, $product_id) {
    // Verificar que es una cadena base64 válida
    if (strpos($base64_data, 'data:image') !== 0) {
        return false;
    }
    
    // Extraer datos y tipo de imagen
    $image_parts = explode(';base64,', $base64_data);
    if (count($image_parts) != 2) {
        return false;
    }
    
    $image_type_aux = explode('image/', $image_parts[0]);
    $image_type = isset($image_type_aux[1]) ? $image_type_aux[1] : 'png';
    $image_base64 = $image_parts[1];
    
    // Decodificar la imagen
    $image_data = base64_decode($image_base64);
    if (!$image_data) {
        return false;
    }
    
    // Obtener directorio de uploads
    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['basedir'] . '/personalizados/';
    $upload_url = $upload_dir['baseurl'] . '/personalizados/';
    
    // Crear directorio si no existe
    if (!file_exists($upload_path)) {
        wp_mkdir_p($upload_path);
        
        // Crear archivo index.php para proteger el directorio
        file_put_contents($upload_path . 'index.php', '<?php // Silence is golden');
    }
    
    // Generar nombre único de archivo
    $filename = 'personalizado_' . $product_id . '_' . time() . '.' . $image_type;
    $file_path = $upload_path . $filename;
    $file_url = $upload_url . $filename;
    
    // Guardar la imagen
    $saved = file_put_contents($file_path, $image_data);
    
    return $saved ? $file_url : false;
}

/**
 * Obtener datos base64 de una personalización
 */
public function get_personalizacion_base64() {
    // Verificar nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'personalizador_nonce')) {
        wp_send_json_error('Error de seguridad.');
    }
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if (!$id) {
        wp_send_json_error('ID no válido.');
    }
    
    // Obtener datos de la base de datos
    if (!class_exists('CuadrosPersonalizables_DB')) {
        wp_send_json_error('Módulo de base de datos no disponible.');
    }
    
    $db = CuadrosPersonalizables_DB::get_instance();
    $personalizacion = $db->get_personalization_by_id($id);
    
    if (!$personalizacion) {
        wp_send_json_error('No se encontró la personalización.');
    }
    
    // Priorizar base64 (image_data) sobre URL
    $image_data = '';
    if (!empty($personalizacion->image_data)) {
        $image_data = $personalizacion->image_data;
    } elseif (!empty($personalizacion->image_url)) {
        // Intentar cargar la imagen y convertirla a base64
        $upload_dir = wp_upload_dir();
        $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $personalizacion->image_url);
        
        if (file_exists($file_path)) {
            $image_data = 'data:image/png;base64,' . base64_encode(file_get_contents($file_path));
        }
    }
    
    if (empty($image_data)) {
        wp_send_json_error('No hay datos de imagen disponibles.');
    }
    
    wp_send_json_success($image_data);
}

    public function reset_personalization() {
        // Verificar nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'personalizador_nonce')) {
            wp_send_json_error('Error de seguridad.');
        }
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if (!$product_id) {
            wp_send_json_error('ID de producto no válido.');
        }
        
        // Verificar que el product_id corresponde a un producto real
        $product = wc_get_product($product_id);
        if (!$product) {
            wp_send_json_error('El producto especificado no existe.');
        }
        
        // Eliminar de la base de datos
        if (!class_exists('CuadrosPersonalizables_DB')) {
            wp_send_json_error('Error del sistema: Módulo de base de datos no disponible.');
        }
        
        $db = CuadrosPersonalizables_DB::get_instance();
        $result = $db->delete_personalization($product_id);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'La personalización ha sido eliminada correctamente.'
            ));
        } else {
            wp_send_json_error('No se encontró ninguna personalización para eliminar.');
        }
    }
}