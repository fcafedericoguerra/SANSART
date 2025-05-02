<?php
/**
 * Widget Name: Form
 * Description: Third party plugin Plus Form style.
 * Author: Theplus
 * Author URI: https://posimyth.com
 *
 * @package ThePlus
 */

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\controls\change;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ThePlus_Plus_Form.
 */
class ThePlus_Plus_Form extends Widget_Base {

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
	 * Get Widget Name.
	 *
	 * @since   6.0.4
	 */
	public function get_name() {
		return 'tp-plus-form';
	}

	/**
	 * Get Widget Title.
	 *
	 * @since   6.0.4
	 */
	public function get_title() {
		return esc_html__( 'Form', 'theplus' );
	}

	/**
	 * Get Widget Icon.
	 *
	 * @since   6.0.4
	 */
	public function get_icon() {
		return 'fa fa-plus-form theplus_backend_icon';
	}

	/**
	 * Get Widget categories.
	 *
	 * @since   6.0.4
	 */
	public function get_categories() {
		return array( 'plus-forms' );
	}

	/**
	 * Get Widget keywords.
	 *
	 * @since   6.0.4
	 */
	public function get_keywords() {
		return array( 'Form','Plus Form', 'Forms' );
	}

	/**
	 * Get Widget categories.
	 *
	 * @since   6.0.4
	 */
	public function get_custom_help_url() {
		$help_url = $this->tp_help;

		return esc_url( $help_url );
	}

