<?php

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


add_action('elementor/widgets/register', function () {
    class FormidableForm extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'custom_formidable';
        }

        public function get_title()
        {
            return __('Formidable Form Proyecto', 'astra-child');
        }

        public function get_icon()
        {
            return 'eicon-form-horizontal'; // Icono de Elementor
        }

        public function get_categories()
        {
            return array('general');
        }

        protected function register_controls()
        {
            $this->start_controls_section(
                'section_options',
                array(
                    'label' => __('Options', 'astra-child'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                )
            );

            $this->add_basic_switcher_control('title', __('Show Form Title', 'astra-child'));
            $this->add_basic_switcher_control('description', __('Show Form Description', 'astra-child'));
            $this->add_basic_switcher_control('minimize', __('Minimize HTML', 'astra-child'));

            $this->end_controls_section();
        }

        private function add_basic_switcher_control($key, $title)
        {
            $this->add_control(
                $key,
                array(
                    'label' => $title,
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                )
            );
        }

        private function get_form_options()
        {
            $query = array();
            $where = apply_filters('frm_forms_dropdown', $query, 'form');
            $forms = FrmForm::get_published_forms($where, 999, 'exclude');
            $options = array('' => '');

            foreach ($forms as $form) {
                $form_title = '' === $form->name ? FrmFormsHelper::get_no_title_text() : FrmAppHelper::truncate($form->name, 50);
                $options[$form->id] = esc_html($form_title);
            }

            return $options;
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $form_id = get_field('select_formidable_form'); // Asegúrate de que este campo sea el correcto en ACF
            $title = isset($settings['title']) && 'yes' === $settings['title'];
            $description = isset($settings['description']) && 'yes' === $settings['description'];
            $minimize = isset($settings['minimize']) && 'yes' === $settings['minimize'];

            $form_shortcode = '[formidable id="' . $form_id . '" title="' . $title . '" description="' . $description . '" minimize="' . $minimize . '"]';
            echo do_shortcode($form_shortcode);
        }
    }
    \Elementor\Plugin::instance()->widgets_manager->register(new FormidableForm());

});