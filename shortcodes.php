<?php

/**
 * Process calculator form submission & redirect to next step
 *
 * @return string
 */
function cplat_submit_form_shortcode() {
  session_start();
  $step = isset( $_GET['step'] ) ? intval( $_GET['step'] ) : 1;

  // if ( ! is_user_logged_in() ) {
  //  $step = 2;
  // }

  // if ( is_user_logged_in() && ( ! isset( $_GET['step'] ) || intval( $_GET['step'] ) === 1 ) ) {
  //  $step = 2;
  // }
  if ( ( ! isset( $_GET['step'] ) || intval( $_GET['step'] ) === 1 ) ) {
    $step = 2;
  }
  $version  = isset( $_GET['version'] ) ? intval( $_GET['version'] ) : 'new';
  $htb_data = isset( $_GET['htb_serialized_data']) ? $_GET['htb_serialized_data'] : 'not_set';
  $currency = "£";

  if ( is_user_logged_in() ) {
    $calc_user = wp_get_current_user();
  } else {
    $calc_user = new stdClass();
  }
  if ( isset( $_GET['ecc_version'] ) && $version === 'new' ) {
    $ecc_version = trim( $_GET['ecc_version'] );
    $ecc_data    = get_user_meta( $calc_user->ID, Ctp_Ecc::USER_META_KEY, true );
    if ( isset( $ecc_data[ $ecc_version ] ) ) {
      $ecc_data        = $ecc_data[ $ecc_version ];
      $calc_user->data = get_ecc_data_for_platform( $ecc_data );
    }

  } else {
    if ( isset( $calc_user->ID ) ) {
      //$all_versions = get_user_meta( $calc_user->ID, 'user_financial_data', true );
      $all_versions = $_SESSION['user_financial_data'];
    }
    $all_versions = $_SESSION['user_financial_data'];
    if ( isset( $all_versions[ $version ] ) ) {
      $calc_user->data = (object) $all_versions[ $version ];
    } else {
      $calc_user->data = new stdClass();
    }
    if ( $step === 4 ) {
        // RSPL Task#25
            $i_remove_slider_section = 0;

        // RSPL TASK#22
            // RSPL Task#37
      if ( isset($_POST['updated']) && !empty($_POST['updated']) && $_POST['updated'] == 1 ) {
        $total_funds            = $_POST['total_funds'] - $_POST['total_shares'];
        $total_ex_traded        = $_POST['total_shares'];
        $total_cash             = $_POST['total_savings_and_investments_cash'];
        $total_investments      = $_POST['total_savings_and_investments_total'];
        $no_of_trades           = $_POST['nooftrades'];
        $results                = $_POST['results'];
        $trading_freq_funds     = $_POST['investment_frequency_funds'];
        $trading_freq_ex_traded = $_POST['investment_frequency_ex_traded'];
        $avg_trading_funds      = $_POST['average_investment_funds'];
        $avg_trading_ex_traded  = $_POST['average_investment_ex_traded'];
        /*RSPL Ticket#192 Start */
        $yearly_investment_funds = $_POST['yearly_investment_funds'];
        $yearly_investment_ex = $_POST['yearly_investment_ex'];
        $yearly_investment_cash = $_POST['yearly_investment_cash'];
        $total_yearly_investment_total = $yearly_investment_funds + $yearly_investment_ex + $yearly_investment_cash;
        $total_yearly_investment_value = $yearly_investment_funds + $yearly_investment_cash;
        /*RSPL Ticket#192 End */

        /*RSPL Ticket#192 checklist-2 Start */
        $is_growth      = $_POST['is_growth'];
        $growth_rate      = ( $is_growth  == 'yes' ?  4 : $_POST['growth_rate']  );
        $roles_commas      = $_POST['roles_commas'];
        /*RSPL Ticket#192 checklist-2 End */

        /*RSPL Ticket#192 checklist-3 Start */
        $initial_advice_type      = $_POST['initial_advice_type'];
        $initial_adviser_charges      = $_POST['initial_adviser_charges'];
        $annual_advice_type      = $_POST['annual_advice_type'];
        $annual_adviser_charges      = $_POST['annual_adviser_charges'];
        /*RSPL Ticket#192 checklist-3 End */

        // RSPL Ticket#222 checklist-3 Start
        $link_portfolio      = $_POST['link_portfolio'];
        // RSPL Ticket#222 checklist-3 End
        $is_adviser_charges      = $_POST['is_adviser_charges'];

        if ( $results == 'today' ) {
          $checked_today      = ' checked="checked"';
          $checked_over_years = '';
          $slider_over_years  = ' style="display: none;"';
          $checked_in_x_years = '';
          $slider_in_x_years  = ' style="display: none;"';
        }
        if ( $results == 'over_years' ) {
          $checked_today      = '';
          $checked_over_years = ' checked="checked"';
          $slider_over_years  = ' style="display: block;"';
          $checked_in_x_years = '';
          $slider_in_x_years  = ' style="display: none;"';
        }
        if ( $results == 'in_x_years' ) {
          $checked_today      = '';
          $checked_over_years = '';
          $slider_over_years  = ' style=" display: none;"';
          $checked_in_x_years = ' checked="checked"';
          $slider_in_x_years  = ' style=" display: block;"';
        }
        $over_years   = $_POST['investments_over'];
        $point_future = $_POST['investments_in_x_years'];
        $order_by     = $_POST['order_by'];
      } else { 
        if($calc_user->data->total_savings_and_investments_total == NULL) {   
          $calc_user->data = (object)(unserialize(stripslashes($_GET['htb_serialized_data'])));
          $calc_user->data->user_type = 'subscriber';
          $calc_user->data->roles_commas = 'subscriber';
          foreach($calc_user->data as $key => &$row) {
            if($row == "") {
              $row = (int)0;
            }
            if(
              $key !== 'investment_products' &&
              $key !== 'roles_commas' &&
              $key !== 'user_type' &&
              $key !== 'investment_stocks_shares' &&
              $key !== 'calculator_action' &&
              $key !== 'investment_products_in_3' &&
              $key !== 'planning_invest' &&
              $key !== 'planning_stocks_shares' &&
              $key !== 'gender' &&
              $key !== 'inv_management_type' &&
              $key !== 'is_adviser_charges' &&
              $key !== 'initial_advice_type' &&
              $key !== 'annual_advice_type' &&
              $key !== 'investments_today' &&
              $key !== 'is_growth'
            ) {
                $row = (int)$row;
              }
          };

          $htb_data = serialize(json_decode(json_encode($calc_user->data), true));
        }
        $results                = 'today';
        $order_by               = 'cost_low_high';
        $total_funds            = $currency . " " . number_format( $calc_user->data->total_savings_and_investments - $calc_user->data->total_shares );
        $total_ex_traded        = $currency . " " . number_format( $calc_user->data->total_shares );
                $total_cash             = $currency . " " . number_format( $calc_user->data->total_savings_and_investments_cash);
        $total_investments      = $currency . " " . number_format( $calc_user->data->total_savings_and_investments_total );
        $trading_freq_funds     = intval( esc_attr( $calc_user->data->investment_frequency_funds ) );
        $trading_freq_ex_traded = intval( esc_attr( $calc_user->data->investment_frequency_ex_traded ) );
        $avg_trading_funds      = $currency . " " . number_format( esc_attr( $calc_user->data->average_investment_funds ) );
        $avg_trading_ex_traded  = $currency . " " . number_format( esc_attr( $calc_user->data->average_investment_ex_traded ) );
        /*RSPL Ticket#192 Start */
        $total_yearly_investment_total      = $currency . " " . number_format( $calc_user->data->total_yearly_investment_total );
        $total_yearly_investment_value      = $currency . " " . number_format( $calc_user->data->total_yearly_investment_value );
        $yearly_investment_funds      = $currency . " " . number_format( $calc_user->data->yearly_investment_funds );
        $yearly_investment_ex      = $currency . " " . number_format( $calc_user->data->yearly_investment_ex );
        $yearly_investment_cash      = $currency . " " . number_format( $calc_user->data->yearly_investment_cash );
        /*RSPL Ticket#192 End */

        /*RSPL Ticket#192 checklist-2 Start */
        $is_growth      = $calc_user->data->is_growth;
        $growth_rate      = ( $is_growth  == 'yes' ? 4 : $calc_user->data->growth_rate  );
        $roles_commas      = $calc_user->data->roles_commas;
        /*RSPL Ticket#192 checklist-2 End */

        /*RSPL Ticket#192 checklist-3 Start */
        $initial_advice_type     = $calc_user->data->initial_advice_type;   
        $initial_adviser_charges = $calc_user->data->initial_adviser_charges;
        $annual_advice_type      = $calc_user->data->annual_advice_type;
        $annual_adviser_charges  = $calc_user->data->annual_adviser_charges;
        /*RSPL Ticket#192 checklist-3 End */

        // RSPL Ticket#222 checklist-3 Start
        $link_portfolio      = $_POST['link_portfolio'];
        $is_adviser_charges      = $_POST['is_adviser_charges'];
        
        // RSPL Ticket#222 checklist-3 End

        if ( $calc_user->data->investments_today == 'today' ) {
          $checked_today      = ' checked="checked"';
          $checked_over_years = '';
          $slider_over_years  = ' "style: display: none;"';
          $checked_in_x_years = '';
          $slider_over_years  = ' "style: display: none;"';
        }
        if ( $calc_user->data->investments_today == 'over_years' ) {
          $checked_today      = '';
          $checked_over_years = ' checked="checked"';
          $checked_in_x_years = '';
        }
        if ( $calc_user->data->investments_today == 'in_x_years' ) {
          $checked_today      = '';
          $checked_over_years = '';
          $checked_in_x_years = ' checked="checked"';
        }
        $slider_over_years = '';
        $slider_in_x_years = '';
        $over_years        = intval( $calc_user->data->investments_over );
        $point_future      = intval( $calc_user->data->investments_in_x_years );
      }
        //var_dump($calc_user->data);
      // RSPL Task#25
            $linked_version = $version;
      global $wpdb;
            $get_all_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);
            if ( isset( $get_all_linked_version[0]['linked_version'] ) && !empty( $get_all_linked_version[0]['linked_version'] ) ) {
                $i_remove_slider_section = 1;
                $t_all_linked_versions = explode(',',$get_all_linked_version[0]['linked_version']);
                $all_combined_user_data = '';
                $combined_user_data = array();
                $i_linked_version_counter = 1;
                $t_ignorable_keys = array('investment_products','investment_stocks_shares','update','inv_management_type','planning_invest','planning_stocks_shares','gender','investments_today','investments_over','investment_frequency','point_in_time','order_by','order');
                foreach ($t_all_linked_versions as $t_all_linked_version) {
                    $combined_user_data[$t_all_linked_version] = isset($all_versions[$t_all_linked_version]) ? $all_versions[$t_all_linked_version] : '';
                    if ( $i_linked_version_counter == 1 ) {
                        $all_combined_user_data = $combined_user_data[$t_all_linked_version];
                    } else {
                        foreach ($combined_user_data[$t_all_linked_version] as $s_key=>$s_val) {
                            if ( !in_array($s_key, $t_ignorable_keys) ) {
                                $all_combined_user_data[$s_key] = $all_combined_user_data[$s_key] + $combined_user_data[$t_all_linked_version][$s_key];
                            } else {
                                if ( isset($combined_user_data[$t_all_linked_version][$s_key]) && !empty($combined_user_data[$t_all_linked_version][$s_key]) ) {
                                    $all_combined_user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                                }
                            }
                        }
                    }
                    $i_linked_version_counter++;
                }
                if ( isset($all_combined_user_data) && !empty($all_combined_user_data) ) {
                    if( isset($all_combined_user_data['total_savings_and_investments']) && !empty($all_combined_user_data['total_savings_and_investments']) ) {
                        if ( isset($all_combined_user_data['total_shares']) && !empty($all_combined_user_data['total_shares']) ) {
                            $total_funds = (float)$all_combined_user_data['total_savings_and_investments'] - (float)$all_combined_user_data['total_shares'];
                        } else {
                            $total_funds = (float)$all_combined_user_data['total_savings_and_investments'];
                        }
                        $total_funds = $currency . " " . number_format( $total_funds );
                    }

                    if( isset($all_combined_user_data['total_shares']) && !empty($all_combined_user_data['total_shares']) ) {
                        $total_ex_traded = (float)$all_combined_user_data['total_shares'];
                        $total_ex_traded = $currency . " " . number_format( $total_ex_traded );
                    }

                    if( isset($all_combined_user_data['total_savings_and_investments_cash']) && !empty($all_combined_user_data['total_savings_and_investments_cash']) ) {
                        $total_cash = (float)$all_combined_user_data['total_savings_and_investments_cash'];
                        $total_cash = $currency . " " . number_format( $total_cash );
                    }

                    if( isset($all_combined_user_data['total_savings_and_investments_total']) && !empty($all_combined_user_data['total_savings_and_investments_total']) ) {
                        $total_investments = (float)$all_combined_user_data['total_savings_and_investments_total'];
                        $total_investments = $currency . " " . number_format( $total_investments );
                    }

                    if( isset($all_combined_user_data['investment_frequency_funds']) && !empty($all_combined_user_data['investment_frequency_funds']) ) {
                        $trading_freq_funds = (float)$all_combined_user_data['investment_frequency_funds'];
                        $trading_freq_funds = intval( esc_attr( $trading_freq_funds ) );
                    }

                    if( isset($all_combined_user_data['investment_frequency_ex_traded']) && !empty($all_combined_user_data['investment_frequency_ex_traded']) ) {
                        $trading_freq_ex_traded = (float)$all_combined_user_data['investment_frequency_ex_traded'];
                        $trading_freq_funds = intval( esc_attr( $trading_freq_ex_traded ) );
                    }

                    if( isset($all_combined_user_data['average_investment_funds']) && !empty($all_combined_user_data['average_investment_funds']) ) {
                        $avg_trading_funds = (float)$all_combined_user_data['average_investment_funds'];
                        $total_investments = $currency . " " . number_format( $avg_trading_funds );
                    }

                    if( isset($all_combined_user_data['average_investment_ex_traded']) && !empty($all_combined_user_data['average_investment_ex_traded']) ) {
                        $avg_trading_ex_traded = (float)$all_combined_user_data['average_investment_ex_traded'];
                        $total_investments = $currency . " " . number_format( $avg_trading_ex_traded );
                    }
                }
            }
        }
  }
  // echo '<pre>';
  // var_dump($calc_user->data);
  // echo '</pre>';
  // echo '$total_investments: '.$total_investments; exit;
  $calculator_url = esc_url( get_permalink( get_page_by_title( 'Platform Calculator' ) ) );
  $step_2_url     = add_query_arg( 'step', 2, $calculator_url );

  $step_2_url = add_query_arg( 'version', $version, $step_2_url );
  $step_3_url = add_query_arg( 'step', 3, $calculator_url );
  $step_3_url = add_query_arg( 'version', $version, $step_3_url );
  if ( isset( $ecc_version ) ) {
    $step_3_url = add_query_arg( 'ecc_version', $ecc_version, $step_3_url );
  }
  ob_start();
  include 'templates/calculator-steps-form.php';

  return ob_get_clean();
}

