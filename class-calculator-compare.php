<?php

class Calculator_Compare {

	public $custody_charges;
	public $product_charges;
	public $dealing_charges;
	public $acc_openning_charges;
	public $platforms;
	public $platform;
	public $platform_id;
	public $user_data;
	public $year_cost = array();
	public $totals;
	public $order_by;
	public $order;
	public $is_excluded = false;
	const PLATFORM_TYPE_ADVISED = 1;
	const PLATFORM_TYPE_MANUAL = 2;
	const CALC_TYPE_AD_VALORAM = 1;
	const CALC_TYPE_FLAT_RATE = 2;
	const CALC_TYPE_PER_INVESTMENT = 3;
	const CALC_TYPE_PER_TRANSACTION = 4;
	const CALC_TYPE_PER_OPENNING = 5;
	const INV_TYPE_FUND = 1;
	const INV_TYPE_EX_TRADED = 2;
	const STATUS_APPROVED = 1;
	const STATUS_REJECTED = 0;
	const STATUS_PENDING = - 1;
	const STATUS_SWITCH_PENDING = 2;

	public function __construct( $platforms, $user_data, $order_by = 'cost_low_high' ) {

		$this->platforms = $platforms;
		$this->user_data = $user_data;

		switch ( $order_by ) {

			case 'alphabetical_az':
				$this->order_by = 'alphabetical';
				$this->order    = 'ASC';
				break;

			case 'cost_low_high':
				$this->order_by = 'cost';
				$this->order    = 'ASC';
				break;
			case 'cost_high_low':
				$this->order_by = 'cost';
				$this->order    = 'DESC';
				break;

			case 'rating_low_high':
				$this->order_by = 'rating';
				$this->order    = 'ASC';
				break;
			case 'rating_high_low':
				$this->order_by = 'rating';
				$this->order    = 'DESC';
				break;

			case 'recommendation_low_high':
				$this->order_by = 'recommendation';
				$this->order    = 'ASC';
				break;
			case 'recommendation_high_low':
				$this->order_by = 'recommendation';
				$this->order    = 'DESC';
				break;

			default:
				$this->order_by = 'order_by_cost';
				$this->order    = 'ASC';
				break;
		}
	}

