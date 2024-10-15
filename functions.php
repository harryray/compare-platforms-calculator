<?php

function cplat_save_steps()
{

  if (!isset($_POST['calculator_action']) || $_POST['calculator_action'] !== 'save_step') {
    return false;
  }

  $version     = isset($_GET['version']) ? $_GET['version'] : 'new';
  $ecc_version = isset($_GET['ecc_version']) ? $_GET['ecc_version'] : null;

  $user = wp_get_current_user();

  $subscriber_data = new Subscriber_Data();

  $step = isset($_GET['step']) ? intval($_GET['step']) : 2;

  $data = $_POST;
  //pass the data form exit charge
  if (isset($_GET['ecc_version'])) {
    $ecc_version = trim($_GET['ecc_version']);
    $ecc_data    = get_user_meta($user->ID, Ctp_Ecc::USER_META_KEY, true);
    if (isset($ecc_data[$ecc_version])) {
      $ecc_data = $ecc_data[$ecc_version];
      foreach ($ecc_data as $key => $val) {
        if (!empty($ecc_data[$key]) && empty($data[$key])) {
          $data[$key] = $ecc_data[$key];
        }
      }
    }
  }
  if ($data['investment_products'] == 'no' || $data['investment_stocks_shares'] == 'no') {
    $data['total_shares'] = 0;
    $data['ex_instruments_isa'] = 0;
    $data['ex_instruments_jisa'] = 0;
    $data['ex_instruments_sipp'] = 0;
    $data['ex_instruments_jsipp'] = 0;
    $data['ex_instruments_gia'] = 0;
    $data['ex_instruments_onshore_bond'] = 0;
    $data['ex_instruments_offshore_bond'] = 0;
    $data['investment_frequency_ex_traded'] = 0;
    $data['average_investment_ex_traded'] = 0;
  }
  /*Ticket#218 End */
  if( $_POST['calculator_type'] == 'simplified' ){
    $data['inv_management_type'] = 'myself';
    $data['total_savings_and_investments_total'] = $_POST['total_savings_and_investments'];
    $data['investment_products_simplified'] = true;
    // User didn't provide product values
    if ( $_POST['investment_products'] === 'no' && 
    ! empty( $_POST['total_savings_and_investments'] ) ) {

      //Split total by 3
      $slice = $_POST['total_savings_and_investments'] / 2;
      $data['funds_gia']   = $slice;
      $data['funds_isa']   = $slice;
      $data['funds_jisa']  = 0;
      $data['funds_sipp']  = 0;
      $data['funds_jsipp'] = 0;
    }
  }
  $version = $subscriber_data->save_user_meta($user, $data, $version);
  $ttt    = get_user_meta($user->ID, 'user_financial_data', true);
  $main_version = isset($_REQUEST['main_version']) ? $_REQUEST['main_version'] : '';
  $next_step = $step + 1;
  if($_GET['htb_serialized_data']) {
    $htb_serialized_strippedslashes = stripslashes($_GET['htb_serialized_data']);
    $htb_unserialized  = unserialize(stripslashes($htb_serialized_strippedslashes));
    $htb_data = array_merge($htb_unserialized, $data);
  } else {
    $htb_data = $data;
  }

  if (isset($_GET['portfolio_setup']) && !empty($_GET['portfolio_setup']) && $_GET['portfolio_setup'] == 1) {
    $next_step = 2;
    $version = '';
  }
  if( isset($_REQUEST['calculator_type']) && $_REQUEST['calculator_type'] == 'simplified' ){
    $step = 2;
    $next_step = $step;
  }
  $errors = ctp_errors()->get_error_messages();
  if (!$errors) {

    $calculator_url = esc_url(get_permalink(get_page_by_title('Platform Calculator')));
    $redirect       = add_query_arg('step', $next_step, $calculator_url);
    if (isset($_GET['portfolio_setup']) && !empty($_GET['portfolio_setup']) && $_GET['portfolio_setup'] == 1 && $step == 2) {
      $redirect = remove_query_arg(array('version', 'portfolio_setup'));
    } else {
      $redirect = add_query_arg('version', $version, $redirect);
    }
    //$redirect       = add_query_arg('htb_serialized_data', serialize($htb_data), $redirect);
    $redirect       = add_query_arg('main_version', $main_version, $redirect);
    if (isset($ecc_version)) {
      $redirect = add_query_arg('ecc_version', $ecc_version, $redirect);
    }
    wp_redirect($redirect);
    exit;
  }
}

add_action('init', 'cplat_save_steps');

function cplat_get_platform_list($user_data, $user_id, $order_by = 'cost_low_high', $update = false)
{

  $compare   = null;
  $platforms = null;
  //@todo add server side validation, temporary fix


  $user_data = (object) $user_data;
  if (!isset($user_data->age)) {
    $user_data->age = '';
  }
  if (!isset($user_data->gender)) {
    $user_data->gender = '';
  }
  if (cplat_check_user_role('platinum_vendor', $user_id)) {
    $user_data->platinum_user_id = $user_id;
    $user_data->platinum_vendor  = 1;
    $user_data->pid              = isset($user_data->platform_id) ? $user_data->platform_id : null;
  }
  /* Get Current User Role */
  $user = wp_get_current_user();
  $user_data->is_growth = (isset($user_data->is_growth) && $user_data->is_growth != 0 ? $user_data->is_growth : 'yes');
  $user_data->growth_rate = ($user_data->is_growth == 'yes' ? 4 : $user_data->growth_rate);
  // add to accommodate if is_growth contains string "4"
  $user_data->growth_rate = ($user_data->growth_rate == "4" ? 4 : $user_data->growth_rate);
  $compare         = new CTP_API(json_encode($user_data));

  $subscriber_data = new Subscriber_Data();
  $version         = (int) $_POST['version'];
  $platforms       = $subscriber_data->get_user_results_meta($user_id, $version);

  if ($update) {
    $user_data = $compare->ctp_api_update_user_data($_POST);
    $user      = wp_get_current_user();
    $subscriber_data->save_user_meta($user, (array) $user_data, $version);
  }

  //if (empty($platforms) || $update || (isset($_SESSION['cplat_update']) && $_SESSION['cplat_update'] == 1) || isset($platforms['message']) || $user_data->update == 1) {
    $platforms = $compare->get_platform_queue();
    $subscriber_data->save_user_results_meta($user_id, $platforms, $version);
  //}
  $count = count($platforms);


  $results = array(
    'list'  => $platforms,
    'count' => $count
  );

  return $results;
}

function get_users_total_funds($user_data)
{
  $total = 0;
  if (isset($user_data['investment_products']) && $user_data['investment_products'] === 'no') {
    $total += $user_data['total_savings_and_investments'];
    $user_data['funds_isa'];
    $user_data['funds_jisa'];
    $user_data['funds_sipp'];
    $user_data['funds_jsipp'];
    $user_data['funds_gia'];
  } else {
    $total += $user_data['funds_isa'];
    $total += $user_data['funds_jisa'];
    $total += $user_data['funds_sipp'];
    $total += $user_data['funds_jsipp'];
    $total += $user_data['funds_gia'];
  }

  return $total;
}

