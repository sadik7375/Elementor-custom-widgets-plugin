<?php
/**
 * Plugin Name: Elementor Price Plan Widget
 * Description: 4-column customizable pricing plan widget for Elementor with gradient backgrounds and icons.
 * Version: 1.1
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

add_action('elementor/widgets/widgets_registered', function () {
    if (!class_exists('\Elementor\Widget_Base')) return;

    class PricePlanWidget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'custom_price_plan';
        }

        public function get_title() {
            return __('Price Plan Widget', 'custom-price-plan');
        }

        public function get_icon() {
            return 'eicon-price-table';
        }

        public function get_categories() {
            return ['general'];
        }

        protected function _register_controls() {
            $this->start_controls_section('section_title', [
                'label' => __('Section Title', 'custom-price-plan'),
            ]);

            $this->add_control('main_title', [
                'label' => __('Main Title', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Choose Your Plan', 'custom-price-plan'),
            ]);

            $this->end_controls_section();

            $this->start_controls_section('section_plans', [
                'label' => __('Plans', 'custom-price-plan'),
            ]);

            $repeater = new \Elementor\Repeater();

            $repeater->add_control('icon_image', [
                'label' => __('Top Icon/Image', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]);

            $repeater->add_control('plan_name', [
                'label' => __('Plan Name', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Basic',
            ]);

            $repeater->add_control('plan_subtitle', [
                'label' => __('Plan Subtitle', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Best for basic use.',
            ]);

            $repeater->add_control('plan_price', [
                'label' => __('Plan Price', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Free',
            ]);

            $repeater->add_control('plan_duration', [
                'label' => __('Duration', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '/ month',
            ]);

            $repeater->add_control('button_text', [
                'label' => __('Button Text', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Get Started',
            ]);

            $repeater->add_control('button_link', [
                'label' => __('Button URL', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]);

            $repeater->add_control('features', [
                'label' => __('Features (1 per line)', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => "Smart Reply Generation\nBasic Tone Customization\nUp to 20 responses for a day\nSet relationship to get specific reply",
            ]);

          $repeater->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'bg_gradient',
                    'label' => __('Card Background', 'custom-price-plan'),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.price-card',
                ]
            );

            $this->add_control('plans', [
                'label' => __('Pricing Plans', 'custom-price-plan'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ plan_name }}}',
            ]);

            $this->end_controls_section();
        }

protected function render() {
            $settings = $this->get_settings_for_display();

            echo '<div class="price-plan-widget">';
            if (!empty($settings['main_title'])) {
                echo '<h2 class="price-section-title">' . esc_html($settings['main_title']) . '</h2>';
            }

            echo '<div class="price-cards">';

            if (!empty($settings['plans']) && is_array($settings['plans'])) {
                foreach ($settings['plans'] as $index => $plan) {
                    $repeater_id = $this->get_repeater_setting_key('plan_name', 'plans', $index);
                    $this->add_render_attribute($repeater_id, 'class', 'price-card elementor-repeater-item-' . $plan['_id']);

                    $features = !empty($plan['features']) ? explode("\n", $plan['features']) : [];
                    $icon_url = !empty($plan['icon_image']['url']) ? esc_url($plan['icon_image']['url']) : '';
                    $plan_name = esc_html($plan['plan_name']);
                    $plan_subtitle = esc_html($plan['plan_subtitle']);
                    $plan_price = esc_html($plan['plan_price']);
                    $plan_duration = esc_html($plan['plan_duration']);
                    $button_text = esc_html($plan['button_text']);
                    $button_link = !empty($plan['button_link']['url']) ? esc_url($plan['button_link']['url']) : '#';

                    echo '<div ' . $this->get_render_attribute_string($repeater_id) . '>';
                    if ($icon_url) {
                        echo '<div class="price-icon"><img src="' . $icon_url . '" alt="icon"></div>';
                    }
                   
                    echo '<h3 id="plan-name">' . $plan_name . '</h3>';
                    echo '<p class="plan-sub">' . $plan_subtitle . '</p>';
                    echo '<div class="price-value">' . $plan_price . '<span>' . $plan_duration . '</span></div>';
                    echo '<a href="' . $button_link . '" class="price-btn">' . $button_text . '</a>';
                      echo '<hr style="border: none; border-top: 1px solid #cccccc; margin: 20px 0;">';
                             echo '<p class="features-title">What you will get</p>';
   
                    echo '<div class="features-list"><ul>';

                    foreach ($features as $f) {
                        $f = trim($f);
                        if (!empty($f)) {
                            echo '<li>✔ ' . esc_html($f) . '</li>';
                        }
                    }
                    echo '</ul></div>';
                    echo '</div>'; // .price-card
                }
            }

            echo '</div></div>'; // .price-cards & .price-plan-widget

            $this->print_style();
        }


        protected function print_style() {
            echo '<style>
                .price-plan-widget {
                    text-align: center;
                    padding: 40px 20px;
                }
                .price-section-title {
                    font-size: 32px;
                    margin-bottom: 40px;
                }
                    h3#plan-name {
                    font-size: 24px;
                    color: #080808 !important;
                     font-weight: 600 !important;
                    }
                .price-cards {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 20px;
                }
                .price-card {
                    width: 400px;
                    border-radius: 20px;
                    padding: 25px;
                    color: #000;
                    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .price-icon img {
                    width: 40px;
                    height: 40px;
                    margin-bottom: 15px;
                }
                .price-card h3 {
                    margin: 0;
                    font-size: 20px;
                    font-weight: 700;
                    display:flex;
                }
               .plan-sub {
                font-size: 16px;
               margin-bottom: 10px;
                color: #1C1629;
                  display: flex;
                  align-items: flex-start;
        }
                .price-value {
                    font-size: 48px;
                    font-weight: 800;
                    margin-bottom: 15px;
                    display: flex
;
                }
             .price-value span {
            font-size: 18px;
            font-weight: 400;
            margin-top: 10px;
            }


            .price-btn {
            width: 100%;
            display: inline-block;
            margin: 10px auto;
            padding: 10px 20px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            font-weight: 600;
            color: #080808 !important;
            font-weight: 700;
            font-size: 16px !important;
}

                .features-list
                {
               display: flex;
               align-items: flex-start;
                   margin-top: -20px;
                  }
                .features-list ul {
                    text-align: left;
                    margin-top: 0px;
                    padding-left: 0;
                    list-style: none;
                    margin-left:0px;
                }
                .features-list li {
                    margin: 5px 0;
                    font-size: 14px;
                }
                .features-list ul li {
                  list-style: none;
                }

                .price-icon {
                  display: flex;
                  align-items: flex-start;
                  }

                  p.features-title

                 {
                    display: flex;
                    margin: 0px;
                    font-size: 18px;
                    font-weight: 600;
                    color: #080808;
                 }


            </style>';
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new PricePlanWidget());
});
