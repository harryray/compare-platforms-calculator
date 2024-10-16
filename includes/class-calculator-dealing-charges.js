_ = require('underscore-node');
constants = require('./const');
module.exports.Calculator_Dealing_Charges = class Calculator_Dealing_Charges {

    constructor() {
        this.vat_rate = 20;
        this.fee_type_id = 4;
        this.funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        this.is_excluded = false;
    }

    set_user_data(user_data) {
        this.user_data = user_data;

        if (!(this.user_data["yearly_trades_funds"] > 0) || !(this.user_data["yearly_trades_ex"] > 0)) {
            this.number_of_trades = 0;
        } else {

            this.number_of_trades = this.user_data['investment_frequency'];
        }


    }

    set_platform_data(platform, platform_data) {
        let $this = this;
        this.platform = platform;
        this.platform_data = platform_data;
        this.dealing_fees = _.where(platform_data, { 'fee_type_id': this.fee_type_id }) || null;

        this.funds_tiered = this.is_tiered(constants.INV_TYPE_FUND);
        this.ex_instruments_tiered = this.is_tiered(constants.INV_TYPE_EX_TRADED);

        this.platform_data_funds = {};
        this.platform_data_ex_instruments = {};


        _.each(this.dealing_fees, function (value, key) {

            if (value['inv_type'] === constants.INV_TYPE_FUND) {
                let ret = $this.get_multiple(constants.INV_TYPE_FUND);
                value.rem = ret.rem;
                value.mult = ret.mult;
                value.add = ret.add;


                $this.platform_data_funds[key] = value;

            }
            if (value['inv_type'] === constants.INV_TYPE_EX_TRADED) {
                let ret = $this.get_multiple(constants.INV_TYPE_EX_TRADED);
                value.rem = ret.rem;
                value.mult = ret.mult;
                value.add = ret.add;
                $this.platform_data_ex_instruments[key] = value;
            }
        });
        let aua_to = _.pluck(this.dealing_fees, 'aua_to');
        if (aua_to.length) {
            if (!_.contains(aua_to, null) && this.user_data.total_all > _.max(aua_to)) {
                this.is_excluded = true;
            }
        }
    }

    get_multiple(inv_type) {
        let prefix = '';
        let mult = 0;
        let user_data_prefix = '';
        if (inv_type === constants.INV_TYPE_EX_TRADED) {
            prefix = 'ex_instruments_';
            user_data_prefix = 'ex';
        }
        else if (inv_type === constants.INV_TYPE_FUND) {
            prefix = 'funds_';
            user_data_prefix = 'funds';

        }

        let prods = 0;
        let add = '';
        if (this.user_data[prefix + "offshore_bond"] > 0) {
            prods++;
            add = 'offshore_bond';
        }
        if (this.user_data[prefix + "onshore_bond"] > 0) {
            prods++;
            add = 'onshore_bond';
        }

        if (this.user_data[prefix + "jsipp"] > 0) {
            prods++;
            add = 'jsipp';
        }
        if (this.user_data[prefix + "sipp"] > 0) {
            prods++;
            add = 'sipp';
        }
        if (this.user_data[prefix + "jisa"] > 0) {
            prods++;
            add = 'jisa';
        }
        if (this.user_data[prefix + "isa"] > 0) {
            prods++;
            add = 'isa';
        }
        if (this.user_data[prefix + "gia"] > 0) {
            prods++;
            add = 'gia';
        }
        if (this.user_data[prefix + "lifetime_isa"] > 0) {
            prods++;
            add = 'lifetime_isa';
        }
        if (prods === 0) {
            mult = 0;
        } else {
            mult = parseFloat((this.user_data["yearly_trades_" + user_data_prefix] * 2) / prods);
        }
        let rem = parseFloat((this.user_data["yearly_trades_" + user_data_prefix] * 2) - (mult * prods));
        return { 'mult': mult, 'rem': rem, 'add': add };
    }

    get_total() {
        let ext_total = this.total_ex_instruments().total;
        let total = this.total_funds().total + ext_total;
        if (constants.calc_num(this.platform['dealing_fee_credits'])) {
            if (total > this.platform['dealing_fee_credits']) {
                total = total - this.platform['dealing_fee_credits']
            } else {
                total = 0;
            }
        }
        return total;
    }

    total_funds() {
        let $this = this;
        $this.funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        let total = 0;
        let mult = 0;
        let products = ['isa', 'jisa', 'sipp', 'jsipp', 'gia', 'onshore_bond', 'offshore_bond' , 'lifetime_isa'];
        let fund_dealing = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        if (this.funds_tiered) {

            /**
             * Calculate cost for each product
             */
            _.each(products, function (product) {
                let total_product = 0;
                if (constants.calc_num($this.user_data['funds_' + product])) {
                    let funds_tier = $this.select_band(constants.INV_TYPE_FUND);

                    if ($this.platform_data_funds[funds_tier]['rem'] > 0 && $this.platform_data_funds[funds_tier]['add'] === product) {
                        mult = $this.platform_data_funds[funds_tier]['mult'] + $this.platform_data_funds[funds_tier]['rem'];

                    } else {
                        mult = $this.platform_data_funds[funds_tier]['mult'];
                    }
                    //RSPL Ticket#180
                    let which_tier = $this.which_tier(constants.INV_TYPE_FUND, mult);
                    total_product = $this.get_cost_of_product(
                        $this.platform_data_funds[which_tier]['calc_type'],
                        $this.platform_data_funds[which_tier][product],
                        $this.user_data["avg_trade_funds"],
                        mult
                    );
                    if ($this.has_vat($this.platform_data_funds[which_tier]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                }
                total = total + total_product;
                fund_dealing[product] = total_product;

                $this.funds[product] += total_product;
            })
        } else {

            total = 0;
            let mult = 0;
            _.each($this.platform_data_funds, function (value, key) {
                _.each(products, function (product) {

                    let total_product = 0;
                    if (constants.calc_num($this.user_data['funds_' + product])) {
                        if ($this.platform_data_funds[key]['rem'] > 0 && $this.platform_data_funds[key]['add'] === product) {
                            mult = $this.platform_data_funds[key]['mult'] + $this.platform_data_funds[key]['rem'];
                        } else {
                            mult = $this.platform_data_funds[key]['mult'];
                        }
                        total_product = $this.get_cost_of_product(
                            $this.platform_data_funds[key]['calc_type'],   //2
                            $this.platform_data_funds[key][product],  //12.5
                            $this.user_data["avg_trade_funds"], //500
                            mult
                        );

                        if ($this.has_vat($this.platform_data_funds[key]['vat'])) {
                            total_product += $this.get_vat_amount(total_product);
                        }
                    }
                    $this.funds[product] += total_product;
                    fund_dealing[product] = total_product;
                    total = total + total_product;

                });
            });
        }

        this.funds_total = total;
        return { 'total': total, 'dealing': fund_dealing }
    }


    total_ex_instruments() {

        let $this = this;
        $this.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };

        let total = 0;
        let products = ['isa', 'jisa', 'sipp', 'jsipp', 'gia', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        let prod_dealing = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        let mult = 0;
        if (this.ex_instruments_tiered) {

            /**
             * Calculate cost for each product
             */
            _.each(products, function (product) {
                let total_product = 0;

                let ex_instruments_tier = $this.select_band(constants.INV_TYPE_EX_TRADED);
                if (constants.calc_num($this.user_data['ex_instruments_' + product])) {
                    if ($this.platform_data_ex_instruments[ex_instruments_tier]['rem'] > 0 && $this.platform_data_ex_instruments[ex_instruments_tier]['add'] === product) {
                        mult = $this.platform_data_ex_instruments[ex_instruments_tier]['mult'] + $this.platform_data_ex_instruments[ex_instruments_tier]['rem'];
                    } else {
                        mult = $this.platform_data_ex_instruments[ex_instruments_tier]['mult'];
                    }
                    //RSPL Ticket#180
                    let which_tier = $this.which_tier(constants.INV_TYPE_EX_TRADED, mult);
                    total_product = $this.get_cost_of_product(
                        $this.platform_data_ex_instruments[which_tier]['calc_type'],
                        $this.platform_data_ex_instruments[which_tier][product],
                        $this.user_data["avg_trade_ex"],
                        mult
                    );

                    if (constants.calc_num($this.platform_data_ex_instruments[which_tier]['vat'])) {
                        if ($this.has_vat($this.platform_data_ex_instruments[which_tier]['vat'])) {
                            total_product += $this.get_vat_amount(total_product);
                        }
                    }
                }
                total = total + total_product;
                prod_dealing[product] = total_product // test
                $this.ex_instruments[product] += total_product;
            });
        } else {
            total = 0;

            _.each($this.platform_data_ex_instruments, function (value, key) {

                _.each(products, function (product) {
                    let total_product = 0;
                    if (constants.calc_num($this.user_data['ex_instruments_' + product])) {
                        if ($this.platform_data_ex_instruments[key]['rem'] > 0 && $this.platform_data_ex_instruments[key]['add'] === product) {
                            mult = $this.platform_data_ex_instruments[key]['mult'] + $this.platform_data_ex_instruments[key]['rem'];
                        } else {
                            mult = $this.platform_data_ex_instruments[key]['mult'];
                        }
                        total_product = $this.get_cost_of_product(
                            $this.platform_data_ex_instruments[key]['calc_type'],
                            $this.platform_data_ex_instruments[key][product],
                            $this.user_data['avg_trade_ex'],
                            mult
                        );

                        if ($this.has_vat($this.platform_data_ex_instruments[key]['vat'])) {
                            total_product += $this.get_vat_amount(total_product);
                        }
                    }
                    total = total + total_product;
                    prod_dealing[product] = total_product // test
                    $this.ex_instruments[product] += total_product;
                });
            });
            this.ex_instruments = $this.ex_instruments;
        }
        this.ex_total = total;
        return { 'total': total, 'dealing': prod_dealing }
        // return total;

    }

    get_cost_of_product(type, product_rate, amount, mult) {

        if (product_rate === 0 || !_.isNumber(product_rate)) {
            return 0;
        }
        let total = 0;
        switch (type) {
            case constants.TIER_TYPE_AD_VALORAM:
                total = this.calc_ad_valorem(amount, product_rate, mult);
                break;

            case constants.TIER_TYPE_PER_TRANSACTION:
                total = this.calc_per_transaction(product_rate, this.number_of_trades, mult);
                break;
        }
        if (constants.calc_num(total)) {
            total = constants.round(total);
        }

        return total;
    }

    calc_ad_valorem(amount, product_rate, mult) {
        return amount * (product_rate / 100) * mult;
    }

    calc_per_investment(product_rate, number_of_trades) {
        return number_of_trades * product_rate;
    }

    calc_per_transaction(product_rate, number_of_trades, mult) {
        return product_rate * mult;
    }

    is_percentage() {
        return this.dealing_fees[this.tier]['type'] === constants.TIER_TYPE_AD_VALORAM;
    }

    is_tiered(inv_type) {

        let tiered = false;

        _.every(this.dealing_fees, function (row, key) {

            // Not investment type we are looking for
            if (row['inv_type'] !== inv_type) {
                return true;
            }
            // If at least one row of particular investment type is tiered than fees are tiered
            if (constants.calc_num(row['tiered']) && row['tiered'] === 1) {
                tiered = true;
                return false
            }
            return true;
        });

        return tiered;
    }

    select_band(inv_type) {

        let fee_key = null;
        let count = 0;
        let fees = {};
        let product_amount = 0;

        if (inv_type === constants.INV_TYPE_FUND || inv_type === constants.INV_TYPE_BOTH) {
            fees = this.platform_data_funds;
            product_amount = this.user_data['investment_frequency_funds'] * 2;
        }
        if (inv_type === constants.INV_TYPE_EX_TRADED || inv_type === constants.INV_TYPE_BOTH) {
            fees = this.platform_data_ex_instruments;
            product_amount = this.user_data['investment_frequency_ex_traded'] * 2;
        }
        _.every(fees, function (value, key) {

            if (fees[key]['inv_type'] !== inv_type) {
                return false;
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
            if (bottom_bracket <= product_amount && product_amount <= top_bracket) {
                fee_key = key;
                return false;

            }
            return true;
        });
        return fee_key;


    }
    which_tier(inv_type, trades_per_product) {  //RSPL Ticket#180

        let fee_key = null;
        let count = 0;
        let fees = {};
        let product_amount = 0;

        if (inv_type === constants.INV_TYPE_FUND || inv_type === constants.INV_TYPE_BOTH) {
            fees = this.platform_data_funds;
            product_amount = trades_per_product;
        }
        if (inv_type === constants.INV_TYPE_EX_TRADED || inv_type === constants.INV_TYPE_BOTH) {
            fees = this.platform_data_ex_instruments;
            product_amount = trades_per_product;
        }
        _.every(fees, function (value, key) {

            if (fees[key]['inv_type'] !== inv_type) {
                return false;
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
            if (bottom_bracket <= product_amount && product_amount <= top_bracket) {
                fee_key = key;
                return false;

            }
            return true;
        });
        return fee_key;


    }

    has_vat(value) {
        return (constants.calc_num(value) && value === 1);


    }

    get_vat_amount(price_exc_vat) {
        let vat_amount = this.vat_rate * (price_exc_vat / 100);
        vat_amount = constants.round(vat_amount); // round to 2 decimal places
        return vat_amount;
    }
};