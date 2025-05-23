<?php
/**
 * Widget Name: Flip Box
 * Description: Flip Box.
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
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class ThePlus_Flip_Box
 */
class ThePlus_Flip_Box extends Widget_Base {

		/**
	 * Document Link For Need help.
	 *
	 * @var tp_doc of the class.
	 */
	public $tp_doc = THEPLUS_TPDOC;

	/**
	 * Helpdesk Link For Need help.
	 *
	 * @var tp_help of the class.
	 */
	public $tp_help = THEPLUS_HELP;

	/**
	 * Get Widget Name
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	public function get_name() {
		return 'tp-flip-box';
	}

	/**
	 * Get Widget Title
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	public function get_title() {
		return esc_html__( 'Flip Box', 'theplus' );
	}

	/**
	 * Get Widget Icon
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	public function get_icon() {
		return 'fa fa-dot-circle-o theplus_backend_icon';
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
	 * Get Widget Categories
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	public function get_categories() {
		return array( 'plus-creatives' );
	}

	/**
	 * Get Widget Keywords
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	public function get_keywords() {
		return array( 'Flipbox', 'Flip Box', ' Flip-card', 'Card flip', 'Box flip', 'Elementor flipbox', 'Elementor flip box', 'Elementor flip-card', 'Elementor card flip', ' Elementor box flip' );
	}

	/**
	 * It is use for widget add in catch or not.
	 *
	 * @since 6.0.6
	 */
	public function is_dynamic_content(): bool {
		return false;
	}
	
