<?php
@session_start();
$user_type = get_post_meta(get_the_ID(), '_calculator_page_user_type', true); //advisor
$main_version = '';
if (isset($_REQUEST['main_version']) && !empty($_REQUEST['main_version'])) {
    global $wpdb;
    $main_version = $_REQUEST['main_version'];
}
// Create an object of current user.
$user = wp_get_current_user();
$roles_arr = $user->roles;
$roles_commas = ($user_type && $user_type == 'advisor' ? 'adviser' : 'subscriber');
if(!$calc_user->data) {
$calc_user->data = (object)(unserialize(stripslashes($_GET['htb_serialized_data'])));
}
if ((in_array('subscriber', (array) $user->roles) || $user_type == 'subscriber')  && $step != 2) {
    //echo do_shortcode('[ctp-steps-advertisement]'); // #326
}

$compare_now_value = false;
if($_POST['compare-now-value']) {
    $compare_now_value = $_POST['compare-now-value'];
}

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
<div class="cplat-submit-form-container">
    <h1 class="heading-3">Simple investment platform comparison tool</h1>


                <!-- STEPS -->
                <div class="steps--mobile steps--mobile--simplified">
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
    if ($step === 1) : ?>
        <!-- Begin : Ticket#218 && Ticket#220 -->
        <?php
        $s_hide_investment_shares =  $s_hide_investment_shares_que = "";
        $s_hide_investment_products = "style='display: none;'";
        if ($calc_user->data->investment_products == 'no') {
            $s_hide_investment_shares = $s_hide_investment_shares_que = $s_hide_investment_products = "style='display: none;'";
        }
        if ($calc_user->data->investment_products == 'yes' && $calc_user->data->investment_stocks_shares == 'no') {
            $s_hide_investment_products = $s_hide_investment_shares_que =  "";
            $s_hide_investment_shares = "style='display: none;'";
        }
        $products_row_style = (isset($calc_user->data->investment_products_simplified) && $calc_user->data->investment_products_simplified == 'yes' ? "style='display: block;'" : "style='display: none;'");
        $inv_products_row_style = (isset($calc_user->data->investment_products) && $calc_user->data->investment_products == 'yes' ? "style='display: block;'" : "style='display: none;'");
        ?>
        <!-- End : Ticket#218 && Ticket#220 -->
        <!--RSPL TASK#21-->
        <form method="post" action="" id="stepsform" class="stepsform2_container simplified-calci calculator-form" autocomplete="on">
            <input type="hidden" value="<?php echo $user_type; ?>" name="user_type" id="user_type" />
            <input type="hidden" value="<?php echo $user_type; ?>" name="roles_commas" id="roles_commas" />
            <input type="hidden" value="4" name="growth_rate" id="growth_rate" />
            <input type="hidden" name="calculator_type" value="simplified">
            <input type="hidden" name="inv_management_type" value="myself" />

            <div class="step-2">
                <h3><?php _e('Your Investments', 'cplat') ?></h3>
                <div class="calculator-form__question">
                    <div class="row" style="position: relative;">
                        <div class="col-12 form-header__wrap">
                            <label class="form-header label question-label-1" for="total-in-investment-products">
                                <?php
                                echo ctp_get_questions_option('simplified_question_1_label'); ?>
                            </label>
                            <span style="margin-top:9px;" class="help hint--top" data-hint="<?php echo ctp_get_questions_option('simplified_question_1_tooltip') ?>">?<div class="help-popup"><?php echo ctp_get_questions_option('simplified_question_1_tooltip'); ?></div></span>
                        </div>
                        <!--                    <div class="col-lg-1"></div>-->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="calculator-form__number-input--wrap">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input data-compare-now-value="<?php echo $compare_now_value; ?>" placeholder="Investment" class="input-with-hint numeric-input" type="number" pattern="\d*" name="total_savings_and_investments" id="total-in-investment-products" value="<?php echo isset($calc_user->data->total_savings_and_investments) ? intval($calc_user->data->total_savings_and_investments) : ''; ?>" autocomplete="on">
                                </div>
                                </div>
                            </div>
                            <input placeholder="Total" readonly class="total_total_savings_and_investments input-with-hint numeric-input" type="hidden" name="total_savings_and_investments_total" id="total-in-investment-products-total" value="<?php echo isset($calc_user->data->total_savings_and_investments_total) ? intval($calc_user->data->total_savings_and_investments_total) : ''; ?>">
                            <!-- <span style="margin-top:9px;" class="help hint--top" data-hint="<?php echo ctp_get_questions_option('simplified_question_1_tooltip') ?>">?</span> -->
                        </div>
                    </div>
                </div>
                <?php
                $second_que_style = "";
                $second_que_number = 2;
                $third_que_number = 3;
                $third_que_label = "If you know, please tell us how much you have in each of the following:";
                if ( $user_type == 'advisor' ) {
                    $second_que_style = "display: none;";
                    $third_que_label = "How much do you have in the following products and wappers?";
                    $third_que_number = 2;
                }
                ?>
                <div class="calculator-form__question">
                    <div class="row" style="position: relative; <?php echo $second_que_style; ?>">
                        <div class="col-12 form-header__wrap">
                            <span class="form-header label question-label-<?php echo $second_que_number; ?>">
                                <?php echo ctp_get_questions_option('simplified_question_2_label')
                                ?></span>
                                <?php
                                if (!empty(ctp_get_questions_option('simplified_question_2_tooltip'))) {
                                    echo '<span class="help hint--top investment_products-hint" data-hint="' . ctp_get_questions_option('simplified_question_2_tooltip') . '">?<div class="help-popup">'. ctp_get_questions_option('simplified_question_2_tooltip') .'</div></span>';
                                }
                                ?>
                        </div>
                        <!--                    <div class="col-lg-1"></div>-->
                        <div class="col-lg-4" style="margin-top: -6px">
                            <input type="radio" name="investment_products_simplified" id="investment_products_simplified_yes" <?php echo isset($calc_user->data->investment_products_simplified) ? checked($calc_user->data->investment_products_simplified, 'yes') : ''; ?> value="yes"><label for="investment_products_simplified_yes"><span><span></span></span><?php _e('Yes', 'cplat'); ?>
                            </label>
                            <input type="radio" name="investment_products_simplified" id="investment_products_simplified_no" <?php echo isset($calc_user->data->investment_products_simplified) ? checked($calc_user->data->investment_products_simplified, 'no') : 'checked'; ?> value="no"><label for="investment_products_simplified_no"><span><span></span></span><?php _e('No', 'cplat'); ?>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="calculator-form__question">
                    <div class="row" style="position: relative; <?php echo $second_que_style; ?>">
                        <div class="col-12 form-header__wrap">
                            <span class="form-header label question-label-<?php echo $third_que_number; ?>">
                                <?php echo ctp_get_questions_option('simplified_question_3_label'); ?></span>
                                <span class="help hint--top investment_products-hint" data-hint="<?php echo ctp_get_questions_option('simplified_question_3_tooltip') ?>">?<div class="help-popup"><?php echo ctp_get_questions_option('simplified_question_3_tooltip'); ?></div></span>
                        </div>
                        <!--                    <div class="col-lg-1"></div>-->
                        <div class="col-lg-4" style="margin-top: -6px">
                            <input type="radio" name="investment_products" id="investment_products_yes" <?php echo isset($calc_user->data->investment_products) ? checked($calc_user->data->investment_products, 'yes') : ''; ?> value="yes"><label for="investment_products_yes"><span><span></span></span><?php _e('Yes', 'cplat'); ?>
                            </label>
                            <input type="radio" name="investment_products" id="investment_products_no" <?php echo isset($calc_user->data->investment_products) ? checked($calc_user->data->investment_products, 'no') : 'checked'; ?> value="no"><label for="investment_products_no"><span><span></span></span><?php _e('No', 'cplat'); ?>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="investment-products" <?php echo $inv_products_row_style; ?>>
                    <div class="row">
                        <div class="col-12 form-header__wrap">
                            <span class="form-header label question-label-4">
                                <?php echo ctp_get_questions_option('simplified_question_4_label'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-lg-8 col-sm-6">
                            <label for="funds_isa"><?php _e('ISAs', 'cplat'); ?></label>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_isa" id="funds_isa" value="<?php echo !empty($calc_user->data->funds_isa) ? intval($calc_user->data->funds_isa) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ticket#307 Start -->
                    <div class="row indented junior-fields" <?php echo $products_row_style; ?>>
                        <div class="col-lg-8 col-sm-6">
                            <label for="junior-isa"><?php _e('Junior ISAs', 'cplat'); ?></label>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jisa" id="junior-isa" value="<?php echo !empty($calc_user->data->funds_jisa) ? intval($calc_user->data->funds_jisa) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-lg-8 col-sm-6">
                            <label for="funds_sipp"><?php _e('Sipps', 'cplat'); ?></label>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_sipp" id="funds_sipp" value="<?php echo !empty($calc_user->data->funds_sipp) ? intval($calc_user->data->funds_sipp) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented junior-fields" <?php echo $products_row_style; ?>>
                        <div class="col-lg-8 col-sm-6">
                            <label for="junior-sipp"><?php _e('Junior Sipps', 'cplat'); ?></label>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_jsipp" id="junior-sipp" value="<?php echo !empty($calc_user->data->funds_jsipp) ? intval($calc_user->data->funds_jsipp) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-lg-8 col-sm-6">
                            <label for="general-investments"><?php _e('General Investments', 'cplat'); ?></label>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input placeholder="Investment" class="calc-total-all numeric-input ctp-nav" type="number" pattern="\d*" name="funds_gia" id="general-investments" value="<?php echo !empty($calc_user->data->funds_gia) ? intval($calc_user->data->funds_gia) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row indented">
                        <div class="col-lg-8 col-sm-6">
                            <label for="total-all"><?php _e('Total', 'cplat'); ?></label>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span class="currency"><?php echo $currency; ?></span>
                                    <input readonly="readonly" type="text" class="numeric-input ctp-nav" name="total_all" id="total-all" value="<?php echo !empty($calc_user->data->total_all) ? intval($calc_user->data->total_all) : ''; ?>">
                                </div>
                                <input readonly="readonly" type="hidden" class="total_calculation_cls_all numeric-input ctp-nav" name="total_all_total" id="total-all-total" value="<?php echo !empty($calc_user->data->total_all_total) ? intval($calc_user->data->total_all_total) : ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="calculator-form__question">
                    <div class="row">
                        <div class="col-12 form-header__wrap">
                            <span class="form-header label question-label-4">
                                <?php echo ctp_get_questions_option('simplified_question_6_label')
                                ?></span>
                                <span class="help hint--top" data-hint="<?php echo ctp_get_questions_option('simplified_question_6_tooltip'); ?>">?<div class="help-popup"><?php echo ctp_get_questions_option('simplified_question_6_tooltip'); ?></div></span>
                        </div>
                        <div class="col-lg-4" style="margin-top: -6px">
                            <input type="radio" name="ethical_investment" id="ethical_investment_yes"  <?php echo isset($calc_user->data->ethical_investment) ? checked($calc_user->data->ethical_investment, '1') : ''; ?> value="1"><label for="ethical_investment_yes" class="ethical_investment_yes"><span><span></span></span>Yes </label>
                            <input type="radio" name="ethical_investment" id="ethical_investment_no" <?php echo !isset($calc_user->data->ethical_investment) ? 'checked' : ''; ?> <?php echo isset($calc_user->data->ethical_investment) ? checked($calc_user->data->ethical_investment, '0') : ''; ?> value="0"><label for="ethical_investment_no" class="ethical_investment_no"><span><span></span></span>No </label>
                        </div>
                    </div>
                </div>

                <div class="calculator-form__question">
                    <div class="row">
                        <div class="col-12 form-header__wrap">
                            <span class="form-header label question-label-4">
                                <?php echo ctp_get_questions_option('simplified_question_5_label')
                                ?></span>
                                <span class="help hint--top" data-hint="<?php echo ctp_get_questions_option('simplified_question_5_tooltip'); ?>">?<div class="help-popup"><?php echo ctp_get_questions_option('simplified_question_5_tooltip'); ?></div></span>
                        </div>
                        <div class="col-lg-4" style="margin-top: -6px">
                            <input type="radio" name="recommended_portfolio" id="recommended_portfolio_yes" <?php echo !isset($calc_user->data->recommended_portfolio) ? 'checked' : ''; ?> <?php echo isset($calc_user->data->recommended_portfolio) ? checked($calc_user->data->recommended_portfolio, '1') : ''; ?> value="1"><label for="recommended_portfolio_yes" class="recommended_portfolio_yes"><span><span></span></span>Yes </label>
                            <input type="radio" name="recommended_portfolio" id="recommended_portfolio_no" <?php echo isset($calc_user->data->recommended_portfolio) ? checked($calc_user->data->recommended_portfolio, '0') : ''; ?> value="0"><label for="recommended_portfolio_no" class="recommended_portfolio_no"><span><span></span></span>No </label>
                            
                        </div>
                    </div>
                </div>


                <input type="hidden" name="total_savings_and_investments_cash" value="0" />

                <div class="calculator-form__question">
                    <div class="row">
                        <div class="continue-to-right">
                            <input type="hidden" name="total_funds" value="" id="total-funds">
                            <input type="hidden" name="update" value="">
                            <input type="hidden" name="calculator_action" value="save_step">
                            <input type="hidden" name="step" value="1">
                            <input type="hidden" name="main_version" class="main_version_cls" value="<?php echo $main_version; ?>">
                            <input class="continue check-for-totals-simple ctp-nav ctpbtn-not-allowed mt-5" type="submit" value="Continue">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
    <?php
    if ($step === 2) : ?>
        <div class="step-4">
            <!--RSPL Task#83-->
            <div class="platform-save-result-msg"></div>
            <h2><?php _e('Great, here are your results', 'ctp') ?></h2>
            <!--RSPL Task#37 - Changes applied to display the cash and other newly added details in summary section-->
            <fieldset class="calculator-summary summary-desktop">
                <legend><?php _e('Your Summary') ?></legend>
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-8">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_all_total_label'); ?></span>
                    </div>
                    <div class="col-6 col-md-4" style="text-align: right; padding-right: 30px; padding-left: 0;">
                        <span class="summary-value"><?php echo $total_investments; ?></span>
                    </div>
                </div>
            </fieldset>
            <?php /*<fieldset class="calculator-summary summary-mobile">
                <legend><?php _e('Your Summary') ?></legend>
                <div class="row">
                    <div class="col-lg-6">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_all_total_label'); ?></span>
                    </div>
                    <div class="col-lg-6" style="text-align: right;">
                        <span class="summary-value"><?php echo $total_investments; ?></span>
                    </div>
                </div>

                <div class="row fund-type-labels">
                    <div class="col-lg-3">
                        <span class="summary-label">&nbsp;</span>
                    </div>
                    <div class="col-lg-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_funds_label'); ?></span>
                    </div>
                    <div class="col-lg-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_cash_label'); ?></span>
                    </div>
                    <div class="col-lg-3">
                        <span class="summary-label" style="line-height: 10px;"><?php echo ctp_get_questions_option('summary_ex_traded_label'); ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_investments_label') ?></span>
                    </div>
                    <div class="col-lg-3" style="text-align:right;padding-right: 10px;padding-left: 4px;">
                        <span class="summary-value"><?php echo $total_funds; ?></span>
                    </div>
                    <div class="col-lg-3" style="text-align:right; padding-right: 10px;">
                        <span class="summary-value"><?php echo $total_cash; ?></span>
                    </div>
                    <div class="col-lg-3" style="text-align:right; padding-right: 10px;">
                        <span class="summary-value"><?php echo $total_ex_traded; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_trading_freq_label') ?></span>
                    </div>
                    <div class="col-lg-3" style="text-align:right;padding-right: 10px;padding-left: 4px;">
                        <span class="summary-value"><?php echo $trading_freq_funds; ?></span>
                    </div>
                    <div class="col-lg-3" style="text-align:right; padding-right: 10px;">
                        <span class="summary-value">N/A</span>
                    </div>
                    <div class="col-lg-3" style="text-align:right; padding-right: 10px;">
                        <span class="summary-value"><?php echo $trading_freq_ex_traded; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <span class="summary-label"><?php echo ctp_get_questions_option('summary_trading_amnt_label') ?></span>
                    </div>
                    <div class="col-lg-3" style="text-align:right;padding-right: 10px;padding-left: 4px;">
                        <span class="summary-value"><?php echo $avg_trading_funds; ?></span>
                    </div>
                    <div class="col-lg-3" style="text-align:right; padding-right: 10px;">
                        <span class="summary-value">
                            <!--£ 0-->N/A
                        </span>
                    </div>
                    <div class="col-lg-3" style="text-align:right; padding-right: 10px;">
                        <span class="summary-value"><?php echo $avg_trading_ex_traded; ?></span>
                    </div>
                </div>
            </fieldset>
*/ ?>
            <?php 

        if(strpos(get_the_permalink(), 'embedded-calculator') < 1) {
            echo do_shortcode('[display_ad category="calculator" placement="simple" step="results" size="leaderboard"]'); 
        }
            ?>
            <div class="row-with-filter-sliders">
                <div class="results-button-container">
                  <div class="row">
                    <span class="order_resluts_by_label form-header">Order By: </span>
                  </div>
                    <label class="dropdown">
                        <select name="order_results_by" id="order_resluts_by" onchange="document.getElementById('order_by').value = this.value;">
                            <option value="cost_low_high" <?php echo (($order_by == 'cost_low_high') ? ' selected' : ''); ?>>
                                Platform fee - low to high
                            </option>
                            <option value="cost_high_low" <?php echo (($order_by == 'cost_high_low') ? ' selected' : ''); ?>>
                                Platform fee - high to low
                            </option>
                            <option value="rating_high_low" <?php echo (($order_by == 'rating_high_low') ? ' selected' : ''); ?>>
                                Our rating - high to low
                            </option>
                            <option value="rating_low_high" <?php echo (($order_by == 'rating_low_high') ? ' selected' : ''); ?>>
                                Our rating - low to high
                            </option>
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
                        <input class="name-this-search ctp-nav" id="platform-version-name" type="text" placeholder="Name this search..." name="version_name" value="<?php echo $_saved_version_name; ?>">
                    </div>
                    <div class="row" style="width: 100%;">
                      <input type="hidden" name="action" value="save_search_results">
                      <input type="hidden" name="calculator_type" value="simplified">
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
        <!-- <div class="row container"><a class="calculator-step-back" href="<?php echo esc_url($step_2_url) ?>"><?php _e('Back') ?></a></div>
        <div class="row container"><a class="calculator-step-start-again" href="<?php echo esc_url($step_1_url) ?>"><?php _e('Start again') ?></a></div> -->


        <div class="row continue-next-step step-4-buttons">
            <div class="col-lg-6" style="margin-top: 25px;">
                <a class="calculator-step-back ctp-nav" href="<?php echo esc_url($step_1_url) ?>"><?php _e('Back') ?></a>
            </div>
            <div class="col-lg-6 continue-to-right">
                <a class="calculator-step-start-again btn btn-orange" href="<?php echo esc_url($step_1_url) ?>"><?php _e('Start again') ?></a>
            </div>
        </div>
        
        <?php 

        if(strpos(get_the_permalink(), 'embedded-calculator') < 1) {
          echo do_shortcode('[display_ad category="calculator" placement="simple" step="results" size="leaderboard"]'); 
        }
        ?>

    <?php endif; ?>

</div>