<?php


class Subscriber_Data {

	protected $rules;
	protected $fields;
	public $errors;

	public function __construct() {
		$this->rules  = $this->rules();
		$this->fields = $this->get_fields();
	}

	public function rules() {

		return array(
			'total_savings_and_investments' => 'sanitize_money',
			'investment_products'           => 'sanitize_text_field', 
			'investment_products_simplified' => 'sanitize_text_field', //Ticket#315
			'funds_isa'                     => 'sanitize_money',
			'funds_lifetime_isa'            => 'sanitize_money', //Ticket#307
			'funds_jisa'                    => 'sanitize_money',
			'funds_sipp'                    => 'sanitize_money',
			'funds_jsipp'                   => 'sanitize_money',
			'funds_gia'                     => 'sanitize_money',
			'funds_onshore_bond'            => 'sanitize_money',
			'funds_offshore_bond'           => 'sanitize_money',
			'total_all'                     => 'sanitize_money',
			'total_shares'                  => 'sanitize_money',
			'total_funds'                   => 'sanitize_money',
			'inv_management_type'           => 'sanitize_text_field',

			'investment_stocks_shares'     => 'sanitize_text_field',
			'ex_instruments_isa'           => 'sanitize_money',
			'ex_instruments_lifetime_isa'  => 'sanitize_money', //Ticket#307
			'ex_instruments_jisa'          => 'sanitize_money',
			'ex_instruments_sipp'          => 'sanitize_money',
			'ex_instruments_jsipp'         => 'sanitize_money',
			'ex_instruments_gia'           => 'sanitize_money',
			'ex_instruments_onshore_bond'  => 'sanitize_money',
			'ex_instruments_offshore_bond' => 'sanitize_money',

			'planning_invest'        => 'sanitize_text_field',
			'planning_isa'           => 'sanitize_money',
			'planning_lifetime_isa'  => 'sanitize_money', //Ticket#307
			'planning_jisa'          => 'sanitize_money',
			'planning_sipp'          => 'sanitize_money',
			'planning_jsipp'         => 'sanitize_money',
			'planning_gia'           => 'sanitize_money',
			'planning_onshore_bond'  => 'sanitize_money',
			'planning_offshore_bond' => 'sanitize_money',

			'planning_stocks_shares'                => 'sanitize_text_field',
			'planning_ex_instruments_isa'           => 'sanitize_money',
			'planning_ex_instruments_lifetime_isa'  => 'sanitize_money', //Ticket#307
			'planning_ex_instruments_jisa'          => 'sanitize_money',
			'planning_ex_instruments_sipp'          => 'sanitize_money',
			'planning_ex_instruments_jsipp'         => 'sanitize_money',
			'planning_ex_instruments_gia'           => 'sanitize_money',
			'planning_ex_instruments_onshore_bond'  => 'sanitize_money',
			'planning_ex_instruments_offshore_bond' => 'sanitize_money',

			'investment_frequency_funds'     => 'intval',
			'investment_frequency_ex_traded' => 'intval',
			'average_investment_funds'       => 'sanitize_money',
			'average_investment_ex_traded'   => 'sanitize_money',
			'num_of_trades'                  => 'sanitize_text_field',
			'age'                            => 'intval',
			'gender'                         => 'sanitize_text_field',
			'retirement_year'                => 'intval',
			'investments_today'              => 'sanitize_text_field',
			'investments_over'               => 'intval',
			'investments_in_x_years'         => 'intval',

			// RSPL Ticket#192 checklist-2 Start
			'is_growth'         => 'sanitize_text_field',
			'growth_rate'         => 'intval',
			'roles_commas'         => 'sanitize_text_field',
			'user_type'         => 'sanitize_text_field',
			//RSPL Ticket#192 checklist-2 End

			// RSPL Ticket#192 checklist-3 Start
			'initial_advice_type'         => 'sanitize_text_field',
			'initial_adviser_charges'         => 'sanitize_money',
			'initial_adviser_charges_total'         => 'sanitize_money',
			'annual_advice_type'         => 'sanitize_text_field',
			'annual_adviser_charges'         => 'sanitize_money',
			//RSPL Ticket#192 checklist-3 End

			// RSPL Ticket#222 checklist-3 Start
			'link_portfolio'         => 'sanitize_text_field',
			// RSPL Ticket#222 checklist-3 End
			'version'                        => 'sanitize_text_field',
			'version_name'                   => 'sanitize_text_field',
			'update'                         => 'intval',


            //RSPL TASK#21
            //RSPL Task#37
            'total_savings_and_investments_cash' => 'sanitize_money',
            'total_savings_and_investments_total' => 'sanitize_money',
            'funds_isa_cash'                     => 'sanitize_money',
						'funds_isa_total'                    => 'sanitize_money',
						'funds_lifetime_isa_cash'            => 'sanitize_money', //Ticket#307
            'funds_lifetime_isa_total'           => 'sanitize_money', //Ticket#307
            'funds_jisa_cash'                    => 'sanitize_money',
            'funds_jisa_total'                    => 'sanitize_money',
            'funds_sipp_cash'                    => 'sanitize_money',
            'funds_sipp_total'                    => 'sanitize_money',
            'funds_jsipp_cash'                   => 'sanitize_money',
            'funds_jsipp_total'                   => 'sanitize_money',
            'funds_gia_cash'                     => 'sanitize_money',
            'funds_gia_total'                     => 'sanitize_money',
            'funds_onshore_bond_cash'            => 'sanitize_money',
            'funds_onshore_bond_total'            => 'sanitize_money',
            'funds_offshore_bond_cash'           => 'sanitize_money',
            'funds_offshore_bond_total'           => 'sanitize_money',
            'total_all_cash'                     => 'sanitize_money',
						'total_all_total'                     => 'sanitize_money',
			

			//Begin : RSPL TASK#236
			'funds_int_isa_cash'                     => 'sanitize_money',
			'funds_int_lifetime_isa_cash'            => 'sanitize_money', //Ticket#307
            'funds_int_jisa_cash'                    => 'sanitize_money',
            'funds_int_sipp_cash'                    => 'sanitize_money',
            'funds_int_jsipp_cash'                   => 'sanitize_money',
            'funds_int_gia_cash'                     => 'sanitize_money',
            'funds_int_onshore_bond_cash'            => 'sanitize_money',
            'funds_int_offshore_bond_cash'           => 'sanitize_money',
			//End : RSPL TASK#236
			


            'planning_isa_cash'           => 'sanitize_money',
			'planning_isa_total'           => 'sanitize_money',
			'planning_lifetime_isa_cash'           => 'sanitize_money', //Ticket#307
            'planning_lifetime_isa_total'           => 'sanitize_money', //Ticket#307
            'planning_jisa_cash'          => 'sanitize_money',
            'planning_jisa_total'          => 'sanitize_money',
            'planning_sipp_cash'          => 'sanitize_money',
            'planning_sipp_total'          => 'sanitize_money',
            'planning_jsipp_cash'         => 'sanitize_money',
            'planning_jsipp_total'         => 'sanitize_money',
            'planning_gia_cash'           => 'sanitize_money',
            'planning_gia_total'           => 'sanitize_money',
            'planning_onshore_bond_cash'  => 'sanitize_money',
            'planning_onshore_bond_total'  => 'sanitize_money',
            'planning_offshore_bond_cash' => 'sanitize_money',
            'planning_offshore_bond_total' => 'sanitize_money',

            //RSPL Ticket#192 Start
            'total_yearly_investment_value' => 'sanitize_money',
            'total_yearly_investment_total' => 'sanitize_money',
            'yearly_investment_funds' => 'sanitize_money',
            'yearly_investment_ex' => 'sanitize_money',
            'yearly_investment_cash' => 'sanitize_money',
			//RSPL Ticket#192 End
			//RSPL #280
			'is_adviser_charges'         => 'sanitize_text_field',
			'recommended_portfolio'  => 'sanitize_text_field', // Ticket#334
			'ethical_investment'  => 'sanitize_text_field', // Ticket#334
			'utm_source'  => 'sanitize_text_field' // Ticket#371
		);
	}

