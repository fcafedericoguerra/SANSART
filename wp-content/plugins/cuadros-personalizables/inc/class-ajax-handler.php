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
        
        // Endpoints para usuarios no logueados
        add_action('wp_ajax_nopriv_save_personalization', array($this, 'save_personalization'));
        add_action('wp_ajax_nopriv_load_personalization', array($this, 'load_personalization'));
        add_action('wp_ajax_nopriv_reset_personalization', array($this, 'reset_personalization'));
    }
    
    /**
     * Guardar personalización vía AJAX
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
    
    // Dentro de la función save_personalization, justo antes de guardar en la base de datos
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
        $id = $db->save_personalization($product_id, $image_data, $image_state);
        
        if ($id) {
            wp_send_json_success(array(
                'id' => $id,
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
            
            wp_send_json_success(array(
                'id' => $personalization->id,
                'image_data' => $personalization->image_data,
                'image_state' => $personalization->image_state,
                'created_at' => $personalization->created_at,
                'updated_at' => $personalization->updated_at
            ));
        } else {
            wp_send_json_error('No se encontró ninguna personalización guardada para este producto.');
        }
    }
    
    /**
     * Resetear personalización vía AJAX
     */
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