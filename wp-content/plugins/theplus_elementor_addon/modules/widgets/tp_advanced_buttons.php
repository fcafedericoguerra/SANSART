<?php
/**
 * Widget Name: Advanced Buttons
 * Description: Advanced Buttons
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
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use TheplusAddons\Theplus_Element_Load;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class ThePlus_Adv_Text_Block.
 */
class ThePlus_Advanced_Buttons extends Widget_Base {

	/**
	 * Helpdesk Link For Need help.
	 *
	 * @var tp_help of the class.
	 */
	public $tp_help = THEPLUS_HELP;

	/**
	 * Get Widget Name.
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	public function get_name() {
		return 'tp-advanced-buttons';
	}

	/**
	 * Get Widget Title.
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	public function get_title() {
		return esc_html__( 'Advanced Buttons', 'theplus' );
	}

	/**
	 * Get Widget Icon.
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	public function get_icon() {
		return 'fa fa-anchor theplus_backend_icon';
	}

	/**
	 * Get Custom URL.
	 *
	 * @since 5.6.5
	 */
	public function get_custom_help_url() {
		$help_url = $this->tp_help;

		return esc_url( $help_url );
	}

	/**
	 * Get Widget Icon.
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	public function get_categories() {
		return array( 'plus-creatives' );
	}

	/**
	 * Get Widget keywords.
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	public function get_keywords() {
		return array( 'Advanced Buttons', 'Button Widget', 'Custom Buttons', 'Stylish Buttons', 'Creative Buttons', 'Elementor Buttons', 'Button', 'Button Addon', 'Button Plugin', 'Button Element' );
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
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	protected function register_controls() {
		/*adv button section start*/
		$this->start_controls_section(
			'section_advanced_buttons',
			array(
				'label' => esc_html__( 'Advanced Buttons', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'ab_button_type',
			array(
				'label'   => esc_html__( 'Button Type', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cta',
				'options' => array(
					'cta'      => esc_html__( 'CTA Button', 'theplus' ),
					'download' => esc_html__( 'Download Button', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'cta_button_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Style', 'theplus' ),
				'default'   => 'tp_cta_st_1',
				'options'   => array(
					'tp_cta_st_1'  => esc_html__( 'Style 1', 'theplus' ),
					'tp_cta_st_2'  => esc_html__( 'Style 2', 'theplus' ),
					'tp_cta_st_3'  => esc_html__( 'Style 3', 'theplus' ),
					'tp_cta_st_4'  => esc_html__( 'Style 4', 'theplus' ),
					'tp_cta_st_5'  => esc_html__( 'Style 5', 'theplus' ),
					'tp_cta_st_6'  => esc_html__( 'Style 6', 'theplus' ),
					'tp_cta_st_7'  => esc_html__( 'Style 7', 'theplus' ),
					'tp_cta_st_8'  => esc_html__( 'Style 8', 'theplus' ),
					'tp_cta_st_9'  => esc_html__( 'Style 9', 'theplus' ),
					'tp_cta_st_10' => esc_html__( 'Style 10', 'theplus' ),
					'tp_cta_st_11' => esc_html__( 'Style 11', 'theplus' ),
					'tp_cta_st_12' => esc_html__( 'Style 12', 'theplus' ),
					'tp_cta_st_13' => esc_html__( 'Style 13', 'theplus' ),
					'tp_cta_st_14' => esc_html__( 'Style 14', 'theplus' ),

				),
				'condition' => array(
					'ab_button_type' => 'cta',
				),
			)
		);
		$this->add_control(
			'download_button_style',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Style', 'theplus' ),
				'default'   => 'tp_download_st_1',
				'options'   => array(
					'tp_download_st_1' => esc_html__( 'Style 1', 'theplus' ),
					'tp_download_st_2' => esc_html__( 'Style 2', 'theplus' ),
					'tp_download_st_3' => esc_html__( 'Style 3', 'theplus' ),
					'tp_download_st_4' => esc_html__( 'Style 4', 'theplus' ),
					'tp_download_st_5' => esc_html__( 'Style 5', 'theplus' ),
				),
				'condition' => array(
					'ab_button_type' => 'download',
				),
			)
		);
		$this->add_control(
			'common_button_text',
			array(
				'label'       => esc_html__( 'Button Text', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Read More', 'theplus' ),
				'placeholder' => esc_html__( 'Read More', 'theplus' ),
			)
		);
		$this->add_control(
			'dbt_button_text_2',
			array(
				'label'       => esc_html__( 'Loading Text', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'downloading...', 'theplus' ),
				'placeholder' => esc_html__( 'downloading...', 'theplus' ),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .tp-meter:before' => ' content:"{{VALUE}}";',
				),
				'condition'   => array(
					'download_button_style' => 'tp_download_st_5',
				),
			)
		);
		$this->add_control(
			'dbt_button_text_3',
			array(
				'label'       => esc_html__( 'Success Text', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'done!', 'theplus' ),
				'placeholder' => esc_html__( 'done!', 'theplus' ),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .tp-meter.is-done:after' => ' content:"{{VALUE}}";',
				),
				'condition'   => array(
					'download_button_style' => 'tp_download_st_5',
				),
			)
		);
		$this->add_control(
			'common_button_text_2',
			array(
				'label'       => esc_html__( 'Extra Text 1', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Theplus', 'theplus' ),
				'placeholder' => esc_html__( 'Button Text 2', 'theplus' ),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-btn-parrot:before' => ' content:"{{VALUE}}";',
				),
				'condition'   => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_6', 'tp_cta_st_9', 'tp_cta_st_13' ),
				),
			)
		);
		$this->add_control(
			'db_common_button_text_2',
			array(
				'label'       => esc_html__( 'Extra Text', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Theplus', 'theplus' ),
				'placeholder' => esc_html__( 'Button Text', 'theplus' ),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-btn-parrot:before' => ' content:"{{VALUE}}";',
				),
				'condition'   => array(
					'ab_button_type'        => 'download',
					'download_button_style' => array( 'tp_download_st_3' ),
				),
			)
		);
		$this->add_responsive_control(
			'db_common_min_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Minimum Width', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 115,
				),
				'separator'   => 'after',
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap' => 'min-width: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'ab_button_type'        => 'download',
					'download_button_style' => array( 'tp_download_st_3' ),
				),
			)
		);
		$this->add_control(
			'common_button_text_3',
			array(
				'label'       => esc_html__( 'Extra Text 2', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Theplus', 'theplus' ),
				'placeholder' => esc_html__( 'Button Text 2', 'theplus' ),
				'condition'   => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_6', 'tp_cta_st_9', 'tp_cta_st_13' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap:hover .adv-btn-parrot:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:after' => ' content:"{{VALUE}}";',
				),
			)
		);
		$this->add_control(
			'common_emoji_normal',
			array(
				'label'       => esc_html__( 'Normal Emoji', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( '💯', 'theplus' ),
				'placeholder' => esc_html__( 'Normal Emoji', 'theplus' ),
				'condition'   => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_8',
				),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-btn-emoji:before' => ' content:"{{VALUE}}";',
				),
			)
		);
		$this->add_control(
			'common_emoji_hover',
			array(
				'label'       => esc_html__( 'Hover Emoji', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( '👏', 'theplus' ),
				'placeholder' => esc_html__( 'Hover Emoji', 'theplus' ),
				'condition'   => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_8',
				),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap:hover .adv-btn-emoji:before' => ' content:"{{VALUE}}";',
				),
			)
		);
		$this->add_responsive_control(
			'st14_button_width',
			array(
				'label'     => esc_html__( 'Button Width', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 500,
				'step'      => 1,
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_14' ),
				),
			)
		);
		$this->add_responsive_control(
			'st10_button_width',
			array(
				'label'     => esc_html__( 'Button Width', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 1000,
				'step'      => 1,
				'default'   => 155,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap svg' => 'width: {{SIZE}}px',
				),
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_10' ),
				),
			)
		);
		$this->add_responsive_control(
			'st10_button_height',
			array(
				'label'     => esc_html__( 'Button Height', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 1000,
				'step'      => 1,
				'default'   => 55,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap svg' => 'height: {{SIZE}}px',
				),
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_10' ),
				),
			)
		);
		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Link', 'theplus' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'separator'   => 'before',
				'placeholder' => esc_html__( 'https://www.demo-link.com', 'theplus' ),
				'default'     => array(
					'url' => '#',
				),
			)
		);
		$this->add_control(
			'download_file_name',
			array(
				'label'       => esc_html__( 'Download File Name', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'download', 'theplus' ),
				'placeholder' => esc_html__( 'Download File Name', 'theplus' ),
				'condition'   => array(
					'ab_button_type' => 'download',
				),
			)
		);
		$this->end_controls_section();
		/*adv button section end*/

		/*Alignment  option start*/
		$this->start_controls_section(
			'section_button_align',
			array(
				'label' => esc_html__( 'Alignment', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_responsive_control(
			'button_align',
			array(
				'label'     => esc_html__( 'Alignment', 'theplus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'theplus' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'theplus' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'theplus' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'download_button_style!' => 'tp_download_st_5',
				),
			)
		);
		$this->add_responsive_control(
			'button_align_d_st5',
			array(
				'label'     => esc_html__( 'Alignment', 'theplus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'theplus' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'theplus' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'theplus' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'download_button_style' => 'tp_download_st_5',
				),
			)
		);
		$this->add_control(
			'tooltip_alignment',
			array(
				'label'     => esc_html__( 'Tool tip Position', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'st13_tt_align_left',
				'options'   => array(
					'st13_tt_align_left'  => esc_html__( 'Left', 'theplus' ),
					'st13_tt_align_right' => esc_html__( 'Right', 'theplus' ),
				),
				'condition' => array(
					'ab_button_type!'  => 'download',
					'cta_button_style' => 'tp_cta_st_13',
				),
			)
		);

		$this->end_controls_section();
		/*Alignment  option end*/

		/*extra option section start*/
		$this->start_controls_section(
			'section_ab_extra_options',
			array(
				'label'     => esc_html__( 'Extra Options', 'theplus' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_3', 'tp_cta_st_4', 'tp_cta_st_5', 'tp_cta_st_6' ),
				),
			)
		);
		$this->add_responsive_control(
			'min_width_st5',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Minimum Width', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 20,
						'max'  => 500,
						'step' => 1,
					),
				),
				'condition'   => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_5',
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap' => 'min-width: calc({{SIZE}}{{UNIT}} + 1px);',
				),
			)
		);
		$this->add_control(
			'animate_duration_normal',
			array(
				'label'     => esc_html__( 'Normal Animation Speed', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .pulsing:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .pulsing:after,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .tp-cta-st5-text' => 'animation-duration: {{VALUE}}s;-o-animation-duration: {{VALUE}}s;
					-ms-animation-duration: {{VALUE}}s;-moz-animation-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;',
				),
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_3', 'tp_cta_st_4' ),
				),
			)
		);
			$this->add_control(
				'animate_duration_hover',
				array(
					'label'     => esc_html__( 'Hover Animation Speed', 'theplus' ),
					'type'      => \Elementor\Controls_Manager::NUMBER,
					'min'       => 1,
					'max'       => 10,
					'step'      => 1,
					'selectors' => array(
						'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4:hover .pulsing:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4:hover .pulsing:after,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap:hover .tp-cta-st5-text' => 'animation-duration: 0.{{VALUE}}s;-o-animation-duration: 0.{{VALUE}}s;-ms-animation-duration: 0.{{VALUE}}s;-moz-animation-duration: 0.{{VALUE}}s;-webkit-animation-duration: 0.{{VALUE}}s;',
					),
					'condition' => array(
						'ab_button_type'   => 'cta',
						'cta_button_style' => array( 'tp_cta_st_3', 'tp_cta_st_4', 'tp_cta_st_5' ),
					),
				)
			);
		$this->add_control(
			'marquee_speed',
			array(
				'label'     => esc_html__( 'Marquee Speed', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 12,
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_6',
				),
			)
		);
		$this->add_control(
			'marquee_direction',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Marquee Direction', 'theplus' ),
				'default'   => 'left',
				'options'   => array(
					'right' => esc_html__( 'Left to Right', 'theplus' ),
					'left'  => esc_html__( 'Right to Left', 'theplus' ),
				),
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_6',
				),
			)
		);
		$this->end_controls_section();
		/*extra option section end*/

		/* style section start*/
		$this->start_controls_section(
			'section_styling',
			array(
				'label'     => esc_html__( 'Advanced Button Style', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type'    => 'cta',
					'cta_button_style!' => array( 'tp_cta_st_10', 'tp_cta_st_11', 'tp_cta_st_14' ),
				),
			)
		);
		$this->add_responsive_control(
			'btn_cta1_2_circle_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Outer Border Height/Width', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 300,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 55,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:before' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => array( 'tp_cta_st_1', 'tp_cta_st_2' ),
				),
			)
		);
		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover > span',
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
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta .adv-button-link-wrap,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap .tp-cta-st5-text' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_text_extra_color',
			array(
				'label'     => esc_html__( 'Extra Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_9',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .pulsing:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .pulsing:after,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'button_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				/*not-cta-4*/
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap',
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
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap:hover .tp-cta-st5-text' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'cta_button_style!' => 'tp_cta_st_13',
				),

			)
		);
		$this->add_control(
			'btn_cta_13_text_hover_color',
			array(
				'label'     => esc_html__( 'Text Hover Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover > span' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_13',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:hover:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:hover:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_hover_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:hover:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:hover:before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_4 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'button_hover_radius',
			array(
				'label'      => esc_html__( 'Hover Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:hover:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:hover:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_1 .adv-button-link-wrap:hover:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_2 .adv-button-link-wrap:hover:before,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_3 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_5 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_6 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_7 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_8 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_12 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*style section end*/

		/*style cta 13 section start*/
		$this->start_controls_section(
			'section_styling_cta13_tt',
			array(
				'label'     => esc_html__( 'Tool Tip Style', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_13',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'buttontt_cta13_typography',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:after',
			)
		);

		$this->start_controls_tabs( 'tabs_buttontt_cta13_style' );

		$this->start_controls_tab(
			'tab_buttontt_cta13_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);

		$this->add_control(
			'text_buttontt_cta13_color',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:after' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'buttontt_cta13_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:before',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'buttontt_cta13_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:before',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'buttontt_cta13_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'buttontt_cta13_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:before',
			)
		);
		$this->add_control(
			'buttontt_cta13_transform',
			array(
				'label'       => esc_html__( 'Transform css', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'skew(-25deg)',
				'placeholder' => esc_html__( 'skew(-25deg)', 'theplus' ),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:before' => 'transform: {{VALUE}};-ms-transform: {{VALUE}};-moz-transform: {{VALUE}};-webkit-transform: {{VALUE}};transform-style: preserve-3d;-ms-transform-style: preserve-3d;-moz-transform-style: preserve-3d;-webkit-transform-style: preserve-3d;',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_buttontt_cta13_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_control(
			'buttontt_cta13_txt_hover_color',
			array(
				'label'     => esc_html__( 'Text Hover Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover:after' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'buttontt_cta13_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover:before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'buttontt_cta13_hover_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover:before',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'buttontt_cta13_hover_radius',
			array(
				'label'      => esc_html__( 'Hover Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'buttontt_cta13_hover_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover:before',
			)
		);
			$this->add_control(
				'buttontt_cta13_transform_h',
				array(
					'label'       => esc_html__( 'Transform css', 'theplus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => 'skew(-25deg)',
					'placeholder' => esc_html__( 'skew(-25deg)', 'theplus' ),
					'selectors'   => array(
						'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_13 .adv-button-link-wrap:hover:before' => 'transform: {{VALUE}};-ms-transform: {{VALUE}};-moz-transform: {{VALUE}};-webkit-transform: {{VALUE}};transform-style: preserve-3d;-ms-transform-style: preserve-3d;-moz-transform-style: preserve-3d;-webkit-transform-style: preserve-3d;',
					),
				)
			);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*style cta 13 section end*/

		/*style cta 10 section start*/
		$this->start_controls_section(
			'section_styling_cta10',
			array(
				'label'     => esc_html__( 'Advanced Button Style', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_10',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_cta10_typography',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap span',
			)
		);
		$this->add_control(
			'btn_cta10_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_cta10_fill_color',
			array(
				'label'     => esc_html__( 'Fill Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap svg .tp-cpt-btn01' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_cta10_hover_fill_color',
			array(
				'label'     => esc_html__( 'Hover Fill Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap:hover .tp-cpt-btn01' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_cta10_hover_dot_color',
			array(
				'label'     => esc_html__( 'Hover Dot Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap svg .tp-cpt-btn02' => 'stroke: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_cta10_stroke_color',
			array(
				'label'     => esc_html__( 'Stroke Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap svg .tp-cpt-btn01' => 'stroke: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'btn_cta10_stroke_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Stroke Width', 'theplus' ),
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
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_10 .adv-button-link-wrap svg' => 'stroke-width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->end_controls_section();
		/*style cat 10 section end*/

		/*style cta 11 section start*/
		$this->start_controls_section(
			'section_styling_cta_11',
			array(
				'label'     => esc_html__( 'Advanced Button Style', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_11',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_cta_11_typography',
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap',
			)
		);
		$this->start_controls_tabs( 'tabs_button_cta_11_style' );
		$this->start_controls_tab(
			'tab_button_cta_11_n',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);
		$this->add_control(
			'btn_text_cta_11_color_n',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'btn_text_cta_11_bg_n',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap',
			)
		);
		$this->add_control(
			'btn_text_cta_11_border_n',
			array(
				'label'     => esc_html__( 'Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_text_cta_11_dots_head_n',
			array(
				'label'     => esc_html__( 'Dots Color', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'btn_text_cta_11_dots_bg_n',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap::before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap::after',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_button_cta_11_h',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_control(
			'btn_text_cta_11_color_h',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'btn_text_cta_11_bg_h',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap:hover',
			)
		);
		$this->add_control(
			'btn_text_cta_11_border_h',
			array(
				'label'     => esc_html__( 'Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_text_cta_11_dots_head_h',
			array(
				'label'     => esc_html__( 'Dots Color', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'btn_text_cta_11_dots_bg_h',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap:hover::before,
				{{WRAPPER}} .pt_plus_adv_button.ab-cta.tp_cta_st_11 .adv-button-link-wrap:hover::after',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*style cta 11 section end*/

		/*style cta 14 section start*/
		$this->start_controls_section(
			'section_styling_cta_14',
			array(
				'label'     => esc_html__( 'Advanced Button Style', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type'   => 'cta',
					'cta_button_style' => 'tp_cta_st_14',
				),
			)
		);
		$this->add_control(
			'st14_font_family',
			array(
				'label'   => esc_html__( 'Font Family', 'theplus' ),
				'type'    => \Elementor\Controls_Manager::FONT,
				'default' => 'Open Sans',
			)
		);
		$this->add_control(
			'st14_text_size',
			array(
				'label'   => esc_html__( 'Text Size', 'theplus' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 5,
				'max'     => 500,
				'step'    => 1,
				'default' => 14,
			)
		);
		$this->add_control(
			'st14_text_weight',
			array(
				'label'   => esc_html__( 'Font Weight', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'100'    => esc_html__( '100', 'theplus' ),
					'200'    => esc_html__( '200', 'theplus' ),
					'300'    => esc_html__( '300', 'theplus' ),
					'400'    => esc_html__( '400', 'theplus' ),
					'500'    => esc_html__( '500', 'theplus' ),
					'600'    => esc_html__( '600', 'theplus' ),
					'700'    => esc_html__( '700', 'theplus' ),
					'800'    => esc_html__( '800', 'theplus' ),
					'900'    => esc_html__( '900', 'theplus' ),
					''       => esc_html__( 'Default', 'theplus' ),
					'bold'   => esc_html__( 'Bold', 'theplus' ),
					'normal' => esc_html__( 'Normal', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'st14_text_color',
			array(
				'label'   => esc_html__( 'Text Color', 'theplus' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'global'  => array(
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
				),
				'default' => '#fff',
			)
		);
		$this->add_control(
			'st14_color_2',
			array(
				'label'   => esc_html__( 'Top Layer Background Color', 'theplus' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'global'  => array(
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
				),
				'default' => '#8072fc',
			)
		);
		$this->add_control(
			'st14_color_1',
			array(
				'label'   => esc_html__( 'Bottom Layer Background Color', 'theplus' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'global'  => array(
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
				),
				'default' => '#ff5a6e',
			)
		);
		$this->add_control(
			'st14_color_3',
			array(
				'label'   => esc_html__( 'Hover Color', 'theplus' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'global'  => array(
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
				),
				'default' => '#6fc784',
			)
		);
		$this->end_controls_section();

		/*style download button section start*/
		$this->start_controls_section(
			'section_styling_download',
			array(
				'label'     => esc_html__( 'Advanced Button Style', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type'        => 'download',
					'download_button_style' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4', 'tp_download_st_5', 'tp_download_st_3' ),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'st5_typography',
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .tp-meter:before,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .tp-meter.is-done:after,
				{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap span',
				'condition' => array(
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4' ),
				),
			)
		);
		$this->add_control(
			'st5__color_n',
			array(
				'label'     => esc_html__( 'Normal Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap,
					{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap span' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4' ),
				),
			)
		);
		$this->add_control(
			'st5_color_h',
			array(
				'label'     => esc_html__( 'Hover Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap:hover,
					{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:hover span' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4' ),
				),
			)
		);
		$this->add_control(
			'dst3_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:before,
					{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:after' => 'border-color: {{VALUE}};',
				),
				'separator' => 'before',
				'condition' => array(
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4', 'tp_download_st_5' ),
				),
			)
		);
		$this->add_control(
			'dst3_icon_bg',
			array(
				'label'     => esc_html__( 'Icon Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:before,
					{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:after' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4', 'tp_download_st_5' ),
				),
			)
		);
		$this->add_control(
			'dst3_icon_bg_h',
			array(
				'label'     => esc_html__( 'Icon Hover Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:hover:before,
					{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:hover:after' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4', 'tp_download_st_5' ),
				),
			)
		);
		$this->add_control(
			'st5_complete_txt_color',
			array(
				'label'     => esc_html__( 'Complete Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .tp-meter.is-done:after' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_3', 'tp_download_st_4' ),
				),
			)
		);
		$this->add_responsive_control(
			'd_st4_iconsize',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Icon Size', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					),
				),
				'separator'   => 'after',
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap i::after' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(

					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_3', 'tp_download_st_5' ),
				),
			)
		);
		$this->add_control(
			'download_n_color',
			array(
				'label'     => esc_html__( 'Normal Icon Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap svg polyline,
					{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap svg path,
					{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap #arrow path, {{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap #arrow polyline' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap i::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tp_download_st_5 .adv-button-link-wrap .icon-download' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tp_download_st_5 .adv-button-link-wrap .icon-download:after' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .tp_download_st_5 .adv-button-link-wrap .icon-download:before' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_3' ),
				),
			)
		);
		$this->add_control(
			'download_h_color',
			array(
				'label'     => esc_html__( 'Hover Icon Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap:hover #arrow path, {{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap:hover #arrow polyline' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap:hover i::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tp_download_st_5 .adv-button-link-wrap:hover .icon-download' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tp_download_st_5 .adv-button-link-wrap:hover .icon-download:after' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .tp_download_st_5 .adv-button-link-wrap:hover .icon-download:before' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_3' ),
				),
			)
		);
		$this->add_control(
			'download_a_color',
			array(
				'label'     => esc_html__( 'Download Icon Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap.downloaded svg path#check,
					{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap svg#check' => 'stroke: {{VALUE}};',
				),
				'condition' => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_3', 'tp_download_st_4', 'tp_download_st_5' ),
				),
			)
		);
		$this->add_control(
			'download_btn_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap.load #border	' => 'stroke: {{VALUE}};',
				),
				'condition' => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_3', 'tp_download_st_4', 'tp_download_st_5' ),
				),
			)
		);
		$this->start_controls_tabs( 'tabs_download_style' );

		$this->start_controls_tab(
			'tab_download_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'download_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap span',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'download_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_5 .tp-meter,
				{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap span',
				'separator' => 'before',
				'condition' => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_2' ),
				),
			)
		);
		$this->add_responsive_control(
			'download_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_5 .tp-meter,
				{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_2' ),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'download_shadow',
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap,
				{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap span',
				'separator' => 'before',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_download_Hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'download_background_hover',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_5 .tp-meter,
				{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:hover span',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'download_border_hover',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap:hover',
				'separator' => 'before',
				'condition' => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_2', 'tp_download_st_3', 'tp_download_st_5' ),
				),
			)
		);
		$this->add_control(
			'download_border_hover_st5',
			array(
				'label'     => esc_html__( 'Border Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_5 .tp-meter,
					{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:hover span' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'download_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap:hover,
					{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap.downloaded:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'ab_button_type'         => 'download',
					'download_button_style!' => array( 'tp_download_st_2', 'tp_download_st_3', 'tp_download_st_5' ),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'download_shadow_hover',
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_1 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_2 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_4 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.tp_download_st_5 .adv-button-link-wrap:hover,
				{{WRAPPER}} .pt_plus_adv_button.ab-download.tp_download_st_5 .tp-meter,
				{{WRAPPER}} .tp_download_st_3 .adv-button-link-wrap:hover span',
				'separator' => 'before',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*style download button section end*/

		/*Extra text download btn st 1-2-4 start*/
		$this->start_controls_section(
			'section_ext_btn_txt_124_dwnld',
			array(
				'label'     => esc_html__( 'Download Text Style', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type'        => 'download',
					'download_button_style' => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4' ),
				),
			)
		);
		$this->add_responsive_control(
			'ext_btn_124_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ext_btn_124_top_offset',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Top Offset', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => -250,
						'max'  => 250,
						'step' => 1,
					),
				),
				'separator'   => 'before',
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt' => 'top: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_responsive_control(
			'ext_btn_124_right_offset',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Right Offset', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => -250,
						'max'  => 250,
						'step' => 1,
					),
				),
				'separator'   => 'before',
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'ext_btn_124_typography',
				'label'     => esc_html__( 'Typography', 'theplus' ),
				'global'    => array(
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt',
				'separator' => 'before',
			)
		);
		$this->start_controls_tabs( 'tabs_ext_btn_124' );
		$this->start_controls_tab(
			'tab_ext_btn_124',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);
		$this->add_control(
			'tab_ext_btn_124_color_n',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tab_ext_btn_124_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'tab_ext_btn_124_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'tab_ext_btn_124_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tab_ext_btn_124_shadow',
				'selector' => '{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_ext_btn_124_h',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_control(
			'tab_ext_btn_124_color_h',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tab_ext_btn_124_background_h',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt:hover',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'tab_ext_btn_124_border_h',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt:hover',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'tab_ext_btn_124_radius_h',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tab_ext_btn_124_shadow_h',
				'selector' => '{{WRAPPER}} .pt-plus-adv-button-wrapper .adv_btn_ext_txt:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Extra text download btn st 1-2-4 end*/

		/*style download st3 start*/
		$this->start_controls_section(
			'section_styling_d_st3',
			array(
				'label'     => esc_html__( 'Box Content', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ab_button_type!'       => 'cta',
					'cta_button_style!'     => array( 'tp_download_st_1', 'tp_download_st_2', 'tp_download_st_4', 'tp_download_st_5' ),
					'download_button_style' => 'tp_download_st_3',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_download_style_3' );

		$this->start_controls_tab(
			'tab_download_3_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'download_3_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'download_3_border',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'download_3_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'download_3_shadow',
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap',
				'separator' => 'before',
			)
		);
		$this->end_controls_tab();
			$this->start_controls_tab(
				'tab_download_3_hover',
				array(
					'label' => esc_html__( 'Hover', 'theplus' ),
				)
			);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'download_3_h_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap:hover',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'download_3_border_h',
				'label'     => esc_html__( 'Border', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap:hover',
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'download_3_radius_h',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'download_3_shadow_h',
				'selector'  => '{{WRAPPER}} .pt_plus_adv_button.tp_download_st_3 .adv-button-link-wrap:hover',
				'separator' => 'before',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
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
	 * Render Accrordion.
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$data_class = '';

		$ab_button_type   = $settings['ab_button_type'];
		$cta_button_style = $settings['cta_button_style'];

		$download_button_style = $settings['download_button_style'];

		$download_file_name = ! empty( $settings['download_file_name'] ) ? $settings['download_file_name'] : 'download';

		$tooltip_alignment = ! empty( $settings['tooltip_alignment'] ) ? $settings['tooltip_alignment'] : 'st13_tt_align_left';

		/*--On Scroll View Animation ---*/
		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation-attr.php';

		/*--Plus Extra ---*/
		$PlusExtra_Class = 'plus-adv-button-widget';
		include THEPLUS_PATH . 'modules/widgets/theplus-widgets-extra.php';
		/*--Plus Extra ---*/

		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['button_link'] );
		}

		$lz1 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['button_background_image'], $settings['button_hover_background_image'] ) : '';
		$this->add_render_attribute( 'button', 'class', 'adv-button-link-wrap ' . $lz1 );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( 'download' === $ab_button_type ) {
			$this->add_render_attribute( 'button', 'download', '' . sanitize_text_field( $download_file_name ) . '' );
		}

		$data_class = ' ab-' . $ab_button_type . ' ';
		if ( 'cta' === $ab_button_type ) {
			$data_class .= ' ' . $cta_button_style . ' ';
		}

		if ( 'download' === $ab_button_type ) {
			$data_class .= ' ' . $download_button_style . ' ';
		}

		$tt_position_class = '';
		if ( ! empty( $cta_button_style ) && 'tp_cta_st_13' === $cta_button_style ) {
			if ( 'st13_tt_align_left' === $tooltip_alignment ) {
				$tt_position_class = 'st13_tt_align_left';
			} elseif ( 'st13_tt_align_right' === $tooltip_alignment ) {
				$tt_position_class = 'st13_tt_align_right';
			}
		}

		if ( 'tp_cta_st_14' === $cta_button_style && ! empty( $settings['button_align'] ) ) {

			if ( 'flex-start' === $settings['button_align'] ) {
				$tt_position_class = 'st14_left';
			}

			if ( 'flex-end' === $settings['button_align'] ) {
				$tt_position_class = 'st14_right';
			}

			if ( 'center' === $settings['button_align'] ) {
				$tt_position_class = 'st14_center';
			}

			$data_attr  = 'data-st14txtcolor=' . esc_attr( $settings['st14_text_color'] ) . '';
			$data_attr .= ' data-st14fontfamily=\'' . esc_attr( $settings['st14_font_family'] ) . '\'';
			$data_attr .= ' data-st14textsize=' . esc_attr( $settings['st14_text_size'] ) . '';
			$data_attr .= ' data-st14textweight=' . esc_attr( $settings['st14_text_weight'] ) . '';
		} elseif ( 'download' === $ab_button_type ) {
			$data_attr = ' data-dfname=' . sanitize_text_field( $settings['download_file_name'] ) . '';
		} else {
			$data_attr = '';
		}

		$down_btn_align = '';
		if ( ! empty( $settings['button_align'] ) && 'download' === $ab_button_type ) {

			if ( 'flex-start' === $settings['button_align'] ) {
				$down_btn_align = 'dba_left';
			}

			if ( 'flex-end' === $settings['button_align'] ) {
				$down_btn_align = 'dba_right';
			}

			if ( 'center' === $settings['button_align'] ) {
				$down_btn_align = 'dba_center';
			}
		}

		$id = $this->get_id();

		$uid_advbutton = uniqid( 'advbutton' );

		$adv_button = '<div class="pt-plus-adv-button-wrapper ' . $animated_class . '" ' . $animation_attr . '>';

		if ( ! empty( $settings['common_button_text'] ) ) {
			if ( 'download' === $ab_button_type && ( 'tp_download_st_1' === $download_button_style || 'tp_download_st_2' === $download_button_style || 'tp_download_st_4' === $download_button_style ) ) {
				$lz2 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['tab_ext_btn_124_background_image'], $settings['tab_ext_btn_124_background_h_image'] ) : '';

				$adv_button .= '<div class="adv_btn_ext_txt ' . esc_attr( $lz2 ) . '">' . esc_html( $settings['common_button_text'] ) . '</div>';
			}
		}

		$adv_button .= '<div id="' . esc_attr( $uid_advbutton ) . '" class="pt_plus_adv_button ' . esc_attr( $data_class ) . ' ' . esc_attr( $tt_position_class ) . ' ' . esc_attr( $down_btn_align ) . '" ' . $data_attr . '>';

		if ( 'tp_cta_st_4' === $cta_button_style ) {
			$adv_button .= '<div class="pulsing"></div>';
		}

		$adv_button .= '<a ' . $this->get_render_attribute_string( 'button' ) . '>';
		$adv_button .= $this->render_text();
		$adv_button .= '</a>';

		if ( 'tp_download_st_5' === $download_button_style ) {
			$adv_button .= '<div class="tp-meter"><span class="tp-meter-progress"></span></div>';
		}

			$adv_button .= '</div>';

		$adv_button .= '</div>';

		if ( 'cta' === $ab_button_type && 'tp_cta_st_9' === $cta_button_style ) {
				$adv_button .= '<style>#' . esc_attr( $uid_advbutton ) . '.pt_plus_adv_button.ab-cta.tp_cta_st_9 .adv-btn-parrot{animation: tp-blink-' . esc_attr( $id ) . ' 0.8s infinite;}
				@keyframes tp-blink-' . esc_attr( $id ) . ' {
					  25%,
						75% {
						color: transparent;
					  }
					  40%,
						60% {
						color: ' . esc_attr( $settings['btn_text_extra_color'] ) . ';
					  }
					}</style>';
		}

		echo $before_content . $adv_button . $after_content;
	}

	/**
	 * Render Accrordion.
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$common_button_text = '';
		$ab_button_type     = ! empty( $settings['ab_button_type'] ) ? $settings['ab_button_type'] : 'cta';
		$cta_button_style   = $settings['cta_button_style'];

		$download_button_style = $settings['download_button_style'];

		$button_text   = $settings['common_button_text'];
		$button_text_1 = $settings['common_button_text_2'];
		$button_text_2 = $settings['common_button_text_3'];

		$st10_button_width  = ( isset( $settings['st10_button_width'] ) ) ? $settings['st10_button_width'] : 150;
		$st10_button_height = ( isset( $settings['st10_button_height'] ) ) ? $settings['st10_button_height'] : 55;
		$db_button_text_1   = $settings['db_common_button_text_2'];

		if ( 'cta' === $ab_button_type ) {
			if ( 'tp_cta_st_1' === $cta_button_style || 'tp_cta_st_2' === $cta_button_style || 'tp_cta_st_3' === $cta_button_style || 'tp_cta_st_4' === $cta_button_style || 'tp_cta_st_12' === $cta_button_style ) {
				$common_button_text = '<span>' . esc_html( $button_text ) . '</span>';
			}
			if ( 'tp_cta_st_5' === $cta_button_style ) {
				$common_button_text = '<p class="tp-cta-st5-text">' . esc_html( $button_text ) . '</p>';
			}
			if ( 'tp_cta_st_6' === $cta_button_style ) {
				$common_button_text = esc_html( $button_text ) . '<marquee scrollamount="' . esc_attr( $settings['marquee_speed'] ) . '" direction="' . esc_attr( $settings['marquee_direction'] ) . '" ><span>' . esc_html( $button_text_1 ) . '</span></marquee>';
			}
			if ( 'tp_cta_st_7' === $cta_button_style ) {
				$common_button_text = esc_html( $button_text ) . '<div class="hands"></div>';
			}
			if ( 'tp_cta_st_8' === $cta_button_style ) {
				$common_button_text = esc_html( $button_text ) . '<div class="adv-btn-emoji"></div><div class="adv-btn-emoji"></div><div class="adv-btn-emoji"></div>';
			}
			if ( 'tp_cta_st_9' === $cta_button_style ) {
				$common_button_text = esc_html( $button_text ) . '<div class="adv-btn-parrot"></div><div class="adv-btn-parrot"></div><div class="adv-btn-parrot"></div><div class="adv-btn-parrot"></div><div class="adv-btn-parrot"></div><div class="adv-btn-parrot"></div>';
			}
			if ( 'tp_cta_st_10' === $cta_button_style ) {
				$common_button_text = '<span>' . esc_html( $button_text ) . '</span><svg><polyline class="tp-cpt-btn01" points="0 0, ' . esc_attr( $st10_button_width ) . ' 0, ' . esc_attr( $st10_button_width ) . ' ' . esc_attr( $st10_button_height ) . ', 0 ' . esc_attr( $st10_button_height ) . ', 0 0"></polyline><polyline class="tp-cpt-btn02" points="0 0, ' . esc_attr( $st10_button_width ) . ' 0, ' . esc_attr( $st10_button_width ) . ' ' . esc_attr( $st10_button_height ) . ', 0 ' . esc_attr( $st10_button_height ) . ', 0 0"></polyline></svg>';
			}
			if ( 'tp_cta_st_11' === $cta_button_style ) {
				$common_button_text = esc_html( $button_text );
			}
			if ( 'tp_cta_st_13' === $cta_button_style ) {
				$common_button_text = esc_html( $button_text ) . '<span>' . esc_html( $button_text_1 ) . '</span>';
			}

			if ( 'tp_cta_st_14' === $cta_button_style ) {
				$st14width = '';
				$detect    = new Mobile_Detect();

				$dek_width = ! empty( $settings['st14_button_width'] ) ? $settings['st14_button_width'] : '';
				$tab_width = ! empty( $settings['st14_button_width_tablet'] ) ? $settings['st14_button_width_tablet'] : $dek_width;
				$mob_width = ! empty( $settings['st14_button_width_mobile'] ) ? $settings['st14_button_width_mobile'] : $tab_width;

				if ( $detect->isTablet() && $tab_width ) {
					$st14width = $tab_width;
				} elseif ( $detect->isMobile() && $mob_width ) {
					$st14width = $mob_width;
				} else {
					$st14width = $dek_width;
				}

				$common_button_text = '<svg class="liquid-button" data-text="' . esc_attr( $button_text ) . '" data-width="' . esc_attr( $st14width ) . '"							  data-force-factor="0.1" data-layer-1-viscosity="0.5" data-layer-2-viscosity="0.4" data-layer-1-mouse-force="400" data-layer-2-mouse-force="500" data-layer-1-force-limit="1" data-layer-2-force-limit="2" data-color1="' . esc_attr( $settings['st14_color_1'] ) . '" data-color2="' . esc_attr( $settings['st14_color_2'] ) . '" data-color3="' . esc_attr( $settings['st14_color_3'] ) . '"></svg>';
			}
		} elseif ( 'download' === $ab_button_type ) {
			if ( 'tp_download_st_1' === $download_button_style ) {
				$common_button_text = '<svg width="22px" height="16px" viewBox="0 0 22 16"><path d="M2,10 L6,13 L12.8760559,4.5959317 C14.1180021,3.0779974 16.2457925,2.62289624 18,3.5 L18,3.5 C19.8385982,4.4192991 21,6.29848669 21,8.35410197 L21,10 C21,12.7614237 18.7614237,15 16,15 L1,15" id="check"></path><polyline points="4.5 8.5 8 11 11.5 8.5" class="svg-out"></polyline><path d="M8,1 L8,11" class="svg-out"></path></svg>';
			}
			if ( 'tp_download_st_2' === $download_button_style ) {
				$common_button_text = '<svg id="arrow" width="14px" height="20px" viewBox="17 14 14 20"><path d="M24,15 L24,32"></path><polyline points="30 27 24 33 18 27"></polyline></svg><svg id="check" width="21px" height="15px" viewBox="13 17 21 15"><polyline points="32.5 18.5 20 31 14.5 25.5"></polyline></svg><svg  id="border" width="48px" height="48px" viewBox="0 0 48 48"><path d="M24,1 L24,1 L24,1 C36.7025492,1 47,11.2974508 47,24 L47,24 L47,24 C47,36.7025492 36.7025492,47 24,47 L24,47 L24,47 C11.2974508,47 1,36.7025492 1,24 L1,24 L1,24 C1,11.2974508 11.2974508,1 24,1 L24,1 Z"></path></svg>';
			}
			if ( 'tp_download_st_3' === $download_button_style ) {
				$common_button_text = '<span>' . esc_html( $db_button_text_1 ) . '</span><span>' . esc_html( $button_text ) . '</span>';
			}
			if ( 'tp_download_st_4' === $download_button_style ) {
				$common_button_text = '<i class="fa"></i>';
			}
			if ( 'tp_download_st_5' === $download_button_style ) {
				$common_button_text = '' . esc_html( $button_text ) . '<span class="icon-wrap"><i class="icon-download"></i></span>';
			}
		}

		return $common_button_text;
	}

	/**
	 * Content_template
	 *
	 * @since 3.0.0
	 * @version 5.4.1
	 */
	protected function content_template() {}
}
