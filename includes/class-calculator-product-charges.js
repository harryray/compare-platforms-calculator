_ = require('underscore-node');
db = require('../model');
constants = require('./const');
let Pcharge = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 }; //Ticket#243
let Pcharge_funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 }; //Ticket#243
all = {};

module.exports.Calculator_Product_Charges = class Calculator_Product_Charges {

    constructor(platform, platform_data) {
        this.platform = platform;
        this.is_excluded = false;
        this.product_annual_amount = platform['ann_admin_amount'];
        this.product_annual_charge_max = _.isNumber(platform['ann_admin_fee_max']) ? platform['ann_admin_fee_max'] : '';
        this.product_annual_charge_min = _.isNumber(platform['ann_admin_fee_min']) ? platform['ann_admin_fee_min'] : '';
        this.vat_rate = 20;
        this.fee_type_id = 2;
        this.product_annual_charges = _.where(platform_data, { 'fee_type_id': this.fee_type_id }) || null;
        this.pp_total = {};
        this.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        this.pp_total.funds = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0 };
        this.pp_total.cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 };
        this.pp_total.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 'lifetime_isa': 0 };
    }


    set_user_data(user_data) {

        this.user_data = user_data;

        this.number_of_trades = this.user_data['investment_frequency'];
        let aua_to = _.pluck(this.product_annual_charges, 'aua_to');
        if (aua_to.length) {
            if (!_.contains(aua_to, null) && this.user_data.total_all > _.max(aua_to)) {
                this.is_excluded = true;
            }
        }


    }


    get_total(year, funds_custody, ex_custody, ext_dealing, adviser_charges) {
        let total = 0;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        let $this = this;
        this.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };

        // Start: Ticket#243
        if (year == 1) {
            Pcharge = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
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
        // End: Ticket#243
        /**
         * Calculate cost for each product
         */
        _.each(products, function (product) {

            $this.all[product] = 0;
            /*Begin: Ticket#243 - Cash value added  */
            let product_amount = parseFloat($this.user_data['funds_' + product]) + parseFloat($this.user_data['ex_instruments_' + product]) + parseFloat($this.user_data['funds_' + product + '_cash']);
            let planning_amount;
            let Funds_planning = $this.user_data['planning_' + product] - $this.user_data['planning_ex_instruments_' + product];
            let Ex_planning = $this.user_data['planning_ex_instruments_' + product];
            let cash_planning = $this.user_data['planning_cash_' + product];
            let product_tier = $this.which_tier(product, product_amount);
            let calc_type
            if (!_.isNull(product_tier)) {
                calc_type = $this.product_annual_charges[product_tier]['calc_type'];
            }
            planning_amount = parseFloat(Funds_planning) + parseFloat(Ex_planning) + parseFloat(cash_planning)
            if (calc_type != 2) {
                if (over_years === 'in_x_years' || over_years === 'over_years') {
                    if (year == 1 || over_years === 'in_x_years') {
                        product_amount = product_amount * growth_rate;
                        Pcharge[product] = _.clone(product_amount)
                    } else {
                        product_amount = (parseFloat(Pcharge[product]) + parseFloat(planning_amount)) * growth_rate;
                    }
                }
                product_tier = $this.which_tier(product, product_amount);
                if (!_.isNull(product_tier)) {
                    calc_type = $this.product_annual_charges[product_tier]['calc_type'];
                }
            }
            /*end: Ticket#243*/
            if ($this.is_tiered(product)) {
                // All the rows where this product charge is added
                let product_rows = {};
                _.each($this.product_annual_charges, function (row, key) {
                    if (constants.calc_num(row[product])) {
                        product_rows[key] = key;
                    }
                });
                let total_product = 0;
                let product_cash_amount = product_amount - parseFloat($this.user_data['funds_' + product + '_cash']);
                let product_funds_amount = product_amount - parseFloat($this.user_data['funds_' + product]);
                let product_eti_amount = product_amount - parseFloat($this.user_data['ex_instruments_' + product]);
                if (!_.isNull(product_tier)) {
                    total_product = $this.get_cost_of_product(
                        $this.product_annual_charges[product_tier]['calc_type'],
                        $this.product_annual_charges[product_tier][product],
                        product_amount
                    );
                    if ($this.has_vat($this.product_annual_charges[product_tier]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }

                    if (calc_type == 2) {
                        $this.all[product] = total_product;
                    }
                    else {
                        /*Begin: Ticket#243*/
                        let funds_cust = 0
                        let ex_cust = 0
                        let ex_dealing = 0
                        let ex_adviser_charge = 0;
                        let fund_adviser_charge = 0;
                        let cash_adviser_charge = 0;
                        if (funds_custody != undefined) {
                            funds_cust = funds_custody[product]
                        }
                        if (ex_custody != undefined) {
                            ex_cust = ex_custody[product]
                        }
                        if (ext_dealing != undefined) {
                            ex_dealing = ext_dealing[product]
                        }
                        if (adviser_charges.ex_instruments != undefined) {
                            ex_adviser_charge = adviser_charges.ex_instruments[product]
                        }
                        if (adviser_charges.funds != undefined) {
                            fund_adviser_charge = adviser_charges.funds[product]
                        }
                        if (adviser_charges.cash != undefined) {
                            cash_adviser_charge = adviser_charges.cash[product]
                        }
                        let subtration_total = total_product + funds_cust + ex_cust + ex_dealing + cash_adviser_charge + fund_adviser_charge + ex_adviser_charge;
                        /*Begin: Ticket#243*/
                        if (over_years === 'in_x_years' || over_years === 'over_years') {
                            if (parseFloat($this.user_data['funds_' + product + '_cash']) > subtration_total) {
                                const tempCashAmount = parseFloat($this.user_data['funds_' + product + '_cash']) - subtration_total - adviser_charges.cash[product];
                                product_amount = product_cash_amount + tempCashAmount;
                                Pcharge[product] = _.clone(product_amount);
                                $this.all[product] = total_product;
                                $this.pp_total.cash[product] = product_amount;
                            } else if (parseFloat($this.user_data['funds_' + product]) > subtration_total) {
                                const tempFundAmount = parseFloat($this.user_data['funds_' + product]) - subtration_total - adviser_charges.funds[product];
                                product_amount = product_funds_amount + tempFundAmount;
                                Pcharge[product] = _.clone(product_amount)
                                $this.all[product] = total_product;
                                $this.pp_total.funds[product] = product_amount;
                            } else {
                                const tempEtiAmount = parseFloat($this.user_data['ex_instruments_' + product]) - subtration_total;
                                product_amount = product_eti_amount + tempEtiAmount;
                                Pcharge[product] = _.clone(product_amount)
                                $this.all[product] = total_product;
                                $this.pp_total.ex_instruments[product] = product_amount;
                            }
                        } else {
                            $this.all[product] = total_product;
                        }
                    }
                    /*End: Ticket#243*/
                    total = total + total_product;
                    //$this.all[product] = total_product; //Ticket#243
                }

            } else {
                _.each($this.product_annual_charges, function (row) {
                    let total_product = 0;
                    let product_cash_amount = product_amount - parseFloat($this.user_data['funds_' + product + '_cash']);
                    let product_funds_amount = product_amount - parseFloat($this.user_data['funds_' + product]);
                    let product_eti_amount = product_amount - parseFloat($this.user_data['ex_instruments_' + product]);
                    if (_.isNumber(row[product]) && row[product] > 0) {
                        let rate = row[product];
                        let type = row['calc_type'];
                        let vat = row['vat'];
                        total_product = $this.get_cost_of_product(
                            type,
                            rate,
                            product_amount
                        );
                        if ($this.has_vat(vat)) {
                            total_product += $this.get_vat_amount(total_product);
                        }
                        if (calc_type == 2) {
                            $this.all[product] = total_product;
                        } else {
                            /*Begin: Ticket#243*/
                            let funds_cust = 0
                            let ex_cust = 0
                            let ex_dealing = 0
                            if (funds_custody != undefined) {
                                funds_cust = funds_custody[product]
                            }
                            if (ex_custody != undefined) {
                                ex_cust = ex_custody[product]
                            }
                            if (ext_dealing != undefined) {
                                ex_dealing = ext_dealing[product]
                            }
                            let subtration_total = total_product + funds_cust + ex_cust + ex_dealing;
                            /*Begin: Ticket#243*/
                            if (over_years === 'in_x_years' || over_years === 'over_years') {
                                if (parseFloat($this.user_data['funds_' + product + '_cash']) > subtration_total) {
                                    const tempCashAmount = parseFloat($this.user_data['funds_' + product + '_cash']) - subtration_total;
                                    product_amount = product_cash_amount + tempCashAmount;
                                    Pcharge[product] = _.clone(product_amount)
                                    $this.all[product] = total_product;
                                    $this.pp_total.cash[product] = product_amount;
                                } else if (parseFloat($this.user_data['funds_' + product]) > subtration_total) {
                                    const tempFundAmount = parseFloat($this.user_data['funds_' + product]) - subtration_total;
                                    product_amount = product_funds_amount + tempFundAmount;
                                    Pcharge[product] = _.clone(product_amount)
                                    $this.all[product] = total_product;
                                    $this.pp_total.funds[product] = product_amount;
                                } else {
                                    const tempEtiAmount = parseFloat($this.user_data['ex_instruments_' + product]) - subtration_total;
                                    product_amount = product_eti_amount + tempEtiAmount;
                                    Pcharge[product] = _.clone(product_amount)
                                    $this.all[product] = total_product;
                                    $this.pp_total.ex_instruments[product] = product_amount;
                                }
                            } else {
                                $this.all[product] = total_product;
                            }
                        }
                        /*End: Ticket#243*/
                        total = total + total_product;
                        //$this.all[product] = total_product; //Ticket#243
                    }

                });

            }
        });

        if (constants.calc_num(this.product_annual_amount) && _.isNumber(this.product_annual_charge_max)) {
            if (this.product_annual_amount <= this.user_data.total_savings_and_investments) {
                total = this.product_annual_charge_max;
                if (this.product_annual_charge_max <= 0) {
                    $this.all = {
                        'gia': 0,
                        'isa': 0,
                        'jisa': 0,
                        'sipp': 0,
                        'jsipp': 0,
                        'onshore_bond': 0,
                        'offshore_bond': 0, 'lifetime_isa': 0 };
                }

            }
        }
        // Is there a min cap applied
        if (constants.calc_num(this.product_annual_charge_min) && (total < this.product_annual_charge_min)) {


            total = this.product_annual_charge_min;

        }

        // Is there a max cap applied
        if (constants.calc_num(this.product_annual_charge_max) && (total > this.product_annual_charge_max)) {
            total = this.product_annual_charge_max;

        }
        //not specific to products
        if (this.platform.info_url === 'fidelity' || this.platform.info_url === 'fundsnetwork') {
            $this.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
            Pcharge = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 }; //Ticket#243
            Pcharge_funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 }; //Ticket#243
        }
        return total;
    }


    // get total clone for funds
    get_funds_total(year, product, funds_custody, ex_custody, ext_dealing) {
        let total = 0;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        let $this = this;
        this.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        // Start: Ticket#243
        // if (year == 1) {
        //     Pcharge_funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        // }
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
        // End: Ticket#243
        /**
         * Calculate cost for each product
         */


        $this.all[product] = 0;
        /*Begin: Ticket#243 - Cash value added  */
        let product_amount = parseFloat($this.user_data['funds_' + product]) + parseFloat($this.user_data['ex_instruments_' + product]) + parseFloat($this.user_data['funds_' + product + '_cash']);
        let planning_amount;
        let Funds_planning = $this.user_data['planning_' + product] - $this.user_data['planning_ex_instruments_' + product];
        let Ex_planning = $this.user_data['planning_ex_instruments_' + product];
        let cash_planning = $this.user_data['planning_cash_' + product];
        let product_tier = $this.which_tier(product, product_amount);
        let calc_type
        if (!_.isNull(product_tier)) {
            calc_type = $this.product_annual_charges[product_tier]['calc_type'];
        }
        planning_amount = parseFloat(Funds_planning) + parseFloat(Ex_planning) + parseFloat(cash_planning)
        if (calc_type != 2) {
            if (over_years === 'in_x_years' || over_years === 'over_years') {
                if (year == 1 || over_years === 'in_x_years') {
                    product_amount = product_amount * growth_rate;
                    Pcharge_funds[product] = _.clone(product_amount)
                } else {
                    product_amount = (parseFloat(Pcharge_funds[product]) + parseFloat(planning_amount)) * growth_rate;
                }
            }
            product_tier = $this.which_tier(product, product_amount);
            if (!_.isNull(product_tier)) {
                calc_type = $this.product_annual_charges[product_tier]['calc_type'];
            }
        }
        /*end: Ticket#243*/
        if ($this.is_tiered(product)) {
            // All the rows where this product charge is added
            let product_rows = {};
            _.each($this.product_annual_charges, function (row, key) {
                if (constants.calc_num(row[product])) {
                    product_rows[key] = key;
                }
            });

            let total_product = 0;
            let product_cash_amount = product_amount - parseFloat($this.user_data['funds_' + product + '_cash']);
            let product_funds_amount = product_amount - parseFloat($this.user_data['funds_' + product]);
            let product_eti_amount = product_amount - parseFloat($this.user_data['ex_instruments_' + product]);
            if (!_.isNull(product_tier)) {
                total_product = $this.get_cost_of_product(
                    $this.product_annual_charges[product_tier]['calc_type'],
                    $this.product_annual_charges[product_tier][product],
                    product_amount
                );

                if ($this.has_vat($this.product_annual_charges[product_tier]['vat'])) {
                    total_product += $this.get_vat_amount(total_product);
                }

                if (calc_type == 2) {
                    $this.all[product] = total_product;
                } else {
                    let funds_cust = 0
                    let ex_cust = 0
                    let ex_dealing = 0
                    if (funds_custody != undefined) {
                        funds_cust = funds_custody
                    }
                    if (ex_custody != undefined) {
                        ex_cust = ex_custody[product]
                    }
                    if (ext_dealing != undefined) {
                        ex_dealing = ext_dealing[product]
                    }
                    let subtration_total = total_product + funds_cust + ex_cust + ex_dealing;
                    /*Begin: Ticket#243*/
                    if (over_years === 'in_x_years' || over_years === 'over_years') {
                        if (parseFloat($this.user_data['funds_' + product + '_cash']) > subtration_total) {
                            const tempCashAmount = parseFloat($this.user_data['funds_' + product + '_cash']) - subtration_total;
                            product_amount = product_cash_amount + tempCashAmount;
                            Pcharge_funds[product] = _.clone(product_amount);
                            $this.all[product] = total_product;
                        } else if (parseFloat($this.user_data['funds_' + product]) > subtration_total) {
                            const tempFundAmount = parseFloat($this.user_data['funds_' + product]) - subtration_total;
                            product_amount = product_funds_amount + tempFundAmount;
                            Pcharge_funds[product] = _.clone(product_amount);
                            $this.all[product] = total_product;
                        } else {
                            const tempEtiAmount = parseFloat($this.user_data['ex_instruments_' + product]) - subtration_total;
                            product_amount = product_eti_amount + tempEtiAmount;
                            Pcharge_funds[product] = _.clone(product_amount);
                            $this.all[product] = total_product;
                        }
                    } else {
                        $this.all[product] = total_product;
                    }
                }
                /*End: Ticket#243*/
                total = total + total_product;
                //$this.all[product] = total_product; //Ticket#243
            }

        } else {
            _.each($this.product_annual_charges, function (row) {
                let total_product = 0;
                let product_cash_amount = product_amount - parseFloat($this.user_data['funds_' + product + '_cash']);
                let product_funds_amount = product_amount - parseFloat($this.user_data['funds_' + product]);
                let product_eti_amount = product_amount - parseFloat($this.user_data['ex_instruments_' + product]);
                if (_.isNumber(row[product]) && row[product] > 0) {
                    let rate = row[product];
                    let type = row['calc_type'];
                    let vat = row['vat'];

                    total_product = $this.get_cost_of_product(
                        type,
                        rate,
                        product_amount
                    );
                    if ($this.has_vat(vat)) {
                        total_product += $this.get_vat_amount(total_product);
                    }

                    if (calc_type == 2) {
                        $this.all[product] = total_product;
                    } else {
                        let funds_cust = 0
                        let ex_cust = 0
                        let ex_dealing = 0
                        if (funds_custody != undefined) {
                            funds_cust = funds_custody
                        }
                        if (ex_custody != undefined) {
                            ex_cust = ex_custody[product]
                        }
                        if (ext_dealing != undefined) {
                            ex_dealing = ext_dealing[product]
                        }
                        let subtration_total = total_product + funds_cust + ex_cust + ex_dealing;
                        /*Begin: Ticket#243*/
                        if (over_years === 'in_x_years' || over_years === 'over_years') {
                            if (parseFloat($this.user_data['funds_' + product + '_cash']) > subtration_total) {
                                const tempCashAmount = parseFloat($this.user_data['funds_' + product + '_cash']) - subtration_total;
                                product_amount = product_cash_amount + tempCashAmount;
                                Pcharge_funds[product] = _.clone(product_amount)
                                $this.all[product] = total_product;
                            } else if (parseFloat($this.user_data['funds_' + product]) > subtration_total) {
                                const tempFundAmount = parseFloat($this.user_data['funds_' + product]) - subtration_total;
                                product_amount = product_funds_amount + tempFundAmount;
                                Pcharge_funds[product] = _.clone(product_amount);
                                $this.all[product] = total_product;
                            } else {
                                const tempEtiAmount = parseFloat($this.user_data['ex_instruments_' + product]) - subtration_total;
                                product_amount = product_eti_amount + tempEtiAmount;
                                Pcharge_funds[product] = _.clone(product_amount);
                                $this.all[product] = total_product;
                            }
                        } else {
                            $this.all[product] = total_product;
                        }
                    }
                    /*End: Ticket#243*/
                    total = total + total_product;
                    //$this.all[product] = total_product; //Ticket#243
                }

            });

        }


        if (constants.calc_num(this.product_annual_amount) && _.isNumber(this.product_annual_charge_max)) {
            if (this.product_annual_amount <= this.user_data.total_savings_and_investments) {
                total = this.product_annual_charge_max;
                if (this.product_annual_charge_max <= 0) {
                    $this.all = {
                        'gia': 0,
                        'isa': 0,
                        'jisa': 0,
                        'sipp': 0,
                        'jsipp': 0,
                        'onshore_bond': 0,
                        'offshore_bond': 0, 'lifetime_isa': 0 };
                }

            }
        }
        // Is there a min cap applied
        if (constants.calc_num(this.product_annual_charge_min) && (total < this.product_annual_charge_min)) {


            total = this.product_annual_charge_min;

        }

        // Is there a max cap applied
        if (constants.calc_num(this.product_annual_charge_max) && (total > this.product_annual_charge_max)) {


            total = this.product_annual_charge_max;

        }
        return total;
    }

    get_total_product_charges(pp_charges, year, cash_int) {
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        let $this = this;
        let total = 0;
        $this.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        let over_years = $this.user_data['investments_today'];
        let user_inputed_year = 0;
        if (over_years === 'in_x_years') {
            user_inputed_year = this.user_data.investments_in_x_years;
        } else if (over_years === 'over_years') {
            user_inputed_year = this.user_data.investments_over;
        }
        let total_product = 0;

        let key = '';
        if ($this.user_data.total_savings_and_investments_cash > 0) {
            key = 'cash'
        } else if ($this.user_data.total_funds > 0) {
            key = 'funds'
        } else if ($this.user_data.total_shares > 0) {
            key = 'ex_instruments'
        }
        if (key === '') {
            return 0;
        }

        /**
         * Calculate cost for each product
         */
        _.each(products, function (product) {
            let product_amount = 0;
            let planning = 0;

            let product_key = '';
            if (key === 'cash') {
                product_key = 'funds_' + product + '_' + key;
            } else {
                product_key = key + '_' + product;
            }

            product_amount = parseFloat(pp_charges['funds'][product]) + parseFloat(pp_charges['ex_instruments'][product]) + (year === 1 ? parseFloat(pp_charges['cash_int_values'][product]) + cash_int[product] : parseFloat(pp_charges['cash_int_values'][product]));
            planning = pp_charges['planning_funds'][product] + pp_charges['planning_eti'][product] + (year === 1 ? pp_charges['planning_cash'][product] + cash_int[product] : pp_charges['planning_cash'][product]);
            if (planning !== null && planning > 0) {
                if (year == 1) {
                    product_amount = _.clone(product_amount + planning);
                }
            }

            let product_tier = $this.which_tier(product, product_amount);
            let calc_type = null;
            if (!_.isNull(product_tier)) {
                calc_type = $this.product_annual_charges[product_tier]['calc_type'];
            }

            if ($this.is_tiered(product)) {
                // All the rows where this product charge is added
                let product_rows = {};
                _.each($this.product_annual_charges, function (row, key) {
                    if (constants.calc_num(row[product])) {
                        product_rows[key] = key;
                    }
                });

                if (!_.isNull(product_tier)) {
                    total_product = $this.get_cost_of_product(
                        $this.product_annual_charges[product_tier]['calc_type'],
                        $this.product_annual_charges[product_tier][product],
                        product_amount
                    );

                    if ($this.has_vat($this.product_annual_charges[product_tier]['vat'])) {
                        total_product += $this.get_vat_amount(total_product);
                    }
                    $this.all[product] = total_product;
                    total = total + total_product;
                }
            } else {
                _.each($this.product_annual_charges, function (row) {
                    let total_product = 0;
                    if (_.isNumber(row[product]) && row[product] > 0) {
                        let rate = row[product];
                        let type = row['calc_type'];
                        let vat = row['vat'];

                        total_product = $this.get_cost_of_product(
                            type,
                            rate,
                            product_amount
                        );
                        if ($this.has_vat(vat)) {
                            total_product += $this.get_vat_amount(total_product);
                        }
                        $this.all[product] = total_product;
                        total = total + total_product;
                    }
                });
            }
        });
        if (constants.calc_num(this.product_annual_amount) && _.isNumber(this.product_annual_charge_max)) {
            if (this.product_annual_amount <= this.user_data.total_savings_and_investments) {
                total = this.product_annual_charge_max;
                if (this.product_annual_charge_max <= 0) {
                    $this.all = {
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
            }
        }
        // Is there a min cap applied
        if (constants.calc_num(this.product_annual_charge_min) && (total < this.product_annual_charge_min)) {
            total = this.product_annual_charge_min;
        }
        // Is there a max cap applied
        if (constants.calc_num(this.product_annual_charge_max) && (total > this.product_annual_charge_max)) {
            total = this.product_annual_charge_max;
        }
        const result = {
            total: total,
            allProducts: _.clone($this.all)
        };
        //not specific to products
        if (this.platform.info_url === 'fidelity' || this.platform.info_url === 'fundsnetwork') {
            $this.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0 , 'lifetime_isa': 0 };
            Pcharge = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0 , 'lifetime_isa': 0 }; //Ticket#243
            Pcharge_funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0 , 'lifetime_isa': 0 }; //Ticket#243
        }
        return result;
    }


    get_cost_of_product(type, product_rate, amount) {
        if (amount === 0 || !_.isNumber(amount)) {
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

    is_tiered(product) {

        let tiered = false;

        if (!_.isEmpty(this.product_annual_charges)) {

            _.every(this.product_annual_charges, function (row, key) {
                if (!constants.calc_num(row[product])) {
                    return true;
                }
                // If at least one row of particular investment type is tiered than fees are tiered
                if (_.isNumber(row['tiered']) && row['tiered'] === 1) {

                    tiered = true;
                    return false;
                }
                return true;
            });
        }

        return tiered;
    }

    which_tier(product, product_amount) {

        let fee_key = null;
        let count = 0;
        let $this = this;
        _.every(this.product_annual_charges, function (value, key) {

            if (!constants.calc_num(value[product])) {
                return true;
            }
            count++;
            if (!constants.calc_num(value['aua_to'])) {
                value['aua_to'] = Infinity;
            }
            if (!_.isNumber(value['aua_from'])) {
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
        return (_.isNumber(value) && value === 1);


    }

    get_vat_amount(price_exc_vat) {
        let vat_amount = this.vat_rate * (price_exc_vat / 100);
        //vat_amount = Math.round(vat_amount); // round to 2 decimal places
        //vat_amount = vat_amount.toFixed(2);
        return vat_amount;
    }

};