function cplat_set_adjustments($user_data, $adjustments)
{


  switch ($adjustments['order_by']) {

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
  return $user_data;
}

function cplat_set_adjustments_old_func($user_data, $adjustments)
{

  $ratio = array();

  /**
   * Funds adjustments
   */
  $funds_total = 0;
  $funds_total += $user_data['funds_isa'];
  $funds_total += $user_data['funds_jisa'];
  $funds_total += $user_data['funds_sipp'];
  $funds_total += $user_data['funds_jsipp'];
  $funds_total += $user_data['funds_gia'];

  //RSPL Task#37
  $ratio['funds_isa']       = $funds_total / $user_data['funds_isa'];
  $ratio['funds_jisa']      = $funds_total / $user_data['funds_jisa'];
  $ratio['funds_sipp']      = $funds_total / $user_data['funds_sipp'];
  $ratio['funds_jsipp']     = $funds_total / $user_data['funds_jsipp'];
  $ratio['funds_gia']       = $funds_total / $user_data['funds_gia'];
  $new_total_funds          = cplat_sanitize_number($adjustments['total_funds']);
  $user_data['funds_isa']   = cplat_sanitize_number($new_total_funds / $ratio['funds_isa']);
  $user_data['funds_jisa']  = cplat_sanitize_number($new_total_funds / $ratio['funds_jisa']);
  $user_data['funds_sipp']  = cplat_sanitize_number($new_total_funds / $ratio['funds_sipp']);
  $user_data['funds_jsipp'] = cplat_sanitize_number($new_total_funds / $ratio['funds_jsipp']);
  $user_data['funds_gia']   = cplat_sanitize_number($new_total_funds / $ratio['funds_gia']);
  $user_data['total_all']   = $new_total_funds;
  //$user_data['total_funds']   = $new_total_funds;

  /**
   * Cash adjustments
   */
  //RSPL Task#37
  $cash_total = 0;
  $cash_total += $user_data['funds_isa_cash'];
  $cash_total += $user_data['funds_jisa_cash'];
  $cash_total += $user_data['funds_sipp_cash'];
  $cash_total += $user_data['funds_jsipp_cash'];
  $cash_total += $user_data['funds_gia_cash'];

  $ratio['funds_isa_cash']       = $cash_total / $user_data['funds_isa_cash'];
  $ratio['funds_jisa_cash']      = $cash_total / $user_data['funds_jisa_cash'];
  $ratio['funds_sipp_cash']      = $cash_total / $user_data['funds_sipp_cash'];
  $ratio['funds_jsipp_cash']     = $cash_total / $user_data['funds_jsipp_cash'];
  $ratio['funds_gia_cash']       = $cash_total / $user_data['funds_gia_cash'];
  $new_total_cash          = cplat_sanitize_number($adjustments['total_cash']);
  $user_data['funds_isa_cash']   = cplat_sanitize_number($new_total_cash / $ratio['funds_isa_cash']);
  $user_data['funds_jisa_cash']  = cplat_sanitize_number($new_total_cash / $ratio['funds_jisa_cash']);
  $user_data['funds_sipp_cash']  = cplat_sanitize_number($new_total_cash / $ratio['funds_sipp_cash']);
  $user_data['funds_jsipp_cash'] = cplat_sanitize_number($new_total_cash / $ratio['funds_jsipp_cash']);
  $user_data['funds_gia_cash']   = cplat_sanitize_number($new_total_cash / $ratio['funds_gia_cash']);
  $user_data['total_all_cash']   = $new_total_cash;


  /**
   * Cash adjustments RSPL #236
   */
  //Begin : RSPL Task#236
  // $user_data['funds_int_isa_cash']       = $user_data['funds_isa_cash'];
  // $user_data['funds_int_jisa_cash']      = $user_data['funds_jisa_cash'];
  // $user_data['funds_int_sipp_cash']      = $user_data['funds_sipp_cash'];
  // $user_data['funds_int_jsipp_cash']     = $user_data['funds_jsipp_cash'];
  // $user_data['funds_int_gia_cash']       = $user_data['funds_gia_cash'];
  //Begin : RSPL Task#236



  /**
   * Ex Instruments adjustments
   */
  $ex_instruments_total          = 0;
  $ex_instruments_total          += $user_data['ex_instruments_isa'];
  $ex_instruments_total          += $user_data['ex_instruments_jisa'];
  $ex_instruments_total          += $user_data['ex_instruments_sipp'];
  $ex_instruments_total          += $user_data['ex_instruments_jsipp'];
  $ex_instruments_total          += $user_data['ex_instruments_gia'];

  $ratio['ex_instruments_isa']   = $ex_instruments_total / $user_data['ex_instruments_isa'];
  $ratio['ex_instruments_jisa']  = $ex_instruments_total / $user_data['ex_instruments_jisa'];
  $ratio['ex_instruments_sipp']  = $ex_instruments_total / $user_data['ex_instruments_sipp'];
  $ratio['ex_instruments_jsipp'] = $ex_instruments_total / $user_data['ex_instruments_jsipp'];
  $ratio['ex_instruments_gia']   = $ex_instruments_total / $user_data['ex_instruments_gia'];
  $new_ex_total                  = cplat_sanitize_number($adjustments['total_shares']);
  $user_data['ex_instruments_isa']   = cplat_sanitize_number($new_ex_total / $ratio['ex_instruments_isa']);
  $user_data['ex_instruments_jisa']  = cplat_sanitize_number($new_ex_total / $ratio['ex_instruments_jisa']);
  $user_data['ex_instruments_sipp']  = cplat_sanitize_number($new_ex_total / $ratio['ex_instruments_sipp']);
  $user_data['ex_instruments_jsipp'] = cplat_sanitize_number($new_ex_total / $ratio['ex_instruments_jsipp']);
  $user_data['ex_instruments_gia']   = cplat_sanitize_number($new_ex_total / $ratio['ex_instruments_gia']);
  //$user_data['total_shares']   = $new_ex_total;

  // RSPL Task#25
  // $user_data['funds_isa_total']   = $user_data['funds_isa'] + $user_data['funds_isa_cash'] + $user_data['ex_instruments_isa'];
  // $user_data['funds_jisa_total']  = $user_data['funds_jisa'] + $user_data['funds_jisa_cash'] + $user_data['ex_instruments_jisa'];
  // $user_data['funds_sipp_total']  = $user_data['funds_sipp'] + $user_data['funds_sipp_cash'] + $user_data['ex_instruments_sipp'];
  // $user_data['funds_jsipp_total'] = $user_data['funds_jsipp'] + $user_data['funds_jsipp_cash'] + $user_data['ex_instruments_jsipp'];
  // $user_data['funds_gia_total']   = $user_data['funds_gia'] + $user_data['funds_gia_cash'] + $user_data['ex_instruments_gia'];
  // $user_data['total_all_total'] = $new_total_funds+$new_total_cash+$new_ex_total;
  // $user_data['total_savings_and_investments_cash'] = $new_total_cash;
  // $user_data['total_savings_and_investments_total'] = $new_total_funds+$new_total_cash+$new_ex_total;
  // $user_data['total_savings_and_investments'] = $new_total_funds;

  // RSPL Task#25
  $user_data['funds_isa_total']   = $user_data['funds_isa'] + $user_data['funds_isa_cash'];
  $user_data['funds_jisa_total']  = $user_data['funds_jisa'] + $user_data['funds_jisa_cash'];
  $user_data['funds_sipp_total']  = $user_data['funds_sipp'] + $user_data['funds_sipp_cash'];
  $user_data['funds_jsipp_total'] = $user_data['funds_jsipp'] + $user_data['funds_jsipp_cash'];
  $user_data['funds_gia_total']   = $user_data['funds_gia'] + $user_data['funds_gia_cash'];
  $user_data['total_all_total'] = $new_total_funds + $new_total_cash;
  $user_data['total_savings_and_investments_cash'] = $new_total_cash;
  $user_data['total_savings_and_investments_total'] = $new_total_funds + $new_total_cash;
  $user_data['total_savings_and_investments'] = $new_total_funds;

  //RSPL Task#37
  $user_data['investment_frequency'] = $adjustments['investment_frequency'];
  $user_data['point_in_time']        = $adjustments['point_in_time'];

  // RSPL Task#172
  $user_data['investment_frequency_funds'] = $adjustments['investment_frequency_funds'];
  $user_data['investment_frequency_ex_traded'] = $adjustments['investment_frequency_ex_traded'];
  $user_data['average_investment_funds'] = $adjustments['average_investment_funds'];
  $user_data['average_investment_ex_traded'] = $adjustments['average_investment_ex_traded'];

  switch ($adjustments['order_by']) {

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
  // RSPL Ticket#192 checklist-2 Start
  $user_data['is_growth'] = $adjustments['is_growth'];
  $user_data['growth_rate'] = ($user_data['is_growth'] == 'yes' ? 4 : $adjustments['growth_rate']);
  $user_data['roles_commas'] = $adjustments['roles_commas'];
  // RSPL Ticket#192 checklist-2 End

  // RSPL Ticket#192 checklist-3 Start
  // echo '<pre>';
  // print_r($adjustments); exit;
  $user_data['initial_advice_type'] = $adjustments['initial_advice_type'];
  $user_data['initial_adviser_charges'] = $adjustments['initial_adviser_charges'];
  $user_data['annual_advice_type'] = $adjustments['annual_advice_type'];
  $user_data['annual_adviser_charges'] = $adjustments['annual_adviser_charges'];
  // RSPL Ticket#192 checklist-3 End

  //Ticket#192 - CTP Development Changes start

  $over_years = $adjustments['investments_today'];
  if ($over_years === 'over_years' || $over_years === 'in_x_years') {
    // Yearly Funds Calculation
    $yearly_fund_total          = 0;
    $yearly_fund_total          += $user_data['planning_isa'];
    $yearly_fund_total          += $user_data['planning_jisa'];
    $yearly_fund_total          += $user_data['planning_sipp'];
    $yearly_fund_total          += $user_data['planning_jsipp'];
    $yearly_fund_total          += $user_data['planning_gia'];

    $ratio['planning_isa']   = $yearly_fund_total / $user_data['planning_isa'];
    $ratio['planning_jisa']  = $yearly_fund_total / $user_data['planning_jisa'];
    $ratio['planning_sipp']  = $yearly_fund_total / $user_data['planning_sipp'];
    $ratio['planning_jsipp'] = $yearly_fund_total / $user_data['planning_jsipp'];
    $ratio['planning_gia']   = $yearly_fund_total / $user_data['planning_gia'];
    $new_yearly_fund_total                  = cplat_sanitize_number($adjustments['yearly_investment_funds']);
    $user_data['planning_isa']   = cplat_sanitize_number($new_yearly_fund_total / $ratio['planning_isa']);
    $user_data['planning_jisa']  = cplat_sanitize_number($new_yearly_fund_total / $ratio['planning_jisa']);
    $user_data['planning_sipp']  = cplat_sanitize_number($new_yearly_fund_total / $ratio['planning_sipp']);
    $user_data['planning_jsipp'] = cplat_sanitize_number($new_yearly_fund_total / $ratio['planning_jsipp']);
    $user_data['planning_gia']   = cplat_sanitize_number($new_yearly_fund_total / $ratio['planning_gia']);
    $user_data['yearly_investment_funds']   = $new_yearly_fund_total;

    //Yearly Cash Calculation
    $yearly_cash_total          = 0;
    $yearly_cash_total          += $user_data['planning_isa_cash'];
    $yearly_cash_total          += $user_data['planning_jisa_cash'];
    $yearly_cash_total          += $user_data['planning_sipp_cash'];
    $yearly_cash_total          += $user_data['planning_jsipp_cash'];
    $yearly_cash_total          += $user_data['planning_gia_cash'];

    $ratio['planning_isa_cash']   = $yearly_cash_total / $user_data['planning_isa_cash'];
    $ratio['planning_jisa_cash']  = $yearly_cash_total / $user_data['planning_jisa_cash'];
    $ratio['planning_sipp_cash']  = $yearly_cash_total / $user_data['planning_sipp_cash'];
    $ratio['planning_jsipp_cash'] = $yearly_cash_total / $user_data['planning_jsipp_cash'];
    $ratio['planning_gia_cash']   = $yearly_cash_total / $user_data['planning_gia_cash'];
    $new_yearly_cash_total                  = cplat_sanitize_number($adjustments['yearly_investment_cash']);
    $user_data['planning_isa_cash']   = cplat_sanitize_number($new_yearly_cash_total / $ratio['planning_isa_cash']);
    $user_data['planning_jisa_cash']  = cplat_sanitize_number($new_yearly_cash_total / $ratio['planning_jisa_cash']);
    $user_data['planning_sipp_cash']  = cplat_sanitize_number($new_yearly_cash_total / $ratio['planning_sipp_cash']);
    $user_data['planning_jsipp_cash'] = cplat_sanitize_number($new_yearly_cash_total / $ratio['planning_jsipp_cash']);
    $user_data['planning_gia_cash']   = cplat_sanitize_number($new_yearly_cash_total / $ratio['planning_gia_cash']);
    $user_data['yearly_investment_cash']   = $new_yearly_cash_total;

    // Yearly Product wise Total
    $user_data['planning_isa_total']   = $user_data['planning_isa'] + $user_data['planning_isa_cash'];
    $user_data['planning_jisa_total']   = $user_data['planning_jisa'] + $user_data['planning_jisa_cash'];
    $user_data['planning_sipp_total']   = $user_data['planning_sipp'] + $user_data['planning_sipp_cash'];
    $user_data['planning_jsipp_total']   = $user_data['planning_jsipp'] + $user_data['planning_jsipp_cash'];
    $user_data['planning_gia_total']   = $user_data['planning_gia'] + $user_data['planning_gia_cash'];

    //Yearly Ex-Traded Calculations
    // Yearly Funds Calculation
    $yearly_ex_total          = 0;
    $yearly_ex_total          += $user_data['planning_ex_instruments_isa'];
    $yearly_ex_total          += $user_data['planning_ex_instruments_jisa'];
    $yearly_ex_total          += $user_data['planning_ex_instruments_sipp'];
    $yearly_ex_total          += $user_data['planning_ex_instruments_jsipp'];
    $yearly_ex_total          += $user_data['planning_ex_instruments_gia'];

    $ratio['planning_ex_instruments_isa']   = $yearly_ex_total / $user_data['planning_ex_instruments_isa'];
    $ratio['planning_ex_instruments_jisa']  = $yearly_ex_total / $user_data['planning_ex_instruments_jisa'];
    $ratio['planning_ex_instruments_sipp']  = $yearly_ex_total / $user_data['planning_ex_instruments_sipp'];
    $ratio['planning_ex_instruments_jsipp'] = $yearly_ex_total / $user_data['planning_ex_instruments_jsipp'];
    $ratio['planning_ex_instruments_gia']   = $yearly_ex_total / $user_data['planning_ex_instruments_gia'];
    $new_yearly_ex_total                  = cplat_sanitize_number($adjustments['yearly_investment_ex']);
    $user_data['planning_ex_instruments_isa']   = cplat_sanitize_number($new_yearly_ex_total / $ratio['planning_ex_instruments_isa']);
    $user_data['planning_ex_instruments_jisa']  = cplat_sanitize_number($new_yearly_ex_total / $ratio['planning_ex_instruments_jisa']);
    $user_data['planning_ex_instruments_sipp']  = cplat_sanitize_number($new_yearly_ex_total / $ratio['planning_ex_instruments_sipp']);
    $user_data['planning_ex_instruments_jsipp'] = cplat_sanitize_number($new_yearly_ex_total / $ratio['planning_ex_instruments_jsipp']);
    $user_data['planning_ex_instruments_gia']   = cplat_sanitize_number($new_yearly_ex_total / $ratio['planning_ex_instruments_gia']);
    $user_data['yearly_investment_ex']   = $new_yearly_ex_total;

    //Add Yearly invertment to UserData
    $user_data['total_yearly_investment_value']   = $new_yearly_fund_total + $new_yearly_cash_total;
    $user_data['total_yearly_investment_total']   = $new_yearly_fund_total + $new_yearly_cash_total + $new_yearly_ex_total;
  }
  //Ticket#192 - CTP Development Changes End
  $user_data['investments_today'] = $adjustments['investments_today'];
  $user_data['investments_over'] = $adjustments['investments_over'];
  $user_data['investments_in_x_years'] = $adjustments['investments_in_x_years'];
  $user_data['investments_in_x_years'] = $adjustments['investments_in_x_years'];
  //echo '<pre>'; print_r($user_data); echo '</pre>'; exit;
  return $user_data;
}

function sanitize_money($value)
{

  $value = preg_replace('/[^0-9\.]/', '', $value);
  if (!is_numeric($value)) {
    return 0;
  }

  return trim(floatval($value));
}

function esc_money($value)
{
  return number_format(sanitize_money($value), 2);
}

function cplat_check_user_role($role, $user_id = null)
{

  if (is_numeric($user_id)) {
    $user = get_userdata($user_id);
  } else {
    $user = wp_get_current_user();
  }

  if (empty($user)) {
    return false;
  }

  return in_array($role, (array) $user->roles);
}

/*-----------------------------------------------------------------------------------*/
/*  Nonce Permissions check
/*-----------------------------------------------------------------------------------*/
add_action('datb_nonce_check', 'datb_nonce_check');
function cplat_nonce_check()
{

  if (!isset($_POST['get_cplat_nonce']) || !wp_verify_nonce($_POST['get_cplat_nonce'], 'get_cplat-nonce')) {
    die('Permission check failed');
  } else {
    return true;
  }
}

function cplat_enqueue_scripts()
{

  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-datepicker');
  wp_enqueue_script('select-script', plugin_dir_url(__FILE__) . '/js/jquery.selectric.min.js', array('jquery'), time());
  wp_enqueue_script('platform-script', plugin_dir_url(__FILE__) . '/js/vendor-data.js', array('jquery'), time(), true);
  wp_enqueue_script('repeatable-script', plugin_dir_url(__FILE__) . '/js/repeatable-fields.js', array('jquery'), time());

  /**
   * Global js variables
   */
  // RSPL Task#25
  $user = wp_get_current_user();
  $user_type = get_post_meta(get_the_ID(), '_calculator_page_user_type', true); //advisor
  $allowed_linked_portfolios = 0;
  if (in_array('adviser', (array) $user->roles) || $user_type == 'advisor') {
    $allowed_linked_portfolios = 1;
    /*if ( isset($_GET['step']) && !empty($_GET['step']) && $_GET['step'] == 3 && isset($_GET['main_version']) && !empty($_GET['main_version']) && isset($_GET['version']) && !empty($_GET['version']) && $_GET['main_version'] != $_GET['version'] ) {*/
    if (isset($_GET['step']) && !empty($_GET['step']) && $_GET['step'] == 3 && isset($_GET['main_version']) && !empty($_GET['main_version']) && isset($_GET['version']) && !empty($_GET['version'])) {
      global $wpdb;
      $table_name = 'linked_portfolio_tbl';
      $is_exist = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE main_version LIKE '" . $_GET['main_version'] . "' LIMIT 1", ARRAY_A);
      if (isset($is_exist[0]['linked_version']) && !empty($is_exist[0]['linked_version'])) {
        $t_all_linked_versions = explode(',', $is_exist[0]['linked_version']);
        if (strpos((string)$is_exist[0]['linked_version'], (string)$_GET['version']) !== false) {
          if (count($t_all_linked_versions) < 10) {
          } else {
            $allowed_linked_portfolios = 2;
          }
        } else {
          if (count($t_all_linked_versions) < 10) {
            $all_linked_versions = $is_exist[0]['linked_version'] . ',' . $_GET['version'];
            $t_where = array('main_version' => $_GET['main_version']);
            $t_data = array(
              'linked_version' => $all_linked_versions,
              'linked_version_data' => '-',
              'updated_at' => current_time('Y-m-d H:i:s')
            );
            $wpdb->update($table_name, $t_data, $t_where);
            if (count($t_all_linked_versions) == 9) {
              $allowed_linked_portfolios = 2;
            }
          } else {
            $allowed_linked_portfolios = 2;
          }
        }
      }
    }
  }
  $s_current_main_version = '';
  $s_current_actual_version = '';
  if (isset($_GET['main_version']) && !empty($_GET['main_version'])) {
    $s_current_main_version = $_GET['main_version'];
  }
  if (isset($_GET['version']) && !empty($_GET['version'])) {
    $s_current_actual_version = $_GET['version'];
  }
  /* Get Current User Role */
  $user = wp_get_current_user();
  //$roles_arr = $user->roles;
  //$roles_commas = implode(',', $roles_arr);
  $roles_commas = ($user_type && $user_type == 'advisor' ? 'adviser' : 'subscriber');
  wp_localize_script('platform-script', 'get_cplat_vars', array(
    'ajaxurl'         => admin_url('admin-ajax.php'),
    'get_cplat_nonce' => wp_create_nonce('get_cplat-nonce'),
    'home_url' => get_home_url(),
    'allowed_linked_portfolios' => $allowed_linked_portfolios,
    'main_version' => (isset($s_current_main_version) && !empty($s_current_main_version)) ? $s_current_main_version : $s_current_actual_version,
    'current_version' => $s_current_actual_version,
    'current_user_roles' => $roles_commas,
    'user_type' => $user_type,
    'is_user_logged_in' => is_user_logged_in()
  ));
}

add_action('wp_enqueue_scripts', 'cplat_enqueue_scripts');

function cplat_calculator_scripts_init()
{
?>
  <script type="text/javascript">
    /* <![CDATA[ */
    (function($) {
      'use strict';

      function initialize_cplat_tabs() {
        $('.cplat-repeat').each(function() {
          $(this).repeatable_fields({
            wrapper: '.platform-data',
            container: '.container',
            row: '.data-row',
            add: '.add',
            remove: '.remove',
            template: '.template',
            sortable: false,
            row_count_placeholder: '{{row-count-placeholder}}'
          });
          $("#platform-from").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: 'd M yy',
            onClose: function(selectedDate) {
              $("#to").datepicker("option", "minDate", selectedDate);
            }
          });
          $("#platform-to").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: 'd M yy',
            onClose: function(selectedDate) {
              $("#from").datepicker("option", "maxDate", selectedDate);
            }
          });
          <?php if (is_page('print')) : ?>
            javascript.window.print();
          <?php endif; ?>
        });

      }

      $(document).ready(function() {
        initialize_cplat_tabs();

      });
    })(jQuery);
    /* ]]> */
  </script>
