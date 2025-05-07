<?php
/*
Plugin Name: Cuadros Personalizables
Description: Permite seleccionar un mockup, definir el área editable y asociarlo a un producto de WooCommerce.
Version: 1.2
Author: Federico Guerra
*/

if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo
}

// URL base del plugin  (termina con /)
if ( ! defined( 'CPC_URL' ) ) {
    define( 'CPC_URL', plugin_dir_url( __FILE__ ) );
}
// Versión del plugin: úsala también para cache-buster
if ( ! defined( 'CPC_VER' ) ) {
    define( 'CPC_VER', '1.0.0' );   // cámbiala al lanzar nuevas versiones
}


// Definir constantes para el plugin
define('CPC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CPC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CPC_PLUGIN_VERSION', '1.0.1');
define('CPC_DB_VERSION', '1.0');

// Incluir archivos con las clases del plugin
require_once CPC_PLUGIN_DIR . 'inc/class-db-manager.php';
require_once CPC_PLUGIN_DIR . 'inc/class-admin-interface.php';
require_once CPC_PLUGIN_DIR . 'inc/class-admin-ajax.php';
require_once CPC_PLUGIN_DIR . 'inc/class-ajax-handler.php';
require_once CPC_PLUGIN_DIR . 'inc/class-frontend-personalizador.php';
require_once CPC_PLUGIN_DIR . 'inc/class-personalizador.php';
require_once CPC_PLUGIN_DIR . 'inc/class-diagnostico.php';

/**
 * Verifica y crea la carpeta de personalizados
 * 
 * @return array Estado de la carpeta y posibles errores
 */
function cpc_verificar_carpeta_personalizados() {
    $upload_dir = wp_upload_dir();
    $personalizados_path = $upload_dir['basedir'] . '/personalizados/';
    $personalizados_url = $upload_dir['baseurl'] . '/personalizados/';
    
    $resultado = array(
        'existe' => false,
        'permisos' => false,
        'index' => false,
        'error' => '',
        'path' => $personalizados_path,
        'url' => $personalizados_url
    );
    
    // Verificar si existe
    if (!file_exists($personalizados_path)) {
        // Intentar crear la carpeta
        $created = wp_mkdir_p($personalizados_path);
        if (!$created) {
            $resultado['error'] = 'No se pudo crear la carpeta de personalizados.';
            return $resultado;
        }
    }
    
    $resultado['existe'] = true;
    
    // Verificar permisos
    if (!is_writable($personalizados_path)) {
        // Intentar establecer permisos
        chmod($personalizados_path, 0755);
        if (!is_writable($personalizados_path)) {
            $resultado['error'] = 'La carpeta no tiene permisos de escritura.';
            return $resultado;
        }
    }
    
    $resultado['permisos'] = true;
    
    // Verificar index.php
    if (!file_exists($personalizados_path . 'index.php')) {
        // Crear archivo index.php
        $index_content = '<?php // Silence is golden';
        $index_created = file_put_contents($personalizados_path . 'index.php', $index_content);
        if (!$index_created) {
            $resultado['error'] = 'No se pudo crear el archivo index.php';
            return $resultado;
        }
    }
    
    $resultado['index'] = true;
    
    return $resultado;
}

/**
 * Acción AJAX para verificar la carpeta de personalizados
 */
function cpc_ajax_verificar_carpeta_personalizados() {
    // Verificar nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'personalizador_nonce')) {
        wp_send_json_error('Error de seguridad');
    }
    
    // Verificar y crear carpeta
    $resultado = cpc_verificar_carpeta_personalizados();
    
    if (!empty($resultado['error'])) {
        wp_send_json_error($resultado);
    } else {
        wp_send_json_success($resultado);
    }
}
add_action('wp_ajax_verificar_carpeta_personalizados', 'cpc_ajax_verificar_carpeta_personalizados');
add_action('wp_ajax_nopriv_verificar_carpeta_personalizados', 'cpc_ajax_verificar_carpeta_personalizados');