	public function get_fields() {
		return array_keys( $this->rules );
	}

	public function sanitize( array $data ) {

		$clean_data = array();
		foreach ( $this->rules as $field => $callback ) {
			if ( isset( $data[ $field ] ) ) {
				$clean_data[ $field ] = call_user_func( $callback, $data[ $field ] );

				if ( $field === 'investment_frequency' ) {
					//$clean_data[$field] = '';
				}
				if ( $field === 'age' && empty( $data[ $field ] ) ) {
					ctp_errors()->add_error( 'ctp_age', __( 'Whoops, you’ve missed this question!' ) );
				}
				/*			    if ($field === 'retirement_year' && empty($data[$field])) {
									ctp_errors()->add_error( 'ctp_retirement_year', __( 'Whoops, you’ve missed this question!' ) );
								}
								if ($field === 'retirement_year' && $data['age'] > $data['retirement_year']) {
									ctp_errors()->add_error( 'ctp_age', __( 'Retirement age must be higher or same as your age' ) );
								}
								if ($field === 'age' && $data['retirement_year'] < $data['age']) {
									ctp_errors()->add_error( 'ctp_age', __( 'Your age must be lower or same as your retirement age' ) );
								}*/

			}
		}

		return $clean_data;
	}

	public function get_subscriber_data( $user ) {

		foreach ( $this->fields as $field ) {
			$value        = get_user_meta( $user->ID, $field, true );
			$user->$field = $value;
		}

		return $user;
	}

