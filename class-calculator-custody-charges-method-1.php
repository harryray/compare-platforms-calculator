<?php

class Calculator_Custody_Charges_Method_1 {

	public $platform_data;
	public $custody_fees;
	public $user_data;
	public $tiered;
	public $tier;
	public $aua;
	public $band;
	public $vat_rate = '20%';
	public $platform_data_funds;
	public $platform_data_ex_instruments;
	public $funds_tiered;
	public $ex_instruments_tiered;
	public $number_of_trades;

	public $total_all_assets;

	// Debug
	public $funds = array();
	public $ex_instruments = array();

	public function __construct() {

	}

	public function set_user_data($user_data) {
		$this->user_data = $user_data;
		$this->user_data['funds_gia'] = $this->user_data['funds_gia'] - $this->user_data['ex_instruments_gia'];
		$this->user_data['funds_isa'] = $this->user_data['funds_isa'] - $this->user_data['ex_instruments_isa'];
		$this->user_data['funds_jisa'] = $this->user_data['funds_jisa'] - $this->user_data['ex_instruments_jisa'];
		$this->user_data['funds_sipp'] = $this->user_data['funds_sipp'] - $this->user_data['ex_instruments_sipp'];
		$this->user_data['funds_jsipp'] = $this->user_data['funds_jsipp'] - $this->user_data['ex_instruments_jsipp'];
		$this->number_of_trades = $this->user_data['investment_frequency'];

		// User didn't provide product values
		if ( $this->user_data['investment_products'] === 'no' && ! empty( $this->user_data['total_savings_and_investments'] ) ) {

			//Split total by 3
			$slice = $this->user_data['total_savings_and_investments'] / 3;

			$this->user_data['funds_gia'] = $slice;
			$this->user_data['funds_isa'] = $slice;
			$this->user_data['funds_jisa'] = 0;
			$this->user_data['funds_sipp'] = $slice;
			$this->user_data['funds_jsipp'] = 0;
		}

		$total_all_assets = $this->user_data['ex_instruments_gia'];
		$total_all_assets += $this->user_data['ex_instruments_isa'];
		$total_all_assets += $this->user_data['ex_instruments_jisa'];
		$total_all_assets += $this->user_data['ex_instruments_sipp'];
		$total_all_assets += $this->user_data['ex_instruments_jsipp'];

		$total_all_assets += $this->user_data['funds_gia'];
		$total_all_assets += $this->user_data['funds_isa'];
		$total_all_assets += $this->user_data['funds_jisa'];
		$total_all_assets += $this->user_data['funds_sipp'];
		$total_all_assets += $this->user_data['funds_jsipp'];
		$this->total_all_assets = $total_all_assets;
	}

	public function set_platform_data($platform_data) {

		$this->platform_data = $platform_data;

		$this->custody_fees = $platform_data['platform_fees'];

		$this->funds_tiered = $this->is_tiered( 'fund' );
		$this->ex_instruments_tiered = $this->is_tiered( 'ex_traded' );

		$this->platform_data_funds = array();
		$this->platform_data_ex_instruments = array();

		foreach ($this->custody_fees as $key => $value) {
			if ( $value['investment_type'] === 'fund' ) {
				$this->platform_data_funds[] = $value;
			}
			if ( $value['investment_type'] === 'ex_traded' ) {
				$this->platform_data_ex_instruments[] = $value;
			}
		}
	}

	public function get_total() {

		$funds_total = $this->total_funds();
		$ex_instruments_total = $this->total_ex_instruments();
		$all_total = $funds_total + $ex_instruments_total;

		// Is there a funds specific cap ?
		$funds_cap = !empty( $this->platform_data['funds_custody_fees_min'] ) || !empty( $this->platform_data['funds_custody_fees_max'] ) ? true : false;

		// Is there a ex instruments specific cap ?
		$ex_instruments_cap = !empty( $this->platform_data['ex_instruments_custody_fees_min'] ) || !empty( $this->platform_data['ex_instruments_custody_fees_max'] ) ? true : false;

		// Is there a general price cap ?
		$all_cap = ! empty( $this->platform_data['custody_fees_min'] ) || ! empty( $this->platform_data['custody_fees_max'] ) ? true : false;
		//die(var_dump( $all_total ));

		if ( ( $funds_cap === false || $ex_instruments_cap === false ) && $all_cap === true ) {

				$min = ctp_clean_number( $this->platform_data['custody_fees_min'] );
				$max = ctp_clean_number( $this->platform_data['custody_fees_max'] );

			if ( $all_total < $min && ! empty( $min ) ) {
				$all_total = $min;
			} elseif ( $all_total > $max && ! empty( $max ) ) {
				$all_total = $max;
			}
		}

		return $all_total;
	}

