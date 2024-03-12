<style>
@media print {
    #print-link {display: none;}
    table {page-break-inside: auto}
    #printThisDocument{display: none;}
    tr {page-break-inside: avoid;page-break-after: auto}
    thead {display: table-header-group}
    #btnPrint{display: none;}
    body.modalprinter * {visibility: hidden;}
    body.modalprinter .modal-dialog.focused {position: absolute;padding: 0;margin: 0;left: 0;top: 0;}
    body.modalprinter .modal-lg.custom-modal-lg{width:100%;margin:0;}
    body.modalprinter .modal-dialog.focused .modal-content {border-width: 0;}
    body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title,
    body.modalprinter .modal-dialog.focused .modal-content .modal-body,
    body.modalprinter .modal-dialog.focused .modal-content .modal-body * {visibility: visible;}
    body.modalprinter .modal-dialog.focused .modal-content .modal-header,
    body.modalprinter .modal-dialog.focused .modal-content .modal-body {padding: 0;}
    body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title {margin-bottom: 20px;}
    body.modalprinter .layout-wide-mode #wrapper{display:none;}
    body.modalprinter .invoice-items tbody td, body.modalprinter .invoice-items thead th{padding:20px 10px !important;}
    body.modalprinter .invoice-items-main tbody td, body.modalprinter .invoice-items-main thead th{padding:20px 10px !important;}
    body.modalprinter .invoice-items tbody td{word-break: break-word;}
    body.modalprinter .invoice-items-main tbody td{word-break: break-word;}
    .content-block{padding-bottom:20px !important;}
    .url-block{width: 100px;white-space: pre-wrap;background:red;}
}
@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
    td > img {
        max-width: 300px !important;
    }
}
.url-block{width: 250px;word-break: break-all;}
</style>
<!-- Result Print Pop modal -->
<div class="fusion-modal modal fade modal-4 modal4 in print-result-modal printable autoprint" id="printThisDocument">
    <div class="modal-dialog focused modal-lg custom-modal-lg">
        <div class="modal-content fusion-modal-content" style="background-color:#fff">
            <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    
            <div class="modal-body fusion-clearfix">
                <div class="custom-responsive" id="custom-print-results-id">
                    <table class="body-wrap"
                           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; width: 100%; margin: 0;">
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                            <!--<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0;"
                                valign="top"></td>-->
                            <td class="container-cls" width="100%"
                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; display: block !important; clear: both !important; margin: 0 auto;"
                                valign="top">
                                <div class="content"
                                     style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; display: block; margin: 0 auto; padding: 20px;">
                                    <table class="main" width="100%" cellpadding="0" cellspacing="0"
                                           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; border-radius: 3px; background-color: #fff; margin: 0; "
                                           bgcolor="#fff">
                                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                            <td class="content-wrap aligncenter"
                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; text-align: center; margin: 0; padding: 20px;"
                                                align="center" valign="top">
                                                <table width="100%" cellpadding="0" cellspacing="0"
                                                       style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">


                                                    <!-- Logo Header -->

                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                        <td class="content-block"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                            valign="top">

                                                            <h1 class="aligncenter"
                                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,'Lucida Grande',sans-serif; box-sizing: border-box; font-size: 32px; color: #000; line-height: 1.2em; font-weight: 500; text-align: center; margin: 40px 0 0;"
                                                                align="center">
                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2015/12/logo.png">
                                                            </h1>

                                                        </td>
                                                    </tr>

                                                    <!-- Subheading -->

                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                        <td class="content-block"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                            valign="top">
                                                            <h3 class="aligncenter"
                                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,'Lucida Grande',sans-serif; box-sizing: border-box; font-size: 21px; color: #000; line-height: 1.2em; font-weight: 400; text-align: center; margin: 20px 0 0;"
                                                                align="center">
                                                                Your Search Results - <?php echo date( 'd/m/Y' ); ?>
                                                            </h3>
                                                        </td>
                                                    </tr>


                                                    <!-- Content -->

                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                        <td class="content-block aligncenter"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                                            align="center" valign="top">

                                                            <table class="invoice"
                                                                   style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; text-align: left; width: 100%; margin: 20px auto;">

                                                                <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px;"
                                                                        valign="top">
                                                                        <table class="invoice-items-main" cellpadding="0" cellspacing="0"
                                                                               style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; width: 100%; margin: 0; border-width: 1px; border-color: #eee; border-style: solid;">

                                                                            <!-- Summary -->

                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px; color:#F1674E"
                                                                                    valign="top" colspan="3">
                                                                                    SUMMARY
                                                                                </td>
                                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;"
                                                                                    valign="top">

                                                                                </td>
                                                                            </tr>


                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474;"
                                                                                    valign="top">
                                                                                    TOTAL VALUE OF SAVINGS & INVESTMENTS
                                                                                </td>

                                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;"
                                                                                    valign="top" colspan="3" align="right">

                                                                                    <?php
                                                                                    //RSPL Task#83
                                                                                    //$total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp;
                                                                                    $total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp + $calc_user->data->funds_isa_cash + $calc_user->data->funds_gia_cash + $calc_user->data->funds_jisa_cash + $calc_user->data->funds_sipp_cash + $calc_user->data->funds_jsipp_cash;
                                                                                    if ( $calc_user->investment_products === 'yes' && $total_saving_investments > 0 ) {
                                                                                        //RSPL Task#83
                                                                                        //$total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp;
                                                                                        $total_saving_investments = $calc_user->data->funds_isa + $calc_user->data->funds_gia + $calc_user->data->funds_jisa + $calc_user->data->funds_sipp + $calc_user->data->funds_jsipp + $calc_user->data->funds_isa_cash + $calc_user->data->funds_gia_cash + $calc_user->data->funds_jisa_cash + $calc_user->data->funds_sipp_cash + $calc_user->data->funds_jsipp_cash;

                                                                                    } else {
                                                                                        //RSPL Task#83
                                                                                        //$total_saving_investments = $calc_user->data->total_savings_and_investments;
                                                                                        $total_saving_investments = $calc_user->data->total_savings_and_investments_total;
                                                                                    }
                                                                                    $total_funds  = get_total_funds( $calc_user );
                                                                                    //RSPL Task#83
                                                                                    $total_cash  = get_total_cash( $calc_user );
                                                                                    $total_shares = $total_saving_investments - $total_funds - $total_cash;
                                                                                    ?>
                                                                                    <?php echo $currency . " " . number_format( $total_saving_investments ); ?>

                                                                                </td>
                                                                            </tr>

                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <td style="width: 43%; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474;">
                                                                                    &nbsp;
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474; line-height: 15px;">
                                                                                    &nbsp;<br/>FUNDS
                                                                                </td>
                                                                                <!--RSPL Task#83-->
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474; line-height: 15px;">
                                                                                    &nbsp;<br/>CASH
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474; line-height: 15px;">
                                                                                    EXCHANGE-TRADED<br/>INSTRUMENTS
                                                                                </td>
                                                                            </tr>
                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <td style="width: 43%; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474;">
                                                                                    TOTAL INVESTMENTS
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;"><?php echo cplat_total_form( $total_funds ) ?>

                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;"><?php echo cplat_total_form( $total_cash ) ?>

                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;">
                                                                                    <?php echo cplat_total_form( $total_shares ); ?>
                                                                                </td>
                                                                            </tr>


                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <td style="width: 43%; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474;">
                                                                                    TRADING FREQUENCY
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;">
                                                                                    <?php echo cplat_sanitize_number( $calc_user->data->investment_frequency_funds ); ?>
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;">
                                                                                    N/A
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;">
                                                                                    <?php echo cplat_sanitize_number( $calc_user->data->investment_frequency_ex_traded ); ?>
                                                                                </td>
                                                                            </tr>


                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <td style="width: 43%; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474;">
                                                                                    AVERAGE TRADING AMOUNT
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;">
                                                                                    <?php echo cplat_total_form( $calc_user->data->average_investment_funds ); ?>
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;">
                                                                                    <?php echo 'N/A'; //echo cplat_total_form( 0 ); ?>
                                                                                </td>
                                                                                <td style="width: 19%; text-align: center; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;">
                                                                                    <?php echo cplat_total_form( $calc_user->data->average_investment_ex_traded ); ?>
                                                                                </td>
                                                                            </tr>

                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#747474;"
                                                                                    colspan="3"><?php
                                                                                    switch ( $calc_user->data->investments_today ) {
                                                                                        case 'today':
                                                                                            echo "BASED ON INVESTMENTS TODAY";
                                                                                            $inv_val = '';
                                                                                            break;
                                                                                        case 'over_years':
                                                                                            echo "TOTAL COST OVER YEARS:";
                                                                                            $inv_val = intval( $calc_user->data->investments_over );
                                                                                            break;
                                                                                        case 'in_x_years':
                                                                                            echo "POINT IN TIME:";
                                                                                            $inv_val = intval( $calc_user->data->investments_in_x_years );
                                                                                            break;
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 5px 10px;color:#F1674E;"
                                                                                    valign="top" align="right">
                                                                                    <?php echo $inv_val; ?>
                                                                                </td>
                                                                            </tr>


                                                                        </table>


                                                                        <!-- Results -->


                                                                        <table class="invoice-items" cellpadding="0" cellspacing="0"
                                                                               style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; width: 100%; margin: 0; border-width: 1px; border-color: #eee; border-style: solid; margin-top: 20px;">
                                                                            <thead>
                                                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                                                <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:16px; color:#747474; text-align:center;"
                                                                                    valign="middle">PLATFORM
                                                                                </th>
                                                                                <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:16px; color:#747474; text-align:center;"
                                                                                    valign="middle">PLATFORM FEE
                                                                                </th>
                                                                                <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:16px; color:#747474; text-align:center;"
                                                                                    valign="middle">RECOMMENDED FUNDS LIST AVAILABLE
                                                                                </th>
                                                                                <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:16px; color:#747474; text-align:center;"
                                                                                    valign="middle">OUR RATING
                                                                                </th>
                                                                                <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:16px; color:#747474; text-align:center;"
                                                                                    valign="middle">MORE INFO
                                                                                </th>
                                                                                <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:16px; color:#747474; text-align:center;"
                                                                                    valign="middle">WEBSITE
                                                                                </th>
                                                                            </tr>

                                                                            </thead>

                                                                            <tbody>
                                                                            <?php
                                                                            $count             = 0;
                                                                            foreach ( $platforms as $platform ) :
                                                                                $platform_data = $platform['data'];

                                                                                // RSPL Task#164
                                                                                $continue_listing = 1;
                                                                                if ( in_array($platform_data['platform_id'], array(4759,4760,4230) ) && $CURRENT_TOTAL_VALUE_OF_SAVINGS_INVESTMENTS < 1000000 ) {
                                                                                    $continue_listing = 0;
                                                                                }

                                                                                $platform_cost = isset( $platform['cost'] ) ? $platform['cost'] : '';
                                                                                $feat_image    = isset( $platform_data['img'] ) ? $platform_data['img'] : '';
//                                                                                $feat_image = str_replace("dev.comparetheplatform.com","comparetheplatform.com",$feat_image);
//                                                                                $feat_image = str_replace("http://localhost/ctp_prod","https://comparetheplatform.com",$feat_image);
//                                                                                $feat_image = str_replace("localhost/ctp_prod","comparetheplatform.com",$feat_image);
                                                                                $info_url      = isset( $platform['data']['info_url'] ) ? $platform['data']['info_url'] : '';
                                                                                $count ++;
                                                                                if ( $count % 2 == 0 ) {
                                                                                    $stripe = 'stripe';
                                                                                } else {
                                                                                    $stripe = '';
                                                                                }

                                                                                // RSPL Task#164
                                                                                if ( $continue_listing == 1 ) {
                                                                                ?>
                                                                                <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; <?php echo $stripe === 'stripe' ? 'background-color:#f8f8f8' : ''; ?>">
                                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px;border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle; width:200px;"
                                                                                        valign="middle">
                                                                                        <img style="max-width: 300px; width:100%;height:auto;display:block;"
                                                                                             src="<?php echo ! empty( $feat_image ) ? esc_url( $feat_image ) : ''; ?>"/>
                                                                                    </td>
                                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;"
                                                                                        valign="top">
                                                                                        <?php echo $currency . ' ' . esc_money( $platform_cost ); ?>
                                                                                    </td>
                                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;"
                                                                                        valign="top">
                                                                                        <?php
                                                                                        // $recommended = $platform_data['recommended'];
                                                                                        $recommended = strtolower( get_post_meta( $platform_data['platform_id'], '_cplat_recommended_funds_list', true ) );
                                                                                        echo $recommended == 'yes' ? 'Yes' : 'No'; ?>
                                                                                    </td>
                                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 0px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;"
                                                                                        valign="top">
                                                                                        <?php
                                                                                        $rating = get_post_meta( $platform_data['platform_id'], '_cplat_rating', true );
                                                                                        // $rating = $platform_data['rating'];
                                                                                        for ( $orange = 1; $orange <= $rating; $orange ++ ) {
                                                                                            echo '<span style="font-size: 32px; color: #F1674E;">&#8226;</span>';
                                                                                            $remain = $orange;
                                                                                        }
                                                                                        for ( $gray = $remain; $gray < 5; $gray ++ ) {
                                                                                            echo '<span style="font-size: 32px; color: #e8e8e8; opacity: 0.6;">&#8226;</span>';
                                                                                        }
                                                                                        ?>
                                                                                        <!--<div class="result-rating rating-<?php /*echo $rating; */?>">
                                                                                            <div class="rating-bullets">
                                                                                                <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array( intval( $rating ), range( 1, 5 ) ) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                     class="bullet"></div>
                                                                                                <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array( intval( $rating ), range( 2, 5 ) ) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                     class="bullet"></div>
                                                                                                <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array( intval( $rating ), range( 3, 5 ) ) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                     class="bullet"></div>
                                                                                                <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array( intval( $rating ), range( 4, 5 ) ) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                     class="bullet"></div>
                                                                                                <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array( intval( $rating ), array( 5 ) ) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                     class="bullet"></div>
                                                                                            </div>
                                                                                        </div>-->
                                                                                    </td>
                                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle; color:#747474;"
                                                                                        class="url-td">
                                                                                        <a style="color:#F1674E; text-decoration: none;"
                                                                                           href="<?php echo $info_url ?>"
                                                                                           target="_blank">PLATFORM <br>INFO</a>
                                                                                    </td>
                                                                                    <?php $plat_url = $platform_data['url'] ?>
                                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;color:#747474;"
                                                                                        class="url-td">
                                                                                        <?php
                                                                                        $plat_url = str_replace( 'http://', '', $plat_url );
                                                                                        $plat_url = str_replace( 'https://', '', $plat_url );
                                                                                        $plat_url = stristr( $plat_url, '/', true );

                                                                                        echo $plat_url;
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } endforeach; ?>

                                                                            </tbody>
                                                                        </table>

                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>


                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                        <td class="content-block aligncenter"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                                            align="center" valign="top">
                                                            <!--                     <a href="http://comparetheplatform.com" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size:516px; color: #348eda; text-decoration: underline; margin: 0;">
                                                                                comparetheplatform.com
                                                                                </a> -->
                                                        </td>
                                                    </tr>

                                                    <!-- Company address -->
                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                        <td class="content-block aligncenter"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                                            align="center" valign="top">

                                                            <!-- Some footer text goes here -->

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                    </table>

                                    <div class="footer"
                                         style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                                        <table width="100%"
                                               style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; margin: 0;">
                                                <td class="aligncenter content-block"
                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;"
                                                    align="center" valign="top">

                                                    <!-- Below page text goes here -->

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                            <!--<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0;"
                                valign="top"></td>-->
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer"><input type="button" class="results-save" data-dismiss="modal" value="Close"></div>
        </div>
    </div>
</div>