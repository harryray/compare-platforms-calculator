<div class="platform-data-table cplat-repeat">
    <h2><?php _e( 'Cash', 'cplat' ); ?></h2>
    <table class="platform-data">
        <thead>
        <!--RSPL TASK#22-->
        <th width="10%"><?php _e( 'Name', 'cplat' ); ?></th>
        <th width="3%"><?php _e( 'Tiered', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'AUA from', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'AUA to', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'GIA', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'ISA', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'JISA', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'Sipp', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'Child Sipp', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'Onshore bond', 'cplat' ); ?></th>
        <th width="4%"><?php _e( 'Offshore bond', 'cplat' ); ?></th>
        <!-- Ticket#307 -->
        <th width="4%"><?php _e( 'Lifetime ISAs', 'cplat' ); ?></th> 
        <th width="4%"><?php _e( 'Remove', 'cplat' ); ?></th>
        </thead>
        <tbody class="container">
        <?php
        $count = 0;
        $type = 1;
        if ( isset( $all_data['cash_fees'] ) ) :
            foreach ( $all_data['cash_fees'] as $row_key => $data ) :
                ?>
                <tr data-row="<?php echo $row_key; ?>" class="data-row">
                    <th>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][fee_name]" value="<?php echo cplat_text( $data['fee_name'], '' ); ?>">
                        <input type="hidden" name="cash_fees[<?php echo $count; ?>][investment_type]" value="4">
                        <input type="hidden" name="cash_fees[<?php echo $count; ?>][type]" value="1">
                    </th>
                    <td>
                        <input type="checkbox" <?php echo checked( $data['tiered'], '1' ) ?> name="cash_fees[<?php echo $count; ?>][tiered]" value="1">
                    </td>
                    <td class="currency-value">
                        <span class="symbol"><?php echo $currency; ?></span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][aua_from]" value="<?php echo cplat_text( $data['aua_from'], '' ); ?>">
                    </td>
                    <td class="currency-value">
                        <span class="symbol"><?php echo $currency; ?></span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][aua_to]" value="<?php echo cplat_text( $data['aua_to'], '' ); ?>">
                    </td>
                    <td class="number-value">
                        <span class="symbol">$</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][gia]" value="<?php echo cplat_text( $data['gia'], '' ); ?>">
                    </td>
                    <td class="number-value">
                        <span class="symbol">%</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][isa]" value="<?php echo cplat_text( $data['isa'], '' ); ?>">
                    </td>
                    <td class="number-value">
                        <span class="symbol">%</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][jisa]" value="<?php echo cplat_text( $data['jisa'], '' ); ?>">
                    </td>
                    <td class="number-value">
                        <span class="symbol">%</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][sipp]" value="<?php echo cplat_text( $data['sipp'], '' ); ?>">
                    </td>
                    <td class="number-value">
                        <span class="symbol">%</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][childsipp]" value="<?php echo cplat_text( $data['jsipp'], '' ); ?>">
                    </td>
                    <td class="number-value">
                        <span class="symbol">%</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][onshore_bond]" value="<?php echo cplat_text( $data['onshore_bond'], '' ); ?>">
                    </td>
                    <td class="number-value">
                        <span class="symbol">%</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][offshore_bond]" value="<?php echo cplat_text( $data['offshore_bond'], '' ); ?>">
                    </td>
                    <!-- Ticket#307 -->
                    <td class="number-value">
                        <span class="symbol">%</span>
                        <input type="text" name="cash_fees[<?php echo $count; ?>][lifetime_isa]" value="<?php echo cplat_text( $data['lifetime_isa'], '' ); ?>">
                    </td>
                    <td><span class="remove"><?php _e( 'Remove', 'cplat' ); ?></span></td>
                </tr>
                <?php $count ++; endforeach; endif; ?>
        <tr class="data-row template">
            <th>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][fee_name]" value="">
                <input type="hidden" name="cash_fees[{{row-count-placeholder}}][investment_type]" value="4">
                <input type="hidden" name="cash_fees[{{row-count-placeholder}}][type]" value="1">
            </th>
            <td><input type="checkbox" name="cash_fees[{{row-count-placeholder}}][tiered]" value="1"></td>
            <td class="currency-value">
                <span class="symbol"><?php echo $currency; ?></span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][aua_from]" value="">
            </td>
            <td class="currency-value">
                <span class="symbol"><?php echo $currency; ?></span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][aua_to]" value="">
            </td>

            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][gia]" value="">
            </td>
            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][isa]" value="">
            </td>
            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][jisa]" value="">
            </td>
            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][sipp]" value="">
            </td>
            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][childsipp]" value="">
            </td>
            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][onshore_bond]" value="">
            </td>
            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][offshore_bond]" value="">
            </td>
            <!-- Ticket#307 -->
            <td class="number-value">
                <span class="symbol">%</span>
                <input type="text" name="cash_fees[{{row-count-placeholder}}][lifetime_isa]" value="">
            </td>
            <td><span class="remove"><?php _e( 'Remove', 'cplat' ); ?></span></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="12"><span class="add repeatable-btn"><?php _e( 'ADD ROW', 'cplat' ); ?></span></td>
        </tr>
        </tfoot>
    </table>
</div>