add_shortcode( 'cplat_submit_form', 'cplat_submit_form_shortcode' );

/**
 * @author : RSPL  | 17 February 2021
 * @description : Simplified calculator
 * @return : Return form for simplified calculator
**/
add_shortcode( 'simplified-calculator', 'simplified_calculator_form_shortcode_cb' );
function simplified_calculator_form_shortcode_cb(){
  //session_set_cookie_params(['SameSite' => 'None', 'Secure' => true]);
  session_start();
  

  $step = isset( $_GET['step'] ) ? intval( $_GET['step'] ) : 1;
  $version  = isset( $_GET['version'] ) ? intval( $_GET['version'] ) : 'new';
  $htb_data = isset( $_GET['htb_serialized_data']) ? $_GET['htb_serialized_data'] : 'not_set';

  $currency = "£";

  if ( is_user_logged_in() ) {
    $calc_user = wp_get_current_user();
  } else {
    $calc_user = new stdClass();
  }
  if ( isset( $_GET['ecc_version'] ) && $version === 'new' ) {
    $ecc_version = trim( $_GET['ecc_version'] );
    $ecc_data    = get_user_meta( $calc_user->ID, Ctp_Ecc::USER_META_KEY, true );
    if ( isset( $ecc_data[ $ecc_version ] ) ) {
      $ecc_data        = $ecc_data[ $ecc_version ];
      $calc_user->data = get_ecc_data_for_platform( $ecc_data );
    }

  } else {
    if ( isset( $calc_user->ID ) ) {
      //$all_versions = get_user_meta( $calc_user->ID, 'user_financial_data', true );
      $all_versions = $_SESSION['user_financial_data'];
    }
    $all_versions = $_SESSION['user_financial_data'];
    if ( isset( $all_versions[ $version ] ) ) {
      $calc_user->data = (object) $all_versions[ $version ];
    } else {
      $calc_user->data = new stdClass();
    }
    if ( $step === 2 ) {
        // RSPL Task#25
            $i_remove_slider_section = 0;

        // RSPL TASK#22
            // RSPL Task#37
      if ( isset($_POST['updated']) && !empty($_POST['updated']) && $_POST['updated'] == 1 ) {
        $total_funds            = $_POST['total_funds'] - $_POST['total_shares'];
        $total_ex_traded        = $_POST['total_shares'];
        $total_cash             = $_POST['total_savings_and_investments_cash'];
        $total_investments      = $_POST['total_savings_and_investments_total'];
        $no_of_trades           = $_POST['nooftrades'];
        $results                = $_POST['results'];
        $trading_freq_funds     = $_POST['investment_frequency_funds'];
        $trading_freq_ex_traded = $_POST['investment_frequency_ex_traded'];
        $avg_trading_funds      = $_POST['average_investment_funds'];
        $avg_trading_ex_traded  = $_POST['average_investment_ex_traded'];
        /*RSPL Ticket#192 Start */
        $yearly_investment_funds = $_POST['yearly_investment_funds'];
        $yearly_investment_ex = $_POST['yearly_investment_ex'];
        $yearly_investment_cash = $_POST['yearly_investment_cash'];
        $total_yearly_investment_total = $yearly_investment_funds + $yearly_investment_ex + $yearly_investment_cash;
        $total_yearly_investment_value = $yearly_investment_funds + $yearly_investment_cash;
        /*RSPL Ticket#192 End */

        /*RSPL Ticket#192 checklist-2 Start */
        $is_growth      = $_POST['is_growth'];
        $growth_rate      = ( $is_growth  == 'yes' ?  4 : $_POST['growth_rate']  );
        $roles_commas      = $_POST['roles_commas'];
        /*RSPL Ticket#192 checklist-2 End */

        /*RSPL Ticket#192 checklist-3 Start */
        $initial_advice_type      = $_POST['initial_advice_type'];
        $initial_adviser_charges      = $_POST['initial_adviser_charges'];
        $annual_advice_type      = $_POST['annual_advice_type'];
        $annual_adviser_charges      = $_POST['annual_adviser_charges'];
        /*RSPL Ticket#192 checklist-3 End */

        // RSPL Ticket#222 checklist-3 Start
        $link_portfolio      = $_POST['link_portfolio'];
        // RSPL Ticket#222 checklist-3 End
        $is_adviser_charges      = $_POST['is_adviser_charges'];

        if ( $results == 'today' ) {
          $checked_today      = ' checked="checked"';
          $checked_over_years = '';
          $slider_over_years  = ' style="display: none;"';
          $checked_in_x_years = '';
          $slider_in_x_years  = ' style="display: none;"';
        }
        if ( $results == 'over_years' ) {
          $checked_today      = '';
          $checked_over_years = ' checked="checked"';
          $slider_over_years  = ' style="display: block;"';
          $checked_in_x_years = '';
          $slider_in_x_years  = ' style="display: none;"';
        }
        if ( $results == 'in_x_years' ) {
          $checked_today      = '';
          $checked_over_years = '';
          $slider_over_years  = ' style=" display: none;"';
          $checked_in_x_years = ' checked="checked"';
          $slider_in_x_years  = ' style=" display: block;"';
        }
        $over_years   = $_POST['investments_over'];
        $point_future = $_POST['investments_in_x_years'];
        $order_by     = $_POST['order_by'];

      } else {     
        if($calc_user->data->total_savings_and_investments_total == NULL) {   
          $calc_user->data = (object)(unserialize(stripslashes($_GET['htb_serialized_data'])));
        }
        
        $results                = 'today';
        $order_by               = 'cost_low_high';
        $total_funds            = $currency . " " . number_format( $calc_user->data->total_savings_and_investments - $calc_user->data->total_shares );
        $total_ex_traded        = $currency . " " . number_format( $calc_user->data->total_shares );
                $total_cash             = $currency . " " . number_format( $calc_user->data->total_savings_and_investments_cash);
        $total_investments      = $currency . " " . number_format( $calc_user->data->total_savings_and_investments_total );
        $trading_freq_funds     = intval( esc_attr( $calc_user->data->investment_frequency_funds ) );
        $trading_freq_ex_traded = intval( esc_attr( $calc_user->data->investment_frequency_ex_traded ) );
        $avg_trading_funds      = $currency . " " . number_format( esc_attr( $calc_user->data->average_investment_funds ) );
        $avg_trading_ex_traded  = $currency . " " . number_format( esc_attr( $calc_user->data->average_investment_ex_traded ) );
        /*RSPL Ticket#192 Start */
        $total_yearly_investment_total      = $currency . " " . number_format( $calc_user->data->total_yearly_investment_total );
        $total_yearly_investment_value      = $currency . " " . number_format( $calc_user->data->total_yearly_investment_value );
        $yearly_investment_funds      = $currency . " " . number_format( $calc_user->data->yearly_investment_funds );
        $yearly_investment_ex      = $currency . " " . number_format( $calc_user->data->yearly_investment_ex );
        $yearly_investment_cash      = $currency . " " . number_format( $calc_user->data->yearly_investment_cash );
        /*RSPL Ticket#192 End */

        /*RSPL Ticket#192 checklist-2 Start */
        $is_growth      = $calc_user->data->is_growth;
        $growth_rate      = ( $is_growth  == 'yes' ? 4 : $calc_user->data->growth_rate  );
        $roles_commas      = $calc_user->data->roles_commas;
        /*RSPL Ticket#192 checklist-2 End */

        /*RSPL Ticket#192 checklist-3 Start */
        $initial_advice_type     = $calc_user->data->initial_advice_type;   
        $initial_adviser_charges = $calc_user->data->initial_adviser_charges;
        $annual_advice_type      = $calc_user->data->annual_advice_type;
        $annual_adviser_charges  = $calc_user->data->annual_adviser_charges;
        /*RSPL Ticket#192 checklist-3 End */

        // RSPL Ticket#222 checklist-3 Start
        $link_portfolio      = $_POST['link_portfolio'];
        $is_adviser_charges      = $_POST['is_adviser_charges'];
        
        // RSPL Ticket#222 checklist-3 End

        if ( $calc_user->data->investments_today == 'today' ) {
          $checked_today      = ' checked="checked"';
          $checked_over_years = '';
          $slider_over_years  = ' "style: display: none;"';
          $checked_in_x_years = '';
          $slider_over_years  = ' "style: display: none;"';
        }
        if ( $calc_user->data->investments_today == 'over_years' ) {
          $checked_today      = '';
          $checked_over_years = ' checked="checked"';
          $checked_in_x_years = '';
        }
        if ( $calc_user->data->investments_today == 'in_x_years' ) {
          $checked_today      = '';
          $checked_over_years = '';
          $checked_in_x_years = ' checked="checked"';
        }
        $slider_over_years = '';
        $slider_in_x_years = '';
        $over_years        = intval( $calc_user->data->investments_over );
        $point_future      = intval( $calc_user->data->investments_in_x_years );
      }
      // RSPL Task#25
            $linked_version = $version;
      global $wpdb;
            $get_all_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);
            if ( isset( $get_all_linked_version[0]['linked_version'] ) && !empty( $get_all_linked_version[0]['linked_version'] ) ) {
                $i_remove_slider_section = 1;
                $t_all_linked_versions = explode(',',$get_all_linked_version[0]['linked_version']);
                $all_combined_user_data = '';
                $combined_user_data = array();
                $i_linked_version_counter = 1;
                $t_ignorable_keys = array('investment_products','investment_stocks_shares','update','inv_management_type','planning_invest','planning_stocks_shares','gender','investments_today','investments_over','investment_frequency','point_in_time','order_by','order');
                foreach ($t_all_linked_versions as $t_all_linked_version) {
                    $combined_user_data[$t_all_linked_version] = isset($all_versions[$t_all_linked_version]) ? $all_versions[$t_all_linked_version] : '';
                    if ( $i_linked_version_counter == 1 ) {
                        $all_combined_user_data = $combined_user_data[$t_all_linked_version];
                    } else {
                        foreach ($combined_user_data[$t_all_linked_version] as $s_key=>$s_val) {
                            if ( !in_array($s_key, $t_ignorable_keys) ) {
                                $all_combined_user_data[$s_key] = $all_combined_user_data[$s_key] + $combined_user_data[$t_all_linked_version][$s_key];
                            } else {
                                if ( isset($combined_user_data[$t_all_linked_version][$s_key]) && !empty($combined_user_data[$t_all_linked_version][$s_key]) ) {
                                    $all_combined_user_data[$s_key] = $combined_user_data[$t_all_linked_version][$s_key];
                                }
                            }
                        }
                    }
                    $i_linked_version_counter++;
                }
                if ( isset($all_combined_user_data) && !empty($all_combined_user_data) ) {
                    if( isset($all_combined_user_data['total_savings_and_investments']) && !empty($all_combined_user_data['total_savings_and_investments']) ) {
                        if ( isset($all_combined_user_data['total_shares']) && !empty($all_combined_user_data['total_shares']) ) {
                            $total_funds = (float)$all_combined_user_data['total_savings_and_investments'] - (float)$all_combined_user_data['total_shares'];
                        } else {
                            $total_funds = (float)$all_combined_user_data['total_savings_and_investments'];
                        }
                        $total_funds = $currency . " " . number_format( $total_funds );
                    }

                    if( isset($all_combined_user_data['total_shares']) && !empty($all_combined_user_data['total_shares']) ) {
                        $total_ex_traded = (float)$all_combined_user_data['total_shares'];
                        $total_ex_traded = $currency . " " . number_format( $total_ex_traded );
                    }

                    if( isset($all_combined_user_data['total_savings_and_investments_cash']) && !empty($all_combined_user_data['total_savings_and_investments_cash']) ) {
                        $total_cash = (float)$all_combined_user_data['total_savings_and_investments_cash'];
                        $total_cash = $currency . " " . number_format( $total_cash );
                    }

                    if( isset($all_combined_user_data['total_savings_and_investments_total']) && !empty($all_combined_user_data['total_savings_and_investments_total']) ) {
                        $total_investments = (float)$all_combined_user_data['total_savings_and_investments_total'];
                        $total_investments = $currency . " " . number_format( $total_investments );
                    }

                    if( isset($all_combined_user_data['investment_frequency_funds']) && !empty($all_combined_user_data['investment_frequency_funds']) ) {
                        $trading_freq_funds = (float)$all_combined_user_data['investment_frequency_funds'];
                        $trading_freq_funds = intval( esc_attr( $trading_freq_funds ) );
                    }

                    if( isset($all_combined_user_data['investment_frequency_ex_traded']) && !empty($all_combined_user_data['investment_frequency_ex_traded']) ) {
                        $trading_freq_ex_traded = (float)$all_combined_user_data['investment_frequency_ex_traded'];
                        $trading_freq_funds = intval( esc_attr( $trading_freq_ex_traded ) );
                    }

                    if( isset($all_combined_user_data['average_investment_funds']) && !empty($all_combined_user_data['average_investment_funds']) ) {
                        $avg_trading_funds = (float)$all_combined_user_data['average_investment_funds'];
                        $total_investments = $currency . " " . number_format( $avg_trading_funds );
                    }

                    if( isset($all_combined_user_data['average_investment_ex_traded']) && !empty($all_combined_user_data['average_investment_ex_traded']) ) {
                        $avg_trading_ex_traded = (float)$all_combined_user_data['average_investment_ex_traded'];
                        $total_investments = $currency . " " . number_format( $avg_trading_ex_traded );
                    }
                }
            }
        }
  }
  $calculator_url = esc_url( get_permalink( get_page_by_title( 'Platform Calculator' ) ) );
  $step_1_url     = add_query_arg( 'step', 1, $calculator_url );
  $step_1_url = add_query_arg( 'version', $version, $step_1_url );
  $step_2_url = add_query_arg( 'step', 2, $calculator_url );
  $step_2_url = add_query_arg( 'version', $version, $step_2_url );
  ob_start();

  include 'templates/simplified-calculator-steps-form.php';

  return ob_get_clean();
}


