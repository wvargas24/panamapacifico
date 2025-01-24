<?php

function register_custom_elementor_section()
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
        's' => 'Call To Action Section', // Buscar por título
    ]);

    if (!empty($existing_template)) {
        return; // Si ya existe, no hacer nada
    }

    // Crear contenido HTML para la sección con estilos por defecto
    $content = json_encode([
        [
            'id' => 'custom_section',
            'elType' => 'section',
            'settings' => [
                'padding' => [
                    'unit' => 'px',
                    'top' => 50,
                    'right' => 20,
                    'bottom' => 50,
                    'left' => 20,
                ],
                'background_background' => 'classic',
                'background_color' => '#f4f4f4',
            ],
            'elements' => [
                [
                    'id' => 'custom_column',
                    'elType' => 'column',
                    'settings' => [
                        '_column_size' => 100,
                        'align' => 'center',
                    ],
                    'elements' => [
                        [
                            'id' => 'custom_heading',
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => [
                                'title' => '¡Bienvenido a mi sitio!',
                                'header_size' => 'h2',
                                'title_color' => '#333333',
                                'typography_typography' => 'custom',
                                'typography_font_family' => 'Arial',
                                'typography_font_size' => [
                                    'unit' => 'px',
                                    'size' => 32,
                                ],
                            ],
                        ],
                        [
                            'id' => 'custom_text_editor',
                            'elType' => 'widget',
                            'widgetType' => 'text-editor',
                            'settings' => [
                                'editor' => 'Este es un bloque de contenido editable.',
                                'text_color' => '#555555',
                                'typography_typography' => 'custom',
                                'typography_font_size' => [
                                    'unit' => 'px',
                                    'size' => 16,
                                ],
                            ],
                        ],
                        [
                            'id' => 'custom_button',
                            'elType' => 'widget',
                            'widgetType' => 'button',
                            'settings' => [
                                'text' => 'Leer más',
                                'link' => [
                                    'url' => 'https://www.misitio.com',
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
        'post_title' => 'Call To Action Section', // Nombre de la sección
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
        error_log('Error al registrar la sección: ' . $template_id->get_error_message());
    }
}

// Enganchar la función al inicio de Elementor
add_action('elementor/init', 'register_custom_elementor_section');