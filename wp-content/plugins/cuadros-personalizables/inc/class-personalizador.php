<?php

class CuadrosPersonalizables {

    public function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'cargar_scripts_personalizador'));
        add_filter('woocommerce_add_cart_item_data', array($this, 'agregar_datos_personalizados_al_carrito'), 10, 2);
        add_filter('woocommerce_get_item_data', array($this, 'mostrar_datos_en_carrito'), 10, 2);
        add_action('woocommerce_add_order_item_meta', array($this, 'guardar_datos_en_orden'), 10, 2);
    }

    public function cargar_scripts_personalizador() {
        if (is_product()) {
            wp_enqueue_script('fabric-js', 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.2.4/fabric.min.js', array(), null, true);
            wp_enqueue_script('personalizador-js', plugin_dir_url(__FILE__) . '../assets/js/personalizador.js', array('jquery', 'fabric-js'), '1.0', true);
            wp_enqueue_style('personalizador-css', plugin_dir_url(__FILE__) . '../assets/css/personalizador.css');
        }
    }

    public function agregar_datos_personalizados_al_carrito($cart_item_data, $product_id) {
        if (!empty($_POST['img_personalizada'])) {
            $cart_item_data['img_personalizada'] = sanitize_text_field($_POST['img_personalizada']);
        }
        return $cart_item_data;
    }

    public function mostrar_datos_en_carrito($item_data, $cart_item) {
        if (isset($cart_item['img_personalizada'])) {
            $item_data[] = array(
                'name'  => 'Diseño Personalizado',
                'value' => '<img src="' . esc_url($cart_item['img_personalizada']) . '" style="max-width:120px;"/>'
            );
        }
        return $item_data;
    }

    public function guardar_datos_en_orden($item_id, $values) {
        if (isset($values['img_personalizada'])) {
            wc_add_order_item_meta($item_id, 'Diseño Personalizado', $values['img_personalizada']);
        }
    }
}
