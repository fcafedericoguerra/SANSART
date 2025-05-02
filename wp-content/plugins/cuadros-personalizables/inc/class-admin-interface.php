<?php

if (!defined('ABSPATH')) {
    exit;
}

class CuadrosPersonalizables_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'crear_menu_admin'));
        add_action('admin_enqueue_scripts', array($this, 'cargar_scripts_admin'));
    }

    /**
     * Crear un menú en el panel de administración
     */
    public function crear_menu_admin() {
        add_menu_page(
            'Cuadros Personalizables',
            'Cuadros Personalizables',
            'manage_options',
            'cuadros_personalizables',
            array($this, 'pagina_admin'),
            'dashicons-format-image',
            25
        );
    }

    /**
     * Encolar scripts y estilos en la página de administración
     */
    public function cargar_scripts_admin($hook) {
        // Solo cargar en la página del plugin (evita conflictos con otras partes de WP)
        if ($hook !== 'toplevel_page_cuadros_personalizables') {
            return;
        }

        wp_enqueue_media(); // Habilita la biblioteca de medios de WordPress

        // Fabric.js para manejar el canvas
        wp_enqueue_script(
            'fabric-js',
            'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js',
            array(),
            null,
            true
        );

        // Script principal de la administración
        wp_enqueue_script(
            'admin-personalizador-js',
            plugin_dir_url(__FILE__) . '../assets/js/admin-personalizador.js',
            array('jquery', 'fabric-js'),
            '1.0',
            true
        );

        // Estilos básicos
        wp_enqueue_style(
            'personalizador-css',
            plugin_dir_url(__FILE__) . '../assets/css/personalizador.css'
        );
    }

    /**
     * Mostrar la página de administración
     */
    public function pagina_admin() {
        // Obtener productos de WooCommerce
        $productos = wc_get_products(array('limit' => -1));
        ?>
    <div class="wrap">
        <h1>Gestión de Mockups</h1>

        <label for="mockup-url">Imagen del Mockup</label>
        <input type="text" id="mockup-url" class="regular-text" readonly>
        <button id="seleccionar-mockup" class="button">Seleccionar desde Medios</button>

        <br><br>
        <label for="producto-mockup">Asociar a un Producto</label>
        <select id="producto-mockup">
            <option value="">-- Seleccionar Producto --</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?php echo esc_attr($producto->get_id()); ?>">
                    <?php echo esc_html($producto->get_name()); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <div class="button-group">
            <button id="guardar-mockup" class="button button-primary">Guardar Mockup</button>
            <button id="reiniciar-area" class="button button-secondary" disabled>Reiniciar área editable</button>
        </div>

        <h2 style="margin-top:30px;">Vista Previa del Mockup</h2>
        <div class="canvas-container" style="position: relative;">
            <canvas id="mockupCanvas" width="600" height="400" style="border:1px solid #ccc;"></canvas>
            <div class="mockup-instructions" style="margin-top: 10px; font-style: italic; color: #666;">
                Haz clic y arrastra para definir el área editable, luego usa los puntos de control para ajustarla.
            </div>
        </div>
    </div>
        <?php
    }
}