<?php
}

add_action('wp_head', 'cplat_calculator_scripts_init');

/**
 * Admin Styles
 */
function cplat_enqueue_admin_style()
{

  wp_enqueue_style('admin-platform-style', plugin_dir_url(__FILE__) . '/css/admin-calc.css');
}

add_action('admin_enqueue_scripts', 'cplat_enqueue_admin_style');

function cplat_text($field, $default)
{
  return isset($field) ? $field : $default;
}

add_action('admin_menu', 'remove_menus', 102);
function remove_menus()
{
  if (cplat_check_user_role('platform_data_manager')) {
    global $submenu;
    remove_menu_page('index.php'); // Posts
  }
}

function cplat_calc_body_class($classes)
{

  global $post;
  if (!isset($post)) {
    return false;
  }
  $post_slug = $post->post_name;

  if (current_user_can('edit_platform_data') && $post_slug === 'client-area') {
    $classes[] = 'platform-vendor-account';
  }

  return $classes;
}

add_action('body_class', 'cplat_calc_body_class');

function cplat_get_current_version($all_data)
{

  $today    = time();
  $selected = false;
  foreach ($all_data as $key => $data) {
    $from = strtotime($data['active_from']);
    $to   = strtotime($data['active_to']);

    // Is on date range
    if ($from <= $today && $to >= $today) {
      // Is approved
      if (Calculator_Compare::STATUS_APPROVED == $data['rec_status']) {
        $selected = $key;
        break;
      }
    }
  }

  return $selected;
}