	public function get_platform_cost( $platform, $user_data ) {

		$total      = 0;
		$over_years = $user_data['investments_today'];

		if ( $over_years === 'over_years' ) {

			foreach ( range( 1, $user_data['investments_over'] ) as $year ) {

				$planning_gia       = ( $this->user_data['planning_gia'] - $this->user_data['planning_ex_instruments_gia'] ) * $year;
				$planning_isa       = ( $this->user_data['planning_isa'] - $this->user_data['planning_ex_instruments_isa'] ) * $year;
				$planning_jisa      = ( $this->user_data['planning_jisa'] - $this->user_data['planning_ex_instruments_jisa'] ) * $year;
				$planning_sipp      = ( $this->user_data['planning_sipp'] - $this->user_data['planning_ex_instruments_sipp'] ) * $year;
				$planning_childsipp = ( $this->user_data['planning_childsipp'] - $this->user_data['planning_ex_instruments_childsipp'] ) * $year;

				$planning_ex_instruments_gia       = $this->user_data['planning_ex_instruments_gia'] * $year;
				$planning_ex_instruments_isa       = $this->user_data['planning_ex_instruments_isa'] * $year;
				$planning_ex_instruments_jisa      = $this->user_data['planning_ex_instruments_jisa'] * $year;
				$planning_ex_instruments_sipp      = $this->user_data['planning_ex_instruments_sipp'] * $year;
				$planning_ex_instruments_childsipp = $this->user_data['planning_ex_instruments_childsipp'] * $year;

				$user_data['funds_gia']       = $planning_gia + $this->user_data['funds_gia'] + $planning_ex_instruments_gia;
				$user_data['funds_isa']       = $planning_isa + $this->user_data['funds_isa'] + $planning_ex_instruments_isa;
				$user_data['funds_jisa']      = $planning_jisa + $this->user_data['funds_jisa'] + $planning_ex_instruments_jisa;
				$user_data['funds_sipp']      = $planning_sipp + $this->user_data['funds_sipp'] + $planning_ex_instruments_sipp;
				$user_data['funds_childsipp'] = $planning_childsipp + $this->user_data['funds_childsipp'] + $planning_ex_instruments_childsipp;

				$user_data['ex_instruments_gia']       = $planning_ex_instruments_gia + $this->user_data['ex_instruments_gia'];
				$user_data['ex_instruments_isa']       = $planning_ex_instruments_isa + $this->user_data['ex_instruments_isa'];
				$user_data['ex_instruments_jisa']      = $planning_ex_instruments_jisa + $this->user_data['ex_instruments_jisa'];
				$user_data['ex_instruments_sipp']      = $planning_ex_instruments_sipp + $this->user_data['ex_instruments_sipp'];
				$user_data['ex_instruments_childsipp'] = $planning_ex_instruments_childsipp + $this->user_data['ex_instruments_childsipp'];

				$total += $this->year_total( $platform, $user_data, $year );
			}

		} elseif ( $over_years === 'in_x_years' ) {
			$year               = $user_data['investments_in_x_years'];
			$planning_gia       = ( $this->user_data['planning_gia'] - $this->user_data['planning_ex_instruments_gia'] ) * $year;
			$planning_isa       = ( $this->user_data['planning_isa'] - $this->user_data['planning_ex_instruments_isa'] ) * $year;
			$planning_jisa      = ( $this->user_data['planning_jisa'] - $this->user_data['planning_ex_instruments_jisa'] ) * $year;
			$planning_sipp      = ( $this->user_data['planning_sipp'] - $this->user_data['planning_ex_instruments_sipp'] ) * $year;
			$planning_childsipp = ( $this->user_data['planning_childsipp'] - $this->user_data['planning_ex_instruments_childsipp'] ) * $year;

			$planning_ex_instruments_gia       = $this->user_data['planning_ex_instruments_gia'] * $year;
			$planning_ex_instruments_isa       = $this->user_data['planning_ex_instruments_isa'] * $year;
			$planning_ex_instruments_jisa      = $this->user_data['planning_ex_instruments_jisa'] * $year;
			$planning_ex_instruments_sipp      = $this->user_data['planning_ex_instruments_sipp'] * $year;
			$planning_ex_instruments_childsipp = $this->user_data['planning_ex_instruments_childsipp'] * $year;

			$user_data['funds_gia']       = $planning_gia + $this->user_data['funds_gia'] + $planning_ex_instruments_gia;
			$user_data['funds_isa']       = $planning_isa + $this->user_data['funds_isa'] + $planning_ex_instruments_isa;
			$user_data['funds_jisa']      = $planning_jisa + $this->user_data['funds_jisa'] + $planning_ex_instruments_jisa;
			$user_data['funds_sipp']      = $planning_sipp + $this->user_data['funds_sipp'] + $planning_ex_instruments_sipp;
			$user_data['funds_childsipp'] = $planning_childsipp + $this->user_data['funds_childsipp'] + $planning_ex_instruments_childsipp;

			$user_data['ex_instruments_gia']       = $planning_ex_instruments_gia + $this->user_data['ex_instruments_gia'];
			$user_data['ex_instruments_isa']       = $planning_ex_instruments_isa + $this->user_data['ex_instruments_isa'];
			$user_data['ex_instruments_jisa']      = $planning_ex_instruments_jisa + $this->user_data['ex_instruments_jisa'];
			$user_data['ex_instruments_sipp']      = $planning_ex_instruments_sipp + $this->user_data['ex_instruments_sipp'];
			$user_data['ex_instruments_childsipp'] = $planning_ex_instruments_childsipp + $this->user_data['ex_instruments_childsipp'];

			$total += $this->year_total( $platform, $user_data, $year );

		} else {
			$year  = 1;
			$total = $this->year_total( $platform, $user_data, $year );
		}

		return $total;
	}

