<?php

class Platform_Vendor_Data {

	protected $rules;
	protected $fields;
	CONST PLATFORM_FEE = 1;

	CONST PLATFORM_TABLE = 'platcalc_platform';
	CONST PLATFORM_DATA_TABLE = 'platcalc_platform_data';
	CONST PLATFORM_DATA_CHARGES_TABLE = 'platcalc_platform_data_charges';



	public function __construct() {
		$this->rules = $this->rules();
		$this->fields = $this->get_fields();

	}

	public function rules() {

		return array(
			'platform_fees'                             => '',
			'platform_investment_charges'               => '',
			'platform_product_annual_chares'            => '',
			'product_annual_charge_min'                 => '',
			'product_annual_charge_max'                 => '',
			'platform_dealing_switching_fees'           => '',
			'platform_transfer_account_opening_charges' => '',
			'platform_transfer_out_closure_charges'     => '',
			'platform_sipp_charges'                     => '',
			'user_id'                                   => '',
			'active_from'                               => '',
			'active_to'                                 => '',
			'gia_supported'                             => '',
			'isa_supported'                             => '',
			'sipp_supported'                            => '',
			'childsipp_supported'                       => ''
		);
	}

	public function get_fields() {
		return array_keys( $this->rules );
	}

	public function sanitize( array $data ) {

		$clean_data = array();
		foreach ( $this->rules as $field => $callback ) {
			if ( isset( $data[ $field ] ) && "" !== $data[ $field ] ) {
				$clean_data[ $field ] = call_user_func( $callback, $data[ $field ] );
			}
		}

		return $clean_data;
	}

	public function validate( array $data ) {
	}

	public function save_platform_meta( $post_id, $data ) {

		// $clean_meta = $this->sanitize( $data );
		$clean_meta = $data;

		foreach ( $clean_meta as $key => $value ) {

			if ($key === 'active_from' && empty( $value ) ) {

				$value = date('d M Y', time());
			}
			if ( $key === 'active_to' && empty( $value ) ) {
				$value = date( 'd M Y', strtotime( '+1 years' ) );
			}


		}
	}

	function get( $post_id ) {

		$post = get_post( $post_id );

		foreach ( $this->fields as $key ) {
			$value      = get_post_meta( $post_id, $key, true );
			$post->$key = $value;
		}

		return $post;
	}

	function get_all( $user_id ) {

		$args = array(
			'meta_query'     => array(
				array(
					'key'   => 'user_id',
					'value' => $user_id
				)
			),
			'post_type'      => 'platform_data',
			'posts_per_page' => - 1
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post_key => $post ) {

			foreach ( $this->fields as $key ) {
				$value                    = get_post_meta( $post->ID, $key, true );
				$posts[ $post_key ]->$key = $value;
			}
		}

		return $posts;
	}

	function can_edit( $user_id, $post_id ) {

		$data_owner_user_id = get_post_meta( $post_id, 'user_id', true );

		if ( intval( $user_id ) === intval( $data_owner_user_id ) ) {
			return true;
		} else {
			return false;
		}
	}
}