function ctp_clean_number($number)
{
  $number = str_replace(' ', '', $number);
  $number = str_replace(',', '', $number);

  return $number;
}

function save_search_results()
{
  session_start();
  if (isset($_POST['action']) && $_POST['action'] === 'save_search_results') {

    $version = isset($_GET['version']) ? (int) $_GET['version'] : 0;
    if ($version === 0) {
      return false;
    }

    $user = wp_get_current_user();
    // Update version
    $name = sanitize_text_field($_POST['version_name']);

    //$saved_data = get_user_meta( $user->ID, 'user_financial_data', true );
    $saved_data = $_SESSION['user_financial_data'];
    if (isset($saved_data[$version])) {
      $saved_data[$version]['version_name'] = $name;
      if ($_POST['updated'] == 1) {
        //RSPL TASK#22
        $saved_data[$version]['total_savings_and_investments']  = intval($_POST['total_all']);
        //RSPL Task#37
        $saved_data[$version]['total_savings_and_investments_cash']  = intval($_POST['total_all_cash']);
        $saved_data[$version]['total_savings_and_investments_total']  = intval($_POST['total_all_total']);
        $saved_data[$version]['total_shares']                   = intval($_POST['total_shares']);
        $saved_data[$version]['investments_today']              = $_POST['investments_today'];
        $saved_data[$version]['investment_frequency']           = intval($_POST['nooftrades']);
        $saved_data[$version]['investment_frequency_funds']     = intval($_POST['investment_frequency_funds']);
        $saved_data[$version]['investment_frequency_ex_traded'] = intval($_POST['investment_frequency_ex_traded']);
        $saved_data[$version]['average_investment_funds']       = intval($_POST['average_investment_funds']);
        $saved_data[$version]['average_investment_ex_traded']   = intval($_POST['average_investment_ex_traded']);
        $saved_data[$version]['investments_over']               = intval($_POST['investments_over']);
        $saved_data[$version]['investments_in_x_years']         = intval($_POST['investments_in_x_years']);
      }
    }
    update_user_meta($user->ID, 'user_financial_data', $saved_data);

    $my_acc_url = get_permalink(get_page_by_path('client-area'));
    $my_acc_url = add_query_arg('message', 'version_saved', $my_acc_url);
    wp_redirect($my_acc_url);
    exit;
  }
}