/**
 * Verifica la carpeta de personalizados durante la activación del plugin y la inicialización
 */
function cpc_verificar_carpeta_personalizados_init() {
    // Verificar carpeta al iniciar
    cpc_verificar_carpeta_personalizados();
}
add_action('init', 'cpc_verificar_carpeta_personalizados_init');

/**
 * Intenta reparar URLs de imágenes que podrían estar mal formadas
 * 
 * @param string $image_url URL de la imagen a verificar
 * @return string URL corregida o la original si no se pudo corregir
 */
function cpc_corregir_url_imagen($image_url) {
    if (empty($image_url)) {
        return $image_url;
    }
    
    // Si es base64, devolver tal cual
    if (strpos($image_url, 'data:image') === 0) {
        return $image_url;
    }
    
    // Comprobar si es una URL relativa
    if (strpos($image_url, 'http') !== 0 && strpos($image_url, '//') !== 0) {
        $upload_dir = wp_upload_dir();
        
        // Si comienza con slash, es una ruta absoluta desde la raíz del sitio
        if (strpos($image_url, '/') === 0) {
            return site_url($image_url);
        }
        
        // Si no, asumimos que es relativa a uploads
        return $upload_dir['baseurl'] . '/' . ltrim($image_url, '/');
    }
    
    return $image_url;
}

/**
 * Añade diagnóstico de carpeta personalizada al sistema de diagnóstico
 * 
 * @param array $diagnostico Diagnóstico actual
 * @return array Diagnóstico actualizado
 */
function cpc_anadir_diagnostico_carpeta($diagnostico) {
    $resultado = cpc_verificar_carpeta_personalizados();
    
    $diagnostico['carpeta_personalizados'] = $resultado;
    
    if (!$resultado['existe'] || !$resultado['permisos'] || !$resultado['index']) {
        $diagnostico['errores'][] = "Problema con la carpeta personalizados: " . (!empty($resultado['error']) ? $resultado['error'] : "Verificar ruta y permisos.");
    }
    
    return $diagnostico;
}
add_filter('cpc_diagnostico_verificar_estructura', 'cpc_anadir_diagnostico_carpeta');

/**
 * Verifica si la carpeta personalizados existe al activar el plugin
 */
function cpc_verificar_carpeta_al_activar() {
    // Verificar carpeta
    $resultado = cpc_verificar_carpeta_personalizados();
    
    // Si hay error, mostrar notificación
    if (!empty($resultado['error'])) {
        update_option('cpc_error_carpeta_personalizados', $resultado['error']);
        update_option('cpc_necesita_diagnostico', true);
    }
}
add_action('activated_plugin', 'cpc_verificar_carpeta_al_activar');

/**
 * Repara la carpeta /personalizados/ cuando se repara el plugin
 */
function cpc_reparar_carpeta_personalizados() {
    cpc_verificar_carpeta_personalizados();
}
add_action('cpc_reparar_plugin', 'cpc_reparar_carpeta_personalizados');

/**
 * Inicializar el plugin
 */
function cpc_iniciar_plugin() {
    // Verificar que WooCommerce esté activo
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'cpc_woocommerce_missing_notice');
        return;
    }
    
    // Instanciar la clase de administración (manejo de mockups en el admin)
    new CuadrosPersonalizables_Admin();

    // Instanciar la clase AJAX para manejar guardado de mockup
    new CuadrosPersonalizables_Ajax();
    
    // Instanciar el nuevo manejador de AJAX
    new CuadrosPersonalizables_Ajax_Handler();

    // Clase del frontend para mostrar el pop-up y manejar la personalización del lado del usuario
    new CuadrosPersonalizables_Frontend();
    
    // Comprobar si hay actualizaciones de base de datos
    cpc_verificar_actualizaciones();
    
    // Verificar carpeta de personalizados
    cpc_verificar_carpeta_personalizados();
}
add_action('plugins_loaded', 'cpc_iniciar_plugin');