	public function total_funds() {

		//fix this stupid stuff
		$this->user_data['funds_childsipp'] = $this->user_data['funds_jsipp'];

		$total = 0;
		$products = array( 'gia', 'isa', 'jisa', 'sipp', 'childsipp' );

		if( $this->funds_tiered ) {

			/**
			 * Calculate cost for each product
			 */
			foreach ( $products as $product ) {
				$total_product = 0;
				$funds_tier = $this->which_tier( 'fund', $this->total_all_assets );

				$total_product = $this->get_cost_of_product(
					$this->platform_data_funds[$funds_tier]['type'],
					$this->platform_data_funds[$funds_tier][$product],
					$this->user_data['funds_' . $product]
				);
				$total_product = round( $total_product, 2 );
				if ($this->has_vat( $this->platform_data_funds[$funds_tier]['vat'] ) ) {
					$total_product += $this->get_vat_amount( $total_product );
				}

				// die(var_dump(
				// 	$this->total_all_assets,
				// 	$funds_tier,
				// 	$this->platform_data_funds[$funds_tier]['type'],
				// 	$this->platform_data_funds[$funds_tier][$product],
				// 	$this->user_data['funds_' . $product]
				// ));

				$total = $total + $total_product;

				// die(var_dump(
				// 	$funds_tier,
				// 	$this->platform_data_funds,
				// 	$total_product,
				// 	$total,
				// 	$this->platform_data_funds[$funds_tier]['type'],
				// 	$this->platform_data_funds[$funds_tier][$product],
				// 	$this->user_data['funds_' . $product]

				// 	));

				// DEBUG
				$this->funds[$product] = $total_product;

			}
		} else {
			$total = 0;

			foreach ($this->platform_data_funds as $key => $value) {

				foreach ( $products as $product ) {
					$total_product = 0;
					$total_product = $this->get_cost_of_product(
						$this->platform_data_funds[$key]['type'],
						$this->platform_data_funds[$key][$product],
						$this->user_data['funds_' . $product]
					);

					$total_product = round( $total_product, 2 );
					if($product === 'isa') {
					// die(var_dump(
					// 	$total_product,
					// 	$this->platform_data_funds[$key]['type'],
					// 	$this->platform_data_funds[$key][$product],
					// 	$this->user_data['funds_' . $product]));
					}

					if ($this->has_vat( $this->platform_data_funds[$key]['vat'] ) ) {
						$total_product += $this->get_vat_amount( $total_product );
					}

					$total = $total + $total_product;
					// DEBUG
					$this->funds[$product] = $total_product;
				}
			}
		}

		// Is there a funds specific cap ?
		$funds_cap = isset( $this->platform_data['funds_custody_fees_min'] ) || isset( $this->platform_data['funds_custody_fees_max'] ) ? true : false;

		if ( $funds_cap && $total > 0 ) {

			$min = ctp_clean_number( $this->platform_data['funds_custody_fees_min'] );
			$max = ctp_clean_number( $this->platform_data['funds_custody_fees_max'] );

			if ( $total < $min && ! empty( $min ) ) {
				$total = $min;
			} elseif ( $total > $max && ! empty( $max ) ) {
				$total = $max;
			}
		}
		return $total;
	}

