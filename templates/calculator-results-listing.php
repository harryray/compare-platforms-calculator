<?php
//
$user = wp_get_current_user();
$main_version = '';
$url_parts = parse_url($_SERVER['HTTP_REFERER']);
parse_str($url_parts['query'], $url_params);
$user = wp_get_current_user();
$inv_management_type = $calc_user->data->inv_management_type;
$user_type = $calc_user->data->user_type;
//$user_type = ( $user_type && $user_type == 'adviser' ? 'advisor' : 'subscriber' );
$roles_commas = $user_type;
if (isset($url_params['main_version']) && !empty($url_params['main_version'])) {
    $main_version = $url_params['main_version'];
}
$investment_products_simplified = $calc_user->data->investment_products_simplified;
if (isset($investment_products_simplified)) {
    $hide_show_simplified_charges = "simplied_display_none";
} else {
    $hide_show_simplified_charges = "simplied_display_block";
}
global $wpdb;
$get_linked_version = $wpdb->get_results("SELECT linked_version FROM linked_portfolio_tbl WHERE main_version LIKE '" . $main_version . "' LIMIT 1", ARRAY_A);
$linked_version_arr = explode(',', $get_linked_version[0]['linked_version']);
$linked_count = count($linked_version_arr);
$inv_type_class = 'inv_type_res_' . $calc_user->data->inv_management_type;
if ( $user_type == 'advisor' ) {
    $result_listing_class = 'adviser-platform-calci-class';
    $calculator_type = 'advisor';
} else {
    $result_listing_class = 'consumer-platform-calci-class';
    $calculator_type = 'subscriber';
    if (isset($investment_products_simplified)) {
        $result_listing_class .= ' simplified-platform-calci-class';
        $calculator_type = 'simplified';
    }
}
if ( $user_type != 'advisor' ) {
    $result_page_page_note_acf = ctp_get_questions_option('result_page_page_consumer_note');
} else {
    $result_page_page_note_acf = ctp_get_questions_option('result_page_page_note');
}

$results_count = count( $platforms );
$sub_count = 0;
foreach ( $platforms as $platform_i ) {
    $platform_data_i = $platform_i['data'];
    $platform_average_rating_i = platform_average_rating( $platform_data_i['platform_id'] );
    if ( 
        ( in_array( $platform_data_i['platform_id'], array(
            4759,
            4760,
            4230
        ) ) && $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS < 1000000 )
        || ( $calc_user->data->recommended_portfolio && $platform_average_rating_i  < 3 )
     ) 
     {
            $sub_count++;
    }
}
if( !empty($sub_count) && $sub_count > 0 ){
    $results_count -= $sub_count;
}
?>

<?php
if ( $user_type == 'subscriber' ) {
    //echo do_shortcode('[ctp-result-advertisement]'); // #326
}

$comparison_url = get_home_url() . '/platform-comparisons/';
if($calc_user->data->embedded_calculator || strpos($url_parts['path'], 'embedded') > 0) {
  $comparison_url = get_home_url() . '/embedded-platform-comparisons/';
}

?>
<div class="results-found"><?php echo $results_count; ?><?php _e(' results found', 'cplat'); ?></div>
<div class="result-item-header clearfix <?php echo $inv_type_class; ?>">
    <div class="result-main-col">Platform</div>
    <div class="result-main-col"><span>Platform <br>Fee</span></div>
    <?php if ($calc_user->data->inv_management_type == 'myself') { ?>
        <div class="result-main-col"><span>RECOMMENDED <br>FUNDS LIST <br>AVAILABLE</span></div>
        <div class="result-main-col"><span>Our Rating</span></div>
    <?php } ?>
    <div class="result-main-col"><span>More Info</span></div>
    <div class="result-main-col">Link</div>
    <div class="result-main-col platform-comparisons-container">
        <span>
            <b data-val="<?php echo $comparison_url; ?>" data-version="<?php echo $_REQUEST['version']; ?>" data-serialized-inputs='<?php echo serialize($calc_user->data); ?>' data-serialized-platforms='<?php echo json_encode($platforms); ?>' class="platform-compare-btn-go disabled" type="button" value="Compare" id="platform-compare-btn-go">Compare</b>
            <span class="select-up-to">(Select up to 3)</span>
        </span>
    </div>
</div>


