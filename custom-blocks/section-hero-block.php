<?php
function register_custom_hero_section()
{
    // Verificar si Elementor está cargado
    if (!did_action('elementor/loaded')) {
        return;
    }

    // Verificar si la plantilla ya existe
    $existing_template = get_posts([
        'post_type' => 'elementor_library',
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_elementor_template_type',
                'value' => 'section',
            ],
        ],
        's' => 'Custom Hero Section', // Buscar por título
    ]);

    if (!empty($existing_template)) {
        return; // Si ya existe, no hacer nada
    }

    // Crear contenido JSON para la sección Hero
    $content = json_encode([
        [
            'id' => 'hero_section',
            'elType' => 'section',
            'settings' => [
                'padding' => [
                    'unit' => 'px',
                    'top' => 100,
                    'right' => 20,
                    'bottom' => 100,
                    'left' => 20,
                ],
                'background_background' => 'classic', // Puede ser classic o gradient
                'background_color' => '#1e1e1e', // Color de fondo por defecto
                'background_image' => [ // Imagen de fondo opcional
                    'url' => 'https://via.placeholder.com/1920x1080',
                ],
                'background_size' => 'cover',
                'background_position' => 'center center',
                'content_width' => 'boxed',
                'content_position' => 'middle',
            ],
            'elements' => [
                [
                    'id' => 'hero_column',
                    'elType' => 'column',
                    'settings' => [
                        '_column_size' => 100,
                        'align' => 'center',
                    ],
                    'elements' => [
                        [
                            'id' => 'hero_heading',
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => [
                                'title' => 'Libera tu potencial con Panamá Pacifico',
                                'header_size' => 'h1',
                                'title_color' => '#ffffff',
                                'typography_typography' => 'custom',
                                'typography_font_family' => 'Arial',
                                'typography_font_size' => [
                                    'unit' => 'px',
                                    'size' => 48,
                                ],
                            ],
                        ],
                        [
                            'id' => 'hero_text_editor',
                            'elType' => 'widget',
                            'widgetType' => 'text-editor',
                            'settings' => [
                                'editor' => 'La comunidad es inclusiva y diversa, con residentes que crean vínculos y están en constante transformación. Es una gran familia que se involucra y cuida de sus icónicos espacios como los Bunkers, Trillo Titi, los Greenfingers y la Ciclovía.',
                                'text_color' => '#dddddd',
                                'typography_typography' => 'custom',
                                'typography_font_size' => [
                                    'unit' => 'px',
                                    'size' => 18,
                                ],
                            ],
                        ],
                        [
                            'id' => 'hero_button',
                            'elType' => 'widget',
                            'widgetType' => 'button',
                            'settings' => [
                                'text' => 'Conoce más',
                                'link' => [
                                    'url' => 'https://www.tusitio.com',
                                ],
                                'button_color' => '#ffffff',
                                'background_color' => '#0073e6',
                                'border_radius' => [
                                    'unit' => 'px',
                                    'size' => 5,
                                ],
                                'typography_typography' => 'custom',
                                'typography_font_size' => [
                                    'unit' => 'px',
                                    'size' => 14,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]);

    // Definir los datos de la plantilla
    $template_data = [
        'post_title' => 'Custom Hero Section', // Nombre del Hero
        'post_content' => '', // El contenido se almacena en meta
        'post_status' => 'publish',
        'post_type' => 'elementor_library', // Tipo de contenido para Elementor
        'meta_input' => [
            '_elementor_data' => $content, // Datos de la sección
            '_elementor_edit_mode' => 'builder',
            '_elementor_template_type' => 'section', // Tipo de plantilla: sección
            '_wp_page_template' => 'elementor_canvas',
        ],
    ];

    // Insertar la plantilla en la base de datos
    $template_id = wp_insert_post($template_data);

    // Verificar si la plantilla se creó correctamente
    if (!is_wp_error($template_id)) {
        update_post_meta($template_id, '_elementor_template_type', 'section');
        update_post_meta($template_id, '_elementor_data', $content);
    } else {
        error_log('Error al registrar el Hero Section: ' . $template_id->get_error_message());
    }
}

// Enganchar la función al inicio de Elementor
add_action('elementor/init', 'register_custom_hero_section');