	public function year_total( $platform, $user_data, $year ) {
		$this->platform = $platform;

		$method = get_post_meta( $this->platform_id, '_cplat_calculation_method', true );

		switch ( $method ) {
			case 'method1':
				$this->custody_charges = new Calculator_Custody_Charges_Method_1();
				break;

			case 'method2':
				$this->custody_charges = new Calculator_Custody_Charges_Method_2();
				break;

			case 'method3':
				$this->custody_charges = new Calculator_Custody_Charges_Method_3();
				break;

			case 'method4':
				$this->custody_charges = new Calculator_Custody_Charges_Method_4();
				break;

			case 'method5':
				$this->custody_charges = new Calculator_Custody_Charges_Method_5();
				break;

			default:
				$this->custody_charges = new Calculator_Custody_Charges_Method_1();
				break;
		}

		$this->product_charges      = new Calculator_Product_Charges();
		$this->dealing_charges      = new Calculator_Dealing_Charges();
		$this->acc_openning_charges = new Calculator_Acc_Openning_Charges();

		$this->custody_charges->set_platform_data( $platform );
		$this->product_charges->set_platform_data( $platform );
		$this->dealing_charges->set_platform_data( $platform );
		$this->acc_openning_charges->set_platform_data( $platform );

		$this->custody_charges->set_user_data( $user_data );
		$this->product_charges->set_user_data( $user_data );
		$this->dealing_charges->set_user_data( $user_data );
		$this->acc_openning_charges->set_user_data( $user_data );

		if ( $method === 'method5' ) {
			$this->is_excluded = $this->custody_charges->is_excluded_due_top_aua();
		} else {
			$this->is_excluded = false;
		}

		$custody  = $this->custody_charges->get_total();
		$product  = $this->product_charges->get_total();
		$dealing  = $this->dealing_charges->get_total();
		$openning = $this->acc_openning_charges->get_total();

		// Year custody - funds
		$this->year_cost[ 'year_' . $year ]['funds_total'] = $this->custody_charges->total_funds();

		// Year custody - ex traded
		$this->year_cost[ 'year_' . $year ]['ex_instruments'] = $this->custody_charges->total_ex_instruments();

		// Year custody - product charges
		$this->year_cost[ 'year_' . $year ]['product_charges'] = $this->product_charges->get_total();

		// Year dealing - funds
		$this->year_cost[ 'year_' . $year ]['dealing_charges_funds'] = $this->dealing_charges->total_funds();

		// Year dealing - extraded
		$this->year_cost[ 'year_' . $year ]['dealing_charges_ex_instruments'] = $this->dealing_charges->total_ex_instruments();

		if ( $year > 1 && $user_data['investments_today'] !== 'in_x_years' ) {
			$this->totals['funds']                          = $this->totals['funds'] + $this->year_cost[ 'year_' . $year ]['funds_total'];
			$this->totals['ex_instruments']                 = $this->totals['ex_instruments'] + $this->year_cost[ 'year_' . $year ]['ex_instruments'];
			$this->totals['product_charges']                = $this->totals['product_charges'] + $this->year_cost[ 'year_' . $year ]['product_charges'];
			$this->totals['dealing_charges_funds']          = $this->totals['dealing_charges_funds'] + $this->year_cost[ 'year_' . $year ]['dealing_charges_funds'];
			$this->totals['dealing_charges_ex_instruments'] = $this->totals['dealing_charges_ex_instruments'] + $this->year_cost[ 'year_' . $year ]['dealing_charges_ex_instruments'];

		} else {
			$this->totals['funds']                          = $this->year_cost[ 'year_' . $year ]['funds_total'];
			$this->totals['ex_instruments']                 = $this->year_cost[ 'year_' . $year ]['ex_instruments'];
			$this->totals['product_charges']                = $this->year_cost[ 'year_' . $year ]['product_charges'];
			$this->totals['dealing_charges_funds']          = $this->year_cost[ 'year_' . $year ]['dealing_charges_funds'];
			$this->totals['dealing_charges_ex_instruments'] = $this->year_cost[ 'year_' . $year ]['dealing_charges_ex_instruments'];
			if ( isset( $platform['acc_openning_fee'] ) ) {
				$this->totals['openning_fee'] = $openning;
			}
		}

		$total = $custody + $product + $dealing;

		$over_years = $user_data['investments_today'];

		if ( $over_years === 'in_x_years' ) {

			if ( $year === 1 ) {

				$this->totals['openning_charges_all'] = $this->acc_openning_charges->all;

				$this->totals['openning_fee']              = $openning;
				$this->year_cost['year_1']['openning_fee'] = $openning;

				$total = $total + $openning;
			} else {
				$this->totals['openning_fee'] = 0;
			}

		} else {

			if ( isset( $platform['acc_openning_fee'] ) && $year === 1 ) {
				$this->totals['openning_charges_all'] = $this->acc_openning_charges->all;

				$this->totals['openning_fee']              = $openning;
				$this->year_cost['year_1']['openning_fee'] = $openning;

				$total = $total + $openning;
			}
		}

		return $total;
	}

