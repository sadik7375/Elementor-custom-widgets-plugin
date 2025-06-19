<?php
/*
Plugin Name: Elementor Testimonial Slider Widget
Description: A custom testimonial slider like the design.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', ['jquery'], null, true);
});


add_action('elementor/widgets/register', function($widgets_manager) {
    if (!class_exists('Testimonial_Slider_Widget')) {
        class Testimonial_Slider_Widget extends \Elementor\Widget_Base {
            public function get_name() {
                return 'testimonial_slider';
            }

            public function get_title() {
                return esc_html__('Testimonial Slider', 'elementor');
            }

            public function get_icon() {
                return 'eicon-slider-push';
            }

            public function get_categories() {
                return ['general'];
            }

          protected function register_controls() {
    $this->start_controls_section('content_section', [
        'label' => __('Testimonials', 'elementor'),
    ]);

    $repeater = new \Elementor\Repeater();

    $repeater->add_control('image', [
        'label' => __('Image', 'elementor'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
    ]);

    $repeater->add_control('name', [
        'label' => __('Name', 'elementor'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => 'Emma Johnson',
    ]);

    $repeater->add_control('title', [
        'label' => __('Position', 'elementor'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => 'Founder at Specra',
    ]);

    $repeater->add_control('testimonial', [
        'label' => __('Testimonial', 'elementor'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'default' => 'Diplomat Response has transformed my online communication...',
    ]);

    $repeater->add_control('country', [
        'label' => __('Country', 'elementor'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => 'South Korea',
    ]);

    $repeater->add_control('date', [
        'label' => __('Date', 'elementor'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => '24 December 2024',
    ]);

    $repeater->add_control('rating', [
        'label' => __('Rating', 'elementor'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 1,
        'max' => 5,
        'default' => 5,
    ]);

    // ✅ Gradient background control
    $repeater->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            'name' => 'bg_gradient',
            'label' => __('Background', 'elementor'),
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .testimonial-card',
        ]
    );

    $this->add_control('slides', [
        'label' => __('Testimonial Slides', 'elementor'),
        'type' => \Elementor\Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [],
        'title_field' => '{{{ name }}}',
    ]);

    $this->end_controls_section();
}


protected function render() {
    $settings = $this->get_settings_for_display();
    if (empty($settings['slides'])) return;
    ?>
    <div class="testimonial-slider swiper">
        <div class="swiper-wrapper">
            <?php foreach ($settings['slides'] as $slide) : ?>
                <div class="swiper-slide elementor-repeater-item-<?= esc_attr($slide['_id']) ?>" style="padding:20px;">
                    <div class="testimonial-card" style="border-radius:12px; padding:20px; box-shadow:0 0 10px rgba(0,0,0,0.05);">
                        <div style="display:flex; align-items:center; margin-bottom:15px;">
                            <img src="<?= esc_url($slide['image']['url']) ?>" alt="<?= esc_attr($slide['name']) ?>" style="width:50px; height:50px; border-radius:50%; object-fit:cover; margin-right:15px;">
                            <div>
                                <div style="font-weight:bold; font-size:20px "><?= esc_html($slide['name']) ?></div>
                                <div style="font-size:12px; font-size:16px"><?= esc_html($slide['title']) ?></div>
                            </div>
                        </div>
                        <hr style="margin: 10px 0; border-top: 1px solid #d9d4e1;">
                        <div style="font-size:18px;margin-bottom:10px;padding-top: 20px;padding-bottom: 20px;  ">
                            <?= esc_html($slide['testimonial']) ?>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 10px;">
                            <div>
                                <div style="font-weight: bold; font-size:20px"><?= esc_html($slide['country']) ?></div>
                                <div style="font-size: 12px; font-size:16px "><?= esc_html($slide['date']) ?></div>
                            </div>
                            <div style="color: #f6b800; font-size: 14px;">
                                <?php for ($i = 0; $i < $slide['rating']; $i++) echo '★'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <style>
        .testimonial-slider .swiper-slide {
            width: 100%;
          
            margin-right:0px !important;
            margin-left:0px !important
        }
        .testimonial-slider {
            overflow: hidden;
            padding-bottom: 40px;
        }
    </style>

    <script>
        jQuery(document).ready(function () {
            new Swiper('.testimonial-slider', {
           
                
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView:1,
                    },
                    1024: {
                        slidesPerView: 5,
                    }
                }
            });
        });
    </script>
    <?php
}

        }
    }
   
    $widgets_manager->register(new Testimonial_Slider_Widget());
});
