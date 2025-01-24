<?php

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


add_action('elementor/widgets/register', function () {
    class Project_Amenities_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'project_amenities_widget';
        }

        public function get_title()
        {
            return __('Lista de Amenidades del Proyecto', 'astra-child');
        }

        public function get_icon()
        {
            return 'eicon-post-list'; // Icono del widget
        }

        public function get_categories()
        {
            return ['general']; // Categoría del widget
        }

        protected function _register_controls()
        {
            // Controles para personalizar el widget
            $this->start_controls_section(
                'amenities_section',
                [
                    'label' => __('Configuración de Amenidades', 'astra-child'),
                ]
            );

            $this->add_control(
                'tagline',
                [
                    'label' => __('Tagline', 'astra-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Este es el tagline del proyecto', 'astra-child'),
                    'dynamic' => [
                        'active' => true,
                        'tag' => 'acf-field_name',  // Cambiar a tu nombre de campo ACF, por ejemplo: 'field_7891011'
                    ],
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
                        'tag' => 'post_title', // Esto es solo un ejemplo de una etiqueta dinámica
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

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $tagline = $settings['tagline'];
            $title = $settings['title'];
            $show_icon = $settings['show_icon'] === 'yes';

            $project_id = get_the_ID();

            $amenities = wp_get_post_terms($project_id, 'project_amenity', ['fields' => 'all']);

            if (!empty($amenities) && !is_wp_error($amenities)) {
                ?>
                <div class="project-amenities-widget">
                    <div class="elementor-element e-con-boxed e-flex e-con e-parent e-lazyloaded" data-element_type="container">
                        <div class="e-con-inner">
                            <div class="elementor-element e-con-full e-flex e-con e-child">
                                <div class="elementor-element elementor-element-a71e9c2 elementor-widget elementor-widget-heading">
                                    <h6 class="elementor-heading-title elementor-size-default project-tagline"><?php echo esc_html($tagline); ?></h6>
                                </div>
                                <div class="elementor-element elementor-element-d9944cd elementor-widget elementor-widget-heading" data-id="d9944cd" data-element_type="widget" data-widget_type="heading.default">
                                    <?php if (!empty($title)): ?>
                                        <h3 class="elementor-heading-title elementor-size-default"><?php echo esc_html($title); ?></h3>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="elementor-element e-con-full e-flex e-con e-child">
                                <ul class="amenities-list">
                                    <?php foreach ($amenities as $amenity): ?>
                                        <li class="amenity-item">
                                            <?php if ($show_icon): ?>
                                                <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                                            <?php endif; ?>
                                            <span class="amenity-name"><?php echo esc_html($amenity->name); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="elementor-element e-con-full e-flex e-con e-parent e-lazyloaded" data-element_type="container">
                        <div class="elementor-element e-con-full e-flex e-con e-child">
                            <div class="amenities-slider">
                                <?php foreach ($amenities as $amenity): ?>
                                    <?php
                                    $term = get_term($amenity->term_id);
                                    $image = get_field('project_amenity_imagen', $term);
                                    $description = term_description($amenity->term_id, 'project_amenity'); // Descripción de la amenidad
                                    $description = strip_tags($description); // Eliminar cualquier etiqueta HTML
                                    ?>
                                    <div class="amenity-slide">
                                        <?php if ($image): ?>
                                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($amenity->name); ?>" class="amenity-image">
                                        <?php endif; ?>
                                        <h4 class="amenity-title"><?php echo esc_html($amenity->name); ?></h4>
                                        <?php if (!empty($description)): ?>
                                            <p class="amenity-description"><?php echo esc_html($description); ?></p>
                                        <?php endif; ?>
                                    </div>
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

    \Elementor\Plugin::instance()->widgets_manager->register(new Project_Amenities_Widget());

});