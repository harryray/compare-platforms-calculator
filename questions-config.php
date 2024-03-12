<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class CTP_Extra_Settings_Admin {
	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'ctp_options';
	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'ctp_questions_metabox';
	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';
	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var CTP_Extra_Settings_Admin
	 **/
	private static $instance = null;
	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'Questions Settings', 'ctp' );
	}
	/**
	 * Returns the running object
	 *
	 * @return CTP_Extra_Settings_Admin
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new CTP_Extra_Settings_Admin();
			self::$instance->hooks();
		}
		return self::$instance;
	}
	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_questions_settings_metabox' ) );
	}
	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}
	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		//$this->options_page = add_submenu_page( 'edit.php', $this->title, $this->title, 'manage_options', $this->key, array( $this, 'posts_settings_page_display' ) );
		$this->options_page = add_submenu_page( 'edit.php?post_type=platform', $this->title, $this->title, 'edit_platform_data', $this->key, array( $this, 'questions_settings_page_display' ) );
		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function questions_settings_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_questions_settings_metabox() {
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );
		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );
		$cmb->add_field( array(
			'name' => 'Advisor Platform Calculator',
			'desc' => 'Below fields will be shown on Advisor platform calculator page only',
			'type' => 'title',
			'id'   => 'advisor_question_fields'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 1 label',
		    'desc'    => '',
		    'id'      => 'question_1_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 1 help',
		    'desc'    => '',
		    'id'      => 'question_1_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 2 label',
		    'desc'    => '',
		    'id'      => 'question_2_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 2 help',
		    'desc'    => '',
		    'id'      => 'question_2_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 3 label',
		    'desc'    => '',
		    'id'      => 'question_3_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 4 label',
		    'desc'    => '',
		    'id'      => 'question_4_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 4 help',
		    'desc'    => '',
		    'id'      => 'question_4_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 5 label',
		    'desc'    => '',
		    'id'      => 'question_5_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 5 help',
		    'desc'    => '',
		    'id'      => 'question_5_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 5A label',
			'desc'    => '',
			'id'      => 'question_5a_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 5A help',
			'desc'    => '',
			'id'      => 'question_5a_help',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 5B label',
			'desc'    => '',
			'id'      => 'question_5b_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 5B help',
			'desc'    => '',
			'id'      => 'question_5b_help',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 6 label',
		    'desc'    => '',
		    'id'      => 'question_6_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 6 help',
		    'desc'    => '',
		    'id'      => 'question_6_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 6A label',
			'desc'    => '',
			'id'      => 'question_6a_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 6A help',
			'desc'    => '',
			'id'      => 'question_6a_help',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 6B label',
			'desc'    => '',
			'id'      => 'question_6b_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 6B help',
			'desc'    => '',
			'id'      => 'question_6b_help',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 7 label',
		    'desc'    => '',
		    'id'      => 'question_7_label',
		    'type'    => 'textarea_small'
		) );
		 $cmb->add_field( array(
		     'name'    => 'Question 7 help',
		     'desc'    => '',
		     'id'      => 'question_7_help',
		     'type'    => 'textarea_small'
		 ) );
		$cmb->add_field( array(
		    'name'    => 'Question 8 label',
		    'desc'    => '',
		    'id'      => 'question_8_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9 label',
		    'desc'    => '',
		    'id'      => 'question_9_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 10 label',
		    'desc'    => '',
		    'id'      => 'question_10_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 11 label',
		    'desc'    => '',
		    'id'      => 'question_11_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 11 help',
		    'desc'    => '',
		    'id'      => 'question_11_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question Linked Portfolio label',
		    'desc'    => '',
		    'id'      => 'question_portfolio_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question Linked Portfolio help',
		    'desc'    => '',
		    'id'      => 'question_portfolio_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 12 label',
		    'desc'    => '',
		    'id'      => 'question_12_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 12A label',
		    'desc'    => '',
		    'id'      => 'question_12A_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 12A help',
		    'desc'    => '',
		    'id'      => 'question_12A_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 12B label',
		    'desc'    => '',
		    'id'      => 'question_12B_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 12B help',
		    'desc'    => '',
		    'id'      => 'question_12B_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 12C label',
		    'desc'    => '',
		    'id'      => 'question_12C_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 12C help',
		    'desc'    => '',
		    'id'      => 'question_12C_help',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 13 label',
		    'desc'    => '',
		    'id'      => 'question_13_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 14 label',
		    'desc'    => '',
		    'id'      => 'question_14_label',
		    'type'    => 'textarea_small'
		) );

		$cmb->add_field( array(
			'name' => 'Consumer Platform Calculator',
			'desc' => 'Below fields will be shown on Consumer platform calculator page only',
			'type' => 'title',
			'id'   => 'consumer_question_fields'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 1 label',
		    'desc'    => '',
		    'id'      => 'question_1_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 1 Tooltip',
		    'desc'    => '',
		    'id'      => 'question_1_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 2 label',
		    'desc'    => '',
		    'id'      => 'question_2_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 2 Tooltip',
		    'desc'    => '',
		    'id'      => 'question_2_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 3 label',
		    'desc'    => '',
		    'id'      => 'question_3_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 4 label',
		    'desc'    => '',
		    'id'      => 'question_4_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 4 Tooltip',
		    'desc'    => '',
		    'id'      => 'question_4_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 5 label',
		    'desc'    => '',
		    'id'      => 'question_5_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 5 Tooltip',
		    'desc'    => '',
		    'id'      => 'question_5_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		// $cmb->add_field( array(
		//     'name'    => 'Question 5A label',
		//     'desc'    => '',
		//     'id'      => 'question_5a_label_d2c',
		//     'type'    => 'textarea_small'
		// ) );
		$cmb->add_field( array(
		    'name'    => 'Question 5A Tooltip',
		    'desc'    => '',
		    'id'      => 'question_5a_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		// $cmb->add_field( array(
		//     'name'    => 'Question 5B label',
		//     'desc'    => '',
		//     'id'      => 'question_5b_label_d2c',
		//     'type'    => 'textarea_small'
		// ) );
		$cmb->add_field( array(
		    'name'    => 'Question 5B Tooltip',
		    'desc'    => '',
		    'id'      => 'question_5b_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 6 label',
		    'desc'    => '',
		    'id'      => 'question_6_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 6 Tooltip',
		    'desc'    => '',
		    'id'      => 'question_6_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 7 label',
		    'desc'    => '',
		    'id'      => 'question_7_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 8 label',
		    'desc'    => '',
		    'id'      => 'question_8_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9 label',
		    'desc'    => '',
		    'id'      => 'question_9_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9A label',
		    'desc'    => '',
		    'id'      => 'question_9a_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9A Tooltip',
		    'desc'    => '',
		    'id'      => 'question_9a_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9B label',
		    'desc'    => '',
		    'id'      => 'question_9b_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9B Tooltip',
		    'desc'    => '',
		    'id'      => 'question_9b_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9C label',
		    'desc'    => '',
		    'id'      => 'question_9c_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 9C Tooltip',
		    'desc'    => '',
		    'id'      => 'question_9c_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );

		$cmb->add_field( array(
		    'name'    => 'Question 10 label',
		    'desc'    => '',
		    'id'      => 'question_10_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 10 Tooltip',
		    'desc'    => '',
		    'id'      => 'question_10_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );

		$cmb->add_field( array(
		    'name'    => 'Question 11 label',
		    'desc'    => '',
		    'id'      => 'question_11_label_d2c',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'Question 11 Tooltip',
		    'desc'    => '',
		    'id'      => 'question_11_tooltip_d2c',
		    'type'    => 'textarea_small'
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Summary
		/*-----------------------------------------------------------------------------------*/
		// $cmb->add_field( array(
		// 	'name'    => 'TOTAL VALUE OF SAVINGS & INVESTMENTS',
		// 	'desc'    => '',
		// 	'id'      => 'summary_all_total_d2c_label',
		// 	'type'    => 'textarea_small'
		// ) );
		// $cmb->add_field( array(
		// 	'name'    => 'FUNDS',
		// 	'desc'    => '',
		// 	'id'      => 'summary_funds_d2c_label',
		// 	'type'    => 'textarea_small'
		// ) );
		// $cmb->add_field( array(
		// 	'name'    => 'EX-TRADED INSTRUMENTS',
		// 	'desc'    => '',
		// 	'id'      => 'summary_ex_traded_d2c_label',
		// 	'type'    => 'textarea_small'
		// ) );
		// $cmb->add_field( array(
		// 	'name'    => 'CASH',
		// 	'desc'    => '',
		// 	'id'      => 'summary_cash_d2c_label',
		// 	'type'    => 'textarea_small'
		// ) );
		// $cmb->add_field( array(
		//     'name'    => 'TOTAL INVESTMENTS',
		//     'desc'    => '',
		//     'id'      => 'summary_investments_d2c_label',
		//     'type'    => 'textarea_small'
		// ) );
		// $cmb->add_field( array(
		//     'name'    => 'TRADING FREQUENCY',
		//     'desc'    => '',
		//     'id'      => 'summary_trading_freq_d2c_label',
		//     'type'    => 'textarea_small'
		// ) );
		// $cmb->add_field( array(
		// 	'name'    => 'AVERAGE TRADING AMOUNT',
		// 	'desc'    => '',
		// 	'id'      => 'summary_trading_amnt_d2c_label',
		// 	'type'    => 'textarea_small'
		// ) );

		/*-----------------------------------------------------------------------------------*/
		/*	Summary
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name' => 'Platform Calculator summary fields',
			'desc' => 'Below fields will be shown on platform calculator page only',
			'type' => 'title',
			'id'   => 'summary_question_fields'
		) );
		$cmb->add_field( array(
			'name'    => 'TOTAL VALUE OF SAVINGS & INVESTMENTS',
			'desc'    => '',
			'id'      => 'summary_all_total_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'FUNDS',
			'desc'    => '',
			'id'      => 'summary_funds_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'EX-TRADED INSTRUMENTS',
			'desc'    => '',
			'id'      => 'summary_ex_traded_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'CASH',
			'desc'    => '',
			'id'      => 'summary_cash_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'TOTAL INVESTMENTS',
		    'desc'    => '',
		    'id'      => 'summary_investments_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
		    'name'    => 'TRADING FREQUENCY',
		    'desc'    => '',
		    'id'      => 'summary_trading_freq_label',
		    'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'AVERAGE TRADING AMOUNT',
			'desc'    => '',
			'id'      => 'summary_trading_amnt_label',
			'type'    => 'textarea_small'
		) );


		/*RSPL TASK#307 Start*/
		$cmb->add_field( array(
			'name' => 'Quick Platform Calculator',
			'desc' => 'Below fields will be shown on Quick platform calculator page only',
			'type' => 'title',
			'id'   => 'simplified_question_fields'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 1 Label',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_1_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 1 Tooltip',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_1_tooltip',
			'type'    => 'textarea_small'
		) );

		$cmb->add_field( array(
			'name'    => 'Question 2 Label',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_2_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 2 Tooltip',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_2_tooltip',
			'type'    => 'textarea_small'
		) );

		$cmb->add_field( array(
			'name'    => 'Question 3 Label',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_3_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 3 Tooltip',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_3_tooltip',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 4 Label',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_4_label',
			'type'    => 'textarea_small'
		) );

		$cmb->add_field( array(
			'name'    => 'Question 5 Label',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_5_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 5 Tooltip',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_5_tooltip',
			'type'    => 'textarea_small'
		) );

		$cmb->add_field( array(
			'name'    => 'Question 6 Label',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_6_label',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 6 Tooltip',
			'desc'    => 'This text will be shown on Quick platform calculator page only',
			'id'      => 'simplified_question_6_tooltip',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name' => 'General',
			'desc' => 'General fields for calculator page',
			'type' => 'title',
			'id'   => 'general_question_fields'
		) );
		/*RSPL TASK#301 Start*/
		$cmb->add_field( array(
			'name'    => 'Register note',
			'desc'    => 'This text will be shown on register page only',
			'id'      => 'register_page_note',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Result page note(Adviser)',
			'desc'    => 'This text will be shown on Result page only',
			'id'      => 'result_page_page_note',
			'type'    => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name'    => 'Result page note(Consumer)',
			'desc'    => 'This text will be shown on Result page only',
			'id'      => 'result_page_page_consumer_note',
			'type'    => 'textarea_small'
		) );
	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}
		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'ctp' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}
	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}
}
/**
 * Helper function to get/return the CTP_Extra_Settings_Admin object
 * @since  0.1.0
 * @return CTP_Extra_Settings_Admin object
 */
function ctp_admin() {
	return CTP_Extra_Settings_Admin::get_instance();
}
/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function ctp_get_questions_option( $key = '' ) {
	return cmb2_get_option( ctp_admin()->key, $key );
}

// Get it started
ctp_admin();
