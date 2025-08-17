<?php
function sansart_cuadros_relacionados_por_artista($artista_id = null) {
  if (!$artista_id && is_singular('artista')) {
    $artista_id = get_the_ID();
  }

  if (!$artista_id || !is_numeric($artista_id)) {
    return '<p>Artista no válido.</p>';
  }

  ob_start();

  //⛔️ SOLO PARA DEBUG (comenta en producción)

  if (!is_admin()) {
    echo '<pre style="background:#fff; padding:1rem; border:2px solid red; max-width:90vw; overflow:auto;">';
    echo '<strong>DEBUG desde shortcode:</strong><br>';
    echo 'Artista ID: ' . esc_html($artista_id) . '<br><br>';

    $rel_test = new WP_Query([
      'post_type' => 'product',
      'posts_per_page' => 5,
    ]);

    while ($rel_test->have_posts()) {
      $rel_test->the_post();
      echo '<hr><strong>Producto:</strong> ' . get_the_title() . '<br>';
      $acf_val = get_field('artista_relacionado');
      echo 'Campo ACF: ';
      var_dump($acf_val);
    }

    wp_reset_postdata();
    echo '</pre>';
  }

  // Consulta de productos relacionados
  $cuadros = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => -1,
    'meta_query' => [[
      'key' => 'artista_relacionado',
      'value' => '"' . $artista_id . '"',
      'compare' => 'LIKE'
    ]]
  ]);

  if ($cuadros->have_posts()) {
    echo '<div class="cuadros-grid">';
    while ($cuadros->have_posts()) {
      $cuadros->the_post();
      echo '<div class="cuadro">';
      echo '<a href="' . get_permalink() . '">';
      if (has_post_thumbnail()) {
        echo get_the_post_thumbnail(get_the_ID(), 'medium');
      }
      echo '<h3>' . get_the_title() . '</h3>';
      echo '</a>';
      echo '</div>';
    }
    echo '</div>';
  } else {
    echo '<p>No hay cuadros disponibles de este artista.</p>';
  }

  wp_reset_postdata();
  return ob_get_clean();
}

// Shortcode
add_shortcode('cuadros_artista', function($atts) {
  $atts = shortcode_atts([
    'id' => null
  ], $atts);

  return sansart_cuadros_relacionados_por_artista($atts['id']);
});
// AJAX handler para obtener cuadros por artista (paginados y ordenados)
function sansart_ajax_get_cuadros_artista() {
  if (!isset($_POST['artista_id']) || !is_numeric($_POST['artista_id'])) {
    wp_send_json_error(['message' => 'ID de artista inválido']);
  }

  $artista_id = intval($_POST['artista_id']);
  $pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
  $orden = (isset($_POST['orden']) && $_POST['orden'] === 'asc') ? 'ASC' : 'DESC';
  $por_pagina = 9;

  $query = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => $por_pagina,
    'paged' => $pagina,
    'orderby' => 'date',
    'order' => $orden,
    'meta_query' => [[
      'key' => 'artista_relacionado',
      'value' => '"' . $artista_id . '"',
      'compare' => 'LIKE',
    ]],
  ]);

  ob_start();
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      echo '<div class="cuadro-artista" data-fecha="' . get_the_date('c') . '">';
      $link = esc_url(get_permalink());
      $title = esc_html(get_the_title());
      $thumb = get_the_post_thumbnail(get_the_ID(), 'medium');
      echo "<a href=\"$link\">$thumb<h3>$title</h3></a>";
      echo '</div>';
    }
  }
  wp_reset_postdata();

  $html = ob_get_clean();
  wp_send_json_success(['html' => $html]);
}

// Registro de la función AJAX para usuarios logueados y no logueados
add_action('wp_ajax_get_cuadros_artista', 'sansart_ajax_get_cuadros_artista');
add_action('wp_ajax_nopriv_get_cuadros_artista', 'sansart_ajax_get_cuadros_artista');