/**
 *
 *
 * @return string
 */
function cplat_platform_fee_shortcode() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }

  $currency = "£";

  if ( is_user_logged_in() ) {
    $platform_user = wp_get_current_user();
  } else {
    $platform_user = new stdClass();
  }

  $charge_types = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );

  ob_start();
  include 'templates/platform-fee.php';

  return ob_get_clean();
}

add_shortcode( 'platform_fee_data', 'cplat_platform_fee_shortcode' );


/**
 *
 *
 * @return bool|string
 */
function cplat_platform_annual_admin_charges() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }

  $currency = "£";

  if ( is_user_logged_in() ) {
    $platform_user = wp_get_current_user();
  } else {
    $platform_user = new stdClass();
  }

  $charge_types = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );
  $action       = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : false;
  $id           = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : '';

  if ( ! $action ) {
    return false;
  }

  ob_start();
  include 'templates/platform-anual-admin-charges.php';

  return ob_get_clean();
}

add_shortcode( 'platform_annual_admin_charges', 'cplat_platform_annual_admin_charges' );


function cplat_platform_investment_admin_charges() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }

  $currency = "£";

  if ( is_user_logged_in() ) {
    $platform_user = wp_get_current_user();
  } else {
    $platform_user = new stdClass();
  }

  $charge_types = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );
  ob_start();
  include 'templates/investment-fee.php';

  return ob_get_clean();
}

