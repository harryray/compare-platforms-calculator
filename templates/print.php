<br>
<br>
<a target="_blank" href="javascript:window.print()" class="results-print">Print</a>
<br>
<br>

<fieldset class="calculator-summary">
    <legend><?php _e( 'Your Summary' ) ?></legend>
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <span class="summary-label"><?php _e( 'Total Value of Savings & Investments', 'cpalt' ); ?></span>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
			<?php
			$total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp + $calc_user->data->funds_onshore_bond + $calc_user->data->funds_offshore_bond;
			if ( $calc_user->investment_products === 'yes' && $total_saving_investments > 0 ) {
				$total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp + $calc_user->data->funds_onshore_bond + $calc_user->data->funds_offshore_bond;

			} else {
				$total_saving_investments = $calc_user->data->total_savings_and_investments;
			}
			?>
            <span class="summary-value"><?php echo $currency . " " . number_format( $total_saving_investments ); ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <span class="summary-label"><?php _e( 'Total Investments In Stocks & Shares', 'cpalt' ); ?></span>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <span class="summary-value"><?php echo $currency . " " . number_format( $calc_user->data->ex_instruments_isa + $calc_user->data->ex_instruments_gia + $calc_user->data->ex_instruments_jisa + $calc_user->data->ex_instruments_sipp + $calc_user->data->ex_instruments_jsipp + $calc_user->data->ex_instruments_onshore_bond + $calc_user->data->ex_instruments_offshore_bond  ); ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <span class="summary-label"><?php _e( 'Trading Frequency', 'cpalt' ); ?></span>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <span class="summary-value"><?php echo esc_attr( $calc_user->data->investment_frequency ); ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <span class="summary-label"><?php _e( 'Point In Time', 'cpalt' ); ?></span>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <span class="summary-value"><?php echo intval( $calc_user->data->investments_over ); ?></span>
        </div>
    </div>
</fieldset>
<br>
<br>
<br>
<?php foreach ( $platforms as $platform ) :
	$platform_data = $platform['data'];
	$platform_cost = $platform['cost'];
	$feat_image = wp_get_attachment_image_src( get_post_thumbnail_id( $platform_data->ID ), 'full' );
	?>
    <div class="row print platform-result-item" style="min-height:70px;">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <img src="<?php echo isset( $feat_image[0] ) ? esc_url( $feat_image[0] ) : ''; ?>"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <h4><?php echo esc_attr( $platform_data->post_title ); ?></h4>
            <div class="desc"><?php echo esc_attr( $platform_data->post_excerpt ); ?></div>
            Website: <?php echo esc_url( get_post_meta( $platform_data->ID, '_cplat_platform_link', true ) ); ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <h4><?php _e( 'COST', 'cplat' ); ?></h4>
            <div class="platform-cost"><?php echo $currency . ' ' . esc_money( $platform_cost ); ?></div>
        </div>
    </div>
<?php endforeach; ?>