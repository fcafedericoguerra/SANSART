<?php
/**
 * shortcodes.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function shortcode_listado_artistas($atts, $content = null) {
    $atts = shortcode_atts(array(
        'cantidad' => -1,
    ), $atts, 'listado_artistas');

    $args = array(
        'post_type'      => 'artistas',
        'posts_per_page' => $atts['cantidad'],
        'orderby'        => 'title',
        'order'          => 'DESC',
    );
    $query = new WP_Query($args);

    $tecnicas = get_terms(array(
        'taxonomy' => 'tecnicas',
        'hide_empty' => false
    ));

    ob_start();
    ?>

    <style>
    .filtros-box {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border-radius: 8px;
        padding: 15px;
        width: 100%;
        margin-bottom: 20px;
    }

    .filtro-select,
    .filtro-input {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 14px;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(4px);
        transition: all 0.25s ease;
        opacity: 0;
        transform: translateY(10px);
        animation: entradaFiltro 0.4s ease forwards;
        min-width: 180px;
        max-width: 100%;
        flex: 0 0 auto;
        white-space: nowrap;
    }

    .filtro-select:nth-of-type(1) { animation-delay: 0.1s; }
    .filtro-select:nth-of-type(2) { animation-delay: 0.2s; }
    .filtro-input { animation-delay: 0.3s; }

    .filtro-input:focus,
    .filtro-select:focus {
        border-color: #0099a8;
        box-shadow: 0 0 3px rgba(0, 153, 168, 0.4);
    }

    @media (max-width: 768px) {
        .filtros-box {
            flex-direction: column;
            align-items: stretch;
        }

        .filtro-select,
        .filtro-input {
            width: 100%;
        }
    }

    @keyframes entradaFiltro {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>

    <!-- FILTROS -->
    <div class="filtros-box">
        <select id="filtro-tecnica" class="filtro-select">
            <option value="">Todas las técnicas</option>
            <?php foreach ($tecnicas as $tec): ?>
                <option value="<?php echo esc_attr(strtolower($tec->name)); ?>">
                    <?php echo esc_html($tec->name); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select id="filtro-fecha" class="filtro-select">
            <option value="desc">Más recientes primero</option>
            <option value="asc">Más antiguos primero</option>
        </select>

        <input type="text" id="filtro-nombre" class="filtro-input" placeholder="Buscar por nombre">
    </div>

    <!-- LISTADO DE ARTISTAS -->
    <div id="listado-artistas">
    <?php
    if ( $query->have_posts() ) :
        while ($query->have_posts() ) : $query->the_post();

            $nombre = get_the_title();
            $fecha = get_the_date('Y-m-d');
            $permalink = get_permalink();

            $tecnica = get_field('tecnica_usada');
            if (empty($tecnica)) {
                $terms = get_the_terms(get_the_ID(), 'tecnicas');
                if (!empty($terms) && !is_wp_error($terms)) {
                    $tecnica = $terms[0]->name;
                } else {
                    $tecnica = '';
                }
            }

            $es_destacado = get_field('destacado');
            ?>
            <div class="tarjeta-artista"
                 data-nombre="<?php echo esc_attr(strtolower($nombre)); ?>"
                 data-tecnica="<?php echo esc_attr(strtolower($tecnica)); ?>"
                 data-fecha="<?php echo esc_attr($fecha); ?>">

                <?php if ($es_destacado): ?>
                    <div style="
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background: gold;
                        color: black;
                        font-size: 12px;
                        font-weight: bold;
                        padding: 4px 6px;
                        border-radius: 4px;
                    ">Destacado ⭐</div>
                <?php endif; ?>

                <?php if (has_post_thumbnail()): ?>
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'medium')); ?>" alt="<?php echo esc_attr($nombre); ?>">
                <?php endif; ?>

                <h2><?php echo esc_html($nombre); ?></h2>

                <div class="contenido-artista">
                    <?php the_content(); ?>
                </div>

                <a href="<?php echo esc_url($permalink); ?>" class="boton-artista">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Ver artista
                </a>
            </div>
        <?php
        endwhile;
        wp_reset_postdata();
    else:
        echo '<p>No hay artistas disponibles.</p>';
    endif;
    ?>
    </div>

    <div style="text-align: center;">
        <button id="btn-cargar-mas" class="btn-cargar-mas">Cargar más</button>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('listado_artistas', 'shortcode_listado_artistas');