	public function total_ex_instruments() {


		//fix this stupid stuff
		$this->user_data['ex_instruments_childsipp'] = $this->user_data['ex_instruments_jsipp'];

		$total = 0;
		$products = array( 'gia', 'isa', 'jisa', 'sipp', 'childsipp' );

		if( $this->ex_instruments_tiered ) {

			/**
			 * Calculate cost for each product
			 */
			foreach ( $products as $product ) {
				$total_product = 0;
				$ex_instruments_tier = $this->which_tier( 'ex_traded', $this->total_all_assets );

				$total_product = $this->get_cost_of_product(
					$this->platform_data_ex_instruments[$ex_instruments_tier]['type'],
					$this->platform_data_ex_instruments[$ex_instruments_tier][$product],
					$this->user_data['ex_instruments_' . $product]
				);
				$total_product = round( $total_product, 2 );
				if ($this->has_vat( $this->platform_data_ex_instruments[$ex_instruments_tier]['vat'] ) ) {
					$total_product += $this->get_vat_amount( $total_product );
				}

				$total = $total + $total_product;
				// die(var_dump(
				// 	$ex_instruments_tier,
				// 	$this->platform_data_ex_instruments,
				// 	$total_product,
				// 	$total,
				// 	$this->platform_data_ex_instruments[$ex_instruments_tier]['type'],
				// 	$this->platform_data_ex_instruments[$ex_instruments_tier][$product],
				// 	$this->user_data['ex_instruments_' . $product]

				// 	));
				// DEBUG
				$this->ex_instruments[$product] = $total_product;
			}
		} else {

			$total = 0;
			foreach ($this->platform_data_ex_instruments as $key => $value) {

				foreach ( $products as $product ) {
					$total_product = $this->get_cost_of_product(
						$this->platform_data_ex_instruments[$key]['type'],
						$this->platform_data_ex_instruments[$key][$product],
						$this->user_data['ex_instruments_' . $product]
					);
					$total_product = round( $total_product, 2 );
					if ($this->has_vat( $this->platform_data_ex_instruments[$key]['vat'] ) ) {
						$total_product += $this->get_vat_amount( $total_product );
					}
					$total = $total + $total_product;
						// die(var_dump(
						// 	$this->platform_data_ex_instruments[$key]['type'],
						// 	$this->platform_data_ex_instruments[$key][$product],
						// 	$this->user_data['ex_instruments_' . $product]
						// ));

					// DEBUG
					$this->ex_instruments[$product] = $total_product;
				}
			}
		}

		// Is there a ex instruments specific cap ?
		$ex_instruments_cap = ! empty( $this->platform_data['ex_instruments_custody_fees_min'] ) || ! empty( $this->platform_data['ex_instruments_custody_fees_max'] ) ? true : false;

		// Is there a general price cap ?
		$all_cap = ! empty( $this->platform_data['custody_fees_min'] ) || ! empty( $this->platform_data['custody_fees_max'] ) ? true : false;

		if ( $ex_instruments_cap && $total > 0 ) {

			$min = ctp_clean_number( $this->platform_data['ex_instruments_custody_fees_min'] );
			$max = ctp_clean_number( $this->platform_data['ex_instruments_custody_fees_max'] );

			if ( $total < $min && ! empty( $min ) ) {
				$total = $min;
			}
			if ( $total > $max && ! empty( $max ) ) {
				$total = $max;
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

	public function is_percentage() {
		if ( $this->custody_fees[$this->tier]['type'] === 'ad_valorem' ) {
			return true;
		} else {
			return false;
		}
	}

	public function is_tiered( $investment_type ) {

		$tiered = false;

		foreach( $this->custody_fees as $key => $row ) {

			// Not investment type we are looking for
			if ( $row['investment_type'] !== $investment_type ) {
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

public function which_tier( $investment_type, $product_amount ) {

		$fee_key = null;
		$count = 0;
		$fees = array();

		if ( $investment_type === 'fund' ) {
			$fees = $this->platform_data_funds;
		}
		if ( $investment_type === 'ex_traded' ) {
			$fees = $this->platform_data_ex_instruments;
		}
		foreach ($fees as $key => $value) {

			if ($fees[$key]['investment_type'] !== $investment_type ) {
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