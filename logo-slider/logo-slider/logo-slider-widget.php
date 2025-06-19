<?php
/*
Plugin Name: Elementor Logo Slider 
Description: Adds a responsive logo slider widget for Elementor.
Version: 1.1
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

add_action('elementor/widgets/register', function ($widgets_manager) {
    if (!class_exists('Elementor_Logo_Slider_Widget')) {
        class Elementor_Logo_Slider_Widget extends \Elementor\Widget_Base {
            public function get_name() { return 'logo_slider'; }
            public function get_title() { return esc_html__('Logo Slider', 'elementor'); }
            public function get_icon() { return 'eicon-carousel'; }
            public function get_categories() { return ['general']; }

            protected function register_controls() {
                $this->start_controls_section('logos_section', ['label' => esc_html__('Logos', 'elementor')]);

                $repeater = new \Elementor\Repeater();

                $repeater->add_control('logo_image', [
                    'label' => esc_html__('Logo Image', 'elementor'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                ]);

                $repeater->add_control('logo_link', [
                    'label' => esc_html__('Link', 'elementor'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => 'https://example.com',
                    'default' => ['is_external' => true, 'nofollow' => true],
                ]);

                $this->add_control('logos', [
                    'label' => esc_html__('Logos', 'elementor'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => 'Logo',
                ]);

                $this->end_controls_section();
            }

            protected function render() {
                $settings = $this->get_settings_for_display();

                if (empty($settings['logos'])) return;

                echo '<div class="custom-logo-slider-wrapper">';
                echo '<div class="owl-carousel custom-logo-slider">';
                foreach ($settings['logos'] as $index => $logo) {
                    $img = esc_url($logo['logo_image']['url']);
                    $link = esc_url($logo['logo_link']['url']);
                    $target = $logo['logo_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $logo['logo_link']['nofollow'] ? ' rel="nofollow"' : '';
                    echo '<div class="logo-slide">';
                    echo "<a href='{$link}' {$target} {$nofollow}><img src='{$img}' alt='Logo {$index}'></a>";
                    echo '</div>';
                }
                echo '</div>'; // end carousel
                echo '</div>'; // end wrapper
            }
        }
    }
    $widgets_manager->register(new Elementor_Logo_Slider_Widget());
});

add_action('elementor/frontend/after_enqueue_scripts', function () {
    wp_enqueue_style('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
    wp_enqueue_style('owl-theme', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css');
    wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', ['jquery'], null, true);

    // Enqueue your custom CSS from external file
    wp_enqueue_style('elementor-logo-slider-style', plugins_url('elementor-logo-slider.css', __FILE__), [], '1.0');

    // Add custom inline script
    wp_add_inline_script('owl-carousel', "
        jQuery(document).ready(function($) {
            $('.custom-logo-slider').owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 2000,
                smartSpeed: 700,
                responsive: {
                    0: { items: 2 },
                    480: { items: 3 },
                    768: { items: 5 },
                    1024: { items: 7 }
                }
            });
        });
    ");
});

// Custom styles for fade effect
// add_action('wp_enqueue_scripts', function () {
//     wp_add_inline_style('owl-carousel', "
//         .custom-logo-slider-wrapper {
//             position: relative;
//             overflow: hidden;
//             padding: 20px 0;
//         }

//         .custom-logo-slider .logo-slide img {
//             max-height: 50px;
//             opacity: 0.6;
//             transition: opacity 0.3s ease;
//             filter: grayscale(100%);
//         }

//         .custom-logo-slider .logo-slide img:hover {
//             opacity: 1;
//             filter: grayscale(0%);
//         }

//         .custom-logo-slider-wrapper::before,
//         .custom-logo-slider-wrapper::after {
//             content: '';
//             position: absolute;
//             top: 0;
//             width: 60px;
//             height: 100%;
//             z-index: 2;
//             pointer-events: none;
//         }

//         .custom-logo-slider-wrapper::before {
//             left: 0;
//             background: linear-gradient(to right, #fff, rgba(255,255,255,0));
//         }

//         .custom-logo-slider-wrapper::after {
//             right: 0;
//             background: linear-gradient(to left, #fff, rgba(255,255,255,0));
//         }
//     ");
// });
