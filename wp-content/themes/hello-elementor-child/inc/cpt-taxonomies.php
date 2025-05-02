<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============== CPT "artistas" ===============
function cpt_artistas_init() {
    $labels = array(
        'name'               => __('Artistas'),
        'singular_name'      => __('Artista'),
        'add_new'            => __('Añadir Nuevo Artista'),
        'add_new_item'       => __('Añadir Nuevo Artista'),
        'edit_item'          => __('Editar Artista'),
        'new_item'           => __('Nuevo Artista'),
        'view_item'          => __('Ver Artista'),
        'search_items'       => __('Buscar Artistas'),
        'not_found'          => __('No se encontraron artistas'),
        'not_found_in_trash' => __('No hay artistas en la papelera'),
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'artistas'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-art',
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('artistas', $args);
}
add_action('init', 'cpt_artistas_init');

// =============== Taxonomía personalizada "técnica usada" ===============
function registrar_taxonomia_tecnica_usada() {
    $labels = array(
        'name'              => __('Técnicas'),
        'singular_name'     => __('Técnica'),
        'search_items'      => __('Buscar técnicas'),
        'all_items'         => __('Todas las técnicas'),
        'parent_item'       => __('Técnica superior'),
        'parent_item_colon' => __('Técnica superior:'),
        'edit_item'         => __('Editar técnica'),
        'update_item'       => __('Actualizar técnica'),
        'add_new_item'      => __('Añadir nueva técnica'),
        'new_item_name'     => __('Nombre de la nueva técnica'),
        'menu_name'         => __('Técnicas'),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tecnica'),
    );

    register_taxonomy('tecnica_usada', array('product'), $args);
}
add_action('init', 'registrar_taxonomia_tecnica_usada');