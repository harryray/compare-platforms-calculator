<h1>comparetheplatform.com - Results</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px;">
    <tr>
        <th style="padding: 10px;text-align: left;">TOTAL VALUE OF SAVINGS & INVESTMENTS</th>
        <td style="padding: 10px;text-align:right">    <?php
			$total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp + $calc_user->data->funds_onshore_bond + $calc_user->data->funds_offshore_bond;
			if ( $calc_user->investment_products === 'yes' && $total_saving_investments > 0 ) {
				$total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp + $calc_user->data->funds_onshore_bond + $calc_user->data->funds_offshore_bond;

			} else {
				$total_saving_investments = $calc_user->data->total_savings_and_investments;
			}
			?>
            <span class="summary-value"><?php echo $currency . " " . number_format( $total_saving_investments ); ?></span>
        </td>
    </tr>
    <tr>
        <th style="padding: 10px;text-align: left;">TOTAL INVESTMENTS IN STOCKS & SHARES</th>
        <td style="padding: 10px;text-align:right">
            <span class="summary-value"><?php echo $currency . " " . number_format( $calc_user->data->ex_instruments_isa + $calc_user->data->ex_instruments_gia + $calc_user->data->ex_instruments_jisa + $calc_user->data->ex_instruments_sipp + $calc_user->data->ex_instruments_jsipp +$calc_user->data->ex_instruments_onshore_bond+$calc_user->data->ex_instruments_offshore_bond ); ?></span>
        </td>
    </tr>
    <tr>
        <th style="padding: 10px;text-align: left;">TRADING FREQUENCY</th>
        <td style="padding: 10px;text-align:right">
            <span class="summary-value"><?php echo esc_attr( $calc_user->data->investment_frequency ); ?></span>
        </td>
    </tr>
    <tr>
        <th style="padding: 10px;text-align: left;">POINT IN TIME</th>
        <td style="padding: 10px;text-align:right">
            <span class="summary-value"><?php echo intval( $calc_user->data->investments_over ); ?></span>
        </td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px;">
	<?php foreach ( $platforms as $platform ) :
		$platform_data = $platform['data'];
		$platform_cost = $platform['cost'];
		$feat_image = wp_get_attachment_image_src( get_post_thumbnail_id( $platform_data->ID ), 'full' );
		?>
        <tr>
            <td style="padding: 10px; max-width: 200px;">
                <img style="max-width:100% !important;height:auto;display:block;"
                     src="<?php echo isset( $feat_image[0] ) ? esc_url( $feat_image[0] ) : ''; ?>"/>
            </td>
            <td style="padding: 10px;">
                <h4><?php echo esc_attr( $platform_data->post_title ); ?></h4>
				<?php echo esc_attr( $platform_data->post_excerpt ); ?>
                Website: <?php echo esc_url( get_post_meta( $platform_data->ID, '_cplat_platform_link', true ) ); ?>
            </td>
            <td style="min-width: 100px" style="padding: 10px;">
                <h4><?php _e( 'COST', 'cplat' ); ?></h4>
				<?php echo $currency . ' ' . esc_money( $platform_cost ); ?>
            </td>
        </tr>
	<?php endforeach; ?>
</table>