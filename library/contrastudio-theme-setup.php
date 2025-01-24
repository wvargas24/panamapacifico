<?php

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * After Theme Setup
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_action('after_setup_theme', 'contrastudio_setup', 16);
function contrastudio_setup()
{
    add_action('wp_enqueue_scripts', 'astra_force_remove_style', 99);
    // launching operation cleanup
    add_action('init', 'contrastudio_head_cleanup');
    // remove WP version from RSS
    add_filter('the_generator', 'contrastudio_rss_version');
    // remove pesky injected css for recent comments widget
    add_filter('wp_head', 'contrastudio_remove_wp_widget_recent_comments_style', 1);
    // clean up comment styles in the head
    add_action('wp_head', 'contrastudio_remove_recent_comments_style', 1);
    // clean up gallery output in wp
    add_filter('gallery_style', 'contrastudio_gallery_style');

    add_action('init', 'disable_emojis');

    if (!is_admin()) {
        // comment reply script for threaded comments
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }

    add_action('widgets_init', 'unregister_default_widgets', 11);

    add_filter('embed_defaults', 'contrastudio_new_embed_size');

    add_action('init', 'astra_child_register_custom_fonts');

    // Add CSS & JS
    add_action('wp_enqueue_scripts', 'contrastudio_css_js', 999);

    // check user - ajax
    add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
    add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');

    add_filter('frm_scroll_offset', 'frm_scroll_offset');

    // launching this stuff after theme setup
    contrastudio_theme_support();
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Theme Cleanup
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function astra_force_remove_style()
{
    wp_dequeue_style('astra-theme-css');
    wp_dequeue_style('astra-addon-css');
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Head Cleanup
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_head_cleanup()
{
    // EditURI/RSD link
    remove_action('wp_head', 'rsd_link');
    // windows live writer
    remove_action('wp_head', 'wlwmanifest_link');
    // index link
    remove_action('wp_head', 'index_rel_link');
    // previous link
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    // start link
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    // links for adjacent posts
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    // WP version
    remove_action('wp_head', 'wp_generator');
    // feed links
    remove_action('wp_head', 'feed_links', 2);
    // comments feed
    remove_action('wp_head', 'feed_links_extra', 3);
    // prev and next links
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    // Remove the REST API meta data link from header (and everywhere else)
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    // Remove Shortlinks Meta Data
    remove_action('wp_head', 'wp_shortlink_wp_head');

    //remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

    // remove fixed admin bar on from the site view
    //add_filter('show_admin_bar', '__return_false');

    add_filter('admin_bar_menu', 'replace_howdy', 25);

    add_action('admin_head', 'admin_seperators');

    add_filter('disable_wpseo_json_ld_search', '__return_true');

    add_filter('wpseo_enhanced_slack_data', 'contrastudio_yoast_remove_username_metatag');

    add_filter('excerpt_length', 'new_excerpt_length', 999);
    add_filter('excerpt_more', 'contrastudio_excerpt_more');

    add_filter('xmlrpc_enabled', '__return_false');

    // fix modernizr 'document.body' issue
    add_filter('body_class', 'browser_body_class');

    // Item Cleanup
    if (!empty($GLOBALS['wp_embed'])) {
        add_filter('styled_content', array($GLOBALS['wp_embed'], 'run_shortcode'), 8);
        add_filter('styled_content', array($GLOBALS['wp_embed'], 'autoembed'), 8);
    }

    if (!is_super_admin()) {
        add_filter('show_admin_bar', '__return_false');
    }

    add_filter('styled_content', 'capital_P_dangit', 11);
    //add_filter('styled_content', 'wptexturize');
    add_filter('styled_content', 'convert_smilies');
    add_filter('styled_content', 'convert_chars');
    add_filter('styled_content', 'wpautop');
    add_filter('styled_content', 'shortcode_unautop');
    add_filter('styled_content', 'do_shortcode', 11);

    add_filter('the_content', 'remove_empty_p', 20, 1);
    add_filter('styled_content', 'remove_empty_p', 20, 1);

    add_filter('the_content', 'filter_ptags_on_images');
    add_filter('styled_content', 'filter_ptags_on_images');

    add_filter('tiny_mce_before_init', 'uncoverwp_tiny_mce_fix');

    remove_filter('the_content', 'wptexturize');
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Custom Upload Mimes
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes($existing_mimes = array())
{
    $existing_mimes['svg'] = 'mime/type';
    $existing_mimes['dwg'] = 'application/octet-stream';

    return $existing_mimes;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Remove the username tag from the Twitter card necessary for Slack
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_yoast_remove_username_metatag($tag_arr)
{
    if (isset($tag_arr['Written by'])) {
        unset($tag_arr['Written by']);
    }

    return $tag_arr;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Custom excerpt length (word count) and 'Read More' link
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function new_excerpt_length($length)
{
    return 30;
}

function contrastudio_excerpt_more($more)
{
    global $post;
    return '...  ';
}

function excerpt($limit)
{
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . '...';
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
    return $excerpt;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Remove wrapping paragraph tags on images
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function filter_ptags_on_images($content)
{
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Remove default image sizes
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function prefix_remove_default_images($sizes)
{
    //unset( $sizes['small']); // 150px
    //unset( $sizes['medium']); // 300px
    //unset( $sizes['large']); // 1024px
    //unset( $sizes['medium_large']); // 768px

    return $sizes;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Set default dimensions for embedded elements
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_new_embed_size()
{
    return array('width' => 1000, 'height' => 600);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Tiny MCE tweaks
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function uncoverwp_tiny_mce_fix($init)
{
    // html elements being stripped
    $init['extended_valid_elements'] = 'div[*], article[*], span[*], i[*]';

    // don't remove line breaks
    $init['remove_linebreaks'] = false;

    // convert newline characters to BR
    //$init['convert_newlines_to_brs'] = true;

    // don't remove redundant BR
    //$init['remove_redundant_brs'] = false;

    // pass back to wordpress
    return $init;
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Enqueue Theme Fonts
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

function astra_child_register_custom_fonts()
{
    // Registrar las fuentes personalizadas
    add_filter('elementor/fonts/groups', function ($font_groups) {
        $font_groups['custom'] = 'Custom Fonts'; // Crear un grupo personalizado
        return $font_groups;
    });

    add_filter('elementor/fonts/additional_fonts', function ($fonts) {
        $fonts['Neue Montreal'] = 'custom'; // Registrar la fuente en el grupo 'custom'
        $fonts['Plexes'] = 'custom';
        return $fonts;
    });
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Enqueue Theme Styles and Scripts
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_css_js()
{
    /* ~ CSS ASSETS ~~~~~~~~~~~~~~~~~~ */

    // CSS - Google Material icons font
    wp_register_style('google-material-icons', 'https://fonts.googleapis.com/css2?family=Material+Icons', array(), '4.0.0');
    wp_enqueue_style('google-material-icons');
    wp_register_style('google-material-icons-outline', 'https://fonts.googleapis.com/css2?family=Material+Icons+Outlined', array(), '4.0.0');
    wp_enqueue_style('google-material-icons-outline');

    // CSS Theme Parent
    // wp_enqueue_style('astra-style', get_template_directory_uri() . '/style.css'); // Estilos del padre

    // CSS - Theme Child
    wp_enqueue_style('contrastudio-styles', get_stylesheet_directory_uri() . '/dist/css/style.css', array(), THEME_VERSION);

    // wp_enqueue_style('contrastudio-styles');


    // JS - Theme Child
    wp_enqueue_script('contrastudio-js', get_stylesheet_directory_uri() . '/dist/js/scripts.min.js', array('jquery'), THEME_VERSION, true);
    wp_script_add_data('contrastudio-js', 'type', 'module');
    wp_localize_script('contrastudio-js', 'contrastudioAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_events_nonce')
    ));
}



/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Adding WP 3+ Functions & Theme Support
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_theme_support()
{
    // wp thumbnails
    add_theme_support('post-thumbnails');

    add_filter('intermediate_image_sizes_advanced', 'prefix_remove_default_images');

    // image sizes
    add_image_size('largest', 2000, 2000, array('center', 'center'));
    add_image_size('near-full', 2000);
    add_image_size('post-thumb', 637, 356, array('center', 'top'));
    add_image_size('phone-image', 500);
    add_image_size('tablet-image', 800);
    add_image_size('logo-image', 250);

    // wp custom background (thx to @bransonwerner for update)
    add_theme_support(
        'custom-background',
        array(
            'default-image' => '',  // background image default
            'default-color' => '', // background color default (dont add the #)
            'wp-head-callback' => '_custom_background_cb',
            'admin-head-callback' => '',
            'admin-preview-callback' => ''
        )
    );

    // rss thingy
    add_theme_support('automatic-feed-links');

    // adding post format support
    add_theme_support(
        'post-formats',
        array(
            'aside',             // title less blurb
            'gallery',           // gallery of images
            'link',              // quick link to other site
            'image',             // an image
            'quote',             // a quick quote
            'status',            // a Facebook like status update
            'video',             // video
            'audio',             // audio
            'chat'               // chat transcript
        )
    );

    // wp menus
    add_theme_support('menus');

    $theme_domain = 'contrastudio';
    $theme_menu_locations = 'nav_menu_locations';
    $core_menus = ['Main Menu', 'Mobile Menu'];

    foreach ($core_menus as $menu) {
        $menu_name = $menu;
        $menu_exists = wp_get_nav_menu_object($menu_name);
        $menu_class = cleanString($menu_name);

        register_nav_menus(
            array(
                $menu_class => __($menu_name, $theme_domain)
            )
        );

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
            $locations = get_theme_mod($theme_menu_locations);
            $locations[$menu_class] = $menu_id;
            set_theme_mod($theme_menu_locations, $locations);
        }
    }
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Scroll Offset for Form Autoscroll to top
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function frm_scroll_offset()
{
    return 150;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Event Calendar Pro tweaks
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function tribe_modify_related_posts_args($args)
{
    $venue_id = tribe_get_venue_id();

    if ($venue_id) {
        unset($args['tax_query']);
        $args['meta_query'] = [
            'relation' => 'AND',
            [
                'key' => '_EventVenueID',
                'value' => $venue_id,
                'compare' => '=',
            ]
        ];
    }

    return $args;
}
add_filter('tribe_related_posts_args', 'tribe_modify_related_posts_args');


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Search tweaks
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_action('template_redirect', 'redirect_post_type_single');
function redirect_post_type_single()
{
    if (is_singular('install-resources')) {
        wp_redirect(get_page_link(318), 301);	// redirect to For Installers page
    } else if (is_singular('additional-resources')) {
        wp_redirect(get_page_link(624), 301);	// redirect to Additional Resources page
    } else {
        return;
    }

    exit;
}