	public function get_over_x_years( $over_years, $total ) {

		// User didn't provide product values
		if ( $this->user_data['investment_products'] === 'no' && ! empty( $this->user_data['total_savings_and_investments'] ) ) {

			//Split total by 3
			$slice = $this->user_data['total_savings_and_investments'] / 3;

			$this->user_data['funds_gia']   = $slice;
			$this->user_data['funds_isa']   = $slice;
			$this->user_data['funds_jisa']  = 0;
			$this->user_data['funds_sipp']  = $slice;
			$this->user_data['funds_jsipp'] = 0;
		}

		$planning_gia       = $this->user_data['planning_gia'] * $over_years;
		$planning_isa       = $this->user_data['planning_isa'] * $over_years;
		$planning_jisa      = $this->user_data['planning_jisa'] * $over_years;
		$planning_sipp      = $this->user_data['planning_sipp'] * $over_years;
		$planning_childsipp = $this->user_data['planning_childsipp'] * $over_years;

		$planning_ex_instruments_gia       = $this->user_data['planning_ex_instruments_gia'] * $over_years;
		$planning_ex_instruments_isa       = $this->user_data['planning_ex_instruments_isa'] * $over_years;
		$planning_ex_instruments_jisa      = $this->user_data['planning_ex_instruments_jisa'] * $over_years;
		$planning_ex_instruments_sipp      = $this->user_data['planning_ex_instruments_sipp'] * $over_years;
		$planning_ex_instruments_childsipp = $this->user_data['planning_ex_instruments_childsipp'] * $over_years;

		$user_data = $this->user_data;

		$user_data['funds_gia']       = ( $planning_gia - $planning_ex_instruments_gia ) + $this->user_data['funds_gia'];
		$user_data['funds_isa']       = ( $planning_isa - $planning_ex_instruments_isa ) + $this->user_data['funds_isa'];
		$user_data['funds_jisa']      = ( $planning_jisa - $planning_ex_instruments_jisa ) + $this->user_data['funds_jisa'];
		$user_data['funds_sipp']      = ( $planning_sipp - $planning_ex_instruments_sipp ) + $this->user_data['funds_sipp'];
		$user_data['funds_childsipp'] = ( $planning_childsipp - $planning_ex_instruments_childsipp ) + $this->user_data['funds_childsipp'];

		$user_data['ex_instruments_gia']       = $planning_ex_instruments_gia + $this->user_data['ex_instruments_gia'];
		$user_data['ex_instruments_isa']       = $planning_ex_instruments_isa + $this->user_data['ex_instruments_isa'];
		$user_data['ex_instruments_jisa']      = $planning_ex_instruments_jisa + $this->user_data['ex_instruments_jisa'];
		$user_data['ex_instruments_sipp']      = $planning_ex_instruments_sipp + $this->user_data['ex_instruments_sipp'];
		$user_data['ex_instruments_childsipp'] = $planning_ex_instruments_childsipp + $this->user_data['ex_instruments_childsipp'];

		$custody_charges = new Calculator_Custody_Charges();
		$product_charges = new Calculator_Product_Charges();

		$custody_charges->set_platform_data( $this->platform );
		$product_charges->set_platform_data( $this->platform );

		$custody_charges->set_user_data( $user_data );
		$product_charges->set_user_data( $user_data );

		$custody = $custody_charges->get_total();
		$product = $product_charges->get_total();

		$total = $custody + $product;

		return $total;
	}

