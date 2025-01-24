<?php

require_once 'contrastudio-admin.php';
require_once('contrastudio-custom-post-types.php');

if (THEME_ACF) {
    require_once 'contrastudio-acf.php';
}

require_once 'contrastudio-theme-functions.php';
require_once 'contrastudio-theme-setup.php';
require_once 'contrastudio-shortcodes.php';
require_once 'contrastudio-acf-json-registration.php';
require_once 'contrastudio-elementor-json-registration.php';

require_once get_stylesheet_directory() . '/elementor-widgets/custom-media-carousel.php';
require_once get_stylesheet_directory() . '/elementor-widgets/project-amenities.php';
require_once get_stylesheet_directory() . '/elementor-widgets/project-gallery.php';
require_once get_stylesheet_directory() . '/elementor-widgets/project-questions.php';
require_once get_stylesheet_directory() . '/elementor-widgets/project-formidable-form.php';