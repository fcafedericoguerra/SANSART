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
require_once get_stylesheet_directory() . '/inc/shortcode-cuadros-artista.php';
require_once get_stylesheet_directory() . '/inc/shortcodes-editables.php';

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

    // Solo en páginas con [galeria_editables]
    if ( is_singular() && has_shortcode(get_post()->post_content, 'galeria_editables') ) {

        wp_enqueue_script(
            'galeria-editables-js',
            get_stylesheet_directory_uri() . '/assets/js/galeria-editables.js',
            array('jquery'),
            time(),
            true
        );

        wp_localize_script('galeria-editables-js', 'ajaxurl', admin_url('admin-ajax.php'));

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

// 5.1) AJAX: filtrar productos editables
function ajax_filtrar_editables() {
    $tamano     = sanitize_text_field($_POST['tamano']);
    $material   = sanitize_text_field($_POST['material']);
    $orden      = sanitize_text_field($_POST['orden']);
    $precio_min = isset($_POST['precio_min']) ? floatval($_POST['precio_min']) : 0;
    $precio_max = isset($_POST['precio_max']) ? floatval($_POST['precio_max']) : 10000;

    $tax_query = array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => array('editables'),
            'operator' => 'IN'
        )
    );

    if (!empty($tamano)) {
        $tax_query[] = array(
            'taxonomy' => 'pa_tamano',
            'field'    => 'slug',
            'terms'    => $tamano
        );
    }

    if (!empty($material)) {
        $tax_query[] = array(
            'taxonomy' => 'pa_material',
            'field'    => 'slug',
            'terms'    => $material
        );
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 9,
        'orderby'        => 'date',
        'order'          => $orden === 'asc' ? 'ASC' : 'DESC',
        'tax_query'      => $tax_query,
        'meta_query'     => array(
            array(
                'key'     => '_price',
                'value'   => array($precio_min, $precio_max),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC'
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
        wp_send_json_error('No hay productos que coincidan con los filtros.');
    }

    wp_die();
}
add_action('wp_ajax_filtrar_editables', 'ajax_filtrar_editables');
add_action('wp_ajax_nopriv_filtrar_editables', 'ajax_filtrar_editables');

// 5.2) AJAX: cargar más productos editables
function ajax_cargar_mas_editables() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 9,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array('editables'),
                'operator' => 'IN'
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
add_action('wp_ajax_cargar_mas_editables', 'ajax_cargar_mas_editables');
add_action('wp_ajax_nopriv_cargar_mas_editables', 'ajax_cargar_mas_editables');

// 6) Encolar script JS global
function hec_enqueue_global_scripts() {
    wp_enqueue_script(
        'hec-global',
        get_stylesheet_directory_uri() . '/assets/js/global.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'hec_enqueue_global_scripts', 20);

// 7) Encolar estilos globales del tema hijo
function hec_enqueue_styles() {
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array(),
        '1.0.0'
    );
}

add_shortcode('mostrar_carrito_sansart', 'shortcode_mostrar_carrito_sansart');
function shortcode_mostrar_carrito_sansart() {
	if ( ! is_user_logged_in() ) wc_load_cart(); // para sesiones sin login

	if ( WC()->cart->is_empty() ) {
		return '<p>Tu carrito está vacío.</p>';
	}

	ob_start();
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product = $cart_item['data'];

		// 👇 Si existe imagen personalizada de FPD, úsala (soporta base64)
		if ( isset($cart_item['fpd_product_thumbnail']) ) {
			$src = $cart_item['fpd_product_thumbnail'];

			// Verificamos si es base64 o una URL normal
			if ( strpos($src, 'data:image') === 0 ) {
				$thumbnail = '<img src="' . $src . '" alt="Vista previa personalizada" style="max-width: 100px; height: auto;">';
			} else {
				$thumbnail = '<img src="' . esc_url($src) . '" alt="Vista previa personalizada" style="max-width: 100px; height: auto;">';
			}
		} else {
			$thumbnail = $product->get_image( 'thumbnail' );
		}

		echo '<div class="cart-item" style="display: flex; gap: 1rem; margin-bottom: 1rem;">';
		echo '<div class="cart-thumb">' . $thumbnail . '</div>';
		echo '<div class="cart-info">';
		echo '<p style="margin: 0 0 4px;">' . esc_html($product->get_name()) . '</p>';
		echo '<p style="margin: 0 0 4px;">Cantidad: ' . esc_html($cart_item['quantity']) . '</p>';
		echo '<p style="margin: 0;">Precio: ' . wc_price($product->get_price()) . '</p>';
		echo '</div>';
		echo '</div>';
	}
	return ob_get_clean();
}
add_filter('woocommerce_add_cart_item_data', 'sansart_agregar_thumbnail_fpd_al_carrito', 10, 3);
function sansart_agregar_thumbnail_fpd_al_carrito($cart_item_data, $product_id, $variation_id) {
    // Verificamos si FPD ya generó una imagen
    if (isset($_POST['fpd_product_thumbnail'])) {
        $cart_item_data['fpd_product_thumbnail'] = sanitize_text_field($_POST['fpd_product_thumbnail']);
    }

    return $cart_item_data;
}
/// AJAX: Refrescar HTML del carrito sidebar [mostrar_carrito_sansart] + contador
add_action('wp_ajax_sa_refresh_cart_html', 'sa_refresh_cart_html');
add_action('wp_ajax_nopriv_sa_refresh_cart_html', 'sa_refresh_cart_html');
function sa_refresh_cart_html() {
    $html = do_shortcode('[mostrar_carrito_sansart]');

    // Calcular el contador
    $count = 0;
    if ( WC()->cart && !WC()->cart->is_empty() ) {
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $count += $cart_item['quantity'];
        }
    }

    wp_send_json_success([
        'html' => $html,
        'count' => $count,
    ]);
    wp_die();
}

