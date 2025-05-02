<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Evitar acceso directo
}

/**
 * Hello Elementor Child Theme functions and definitions
 */

// 1) Requerir archivos que mueven el código del CPT, taxonomía y meta box:
require_once get_stylesheet_directory() . '/inc/cpt-taxonomies.php';
require_once get_stylesheet_directory() . '/inc/meta-boxes.php';

// 2) Cargar shortcodes
require_once get_stylesheet_directory() . '/inc/shortcodes.php';
require_once get_stylesheet_directory() . '/inc/shortcodes-cuadros.php';

// 3) Encolar scripts condicionalmente
function hec_enqueue_scripts_condicionales() {
    wp_enqueue_script('jquery');

    // Listado de artistas
    wp_enqueue_script(
        'hec-listado-artistas-js',
        get_stylesheet_directory_uri() . '/assets/js/listado-artistas.js',
        array('jquery'),
        '1.0',
        true
    );

    // Solo en páginas con [galeria_cuadros]
    if ( is_singular() && has_shortcode(get_post()->post_content, 'galeria_cuadros') ) {

        wp_enqueue_script(
            'galeria-cuadros-js',
            get_stylesheet_directory_uri() . '/assets/js/galeria-cuadros.js',
            array('jquery'),
            time(),
            true
        );

        wp_localize_script('galeria-cuadros-js', 'ajaxurl', admin_url('admin-ajax.php'));

        wp_enqueue_style(
            'nouislider-css',
            'https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.css',
            array(),
            null
        );

        wp_enqueue_script(
            'nouislider-js',
            'https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.js',
            array(),
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hec_enqueue_scripts_condicionales', 20);

// 4) AJAX: cargar más productos desde JS
function ajax_cargar_mas_productos() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 9,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array('editables'),
                'operator' => 'NOT IN'
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            global $product;
            ?>
            <div class="cuadro-producto">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()): ?>
                        <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                    <h4><?php the_title(); ?></h4>
                    <span class="precio">Desde <?php echo wc_price($product->get_price()); ?></span>
                </a>
            </div>
            <?php
        }
        wp_reset_postdata();
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_error('No hay más productos.');
    }

    wp_die();
}
add_action('wp_ajax_cargar_mas_productos', 'ajax_cargar_mas_productos');
add_action('wp_ajax_nopriv_cargar_mas_productos', 'ajax_cargar_mas_productos');

// 5) AJAX: filtrar productos por categoría, técnica y fecha
function ajax_filtrar_productos() {
    $categoria = sanitize_text_field($_POST['categoria']);
    $tecnica   = sanitize_text_field($_POST['tecnica']);
    $orden     = sanitize_text_field($_POST['orden']);
    $precio_min = isset($_POST['precio_min']) ? floatval($_POST['precio_min']) : 0;
    $precio_max = isset($_POST['precio_max']) ? floatval($_POST['precio_max']) : 10000;

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 9,
        'orderby'        => 'date',
        'order'          => $orden === 'asc' ? 'ASC' : 'DESC',
        'tax_query'      => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array('editables'),
                'operator' => 'NOT IN'
            )
        ),
        'meta_query' => array(
            array(
                'key' => '_price',
                'value' => array($precio_min, $precio_max),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            )
        )
    );

    if (!empty($categoria)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $categoria
        );
    }

    if (!empty($tecnica)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'tecnica_usada',
            'field'    => 'slug',
            'terms'    => $tecnica
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            global $product;
            ?>
            <div class="cuadro-producto">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()): ?>
                        <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                    <h4><?php the_title(); ?></h4>
                    <span class="precio">Desde <?php echo wc_price($product->get_price()); ?></span>
                </a>
            </div>
            <?php
        }
        wp_reset_postdata();
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_error('No hay productos que coincidan con los filtros.');
    }

    wp_die();
}
add_action('wp_ajax_filtrar_productos', 'ajax_filtrar_productos');
add_action('wp_ajax_nopriv_filtrar_productos', 'ajax_filtrar_productos');
