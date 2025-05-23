<?php
/**
 * Widget Name: Draw SVG
 * Description: SVG Draw
 * Author: Theplus
 * Author URI: https://posimyth.com
 *
 * @package ThePlus
 */

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

use TheplusAddons\Theplus_Element_Load;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class ThePlus_Draw_Svg.
 */
class ThePlus_Draw_Svg extends Widget_Base {

	/**
	 * Helpdesk Link For Need help.
	 *
	 * @var tp_help of the class.
	 */
	public $tp_help = THEPLUS_HELP;

	/**
	 * Get Widget Name.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	public function get_name() {
		return 'tp-draw-svg';
	}

	/**
	 * Get Widget Title.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	public function get_title() {
		return esc_html__( 'Draw SVG', 'theplus' );
	}

	/**
	 * Get Widget Icon.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	public function get_icon() {
		return 'fa fa-car theplus_backend_icon';
	}

	/**
	 * Get Custom URL.
	 *
	 * @since 1.0.0
	 * @version 5.6.5
	 */
	public function get_custom_help_url() {
		$help_url = $this->tp_help;

		return esc_url( $help_url );
	}

	/**
	 * Get Widget Categories.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	public function get_categories() {
		return array( 'plus-creatives' );
	}

	/**
	 * Get Widget Keywords.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	public function get_keywords() {
		return array( 'Draw SVG', 'SVG drawing', 'SVG animator', 'SVG widget', 'SVG', 'SVG animation', 'SVG graphics', 'SVG design' );
	}

	/**
     * It is use for widget add in catch or not.
     *
     * @since 6.1.1
     */
    public function is_dynamic_content(): bool {
        return false;
    }
	
