<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Get Custom Post Type Class
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
require_once 'contrastudio-cpt.php';

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Projects
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
$projects = new CPT(
    array(
        'post_type_name' => 'project',
        'singular' => 'Proyecto',
        'plural' => 'Proyectos',
        'slug' => 'proyecto'
    ),
    array(
        'show_in_rest' => true,
        'supports' => array('title', 'thumbnail', 'excerpt', 'custom-fields')
    )
);

add_action('init', 'create_project_amenity_taxonomy', 0);
function create_project_amenity_taxonomy()
{
    $labels = array(
        'name' => _x('Amenidades', 'taxonomy general name'),
        'singular_name' => _x('Amenidad', 'taxonomy singular name'),
        'search_items' => __('Buscar Amenidades'),
        'all_items' => __('Todas las Amenidades'),
        'parent_item' => __('Amenidad Padre'),
        'parent_item_colon' => __('Amenidad Padre:'),
        'edit_item' => __('Editar Amenidad'),
        'update_item' => __('Actualizar Amenidad'),
        'add_new_item' => __('Agregar nueva Amenidad'),
        'new_item_name' => __('Nuevo Nombre de Amenidad'),
        'menu_name' => __('Amenidades'),
    );
    register_taxonomy('project_amenity', array('project'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'meta_box_cb' => false,
        'query_var' => true,
        'rewrite' => array('slug' => 'project_amenity'),
    ));
}

add_action('init', 'create_project_type_taxonomy', 0);
function create_project_type_taxonomy()
{
    $labels = array(
        'name' => _x('Tipos', 'taxonomy general name'),
        'singular_name' => _x('Tipo', 'taxonomy singular name'),
        'search_items' => __('Buscar Tipos'),
        'all_items' => __('Todas las Tipos'),
        'parent_item' => __('Tipo Padre'),
        'parent_item_colon' => __('Tipo Padre:'),
        'edit_item' => __('Editar Tipo'),
        'update_item' => __('Actualizar Tipo'),
        'add_new_item' => __('Agregar nuevo Tipo'),
        'new_item_name' => __('Nuevo Nombre de Tipo'),
        'menu_name' => __('Tipos'),
    );
    register_taxonomy('project_type', array('project'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'project_type'),
    ));
}

add_action('init', 'create_project_status_taxonomy', 0);
function create_project_status_taxonomy()
{
    $labels = array(
        'name' => _x('Estados', 'taxonomy general name'),
        'singular_name' => _x('Estado', 'taxonomy singular name'),
        'search_items' => __('Buscar Estados'),
        'all_items' => __('Todas las Estados'),
        'parent_item' => __('Estado Padre'),
        'parent_item_colon' => __('Estado Padre:'),
        'edit_item' => __('Editar Estado'),
        'update_item' => __('Actualizar Estado'),
        'add_new_item' => __('Agregar nuevo Estado'),
        'new_item_name' => __('Nuevo Nombre de Estado'),
        'menu_name' => __('Estados'),
    );
    register_taxonomy('project_status', array('project'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'project_status'),
    ));
}

$projects->columns(array(
    'cb' => '<input type="checkbox" />',
    'title' => __('Titulo'),
    'project_type' => __('Tipo'),
    'project_status' => __('Estado'),
    'project_amenity' => __('Amenidades'),
    'date' => __('Fecha')
));
$projects->sortable(array(
    'title' => array('title', true),
    'project_type' => array('project_type', true),
    'project_status' => array('project_status', true),
    'project_amenity' => array('project_amenity', true),
    'date' => array('date', true)
));
$projects->menu_icon("dashicons-building");

/**
 * Function to create the default list of amenities.
 */

function add_default_project_amenity_terms()
{
    $taxonomy = 'project_amenity';
    $terms = [
        'Greenway',
        'Salón de fiestas',
        '3 áreas para BBQ',
        'Gimnasio',
        'Piscina para adultos y niños',
        '4 Gazebos',
        'Fitness Circuit',
        'Piscina',
        'Pet Spa',
        'Huerto',
        'Área de Sundeck',
        'Juice Bar',
        'Game Room',
        'Coworking',
        'Sport Lounge',
    ];

    foreach ($terms as $term) {
        if (!term_exists($term, $taxonomy)) {
            wp_insert_term($term, $taxonomy);
        }
    }
}
add_action('init', 'add_default_project_amenity_terms');

/**
 * Function to create the default list of types.
 */

function add_default_project_type_terms()
{
    $taxonomy = 'project_type';
    $parent_terms = [
        'Residencial' => ['Casa', 'Apartamento', 'Villa', 'Condominio'],
        'Comercial' => ['Oficina', 'Local Comercial', 'Tienda'],
        'Industrial' => ['Bodega', 'Fábrica'],
        'Terrenos' => ['Lote', 'Parcela', 'Terreno Urbano'],
    ];

    foreach ($parent_terms as $parent => $children) {
        $parent_term = wp_insert_term($parent, $taxonomy);
        if (!is_wp_error($parent_term) && isset($parent_term['term_id'])) {
            foreach ($children as $child) {
                if (!term_exists($child, $taxonomy)) {
                    wp_insert_term($child, $taxonomy, ['parent' => $parent_term['term_id']]);
                }
            }
        }
    }
}
add_action('init', 'add_default_project_type_terms');

/**
 * Function to create the default list of states.
 */

function add_default_project_status_terms()
{
    $taxonomy = 'project_status';
    $terms = [
        'Planificado',
        'En Construcción',
        'Disponible',
        'En Preventa',
        'Reservado',
        'En Venta',
        'Vendido',
        'Suspendido',
        'Finalizado',
        'En Remodelación',
        'Alquilado',
        'Cancelado',
    ];

    foreach ($terms as $term) {
        if (!term_exists($term, $taxonomy)) {
            wp_insert_term($term, $taxonomy);
        }
    }
}
add_action('init', 'add_default_project_status_terms');
