<?php

class Calculator_Acc_Openning_Charges {

	public $acc_openning_fee;
	public $user_data;
	public $vat_rate = '20%';

	public $all = array();
	const TRANSFER_TYPES = [ 1 => 'Cash', 2 => 'In Specie', 3 => 'Abroad', 4=>'All' ];

	public function __construct() {

	}

	public function set_user_data( $user_data ) {
		$this->user_data = $user_data;

		// User didn't provide product values
		if ( $this->user_data['investment_products'] === 'no' && ! empty( $this->user_data['total_savings_and_investments'] ) ) {

			$slice = $this->user_data['total_savings_and_investments'] / 3;

			$this->user_data['funds_gia']   = $slice;
			$this->user_data['funds_isa']   = $slice;
			$this->user_data['funds_jisa']  = 0;
			$this->user_data['funds_sipp']  = $slice;
			$this->user_data['funds_jsipp'] = 0;
		}
	}

	public function set_platform_data( $platform_data ) {
		$this->acc_openning_fee = $platform_data['acc_openning_fee'];
	}

	public function get_total() {

		$total    = 0;
		$products = array( 'gia', 'isa', 'jisa', 'sipp', 'childsipp' );

		foreach ( $products as $product ) {

			if (
				( isset( $this->user_data[ 'funds_' . $product ] ) && empty( $this->user_data[ 'funds_' . $product ] ) )
				||
				( isset( $this->user_data[ 'ex_traded_' . $product ] ) && empty( $this->user_data[ 'ex_traded_' . $product ] ) )
			) {

				continue;
			}

			$product_amount = $this->acc_openning_fee[ $product ];

			if ( $this->has_vat() ) {
				$product_amount += $this->get_vat_amount( $product_amount );
			}

			$this->all[ $product ] = $product_amount;
			$total                 = $total + $product_amount;
		}

		return $total;

	}

	public function has_vat() {
		if ( isset( $this->acc_openning_fee['vat'] )
		     &&
		     $this->acc_openning_fee['vat'] === '1' ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_vat_amount( $price_exc_vat ) {
		$vat_amount = $this->vat_rate * ( $price_exc_vat / 100 );
		$vat_amount = round( $vat_amount, 2 ); // round to 2 decimal places

		return $vat_amount;
	}
}