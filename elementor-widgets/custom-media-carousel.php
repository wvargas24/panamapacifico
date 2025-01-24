<?php

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


add_action('elementor/widgets/register', function () {
    class Custom_Media_Carousel_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'custom_media_carousel';
        }

        public function get_title()
        {
            return __('Media Carousel Dinámico', 'astra-child');
        }

        public function get_icon()
        {
            return 'eicon-slider-album'; // Icono de Elementor
        }

        public function get_categories()
        {
            return ['general']; // Categoría del widget
        }

        protected function register_controls()
        {
            $this->start_controls_section(
                'carousel_settings',
                [
                    'label' => __('Configuraciones del Carrusel', 'astra-child'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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

            // Obtener los datos de ACF para el campo Gallery
            $slides = get_field('project_logos') ?? [];

            if (!$slides) {
                echo '<p>' . __('No hay imágenes disponibles.', 'astra-child') . '</p>';
                return;
            }

            // Preparar clases para Swiper Slider
            $default_settings = [
                'container_class' => '',
                'video_play_icon' => true,
            ];

            $settings = array_merge($default_settings, $settings);
            $slides_count = count($slides);
            $swiper_class = 'custom-slick-slider';

            $slides_to_show = $settings['slides_to_show'];
            $slides_to_scroll = $settings['slides_to_scroll'];
            $autoplay = $settings['autoplay'] === 'yes' ? 'true' : 'false';
            $autoplay_speed = !empty($settings['autoplay_speed']) ? $settings['autoplay_speed'] : 3000;
            $space_between = !empty($settings['space_between']) ? $settings['space_between'] : 20;
            $loop = $settings['loop'] === 'yes' ? 'true' : 'false';

            ?>
            <div class="<?php echo esc_attr($settings['container_class']); ?> <?php echo esc_attr($swiper_class); ?>" role="region" aria-roledescription="carousel">
                <div class="custom-slider" data-slides-per-view="<?php echo esc_attr($slides_to_show); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-loop="<?php echo esc_attr($loop); ?>"
                     data-slides-per-group="<?php echo esc_attr($slides_to_scroll); ?>" data-space-between="<?php echo esc_attr($space_between); ?>" data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>" role="region"
                     aria-roledescription="carousel">
                    <?php foreach ($slides as $slide): ?>
                        <div class="item-slide" role="group" aria-roledescription="slide">
                            <div class="elementor-carousel-image" role="img" style="background-image: url('<?php echo esc_url($slide['url']); ?>')"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        }


        public function _content_template()
        {
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Custom_Media_Carousel_Widget());

});