add_action('init', 'save_search_results');

function cplat_print_result()
{
  session_start();
  $version = isset($_GET['printresult']) ? (int) $_GET['printresult'] : 0;
  if ($version === 0) {
    return false;
  }
  $currency = "£";

  if (is_user_logged_in()) {
    $calc_user = wp_get_current_user();
  } else {
    return false;
  }

  if (isset($calc_user->ID)) {
    //$all_versions = get_user_meta( $calc_user->ID, 'user_financial_data', true );
    $all_versions = $_SESSION['user_financial_data'];
  }
  if (isset($all_versions[$version])) {
    $calc_user->data = (object) $all_versions[$version];
  }

  if ($all_versions[$version]) {
    $platforms_data = cplat_get_platform_list($all_versions[$version], $calc_user->ID);
    $platforms      = $platforms_data['list'];
    $results_count  = $platforms_data['count'];
  } else {
    $platforms     = array();
    $results_count = 0;
  }

  include 'templates/print-results.php';
}

add_shortcode('print_result', 'cplat_print_result');

function cplat_email_result()
{
  @session_start();
  $version = isset($_GET['emailresult']) ? (int) $_GET['emailresult'] : 0;
  if ($version === 0) {
    return false;
  }
  $currency = "£";

  if (is_user_logged_in()) {
    $calc_user = wp_get_current_user();
  } else {
    return false;
  }

  if (isset($calc_user->ID)) {
    //$all_versions = get_user_meta( $calc_user->ID, 'user_financial_data', true );
    $all_versions = $_SESSION['user_financial_data'];
  }
  if (isset($all_versions[$version])) {
    $calc_user->data = (object) $all_versions[$version];
  }

  if ($all_versions[$version]) {
    $platforms_data = cplat_get_platform_list($all_versions[$version], $calc_user->ID);
    $platforms      = $platforms_data['list'];
    $results_count  = $platforms_data['count'];
  } else {
    $platforms     = array();
    $results_count = 0;
  }

  ob_start();
  include 'templates/email.php';
  $message = ob_get_clean();

  // Email vars
  $blogname    = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
  $admin_email = get_option('admin_email');

  $headers = 'From: Compare The Platform <no-reply@comparetheplatform.com>' . "\r\n";
  $user    = get_current_user_id();
  $user    = get_userdata($user);
  $subject = 'comparetheplatform.com results';

  add_filter('wp_mail_content_type', 'set_html_content_type');
  $result = wp_mail($user->user_email, $subject, $message);
  remove_filter('wp_mail_content_type', 'set_html_content_type');
  $my_acc_url = get_permalink(get_page_by_path('client-area'));
  if ($result) {
    $my_acc_url = add_query_arg('message', 'email_success', $my_acc_url);
  } else {
    $my_acc_url = add_query_arg('email', 'email_failed', $my_acc_url);
  }
  wp_redirect($my_acc_url);
  exit;
}

add_action('init', 'cplat_email_result');


function cplat_email_test()
{
  session_start();
  $version = isset($_GET['emailtestresult']) ? (int) $_GET['emailtestresult'] : 0;
  if ($version === 0) {
    return false;
  }
  $currency = "£";

  if (is_user_logged_in()) {
    $calc_user = wp_get_current_user();
  } else {
    return false;
  }

  if (isset($calc_user->ID)) {
    //$all_versions = get_user_meta( $calc_user->ID, 'user_financial_data', true );
    $all_versions = $_SESSION['user_financial_data'];
  }
  if (isset($all_versions[$version])) {
    $calc_user->data = (object) $all_versions[$version];
  }


  if ($all_versions[$version]) {
    $platforms_data = cplat_get_platform_list($all_versions[$version], $calc_user->ID);
    $platforms      = $platforms_data['list'];
    $results_count  = $platforms_data['count'];
  } else {
    $platforms     = array();
    $results_count = 0;
  }

  ob_start();
  include 'templates/email.php';
  $message = ob_get_clean();

  return $message;
}


/**
 * Set content type for confirmation emails
 */
function set_html_content_type()
{
  return 'text/html';
}

function cplat_remove_version()
{
  $version = 0;
  $key = 'user_financial_data';
  if ((isset($_GET['removeversion']) && !empty($_GET['removeversion'])) || (isset($_GET['removeeccversion']) && !empty($_GET['removeeccversion'])) || (isset($_GET['removeroboversion']) && !empty($_GET['removeroboversion']))) {
    if (isset($_GET['removeversion']) && !empty($_GET['removeversion'])) {
      session_start();
      $version = isset($_GET['removeversion']) ? (int) $_GET['removeversion'] : 0;
      $key = 'user_financial_data';
      $_SESSION['i_is_version_removed'] = 2;
    }
    if (isset($_GET['removeeccversion']) && !empty($_GET['removeeccversion'])) {
      session_start();
      $version = isset($_GET['removeeccversion']) ? (int) $_GET['removeeccversion'] : 0;
      $key = Ctp_Ecc::USER_META_KEY;
      $_SESSION['i_is_version_removed'] = 2;
    }
    if (isset($_GET['removeroboversion']) && !empty($_GET['removeroboversion'])) {
      session_start();
      $version = isset($_GET['removeroboversion']) ? (int) $_GET['removeroboversion'] : 0;
      $key = Robo_Calculator::USER_META_KEY;
      $_SESSION['i_is_version_removed'] = 2;
    }
    if (isset($_GET['removesimpleversion']) && !empty($_GET['removesimpleversion'])) {
      session_start();
      $version = isset($_GET['removesimpleversion']) ? (int) $_GET['removesimpleversion'] : 0;
      $key = 'user_financial_data';
      $_SESSION['i_is_version_removed'] = 2;
    }
    
  }
  //$version = isset( $_GET['removeversion'] ) ? (int) $_GET['removeversion'] : 0;
  if ($version === 0) {
    return false;
  }
  if (is_user_logged_in()) {
    $calc_user = wp_get_current_user();
  } else {
    return false;
  }

  $all_versions = get_user_meta($calc_user->ID, $key, true);

  if (isset($all_versions[$version])) {
    //unset($all_versions[$version]);
    $all_versions[$version]['is_deleted'] = 'true';
    $_SESSION['i_is_version_removed'] = 1;
    update_user_meta($calc_user->ID, $key, $all_versions);
  } else {
    $_SESSION['i_is_version_removed'] = 0;
  }
}

