<?php

if (!defined('ABSPATH')) {
    exit;
}

class CuadrosPersonalizables_Ajax {

    public function __construct() {
        add_action('wp_ajax_guardar_mockup', array($this, 'guardar_mockup'));
    }

    public function guardar_mockup() {
        // Verificar permisos
        if (!current_user_can('manage_options')) {
            wp_send_json_error("No tienes permisos suficientes.");
        }

        // Obtener datos enviados por AJAX
        $producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : 0;
        $mockup_url  = isset($_POST['mockup_url']) ? esc_url_raw($_POST['mockup_url']) : '';
        $area_json   = isset($_POST['area_json']) ? sanitize_text_field($_POST['area_json']) : '';

        // Validar campos requeridos
        if (empty($producto_id) || empty($mockup_url)) {
            wp_send_json_error("Faltan datos para guardar el mockup (producto y/o URL).");
        }

        // Guardar la URL del mockup como metadata
        update_post_meta($producto_id, '_mockup_url', $mockup_url);

        // Guardar el área si se envía
        if (!empty($area_json)) {
            update_post_meta($producto_id, '_mockup_area', $area_json);
        }

        // Respuesta de éxito
        wp_send_json_success("Mockup guardado correctamente.");
    }
}

// Instanciar la clase
new CuadrosPersonalizables_Ajax();

