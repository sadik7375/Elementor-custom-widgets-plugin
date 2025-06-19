<?php
/**
 * Plugin Name: Photo Slider for Elementor
 * Description: A custom Elementor widget to create an image slider with Owl Carousel.
 * Version: 1.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit;
}
use Elementor\Group_Control_Typography;

// Register the widget only after Elementor is loaded
function register_photo_slider_widget() {
    // Check if Elementor is active and Widget_Base class exists
    if (!did_action('elementor/loaded')) {
        return;
    }

    if (!class_exists('\Elementor\Widget_Base')) {
        return;
    }

    class Elementor_Photo_Slider_Widget extends \Elementor\Widget_Base {
        public function get_name() {
            return 'photo-slider';
        }

        public function get_title() {
            return __('Photo Slider', 'photo-slider');
        }

        public function get_icon() {
            return 'eicon-slider-push';
        }

        public function get_categories() {
            return ['general'];
        }

        protected function register_controls() {
            $this->start_controls_section(
                'section_slides',
                [
                    'label' => __('Slides', 'photo-slider'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'slide_image',
                [
                    'label' => __('Image', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_control(
                'slide_title',
                [
                    'label' => __('Title', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                ]
            );

            $repeater->add_control(
                'slide_description',
                [
                    'label' => __('Description', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                ]
            );

            $this->add_control(
                'slides',
                [
                    'label' => __('Slides', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ slide_title }}}',
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'section_carousel',
                [
                    'label' => __('Carousel Settings', 'photo-slider'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'items',
                [
                    'label' => __('Items to Show', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 3,
                ]
            );

            $this->add_control(
                'items_mobile',
                [
                    'label' => __('Items on Mobile', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 1,
                ]
            );

            $this->end_controls_section();



            $this->start_controls_section(
                'section_style',
                [
                    'label' => __('Style', 'photo-slider'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            // Title Style
            $this->add_control(
                'title_heading',
                [
                    'label' => __('Title', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
                'title_color',
                [
                    'label' => __('Text Color', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .slide-title' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __('Typography', 'photo-slider'),
                    'selector' => '{{WRAPPER}} .slide-title',
                ]
            );
            
            // Subtitle Style
            $this->add_control(
                'subtitle_heading',
                [
                    'label' => __('Subtitle', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
                'subtitle_color',
                [
                    'label' => __('Text Color', 'photo-slider'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .slide-subtitle' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'subtitle_typography',
                    'label' => __('Typography', 'photo-slider'),
                    'selector' => '{{WRAPPER}} .slide-subtitle',
                ]
            );
            
            $this->end_controls_section();
            





        }

        protected function render() {
            $settings = $this->get_settings_for_display();

            if (empty($settings['slides'])) {
                return;
            }
            ?>
   <div class="elementor-photo-slider">
  <div class="owl-carousel">
    <?php foreach ($settings['slides'] as $slide) : ?>
      <div class="slide-item">
        <?php if (!empty($slide['slide_image']['url'])) : ?>
          <div class="icon-wrapper">
            <img src="<?php echo esc_url($slide['slide_image']['url']); ?>" alt="<?php echo esc_attr($slide['slide_title']); ?>" />
          </div>
        <?php endif; ?>
        <div class="slide-content">
          <?php if (!empty($slide['slide_title'])) : ?>
            <h3 class="slide-title"><?php echo nl2br(esc_html($slide['slide_title'])); ?></h3>
          <?php endif; ?>
          <?php if (!empty($slide['slide_description'])) : ?>
            <p class="slide-subtitle"><?php echo esc_html($slide['slide_description']); ?></p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>


          <script>
    jQuery(document).ready(function($) {
        $('.owl-carousel').owlCarousel({
            items: <?php echo intval($settings['items']); ?>,
            responsive: {
                0: { items: <?php echo intval($settings['items_mobile']); ?> },
                768: { items: <?php echo intval($settings['items']); ?> }
            },
            loop: true,
            nav: true, // Enable navigation arrows
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            navText: [
                '<i class="fa-solid fa-arrow-left"></i>', // Previous arrow icon
                '<i class="fa-solid fa-arrow-right"></i>'  // Next arrow icon
            ]
        });
    });
</script>
            <?php
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Elementor_Photo_Slider_Widget());
}
add_action('elementor/widgets/register', 'register_photo_slider_widget');

// Enqueue styles and scripts
function enqueue_photo_slider_assets() {
    wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', ['jquery'], '2.3.4', true);
    wp_enqueue_style('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', [], '2.3.4');
    wp_enqueue_style('photo-slider-style', plugin_dir_url(__FILE__) . 'photo-slider.css');
}
add_action('wp_enqueue_scripts', 'enqueue_photo_slider_assets');