	public function get_platform_queue() {

		$platform_queue = array();
		foreach ( $this->platforms as $key => $platform ) {

			$current_version = cplat_get_current_version( array_reverse( $platform->data, true ) );

			// Has current version expired date?
			$current_version_expiry_date = time() > strtotime( $platform->data[ $current_version ]['data']['active_to'] );
			if ( $current_version_expiry_date === true ) {
				continue;
			}

			// Has current version support for invested products?
			if ( $this->platform_products_available( $platform->data[ $current_version ]['data'], $this->user_data ) === false ) {
				continue;
			}

			//$platform_queue['ID'] = $platform->ID;
			$this->platform_id                       = $platform->ID;
			$platform_queue[ $platform->ID ]['data'] = $platform;

			$platform_queue[ $platform->ID ]['cost'] = $this->get_platform_cost( $platform->data[ $current_version ]['data'], $this->user_data );

			if ( $this->is_excluded === true ) {
				unset( $platform_queue[ $platform->ID ] );
				continue;
			}

			$platform_queue[ $platform->ID ]['year_cost'] = $this->year_cost;

			$platform_queue[ $platform->ID ]['custody_charges']['total'] = $this->totals['funds'] + $this->totals['ex_instruments'] + $this->totals['product_charges'];

			$platform_queue[ $platform->ID ]['custody_charges']['funds']       = $this->custody_charges->funds;
			$platform_queue[ $platform->ID ]['custody_charges']['funds_total'] = $this->totals['funds'];

			$platform_queue[ $platform->ID ]['custody_charges']['ex_instruments']       = $this->custody_charges->ex_instruments;
			$platform_queue[ $platform->ID ]['custody_charges']['ex_instruments_total'] = $this->totals['ex_instruments'];

			$platform_queue[ $platform->ID ]['product_charges']['total'] = $this->totals['product_charges'];
			$platform_queue[ $platform->ID ]['product_charges']['all']   = $this->product_charges->all;

			$platform_queue[ $platform->ID ]['dealing_charges']['total']          = $this->totals['dealing_charges_funds'] + $this->totals['dealing_charges_ex_instruments'] + $this->totals['openning_fee'];
			$platform_queue[ $platform->ID ]['dealing_charges']['funds']          = $this->dealing_charges->funds;
			$platform_queue[ $platform->ID ]['dealing_charges']['funds']['total'] = $this->totals['dealing_charges_funds'];

			$platform_queue[ $platform->ID ]['dealing_charges']['ex_instruments']          = $this->dealing_charges->ex_instruments;
			$platform_queue[ $platform->ID ]['dealing_charges']['ex_instruments']['total'] = $this->totals['dealing_charges_ex_instruments'];

			$platform_queue[ $platform->ID ]['acc_openning_fee']['total'] = $this->totals['openning_fee'];
			$platform_queue[ $platform->ID ]['acc_openning_fee']['all']   = $this->totals['openning_charges_all'];

			$platform_queue[ $platform->ID ]['year_cost'] = $this->year_cost;

		}

		switch ( $this->order_by ) {

			case 'alphabetical':
				$queue = $this->order_by_alphabetical( $platform_queue, $this->order );
				break;

			case 'cost':
				$queue = $this->order_by_cost( $platform_queue, $this->order );
				break;

			case 'rating':
				$queue = $this->order_by_rating( $platform_queue, $this->order );
				break;

			case 'recommendation':
				$queue = $this->order_by_recommendation( $platform_queue, $this->order );
				break;

			default:
				$queue = $this->order_by_cost( $platform_queue );
				break;
		}

		return $queue;
	}

