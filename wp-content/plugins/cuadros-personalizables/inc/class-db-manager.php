<?php
/**
 * Clase para gestionar la persistencia en base de datos
 * 
 * @package CuadrosPersonalizables
 */

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

class CuadrosPersonalizables_DB {
    
    /**
     * Instancia única (patrón Singleton)
     */
    private static $instance = null;
    
    /**
     * Nombre de la tabla
     */
    private $table_name;
    
    /**
     * Constructor privado (patrón Singleton)
     */
    private function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'cuadros_personalizados';
    }
    
    /**
     * Obtener la instancia única
     */
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new CuadrosPersonalizables_DB();
        }
        return self::$instance;
    }
    
    /**
     * Crear tablas en la base de datos
     */
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            product_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            session_id varchar(255) NOT NULL,
            image_data longtext NOT NULL,
            image_state longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Guardar personalización
     * 
     * @param int $product_id ID del producto
     * @param string $image_data Datos de la imagen (Base64)
     * @param string $image_state Estado de la imagen (JSON)
     * @return int ID de la personalización
     */
    /**
 * Guardar personalización vía AJAX
 */
public function save_personalization($product_id, $image_data, $image_state) {
    global $wpdb;
    
    $user_id = get_current_user_id();
    // Asegurarse de que WC()->session está disponible
    $session_id = '';
    if (function_exists('WC') && WC()->session) {
        $session_id = WC()->session->get_customer_id();
    } else {
        // Fallback si WC()->session no está disponible
        if (!isset($_COOKIE['cpc_session_id'])) {
            $session_id = md5(uniqid('cpc_', true));
            setcookie('cpc_session_id', $session_id, time() + 30 * DAY_IN_SECONDS, '/');
        } else {
            $session_id = $_COOKIE['cpc_session_id'];
        }
    }
    
    // Comprobar si ya existe
    $existing_id = $this->get_personalization_id($product_id);
    
    if ($existing_id) {
        // Actualizar
        $wpdb->update(
            $this->table_name,
            array(
                'image_data' => $image_data,
                'image_state' => $image_state,
                'updated_at' => current_time('mysql')
            ),
            array('id' => $existing_id)
        );
        return $existing_id;
    } else {
        // Insertar nuevo
        $wpdb->insert(
            $this->table_name,
            array(
                'product_id' => $product_id,
                'user_id' => $user_id,
                'session_id' => $session_id,
                'image_data' => $image_data,
                'image_state' => $image_state,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            )
        );
        return $wpdb->insert_id;
    }
}
    
    /**
     * Obtener personalización por producto
     * 
     * @param int $product_id ID del producto
     * @return object|null Datos de personalización o null
     */
    public function get_personalization($product_id) {
        global $wpdb;
        
        $user_id = get_current_user_id();
        $session_id = '';
        
        // Obtener session_id
        if (function_exists('WC') && isset(WC()->session)) {
            $session_id = WC()->session->get_customer_id();
        } elseif (isset($_COOKIE['cpc_session_id'])) {
            $session_id = $_COOKIE['cpc_session_id'];
        }
        
        // Si no hay user_id ni session_id, no podemos buscar
        if (!$user_id && !$session_id) {
            return null;
        }
        
        // Preparar condición para WHERE
        $where_condition = "product_id = %d AND (";
        $where_params = array($product_id);
        
        if ($user_id) {
            $where_condition .= "user_id = %d";
            $where_params[] = $user_id;
        }
        
        if ($session_id) {
            if ($user_id) {
                $where_condition .= " OR ";
            }
            $where_condition .= "session_id = %s";
            $where_params[] = $session_id;
        }
        
        $where_condition .= ")";
        
        // Obtener resultado
        $query = $wpdb->prepare(
            "SELECT * FROM $this->table_name WHERE $where_condition ORDER BY updated_at DESC LIMIT 1",
            $where_params
        );
        
        $result = $wpdb->get_row($query);
        
        return $result;
    }
    
    /**
     * Obtener personalización por ID
     * 
     * @param int $id ID de la personalización
     * @return object|null Datos de personalización o null
     */
    public function get_personalization_by_id($id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $this->table_name WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Obtener ID de personalización por producto
     * 
     * @param int $product_id ID del producto
     * @return int|null ID de la personalización o null
     */
    public function get_personalization_id($product_id) {
        global $wpdb;
        
        $user_id = get_current_user_id();
        $session_id = '';
        
        // Obtener session_id
        if (function_exists('WC') && isset(WC()->session)) {
            $session_id = WC()->session->get_customer_id();
        } elseif (isset($_COOKIE['cpc_session_id'])) {
            $session_id = $_COOKIE['cpc_session_id'];
        }
        
        // Si no hay user_id ni session_id, no podemos buscar
        if (!$user_id && !$session_id) {
            return null;
        }
        
        // Preparar condición para WHERE
        $where_condition = "product_id = %d AND (";
        $where_params = array($product_id);
        
        if ($user_id) {
            $where_condition .= "user_id = %d";
            $where_params[] = $user_id;
        }
        
        if ($session_id) {
            if ($user_id) {
                $where_condition .= " OR ";
            }
            $where_condition .= "session_id = %s";
            $where_params[] = $session_id;
        }
        
        $where_condition .= ")";
        
        // Obtener resultado
        $query = $wpdb->prepare(
            "SELECT id FROM $this->table_name WHERE $where_condition ORDER BY updated_at DESC LIMIT 1",
            $where_params
        );
        
        return $wpdb->get_var($query);
    }
    
    /**
     * Eliminar personalización
     * 
     * @param int $product_id ID del producto
     * @return bool True si se eliminó, false si no se encontró
     */
    public function delete_personalization($product_id) {
        global $wpdb;
        
        $existing_id = $this->get_personalization_id($product_id);
        
        if ($existing_id) {
            $wpdb->delete(
                $this->table_name,
                array('id' => $existing_id)
            );
            return true;
        }
        
        return false;
    }
    /**
 * Actualiza los metadatos de una personalización existente
 * 
 * @param int $id ID de la personalización
 * @param array $metadata Metadatos a actualizar
 * @return bool Éxito o fracaso de la actualización
 */
public function update_personalization_metadata($id, $metadata) {
    global $wpdb;
    
    if (!$id || empty($metadata)) {
        return false;
    }
    
    // Convertir metadatos a valores SQL seguros
    $update_data = array();
    
    if (isset($metadata['loaded_at'])) {
        $update_data['loaded_at'] = $metadata['loaded_at'];
    }
    
    if (isset($metadata['views'])) {
        $update_data['views'] = intval($metadata['views']);
    }
    
    if (isset($metadata['status'])) {
        $update_data['status'] = sanitize_text_field($metadata['status']);
    }
    
    // Si no hay datos para actualizar, salir
    if (empty($update_data)) {
        return false;
    }
    
    // Comprobar si existe el registro
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $this->table_name WHERE id = %d",
        $id
    ));
    
    if (!$exists) {
        return false;
    }
    
    // Actualizar el registro existente
    $result = $wpdb->update(
        $this->table_name,
        $update_data,
        array('id' => $id)
    );
    
    return ($result !== false);
}
}