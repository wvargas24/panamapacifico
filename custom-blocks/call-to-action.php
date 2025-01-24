<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('elementor/widgets/register', function () {
    class Elementor_Call_To_Action_Block extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'call_to_action_block';
        }

        public function get_title()
        {
            return __('Call to Action Block', 'astra-child');
        }

        public function get_icon()
        {
            return 'eicon-call-to-action';
        }

        public function get_categories()
        {
            return ['general'];
        }

        protected function _register_controls()
        {
            // Section Settings
            $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Content', 'astra-child'),
                ]
            );

            $this->add_control(
                'title',
                [
                    'label' => __('Title', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Your Call to Action Title', 'astra-child'),
                ]
            );

            $this->add_control(
                'subtitle',
                [
                    'label' => __('Subtitle', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => __('Your subtitle goes here.', 'astra-child'),
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __('Button Text', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Click Here', 'astra-child'),
                ]
            );

            $this->add_control(
                'button_link',
                [
                    'label' => __('Button Link', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => __('https://your-link.com', 'astra-child'),
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            echo '<div class="call-to-action-block">';
            echo '<h2>' . esc_html($settings['title']) . '</h2>';
            echo '<p>' . esc_html($settings['subtitle']) . '</p>';
            echo '<a href="' . esc_url($settings['button_link']['url']) . '" class="cta-button">' . esc_html($settings['button_text']) . '</a>';
            echo '</div>';
        }

    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Elementor_Call_To_Action_Block());
});