	public function save_user_meta( $user, $data, $version ) {
		session_start();
		if ( isset( $_POST['update'] ) ) {
			$data['update'] = $_POST['update'];
		} else {
			$data['update'] = null;
		}
		$clean_meta = $this->sanitize( $data );
		//$_SESSION['cplat_update'] = isset( $_POST['update'] ) ? $_POST['update'] : 0;
		//$saved_data = get_user_meta( $user->ID, 'user_financial_data', true );
		//echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
		$saved_data = $_SESSION['user_financial_data'];
		if( is_user_logged_in() ){
			$saved_data = get_user_meta( $user->ID, 'user_financial_data', true );
		}
		if ( empty( $saved_data ) ) {
			$saved_data = array();
		}
		if ( $version === 'new' ) {
			$version = time();
		}
		if ( isset( $saved_data[ $version ] ) ) {
			foreach ( $clean_meta as $key => $value ) {
				$saved_data[ $version ][ $key ] = $value;
			}
		} else {
			$saved_data[ $version ] = $clean_meta;
		}
		//echo '<pre>'; print_r($saved_data); exit;
		//update_user_meta( $user->ID, 'user_financial_data', $saved_data );
		$_SESSION['user_financial_data'] = $saved_data;
		if( is_user_logged_in() ){
			update_user_meta( $user->ID, 'user_financial_data', $saved_data );	
		}

		return $version;
	}

	public function save_user_results_meta( $user_id, $data, $version ) {
		session_start();
		$saved_data = $_SESSION['user_ctp_results_data'];
		if( is_user_logged_in() ){
			$saved_data = get_user_meta( $user_id, 'user_ctp_results_data', true );
		}
		if ( empty( $saved_data ) ) {
			$saved_data = array();
		}
		$saved_data[ $version ] = $data;
		$_SESSION['user_ctp_results_data'] = $saved_data;
		if( is_user_logged_in() ){
			update_user_meta( $user_id, 'user_ctp_results_data', $saved_data );
		}
		return $version;
	}

	public function get_user_results_meta( $user_id, $version ) {
		session_start();
		$saved_data = $_SESSION['user_ctp_results_data'];
		if( is_user_logged_in() ){
			$saved_data = get_user_meta( $user_id, 'user_ctp_results_data', true );
		}$return     = [];
		if ( isset( $saved_data[ $version ] ) ) {
			$return = $saved_data[ $version ];
		}
		return $return;
	}
}