add_action('init', 'cplat_remove_version');


function cplat_delete_account()
{
  if (!isset($_GET['deleteaccount'])) {
    return false;
  }

  if (!isset($_REQUEST['_wpnonce'])) {
    return false;
  }
  $nonce = $_REQUEST['_wpnonce'];

  if (is_user_logged_in()) {
    $calc_user = wp_get_current_user();
    if (!wp_verify_nonce($nonce, 'deleteuseronce' . $calc_user->ID)) {
      die('There was a problem delteting your account.');
    }
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    wp_delete_user($calc_user->ID);
    // remove_user_meta($calc_user->ID, 'user_financial_data');
    wp_redirect(home_url());
    exit;
  } else {
    return false;
  }
}

add_action('init', 'cplat_delete_account');


function cplat_change_pass()
{

  // TODO check for current password
  if (isset($_POST['action']) && $_POST['action'] === 'change_pass') {
    $user_id  = get_current_user_id();
    $password = sanitize_text_field($_POST['new_pass']);
    wp_set_password($password, $user_id);
  }
}

add_action('init', 'cplat_change_pass');


function get_total_funds($calc_user)
{

  if ($calc_user->investment_products === 'yes' && $calc_user->total_savings_and_investments > 0) {

    $ex_instruments_gia           = isset($calc_user->ex_instruments_gia) ? $calc_user->ex_instruments_gia : 0;
    $ex_instruments_isa           = isset($calc_user->ex_instruments_isa) ? $calc_user->ex_instruments_isa : 0;
    $ex_instruments_jisa          = isset($calc_user->ex_instruments_jisa) ? $calc_user->ex_instruments_jisa : 0;
    $ex_instruments_sipp          = isset($calc_user->ex_instruments_sipp) ? $calc_user->ex_instruments_sipp : 0;
    $ex_instruments_jsipp         = isset($calc_user->ex_instruments_jsipp) ? $calc_user->ex_instruments_jsipp : 0;
    $ex_instruments_onshore_bond  = isset($calc_user->ex_instruments_onshore_bond) ? $calc_user->ex_instruments_onshore_bond : 0;
    $ex_instruments_offshore_bond = isset($calc_user->ex_instruments_offshore_bond) ? $calc_user->ex_instruments_offshore_bond : 0;

    $funds_gia           = max($calc_user->funds_gia - $ex_instruments_gia, 0);
    $funds_isa           = max($calc_user->funds_isa - $ex_instruments_isa, 0);
    $funds_jisa          = max($calc_user->funds_jisa - $ex_instruments_jisa, 0);
    $funds_sipp          = max($calc_user->funds_sipp - $ex_instruments_sipp, 0);
    $funds_jsipp         = max($calc_user->funds_jsipp - $ex_instruments_jsipp, 0);
    $funds_onshore_bond  = max($calc_user->funds_onshore_bond - $ex_instruments_onshore_bond, 0);
    $funds_offshore_bond = max($calc_user->funds_offshore_bond - $ex_instruments_offshore_bond, 0);

    $total_funds = bcadd($funds_isa, $funds_gia, 2);
    $total_funds = bcadd($total_funds, $funds_jisa, 2);
    $total_funds = bcadd($total_funds, $funds_sipp, 2);
    $total_funds = bcadd($total_funds, $funds_jsipp, 2);
    $total_funds = bcadd($total_funds, $funds_onshore_bond, 2);
    $total_funds = bcadd($total_funds, $funds_offshore_bond, 2);
    $total_funds = ceil($total_funds);
  } else {
    $total_funds = max($calc_user->total_savings_and_investments - $calc_user->total_shares, 0);
  }
  $total_funds = 0;
  return $total_funds;
}

function get_total_shares($calc_user)
{

  if ($calc_user->total_shares > 0) {

    $total_shares = 0;
    $total_shares += $calc_user->ex_instruments_gia;
    $total_shares += $calc_user->ex_instruments_isa;
    $total_shares += $calc_user->ex_instruments_jisa;
    $total_shares += $calc_user->ex_instruments_sipp;
    $total_shares += $calc_user->ex_instruments_jsipp;
  } else {
    $total_shares = $calc_user->total_shares;
  }

  return $total_shares;
}

//RSPL Task#83
function get_total_cash($calc_user)
{

  if ($calc_user->total_shares > 0) {

    $total_cash = 0;
    $total_cash += $calc_user->funds_gia_cash;
    $total_cash += $calc_user->funds_isa_cash;
    $total_cash += $calc_user->funds_jisa_cash;
    $total_cash += $calc_user->funds_sipp_cash;
    $total_cash += $calc_user->funds_jsipp_cash;
  } else {
    $total_cash = $calc_user->total_savings_and_investments_cash;
  }

  return $total_cash;
}

function get_slider_interval($num)
{

  $numlength = strlen((string) $num);
  $interval  = 1;
  switch ($numlength) {
    case '1':
    case '2':
    case '3':
    case '4':
      $interval = 1000;
      break;
    case '5':
      $interval = 10000;
      break;
    case '6':
      $interval = 100000;
      break;
    case '7':
      $interval = 1000000;
      break;

    case '8':
      $interval = 10000000;
      break;

    case '9':
      $interval = 100000000;
      break;

    default:
      $interval = 1000;
      break;
  }

  return $interval;
}

function cplat_register_notification_email_fields()
{
  register_setting('general', 'cplat_notification_email', 'esc_attr');
  add_settings_field('cplat_notification_email', '<label for="cplat_notification_email">' . __('Platform Update Notification Email', 'cplat_notification_email') . '</label>', 'cplat_notification_email_html', 'general');
}

add_filter('admin_init', 'cplat_register_notification_email_fields');

function cplat_notification_email_html()
{
  $email = get_option('cplat_notification_email', '');
  echo '<input type="text" class="regular-text" id="cplat_notification_email" name="cplat_notification_email" value="' . $email . '" />';
}

function cplat_platform_update_notification($platform_name, $updated_by = null)
{

  $admin_email = get_option('cplat_notification_email');

  $to        = $admin_email;
  $from_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
  $headers   = sprintf('From: %s <%s>', $from_name, $admin_email) . "\r\n";
  $subject   = 'Platform ' . $platform_name . ' has been updated';

  // Email Invoice Template
  add_filter('wp_mail_content_type', 'set_html_content_type');

  $message = 'Platform ' . $platform_name . ' has been updated on ' . date('d M Y H:i');
  if ($updated_by !== null) {
    $message .= " by $updated_by ." . "\r\n\r\n";
  } else {
    $message .= '.' . "\r\n\r\n";
  }

  // Send email to user
  wp_mail($to, $subject, $message, $headers);
}


/*-----------------------------------------------------------------------------------*/
/*  Add admin columns for platform post type
/*-----------------------------------------------------------------------------------*/
/**
 * Add columns to list
 */
function platform_columns_head($defaults)
{
  $defaults['expiry_date'] = 'Expiry Date';

  return $defaults;
}

add_filter('manage_platform_posts_columns', 'platform_columns_head', 10);

/**h
 * Add new content to registered columns
 *
 * @param  string $column_name name of the column
 * @param  int $post_id id of post in currently processed row
 *
 * @return mixed            content displayed in currently processed row
 */
