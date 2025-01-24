<?php

/**
 * Theme Functions
 * Author: Wuilly Vargas
 * GitHub: wvargas24
 * @package Astra Child
 * //~~~//~~~//~~~//~~~//~~~//~~~//~~~//~~~//-~+
 */

// Defines
define('FL_CHILD_THEME_DIR', get_stylesheet_directory());
define('FL_CHILD_THEME_URL', get_stylesheet_directory_uri());

// theme version
if (SCRIPT_DEBUG === true) {
    define('THEME_VERSION', time());
} else {
    define('THEME_VERSION', time());
    //defined('THEME_VERSION') or define('THEME_VERSION', '1.2.59');
}

// theme directory
define('THEME_DIR', get_stylesheet_directory());
define('THEME_DIR_URI', get_stylesheet_directory_uri());

// theme plugins
define('THEME_PLUGINS_DIR', THEME_DIR . '/includes/plugins');
define('THEME_PLUGINS_DIR_URI', THEME_DIR_URI . '/includes/plugins');

// modules
define('MODULES_DIR', THEME_DIR . '/includes/modules');
define('MODULES_DIR_URI', THEME_DIR_URI . '/includes/modules');

// headers
define('HEADERS_DIR', THEME_DIR . '/includes/headers');
define('HEADERS_DIR_URI', THEME_DIR_URI . '/includes/headers');

// WooCommerce
define('THEME_WOOCOMMERCE', class_exists('WooCommerce'));

// ACF
define('THEME_ACF', class_exists('acf'));

// parent theme directory (for child themes)
define('TEMPLATE_DIR', THEME_DIR);
define('TEMPLATE_DIR_URI', THEME_DIR_URI);

// main theme setup
require_once 'library/contrastudio.php';




// require_once get_stylesheet_directory() . '/custom-blocks/call-to-action.php';
// require_once get_stylesheet_directory() . '/custom-blocks/call-to-action-block.php';
// require_once get_stylesheet_directory() . '/custom-blocks/section-hero.php';


add_filter('acf/load_field/name=select_formidable_form', function ($field) {
    // Obtén todos los formularios de Formidable Forms
    $forms = FrmForm::get_published_forms(); // Método de Formidable Forms para obtener formularios activos

    // Prepara las opciones para el campo
    $field['choices'] = [];
    foreach ($forms as $form) {
        $field['choices'][$form->id] = $form->name; // Usa el ID del formulario como clave y el nombre como etiqueta
    }

    // Establecer el valor devuelto como el ID del formulario
    $field['return_format'] = 'id'; // Esto asegura que ACF devuelva el ID en lugar del nombre

    return $field;
});

