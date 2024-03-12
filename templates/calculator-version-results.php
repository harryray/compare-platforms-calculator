<?php
$platform = $platforms;
$platform_data = $platform['data'];
$platform_cost = $platform['cost'];
?>
<div class="results-details">
    <div class="debug-calc">
        <h3 class="results-details-heading">Custody fees all - Total:<span><?php echo cplt_mesc((float) $platform['custody_charges']['funds_total'] + (float) $platform['custody_charges']['ex_instruments_total'] + (float) $platform['custody_charges']['platform_custody_cash_total'] + (float) $platform['product_charges']['total']); ?></span>
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
                <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                    <div class="result-product-col" style=<?php echo $div_width ?>>
                        <span class="resultproduct-label">LISAs</span>
                        <span
                                class="result-product-value"><?php echo cplt_mesc( $platform['custody_charges']['funds']['lifetime_isa'] ); ?></span>
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
                <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">ONSHBDS</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['funds']['onshore_bond']); ?></span>
                </div>
                <div class="result-product-col" style=<?php echo $div_width ?>>
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

            <h4 class="results-details-subheading">Custody fees - Exchange traded investments: </h4>
            <div class="results-product-row clearfix">
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">Gia</span>
                    <span class="result-product-label"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['gia']) ?></span>
                </div>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">Isas</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['isa']) ?></span>
                </div>
                <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                    <div class="result-product-col" style=<?php echo $div_width ?>>
                        <span class="resultproduct-label">LISAs</span>
                        <span
                                class="result-product-value"><?php echo cplt_mesc( $platform['custody_charges']['ex_instruments']['lifetime_isa'] ); ?></span>
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
                <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">ONSHBDS</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['ex_instruments']['onshore_bond']) ?></span>
                </div>

                <div class="result-product-col" style=<?php echo $div_width ?>>
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


            <h4 class="results-details-subheading">Custody fees - Cash</h4>
            <div class="results-product-row clearfix">
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">Gia</span>
                    <span class="result-product-label"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['gia']); ?></span>
                </div>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">Isas</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['isa']); ?></span>
                </div>
                <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                    <div class="result-product-col" style=<?php echo $div_width ?>>
                        <span class="resultproduct-label">LISAs</span>
                        <span
                                class="result-product-value"><?php echo cplt_mesc( $platform['custody_charges']['platform_custody_cash']['lifetime_isa'] ); ?></span>
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
                <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">ONSHBDS</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['platform_custody_cash']['onshore_bond']); ?></span>
                </div>
                <div class="result-product-col" style=<?php echo $div_width ?>>
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
                <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                    <div class="result-product-col" style=<?php echo $div_width ?>>
                        <span class="resultproduct-label">LISAs</span>
                        <span
                                class="result-product-value"><?php echo cplt_mesc( $platform['product_charges']['all']['lifetime_isa'] ); ?></span>
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
                <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="result-product-label">ONSHBDS</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['product_charges']['all']['onshore_bond']) ?></span>
                </div>
                <div class="result-product-col" style=<?php echo $div_width ?>>
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
        <h3 class="results-details-heading">Cash Interest - Total:
            <span><?php echo cplt_mesc((float) $platform['custody_charges']['cash_total']); ?></span>
        </h3>
        <div class="results-table">
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
                <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                    <div class="result-product-col" style=<?php echo $div_width ?>>
                        <span class="resultproduct-label">LISAs</span>
                        <span
                                class="result-product-value"><?php echo cplt_mesc( $platform['custody_charges']['cash']['lifetime_isa'] ); ?></span>
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
                <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">ONSHBDS</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['onshore_bond']); ?></span>
                </div>
                <div class="result-product-col" style=<?php echo $div_width ?>>
                    <span class="resultproduct-label">OFFSHBDS</span>
                    <span class="result-product-value"><?php echo cplt_mesc($platform['custody_charges']['cash']['offshore_bond']); ?></span>
                </div>
                <?php } ?>
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

        <h3 class="results-details-heading">Dealing fees all - Total:
                        <span><?php echo cplt_mesc( $platform['dealing_charges']['total'] ) ?></span></h3>
                    <div class="results-table">
                        <h4 class="results-details-subheading">Dealing fees - Funds</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span
                                        class="result-product-label"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['gia'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['isa'] ) ?></span>
                            </div>
                            <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span
                                            class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['lifetime_isa'] ); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['jisa'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['sipp'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['jsipp'] ) ?></span>
                            </div>
                            <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">ONSHBDS</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['onshore_bond'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">OFFSHBDS</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['funds']['offshore_bond'] ) ?></span>
                            </div>
                            <?php } ?>
	                        <?php if ( $show_years ) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span
                                            class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year}"]['dealing_charges_funds'] ) ?></span></span>
                                </div>
		                        <?php if ( isset( $year2 ) ) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span
                                                class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year2}"]['dealing_charges_funds'] ) ?></span></span>
                                    </div>
		                        <?php }
		                        if ( isset( $year3 ) ) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span
                                                class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year3}"]['dealing_charges_funds'] ) ?></span></span>
                                    </div>
		                        <?php }
	                        } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span
                                        class="result-product-value"><span><?php echo cplt_mesc( $platform['dealing_charges']['funds_total'] ) ?></span></span>
                            </div>
                        </div>

                        <h4 class="results-details-subheading">Dealing fees - exchange-traded investments</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span
                                        class="result-product-label"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['gia'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['isa'] ) ?></span>
                            </div>
                            <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span
                                            class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['lifetime_isa'] ); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['jisa'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['sipp'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['jsipp'] ) ?></span>
                            </div>
                            <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">ONSHBDS</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['onshore_bond'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">OFFSHBDS</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments']['offshore_bond'] ) ?></span>
                            </div>
                            <?php } ?>
	                        <?php if ( $show_years ) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span
                                            class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year}"]['dealing_charges_ex_instruments'] ) ?></span></span>
                                </div>
		                        <?php if ( isset( $year2 ) ) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span
                                                class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year2}"]['dealing_charges_ex_instruments'] ) ?></span></span>
                                    </div>
		                        <?php }
		                        if ( isset( $year3 ) ) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span
                                                class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year3}"]['dealing_charges_ex_instruments'] ) ?></span></span>
                                    </div>
		                        <?php }
	                        } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span
                                        class="result-product-value"><span><?php echo cplt_mesc( $platform['dealing_charges']['ex_instruments_total'] ) ?></span></span>
                            </div>
                        </div>


                        <h4 class="results-details-subheading">Account Opening Fee</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Gia</span>
                                <span
                                        class="result-product-label"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['gia'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Isas</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['isa'] ) ?></span>
                            </div>
                            <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span
                                            class="result-product-value"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['lifetime_isa'] ); ?></span>
                                </div>
                            <?php } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Jisas</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['jisa'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">Sipp</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['sipp'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">JSIPPs</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['jsipp'] ) ?></span>
                            </div>
                            <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">ONSHBDS</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['onshore_bond'] ) ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">OFFSHBDS</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $platform['acc_openning_fee']['all']['offshore_bond'] ) ?></span>
                            </div>
                            <?php } ?>
	                        <?php if ( $show_years ) { ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="result-product-label">Year<?php echo $year ?></span>
                                    <span
                                            class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year}"]['openning_fee'] ) ?></span></span>
                                </div>
		                        <?php if ( isset( $year2 ) ) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year2 ?></span>
                                        <span
                                                class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year2}"]['openning_fee'] ) ?></span></span>
                                    </div>
		                        <?php }
		                        if ( isset( $year3 ) ) { ?>
                                    <div class="result-product-col" style=<?php echo $div_width ?>>
                                        <span class="result-product-label">Year<?php echo $year3 ?></span>
                                        <span
                                                class="result-product-value"><span><?php echo cplt_mesc( $platform['year_cost']["year_{$year3}"]['openning_fee'] ) ?></span></span>
                                    </div>
		                        <?php }
	                        } ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="result-product-label">Total</span>
                                <span
                                        class="result-product-value"><span><?php echo cplt_mesc( $platform['acc_openning_fee']['total'] ) ?></span></span>
                            </div>
                        </div>
                    </div>


        <h3 class="results-details-heading">Adviser Charges all - Total:
            <span><?php echo cplt_mesc($platform['adviser_charges']['total']) ?></span></h3>
        <div class="results-table">
            <?php
            $user = wp_get_current_user();
            $roles_arr = $user->roles;
            $roles_commas = implode(',', $roles_arr);
            $inv_management_type = $calc_user->data->inv_management_type;
            $advisor_visibility = 'hide';
            $user_type = $calc_user->data->user_type;
            if (in_array('adviser', (array) $user->roles) || $inv_management_type == 'advisor' || $user_type == 'advisor') {
                $advisor_visibility = 'show';
            ?>
                <div class="advisor_section <?php echo $advisor_visibility . ' ' . $user_type ?>">
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
                            <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span
                                            class="result-product-value"><?php echo cplt_mesc( $platform['adviser_charges']['initial_adviser_charges']['lifetime_isa'] ); ?></span>
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
                            <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">ONSHBDS</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['onshore_bond']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">OFFSHBDS</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['initial_adviser_charges']['offshore_bond']); ?></span>
                            </div>
                            <?php } ?>
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
                            <?php if ( ( !empty( $user->roles ) && !in_array( 'adviser', (array) $user->roles  ) )  || $user_type != 'advisor'  ) {  ?>
                                <div class="result-product-col" style=<?php echo $div_width ?>>
                                    <span class="resultproduct-label">LISAs</span>
                                    <span
                                            class="result-product-value"><?php echo cplt_mesc( $platform['adviser_charges']['annual_adviser_charges']['lifetime_isa'] ); ?></span>
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
                            <?php if ( ( !empty( $user->roles ) && in_array( 'adviser', (array) $user->roles  )  )  || $user_type == 'advisor'  ) {  ?>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
                                <span class="resultproduct-label">ONSHBDS</span>
                                <span class="result-product-value"><?php echo cplt_mesc($platform['adviser_charges']['annual_adviser_charges']['onshore_bond']); ?></span>
                            </div>
                            <div class="result-product-col" style=<?php echo $div_width ?>>
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
        </div>
    </div>