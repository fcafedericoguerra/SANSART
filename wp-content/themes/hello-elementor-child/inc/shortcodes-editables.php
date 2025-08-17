<?php
/**
 * Shortcode galería de productos editables (variables)
 */
if (!defined('ABSPATH')) exit;

function shortcode_galeria_editables($atts) {
  ob_start();

  // Obtener atributos disponibles (taxonomías de productos variables)
  $tamanos = get_terms([
    'taxonomy'   => 'pa_tamano',
    'hide_empty' => false
  ]);

  $materiales = get_terms([
    'taxonomy'   => 'pa_material',
    'hide_empty' => false
  ]);
  ?>

  <section id="galeria-editables">

    <!-- Botón toggle filtros (solo móvil) -->
    <button id="toggle-filtros" class="btn-toggle-filtros">
      <span class="icono-filtro">
        <!-- Ícono filtro -->
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="4" y1="21" x2="4" y2="14"/>
          <line x1="4" y1="10" x2="4" y2="3"/>
          <line x1="12" y1="21" x2="12" y2="12"/>
          <line x1="12" y1="8" x2="12" y2="3"/>
          <line x1="20" y1="21" x2="20" y2="16"/>
          <line x1="20" y1="12" x2="20" y2="3"/>
          <line x1="1" y1="14" x2="7" y2="14"/>
          <line x1="9" y1="8" x2="15" y2="8"/>
          <line x1="17" y1="16" x2="23" y2="16"/>
        </svg>
      </span>
      <span class="texto-btn">Mostrar filtros</span>
    </button>

    <div class="galeria-wrapper">
      <!-- Filtros -->
      <aside class="galeria-filtros">
        <button class="cerrar-filtros" aria-label="Cerrar filtros">&times;</button>
        <h3>Filtrar por:</h3>

        <label>Tamaño</label>
        <select id="filtro-tamano">
          <option value="">Todos</option>
          <?php foreach ($tamanos as $term): ?>
            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
          <?php endforeach; ?>
        </select>

        <label>Material</label>
        <select id="filtro-material">
          <option value="">Todos</option>
          <?php foreach ($materiales as $term): ?>
            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
          <?php endforeach; ?>
        </select>

        <label>Ordenar por</label>
        <select id="ordenar">
          <option value="desc">Más recientes</option>
          <option value="asc">Más antiguos</option>
        </select>

        <label>Precio</label>
        <div id="slider-precio" style="margin: 10px 0;"></div>
        <div class="valores-precio">
          <span id="precio-min">$0</span> – <span id="precio-max">$5000</span>
        </div>
        <input type="hidden" id="filtro-precio-min" value="0">
        <input type="hidden" id="filtro-precio-max" value="5000">
      </aside>

      <!-- Productos -->
      <section class="galeria-productos">
        <?php
        $args = array(
          'post_type'      => 'product',
          'posts_per_page' => 9,
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

        $loop = new WP_Query($args);
        if ($loop->have_posts()):
          while ($loop->have_posts()): $loop->the_post();
            global $product;
            ?>
            <div class="cuadro-producto animar-carta animar-entrada">
              <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()): ?>
                  <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>" alt="<?php the_title(); ?>">
                <?php endif; ?>
                <h4><?php the_title(); ?></h4>
                <span class="precio">Desde <?php echo wc_price($product->get_price()); ?></span>
              </a>
            </div>
            <?php
          endwhile;
          wp_reset_postdata();
        else:
          echo "<p>No hay productos disponibles.</p>";
        endif;
        ?>
      </section>
    </div>

    <!-- Botón "Cargar más" -->
    <div class="contenedor-cargar-mas" style="text-align:center; margin-top: 30px;">
      <button id="btn-cargar-mas" class="btn-cargar-mas">Cargar más</button>
    </div>

  </section>
  <?php

  return ob_get_clean();
}

add_shortcode('galeria_editables', 'shortcode_galeria_editables');