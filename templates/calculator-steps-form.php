<?php
@session_start();
$user_type = get_post_meta(get_the_ID(), '_calculator_page_user_type', true); //advisor
$main_version = '';
if (isset($_REQUEST['main_version']) && !empty($_REQUEST['main_version'])) {
    global $wpdb;
    $main_version = $_REQUEST['main_version'];
} else if (isset($_REQUEST['version']) && !empty($_REQUEST['version'])) {
    global $wpdb;
    $linked_version = $_REQUEST['version'];
    $get_main_portfolios = $wpdb->get_results("SELECT main_version FROM linked_portfolio_tbl WHERE linked_version LIKE '%" . $linked_version . "%' LIMIT 1", ARRAY_A);
    if (isset($get_main_portfolios[0]['main_version']) && !empty($get_main_portfolios[0]['main_version'])) {
        $main_version = $get_main_portfolios[0]['main_version'];
    }
}
if (($step === 2 || $step === 4) && isset($main_version) && !empty($main_version)) {
    //if (is_user_logged_in() && ($step === 2 || $step === 4) && isset($main_version) && !empty($main_version)) {
    $i_portfolio_counter = 0;
    $all_linked_portfolios = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE main_version LIKE '" . $main_version . "' LIMIT 1", ARRAY_A);
    if (isset($all_linked_portfolios[0]['linked_version']) && !empty($all_linked_portfolios[0]['linked_version'])) {
        $individual_linked_portfolios = explode(',', $all_linked_portfolios[0]['linked_version']);
        echo '<div class="switch-platform portfolio-nav-tabs clearfix">';
        echo '<ul class="plat-tabs" style=" float: left; padding-left: 0px;">';
        foreach ($individual_linked_portfolios as $individual_linked_portfolio) {
            // print_r($all_linked_portfolio);
            $portfolio_version = $individual_linked_portfolio;
            $s_active_class = '';
            if ($i_portfolio_counter == 0) {
                // $s_display_text = 'Main Portfolio';
                $i_portfolio_counter++;
                $s_display_text = 'Portfolio ' . $i_portfolio_counter++;
            } else {
                $s_display_text = 'Portfolio ' . $i_portfolio_counter++;
            }
            if ($_GET['version'] === $portfolio_version) {
                $s_active_class = 'active';
            }
            if ($user_type == 'advisor') {
                $user_type_slug = 'platform-calculator';
            } else {
                $user_type_slug = 'platform-calculator-consumer';
            }
            echo '<li class="' . $s_active_class . '"><a href="' . get_home_url() . '/' . $user_type_slug . '/?step=2&main_version=' . $main_version . '&version=' . $portfolio_version . '">' . $s_display_text . '</a></li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}
// Create an object of current user.
$user = wp_get_current_user();
//Ticket#192 checklist 2 and 3 Start
$roles_arr = $user->roles;
//$roles_commas = implode(',', $roles_arr);
$roles_commas = ($user_type && $user_type == 'advisor' ? 'adviser' : 'subscriber');

if(!$calc_user->data) {
$calc_user->data = (object)(unserialize(stripslashes($_GET['htb_serialized_data'])));
}

$user_type = 'subscriber';

if ($user_type == 'advisor') {
    if ($calc_user->data->initial_advice_type == 'flat') {
        $initial_currency_symbol = '£';
        $initial_input_attr = '';
    } else {
        $initial_currency_symbol = '%';
        $initial_input_attr = 'min="0" max="10" step="any" ';
    }
    if ($calc_user->data->annual_advice_type == 'flat') {
        $annual_currency_symbol = '£';
        $annual_input_attr = '';
    } else {
        $annual_currency_symbol = '%';
        $annual_input_attr = 'min="0" max="10" step="any" ';
    }
}
if ( $user_type != 'advisor' ) {
    $result_page_page_note_acf = ctp_get_questions_option('result_page_page_consumer_note');
} else {
    $result_page_page_note_acf = ctp_get_questions_option('result_page_page_note');
}
//Ticket#192 checklist 2 and 3 End
?>
<?php
// Show steps on mobile

$step_current = isset($_GET['step']) ? intval($_GET['step']) : 1;
$step_count = 0;
if (strpos(esc_url(get_permalink()), 'platform-calculator-consumer') > 0 || strpos(esc_url(get_permalink()), 'platform-calculator') > 0) {
$step_count = 3;
};
if (strpos(esc_url(get_permalink()), 'simplified-calculator') > 0 || strpos(esc_url(get_permalink()), 'robo-calculator') > 0 || strpos(esc_url(get_permalink()), 'exit-charge-calculator') > 0) {
$step_count = 2;
};

?>
<?php
$user_type = 'subscriber';
if ($user_type == 'subscriber' && $step != 4) {
    //echo do_shortcode('[ctp-steps-advertisement]'); // #326
}
$lable_10_text = $help_10_text = $lable_11_text = $help_11_text = '';
if ( $user_type == 'subscriber' ) {
    $lable_1_text = ctp_get_questions_option('question_1_label_d2c');
    $help_1_text = ctp_get_questions_option('question_1_tooltip_d2c');
    $lable_2_text = ctp_get_questions_option('question_2_label_d2c');
    $help_2_text = ctp_get_questions_option('question_2_tooltip_d2c');
    $lable_3_text = ctp_get_questions_option('question_3_label_d2c');
    $help_3_text = ctp_get_questions_option('question_3_tooltip_d2c');
    $lable_4_text = ctp_get_questions_option('question_4_label_d2c');
    $help_4_text = ctp_get_questions_option('question_4_tooltip_d2c');
    $lable_5_text = ctp_get_questions_option('question_5_label_d2c');
    $help_5_text = ctp_get_questions_option('question_5_tooltip_d2c');
    $lable_5a_text = ctp_get_questions_option('question_5a_label_d2c');
    $help_5a_text = ctp_get_questions_option('question_5a_tooltip_d2c');
    $lable_5b_text = ctp_get_questions_option('question_5b_label_d2c');
    $help_5b_text = ctp_get_questions_option('question_5b_tooltip_d2c');

    $lable_6_text = ctp_get_questions_option('question_6_label_d2c');
    $help_6_text = ctp_get_questions_option('question_6_tooltip_d2c');

    $lable_7_text = ctp_get_questions_option('question_7_label_d2c');
    $lable_8_text = ctp_get_questions_option('question_8_label_d2c');

    $lable_9_text = ctp_get_questions_option('question_9_label_d2c');

    $lable_9a_text = ctp_get_questions_option('question_9a_label_d2c');
    $help_9a_text = ctp_get_questions_option('question_9a_tooltip_d2c');
    $lable_9b_text = ctp_get_questions_option('question_9b_label_d2c');
    $help_9b_text = ctp_get_questions_option('question_9b_tooltip_d2c');
    $lable_9c_text = ctp_get_questions_option('question_9c_label_d2c');
    $help_9c_text = ctp_get_questions_option('question_9c_tooltip_d2c');

    $lable_10_text = ctp_get_questions_option('question_10_label_d2c');
    $help_10_text = ctp_get_questions_option('question_10_tooltip_d2c');
    $lable_11_text = ctp_get_questions_option('question_11_label_d2c');
    $help_11_text = ctp_get_questions_option('question_11_tooltip_d2c');
} else {
    $lable_1_text = ctp_get_questions_option('question_1_label');
    $help_1_text = ctp_get_questions_option('question_1_help');
    $lable_2_text = ctp_get_questions_option('question_2_label');
    $help_2_text = ctp_get_questions_option('question_2_help');
    $lable_3_text = ctp_get_questions_option('question_3_label');
    $help_3_text = ctp_get_questions_option('question_3_help');
    $lable_4_text = ctp_get_questions_option('question_4_label');
    $help_4_text = ctp_get_questions_option('question_4_help');
    $lable_5_text = ctp_get_questions_option('question_5_label');
    $help_5_text = ctp_get_questions_option('question_5_help');
    $lable_5a_text = ctp_get_questions_option('question_5a_label');
    $help_5a_text = ctp_get_questions_option('question_5a_help');
    $lable_5b_text = ctp_get_questions_option('question_5b_label');
    $help_5b_text = ctp_get_questions_option('question_5b_help');

    $lable_6_text = ctp_get_questions_option('question_7_label');
    $help_6_text = ctp_get_questions_option('question_7_help');

    $lable_7_text = ctp_get_questions_option('question_8_label');
    $lable_8_text = ctp_get_questions_option('question_9_label');

    $lable_9_text = ctp_get_questions_option('question_12_label');

    $lable_9a_text = ctp_get_questions_option('question_12A_label');
    $help_9a_text = ctp_get_questions_option('question_12A_help');

    $lable_9b_text = ctp_get_questions_option('question_12B_label');
    $help_9b_text = ctp_get_questions_option('question_12B_help');

    $lable_9c_text = ctp_get_questions_option('question_12C_label');
    $help_9c_text = ctp_get_questions_option('question_12C_help');
}
?>
<div class="cplat-submit-form-container section-padding-bottom">
    <?php /*
    <ul class="cplat-steps clearfix">
        <?php /*<li class="<?php echo $step === 1 || $step === 'login' ? 'active' : ''; ?> arrow-step-start"><?php _e('Step One', 'cplat'); ?></li>
        <li class="<?php echo $step === 2 ? 'active' : ''; ?> arrow-step"><?php _e('Step Two', 'cplat'); ?></li>
        <li class="<?php echo $step === 3 ? 'active' : ''; ?> arrow-step"><?php _e('Step Three', 'cplat'); ?></li>
        <li class="<?php echo $step === 4 ? 'active' : ''; ?> arrow-step-end"><?php _e('Step Four', 'cplat'); ?></li> ?>

        <li class="<?php echo $step === 2 || $step === 'login' ? 'active' : ''; ?> arrow-step-start"><?php _e('Step One', 'cplat'); ?></li>
        <li class="<?php echo $step === 3 ? 'active' : ''; ?> arrow-step"><?php _e('Step Two', 'cplat'); ?></li>
        <li class="<?php echo $step === 4 ? 'active' : ''; ?> arrow-step-end"><?php _e('Step Three', 'cplat'); ?></li>
    </ul>

    <ul class="cplat-steps-mobile clearfix">
        <?php /*<li class="<?php echo $step === 1 || $step === 'login' ? 'active' : ''; ?> arrow-step-start"><?php _e('One', 'cplat'); ?></li>
        <li class="<?php echo $step === 2 ? 'active' : ''; ?> arrow-step"><?php _e('Two', 'cplat'); ?></li>
        <li class="<?php echo $step === 3 ? 'active' : ''; ?> arrow-step"><?php _e('Three', 'cplat'); ?></li>
        <li class="<?php echo $step === 4 ? 'active' : ''; ?> arrow-step-end"><?php _e('Four', 'cplat'); ?></li> ?>

        <li class="<?php echo $step === 2 || $step === 'login' ? 'active' : ''; ?> arrow-step-start"><?php _e('One', 'cplat'); ?></li>
        <li class="<?php echo $step === 3 ? 'active' : ''; ?> arrow-step"><?php _e('Two', 'cplat'); ?></li>
        <li class="<?php echo $step === 4 ? 'active' : ''; ?> arrow-step-end"><?php _e('Three', 'cplat'); ?></li>

    </ul>
    */ ?>
    <?php if ($step === 1) : ?>
        <div class="logme-container">
            <h2><?php _e('Already Registered, Login', 'cplat') ?></h2>
            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Log In'))); ?>" class="logme-btn"><?php _e('Log Me In', 'cplat') ?></a>
        </div>
        <div class="register-container">
            <h2><?php _e('First Time? Tell Us A Little More', 'cplat') ?></h2>

            <?php
            if (class_exists('Ctp_Theme_My_Login')) {
                echo do_shortcode('[ctp-theme-my-login default_action="register"]');
            } else {
                echo "required plugin Theme My Login is not activated or installed";
            }
            ?>
        </div>
    <?php endif; ?>
    <?php if ($step === 2) : ?>


        <!-- Begin : Ticket#218 && Ticket#220 -->
        <?php
        $s_hide_investment_products = $s_hide_investment_shares =  $s_hide_investment_shares_que = "";
        if ($calc_user->data->investment_products == 'no') {
            $s_hide_investment_shares = $s_hide_investment_shares_que = $s_hide_investment_products = "style='display: none;'";
        }
        if ($calc_user->data->investment_products == 'yes' && $calc_user->data->investment_stocks_shares == 'no') {
            $s_hide_investment_products = $s_hide_investment_shares_que =  "";
            $s_hide_investment_shares = "style='display: none;'";
        }
        ?>
        <!-- End : Ticket#218 && Ticket#220 -->
        <!--RSPL TASK#21-->
        <form method="post" action="" id="stepsform" class="stepsform2_container calculator-form" autocomplete="on">
            <input type="hidden" value="<?php echo $user_type; ?>" name="user_type" id="user_type" />
            <input type="hidden" value="<?php echo $user_type; ?>" name="roles_commas" id="roles_commas" />
            <input type="hidden" value="4" name="growth_rate" id="growth_rate" />
            <?php if( $user_type == 'advisor' ){ 
                $utm_source = '';
                if( isset($_REQUEST['utm_source']) ){
                    $utm_source = $_REQUEST['utm_source'];
                }
                if( isset($calc_user->data->utm_source) ){
                    $utm_source = $calc_user->data->utm_source;
                }
                ?>
                <input type="hidden" value="<?php echo $utm_source; ?>" name="utm_source" id="utm_source" />
            <?php } ?>
            <div class="step-2">
                <h1 class="heading-3"><?php _e('Investment platform comparison tool', 'cplat'); ?></h1>


                <!-- STEPS -->
                <div class="steps--mobile">
                  <div>
                    <?php
                    if ($step_count && $step_count > 0) {
                      $i = 1;
                      if ($_GET['step'] == 'results') {
                        $step = $step_count;
                      }
                      echo "<ul class='calculator-header__steps'>";
                      while ($i < ($step_count + 1)) {
                        $class = '';
                        if (strpos(esc_url(get_permalink()), 'platform-calculator-consumer') > 0 || strpos(esc_url(get_permalink()), 'platform-calculator') > 0) {
                          if ($step > 1) {
                            if ($step == $i + 1) {
                              $class = 'class="active"';
                            }
                          } else {
                            if ($step == $i) {
                              $class = 'class="active"';
                            }
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        } else {
                          if ($step == $i) {
                            $class = 'class="active"';
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        }
                      }
                      echo "</ul>";
                    }
                    ?>
                  </div>
                </div>

                <?php
                $second_que_style = "";
                $second_que_number = 2;
                $third_que_number = 3;
                $third_que_label = "If you know, please tell us how much you have in each of the following:";
                if ($user_type == 'advisor') {
                    //$second_que_style = "display: none;";
                    $third_que_label = "How much do you have in the following products and wappers?";
                    $third_que_number = 2;
                }
                ?>
                <div class="calculator-form__question" style="position: relative; <?php echo $second_que_style; ?>">
                    <div class="col-lg-12 form-header__wrap">
                        <span class="form-header label question-label-<?php echo $second_que_number; ?>">
                            <?php echo $lable_2_text; ?></span>
                        <span class="help hint--top investment_products-hint-mobile" data-hint="<?php echo $help_2_text; ?>">?<div class="help-popup"><?php echo $help_2_text; ?></div></span>
                    </div>
                    <!--                    <div class="col-lg-1"></div>-->
                    <div class="col-lg-12" style="margin-top: -6px">
                        <?php
                        if ($user_type == 'advisor') {
                        ?>
                            <input type="radio" name="investment_products" id="investment_products_yes" <?php echo isset($calc_user->data->investment_products) ? checked($calc_user->data->investment_products, 'yes') : 'checked'; ?> value="yes" style=""><label for="investment_products_yes" style=""><span><span></span></span><?php _e('Yes', 'cplat'); ?>
                            <?php /*<input type="radio" name="investment_products" id="investment_products_yes" <?php echo isset($calc_user->data->investment_products) ? checked($calc_user->data->investment_products, 'yes') : 'checked'; ?> value="yes" style="opacity: 0 !important; visibility: hidden !important; pointer-events: none !important; cursor: not-allowed !important; display: none !important;"><label for="investment_products_yes" style="opacity: 0 !important; visibility: hidden !important; pointer-events: none !important; cursor: not-allowed !important; display: none !important;"><span><span></span></span><?php _e('Yes', 'cplat'); ?> */ ?>
                            </label>
                        <?php
                        } else {
                        ?>
                            <input type="radio" name="investment_products" id="investment_products_yes" <?php echo isset($calc_user->data->investment_products) ? checked($calc_user->data->investment_products, 'yes') : ''; ?> value="yes">
                            <label for="investment_products_yes" class="investment_products calculator-form__radio-button--label" ><span><span></span></span><?php _e('Yes', 'cplat'); ?>
                            </label>
                            <input type="radio" name="investment_products" id="investment_products_no" <?php echo isset($calc_user->data->investment_products) ? checked($calc_user->data->investment_products, 'no') : 'checked'; ?> value="no">
                            <label for="investment_products_no" class="investment_products calculator-form__radio-button--label" ><span><span></span></span><?php _e('No', 'cplat'); ?>
                            </label>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="calculator-form__question <?php echo $user_type == 'advisor' ? '' : 'hide'; ?>" id="investment-products" <?php echo $s_hide_investment_products; ?>>
                    <div class="row">
                        <div class="col-lg-12">
                            <span class="form-header label question-label-<?php echo $third_que_number; ?>">
                                <?php echo $lable_3_text; ?>
                            </span>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header mt-3" for="funds_isa"><?php _e('ISAs', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_isa field-label">Investment</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_isa" id="funds_isa" value="<?php echo !empty($calc_user->data->funds_isa) ? intval($calc_user->data->funds_isa) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_isa_cash">Cash</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_isa_cash" id="funds_isa_cash" value="<?php echo !empty($calc_user->data->funds_isa_cash) ? intval($calc_user->data->funds_isa_cash) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_isa_total">Total</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_isa_total" id="funds_isa_total" value="<?php echo !empty($calc_user->data->funds_isa_total) ? intval($calc_user->data->funds_isa_total) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ticket#307 Start -->
                    <?php if ($user_type != 'advisor') { ?>
                        <div class="row indented">
                            <div class="col-12">
                                <label class="form-header" for="funds_lifetime_isa"><?php _e('Lifetime ISAs', 'cplat'); ?></label>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                            <label for="funds_lifetime_isa field-label">Investment</label>
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_lifetime_isa" id="funds_lifetime_isa" value="<?php echo !empty($calc_user->data->funds_lifetime_isa) ? intval($calc_user->data->funds_lifetime_isa) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                            <label for="funds_lifetime_isa_cash">Cash</label>
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_lifetime_isa_cash" id="funds_lifetime_isa_cash" value="<?php echo !empty($calc_user->data->funds_lifetime_isa_cash) ? intval($calc_user->data->funds_lifetime_isa_cash) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                            <label for="funds_lifetime_isa_total">Total</label>
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_lifetime_isa_total" id="funds_lifetime_isa_total" value="<?php echo !empty($calc_user->data->funds_lifetime_isa_total) ? intval($calc_user->data->funds_lifetime_isa_total) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="junior-isa"><?php _e('Junior ISAs', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_jisa field-label">Investment</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jisa" id="junior-isa" value="<?php echo !empty($calc_user->data->funds_jisa) ? intval($calc_user->data->funds_jisa) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_jisa_cash">Cash</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jisa_cash" id="junior-isa-cash" value="<?php echo !empty($calc_user->data->funds_jisa_cash) ? intval($calc_user->data->funds_jisa_cash) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_jisa_total">Total</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jisa_total" id="junior-isa-total" value="<?php echo !empty($calc_user->data->funds_jisa_total) ? intval($calc_user->data->funds_jisa_total) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="funds_sipp"><?php _e('Sipps', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_sipp field-label">Investment</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_sipp" id="funds_sipp" value="<?php echo !empty($calc_user->data->funds_sipp) ? intval($calc_user->data->funds_sipp) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_sipp_cash">Cash</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_sipp_cash" id="funds_sipp_cash" value="<?php echo !empty($calc_user->data->funds_sipp_cash) ? intval($calc_user->data->funds_sipp_cash) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_sipp_total">Total</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_sipp_total" id="funds_sipp_total" value="<?php echo !empty($calc_user->data->funds_sipp_total) ? intval($calc_user->data->funds_sipp_total) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="junior-sipp"><?php _e('Junior Sipps', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_jsipp field-label">Investment</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jsipp" id="junior-sipp" value="<?php echo !empty($calc_user->data->funds_jsipp) ? intval($calc_user->data->funds_jsipp) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_jsipp_cash">Cash</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jsipp_cash" id="junior-sipp_cash" value="<?php echo !empty($calc_user->data->funds_jsipp_cash) ? intval($calc_user->data->funds_jsipp_cash) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_jsipp_total">Total</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jsipp_total" id="junior-sipp_total" value="<?php echo !empty($calc_user->data->funds_jsipp_total) ? intval($calc_user->data->funds_jsipp_total) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="general-investments"><?php _e('General Investments', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_gia field-label">Investment</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_gia" id="general-investments" value="<?php echo !empty($calc_user->data->funds_gia) ? intval($calc_user->data->funds_gia) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_gia_cash">Cash</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_gia_cash" id="general-investments_cash" value="<?php echo !empty($calc_user->data->funds_gia_cash) ? intval($calc_user->data->funds_gia_cash) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for="funds_gia_total">Total</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_gia_total" id="general-investments_total" value="<?php echo !empty($calc_user->data->funds_gia_total) ? intval($calc_user->data->funds_gia_total) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ticket#307 Start -->
                    <?php
                    if ($user_type == 'advisor') { ?>
                        <div class="row indented">
                            <div class="col-12">
                                <label class="form-header" for="funds_onshore_bond"><?php _e('Onshore bonds', 'cplat'); ?></label>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <label for=" field-labelfunds_onshore_bond">Investment</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_onshore_bond" id="funds_onshore_bond" value="<?php echo !empty($calc_user->data->funds_onshore_bond) ? intval($calc_user->data->funds_onshore_bond) : ''; ?>">
                                    </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <label for="funds_onshore_bond_cash">Cash</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_onshore_bond_cash" id="funds_onshore_bond_cash" value="<?php echo !empty($calc_user->data->funds_onshore_bond_cash) ? intval($calc_user->data->funds_onshore_bond_cash) : ''; ?>">
                                    </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <label for="funds_onshore_bond_total">Total</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_onshore_bond_total" id="funds_onshore_bond_total" value="<?php echo !empty($calc_user->data->funds_onshore_bond_total) ? intval($calc_user->data->funds_onshore_bond_total) : ''; ?>">
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row indented">
                            <div class="col-12">
                                <label class="form-header" for="funds_offshore_bond"><?php _e('Offshore bonds', 'cplat'); ?></label>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <label for=" field-labelfunds_offshore_bond">Investment</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_offshore_bond" id="funds_offshore_bond" value="<?php echo !empty($calc_user->data->funds_offshore_bond) ? intval($calc_user->data->funds_offshore_bond) : ''; ?>">
                    </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <label for="funds_offshore_bond_cash">Cash</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Cash" class="cash_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_offshore_bond_cash" id="funds_offshore_bond_cash" value="<?php echo !empty($calc_user->data->funds_offshore_bond_cash) ? intval($calc_user->data->funds_offshore_bond_cash) : ''; ?>">
                    </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <label for="funds_offshore_bond_total">Total</label>
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input placeholder="Total" readonly class="total_calculation_cls numeric-input ctp-nav" type="number" pattern="\d*" name="funds_offshore_bond_total" id="funds_offshore_bond_total" value="<?php echo !empty($calc_user->data->funds_offshore_bond_total) ? intval($calc_user->data->funds_offshore_bond_total) : ''; ?>">
                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Ticket#307 end -->
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="total-all"><?php _e('Total', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for=" value field-label">Investment</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input readonly="readonly" type="text" class="numeric-input ctp-nav" name="total_all" id="total-all" value="<?php echo !empty($calc_user->data->total_all) ? intval($calc_user->data->total_all) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for=" value_cash">Cash</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input readonly="readonly" type="text" class="cash_calculation_cls_all numeric-input ctp-nav" name="total_all_cash" id="total-all-cash" value="<?php echo !empty($calc_user->data->total_all_cash) ? intval($calc_user->data->total_all_cash) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <label for=" value_total">Total</label>
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input readonly="readonly" type="text" class="total_calculation_cls_all numeric-input ctp-nav" name="total_all_total" id="total-all-total" value="<?php echo !empty($calc_user->data->total_all_total) ? intval($calc_user->data->total_all_total) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="calculator-form__question row q4" <?php echo $s_hide_investment_shares_que; ?>>
                    <div class="col-lg-12">
                        <div class="form-header__wrap">
                            <span class="form-header label question-label-<?php echo $third_que_number + 1; ?>">
                                <?php echo $lable_4_text; ?></span>
                        <span class="help hint--top" data-hint="<?php echo $help_4_text; ?>">?<div class="help-popup"><?php echo $help_4_text; ?></div></span>
                        </div>
                    </div>
                    <div class="col-lg-6" style="margin-top:-5px;">
                        <input type="radio" name="investment_stocks_shares" id="investment_stocks_shares_yes" <?php echo isset($calc_user->data->investment_stocks_shares) ? checked($calc_user->data->investment_stocks_shares, 'yes') : ''; ?> class="ctp-nav" value="yes"><label for="investment_stocks_shares_yes"><span><span></span></span><?php _e('Yes', 'cplat'); ?>
                        </label>
                        <input type="radio" name="investment_stocks_shares" id="investment_stocks_shares_no" <?php echo isset($calc_user->data->investment_stocks_shares) ? checked($calc_user->data->investment_stocks_shares, 'no') : 'checked'; ?> value="no"><label for="investment_stocks_shares_no" class="ctp-nav"><span><span></span></span><?php _e('No', 'cplat'); ?>
                        </label>
                    </div>
                </div>

                <div class="calculator-form__question" id="investment-shares" <?php echo $s_hide_investment_shares; ?>>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header mt-0" for="ex_instruments_isa"><?php _e('ISAs', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                </div>
                                <div class="col-lg-4">
                                </div> -->
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_isa" id="ex_instruments_isa" value="<?php echo !empty($calc_user->data->ex_instruments_isa) ? intval($calc_user->data->ex_instruments_isa) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ticket#307 Start -->
                    <?php if ($user_type != 'advisor') { ?>
                        <div class="row indented">
                            <div class="col-12">
                                <label class="form-header" for="ex_instruments_lifetime_isa"><?php _e('Lifetime ISAs', 'cplat'); ?></label>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <!-- <div class="col-lg-4">
                                    </div>
                                    <div class="col-lg-4">
                                    </div> -->
                                    <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_lifetime_isa" id="ex_instruments_lifetime_isa" value="<?php echo !empty($calc_user->data->ex_instruments_lifetime_isa) ? intval($calc_user->data->ex_instruments_lifetime_isa) : ''; ?>">
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="ex_instruments_jisa"><?php _e('Junior ISAs', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                </div>
                                <div class="col-lg-4">
                                </div> -->
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_jisa" id="ex_instruments_jisa" value="<?php echo !empty($calc_user->data->ex_instruments_jisa) ? intval($calc_user->data->ex_instruments_jisa) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="ex_instruments_sipp"><?php _e('Sipps', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                </div>
                                <div class="col-lg-4">
                                </div> -->
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_sipp" id="ex_instruments_sipp" value="<?php echo !empty($calc_user->data->ex_instruments_sipp) ? intval($calc_user->data->ex_instruments_sipp) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="ex_instruments_jsipp"><?php _e('Junior Sipps', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                </div>
                                <div class="col-lg-4">
                                </div> -->
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_jsipp" id="ex_instruments_jsipp" value="<?php echo !empty($calc_user->data->ex_instruments_jsipp) ? intval($calc_user->data->ex_instruments_jsipp) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="ex_instruments_gia"><?php _e('General Investments', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                </div>
                                <div class="col-lg-4">
                                </div> -->
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_gia" id="ex_instruments_gia" value="<?php echo !empty($calc_user->data->ex_instruments_gia) ? intval($calc_user->data->ex_instruments_gia) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ticket#307 Start -->
                    <?php if ($user_type == 'advisor') { ?>
                        <div class="row indented">
                            <div class="col-12">
                                <label class="form-header" for="ex_instruments_gia"><?php _e('Onshore bonds', 'cplat'); ?></label>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <!-- <div class="col-lg-4">
                                    </div>
                                    <div class="col-lg-4">
                                    </div> -->
                                    <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_onshore_bond" id="ex_instruments_onshore_bond" value="<?php echo !empty($calc_user->data->ex_instruments_onshore_bond) ? intval($calc_user->data->ex_instruments_onshore_bond) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row indented">
                            <div class="col-12">
                                <label class="form-header" for="ex_instruments_gia"><?php _e('Offshore bonds', 'cplat'); ?></label>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <!-- <div class="col-lg-4">
                                    </div>
                                    <div class="col-lg-4">
                                    </div> -->
                                    <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input class="calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="ex_instruments_offshore_bond" id="ex_instruments_offshore_bond" value="<?php echo !empty($calc_user->data->ex_instruments_offshore_bond) ? intval($calc_user->data->ex_instruments_offshore_bond) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Ticket#307 End -->
                    <div class="row indented">
                        <div class="col-12">
                            <label class="form-header" for="total-shares"><?php _e('Total Shares', 'cplat'); ?></label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                </div>
                                <div class="col-lg-4">
                                </div> -->
                                <div class="col-lg-4">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input readonly="readonly" type="text" class="numeric-input ctp-nav" name="total_shares" id="total-shares" value="<?php echo !empty($calc_user->data->total_shares) ? intval($calc_user->data->total_shares) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="calculator-form__question" style="position: relative;">
                    <div class="col-md-12">
                        <label class="form-header label question-label-1" for="total-in-investment-products">
                            <?php echo $lable_1_text; ?></label>
                    </div>
                    <!--                    <div class="col-lg-1"></div>-->
                    <div class="col-lg-8 col-md-6 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <label for="total_savings_and_investments">Investment</label>
                                    <input placeholder="Investment" class="input-with-hint numeric-input" type="number" pattern="\d*" name="total_savings_and_investments" id="total-in-investment-products" value="<?php echo isset($calc_user->data->total_savings_and_investments) ? intval($calc_user->data->total_savings_and_investments) : ''; ?>" autocomplete="on">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <label for="total_savings_and_investments_cash">Cash</label>
                                    <input placeholder="Cash" class="cash_total_savings_and_investments input-with-hint numeric-input" type="number" pattern="\d*" name="total_savings_and_investments_cash" id="total-in-investment-products-cash" value="<?php echo isset($calc_user->data->total_savings_and_investments_cash) ? intval($calc_user->data->total_savings_and_investments_cash) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <label for="total_savings_and_investments_total">Total</label>
                                    <input placeholder="Total" readonly class="total_total_savings_and_investments input-with-hint numeric-input" type="text" name="total_savings_and_investments_total" id="total-in-investment-products-total" value="<?php echo isset($calc_user->data->total_savings_and_investments_total) ? intval($calc_user->data->total_savings_and_investments_total) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php /*<span style="margin-top:9px;" class="help hint--top" data-hint="<?php echo $help_1_text; ?>">?</span> */?>
                </div>

                <div class="calculator-form__question">
                    <div class="row">
                        <div class="continue-to-right">
                            <input type="hidden" name="total_funds" value="" id="total-funds">
                            <input type="hidden" name="update" value="">
                            <input type="hidden" name="calculator_action" value="save_step">
                            <input type="hidden" name="step" value="3">
                            <input type="hidden" name="main_version" class="main_version_cls" value="<?php echo $main_version; ?>">
                            <input class="continue check-for-totals ctp-nav mt-5" type="submit" value="Continue">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($step === 3) : ?>
        <form method="post" action="" class="stepsform3_container calculator-form">
            <div class="step-3">
                <h1 class="heading-3"><?php _e('Investment platform comparison tool', 'cplat') ?></h1>



                <!-- STEPS -->
                <div class="steps--mobile">
                  <div>
                    <?php
                    if ($step_count && $step_count > 0) {
                      $i = 1;
                      if ($_GET['step'] == 'results') {
                        $step = $step_count;
                      }
                      echo "<ul class='calculator-header__steps'>";
                      while ($i < ($step_count + 1)) {
                        $class = '';
                        if (strpos(esc_url(get_permalink()), 'platform-calculator-consumer') > 0 || strpos(esc_url(get_permalink()), 'platform-calculator') > 0) {
                          if ($step > 1) {
                            if ($step == $i + 1) {
                              $class = 'class="active"';
                            }
                          } else {
                            if ($step == $i) {
                              $class = 'class="active"';
                            }
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        } else {
                          if ($step == $i) {
                            $class = 'class="active"';
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        }
                      }
                      echo "</ul>";
                    }
                    ?>
                  </div>
                </div>

                <?php
                $move_step = false;

                $errors = ctp_errors()->get_error_messages();
                /* <!-- Begin : Ticket#218 && Ticket#220 --> */
                // echo '<pre>';
                // print_r($calc_user->data);
                // echo '</pre>';
                // var_dump($calc_user->data->planning_invest);
                $s_hide_ex_traded = $s_hide_planning_invest = $s_hide_planning_stocks_shares = $s_hide_planning_stocks_que = "";
                $investment_frequency_lbl = 5;
                $planning_invest_lbl = 6;
                $recommended_lbl = 7;
                $ethical_investment_lbl =8;
                if ( $user_type == 'advisor' ) {
                    if (!$calc_user->data->planning_invest || $calc_user->data->planning_invest == 'no') {
                        $planning_invest_ex_lbl = 7;
                        $age_lbl = 8;
                        $linked_portfolio_lbl = 9;
                        $is_advisor_lbl = 10;
                        $adviser_charges_lbl = 11;
                        $inv_option_lbl = 12;
                        $growth_lbl = 13;
                    } else {
                        $planning_invest_ex_lbl = 8;
                        $age_lbl = 9;
                        $linked_portfolio_lbl = 10;
                        $is_advisor_lbl = 11;
                        $adviser_charges_lbl = 12;
                        $inv_option_lbl = 13;
                        $growth_lbl = 14;
                    }
                } else {
                    $planning_invest_ex_lbl = 6;
                    $recommended_lbl = 7;
                    $ethical_investment_lbl = 8;
                    $age_lbl = 9;
                    //$gender_lbl = 9;
                    $inv_type_lbl = 10;
                    $inv_option_lbl = 11;
                    $growth_lbl = 12;
                    if (!$calc_user->data->investment_products || $calc_user->data->investment_products == 'no') {
                        $investment_frequency_lbl = 3;
                        //$investment_frequency_ex_lbl = 4;
                        $planning_invest_lbl = 4;
                        if (!$calc_user->data->planning_invest || $calc_user->data->planning_invest == 'no') {
                            $age_lbl = 6;
                            //$gender_lbl = 7;
                            $inv_type_lbl = 7;
                            $inv_option_lbl = 8;
                            $growth_lbl = 9;
                        } else {
                            $planning_invest_ex_lbl = 6;
                            $age_lbl = 7;
                            //$gender_lbl = 8;
                            $inv_type_lbl = 8;
                            $inv_option_lbl = 9;
                            $growth_lbl = 10;
                        }
                        $s_hide_ex_traded = "style='display: none;'";
                    } else {
                        $age_lbl = 7;
                        //$gender_lbl = 8;
                        $inv_type_lbl = 8;
                        $inv_option_lbl = 9;
                        $growth_lbl = 10;
                    }
                }
                if ($calc_user->data->investment_stocks_shares == 'no') {
                    $s_hide_ex_traded = "style='display: none;'";
                }
                if ($calc_user->data->planning_invest == 'no') {
                    $s_hide_planning_invest = $s_hide_planning_stocks_shares = $s_hide_planning_stocks_que = "style='display: none;'";
                }
                if ($calc_user->data->planning_stocks_shares == 'no') {
                    $s_hide_planning_stocks_shares = "style='display: none;'";
                }
                $show_hide_plng = "style='display: none;'";
                if ($calc_user->data->planning_invest == 'yes') {
                    $show_hide_plng = "";
                }
                echo '<input type="hidden" name="investment_products_in_3" value="' . $calc_user->data->investment_products . '"/>';
                ?>
                <div class="calculator-form__question">
                    <div class="">
                        <div class="col-lg-12 mobile-label-pos form-header__wrap">
                            <label class="form-header label question-label-<?php echo $investment_frequency_lbl; ?>">
                                <?php echo $lable_5_text;  ?>
                            </label>
                        <span class="help hint--top investment_frequency-description" data-hint="<?php echo $lable_5_text; ?>">?<div class="help-popup"><?php echo $lable_5_text; ?></div></span>
                        </div>
                    </div>
                    <div class="row indented funds-avg-trades">
                        <div class="col-12 sm-full form-header__wrap">
                            <label class="form-header mt-0" for="num_of_fund_trades">Funds</label>
                            <span class="help hint--top" data-hint="<?php echo $help_5a_text; ?>">?<div class="help-popup"><?php echo $help_5a_text; ?></div></span>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 paddingZero-init">
                                <div class="pos-relative calculator-form__number-input--wrap no-currency">
                                    <label class="paddingZero">Number of trades</label>
                                    <input type="number" pattern="\d*" class="numeric-input ctp-nav" name="investment_frequency_funds" id="num_of_fund_trades" value="<?php echo !empty($calc_user->data->investment_frequency_funds) ? $calc_user->data->investment_frequency_funds : ''; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 avg-trade-funds calculator-form__number-input--wrap">
                                <label class="paddingZero">Average amount</label>
                                <div class="pos-relative">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input type="number" pattern="\d*" class="numeric-input ctp-nav" name="average_investment_funds" id="avg_amnt_of_fund_trades" value="<?php echo !empty($calc_user->data->average_investment_funds) ? intval($calc_user->data->average_investment_funds) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented ext-avg-trades" <?php echo $s_hide_ex_traded; ?>>
                        <div class="col-12 sm-full form-header__wrap">
                            <label class="form-header" for="num_of_fund_trades">Exchange-traded investments</label>
                            <span class="help hint--top" data-hint="<?php echo $help_5b_text; ?>">?<div class="help-popup"><?php echo $help_5b_text; ?></div></span>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <div class="pos-relative calculator-form__number-input--wrap no-currency">
                                    <label class="paddingZero">Number of trades</label>
                                    <input type="number" pattern="\d*" class="numeric-input ctp-nav" name="investment_frequency_ex_traded" id="num_of_ex_traded_trades" value="<?php echo !empty($calc_user->data->investment_frequency_ex_traded) ? $calc_user->data->investment_frequency_ex_traded : ''; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 avg-trade-ex-change calculator-form__number-input--wrap">
                                <label class="paddingZero">Average amount</label>
                                <div class="pos-relative">
                                    <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input type="number" pattern="\d*" class="numeric-input ctp-nav" name="average_investment_ex_traded" id="avg_amnt_of_ex_traded_trades" value="<?php echo !empty($calc_user->data->average_investment_ex_traded) ? intval($calc_user->data->average_investment_ex_traded) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: RSPL - Ticker#280 -->
                <div class="calculator-form__question">
                    <div class=" planning-inv-funds-que">
                        <div class="calc-form-title col-md-12 form-header__wrap">
                            <label class="form-header label question-label-<?php echo $planning_invest_lbl; ?>" for="total-in-investment-products"><?php echo $lable_6_text; ?></label>
                            <span class="help hint--top" data-hint="<?php echo $help_6_text; ?>">?<div class="help-popup"><?php echo $help_6_text; ?></div></span>
                        </div>
                        <!-- <div class="col-lg-1"></div> -->
                        <div class="col-lg-4 col-sm-12 planning-inv-div-radio">
                            <input type="radio" name="planning_invest" class="ctp-nav" id="planning_invest_yes" <?php echo isset($calc_user->data->planning_invest) ? checked($calc_user->data->planning_invest, 'yes') : ''; ?> value="yes"><label for="planning_invest_yes" style="margin-right: 17px;"><span><span></span></span><?php _e('Yes', 'ctp'); ?></label>
                            <input type="radio" name="planning_invest" class="ctp-nav" id="planning_invest_no" <?php echo !isset($calc_user->data->planning_invest) ? 'checked' : ''; ?> <?php echo isset($calc_user->data->planning_invest) ? checked($calc_user->data->planning_invest, 'no') : ''; ?> value="no"><label for="planning_invest_no"><span><span></span></span><?php _e('No', 'ctp'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="calculator-form__question show_hide_plng" <?php echo $show_hide_plng; ?>>
                        <span class="form-header show_hide_plng" <?php echo $show_hide_plng; ?>>If so, how much?</span>
                        <div id="planning-invest" <?php echo $s_hide_planning_invest; ?>>
                            <div class=" indented">
                                <div class="col-lg-4 col-sm-6">
                                    <label for="planning_isa"><?php _e('ISAs', 'ctp'); ?></label>
                                </div>
                                <div class="col-lg-8 col-sm-6">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Investment" type="number" pattern="\d*" class="calc-total-all-q3 calc_planning_funds_q7 numeric-input ctp-nav" name="planning_isa" id="planning_isa" value="<?php echo !empty($calc_user->data->planning_isa) ? intval($calc_user->data->planning_isa) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Cash" type="number" pattern="\d*" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" name="planning_isa_cash" id="planning_isa_cash" value="<?php echo !empty($calc_user->data->planning_isa_cash) ? intval($calc_user->data->planning_isa_cash) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Total" readonly type="number" pattern="\d*" class="total_calculation_cls_q3 numeric-input ctp-nav" name="planning_isa_total" id="planning_isa_total" value="<?php echo !empty($calc_user->data->planning_isa_total) ? intval($calc_user->data->planning_isa_total) : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Ticket#307 Start -->
                            <?php if ($user_type != 'advisor') { ?>
                                <div class=" indented">
                                    <div class="col-lg-4 col-sm-6">
                                        <label for="planning_lifetime_isa"><?php _e('Lifetime ISAs', 'ctp'); ?></label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Investment" type="number" pattern="\d*" class="calc-total-all-q3 calc_planning_funds_q7 numeric-input ctp-nav" name="planning_lifetime_isa" id="planning_lifetime_isa" value="<?php echo !empty($calc_user->data->planning_lifetime_isa) ? intval($calc_user->data->planning_lifetime_isa) : '' ?>">
                                            </div>
                                            </div>
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Cash" type="number" pattern="\d*" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" name="planning_lifetime_isa_cash" id="planning_lifetime_isa_cash" value="<?php echo !empty($calc_user->data->planning_lifetime_isa_cash) ? intval($calc_user->data->planning_lifetime_isa_cash) : '' ?>">
                                            </div>
                                            </div>
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Total" readonly type="number" pattern="\d*" class="total_calculation_cls_q3 numeric-input ctp-nav" name="planning_lifetime_isa_total" id="planning_lifetime_isa_total" value="<?php echo !empty($calc_user->data->planning_lifetime_isa_total) ? intval($calc_user->data->planning_lifetime_isa_total) : '' ?>">
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class=" indented">
                                <div class="col-lg-4 col-sm-6">
                                    <label for="planning-junior-isa"><?php _e('Junior ISAs', 'ctp'); ?></label>
                                </div>
                                <div class="col-lg-8 col-sm-6">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Investment" type="number" pattern="\d*" class="calc-total-all-q3 calc_planning_funds_q7 numeric-input ctp-nav" name="planning_jisa" id="planning-junior-isa" value="<?php echo !empty($calc_user->data->planning_jisa) ? intval($calc_user->data->planning_jisa) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Cash" type="number" pattern="\d*" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" name="planning_jisa_cash" id="planning-junior-isa-cash" value="<?php echo !empty($calc_user->data->planning_jisa_cash) ? intval($calc_user->data->planning_jisa_cash) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Total" readonly type="number" pattern="\d*" class="total_calculation_cls_q3 numeric-input ctp-nav" name="planning_jisa_total" id="planning-junior-isa-total" value="<?php echo !empty($calc_user->data->planning_jisa_total) ? intval($calc_user->data->planning_jisa_total) : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" indented">
                                <div class="col-lg-4 col-sm-6">
                                    <label for="planning-sipp"><?php _e('Sipps', 'ctp'); ?></label>
                                </div>
                                <div class="col-lg-8 col-sm-6">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Investment" type="number" pattern="\d*" class="calc-total-all-q3 calc_planning_funds_q7 numeric-input ctp-nav" name="planning_sipp" id="planning-sipp" value="<?php echo !empty($calc_user->data->planning_sipp) ? intval($calc_user->data->planning_sipp) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Cash" type="number" pattern="\d*" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" name="planning_sipp_cash" id="planning-sipp-cash" value="<?php echo !empty($calc_user->data->planning_sipp_cash) ? intval($calc_user->data->planning_sipp_cash) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Total" readonly type="number" pattern="\d*" class="total_calculation_cls_q3 numeric-input ctp-nav" name="planning_sipp_total" id="planning-sipp-total" value="<?php echo !empty($calc_user->data->planning_sipp_total) ? intval($calc_user->data->planning_sipp_total) : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" indented">
                                <div class="col-lg-4 col-sm-6">
                                    <label for="planning-junior-sipp"><?php _e('Junior Sipps', 'ctp'); ?></label>
                                </div>
                                <div class="col-lg-8 col-sm-6">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Investment" type="number" pattern="\d*" class="calc-total-all-q3 calc_planning_funds_q7 numeric-input ctp-nav" name="planning_jsipp" id="planning-junior-sipp" value="<?php echo !empty($calc_user->data->planning_jsipp) ? intval($calc_user->data->planning_jsipp) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Cash" type="number" pattern="\d*" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" name="planning_jsipp_cash" id="planning-junior-sipp-cash" value="<?php echo !empty($calc_user->data->planning_jsipp_cash) ? intval($calc_user->data->planning_jsipp_cash) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Total" readonly type="number" pattern="\d*" class="total_calculation_cls_q3 numeric-input ctp-nav" name="planning_jsipp_total" id="planning-junior-sipp-total" value="<?php echo !empty($calc_user->data->planning_jsipp_total) ? intval($calc_user->data->planning_jsipp_total) : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" indented">
                                <div class="col-lg-4 col-sm-6">
                                    <label for="planning-general-investments"><?php _e('General Investments', 'ctp'); ?></label>
                                </div>
                                <div class="col-lg-8 col-sm-6">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Investment" type="number" pattern="\d*" class="calc-total-all-q3 calc_planning_funds_q7 general-investments-ctp-cash numeric-input ctp-nav" name="planning_gia" id="planning-general-investments" value="<?php echo !empty($calc_user->data->planning_gia) ? intval($calc_user->data->planning_gia) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Cash" type="number" pattern="\d*" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" name="planning_gia_cash" id="planning-general-investments-cash" value="<?php echo !empty($calc_user->data->planning_gia_cash) ? intval($calc_user->data->planning_gia_cash) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Total" readonly type="number" pattern="\d*" class="total_calculation_cls_q3 numeric-input ctp-nav" name="planning_gia_total" id="planning-general-investments-total" value="<?php echo !empty($calc_user->data->planning_gia_total) ? intval($calc_user->data->planning_gia_total) : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Ticket#307 Start -->
                            <?php if ($user_type == 'advisor') { ?>
                                <div class=" indented">
                                    <div class="col-lg-4 col-sm-6">
                                        <label for="planning_onshore_bond"><?php _e('Onshore bonds', 'cplat'); ?></label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Investment" class="calc-total-all-q3 calc_planning_funds_q7 calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="planning_onshore_bond" id="planning_onshore_bond" value="<?php echo !empty($calc_user->data->planning_onshore_bond) ? intval($calc_user->data->planning_onshore_bond) : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Cash" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" type="number" pattern="\d*" name="planning_onshore_bond_cash" id="planning_onshore_bond_cash" value="<?php echo !empty($calc_user->data->planning_onshore_bond_cash) ? intval($calc_user->data->planning_onshore_bond_cash) : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Total" readonly class="total_calculation_cls_q3 numeric-input ctp-nav" type="number" pattern="\d*" name="planning_onshore_bond_total" id="planning_onshore_bond_total" value="<?php echo !empty($calc_user->data->planning_onshore_bond_total) ? intval($calc_user->data->planning_onshore_bond_total) : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" indented">
                                    <div class="col-lg-4 col-sm-6">
                                        <label for="ex_instruments_gia"><?php _e('Offshore bonds', 'cplat'); ?></label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Investment" class="calc-total-all-q3 calc_planning_funds_q7 calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="planning_offshore_bond" id="planning_offshore_bond" value="<?php echo !empty($calc_user->data->planning_offshore_bond) ? intval($calc_user->data->planning_offshore_bond) : ''; ?>">
                            </div>
                                            </div>
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Cash" class="cash_calculation_cls_q3 calc_planning_cash_q7 numeric-input ctp-nav" type="number" pattern="\d*" name="planning_offshore_bond_cash" id="planning_offshore_bond_cash" value="<?php echo !empty($calc_user->data->planning_offshore_bond_cash) ? intval($calc_user->data->planning_offshore_bond_cash) : ''; ?>">
                            </div>
                                            </div>
                                            <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                                <span class="currency"><?php echo $currency; ?></span>
                                                <input placeholder="Total" readonly class="total_calculation_cls_q3 numeric-input ctp-nav" type="number" pattern="\d*" name="planning_offshore_bond_total" id="planning_offshore_bond_total" value="<?php echo !empty($calc_user->data->planning_offshore_bond_total) ? intval($calc_user->data->planning_offshore_bond_total) : ''; ?>">
                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- Ticket#307 Start -->
                            <!-- Ticket#192 - CTP Changes Start  -->
                            <div class=" indented">
                                <div class="col-lg-4 col-sm-6">
                                    <label for="ex_instruments_gia"><?php _e('Total', 'cplat'); ?></label>
                                </div>
                                <div class="col-lg-8 col-sm-6">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input readonly placeholder="Investment" class="calc-total-all-q3 total_planning_funds_q7 calc-total-shares numeric-input ctp-nav" type="number" pattern="\d*" name="yearly_investment_funds" id="yearly_investment_funds" value="<?php echo !empty($calc_user->data->yearly_investment_funds) ? intval($calc_user->data->yearly_investment_funds) : ''; ?>">
                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input readonly placeholder="Cash" class="total_planning_cash_q7 numeric-input ctp-nav" type="number" pattern="\d*" name="yearly_investment_cash" id="yearly_investment_cash" value="<?php echo !empty($calc_user->data->yearly_investment_cash) ? intval($calc_user->data->yearly_investment_cash) : ''; ?>">
                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input placeholder="Total" readonly class="total_calculation_cls_q3 total_calculation_cls_q7 numeric-input ctp-nav" type="number" pattern="\d*" name="total_yearly_investment_value" id="total_yearly_investment_value" value="<?php echo !empty($calc_user->data->total_yearly_investment_value) ? intval($calc_user->data->total_yearly_investment_value) : ''; ?>">
                            </div>
                                            <input placeholder="Total" readonly class="final_total_planning_cls_q7 numeric-input ctp-nav" type="hidden" name="total_yearly_investment_total" id="total_yearly_investment_total" value="<?php echo !empty($calc_user->data->total_yearly_investment_total) ? intval($calc_user->data->total_yearly_investment_total) : ''; ?>">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Ticket#192 - CTP Changes End  -->
                        </div>
                    </div>
                <div class="calculator-form__question" <?php echo $s_hide_planning_stocks_que; ?>>
                    <div class=" q7" <?php echo $s_hide_planning_stocks_que; ?>>
                        <div class="col-12 calc-form-title">
                            <label class="label ps-0 form-header question-label-<?php echo $planning_invest_ex_lbl; ?>" for="total-in-investment-products"><?php echo $lable_7_text; ?></label>
                        </div>
                        <div class="col-lg-4 col-sm-12 planing-ext-cls" style="margin-top:-5px;">
                            <input type="radio" name="planning_stocks_shares" class="ctp-nav" id="planning_stocks_shares_yes" <?php echo isset($calc_user->data->planning_stocks_shares) ? checked($calc_user->data->planning_stocks_shares, 'yes') : ''; ?> value="yes"><label for="planning_stocks_shares_yes"><span><span></span></span><?php _e('Yes', 'ctp'); ?>
                            </label>
                            <input type="radio" name="planning_stocks_shares" class="ctp-nav" id="planning_stocks_shares_no" <?php echo isset($calc_user->data->planning_stocks_shares) ? checked($calc_user->data->planning_stocks_shares, 'no') : 'checked'; ?> value="no"><label for="planning_stocks_shares_no"><span><span></span></span><?php _e('No', 'ctp'); ?>
                            </label>
                        </div>
                    </div>
                    <div id="planning-stocks-shares" <?php echo $s_hide_planning_stocks_shares; ?>>
                        <div class="row indented">
                            <div class="col-12 form-header">
                                <label for="planning_isa"><?php _e('ISAs', 'ctp'); ?></label>
                            </div>
                            <div class="col-12 ps-0">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input type="number" pattern="\d*" class="numeric-input ctp-nav calc_planning_ex_q8" name="planning_ex_instruments_isa" id="planning_ex_instruments_isa" value="<?php echo !empty($calc_user->data->planning_ex_instruments_isa) ? intval($calc_user->data->planning_ex_instruments_isa) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Ticket#307 Start -->
                        <?php if ($user_type != 'advisor') { ?>
                            <div class="row indented">
                                <div class="col-12 form-header">
                                    <label for="planning_lifetime_isa"><?php _e('Lifetime ISAs', 'ctp'); ?></label>
                                </div>
                                <div class="col-12 ps-0">
                                    <div class="row">
                                        <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input type="number" pattern="\d*" class="numeric-input ctp-nav calc_planning_ex_q8" name="planning_ex_instruments_lifetime_isa" id="planning_ex_instruments_lifetime_isa" value="<?php echo !empty($calc_user->data->planning_ex_instruments_lifetime_isa) ? intval($calc_user->data->planning_ex_instruments_lifetime_isa) : '' ?>">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row indented">
                            <div class="col-12 form-header">
                                <label for="planning_ex_instruments-junior-isa"><?php _e('Junior ISAs', 'ctp'); ?></label>
                            </div>
                            <div class="col-12 ps-0">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input type="number" pattern="\d*" class="numeric-input ctp-nav calc_planning_ex_q8" name="planning_ex_instruments_jisa" id="planning_ex_instruments-junior-isa" value="<?php echo !empty($calc_user->data->planning_ex_instruments_jisa) ? intval($calc_user->data->planning_ex_instruments_jisa) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row indented">
                            <div class="col-12 form-header">
                                <label for="planning_ex_instruments-sipp"><?php _e('Sipps', 'ctp'); ?></label>
                            </div>
                            <div class="col-12 ps-0">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input type="number" pattern="\d*" class="numeric-input ctp-nav calc_planning_ex_q8" name="planning_ex_instruments_sipp" id="planning_ex_instruments-sipp" value="<?php echo !empty($calc_user->data->planning_ex_instruments_sipp) ? intval($calc_user->data->planning_ex_instruments_sipp) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row indented">
                            <div class="col-12 form-header">
                                <label for="planning_ex_instruments-junior-sipp"><?php _e('Junior Sipps', 'ctp'); ?></label>
                            </div>
                            <div class="col-12 ps-0">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input type="number" pattern="\d*" class="numeric-input ctp-nav calc_planning_ex_q8" name="planning_ex_instruments_jsipp" id="planning_ex_instruments-junior-sipp" value="<?php echo !empty($calc_user->data->planning_ex_instruments_jsipp) ? intval($calc_user->data->planning_ex_instruments_jsipp) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row indented">
                            <div class="col-12 form-header">
                                <label for="planning-general-investments"><?php _e('General Investments', 'ctp'); ?></label>
                            </div>
                            <div class="col-12 ps-0">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input type="number" pattern="\d*" class="numeric-input ctp-nav calc_planning_ex_q8" name="planning_ex_instruments_gia" id="planning-ex-instruments-general-investments" value="<?php echo !empty($calc_user->data->planning_ex_instruments_gia) ? intval($calc_user->data->planning_ex_instruments_gia) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Ticket#307 Start -->
                        <?php if ($user_type == 'advisor') { ?>
                            <div class="row indented">
                                <div class="col-12 form-header">
                                    <label for="planning_ex_instruments_onshore_bond"><?php _e('Onshore bonds', 'cplat'); ?></label>
                                </div>
                                <div class="col-12 ps-0">
                                    <div class="row">
                                        <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input class="calc-total-shares numeric-input ctp-nav calc_planning_ex_q8" type="number" pattern="\d*" name="planning_ex_instruments_onshore_bond" id="planning_ex_instruments_onshore_bond" value="<?php echo !empty($calc_user->data->planning_ex_instruments_onshore_bond) ? intval($calc_user->data->planning_ex_instruments_onshore_bond) : ''; ?>">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row indented">
                                <div class="col-12 form-header">
                                    <label for="planning_ex_instruments_offshore_bond"><?php _e('Offshore bonds', 'cplat'); ?></label>
                                </div>
                                <div class="col-12 ps-0">
                                    <div class="">
                                        <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input class="calc-total-shares numeric-input ctp-nav calc_planning_ex_q8" type="number" pattern="\d*" name="planning_ex_instruments_offshore_bond" id="planning_ex_instruments_offshore_bond" value="<?php echo !empty($calc_user->data->planning_ex_instruments_offshore_bond) ? intval($calc_user->data->planning_ex_instruments_offshore_bond) : ''; ?>">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Ticket#307 End -->
                        <!-- RSPL TASK#192 - CTP Development Changes Start -->
                        <div class="row indented">
                            <div class="col-12 form-header">
                                <label for="yearly_investment_ex"><?php _e('Total', 'cplat'); ?></label>
                            </div>
                            <div class="col-12 ps-0">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="calculator-form__number-input--wrap">
                                        <span class="currency"><?php echo $currency; ?></span>
                                        <input class="calc-total-shares total_planning_ex numeric-input ctp-nav" type="number" readonly name="yearly_investment_ex" id="total_planning_ex" value="<?php echo !empty($calc_user->data->yearly_investment_ex) ? intval($calc_user->data->yearly_investment_ex) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- RSPL TASK#192 - CTP Development Changes End -->
                    </div>
                </div>
                <?php if ( $user_type != 'advisor' ) {  ?>
                <div class="calculator-form__question">
                    <div class=" ethical_investment">
                        <div class="calc-form-title col-md-12 form-header__wrap">
                            <label class="form-header label question-label-<?php echo $ethical_investment_lbl; ?>" for="ethical_investment-products"><?php echo $lable_11_text; ?></label>
                            <span class="help hint--top" data-hint="<?php echo $help_11_text; ?>">?<div class="help-popup"><?php echo $help_11_text; ?></div></span>
                        </div>
                        <!-- <div class="col-lg-1"></div> -->
                        <div class="col-lg-4 col-sm-12 radio-common-style">
                            <input type="radio" name="ethical_investment" id="ethical_investment_yes"  <?php echo isset($calc_user->data->ethical_investment) ? checked($calc_user->data->ethical_investment, '1') : ''; ?> value="1"><label style="margin-right: 17px;" for="ethical_investment_yes" class="ethical_investment_yes"><span><span></span></span>Yes </label>
                            <input type="radio" name="ethical_investment" id="ethical_investment_no" <?php echo !isset($calc_user->data->ethical_investment) ? 'checked' : ''; ?> <?php echo isset($calc_user->data->ethical_investment) ? checked($calc_user->data->ethical_investment, '0') : ''; ?> value="0"><label for="ethical_investment_no" class="ethical_investment_no"><span><span></span></span>No </label>
                        </div>
                    </div>
                </div>
                <div class="calculator-form__question">
                    <div class=" platform-recommended-que">
                        <div class="calc-form-title col-md-12 form-header__wrap">
                            <label class="form-header label question-label-<?php echo $recommended_lbl; ?>" for="recommended_lbl-products"><?php echo $lable_10_text; ?></label>
                            <span class="help hint--top" data-hint="<?php echo $help_10_text; ?>">?<div class="help-popup"><?php echo $help_10_text; ?></div></span>
                        </div>
                        <!-- <div class="col-lg-1"></div> -->
                        <div class="col-lg-4 col-sm-12 radio-common-style">
                            <input type="radio" name="recommended_portfolio" id="recommended_portfolio_yes" <?php echo !isset($calc_user->data->recommended_portfolio) ? 'checked' : ''; ?> <?php echo isset($calc_user->data->recommended_portfolio) ? checked($calc_user->data->recommended_portfolio, '1') : ''; ?> value="1"><label style="margin-right: 17px;" for="recommended_portfolio_yes" class="recommended_portfolio_yes"><span><span></span></span>Yes </label>
                            <input type="radio" name="recommended_portfolio" id="recommended_portfolio_no" <?php echo isset($calc_user->data->recommended_portfolio) ? checked($calc_user->data->recommended_portfolio, '0') : ''; ?> value="0"><label for="recommended_portfolio_no" class="recommended_portfolio_no"><span><span></span></span>No </label>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php
                $is_adviser = false;
                $s_hide_row = '';
                $growth_rate_hide_row = '';
                $i_default_age = '';
                $readonly_input = '';
                $inv_type_myself_checked = 'checked';
                $inv_type_adviser_checked = '';
                if ( $user_type == 'advisor' ) {
                    $is_adviser = true;
                    $s_hide_row = "style='display: none;'";
                    //$i_default_age = '55';
                    $i_default_age = '';
                    $readonly_input = '';
                    //$readonly_input = 'readonly="readonly"';
                    $inv_type_myself_checked = '';
                    $inv_type_adviser_checked = 'checked';
                    if (isset($calc_user->data->inv_management_type) && !empty($calc_user->data->inv_management_type)) {
                        $calc_user->data->inv_management_type = 'advisor';
                    }
                }
                // if (in_array('adviser', (array) $user->roles) || in_array('subscriber', (array) $user->roles) || 
                // in_array('administrator', (array) $user->roles) || $user_type == 'advisor') {
                if ($calc_user->data->investments_today == '' || $calc_user->data->investments_today == 'today') {
                    $growth_rate_hide_row = "style='display: none;'";
                } else {
                    $growth_rate_hide_row = "style='display: block;'";
                }
                // }
                ?>
                <div class="calculator-form__question">
                    <div class="age-row">
                        <div class="col-md-12 form-header__wrap">
                            <label class="form-header label question-label-<?php echo $age_lbl; ?>" for="age"><?php echo $lable_8_text;  ?></label>
                            <span class="help hint--top" data-hint="<?php echo $help_10_text; ?>">?<div class="help-popup"><?php echo $help_10_text; ?></div></span>
                        </div>
                        <!--  -->
                        <div class="row align-items-end">
                            <div class="col-md-3 sm-three-equal age_input_type calculator-form__number-input--wrap no-currency">
                                <label class="form-header" for="age">Age</label>
                                <input class="short-input-with-hint ctp-nav" type="number" pattern="\d*" name="age" id="age" <?php echo $readonly_input; ?> value="<?php echo !empty($calc_user->data->age) ? intval($calc_user->data->age) : $i_default_age; ?>" placeholder="Age" max="100">
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                <div class="col-md sm-three-equal gender_male"><input type="radio" name="gender" id="gender_male" <?php echo isset($calc_user->data->gender) ? checked($calc_user->data->gender, 'male') : 'checked '; ?> value="male" class="ctp-nav"><label for="gender_male"><span><span></span></span><?php _e('Male', 'ctp'); ?></label><input type="radio" name="gender" id="gender_female" <?php echo isset($calc_user->data->gender) ? checked($calc_user->data->gender, 'female') : ''; ?> value="female" class="ctp-nav"><label for="gender_female"><span><span></span></span><?php _e('Female', 'ctp'); ?></label></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ticket#307 start -->
                <!--RSPL Task#25-->
                <?php
                if ($user_type == 'advisor') {
                    echo '<input type="hidden" name="inv_management_type" value="advisor"/>';
                } else {
                    echo '<input type="hidden" name="inv_management_type" value="myself"/>';
                }
                ?>
                <!-- Ticket#307 start -->
                <!--RSPL Task#25-->
                <?php
                $allowed_linked_portfolios = 0;
                if ( $user_type == 'advisor' ) { /*
                ?>
                <div class="calculator-form__question">
                    <div class="row linked_portfolio_row">
                        <div class="col-md-12 form-header__wrap">
                            <label for="question-linked-portfolio" class="form-header label question-label-<?php echo $linked_portfolio_lbl; ?>"><?php echo ctp_get_questions_option('question_portfolio_label') ?></label>
                            <span class="help hint--top" data-hint="<?php echo ctp_get_questions_option('question_portfolio_help') ?>">?<div class="help-popup"><?php echo ctp_get_questions_option('question_portfolio_help') ?></div></span>
                        </div>
                        <div class="col-lg-6 col-sm-12 link_portfolio_yes_no">
                            <input type="radio" name="link_portfolio" id="link_portfolio_yes" value="yes" <?php echo isset($calc_user->data->link_portfolio) ? checked($calc_user->data->link_portfolio, 'yes') : ''; ?> class="portfolio_redirection_cls">
                            <label for="link_portfolio_yes"><span><span></span></span>Yes </label>

                            <input type="radio" name="link_portfolio" id="link_portfolio_no" <?php echo !isset($calc_user->data->link_portfolio) ? 'checked' : ''; ?> <?php echo isset($calc_user->data->link_portfolio) ? checked($calc_user->data->link_portfolio, 'no') : ''; ?> value="no">
                            <label for="link_portfolio_no"><span><span></span></span>No</label>
                        </div>
                    </div>
                </div>
                <?php
                */ }
                ?>
                <!--RSPL Ticket#192 checklist-3 Start-->
                <?php
                $hide_show_adviser_que = 'style="display:none;"';
                $hide_show_adviser_chrg = 'style="display:none;"';
                $hide_show_result_view = '';
                //$is_adviser_charges
                if ($user_type == 'advisor' && !isset($calc_user->data->link_portfolio)) {
                    $hide_show_adviser_que = '';
                    //$hide_show_adviser_chrg = '';
                }
                //var_dump($calc_user->data->is_adviser_charges);
                if (($calc_user->data->is_adviser_charges == 'yes' || $calc_user->data->is_adviser_charges == NULL)
                    && ($calc_user->data->link_portfolio == 'no' || 
                    $calc_user->data->link_portfolio == NULL) && $user_type == 'advisor') {
                    $hide_show_adviser_chrg = '';
                }
                if (isset($calc_user->data->link_portfolio) && $calc_user->data->link_portfolio == 'yes') {
                    $hide_show_result_view = 'style="display:none;"';
                }
                ?>
                <div class="calculator-form__question" <?php echo $hide_show_adviser_que; ?>>
                    <div class="row is_adviser_que" <?php echo $hide_show_adviser_que; ?>>
                        <div class="col-sm-12">
                            <div class="form-header__wrap">
                            <label for="question-is-adviser" style="padding-left: 0;" class="form-header label question-label-<?php echo $is_advisor_lbl; ?>">
                                Would you like to add your advice charges?
                            </label>
                            <span class="help hint--top" data-hint="Would you like to add your advice charges?">?<div class="help-popup">Would you like to add your advice charges?</div></span>
                            </div>
                        </div>
                        <!-- <div class="col-lg-1"></div> -->
                        <div class="col-lg-6 col-sm-12 is_adviser_charges_yes_no">
                            <input type="radio" name="is_adviser_charges" id="is_adviser_charges_yes" <?php echo !isset($calc_user->data->is_adviser_charges) ? 'checked' : ''; ?> value="yes" <?php echo isset($calc_user->data->is_adviser_charges) ? checked($calc_user->data->is_adviser_charges, 'yes') : ''; ?>>
                            <label for="is_adviser_charges_yes"><span><span></span></span>Yes </label>
                            <input type="radio" name="is_adviser_charges" id="is_adviser_charges_no" <?php echo isset($calc_user->data->is_adviser_charges) ? checked($calc_user->data->is_adviser_charges, 'no') : ''; ?> value="no">
                            <label for="is_adviser_charges_no"><span><span></span></span>No</label>
                        </div>
                        
                    </div>
                </div>
                <?php
                //if (in_array('adviser', (array) $user->roles)) {
                ?>
                <div id="adviser-charges-main" <?php echo $hide_show_adviser_chrg; ?>>
                    <div class="">
                    <div class="calculator-form__question">
                            <div class="col-lg-12">
                                <div class="form-header__wrap">
                                <label style="padding-left:0;" class="form-header label question-label-<?php echo $adviser_charges_lbl;   ?>">
                                    <?php echo ctp_get_questions_option('question_14_label') ?></label>
                                </div>
                            </div>
                    </div>
                    </div>

                    <div class="row indented initial-adviser-charge-wrap">
                        <div class="col-lg-6 col-sm-6 sm-full">
                            <label for="initial_adviser_charge"><?php _e('Initial advice charge:', 'ctp'); ?></label>
                        </div>
                        <div class="col-lg-2 col-sm-6 initial-adviser-charge-fields sm-three-equal initial_advice_type_percentage">
                            <input type="radio" name="initial_advice_type" id="initial_advice_type_percentage" <?php echo isset($calc_user->data->initial_advice_type) ? checked($calc_user->data->initial_advice_type, 'percentage') : 'checked '; ?> value="percentage" class="ctp-nav">
                            <label for="initial_advice_type_percentage"><span><span></span></span><?php _e('%', 'ctp'); ?>
                            </label>
                        </div>
                        <div class="col-lg-2 col-sm-6 initial-adviser-charge-fields sm-three-equal initial_advice_type_flat">
                            <input type="radio" name="initial_advice_type" id="initial_advice_type_flat" <?php echo isset($calc_user->data->initial_advice_type) ? checked($calc_user->data->initial_advice_type, 'flat') : ''; ?> value="flat" class="ctp-nav">
                            <label for="initial_advice_type_flat"><span><span></span></span><?php _e('£', 'ctp'); ?></label>
                        </div>
                        <div class="col-lg-2 col-sm-6 initial-adviser-charge-fields initial-adviser-charges-input sm-three-equal initial_advice_inputval">
                                    <div class="calculator-form__number-input--wrap">
                            <span class="currency"><?php echo $initial_currency_symbol; ?></span><input type="number" pattern="\d*" class="adviser-charges-input ctp-nav" id="initial-adviser-charges" <?php echo $initial_input_attr; ?> name="initial_adviser_charges" value="<?php echo isset($calc_user->data->initial_adviser_charges) ? $calc_user->data->initial_adviser_charges : ''; ?>">
                                    </div>
                        </div>
                    </div>

                    <div class="row indented annual-adviser-charge-wrap">
                        <div class="col-lg-6 col-sm-6 sm-full">
                            <label for="annual_adviser_charge"><?php _e('Annual advice charge:', 'ctp'); ?></label>
                        </div>
                        <div class="col-lg-2 col-sm-6 initial-adviser-charge-fields sm-three-equal annual_advice_type_percentage">
                            <input type="radio" name="annual_advice_type" id="annual_advice_type_percentage" <?php echo isset($calc_user->data->annual_advice_type) ? checked($calc_user->data->annual_advice_type, 'percentage') : 'checked '; ?> value="percentage" class="ctp-nav">
                            <label for="annual_advice_type_percentage"><span><span></span></span><?php _e('%', 'ctp'); ?>
                            </label>
                        </div>
                        <div class="col-lg-2 col-sm-6 initial-adviser-charge-fields sm-three-equal annual_advice_type_flat">
                            <input type="radio" name="annual_advice_type" id="annual_advice_type_flat" <?php echo isset($calc_user->data->annual_advice_type) ? checked($calc_user->data->annual_advice_type, 'flat') : ''; ?> value="flat" class="ctp-nav">
                            <label for="annual_advice_type_flat"><span><span></span></span><?php _e('£', 'ctp'); ?></label>
                        </div>
                        <div class="col-lg-2 col-sm-6 initial-adviser-charge-fields annual-adviser-charges-input sm-three-equal annual_advice_inputval">
                                    <div class="calculator-form__number-input--wrap">
                            <span class="currency"><?php echo $annual_currency_symbol; ?></span><input type="number" pattern="\d*" class="adviser-charges-input ctp-nav" id="annual-adviser-charges" <?php echo $annual_input_attr; ?> name="annual_adviser_charges" value="<?php echo isset($calc_user->data->annual_adviser_charges) ? $calc_user->data->annual_adviser_charges : ''; ?>">
                                    </div>
                        </div>
                    </div>
                </div>
                <?php
                //  }
                ?>
                <!--RSPL Ticket#192 checklist-3 End-->
                <div class="result-view-dis" <?php echo $hide_show_result_view; ?>>
                <div class="calculator-form__question">
                    <div class="row">
                        <div class="col-md-12 calc-form-title form-header__wrap">
                            <span class="form-header label question-label-<?php echo $inv_option_lbl;  ?>"><?php echo $lable_9_text; ?></span>
                            <span class="help hint--top" data-hint="<?php echo $help_9a_text ?>">?<div class="help-popup"><?php echo $help_9a_text; ?></div></span>
                        </div>
                        <div class="col-lg-6 calc-form-help">

                        </div>
                    </div>

                    <div class="row over-period-controls">
                        <div class="col-lg-5 col-sm-3 investment-today-button">
                            <input <?php echo isset($calc_user->data->investments_today) ? checked($calc_user->data->investments_today, 'today') : 'checked'; ?> checked="checked" class="investment-over-today ctp-nav" type="radio" name="investments_today" value="today"><label class="investments_today" for="investments_today"><?php echo $lable_9a_text; ?></label>
                        </div>
                    </div>
                </div>
                </div> <!-- new end-->

                <!--RSPL Ticket#192 checklist-2 Start-->
                <?php
                // if (in_array('adviser', (array) $user->roles) || $user_type == 'advisor' 
                // || in_array('subscriber', (array) $user->roles) || in_array('administrator', (array) $user->roles)) {
                ?>
                <div class="row growth_rate_toggle" <?php echo $growth_rate_hide_row; ?>>
                    <div class="col-lg-6 col-sm-9">
                        <label class="form-header label question-label-<?php if ($is_adviser || $user_type != 'advisor' ) {
                                                                echo $growth_lbl;
                                                            } else {
                                                                echo '14';
                                                            }  ?>"><?php echo ctp_get_questions_option('question_13_label') ?></label>
                    </div>
                    <div class="col-lg-6 growth_rate_toggle_radios">
                        <input type="radio" name="is_growth" id="is_growth_yes" <?php echo isset($calc_user->data->is_growth) ? checked($calc_user->data->is_growth, 'yes') : 'checked '; ?> value="yes" class="ctp-nav">
                        <label for="is_growth_yes"><span><span></span></span><?php _e('Yes', 'ctp'); ?></label>
                        <input type="radio" name="is_growth" id="is_growth_no" <?php echo isset($calc_user->data->is_growth) ? checked($calc_user->data->is_growth, 'no') : ''; ?> value="no" class="ctp-nav"><label for="is_growth_no"><span><span></span></span><?php _e('No', 'ctp'); ?></label>
                    </div>
                </div>

                <div class="row growth_rate_toggle growth-slider" <?php echo $growth_rate_hide_row; ?>>
                    <div class="col-lg-8 hide-in-laptop">
                        <span class="label"><?php // _e( 'Based on investments over:', 'ctp' ); 
                                            ?></span>
                    </div>
                    <div class="col-lg-4 growth-rate-input float-right-cls over-years-input">
                        <!-- <div id="slider-growth-rate"></div> -->
                        <input type="number" pattern="\d*" min="0" max="50" step="any" class="numeric-input ctp-nav" id="growth-rate" name="growth_rate" <?php echo ($calc_user->data->is_growth == 'no' ? "" : "disabled") ?> value="<?php echo isset($calc_user->data->growth_rate) ? intval($calc_user->data->growth_rate) : '4'; ?>"><span class="growth-rate"><?php _e("&nbsp%", 'ctp') ?></span>
                    </div>
                </div>

                <?php
                //}
                ?>
                <!--RSPL Ticket#192 checklist-2 End-->

                <div class="calculator-form__question">
                <div class="row continue-next-step">
                    <div class="col-lg-6" style="margin-top: 25px;">
                        <a class="calculator-step-back ctp-nav" href="<?php echo esc_url($step_2_url) ?>"><?php _e('Back') ?></a>
                    </div>
                    <div class="col-lg-6 continue-to-right">
                        <input type="hidden" name="update" value="<?php echo $calc_user->data->update ?>">
                        <input type="hidden" name="calculator_action" value="save_step">
                        <input type="hidden" name="step" value="3">
                        <input type="hidden" name="main_version" class="main_version_cls" value="<?php echo $main_version; ?>">
                        <input class="continue check-for-age ctp-nav mt-5" type="submit" value="Continue">
                    </div>
                </div>
                </div>


            </div>
        </form>
    <?php endif; ?>

    <?php if ($step === 4) : ?>
        <div class="step-4">
            <!--RSPL Task#83-->
            <div class="platform-save-result-msg"></div>
            <h3><?php _e('Great, here are your results', 'ctp') ?></h3>

            


                <!-- STEPS -->
                <div class="steps--mobile">
                  <div>
                    <?php
                    if ($step_count && $step_count > 0) {
                      $i = 1;
                      if ($_GET['step'] == 'results') {
                        $step = $step_count;
                      }
                      echo "<ul class='calculator-header__steps'>";
                      while ($i < ($step_count + 1)) {
                        $class = '';
                        if (strpos(esc_url(get_permalink()), 'platform-calculator-consumer') > 0 || strpos(esc_url(get_permalink()), 'platform-calculator') > 0) {
                          if ($step > 1) {
                            if ($step == $i + 1) {
                              $class = 'class="active"';
                            }
                          } else {
                            if ($step == $i) {
                              $class = 'class="active"';
                            }
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        } else {
                          if ($step == $i) {
                            $class = 'class="active"';
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        }
                      }
                      echo "</ul>";
                    }
                    ?>
                  </div>
                </div>

            <!--RSPL Task#37 - Changes applied to display the cash and other newly added details in summary section-->
            <fieldset class="calculator-summary">
                <legend><?php _e('Your Summary') ?></legend>
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-8">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_all_total_label'); ?></span>
                    </div>
                    <div class="col-6 col-md-4" style="text-align: right; padding-right: 30px; padding-left: 0;">
                        <span class="summary-value"><?php echo $total_investments; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-3">
                        <span class="summary-label">&nbsp;</span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-top: 16px;">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_funds_label'); ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-top: 16px; padding-right: 30px; padding-left: 0;">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_cash_label'); ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-right: 30px; padding-left: 0;">
                        <span class="summary-label" style="line-height: 10px;"><?php echo ctp_get_questions_option('summary_ex_traded_label'); ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_investments_label') ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right;">
                        <span class="summary-value"><?php echo $total_funds; ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-right: 30px;">
                        <span class="summary-value"><?php echo $total_cash; ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-right: 30px;">
                        <span class="summary-value"><?php echo $total_ex_traded; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_trading_freq_label') ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right;">
                        <span class="summary-value"><?php echo $trading_freq_funds; ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-right: 30px;">
                        <span class="summary-value">N/A</span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-right: 30px;">
                        <span class="summary-value"><?php echo $trading_freq_ex_traded; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_trading_amnt_label') ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right;">
                        <span class="summary-value"><?php echo $avg_trading_funds; ?></span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-right: 30px;">
                        <span class="summary-value">
                            <!--£ 0-->N/A
                        </span>
                    </div>
                    <div class="col-6 col-md-3" style="text-align:right; padding-right: 30px;">
                        <span class="summary-value"><?php echo $avg_trading_ex_traded; ?></span>
                    </div>
                </div>
            </fieldset>
            
            <!-- Fresh Pies add advertisement top of results
			<div class="fp-ctp-da-results fp-ctp-da-results-top fp-ctp-da-desktop">
				<ins class='dcmads' style='display:inline-block;width:728px;height:90px'
					data-dcm-placement='N3643.3607496FUNDSCAPE/B25176812.323308418'
					data-dcm-rendering-mode='iframe'
					data-dcm-https-only
					data-dcm-gdpr-applies='gdpr=${GDPR}'
					data-dcm-gdpr-consent='gdpr_consent=${GDPR_CONSENT_755}'
					data-dcm-addtl-consent='addtl_consent=${ADDTL_CONSENT}'
					data-dcm-ltd='false'
					data-dcm-resettable-device-id=''
					data-dcm-app-id=''>
				  <script src='https://www.googletagservices.com/dcm/dcmads.js'></script>
				</ins>
			</div>
			<div class="fp-ctp-da-results fp-ctp-da-results-top fp-ctp-da-mobile">
				<ins class='dcmads' style='display:inline-block;width:300px;height:250px'
					data-dcm-placement='N3643.3607496FUNDSCAPE/B25176812.322059660'
					data-dcm-rendering-mode='iframe'
					data-dcm-https-only
					data-dcm-gdpr-applies='gdpr=${GDPR}'
					data-dcm-gdpr-consent='gdpr_consent=${GDPR_CONSENT_755}'
					data-dcm-addtl-consent='addtl_consent=${ADDTL_CONSENT}'
					data-dcm-ltd='false'
					data-dcm-resettable-device-id=''
					data-dcm-app-id=''>
				  <script src='https://www.googletagservices.com/dcm/dcmads.js'></script>
				</ins>
			</div>
			<!-- END Fresh Pies add advertisement top of results -->

        
        <?php 

        if(strpos(get_the_permalink(), 'robo-calculator') > 0) {
          $calculator_placement = 'robo';
        } else {
          $calculator_placement = 'investment';
        }
        if(strpos(get_the_permalink(), 'embedded-calculator') < 1) {
          echo do_shortcode('[display_ad category="calculator" placement="'.$calculator_placement.'" step="results" size="leaderboard"]')
        ;
        } 

        ?>

            <div class="row-with-filter-sliders">
                <div class="results-button-container">
                    <div class="row"><span class="form-header">Order By:</span></div>
                    <label class="dropdown">
                        <select name="order_results_by" id="order_resluts_by" onchange="document.getElementById('order_by').value = this.value;">
                            <option value="cost_low_high" <?php echo (($order_by == 'cost_low_high') ? ' selected' : ''); ?>>
                                Platform fee - low to high
                            </option>
                            <option value="cost_high_low" <?php echo (($order_by == 'cost_high_low') ? ' selected' : ''); ?>>
                                Platform fee - high to low
                            </option>
                            <?php
                            if($user_type !== "advisor") {
                            ?>
                                <option value="rating_high_low" <?php echo (($order_by == 'rating_high_low') ? ' selected' : ''); ?>>
                                    Our rating - high to low
                                </option>
                                <option value="rating_low_high" <?php echo (($order_by == 'rating_low_high') ? ' selected' : ''); ?>>
                                    Our rating - low to high
                                </option>
                            <?php } ?>
                            <option value="alphabetical_az" <?php echo (($order_by == 'alphabetical_az') ? ' selected' : ''); ?>>
                                Alphabetical A-Z
                            </option>
                        </select>
                    </label>
                    <form name="hiddenform" method="POST" id='result-update-form' action="">
                        <input type="hidden" name="totalfund" id="total-fund" value="<?php echo cplat_sanitize_number($total_funds); ?>" />
                        <input type="hidden" name="totalextraded" id="total-ex-traded" value="<?php echo cplat_sanitize_number($total_ex_traded); ?>" />
                        <input type="hidden" name="totalinvestments" id="total-investments" value="<?php echo cplat_sanitize_number($total_investments); ?>" />
                        <input type="hidden" name="yearly_trades_funds" id="yearly_trades_funds" value="<?php echo intval($trading_freq_funds); ?>" />
                        <input type="hidden" name="yearly_trades_ex" id="yearly_trades_ex" value="<?php echo intval($trading_freq_ex_traded); ?>" />
                        <input type="hidden" name="avg_trade_funds" id="avg_trade_funds" value="<?php echo cplat_sanitize_number($avg_trading_funds); ?>" />
                        <input type="hidden" name="avg_trade_ex" id="avg_trade_ex" value="<?php echo cplat_sanitize_number($avg_trading_ex_traded); ?>" />
                        <input type="hidden" name="results" id="results" value="<?php echo $results; ?>" />
                        <input type="hidden" name="over_years" id="over_years" value="<?php echo $over_years; ?>" />
                        <input type="hidden" name="point_future" id="point_future" value="<?php echo $point_future; ?>" />
                        <input type="hidden" name="order_by" id="order_by" />
                        <input type="hidden" name="roles_commas" id="roles_commas" value="<?php echo $roles_commas; ?>" />
                        <input type="hidden" name="is_growth" id="is_growth" value="<?php echo $is_growth; ?>" />
                        <input type="hidden" name="growth_rate" id="growth_rate" value="<?php echo $growth_rate; ?>" />
                        <input type="hidden" name="updated" id="updated" value="1" />

                    </form>
                    <!-- <a class="cplat-show-results" href=""><?php //_e('Update Results', 'cplat'); 
                                                                ?></a> -->
                </div>
            </div>
            <div class="results-controls">
                <form method="POST" action="">
                    <?php
                    $_saved_version_name = '';
                    $user_data_saved_vname = $calc_user->data->version_name;
                    $saved_vname = $_SESSION['saved_version_name'];
                    if (isset($user_data_saved_vname) || isset($saved_vname)) {
                        if ($saved_vname) {
                            $_saved_version_name = $saved_vname;
                        } else {
                            $_saved_version_name = $user_data_saved_vname;
                        }
                    }
                    ?>
                    <div class="row">
                        <span class="form-header">Name this search</span>
                        <input class="name-this-search ctp-nav mt-0" id="platform-version-name" type="text" placeholder="Name this search..." name="version_name" value="<?php echo $_saved_version_name; ?>">
                    </div>
                    <div class="row">
                        <input type="hidden" name="action" value="save_search_results">
                        <input type="hidden" name="totalfund" id="total-fund-s" value="<?php echo cplat_sanitize_number($total_funds); ?>" />
                        <input type="hidden" name="totalextraded" id="total-ex-traded-s" value="<?php echo cplat_sanitize_number($total_ex_traded); ?>" />
                        <input type="hidden" name="totalinvestments" id="total-investments-s" value="<?php echo cplat_sanitize_number($total_investments); ?>" />
                        <input type="hidden" name="yearly_trades_funds" id="yearly_trades_funds-s" value="<?php echo cplat_sanitize_number($trading_freq_funds); ?>" />
                        <input type="hidden" name="yearly_trades_ex" id="yearly_trades_ex-s" value="<?php echo cplat_sanitize_number($trading_freq_ex_traded); ?>" />
                        <input type="hidden" name="avg_trade_funds" id="avg_trade_funds-s" value="<?php echo cplat_sanitize_number($avg_trading_funds); ?>" />
                        <input type="hidden" name="avg_trade_ex" id="avg_trade_ex-s" value="<?php echo cplat_sanitize_number($avg_trading_ex_traded); ?>" />
                        <input type="hidden" name="results" id="results-s" value="<?php echo $results; ?>" />
                        <input type="hidden" name="over_years" id="over_years-s" value="<?php echo cplat_sanitize_number($over_years); ?>" />
                        <input type="hidden" name="point_future" id="point_future-s" value="<?php echo cplat_sanitize_number($point_future); ?>" />
                        <input type="hidden" name="order_by" id="order_by-s" value="<?php echo $order_by; ?>" />
                        <input type="hidden" name="roles_commas" id="roles_commas" value="<?php echo $roles_commas; ?>" />
                        <input type="hidden" name="update" id="update" value="<?php echo $update ?>" />
                        <div class="col-12 px-0 mt-4">
                            <input class="results-save results-save-pf btn btn-orange" type="submit" value="Save">
                            <a href="#" class="results-email results-email-pf btn btn-ghost" style="pointer-events: none;cursor: not-allowed;">Email</a>
                            <a target="" href="#" data-toggle="modal" data-target=".fusion-modal.print-result-modal" class="results-print results-print-pf btn btn-ghost" style="pointer-events: none; cursor: not-allowed;">Print</a>
                            <div class="pt-4">
                                <div class="col-md-8 col-12">
                                    <?php
                                    if (!empty($result_page_page_note_acf)) {
                                        echo '<div class="charges-note">' . $result_page_page_note_acf . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="results-container-update">
            <div class="platform-loading-update">
                <h3><?php //_e('Loading results') ?></h3>
                <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </div>
        </div>
        <!-- Result list -->
        <div class="platform-list-queue" data-version="<?php echo $version; ?>" data-serialized-inputs='<?php echo $htb_data;?>'>


        </div>
        <div class="results-container">
            <div class="platform-loading">
                <h3><?php _e('Loading results') ?></h3>
                <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </div>
        </div>
        <!-- <div class="row container"><a class="calculator-step-back" href="<?php echo esc_url($step_3_url) ?>"><?php _e('Back') ?></a></div>
        <div class="row container"><a class="calculator-step-start-again" href="<?php echo esc_url($step_2_url) ?>"><?php _e('Start again') ?></a></div> -->

		<!-- Fresh Pies add advertisement bottom of results
		<div class="fp-ctp-da-results fp-ctp-da-results-bottom fp-ctp-da-desktop">
			<ins class='dcmads' style='display:inline-block;width:728px;height:90px'
				data-dcm-placement='N3643.3607496FUNDSCAPE/B25176812.323308418'
				data-dcm-rendering-mode='iframe'
				data-dcm-https-only
				data-dcm-gdpr-applies='gdpr=${GDPR}'
				data-dcm-gdpr-consent='gdpr_consent=${GDPR_CONSENT_755}'
				data-dcm-addtl-consent='addtl_consent=${ADDTL_CONSENT}'
				data-dcm-ltd='false'
				data-dcm-resettable-device-id=''
				data-dcm-app-id=''>
			  <script src='https://www.googletagservices.com/dcm/dcmads.js'></script>
			</ins>
		</div>
		<div class="fp-ctp-da-results fp-ctp-da-results-bottom fp-ctp-da-mobile">
			<ins class='dcmads' style='display:inline-block;width:300px;height:250px'
				data-dcm-placement='N3643.3607496FUNDSCAPE/B25176812.322059660'
				data-dcm-rendering-mode='iframe'
				data-dcm-https-only
				data-dcm-gdpr-applies='gdpr=${GDPR}'
				data-dcm-gdpr-consent='gdpr_consent=${GDPR_CONSENT_755}'
				data-dcm-addtl-consent='addtl_consent=${ADDTL_CONSENT}'
				data-dcm-ltd='false'
				data-dcm-resettable-device-id=''
				data-dcm-app-id=''>
			  <script src='https://www.googletagservices.com/dcm/dcmads.js'></script>
			</ins>
		</div>
		<!-- END Fresh Pies add advertisement bottom of results -->


        <div class="row continue-next-step step-4-buttons">
            <div class="col-lg-6" style="margin-top: 25px;">
                <a class="calculator-step-back ctp-nav" href="<?php echo esc_url($step_3_url) ?>"><?php _e('Back') ?></a>
            </div>
            <div class="col-lg-6 continue-to-right">
                <a class="calculator-step-start-again btn btn-orange" href="<?php echo esc_url($step_2_url) ?>"><?php _e('Start again') ?></a>
            </div>
        </div>
        
        <?php 

        if(strpos(get_the_permalink(), 'embedded-calculator') < 1) {
          echo do_shortcode('[display_ad category="calculator" placement="'.$calculator_placement.'" step="results" size="leaderboard"]');
        }

        ?>

    <?php endif; ?>

</div>
<!-- Fresh Pies deleted extra ending </div> to fix layout issue 13/12/2021 -->