add_shortcode( 'platform_investment_admin_charges', 'cplat_platform_investment_admin_charges' );


function cplat_dealing_switching_fees() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }

  $currency = "£";

  if ( is_user_logged_in() ) {
    $platform_user = wp_get_current_user();
  } else {
    $platform_user = new stdClass();
  }

  $charge_types = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );

  ob_start();
  include 'templates/dealing-switching-fee.php';

  return ob_get_clean();
}

add_shortcode( 'platform_dealing_switching_fees', 'cplat_dealing_switching_fees' );


function cplat_platform_transfer_account_opening_charges() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }

  $currency = "£";

  if ( is_user_logged_in() ) {
    $platform_user = wp_get_current_user();
  } else {
    $platform_user = new stdClass();
  }
  $charge_types   = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );
  $transfer_types = array(

    1 => __( 'Specie' ),
    2 => __( 'Cash' ),
    3 => __( 'Abroad' ),

  );

  ob_start();
  include 'templates/transfer-account-opening-charges.php';

  return ob_get_clean();
}

add_shortcode( 'platform_transfer_account_opening_charges', 'cplat_platform_transfer_account_opening_charges' );


function cplat_platform_transfer_closure_charges() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }

  $currency = "£";

  if ( is_user_logged_in() ) {
    $platform_user = wp_get_current_user();
  } else {
    $platform_user = new stdClass();
  }

  $charge_types   = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );
  $transfer_types = array(

    'flat_rate'      => __( 'Flat rate' ),
    'per_investment' => __( 'Per Investment' ),

  );


  ob_start();
  include 'templates/transfer-out-closure.php';

  return ob_get_clean();
}

