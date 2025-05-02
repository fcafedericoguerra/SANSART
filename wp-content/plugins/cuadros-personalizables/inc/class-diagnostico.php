<?php
/**
 * Clase para diagnóstico y solución de problemas
 * 
 * @package CuadrosPersonalizables
 */

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

class CuadrosPersonalizables_Diagnostico {
    
    /**
     * Verifica la estructura del plugin
     * 
     * @return array Información de diagnóstico
     */
    public static function verificar_estructura() {
        global $wpdb;
        $tabla = $wpdb->prefix . 'cuadros_personalizados';
        
        $diagnostico = array(
            'tabla_existe' => false,
            'archivos_existentes' => array(),
            'rutas' => array(),
            'errores' => array(),
            'advertencias' => array(),
            'info_sistema' => array()
        );
        
        // Verificar tabla
        $tabla_existe = $wpdb->get_var("SHOW TABLES LIKE '$tabla'");
        $diagnostico['tabla_existe'] = !empty($tabla_existe);
        
        if (!$diagnostico['tabla_existe']) {
            $diagnostico['errores'][] = "La tabla $tabla no existe en la base de datos.";
        }
        
        // Verificar constantes del plugin
        if (!defined('CPC_PLUGIN_DIR') || !defined('CPC_PLUGIN_URL')) {
            $diagnostico['errores'][] = "Las constantes del plugin no están definidas correctamente.";
        }
        
        // Verificar archivos
        $archivos_requeridos = array(
            'js' => defined('CPC_PLUGIN_DIR') ? CPC_PLUGIN_DIR . 'assets/js/personalizador-frontend.js' : '',
            'css' => defined('CPC_PLUGIN_DIR') ? CPC_PLUGIN_DIR . 'assets/css/personalizador.css' : '',
            'class_db' => defined('CPC_PLUGIN_DIR') ? CPC_PLUGIN_DIR . 'inc/class-db-manager.php' : '',
            'class_ajax' => defined('CPC_PLUGIN_DIR') ? CPC_PLUGIN_DIR . 'inc/class-ajax-handler.php' : '',
            'class_frontend' => defined('CPC_PLUGIN_DIR') ? CPC_PLUGIN_DIR . 'inc/class-frontend-personalizador.php' : ''
        );
        
        foreach ($archivos_requeridos as $key => $archivo) {
            if (!empty($archivo)) {
                $diagnostico['archivos_existentes'][$key] = file_exists($archivo);
                if (!$diagnostico['archivos_existentes'][$key]) {
                    $diagnostico['errores'][] = "No se encontró el archivo: $archivo";
                }
            } else {
                $diagnostico['errores'][] = "No se pudo verificar la existencia del archivo $key por problema con las constantes del plugin.";
            }
        }
        
        // Verificar rutas
        $diagnostico['rutas'] = array(
            'plugin_dir' => defined('CPC_PLUGIN_DIR') ? CPC_PLUGIN_DIR : 'No definido',
            'plugin_url' => defined('CPC_PLUGIN_URL') ? CPC_PLUGIN_URL : 'No definido',
            'upload_dir' => wp_upload_dir()
        );
        
        // Verificar WooCommerce
        $diagnostico['info_sistema']['woocommerce_activo'] = class_exists('WooCommerce');
        if (!$diagnostico['info_sistema']['woocommerce_activo']) {
            $diagnostico['errores'][] = "WooCommerce no está activo. El plugin requiere WooCommerce para funcionar correctamente.";
        }
        
        // Verificar versión de PHP
        $diagnostico['info_sistema']['php_version'] = phpversion();
        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            $diagnostico['advertencias'][] = "Tu versión de PHP es antigua. Recomendamos PHP 7.0 o superior para un óptimo funcionamiento.";
        }
        
        // Verificar soporte de MySQL
        if (function_exists('mysqli_get_client_info')) {
            $diagnostico['info_sistema']['mysql_version'] = mysqli_get_client_info();
        } else {
            $diagnostico['info_sistema']['mysql_version'] = 'No detectado';
            $diagnostico['advertencias'][] = "No se pudo detectar la versión de MySQL. Asegúrate de tener MySQL 5.6 o superior.";
        }
        
