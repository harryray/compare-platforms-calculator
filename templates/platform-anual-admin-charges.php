<div class="clearfix">
	<div class="span3">
		<h2><?php _e('Annual product administration charges min/max', 'cplat'); ?></h2>
		<h4>Use this to cap minimum and maximum total annual product administration charge</h4>
		<table class="platform-data">
			<thead>
				<tr>
					<th><?php _e('Min', 'cplat'); ?></th>
					<th><?php _e('Max', 'cplat'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="product_annual_charge_min" value="<?php echo isset($all_data['ann_admin_fee_min']) ? $all_data['ann_admin_fee_min'] : ''; ?>"></td>
					<td class="number-value"><span class="symbol"><?php echo $currency ?></span><input placeholder="" type="text" name="product_annual_charge_max" value="<?php echo isset($all_data['ann_admin_fee_max']) ? $all_data['ann_admin_fee_max'] : ''; ?>"></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<h2><?php _e('Annual product administration charges', 'cplat'); ?></h2>

<div class="platform-data-table cplat-repeat">
	<table class="platform-data">
		<thead>
			<th width="20%"><?php _e('Name', 'cplat'); ?></th>
			<th width="12%"><?php _e('Type', 'cplat'); ?></th>
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
			<?php
			// $platform_product_annual_charges_labels = array(
			// 	__( 'General investment account', 'cplat' ),
			// 	__( 'ISA', 'cplat' ),
			// 	__( 'Junior ISA', 'cplat' ),
			// 	__( 'Sipp', 'cplat' ),
			// 	__( 'Child Sipp', 'cplat' )
			// );
			$count = 0;
			if (isset($all_data['platform_product_annual_chares'])) :
				foreach ($all_data['platform_product_annual_chares'] as $data) :
					$type = isset($data['calc_type']) ? $data['calc_type'] : '';
			?>
					<tr class="data-row">
						<th><input type="text" name="platform_product_annual_chares[<?php echo $count ?>][fee_name]" value="<?php echo cplat_text($data['fee_name'], ''); ?>"></th>
						<td>
							<select name="platform_product_annual_chares[<?php echo $count; ?>][type]">
								<?php foreach ($charge_types as $key => $label) : ?>
									<option <?php echo selected($type, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td><input type="checkbox" <?php echo checked($data['tiered'], '1') ?> name="platform_product_annual_chares[<?php echo $count; ?>][tiered]" value="1"></td>
						<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][aua_from]" value="<?php echo cplat_text($data['aua_from'], ''); ?>"></td>
						<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][aua_to]" value="<?php echo cplat_text($data['aua_to'], ''); ?>"></td>

						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][gia]" value="<?php echo cplat_text($data['gia'], ''); ?>"></td>
						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][isa]" value="<?php echo cplat_text($data['isa'], ''); ?>"></td>
						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][jisa]" value="<?php echo cplat_text($data['jisa'], ''); ?>"></td>
						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][sipp]" value="<?php echo cplat_text($data['sipp'], ''); ?>"></td>
						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][childsipp]" value="<?php echo cplat_text($data['jsipp'], ''); ?>"></td>
						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][onshore_bond]" value="<?php echo cplat_text($data['onshore_bond'], ''); ?>"></td>
						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][offshore_bond]" value="<?php echo cplat_text($data['offshore_bond'], ''); ?>"></td>
						<!-- Ticket#307 -->
						<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span><input type="text" name="platform_product_annual_chares[<?php echo $count; ?>][lifetime_isa]" value="<?php echo cplat_text($data['lifetime_isa'], ''); ?>"></td>

						<td><input type="checkbox" <?php echo checked($data['vat'], '1') ?> name="platform_product_annual_chares[<?php echo $count; ?>][vat]" value="1">
							<?php if (isset($data['id'])) { ?>
								<input type="hidden" value="<?php echo $data['id'] ?>" name="platform_product_annual_chares[<?php echo $count; ?>][fee_id]" id="platform_product_annual_chares[<?php echo $count; ?>][fee-id]" class="fee-id" /><?php } ?>
							<input type="hidden" value="2" name="platform_product_annual_chares[<?php echo $count; ?>][fee_type_id]" id="platform_product_annual_chares[<?php echo $count; ?>][fee-type-id]" />
						</td>

						<td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
					</tr>
				<?php $count++;
				endforeach; ?>
				<input type="hidden" name="platform_product_annual_chares[deleted]" value="" class="deleted" />
			<?php endif; ?>
			<tr class="template data-row">
				<th><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][fee_name]" value=""></th>
				<td>
					<select name="platform_product_annual_chares[{{row-count-placeholder}}][type]">
						<?php foreach ($charge_types as $key => $label) : ?>
							<option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td><input type="checkbox" name="platform_product_annual_chares[{{row-count-placeholder}}][tiered]" value="1"></td>
				<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][aua_from]" value=""></td>
				<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][aua_to]" value=""></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][gia]" value=""></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][isa]" value=""></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][jisa]" value=""></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][sipp]" value=""></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][childsipp]" value=""></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][onshore_bond]" value=""></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][offshore_bond]" value=""></td>
				<!-- Ticket#307 -->
				<td class="number-value"><span class="symbol">%</span><input type="text" name="platform_product_annual_chares[{{row-count-placeholder}}][lifetime_isa]" value=""></td>
				<td><input type="checkbox" name="platform_product_annual_chares[{{row-count-placeholder}}][vat]" value="1">
					<input type="hidden" value="2" name="platform_product_annual_chares[{{row-count-placeholder}}][fee_type_id]" id="platform_product_annual_chares[{{row-count-placeholder}}][fee-type-id]" />
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