add_shortcode( 'platform_transfer_closure_charges', 'cplat_platform_transfer_closure_charges' );

function cplat_platform_sipp_charges() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }

  $currency = "£";

  if ( is_user_logged_in() ) {
    $platform_user = wp_get_current_user();
  } else {
    $platform_user = new stdClass();
  }

  $charge_types = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );
  ob_start();
  include 'templates/sipp-charges.php';

  return ob_get_clean();
}

add_shortcode( 'platform_sipp_charges', 'cplat_platform_sipp_charges' );


function cplat_vendor_account() {

  if ( ! is_user_logged_in() || ! cplat_check_user_role( 'platform_vendor' ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }
  $currency = "£";
  $user     = wp_get_current_user();

  $platform_ids = get_user_meta( $user->ID, '_cplat_user_platform', true );
  $platform_id  = is_array( $platform_ids ) ? $platform_ids[0] : $platform_ids;

  $switch_platform_id = isset( $_GET['platform_id'] ) ? intval( $_GET['platform_id'] ) : 0;

  if ( $switch_platform_id ) {
    if ( is_array( $platform_ids ) && in_array( $switch_platform_id, $platform_ids ) ) {
      $platform_id = $switch_platform_id;
    }
  }

  $charge_types = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );

    //RSPL TASK#21
  $investment_types = array(
    1 => __( 'Fund' ),
    2 => __( 'Exchange-traded investment' ),
    4 => __( 'Cash' )
  );
  $transfer_types   = array(

    'flat_rate'      => __( 'Flat rate' ),
    'per_investment' => __( 'Per Investment' ),

  );

  if ( ! empty( $platform_id ) ) {
    //$all_data = get_post_meta( $platform_id, 'platform_data', true);
    $ctp_api        = new CTP_API();
    $platform_data  = $ctp_api->ctp_api_get_platform( $platform_id );
    $all_data       = $platform_data['platform_data'];
    $platform_post  = $platform_data['platform'][0];
    $platform_title = $platform_post['platform_name'];

  } else {
    $all_data       = false;
    $platform_title = '';
  }

  if ( isset( $all_data ) ) {
    /**
     * Get latest version
     */
    end( $all_data );
    $version_id = key( $all_data );
  } else {
    /**
     * This is first version
     */
    $version_id = '';
  }

  ob_start();
  if ( 0 < $platform_id && empty( $version_id ) ) {
    // _e('Unable to load your platform data', 'cplat');
    include 'templates/platform-data.php';

  } elseif ( 0 < $platform_id && 0 < $version_id ) {

    $all_data = isset( $all_data[ $version_id ] ) ? $all_data[ $version_id ] : false;
    include 'templates/platform-data.php';

  } else {
    //_e('Unable to load your platform data', 'cplat');
  }

  return ob_get_clean();
}