        // Verificar permisos de escritura
        if (defined('CPC_PLUGIN_DIR')) {
            $diagnostico['info_sistema']['permisos_assets'] = is_writable(CPC_PLUGIN_DIR . 'assets');
            if (!$diagnostico['info_sistema']['permisos_assets']) {
                $diagnostico['advertencias'][] = "El directorio 'assets' no tiene permisos de escritura. Esto puede causar problemas al crear archivos.";
            }
        }
        
        return $diagnostico;
    }
    
    /**
     * Muestra la página de diagnóstico en el admin
     */
    public static function mostrar_diagnostico() {
        $diagnostico = self::verificar_estructura();
        
        echo '<div class="wrap">';
        echo '<h1>Diagnóstico de Cuadros Personalizables</h1>';
        
        // Mostrar errores primero si existen
        if (!empty($diagnostico['errores'])) {
            echo '<div class="notice notice-error">';
            echo '<h2>Errores críticos detectados</h2>';
            echo '<ul>';
            foreach ($diagnostico['errores'] as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        
        // Mostrar advertencias
        if (!empty($diagnostico['advertencias'])) {
            echo '<div class="notice notice-warning">';
            echo '<h2>Advertencias</h2>';
            echo '<ul>';
            foreach ($diagnostico['advertencias'] as $warning) {
                echo '<li>' . esc_html($warning) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        
        echo '<h2>Estado del sistema</h2>';
        echo '<table class="widefat">';
        echo '<thead><tr><th>Componente</th><th>Estado</th></tr></thead>';
        echo '<tbody>';
        
        // Tabla
        echo '<tr>';
        echo '<td>Base de datos</td>';
        echo '<td>' . ($diagnostico['tabla_existe'] ? '<span style="color:green">✓</span> OK' : '<span style="color:red">✗</span> Error') . '</td>';
        echo '</tr>';
        
        // Archivos
        foreach ($diagnostico['archivos_existentes'] as $key => $existe) {
            echo '<tr>';
            echo '<td>Archivo: ' . esc_html($key) . '</td>';
            echo '<td>' . ($existe ? '<span style="color:green">✓</span> OK' : '<span style="color:red">✗</span> No encontrado') . '</td>';
            echo '</tr>';
        }
        
        // WooCommerce
        echo '<tr>';
        echo '<td>WooCommerce</td>';
        echo '<td>' . (isset($diagnostico['info_sistema']['woocommerce_activo']) && $diagnostico['info_sistema']['woocommerce_activo'] ? '<span style="color:green">✓</span> Activo' : '<span style="color:red">✗</span> No activo') . '</td>';
        echo '</tr>';
        
        // PHP Version
        echo '<tr>';
        echo '<td>Versión de PHP</td>';
        echo '<td>' . (isset($diagnostico['info_sistema']['php_version']) ? esc_html($diagnostico['info_sistema']['php_version']) : 'No detectado') . '</td>';
        echo '</tr>';
        
        // MySQL Version
        echo '<tr>';
        echo '<td>Versión de MySQL</td>';
        echo '<td>' . (isset($diagnostico['info_sistema']['mysql_version']) ? esc_html($diagnostico['info_sistema']['mysql_version']) : 'No detectado') . '</td>';
        echo '</tr>';
        
        echo '</tbody></table>';
        
        if (!empty($diagnostico['errores']) || !empty($diagnostico['advertencias'])) {
            echo '<h2>Acciones recomendadas</h2>';
            echo '<p>Para resolver estos problemas, puede intentar:</p>';
            echo '<ul>';
            echo '<li>Desactivar y volver a activar el plugin para recrear las tablas y archivos.</li>';
            echo '<li>Verificar los permisos de escritura en la carpeta del plugin.</li>';
            echo '<li>Comprobar que el servidor cumple los requisitos mínimos (PHP 7.0+, MySQL 5.6+).</li>';
            echo '</ul>';
            
            echo '<form method="post">';
            wp_nonce_field('reparar_plugin_nonce', 'reparar_nonce');
            echo '<input type="hidden" name="reparar_plugin" value="1">';
            echo '<button type="submit" class="button button-primary">Intentar reparación automática</button>';
            echo '</form>';
        } else {
            echo '<p style="color:green">No se detectaron problemas. El plugin parece estar funcionando correctamente.</p>';
        }
        
        echo '<h2>Información del sistema</h2>';
        echo '<pre>';
        print_r($diagnostico['rutas']);
        echo '</pre>';
        
        echo '</div>';
        
        // Marcar como revisado
        update_option('cpc_necesita_diagnostico', false);
    }
    
    /**
     * Reparar el plugin
     * 
     * @return bool Éxito de la reparación
     */
    public static function reparar_plugin() {
        // Recrear tabla
        if (class_exists('CuadrosPersonalizables_DB')) {
            $db = CuadrosPersonalizables_DB::get_instance();
            $db->create_tables();
        } else {
            return false;
        }
        
        // Recrear archivos necesarios
        self::recrear_archivos_necesarios();
        
        return true;
    }
    
    /**
     * Recrear archivos necesarios
     */
    private static function recrear_archivos_necesarios() {
        // Asegurarse de que existen los directorios
        if (defined('CPC_PLUGIN_DIR')) {
            wp_mkdir_p(CPC_PLUGIN_DIR . 'assets/js/');
            wp_mkdir_p(CPC_PLUGIN_DIR . 'assets/css/');
            
            // Recrear archivos JS si no existen
            $archivos_js = array(
                'personalizador-frontend.js',
                'personalizador-debug.js'
            );
            
            foreach ($archivos_js as $archivo) {
                $ruta_completa = CPC_PLUGIN_DIR . 'assets/js/' . $archivo;
                if (!file_exists($ruta_completa)) {
                    file_put_contents(
                        $ruta_completa,
                        "// Archivo creado automáticamente\n// Este archivo debe ser reemplazado con el código actual del personalizador"
                    );
                }
            }
            
            // Recrear archivos CSS si no existen
            $archivos_css = array('personalizador.css');
            
            foreach ($archivos_css as $archivo) {
                $ruta_completa = CPC_PLUGIN_DIR . 'assets/css/' . $archivo;
                if (!file_exists($ruta_completa)) {
                    file_put_contents(
                        $ruta_completa,
                        "/* Archivo creado automáticamente */\n/* Este archivo debe ser reemplazado con los estilos actuales del personalizador */"
                    );
                }
            }
        }
    }
    
    /**
     * Registrar menú de administración
     */
    public static function admin_menu() {
        add_submenu_page(
            'edit.php?post_type=product',
            'Diagnóstico de Cuadros Personalizables',
            'Diagnóstico Cuadros',
            'manage_options',
            'cpc-diagnostico',
            array('CuadrosPersonalizables_Diagnostico', 'mostrar_diagnostico')
        );
    }
    
    /**
     * Inicializar la clase
     */
    public static function init() {
        add_action('admin_menu', array('CuadrosPersonalizables_Diagnostico', 'admin_menu'));
        
        // Manejar reparación
        if (isset($_POST['reparar_plugin']) && isset($_POST['reparar_nonce']) && wp_verify_nonce($_POST['reparar_nonce'], 'reparar_plugin_nonce')) {
            $resultado = self::reparar_plugin();
            add_action('admin_notices', function() use ($resultado) {
                if ($resultado) {
                    echo '<div class="notice notice-success"><p>Reparación automática completada. Verifique nuevamente el diagnóstico.</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>Error al intentar la reparación automática. Por favor, contacte al soporte técnico.</p></div>';
                }
            });
        }
    }
}

// Inicializar diagnóstico
CuadrosPersonalizables_Diagnostico::init();