/**
 * Notificación si WooCommerce no está instalado
 */
function cpc_woocommerce_missing_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php _e('Cuadros Personalizables requiere que WooCommerce esté instalado y activado.', 'cuadros-personalizables'); ?></p>
    </div>
    <?php
}

/**
 * Verificar si es necesario actualizar la base de datos
 */
function cpc_verificar_actualizaciones() {
    $db_version = get_option('cpc_db_version', '0');
    
    if (version_compare($db_version, CPC_DB_VERSION, '<')) {
        // Crear o actualizar tablas
        if (class_exists('CuadrosPersonalizables_DB')) {
            $db = CuadrosPersonalizables_DB::get_instance();
            $db->create_tables();
        }
        
        // Actualizar versión
        update_option('cpc_db_version', CPC_DB_VERSION);
        
        // Marcar para diagnóstico
        update_option('cpc_necesita_diagnostico', true);
    }
}

/**
 * Registrar scripts y estilos al activar el plugin
 */
function cpc_activar_plugin() {
    // Crear directorios si no existen
    wp_mkdir_p(CPC_PLUGIN_DIR . 'assets/js/');
    wp_mkdir_p(CPC_PLUGIN_DIR . 'assets/css/');
    
    // Verificar que los archivos están en su lugar
    $archivos_necesarios = array(
        'js' => array(
            'personalizador-frontend.js',
            'personalizador-debug.js'
        ),
        'css' => array(
            'personalizador.css'
        )
    );
    
    // Crear archivos JS si no existen
    foreach ($archivos_necesarios['js'] as $archivo) {
        $ruta_completa = CPC_PLUGIN_DIR . 'assets/js/' . $archivo;
        if (!file_exists($ruta_completa)) {
            file_put_contents(
                $ruta_completa,
                "// Archivo creado automáticamente\n// Este archivo debe ser reemplazado con el código actual del personalizador"
            );
        }
    }
    
    // Crear archivos CSS si no existen
    foreach ($archivos_necesarios['css'] as $archivo) {
        $ruta_completa = CPC_PLUGIN_DIR . 'assets/css/' . $archivo;
        if (!file_exists($ruta_completa)) {
            file_put_contents(
                $ruta_completa,
                "/* Archivo creado automáticamente */\n/* Este archivo debe ser reemplazado con los estilos actuales del personalizador */"
            );
        }
    }
    
    // Crear tablas en la base de datos
    if (class_exists('CuadrosPersonalizables_DB')) {
        $db = CuadrosPersonalizables_DB::get_instance();
        $db->create_tables();
    }
    
    // Registrar versión de la base de datos
    update_option('cpc_db_version', CPC_DB_VERSION);
    
    // Marcar que necesita revisión
    update_option('cpc_necesita_diagnostico', true);
    
    // Registrar capacidad para ejecutar diagnóstico
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('manage_cuadros_personalizables');
    }
    
    // Limpiar cualquier transient o caché
    delete_transient('cpc_cache_mockups');
    
    // Verificar carpeta de personalizados
    cpc_verificar_carpeta_personalizados();
}
register_activation_hook(__FILE__, 'cpc_activar_plugin');

/**
 * Limpiar al desactivar
 */
function cpc_desactivar_plugin() {
    // Limpiar cualquier configuración temporal si es necesario
    delete_transient('cpc_cache_mockups');
}
register_deactivation_hook(__FILE__, 'cpc_desactivar_plugin');

/**
 * Desinstalar plugin
 */
