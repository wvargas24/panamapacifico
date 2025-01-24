<?php

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


add_action('elementor/widgets/register', function () {
    class Project_Gallery_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'project_gallery_widget';
        }

        public function get_title()
        {
            return __('Galería del Proyecto', 'astra-child');
        }

        public function get_icon()
        {
            return 'eicon-gallery-grid'; // Icono del widget
        }

        public function get_categories()
        {
            return ['general']; // Categoría del widget
        }

        protected function _register_controls()
        {
            // Controles para personalizar el widget
            $this->start_controls_section(
                'gallery_section',
                [
                    'label' => __('Configuración de Galería', 'astra-child'),
                ]
            );


            $this->add_control(
                'title',
                [
                    'label' => __('Título', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Amenidades del Proyecto', 'astra-child'),
                    'dynamic' => [
                        'active' => true,
                        'tag' => 'project_gallery_title', // Esto es solo un ejemplo de una etiqueta dinámica
                    ],
                ]
            );

            $this->add_control(
                'show_button',
                [
                    'label' => __('Mostrar Boton', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'astra-child'),
                    'label_off' => __('No', 'astra-child'),
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'button_label',
                [
                    'label' => __('Label de boton', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Tour virtual', 'astra-child'),
                    'dynamic' => [
                        'active' => true,
                        'tag' => 'project_gallery_button_label', // Esto es solo un ejemplo de una etiqueta dinámica
                    ],
                    'condition' => [
                        'show_button' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'button_link',
                [
                    'label' => esc_html__('Link', 'textdomain'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'options' => ['url', 'is_external', 'nofollow'],
                    'default' => [
                        'url' => '',
                        'is_external' => true,
                        'nofollow' => true,
                        // 'custom_attributes' => '',
                    ],
                    'label_block' => true,
                    'dynamic' => [
                        'active' => true,
                        'tag' => 'project_gallery_button_link', // Esto es solo un ejemplo de una etiqueta dinámica
                    ],
                    'condition' => [
                        'show_button' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_icon',
                [
                    'label' => __('Mostrar Icono', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'astra-child'),
                    'label_off' => __('No', 'astra-child'),
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'icon',
                [
                    'label' => esc_html__('Icon', 'textdomain'),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-circle',
                        'library' => 'fa-solid',
                    ],
                    'recommended' => [
                        'fa-solid' => [
                            'circle',
                            'dot-circle',
                            'square-full',
                        ],
                        'fa-regular' => [
                            'circle',
                            'dot-circle',
                            'square-full',
                        ],
                    ],
                    'condition' => [
                        'show_icon' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'loop',
                [
                    'label' => __('Loop', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'astra-child'),
                    'label_off' => __('No', 'astra-child'),
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'slides_to_show',
                [
                    'label' => __('Slides a Mostrar', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 3,
                    'min' => 1,
                    'max' => 10,
                ]
            );

            $this->add_control(
                'slides_to_scroll',
                [
                    'label' => __('Elementos por desplazamiento', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 1,
                    'min' => 1,
                    'step' => 1,
                ]
            );

            $this->add_control(
                'show_arrows',
                [
                    'label' => __('Mostrar Flechas', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'astra-child'),
                    'label_off' => __('No', 'astra-child'),
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_dots',
                [
                    'label' => __('Mostrar Puntos', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'astra-child'),
                    'label_off' => __('No', 'astra-child'),
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'autoplay',
                [
                    'label' => __('Autoplay', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'astra-child'),
                    'label_off' => __('No', 'astra-child'),
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'autoplay_speed',
                [
                    'label' => __('Velocidad de Autoplay (ms)', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 3000, // Velocidad por defecto en milisegundos
                    'min' => 1000, // Mínimo 1 segundo
                    'step' => 500, // Incremento de 500 ms
                    'condition' => [
                        'autoplay' => 'yes', // Solo muestra este control si autoplay está activado
                    ],
                ]
            );

            $this->add_control(
                'space_between',
                [
                    'label' => __('Espacio entre items (px)', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 20, // Espacio por defecto
                    'min' => 0, // Sin margen
                    'step' => 5, // Incremento de 5 píxeles
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $title = $settings['title'];
            $show_icon = $settings['show_icon'] === 'yes';

            $project_id = get_the_ID();
            $gallery_images = get_field('project_gallery', $project_id);

            $slides_to_show = $settings['slides_to_show'];
            $slides_to_scroll = $settings['slides_to_scroll'];
            $autoplay = $settings['autoplay'] === 'yes' ? 'true' : 'false';
            $autoplay_speed = !empty($settings['autoplay_speed']) ? $settings['autoplay_speed'] : 3000;
            $space_between = !empty($settings['space_between']) ? $settings['space_between'] : 20;
            $loop = $settings['loop'] === 'yes' ? 'true' : 'false';
            $show_button = $settings['show_button'] === 'yes';
            $button_label = $settings['button_label'];

            if (!empty($gallery_images) && !is_wp_error($gallery_images)) {
                ?>
                <div class="project-gallery-widget">
                    <div class="elementor-element e-con-boxed e-flex e-con e-parent">
                        <div class="e-con-inner">
                            <div class="elementor-element e-con-full e-flex e-con e-child">
                                <div class="elementor-element elementor-widget elementor-widget-heading" data-id="d9944cd" data-element_type="widget" data-widget_type="heading.default">
                                    <?php if ($show_icon): ?>
                                        <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                                    <?php endif; ?>
                                    <?php if (!empty($title)): ?>
                                        <h3 class="elementor-heading-title elementor-size-default"><?php echo esc_html($title); ?></h3>
                                    <?php endif; ?>
                                </div>
                                <?php if ($show_button): ?>
                                    <div class="elementor-element elementor-element-206c11f elementor-widget elementor-widget-button" data-id="206c11f" data-element_type="widget" data-widget_type="button.default">
                                        <a class="elementor-button elementor-button-link elementor-size-sm" href="<?php echo esc_url($settings['button_link']['url']); ?>" role="button">
                                            <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-text"><?php echo $button_label; ?></span>
                                            </span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element e-con-full e-flex e-con e-parent e-lazyloaded" data-id="5094f95" data-element_type="container" id="gallery-slick-slider">
                        <div class="elementor-element e-con-full e-flex e-con e-child">
                            <div class="project-gallery-slider" data-slides-per-view="<?php echo esc_attr($slides_to_show); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-loop="<?php echo esc_attr($loop); ?>"
                                 data-slides-per-group="<?php echo esc_attr($slides_to_scroll); ?>" data-space-between="<?php echo esc_attr($space_between); ?>" data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>"
                                 role="region" aria-roledescription="carousel">
                                <?php foreach ($gallery_images as $image): ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo '<p>' . __('No hay amenidades disponibles.', 'astra-child') . '</p>';
            }
        }


    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Project_Gallery_Widget());

});