	/**
	 * Register controls.
	 *
	 * @since 3.3.0
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
			'info_box_layout',
			array(
				'label'   => esc_html__( 'Select Layout', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'single_layout',
				'options' => array(
					'single_layout'   => esc_html__( 'Listing', 'theplus' ),
					'carousel_layout' => esc_html__( 'Carousel', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'how_it_works_carousel',
			array(
				'label' => wp_kses_post( "<a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "elementor-flip-box-carousel/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> Learn How it works  <i class='eicon-help-o'></i> </a>" ),
				'type'  => Controls_Manager::HEADING,
				'condition' => array(
					'info_box_layout' => 'carousel_layout',
				),
			)
		);
		$this->add_control(
			'flip_style',
			array(
				'label'   => esc_html__( 'Flip Type', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'theplus' ),
					'vertical'   => esc_html__( 'Vertical', 'theplus' ),
				),
			)
		);
		$this->add_responsive_control(
			'flip_box_height',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Box Height', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 50,
						'max'  => 700,
						'step' => 5,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 300,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-front,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-back' => 'min-height: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'loop_select_icon',
			array(
				'label'       => esc_html__( 'Select Icon', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'You can select Icon, Custom Image or SVG using this option.', 'theplus' ),
				'default'     => '',
				'options'     => array(
					''      => esc_html__( 'None', 'theplus' ),
					'icon'  => esc_html__( 'Icon', 'theplus' ),
					'image' => esc_html__( 'Image', 'theplus' ),
					'svg'   => esc_html__( 'Svg', 'theplus' ),
				),
				'condition'   => array(
					'info_box_layout' => 'carousel_layout',
				),
			)
		);
		$this->add_control(
			'loop_display_button',
			array(
				'label'     => esc_html__( 'Button', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'info_box_layout' => 'carousel_layout',
				),
			)
		);
		$this->add_control(
			'loop_button_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Button Style', 'theplus' ),
				'default'   => 'style-7',
				'options'   => array(
					'style-7' => esc_html__( 'Style 1', 'theplus' ),
					'style-8' => esc_html__( 'Style 2', 'theplus' ),
					'style-9' => esc_html__( 'Style 3', 'theplus' ),
				),
				'condition' => array(
					'info_box_layout'     => 'carousel_layout',
					'loop_display_button' => 'yes',
				),
			)
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'loop_front_options',
			array(
				'label'     => esc_html__( 'Front Options', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$repeater->add_control(
			'loop_title',
			array(
				'label'   => esc_html__( 'Title Of Flip Box', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'The Plus', 'theplus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'loop_image_icon',
			array(
				'label'       => esc_html__( 'Select Icon', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'You can select Icon, Custom Image or SVG using this option.', 'theplus' ),
				'default'     => 'icon',
				'options'     => array(
					''      => esc_html__( 'None', 'theplus' ),
					'icon'  => esc_html__( 'Icon', 'theplus' ),
					'image' => esc_html__( 'Image', 'theplus' ),
					'svg'   => esc_html__( 'Svg', 'theplus' ),
				),
			)
		);
		$repeater->add_control(
			'loop_svg_icon',
			array(
				'label'     => esc_html__( 'Svg Select Option', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'img',
				'options'   => array(
					'img' => esc_html__( 'Custom Upload', 'theplus' ),
					'svg' => esc_html__( 'Pre Built SVG Icon', 'theplus' ),
				),
				'condition' => array(
					'loop_image_icon' => 'svg',
				),
			)
		);
		$repeater->add_control(
			'loop_svg_image',
			array(
				'label'       => esc_html__( 'Only Svg', 'theplus' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => esc_html__( 'Select Only .svg File from media library.', 'theplus' ),
				'default'     => array(
					'url' => '',
				),
				'media_type'  => 'image',
				'condition'   => array(
					'loop_image_icon' => 'svg',
					'loop_svg_icon'   => 'img',
				),
			)
		);
		$repeater->add_control(
			'loop_svg_d_icon',
			array(
				'label'     => esc_html__( 'Select Svg Icon', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'app.svg',
				'options'   => theplus_svg_icons_list(),
				'condition' => array(
					'loop_image_icon' => 'svg',
					'loop_svg_icon'   => 'svg',
				),
			)
		);
		$repeater->add_control(
			'loop_max_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Max Width Svg', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 2,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'loop_image_icon' => 'svg',
					'loop_svg_icon'   => array( 'svg', 'img' ),
				),
			)
		);
		$repeater->add_control(
			'loop_select_image',
			array(
				'label'      => esc_html__( 'Use Image As Icon', 'theplus' ),
				'type'       => Controls_Manager::MEDIA,
				'default'    => array(
					'url' => '',
				),
				'media_type' => 'image',
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'loop_image_icon' => 'image',
				),
			)
		);
		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'loop_select_image_thumbnail',
				'default'   => 'full',
				'separator' => 'after',
				'condition' => array(
					'loop_image_icon' => 'image',
				),
			)
		);
		$repeater->add_control(
			'loop_icon_font_style',
			array(
				'label'     => esc_html__( 'Icon Font', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'font_awesome',
				'options'   => array(
					'font_awesome'   => esc_html__( 'Font Awesome', 'theplus' ),
					'font_awesome_5' => esc_html__( 'Font Awesome 5', 'theplus' ),
					'icon_mind'      => esc_html__( 'Icons Mind', 'theplus' ),
				),
				'condition' => array(
					'loop_image_icon' => 'icon',
				),
			)
		);
		$repeater->add_control(
			'loop_icon_fontawesome',
			array(
				'label'     => esc_html__( 'Icon Library', 'theplus' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-bank',
				'condition' => array(
					'loop_image_icon'      => 'icon',
					'loop_icon_font_style' => 'font_awesome',
				),
			)
		);
		$repeater->add_control(
			'loop_icon_fontawesome_5',
			array(
				'label'     => esc_html__( 'Icon Library', 'theplus' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-plus',
					'library' => 'solid',
				),
				'condition' => array(
					'loop_image_icon'      => 'icon',
					'loop_icon_font_style' => 'font_awesome_5',
				),
			)
		);
		$repeater->add_control(
			'loop_icons_mind',
			array(
				'label'       => esc_html__( 'Icon Library', 'theplus' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => '',
				'label_block' => true,
				'options'     => theplus_icons_mind(),
				'condition'   => array(
					'loop_image_icon'      => 'icon',
					'loop_icon_font_style' => 'icon_mind',
				),
			)
		);
		$repeater->add_control(
			'loop_back_options',
			array(
				'label'     => esc_html__( 'Back Options', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$repeater->add_control(
			'loop_content_desc',
			array(
				'label'   => esc_html__( 'Description', 'theplus' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'theplus' ),
			)
		);
		$repeater->add_control(
			'loop_button_text',
			array(
				'label'       => esc_html__( 'Text', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Read More', 'theplus' ),
				'placeholder' => esc_html__( 'Read More', 'theplus' ),
			)
		);
		$repeater->add_control(
			'loop_button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'theplus' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://www.demo-link.com', 'theplus' ),
				'default'     => array(
					'url' => '#',
				),
			)
		);
		$repeater->start_controls_tabs( 'tabs_loop_background_style' );
		$repeater->start_controls_tab(
			'tab_loop_background_front',
			array(
				'label' => esc_html__( 'Front', 'theplus' ),
			)
		);
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_loop_front_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_info_box {{CURRENT_ITEM}}.service-flipbox-front',
			)
		);
		$repeater->add_control(
			'box_loop_front_overlay_bg_color',
			array(
				'label'     => esc_html__( 'Overlay Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner {{CURRENT_ITEM}} .infobox-front-overlay' => 'background: {{VALUE}};',
				),
			)
		);
		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'tab_loop_background_back',
			array(
				'label' => esc_html__( 'Back', 'theplus' ),
			)
		);
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_loop_back_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_info_box {{CURRENT_ITEM}}.service-flipbox-back',
			)
		);
		$repeater->add_control(
			'box_loop_back_overlay_bg_color',
			array(
				'label'     => esc_html__( 'Overlay Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner {{CURRENT_ITEM}} .infobox-back-overlay' => 'background: {{VALUE}};',
				),
			)
		);
		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();
		$this->add_control(
			'loop_content',
			array(
				'label'       => esc_html__( 'Carousel FlipBox', 'theplus' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'loop_title' => 'The Plus',
					),
					array(
						'loop_title' => 'The Plus 2',
					),
					array(
						'loop_title' => 'The Plus 3',
					),
					array(
						'loop_title' => 'The Plus 4',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ loop_title }}}',
				'condition'   => array(
					'info_box_layout' => 'carousel_layout',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'front_content_section',
			array(
				'label'     => esc_html__( 'Front Side', 'theplus' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'front_options',
			array(
				'label'     => esc_html__( 'Front Options', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'title',
			array(
				'label'     => esc_html__( 'Title', 'theplus' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'The Plus', 'theplus' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'image_icon',
			array(
				'label'       => esc_html__( 'Select Icon', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'You can select Icon, Custom Image or SVG using this option.', 'theplus' ),
				'default'     => 'icon',
				'options'     => array(
					''      => esc_html__( 'None', 'theplus' ),
					'icon'  => esc_html__( 'Icon', 'theplus' ),
					'image' => esc_html__( 'Image', 'theplus' ),
					'svg'   => esc_html__( 'Svg', 'theplus' ),
				),
				'condition'   => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'svg_icon',
			array(
				'label'     => esc_html__( 'Svg Select Option', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'img',
				'options'   => array(
					'img' => esc_html__( 'Custom Upload', 'theplus' ),
					'svg' => esc_html__( 'Pre Built SVG Icon', 'theplus' ),
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'svg',
				),
			)
		);
		$this->add_control(
			'svg_image',
			array(
				'label'       => esc_html__( 'Only Svg', 'theplus' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => esc_html__( 'Select Only .svg File from media library.', 'theplus' ),
				'default'     => array(
					'url' => '',
				),
				'media_type'  => 'image',
				'condition'   => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'svg',
					'svg_icon'        => 'img',
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
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'svg',
					'svg_icon'        => 'svg',
				),
			)
		);
		$this->add_control(
			'select_image',
			array(
				'label'      => esc_html__( 'Use Image As Icon', 'theplus' ),
				'type'       => Controls_Manager::MEDIA,
				'default'    => array(
					'url' => '',
				),
				'media_type' => 'image',
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'image',
				),
			)
		);
		$this->add_responsive_control(
			'select_image_size',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Icon Image Max-Width', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 10,
						'max'  => 700,
						'step' => 5,
					),
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box.info-box-style_5 .service-img' => 'max-width: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'image',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'select_image_thumbnail',
				'default'   => 'full',
				'condition' => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'image',
				),
			)
		);
		$this->add_control(
			'icon_font_style',
			array(
				'label'     => esc_html__( 'Icon Font', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'font_awesome',
				'options'   => array(
					'font_awesome'   => esc_html__( 'Font Awesome', 'theplus' ),
					'font_awesome_5' => esc_html__( 'Font Awesome 5', 'theplus' ),
					'icon_mind'      => esc_html__( 'Icons Mind', 'theplus' ),
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'icon',
				),
			)
		);
		$this->add_control(
			'icon_fontawesome',
			array(
				'label'     => esc_html__( 'Icon Library', 'theplus' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-bank',
				'condition' => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'icon',
					'icon_font_style' => 'font_awesome',
				),
			)
		);
		$this->add_control(
			'icon_fontawesome_5',
			array(
				'label'     => esc_html__( 'Icon Library', 'theplus' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-plus',
					'library' => 'solid',
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'icon',
					'icon_font_style' => 'font_awesome_5',
				),
			)
		);
		$this->add_control(
			'icons_mind',
			array(
				'label'       => esc_html__( 'Icon Library', 'theplus' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => '',
				'label_block' => true,
				'options'     => theplus_icons_mind(),
				'condition'   => array(
					'info_box_layout' => 'single_layout',
					'image_icon'      => 'icon',
					'icon_font_style' => 'icon_mind',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'back_content_section',
			array(
				'label'     => esc_html__( 'Back Side', 'theplus' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'back_options',
			array(
				'label'     => esc_html__( 'Back Options', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'content_desc',
			array(
				'label'       => esc_html__( 'Description', 'theplus' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'theplus' ),
				'placeholder' => esc_html__( 'Type your description here', 'theplus' ),
				'condition'   => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'display_button',
			array(
				'label'     => esc_html__( 'Button', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'default'   => 'no',
				'separator' => 'before',
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'button_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Button Style', 'theplus' ),
				'default'   => 'style-7',
				'options'   => array(
					'style-7' => esc_html__( 'Style 1', 'theplus' ),
					'style-8' => esc_html__( 'Style 2', 'theplus' ),
					'style-9' => esc_html__( 'Style 3', 'theplus' ),
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
					'display_button'  => 'yes',
				),
			)
		);
		$this->add_control(
			'button_text',
			array(
				'label'       => esc_html__( 'Text', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Read More', 'theplus' ),
				'placeholder' => esc_html__( 'Read More', 'theplus' ),
				'condition'   => array(
					'info_box_layout' => 'single_layout',
					'display_button'  => 'yes',
				),
			)
		);
		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'theplus' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://www.demo-link.com', 'theplus' ),
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'info_box_layout' => 'single_layout',
					'display_button'  => 'yes',
				),
			)
		);
		$this->add_control(
			'button_icon_font_style',
			array(
				'label'     => esc_html__( 'Icon Font', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'font_awesome',
				'options'   => array(
					'font_awesome'   => esc_html__( 'Font Awesome', 'theplus' ),
					'font_awesome_5' => esc_html__( 'Font Awesome 5', 'theplus' ),
					'icon_mind'      => esc_html__( 'Icons Mind', 'theplus' ),
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
					'display_button'  => 'yes',
					'button_style!'   => array( 'style-7', 'style-9' ),
				),
			)
		);
		$this->add_control(
			'button_icon',
			array(
				'label'       => esc_html__( 'Icon', 'theplus' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => 'fa fa-chevron-right',
				'condition'   => array(
					'info_box_layout'        => 'single_layout',
					'display_button'         => 'yes',
					'button_style!'          => array( 'style-7', 'style-9' ),
					'button_icon_font_style' => 'font_awesome',
				),
			)
		);
		$this->add_control(
			'button_icon_5',
			array(
				'label'     => esc_html__( 'Icon Library', 'theplus' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-plus',
					'library' => 'solid',
				),
				'condition' => array(
					'info_box_layout'        => 'single_layout',
					'display_button'         => 'yes',
					'button_style!'          => array( 'style-7', 'style-9' ),
					'button_icon_font_style' => 'font_awesome_5',
				),
			)
		);
		$this->add_control(
			'button_icons_mind',
			array(
				'label'       => esc_html__( 'Icon Library', 'theplus' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => '',
				'label_block' => true,
				'options'     => theplus_icons_mind(),
				'condition'   => array(
					'info_box_layout'        => 'single_layout',
					'display_button'         => 'yes',
					'button_style!'          => array( 'style-7', 'style-9' ),
					'button_icon_font_style' => 'icon_mind',
				),
			)
		);
		$this->add_control(
			'before_after',
			array(
				'label'     => esc_html__( 'Icon Position', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'after'  => esc_html__( 'After', 'theplus' ),
					'before' => esc_html__( 'Before', 'theplus' ),
				),
				'condition' => array(
					'info_box_layout'         => 'single_layout',
					'display_button'          => 'yes',
					'button_style!'           => array( 'style-7', 'style-9' ),
					'button_icon_font_style!' => '',
				),
			)
		);
		$this->add_control(
			'icon_spacing',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'theplus' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'condition' => array(
					'info_box_layout'         => 'single_layout',
					'display_button'          => 'yes',
					'button_style!'           => array( 'style-7', 'style-9' ),
					'button_icon_font_style!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .button-link-wrap i.button-after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .button-link-wrap i.button-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_img_styling',
			array(
				'label'     => esc_html__( 'Front Image Icon', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'info_box_layout'  => 'carousel_layout',
					'loop_select_icon' => 'image',
				),
			)
		);
		$this->add_responsive_control(
			'img_max_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Max Width', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .service-flipbox .service-flipbox-front .ts-icon-img.icon-img-b img' => 'margin: 0 auto;max-width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'loop_img_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .service-flipbox .service-flipbox-front .ts-icon-img.icon-img-b img',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'loop_img_br',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .service-flipbox .service-flipbox-front .ts-icon-img.icon-img-b img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'loop_img_shadow',
				'label'    => esc_html__( 'Box Shadow', 'theplus' ),
				'selector' => '{{WRAPPER}} .service-flipbox .service-flipbox-front .ts-icon-img.icon-img-b img',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_svg_styling',
			array(
				'label'      => esc_html__( 'Front Svg Icon', 'theplus' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'loop_select_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
						array(
							'name'     => 'image_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
					),
				),
			)
		);
		$this->add_control(
			'svg_type',
			array(
				'label'      => esc_html__( 'Select Style Image', 'theplus' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'delayed',
				'options'    => theplus_svg_type(),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'loop_select_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
						array(
							'name'     => 'image_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
					),
				),
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
						'max'  => 300,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'loop_select_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
						array(
							'name'     => 'image_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
					),
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
						'max'  => 1000,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'condition'  => array(
					'image_icon' => 'svg',
					'svg_icon'   => array( 'svg', 'img' ),
				),
			)
		);
		$this->add_control(
			'border_stroke_color',
			array(
				'label'      => esc_html__( 'Border/Stoke Color', 'theplus' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#ff0000',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'loop_select_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
						array(
							'name'     => 'image_icon',
							'operator' => '==',
							'value'    => 'svg',
						),
					),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_styling',
			array(
				'label'      => esc_html__( 'Front Icon', 'theplus' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'loop_select_icon',
							'operator' => '==',
							'value'    => 'icon',
						),
						array(
							'name'     => 'image_icon',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			)
		);
		$this->add_control(
			'icon_style',
			array(
				'label'   => esc_html__( 'Icon Styles', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'square',
				'options' => array(
					''              => esc_html__( 'None', 'theplus' ),
					'square'        => esc_html__( 'Square', 'theplus' ),
					'rounded'       => esc_html__( 'Rounded', 'theplus' ),
					'hexagon'       => esc_html__( 'Hexagon', 'theplus' ),
					'pentagon'      => esc_html__( 'Pentagon', 'theplus' ),
					'square-rotate' => esc_html__( 'Square Rotate', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'icon_size',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Icon Size', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 25,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon svg' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		$this->add_control(
			'icon_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Icon Width', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 250,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 50,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;line-height: {{SIZE}}{{UNIT}} !important;text-align: center;',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_icon_style' );
		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);
		$this->add_control(
			'icon_color_option',
			array(
				'label'       => esc_html__( 'Icon Color', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'solid'    => array(
						'title' => esc_html__( 'Classic', 'theplus' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'theplus' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'default'     => 'solid',
				'label_block' => false,
			)
		);
		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon:before,
					{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon i:before' => 'color: {{VALUE}};background: transparent;-webkit-background-clip: unset;-webkit-text-fill-color: initial;',
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon:before,
					{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon svg' => 'fill: {{VALUE}};background: transparent;-webkit-background-clip: unset;-webkit-text-fill-color: initial;',
				),
				'condition' => array(
					'icon_color_option' => 'solid',
				),
				'separator' => 'after',
			)
		);
		$this->add_control(
			'icon_gradient_color1',
			array(
				'label'     => esc_html__( 'Color 1', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'orange',
				'condition' => array(
					'icon_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'icon_gradient_color1_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 1 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'icon_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'icon_gradient_color2',
			array(
				'label'     => esc_html__( 'Color 2', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'cyan',
				'condition' => array(
					'icon_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'icon_gradient_color2_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 2 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'icon_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'icon_gradient_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Gradient Style', 'theplus' ),
				'default'   => 'linear',
				'options'   => theplus_get_gradient_styles(),
				'condition' => array(
					'icon_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'icon_gradient_angle',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Gradient Angle', 'theplus' ),
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range'      => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon:before,
					{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon i:before' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{icon_gradient_color1.VALUE}} {{icon_gradient_color1_control.SIZE}}{{icon_gradient_color1_control.UNIT}}, {{icon_gradient_color2.VALUE}} {{icon_gradient_color2_control.SIZE}}{{icon_gradient_color2_control.UNIT}});-webkit-transition: all 0.3s linear;-moz-transition: all 0.3s linear;-o-transition: all 0.3s linear;-ms-transition: all 0.3s linear;transition: all 0.3s linear;',
				),
				'condition'  => array(
					'icon_color_option'   => 'gradient',
					'icon_gradient_style' => array( 'linear' ),
				),
				'of_type'    => 'gradient',
				'separator'  => 'after',
			)
		);
		$this->add_control(
			'icon_gradient_position',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Position', 'theplus' ),
				'options'   => theplus_get_position_options(),
				'default'   => 'center center',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon:before,
					{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon i:before' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{icon_gradient_color1.VALUE}} {{icon_gradient_color1_control.SIZE}}{{icon_gradient_color1_control.UNIT}}, {{icon_gradient_color2.VALUE}} {{icon_gradient_color2_control.SIZE}}{{icon_gradient_color2_control.UNIT}});-webkit-transition: all 0.3s linear;-moz-transition: all 0.3s linear;-o-transition: all 0.3s linear;-ms-transition: all 0.3s linear;transition: all 0.3s linear;',
				),
				'condition' => array(
					'icon_color_option'   => 'gradient',
					'icon_gradient_style' => 'radial',
				),
				'of_type'   => 'gradient',
				'separator' => 'after',

			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'icon_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'icon_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon' => 'border-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_control(
			'icon_hover_color_option',
			array(
				'label'       => esc_html__( 'Icon Hover Color', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'solid'    => array(
						'title' => esc_html__( 'Classic', 'theplus' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'theplus' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'default'     => 'solid',
				'label_block' => false,
			)
		);
		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-icon:before,
					{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon i:before' => 'color: {{VALUE}};background: transparent;-webkit-background-clip: unset;-webkit-text-fill-color: initial;',
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-icon svg' => 'fill: {{VALUE}};background: transparent;-webkit-background-clip: unset;-webkit-text-fill-color: initial;',
				),
				'condition' => array(
					'icon_hover_color_option' => 'solid',
				),
				'separator' => 'after',
			)
		);
		$this->add_control(
			'icon_hover_gradient_color1',
			array(
				'label'     => esc_html__( 'Color 1', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'orange',
				'condition' => array(
					'icon_hover_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'icon_hover_gradient_color1_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 1 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'icon_hover_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'icon_hover_gradient_color2',
			array(
				'label'     => esc_html__( 'Color 2', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'cyan',
				'condition' => array(
					'icon_hover_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'icon_hover_gradient_color2_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 2 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'icon_hover_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'icon_hover_gradient_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Gradient Style', 'theplus' ),
				'default'   => 'linear',
				'options'   => theplus_get_gradient_styles(),
				'condition' => array(
					'icon_hover_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'icon_hover_gradient_angle',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Gradient Angle', 'theplus' ),
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range'      => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-icon:before,
					{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon i:before' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{icon_hover_gradient_color1.VALUE}} {{icon_hover_gradient_color1_control.SIZE}}{{icon_hover_gradient_color1_control.UNIT}}, {{icon_hover_gradient_color2.VALUE}} {{icon_hover_gradient_color2_control.SIZE}}{{icon_hover_gradient_color2_control.UNIT}})',
				),
				'condition'  => array(
					'icon_hover_color_option'   => 'gradient',
					'icon_hover_gradient_style' => array( 'linear' ),
				),
				'of_type'    => 'gradient',
				'separator'  => 'after',
			)
		);
		$this->add_control(
			'icon_hover_gradient_position',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Position', 'theplus' ),
				'options'   => theplus_get_position_options(),
				'default'   => 'center center',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-icon:before,
					{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-icon i:before' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{icon_hover_gradient_color1.VALUE}} {{icon_hover_gradient_color1_control.SIZE}}{{icon_hover_gradient_color1_control.UNIT}}, {{icon_hover_gradient_color2.VALUE}} {{icon_hover_gradient_color2_control.SIZE}}{{icon_hover_gradient_color2_control.UNIT}})',
				),
				'condition' => array(
					'icon_hover_color_option'   => 'gradient',
					'icon_hover_gradient_style' => 'radial',
				),
				'of_type'   => 'gradient',
				'separator' => 'after',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'icon_hover_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-icon',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'icon_border_hover_color',
			array(
				'label'     => esc_html__( 'Hover Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-icon' => 'border-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_styling',
			array(
				'label' => esc_html__( 'Front Title', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'selector' => '{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-title',
			)
		);
		$this->start_controls_tabs( 'tabs_title_style' );
		$this->start_controls_tab(
			'tab_title_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);
		$this->add_control(
			'title_color_option',
			array(
				'label'       => esc_html__( 'Title Color', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'solid'    => array(
						'title' => esc_html__( 'Classic', 'theplus' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'theplus' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'default'     => 'solid',
				'label_block' => false,
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#313131',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'title_color_option' => 'solid',
				),
			)
		);
		$this->add_control(
			'title_gradient_color1',
			array(
				'label'     => esc_html__( 'Color 1', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'orange',
				'condition' => array(
					'title_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'title_gradient_color1_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 1 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'title_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'title_gradient_color2',
			array(
				'label'     => esc_html__( 'Color 2', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'cyan',
				'condition' => array(
					'title_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'title_gradient_color2_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 2 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'title_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'title_gradient_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Gradient Style', 'theplus' ),
				'default'   => 'linear',
				'options'   => theplus_get_gradient_styles(),
				'condition' => array(
					'title_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'title_gradient_angle',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Gradient Angle', 'theplus' ),
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range'      => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-title' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{title_gradient_color1.VALUE}} {{title_gradient_color1_control.SIZE}}{{title_gradient_color1_control.UNIT}}, {{title_gradient_color2.VALUE}} {{title_gradient_color2_control.SIZE}}{{title_gradient_color2_control.UNIT}})',
				),
				'condition'  => array(
					'title_color_option'   => 'gradient',
					'title_gradient_style' => array( 'linear' ),
				),
				'of_type'    => 'gradient',
			)
		);
		$this->add_control(
			'title_gradient_position',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Position', 'theplus' ),
				'options'   => theplus_get_position_options(),
				'default'   => 'center center',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-title' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{title_gradient_color1.VALUE}} {{title_gradient_color1_control.SIZE}}{{title_gradient_color1_control.UNIT}}, {{title_gradient_color2.VALUE}} {{title_gradient_color2_control.SIZE}}{{title_gradient_color2_control.UNIT}})',
				),
				'condition' => array(
					'title_color_option'   => 'gradient',
					'title_gradient_style' => 'radial',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_title_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_control(
			'title_hover_color_option',
			array(
				'label'       => esc_html__( 'Title Hover Color', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'solid'    => array(
						'title' => esc_html__( 'Classic', 'theplus' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'theplus' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'default'     => 'solid',
				'label_block' => false,
			)
		);
		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3351a6',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'title_hover_color_option' => 'solid',
				),
			)
		);
		$this->add_control(
			'title_hover_gradient_color1',
			array(
				'label'     => esc_html__( 'Color 1', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'orange',
				'condition' => array(
					'title_hover_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'title_hover_gradient_color1_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 1 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'title_hover_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'title_hover_gradient_color2',
			array(
				'label'     => esc_html__( 'Color 2', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'cyan',
				'condition' => array(
					'title_hover_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'title_hover_gradient_color2_control',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Color 2 Location', 'theplus' ),
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'title_hover_color_option' => 'gradient',
				),
				'of_type'     => 'gradient',
			)
		);
		$this->add_control(
			'title_hover_gradient_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Gradient Style', 'theplus' ),
				'default'   => 'linear',
				'options'   => theplus_get_gradient_styles(),
				'condition' => array(
					'title_hover_color_option' => 'gradient',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->add_control(
			'title_hover_gradient_angle',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Gradient Angle', 'theplus' ),
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range'      => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-title' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{title_hover_gradient_color1.VALUE}} {{title_hover_gradient_color1_control.SIZE}}{{title_hover_gradient_color1_control.UNIT}}, {{title_hover_gradient_color2.VALUE}} {{title_hover_gradient_color2_control.SIZE}}{{title_hover_gradient_color2_control.UNIT}})',
				),
				'condition'  => array(
					'title_hover_color_option'   => 'gradient',
					'title_hover_gradient_style' => array( 'linear' ),
				),
				'of_type'    => 'gradient',
			)
		);
		$this->add_control(
			'title_hover_gradient_position',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Position', 'theplus' ),
				'options'   => theplus_get_position_options(),
				'default'   => 'center center',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-title' => 'background-color: transparent;-webkit-background-clip: text;-webkit-text-fill-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{title_hover_gradient_color1.VALUE}} {{title_hover_gradient_color1_control.SIZE}}{{title_hover_gradient_color1_control.UNIT}}, {{title_hover_gradient_color2.VALUE}} {{title_hover_gradient_color2_control.SIZE}}{{title_hover_gradient_color2_control.UNIT}})',
				),
				'condition' => array(
					'title_hover_color_option'   => 'gradient',
					'title_hover_gradient_style' => 'radial',
				),
				'of_type'   => 'gradient',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'title_top_space',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Title Top Space', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'step' => 2,
						'min'  => -150,
						'max'  => 150,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'render_type' => 'ui',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box.info-box-style_5 .info-box-inner .service-title' => 'margin-top : {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'title_btm_space',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Title Bottom Space', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'step' => 2,
						'min'  => -150,
						'max'  => 150,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box.info-box-style_5 .info-box-inner .service-title' => 'margin-bottom : {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc_styling',
			array(
				'label' => esc_html__( 'Back Description', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'selector' => '{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-desc,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-desc p',
			)
		);
		$this->add_control(
			'desc_hover_color',
			array(
				'label'     => esc_html__( 'Desc Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-desc,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-desc p' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_styling',
			array(
				'label'      => esc_html__( 'Back Button', 'theplus' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'display_button',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'loop_display_button',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);
		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => '15',
					'right'    => '30',
					'bottom'   => '15',
					'left'     => '30',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_button .button-link-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .pt_plus_button .button-link-wrap',
			)
		);
		$this->add_responsive_control(
			'button_iconSize',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Icon Size', 'theplus' ),
				'size_units'  => array( 'px' ),

				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_button .button-link-wrap i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pt_plus_button .button-link-wrap svg' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_button_style' );
		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);

		$this->add_control(
			'btn_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_button .button-link-wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pt_plus_button .button-link-wrap svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .pt_plus_button.button-style-7 .button-link-wrap:after' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'button_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap',
				'condition' => array(
					'button_style' => 'style-8',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'loop_button_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap',
				'condition' => array(
					'loop_button_style' => 'style-8',
				),
			)
		);
		$this->add_control(
			'button_border_style',
			array(
				'label'      => esc_html__( 'Border Style', 'theplus' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'solid',
				'options'    => array(
					'none'   => esc_html__( 'None', 'theplus' ),
					'solid'  => esc_html__( 'Solid', 'theplus' ),
					'dotted' => esc_html__( 'Dotted', 'theplus' ),
					'dashed' => esc_html__( 'Dashed', 'theplus' ),
					'groove' => esc_html__( 'Groove', 'theplus' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap' => 'border-style: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
						array(
							'name'     => 'loop_button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
					),
				),
			)
		);
		$this->add_responsive_control(
			'button_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
						array(
							'name'     => 'loop_button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
					),
				),
			)
		);
		$this->add_control(
			'button_border_color',
			array(
				'label'      => esc_html__( 'Border Color', 'theplus' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#313131',
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap' => 'border-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
						array(
							'name'     => 'loop_button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
					),
				),
				'separator'  => 'after',
			)
		);
		$this->add_responsive_control(
			'button_border_color_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
						array(
							'name'     => 'loop_button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_shadow',
				'selector'  => '{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap',
				'condition' => array(
					'button_style' => 'style-8',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_control(
			'btn_text_hover_color',
			array(
				'label'     => esc_html__( 'Text Hover Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_button .button-link-wrap:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pt_plus_button .button-link-wrap:hover svg' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'button_hover_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap:hover',
				'separator' => 'after',
				'condition' => array(
					'button_style' => 'style-8',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'loop_button_hover_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap',
				'condition' => array(
					'loop_button_style' => 'style-8',
				),
			)
		);
		$this->add_control(
			'button_border_hover_color',
			array(
				'label'      => esc_html__( 'Hover Border Color', 'theplus' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#313131',
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap:hover' => 'border-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
						array(
							'name'     => 'loop_button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
					),
				),
				'separator'  => 'after',
			)
		);
		$this->add_responsive_control(
			'button_border_hover_color_radius',
			array(
				'label'      => esc_html__( 'Hover Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
						array(
							'name'     => 'loop_button_style',
							'operator' => '==',
							'value'    => 'style-8',
						),
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_hover_shadow',
				'selector'  => '{{WRAPPER}} .pt_plus_button.button-style-8 .button-link-wrap:hover',
				'condition' => array(
					'button_style' => 'style-8',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_bg_option_styling',
			array(
				'label' => esc_html__( 'Background Options', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'box_border',
			array(
				'label'     => esc_html__( 'Box Border', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'box_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#252525',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-front,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-back' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'box_border' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'box_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-front,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-back' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'box_border' => 'yes',
				),
			)
		);
		$this->add_control(
			'box_border_style',
			array(
				'label'     => esc_html__( 'Border Style', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => theplus_get_border_style(),
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-front,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-back' => 'border-style: {{VALUE}};',
				),
				'condition' => array(
					'box_border' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-front,{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-back,{{WRAPPER}} .pt_plus_info_box .infobox-front-overlay,{{WRAPPER}} .pt_plus_info_box .infobox-back-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_background_style' );
		$this->start_controls_tab(
			'tab_background_front',
			array(
				'label'     => esc_html__( 'Front', 'theplus' ),
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'box_front_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-front',
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'box_front_overlay_bg_color',
			array(
				'label'     => esc_html__( 'Overlay Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .infobox-front-overlay' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_background_back',
			array(
				'label'     => esc_html__( 'Back', 'theplus' ),
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'box_back_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-back',
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->add_control(
			'box_back_overlay_bg_color',
			array(
				'label'     => esc_html__( 'Overlay Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .infobox-back-overlay' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'info_box_layout' => 'single_layout',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'shadow_options',
			array(
				'label'     => esc_html__( 'Box Shadow Options', 'theplus' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->start_controls_tabs( 'tabs_shadow_style' );
		$this->start_controls_tab(
			'tab_shadow_front',
			array(
				'label' => esc_html__( 'Front', 'theplus' ),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_info_box .info-box-inner .service-flipbox-front',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_shadow_back',
			array(
				'label' => esc_html__( 'Back', 'theplus' ),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_hover_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_info_box .info-box-inner:hover .service-flipbox-back',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_carousel_options_styling',
			array(
				'label'     => esc_html__( 'Carousel Options', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'info_box_layout' => 'carousel_layout',
				),
			)
		);
		$this->add_control(
			'carousel_unique_id',
			array(
				'label'     => wp_kses_post( "Unique Carousel ID <a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "elementor-flip-box-with-carousel-remote/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>" ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'separator'   => 'after',
				'description' => esc_html__( 'Keep this blank or Setup Unique id for carousel which you can use with "Carousel Remote" widget.', 'theplus' ),
			)
		);
		$this->add_control(
			'slider_direction',
			array(
				'label'   => esc_html__( 'Slider Mode', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'theplus' ),
					'vertical'   => esc_html__( 'Vertical', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'carousel_direction',
			array(
				'label'   => esc_html__( 'Slide Direction', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => array(
					'rtl' => esc_html__( 'Right to Left', 'theplus' ),
					'ltr' => esc_html__( 'Left to Right', 'theplus' ),
				),
			)
		);
		$this->add_responsive_control(
			'carousel_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box.list-carousel-slick' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'slide_speed',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Slide Speed', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 1500,
				),
			)
		);
		$this->start_controls_tabs( 'tabs_carousel_style' );
		$this->start_controls_tab(
			'tab_carousel_desktop',
			array(
				'label' => esc_html__( 'Desktop', 'theplus' ),
			)
		);
		$this->add_control(
			'slider_desktop_column',
			array(
				'label'   => esc_html__( 'Desktop Columns', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => theplus_carousel_desktop_columns(),
			)
		);
		$this->add_control(
			'steps_slide',
			array(
				'label'       => esc_html__( 'Next Previous', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '1',
				'description' => esc_html__( 'Select option of column scroll on previous or next in carousel.', 'theplus' ),
				'options'     => array(
					'1' => esc_html__( 'One Column', 'theplus' ),
					'2' => esc_html__( 'All Visible Columns', 'theplus' ),
				),
			)
		);
		$this->add_responsive_control(
			'slider_padding',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Slide Padding', 'theplus' ),
				'size_units'  => array( 'px' ),

				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 15,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .list-carousel-slick:not(.multi-row) .slick-initialized .slick-slide' => 'margin: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .list-carousel-slick.multi-row .slick-initialized .slick-slide' => 'margin: 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .list-carousel-slick.multi-row .slick-initialized .slick-slide > div' => 'margin: {{SIZE}}{{UNIT}} 0',
				),
			)
		);
		$this->add_control(
			'slider_draggable',
			array(
				'label'     => esc_html__( 'Draggable', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
			)
		);
		$this->add_control(
			'slider_infinite',
			array(
				'label'     => esc_html__( 'Infinite Mode', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
			)
		);
		$this->add_control(
			'slider_pause_hover',
			array(
				'label'     => esc_html__( 'Pause On Hover', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'slider_adaptive_height',
			array(
				'label'     => esc_html__( 'Adaptive Height', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'slider_animation',
			array(
				'label'   => esc_html__( 'Animation Type', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => array(
					'ease'   => esc_html__( 'With Hold', 'theplus' ),
					'linear' => esc_html__( 'Continuous', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'slider_autoplay',
			array(
				'label'     => esc_html__( 'Autoplay', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
			)
		);
		$this->add_control(
			'autoplay_speed',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Autoplay Speed', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 500,
						'max'  => 10000,
						'step' => 200,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 3000,
				),
				'condition'  => array(
					'slider_autoplay' => 'yes',
				),
			)
		);
		$this->add_control(
			'slider_dots',
			array(
				'label'     => esc_html__( 'Show Dots', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'slider_dots_style',
			array(
				'label'     => esc_html__( 'Dots Style', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'style-1',
				'options'   => array(
					'style-1' => esc_html__( 'Style 1', 'theplus' ),
					'style-2' => esc_html__( 'Style 2', 'theplus' ),
					'style-3' => esc_html__( 'Style 3', 'theplus' ),
					'style-4' => esc_html__( 'Style 4', 'theplus' ),
					'style-5' => esc_html__( 'Style 5', 'theplus' ),
					'style-6' => esc_html__( 'Style 6', 'theplus' ),
					'style-7' => esc_html__( 'Style 7', 'theplus' ),
				),
				'condition' => array(
					'slider_dots' => array( 'yes' ),
				),
			)
		);
		$this->add_control(
			'dots_border_color',
			array(
				'label'     => esc_html__( 'Dots Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#252525',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-1 li button,{{WRAPPER}} .list-carousel-slick .slick-dots.style-6 li button' => '-webkit-box-shadow:inset 0 0 0 8px {{VALUE}};-moz-box-shadow: inset 0 0 0 8px {{VALUE}};box-shadow: inset 0 0 0 8px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-1 li.slick-active button' => '-webkit-box-shadow:inset 0 0 0 1px {{VALUE}};-moz-box-shadow: inset 0 0 0 1px {{VALUE}};box-shadow: inset 0 0 0 1px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-2 li button' => 'border-color:{{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick ul.slick-dots.style-3 li button' => '-webkit-box-shadow: inset 0 0 0 1px {{VALUE}};-moz-box-shadow: inset 0 0 0 1px {{VALUE}};box-shadow: inset 0 0 0 1px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-3 li.slick-active button' => '-webkit-box-shadow: inset 0 0 0 8px {{VALUE}};-moz-box-shadow: inset 0 0 0 8px {{VALUE}};box-shadow: inset 0 0 0 8px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick ul.slick-dots.style-4 li button' => '-webkit-box-shadow: inset 0 0 0 0px {{VALUE}};-moz-box-shadow: inset 0 0 0 0px {{VALUE}};box-shadow: inset 0 0 0 0px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-1 li button:before' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'slider_dots_style' => array( 'style-1', 'style-2', 'style-3', 'style-5' ),
					'slider_dots'       => 'yes',
				),
			)
		);
		$this->add_control(
			'dots_bg_color',
			array(
				'label'     => esc_html__( 'Dots Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-2 li button,{{WRAPPER}} .list-carousel-slick ul.slick-dots.style-3 li button,{{WRAPPER}} .list-carousel-slick .slick-dots.style-4 li button:before,{{WRAPPER}} .list-carousel-slick .slick-dots.style-5 button,{{WRAPPER}} .list-carousel-slick .slick-dots.style-7 button' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'slider_dots_style' => array( 'style-2', 'style-3', 'style-4', 'style-5', 'style-7' ),
					'slider_dots'       => 'yes',
				),
			)
		);
		$this->add_control(
			'dots_active_border_color',
			array(
				'label'     => esc_html__( 'Dots Active Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-2 li::after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-4 li.slick-active button' => '-webkit-box-shadow: inset 0 0 0 1px {{VALUE}};-moz-box-shadow: inset 0 0 0 1px {{VALUE}};box-shadow: inset 0 0 0 1px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-6 .slick-active button:after' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'slider_dots_style' => array( 'style-2', 'style-4', 'style-6' ),
					'slider_dots'       => 'yes',
				),
			)
		);
		$this->add_control(
			'dots_active_bg_color',
			array(
				'label'     => esc_html__( 'Dots Active Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-2 li::after,{{WRAPPER}} .list-carousel-slick .slick-dots.style-4 li.slick-active button:before,{{WRAPPER}} .list-carousel-slick .slick-dots.style-5 .slick-active button,{{WRAPPER}} .list-carousel-slick .slick-dots.style-7 .slick-active button' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'slider_dots_style' => array( 'style-2', 'style-4', 'style-5', 'style-7' ),
					'slider_dots'       => 'yes',
				),
			)
		);
		$this->add_control(
			'dots_top_padding',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Dots Top Padding', 'theplus' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} .list-carousel-slick .slick-slider.slick-dotted' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'slider_dots' => 'yes',
				),
			)
		);
		$this->add_control(
			'hover_show_dots',
			array(
				'label'     => esc_html__( 'On Hover Dots', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'slider_dots' => 'yes',
				),
			)
		);
		$this->add_control(
			'slider_arrows',
			array(
				'label'     => esc_html__( 'Show Arrows', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'slider_arrows_style',
			array(
				'label'     => esc_html__( 'Arrows Style', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'style-1',
				'options'   => array(
					'style-1' => esc_html__( 'Style 1', 'theplus' ),
					'style-2' => esc_html__( 'Style 2', 'theplus' ),
					'style-3' => esc_html__( 'Style 3', 'theplus' ),
					'style-4' => esc_html__( 'Style 4', 'theplus' ),
					'style-5' => esc_html__( 'Style 5', 'theplus' ),
					'style-6' => esc_html__( 'Style 6', 'theplus' ),
				),
				'condition' => array(
					'slider_arrows' => array( 'yes' ),
				),
			)
		);
		$this->add_control(
			'arrows_position',
			array(
				'label'     => esc_html__( 'Arrows Style', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top-right',
				'options'   => array(
					'top-right'     => esc_html__( 'Top-Right', 'theplus' ),
					'bottm-left'    => esc_html__( 'Bottom-Left', 'theplus' ),
					'bottom-center' => esc_html__( 'Bottom-Center', 'theplus' ),
					'bottom-right'  => esc_html__( 'Bottom-Right', 'theplus' ),
				),
				'condition' => array(
					'slider_arrows'       => array( 'yes' ),
					'slider_arrows_style' => array( 'style-3', 'style-4' ),
				),
			)
		);
		$this->add_control(
			'arrow_bg_color',
			array(
				'label'     => esc_html__( 'Arrow Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c44d48',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-nav.slick-prev.style-1,{{WRAPPER}} .list-carousel-slick .slick-nav.slick-next.style-1,{{WRAPPER}} .list-carousel-slick .slick-nav.style-3:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-3:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-6:before,{{WRAPPER}} .list-carousel-slick .slick-next.style-6:before' => 'background: {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-prev.style-4:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-4:before' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'slider_arrows_style' => array( 'style-1', 'style-3', 'style-4', 'style-6' ),
					'slider_arrows'       => 'yes',
				),
			)
		);
		$this->add_control(
			'arrow_icon_color',
			array(
				'label'     => esc_html__( 'Arrow Icon Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-nav.slick-prev.style-1:before,{{WRAPPER}} .list-carousel-slick .slick-nav.slick-next.style-1:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-3:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-3:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-4:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-4:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-6 .icon-wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-prev.style-2 .icon-wrap:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-2 .icon-wrap:after,{{WRAPPER}} .list-carousel-slick .slick-next.style-2 .icon-wrap:before,{{WRAPPER}} .list-carousel-slick .slick-next.style-2 .icon-wrap:after,{{WRAPPER}} .list-carousel-slick .slick-prev.style-5 .icon-wrap:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-5 .icon-wrap:after,{{WRAPPER}} .list-carousel-slick .slick-next.style-5 .icon-wrap:before,{{WRAPPER}} .list-carousel-slick .slick-next.style-5 .icon-wrap:after' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'slider_arrows_style' => array( 'style-1', 'style-2', 'style-3', 'style-4', 'style-5', 'style-6' ),
					'slider_arrows'       => 'yes',
				),
			)
		);
		$this->add_control(
			'arrow_hover_bg_color',
			array(
				'label'     => esc_html__( 'Arrow Hover Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-nav.slick-prev.style-1:hover,{{WRAPPER}} .list-carousel-slick .slick-nav.slick-next.style-1:hover,{{WRAPPER}} .list-carousel-slick .slick-prev.style-2:hover::before,{{WRAPPER}} .list-carousel-slick .slick-next.style-2:hover::before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-3:hover:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-3:hover:before' => 'background: {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-prev.style-4:hover:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-4:hover:before' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'slider_arrows_style' => array( 'style-1', 'style-2', 'style-3', 'style-4' ),
					'slider_arrows'       => 'yes',
				),
			)
		);
		$this->add_control(
			'arrow_hover_icon_color',
			array(
				'label'     => esc_html__( 'Arrow Hover Icon Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c44d48',
				'selectors' => array(
					'{{WRAPPER}} .list-carousel-slick .slick-nav.slick-prev.style-1:hover:before,{{WRAPPER}} .list-carousel-slick .slick-nav.slick-next.style-1:hover:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-3:hover:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-3:hover:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-4:hover:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-4:hover:before,{{WRAPPER}} .list-carousel-slick .slick-nav.style-6:hover .icon-wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-prev.style-2:hover .icon-wrap::before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-2:hover .icon-wrap::after,{{WRAPPER}} .list-carousel-slick .slick-next.style-2:hover .icon-wrap::before,{{WRAPPER}} .list-carousel-slick .slick-next.style-2:hover .icon-wrap::after,{{WRAPPER}} .list-carousel-slick .slick-prev.style-5:hover .icon-wrap::before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-5:hover .icon-wrap::after,{{WRAPPER}} .list-carousel-slick .slick-next.style-5:hover .icon-wrap::before,{{WRAPPER}} .list-carousel-slick .slick-next.style-5:hover .icon-wrap::after' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'slider_arrows_style' => array( 'style-1', 'style-2', 'style-3', 'style-4', 'style-5', 'style-6' ),
					'slider_arrows'       => 'yes',
				),
			)
		);
		$this->add_control(
			'outer_section_arrow',
			array(
				'label'     => esc_html__( 'Outer Content Arrow', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'slider_arrows'       => 'yes',
					'slider_arrows_style' => array( 'style-1', 'style-2', 'style-5', 'style-6' ),
				),
			)
		);
		$this->add_control(
			'hover_show_arrow',
			array(
				'label'     => esc_html__( 'On Hover Arrow', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'slider_arrows' => 'yes',
				),
			)
		);
		$this->add_control(
			'slider_center_mode',
			array(
				'label'     => esc_html__( 'Center Mode', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'center_padding',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Center Padding', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 0,
				),
				'condition'  => array(
					'slider_center_mode' => array( 'yes' ),
				),
			)
		);
		$this->add_control(
			'slider_center_effects',
			array(
				'label'     => esc_html__( 'Center Slide Effects', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => theplus_carousel_center_effects(),
				'condition' => array(
					'slider_center_mode' => array( 'yes' ),
				),
			)
		);
		$this->add_control(
			'scale_center_slide',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Center Slide Scale', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0.3,
						'max'  => 2,
						'step' => 0.02,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .list-carousel-slick .slick-slide.slick-current.slick-active.slick-center,
					{{WRAPPER}} .list-carousel-slick .slick-slide.scc-animate' => '-webkit-transform: scale({{SIZE}});-moz-transform:    scale({{SIZE}});-ms-transform:     scale({{SIZE}});-o-transform:      scale({{SIZE}});transform:scale({{SIZE}});opacity:1;',
				),
				'condition'  => array(
					'slider_center_mode'    => 'yes',
					'slider_center_effects' => 'scale',
				),
			)
		);
		$this->add_control(
			'scale_normal_slide',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Normal Slide Scale', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0.3,
						'max'  => 2,
						'step' => 0.02,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 0.8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .list-carousel-slick .slick-slide' => '-webkit-transform: scale({{SIZE}});-moz-transform:    scale({{SIZE}});-ms-transform:     scale({{SIZE}});-o-transform:      scale({{SIZE}});transform:scale({{SIZE}});',
				),
				'condition'  => array(
					'slider_center_mode'    => 'yes',
					'slider_center_effects' => 'scale',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'shadow_active_slide',
				'selector'  => '{{WRAPPER}} .list-carousel-slick .slick-slide.slick-current.slick-active.slick-center .info-box-bg-box',
				'condition' => array(
					'slider_center_mode'    => 'yes',
					'slider_center_effects' => 'shadow',
				),
			)
		);
		$this->add_control(
			'opacity_normal_slide',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Normal Slide Opacity', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0.1,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 0.7,
				),
				'selectors'  => array(
					'{{WRAPPER}} .list-carousel-slick .slick-slide' => 'opacity:{{SIZE}}',
				),
				'condition'  => array(
					'slider_center_mode'     => 'yes',
					'slider_center_effects!' => 'none',
				),
			)
		);
		$this->add_control(
			'slider_rows',
			array(
				'label'     => esc_html__( 'Number Of Rows', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1' => esc_html__( '1 Row', 'theplus' ),
					'2' => esc_html__( '2 Rows', 'theplus' ),
					'3' => esc_html__( '3 Rows', 'theplus' ),
				),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'slide_row_top_space',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Row Top Space', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} .list-carousel-slick[data-slider_rows="2"] .slick-slide > div:last-child,{{WRAPPER}} .list-carousel-slick[data-slider_rows="3"] .slick-slide > div:nth-last-child(-n+2)' => 'padding-top:{{SIZE}}px',
				),
				'condition'  => array(
					'slider_rows' => array( '2', '3' ),
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_carousel_tablet',
			array(
				'label' => esc_html__( 'Tablet', 'theplus' ),
			)
		);
		$this->add_control(
			'slider_tablet_column',
			array(
				'label'   => esc_html__( 'Tablet Columns', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => theplus_carousel_tablet_columns(),
			)
		);
		$this->add_control(
			'tablet_steps_slide',
			array(
				'label'       => esc_html__( 'Next Previous', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '1',
				'description' => esc_html__( 'Select option of column scroll on previous or next in carousel.', 'theplus' ),
				'options'     => array(
					'1' => esc_html__( 'One Column', 'theplus' ),
					'2' => esc_html__( 'All Visible Columns', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'slider_responsive_tablet',
			array(
				'label'     => esc_html__( 'Responsive Tablet', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'tablet_slider_draggable',
			array(
				'label'     => esc_html__( 'Draggable', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_tablet' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_slider_infinite',
			array(
				'label'     => esc_html__( 'Infinite Mode', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_tablet' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_slider_autoplay',
			array(
				'label'     => esc_html__( 'Autoplay', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_tablet' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_autoplay_speed',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Autoplay Speed', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 500,
						'max'  => 10000,
						'step' => 200,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 1500,
				),
				'condition'  => array(
					'slider_responsive_tablet' => 'yes',
					'tablet_slider_autoplay'   => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_slider_dots',
			array(
				'label'     => esc_html__( 'Show Dots', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_tablet' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_slider_arrows',
			array(
				'label'     => esc_html__( 'Show Arrows', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'slider_responsive_tablet' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_slider_rows',
			array(
				'label'     => esc_html__( 'Number Of Rows', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1' => esc_html__( '1 Row', 'theplus' ),
					'2' => esc_html__( '2 Rows', 'theplus' ),
					'3' => esc_html__( '3 Rows', 'theplus' ),
				),
				'condition' => array(
					'slider_responsive_tablet' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_center_mode',
			array(
				'label'     => esc_html__( 'Center Mode', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'separator' => 'before',
				'condition' => array(
					'slider_responsive_tablet' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_center_padding',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Center Padding', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 0,
				),
				'condition'  => array(
					'slider_responsive_tablet' => 'yes',
					'tablet_center_mode'       => array( 'yes' ),
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_carousel_mobile',
			array(
				'label' => esc_html__( 'Mobile', 'theplus' ),
			)
		);
		$this->add_control(
			'slider_mobile_column',
			array(
				'label'   => esc_html__( 'Mobile Columns', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '2',
				'options' => theplus_carousel_mobile_columns(),
			)
		);
		$this->add_control(
			'mobile_steps_slide',
			array(
				'label'       => esc_html__( 'Next Previous', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '1',
				'description' => esc_html__( 'Select option of column scroll on previous or next in carousel.', 'theplus' ),
				'options'     => array(
					'1' => esc_html__( 'One Column', 'theplus' ),
					'2' => esc_html__( 'All Visible Columns', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'slider_responsive_mobile',
			array(
				'label'     => esc_html__( 'Responsive Mobile', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'mobile_slider_draggable',
			array(
				'label'     => esc_html__( 'Draggable', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_mobile' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_slider_infinite',
			array(
				'label'     => esc_html__( 'Infinite Mode', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_mobile' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_slider_autoplay',
			array(
				'label'     => esc_html__( 'Autoplay', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_mobile' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_autoplay_speed',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Autoplay Speed', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 500,
						'max'  => 10000,
						'step' => 200,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 1500,
				),
				'condition'  => array(
					'slider_responsive_mobile' => 'yes',
					'mobile_slider_autoplay'   => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_slider_dots',
			array(
				'label'     => esc_html__( 'Show Dots', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'slider_responsive_mobile' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_slider_arrows',
			array(
				'label'     => esc_html__( 'Show Arrows', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'slider_responsive_mobile' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_slider_rows',
			array(
				'label'     => esc_html__( 'Number Of Rows', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1' => esc_html__( '1 Row', 'theplus' ),
					'2' => esc_html__( '2 Rows', 'theplus' ),
					'3' => esc_html__( '3 Rows', 'theplus' ),
				),
				'condition' => array(
					'slider_responsive_mobile' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_center_mode',
			array(
				'label'     => esc_html__( 'Center Mode', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'separator' => 'before',
				'condition' => array(
					'slider_responsive_mobile' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_center_padding',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Center Padding', 'theplus' ),
				'size_units' => '',
				'range'      => array(
					'' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => '',
					'size' => 0,
				),
				'condition'  => array(
					'slider_responsive_mobile' => 'yes',
					'mobile_center_mode'       => array( 'yes' ),
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_extra_option_styling',
			array(
				'label' => esc_html__( 'Extra Options', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Box Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '15',
					'right'  => '15',
					'bottom' => '15',
					'left'   => '15',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_info_box .info-box-inner .info-box-bg-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'messy_column',
			array(
				'label'     => esc_html__( 'Messy Columns', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'info_box_layout' => 'carousel_layout',
				),
			)
		);
		$this->add_control(
			'messy_column_even',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Even Columns', 'theplus' ),
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array(
						'min'  => -250,
						'max'  => 250,
						'step' => 2,
					),
					'%'  => array(
						'min'  => 70,
						'max'  => 70,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box .slick-initialized .slick-slide.info-box-inner:nth-child(2n+1)' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'info_box_layout' => 'carousel_layout',
					'messy_column'    => array( 'yes' ),
				),
			)
		);
		$this->add_control(
			'messy_column_odd',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Odd Columns', 'theplus' ),
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array(
						'min'  => -250,
						'max'  => 250,
						'step' => 2,
					),
					'%'  => array(
						'min'  => 70,
						'max'  => 70,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 70,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_info_box .slick-initialized .slick-slide.info-box-inner:nth-child(2n+2)' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'info_box_layout' => 'carousel_layout',
					'messy_column'    => array( 'yes' ),
				),
			)
		);
		$this->add_control(
			'box_hover_effects',
			array(
				'label'     => esc_html__( 'Box Hover Effects', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => theplus_get_content_hover_effect_options(),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'box_hover_shadow_color',
			array(
				'label'     => esc_html__( 'Shadow Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.6)',
				'condition' => array(
					'box_hover_effects' => array( 'float_shadow', 'grow_shadow', 'shadow_radial' ),
				),
			)
		);
		$this->add_control(
			'box_hover_shadow_color_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note : Works in Frontend.', 'theplus' ),
				'content_classes' => 'tp-controller-notice',
				'condition'       => array(
					'box_hover_effects' => array( 'float_shadow', 'grow_shadow', 'shadow_radial' ),
				),
			)
		);
		$this->add_control(
			'responsive_visible_opt',
			array(
				'label'     => esc_html__( 'Responsive Visibility', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'desktop_opt',
			array(
				'label'     => esc_html__( 'Desktop', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'condition' => array(
					'responsive_visible_opt' => 'yes',
				),
			)
		);
		$this->add_control(
			'tablet_opt',
			array(
				'label'     => esc_html__( 'Tablet', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'condition' => array(
					'responsive_visible_opt' => 'yes',
				),
			)
		);
		$this->add_control(
			'mobile_opt',
			array(
				'label'     => esc_html__( 'Mobile', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'condition' => array(
					'responsive_visible_opt' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_plus_extra_adv',
			array(
				'label' => esc_html__( 'Plus Extras', 'theplus' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);
		$this->end_controls_section();

		/*--On Scroll View Animation ---*/
		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation.php';
		include THEPLUS_PATH . 'modules/widgets/theplus-needhelp.php';
	}

	/**
	 * Render Flip box
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$main_style        = 'style_5';
		$info_box_layout   = ! empty( $settings['info_box_layout'] ) ? $settings['info_box_layout'] : 'single_layout';
		$box_hover_effects = ! empty( $settings['box_hover_effects'] ) ? $settings['box_hover_effects'] : '';

		$hover_class  = '';
		$hover_attr   = '';
		$hover_uniqid = uniqid( 'hover-effect' );

		if ( 'float_shadow' === $box_hover_effects || 'grow_shadow' === $box_hover_effects || 'shadow_radial' === $box_hover_effects ) {
			$hover_attr .= 'data-hover_uniqid="' . esc_attr( $hover_uniqid ) . '" ';
			$hover_attr .= ' data-hover_shadow="' . esc_attr( $settings['box_hover_shadow_color'] ) . '" ';
			$hover_attr .= ' data-content_hover_effects="' . esc_attr( $settings['box_hover_effects'] ) . '" ';
		}

		if ( 'grow' === $box_hover_effects ) {
			$hover_class .= 'content_hover_grow';
		} elseif ( 'push' === $box_hover_effects ) {
			$hover_class .= 'content_hover_push';
		} elseif ( 'bounce-in' === $box_hover_effects ) {
			$hover_class .= 'content_hover_bounce_in';
		} elseif ( 'float' === $box_hover_effects ) {
			$hover_class .= 'content_hover_float';
		} elseif ( 'wobble_horizontal' === $box_hover_effects ) {
			$hover_class .= 'content_hover_wobble_horizontal';
		} elseif ( 'wobble_vertical' === $box_hover_effects ) {
			$hover_class .= 'content_hover_wobble_vertical';
		} elseif ( 'float_shadow' === $box_hover_effects ) {
			$hover_class .= ' ' . esc_attr( $hover_uniqid ) . ' content_hover_float_shadow';
		} elseif ( 'grow_shadow' === $box_hover_effects ) {
			$hover_class .= ' ' . esc_attr( $hover_uniqid ) . ' content_hover_grow_shadow';
		} elseif ( 'shadow_radial' === $box_hover_effects ) {
			$hover_class .= '' . esc_attr( $hover_uniqid ) . ' content_hover_radial';
		}

		/*--On Scroll View Animation ---*/
		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation-attr.php';

		/*--Plus Extra ---*/
		$PlusExtra_Class = 'plus-flip-box-widget';
		include THEPLUS_PATH . 'modules/widgets/theplus-widgets-extra.php';

		$output      = '';
		$title_css   = '';
		$the_button  = '';
		$description = '';
		$service_img = '';
		$service_btn = '';

		$imge_content  = '';
		$subtitle_css  = '';
		$service_title = '';
		$service_space = '';

		$serice_box_border  = '';
		$service_icon_style = '';

		if ( 'yes' === $settings['box_border'] ) {
			$serice_box_border = 'service-border-box';
		}

		$icon_font_style = ! empty( $settings['icon_font_style'] ) ? $settings['icon_font_style'] : '';

		$image_icon = ! empty( $settings['image_icon'] ) ? $settings['image_icon'] : '';
		$img_src    = '';
		if ( 'image' === $image_icon ) {
			if ( ! empty( $settings['select_image']['url'] ) ) {
				$image_id = $settings['select_image']['id'];
				$img_src  = tp_get_image_rander( $image_id, $settings['select_image_thumbnail_size'], array( 'class' => 'service-img' ) );
			}
			$service_img = $img_src;
		}

		$icon_style = $settings['icon_style'];
		if ( 'square' === $icon_style ) {
			$service_icon_style = 'icon-squre';
		}
		if ( 'rounded' === $icon_style ) {
			$service_icon_style = 'icon-rounded';
		}
		if ( 'hexagon' === $icon_style ) {
			$service_icon_style = 'icon-hexagon';
		}
		if ( 'pentagon' === $icon_style ) {
			$service_icon_style = 'icon-pentagon';
		}
		if ( 'square-rotate' === $icon_style ) {
			$service_icon_style = 'icon-square-rotate';
		}
		if ( 'icon' === $image_icon ) {
			$icons = '';
			if ( 'font_awesome' === $icon_font_style ) {
				$icons = $settings['icon_fontawesome'];
			} elseif ( 'icon_mind' === $icon_font_style ) {
				$icons = $settings['icons_mind'];
			} elseif ( 'font_awesome_5' === $icon_font_style ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon( $settings['icon_fontawesome_5'], array( 'aria-hidden' => 'true' ) );
				$icons = ob_get_contents();
				ob_end_clean();
			}
				$bg3 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['icon_background_image'], $settings['icon_hover_background_image'] ) : '';
			if ( 'font_awesome_5' === $icon_font_style ) {
				$service_img = '<span class="service-icon ' . $bg3 . ' ' . esc_attr( $service_icon_style ) . '">' . $icons . '</span>';
			} else {
				$service_img = '<i class=" ' . esc_attr( $icons ) . ' ' . $bg3 . ' service-icon ' . esc_attr( $service_icon_style ) . '"></i>';
			}
		}

		if ( ! empty( $settings['border_stroke_color'] ) ) {
			$border_stroke_color = $settings['border_stroke_color'];
		} else {
			$border_stroke_color = 'none';
		}
		if ( 'svg' === $image_icon ) {
			if ( 'img' === $settings['svg_icon'] ) {
				$svg_url = $settings['svg_image']['url'];
			} else {
				$svg_url = THEPLUS_URL . 'assets/images/svg/' . esc_attr( $settings['svg_d_icon'] );
			}
			$rand_no = wp_rand( 1000000, 1500000 );

			$service_img      = '<div class="pt_plus_animated_svg  svg-' . esc_attr( $rand_no ) . '" data-id="svg-' . esc_attr( $rand_no ) . '" data-type="' . esc_attr( $settings['svg_type'] ) . '" data-duration="' . esc_attr( $settings['duration']['size'] ) . '" data-stroke="' . esc_attr( $border_stroke_color ) . '" data-fill_color="none">';
			$service_img     .= '<div class="svg_inner_block" style="max-width:' . esc_attr( $settings['max_width']['size'] ) . esc_attr( $settings['max_width']['unit'] ) . ';max-height:' . esc_attr( $settings['max_width']['size'] ) . esc_attr( $settings['max_width']['unit'] ) . ';">';
				$service_img .= '<object id="svg-' . esc_attr( $rand_no ) . '" type="image/svg+xml" data="' . esc_url( $svg_url ) . '" ></object>';
			$service_img     .= '</div>';
			$service_img     .= '</div>';
		}

		if ( ! empty( $settings['url_link']['url'] ) ) {
			$this->add_render_attribute( 'box_link', 'href', esc_url( $settings['url_link']['url'] ) );
			if ( $settings['url_link']['is_external'] ) {
				$this->add_render_attribute( 'box_link', 'target', '_blank' );
			}
			if ( $settings['url_link']['nofollow'] ) {
				$this->add_render_attribute( 'box_link', 'rel', 'nofollow' );
			}
		}

		if ( ! empty( $settings['title'] ) ) {
			if ( ! empty( $settings['url_link']['url'] ) ) {
				$service_title = '<div class="service-title"> ' . esc_html( $settings['title'] ) . ' </div>';
			} else {
				$service_title = '<div class="service-title"> ' . esc_html( $settings['title'] ) . ' </div>';
			}
		}

		$content_desc = $settings['content_desc'];
		if ( ! empty( $content_desc ) ) {
			$description = '<div class="service-desc"> ' . wp_kses_post( $content_desc ) . ' </div>';
		}

		/** Carousel Option*/
		$isotope       = '';
		$data_slider   = '';
		$arrow_class   = '';
		$data_carousel = '';

		$carousel_direction = '';
		$carousel_slider    = '';
		if ( 'carousel_layout' === $info_box_layout ) {
			$slider_direction = 'vertical' === $settings['slider_direction'] ? 'true' : 'false';
			$data_slider     .= ' data-slider_direction="' . esc_attr( $slider_direction ) . '"';
			$data_slider     .= ' data-slide_speed="' . ( isset( $settings['slide_speed']['size'] ) ? esc_attr( $settings['slide_speed']['size'] ) : 1500 ) . '"';

			$data_slider .= ' data-slider_desktop_column="' . ( isset( $settings['slider_desktop_column'] ) ? esc_attr( $settings['slider_desktop_column'] ) : 4 ) . '"';
			$data_slider .= ' data-steps_slide="' . ( isset( $settings['steps_slide'] ) ? esc_attr( $settings['steps_slide'] ) : 1 ) . '"';

			$slider_draggable = ( 'yes' === $settings['slider_draggable'] ) ? 'true' : 'false';
			$data_slider     .= ' data-slider_draggable="' . esc_attr( $slider_draggable ) . '"';

			$slider_infinite = ( 'yes' === $settings['slider_infinite'] ) ? 'true' : 'false';
			$data_slider    .= ' data-slider_infinite="' . esc_attr( $slider_infinite ) . '"';

			$slider_pause_hover = ( 'yes' === $settings['slider_pause_hover'] ) ? 'true' : 'false';

			$data_slider .= ' data-slider_pause_hover="' . esc_attr( $slider_pause_hover ) . '"';

			$slider_adaptive_height = ( 'yes' === $settings['slider_adaptive_height'] ) ? 'true' : 'false';
			$data_slider           .= ' data-slider_adaptive_height="' . esc_attr( $slider_adaptive_height ) . '"';

			$slider_animation = ( isset( $settings['slider_animation'] ) ? $settings['slider_animation'] : 'ease' );
			$data_slider     .= ' data-slider_animation="' . esc_attr( $slider_animation ) . '"';

			$slider_autoplay = ( 'yes' === $settings['slider_autoplay'] ) ? 'true' : 'false';
			$data_slider    .= ' data-slider_autoplay="' . esc_attr( $slider_autoplay ) . '"';
			$data_slider    .= ' data-autoplay_speed="' . ( isset( $settings['autoplay_speed']['size'] ) ? esc_attr( $settings['autoplay_speed']['size'] ) : 3000 ) . '"';

			/** Tablet*/
			$data_slider .= ' data-slider_tablet_column="' . ( isset( $settings['slider_tablet_column'] ) ? esc_attr( $settings['slider_tablet_column'] ) : 3 ) . '"';
			$data_slider .= ' data-tablet_steps_slide="' . ( isset( $settings['tablet_steps_slide'] ) ? esc_attr( $settings['tablet_steps_slide'] ) : 1 ) . '"';

			$slider_responsive_tablet = ! empty( $settings['slider_responsive_tablet'] ) ? $settings['slider_responsive_tablet'] : '';
			$data_slider             .= ' data-slider_responsive_tablet="' . esc_attr( $slider_responsive_tablet ) . '"';
			if ( 'yes' === $slider_responsive_tablet ) {
				$tablet_slider_draggable = ( 'yes' === $settings['tablet_slider_draggable'] ) ? 'true' : 'false';

				$data_slider .= ' data-tablet_slider_draggable="' . esc_attr( $tablet_slider_draggable ) . '"';

				$tablet_slider_infinite = ( 'yes' === $settings['tablet_slider_infinite'] ) ? 'true' : 'false';

				$data_slider .= ' data-tablet_slider_infinite="' . esc_attr( $tablet_slider_infinite ) . '"';

				$tablet_slider_autoplay = ( 'yes' === $settings['tablet_slider_autoplay'] ) ? 'true' : 'false';

				$data_slider .= ' data-tablet_slider_autoplay="' . esc_attr( $tablet_slider_autoplay ) . '"';
				$data_slider .= ' data-tablet_autoplay_speed="' . ( isset( $settings['tablet_autoplay_speed']['size'] ) ? esc_attr( $settings['tablet_autoplay_speed']['size'] ) : 1500 ) . '"';

				$tablet_slider_dots = ( 'yes' === $settings['tablet_slider_dots'] ) ? 'true' : 'false';

				$data_slider .= ' data-tablet_slider_dots="' . esc_attr( $tablet_slider_dots ) . '"';

				$tablet_slider_arrows = ( 'yes' === $settings['tablet_slider_arrows'] ) ? 'true' : 'false';

				$data_slider .= ' data-tablet_slider_arrows="' . esc_attr( $tablet_slider_arrows ) . '"';
				$data_slider .= ' data-tablet_slider_rows="' . ( isset( $settings['tablet_slider_rows'] ) ? esc_attr( $settings['tablet_slider_rows'] ) : 1 ) . '"';

				$tablet_center_mode = ( 'yes' === $settings['tablet_center_mode'] ) ? 'true' : 'false';

				$data_slider .= ' data-tablet_center_mode="' . esc_attr( $tablet_center_mode ) . '" ';
				$data_slider .= ' data-tablet_center_padding="' . ( isset( $settings['tablet_center_padding']['size'] ) ? esc_attr( $settings['tablet_center_padding']['size'] ) : 0 ) . '" ';
			}

			/** Mobile*/
			$data_slider .= ' data-slider_mobile_column="' . ( isset( $settings['slider_mobile_column'] ) ? esc_attr( $settings['slider_mobile_column'] ) : 2 ) . '"';
			$data_slider .= ' data-mobile_steps_slide="' . ( isset( $settings['mobile_steps_slide'] ) ? esc_attr( $settings['mobile_steps_slide'] ) : 1 ) . '"';

			$slider_responsive_mobile = ! empty( $settings['slider_responsive_mobile'] ) ? $settings['slider_responsive_mobile'] : '';
			$data_slider             .= ' data-slider_responsive_mobile="' . esc_attr( $slider_responsive_mobile ) . '"';
			if ( 'yes' === $slider_responsive_mobile ) {
				$mobile_slider_draggable = ( 'yes' === $settings['mobile_slider_draggable'] ) ? 'true' : 'false';

				$data_slider .= ' data-mobile_slider_draggable="' . esc_attr( $mobile_slider_draggable ) . '"';

				$mobile_slider_infinite = ( 'yes' === $settings['mobile_slider_infinite'] ) ? 'true' : 'false';

				$data_slider .= ' data-mobile_slider_infinite="' . esc_attr( $mobile_slider_infinite ) . '"';

				$mobile_slider_autoplay = ( 'yes' === $settings['mobile_slider_autoplay'] ) ? 'true' : 'false';

				$data_slider .= ' data-mobile_slider_autoplay="' . esc_attr( $mobile_slider_autoplay ) . '"';
				$data_slider .= ' data-mobile_autoplay_speed="' . ( isset( $settings['mobile_autoplay_speed']['size'] ) ? esc_attr( $settings['mobile_autoplay_speed']['size'] ) : 1500 ) . '"';

				$mobile_slider_dots = ( 'yes' === $settings['mobile_slider_dots'] ) ? 'true' : 'false';

				$data_slider .= ' data-mobile_slider_dots="' . esc_attr( $mobile_slider_dots ) . '"';

				$mobile_slider_arrows = ( 'yes' === $settings['mobile_slider_arrows'] ) ? 'true' : 'false';

				$data_slider .= ' data-mobile_slider_arrows="' . esc_attr( $mobile_slider_arrows ) . '"';
				$data_slider .= ' data-mobile_slider_rows="' . ( isset( $settings['mobile_slider_rows'] ) ? esc_attr( $settings['mobile_slider_rows'] ) : 1 ) . '"';

				$mobile_center_mode = ( 'yes' === $settings['mobile_center_mode'] ) ? 'true' : 'false';

				$data_slider .= ' data-mobile_center_mode="' . esc_attr( $mobile_center_mode ) . '" ';
				$data_slider .= ' data-mobile_center_padding="' . ( isset( $settings['mobile_center_padding']['size'] ) ? esc_attr( $settings['mobile_center_padding']['size'] ) : 0 ) . '"';
			}

			$slider_dots  = 'yes' === $settings['slider_dots'] ? 'true' : 'false';
			$data_slider .= ' data-slider_dots="' . esc_attr( $slider_dots ) . '"';
			$data_slider .= ' data-slider_dots_style="slick-dots ' . ( isset( $settings['slider_dots_style'] ) ? esc_attr( $settings['slider_dots_style'] ) : 'style-1' ) . '" ';

			$slider_arrows = 'yes' === $settings['slider_arrows'] ? 'true' : 'false';

			$data_slider .= ' data-slider_arrows="' . esc_attr( $slider_arrows ) . '"';
			$data_slider .= ' data-slider_arrows_style="' . ( isset( $settings['slider_arrows_style'] ) ? esc_attr( $settings['slider_arrows_style'] ) : 'style-1' ) . '" ';
			$data_slider .= ' data-arrows_position="' . ( isset( $settings['arrows_position'] ) ? esc_attr( $settings['arrows_position'] ) : 'top-right' ) . '" ';
			$data_slider .= ' data-arrow_bg_color="' . ( isset( $settings['arrow_bg_color'] ) ? esc_attr( $settings['arrow_bg_color'] ) : '#c44d48' ) . '" ';
			$data_slider .= ' data-arrow_icon_color="' . ( isset( $settings['arrow_icon_color'] ) ? esc_attr( $settings['arrow_icon_color'] ) : '#fff' ) . '" ';
			$data_slider .= ' data-arrow_hover_bg_color="' . ( isset( $settings['arrow_hover_bg_color'] ) ? esc_attr( $settings['arrow_hover_bg_color'] ) : '#fff' ) . '" ';
			$data_slider .= ' data-arrow_hover_icon_color="' . ( isset( $settings['arrow_hover_icon_color'] ) ? esc_attr( $settings['arrow_hover_icon_color'] ) : '#c44d48' ) . '" ';

			$slider_center_mode = ( 'yes' === $settings['slider_center_mode'] ) ? 'true' : 'false';

			$data_slider .= ' data-slider_center_mode="' . esc_attr( $slider_center_mode ) . '" ';
			$data_slider .= ' data-center_padding="' . ( isset( $settings['center_padding']['size'] ) ? esc_attr( $settings['center_padding']['size'] ) : 0 ) . '" ';
			$data_slider .= ' data-scale_center_slide="' . ( isset( $settings['scale_center_slide']['size'] ) ? esc_attr( $settings['scale_center_slide']['size'] ) : 1 ) . '" ';
			$data_slider .= ' data-scale_normal_slide="' . ( isset( $settings['scale_normal_slide']['size'] ) ? esc_attr( $settings['scale_normal_slide']['size'] ) : 0.8 ) . '" ';
			$data_slider .= ' data-opacity_normal_slide="' . ( isset( $settings['opacity_normal_slide']['size'] ) ? esc_attr( $settings['opacity_normal_slide']['size'] ) : 0.7 ) . '" ';
			$data_slider .= ' data-slider_rows="' . ( isset( $settings['slider_rows'] ) ? esc_attr( $settings['slider_rows'] ) : 1 ) . '" ';
			$isotope      = 'list-carousel-slick';

			if ( 'style-3' === $settings['slider_arrows_style'] || 'style-4' === $settings['slider_arrows_style'] ) {
				$arrow_class = $settings['arrows_position'];
			}
			if ( ( $settings['slider_rows'] > 1 ) || ( $settings['tablet_slider_rows'] > 1 ) || ( $settings['mobile_slider_rows'] > 1 ) ) {
				$arrow_class .= ' multi-row';
			}
			if ( ! empty( $settings['hover_show_dots'] ) && 'yes' === $settings['hover_show_dots'] ) {
				$data_carousel .= ' hover-slider-dots';
			}
			if ( ! empty( $settings['hover_show_arrow'] ) && 'yes' === $settings['hover_show_arrow'] ) {
				$data_carousel .= ' hover-slider-arrow';
			}
			if ( ! empty( $settings['outer_section_arrow'] ) && 'yes' === $settings['outer_section_arrow'] && ( 'style-1' === $settings['slider_arrows_style'] || 'style-2' === $settings['slider_arrows_style'] || 'style-5' === $settings['slider_arrows_style'] || 'style-6' === $settings['slider_arrows_style'] ) ) {
				$data_carousel .= ' outer-slider-arrow';
			}

			$carousel_direction = ! empty( $settings['carousel_direction'] ) ? $settings['carousel_direction'] : 'ltr';

			if ( ! empty( $carousel_direction ) ) {
				$carousel_data = array(
					'carousel_direction' => $carousel_direction,
				);

				$carousel_slider = 'data-result="' . htmlspecialchars( wp_json_encode( $carousel_data, true ), ENT_QUOTES, 'UTF-8' ) . '"';
			}
		}

		$the_button = '';
		if ( 'yes' === $settings['display_button'] ) {
			if ( ! empty( $settings['button_link']['url'] ) ) {
				$this->add_render_attribute( 'button', 'href', esc_url( $settings['button_link']['url'] ) );
				if ( $settings['button_link']['is_external'] ) {
					$this->add_render_attribute( 'button', 'target', '_blank' );
				}
				if ( $settings['button_link']['nofollow'] ) {
					$this->add_render_attribute( 'button', 'rel', 'nofollow' );
				}
			}
			$bg6 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['button_background_image'], $settings['button_hover_background_image'] ) : '';
			$this->add_render_attribute( 'button', 'class', 'button-link-wrap' . $bg6 );
			$this->add_render_attribute( 'button', 'role', 'button' );

			$button_style = ! empty( $settings['button_style'] ) ? $settings['button_style'] : 'style-7';
			$button_text  = ! empty( $settings['button_text'] ) ? $settings['button_text'] : 'Read More';
			$btn_uid      = uniqid( 'btn' );
			$data_class   = $btn_uid;
			$data_class  .= ' button-' . esc_attr( $button_style ) . ' ';

			$the_button = '<div class="pt-plus-button-wrapper text-center">';

			$the_button .= '<div class="button_parallax">';

				$the_button .= '<div class="text-center ts-button">';

					$the_button .= '<div class="pt_plus_button ' . esc_attr( $data_class ) . '">';

						$the_button         .= '<div class="animted-content-inner">';
							$the_button     .= '<a ' . $this->get_render_attribute_string( 'button' ) . '>';
								$the_button .= $this->render_text();
							$the_button     .= '</a>';

						$the_button .= '</div>';

					$the_button .= '</div>';
				$the_button     .= '</div>';

			$the_button .= '</div>';

			$the_button .= '</div>';
		}

		if ( 'horizontal' === $settings['flip_style'] ) {
			$service_flip = 'flip-horizontal';
		}
		if ( 'vertical' === $settings['flip_style'] ) {
			$service_flip = 'flip-vertical';
		}
		if ( 'carousel_layout' === $info_box_layout ) {
			if ( ! empty( $settings['loop_content'] ) ) {
				$index = 0;
				foreach ( $settings['loop_content'] as $item ) {

					$list_img = '';
					$svg_type = '';
					$duration = '';

					$svg_image   = '';
					$loop_title  = '';
					$list_title  = '';
					$description = '';

					$list_subtitle   = '';
					$loop_btn_text   = '';
					$loop_max_width  = '';
					$loop_image_icon = '';
					$loop_svg_d_icon = '';
					$loop_svg_url    = '';

					if ( ! empty( $settings['loop_url_link']['url'] ) ) {
						$this->add_render_attribute( 'loop_box_link', 'href', esc_url( $settings['loop_url_link']['url'] ) );
						if ( $settings['loop_url_link']['is_external'] ) {
							$this->add_render_attribute( 'loop_box_link', 'target', '_blank' );
						}
						if ( $settings['loop_url_link']['nofollow'] ) {
							$this->add_render_attribute( 'loop_box_link', 'rel', 'nofollow' );
						}
					}

					if ( ! empty( $item['loop_title'] ) ) {
						$loop_title = $item['loop_title'];
						if ( ! empty( $settings['loop_url_link']['url'] ) ) {
							$list_title = '<h6 class="service-title">' . esc_html( $loop_title ) . '</h6>';
						} else {
							$list_title = '<h6 class="service-title">' . esc_html( $loop_title ) . '</h6>';
						}
					}

					$loop_content_desc = ( ! empty( $item['loop_content_desc'] ) ? $item['loop_content_desc'] : '' );
					if ( ! empty( $loop_content_desc ) ) {
						$description = '<div class="service-desc"> ' . wp_kses_post( $loop_content_desc ) . ' </div>';
					}

					if ( ! empty( $item['loop_image_icon'] ) ) {

						$loop_svg_d_icon = $item['loop_svg_d_icon'];
						$loop_max_width  = ( ( ! empty( $item['loop_max_width']['size'] ) && ! empty( $item['loop_max_width']['unit'] ) ) ? $item['loop_max_width']['size'] . $item['loop_max_width']['unit'] : '100px' );

						if ( isset( $item['loop_image_icon'] ) && 'image' === $item['loop_image_icon'] ) {
							$loop_img_src = '';
							if ( ! empty( $item['loop_select_image']['url'] ) ) {
								$image_id     = $item['loop_select_image']['id'];
								$loop_img_src = tp_get_image_rander( $image_id, $item['loop_select_image_thumbnail_size'] );
							}

							$list_img  = '<div class="ts-icon-img icon-img-b " >';
							$list_img .= $loop_img_src;
							$list_img .= '</div>';
						} elseif ( isset( $item['loop_image_icon'] ) && 'icon' === $item['loop_image_icon'] ) {
							$icons = '';
							if ( 'font_awesome' === $item['loop_icon_font_style'] ) {
								$icons = $item['loop_icon_fontawesome'];
							} elseif ( 'icon_mind' === $item['loop_icon_font_style'] ) {
								$icons = $item['loop_icons_mind'];
							} elseif ( 'font_awesome_5' === $item['loop_icon_font_style'] ) {
								ob_start();
									\Elementor\Icons_Manager::render_icon( $item['loop_icon_fontawesome_5'], array( 'aria-hidden' => 'true' ) );
									$icons = ob_get_contents();
								ob_end_clean();
							}

							if ( ! empty( $icons ) ) {
								$bg4 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['icon_background_image'], $settings['icon_hover_background_image'] ) : '';
								if ( ! empty( $item['loop_icon_font_style'] ) && 'font_awesome_5' === $item['loop_icon_font_style'] ) {
									$list_img = '<span class="service-icon ' . $bg4 . ' ' . esc_attr( $service_icon_style ) . '" >' . $icons . '</span>';
								} else {
									$list_img = '<i class=" ' . esc_attr( $icons ) . ' ' . $bg4 . ' service-icon ' . esc_attr( $service_icon_style ) . '" ></i>';
								}
							}
						} elseif ( isset( $item['loop_image_icon'] ) && 'svg' === $item['loop_image_icon'] ) {
							if ( 'img' === $item['loop_svg_icon'] ) {
								if ( ! empty( $item['loop_svg_image']['url'] ) ) {
									$loop_svg_url = $item['loop_svg_image']['url'];
								}
							} else {
								$loop_svg_url = THEPLUS_URL . 'assets/images/svg/' . esc_attr( $loop_svg_d_icon );
							}

							$rand_no = wp_rand( 1000000, 1500000 );

							$list_img = '<div class="pt_plus_animated_svg svg-' . esc_attr( $rand_no ) . ' " data-id="svg-' . esc_attr( $rand_no ) . '" data-type="' . esc_attr( $settings['svg_type'] ) . '" data-duration="' . esc_attr( $settings['duration']['size'] ) . '" data-stroke="' . esc_attr( $border_stroke_color ) . '" data-fill_color="none">';

								$list_img .= '<div class="svg_inner_block" style="max-width:' . esc_attr( $loop_max_width ) . ';max-height:' . esc_attr( $loop_max_width ) . ';">';

									$list_img .= '<object id="svg-' . esc_attr( $rand_no ) . '" type="image/svg+xml" data="' . esc_url( $loop_svg_url ) . '" ></object>';

								$list_img .= '</div>';

							$list_img .= '</div>';
						}
					}

					$loop_button = '';
					if ( 'yes' === $settings['loop_display_button'] ) {
						$link_key = 'link_' . $index;
						if ( ! empty( $item['loop_button_link']['url'] ) ) {
							$this->add_render_attribute( $link_key, 'href', esc_url( $item['loop_button_link']['url'] ) );
							if ( $item['loop_button_link']['is_external'] ) {
								$this->add_render_attribute( $link_key, 'target', '_blank' );
							}
							if ( $item['loop_button_link']['nofollow'] ) {
								$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
							}
						}
						$bg5 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['loop_button_background_image'], $settings['loop_button_hover_background_image'] ) : '';
						$this->add_render_attribute( $link_key, 'class', 'button-link-wrap' . $bg5 );
						$this->add_render_attribute( $link_key, 'role', 'button' );

						$button_style = $settings['loop_button_style'];
						$button_text  = $item['loop_button_text'];
						$btn_uid      = uniqid( 'btn' );
						$data_class   = $btn_uid;
						$data_class  .= ' button-' . $button_style . ' ';

						if ( 'style-7' === $button_style ) {
							$button_text = wp_kses_post( $button_text ) . '<span class="btn-arrow"></span>';
						}
						if ( 'style-8' === $button_style ) {
							$button_text = wp_kses_post( $button_text );
						}
						if ( 'style-9' === $button_style ) {
							$button_text = wp_kses_post( $button_text ) . '<span class="btn-arrow"><i class="fa-show fa fa-chevron-right" aria-hidden="true"></i><i class="fa-hide fa fa-chevron-right" aria-hidden="true"></i></span>';
						}

						$loop_button  = '<div class="pt-plus-button-wrapper text-center">';
						$loop_button .= '<div class="button_parallax">';

							$loop_button .= '<div class="text-center ts-button">';

								$loop_button .= '<div class="pt_plus_button ' . esc_attr( $data_class ) . '">';

									$loop_button .= '<div class="animted-content-inner">';

										$loop_button .= '<a ' . $this->get_render_attribute_string( $link_key ) . '>' . $button_text . '</a>';

									$loop_button .= '</div>';

								$loop_button .= '</div>';

							$loop_button .= '</div>';

						$loop_button .= '</div>';
						$loop_button .= '</div>';

						++$index;
					}

					$output .= '<div class="info-box-inner">';
					if ( 'style_5' === $main_style ) {
						$output .= '<div class="info-box-bg-box content_hover_effect ' . esc_attr( $hover_class ) . '">';

							$output .= '<div class="service-flipbox ' . esc_attr( $service_flip ) . ' height-full">';

								$output .= '<div class="service-flipbox-holder height-full text-center perspective bezier-1" >';

									$bg1 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $item['box_loop_front_background_image'] ) : '';

									$output .= '<div class="service-flipbox-front ' . $bg1 . ' bezier-1 no-backface origin-center elementor-repeater-item-' . esc_attr( $item['_id'] ) . '">';

										$output .= '<div class="service-flipbox-content width-full">';

											$output .= $list_img;
											$output .= '<div class="service-content">' . wp_kses_post( $list_title ) . '</div>';

										$output .= '</div>';

										$output .= '<div class="infobox-front-overlay"></div>';

									$output .= '</div>';

									$bg2 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $item['box_loop_back_background_image'] ) : '';

									$output .= '<div class="service-flipbox-back ' . $bg2 . ' fold-back-horizontal no-backface bezier-1 origin-center elementor-repeater-item-' . esc_attr( $item['_id'] ) . '">';

										$output .= '<div class="service-flipbox-content width-full">';

											$output .= wp_kses_post( $description );
											$output .= $loop_button;

										$output .= '</div>';
										$output .= '<div class="infobox-back-overlay"></div>';

									$output .= '</div>';

								$output .= '</div>';

							$output .= '</div>';

						$output .= '</div>';
					}

					$output .= '</div>';
				}
			}
		}

		if ( 'single_layout' === $info_box_layout ) {
			$output = '<div class="info-box-inner content_hover_effect ' . esc_attr( $hover_class ) . '"  ' . $hover_attr . '>';

			if ( 'style_5' === $main_style ) {
				$output .= '<div class="info-box-bg-box">';
				$output .= '<div class="service-flipbox ' . esc_attr( $service_flip ) . ' height-full">';

					$output .= '<div class="service-flipbox-holder height-full text-center perspective bezier-1">';

						$bg7 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['box_front_background_image'] ) : '';

						$output .= '<div class="service-flipbox-front ' . $bg7 . ' bezier-1 no-backface origin-center">';

							$output .= '<div class="service-flipbox-content width-full">';

								$output     .= $service_img;
								$output     .= '<div class="service-content">';
									$output .= wp_kses_post( $service_title );
								$output     .= '</div>';

							$output .= '</div>';
							$output .= '<div class="infobox-front-overlay"></div>';

						$output .= '</div>';

						$bg8     = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['box_back_background_image'] ) : '';
						$output .= '<div class="service-flipbox-back ' . $bg8 . ' fold-back-horizontal no-backface bezier-1 origin-center">';

							$output .= '<div class="service-flipbox-content width-full">';

								$output .= wp_kses_post( $description );
								$output .= $the_button;

							$output .= '</div>';
							$output .= '<div class="infobox-back-overlay"></div>';

						$output .= '</div>';

					$output .= '</div>';

				$output .= '</div>';

				$output .= '</div>';
			}

			$output .= '</div>';
		}

		$visiblity_hide = '';
		if ( ! empty( $settings['responsive_visible_opt'] ) && 'yes' === $settings['responsive_visible_opt'] ) {
			$visiblity_hide .= ( 'yes' !== $settings['desktop_opt'] && empty( $settings['desktop_opt'] ) ) ? 'hide-desktop ' : '';
			$visiblity_hide .= ( 'yes' !== $settings['tablet_opt'] && empty( $settings['tablet_opt'] ) ) ? 'hide-tablet ' : '';
			$visiblity_hide .= ( 'yes' !== $settings['mobile_opt'] && empty( $settings['mobile_opt'] ) ) ? 'hide-mobile ' : '';
		}

		$uid         = uniqid( 'flip_box' );
		$carousel_bg = '';

		if ( ! empty( $settings['carousel_unique_id'] ) ) {
			$uid = 'tpca_' . $settings['carousel_unique_id'];

			$carousel_bg = ' data-carousel-bg-conn="bgcarousel' . esc_attr( $settings['carousel_unique_id'] ) . '"';
		}

		$info_box  = '<div class="pt_plus_info_box ' . esc_attr( $isotope ) . ' ' . esc_attr( $arrow_class ) . ' ' . esc_attr( $data_carousel ) . ' ' . esc_attr( $uid ) . ' info-box-' . esc_attr( $main_style ) . ' ' . esc_attr( $animated_class ) . '  ' . esc_attr( $service_space ) . ' ' . esc_attr( $visiblity_hide ) . '" ' . $carousel_slider . ' dir=' . esc_attr( $carousel_direction ) . ' data-id="' . esc_attr( $uid ) . '" ' . $animation_attr . ' ' . $data_slider . ' ' . $carousel_bg . '>';
		$info_box .= '<div class="post-inner-loop">' . $output . '</div>';
		$info_box .= '</div>';

		echo $before_content . $info_box . $after_content;
	}

	/**
	 * Render text
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$icons_after  = '';
		$icons_before = '';
		$button_style = ! empty( $settings['button_style'] ) ? $settings['button_style'] : 'style-7';
		$before_after = ! empty( $settings['before_after'] ) ? $settings['before_after'] : 'after';
		$button_text  = ! empty( $settings['button_text'] ) ? $settings['button_text'] : '';

		$button_icon_font_style = ! empty( $settings['button_icon_font_style'] ) ? $settings['button_icon_font_style'] : 'font_awesome';

		$icons = '';
		if ( 'font_awesome' === $button_icon_font_style ) {
			$icons = $settings['button_icon'];
		} elseif ( 'icon_mind' === $button_icon_font_style ) {
			$icons = $settings['button_icons_mind'];
		} elseif ( 'font_awesome_5' === $button_icon_font_style ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $settings['button_icon_5'], array( 'aria-hidden' => 'true' ) );
			$icons = ob_get_contents();
			ob_end_clean();
		}

		if ( 'before' === $before_after && ! empty( $icons ) ) {
			if ( 'font_awesome_5' === $button_icon_font_style ) {
				$icons_before = '<span class="btn-icon button-before">' . $icons . '</span>';
			} else {
				$icons_before = '<i class="btn-icon button-before ' . esc_attr( $icons ) . '"></i>';
			}
		}

		if ( 'after' === $before_after && ! empty( $icons ) ) {
			if ( 'font_awesome_5' === $button_icon_font_style ) {
				$icons_after = '<span class="btn-icon button-after">' . $icons . '</span>';
			} else {
				$icons_after = '<i class="btn-icon button-after ' . esc_attr( $icons ) . '"></i>';
			}
		}

		if ( 'style-8' === $button_style ) {
			$button_text = $icons_before . wp_kses_post( $button_text ) . $icons_after;
		}
		if ( 'style-7' === $button_style ) {
			$button_text = wp_kses_post( $button_text ) . '<span class="btn-arrow"></span>';
		}
		if ( 'style-9' === $button_style ) {
			$button_text = wp_kses_post( $button_text ) . '<span class="btn-arrow"><i class="fa-show fa fa-chevron-right" aria-hidden="true"></i><i class="fa-hide fa fa-chevron-right" aria-hidden="true"></i></span>';
		}

		return $button_text;
	}

	/**
	 * Render content_template
	 *
	 * @since 3.3.0
	 * @version 5.4.2
	 */
	protected function content_template() {
	}
}