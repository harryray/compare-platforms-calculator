<?php

class Calculator_Product_Charges {

	public $product_annual_charges;
	public $product_annual_charge_min;
	public $product_annual_charge_max;
	public $user_data;
	public $number_of_trades;
	public $vat_rate = '20%';

	public $all = array();

	public function __construct() {

	}

	public function set_user_data($user_data) {

		$this->user_data = $user_data;

        $this->user_data['funds_gia']   = $this->user_data['funds_gia'] - $this->user_data['ex_instruments_gia'];
        $this->user_data['funds_isa']   = $this->user_data['funds_isa'] - $this->user_data['ex_instruments_isa'];
        $this->user_data['funds_jisa']  = $this->user_data['funds_jisa'] - $this->user_data['ex_instruments_jisa'];
        $this->user_data['funds_sipp']  = $this->user_data['funds_sipp'] - $this->user_data['ex_instruments_sipp'];
        $this->user_data['funds_jsipp'] = $this->user_data['funds_jsipp'] - $this->user_data['ex_instruments_jsipp'];

		$this->number_of_trades = $this->user_data['investment_frequency'];

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

	public function set_platform_data($platform_data) {
		$this->product_annual_charges = $platform_data['platform_product_annual_chares'];
		$this->product_annual_charge_min = isset( $platform_data['product_annual_charge_min'] ) ? intval($platform_data['product_annual_charge_min']) : '';
		$this->product_annual_charge_max = isset( $platform_data['product_annual_charge_max'] ) ? intval($platform_data['product_annual_charge_max']) : '';
	}

	public function get_total() {

		$this->user_data['funds_childsipp'] = $this->user_data['funds_jsipp'];
		$this->user_data['ex_instruments_childsipp'] = $this->user_data['ex_instruments_jsipp'];

		$total = 0;
		$products = array( 'gia', 'isa', 'jisa', 'sipp', 'childsipp' );

			/**
			 * Calculate cost for each product
			 */
			foreach ( $products as $product ) {
				$product_amount = $this->user_data['funds_'.$product] + $this->user_data['ex_instruments_'.$product];

				if( $this->is_tiered( $product ) ) {
					// All the rows where this product charge is added
					$product_rows = array();
					foreach( $this->product_annual_charges as $key => $row ) {
						if ( ! empty( $row[$product] ) ) {
							$product_rows[] = $key;
						}
					}

					$product_tier = $this->which_tier( $product, $product_amount );

					$total_product = $this->get_cost_of_product(
						$this->product_annual_charges[$product_tier]['type'],
						$this->product_annual_charges[$product_tier][$product],
						$product_amount
					);

					if ($this->has_vat( $this->product_annual_charges[$product_tier]['vat'] ) ) {
						$total_product += $this->get_vat_amount( $total_product );
					}

					$total = $total + $total_product;
					$this->all[$product] = $total_product;
				} else {

					foreach( $this->product_annual_charges as $row ) {
						if ( ! empty( $row[$product] ) ) {
							$rate = $row[$product];
							$type = $row['type'];
							$vat = $row['vat'];
						}
					}

					$total_product = $this->get_cost_of_product(
						$type,
						$rate,
						$product_amount
					);

					if ($this->has_vat( $vat ) ) {
						$total_product += $this->get_vat_amount( $total_product );
					}

					$total = $total + $total_product;
					$this->all[$product] = $total_product;
				}
			}

		// Is there a min cap applied
		if (isset($this->product_annual_charge_min)
			&& !empty($this->product_annual_charge_min)
			&& $this->product_annual_charge_min > 0
			) {

			if ($total < $this->product_annual_charge_min) {
				$total = $this->product_annual_charge_min;
			}
		}

		// Is there a max cap applied
		if (isset($this->product_annual_charge_max)
			&& !empty($this->product_annual_charge_max)
			&& $this->product_annual_charge_max > 0
			) {

			if ($total > $this->product_annual_charge_max) {
				$total = $this->product_annual_charge_max;
			}
		}

		return $total;
	}

	public function get_cost_of_product( $type, $product_rate, $amount ) {

		if ( $amount === 0 || empty( $amount ) ) {
			return 0;
		}

		switch ($type) {
			case 'ad_valorem':
				$total = $this->calc_ad_valorem( $amount, $product_rate );
				break;

			case 'flat_rate':
				$total = $product_rate;
				break;

			case 'per_investment':
				$total = $this->calc_per_investment( $product_rate, $this->number_of_trades );
				break;

			case 'per_transaction':
				$total = $this->calc_per_transaction( $product_rate, $this->number_of_trades );
				break;
		}

		return $total;
	}

	public function calc_ad_valorem( $amount, $product_rate ) {
		return ( $product_rate / 100 ) * $amount;
	}

	public function calc_per_investment( $product_rate, $number_of_trades ) {
		return $number_of_trades * 2 * $product_rate;
	}

	public function calc_per_transaction( $product_rate, $number_of_trades ) {
		return $number_of_trades * $product_rate;
	}

	public function is_tiered( $product) {

		$tiered = false;

		foreach( $this->product_annual_charges as $key => $row ) {
			if ( empty( $row[$product] ) ) {
				continue;
			}
			// If at least one row of particular investment type is tiered than fees are tiered
			if ( isset($row['tiered']) && $row['tiered'] === '1' ) {
				$tiered = true;
				break;
			}
		}

		return $tiered;
	}

	public function which_tier( $product, $product_amount ) {

		$fee_key = null;
		$count = 0;
		foreach ($this->product_annual_charges as $key => $value) {

			if ( empty( $value[$product] ) ) {
				continue;
			}
			$count++;
			if ( empty($value['aua_to']) ) {
				$value['aua_to'] = 999999999;
			}
			if ( empty($value['aua_from']) ) {
				$value['aua_from'] = 0;
			}

			if ( $count === 1 ) {
				$bottom_bracket = 0;
				$top_bracket = $value['aua_to'];
			}
			if ( $count > 1 ) {
				$bottom_bracket = $value['aua_from'];
				$top_bracket = $value['aua_to'];
			}
			$bottom_bracket = ctp_clean_number($bottom_bracket);
			$top_bracket = ctp_clean_number($top_bracket);

			if ($bottom_bracket <= $product_amount && $product_amount <= $top_bracket ) {
				$fee_key = $key;
				break;
			}
		}

		return $fee_key;
	}


	public function has_vat( $value ) {
		if ( isset( $value )
			&&
			$value === '1') {
			return true;
		} else {
			return false;
		}
	}

	public function get_vat_amount( $price_exc_vat ) {
		$vat_amount = $this->vat_rate * ( $price_exc_vat / 100 );
		$vat_amount = round($vat_amount, 2); // round to 2 decimal places
		return $vat_amount;
	}

}