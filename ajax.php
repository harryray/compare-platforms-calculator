<?php
add_action( 'wp_ajax_cplat_save_platform_data', 'cplat_save_platform_data_callback' );
add_action( 'wp_ajax_nopriv_cplat_save_platform_data', 'cplat_save_platform_data_callback' );
/**
 * List of selected dates
 * 'edit_others_edit_platform_datas'
 */
function cplat_save_platform_data_callback() {

	/**
	 * Currently logged in user
	 */
	$user = wp_get_current_user();

	// Sanitize data
	$data   = $_POST;
	$result = array();
	parse_str( $data['data'], $d );


	$switch_platform_id = isset( $d['platform_id'] ) ? intval( $d['platform_id'] ) : 0;
	$platform_ids       = get_user_meta( $user->ID, '_cplat_user_platform', true );

	if ( $switch_platform_id ) {
		if ( is_array( $platform_ids ) && in_array( $switch_platform_id, $platform_ids ) ) {
			$platform_id = $switch_platform_id;
		} else {
			$platform_id = $platform_ids;
		}
	}

	if ( current_user_can( 'edit_others_edit_platform_datas' ) ) {
		$platform_id = isset( $d['platform_id'] ) ? $d['platform_id'] : '';
	}
	if ( ! isset( $platform_id ) || 0 === $platform_id ) {
		return false;
	}
	$ctp_api = new CTP_API( $user );
	//get next version
	$version = $ctp_api->get_next_platform_version( $platform_id );

	$old = get_post_meta( $platform_id, 'platform_data', true );

	$new = array();

	/**
	 * This is first ever version
	 */
	if ( empty( $old ) ) {
		$version_id = 1;
	}

	/**
	 * Create new version or update current one
	 * depends on who is editing it
	 */

	$version_id = $version + 1;
	if ( $version_id !== 1 ) {
		$new = $old;
	}

	$new[ $version_id ]['data']         = $d;
	$new[ $version_id ]['date_created'] = date( 'Y-m-d' );

	if ( empty( $new[ $version_id ]['data']['active_from'] ) ) {
		$new[ $version_id ]['data']['active_from'] = date( 'Y-m-d', time() );
	} else {
		$new[ $version_id ]['data']['active_from'] = date( 'Y-m-d', strtotime( $new[ $version_id ]['data']['active_from'] ) );
	}
	if ( empty( $new[ $version_id ]['data']['active_to'] ) ) {
		$new[ $version_id ]['data']['active_to'] = date( 'Y-m-d', strtotime( '+1 years' ) );
	} else {
		$new[ $version_id ]['data']['active_to'] = date( 'Y-m-d', strtotime( $new[ $version_id ]['data']['active_to'] ) );
	}
	$new[ $version_id ]['date_created'] = date( 'Y-m-d' );
    $new[$version_id]['ethical_investment'] = ( isset($d['ethical_investment']) ? $d['ethical_investment'] : 0 );
	$updated                            = update_post_meta( $platform_id, 'platform_data', $new );
	/**
	 * Set response status
	 */
	if ( $updated === true || ( is_int( $updated ) && $updated > 0 ) ) {
		$result = array(
			'updated'        => true,
			'message'        => __( 'Saved', 'cplat' ),
			'new_version_id' => $version_id
		);
		$ret    = CTP_API::PLATFORM_SUCCESS_RESPONSE;
		if ( is_plugin_active( 'ctp-api/ctp-api.php' ) ) {
			$ctp_api = new CTP_API();
			if ( $version_id === 1 ) {
				$new['version_id']                         = $version_id;
				$platform_data                             = $ctp_api->get_platform_data_from_post( $platform_id );
				$data_to_send['platform']['platform_name'] = $platform_data['name'];
				$data_to_send['platform']['platform_id']   = $platform_data['ID'];
				$data_to_send['platform']['info_url']      = $platform_data['info_link'];
				$data_to_send['platform']['published']     = $platform_data['published'];
				$data_to_send['platform']['platform_type'] = $platform_data['type'] == 'advised' ? Calculator_Compare::PLATFORM_TYPE_ADVISED : Calculator_Compare::PLATFORM_TYPE_MANUAL;
				switch ( $platform_data['method'] ) {
					case'method1':
						$data_to_send['platform']['calculation_method'] = 1;
						break;
					case'method2':
						$data_to_send['platform']['calculation_method'] = 2;
						break;
					case'method3':
						$data_to_send['platform']['calculation_method'] = 3;
						break;
					case'method4':
						$data_to_send['platform']['calculation_method'] = 4;
						break;
					case'method5':
						$data_to_send['platform']['calculation_method'] = 5;
						break;
					default:
						$data_to_send['platform']['calculation_method'] = 1;
						break;
				}
				$data_to_send['platform']['recommended']     = $platform_data['recommended'] == 'yes' ? 1 : 0;
				$data_to_send['platform']['img']             = $platform_data['img'];
				$data_to_send['platform']['rating']          = $platform_data['rating'];
				$data_to_send['platform']['url']             = $platform_data['url'];
				$data_to_send['platform']['sandbox']         = 0;
				$data_to_send['platform']['platform_id']     = $platform_id;
				$data_to_send['platform_data']               = $new[ $version_id ]['data'];
				$data_to_send['platform_data']['version']    = $version_id;
				$data_to_send['platform_data']['rec_status'] = Calculator_Compare::STATUS_PENDING;
				$ret                                         = $ctp_api->ctp_api_add_platform( $data_to_send );
				if ( $ret ) {
					$ret = $ctp_api->ctp_api_update_platform( $platform_id, $new[ $version_id ] );
				}
			} else {
				//make sure the id is the latest
				$new[ $version_id ]['data']['version_id'] = $version_id;
				$ret                                      = $ctp_api->ctp_api_update_platform( $platform_id, $new[ $version_id ] );
			}
		}
		if ( $ret === CTP_API::PLATFORM_SUCCESS_RESPONSE ) {
			$platform_post = get_post( $platform_id );

			$display_name = '';
			if ( isset( $user->display_name ) ) {
				$display_name = $user->display_name;
			}
			cplat_platform_update_notification( $platform_post->post_title, $display_name );

		}
	} else {
		$result = array(
			'updated' => $updated,
			// 'platform_id' =>  $platform_id,
			'message' => __( 'There was a problem saving your data', 'cplat' )
		);
	}

	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_cplat_update_results', 'cplat_update_results_callback' );
add_action( 'wp_ajax_nopriv_cplat_update_results', 'cplat_update_results_callback' );
function cplat_update_results_callback() {
	$currency            = '£';
	$user                = wp_get_current_user();
	$version             = (int) $_POST['version'];
    //$user_financial_data = get_user_meta( $user->ID, 'user_financial_data', true );
    session_start();
    $user_financial_data = $_SESSION['user_financial_data'];
	$user_data           = isset( $user_financial_data[ $version ] ) ? $user_financial_data[ $version ] : false;

    $start_of_data = strpos( $_SERVER['HTTP_REFERER'], 'htb_serialized_data') + 20;
    $end_of_data   = strpos( $_SERVER['HTTP_REFERER'], 'main_version');
    
    //var_dump(substr($_SERVER['HTTP_REFERER'], $start_of_data, ($end_of_data - strlen($_SERVER['HTTP_REFERER']))));

    if($_POST['htb_data']) {
        $htb_data = stripslashes($_POST['htb_data']);
    } else {
    $htb_data = substr($_SERVER['HTTP_REFERER'], $start_of_data);
    }

	if ( ! $user_data && strlen($htb_data) > 10 ) {
        $user_data = (object)(unserialize(trim(stripslashes(urldecode($htb_data)))));
  
    } else if(!$user_data && strlen($htb_data) < 10) {
		echo 'user data missing';

	}
	
    $user->data = (object) $user_data;
	$calc_user  = $user;
    // RSPL Task#25
    global $wpdb;
    $linked_version = $version;
    $get_all_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);

    if ( isset( $get_all_linked_version[0]['linked_version'] ) && !empty( $get_all_linked_version[0]['linked_version'] ) ) {
        $t_all_linked_versions = explode(',',$get_all_linked_version[0]['linked_version']);
        $i_total_linked_versions = count($t_all_linked_versions);
        $user_data = '';
        $combined_user_data = array();
        $i_linked_version_counter = 1;
        $t_ignorable_keys = array('investment_products','investment_stocks_shares','update','inv_management_type','planning_invest','planning_stocks_shares','gender','investments_today','investments_over','investment_frequency','point_in_time','order_by','order');
        foreach ($t_all_linked_versions as $t_all_linked_version) {
            $combined_user_data[$t_all_linked_version] = isset($user_financial_data[$t_all_linked_version]) ? $user_financial_data[$t_all_linked_version] : '';
            if ( $i_linked_version_counter == 1 ) {
                $user_data = $combined_user_data[$t_all_linked_version];
            } else {
                foreach ($combined_user_data[$t_all_linked_version] as $s_key=>$s_val) {
                    if ( !in_array($s_key, $t_ignorable_keys) ) {
                        $user_data[$s_key] = $user_data[$s_key] + $combined_user_data[$t_all_linked_version][$s_key];
                    } else {
                        $user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                        if ( isset($combined_user_data[$t_all_linked_version][$s_key]) && !empty($combined_user_data[$t_all_linked_version][$s_key]) ) {
                            $user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                        }
                    }
                }
            }
            $i_linked_version_counter++;
        }
        // $user_data['age'] = (int)($user_data['age'] / 4);
        $user_data['age'] = 55;
        // $user_data['inv_management_type'] = 'myself';
        $user_data['roles_commas'] = $_POST['roles_commas'];
        $user_data['user_type'] = $_POST['roles_commas'];
        $user_data['inv_management_type'] = 'adviser';
        $user_data['investment_products'] = 'yes';
        // $user_data['investments_today'] = $_POST['investments_today'];
        // $user_data['investments_over'] = intval( $_POST['investments_over'] );
        // $user_data['investments_in_x_years'] = intval( $_POST['investments_in_x_years'] );
        if ( isset($_POST['order_by']) && !empty($_POST['order_by']) ) {
            $update_order_by = $_POST['order_by'];
        } else {
            $update_order_by = 'cost_low_high';
        }
        switch ( $update_order_by ) {

            case 'alphabetical_az':
                $user_data['order_by'] = 'name';
                $user_data['order']    = 'asc';
                break;

            case 'cost_low_high':
                $user_data['order_by'] = 'cost';
                $user_data['order']    = 'asc';
                break;
            case 'cost_high_low':
                $user_data['order_by'] = 'cost';
                $user_data['order']    = 'desc';
                break;

            case 'rating_low_high':
                $user_data['order_by'] = 'rating';
                $user_data['order']    = 'asc';
                break;
            case 'rating_high_low':
                $user_data['order_by'] = 'rating';
                $user_data['order']    = 'desc';
                break;

            case 'recommendation_low_high':
                $user_data['order_by'] = 'recommendation';
                $user_data['order']    = 'asc';
                break;
            case 'recommendation_high_low':
                $user_data['order_by'] = 'recommendation';
                $user_data['order']    = 'desc';
                break;

            default:
                $user_data['order_by'] = 'order_by_cost';
                $user_data['order']    = 'desc';
                break;
        }
        //echo '<pre>'; print_r($_POST); exit;
        $user->data = (object) $user_data;
    } else {
        //RSPL Task#37
        $_POST['total_funds'] = $_POST['total_funds'] + $_POST['total_shares'];
        if ( in_array( 'adviser', (array) $user->roles ) ) {
            $user_data['age'] = 55;
            // $user_data['inv_management_type'] = 'myself';
            $user_data['inv_management_type'] = 'adviser';
            $user_data['investment_products'] = 'yes';
        }
        $user_data = (array)$user_data;
        $user_data       = cplat_set_adjustments( $user_data, $_POST );
        $user->data      = (object) $user_data;

        $subscriber_data = new Subscriber_Data();
        //echo 'AJAX.php Before Save Userdata<pre>'; print_r($user_data); exit;
        $subscriber_data->save_user_meta( $user, $user_data, $version );

        if ( $user_data ) {
            $user_data['version_name'] = $user_data;

        }
    }


    if( $_POST['roles_commas'] == 'subscriber' ){
        $user_data['inv_management_type'] = 'myself';
    }
    if(sizeof($user_data) == "1") {

      $htb_data = $_POST['htb_data'];
      
      $slashesStrippedInputArray = stripslashes($htb_data);
      $user_data  = unserialize(stripslashes($slashesStrippedInputArray));

    }
    $user->data = (object) $user_data;
	/**
	 * Filters changed but not product value
	 *
	 *
	 * } else {
	 * /**
	 * Case: No filter have changed
	 *
	 *
	 *
	 * }*/


	if ( $user_data ) {
        //RSPL Task#37
		$platforms_data = cplat_get_platform_list( $user->data, $user->ID, $_POST['order_by'], false );

        $platforms_queue = $platforms_data['list'];


        // HTB SORTING
        switch($_POST['order_by']) {
        case 'cost_low_high':
            $cost_order = array();
            foreach ( $platforms_queue as $key => $row ) {
                $cost_order[ $key ] = $row['cost'];
            }
            array_multisort($cost_order, SORT_ASC, $platforms_queue );
            $platforms_data['list'] = $platforms_queue;
            break;
        case 'cost_high_low':
            $cost_order = array();
            foreach ( $platforms_queue as $key => $row ) {
                $cost_order[ $key ] = $row['cost'];
            }

            array_multisort($cost_order, SORT_DESC, $platforms_queue );
            $platforms_data['list'] = $platforms_queue;
            break;
        case 'rating_high_low':
            $rating_groups = array();
            foreach ( $platforms_queue as $key => $row ) {
                $rating_groups[$key] = $row['data']['rating'];
            }
            array_multisort($rating_groups, SORT_DESC, $platforms_queue );
            $platforms_data['list'] = $platforms_queue;
            break;
        case 'rating_low_high':
            $rating_groups = array();
            foreach ( $platforms_queue as $key => $row ) {
                $rating_groups[$key] = $row['data']['rating'];
            }
            array_multisort($rating_groups, SORT_ASC, $platforms_queue );
            $platforms_data['list'] = $platforms_queue;
            break;
        case 'alphabetical_az':
            $alphabetical = array();
            foreach ( $platforms_queue as $key => $row ) {
                $alphabetical[ $key ] = strtolower( $row['data']['platform_name'] );
            }
            array_multisort($alphabetical, SORT_ASC, SORT_STRING, $platforms_queue );
            $platforms_data['list'] = $platforms_queue;
            break;
        }
        // /HTB SORTING

		$platforms      = $platforms_data['list'];
		$results_count  = $platforms_data['count'];
	} else {
		$platforms     = array();
		$results_count = 0;
	}


	$from_updated_values = true;


    //RSPL Task#83
    //ob_start();
    //include 'templates/calculator-results-listing.php';
    //echo ob_get_clean();
    // RSPL Task#164
    $over_years = $_POST['investments_today'];
    $year = $_POST['investments_in_x_years'];
    $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS = cplat_sanitize_number($_POST['total_savings_and_investments_total']);
    if ($over_years === 'over_years') {
        $year = $_POST['investments_over'];
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    else if ($over_years === 'in_x_years') {
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }

    if ( isset( $_REQUEST['dual_response'] ) && $_REQUEST['dual_response'] == 1 ) {
        ob_start();
        include 'templates/calculator-results-listing.php';
        $listing_content = ob_get_contents();
        ob_get_clean();
        // ob_start();
        // include 'templates/print-result-popup.php';
        // $popup_content = ob_get_contents();
        // ob_get_clean();
        // RSPL Task#159
        // echo json_encode(array( 'listing_content'=>$listing_content, 'popup_content'=>$popup_content) );
        //echo json_encode( array( 'listing_content'=>$listing_content ) ); //Ticket#236
        echo $listing_content; exit; //RSPL #237

    } else {
        ob_start();
        include 'templates/calculator-results-listing.php';
        echo ob_get_clean();
    }
	die();
}

// RSPL Task#162 start
add_action( 'wp_ajax_cplat_update_results_for_print', 'cplat_update_results_for_print_callback' );
add_action( 'wp_ajax_nopriv_cplat_update_results_for_print', 'cplat_update_results_for_print_callback' );
function cplat_update_results_for_print_callback() {

	$currency            = '£';
	$user                = wp_get_current_user();
	$version             = (int) $_POST['version'];
    //$user_financial_data = get_user_meta( $user->ID, 'user_financial_data', true );
    session_start();
    $user_financial_data = $_SESSION['user_financial_data'];
	$user_data           = isset( $user_financial_data[ $version ] ) ? $user_financial_data[ $version ] : false;

	if ( ! $user_data ) {
		echo 'user data missing';

	}

	$user->data = (object) $user_data;
	$calc_user  = $user;

    // RSPL Task#25
    global $wpdb;
    $linked_version = $version;
    $get_all_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);
    if ( isset( $get_all_linked_version[0]['linked_version'] ) && !empty( $get_all_linked_version[0]['linked_version'] ) ) {
        $t_all_linked_versions = explode(',',$get_all_linked_version[0]['linked_version']);
        $i_total_linked_versions = count($t_all_linked_versions);
        $user_data = '';
        $combined_user_data = array();
        $i_linked_version_counter = 1;
        $t_ignorable_keys = array('investment_products','investment_stocks_shares','update','inv_management_type','planning_invest','planning_stocks_shares','gender','investments_today','investments_over','investment_frequency','point_in_time','order_by','order');
        foreach ($t_all_linked_versions as $t_all_linked_version) {
            $combined_user_data[$t_all_linked_version] = isset($user_financial_data[$t_all_linked_version]) ? $user_financial_data[$t_all_linked_version] : '';
            if ( $i_linked_version_counter == 1 ) {
                $user_data = $combined_user_data[$t_all_linked_version];
            } else {
                foreach ($combined_user_data[$t_all_linked_version] as $s_key=>$s_val) {
                    if ( !in_array($s_key, $t_ignorable_keys) ) {
                        $user_data[$s_key] = $user_data[$s_key] + $combined_user_data[$t_all_linked_version][$s_key];
                    } else {
                        $user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                        if ( isset($combined_user_data[$t_all_linked_version][$s_key]) && !empty($combined_user_data[$t_all_linked_version][$s_key]) ) {
                            $user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                        }
                    }
                }
            }
            $i_linked_version_counter++;
        }
        // $user_data['age'] = (int)($user_data['age'] / 4);
        $user_data['age'] = 55;
        // $user_data['inv_management_type'] = 'myself';
        $user_data['inv_management_type'] = 'adviser';
        $user_data['investment_products'] = 'yes';
        $user_data['investments_today'] = $_POST['investments_today'];
        $user_data['investments_over'] = intval( $_POST['investments_over'] );
        $user_data['investments_in_x_years'] = intval( $_POST['investments_in_x_years'] );
        if ( isset($_POST['order_by']) && !empty($_POST['order_by']) ) {
            $update_order_by = $_POST['order_by'];
        } else {
            $update_order_by = 'cost_low_high';
        }
        switch ( $update_order_by ) {

            case 'alphabetical_az':
                $user_data['order_by'] = 'name';
                $user_data['order']    = 'asc';
                break;

            case 'cost_low_high':
                $user_data['order_by'] = 'cost';
                $user_data['order']    = 'asc';
                break;
            case 'cost_high_low':
                $user_data['order_by'] = 'cost';
                $user_data['order']    = 'desc';
                break;

            case 'rating_low_high':
                $user_data['order_by'] = 'rating';
                $user_data['order']    = 'asc';
                break;
            case 'rating_high_low':
                $user_data['order_by'] = 'rating';
                $user_data['order']    = 'desc';
                break;

            case 'recommendation_low_high':
                $user_data['order_by'] = 'recommendation';
                $user_data['order']    = 'asc';
                break;
            case 'recommendation_high_low':
                $user_data['order_by'] = 'recommendation';
                $user_data['order']    = 'desc';
                break;

            default:
                $user_data['order_by'] = 'order_by_cost';
                $user_data['order']    = 'desc';
                break;
        }

        $user->data = (object) $user_data;
    } else {
        //RSPL Task#37
        $_POST['total_funds'] = $_POST['total_funds'] + $_POST['total_shares'];
        if ( in_array( 'adviser', (array) $user->roles ) ) {
            $user_data['age'] = 55;
            // $user_data['inv_management_type'] = 'myself';
            $user_data['inv_management_type'] = 'adviser';
            $user_data['investment_products'] = 'yes';
        }
        $user_data       = cplat_set_adjustments( $user_data, $_POST );
        $user->data      = (object) $user_data;
        $subscriber_data = new Subscriber_Data();
        $subscriber_data->save_user_meta( $user, $user_data, $version );

        if ( $user_data ) {
            $user_data['version_name'] = $user_data;

        }
    }
    if( $_POST['roles_commas'] == 'subscriber' ){
        $user_data['inv_management_type'] = 'myself';
    }
    $user->data = (object) $user_data;


	/**
	 * Filters changed but not product value
	 *
	 *
	 * } else {
	 * /**
	 * Case: No filter have changed
	 *
	 *
	 *
	 * }*/


	if ( $user_data ) {
        //RSPL Task#37
		$platforms_data = cplat_get_platform_list( $user->data, $user->ID, $_POST['order_by'], false );
		$platforms      = $platforms_data['list'];
		$results_count  = $platforms_data['count'];
	} else {
		$platforms     = array();
		$results_count = 0;
	}


	$from_updated_values = true;

    // RSPL Task#164
    $over_years = $_POST['investments_today'];
    $year = $_POST['investments_in_x_years'];
    $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS = cplat_sanitize_number($_POST['total_savings_and_investments_total']);
    if ($over_years === 'over_years') {
        $year = $_POST['investments_over'];
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    else if ($over_years === 'in_x_years') {
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    ob_start();
    include 'templates/print-result-popup.php';
    $popup_content = ob_get_contents();
    ob_get_clean();
    echo json_encode( array( 'popup_content'=>$popup_content ) );
	die();
}
// RSPL Task#162 end

add_action( 'wp_ajax_cplat_get_results', 'cplat_get_results_callback' );
add_action( 'wp_ajax_nopriv_cplat_get_results', 'cplat_get_results_callback' );
function cplat_get_results_callback() {
  session_start();
	$currency            = '£';
	$user                = wp_get_current_user();
	$version             = isset( $_POST['version'] ) ? (int) $_POST['version'] : 0;
    //$user_financial_data = get_user_meta($user->ID, 'user_financial_data', true);
    $user_financial_data = $_SESSION['user_financial_data'];
	// RSPL Task#25
    global $wpdb;
    $linked_version = $version;
    $get_all_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);
    if ( isset( $get_all_linked_version[0]['linked_version'] ) && !empty( $get_all_linked_version[0]['linked_version'] ) ) {
        $t_all_linked_versions = explode(',',$get_all_linked_version[0]['linked_version']);
        $i_total_linked_versions = count($t_all_linked_versions);
        $user_data = '';
        $combined_user_data = array();
        $i_linked_version_counter = 1;
        $t_ignorable_keys = array('investment_products','investment_stocks_shares','update','inv_management_type','planning_invest','planning_stocks_shares','gender','investments_today','investments_over','investment_frequency','point_in_time','order_by','order');
        foreach ($t_all_linked_versions as $t_all_linked_version) {
            $combined_user_data[$t_all_linked_version] = isset($user_financial_data[$t_all_linked_version]) ? $user_financial_data[$t_all_linked_version] : '';
            if ( $i_linked_version_counter == 1 ) {
                $user_data = $combined_user_data[$t_all_linked_version];
            } else {
                foreach ($combined_user_data[$t_all_linked_version] as $s_key=>$s_val) {
                    if ( !in_array($s_key, $t_ignorable_keys) ) {
                        $user_data[$s_key] = $user_data[$s_key] + $combined_user_data[$t_all_linked_version][$s_key];
                    } else {
                        if ( isset($combined_user_data[$t_all_linked_version][$s_key]) && !empty($combined_user_data[$t_all_linked_version][$s_key]) ) {
                            $user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                        }
                    }
                }
            }
            $i_linked_version_counter++;
        }
        // $user_data['age'] = (int)($user_data['age'] / 4);
        $user_data['age'] = 55;
        // $user_data['inv_management_type'] = 'myself';
        $user_data['inv_management_type'] = 'adviser';
        $user_data['investment_products'] = 'yes';

        $user_data['roles_commas'] = $_POST['roles_commas'];
        $user_data['user_type'] = $_POST['roles_commas'];
        
    } else {
        $user_data = isset($user_financial_data[$version]) ? $user_financial_data[$version] : false;
        if ( in_array( 'adviser', (array) $user->roles ) ) {
            $user_data['age'] = 55;
            // $user_data['inv_management_type'] = 'myself';
            $user_data['inv_management_type'] = 'adviser';
            $user_data['investment_products'] = 'yes';
        }
    }
    if( $_POST['roles_commas'] == 'subscriber' ){
        $user_data['inv_management_type'] = 'myself';
    }
    if(sizeof($user_data) == "1") {

      $htb_data = $_POST['htb_data'];
      
      $slashesStrippedInputArray = stripslashes($htb_data);
      $user_data  = unserialize(stripslashes($slashesStrippedInputArray));
      $user_data['embedded_calculator'] = true;

    }

    $user->data = (object) $user_data;
    if ($user_data) {
        $platforms_data = cplat_get_platform_list($user_data, $user->ID);
        $platforms = $platforms_data['list'];
        //die(var_dump($platforms));
        $results_count = $platforms_data['count'];
        
        // manually sort
        $platforms_queue  = $platforms_data['list'];
        $cost_order = array();
        foreach ( $platforms_queue as $key => $row ) {
            $cost_order[ $key ] = $row['cost'];
        }
        array_multisort($cost_order, SORT_ASC, $platforms );
        //array_multisort($cost_order, SORT_ASC, $platforms_queue );
        $platforms_data['list'] = $platforms_queue;


    } else {
        $platforms = array();
        $results_count = 0;
    }
    $user->data = (object)$user_data;
    $calc_user = $user;
    //RSPL Task#83
    //ob_start();
    //include 'templates/calculator-results-listing.php';
    //echo ob_get_clean();

    // RSPL Task#164
    $over_years = $user_data['investments_today'];
    $year = $user_data['investments_in_x_years'];
    $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS = $user_data['total_savings_and_investments_total'];
    if ($over_years === 'over_years') {
        $year = $user_data['investments_over'];
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    else if ($over_years === 'in_x_years') {
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    if (isset($_REQUEST['dual_response']) && $_REQUEST['dual_response'] == 1) {
        ob_start();
        include 'templates/calculator-results-listing.php';
        $listing_content = ob_get_contents();
        ob_get_clean();
        // ob_start();
        // include 'templates/print-result-popup.php';
        // $popup_content = ob_get_contents();
        // ob_get_clean();
        // RSPL Task#159
        // echo json_encode(array('listing_content' => $listing_content, 'popup_content' => $popup_content));
        //echo json_encode( array( 'listing_content' => $listing_content ) ); // Ticket#83
        //echo $listing_content; exit; // Ticket#237
    } else {
        ob_start();
        $listing_content = ob_get_contents();
        ob_get_clean();
        // include 'templates/calculator-results-listing.php';
        // echo ob_get_clean();
    }
    if( is_user_logged_in() ){
        echo json_encode( array( 'is_user_logged_in'=>'true','listing_content' => $listing_content ) );
    }else{
        echo json_encode( array( 'is_user_logged_in'=>'false','listing_content' => $listing_content ) );
    }
	die();
}

// RSPL Task#162 start
add_action( 'wp_ajax_cplat_get_results_for_print', 'cplat_get_results_for_print_callback' );
add_action( 'wp_ajax_nopriv_cplat_get_results_for_print', 'cplat_get_results_for_print_callback' );
function cplat_get_results_for_print_callback() {
    @session_start();
	$url = $_POST['current_URL'];
	$url = add_query_arg('save_print_result', 'true', $url);
	$user_type = $_POST['user_type'];
    $currency            = '£';
    $user                = wp_get_current_user();
    $version             = isset( $_POST['version'] ) ? (int) $_POST['version'] : 0;
    $user_financial_data = $_SESSION['user_financial_data'];
    //$user_financial_data = get_user_meta($user->ID, 'user_financial_data', true);

    // RSPL Task#25
    global $wpdb;
    $linked_version = $version;
    $get_all_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);
    if ( isset( $get_all_linked_version[0]['linked_version'] ) && !empty( $get_all_linked_version[0]['linked_version'] ) ) {
        $t_all_linked_versions = explode(',',$get_all_linked_version[0]['linked_version']);
        $i_total_linked_versions = count($t_all_linked_versions);
        $user_data = '';
        $combined_user_data = array();
        $i_linked_version_counter = 1;
        $t_ignorable_keys = array('investment_products','investment_stocks_shares','update','inv_management_type','planning_invest','planning_stocks_shares','gender','investments_today','investments_over','investment_frequency','point_in_time','order_by','order');
        foreach ($t_all_linked_versions as $t_all_linked_version) {
            $combined_user_data[$t_all_linked_version] = isset($user_financial_data[$t_all_linked_version]) ? $user_financial_data[$t_all_linked_version] : '';
            if ( $i_linked_version_counter == 1 ) {
                $user_data = $combined_user_data[$t_all_linked_version];
            } else {
                foreach ($combined_user_data[$t_all_linked_version] as $s_key=>$s_val) {
                    if ( !in_array($s_key, $t_ignorable_keys) ) {
                        $user_data[$s_key] = $user_data[$s_key] + $combined_user_data[$t_all_linked_version][$s_key];
                    } else {
                        if ( isset($combined_user_data[$t_all_linked_version][$s_key]) && !empty($combined_user_data[$t_all_linked_version][$s_key]) ) {
                            $user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                        }
                    }
                }
            }
            $i_linked_version_counter++;
        }
        // $user_data['age'] = (int)($user_data['age'] / 4);
        $user_data['age'] = 55;
        // $user_data['inv_management_type'] = 'myself';
        $user_data['inv_management_type'] = 'adviser';
        $user_data['investment_products'] = 'yes';
    } else {
        $user_data = isset($user_financial_data[$version]) ? $user_financial_data[$version] : false;
        if ( in_array( 'adviser', (array) $user->roles ) || $user_type == 'advisor' ) {
            $user_data['age'] = 55;
            // $user_data['inv_management_type'] = 'myself';
            $user_data['inv_management_type'] = 'adviser';
            $user_data['investment_products'] = 'yes';
        }
    }
    if( $_POST['roles_commas'] == 'subscriber' ){
        $user_data['inv_management_type'] = 'myself';
    }
    $user->data = (object) $user_data;
    if ($user_data) {
        $platforms_data = cplat_get_platform_list($user_data, $user->ID);
        $platforms = $platforms_data['list'];
        $results_count = $platforms_data['count'];
    } else {
        $platforms = array();
        $results_count = 0;
    }
    $user->data = (object)$user_data;
    $calc_user = $user;

    // RSPL Task#164
    $over_years = $user_data['investments_today'];
    $year = $user_data['investments_in_x_years'];
    $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS = $user_data['total_savings_and_investments_total'];
    if ($over_years === 'over_years') {
        $year = $user_data['investments_over'];
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    else if ($over_years === 'in_x_years') {
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    ob_start();
    include 'templates/print-result-popup.php';
    $popup_content = ob_get_contents();
    ob_get_clean();
    $ret['is_print'] = false;
    if (is_user_logged_in()) {
        echo json_encode( array( 'popup_content' => $popup_content ) );
    }else{
        //echo json_encode( array( 'popup_content' => $popup_content ) );
        $ret['is_login'] = false;
        $ret['is_print'] = true;
		$ret['login_url'] = site_url('/register');
        $ret['is_user_type'] = $user_type;
        $ret['popup_content'] = $popup_content;
		$ret['http_redirect_URL'] = $url;
		$_SESSION['is_user_type'] = $user_type;
		$_SESSION['http_redirect_URL'] = $url;
		echo json_encode($ret);
    }
    die();
}
// RSPL Task#162 end

add_action( 'wp_ajax_cplat_store_linked_portfolios', 'cplat_store_linked_portfolios_callback' );
add_action( 'wp_ajax_nopriv_cplat_store_linked_portfolios', 'cplat_store_linked_portfolios_callback' );
function cplat_store_linked_portfolios_callback() {
    $user_type = $_POST['user_type']; //advisor
    $ret                 = array('status'=>2,'msg'=>'Invalid Request');
    $user                = wp_get_current_user();
    $version             = isset( $_POST['version'] ) ? (int) $_POST['version'] : 0;
    $main_version        = isset( $_POST['main_version'] ) ? (int) $_POST['main_version'] : 0;

    if ( ( (isset($user->ID) && !empty($user->ID) ) || $user_type == 'advisor' ) && isset($main_version) && !empty($main_version) 
    && ( in_array( 'adviser', (array) $user->roles ) || $user_type == 'advisor' ) ) {
        $ret = array('status'=>1,'msg'=>'Success');
        $table_name = 'linked_portfolio_tbl';
        global $wpdb;
        $is_exist = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE main_version LIKE '" . $main_version . "' LIMIT 1", ARRAY_A);
        if ( isset( $is_exist[0]['linked_version'] ) && !empty( $is_exist[0]['linked_version'] ) ) {
            $t_all_linked_versions = explode(',',$is_exist[0]['linked_version']);
            if (strpos((string)$is_exist[0]['linked_version'], (string)$version) !== false) {
                if ( count($t_all_linked_versions) < 10 ) {
                    echo json_encode( $ret );
                    die();
                } else {
                    $ret = array('status'=>3,'msg'=>'Whoops, you cannot link anymore portfolio. You have already linked 9 portfolios!');
                    echo json_encode( $ret );
                    die();
                }
            } else {
                if ( count($t_all_linked_versions) < 10 ) {
                    $all_linked_versions = $is_exist[0]['linked_version'] . ',' . $version;
                    $t_where = array('main_version' => $main_version);
                    $t_data = array('linked_version' => $all_linked_versions,
                        'linked_version_data' => '-',
                        'updated_at' => current_time('Y-m-d H:i:s'));
                    $wpdb->update($table_name, $t_data, $t_where);
                } else {
                    $ret = array('status'=>3,'msg'=>'Whoops, you cannot link anymore portfolio. You have already linked 9 portfolios!');
                }
            }
        } else {
            $t_data = array('main_version' => $main_version,
                'linked_version' => $main_version,
                'linked_version_data' => '-',
                'created_at' => current_time('Y-m-d H:i:s'),
                'updated_at' => current_time('Y-m-d H:i:s'));
            $wpdb->insert($table_name, $t_data);
        }
    }
    echo json_encode( $ret );
    die();
}
// RSPL#289
add_action('wp_ajax_get_linked_version_charges_action','get_linked_version_charges_action_cb');
add_action('wp_ajax_nopriv_get_linked_version_charges_action','get_linked_version_charges_action_cb');
function get_linked_version_charges_action_cb(){
    @session_start();
    $currency            = '£';
	$user                = wp_get_current_user();
    $version             = isset( $_POST['version'] ) ? (int) $_POST['version'] : 0;
    $selected_platform_id = $_POST['platform_id'];
    $user_financial_data = get_user_meta($user->ID, 'user_financial_data', true);
    $user_financial_data = $_SESSION['user_financial_data'];
    $user_type = ( $_POST['user_type'] ? $_POST['user_type'] : 'subscriber' );

	// RSPL Task#25
    global $wpdb;
    $user_data = isset($user_financial_data[$version]) ? $user_financial_data[$version] : false;
    if ( in_array( 'adviser', (array) $user->roles ) || $user_type == 'advisor' ) {
        $user_data['age'] = 55;
        $user_data['inv_management_type'] = 'adviser';
        $user_data['investment_products'] = 'yes';
        $user_data['user_type'] = $user_type;
    }
    $user_data['age'] = 55;
    if ($user_data) {
        $platforms_data = cplat_get_platform_list($user_data, $user->ID);
        $platforms = $platforms_data['list'];
        $results_count = $platforms_data['count'];
        $search_datas = array_column($platforms, 'data');
        $search_ids = array_column($search_datas, 'id');
        $platform_index = array_search($selected_platform_id, $search_ids );
        $platforms = $platforms[$platform_index];
    } else {
        $platforms = array();
        $results_count = 0;
    }
    $user->data = (object)$user_data;
    $calc_user = $user;

    // RSPL Task#164
    $over_years = $user_data['investments_today'];
    $year = $user_data['investments_in_x_years'];
    $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS = $user_data['total_savings_and_investments_total'];
    if ($over_years === 'over_years') {
        $year = $user_data['investments_over'];
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    else if ($over_years === 'in_x_years') {
        $planning_gia = $user_data['planning_gia'] * $year;
        $planning_isa = $user_data['planning_isa'] * $year;
        $planning_jisa = $user_data['planning_jisa'] * $year;
        $planning_sipp = $user_data['planning_sipp'] * $year;
        $planning_jsipp = $user_data['planning_jsipp'] * $year;
        $planning_onshore_bond = $user_data['planning_onshore_bond'] * $year;
        $planning_offshore_bond = $user_data['planning_offshore_bond'] * $year;
        $planning_gia_cash = $user_data['planning_gia_cash'] * $year;
        $planning_isa_cash = $user_data['planning_isa_cash'] * $year;
        $planning_jisa_cash = $user_data['planning_jisa_cash'] * $year;
        $planning_sipp_cash = $user_data['planning_sipp_cash'] * $year;
        $planning_jsipp_cash = $user_data['planning_jsipp_cash'] * $year;
        $planning_onshore_bond_cash = $user_data['planning_onshore_bond_cash'] * $year;
        $planning_offshore_bond_cash = $user_data['planning_offshore_bond_cash'] * $year;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_gia_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_isa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jisa_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_sipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_jsipp_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_onshore_bond_cash;
        $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS += $planning_offshore_bond_cash;
    }
    ob_start();
    include 'templates/calculator-version-results.php';
    $result_data = ob_get_contents();
    ob_get_clean();
    echo json_encode( array( 'result_data' => $result_data ) );
	die();
}