function cpc_desinstalar_plugin() {
    // Eliminar tablas si está configurado
    if (get_option('cpc_eliminar_datos_desinstalacion', false)) {
        global $wpdb;
        $tabla = $wpdb->prefix . 'cuadros_personalizados';
        $wpdb->query("DROP TABLE IF EXISTS $tabla");
        
        // Eliminar opciones
        delete_option('cpc_db_version');
        delete_option('cpc_necesita_diagnostico');
        delete_option('cpc_eliminar_datos_desinstalacion');
    }
    
    // Eliminar capacidad
    $role = get_role('administrator');
    if ($role) {
        $role->remove_cap('manage_cuadros_personalizables');
    }
}
register_uninstall_hook(__FILE__, 'cpc_desinstalar_plugin');

/**
 * Mostrar aviso de diagnóstico si es necesario
 */
function cpc_mostrar_aviso_diagnostico() {
    if (get_option('cpc_necesita_diagnostico', false) && current_user_can('manage_options')) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <?php _e('El plugin Cuadros Personalizables podría necesitar diagnóstico. ', 'cuadros-personalizables'); ?>
                <a href="<?php echo admin_url('edit.php?post_type=product&page=cpc-diagnostico'); ?>">
                    <?php _e('Ejecutar diagnóstico', 'cuadros-personalizables'); ?>
                </a>
            </p>
        </div>
        <?php
    }
    
    // Mostrar aviso específico de carpeta si hay error
    $error_carpeta = get_option('cpc_error_carpeta_personalizados', '');
    if (!empty($error_carpeta) && current_user_can('manage_options')) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <?php _e('Cuadros Personalizables: ', 'cuadros-personalizables'); ?> 
                <?php echo esc_html($error_carpeta); ?>
                <a href="<?php echo admin_url('edit.php?post_type=product&page=cpc-diagnostico'); ?>">
                    <?php _e('Ejecutar diagnóstico para intentar reparar', 'cuadros-personalizables'); ?>
                </a>
            </p>
        </div>
        <?php
        // Limpiar aviso después de mostrarlo
        delete_option('cpc_error_carpeta_personalizados');
    }
}
add_action('admin_notices', 'cpc_mostrar_aviso_diagnostico');


/**
 * Actualizar tabla para usar image_url en lugar de image_data
 */
function cpc_actualizar_tabla_personalizacion() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'cuadros_personalizados';
    
    // Verificar si ya existe la columna image_url
    $columna_existe = $wpdb->get_results("SHOW COLUMNS FROM {$tabla} LIKE 'image_url'");
    
    if (empty($columna_existe)) {
        // Añadir nueva columna image_url
        $wpdb->query("ALTER TABLE {$tabla} ADD COLUMN image_url text NOT NULL AFTER session_id");
        
        // Migrar datos si hay registros existentes (esto sería temporal)
        // Este proceso podría ser pesado si hay muchos registros, considera hacerlo por lotes
        $registros = $wpdb->get_results("SELECT id, image_data FROM {$tabla} WHERE image_data != ''");
        
        if (!empty($registros)) {
            foreach ($registros as $registro) {
                if (!empty($registro->image_data) && strpos($registro->image_data, 'data:image') === 0) {
                    // Aquí llamarías a una función para convertir y guardar la imagen
                    // Pero como esto está fuera del contexto de la clase, usaremos un enfoque diferente
                    
                    // Extraer tipo y datos
                    $image_parts = explode(';base64,', $registro->image_data);
                    if (count($image_parts) == 2) {
                        $image_type_aux = explode('image/', $image_parts[0]);
                        $image_type = isset($image_type_aux[1]) ? $image_type_aux[1] : 'png';
                        $image_base64 = $image_parts[1];
                        
                        // Decodificar
                        $image_data = base64_decode($image_base64);
                        
                        // Obtener directorio de uploads
                        $upload_dir = wp_upload_dir();
                        $upload_path = $upload_dir['basedir'] . '/personalizados/';
                        $upload_url = $upload_dir['baseurl'] . '/personalizados/';
                        
                        // Crear directorio si no existe
                        if (!file_exists($upload_path)) {
                            wp_mkdir_p($upload_path);
                            file_put_contents($upload_path . 'index.php', '<?php // Silence is golden');
                        }
                        
                        // Guardar archivo
                        $filename = 'personalizado_migrado_' . $registro->id . '_' . time() . '.' . $image_type;
                        $file_path = $upload_path . $filename;
                        $file_url = $upload_url . $filename;
                        
                        if (file_put_contents($file_path, $image_data)) {
                            // Actualizar registro con la URL
                            $wpdb->update(
                                $tabla,
                                array('image_url' => $file_url),
                                array('id' => $registro->id)
                            );
                        }
                    }
                }
            }
        }
        
        // Opcional: Eliminar la columna image_data después de migrar
        // Esto depende de si quieres mantener compatibilidad con versiones anteriores
        // $wpdb->query("ALTER TABLE {$tabla} DROP COLUMN image_data");
    }
}

