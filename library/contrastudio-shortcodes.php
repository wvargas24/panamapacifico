<?php

function get_first_category()
{
    $categories = get_the_category();
    if (!empty($categories)) {
        return esc_html($categories[0]->name); // Devuelve la primera categoría
    }
    return 'Sin categoría'; // Mensaje predeterminado si no hay categorías
}
add_shortcode('first_category', 'get_first_category');


function render_filtered_posts_shortcode($atts)
{
    // Atributos del shortcode
    $atts = shortcode_atts(
        array(
            'template_id' => '952', // ID del template guardado en Elementor
        ),
        $atts,
        'filtered_posts'
    );

    ob_start();
    ?>
    <div class="filtered-posts-container elementor-element elementor-element-dca5609 elementor-grid-3 elementor-grid-tablet-2 elementor-grid-mobile-1 elementor-widget elementor-widget-loop-grid">
        <!-- Filtros -->
        <div class="filters">
            <div class="category-filter-wrapper dropdown">
                <button class="elementor-button elemento-size-sm dropdown-toggle icon-filter" type="button" id="category-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Filtrar</button>
                <ul class="dropdown-menu" aria-labelledby="category-dropdown">
                    <li>
                        <a class="dropdown-item" href="#" data-category="">Todas las Categorías</a>
                    </li>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        echo '<li><a class="dropdown-item" href="#" data-category="' . $category->slug . '">' . $category->name . '</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- <input type="text" id="search-filter" placeholder="Buscar..." class="form-control w-50"> -->
            <div class="input-group w-50 ps-5">
                <input class="form-control border rounded-pill" type="text" placeholder="¿Que estas buscando?" id="search-filter">
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary border-0 bg-transparent rounded-pill ms-n3" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>

        <!-- Grid de Posts -->
        <div id="posts-grid" class="elementor-loop-container elementor-grid">
            <?php
            // Renderizar los posts iniciales
            $posts_html = get_filtered_posts_html($atts['template_id']);
            echo $posts_html['posts_html']; // Asegúrate de imprimir solo el HTML aquí
            ?>
        </div>
        <div class="content-loader" style="display: none;">
            <div class="loader loader-pink"></div>
        </div>
        <div class="e-loop__load-more elementor-button-wrapper">
            <?php $nonce = wp_create_nonce('load_more_posts_nonce'); ?>
            <a href="#" class="elementor-button-link elementor-button load-more-btn" role="button" data-nonce="<?php echo $nonce; ?>" data-page="2">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">Ver más</span>
                </span>
                <span class="e-load-more-spinner">
                    <svg aria-hidden="true" class="e-font-icon-svg e-fas-spinner" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path
                              d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z">
                        </path>
                    </svg>
                </span>
            </a>
        </div>
        <input type="hidden" id="template-id" value="<?php echo $atts['template_id']; ?>">
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('filtered_posts', 'render_filtered_posts_shortcode');



function get_filtered_posts_html($template_id, $category = '', $search = '', $paged = 1)
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 9,
        'paged' => $paged,
        'post_status' => 'publish',
    );

    if (!empty($category)) {
        $args['category_name'] = $category;
    }

    if (!empty($search)) {
        $args['s'] = $search;
    }

    $query = new WP_Query($args);
    $output = array(
        'posts_html' => '',
        'has_more' => false,
    );

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output['posts_html'] .= \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id);
        }
        // Verificar si hay más posts
        $output['has_more'] = $query->max_num_pages >= $paged;
    } else {
        $output['posts_html'] = '<p>No se encontraron resultados.</p>';
    }

    wp_reset_postdata();
    return $output;
}

function ajax_filtered_posts()
{
    //$template_id = 952; // ID del template
    $template_id = isset($_POST['template_id']) ? sanitize_text_field($_POST['template_id']) : 952;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $filtered_posts = get_filtered_posts_html($template_id, $category, $search, $paged);
    wp_send_json($filtered_posts);
    wp_die();
}

add_action('wp_ajax_filtered_posts', 'ajax_filtered_posts');
add_action('wp_ajax_nopriv_filtered_posts', 'ajax_filtered_posts');