add_shortcode( 'vendor_account', 'cplat_vendor_account' );

function cplat_manage_vendor_account() {

  if ( ! current_user_can( 'edit_platform_data' ) ) {
    return false;
  }

  if ( ! isset( $_GET['platform_id'] ) || 0 === intval( $_GET['platform_id'] ) ) {
    //return __('Unable to load your platform data', 'cplat');
  }
  $platform_id = isset( $_GET['platform_id'] ) ? (int) $_GET['platform_id'] : 0;
  $version_id  = isset( $_GET['version_id'] ) ? (int) $_GET['version_id'] : 0;

  // TODO can user edit/view this version. Admin can any, platform vendor only latest

  $currency         = "£";
  $charge_types     = array(
    Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
    Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
    Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
    Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
  );
    //RSPL TASK#21
    $investment_types = array(
    1 => __( 'Fund' ),
    2 => __( 'Exchange-traded investment' ),
    4 => __( 'Cash' )
  );
  $transfer_types   = array(

    'flat_rate'      => __( 'Flat rate' ),
    'per_investment' => __( 'Per Investment' ),

  );

  if ( ! empty( $platform_id ) ) {

    $ctp_api        = new CTP_API();
    $platform_data  = $ctp_api->ctp_api_get_platform( $platform_id );
    $all_data       = $platform_data['platform_data'];
    $platform_post  = $platform_data['platform'][0];
    $platform_title = $platform_post['platform_name'];
  } else {
    $all_data       = false;
    $platform_title = '';
  }

  ob_start();

  if ( 0 < $platform_id && 0 === $version_id ) {

    //_e('Unable to load your platform data', 'cplat');

    include 'templates/platform-data.php';

  } elseif ( 0 < $platform_id && 0 < $version_id ) {

    $all_data = isset( $all_data[ $version_id ] ) ? $all_data[ $version_id ] : false;
    include 'templates/platform-data.php';

  } else {
    //_e('Unable to load your platform data', 'cplat');
  }

  return ob_get_clean();
}

