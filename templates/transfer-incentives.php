<div class="clearfix">
    <div class="span3">
        <h2><?php _e('Incentive charges min/max', 'cplat'); ?></h2>
        <h4>Use this to cap minimum and maximum total incentive charge</h4>
        <table class="platform-data">
            <thead>
                <tr>
                    <th><?php _e('Min', 'cplat'); ?></th>
                    <th><?php _e('Max', 'cplat'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="incentive_min" value="<?php echo isset($all_data['incentive_min']) ? $all_data['incentive_min'] : ''; ?>">
                    </td>
                    <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="incentive_max" value="<?php echo isset($all_data['incentive_max']) ? $all_data['incentive_max'] : ''; ?>">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<h2><?php _e('Transfer incentive charges', 'cplat'); ?></h2>
<div class="platform-data-table cplat-repeat">

    <table class="platform-data">
        <thead>
            <th width="20%"><?php _e('Name', 'cplat'); ?></th>
            <th width="12%"><?php _e('Type', 'cplat'); ?></th>
            <th width="7%"><?php _e('Transfer type', 'cplat'); ?></th>
            <th width="3%"><?php _e('Tiered', 'cplat'); ?></th>
            <th width="8%"><?php _e('AUA from', 'cplat'); ?></th>
            <th width="8%"><?php _e('AUA to', 'cplat'); ?></th>
            <th width="12%"><?php _e('Max', 'cplat'); ?></th>
            <th width="6%"><?php _e('GIA', 'cplat'); ?></th>
            <th width="6%"><?php _e('ISA', 'cplat'); ?></th>
            <th width="6%"><?php _e('JISA', 'cplat'); ?></th>
            <th width="6%"><?php _e('Sipp', 'cplat'); ?></th>
            <th width="6%"><?php _e('Child Sipp', 'cplat'); ?></th>
            <th width="6%"><?php _e('Onshore bond', 'cplat'); ?></th>
            <th width="6%"><?php _e('Offshore bond', 'cplat'); ?></th>
            <!-- Ticket#307 -->
			<th width="6%"><?php _e('Lifetime ISAs', 'cplat'); ?></th>
            <th width="3%"><?php _e('VAT', 'cplat'); ?></th>
            <th width="4%"><?php _e('Remove', 'cplat'); ?></th>
        </thead>
        <tbody class="container">
            <?php
            // $platform_investment_charges = array(
            // 		__( 'Set-up flexi-access drawdown', 'cplat' ),
            // 		__( 'Flexi-access drawdown annual fee', 'cplat' ),
            // 		__( 'Income payments', 'cplat' ),
            // 		__( 'Ad hoc income payments', 'cplat' ),
            // 		__( 'Variation of income payments', 'cplat' ),
            // 		__( 'Tax free lump sum', 'cplat' ),
            // 		__( 'Uncrystallised funds pension lump sum', 'cplat' ),
            // 		__( 'Annuity purchase', 'cplat' ),
            // 		__( 'Annuity purchase', 'cplat' ),
            // 		__( 'Valuation/ death claim', 'cplat' ),
            // 		__( 'Pension splitting on divorce', 'cplat' ),
            // 		__( 'Repayment of excess contribution', 'cplat' ),
            // 		__( 'Crystallisation events', 'cplat' )
            // 	);
            $count = 0;

            if (isset($all_data['platform_incentive_charges'])) :
                foreach ($all_data['platform_incentive_charges'] as $data) :
                    $type = isset($data['calc_type']) ? $data['calc_type'] : '';
                    $transfer_type_val = isset($data['transfer_type']) ? $data['transfer_type'] : '';
            ?>
                    <tr class="data-row">
                        <th><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][fee_name]" value="<?php echo cplat_text($data['fee_name'], ''); ?>"></th>
                        <td>
                            <select name="platform_incentive_charges[<?php echo $count; ?>][type]">
                                <?php foreach ($charge_types as $key => $label) : ?>
                                    <option <?php echo selected($type, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="platform_incentive_charges[<?php echo $count; ?>][transfer_type]">
                                <?php foreach (Calculator_Acc_Openning_Charges::TRANSFER_TYPES as $key => $label) : ?>
                                    <option <?php echo selected($transfer_type_val, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="checkbox" name="platform_incentive_charges[<?php echo $count; ?>][tiered]" value="1" <?php echo checked($data['tiered'], '1') ?>></td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][aua_from]" value="<?php echo cplat_text($data['aua_from'], ''); ?>">
                        </td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][aua_to]" value="<?php echo cplat_text($data['aua_to'], ''); ?>">
                        </td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][max_cap]" value="<?php echo cplat_text($data['max_cap'], ''); ?>">
                        </td>
                        <td class="number-value"><span class="symbol"><?php echo $type === 'ad_valorem' || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][gia]" value="<?php echo cplat_text($data['gia'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === 'ad_valorem' || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][isa]" value="<?php echo cplat_text($data['isa'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === 'ad_valorem' || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][jisa]" value="<?php echo cplat_text($data['jisa'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === 'ad_valorem' || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][sipp]" value="<?php echo cplat_text($data['sipp'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === 'ad_valorem' || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][childsipp]" value="<?php echo cplat_text($data['jsipp'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][onshore_bond]" value="<?php echo cplat_text($data['onshore_bond'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][offshore_bond]" value="<?php echo cplat_text($data['offshore_bond'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_incentive_charges[<?php echo $count; ?>][lifetime_isa]" value="<?php echo cplat_text($data['lifetime_isa'], ''); ?>"></td>
                        <td><input <?php echo checked($data['vat'], '1') ?> type="checkbox" name="platform_incentive_charges[<?php echo $count; ?>][vat]" value="1">
                            <input type="hidden" value="8" class="fee-type-id" name="platform_incentive_charges[<?php echo $count; ?>][fee_type_id]" />
                        </td>

                        <td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
                    </tr>
            <?php $count++;
                endforeach;
            endif; ?>
            <input type="hidden" name="platform_incentive_charges[deleted]" value="" class="deleted" />
            <tr class="data-row template">
                <th><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][fee_name]" value=""></th>
                <td>
                    <select name="platform_incentive_charges[{{row-count-placeholder}}][type]">
                        <?php foreach ($charge_types as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="platform_incentive_charges[{{row-count-placeholder}}][transfer_type]">
                        <?php foreach (Calculator_Acc_Openning_Charges::TRANSFER_TYPES as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="checkbox" name="platform_incentive_charges[{{row-count-placeholder}}][tiered]" value="1">
                </td>
                <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][aua_from]" value=""></td>
                <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][aua_to]" value=""></td>
                <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][max_cap]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][gia]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][isa]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][jisa]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][sipp]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][childsipp]" value="">
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][onshore_bond]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][offshore_bond]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_incentive_charges[{{row-count-placeholder}}][lifetime_isa]" value=""></td>
                <input type="hidden" value="8" class="fee-type-id" name="platform_incentive_charges[{{row-count-placeholder}}][fee_type_id]" /></td>
                <td><input type="checkbox" name="platform_incentive_charges[{{row-count-placeholder}}][vat]" value="1"></td>
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