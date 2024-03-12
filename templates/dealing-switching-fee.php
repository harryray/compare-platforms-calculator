<div class="clearfix">
    <div class="span3">
        <h2><?php _e('Dealing fee credits', 'cplat'); ?></h2>
        <h4>Use this to add dealing fee credits</h4>
        <table class="platform-data">
            <thead>
                <tr>
                    <th><?php _e('Dealing fee credits', 'cplat'); ?></th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="dealing_fee_credits" value="<?php echo isset($all_data['dealing_fee_credits']) ? $all_data['dealing_fee_credits'] : '' ?>">
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>
<h2><?php _e('Dealing/switching fees', 'cplat'); ?></h2>
<div class="platform-data-table cplat-repeat">
    <table class="platform-data">
        <thead>
            <th width="10%"><?php _e('Name', 'cplat'); ?></th>
            <th width="12%"><?php _e('Investment Type', 'cplat'); ?></th>
            <th width="6%"><?php _e('Type', 'cplat'); ?></th>
            <th width="3%"><?php _e('Tiered', 'cplat'); ?></th>
            <th width="4%"><?php _e('Bands from', 'cplat'); ?></th>
            <th width="4%"><?php _e('Bands to', 'cplat'); ?></th>
            <th width="4%"><?php _e('GIA', 'cplat'); ?></th>
            <th width="4%"><?php _e('ISA', 'cplat'); ?></th>
            <th width="4%"><?php _e('JISA', 'cplat'); ?></th>
            <th width="4%"><?php _e('Sipp', 'cplat'); ?></th>
            <th width="4%"><?php _e('Child Sipp', 'cplat'); ?></th>
            <th width="4%"><?php _e('Onshore bond', 'cplat'); ?></th>
            <th width="4%"><?php _e('Offshore bond', 'cplat'); ?></th>
            <!-- Ticket#307 -->
            <th width="4%"><?php _e('Lifetime ISAs', 'cplat'); ?></th>
            <!--<th width="3%"><?php //_e( 'Interest Rate', 'cplat' ); 
                                ?></th>-->
            <th width="3%"><?php _e('VAT', 'cplat'); ?></th>
            <th width="4%"><?php _e('Remove', 'cplat'); ?></th>
        </thead>
        <tbody class="container">
            <?php
            $count                            = 0;
            if (isset($all_data['platform_dealing_fa_instruments_fees'])) :
                foreach ($all_data['platform_dealing_fa_instruments_fees'] as $data) :

                    $type = isset($data['calc_type']) ? $data['calc_type'] : '';
                    $selected_investment_type = isset($data['inv_type']) ? $data['inv_type'] : '';
            ?>
                    <tr data-row="<?php echo $count; ?>" class="data-row">
                        <th><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][fa_instrument_name]" value="<?php echo cplat_text($data['fee_name'], ''); ?>"></th>
                        <td>
                            <select name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][investment_type]">
                                <?php foreach ($investment_types as $key => $label) : ?>
                                    <option <?php echo selected($selected_investment_type, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][type]">
                                <?php foreach ($charge_types as $key => $label) : ?>
                                    <option <?php echo selected($type, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input <?php echo checked($data['tiered'], '1') ?> type="checkbox" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][tiered]" value="1"></td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][aua_from]" value="<?php echo cplat_text($data['aua_from'], ''); ?>">
                        </td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][aua_to]" value="<?php echo cplat_text($data['aua_to'], ''); ?>">
                        </td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][gia]" value="<?php echo cplat_text($data['gia'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][isa]" value="<?php echo cplat_text($data['isa'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][jisa]" value="<?php echo cplat_text($data['jisa'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][sipp]" value="<?php echo cplat_text($data['sipp'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][childsipp]" value="<?php echo cplat_text($data['jsipp'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][onshore_bond]" value="<?php echo cplat_text($data['onshore_bond'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][offshore_bond]" value="<?php echo cplat_text($data['offshore_bond'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][lifetime_isa]" value="<?php echo cplat_text($data['lifetime_isa'], ''); ?>"></td>
                        <!--RSPL TASK#22-->
                        <!--<td class="number-value cash_interest_rate_container"><span
                                class="symbol"><?php //echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty( $type ) ? '%' : $currency 
                                                ?></span><input
                                type="text"
                                name="platform_dealing_fa_instruments_fees[<?php //echo $count; 
                                                                            ?>][interest_rate]"
                                value="<?php //echo cplat_text( $data['interest_rate'], '' ); 
                                        ?>" class="cash_interest_rate_field" <?php echo $selected_investment_type === 4 ? '' : 'readonly' ?>></td>-->
                        <td><input <?php echo checked($data['vat'], '1') ?> type="checkbox" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][vat]" value="1">
                            <?php if (isset($data['id'])) { ?>
                                <input type="hidden" value="<?php echo $data['id'] ?>" id="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][fee-id]" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][fee_id]" class="fee-id" /><?php } ?>
                            <input type="hidden" value="4" id="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][fee-type-id]" name="platform_dealing_fa_instruments_fees[<?php echo $count; ?>][fee_type_id]" class="fee-type-id" />
                        </td>
                        <td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
                    </tr>
                <?php $count++;
                endforeach; ?>
                <input type="hidden" name="platform_dealing_fa_instruments_fees[deleted]" value="" class="deleted" /> <?php endif; ?>
            <tr class="template data-row">
                <th><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][fa_instrument_name]" value=""></th>
                <td>
                    <select name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][investment_type]">
                        <?php foreach ($investment_types as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][type]">
                        <?php foreach ($charge_types as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="checkbox" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][tiered]" value="1"></td>
                <td class="currency-value">
                    <!-- <span class="symbol"><?php //echo $currency; 
                                                ?></span> -->
                    <input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][aua_from]" value="">
                </td>
                <td class="currency-value">
                    <!-- <span class="symbol"><?php //echo $currency; 
                                                ?></span> -->
                    <input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][aua_to]" value="">
                </td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][gia]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][isa]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][jisa]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][sipp]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][childsipp]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][onshore_bond]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][offshore_bond]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][lifetime_isa]" value=""></td>
                <!--RSPL Task#37-->
                <!--<td class="number-value cash_interest_rate_container"><span class="symbol">%</span><input type="text"
                                                                         name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][interest_rate]"
                                                                         value="" class="cash_interest_rate_field" readonly></td>-->
                <td><input type="checkbox" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][vat]" value="1">
                    <input type="hidden" value="4" id="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][fee-type-id]" name="platform_dealing_fa_instruments_fees[{{row-count-placeholder}}][fee_type_id]" class="fee-type-id" />
                </td>
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