add_shortcode( 'manage_vendor_account', 'cplat_manage_vendor_account' );


function cplat_subscriber_account() {
  wp_enqueue_script('ctp-blockUI');
  session_start();
  /**
   * User not logged in. Go to login
   */
  if ( ! is_user_logged_in() || ( ! cplat_check_user_role( 'subscriber' ) && ! cplat_check_user_role( 'adviser' ) && ! cplat_check_user_role( 'administrator' ) && ! cplat_check_user_role( 'platform_data_manager' ) ) ) {
    $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
    wp_redirect( $redirect_to_login );
    exit;
  }
  $subscriber        = wp_get_current_user();
  //$saved_results = $_SESSION['user_financial_data'];
  $saved_results     = get_user_meta( $subscriber->ID, 'user_financial_data', true );
  $saved_ecc_results = get_user_meta( $subscriber->ID, Ctp_Ecc::USER_META_KEY, true );
    //RSPL Task#54
  $saved_robo_results = get_user_meta( $subscriber->ID, Robo_Calculator::USER_META_KEY, true );
  ob_start();
  include 'templates/subscriber.php';

  return ob_get_clean();
}

add_shortcode( 'subscriber_account', 'cplat_subscriber_account' );

function cplat_partners() {

  ob_start();
  include 'templates/partners.php';

  return ob_get_clean();
}

