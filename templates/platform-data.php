<?php get_template_part('template-platform-data-header'); ?>
<h1><?php echo esc_attr($platform_title); ?></h1>
<div class="platform-data">
    <div id="msg"></div>
    <form id="platform-data" class="" action="" method="post">

        <h2 class="active-from-heading"><?php _e('Active from / to', 'cplat'); ?></h2>
        <table class="platform-data">
            <thead>
                <tr>
                    <th><?php _e('From', 'cplat'); ?></th>
                    <th><?php _e('To', 'cplat'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input placeholder="enter date" type="text" id="platform-from" name="active_from" value="<?php echo date('d F Y', strtotime($all_data['active_from'])); ?>"></td>
                    <td><input placeholder="enter date" type="text" id="platform-to" name="active_to" value="<?php echo date('d F Y', strtotime($all_data['active_to'])); ?>"></td>
                </tr>
            </tbody>
        </table>


        <h2 class="supported-products-heading"><?php _e('Supported Funds Products', 'cplat'); ?></h2>
        <table class="platform-data supported-products-table">
            <thead>
                <tr>
                    <th><?php _e('GIA', 'cplat'); ?></th>
                    <th><?php _e('ISA', 'cplat'); ?></th>
                    <th><?php _e('JISA', 'cplat'); ?></th>
                    <th><?php _e('Sipp', 'cplat'); ?></th>
                    <th><?php _e('Child sipp', 'cplat'); ?></th>
                    <th><?php _e('Onshore bond', 'cplat'); ?></th>
                    <th><?php _e('Offshore bond', 'cplat'); ?></th>
                    <!-- Ticket#307 -->
                    <th><?php _e('Lifetime ISAs', 'cplat'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_gia'], '1') ?> name="gia_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_isa'], '1') ?> name="isa_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_jisa'], '1') ?> name="jisa_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_sipp'], '1') ?> name="sipp_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_jsipp'], '1') ?> name="childsipp_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_onshore_bond'], '1') ?> name="onshore_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_offshore_bond'], '1') ?> name="offshore_supported" value="1"></td>
                    <!-- Ticket#307 -->
                    <td><input type="checkbox" <?php echo checked($all_data['sup_fund_lifetime_isa'], '1') ?> name="lifetime_isa_supported" value="1"></td>
                </tr>
            </tbody>
        </table>

        <div class="clearfix">
            <h2><?php _e('Platform Details', 'cplat'); ?></h2>
            <table class="platform-data platform-detail-table">
                <thead>
                    <tr>
                        <th><?php _e('ESG', 'cplat'); ?></th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="checkbox" <?php echo checked($all_data['ethical_investment'], '1') ?> name="ethical_investment" id="ethical_investment" value="1">
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <h2 class="supported-products-heading"><?php _e('Supported Exchange-traded Products', 'cplat'); ?></h2>
        <table class="platform-data supported-products-table">
            <thead>
                <tr>
                    <th><?php _e('GIA', 'cplat'); ?></th>
                    <th><?php _e('ISA', 'cplat'); ?></th>
                    <th><?php _e('JISA', 'cplat'); ?></th>
                    <th><?php _e('Sipp', 'cplat'); ?></th>
                    <th><?php _e('Child sipp', 'cplat'); ?></th>
                    <th><?php _e('Onshore bond', 'cplat'); ?></th>
                    <th><?php _e('Offshore bond', 'cplat'); ?></th>
                    <!-- Ticket#307 -->
                    <th><?php _e('Lifetime ISAs', 'cplat'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_gia'], '1') ?> name="ex_instruments_gia_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_isa'], '1') ?> name="ex_instruments_isa_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_jisa'], '1') ?> name="ex_instruments_jisa_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_sipp'], '1') ?> name="ex_instruments_sipp_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_jsipp'], '1') ?> name="ex_instruments_childsipp_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_onshore_bond'], '1') ?> name="ex_instruments_onshore_supported" value="1"></td>
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_offshore_bond'], '1') ?> name="ex_instruments_offshore_supported" value="1"></td>
                    <!-- Ticket#307 -->
                    <td><input type="checkbox" <?php echo checked($all_data['sup_ex_lifetime_isa'], '1') ?> name="ex_instruments_lifetime_isa_supported" value="1"></td>
                </tr>
            </tbody>
        </table>
        <div class="clearfix">
            <div class="span3">
                <h2><?php _e('All platform/custody fees min/max', 'cplat'); ?></h2>
                <h4>Use this to cap total platform/custody fees for both funds and exchange-traded instruments</h4>
                <table class="platform-data">
                    <thead>
                        <tr>
                            <th><?php _e('Min', 'cplat'); ?></th>
                            <th><?php _e('Max', 'cplat'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="custody_fees_min" value="<?php echo $all_data['all_cust_fee_min']; ?>"></td>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="custody_fees_max" value="<?php echo $all_data['all_cust_fee_max']; ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="span3">

                <h2><?php _e('Funds only platform/custody fees min/max', 'cplat'); ?></h2>
                <h4>This will take precedence over "All Platform/custody fees min/max"</h4>
                <table class="platform-data">
                    <thead>
                        <tr>
                            <th><?php _e('Min', 'cplat'); ?></th>
                            <th><?php _e('Max', 'cplat'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="funds_custody_fees_min" value="<?php echo $all_data['fund_cust_fee_min']; ?>"></td>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="funds_custody_fees_max" value="<?php echo $all_data['fund_cust_fee_max']; ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!--RSPL Task#73 Starts-->
            <div class="span3">

                <h2><?php _e('Cash only platform/custody fees min/max', 'cplat'); ?></h2>
                <h4>This will take precedence over "All Cash fees min/max"</h4>
                <table class="platform-data">
                    <thead>
                        <tr>
                            <th><?php _e('Min', 'cplat'); ?></th>
                            <th><?php _e('Max', 'cplat'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="cash_custody_fees_min" value="<?php echo $all_data['cash_cust_fee_min']; ?>"></td>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="cash_custody_fees_max" value="<?php echo $all_data['cash_cust_fee_max']; ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--RSPL Task#73 Starts-->

            <div class="span3">
                <h2><?php _e('Exchange-traded instruments only platform/custody fees min/max', 'cplat'); ?></h2>
                <h4>This will take precedence over "All Platform/custody fees min/max"</h4>
                <table class="platform-data">
                    <thead>
                        <tr>
                            <th><?php _e('Min', 'cplat'); ?></th>
                            <th><?php _e('Max', 'cplat'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="ex_instruments_custody_fees_min" value="<?php echo $all_data['ex_cust_fee_min']; ?>"></td>
                            <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="ex_instruments_custody_fees_max" value="<?php echo $all_data['ex_cust_fee_max']; ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="platform-data-table cplat-repeat">
            <h2><?php _e('Platform/custody fees', 'cplat'); ?></h2>
            <table class="platform-data">
                <thead>
                    <!--RSPL TASK#22-->
                    <th width="10%"><?php _e('Name', 'cplat'); ?></th>
                    <th width="12%"><?php _e('Investment Type', 'cplat'); ?></th>
                    <th width="6%"><?php _e('Type', 'cplat'); ?></th>
                    <th width="3%"><?php _e('Tiered', 'cplat'); ?></th>
                    <th width="4%"><?php _e('AUA from', 'cplat'); ?></th>
                    <th width="4%"><?php _e('AUA to', 'cplat'); ?></th>
                    <th width="4%"><?php _e('GIA', 'cplat'); ?></th>
                    <th width="4%"><?php _e('ISA', 'cplat'); ?></th>
                    <th width="4%"><?php _e('JISA', 'cplat'); ?></th>
                    <th width="4%"><?php _e('Sipp', 'cplat'); ?></th>
                    <th width="4%"><?php _e('Child Sipp', 'cplat'); ?></th>
                    <th width="4%"><?php _e('Onshore bond', 'cplat'); ?></th>
                    <th width="4%"><?php _e('Offshore bond', 'cplat'); ?></th>
                    <!-- Ticket#307 -->
                    <th width="3%"><?php _e('Lifetime ISAs', 'cplat'); ?></th>
                    <!--                <th width="3%">--><?php //_e( 'Interest Rate', 'cplat' ); 
                                                            ?>
                    <!--</th>-->
                    <th width="3%"><?php _e('VAT', 'cplat'); ?></th>
                    <th width="4%"><?php _e('Remove', 'cplat'); ?></th>
                </thead>
                <tbody class="container">
                    <?php
                    $count = 0;
                    if (isset($all_data['platform_fees'])) :
                        foreach ($all_data['platform_fees'] as $row_key => $data) :
                    ?>
                            <tr data-row="<?php echo $row_key; ?>" class="data-row">
                                <th><input type="text" name="platform_fees[<?php echo $count; ?>][fee_name]" value="<?php echo cplat_text($data['fee_name'], ''); ?>"></th>
                                <?php $selected_investment_type = isset($data['inv_type']) ? $data['inv_type'] : ''; ?>
                                <td>
                                    <select name="platform_fees[<?php echo $count; ?>][investment_type]">
                                        <?php foreach ($investment_types as $key => $label) : ?>
                                            <option <?php echo selected($selected_investment_type, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="platform_fees[<?php echo $count; ?>][type]">
                                        <?php foreach ($charge_types as $key => $label) :
                                            $type = isset($data['calc_type']) ? $data['calc_type'] : '';
                                        ?>
                                            <option <?php echo selected($type, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <td><input type="checkbox" <?php echo checked($data['tiered'], '1') ?> name="platform_fees[<?php echo $count; ?>][tiered]" value="1"></td>

                                <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][aua_from]" value="<?php echo cplat_text($data['aua_from'], ''); ?>"></td>
                                <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][aua_to]" value="<?php echo cplat_text($data['aua_to'], ''); ?>"></td>

                                <td class="number-value"><span class="symbol"><?php echo $type == Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][gia]" value="<?php echo cplat_text($data['gia'], ''); ?>"></td>
                                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][isa]" value="<?php echo cplat_text($data['isa'], ''); ?>"></td>
                                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][jisa]" value="<?php echo cplat_text($data['jisa'], ''); ?>"></td>
                                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][sipp]" value="<?php echo cplat_text($data['sipp'], ''); ?>"></td>
                                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][childsipp]" value="<?php echo cplat_text($data['jsipp'], ''); ?>"></td>
                                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][onshore_bond]" value="<?php echo cplat_text($data['onshore_bond'], ''); ?>"></td>
                                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][offshore_bond]" value="<?php echo cplat_text($data['offshore_bond'], ''); ?>"></td>
                                <!--  Ticket#307 -->
                                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_fees[<?php echo $count; ?>][lifetime_isa]" value="<?php echo cplat_text($data['lifetime_isa'], ''); ?>"></td>
                                <!--RSPL TASK#22-->
                                <!--<td class="number-value cash_interest_rate_container"><span
                                        class="symbol"><?php /*echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty( $type ) ? '%' : $currency*/ ?></span><input
                                        type="text" name="platform_fees[<?php /*echo $count;*/ ?>][interest_rate]"
                                        value="<?php /*echo cplat_text( $data['interest_rate'], '' );*/ ?>" class="cash_interest_rate_field" <?php /*echo $selected_investment_type === 4 ? '' : 'readonly'*/ ?>></td>-->
                                <td><input type="checkbox" <?php echo checked($data['vat'], '1') ?> name="platform_fees[<?php echo $count; ?>][vat]" value="1"></td>
                                <td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
                            </tr>
                    <?php $count++;
                        endforeach;
                    endif; ?>
                    <tr class="data-row template">
                        <th><input type="text" name="platform_fees[{{row-count-placeholder}}][fee_name]" value=""></th>
                        <td>
                            <select name="platform_fees[{{row-count-placeholder}}][investment_type]">
                                <?php foreach ($investment_types as $key => $label) : ?>
                                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="platform_fees[{{row-count-placeholder}}][type]">
                                <?php foreach ($charge_types as $key => $label) : ?>
                                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>

                        <td><input type="checkbox" name="platform_fees[{{row-count-placeholder}}][tiered]" value="1"></td>

                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_fees[{{row-count-placeholder}}][aua_from]" value=""></td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_fees[{{row-count-placeholder}}][aua_to]" value=""></td>

                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][gia]" value="" td>
                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][isa]" value=""></td>
                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][jisa]" value=""></td>
                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][sipp]" value=""></td>
                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][childsipp]" value=""></td>
                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][onshore_bond]" value=""></td>
                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][offshore_bond]" value=""></td>
                        <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_fees[{{row-count-placeholder}}][lifetime_isa]" value=""></td>
                        <!--RSPL TASK#22-->
                        <!--<td class="number-value cash_interest_rate_container"><span class="symbol">%</span><input type="text"
                                                                                 name="platform_fees[{{row-count-placeholder}}][interest_rate]"
                                                                                 value="" class="cash_interest_rate_field" readonly></td>-->
                        <td><input type="checkbox" name="platform_fees[{{row-count-placeholder}}][vat]" value="1"></td>
                        <td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="12"><span class="add repeatable-btn"><?php _e('ADD ROW', 'cplat'); ?></span></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php
        include 'cash-data.php';
        include 'platform-anual-admin-charges.php';
        include 'investment-fee.php';
        include 'dealing-switching-fee.php';
        include 'transfer-account-opening-charges.php';
        include 'transfer-out-closure.php';
        include 'transfer-incentives.php';
        include 'sipp-charges.php';
        ?>

        <!-- <a href="#" class="clone-row add-btn"><?php //_e( 'Add Row', 'cplat' ); 
                                                    ?></a> -->
        <input type="hidden" name="platform_data_action" value="save">
        <input type="hidden" id="version_id" name="version_id" value="<?php echo intval($version_id); ?>">
        <input type="hidden" name="save_platform_data" value="true">
        <input type="hidden" name="platform_id" value="<?php echo $platform_id; ?>">
        <!-- <input class="save-vendor-data save-btn" type="submit" value="<?php //_e( 'Save', 'cplat' ); 
                                                                            ?>"> -->

        <button type="submit" class="save-vendor-data save-btn">
            <i class="fa fa-circle-o-notch fa-spin-animate"></i> <i class="fa fa-check"></i>
            <span><?php _e('Save', 'cplat'); ?></span>
        </button>
    </form>
</div>