<?php
$count      = 0;
$show_years = $calc_user->data->investments_today === 'over_years' && $calc_user->data->investments_over > 0 ? true : false;
$div_width  = $show_years ? 'width:54px;' : 'width:74px;';
if ($show_years) {

    $year = 1;
    if ($calc_user->data->investments_over > 1) {
        $year3 = $calc_user->data->investments_over;
        if ($year3 == 3) {
            $year2 = 2;
        } elseif ($year3 == 2) {
            $year2 = null;
        } else {
            $year2 = round($year3 / 2);
        }
    }
}
foreach ($platforms as $platform) {
  $platform_data = $platform['data'];
  var_dump($platform_data['platform_name']);

	// RSPL Task#164
	$continue_listing = 1;
	if ( in_array( $platform_data['platform_id'], array(
			4759,
			4760,
			4230
		) ) && $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS < 1000000 ) {
		$continue_listing = 0;
	}
    $platform_average_rating = platform_average_rating( $platform_data['platform_id'] );
    if( $calc_user->data->recommended_portfolio && $platform_average_rating  < 3  ){
        $continue_listing = 0;
    }
  var_dump("platform_average_rating " . $platform_average_rating);
  var_dump("continue_listing " . $continue_listing);
    
	$status = get_post_status( $platform_data['platform_id'] );
  var_dump("publish_status " . $status);
	if ( $status !== 'publish' ) {
		continue;
	}
	$last  = count( $platforms ) === $count ? 'last' : '';
	$first = $count === 1 ? 'first' : '';

    $platform_cost = $platform['cost'];
    /*Simlified total*/
    if (isset($investment_products_simplified)) {
        $platform_cost = $platform['simplified_total'];
        if( $platform_cost <= 0 ){
            $continue_listing = 0;
        }
    }
    var_dump("continue_listing II " . $continue_listing);

    echo "<br /><hr /><br />";
    /*simplified total*/
    $feat_image    = wp_get_attachment_image_src(get_post_thumbnail_id($platform_data['platform_id']), 'full');

    if ($count % 2 == 0) {
        $stripe = '';
    } else {
        $stripe = 'stripe';
    }
    $num        = '';
    $span_class = '';
    if ($platform['sandbox']) {
        $is_match = preg_match('#([0-9]+)#', $platform['data']["platform_name"], $match);
        if ($is_match) {
            $num        = $match[0];
            $span_class = 'step';
        }
    }

    // RSPL Task#164
    if ($continue_listing == 1) {
        $count++;
?>
        <div class="platform-result-item <?php echo $first . '' . $last . ' ' . $stripe . ' ' . $inv_type_class . ' ' . $result_listing_class; ?>" id="platform-info-<?php echo $platform_data['id'] ?>" data-count="<?php echo $count; ?>">

            <div class="main-row clearfix">
                <div class="result-main-col dog platform-image-div <?php echo 'logo-image-'.get_post_field( 'post_name', $platform_data['platform_id'] );  ?>">
                    <img src="<?php echo isset($feat_image[0]) ? esc_url($feat_image[0]) : ''; ?>" />
                    <div class="num-span"><span class="<?php echo $span_class ?>"> <?php echo $num ?></span></div>
                </div>
                <div class="result-main-col">
                    <div class="main-result-total"><span class="fee-title-mobile">FEE: </span><?php echo $currency . '' . esc_money($platform_cost); ?>
                    </div>
                    <div class="calculator-results-details"><a href="#" data-platform_info="platform-info-<?php echo $platform_data['id'] ?>" class="calculator-results-details-show"><?php _e('Show <br>calculation', 'cplat'); ?></a>
                    </div>
                </div>
                <?php if ($calc_user->data->inv_management_type == 'myself') { ?>
                    <div class="result-main-col">
                        <?php
                        $recomended = get_post_meta($platform_data['platform_id'], '_cplat_recommended_funds_list', true);
                        if ($recomended === 'yes') :
                        ?>
                            <span class="htb_platform_yes">Yes</span>
                        <?php else : ?>
                            <span class="htb_platform_no">No</span>
                        <?php endif; ?>
                    </div>
                    <div class="result-main-col">
                        <?php
                        $rating = get_post_meta($platform_data['platform_id'], '_cplat_rating', true);
                        if($calculator_type !== "advisor") {
                        ?>
                        <div class="result-rating rating-<?php echo $rating; ?>">
                            <div class="rating rating-number"><?php echo $rating; ?>.0</div>
                            <div class="rating-bullets">
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php
                $gtag_cat = ( $user_type == 'advisor' ? 'ADV Platform Page' : 'D2C Platform Page' );
                 ?>
                <div class="result-main-col platform-info">
                    <a target="_blank" href="<?php echo esc_url(get_post_permalink($platform_data['platform_id'])); ?>#" 
                    class="platform-result-more-details" data-vars-ga-category="<?php echo $gtag_cat; ?>" 
				data-vars-ga-action="<?php echo esc_url(get_post_permalink($platform_data['platform_id'])); ?>" 
				data-vars-ga-label="<?php echo get_the_title($platform_data['platform_id']); ?>">
                    <?php _e('Platform <br>Info', 'cplat'); ?></a>
                </div>
                <div class="result-main-col">
                    <?php
                    $platform_typo = ($calc_user->data->inv_management_type == 'myself' ? 'D2C' : 'ADV');
                    if( $calculator_type == 'simplified' ){
                        $platform_typo = 'Simple';
                    }
                    ?>
                    <?php /*<a class="platform-result-website-link" target="_blank"
                       href="<?php echo esc_url( get_post_meta( $platform_data['platform_id'], '_cplat_platform_link', true ) ); ?>"
                       class="platform-web-button"><?php _e( 'Visit The Website', 'cplat' ); ?></a> */ ?>
                    <a id="visit-platform" class="platform-result-website-link ga-track" target="_blank" 
                    href="<?php echo esc_url(get_post_meta($platform_data['platform_id'], '_cplat_platform_link', true)); ?>" 
                    data-vars-ga-category="<?php echo $platform_typo; ?> - Platform Calculator" 
                    data-vars-ga-action="<?php echo esc_url(get_post_meta($platform_data['platform_id'], '_cplat_platform_link', true)); ?>" data-vars-ga-label="Visit The Website - <?php echo get_the_title($platform_data['platform_id']); ?>"><?php _e('Visit The Website', 'cplat'); ?></a>
                </div>

                <div class="result-main-col ctp-comapare-main-col">
                  <label class="compare-check-toggle">
                    <input type="checkbox" class="ctp-comapare-checkbox" name="platform_compare[]" 
                    value="<?php echo $platform_data['platform_id']; ?>" />
                  </label>
                    <?php
                    $robo_comapare_custody_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]);
                    $robo_comapare_product_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]);
                    ?>
                    <input type="hidden" class="robo-comapare-custody-charge" value='<?php echo $robo_comapare_custody_charge; ?>' />
                    <input type="hidden" class="robo-comapare-product-charge" value='<?php echo $robo_comapare_product_charge; ?>' />
                    <input type="hidden" class="robo-comapare-product-charge-total" value='<?php echo $robo['cost']['total']; ?>' />
                    
                    <input type="hidden" class="calculator_type_class" value="<?php echo $calculator_type; ?>"/>
                </div>
            </div>

            <div class="results-details bg-white">

                <div class="debug-calc p-4" style="display: none;">
                    <h3 class="results-details-heading">Custody fees all - Total:
                        <!--RSPL Task#98-->
                        <!--<span>-->
                        <?php //echo cplt_mesc( bcadd( $platform['custody_charges']['total'], $platform['product_charges']['total'], 2 )-$platform['custody_charges']['cash']['interest_rate'] ); 
                        ?>
                        <!--</span>-->
                        <span><?php echo cplt_mesc((float) $platform['custody_charges']['funds_total'] + (float) $platform['custody_charges']['ex_instruments_total'] + (float) $platform['custody_charges']['platform_custody_cash_total'] + (float) $platform['product_charges']['total']); ?></span>
                    </h3>
                    <div class="results-table">
                        <h4 class="results-details-subheading">Custody fees - Funds</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['custody_charges']['funds']['gia']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['isa']); ?></span>
                            </div>
                            <?php if ( $user_type != 'advisor' ) { ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['jisa']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['sipp']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['jsipp']); ?></span>
                            </div>
                            <?php if (in_array('adviser', (array) $user->roles)  || $user_type == 'advisor') {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['onshore_bond']); ?></span>
                                </div>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['offshore_bond']); ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year1</span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['funds_total']); ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['funds_total']); ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['funds_total']); ?></span></span>
                                    </div>
                            <?php
                                }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['custody_charges']['funds_total']); ?></span></span>
                            </div>
                        </div>

                        <h4 class="results-details-subheading <?php echo $hide_show_simplified_charges; ?>">Custody fees - Exchange traded investments: </h4>
                        <div class="results-product-row clearfix <?php echo $hide_show_simplified_charges; ?>">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['gia']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['isa']) ?></span>
                            </div>
                            <?php if ( $user_type != 'advisor') {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['jisa']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['sipp']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['jsipp']) ?></span>
                            </div>
                            <?php if ( $user_type == 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['onshore_bond']) ?></span>
                                </div>

                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['offshore_bond']) ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['ex_instruments']) ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['ex_instruments']) ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['ex_instruments']) ?></span></span>
                                    </div>
                            <?php
                                }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['custody_charges']['ex_instruments_total']) ?></span></span>
                            </div>
                        </div>


                        <h4 class="results-details-subheading <?php echo $hide_show_simplified_charges; ?>">Custody fees - Cash</h4>
                        <div class="results-product-row clearfix <?php echo $hide_show_simplified_charges; ?>">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['gia']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['isa']); ?></span>
                            </div>
                            <?php if ( $user_type != 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['jisa']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['sipp']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['jsipp']); ?></span>
                            </div>
                            <?php if ( $user_type == 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['onshore_bond']); ?></span>
                                </div>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['offshore_bond']); ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year1</span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['platform_custody_cash_total']); ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['platform_custody_cash_total']); ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['platform_custody_cash_total']); ?></span></span>
                                    </div>
                            <?php
                                }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash_total']); ?></span></span>
                            </div>
                        </div>

                        <h4 class="results-details-subheading">Annual Product fees</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['product_charges']['all']['gia']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['isa']) ?></span>
                            </div>
                            <?php if ( $user_type != 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['jisa']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['sipp']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['jsipp']) ?></span>
                            </div>
                            <?php if ( $user_type == 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['onshore_bond']) ?></span>
                                </div>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['offshore_bond']) ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['product_charges']) ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['product_charges']) ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['product_charges']) ?></span></span>
                                    </div>
                            <?php }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['product_charges']['total']) ?></span></span>
                            </div>
                        </div>
                    </div>


                    <!--RSPL Task#98-->
                    <h3 class="results-details-heading <?php echo $hide_show_simplified_charges; ?>">Cash Interest - Total:
                        <span><?php echo cplt_mesc((float) $platform['custody_charges']['cash_total']); ?></span>
                    </h3>
                    <div class="results-table <?php echo $hide_show_simplified_charges; ?>">
                        <h4 class="results-details-subheading">Cash Interest</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['custody_charges']['cash']['gia']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['isa']); ?></span>
                            </div>
                            <?php if ( $user_type != 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['jisa']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['sipp']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['jsipp']); ?></span>
                            </div>
                            <?php if ( $user_type == 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['onshore_bond']); ?></span>
                                </div>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['offshore_bond']); ?></span>
                                </div>
                            <?php } ?>
                            <!--RSPL TASK#22-->
                            <!--<div class="result-product-col" style=<?php /*echo $div_width */ ?>>
                                <span class="resultproduct-label">INTEREST RATE</span>
                                <span
                                        class="result-product-value"><?php /*echo cplt_mesc( $platform['custody_charges']['cash']['interest_rate'] ); */ ?></span>
                            </div>-->
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year1</span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['cash_total']); ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['cash_total']); ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['cash_total']); ?></span></span>
                                    </div>
                            <?php
                                }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['custody_charges']['cash_total']); ?></span></span>
                            </div>
                        </div>
                    </div>


                    <h3 class="results-details-heading <?php echo $hide_show_simplified_charges; ?>">Dealing fees all - Total:
                        <span><?php echo cplt_mesc($platform['dealing_charges']['total']) ?></span>
                    </h3>
                    <div class="results-table <?php echo $hide_show_simplified_charges; ?>">
                        <h4 class="results-details-subheading">Dealing fees - Funds</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['dealing_charges']['funds']['gia']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['funds']['isa']) ?></span>
                            </div>
                            <?php if ((!empty($user->roles) && !in_array('adviser', (array) $user->roles))  || $user_type != 'advisor') {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['funds']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['funds']['jisa']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['funds']['sipp']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['funds']['jsipp']) ?></span>
                            </div>
                            <?php if ( $user_type == 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['funds']['onshore_bond']) ?></span>
                                </div>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['funds']['offshore_bond']) ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['dealing_charges_funds']) ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['dealing_charges_funds']) ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['dealing_charges_funds']) ?></span></span>
                                    </div>
                            <?php }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['dealing_charges']['funds_total']) ?></span></span>
                            </div>
                        </div>

                        <h4 class="results-details-subheading">Dealing fees - exchange-traded investments</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['gia']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['isa']) ?></span>
                            </div>
                            <?php if ( $user_type != 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['jisa']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['sipp']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['jsipp']) ?></span>
                            </div>
                            <?php if ( $user_type == 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['onshore_bond']) ?></span>
                                </div>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments']['offshore_bond']) ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['dealing_charges_ex_instruments']) ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['dealing_charges_ex_instruments']) ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['dealing_charges_ex_instruments']) ?></span></span>
                                    </div>
                            <?php }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['dealing_charges']['ex_instruments_total']) ?></span></span>
                            </div>
                        </div>


                        <h4 class="results-details-subheading">Account Opening Fee</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span class="result-product-label"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['gia']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['isa']) ?></span>
                            </div>
                            <?php if ( $user_type != 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['lifetime_isa']); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['jisa']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['sipp']) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['jsipp']) ?></span>
                            </div>
                            <?php if ( $user_type == 'advisor' ) {  ?>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">ONSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['onshore_bond']) ?></span>
                                </div>
                                <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">OFFSHBDS</span>
                                    <span class="result-product-value"><?php echo cplt_mesc($platform['acc_openning_fee']['all']['offshore_bond']) ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($show_years) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['openning_fee']) ?></span></span>
                                </div>
                                <?php if (isset($year2)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['openning_fee']) ?></span></span>
                                    </div>
                                <?php }
                                if (isset($year3)) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['openning_fee']) ?></span></span>
                                    </div>
                            <?php }
                            } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['acc_openning_fee']['total']) ?></span></span>
                            </div>
                        </div>
                    </div>
                    <?php
                    $advisor_visibility = 'hide';
                    if ( $user_type == 'advisor') {
                        $advisor_visibility = 'show';
                    ?>
                        <div class="advisor_section <?php echo $advisor_visibility . ' ' . $roles_commas ?>">
                            <h3 class="results-details-heading">Adviser Charges
                                <span><?php echo cplt_mesc((float) $platform['adviser_charges']['total']); ?></span>
                            </h3>
                            <div class="results-table">
                                <h4 class="results-details-subheading">Initial Adviser Charges</h4>
                                <div class="results-product-row clearfix">
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Gia</span>
                                        <span class="result-product-label"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['gia']); ?></span>
                                    </div>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Isas</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['isa']); ?></span>
                                    </div>
                                    <?php if ( $user_type != 'advisor' ) {  ?>
                                        <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                            <span class="resultproduct-label">LISAs</span>
                                            <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['lifetime_isa']); ?></span>
                                        </div>
                                    <?php } ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Jisas</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['jisa']); ?></span>
                                    </div>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Sipp</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['sipp']); ?></span>
                                    </div>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">JSIPPs</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['jsipp']); ?></span>
                                    </div>
                                    <?php if (in_array('adviser', (array) $user->roles)  || $user_type == 'advisor') {  ?>
                                        <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                            <span class="resultproduct-label">ONSHBDS</span>
                                            <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['onshore_bond']); ?></span>
                                        </div>
                                        <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                            <span class="resultproduct-label">OFFSHBDS</span>
                                            <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['offshore_bond']); ?></span>
                                        </div>
                                    <?php } ?>
                                    <!--RSPL TASK#22-->
                                    <!--<div class="result-product-col" style=<?php /*echo $div_width */ ?>>
                                        <span class="resultproduct-label">INTEREST RATE</span>
                                        <span
                                                class="result-product-value"><?php /*echo cplt_mesc( $platform['custody_charges']['cash']['interest_rate'] ); */ ?></span>
                                    </div>-->
                                    <?php if ($show_years) { ?>
                                        <div class="result-product-col" style=<?php echo $div_width ?>>
                                            <span class="result-product-label">Year1</span>
                                            <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['initial_adviser_charges_total']) ?></span>
                                        </div>
                                        <?php if (isset($year2)) { ?>
                                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                                <span class="result-product-label">Year<?php echo $year2 ?></span>
                                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['initial_adviser_charges_total']) ?></span></span>
                                            </div>
                                        <?php }
                                        if (isset($year3)) { ?>
                                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                                <span class="result-product-label">Year<?php echo $year3 ?></span>
                                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['initial_adviser_charges_total']) ?></span></span>
                                            </div>
                                    <?php
                                        }
                                    } ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Total</span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges_total']); ?></span></span>
                                    </div>
                                </div>
                                <h4 class="results-details-subheading">Annual Adviser Charges</h4>
                                <div class="results-product-row clearfix">
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Gia</span>
                                        <span class="result-product-label"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['gia']); ?></span>
                                    </div>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Isas</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['isa']); ?></span>
                                    </div>
                                    <?php if ( $user_type != 'advisor' ) {  ?>
                                        <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                            <span class="resultproduct-label">LISAs</span>
                                            <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['lifetime_isa']); ?></span>
                                        </div>
                                    <?php } ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Jisas</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['jisa']); ?></span>
                                    </div>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">Sipp</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['sipp']); ?></span>
                                    </div>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="resultproduct-label">JSIPPs</span>
                                        <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['jsipp']); ?></span>
                                    </div>
                                    <?php if ( $user_type == 'advisor' ) {  ?>
                                        <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                            <span class="resultproduct-label">ONSHBDS</span>
                                            <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['onshore_bond']); ?></span>
                                        </div>
                                        <div class="result-product-col <?php echo $hide_show_simplified_charges; ?>" style=<?php echo $div_width ?>>
                                            <span class="resultproduct-label">OFFSHBDS</span>
                                            <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['offshore_bond']); ?></span>
                                        </div>
                                    <?php } ?>
                                    <?php if ($show_years) { ?>
                                        <div class="result-product-col" style=<?php echo $div_width ?>>
                                            <span class="result-product-label">Year1</span>
                                            <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year}"]['ongoing_adviser_charges_total']); ?></span></span>
                                        </div>
                                        <?php if (isset($year2)) { ?>
                                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                                <span class="result-product-label">Year<?php echo $year2 ?></span>
                                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year2}"]['ongoing_adviser_charges_total']); ?></span></span>
                                            </div>
                                        <?php }
                                        if (isset($year3)) { ?>
                                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                                <span class="result-product-label">Year<?php echo $year3 ?></span>
                                                <span class="result-product-value"><span><?php echo cplt_mesc($platform['year_cost']["year_{$year3}"]['ongoing_adviser_charges_total']); ?></span></span>
                                            </div>
                                    <?php
                                        }
                                    } ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Total</span>
                                        <span class="result-product-value"><span><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges_total']); ?></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End: RSPL Ticket#277 -->
                    <?php
                    }
                    ?>
                    <!-- RSPL#289 Start -->
                    <?php
                    if ($linked_count >= 2 && 
                    ( $roles_commas == 'advisor' || $user_type == 'advisor' || $user_type == 'adviser')) {
                    ?>
                        <div class="linked_portfolio_section">
                            <h3 class="results-details-heading"></h3>
                            <?php
                            $linked_html = '';
                            if ($linked_version_arr) {
                                $linked_html .= '<div class="linked-version-main">';
                                $_version = 1;
                                foreach ($linked_version_arr as $linked_version_p) {
                                    $linked_html .= '<div class="linked-version-loop-content linked-version-loop-' . $linked_version_p . $platform_data['id'] . '">';
                                    $linked_html .= '<h3 class="linked-accordian-title" id="' . $linked_version_p . '" data-pid="' . $platform_data['id'] . '">Portfolio ' . $_version . ' <span class="arrow-minus-plus"></span></h3>';
                                    $linked_html .= '<div class="results-details-accordian" accordian-id="' . $linked_version_p . $platform_data['id'] . '">
                                    <div class="platform-loading" style="display: none;">
                                        <h3>Loading results</h3>
                                        <div class="spinner">
                                            <div class="rect1"></div>
                                            <div class="rect2"></div>
                                            <div class="rect3"></div>
                                            <div class="rect4"></div>
                                            <div class="rect5"></div>
                                        </div>
                                    </div>
                                    </div>';
                                    $linked_html .= '</div>';
                                    $_version++;
                                }
                                $linked_html .= '</div>';
                            }
                            echo $linked_html;
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- RSPL#289 End -->
                    <div class="clearfix">
                        <a href="#" data-platform_info="platform-info-<?php echo $platform_data['id'] ?>" class="close-result-details btn btn-bright-green">Hide Calculations</a>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