add_shortcode( 'cplat_partners', 'cplat_partners' );

function cplat_platforms() {

  ob_start();
  include 'templates/v2/platforms.php';

  return ob_get_clean();
}

add_shortcode( 'cplat_platforms', 'cplat_platforms' );

function cplat_platforms_advised() {
  $advised = true;
  ob_start();
  include 'templates/v2/platforms.php';

  return ob_get_clean();
}

add_shortcode( 'cplat_platforms_advised', 'cplat_platforms_advised' );

function cplat_platforms_d2c() {
  $d2c = true;
  ob_start();
  include 'templates/v2/platforms.php';

  return ob_get_clean();
}

add_shortcode( 'cplat_platforms_d2c', 'cplat_platforms_d2c' );

add_shortcode( 'ctp_user_form', 'cplat_submit_form_shortcode' );


/**
* @version : Ticket#192 @ checklist - 4
*/
add_shortcode( 'ctp_platform_heat_map', 'ctp_platform_heat_map__shortcode_cb' );
function ctp_platform_heat_map__shortcode_cb(){
  ob_start();
    $platform_type = ( isset($_GET['platform_type']) ? $_GET['platform_type'] : 1  );
    $inv_type = ( $platform_type == 1 ? "myself" :  "" );

      $calc_user_both_isa_gia = new stdClass; 
    $calc_user_both_isa_gia->total_savings_and_investments = 50000;
    $calc_user_both_isa_gia->total_shares = $calc_user_both_isa_gia->total_savings_and_investments_cash = 0;
    $calc_user_both_isa_gia->total_savings_and_investments_total = 50000;
    $calc_user_both_isa_gia->total_all = 50000;
    $calc_user_both_isa_gia->investment_products = 'yes';
    $calc_user_both_isa_gia->planning_stocks_shares = 'no';
    $calc_user_both_isa_gia->funds_isa = 12500;
    $calc_user_both_isa_gia->funds_sipp = 25000;
    $calc_user_both_isa_gia->funds_gia = 12500;
    $calc_user_both_isa_gia->inv_management_type = $inv_type;
    $calc_user_both_isa_gia->total_savings_and_investments = 50000;
    $calc_user_both_isa_gia->age = 26;
    $calc_user_both_isa_gia->gender = 'male';


    $calc_user_sipp_only = new stdClass; 
    $calc_user_sipp_only->total_savings_and_investments = 50000;
    $calc_user_sipp_only->total_shares = $calc_user_sipp_only->total_savings_and_investments_cash = 0;
    $calc_user_sipp_only->total_savings_and_investments_total = 50000;
    $calc_user_sipp_only->total_all = 50000;
    $calc_user_sipp_only->investment_products = 'yes';
    $calc_user_sipp_only->planning_stocks_shares = 'no';
    $calc_user_sipp_only->funds_isa = 0;
    $calc_user_sipp_only->funds_sipp = 50000;
    $calc_user_sipp_only->funds_gia = 0;
    $calc_user_sipp_only->inv_management_type = $inv_type;
    $calc_user_sipp_only->total_savings_and_investments = 50000;
    $calc_user_sipp_only->age = 26;
    $calc_user_sipp_only->gender = 'male';

    $calc_user_isa_only = new stdClass; 
    $calc_user_isa_only->total_savings_and_investments = 50000;
    $calc_user_isa_only->total_shares = $calc_user_isa_only->total_savings_and_investments_cash = 0;
    $calc_user_isa_only->total_savings_and_investments_total = 50000;
    $calc_user_isa_only->total_all = 50000;
    $calc_user_isa_only->investment_products = 'yes';
    $calc_user_isa_only->planning_stocks_shares = 'no';
    $calc_user_isa_only->funds_isa = 50000;
    $calc_user_isa_only->funds_sipp = 0;
    $calc_user_isa_only->funds_gia = 0;
    $calc_user_isa_only->inv_management_type = $inv_type;
    $calc_user_isa_only->total_savings_and_investments = 50000;
    $calc_user_isa_only->age = 26;
    $calc_user_isa_only->gender = 'male';

    /*$calc_user->funds_jisa = 0;
    $calc_user->funds_jsipp = 0;
    $calc_user->funds_onshore_bond = 0;
    $calc_user->funds_offshore_bond = 0;


    $calc_user->ex_instruments_isa = 0;
    $calc_user->ex_instruments_sipp = 0;
    $calc_user->ex_instruments_gia = 0;
    $calc_user->ex_instruments_jisa = 0;
    $calc_user->ex_instruments_jsipp = 0;
    $calc_user->ex_instruments_onshore_bond = 0;
    $calc_user->ex_instruments_offshore_bond = 0; */

    $compare_sipp_only         = new CTP_API( json_encode( $calc_user_sipp_only ) );
    $heat_map_listing_sipp_only = $compare_sipp_only->get_platform_heat_map();

    $compare_isa_only         = new CTP_API( json_encode( $calc_user_isa_only ) );
    $heat_map_listing_isa_only = $compare_isa_only->get_platform_heat_map();

    $compare_both_isa_gia         = new CTP_API( json_encode( $calc_user_both_isa_gia ) );
    $heat_map_listing_both_isa_gia = $compare_both_isa_gia->get_platform_heat_map();
    //echo '<pre>'; print_r($heat_map_listing); exit;
  include 'templates/platform-heat-map.php';

  return ob_get_clean();
}
