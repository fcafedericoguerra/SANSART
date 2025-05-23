<?php
/**
 * Widget Name: Woo Thank You
 * Description: Woo Thank You
 * Author: Posimyth
 * Author URI: http://posimyth.com
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
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use TheplusAddons\Theplus_Element_Load;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class ThePlus_Woo_Thank_You
 */
class ThePlus_Woo_Thank_You extends Widget_Base {

	public $tp_doc = THEPLUS_TPDOC;

	/**
	 * Helpdesk Link For Need help.
	 *
	 * @var tp_help of the class.
	 */
	public $tp_help = THEPLUS_HELP;	

	/**
	 * Get Widget Name.
	 *
	 * @since 5.3.5
	 * @version 5.4.2
	 */
	public function get_name() {
		return 'tp-woo-thank-you';
	}

	/**
	 * Get Widget Title.
	 *
	 * @since 5.3.5
	 * @version 5.4.2
	 */
	public function get_title() {
		return esc_html__( 'Woo Thank You', 'theplus' );
	}

	/**
	 * Get Widget Icon.
	 *
	 * @since 5.3.5
	 * @version 5.4.2
	 */
	public function get_icon() {
		return 'fa fa-gift theplus_backend_icon';
	}

	/**
	 * Get Widget categories.
	 *
	 * @since 5.3.5
	 * @version 5.4.2
	 */
	public function get_categories() {
		return array( 'plus-woo-builder' );
	}