function platform_columns_content($column_name, $post_id)
{
  if ($column_name == 'expiry_date') {
    $platform_data = get_post_meta($post_id, 'platform_data', true);
    if (is_array($platform_data)) {
      $platform_data = array_reverse($platform_data, true);

      $today               = time();
      $selected_version_id = false;
      foreach ($platform_data as $key => $data) {
        $from = strtotime($data['data']['active_from']);
        $to   = strtotime($data['data']['active_to']);

        // Is on date range
        // if ( $from <= $today && $to >= $today) {
        // Is approved
        if (Calculator_Compare::STATUS_APPROVED === $data['status']) {
          $selected_version_id = $key;
          break;
        }
        // }
      }

      if (isset($platform_data[$selected_version_id]['data']['active_to'])) {
        $column_value = date('d M Y', strtotime($platform_data[$selected_version_id]['data']['active_to']));
      } else {
        $column_value = '-';
      }
    } else {
      $column_value = '-';
    }
    if ($to <= $today) {
      echo '<span class="platform-date-expired">' . $column_value . '</span>';
    } else {
      echo $column_value;
    }
  }

  if ($column_name == 'method') {

    $method = get_post_meta($post_id, '_cplat_calculation_method', true);
    preg_match_all('/\d+/', $method, $matches);
    echo isset($matches[0][0]) ? '<div style="text-align:center">' . esc_attr($matches[0][0]) . '</div>' : '';
  }
  if ($column_name == 'platform_type') {

    $platform_type = get_post_meta($post_id, '_cplat_inv_management_type', true);
    if (!empty($platform_type)) {
      echo esc_attr(strtoupper($platform_type));
    }
  }
}

add_action('manage_platform_posts_custom_column', 'platform_columns_content', 10, 2);

function reorder_project_columns($columns)
{

  $new_order = array();
  unset($columns['expiry_date']);
  foreach ($columns as $key => $title) {
    $new_order[$key] = $title;
  }
  $new_order['method']        = '<div style="text-align:center">Calculation Method</div>';
  $new_order['platform_type'] = 'Platform Type';
  $new_order['expiry_date']   = 'Expiry Date';

  return $new_order;
}

add_filter('manage_platform_posts_columns', 'reorder_project_columns', 30);

function ctp_platform_admin_css()
{
  echo '<style>
    .platform-date-expired {
      color: red;
    }
  </style>';
}

add_action('admin_head', 'ctp_platform_admin_css');

function ctp_order_users_by_date_registered($query)
{
  global $pagenow;
  if (!is_admin() || 'users.php' !== $pagenow) {
    return;
  }
  // RSPL Task#173
  // $query->query_orderby = 'ORDER BY user_registered DESC';
}

add_action('pre_user_query', 'ctp_order_users_by_date_registered');





function cplat_sanitize_number($arg)
{
  $num = str_replace(",", "", $arg);
  $num = str_replace("£", "", $num);
  $num = str_replace("$", "", $num);
  $num = str_replace("%", "", $num);
  $num = sanitize_text_field($num);
  if (!is_numeric($num)) {
    $num = 0;
  }

  return $num;
}

function cplat_curr_form($var)
{
  return "&pound;" . number_format($var, 2, ".", ",");
}

/**
 * formatted number for display
 *
 * @param float $var
 *
 * @return string
 */
function cplat_total_form($var)
{
  return "&pound;" . number_format($var, 0, ".", ",");
}

/**
 * return autofill data for calculator from ecc data
 *
 * @param array $data
 *
 * @return stdClass
 */
function get_ecc_data_for_platform($data)
{
  $plat_data                      = new stdClass();
  $plat_data->investments_today   = isset($data['investments_type']) ? $data['investments_type'] : 2;
  $plat_data->gender              = isset($data['gender']) ? $data['gender'] : null;
  $plat_data->age                 = isset($data['age']) ? $data['age'] : null;
  $plat_data->funds_gia           = isset($data['gia'][1]) ? cplat_sanitize_number($data['gia'][1]) : 0;
  $plat_data->funds_gia           += isset($data['gia'][2]) ? cplat_sanitize_number($data['gia'][2]) : 0;
  $plat_data->funds_isa           = isset($data['isa'][1]) ? cplat_sanitize_number($data['isa'][1]) : 0;
  $plat_data->funds_isa           += isset($data['isa'][2]) ? cplat_sanitize_number($data['isa'][2]) : 0;
  $plat_data->funds_sipp          = isset($data['sipp'][1]) ? cplat_sanitize_number($data['sipp'][1]) : 0;
  $plat_data->funds_sipp          += isset($data['sipp'][2]) ? cplat_sanitize_number($data['sipp'][2]) : 0;
  $plat_data->funds_jsipp         = isset($data['jsipp'][1]) ? cplat_sanitize_number($data['jsipp'][1]) : 0;
  $plat_data->funds_jsipp         += isset($data['jsipp'][2]) ? cplat_sanitize_number($data['jsipp'][2]) : 0;
  $plat_data->funds_jisa          = isset($data['jisa'][1]) ? cplat_sanitize_number($data['jisa'][1]) : 0;
  $plat_data->funds_jisa          += isset($data['jisa'][2]) ? cplat_sanitize_number($data['jisa'][2]) : 0;
  $plat_data->funds_onshore_bond  = isset($data['onshore_bond'][1]) ? cplat_sanitize_number($data['onshore_bond'][1]) : 0;
  $plat_data->funds_onshore_bond  += isset($data['onshore_bond'][2]) ? cplat_sanitize_number($data['onshore_bond'][2]) : 0;
  $plat_data->funds_offshore_bond = isset($data['offshore_bond'][1]) ? cplat_sanitize_number($data['offshore_bond'][1]) : 0;
  $plat_data->funds_offshore_bond += isset($data['offshore_bond'][2]) ? cplat_sanitize_number($data['offshore_bond'][2]) : 0;


  $plat_data->total_savings_and_investments = $plat_data->funds_gia + $plat_data->funds_isa + $plat_data->funds_sipp + $plat_data->funds_jsipp + $plat_data->funds_jisa + $plat_data->funds_onshore_bond + $plat_data->funds_offshore_bond;
  $plat_data->total_all                     = $plat_data->total_savings_and_investments;
  $plat_data->total_cash = 0;
  $plat_data->total_savings_and_investments_cash = 0;
  $plat_data->total_savings_and_investments_total                     = $plat_data->total_savings_and_investments;

  return $plat_data;
}