	public function order_by_alphabetical( $platform_queue, $order = 'ASC' ) {

		$alphabetical = array();
		foreach ( $platform_queue as $key => $row ) {
			$alphabetical[ $key ] = strtolower( $row['data']->post_title );
		}

		if ( $order === 'ASC' ) {
			$order_key = SORT_ASC;
		}
		if ( $order === 'DESC' ) {
			$order_key = SORT_DESC;
		}

		array_multisort( $alphabetical, $order_key, SORT_STRING, $platform_queue );

		return $platform_queue;
	}

	public function order_by_cost( $platform_queue, $order = 'ASC' ) {

		$cost = array();
		foreach ( $platform_queue as $key => $row ) {
			$cost[ $key ] = $row['cost'];
		}

		if ( $order === 'ASC' ) {
			$order_key = SORT_ASC;
		}

		if ( $order === 'DESC' ) {
			$order_key = SORT_DESC;
		}

		array_multisort( $cost, $order_key, $platform_queue );

		return $platform_queue;
	}

	public function order_by_rating( $platform_queue, $order = 'DESC' ) {

		$rating_groups = array();

		foreach ( $platform_queue as $key => $value ) {
			$rating_groups[ get_post_meta( $value['data']->ID, '_cplat_rating', true ) ][] = $value;
		}

		if ( $order === 'ASC' ) {
			krsort( $rating_groups );
		}
		if ( $order === 'DESC' ) {
			ksort( $rating_groups );
		}

		$ordered_az = [];
		foreach ( $rating_groups as $key => $value ) {
			$alphabetical = [];
			foreach ( $value as $key => $row ) {
				$alphabetical[ $key ] = strtolower( $row['data']->post_title );
			}
			array_multisort( $alphabetical, SORT_ASC, $value );
			$ordered_az[] = $value;
		}

		$result = array();
		foreach ( $ordered_az as $key => $value ) {
			$result = array_merge( $value, $result );
		}

		return $result;
	}

	public function order_by_recommendation( $platform_queue, $order = 'DESC' ) {

		$recommendation = array();
		foreach ( $platform_queue as $key => $row ) {

			$recommendation_value = get_post_meta( $row['data']->ID, '_cplat_recommended_funds_list', true );

			if ( $recommendation_value === 'yes' ) {
				$recommendation[ $key ] = 1;
			} else {
				$recommendation[ $key ] = 0;
			}
		}
		if ( $order === 'ASC' ) {
			$order_key = SORT_ASC;
		}
		if ( $order === 'DESC' ) {
			$order_key = SORT_DESC;
		}

		array_multisort( $recommendation, $order_key, $platform_queue );

		return $platform_queue;
	}

