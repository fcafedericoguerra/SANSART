<?php
if (!defined('ABSPATH')) exit;

/**
 * Shortcode: [cuadros_artista id="123"]
 * Muestra todos los cuadros (productos) relacionados al artista por ACF.
 */

function sansart_cuadros_relacionados_por_artista($atts) {
    ob_start();

    // Acepta atributo opcional: id
    $atts = shortcode_atts([
        'id' => null,
    ], $atts);

    $artista_id = null;

    // 1. Si viene el ID directamente desde el shortcode
    if (!empty($atts['id']) && is_numeric($atts['id'])) {
        $artista_id = intval($atts['id']);
    }

    // 2. Si no se pasó ID, intentamos obtenerlo del post actual
    if (
        !$artista_id &&
        in_array(get_post_type(), ['artista', 'artistas'])
    ) {
        $artista_id = get_the_ID();
    }

    // 3. Validamos nuevamente
    if (!$artista_id || !is_numeric($artista_id)) {
        echo '<div class="cuadros-artista-error"><p><strong>Artista no válido.</strong></p></div>';
        return ob_get_clean();
    }

    // HTML para filtros y contenedores AJAX
    echo '<div class="cuadros-artista-filtro">';
    echo '<label for="orden_cuadros">Ordenar por:</label>';
    echo '<select id="orden_cuadros">';
    echo '<option value="desc">Más nuevos</option>';
    echo '<option value="asc">Más antiguos</option>';
    echo '</select>';
    echo '</div>';

    echo '<div id="cuadros-artista-grid" class="cuadros-artista-grid" data-artista="' . esc_attr($artista_id) . '" data-pagina="1" data-orden="desc"></div>';

    echo '<div class="cuadros-artista-boton">';
    echo '<button id="ver-mas-cuadros">Ver más</button>';
    echo '</div>';

    return ob_get_clean();
}

// Registrar el shortcode al iniciar
function sansart_register_cuadros_artista_shortcode() {
    add_shortcode('cuadros_artista', 'sansart_cuadros_relacionados_por_artista');
}
add_action('init', 'sansart_register_cuadros_artista_shortcode');