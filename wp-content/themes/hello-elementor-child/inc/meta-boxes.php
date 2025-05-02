<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Meta Box para "Mockup Guardado" (en Products)
function crear_meta_box_mockup() {
    add_meta_box(
        'mockup_data_metabox',
        'Mockup Guardado',
        'mostrar_mockup_data_metabox',
        'product',
        'side',   // o 'normal', 'advanced'
        'default'
    );
}
add_action('add_meta_boxes', 'crear_meta_box_mockup');

function mostrar_mockup_data_metabox($post) {
    $mockup_url  = get_post_meta($post->ID, '_mockup_url', true);
    $mockup_area = get_post_meta($post->ID, '_mockup_area', true);

    if ( ! $mockup_url && ! $mockup_area ) {
        echo '<p>No hay datos de mockup guardados.</p>';
        return;
    }

    echo '<p><strong>URL del Mockup:</strong><br>';
    echo esc_html($mockup_url) . '</p>';

    echo '<p><strong>Área:</strong><br>';
    echo esc_html($mockup_area) . '</p>';
}
