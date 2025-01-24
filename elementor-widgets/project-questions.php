<?php

add_action('elementor/widgets/register', function () {
    class Accordion_Widget extends \Elementor\Widget_Base
    {
        public function get_name()
        {
            return 'custom_accordion_widget';
        }

        public function get_title()
        {
            return __('Acordeón Q/A Proyecto', 'astra-child');
        }

        public function get_icon()
        {
            return 'eicon-accordion';
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
                    'label' => __('Contenido', 'plugin-name'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            // Título del acordeón
            $this->add_control(
                'accordion_title',
                [
                    'label' => __('Título del Acordeón', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Resuelve tus dudas', 'astra-child'),
                    'dynamic' => ['active' => true],
                ]
            );

            $this->add_control(
                'data_source',
                [
                    'label' => __('Origen de Datos', 'plugin-name'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'acf' => __('ACF Repeater', 'plugin-name'),
                    ],
                    'default' => 'acf',
                ]
            );

            $this->add_control(
                'acf_key',
                [
                    'label' => __('Clave del Repeater de ACF', 'plugin-name'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'condition' => [
                        'data_source' => 'acf',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            if ($settings['data_source'] === 'acf' && function_exists('have_rows')):
                $acf_key = $settings['acf_key'];
                if (have_rows($acf_key)): ?>
                    <div class="accordion-container">
                        <?php while (have_rows($acf_key)):
                            the_row();
                            $title = get_sub_field('title');
                            $content = get_sub_field('content');
                            ?>
                            <div class="ac">
                                <h4 class="ac-header">
                                    <button type="button" class="ac-trigger"><?php echo esc_html($title); ?></button>
                                </h4>
                                <div class="ac-panel">
                                    <?php echo wp_kses_post($content); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            <?php endif;
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Accordion_Widget());
});
