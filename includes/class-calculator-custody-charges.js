_ = require('underscore-node');
constants = require('./const');
// Debug
funds = {};
//RSPL TASK#22
cash = {};
ex_instruments = {};
module.exports.custody_charges = class Calculator_Custody_Charges {


    constructor() {
        this.vat_rate = '20';
        this.fee_type_id = 1;
        this.is_excluded = false;
    }

    set_user_data(user_data) {

        this.user_data = user_data;

        this.user_data['funds_gia'] = this.user_data['funds_gia'] - this.user_data['ex_instruments_gia'];
        this.user_data['funds_isa'] = this.user_data['funds_isa'] - this.user_data['ex_instruments_isa'];
        this.user_data['funds_jisa'] = this.user_data['funds_jisa'] - this.user_data['ex_instruments_jisa'];
        this.user_data['funds_sipp'] = this.user_data['funds_sipp'] - this.user_data['ex_instruments_sipp'];
        this.user_data['funds_jsipp'] = this.user_data['funds_jsipp'] - this.user_data['ex_instruments_jsipp'];
        this.user_data['funds_lifetime_isa'] = this.user_data['funds_lifetime_isa'] - this.user_data['ex_instruments_lifetime_isa'];

        this.number_of_trades = this.user_data['investment_frequency'];


    }

    set_platform_data(platform, platform_data) {

        this.platform_data = platform_data;
        this.platform = platform;
        this.custody_fees = _.where(platform_data, { 'fee_type_id': this.fee_type_id }) || null;

        this.funds_tiered = this.is_tiered(constants.INV_TYPE_FUND);
        this.ex_instruments_tiered = this.is_tiered(constants.INV_TYPE_EX_TRADED);
        //RSPL TASK#22
        this.cash_tiered = this.is_tiered(constants.INV_TYPE_CASH);

        this.platform_data_funds = {};
        this.platform_data_ex_instruments = {};
        //RSPL TASK#22
        this.platform_data_cash = {};
        let $this = this;
        this.custody_fees.each(function (value, key) {
            if (value['inv_type'] === constants.INV_TYPE_FUND) {
                $this.platform_data_funds[key] = value;
            }
            if (value['inv_type'] === constants.INV_TYPE_EX_TRADED) {
                $this.platform_data_ex_instruments[key] = value;
            }
            //RSPL TASK#22
            if (value['inv_type'] === constants.INV_TYPE_CASH) {
                $this.platform_data_cash[key] = value;
            }
        });
    }

    get_total() {

        let funds_total = this.total_funds();
        let ex_instruments_total = this.total_ex_instruments();
        //RSPL TASK#22
        let cash_total = this.total_cash();
        let all_total = funds_total + ex_instruments_total + cash_total;

        // Is there a funds specific cap ?
        let funds_cap = constants.calc_num(this.platform['fund_cust_fee_min']) || constants.calc_num(this.platform['fund_cust_fee_max']);

        // Is there a ex instruments specific cap ?
        let ex_instruments_cap = constants.calc_num(this.platform['ex_cust_fee_min']) || constants.calc_num(this.platform['ex_cust_fee_max']);

        // Is there a general price cap ?
        let all_cap = constants.calc_num(this.platform['all_cust_fee_min']) || constants.calc_num(this.platform['all_cust_fee_max']);

        //die(var_dump( all_total ));
        if ((funds_cap === false || ex_instruments_cap === false) && all_cap === true) {

            let min = ctp_clean_number(this.platform['all_cust_fee_min']);
            let max = ctp_clean_number(this.platform['all_cust_fee_max']);

            if (constants.calc_num(min) && all_total < min) {
                all_total = min;
            }
            else if (constants.calc_num(max) && all_total > max) {
                all_total = max;
            }

        }

        return all_total;
    }

    total_funds() {


        this.user_data['funds_jsipp'] = this.user_data['funds_jsipp'];

        let total = 0;
        let $this = this;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'lifetime_isa'];
        //die(var_dump(this.user_data));
        if (this.funds_tiered) {

            /**
             * Calculate cost for each product
             */

            products.each(function (product) {
                let total_product = 0;
                let funds_tier = $this.which_tier(constants.INV_TYPE_FUND, this.user_data['funds_' + product]);
                if ($this.user_data.total_savings_and_investments > $this.platform_data_funds[funds_tier]['aua_to']) {
                    $this.is_excluded = true;
                }
                total_product = $this.get_cost_of_product(
                    $this.platform_data_funds[funds_tier]['calc_type'],
                    $this.platform_data_funds[funds_tier][product],
                    $this.user_data['funds_' + product]
                );

                if ($this.has_vat($this.platform_data_funds[funds_tier]['vat'])) {
                    total_product += $this.get_vat_amount(total_product);
                }

                total = total + total_product;

                $this.funds[product] = total_product;
            });
        } else {
            total = 0;

            this.platform_data_funds.each(function (value, key) {
                products.each(function (product) {
                    let total_product = 0;
                    total_product = $this.get_cost_of_product(
                        $this.platform_data_funds[key]['calc_type'],
                        $this.platform_data_funds[key][product],
                        $this.user_data['funds_' + product]
                    );

                    // total_product = Math.round(total_product);

                    if ($this.has_vat($this.platform_data_funds[key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }

                    total = total + total_product;
                    // DEBUG
                    $this.funds[product] = total_product;
                });
            });
        }

        // Is there a funds specific cap ?
        let funds_cap = constants.calc_num(this.platform['fund_cust_fee_min']) || constants.calc_num(this.platform['fund_cust_fee_max']);

        if (funds_cap && constants.calc_num(total)) {

            let min = ctp_clean_number(this.platform['fund_cust_fee_min']);
            let max = ctp_clean_number(this.platform['fund_cust_fee_max']);

            if (constants.calc_num(min) && total < min) {
                total = min;
            }
            else if (constants.calc_num(max) && total > max) {
                total = max;
            }
        }

        return total;
    }

    //RSPL TASK#22
    total_cash() {


        this.user_data['funds_jsipp_cash'] = this.user_data['funds_jsipp_cash'];

        let total = 0;
        let $this = this;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'lifetime_isa', 'interest_rate'];
        //die(var_dump(this.user_data));
        if (this.cash_tiered) {

            /**
             * Calculate cost for each product
             */

            products.each(function (product) {
                let total_product = 0;
                let cash_tier = $this.which_tier(constants.INV_TYPE_CASH, this.user_data['funds_' + product + '_cash']);
                if ($this.user_data.total_savings_and_investments > $this.platform_data_cash[cash_tier]['aua_to']) {
                    $this.is_excluded = true;
                }
                total_product = $this.get_cost_of_product(
                    $this.platform_data_cash[cash_tier]['calc_type'],
                    $this.platform_data_cash[cash_tier][product],
                    $this.user_data['funds_' + product + '_cash']
                );

                if ($this.has_vat($this.platform_data_cash[cash_tier]['vat'])) {
                    total_product += $this.get_vat_amount(total_product);
                }

                total = total + total_product;

                $this.cash[product] = total_product;
            });
        } else {
            total = 0;

            this.platform_data_cash.each(function (value, key) {
                products.each(function (product) {
                    let total_product = 0;
                    total_product = $this.get_cost_of_product(
                        $this.platform_data_cash[key]['calc_type'],
                        $this.platform_data_cash[key][product],
                        $this.user_data['funds_' + product + '_cash']
                    );

                    // total_product = Math.round(total_product);

                    if ($this.has_vat($this.platform_data_cash[key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }

                    total = total + total_product;
                    // DEBUG
                    $this.cash[product] = total_product;
                });
            });
        }

        // Is there a funds specific cap ?
        // let funds_cap = constants.calc_num(this.platform['fund_cust_fee_min']) || constants.calc_num(this.platform['fund_cust_fee_max']);
        //
        // if (funds_cap && constants.calc_num(total)) {
        //
        //     let min = ctp_clean_number(this.platform['fund_cust_fee_min']);
        //     let max = ctp_clean_number(this.platform['fund_cust_fee_max']);
        //
        //     if (constants.calc_num(min) && total < min ) {
        //         total = min;
        //     }
        //     else if (constants.calc_num(max) && total > max  ) {
        //         total = max;
        //     }
        // }

        return total;
    }

    total_ex_instruments() {


        this.user_data['ex_instruments_jsipp'] = this.user_data['ex_instruments_jsipp'];

        let total = 0;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'lifetime_isa'];
        let $this = this;

        if (this.ex_instruments_tiered) {

            /**
             * Calculate cost for each product
             */

            products.each(function (product) {
                let total_product = 0;
                let ex_instruments_tier = $this.which_tier(constants.INV_TYPE_EX_TRADED, this.user_data['ex_instruments_' + product]);
                if ($this.user_data.total_savings_and_investments > $this.platform_data_ex_instruments[ex_instruments_tier]['aua_to']) {
                    $this.is_excluded = true;
                } else {
                    $this.is_excluded = false;
                }
                total_product = $this.get_cost_of_product(
                    $this.platform_data_ex_instruments[ex_instruments_tier]['calc_type'],
                    $this.platform_data_ex_instruments[ex_instruments_tier][product],
                    $this.user_data['ex_instruments_' + product]
                );
                // total_product = Math.round(total_product);
                if ($this.has_vat($this.platform_data_ex_instruments[ex_instruments_tier]['vat'])) {
                    total_product += $this.get_vat_amount(total_product);
                }

                let total = total + total_product;

                $this.ex_instruments[product] = total_product;
            });
        } else {

            let total = 0;
            this.platform_data_ex_instruments.each(function (value, key) {

                products.each(function (product) {
                    let total_product = $this.get_cost_of_product(
                        $this.platform_data_ex_instruments[key]['calc_type'],
                        $this.platform_data_ex_instruments[key][product],
                        $this.user_data['ex_instruments_' + product]
                    );
                    // total_product = Math.round(total_product);
                    if ($this.has_vat($this.platform_data_ex_instruments[key]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                    total = total + total_product;

                    $this.ex_instruments[product] = total_product;
                });
            });
        }

        // Is there a ex instruments specific cap ?
        let ex_instruments_cap = (_.isNumber(this.platform['ex_cust_fee_min']) || _.isNumber(this.platform['ex_cust_fee_max']));

        // Is there a general price cap ?
        let all_cap = _.isNumber(this.platform['all_cust_fee_min']) || _.isNumber(this.platform['all_cust_fee_max']);

        if (ex_instruments_cap && constants.calc_num(total)) {

            let min = ctp_clean_number(this.platform['ex_cust_fee_min']);
            let max = ctp_clean_number(this.platform['ex_cust_fee_max']);

            if (_.isNumber(min) && total < min) {
                total = min;
            }
            if (total > max && !_.isNumber(max)) {
                total = max;
            }
        }

        return total;
    }

    get_cost_of_product(type, product_rate, amount) {
        let total = 0;
        if (amount === 0 || !_.isNumber(amount)) {
            return 0;
        }

        switch (type) {
            case constants.TIER_TYPE_AD_VALORAM:
                total = this.calc_ad_valorem(amount, product_rate);
                break;

            case constants.TIER_TYPE_FLAT_RATE:
                total = product_rate;
                break;

            case constants.TIER_TYPE_PER_INVESTMENT:
                total = this.calc_per_investment(product_rate, this.number_of_trades);
                break;

            case constants.TIER_TYPE_PER_TRANSACTION:
                total = this.calc_per_transaction(product_rate, this.number_of_trades);
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
        return (this.custody_fees[this.tier]['type'] === constants.TIER_TYPE_AD_VALORAM);


    }

    is_tiered(investment_type) {

        let tiered = false;

        _.every(this.custody_fees, function (row, key) {

            // Not investment type we are looking for
            if (row['inv_type'] !== investment_type) {
                return true;
            }
            // If at least one row of particular investment type is tiered than fees are tiered
            if (_.isNumber(row['tiered']) && row['tiered'] === 1) {
                tiered = true;
                return false;
            }
            return true;
        });

        return tiered;
    }

    which_tier(investment_type, product_amount) {

        let fee_key = null;
        let count = 0;
        let fees = {};

        if (investment_type === constants.INV_TYPE_FUND) {
            fees = this.platform_data_funds;
        }
        if (investment_type === constants.INV_TYPE_EX_TRADED) {
            fees = this.platform_data_ex_instruments;
        }
        //RSPL TASK#22
        if (investment_type === constants.INV_TYPE_CASH) {
            fees = this.platform_data_cash;
        }
        _.every(fees, function (value, key) {

            if (fees[key]['inv_type'] !== investment_type) {
                return false;
            }
            count++;
            let bottom_bracket = 0;
            let top_bracket = value['aua'];
            if (count === 1) {
                bottom_bracket = 0;
                top_bracket = value['aua'];
            }
            if (count > 1) {
                bottom_bracket = fees[key - 1]['aua'];
                top_bracket = value['aua'];
            }
            bottom_bracket = ctp_clean_number(bottom_bracket);
            top_bracket = ctp_clean_number(top_bracket);

            if (bottom_bracket <= product_amount && product_amount <= top_bracket) {
                fee_key = key;
                return false;

            }
            return true
        });

        return fee_key;
    }

    has_vat(value) {
        return (_.isNumber(value)
            &&
            value === 1)
    }


    get_vat_amount(price_exc_vat) {
        let vat_amount = this.vat_rate * (price_exc_vat / 100);
        // vat_amount = Math.round(vat_amount); // round to 2 decimal places
        return vat_amount;
    }
};