//RSPL Task#83
function cplat_email_result_ajax_callback()
{
  @session_start();
  $url = $_POST['current_URL'];
  $calculator_type = $_POST['calculator_type'];
  $url = add_query_arg('send_email_result', 'true', $url);
  $user_type = $_POST['user_type'];
  $name = sanitize_text_field($_POST['version']);
  $ret = [];
  if (is_user_logged_in()) {
    if (!empty($_POST['version'])) {
      $version = (int) $_POST['version'];
      if ($version === 0) {
        return false;
      }
      $currency = "£";

      if (is_user_logged_in()) {
        $calc_user = wp_get_current_user();
      } else {
        return false;
      }

      if (isset($calc_user->ID)) {
        //$all_versions = get_user_meta($calc_user->ID, 'user_financial_data', true);
        $all_versions = $_SESSION['user_financial_data'];
      }
      if (isset($all_versions[$version])) {
        $calc_user->data = (object)$all_versions[$version];
      }

      // RSPL Task#25
      global $wpdb;
      $linked_version = $version;
      $get_all_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);
      if (isset($get_all_linked_version[0]['linked_version']) && !empty($get_all_linked_version[0]['linked_version'])) {
        $t_all_linked_versions = explode(',', $get_all_linked_version[0]['linked_version']);
        $user_data = '';
        $combined_user_data = array();
        $i_linked_version_counter = 1;
        $t_ignorable_keys = array('investment_products', 'investment_stocks_shares', 'update', 'inv_management_type', 'planning_invest', 'planning_stocks_shares', 'gender', 'investments_today', 'investments_over', 'investment_frequency', 'point_in_time', 'order_by', 'order');
        foreach ($t_all_linked_versions as $t_all_linked_version) {
          $combined_user_data[$t_all_linked_version] = isset($all_versions[$t_all_linked_version]) ? $all_versions[$t_all_linked_version] : '';
          if ($i_linked_version_counter == 1) {
            $user_data = $combined_user_data[$t_all_linked_version];
          } else {
            foreach ($combined_user_data[$t_all_linked_version] as $s_key => $s_val) {
              if (!in_array($s_key, $t_ignorable_keys)) {
                $user_data[$s_key] = $user_data[$s_key] + $combined_user_data[$t_all_linked_version][$s_key];
              } else {
                if (isset($combined_user_data[$t_all_linked_version][$s_key]) && !empty($combined_user_data[$t_all_linked_version][$s_key])) {
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
        $all_versions[$version] = $user_data;
        $calc_user->data = (object)$all_versions[$version];
      } else {
        $user_data = isset($all_versions[$version]) ? $all_versions[$version] : false;
        if (in_array('adviser', (array) $calc_user->roles)) {
          $user_data['age'] = 55;
          // $user_data['inv_management_type'] = 'myself';
          $user_data['inv_management_type'] = 'adviser';
          $user_data['investment_products'] = 'yes';
        }
      }

      if ($all_versions[$version]) {
        $platforms_data = cplat_get_platform_list($all_versions[$version], $calc_user->ID);
        $platforms = $platforms_data['list'];
        $results_count = $platforms_data['count'];
      } else {
        $platforms = array();
        $results_count = 0;
      }
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
      } else if ($over_years === 'in_x_years') {
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
      include 'templates/email.php';
      $message = ob_get_clean();

      // Email vars
      $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
      $admin_email = get_option('admin_email');

      $headers = 'From: Compare The Platform <no-reply@comparetheplatform.com>' . "\r\n";
      $user = get_current_user_id();
      $user = get_userdata($user);
      $subject = 'comparetheplatform.com results';

      add_filter('wp_mail_content_type', 'set_html_content_type');
      $result = wp_mail($user->user_email, $subject, $message);
      remove_filter('wp_mail_content_type', 'set_html_content_type');
      if ($result) {
        $ret['msg'] = 'ok';
        $ret['email_msg'] = 'Your results have been sent to ' . $user->user_email;
      } else {
        $ret['msg'] = 'Notok';
        $ret['email_msg'] = 'Sending result to email ' . $user->user_email . ' failed!';
      }
      echo json_encode($ret);
      die;
    }
  }else{
    $ret['is_login'] = false;
    $ret['login_url'] = site_url('/register');
    $ret['is_user_type'] = $user_type;
    $ret['http_redirect_URL'] = $url;
    $_SESSION['is_user_type'] = $user_type;
    $_SESSION['http_redirect_URL'] = $url;
    echo json_encode($ret);
    die;
  }
}

add_action('wp_ajax_cplat_email_result_ajax', 'cplat_email_result_ajax_callback');
add_action('wp_ajax_nopriv_cplat_email_result_ajax', 'cplat_email_result_ajax_callback');

//RSPL Task#83
function save_search_results_ajax_callback()
{
  @session_start();
  $url = $_POST['current_URL'];
  $url = add_query_arg('save_result', 'true', $url);
  $user_type = $_POST['user_type'];
  $name = sanitize_text_field($_POST['version_name']);
  $ret = [];
  if (is_user_logged_in()) {
    if (!empty($_POST['version'])) {
      $version = (int) $_POST['version'];
      if ($version === 0) {
        return false;
      }
      $user = wp_get_current_user();
      // Update version
      $saved_data = get_user_meta( $user->ID, 'user_financial_data', true );
      if (isset($saved_data[$version])) {
        $saved_data[$version]['version_name'] = $name;
        $saved_data[$version]['calculator_type'] = $_POST['calculator_type'];
        $saved_data[$version]['version'] = $version;
        
        if ($_POST['updated'] == 1) {
          //RSPL TASK#22
          $saved_data[$version]['total_savings_and_investments']  = intval($_POST['total_all']);
          //RSPL Task#37
          $saved_data[$version]['total_savings_and_investments_cash']  = intval($_POST['total_all_cash']);
          $saved_data[$version]['total_savings_and_investments_total']  = intval($_POST['total_all_total']);
          $saved_data[$version]['total_shares']                   = intval($_POST['total_shares']);
          $saved_data[$version]['investments_today']              = $_POST['investments_today'];
          $saved_data[$version]['investment_frequency']           = intval($_POST['nooftrades']);
          $saved_data[$version]['investment_frequency_funds']     = intval($_POST['investment_frequency_funds']);
          $saved_data[$version]['investment_frequency_ex_traded'] = intval($_POST['investment_frequency_ex_traded']);
          $saved_data[$version]['average_investment_funds']       = intval($_POST['average_investment_funds']);
          $saved_data[$version]['average_investment_ex_traded']   = intval($_POST['average_investment_ex_traded']);
          $saved_data[$version]['investments_over']               = intval($_POST['investments_over']);
          $saved_data[$version]['investments_in_x_years']         = intval($_POST['investments_in_x_years']);
        }
      }else{
        $user_financial_ses = $_SESSION['user_financial_data'];

        //RSPL TASK#22
        $saved_data[$version]['total_savings_and_investments']  = intval($user_financial_ses['total_all']);
        //RSPL Task#37
        $saved_data[$version]['total_savings_and_investments_cash']  = intval($user_financial_ses['total_all_cash']);
        $saved_data[$version]['total_savings_and_investments_total']  = intval($user_financial_ses['total_all_total']);
        $saved_data[$version]['total_shares']                   = intval($user_financial_ses['total_shares']);
        $saved_data[$version]['investments_today']              = $user_financial_ses['investments_today'];
        $saved_data[$version]['investment_frequency']           = intval($user_financial_ses['nooftrades']);
        $saved_data[$version]['investment_frequency_funds']     = intval($user_financial_ses['investment_frequency_funds']);
        $saved_data[$version]['investment_frequency_ex_traded'] = intval($user_financial_ses['investment_frequency_ex_traded']);
        $saved_data[$version]['average_investment_funds']       = intval($user_financial_ses['average_investment_funds']);
        $saved_data[$version]['average_investment_ex_traded']   = intval($user_financial_ses['average_investment_ex_traded']);
        $saved_data[$version]['investments_over']               = intval($user_financial_ses['investments_over']);
        $saved_data[$version]['investments_in_x_years']         = intval($user_financial_ses['investments_in_x_years']);

        $saved_data[$version]['version_name'] = $name;
        $saved_data[$version]['calculator_type'] = $_POST['calculator_type'];
        $saved_data[$version]['version'] = $version;
      }
      $saved_data[$version]['is_deleted'] = 'false';
      $result = update_user_meta($user->ID, 'user_financial_data', $saved_data);
      if ($result) {
        $ret['msg'] = 'ok';
        $ret['email_msg'] = 'Your results have been saved!';
      } else {
        $ret['msg'] = 'Notok';
        $ret['email_msg'] = 'Error occured while storing your results!';
      }
      $_SESSION['is_user_type'] = '';
      $_SESSION['http_redirect_URL'] = '';
      $_SESSION['saved_version_name'] = '';
    }
  } else {
    $ret['is_login'] = false;
    $ret['login_url'] = site_url('/register');
    $ret['is_user_type'] = $user_type;
    $ret['http_redirect_URL'] = $url;
    $ret['saved_version_name'] = $name;
    $_SESSION['is_user_type'] = $user_type;
    $_SESSION['http_redirect_URL'] = $url;
    $_SESSION['saved_version_name'] = $name;
  }
  echo json_encode($ret);
  die;
}
add_action('wp_ajax_save_search_results_ajax', 'save_search_results_ajax_callback');
add_action('wp_ajax_nopriv_save_search_results_ajax', 'save_search_results_ajax_callback');

/********************************************************* 
 ********************************************************* 
 **************** imported from old theme **************** 
 *********************************************************
*********************************************************/
  
function cplt_mesc($amount){
  return isset($amount) && !empty($amount) ? '£' . esc_money($amount) : '£' . esc_money(0);
}