// Registrar función para ejecutar durante la activación o actualización
add_action('plugins_loaded', function() {
    $db_version = get_option('cpc_db_version', '0');
    
    // Si la versión es anterior a la actual, actualizar
    if (version_compare($db_version, CPC_DB_VERSION, '<')) {
        cpc_actualizar_tabla_personalizacion();
        
        // Actualizar versión
        update_option('cpc_db_version', CPC_DB_VERSION);
    }
});
/**
 * Verificación de depuración para mostrar mensajes cuando algo no carga correctamente
 */
function cpc_debug_info() {
    if (defined('WP_DEBUG') && WP_DEBUG && is_product()) {
        global $product;
        
        if (!$product) return;
        
        $mockup_url = get_post_meta($product->get_id(), '_mockup_url', true);
        
        if (!empty($mockup_url)) {
            // Preparar datos para JavaScript
            $upload_dir = wp_upload_dir();
            $personalizados_path = $upload_dir['basedir'] . '/personalizados/';
            $personalizados_exists = file_exists($personalizados_path) ? 'existe' : 'no existe';
            
            ?>
            <script>
            console.log('=== DEBUG CUADROS PERSONALIZABLES ===');
            console.log('Versión del plugin:', '<?php echo esc_js(CPC_PLUGIN_VERSION); ?>');
            console.log('Mockup URL:', '<?php echo esc_js($mockup_url); ?>');
            console.log('Plugin URL:', '<?php echo esc_js(CPC_PLUGIN_URL); ?>');
            
            // Verificar si los scripts están cargados
            console.log('Scripts cargados:');
            <?php
            $scripts_a_verificar = array(
                'jquery' => 'jQuery',
                'fabric-js' => 'fabric',
                'personalizador-frontend-js' => 'window.fabricCanvas',
            );
            
            foreach ($scripts_a_verificar as $handle => $check_var) {
                if (wp_script_is($handle, 'enqueued')) {
                    echo "console.log('  - {$handle}: Encolado');";
                } else {
                    echo "console.log('  - {$handle}: No encolado');";
                }
            }
            ?>
            
            // Verificar si el elemento mockupCanvas existe y tiene dimensiones
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('mockupCanvas');
                if (canvas) {
                    console.log('Canvas encontrado con dimensiones:', {
                        width: canvas.width,
                        height: canvas.height
                    });
                } else {
                    console.log('Canvas no encontrado en el DOM');
                }
            });
            
            // Verificar carpeta de personalizados
            console.log('Carpeta de personalizados:', '<?php echo esc_js($personalizados_path); ?> (<?php echo esc_js($personalizados_exists); ?>)');
            
            console.log('=== FIN DEBUG ===');
            </script>
            <?php
        }
    }
}
add_action('wp_footer', 'cpc_debug_info', 999);

/**
 * Agregar enlace de configuración en la lista de plugins
 */
function cpc_agregar_enlaces_accion($links) {
    $links[] = '<a href="' . admin_url('edit.php?post_type=product&page=cpc-diagnostico') . '">' . __('Diagnóstico', 'cuadros-personalizables') . '</a>';
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cpc_agregar_enlaces_accion');