	/**
	 * Register controls.
	 *
	 * @since   6.0.4
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'General', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'form_title',
			array(
				'label'       => wp_kses_post( 'Unique Form Name', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'New Plus Form', 'theplus' ),
				'placeholder' => esc_html__( 'Enter Form Name', 'theplus' ),
				'dynamic'     => array(
					'active' => false,
				),
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs( 'tabs_form_button_style' );

		$repeater->start_controls_tab(
			'field_content',
			array(
				'label' => esc_html__( 'Content', 'theplus' ),
			)
		);

		$repeater->add_control(
			'form_fields',
			array(
				'label'       => esc_html__( 'Type', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => esc_html__( 'text', 'theplus' ),
				'options'     => array(
					'text'     => esc_html__( 'Text', 'theplus' ),
					'textarea' => esc_html__( 'Long Text', 'theplus' ),
					'email'    => esc_html__( 'Email', 'theplus' ),
					'number'   => esc_html__( 'Number', 'theplus' ),
					'hidden'   => esc_html__( 'Hidden', 'theplus' ),
					'honeypot' => esc_html__( 'HoneyPot', 'theplus' ),
					'dropdown' => esc_html__( 'Dropdown', 'theplus' ),
					'date'     => esc_html__( 'Date', 'theplus' ),
					'time'     => esc_html__( 'Time', 'theplus' ),
				),
				'label_block' => false,
			)
		);
		$repeater->add_control(
			'field_label',
			array(
				'label'       => esc_html__( 'Label', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Field Label', 'theplus' ),
				'ai'          => array(
					'active' => false,
				),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number', 'dropdown', 'date', 'time' ),
				),
			)
		);
		$repeater->add_control(
			'dropdown_options',
			array(
				'label'       => esc_html__( 'Dropdown Options', 'theplus' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Enter each option on a new line.', 'theplus' ),
				'condition'   => array(
					'form_fields' => 'dropdown',
				),
			)
		);
		$repeater->add_control(
			'place_holder',
			array(
				'label'       => esc_html__( 'Placeholder', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Placeholder Text', 'theplus' ),
				'ai'          => array(
					'active' => false,
				),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number' ),
				),
			)
		);

		$repeater->add_control(
			'required',
			array(
				'label'     => esc_html__( 'Required', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),
				'condition' => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number', 'dropdown', 'date', 'time' ),
				),
			)
		);

		$repeater->add_responsive_control(
			'column_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Column Width', 'theplus' ),
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => '%',
				),
				'condition'  => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number', 'dropdown', 'date', 'time' ),
				),
			)
		);
		$repeater->add_control(
			'textarea_rows',
			array(
				'label'     => esc_html__( 'Rows', 'theplus' ),
				'type'      => Controls_Manager::NUMBER,
				'dynamic'   => array(
					'active' => false,
				),
				'ai'        => array(
					'active' => false,
				),
				'condition' => array(
					'form_fields' => array( 'textarea' ),
				),
				'default'   => '4',
			)
		);
		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'field_advance',
			array(
				'label' => esc_html__( 'Advance', 'theplus' ),
			)
		);

		$repeater->add_control(
			'field_default_value',
			array(
				'label'       => esc_html__( 'Default Value', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Default Value', 'theplus' ),
				'ai'          => array(
					'active' => false,
				),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number', 'hidden' ),
				),
			)
		);

		$repeater->add_control(
			'field_help',
			array(
				'label'       => esc_html__( 'Help Text', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Help Text', 'theplus' ),
				'ai'          => array(
					'active' => false,
				),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number', 'dropdown', 'date', 'time' ),
				),
			)
		);

		$repeater->add_control(
			'field_ad',
			array(
				'label'       => esc_html__( 'Aria Description', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Aria Description', 'theplus' ),
				'ai'          => array(
					'active' => false,
				),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number', 'dropdown', 'date', 'time' ),
				),
			)
		);

		$repeater->add_control(
			'field_id',
			array(
				'label'       => esc_html__( 'Unique ID', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'ID', 'theplus' ),
				'ai'          => array(
					'active' => false,
				),
			)
		);
		$repeater->add_control(
			'unique_id_notice',
			array(
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<p class="tp-controller-notice"><i>Note : Ensure the ID is unique and not duplicated anywhere else on the page displaying this form. Valid entries include uppercase and lowercase letters (A-Z, a-z), numbers (0-9), and underscores, but spaces are not allowed.</i></p>',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'field_shortcode',
			array(
				'label'       => esc_html__( 'Data Shortcode', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Shortcode', 'theplus' ),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number', 'dropdown', 'date', 'time' ),
				),
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'tabs',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'field_label'         => esc_html__( 'First Name', 'theplus' ),
						'place_holder'        => esc_html__( 'Enter First Name', 'theplus' ),
						'form_fields'         => 'text',
						'column_width'        => '50',
						'required'            => 'no',
						'field_default_value' => '',
						'field_id'            => 'first_name',
						'field_shortcode'     => '[value_id="first_name"]',
					),
					array(
						'field_label'         => esc_html__( 'Last Name', 'theplus' ),
						'place_holder'        => esc_html__( 'Enter Last Name', 'theplus' ),
						'form_fields'         => 'text',
						'column_width'        => '50',
						'required'            => 'no',
						'field_default_value' => '',
						'field_id'            => 'last_name',
						'field_shortcode'     => '[value_id="last_name"]',
					),
					array(
						'field_label'         => esc_html__( 'Email', 'theplus' ),
						'place_holder'        => esc_html__( 'Enter your official email address', 'theplus' ),
						'form_fields'         => 'email',
						'column_width'        => '100',
						'required'            => 'yes',
						'field_default_value' => '',
						'field_id'            => 'email',
						'field_shortcode'     => '[value_id="email"]',
					),
					array(
						'field_label'         => esc_html__( 'Mobile Number', 'theplus' ),
						'place_holder'        => esc_html__( 'Enter your mobile number', 'theplus' ),
						'form_fields'         => 'number',
						'column_width'        => '100',
						'required'            => 'no',
						'field_default_value' => '',
						'field_id'            => 'mobile_number',
						'field_shortcode'     => '[value_id="mobile_number"]',
					),
					array(
						'field_label'         => esc_html__( 'Subject', 'theplus' ),
						'place_holder'        => esc_html__( 'Enter your Subject', 'theplus' ),
						'form_fields'         => 'text',
						'column_width'        => '100',
						'required'            => 'no',
						'field_default_value' => '',
						'field_id'            => 'subject',
						'field_shortcode'     => '[value_id="subject"]',
					),
					array(
						'field_label'         => esc_html__( 'Message', 'theplus' ),
						'place_holder'        => esc_html__( 'Share why you are contacting', 'theplus' ),
						'form_fields'         => 'textarea',
						'column_width'        => '100',
						'required'            => 'no',
						'field_default_value' => '',
						'field_id'            => 'message',
						'field_shortcode'     => '[value_id="message"]',
					),
				),
				'title_field' => '{{{ field_label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_section',
			array(
				'label' => esc_html__( 'Button', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->start_controls_tabs( 'tabs_form_btn' );

		$this->start_controls_tab(
			'button_content',
			array(
				'label' => esc_html__( 'Content', 'theplus' ),
			)
		);

		$this->add_control(
			'button_submit',
			array(
				'label'   => esc_html__( 'Button Text', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'default' => esc_html__( 'Send', 'theplus' ),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'button_icon_style',
			array(
				'label'   => esc_html__( 'Icon Font', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'font_awesome_5' => esc_html__( 'Font Awesome 5', 'theplus' ),
					'none'           => esc_html__( 'None', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'icon_fontawesome_5',
			array(
				'label'       => esc_html__( 'Icon Library', 'theplus' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-plus',
					'library' => 'solid',
				),
				'condition'   => array(
					'button_icon_style' => 'font_awesome_5',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'       => esc_html__( 'Icon Position', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => esc_html__( 'after', 'theplus' ),
				'condition' => array(
					'button_icon_style' => 'font_awesome_5',
				),
				'options'     => array(
					'after'  => esc_html__( 'After', 'theplus' ),
					'before' => esc_html__( 'Before', 'theplus' ),
				),
				'label_block' => false,
			)
		);

		$this->add_control(
			'inline_button',
			array(
				'label'     => esc_html__( 'Inline Button', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),
			)
		);
		
		$this->add_responsive_control(
			'button_inline_width',
			array(
				'label'       => esc_html__( 'Button Width', 'theplus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%', 'px' ),
				'range'       => array(
					'%' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 2,
					),
				),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'label_block' => true,
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-submit-container' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'inline_button' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_column_width',
			array(
				'label'       => esc_html__( 'Button Width', 'theplus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%', 'px' ),
				'range'       => array(
					'%' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 2,
					),
				),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'label_block' => true,
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-button' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'inline_button!' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_advance',
			array(
				'label' => esc_html__( 'Advance', 'theplus' ),
			)
		);

		$this->add_control(
			'button_id',
			array(
				'label'       => esc_html__( 'Button ID', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'ai'          => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'button-id', 'theplus' ),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'submit_actions',
			array(
				'label' => esc_html__( 'Submit Actions', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'add_action',
			array(
				'label'       => esc_html__( 'Add Action', 'theplus' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => esc_html__( 'email', 'theplus' ),
				'multiple'    => true,
				'options'     => array(
					'email'    => esc_html__( 'Email', 'theplus' ),
					'Redirect' => esc_html__( 'Redirect', 'theplus' ),
				),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'email_settings',
			array(
				'label'     => esc_html__( 'Email Settings', 'theplus' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'add_action' => 'email',
				),
				'dynamic'   => array(
					'active' => false,
				),
				'ai'        => array(
					'active' => false,
				),
			)
		);

		$this->start_controls_tabs( 'tabs_email' );

		$this->start_controls_tab(
			'email_to_tab',
			array(
				'label' => esc_html__( 'To', 'theplus' ),
			)
		);

		$this->add_control(
			'email_to',
			array(
				'label'   => esc_html__( 'Email Address', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'email_cc_tab',
			array(
				'label' => esc_html__( 'CC', 'theplus' ),
			)
		);

		$this->add_control(
			'email_cc',
			array(
				'label'   => esc_html__( 'Email Address', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'email_bcc_tab',
			array(
				'label' => esc_html__( 'BCC', 'theplus' ),
			)
		);

		$this->add_control(
			'email_bcc',
			array(
				'label'   => esc_html__( 'Email Address', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'email_subject',
			array(
				'label'     => esc_html__( 'Subject', 'theplus' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => false,
				),
				'ai'        => array(
					'active' => false,
				),
				'separator' => 'before',
				'default'   => 'New Form Submission',
			)
		);
		$this->add_control(
			'email_heading',
			array(
				'label'     => esc_html__( 'Email Heading', 'theplus' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => false,
				),
				'ai'        => array(
					'active' => false,
				),
				'default'   => 'New Form Submission',
			)
		);

		$this->add_control(
			'email_message',
			array(
				'label'   => esc_html__( 'Message', 'theplus' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array(
					'active' => false,
				),
				'default' => '[all-values]',
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'field_message_notice',
			array(
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<p class="tp-controller-notice"><i>Note : To retrieve all values, use the [all-values] shortcode in the description. You can customize this by using the individual value shortcodes from each field above.</i></p>',
				'label_block' => true,
			)
		);

		$this->add_control(
			'email_from',
			array(
				'label'   => esc_html__( 'From Email', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'email_from_name',
			array(
				'label'   => esc_html__( 'From Name', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'email_reply_to',
			array(
				'label'   => esc_html__( 'Reply-To', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'redirect_settings',
			array(
				'label'     => esc_html__( 'Redirect Settings', 'theplus' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'add_action' => 'Redirect',
				),
			)
		);

		$this->add_control(
			'redirect_to',
			array(
				'label'       => esc_html__( 'Redirect To', 'theplus' ),
				'type'        => Controls_Manager::URL,
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'default'     => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => true,
				),
				'label_block' => true,
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'message_content',
			array(
				'label' => esc_html__( 'Message Content', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'success_message',
			array(
				'label'       => esc_html__( 'Success Message', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'label_block' => true,
				'default'     => 'Form Submitted Successfully',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'required_fields',
			array(
				'label'       => esc_html__( 'Mandatory Fields', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'label_block' => true,
				'default'     => 'This field is required.',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'invalid_form',
			array(
				'label'       => esc_html__( 'Form Validation Error', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'label_block' => true,
				'default'     => 'Invalid form! Please check it again.',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'form_error',
			array(
				'label'       => esc_html__( 'Submission Issue', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'label_block' => true,
				'default'     => 'There was an error in submitting the form.',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'server_error',
			array(
				'label'       => esc_html__( 'Server Issue', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'label_block' => true,
				'default'     => 'A server error occurred.',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'Additiona_Options',
			array(
				'label' => esc_html__( 'Extra Options', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'form_id',
			array(
				'label'   => esc_html__( 'Form ID', 'theplus' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'form_id_notice',
			array(
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<p class="tp-controller-notice"><i>Note : Ensure the ID is unique and not duplicated anywhere else on the page displaying this form. Valid entries include uppercase and lowercase letters (A-Z, a-z), numbers (0-9), and underscores, but spaces are not allowed.</i></p>',
				'label_block' => true,
			)
		);

		$this->add_control(
			'label_display',
			array(
				'label'     => esc_html__( 'Show Label', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),

			)
		);
		$this->add_control(
			'required_mask',
			array(
				'label'     => esc_html__( 'Required Mark', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),
				'condition' => array(
					'label_display' => 'yes',
				),

			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'form_style',
			array(
				'label' => esc_html__( 'General', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'form_column_gap',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Columns Gap', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tpaep-form-container .tpaep-form' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_row_gap',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Rows Gap', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 10,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tpaep-form-container .tpaep-form' => 'row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_label_heading',
			array(
				'label'     => esc_html__( 'Label', 'theplus' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_label_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form-label',
			)
		);

		$this->add_responsive_control(
			'form_label_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Spacing', 'theplus' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 10,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tpaep-form-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_label_position',
			array(
				'label'       => esc_html__( 'Label Text Align', 'theplus' ),
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
					'{{WRAPPER}} .tpaep-form-label' => 'text-align: {{VALUE}};',
				),
				'default'     => 'left',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_form_label_colors' );

		$this->start_controls_tab(
			'form_label_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);

		$this->add_control(
			'form_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form-label, {{WRAPPER}} .tpaep-form input, {{WRAPPER}} .tpaep-form textarea' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'form_label_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);

		$this->add_control(
			'form_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form-label:hover, {{WRAPPER}} .tpaep-form input, {{WRAPPER}} .tpaep-form textarea' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'form_field_style',
			array(
				'label' => esc_html__( 'Fields', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_text_field_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form input::placeholder, {{WRAPPER}} .tpaep-form textarea::placeholder',
			)
		);

		$this->add_responsive_control(
			'form_placeholder_position',
			array(
				'label'       => esc_html__( 'Input Text Align', 'theplus' ),
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
					'{{WRAPPER}} .tpaep-form-field input::placeholder' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tpaep-form-field textarea::placeholder' => 'text-align: {{VALUE}};',
				),
				'default'     => 'left',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->add_responsive_control(
			'form_field_padding',
			array(
				'label'      => esc_html__( 'Field Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form input, {{WRAPPER}} .tpaep-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_field_bg' );

		$this->start_controls_tab(
			'tab_field_bg_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);

		$this->add_control(
			'form_placeholder_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form-field input::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tpaep-form-field textarea::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_field_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form input, {{WRAPPER}} .tpaep-form textarea' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_field_border_normal',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form input, {{WRAPPER}} .tpaep-form textarea',
			)
		);

		$this->add_responsive_control(
			'form_field_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form input, {{WRAPPER}} .tpaep-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_bg_clr_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);

		$this->add_control(
			'form_placeholder_text_color_hover',
			array(
				'label'     => esc_html__( 'Hover Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form input:hover::placeholder, {{WRAPPER}} .tpaep-form textarea:hover::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_field_bg_clr_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form input:hover, {{WRAPPER}} .tpaep-form textarea:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_field_border_hover',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form input:hover, {{WRAPPER}} .tpaep-form textarea:hover',
			)
		);

		$this->add_responsive_control(
			'form_field_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form input:hover, {{WRAPPER}} .tpaep-form textarea:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_bg_clr_active',
			array(
				'label' => esc_html__( 'Active', 'theplus' ),
			)
		);

		$this->add_control(
			'form_placeholder_text_color_active',
			array(
				'label'     => esc_html__( 'Active Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form input:focus::placeholder, {{WRAPPER}} .tpaep-form textarea:focus::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_field_bg_clr_active',
			array(
				'label'     => esc_html__( 'Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form input:focus, {{WRAPPER}} .tpaep-form textarea:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_field_border_active',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form input:focus, {{WRAPPER}} .tpaep-form textarea:focus',
			)
		);

		$this->add_responsive_control(
			'form_field_border_radius_active',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form input:focus, {{WRAPPER}} .tpaep-form textarea:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'form_button_style',
			array(
				'label' => esc_html__( 'Submit Button', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_button_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form .tpaep-form-button',
			)
		);

		$this->add_responsive_control(
			'form_button_position',
			array(
				'label'       => esc_html__( 'Position', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'theplus' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'theplus' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'theplus' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tpaep-form-submit-container' => 'display: flex; justify-content: {{VALUE}};',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->add_responsive_control(
			'form_button_alignment',
			array(
				'label'       => esc_html__( 'Alignment', 'theplus' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'theplus' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'theplus' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'theplus' ),
						'icon'  => 'eicon-text-align-right',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'theplus' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-button.tpaep-form-submit' => 'justify-content: {{VALUE}};',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->add_responsive_control(
			'form_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_button_border',
				'label'    => esc_html__( 'Button Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form .tpaep-form-button',
			)
		);

		$this->add_responsive_control(
			'form_button_icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'theplus' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => [
							'size' => 10,
							'unit' => 'px',
						],
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-button.tpaep-icon-before' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tpaep-form .tpaep-form-button.tpaep-icon-after' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_button_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'theplus' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => [
						'size' => 18,
						'unit' => 'px',
					],
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-button svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'submit_btn_style' );

		$this->start_controls_tab(
			'submit_btn_style_n',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);

			$this->add_control(
				'form_button_background_color',
				array(
					'label'     => esc_html__( 'Background Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_text_color',
				array(
					'label'     => esc_html__( 'Text Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_icon_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button svg' => 'fill: {{VALUE}};',
						'{{WRAPPER}} .tpaep-form .tpaep-form-button i' => 'fill: {{VALUE}};',
						'{{WRAPPER}} .tpaep-form .tpaep-form-button i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'form_button_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'  => 'before',
				)
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submit_btn_style_h',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);

			$this->add_control(
				'form_button_hover_background_color',
				array(
					'label'     => esc_html__( 'Hover Background Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_hover_text_color',
				array(
					'label'     => esc_html__( 'Hover Text Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_icon_hover_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'theplus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button:hover i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .tpaep-form .tpaep-form-button:hover svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'form_button_hover_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'theplus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tpaep-form .tpaep-form-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'  => 'before',
				)
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'form_help_text',
			array(
				'label' => esc_html__( 'Help Text', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'form_help_padding',
			array(
				'label'      => esc_html__( 'Padding', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tpaep-form .tpaep-help-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'help_text_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form .tpaep-help-text',
			)
		);

		$this->add_control(
			'help_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-help-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'help_text_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-help-text' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_message_style',
			array(
				'label' => esc_html__( 'Message Content', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_message_typography',
				'label'    => esc_html__( 'Typography', 'theplus' ),
				'selector' => '{{WRAPPER}} .tpaep-form .tpaep-form-message',
			)
		);

		$this->add_responsive_control(
			'form_msg_align',
			array(
				'label'       => esc_html__( 'Message Text Align', 'theplus' ),
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
					'{{WRAPPER}} .tpaep-form-message' => 'text-align: {{VALUE}};',
				),
				'default'     => 'left',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_msg_clr' );

		$this->start_controls_tab(
			'msg_clr_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);

		$this->add_control(
			'form_success_message_color',
			array(
				'label'     => esc_html__( 'Success Message Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#28a745',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.success' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_error_message_color',
			array(
				'label'     => esc_html__( 'Error Message Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dc3545',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.error' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_success_msg_bg_clr',
			array(
				'label'     => esc_html__( 'Success Message Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_error_msg_bg_clr',
			array(
				'label'     => esc_html__( 'Error Message Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.error' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_inline_message_color',
			array(
				'label'     => esc_html__( 'Inline Message Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.tpaep-form-inline' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'msg_clr_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);

		$this->add_control(
			'form_success_msg_clr_hover',
			array(
				'label'     => esc_html__( 'Success Message Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#28a745',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.success:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_error_msg_clr_hover',
			array(
				'label'     => esc_html__( 'Error Message Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dc3545',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.error:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_success_msg_bg_clr_hover',
			array(
				'label'     => esc_html__( 'Success Message Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_error_msg_bg_clr_hover',
			array(
				'label'     => esc_html__( 'Error Message Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.error:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_inline_message_color_hover',
			array(
				'label'     => esc_html__( 'Inline Message Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tpaep-form .tpaep-form-message.tpaep-form-inline:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		include THEPLUS_PATH . 'modules/widgets/theplus-needhelp.php';
	}

	/**
	 * Render.
	 *
	 * @since 6.0.4
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$tabs     = ! empty( $settings['tabs'] ) ? $settings['tabs'] : array();

		$submit_button = ! empty( $settings['button_submit'] ) ? $settings['button_submit'] : 'Submit';
		$label_display = ! empty( $settings['label_display'] ) ? $settings['label_display'] : '';
		$button_column = ! empty( $settings['button_column_width']['size'] ) ? $settings['button_column_width']['size'] : '100';

		$button_input_size = ! empty( $settings['input_size'] ) ? $settings['input_size'] : 'medium';
		$button_icon_style = ! empty( $settings['button_icon_style'] ) ? $settings['button_icon_style'] : 'font_awesome_5';

		$icon_position       = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'after';
		$icon_position_class = 'before' === $icon_position ? 'tpaep-icon-before' : 'tpaep-icon-after';

		$button_id   = ! empty( $settings['button_id'] ) ? $settings['button_id'] : 'tpaep-form-button';
		$button_icon = '';

		$form_id = ! empty( $settings['form_id'] ) ? esc_attr( $settings['form_id'] ) : 'tpaep-form-main';

		if ( 'font_awesome_5' === $button_icon_style && ! empty( $settings['icon_fontawesome_5']['value'] ) ) {
			ob_start();
				\Elementor\Icons_Manager::render_icon( $settings['icon_fontawesome_5'], array( 'aria-hidden' => 'true' ) );
				$button_icon = ob_get_contents();
			ob_end_clean();
		}

		$error_message = array(
			'form_id'         => $form_id,
			'Required_mask'   => 'yes' === $settings['required_mask'] ? 'show-asterisks' : 'hide-asterisks',
			'invalid_form'    => ! empty( $settings['invalid_form'] ) ? $settings['invalid_form'] : '',
			'required_fields' => ! empty( $settings['required_fields'] ) ? $settings['required_fields'] : '',
			'form_error'      => ! empty( $settings['form_error'] ) ? $settings['form_error'] : '',
			'success_message' => ! empty( $settings['success_message'] ) ? $settings['success_message'] : '',
			'server_error'    => ! empty( $settings['server_error'] ) ? $settings['server_error'] : '',
		);

		$email_data = array(
			'email_to'        => is_email( $settings['email_to'] ) ? sanitize_email( $settings['email_to'] ) : '',
			'email_subject'   => ! empty( $settings['email_subject'] ) ? $settings['email_subject'] : '',
			'email_heading'   => ! empty( $settings['email_message'] ) ? $settings['email_heading'] : '',
			'email_message'   => ! empty( $settings['email_message'] ) ? $settings['email_message'] : '',
			'email_from'      => ! empty( $settings['email_from'] ) ? $settings['email_from'] : '',
			'email_from_name' => ! empty( $settings['email_from_name'] ) ? $settings['email_from_name'] : '',
			'email_reply_to'  => ! empty( $settings['email_reply_to'] ) ? $settings['email_reply_to'] : '',
			'email_cc'        => ! empty( $settings['email_cc'] ) ? sanitize_email( $settings['email_cc'] ) : null,
			'email_bcc'       => ! empty( $settings['email_bcc'] ) ? sanitize_email( $settings['email_bcc'] ) : null,
			'redirection'     => ! empty( $settings['redirect_to']['url'] ) ?
			array(
				'url'         => esc_url( $settings['redirect_to']['url'] ),
				'is_external' => ! empty( $settings['redirect_to']['is_external'] ) ? true : false,
				'nofollow'    => ! empty( $settings['redirect_to']['nofollow'] ) ? true : false,
			) : '',
			'nonce'             => wp_create_nonce( 'tp-form-nonce' ),
		);
		
		$required_fields = array();
		foreach ( $tabs as $tab ) {
			if ( ! empty( $tab['required'] ) && 'yes' === $tab['required'] ) {
				$required_fields[] = ! empty( $tab['field_id'] ) ? $tab['field_id'] : '';
			}
		}

		$inline_button = ! empty( $settings['inline_button'] ) ? $settings['inline_button'] : 'no';

		$email_data = tp_plus_simple_decrypt( json_encode( $email_data ), 'ey' );

		$error_message    = 'data-formdata="' . htmlspecialchars( wp_json_encode( $error_message, true ), ENT_QUOTES, 'UTF-8' ) . '"';
		$email_data  = 'data-emaildata="' . htmlspecialchars( wp_json_encode( $email_data, true ), ENT_QUOTES, 'UTF-8' ) . '"';

		$form_markup = '<div class="tpaep-form-container" ' . $error_message . ' ' . $email_data . ' >';

			if( 'yes' === $inline_button ) {
				$form_markup .= "<style> .tpaep-form-submit-container .tpaep-form-button{ width:100%!important } </style>";
			} else if ( 'no' === $inline_button ) {
				$form_markup .= "<style> .tpaep-form-submit-container{ width:100%!important } </style>";
			}

			$form_markup .= '<div class="tpaep-form-messages"></div>';

			$form_markup .= '<form id="' . esc_attr( $form_id ) . '" class="tpaep-form" method="post">';

		foreach ( $tabs as $tab ) {
			$tab_column        = ! empty( $tab['column_width']['size'] ) ? $tab['column_width']['size'] : '';
			$tab_id            = ! empty( $tab['field_id'] ) ? $tab['field_id'] : 'tab_' . uniqid();
			$tab_label         = ! empty( $tab['field_label'] ) ? $tab['field_label'] : '';
			$tab_placeholder   = ! empty( $tab['place_holder'] ) ? $tab['place_holder'] : '';
			$tab_default       = ! empty( $tab['field_default_value'] ) ? $tab['field_default_value'] : '';
			$tab_required      = ( ! empty( $tab['required'] ) && 'yes' === $tab['required'] ) ? 'required' : '';
			$tab_input_size    = ! empty( $settings['input_size'] ) ? $settings['input_size'] : 'medium';
			$tab_field_type    = ! empty( $tab['form_fields'] ) ? $tab['form_fields'] : 'text';
			$tab_textarea_rows = ! empty( $tab['textarea_rows'] ) ? $tab['textarea_rows'] : 3;
			$tab_help          = ! empty( $tab['field_help'] ) ? $tab['field_help'] : '';
			$tab_ad            = ! empty( $tab['field_ad'] ) ? $tab['field_ad'] : '';

			$form_markup .= '<div class="tpaep-form-field" style="width: ' . esc_attr( $tab_column ) . '%;">';

			if ( 'yes' === $label_display && ! in_array( $tab_field_type, array( 'recaptcha', 'honeypot', 'hidden' ), true ) ) {
				$form_markup .= '<label for="form_fields[' . esc_attr( $tab_id ) . ']" class="tpaep-form-label">';
				$form_markup .= esc_html( $tab_label );

				if ( ! empty( $tab_required ) ) {
					$form_markup .= ' <span class="tpaep-required-asterisk">*</span>';
				}
				$form_markup .= '</label>';
			}

			if ( $tab_required ) {
				$required_fields[] = esc_attr( $tab_id );
			}

			if ( in_array( $tab_field_type, array( 'text', 'email', 'number' ), true ) ) {
				$form_markup .= '<input type="' . esc_attr( $tab_field_type ) . '" name="' . esc_attr( $tab_id ) . '" id="' . esc_attr( $tab_id ) . '" placeholder="' . esc_attr( $tab_placeholder ) . '" ' . $tab_required . ' class="' . esc_attr( $tab_input_size ) . '" value="' . esc_attr( $tab_default ) . '" aria-description="' . esc_attr( $tab_ad ) . '"/><span class="tpaep-help-text">' . esc_html( $tab_help ) . '</span>';
			} elseif ( 'textarea' === $tab_field_type ) {
				$form_markup .= '<textarea name="' . esc_attr( $tab_id ) . '" rows="' . esc_attr( $tab_textarea_rows ) . '" id="' . esc_attr( $tab_id ) . '" placeholder="' . esc_attr( $tab_placeholder ) . '" ' . $tab_required . ' class="' . esc_attr( $tab_input_size ) . '"aria-description="' . esc_attr( $tab_ad ) . '">' . esc_textarea( $tab_default ) . '</textarea>';
			} elseif ( 'hidden' === $tab_field_type ) {
				$form_markup .= '<input type="hidden" name="hidden" />';
			} elseif ( 'honeypot' === $tab_field_type ) {
				$form_markup .= '<input class="tpaep-honey" type="text" name="honeypot" />';
			} elseif ( 'dropdown' === $tab_field_type ) {
				$options = ! empty( $tab['dropdown_options'] ) ? explode( "\n", $tab['dropdown_options'] ) : array();
				$form_markup .= '<select name="' . esc_attr( $tab_id ) . '" id="' . esc_attr( $tab_id ) . '" class="' . esc_attr( $tab_input_size ) . '" ' . $tab_required . '>';
				foreach ( $options as $option ) {
					$option_value = trim( $option );
					$form_markup .= '<option value="' . esc_attr( $option_value ) . '">' . esc_html( $option_value ) . '</option>';
				}
				$form_markup .= '</select>';
			} elseif ( 'date' === $tab_field_type ) {
				$form_markup .= '<input type="date" name="' . esc_attr( $tab_id ) . '" id="' . esc_attr( $tab_id ) . '" placeholder="' . esc_attr( $tab_placeholder ) . '" ' . $tab_required . ' class="' . esc_attr( $tab_input_size ) . '" value="' . esc_attr( $tab_default ) . '" aria-description="' . esc_attr( $tab_ad ) . '"/>';
			} elseif ( 'time' === $tab_field_type ) {
				$form_markup .= '<input type="time" name="' . esc_attr( $tab_id ) . '" id="' . esc_attr( $tab_id ) . '" placeholder="' . esc_attr( $tab_placeholder ) . '" ' . $tab_required . ' class="' . esc_attr( $tab_input_size ) . '" value="' . esc_attr( $tab_default ) . '" aria-description="' . esc_attr( $tab_ad ) . '"/>';
			}

			$form_markup .= '</div>';
		}
				$form_markup .= '<div class="tpaep-form-submit-container">';

					$form_markup .= '<button id="' . esc_attr( $button_id ) . '" type="submit" class="tpaep-form-submit tpaep-form-button ' . esc_attr( $icon_position_class ) . '" >' . $button_icon . ' ' . esc_html( $submit_button ) . '</button>';

				$form_markup .= '</div>';

			$form_markup .= '</form>';

		$form_markup .= '</div>';

		echo $form_markup;
	}
}