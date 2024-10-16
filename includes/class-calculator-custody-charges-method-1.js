_ = require('underscore-node');
constants = require('./const');
cp = require('./class-calculator-product-charges');
let cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
let p_cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
let funds_temp_amount = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
let etrade_temp_amount = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
let custody_cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
let custody_etrade_temp_amount = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
module.exports.Calculator_Custody_Charges_Method_1 = class Calculator_Custody_Charges_Method_1 {

    constructor() {
        this.vat_rate = 20;
        this.fee_type_id = 1;
        this.show = true;
        this.is_excluded = false;
        // RSPL TASK#37
        this.cash_fee_type_id = 10;
        // Ticket 243
        this.totals = {};
    }


    set_user_data(user_data) {

        this.user_data = user_data;

        this.number_of_trades = this.user_data['investment_frequency'];

        // User didn't provide product values

        //RSPL Task#25
        let total_all_assets_funds = parseFloat(this.user_data['funds_gia']);
        total_all_assets_funds += parseFloat(this.user_data['funds_isa']);
        total_all_assets_funds += parseFloat(this.user_data['funds_jisa']);
        total_all_assets_funds += parseFloat(this.user_data['funds_sipp']);
        total_all_assets_funds += parseFloat(this.user_data['funds_jsipp']);
        total_all_assets_funds += parseFloat(this.user_data['funds_onshore_bond']);
        total_all_assets_funds += parseFloat(this.user_data['funds_offshore_bond']);
        total_all_assets_funds += parseFloat(this.user_data['funds_lifetime_isa']);
        this.total_all_assets_funds = total_all_assets_funds;

        //RSPL Task#25
        let total_all_assets_ex = parseFloat(this.user_data['ex_instruments_gia']);
        total_all_assets_ex += parseFloat(this.user_data['ex_instruments_isa']);
        total_all_assets_ex += parseFloat(this.user_data['ex_instruments_jisa']);
        total_all_assets_ex += parseFloat(this.user_data['ex_instruments_sipp']);
        total_all_assets_ex += parseFloat(this.user_data['ex_instruments_jsipp']);
        total_all_assets_ex += parseFloat(this.user_data['ex_instruments_onshore_bond']);
        total_all_assets_ex += parseFloat(this.user_data['ex_instruments_offshore_bond']);
        total_all_assets_ex += parseFloat(this.user_data['ex_instruments_lifetime_isa']);
        this.total_all_assets_ex = total_all_assets_ex;

        //RSPL Task#37
        let total_all_assets_cash = parseFloat(this.user_data['funds_gia_cash']);
        total_all_assets_cash += parseFloat(this.user_data['funds_isa_cash']);
        total_all_assets_cash += parseFloat(this.user_data['funds_jisa_cash']);
        total_all_assets_cash += parseFloat(this.user_data['funds_sipp_cash']);
        total_all_assets_cash += parseFloat(this.user_data['funds_jsipp_cash']);
        total_all_assets_cash += parseFloat(this.user_data['funds_onshore_bond_cash']);
        total_all_assets_cash += parseFloat(this.user_data['funds_offshore_bond_cash']);
        total_all_assets_cash += parseFloat(this.user_data['funds_lifetime_isa_cash']);
        this.total_all_assets_cash = total_all_assets_cash;

        let total_all_assets = parseFloat(this.user_data['ex_instruments_gia']);
        total_all_assets += parseFloat(this.user_data['ex_instruments_isa']);
        total_all_assets += parseFloat(this.user_data['ex_instruments_jisa']);
        total_all_assets += parseFloat(this.user_data['ex_instruments_sipp']);
        total_all_assets += parseFloat(this.user_data['ex_instruments_jsipp']);
        total_all_assets += parseFloat(this.user_data['ex_instruments_onshore_bond']);
        total_all_assets += parseFloat(this.user_data['ex_instruments_offshore_bond']);
        total_all_assets += parseFloat(this.user_data['ex_instruments_lifetime_isa']);

        total_all_assets += parseFloat(this.user_data['funds_gia']);
        total_all_assets += parseFloat(this.user_data['funds_isa']);
        total_all_assets += parseFloat(this.user_data['funds_jisa']);
        total_all_assets += parseFloat(this.user_data['funds_sipp']);
        total_all_assets += parseFloat(this.user_data['funds_jsipp']);
        total_all_assets += parseFloat(this.user_data['funds_onshore_bond']);
        total_all_assets += parseFloat(this.user_data['funds_offshore_bond']);
        total_all_assets += parseFloat(this.user_data['funds_lifetime_isa']);

        total_all_assets += parseFloat(this.user_data['funds_gia_cash']);
        total_all_assets += parseFloat(this.user_data['funds_isa_cash']);
        total_all_assets += parseFloat(this.user_data['funds_jisa_cash']);
        total_all_assets += parseFloat(this.user_data['funds_sipp_cash']);
        total_all_assets += parseFloat(this.user_data['funds_jsipp_cash']);
        total_all_assets += parseFloat(this.user_data['funds_onshore_bond_cash']);
        total_all_assets += parseFloat(this.user_data['funds_offshore_bond_cash']);
        total_all_assets += parseFloat(this.user_data['funds_lifetime_isa_cash']);
        // this.total_all_assets = _.isNumber(user_data.total_savings_and_investments) ? user_data.total_savings_and_investments : total_all_assets;
        //RSPL TASK#22
        // this.total_all_assets = _.isNumber(user_data.total_savings_and_investments_total) ? user_data.total_savings_and_investments_total-total_all_assets_cash : total_all_assets-total_all_assets_cash;
        // RSPL Task#122
        // this.total_all_assets = _.isNumber(user_data.total_savings_and_investments_total) ? user_data.total_savings_and_investments_total : total_all_assets;
        // RSPL Task#162
        this.total_all_assets = total_all_assets;

    }

    set_platform_data(platform, platform_data) {
        let $this = this;
        this.platform_data = platform_data;
        this.vat_rate = !_.isUndefined(platform_data.vat_val) ? platform_data.vat_val : this.vat_rate;
        this.platform = platform;
        this.custody_fees = _.where(platform_data, { 'fee_type_id': this.fee_type_id }) || null;
        // RSPL TASK#37
        this.cash_fees = _.where(platform_data, { 'fee_type_id': this.cash_fee_type_id }) || null;

        this.funds_tiered = this.is_tiered(constants.INV_TYPE_FUND);
        this.ex_instruments_tiered = this.is_tiered(constants.INV_TYPE_EX_TRADED);
        // RSPL TASK#37
        this.cash_tiered = this.is_cash_tiered(constants.INV_TYPE_CASH);
        // RSPL Task#98
        this.platform_custody_cash_tiered = this.is_tiered(constants.INV_TYPE_CASH);

        this.platform_data_funds = {};
        this.platform_data_ex_instruments = {};
        this.platform_data_cash = {};
        // RSPL Task#98
        this.platform_custody_data_cash = {};

        // RSPL TASK#37
        if (!_.isEmpty(this.cash_fees)) {
            _.each(this.cash_fees, function (value, key) {
                if (value['inv_type'] === constants.INV_TYPE_CASH) {
                    $this.platform_data_cash[key] = value;
                }
            });
            this.platform_data_cash = $this.platform_data_cash;
        }
        if (!_.isEmpty(this.custody_fees)) {
            _.each(this.custody_fees, function (value, key) {
                if (value['inv_type'] === constants.INV_TYPE_FUND) {
                    $this.platform_data_funds[key] = value;
                }
                if (value['inv_type'] === constants.INV_TYPE_EX_TRADED) {
                    $this.platform_data_ex_instruments[key] = value;
                }
                if (value['inv_type'] === constants.INV_TYPE_CASH) {
                    $this.platform_custody_data_cash[key] = value;
                }
            });
            this.platform_data_funds = $this.platform_data_funds;
            this.platform_data_ex_instruments = $this.platform_data_ex_instruments;
            this.platform_custody_data_cash = $this.platform_custody_data_cash;
            let aua_to = _.pluck(this.custody_fees, 'aua_to');

            if (aua_to.length) {
                if (!_.contains(aua_to, null) && this.user_data.total_all > _.max(aua_to)) {
                    this.is_excluded = true;
                }
            }
        }

    }

    get_total(funds_total, ex_total, cash_total, pcash_total) {
        ex_total = _.isNaN(ex_total) ? 0 : ex_total;
        funds_total = _.isNaN(funds_total) ? 0 : funds_total;
        cash_total = _.isNaN(cash_total) ? 0 : cash_total;
        pcash_total = _.isNaN(pcash_total) ? 0 : pcash_total;
        let all_total = funds_total + ex_total - cash_total + pcash_total;

        if (all_total <= 0) {
            all_total = 0;
        }
        // Is there a funds specific cap ?
        let funds_cap = constants.calc_num(this.platform['fund_cust_fee_min']) || constants.calc_num(this.platform['fund_cust_fee_max']);

        // Is there a ex instruments specific cap ?
        let ex_instruments_cap = constants.calc_num(this.platform['ex_cust_fee_min']) || constants.calc_num(this.platform['ex_cust_fee_max']);

        //RSPL Task#37
        // Is there a cash specific cap ?
        let cash_cap = constants.calc_num(this.platform['cash_cust_fee_min']) || constants.calc_num(this.platform['cash_cust_fee_max']);

        // Is there a general price cap ?
        let all_cap = constants.calc_num(this.platform['all_cust_fee_min']) || constants.calc_num(this.platform['all_cust_fee_max']);
        //die(var_dump( all_total ));

        //RSPL Task#37
        if ((funds_cap === false || ex_instruments_cap === false || cash_cap === false) && all_cap === true) {

            let min = ctp_clean_number(this.platform['all_cust_fee_min']);
            let max = ctp_clean_number(this.platform['all_cust_fee_max']);

            if (all_total < min && constants.calc_num(min)) {
                all_total = min;
            } else if (all_total > max && constants.calc_num(max)) {
                all_total = max;
            }
        }
        return all_total;
    }

    get_total_old(year) {

        let funds_total = this.total_funds(year, false);
        let ex_instruments_total = this.total_ex_instruments(year, false);
        /*Begin : Ticket #243 */
        let interest_and_platform_cash_charges = this.interest_and_platform_cash_charges(year, false);
        let cash_total = interest_and_platform_cash_charges.cash_interest;
        let platform_custody_cash_total = interest_and_platform_cash_charges.custody_cash;
        /*End : Ticket #243 */
        //let cash_total = this.total_cash_only(year);
        // RSPL Task#98
        //let platform_custody_cash_total = this.platform_custody_total_cash_only(year);
        let all_total = funds_total + ex_instruments_total - cash_total + platform_custody_cash_total;
        //let all_total = funds_total + ex_instruments_total + platform_custody_cash_total;
        if (all_total <= 0) {
            all_total = 0;
        }

        // Is there a funds specific cap ?
        let funds_cap = constants.calc_num(this.platform['fund_cust_fee_min']) || constants.calc_num(this.platform['fund_cust_fee_max']);

        // Is there a ex instruments specific cap ?
        let ex_instruments_cap = constants.calc_num(this.platform['ex_cust_fee_min']) || constants.calc_num(this.platform['ex_cust_fee_max']);

        //RSPL Task#37
        // Is there a cash specific cap ?
        let cash_cap = constants.calc_num(this.platform['cash_cust_fee_min']) || constants.calc_num(this.platform['cash_cust_fee_max']);

        // Is there a general price cap ?
        let all_cap = constants.calc_num(this.platform['all_cust_fee_min']) || constants.calc_num(this.platform['all_cust_fee_max']);
        //die(var_dump( all_total ));

        //RSPL Task#37
        if ((funds_cap === false || ex_instruments_cap === false || cash_cap === false) && all_cap === true) {

            let min = ctp_clean_number(this.platform['all_cust_fee_min']);
            let max = ctp_clean_number(this.platform['all_cust_fee_max']);

            if (all_total < min && constants.calc_num(min)) {
                all_total = min;
            } else if (all_total > max && constants.calc_num(max)) {
                all_total = max;
            }
        }

        return all_total;
    }

    total_funds(year, dealing_cr, openingCharges, ongoing_adviser, productCharges) {
        let total = 0;
        let $this = this;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        this.funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0,'lifetime_isa': 0 };
        let prod_fcustody = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };
        /*Begin: Ticket#243*/
        if (year == 1) {
            funds_temp_amount = {
                'gia': 0,
                'isa': 0,
                'jisa': 0,
                'sipp': 0,
                'jsipp': 0,
                'onshore_bond': 0,
                'offshore_bond': 0,
                'lifetime_isa': 0
            };
        }
        let over_years = $this.user_data['investments_today'];
        let user_inputed_year = 0;
        if (over_years === 'in_x_years') {
            user_inputed_year = this.user_data.investments_in_x_years;
        } else if (over_years === 'over_years') {
            user_inputed_year = this.user_data.investments_over;
        }
        let is_growth = this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;
        /*End: Ticket#243*/
        if (this.funds_tiered) {
            /**
             * Calculate cost for each product
             */
            const tempCustodyValues = $this.getValuesForCustody();
            _.each(products, function (product) {
                let total_product = 0;
                let s_calculable_amount = 0;
                const cashValue = $this.user_data['funds_' + product + '_cash'];
                const fundsPlanning = $this.user_data['planning_' + product] - $this.user_data['planning_ex_instruments_' + product];
                if (year == 1 || over_years === 'in_x_years') {
                    s_calculable_amount = ((parseFloat($this.total_all_assets_funds) + parseFloat($this.total_all_assets_ex)) * growth_rate);
                } else {
                    s_calculable_amount = tempCustodyValues.allAssets - tempCustodyValues.cash;
                }
                if ($this.platform_custody_cash_tiered) {
                    if (year == 1 || over_years === 'in_x_years') {
                        const totalCashInterest = $this.getTotalCashInterest() || 0;
                        s_calculable_amount = ((parseFloat($this.total_all_assets_funds) + parseFloat($this.total_all_assets_ex)) * growth_rate) + (parseFloat($this.total_all_assets_cash) + totalCashInterest);
                    } else {
                        s_calculable_amount = tempCustodyValues.allAssets;
                    }
                }
                if (isNaN(s_calculable_amount)) {
                    s_calculable_amount = 0;
                }
                let funds_tier = $this.which_tier(constants.INV_TYPE_FUND, s_calculable_amount);
                /*Begin: Ticket#243*/
                let amount_for_calculation = 0;
                if (over_years === 'in_x_years' || over_years === 'over_years') {
                    if (year == 1 || over_years === 'in_x_years') {
                        funds_temp_amount[product] = $this.user_data['funds_' + product];
                        if (year === 1 && (cashValue < openingCharges[product] && funds_temp_amount[product] > openingCharges[product])) {
                            funds_temp_amount[product] = funds_temp_amount[product] - openingCharges[product];
                        }
                        funds_temp_amount[product] = funds_temp_amount[product] * growth_rate;
                    } else {
                        funds_temp_amount[product] = (parseFloat(funds_temp_amount[product]) + parseFloat(fundsPlanning)) * growth_rate
                    }
                    amount_for_calculation = funds_temp_amount[product];
                } else {
                    amount_for_calculation = $this.user_data['funds_' + product];
                }
                /*End: Ticket#243*/
                total_product = $this.get_cost_of_product(
                    $this.platform_data_funds[funds_tier]['calc_type'],
                    $this.platform_data_funds[funds_tier][product],
                    amount_for_calculation //Ticket#243
                );

                if ($this.has_vat($this.platform_data_funds[funds_tier]['vat'])) {
                    total_product += $this.get_vat_amount(total_product);
                }
                /*Begin: Ticket#243*/
                // if (over_years === 'in_x_years' || over_years === 'over_years') {
                //     funds_temp_amount[product] = funds_temp_amount[product] - total_product - dealing_cr[product];
                //     if (cashValue < ongoing_adviser[product] && funds_temp_amount[product] > ongoing_adviser[product]) {
                //         funds_temp_amount[product] = funds_temp_amount[product] - ongoing_adviser[product];
                //     }
                //     if (productCharges && cashValue < productCharges[product] && funds_temp_amount[product] > productCharges[product]) {
                //         funds_temp_amount[product] = funds_temp_amount[product] - productCharges[product];
                //     }
                //     $this.funds[product] = total_product;
                // } else {
                //     $this.funds[product] = total_product;
                // }
                total = total + total_product;
                prod_fcustody[product] = total_product;
            });
        } else {
            total = 0;
            _.each(this.platform_data_funds, function (_value, key) {
                _.each(products, function (product, _index) {
                    let total_product = 0;
                    let amount_for_calculation = 0;
                    const cashValue = $this.user_data['funds_' + product + '_cash'];
                    const fundsPlanning = $this.user_data['planning_' + product] - $this.user_data['planning_ex_instruments_' + product];
                    if (over_years === 'in_x_years' || over_years === 'over_years') {
                        if (year == 1 || over_years === 'in_x_years') {
                            funds_temp_amount[product] = $this.user_data['funds_' + product];
                            if (year === 1 && (cashValue < openingCharges[product] && funds_temp_amount[product] > openingCharges[product])) {
                                funds_temp_amount[product] = funds_temp_amount[product] - openingCharges[product];
                            }
                            funds_temp_amount[product] = funds_temp_amount[product] * growth_rate;
                        } else {
                            funds_temp_amount[product] = (parseFloat(funds_temp_amount[product]) + parseFloat(fundsPlanning)) * growth_rate
                        }
                        amount_for_calculation = funds_temp_amount[product];
                    } else {
                        amount_for_calculation = $this.user_data['funds_' + product];
                    }
                    /*End: Ticket#243*/
                    total_product = $this.get_cost_of_product(
                        $this.platform_data_funds[key]['calc_type'],
                        $this.platform_data_funds[key][product],
                        amount_for_calculation
                    );
                    if ($this.has_vat($this.platform_data_funds[key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                    /*Begin: Ticket#243*/
                    // if (over_years === 'in_x_years' || over_years === 'over_years') {
                    //     funds_temp_amount[product] = funds_temp_amount[product] - total_product - dealing_cr[product];
                    //     if (cashValue < ongoing_adviser[product] && funds_temp_amount[product] > ongoing_adviser[product]) {
                    //         funds_temp_amount[product] = funds_temp_amount[product] - ongoing_adviser[product];
                    //     }
                    //     $this.user_data['funds_' + product] = funds_temp_amount[product];
                    //     if (productCharges && cashValue < productCharges[product] && funds_temp_amount[product] > productCharges[product]) {
                    //         funds_temp_amount[product] = funds_temp_amount[product] - productCharges[product];
                    //     }
                    //     $this.funds[product] = total_product;
                    // } else {
                    //     $this.funds[product] = total_product;
                    // }
                    total = total + total_product;
                    prod_fcustody[product] = total_product;
                    /*End: Ticket#243*/
                });
            });
        }
        // Is there a funds specific cap ?
        let funds_cap = constants.calc_num(this.platform['fund_cust_fee_min']) || constants.calc_num(this.platform['fund_cust_fee_max']);
        if (funds_cap && total > 0) {
            let min = ctp_clean_number(this.platform['fund_cust_fee_min']);
            let max = ctp_clean_number(this.platform['fund_cust_fee_max']);
            let isChanged = false;
            if (total < min && constants.calc_num(min)) {
                total = min;
                isChanged = true;
            } else if (total > max && constants.calc_num(max)) {
                total = max;
                isChanged = true;
            }

            // Divide cap values of products
            if (isChanged && constants.isPlatformCapped(this.platform.info_url)) {
                const productRatio = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
                _.each(products, function (product, _index) {
                    productRatio[product] = parseFloat($this.user_data['funds_' + product] / $this.total_all_assets_funds);
                    if (productRatio[product] > 0) {
                        prod_fcustody[product] = total * productRatio[product];
                    } else {
                        prod_fcustody[product] = 0;
                    }
                });
            }
        }

        _.each(products, function (product, _index) {
            const cashValue = $this.user_data['funds_' + product + '_cash'];
            if (!$this.funds_tiered && _.size($this.platform_data_funds) === 0) {
                if (year == 1 || over_years === 'in_x_years') {
                    funds_temp_amount[product] = $this.user_data['funds_' + product];
                    funds_temp_amount[product] = funds_temp_amount[product] * growth_rate;
                } else if (year > 1 && over_years === 'over_years') {
                    const fundsPlanning = $this.user_data['planning_' + product] - $this.user_data['planning_ex_instruments_' + product];
                    funds_temp_amount[product] = parseFloat(funds_temp_amount[product] + fundsPlanning) * growth_rate;
                }
            }
            if (over_years === 'in_x_years' || over_years === 'over_years') {
                funds_temp_amount[product] = funds_temp_amount[product] - prod_fcustody[product] - dealing_cr[product];
                if (cashValue < ongoing_adviser[product] && funds_temp_amount[product] > ongoing_adviser[product]) {
                    funds_temp_amount[product] = funds_temp_amount[product] - ongoing_adviser[product];
                }
                if (productCharges && cashValue < productCharges[product] && funds_temp_amount[product] > productCharges[product]) {
                    funds_temp_amount[product] = funds_temp_amount[product] - productCharges[product];
                }
                $this.funds[product] = prod_fcustody[product];
            } else {
                $this.funds[product] = prod_fcustody[product];
            }
        });
        return { 'total': total, 'prod_fcustody': prod_fcustody }
    }

    total_ex_instruments(year, ext_dealing, openingCharges, ongoing_adviser, productCharges) {
        let total = 0;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        let prod_excustody = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };
        this.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };
        let $this = this;
        /*Begin: Ticket#243*/
        if (year == 1) {
            etrade_temp_amount = {
                'gia': 0,
                'isa': 0,
                'jisa': 0,
                'sipp': 0,
                'jsipp': 0,
                'onshore_bond': 0,
                'offshore_bond': 0,
                'lifetime_isa': 0
            };
        }
        let over_years = $this.user_data['investments_today'];
        let user_inputed_year = 0;
        if (over_years === 'in_x_years') {
            user_inputed_year = this.user_data.investments_in_x_years;
        } else if (over_years === 'over_years') {
            user_inputed_year = this.user_data.investments_over;
        }
        let is_growth = this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;
        custody_etrade_temp_amount = _.clone(etrade_temp_amount);
        /*End: Ticket#243*/
        if (this.ex_instruments_tiered) {
            /**
             * Calculate cost for each product
             */
            const tempCustodyValues = $this.getValuesForCustody();
            _.each(products, function (product) {
                let total_product = 0;
                const fundsAmount = $this.user_data['funds_' + product];
                const cashAmount = $this.user_data['funds_' + product + '_cash'];
                const etradePlanning = $this.user_data['planning_ex_instruments_' + product];
                // RSPL Task#134
                // let s_calculable_amount = $this.total_all_assets - $this.total_all_assets_cash;

                // Ticket-266
                let s_calculable_amount = 0;
                if (year == 1 || over_years === 'in_x_years') {
                    s_calculable_amount = ((parseFloat($this.total_all_assets_funds) + parseFloat($this.total_all_assets_ex)) * growth_rate);
                } else {
                    s_calculable_amount = tempCustodyValues.allAssets - tempCustodyValues.cash;
                }
                if ($this.platform_custody_cash_tiered) {
                    if (year == 1 || over_years === 'in_x_years') {
                        const totalCashInterest = $this.getTotalCashInterest() || 0;
                        s_calculable_amount = ((parseFloat($this.total_all_assets_funds) + parseFloat($this.total_all_assets_ex)) * growth_rate) + (parseFloat($this.total_all_assets_cash) + totalCashInterest);
                    } else {
                        s_calculable_amount = tempCustodyValues.allAssets;
                    }
                }
                if (isNaN(s_calculable_amount)) {
                    s_calculable_amount = 0;
                }
                let ex_instruments_tier = $this.which_tier(constants.INV_TYPE_EX_TRADED, s_calculable_amount);
                /*Begin: Ticket#243*/
                let amount_for_calculation = 0;
                if (over_years === 'in_x_years' || over_years === 'over_years') {
                    if (year == 1 || over_years === 'in_x_years') {
                        etrade_temp_amount[product] = $this.user_data['ex_instruments_' + product];
                        if (year === 1 && (cashAmount < openingCharges[product] && fundsAmount < openingCharges[product] && etrade_temp_amount[product] > openingCharges[product])) {
                            etrade_temp_amount[product] = etrade_temp_amount[product] - openingCharges[product];
                        }
                        etrade_temp_amount[product] = etrade_temp_amount[product] * growth_rate;
                    } else {
                        etrade_temp_amount[product] = (parseFloat(etrade_temp_amount[product]) + parseFloat(etradePlanning)) * growth_rate
                    }
                    amount_for_calculation = etrade_temp_amount[product];
                } else {
                    amount_for_calculation = $this.user_data['ex_instruments_' + product];
                }
                /*End: Ticket#243*/
                total_product = $this.get_cost_of_product(
                    $this.platform_data_ex_instruments[ex_instruments_tier]['calc_type'],
                    $this.platform_data_ex_instruments[ex_instruments_tier][product],
                    //$this.user_data['ex_instruments_' + product]
                    amount_for_calculation
                );

                if ($this.has_vat($this.platform_data_ex_instruments[ex_instruments_tier]['vat'])) {
                    total_product += $this.get_vat_amount(total_product);
                }
                /*Begin: Ticket#243*/
                // if (over_years === 'in_x_years' || over_years === 'over_years') {
                //     if (is_funds == true) {
                //         etrade_temp_amount[product] = etrade_temp_amount[product] - total_product - ext_dealing[product];
                //         if (cashAmount < ongoing_adviser[product] && fundsAmount < ongoing_adviser[product] && etrade_temp_amount[product] > ongoing_adviser[product]) {
                //             etrade_temp_amount[product] = etrade_temp_amount[product] - ongoing_adviser[product];
                //         }
                //         if (productCharges && cashAmount < productCharges[product] && fundsAmount < productCharges[product] && etrade_temp_amount[product] > productCharges[product]) {
                //             etrade_temp_amount[product] = etrade_temp_amount[product] - productCharges[product];
                //         }
                //         $this.ex_instruments[product] = total_product;
                //     }
                // } else {
                //     $this.ex_instruments[product] = total_product;
                // }
                total = total + total_product;
                prod_excustody[product] = total_product;
                /*End: Ticket#243*/
            });
        } else {

            total = 0;
            _.each(this.platform_data_ex_instruments, function (value, key) {
                _.each(products, function (product) {
                    /*Begin: Ticket#243*/
                    const etradePlanning = $this.user_data['planning_ex_instruments_' + product];
                    let amount_for_calculation = 0;
                    const fundsAmount = $this.user_data['funds_' + product];
                    const cashAmount = $this.user_data['funds_' + product + '_cash'];
                    if (over_years === 'in_x_years' || over_years === 'over_years') {
                        if (year == 1 || over_years === 'in_x_years') {
                            etrade_temp_amount[product] = $this.user_data['ex_instruments_' + product];
                            if (year === 1 && (cashAmount < openingCharges[product] && fundsAmount < openingCharges[product] && etrade_temp_amount[product] > openingCharges[product])) {
                                etrade_temp_amount[product] = etrade_temp_amount[product] - openingCharges[product];
                            }
                            etrade_temp_amount[product] = etrade_temp_amount[product] * growth_rate;
                        } else {
                            etrade_temp_amount[product] = (parseFloat(etrade_temp_amount[product]) + parseFloat(etradePlanning)) * growth_rate
                        }
                        amount_for_calculation = etrade_temp_amount[product];
                    } else {
                        amount_for_calculation = $this.user_data['ex_instruments_' + product];
                    }
                    /*End: Ticket#243*/
                    let total_product = $this.get_cost_of_product(
                        $this.platform_data_ex_instruments[key]['calc_type'],
                        $this.platform_data_ex_instruments[key][product],
                        //$this.user_data['ex_instruments_' + product]
                        amount_for_calculation
                    );
                    if ($this.has_vat($this.platform_data_ex_instruments[key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                    /*Begin: Ticket#243*/
                    // if (over_years === 'in_x_years' || over_years === 'over_years') {
                    //     if (is_funds == true) {
                    //         etrade_temp_amount[product] = etrade_temp_amount[product] - total_product - ext_dealing[product];
                    //         if (cashAmount < ongoing_adviser[product] && fundsAmount < ongoing_adviser[product] && etrade_temp_amount[product] > ongoing_adviser[product]) {
                    //             etrade_temp_amount[product] = etrade_temp_amount[product] - ongoing_adviser[product];
                    //         }
                    //         if (productCharges && cashAmount < productCharges[product] && fundsAmount < productCharges[product] && etrade_temp_amount[product] > productCharges[product]) {
                    //             etrade_temp_amount[product] = etrade_temp_amount[product] - productCharges[product];
                    //         }
                    //         $this.ex_instruments[product] = total_product;
                    //     }
                    // } else {
                    //     $this.ex_instruments[product] = total_product;
                    // }
                    /*End: Ticket#243*/
                    total = total + total_product;
                    prod_excustody[product] = total_product;
                });
            });
        }
        // Is there a ex instruments specific cap ?
        let ex_instruments_cap = constants.calc_num(this.platform['ex_cust_fee_min']) || constants.calc_num(this.platform['ex_cust_fee_max']);
        if (ex_instruments_cap && total > 0) {
            let min = ctp_clean_number(this.platform['ex_cust_fee_min']);
            let max = ctp_clean_number(this.platform['ex_cust_fee_max']);
            let isChanged = false;
            if (total < min && constants.calc_num(min)) {
                total = min;
                isChanged = true;
            }
            if (total > max && constants.calc_num(max)) {
                total = max;
                isChanged = true;
            }

            // Divide cap values of products
            if (isChanged && constants.isPlatformCapped(this.platform.info_url)) {
                const productRatio = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
                _.each(products, function (product, _index) {
                    productRatio[product] = parseFloat($this.user_data['ex_instruments_' + product] / $this.total_all_assets_ex);
                    if (productRatio[product] > 0) {
                        prod_excustody[product] = total * productRatio[product];
                    } else {
                        prod_excustody[product] = 0;
                    }
                });
            }
        }

        _.each(products, function (product, _index) {
            const fundsAmount = $this.user_data['funds_' + product];
            const cashAmount = $this.user_data['funds_' + product + '_cash'];
            if (!$this.ex_instruments_tiered && _.size($this.platform_data_ex_instruments) === 0) {
                if (year == 1 || over_years === 'in_x_years') {
                    etrade_temp_amount[product] = $this.user_data['ex_instruments_' + product];
                    etrade_temp_amount[product] = etrade_temp_amount[product] * growth_rate;
                } else if (year > 1 && over_years === 'over_years') {
                    const etradePlanning = $this.user_data['planning_ex_instruments_' + product];
                    etrade_temp_amount[product] = parseFloat(etrade_temp_amount[product] + etradePlanning) * growth_rate;
                }
            }
            if (over_years === 'in_x_years' || over_years === 'over_years') {
                etrade_temp_amount[product] = etrade_temp_amount[product] - prod_excustody[product] - ext_dealing[product];
                if (cashAmount < ongoing_adviser[product] && fundsAmount < ongoing_adviser[product] && etrade_temp_amount[product] > ongoing_adviser[product]) {
                    etrade_temp_amount[product] = etrade_temp_amount[product] - ongoing_adviser[product];
                }
                if (productCharges && cashAmount < productCharges[product] && fundsAmount < productCharges[product] && etrade_temp_amount[product] > productCharges[product]) {
                    etrade_temp_amount[product] = etrade_temp_amount[product] - productCharges[product];
                }
                $this.ex_instruments[product] = prod_excustody[product];
            } else {
                $this.ex_instruments[product] = prod_excustody[product];
            }
        });
        return { 'total': total, 'prod_excustody': prod_excustody }
    }

    // RSPL Task#98
    platform_custody_total_cash(year, is_funds = true) {

        let total = 0;
        let $this = this;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        this.platform_custody_cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };
        /*Begin: Ticket#243*/
        if (year == 1) {
            etrade_temp_amount = {
                'gia': 0,
                'isa': 0,
                'jisa': 0,
                'sipp': 0,
                'jsipp': 0,
                'onshore_bond': 0,
                'offshore_bond': 0,
                'lifetime_isa': 0
            };
        }
        let over_years = $this.user_data['investments_today'];
        let user_inputed_year = 0;
        if (over_years === 'in_x_years') {
            user_inputed_year = this.user_data.investments_in_x_years;
        } else if (over_years === 'over_years') {
            user_inputed_year = this.user_data.investments_over;
        }
        let is_growth = this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;
        /*End: Ticket#243*/
        if (this.platform_custody_cash_tiered) {

            /**
             * Calculate cost for each product
             */
            _.each(products, function (product) {
                let total_product = 0;

                /*Begin: Ticket#243*/
                let Funds_planning = $this.user_data['planning_ex_instruments_' + product]
                let amount_for_calculation;
                if (over_years === 'in_x_years' || over_years === 'over_years') {
                    if (is_funds == true) {
                        if (year == 1) {
                            etrade_temp_amount[product] = $this.user_data['ex_instruments_' + product];
                            etrade_temp_amount[product] = etrade_temp_amount[product] * growth_rate;
                        } else {
                            etrade_temp_amount[product] = (parseFloat(etrade_temp_amount[product]) + parseFloat(Funds_planning)) * growth_rate
                        }
                    }
                    amount_for_calculation = etrade_temp_amount[product];
                } else {
                    amount_for_calculation = $this.user_data['ex_instruments_' + product];
                }
                /*End: Ticket#243*/
                // RSPL Task#122
                cash_tier = $this.which_tier(constants.INV_TYPE_CASH, $this.total_all_assets, 1);
                total_product = $this.get_cost_of_product(
                    $this.platform_custody_data_cash[cash_tier]['calc_type'],
                    $this.platform_custody_data_cash[cash_tier][product],
                    //users_product_amount
                    amount_for_calculation
                );
                // if ($this.has_vat($this.platform_custody_data_cash[cash_tier]['vat'])) {
                //     total_product += $this.get_vat_amount(total_product);
                // }

                /*Begin: Ticket#243*/
                if (over_years === 'in_x_years' || over_years === 'over_years') {
                    if (is_funds == true) {
                        etrade_temp_amount[product] = etrade_temp_amount[product] - total_product;
                        $this.ex_instruments[product] = total_product;
                    }
                } else {
                    $this.ex_instruments[product] = total_product;
                }
                /*End: Ticket#243*/

                //Begin : Ticket#236
                if (over_years === 'in_x_years' || over_years === 'over_years') {
                    let i_minus = 0;
                    if (year != 1) {
                        i_minus = p_cash_int[product];
                    }
                    p_cash_int[product] += total_product;
                    $this.platform_custody_cash[product] = p_cash_int[product];
                    total = total + p_cash_int[product] - i_minus;
                } else {
                    total = total + total_product;
                    $this.platform_custody_cash[product] = total_product;
                }
                //End : Ticket#236
            });
        } else {
            total = 0;

            _.each(this.platform_custody_data_cash, function (value, key) {
                _.each(products, function (product) {
                    let total_product = 0;
                    let users_product_amount = $this.user_data['funds_' + product + '_cash']
                    if ((roles_array.includes("adviser") || roles_array.includes("subscriber"))
                        || (over_years === 'in_x_years' || over_years === 'over_years')
                    ) {
                        if (p_cash_int[product] > 0) {
                            users_product_amount = p_cash_int[product] + $this.user_data['funds_' + product + '_cash'];
                        }
                    }
                    total_product = $this.get_cost_of_product(
                        $this.platform_custody_data_cash[key]['calc_type'],
                        $this.platform_custody_data_cash[key][product],
                        users_product_amount
                    );
                    // if ($this.has_vat($this.platform_data_cash[key]['vat'])) {
                    //     total_product += $this.get_vat_amount(total_product);
                    // }
                    // Begin: Ticket#236
                    if (over_years === 'in_x_years' || over_years === 'over_years') {
                        let i_minus = 0;
                        if (year != 1) {
                            i_minus = p_cash_int[product];
                        }
                        p_cash_int[product] += total_product;
                        $this.platform_custody_cash[product] = p_cash_int[product];
                        total = total + p_cash_int[product] - i_minus;
                    } else {
                        total = total + total_product;
                        $this.platform_custody_cash[product] += total_product;
                    }
                    // End: Ticket#236
                });
            });
        }
        if (year % user_inputed_year == 0) {
            p_cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        }
        return total;
    }

    total_cash(year) {


        let total = 0;
        let $this = this;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        this.cash = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa' : 0 };
        // Start: Ticket#236
        if (year == 1) {
            cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa' : 0 };
        }
        let over_years = $this.user_data['investments_today'];
        let roles_commas = $this.user_data['roles_commas'];
        let roles_array;
        let user_inputed_year = 0;
        roles_array = roles_commas;
        if (roles_commas) {
            let roles_array = roles_commas.split(",");
            roles_array = JSON.stringify(roles_array);
        }
        if (over_years === 'in_x_years') {
            user_inputed_year = this.user_data.investments_in_x_years;
        } else if (over_years === 'over_years') {
            user_inputed_year = this.user_data.investments_over;
        }
        // End: Ticket#236
        if (this.cash_tiered) {
            /**
             * Calculate cost for each product
             */
            _.each(products, function (product) {
                let total_product = 0;
                let product_amount = 0;
                //RSPL Task#74
                let i_prev_key = false;
                let prev_aua_to = 0;
                //Begin : Ticket#236
                let users_product_amount = parseFloat($this.user_data['funds_' + product + '_cash']);
                if ((roles_array.includes("adviser") || roles_array.includes("subscriber"))
                    || (over_years === 'in_x_years' || over_years === 'over_years')
                ) {
                    if (cash_int[product] > 0) {
                        users_product_amount = cash_int[product] + $this.user_data['funds_' + product + '_cash'];
                    }
                }
                let cash_tier = $this.combine_tiers(constants.INV_TYPE_CASH, users_product_amount);
                //End : Ticket#236
                //TODO Split product assets per tier
                _.each(cash_tier, function (tier_key) {
                    let aua_to = ctp_clean_number($this.platform_data_cash[tier_key]['aua_to']);
                    if (!constants.calc_num(aua_to)) {
                        aua_to = Infinity;
                    }
                    let aua_from = ctp_clean_number($this.platform_data_cash[tier_key]['aua_from']);
                    if (users_product_amount > aua_to) {
                        if (users_product_amount <= aua_from) {
                            product_amount = users_product_amount;
                        } else {
                            // product_amount = aua_to - aua_from;
                            //RSPL Task#74
                            if (i_prev_key === true) {
                                prev_aua_to = ctp_clean_number($this.platform_data_cash[tier_key - 1]['aua_to']);
                                product_amount = aua_to - prev_aua_to;
                            } else {
                                product_amount = aua_to - aua_from;
                            }
                            i_prev_key = true;
                        }
                    } else {
                        if (users_product_amount <= aua_to) {
                            // product_amount = users_product_amount - aua_from;
                            //RSPL Task#74
                            if (i_prev_key === true) {
                                prev_aua_to = ctp_clean_number($this.platform_data_cash[tier_key - 1]['aua_to']);
                                product_amount = users_product_amount - prev_aua_to;
                            } else {
                                product_amount = users_product_amount - aua_from;
                            }
                        }
                    }
                    total_product += $this.get_cost_of_product(
                        $this.platform_data_cash[tier_key]['calc_type'],
                        $this.platform_data_cash[tier_key][product],
                        product_amount
                    );
                    if ($this.has_vat($this.platform_data_cash[tier_key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                });
                //Begin : Ticket#236
                if ((roles_array.includes("adviser") || roles_array.includes("subscriber"))
                    || (over_years === 'in_x_years' || over_years === 'over_years')
                ) {
                    let i_minus = 0;
                    if (year != 1) {
                        i_minus = cash_int[product];
                    }
                    cash_int[product] += total_product;
                    $this.cash[product] = cash_int[product];
                    total = total + cash_int[product] - i_minus;
                } else {
                    total = total + total_product;
                    $this.cash[product] += total_product;
                }
                //End : Ticket#236
            });
        } else {
            total = 0;

            _.each(this.platform_data_cash, function (value, key) {

                _.each(products, function (product) {
                    // Begin: Ticket#236
                    let users_product_amount = $this.user_data['funds_' + product + '_cash']
                    if ((roles_array.includes("adviser") || roles_array.includes("subscriber"))
                        || (over_years === 'in_x_years' || over_years === 'over_years')
                    ) {
                        if (cash_int[product] > 0) {
                            users_product_amount = cash_int[product] + $this.user_data['funds_' + product + '_cash'];
                        }
                    }
                    // End: Ticket#236
                    let total_product = $this.get_cost_of_product(
                        $this.platform_data_cash[key]['calc_type'],
                        $this.platform_data_cash[key][product],
                        users_product_amount
                    );
                    if ($this.has_vat($this.platform_data_cash[key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                    // Begin: Ticket#236
                    if ((roles_array.includes("adviser") || roles_array.includes("subscriber"))
                        || (over_years === 'in_x_years' || over_years === 'over_years')
                    ) {
                        let i_minus = 0;
                        if (year != 1) {
                            i_minus = cash_int[product];
                        }
                        cash_int[product] += total_product;
                        $this.cash[product] = cash_int[product];
                        total = total + cash_int[product] - i_minus;
                    } else {
                        total = total + total_product;
                        $this.cash[product] += total_product;
                    }
                    // End: Ticket#236
                });
            });
        }
        //RSPL Task#37
        // Is there a cash specific cap?
        // let cash_cap = constants.calc_num(this.platform['cash_cust_fee_min']) || constants.calc_num(this.platform['cash_cust_fee_max']);
        //
        // if (cash_cap && total > 0) {
        //
        //     let min = ctp_clean_number(this.platform['cash_cust_fee_min']);
        //     let max = ctp_clean_number(this.platform['cash_cust_fee_max']);
        //
        //     if (constants.calc_num(min) && total < min) {
        //         total = min;
        //     }
        //     else if (constants.calc_num(max) && total > max) {
        //         total = max;
        //     }
        // }
        if (year % user_inputed_year == 0) {
            cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        }
        return total;
    }

    interest_and_platform_cash_charges(year, openingCharges, ongoing_adviser, productCharges) {
        /*Cash Interest function code */
        let total = 0;
        let total_arr = { 'cash_interest': 0, 'custody_cash': 0 };
        let $this = this;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        this.cash = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        // Start: Ticket#243
        if (year == 1) {
            cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0 , 'lifetime_isa': 0};
        }
        let over_years = $this.user_data['investments_today'];
        let user_inputed_year = 0;
        if (over_years === 'in_x_years') {
            user_inputed_year = this.user_data.investments_in_x_years;
        } else if (over_years === 'over_years') {
            user_inputed_year = this.user_data.investments_over;
        }
        custody_cash_int = _.clone(cash_int);
        // End: Ticket#243
        if (this.cash_tiered) {
            /**
             * Calculate cost for each product
             */
            _.each(products, function (product) {
                let total_product = 0;
                let product_amount = 0;
                //RSPL Task#74
                let i_prev_key = false;
                let prev_aua_to = 0;
                /*Begin: Ticket#243*/
                let users_product_amount = parseFloat($this.user_data['funds_' + product + '_cash']);
                const cashPlanning = $this.user_data['planning_cash_' + product];
                if (over_years === 'over_years' && year != 1) {
                    users_product_amount = p_cash_int[product] + cashPlanning;
                }
                /*Begin: Ticket#243*/
                let cash_tier = $this.combine_tiers(constants.INV_TYPE_CASH, users_product_amount);
                //End : Ticket#236
                //TODO Split product assets per tier
                _.each(cash_tier, function (tier_key) {
                    let aua_to = ctp_clean_number($this.platform_data_cash[tier_key]['aua_to']);
                    if (!constants.calc_num(aua_to)) {
                        aua_to = Infinity;
                    }
                    let aua_from = ctp_clean_number($this.platform_data_cash[tier_key]['aua_from']);
                    if (users_product_amount > aua_to) {
                        if (users_product_amount <= aua_from) {
                            product_amount = users_product_amount;
                        } else {
                            // product_amount = aua_to - aua_from;
                            //RSPL Task#74
                            if (i_prev_key === true) {
                                prev_aua_to = ctp_clean_number($this.platform_data_cash[tier_key - 1]['aua_to']);
                                product_amount = aua_to - prev_aua_to;
                            } else {
                                product_amount = aua_to - aua_from;
                            }
                            i_prev_key = true;
                        }
                    } else {
                        if (users_product_amount <= aua_to) {
                            // product_amount = users_product_amount - aua_from;
                            //RSPL Task#74
                            if (i_prev_key === true) {
                                prev_aua_to = ctp_clean_number($this.platform_data_cash[tier_key - 1]['aua_to']);
                                product_amount = users_product_amount - prev_aua_to;
                            } else {
                                product_amount = users_product_amount - aua_from;
                            }
                        }
                    }
                    total_product += $this.get_cost_of_product(
                        $this.platform_data_cash[tier_key]['calc_type'],
                        $this.platform_data_cash[tier_key][product],
                        product_amount
                    );
                    if ($this.has_vat($this.platform_data_cash[tier_key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                });
                /*Begin: Ticket#243*/
                if (over_years === 'in_x_years' || over_years === 'over_years') {
                    if (year == 1 || over_years === 'in_x_years') {
                        cash_int[product] = $this.user_data['funds_' + product + '_cash'];
                        cash_int[product] = cash_int[product] + total_product;
                    } else {
                        const cashPlanning = $this.user_data['planning_cash_' + product];
                        cash_int[product] = (parseFloat(cash_int[product]) + parseFloat(cashPlanning)) + total_product
                    }
                    $this.cash[product] = total_product;
                } else {
                    cash_int[product] = users_product_amount + total_product;
                    $this.cash[product] += total_product;
                }
                total = total + total_product;
                /*End: Ticket#243*/
            });
        } else {
            total = 0;

            _.each(this.platform_data_cash, function (_value, key) {
                _.each(products, function (product) {
                    /*Begin: Ticket#243*/
                    let users_product_amount = $this.user_data['funds_' + product + '_cash'];
                    const cashPlanning = $this.user_data['planning_cash_' + product];
                    if (over_years === 'over_years' && year != 1) {
                        users_product_amount = p_cash_int[product] + cashPlanning;
                    }
                    /*Begin: Ticket#243*/
                    let total_product = $this.get_cost_of_product(
                        $this.platform_data_cash[key]['calc_type'],
                        $this.platform_data_cash[key][product],
                        users_product_amount
                    );
                    if ($this.has_vat($this.platform_data_cash[key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                    /*Begin: Ticket#243*/
                    if (over_years === 'in_x_years' || over_years === 'over_years') {
                        cash_int[product] = users_product_amount + total_product;
                        $this.cash[product] = total_product;
                    } else {
                        cash_int[product] = users_product_amount + total_product;
                        $this.cash[product] += total_product;
                    }
                    total = total + total_product;
                    /*End: Ticket#243*/
                });
            });
        }
        if (_.size(this.platform_data_cash) === 0 && year == 1) {
            _.each(products, function (product) {
                cash_int[product] = $this.user_data['funds_' + product + '_cash'];
            });
        }
        total_arr['cash_interest'] = total;
        /*Platform custody cash code*/
        let p_total = 0;
        this.platform_custody_cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };
        // Start: Ticket#236
        if (year == 1) {
            p_cash_int = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa':0 };
        }
        // End: Ticket#236
        if (this.platform_custody_cash_tiered) {
            /**
             * Calculate cost for each product
             */
            const tempCustodyValues = $this.getValuesForCustody(true);
            _.each(products, function (product) {
                let total_product = 0;
                // RSPL Task#122

                let s_calculable_amount = 0;
                let is_growth = $this.user_data.is_growth;
                let growth_rate = (is_growth == 'yes' ? 4 : $this.user_data.growth_rate);
                growth_rate = 1 + growth_rate / 100;
                if (year == 1 || over_years === 'in_x_years') {
                    const totalCashInterest = $this.getTotalCashInterest() || 0;
                    s_calculable_amount = ((parseFloat($this.total_all_assets_funds) + parseFloat($this.total_all_assets_ex)) * growth_rate) + (parseFloat($this.total_all_assets_cash) + totalCashInterest);
                } else {
                    s_calculable_amount = tempCustodyValues.allAssets;
                }
                if (isNaN(s_calculable_amount)) {
                    s_calculable_amount = 0;
                }
                // let cash_tier = $this.which_tier(constants.INV_TYPE_CASH, $this.total_all_assets, 1);
                let cash_tier = $this.which_tier(constants.INV_TYPE_CASH, s_calculable_amount, 1);
                let funds_product_cash = cash_int[product];
                total_product = $this.get_cost_of_product(
                    $this.platform_custody_data_cash[cash_tier]['calc_type'],
                    $this.platform_custody_data_cash[cash_tier][product],
                    funds_product_cash
                );

                // if ($this.has_vat($this.platform_custody_data_cash[cash_tier]['vat'])) {
                //     total_product += $this.get_vat_amount(total_product);
                // }
                /*Begin: Ticket#243*/
                // if (over_years === 'in_x_years' || over_years === 'over_years') {
                //     if (is_funds == true) {
                //         if (year === 1 && openingCharges[product] && funds_product_cash > openingCharges[product]) {
                //             p_cash_int[product] = funds_product_cash - total_product - openingCharges[product];
                //         } else {
                //             p_cash_int[product] = funds_product_cash - total_product; //this will use to calculate cash interest from 2nd year
                //         }
                //         if (p_cash_int[product] > ongoing_adviser[product]) {
                //             p_cash_int[product] = p_cash_int[product] - ongoing_adviser[product];
                //         }
                //         if (productCharges && p_cash_int[product] > productCharges[product]) {
                //             p_cash_int[product] = p_cash_int[product] - productCharges[product];
                //         }
                //         cash_int[product] = _.clone(p_cash_int[product]);
                //         $this.platform_custody_cash[product] = total_product;
                //     }
                // } else {
                //     $this.platform_custody_cash[product] += total_product;
                // }
                $this.platform_custody_cash[product] = total_product;
                p_total = p_total + total_product;
                /*End: Ticket#243*/
            });
        } else {
            total = 0;

            _.each(this.platform_custody_data_cash, function (value, key) {
                _.each(products, function (product) {
                    let total_product = 0;
                    let users_product_amount = cash_int[product]
                    total_product = $this.get_cost_of_product(
                        $this.platform_custody_data_cash[key]['calc_type'],
                        $this.platform_custody_data_cash[key][product],
                        users_product_amount
                    );

                    // if ($this.has_vat($this.platform_data_cash[key]['vat'])) {
                    //     total_product += $this.get_vat_amount(total_product);
                    // }
                    /*Begin: Ticket#243*/
                    // if (over_years === 'in_x_years' || over_years === 'over_years') {
                    //     if (year === 1 && openingCharges[product] && users_product_amount > openingCharges[product]) {
                    //         p_cash_int[product] = users_product_amount - total_product - openingCharges[product];
                    //     } else {
                    //         p_cash_int[product] = users_product_amount - total_product; //this will use to calculate cash interest from 2nd year
                    //     }
                    //     if (users_product_amount > ongoing_adviser[product]) {
                    //         p_cash_int[product] = p_cash_int[product] - ongoing_adviser[product];
                    //     }
                    //     if (productCharges && p_cash_int[product] > productCharges[product]) {
                    //         p_cash_int[product] = p_cash_int[product] - productCharges[product];
                    //     }
                    //     cash_int[product] = _.clone(p_cash_int[product]);
                    //     $this.platform_custody_cash[product] = total_product;
                    // } else {
                    //     $this.platform_custody_cash[product] += total_product;
                    // }
                    $this.platform_custody_cash[product] = total_product;
                    p_total = p_total + total_product;
                    /*End: Ticket#243*/
                });
            });
        }

        // Is there a cash specific cap?
        let cash_cap = constants.calc_num(this.platform['cash_cust_fee_min']) || constants.calc_num(this.platform['cash_cust_fee_max']);
        if (cash_cap && p_total > 0) {
            let min = ctp_clean_number(this.platform['cash_cust_fee_min']);
            let max = ctp_clean_number(this.platform['cash_cust_fee_max']);
            let isChanged = false;
            if (constants.calc_num(min) && p_total < min) {
                p_total = min;
                isChanged = true;
            }
            else if (constants.calc_num(max) && p_total > max) {
                p_total = max;
                isChanged = true;
            }

            if (isChanged && constants.isPlatformCapped(this.platform.info_url)) {
                const productRatio = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
                _.each(products, function (product, _index) {
                    productRatio[product] = parseFloat($this.user_data['funds_' + product + '_cash'] / $this.total_all_assets_cash);
                    if (productRatio[product] > 0) {
                        $this.platform_custody_cash[product] = p_total * productRatio[product];
                    } else {
                        $this.platform_custody_cash[product] = 0;
                    }
                });
            }
        }

        _.each(products, function (product, _index) {
            const funds_product_cash = cash_int[product];
            if (over_years === 'in_x_years' || over_years === 'over_years') {
                if (year === 1 && openingCharges[product] && funds_product_cash > openingCharges[product]) {
                    p_cash_int[product] = funds_product_cash - $this.platform_custody_cash[product] - openingCharges[product];
                } else {
                    p_cash_int[product] = funds_product_cash - $this.platform_custody_cash[product]; //this will use to calculate cash interest from 2nd year
                }
                if (p_cash_int[product] > ongoing_adviser[product]) {
                    p_cash_int[product] = p_cash_int[product] - ongoing_adviser[product];
                }
                if (productCharges && p_cash_int[product] > productCharges[product]) {
                    p_cash_int[product] = p_cash_int[product] - productCharges[product];
                }
                cash_int[product] = _.clone(p_cash_int[product]);
            }
        });

        total_arr['custody_cash'] = p_total;
        return total_arr;
    }

    get_cost_of_product(type, product_rate, amount) {
        if (!constants.calc_num(amount)) {
            return 0;
        }
        let total = 0;
        switch (type) {
            case constants.TIER_TYPE_AD_VALORAM:
                total = this.calc_ad_valorem(amount, product_rate);
                break;

            case constants.TIER_TYPE_FLAT_RATE:
                total = product_rate;
                break;


        }

        return total;
    }


    calc_ad_valorem(amount, product_rate) {
        return (product_rate / 100) * amount;
    }

    calc_per_investment(product_rate, number_of_trades) {
        return number_of_trades * 2 * product_rate;
    }

    calc_per_transaction(product_rate, number_of_trades) {
        return number_of_trades * product_rate;
    }

    is_percentage() {
        return this.custody_fees[this.tier]['calc_type'] === constants.TIER_TYPE_AD_VALORAM;
    }

    is_tiered(investment_type) {

        let tiered = false;
        if (!_.isEmpty(this.custody_fees)) {
            _.every(this.custody_fees, function (row) {

                // Not investment type we are looking for
                if (row['inv_type'] !== investment_type) {
                    return true;
                }
                // If at least one row of particular investment type is tiered than fees are tiered
                if (constants.calc_num(row['tiered']) && row['tiered'] === 1) {
                    tiered = true;
                    return false;
                }
                return true;
            });
        }

        return tiered;
    }

    // RSPL TASK#37
    is_cash_tiered(investment_type) {
        let tiered = false;
        if (!_.isEmpty(this.cash_fees)) {
            _.every(this.cash_fees, function (row) {
                // Not investment type we are looking for
                if (row['inv_type'] !== investment_type) {
                    return true;
                }
                // If at least one row of particular investment type is tiered than fees are tiered
                if (constants.calc_num(row['tiered']) && row['tiered'] === 1) {
                    tiered = true;
                    return false;
                }
                return true;
            });
        }
        return tiered;
    }

    // RSPL Task#98
    which_tier(investment_type, product_amount, is_platform_custody_cash = 0) {

        let fee_key = null;
        let count = 0;
        let fees = {};

        if (investment_type === constants.INV_TYPE_FUND) {
            fees = this.platform_data_funds;
        }
        if (investment_type === constants.INV_TYPE_EX_TRADED) {
            fees = this.platform_data_ex_instruments;
        }
        //RSPL TASK#22 & RSPL Task#98
        if (investment_type === constants.INV_TYPE_CASH) {
            fees = this.platform_data_cash;

            if (is_platform_custody_cash === 1) {
                fees = this.platform_custody_data_cash;
            }
        }
        _.every(fees, function (value, key) {

            if (fees[key]['inv_type'] !== investment_type) {
                return true;
            }
            count++;

            if (!constants.calc_num(value['aua_to'])) {
                value['aua_to'] = Infinity;
            }
            if (_.isNaN(value['aua_from'])) {
                value['aua_from'] = 0;
            }
            let bottom_bracket = 0;
            let top_bracket = value['aua_to'];


            if (count > 1) {
                bottom_bracket = value['aua_from'];
                top_bracket = value['aua_to'];
            }
            bottom_bracket = ctp_clean_number(bottom_bracket);
            top_bracket = ctp_clean_number(top_bracket);
            if (bottom_bracket <= product_amount && product_amount <= top_bracket) {
                fee_key = key;
                return false;

            }
            return true;
        });

        return fee_key;
    }

    has_vat(value) {
        return (constants.calc_num(value)
            &&
            value === 1);


    }

    get_vat_amount(price_exc_vat) {
        let vat_amount = this.vat_rate * (price_exc_vat / 100);
        // vat_amount = Math.round(vat_amount); // round to 2 decimal places
        return vat_amount;
    }

    // RSPL Task#98
    combine_tiers(investment_type, product_amount, is_platform_custody_cash = 0) {

        let fee_key = {};
        let count = 0;
        let fees = {};

        if (investment_type === constants.INV_TYPE_FUND) {
            fees = this.platform_data_funds;
        }
        if (investment_type === constants.INV_TYPE_EX_TRADED) {
            fees = this.platform_data_ex_instruments;
        }
        //RSPL TASK#22 & RSPL Task#98
        if (investment_type === constants.INV_TYPE_CASH) {
            fees = this.platform_data_cash;
            if (is_platform_custody_cash === 1) {
                fees = this.platform_custody_data_cash;
            }
        }
        _.every(fees, function (value, key) {

            if (fees[key]['inv_type'] !== investment_type) {
                return true;
            }
            count++;

            if (!constants.calc_num(value['aua_to'])) {
                value['aua_to'] = Infinity;
            }
            if (!constants.calc_num(value['aua_from'])) {
                value['aua_from'] = 0;
            }


            let bottom_bracket = 0;
            let top_bracket = value['aua_to'];

            if (count > 1) {
                bottom_bracket = value['aua_from'];
                top_bracket = value['aua_to'];
            }
            bottom_bracket = ctp_clean_number(bottom_bracket);
            top_bracket = ctp_clean_number(top_bracket);

            if (product_amount >= bottom_bracket) {
                fee_key[key] = key;
            }
            return true;
        });

        return fee_key;
    }
    get_cash_vals() {
        return cash_int;
    }

    get_cash_int() {
        return _.isUndefined(this.cash) ? {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        } : this.cash;
    }
    get_et_vals() {
        return etrade_temp_amount;
    }

    get_fund_vals() {
        return funds_temp_amount
    }

    getCustodyETIValues() {
        return custody_etrade_temp_amount;
    }

    getCustodyCashValues() {
        return custody_cash_int;
    }

    getTotalCashInterest() {
        const tempCashInt = this.get_cash_int();
        const products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        let interest = 0;
        _.each(products, function (product) {
            interest += tempCashInt[product];
        })
        return interest;
    }

    getFundPlanning(product) {
        return parseFloat(this.user_data['planning_' + product] - this.user_data['planning_ex_instruments_' + product]);
    }

    getETIPlanning(product) {
        return parseFloat(this.user_data['planning_ex_instruments_' + product]);
    }

    getCashPlanning(product) {
        return parseFloat(this.user_data['planning_cash_' + product]);
    }

    getValuesForCustody(fromCash = false) {
        let totalCash = 0;
        let totalFunds = 0;
        let totalETrades = 0;
        let totalAllAssets = 0;
        const products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        let $this = this;

        // Cary forwarded values
        const fundValues = $this.get_fund_vals();
        const etValues = fromCash ? $this.get_et_vals() : $this.getCustodyETIValues();
        const cashValues = $this.getCustodyCashValues();
        const cashInterest = $this.get_cash_int();

        // Growth Rate
        const is_growth = $this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : $this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;

        _.each(products, function (product) {
            // Total cash
            totalCash += (parseFloat(cashValues[product]) + $this.getCashPlanning(product) + cashInterest[product]);
            // Total Funds
            totalFunds += ((parseFloat(fundValues[product]) + $this.getFundPlanning(product)) * growth_rate);
            // Total ETI
            totalETrades += ((parseFloat(etValues[product]) + $this.getETIPlanning(product)) * growth_rate);
        });

        // Total Assets
        totalAllAssets = totalFunds + totalETrades + totalCash;

        return {
            fund: totalFunds,
            eti: totalETrades,
            cash: totalCash,
            allAssets: totalAllAssets
        };
    }
};