add_action('wp_ajax_sa_ajax_search_products', 'sa_ajax_search_products');
add_action('wp_ajax_nopriv_sa_ajax_search_products', 'sa_ajax_search_products');
function sa_ajax_search_products() {
    $query = sanitize_text_field($_GET['s'] ?? '');
    $result = [];
    if ($query && strlen($query) > 1) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 10,
            's' => $query,
            'post_status' => 'publish',
        );
        $products = get_posts($args);
        foreach ($products as $prod) {
            $result[] = [
                'title' => get_the_title($prod->ID),
                'url'   => get_permalink($prod->ID),
                'img'   => get_the_post_thumbnail_url($prod->ID, 'thumbnail') ?: wc_placeholder_img_src()
            ];
        }
    }
    wp_send_json_success($result);
}

function hec_enqueue_cuadros_artista_css() {
    wp_enqueue_style(
        'cuadros-artista-style',
        get_stylesheet_directory_uri() . '/assets/css/cuadros-artista.css',
        array(),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'hec_enqueue_cuadros_artista_css');

function sansart_enqueue_cuadros_artista_scripts() {
  wp_enqueue_script(
    'cuadros-artista-js',
    get_stylesheet_directory_uri() . '/assets/js/cuadros-artista.js',
    array('jquery'),
    '1.0',
    true
  );

  wp_localize_script('cuadros-artista-js', 'sansartAjax', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
  ));
}
add_action('wp_enqueue_scripts', 'sansart_enqueue_cuadros_artista_scripts');

add_action('wp_enqueue_scripts', 'hec_enqueue_styles', 5);


// AJAX: Obtener cuadros por artista
add_action('wp_ajax_sansart_get_cuadros_artista', 'sansart_get_cuadros_artista');
add_action('wp_ajax_nopriv_sansart_get_cuadros_artista', 'sansart_get_cuadros_artista');

function sansart_get_cuadros_artista() {
    $artista_id = isset($_POST['artista_id']) ? intval($_POST['artista_id']) : 0;
    $pagina     = isset($_POST['pagina'])     ? max(1, intval($_POST['pagina'])) : 1;
    $orden      = (isset($_POST['orden']) && strtolower($_POST['orden']) === 'asc') ? 'ASC' : 'DESC';

    if (!$artista_id) {
        wp_send_json_error('ID de artista no válido.');
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 9,
        'paged'          => $pagina,
        'orderby'        => 'date',
        'order'          => $orden,
        'meta_query'     => array(
            array(
                'key'     => 'artista_relacionado',
                'value'   => '"' . $artista_id . '"', // ACF relación serializada
                'compare' => 'LIKE',
            ),
        ),
    );

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $link       = esc_url(get_permalink());
            $title      = esc_html(get_the_title());
            $thumb_url  = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $thumb_html = $thumb_url ? '<img src="' . esc_url($thumb_url) . '" alt="' . esc_attr($title) . '">' : '';

            echo '<div class="cuadro-artista" data-fecha="' . esc_attr(get_the_date('c')) . '">';
            echo '<a href="' . $link . '">';
            echo $thumb_html;
            echo '<h3>' . $title . '</h3>';
            echo '</a>';
            echo '</div>';
        }
    }
    wp_reset_postdata();

    $html = ob_get_clean();
    $html = preg_replace('/[\r\n\t]+/', '', $html); // limpiar saltos/espacios que puedan interferir
    $html = trim($html);

    wp_send_json_success(array('html' => $html));
}