	/**
	 * Get Widget keywords.
	 *
	 * @since 5.3.5
	 * @version 5.4.2
	 */
	public function get_keywords() {
		return array( 'Thank You', 'woo thankyou', 'thankyou', 'woocommerce', 'post', 'product' );
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
	 * Register controls.
	 *
	 * @since 5.3.5
	 * @version 5.4.2
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_thankyou_page',
			array(
				'label' => esc_html__( 'Woo Thank You', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_order_main',
			array(
				'label'     => wp_kses_post( "Order Status Message <a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "edit-woocommerce-thank-you-page-in-elementor/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>" ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_or_txt',
			array(
				'label'     => esc_html__( 'Success Message', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Your order has been received.', 'theplus' ),
				'condition' => array(
					'tp_thankyou_order_main' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'tp_thankyou_or_txt_align',
			array(
				'label'     => esc_html__( 'Alignment', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'tp_thankyou_order_main' => 'yes',
				),
			)
		);
		$this->add_control(
			'tp_thankyou_order_overview',
			array(
				'label'     => esc_html__( 'Order Meta Details', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'condition' => array(
					'tp_thankyou_order_main' => 'yes',
				),
			)
		);
		$this->add_control(
			'tp_t_oo_layout',
			array(
				'label'     => esc_html__( 'Layout', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'layout1',
				'options'   => array(
					'layout1' => esc_html__( 'Layout 1', 'theplus' ),
					'layout2' => esc_html__( 'Layout 2', 'theplus' ),
				),
				'condition' => array(
					'tp_thankyou_order_main'     => 'yes',
					'tp_thankyou_order_overview' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'tp_t_oo_layout_align',
			array(
				'label'     => esc_html__( 'Alignment', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details' => 'text-align: {{VALUE}};display:inline-flex;',
				),
				'condition' => array(
					'tp_thankyou_order_main'     => 'yes',
					'tp_thankyou_order_overview' => 'yes',
					'tp_t_oo_layout'             => 'layout1',
				),
			)
		);
		$this->add_responsive_control(
			'tp_t_oo_layout_aligncontent',
			array(
				'label'     => esc_html__( 'Content Alignment', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details' => 'justify-content: {{VALUE}};width:100%;',
				),
				'condition' => array(
					'tp_thankyou_order_main'     => 'yes',
					'tp_thankyou_order_overview' => 'yes',
					'tp_t_oo_layout'             => 'layout1',
				),
			)
		);
		$this->add_responsive_control(
			'tp_t_oo_layout_width',
			array(
				'label'      => esc_html__( 'Max Width', 'theplus' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 300,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li' => 'width: {{SIZE}}{{UNIT}};float: unset;display: flex;
					align-items: center;',
				),
				'condition'  => array(
					'tp_thankyou_order_main'     => 'yes',
					'tp_thankyou_order_overview' => 'yes',
					'tp_t_oo_layout'             => 'layout2',
				),
			)
		);
		$this->add_control(
			'tp_thankyou_order_detail',
			array(
				'label'     => esc_html__( 'Order Items List', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'tp_thankyou_order_detail_head',
			array(
				'label'     => esc_html__( 'Heading', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Order details', 'theplus' ),
				'condition' => array(
					'tp_thankyou_order_detail' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'tp_thankyou_order_detail_head_align',
			array(
				'label'     => esc_html__( 'Alignment', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'tp_thankyou_order_detail' => 'yes',
				),
			)
		);
		$this->add_control(
			'tp_thankyou_billing',
			array(
				'label'     => esc_html__( 'Billing Address', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'tp_thankyou_shipping',
			array(
				'label'     => esc_html__( 'Shipping Address', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'label_thankyou_page',
			array(
				'label' => esc_html__( 'Label', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_order_fail',
			array(
				'label'   => esc_html__( 'Order Fail', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'theplus' ),
			)
		);

		$this->add_control(
			'tp_thankyou_order_pay',
			array(
				'label'   => esc_html__( 'Pay', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Pay', 'theplus' ),
			)
		);

		$this->add_control(
			'tp_thankyou_myaccount',
			array(
				'label'   => esc_html__( 'My account', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'My account', 'theplus' ),
			)
		);

		$this->add_control(
			'tp_thankyou_order_number',
			array(
				'label'   => esc_html__( 'Order Number', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Order number:', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_order_date',
			array(
				'label'   => esc_html__( 'Date', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Date:', 'theplus' ),
			)
		);

		$this->add_control(
			'tp_thankyou_order_email',
			array(
				'label'   => esc_html__( 'Email', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Email:', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_order_total',
			array(
				'label'   => esc_html__( 'Total', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Total:', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_payment_method',
			array(
				'label'   => esc_html__( 'Payment method', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Payment method:', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_product',
			array(
				'label'   => esc_html__( 'Product', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Product', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_total',
			array(
				'label'   => esc_html__( 'Total', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Total', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_note',
			array(
				'label'   => esc_html__( 'Note', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Note:', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_billing_address',
			array(
				'label'   => esc_html__( 'Billing address', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Billing address', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_billing_address_na',
			array(
				'label'   => esc_html__( 'N/A', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'N/A', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_shipping_address',
			array(
				'label'   => esc_html__( 'Shipping address', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Shipping address', 'theplus' ),
			)
		);
		$this->add_control(
			'tp_thankyou_shipping_address_na',
			array(
				'label'   => esc_html__( 'N/A', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'N/A', 'theplus' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_received_text_style',
			array(
				'label'     => esc_html__( 'Order Status Message', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tp_thankyou_order_main' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'ort_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ort_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ort_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p',
			)
		);
		$this->add_control(
			'ort_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'ort_bg',
				'label'    => esc_html__( 'Background', 'theplus' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p',
			)
		);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'ort_bg_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p',
				)
			);
			$this->add_responsive_control(
				'ort_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'ort_shadow',
					'label'    => esc_html__( 'Box Shadow', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received,{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-received p',
				)
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_thankyou_order_meta_style',
			array(
				'label' => esc_html__( 'Order Meta Details', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'ort_label_heading',
			array(
				'label'     => esc_html__( 'Label Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ort_label_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li',

			)
		);
		$this->add_control(
			'ort_label_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'ort_text_heading',
			array(
				'label'     => esc_html__( 'Text Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ort_text_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li strong',

			)
		);
		$this->add_control(
			'ort_text_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li strong' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'ort_sep_tab_heading',
			array(
				'label'     => esc_html__( 'Separate Tab Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'ort_sep_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ort_sep_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'ort_sep_bg',
				'label'    => esc_html__( 'Background', 'theplus' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li',
			)
		);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'rt_sep_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li',
				)
			);
			$this->add_responsive_control(
				'ort_sep_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'ort_sep_shadow',
					'label'    => esc_html__( 'Box Shadow', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details li',
				)
			);
		$this->add_control(
			'ort_full_tab_heading',
			array(
				'label'     => esc_html__( 'Full Tab Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'ort_full_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ort_full_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'ort_full_bg',
				'label'    => esc_html__( 'Background', 'theplus' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rt_full_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details',
			)
		);
		$this->add_responsive_control(
			'ort_full_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'ort_full_shadow',
				'label'    => esc_html__( 'Box Shadow', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper ul.order_details',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_thankyou_order_detail_style',
			array(
				'label'     => esc_html__( 'Order Items List', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tp_thankyou_order_detail' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'od_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'od_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'od_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title',
			)
		);
		$this->add_control(
			'od_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'od_bg',
				'label'    => esc_html__( 'Background', 'theplus' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title',
			)
		);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'od_bg_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title',
				)
			);
			$this->add_responsive_control(
				'od_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'od_shadow',
				'label'    => esc_html__( 'Box Shadow', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-order-details__title',
			)
		);
		$this->add_control(
			'odt_table_head_heading',
			array(
				'label'     => esc_html__( 'Order Details Table Heading Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'odt_head_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .order_details thead tr th',

			)
		);
		$this->add_control(
			'odt_head_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .order_details thead tr th' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'od_table_body_head_heading',
			array(
				'label'     => esc_html__( 'Order Details Table Body Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'odt_body_head_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td a,
								{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td strong,
								{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td p',

			)
		);
		$this->add_control(
			'odt_body_head_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td a,
					{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td strong,
					{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td p' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'od_table_bodys_txt_heading',
			array(
				'label'     => esc_html__( 'Body Sub Text Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'odt_bodys_txt_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td span',

			)
		);
		$this->add_control(
			'odt_bodys_txt_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tbody tr td span' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'od_table_foot_head_heading',
			array(
				'label'     => esc_html__( 'Order Details Table Footer Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'odt_f_head_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr th',

			)
		);
		$this->add_control(
			'odt_f_head_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr th' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'od_table_fs_txt_heading',
			array(
				'label'     => esc_html__( 'Footer Sub Text Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'odt_fs_txt_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr span,
				{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr td',

			)
		);
		$this->add_control(
			'odt_fs_txt_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr span,
				{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr td' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'od_table_fss_txt_heading',
			array(
				'label'     => esc_html__( 'Footer Sub Small Text Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'odt_fss_txt_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr small',

			)
		);
		$this->add_control(
			'odt_fss_txt_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .order_details tfoot tr small' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'od_table_heading',
			array(
				'label'     => esc_html__( 'Table Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'odt_table_padding',
			array(
				'label'      => esc_html__( 'Outer Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'odt_inner_table_padding',
			array(
				'label'      => esc_html__( 'Inner Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tr th,
					{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'od_t_bg',
				'label'    => esc_html__( 'Background', 'theplus' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .order_details tr td,
					{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .order_details tr th',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'od_t__border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .order_details tr td,
				{{WRAPPER}}	.tp-woo-thankyou-wrapper .woocommerce-order-details .order_details tr th',
			)
		);
		$this->add_responsive_control(
			'odt_inner_table_borderradius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
				'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .order_details tr td,
				{{WRAPPER}}	.tp-woo-thankyou-wrapper .woocommerce-order-details .order_details tr th' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'od_table_outer_heading',
			array(
				'label'     => esc_html__( 'Outer Table Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'od_table_outer_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table',
			)
		);

		$this->add_control(
			'od_table_head_heading',
			array(
				'label'     => esc_html__( 'Head Table Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'od_table_outer_alignment',
			array(
				'label'       => esc_html__( 'Heading Alignment', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table thead' => 'text-align : {{VALUE}}',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'od_table_head_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table thead',
			)
		);
		$this->add_control(
			'od_table_body_heading',
			array(
				'label'     => esc_html__( 'Body Table Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'od_table_pn_alignment',
			array(
				'label'       => esc_html__( 'Product Name', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tbody .product-name' => 'text-align : {{VALUE}}',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);
		$this->add_control(
			'od_table_pp_alignment',
			array(
				'label'       => esc_html__( 'Product Total', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tbody .product-total' => 'text-align : {{VALUE}}',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'od_table_body_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tbody',
			)
		);
		$this->add_control(
			'od_table_body_i_heading',
			array(
				'label'     => esc_html__( 'Body Inner Table Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'od_table_body_i_alignment',
			array(
				'label'       => esc_html__( 'Body Inner Left', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tfoot th:not(:last-child)' => 'text-align : {{VALUE}}',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);
		$this->add_control(
			'od_table_body_ir_alignment',
			array(
				'label'       => esc_html__( 'Body Inner Right', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tfoot tr:not(:last-child)' => 'text-align : {{VALUE}}',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'od_table_body_i_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tfoot tr:not(:last-child)',
			)
		);
		$this->add_control(
			'od_table_foot_heading',
			array(
				'label'     => esc_html__( 'Footer Table Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'od_table_f_i_alignment',
			array(
				'label'       => esc_html__( 'Footer Inner Left', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tfoot tr:last-child th' => 'text-align : {{VALUE}}',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);
		$this->add_control(
			'od_table_f_ir_alignment',
			array(
				'label'       => esc_html__( 'Footer Right', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tfoot tr:last-child td' => 'text-align : {{VALUE}}',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'od_table_foot_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-order-details .woocommerce-table tfoot tr:last-child',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_thankyou_dwn_style',
			array(
				'label' => esc_html__( 'Downloadable Product List', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'product_dwnld_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-order-downloads' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'product_dwnld_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-order-downloads' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'product_dwnld_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_dwnld_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads',
			)
		);
		$this->add_responsive_control(
			'product_dwnld_br',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-order-downloads' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_dwnld_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads',
			)
		);
		$this->add_control(
			'product_dwnld_heading_title',
			array(
				'label'     => esc_html__( 'Heading Title Style', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pdht_typo',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-order-downloads__title',

			)
		);
		$this->add_control(
			'pdht_color',
			array(
				'label'     => esc_html__( 'Heading Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-order-downloads__title' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'product_dwnld_table',
			array(
				'label'     => esc_html__( 'Table Heading Style', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pdth_typo',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads thead th',

			)
		);
		$this->add_control(
			'pdth_color',
			array(
				'label'     => esc_html__( 'Heading Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads thead th' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'product_dwnld_table_body',
			array(
				'label'     => esc_html__( 'Table Body Style', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pdtb_typo',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td,{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td a',

			)
		);
		$this->add_control(
			'pdtb_color',
			array(
				'label'     => esc_html__( 'Body Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td,{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td a' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'pdtb_color_link',
			array(
				'label'     => esc_html__( 'Link Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td a' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'product_dwnld_button',
			array(
				'label'     => esc_html__( 'Button Style', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'db_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'db_btn_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Button Width', 'theplus' ),
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'render_type' => 'ui',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'db_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file',

			)
		);
		$this->start_controls_tabs( 'db_tabs' );
			$this->start_controls_tab(
				'db_normal',
				array(
					'label' => esc_html__( 'Normal', 'theplus' ),
				)
			);
			$this->add_control(
				'db_n_color',
				array(
					'label'     => esc_html__( 'Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file' => 'color: {{VALUE}}',
					),

				)
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'db_n_bg',
					'label'    => esc_html__( 'Background', 'theplus' ),
					'types'    => array( 'classic', 'gradient' ),
					'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file',
				)
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'db_n_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file',
				)
			);
			$this->add_responsive_control(
				'db_n_br',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'db_n_shadow',
					'label'    => esc_html__( 'Box Shadow', 'theplus' ),
					'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file',
				)
			);
			$this->end_controls_tab();
			$this->start_controls_tab(
				'db_hover',
				array(
					'label' => esc_html__( 'Hover', 'theplus' ),
				)
			);
			$this->add_control(
				'db_h_color',
				array(
					'label'     => esc_html__( 'Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file:hover' => 'color: {{VALUE}}',
					),

				)
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'db_h_bg',
					'label'    => esc_html__( 'Background', 'theplus' ),
					'types'    => array( 'classic', 'gradient' ),
					'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file:hover',
				)
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'db_h_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file:hover',
				)
			);
			$this->add_responsive_control(
				'db_h_br',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'db_h_shadow',
					'label'    => esc_html__( 'Box Shadow', 'theplus' ),
					'selector' => '{{WRAPPER}} .woocommerce-order-downloads .woocommerce-table--order-downloads .woocommerce-MyAccount-downloads-file:hover',
				)
			);
			$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_thankyou_billing_style',
			array(
				'label'     => esc_html__( 'Billing Details', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tp_thankyou_billing' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'b_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--billing-address .woocommerce-column__title',

			)
		);
		$this->add_control(
			'b_head_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--billing-address .woocommerce-column__title' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'bt_sub_text_heading',
			array(
				'label'     => esc_html__( 'Address', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bt_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--billing-address address',

			)
		);
		$this->add_control(
			'bt_head_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--billing-address address' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'bt_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--billing-address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'bt_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--billing-address' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'bt_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--billing-address',
			)
		);
		$this->add_responsive_control(
			'bt_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--billing-address' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_thankyou_shipping_style',
			array(
				'label'     => esc_html__( 'Shipping Details', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'tp_thankyou_shipping' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 's_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--shipping-address .woocommerce-column__title',

			)
		);
		$this->add_control(
			's_head_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--shipping-address .woocommerce-column__title' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'st_sub_text_heading',
			array(
				'label'     => esc_html__( 'Address', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'st_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--shipping-address address',

			)
		);
		$this->add_control(
			'st_head_color',
			array(
				'label'     => esc_html__( 'Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .addresses .woocommerce-column--shipping-address address' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'st_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--shipping-address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'st_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--shipping-address' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'st_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--shipping-address',
			)
		);
		$this->add_responsive_control(
			'st_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-column--shipping-address' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_od_fail_style',
			array(
				'label' => esc_html__( 'Failed Order', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'odf_head',
			array(
				'label'     => esc_html__( 'Text Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'odf_head_typo',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed',

			)
		);
		$this->add_control(
			'odf_head_color',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'odf_btn_head',
			array(
				'label'     => esc_html__( 'Button Option', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'pfb_btn_align',
			array(
				'label'     => esc_html__( 'Alignment', 'theplus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions' => 'text-align:{{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'pfb_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'pfb_btn_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'pfb_btn_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Button Width', 'theplus' ),
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'separator'   => 'before',
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pfb_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay',

			)
		);
		$this->start_controls_tabs( 'pfb_tabs' );
			$this->start_controls_tab(
				'pfb_normal',
				array(
					'label' => esc_html__( 'Normal', 'theplus' ),
				)
			);
			$this->add_control(
				'pfb_n_color',
				array(
					'label'     => esc_html__( 'Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay' => 'color: {{VALUE}}',
					),

				)
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'pfb_n_bg',
					'label'    => esc_html__( 'Background', 'theplus' ),
					'types'    => array( 'classic', 'gradient' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay',
				)
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'pfb_n_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay',
				)
			);
			$this->add_responsive_control(
				'pfb_n_br',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'pfb_n_shadow',
					'label'    => esc_html__( 'Box Shadow', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay',
				)
			);
			$this->end_controls_tab();
			$this->start_controls_tab(
				'pfb_hover',
				array(
					'label' => esc_html__( 'Hover', 'theplus' ),
				)
			);
			$this->add_control(
				'pfb_h_color',
				array(
					'label'     => esc_html__( 'Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay:hover' => 'color: {{VALUE}}',
					),

				)
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'pfb_h_bg',
					'label'    => esc_html__( 'Background', 'theplus' ),
					'types'    => array( 'classic', 'gradient' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay:hover',
				)
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'pfb_h_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay:hover',
				)
			);
			$this->add_responsive_control(
				'pfb_h_br',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'pfb_h_shadow',
					'label'    => esc_html__( 'Box Shadow', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper .woocommerce-thankyou-order-failed-actions .pay:hover',
				)
			);
			$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_content_style',
			array(
				'label' => esc_html__( 'Content Background', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'bc_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'bc_margin',
			array(
				'label'      => esc_html__( 'Margin', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-woo-thankyou-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'bc_bg',
				'label'    => esc_html__( 'Background', 'theplus' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper',
			)
		);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'bc_bg_border',
					'label'    => esc_html__( 'Border', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper',
				)
			);
			$this->add_responsive_control(
				'bc_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-woo-thankyou-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'bc_shadow',
					'label'    => esc_html__( 'Box Shadow', 'theplus' ),
					'selector' => '{{WRAPPER}} .tp-woo-thankyou-wrapper',
				)
			);
		$this->end_controls_section();
		include THEPLUS_PATH . 'modules/widgets/theplus-needhelp.php';
	}

	/**
	 * Written in PHP and HTML.
	 *
	 * @since 1.0.1
	 *
	 * @version 5.4.2
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		if ( class_exists( 'woocommerce' ) ) {

			$tp_thankyou_or_txt            = ! empty( $settings['tp_thankyou_or_txt'] ) ? $settings['tp_thankyou_or_txt'] : esc_html__( 'Your order has been received.', 'theplus' );
			$tp_thankyou_order_detail_head = ! empty( $settings['tp_thankyou_order_detail_head'] ) ? $settings['tp_thankyou_order_detail_head'] : esc_html__( 'Order details', 'theplus' );

			$tp_thankyou_order_fail   = ! empty( $settings['tp_thankyou_order_fail'] ) ? $settings['tp_thankyou_order_fail'] : esc_html__( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'theplus' );
			$tp_thankyou_order_pay    = ! empty( $settings['tp_thankyou_order_pay'] ) ? $settings['tp_thankyou_order_pay'] : esc_html__( 'Pay', 'theplus' );
			$tp_thankyou_myaccount    = ! empty( $settings['tp_thankyou_myaccount'] ) ? $settings['tp_thankyou_myaccount'] : esc_html__( 'My account', 'theplus' );
			$tp_thankyou_order_number = ! empty( $settings['tp_thankyou_order_number'] ) ? $settings['tp_thankyou_order_number'] : esc_html__( 'Order number:', 'theplus' );
			$tp_thankyou_order_date   = ! empty( $settings['tp_thankyou_order_date'] ) ? $settings['tp_thankyou_order_date'] : esc_html__( 'Date:', 'theplus' );
			$tp_thankyou_order_email  = ! empty( $settings['tp_thankyou_order_email'] ) ? $settings['tp_thankyou_order_email'] : esc_html__( 'Email:', 'theplus' );
			$tp_thankyou_order_total  = ! empty( $settings['tp_thankyou_order_total'] ) ? $settings['tp_thankyou_order_total'] : esc_html__( 'Total:', 'theplus' );

			$tp_thankyou_payment_method = ! empty( $settings['tp_thankyou_payment_method'] ) ? $settings['tp_thankyou_payment_method'] : esc_html__( 'Payment method:', 'theplus' );

			$tp_thankyou_product = ! empty( $settings['tp_thankyou_product'] ) ? $settings['tp_thankyou_product'] : esc_html__( 'Product', 'theplus' );
			$tp_thankyou_total   = ! empty( $settings['tp_thankyou_total'] ) ? $settings['tp_thankyou_total'] : esc_html__( 'Total', 'theplus' );
			$tp_thankyou_note    = ! empty( $settings['tp_thankyou_note'] ) ? $settings['tp_thankyou_note'] : esc_html__( 'Note:', 'theplus' );

			$tp_thankyou_billing_address     = ! empty( $settings['tp_thankyou_billing_address'] ) ? $settings['tp_thankyou_billing_address'] : esc_html__( 'Billing address', 'theplus' );
			$tp_thankyou_billing_address_na  = ! empty( $settings['tp_thankyou_billing_address_na'] ) ? $settings['tp_thankyou_billing_address_na'] : esc_html__( 'N/A', 'theplus' );
			$tp_thankyou_shipping_address    = ! empty( $settings['tp_thankyou_shipping_address'] ) ? $settings['tp_thankyou_shipping_address'] : esc_html__( 'Shipping address', 'theplus' );
			$tp_thankyou_shipping_address_na = ! empty( $settings['tp_thankyou_shipping_address_na'] ) ? $settings['tp_thankyou_shipping_address_na'] : esc_html__( 'N/A', 'theplus' );

			global $wp;
			if ( isset( $wp->query_vars['order-received'] ) ) {
				$tp_order_received = absint( $wp->query_vars['order-received'] );
			} else {
				$tp_order_received = absint( theplus_get_order_id() );
			}

			if ( ! $tp_order_received ) {
				return;
			}

			$order    = wc_get_order( $tp_order_received );
			$order_id = $order->get_id();

			if ( ! $order = wc_get_order( $order_id ) ) {
				return;
			}

			$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
			$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );

			$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();

			$downloads      = $order->get_downloadable_items();
			$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();

			if ( ! empty( $show_downloads ) ) {
				wc_get_template(
					'order/order-downloads.php',
					array(
						'downloads'  => $downloads,
						'show_title' => true,
					)
				);
			}

			$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();

			echo '<div class="tp-woo-thankyou-wrapper">';

			if ( ! empty( $settings['tp_thankyou_order_main'] ) && 'yes' === $settings['tp_thankyou_order_main'] ) {
				if ( $order ) {
					if ( $order->has_status( 'failed' ) ) {
						?>
							<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php echo $tp_thankyou_order_fail; ?></p>

							<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
								<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php echo $tp_thankyou_order_pay; ?></a>

								<?php if ( is_user_logged_in() ) { ?>
									<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php echo $tp_thankyou_myaccount; ?></a>
								<?php } ?>
							</p> 
							<?php
					} else {
						if ( ! empty( $tp_thankyou_or_txt ) ) {
							?>
							<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', $tp_thankyou_or_txt, $order ); ?></p> 
																																	<?php
						}

						if ( ! empty( $settings['tp_thankyou_order_overview'] ) && 'yes' === $settings['tp_thankyou_order_overview'] ) {

							$tp_t_oo_layout = '';
							if ( ! empty( $settings['tp_t_oo_layout'] ) ) {
								$tp_t_oo_layout = $settings['tp_t_oo_layout'];
							}

							echo '<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details ' . esc_attr( $tp_t_oo_layout ) . '">';
							?>
							
							<li class="woocommerce-order-overview__order order">
								<?php echo $tp_thankyou_order_number; ?>
									<strong><?php echo $order->get_order_number(); ?></strong>
								</li>
					
								<li class="woocommerce-order-overview__date date">
									<?php echo $tp_thankyou_order_date; ?>
									<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
								</li>
							<?php

							if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) {
								?>
										<li class="woocommerce-order-overview__email email">
										<?php echo $tp_thankyou_order_email; ?>
											<strong><?php echo $order->get_billing_email(); ?></strong>
										</li> 
										<?php
							}
							?>
										<li class="woocommerce-order-overview__total total">
										<?php echo $tp_thankyou_order_total; ?>
											<strong><?php echo $order->get_formatted_order_total(); ?></strong>
										</li> 
								<?php

								if ( $order->get_payment_method_title() ) {
									?>
									<li class="woocommerce-order-overview__payment-method method">
										<?php echo $tp_thankyou_payment_method; ?>
										<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
									</li> 
									<?php
								}

								echo '</ul>';
						}
					}
				} else {
					echo '<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">';
							apply_filters( 'woocommerce_thankyou_order_received_text', $tp_thankyou_or_txt, null );
					echo '</p>';
				}
			}
			/*order details end*/

			/*order table details start*/
			if ( ! empty( $settings['tp_thankyou_order_detail'] ) && 'yes' === $settings['tp_thankyou_order_detail'] ) {
				?>
				<section class="woocommerce-order-details"> 
				<?php
					do_action( 'woocommerce_order_details_before_order_table', $order );

				if ( ! empty( $tp_thankyou_order_detail_head ) ) {
					?>
						<h2 class="woocommerce-order-details__title"><?php echo $tp_thankyou_order_detail_head; ?></h2> 
																				<?php
				}
				?>

					<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
				
						<thead>
							<tr>
								<th class="woocommerce-table__product-name product-name">
									<?php echo $tp_thankyou_product; ?>
								</th>
								<th class="woocommerce-table__product-table product-total">
									<?php echo $tp_thankyou_total; ?>
								</th>
							</tr>
						</thead>
				
						<tbody>
							<?php
							do_action( 'woocommerce_order_details_before_order_table_items', $order );

							foreach ( $order_items as $item_id => $item ) {
								$product = $item->get_product();

								wc_get_template(
									'order/order-details-item.php',
									array(
										'order'         => $order,
										'item_id'       => $item_id,
										'item'          => $item,
										'show_purchase_note' => $show_purchase_note,
										'purchase_note' => $product ? $product->get_purchase_note() : '',
										'product'       => $product,
									)
								);
							}

							do_action( 'woocommerce_order_details_after_order_table_items', $order );
							?>
						</tbody>
				
						<tfoot>
							<?php foreach ( $order->get_order_item_totals() as $key => $total ) { ?>
								<tr>
									<th scope="row"><?php echo $total['label']; ?></th>
									<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : $total['value']; ?></td>
								</tr> 
								<?php
							}

							if ( $order->get_customer_note() ) {
								?>
								<tr>
									<th><?php echo $tp_thankyou_note; ?></th>
									<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
								</tr>
							<?php } ?>
						</tfoot>
					</table>
				
					<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
				</section>
				<?php
			}
			/*
			order table details end*/
			/*combo*/
			?>
			<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses"> 
			<?php
			/*billing address start*/
			if ( ! empty( $settings['tp_thankyou_billing'] ) && 'yes' === $settings['tp_thankyou_billing'] ) {
				?>
				<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

				<h2 class="woocommerce-column__title"><?php echo $tp_thankyou_billing_address; ?></h2>

				<address>
					<?php echo wp_kses_post( $order->get_formatted_billing_address( $tp_thankyou_billing_address_na ) ); ?>

					<?php if ( $order->get_billing_phone() ) : ?>
						<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
					<?php endif; ?>

					<?php if ( $order->get_billing_email() ) : ?>
						<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
					<?php endif; ?>
				</address>
			</div>
				<?php
			}
			/*billing address end*/

			/*shipping address start*/
			if ( ! empty( $settings['tp_thankyou_shipping'] ) && 'yes' === $settings['tp_thankyou_shipping'] ) {
				?>
				<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
				<h2 class="woocommerce-column__title"><?php echo $tp_thankyou_shipping_address; ?></h2>
				<address>
					<?php echo wp_kses_post( $order->get_formatted_shipping_address( $tp_thankyou_shipping_address_na ) ); ?>
				</address>
			</div>
				<?php

			}
			/*shipping address end*/
			?>
			</section>
			<?php
			/*combo*/

			echo '</div>';
		}
	}
}