	/**
	 * Register controls.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	protected function register_controls() {

		/** Content Section Start*/
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'select_svg_option',
			array(
				'label'   => esc_html__( 'Select SVG Option', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'svg_icons',
				'options' => array(
					'svg_icons' => esc_html__( 'Pre Built SVG Icon', 'theplus' ),
					'image'     => esc_html__( 'Custom Upload', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'svg_d_icon',
			array(
				'label'     => esc_html__( 'Select Svg Icon', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'app.svg',
				'options'   => theplus_svg_icons_list(),
				'condition' => array(
					'select_svg_option' => 'svg_icons',
				),
			)
		);
		$this->add_control(
			'svg_image',
			array(
				'label'      => esc_html__( 'Choose SVG', 'theplus' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'svg',
				'default'    => array(
					'url' => '',
				),
				'condition'  => array(
					'select_svg_option' => 'image',
				),
			)
		);
		$this->add_responsive_control(
			'alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'theplus' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'theplus' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'theplus' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'theplus' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'devices'      => array( 'desktop', 'tablet', 'mobile' ),
				'default'      => 'center',
				'prefix_class' => 'text-%s',
			)
		);
		$this->add_control(
			'on_hover_draw',
			array(
				'label'        => esc_html__( 'On Hover Draw', 'theplus' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Enable', 'theplus' ),
				'label_off'    => esc_html__( 'Disable', 'theplus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'on_loop_draw',
			array(
				'label'        => esc_html__( 'Loop Draw', 'theplus' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Enable', 'theplus' ),
				'label_off'    => esc_html__( 'Disable', 'theplus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->end_controls_section();

		/** SVG Style Section*/
		$this->start_controls_section(
			'section_svg_styling',
			array(
				'label' => esc_html__( 'SVG Style', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'svg_type',
			array(
				'label'   => esc_html__( 'Select Style Image', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'delayed',
				'options' => theplus_svg_type(),
			)
		);
		$this->add_control(
			'duration',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Duration', 'theplus' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 90,
				),
			)
		);
		$this->add_control(
			'max_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Max Width Svg', 'theplus' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_animated_svg .svg_inner_block' => 'max-width: {{SIZE}}{{UNIT}};max-height:{{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'min_width_switch!' => 'yes',
				),
			)
		);
		$this->add_control(
			'min_width_switch',
			array(
				'label'        => esc_html__( 'Min Width Svg', 'theplus' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Enable', 'theplus' ),
				'label_off'    => esc_html__( 'Disable', 'theplus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'min_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Min Width Svg', 'theplus' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_animated_svg .svg_inner_block' => 'min-width: {{SIZE}}{{UNIT}};min-height:{{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'min_width_switch' => 'yes',
				),
			)
		);
		$this->add_control(
			'border_stroke_color',
			array(
				'label'   => esc_html__( 'Border/Stoke Color', 'theplus' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#8072fc',
				'global'  => array(
					'active' => false,
				),
			)
		);
		$this->add_control(
			'svg_fill_enable',
			array(
				'label'     => esc_html__( 'Fill Color', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'svg_fill_color',
			array(
				'label'     => esc_html__( 'Fill Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'condition' => array(
					'svg_fill_enable' => 'yes',
				),
				'global'    => array(
					'active' => false,
				),
			)
		);
		$this->end_controls_section();
		/*Adv tab*/
		$this->start_controls_section(
			'section_plus_extra_adv',
			array(
				'label' => esc_html__( 'Plus Extras', 'theplus' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);
		$this->end_controls_section();
		/*Adv tab*/

		/*--On Scroll View Animation ---*/
		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation.php';
	}

	/**
	 * Render Draw SVG.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	protected function render() {

		$settings  = $this->get_settings_for_display();
		$alignment = ! empty( $settings['alignment'] ) ? $settings['alignment'] : 'center';

		/*--On Scroll View Animation ---*/
		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation-attr.php';

		/*--Plus Extra ---*/
		$PlusExtra_Class = 'plus-audio-player-widget';
		include THEPLUS_PATH . 'modules/widgets/theplus-widgets-extra.php';

		if ( 'image' === $settings['select_svg_option'] ) {
			$svg_url = $settings['svg_image']['url'];
		} else {
			$svg_url = THEPLUS_URL . 'assets/images/svg/' . $settings['svg_d_icon'];
		}

		if ( ! empty( $settings['border_stroke_color'] ) ) {
			$border_stroke_color = $settings['border_stroke_color'];
		} else {
			$border_stroke_color = 'none';
		}

		$uid = uniqid( 'svg' );

		$hover_draw = '';
		if ( ! empty( $settings['on_hover_draw'] ) && 'yes' === $settings['on_hover_draw'] ) {
			$hover_draw = 'plus-hover-draw-svg';
		}

		$on_loop_draw = 'no';
		if ( ! empty( $settings['on_loop_draw'] ) && 'yes' === $settings['on_loop_draw'] ) {
			$svg_fill_color = $settings['svg_fill_color'];
			$on_loop_draw   = 'yes';
		}

		if ( ! empty( $settings['svg_fill_enable'] ) && 'yes' === $settings['svg_fill_enable'] ) {
			$svg_fill_color  = $settings['svg_fill_color'];
			$svg_fill_enable = 'yes';
		} else {
			$svg_fill_color  = 'none';
			$svg_fill_enable = 'no';
		}

		$animate_svg = '<div class="pt_plus_animated_svg-an">';

			$animate_svg .= '<div class="pt_plus_animated_svg ' . esc_attr( $hover_draw ) . ' ' . esc_attr( $alignment ) . ' ' . esc_attr( $uid ) . ' ' . esc_attr( $animated_class ) . '" ' . $animation_attr . ' data-id="' . esc_attr( $uid ) . '" data-type="' . esc_attr( $settings['svg_type'] ) . '" data-duration="' . esc_attr( $settings['duration']['size'] ) . '" data-stroke="' . esc_attr( $border_stroke_color ) . '" data-fill_color="' . esc_attr( $svg_fill_color ) . '" data-svg_fill_enable="' . esc_attr( $svg_fill_enable ) . '" data-svg_loop_enable="' . esc_attr( $on_loop_draw ) . '">';

				$animate_svg .= '<div class="svg_inner_block">';

				/**
				 * $animate_svg .='<div class="svg_inner_block" style="position: relative;display: block;">';
				 * if(!empty($svg_url)){
				 * $animate_svg .= file_get_contents($svg_url);
				 * }
				 */
					$animate_svg .= '<object id="' . esc_attr( $uid ) . '" type="image/svg+xml" data="' . esc_url( $svg_url ) . '" ></object>';

				$animate_svg .= '</div>';

			$animate_svg .= '</div>';

		$animate_svg .= '</div>';

		echo $before_content . $animate_svg . $after_content;
	}

	/**
	 * Render content_template.
	 *
	 * @since 1.4.0
	 * @version 5.4.2
	 */
	protected function content_template() {
	}
}
