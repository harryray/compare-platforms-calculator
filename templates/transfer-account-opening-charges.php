<h2><?php _e('Transfer in/account opening charges', 'cplat') ?></h2>
<div class="platform-data-table cplat-repeat">
    <table class="platform-data">
        <thead>
            <th width="20%"><?php _e('Name', 'cplat'); ?></th>
            <th width="12%"><?php _e('Type', 'cplat'); ?></th>
            <th width="8%"><?php _e('Transfer type', 'cplat'); ?></th>
            <th width="3%"><?php _e('Tiered', 'cplat'); ?></th>
            <th width="8%"><?php _e('AUA from', 'cplat'); ?></th>
            <th width="8%"><?php _e('AUA to', 'cplat'); ?></th>

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
            <tr class="data-row">
                <?php
                $openning_charges = isset($all_data['acc_openning_fee']) ? $all_data['acc_openning_fee'] : [];
                $type             = isset($openning_charges['calc_type']) ? $openning_charges['calc_type'] : '';
                $vat              = isset($openning_charges['vat']) ? $openning_charges['vat'] : 0;
                $gia              = isset($openning_charges['gia']) ? $openning_charges['gia'] : '';
                $isa              = isset($openning_charges['isa']) ? $openning_charges['isa'] : '';
                $sipp             = isset($openning_charges['sipp']) ? $openning_charges['sipp'] : '';
                $jsipp            = isset($openning_charges['jsipp']) ? $openning_charges['jsipp'] : '';
                $jisa             = isset($openning_charges['jisa']) ? $openning_charges['jisa'] : '';
                $onshore_bond             = isset($openning_charges['onshore_bond']) ? $openning_charges['onshore_bond'] : '';
                $offshore_bond            = isset($openning_charges['offshore_bond']) ? $openning_charges['offshore_bond'] : '';
                $lifetime_isa            = isset($openning_charges['lifetime_isa']) ? $openning_charges['lifetime_isa'] : '';
                ?>
                <td><input type="text" name="acc_openning_fee[fee_name]" value="<?php echo isset($openning_charges['fee_name']) ? $openning_charges['fee_name'] : 'Account opening fee'; ?>" readonly></td>
                <td><input type="hidden" name="acc_openning_fee[type]" value="5" readonly />
                    <input type="text" name="acc_openning_fee[type_label]" value="<?php echo isset($openning_charges['type']) ? $openning_charges['type'] : 'Per opening'; ?>" readonly>
                </td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[gia]" value="<?php echo $gia; ?>" /></td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[isa]" value="<?php echo $isa; ?>" /></td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[jisa]" value="<?php echo $jisa; ?>" /></td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[sipp]" value="<?php echo $sipp; ?>" /></td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[childsipp]" value="<?php echo $jsipp; ?>"></td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[onshore_bond]" value="<?php echo $onshore_bond; ?>"></td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[offshore_bond]" value="<?php echo $offshore_bond; ?>"></td>
                <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="acc_openning_fee[lifetime_isa]" value="<?php echo $lifetime_isa; ?>"></td>
                <td><input <?php echo checked($vat, '1') ?> type="checkbox" name="acc_openning_fee[vat]" value="1" />
                    <?php if (isset($openning_charges['id'])) { ?>
                        <input type="hidden" value="<?php echo $openning_charges['id'] ?>" name="acc_openning_fee[fee_id]" id="acc_openning_fee[fee-id]" />
                    <?php } ?>
                    <input type="hidden" value="5" class="fee-type-id" name="acc_openning_fee[fee_type_id]" />
                </td>
                <td><span class="">-</span></td>
            </tr>
            <?php
            // $platform_transfer_account_opening_charges = array(
            // 		__( 'Opening', 'cplat' ),
            // 		__( 'From another provider', 'cplat' ),
            // 		__( 'From an employee share scheme', 'cplat' )
            // 	);
            $count = 0;
            if (isset($all_data['platform_transfer_account_opening_charges'])) :
                foreach ($all_data['platform_transfer_account_opening_charges'] as $data) :
                    $type = isset($data['calc_type']) ? $data['calc_type'] : '';
                    $transfer_type_val = isset($data['transfer_type']) ? $data['transfer_type'] : '';
            ?>
                    <tr class="data-row">
                        <th><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count ?>][fee_name]" value="<?php echo cplat_text($data['fee_name'], ''); ?>"></th>
                        <td>
                            <select name="platform_transfer_account_opening_charges[<?php echo $count; ?>][type]">
                                <?php foreach ($charge_types as $key => $label) : ?>
                                    <option <?php echo selected($type, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><select name="platform_transfer_account_opening_charges[<?php echo $count; ?>][transfer_type]">
                                <?php foreach (Calculator_Acc_Openning_Charges::TRANSFER_TYPES as $key => $label) : ?>
                                    <option <?php echo selected($transfer_type_val, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                                <?php endforeach; ?>
                            </select></td>
                        <td><input <?php echo checked($data['tiered'], '1') ?> type="checkbox" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][tiered]" value="1"></td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][aua_from]" value="<?php echo cplat_text($data['aua_from'], ''); ?>">
                        </td>
                        <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][aua_to]" value="<?php echo cplat_text($data['aua_to'], ''); ?>">
                        </td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][gia]" value="<?php echo cplat_text($data['gia'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][isa]" value="<?php echo cplat_text($data['isa'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][jisa]" value="<?php echo cplat_text($data['jisa'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][sipp]" value="<?php echo cplat_text($data['sipp'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][childsipp]" value="<?php echo cplat_text($data['jsipp'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][onshore_bond]" value="<?php echo cplat_text($data['onshore_bond'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][offshore_bond]" value="<?php echo cplat_text($data['offshore_bond'], ''); ?>"></td>
                        <td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][lifetime_isa]" value="<?php echo cplat_text($data['lifetime_isa'], ''); ?>"></td>
                        <td><input <?php echo checked($data['vat'], '1') ?> type="checkbox" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][vat]" value="1">
                            <?php if (isset($data['id'])) { ?>
                                <input type="hidden" value="<?php echo $data['id'] ?>" class="fee-id" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][fee_id]" /><?php } ?>
                            <input type="hidden" value="5" class="fee-type-id" name="platform_transfer_account_opening_charges[<?php echo $count; ?>][fee_type_id]" />
                        </td>
                        <td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
                    </tr>
            <?php $count++;
                endforeach;
            endif; ?>
            <input type="hidden" name="platform_transfer_account_opening_charges[deleted]" value="" class="deleted" />

            <tr class="data-row template">
                <th><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][fee_name]" value=""></th>
                <td>
                    <select name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][type]">
                        <?php foreach ($charge_types as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][transfer_type]">
                        <?php foreach (Calculator_Acc_Openning_Charges::TRANSFER_TYPES as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td><input type="checkbox" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][tiered]" value="1">
                </td>
                <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][aua_from]" value=""></td>
                <td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][aua_to]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][gia]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][isa]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][jisa]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][sipp]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][childsipp]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][onshore_bond]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][offshore_bond]" value=""></td>
                <td class="number-value"><span class="symbol">%</span><input type="text" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][lifetime_isa]" value=""></td>
                <td><input type="checkbox" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][vat]" value="1">
                    <input type="hidden" value="5" class="fee-type-id" name="platform_transfer_account_opening_charges[{{row-count-placeholder}}][fee_type_id]" />
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