	public function platform_products_available( $platform, $user_data ) {

		// User didn't provide product values
		if ( $user_data['investment_products'] === 'no' && ! empty( $user_data['total_savings_and_investments'] ) ) {

			//Split total by 3
			$slice = $user_data['total_savings_and_investments'] / 3;

			$user_data['funds_gia']   = $slice;
			$user_data['funds_isa']   = $slice;
			$user_data['funds_jisa']  = 0;
			$user_data['funds_sipp']  = $slice;
			$user_data['funds_jsipp'] = 0;
		}

		// User has fund gia
		if ( ( ( $user_data['funds_gia'] - $user_data['ex_instruments_gia'] ) > 0 ) || ( ( $user_data['planning_gia'] - $user_data['planning_ex_instruments_gia'] ) > 0 ) ) {
			if ( ! isset( $platform['gia_supported'] ) || $platform['gia_supported'] !== '1' ) {
				return false;
			}
		}

		// User has fund isa
		if ( ( ( $user_data['funds_isa'] - $user_data['ex_instruments_isa'] ) > 0 ) || ( ( $user_data['planning_isa'] - $user_data['planning_ex_instruments_isa'] ) > 0 ) ) {
			if ( ! isset( $platform['isa_supported'] ) || $platform['isa_supported'] !== '1' ) {
				return false;
			}
		}
		// User has fund jisa
		if ( ( ( $user_data['funds_jisa'] - $user_data['ex_instruments_jisa'] ) > 0 ) || ( ( $user_data['planning_jisa'] - $user_data['planning_ex_instruments_jisa'] ) > 0 ) ) {
			if ( ! isset( $platform['jisa_supported'] ) || $platform['jisa_supported'] !== '1' ) {
				return false;
			}
		}

		// User has fund sipp
		if ( ( ( $user_data['funds_sipp'] - $user_data['ex_instruments_sipp'] ) > 0 ) || ( ( $user_data['planning_sipp'] - $user_data['planning_ex_instruments_sipp'] ) > 0 ) ) {
			if ( ! isset( $platform['sipp_supported'] ) || $platform['sipp_supported'] !== '1' ) {
				return false;
			}
		}

		// User has fund jsipp
		if ( ( ( $user_data['funds_jsipp'] - $user_data['ex_instruments_jsipp'] ) > 0 ) || ( ( $user_data['planning_jsipp'] - $user_data['planning_ex_instruments_jsipp'] ) > 0 ) ) {
			if ( ! isset( $platform['childsipp_supported'] ) || $platform['childsipp_supported'] !== '1' ) {
				return false;
			}
		}

		// User has ex_traded gia
		if ( ( $user_data['ex_instruments_gia'] > 0 ) || ( $user_data['planning_ex_instruments_gia'] > 0 ) ) {
			if ( ! isset( $platform['ex_instruments_gia_supported'] ) || $platform['ex_instruments_gia_supported'] !== '1' ) {
				return false;
			}
		}

		// User has ex_traded isa
		if ( ( $user_data['ex_instruments_isa'] > 0 ) || ( $user_data['planning_ex_instruments_isa'] > 0 ) ) {
			if ( ! isset( $platform['ex_instruments_isa_supported'] ) || $platform['ex_instruments_isa_supported'] !== '1' ) {
				return false;
			}
		}

		// User has ex_traded jisa
		if ( ( $user_data['ex_instruments_jisa'] > 0 ) || ( $user_data['planning_ex_instruments_jisa'] > 0 ) ) {
			if ( ! isset( $platform['ex_instruments_jisa_supported'] ) || $platform['ex_instruments_jisa_supported'] !== '1' ) {
				return false;
			}
		}

		// User has ex_traded sipp
		if ( ( $user_data['ex_instruments_sipp'] > 0 ) || ( $user_data['planning_ex_instruments_sipp'] > 0 ) ) {
			if ( ! isset( $platform['ex_instruments_sipp_supported'] ) || $platform['ex_instruments_sipp_supported'] !== '1' ) {
				return false;
			}
		}

		// User has ex_traded jsipp
		if ( ( $user_data['ex_instruments_jsipp'] > 0 ) || ( $user_data['planning_ex_instruments_jsipp'] > 0 ) ) {
			if ( ! isset( $platform['ex_instruments_childsipp_supported'] ) || $platform['ex_instruments_childsipp_supported'] !== '1' ) {
				return false;
			}
		}

		return true;
	}
}