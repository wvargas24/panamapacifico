<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('elementor/widgets/register', function () {
    class Hero_Section_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'hero_section_widget';
        }

        public function get_title()
        {
            return __('Hero Section', 'astra-child');
        }

        public function get_icon()
        {
            return 'eicon-hero';
        }

        public function get_categories()
        {
            return ['general'];
        }

        protected function _register_controls()
        {
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Contenido', 'astra-child'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            // Título
            $this->add_control(
                'hero_title',
                [
                    'label' => __('Título', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Libera tu potencial con Panamá Pacifico', 'astra-child'),
                ]
            );

            // Párrafo
            $this->add_control(
                'hero_paragraph',
                [
                    'label' => __('Párrafo', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => __('La comunidad es inclusiva y diversa...', 'astra-child'),
                ]
            );

            // URL del botón
            $this->add_control(
                'hero_button_url',
                [
                    'label' => __('URL del botón', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'default' => [
                        'url' => '#',
                    ],
                ]
            );

            // Label del botón
            $this->add_control(
                'hero_button_label',
                [
                    'label' => __('Label del botón', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Conoce más', 'astra-child'),
                ]
            );

            // Imagen del botón
            $this->add_control(
                'hero_button_image',
                [
                    'label' => __('Imagen del botón', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                ]
            );

            // Control para tipo de fondo (color o imagen)
            $this->add_control(
                'background_type',
                [
                    'label' => __('Tipo de fondo', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'color' => __('Color', 'astra-child'),
                        'image' => __('Imagen', 'astra-child'),
                    ],
                    'default' => 'color',
                ]
            );

            // Color de fondo
            $this->add_control(
                'background_color',
                [
                    'label' => __('Color de fondo', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-color: {{VALUE}};',
                    ],
                    'default' => '#ffffff',
                    'condition' => [
                        'background_type' => 'color',
                    ],
                ]
            );

            // Imagen de fondo
            $this->add_control(
                'background_image',
                [
                    'label' => __('Imagen de fondo', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'condition' => [
                        'background_type' => 'image',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            // Preparar el estilo de fondo
            $background_style = '';

            if ('color' === $settings['background_type']) {
                // Verificar si 'background_color' está definida y no es vacía
                if (isset($settings['background_color']) && !empty($settings['background_color'])) {
                    $background_style = 'background-color: ' . esc_attr($settings['background_color']) . ';';
                }
            } elseif ('image' === $settings['background_type'] && !empty($settings['background_image']['url'])) {
                $background_style = 'background-image: url(' . esc_url($settings['background_image']['url']) . ');';
            }

            ?>
            <section id="hero_section" class="section_100 hero" style="<?php echo esc_attr($background_style); ?>">
                <figure class="container_vertical gap_136">
                    <h1 class="h1" data-sal="zoom-in" data-sal-duration="1500" data-sal-delay="500" data-sal-easing="ease-in-out"><?php echo esc_html($settings['hero_title']); ?></h1>
                    <div class="_40 right">
                        <p><?php echo esc_html($settings['hero_paragraph']); ?></p>
                        <a id="boton" href="<?php echo esc_url($settings['hero_button_url']['url']); ?>" class="boton texto w-inline-block">
                            <?php if (!empty($settings['hero_button_image']['url'])): ?>
                                <img src="<?php echo esc_url($settings['hero_button_image']['url']); ?>" loading="lazy" alt="" class="arrow_boton">
                            <?php endif; ?>
                            <div><?php echo esc_html($settings['hero_button_label']); ?></div>
                        </a>
                    </div>
                </figure>
            </section>
            <?php
        }

    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Hero_Section_Widget());
});
