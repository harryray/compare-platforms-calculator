_ = require('underscore-node');
constants = require('./const');
module.exports.Calculator_Adviser_Charges = class Calculator_Adviser_Charges {
    constructor(growth_rate) {

        this.growth_rate = growth_rate;
        this.initial_adviser_charges_total = 0;
        this.ongoing_adviser_charges_total = 0;
        this.initial_adviser_charges = {};
        this.ongoing_adviser_charges = {};
        this.user_data = {};
        this.initial_adviser_charges_all = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 
        };
        this.ongoing_adviser_charges_all = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 
        };
        this.funds_temp_amount = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 
        };
        this.initial_adviser_charges.funds = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 
        };
        this.initial_adviser_charges.cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 
        };
        this.initial_adviser_charges.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 
        };
        this.ongoing_adviser_charges.funds = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 ,
            'gia_total': 0,
            'isa_total': 0,
            'jisa_total': 0,
            'sipp_total': 0,
            'jsipp_total': 0,
            'onshore_bond_total': 0,
            'offshore_bond_total': 0,
            'lifetime_isa_total': 0 
        };
        this.ongoing_adviser_charges.cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0, 
            'lifetime_isa': 0 ,
            'gia_total': 0,
            'isa_total': 0,
            'jisa_total': 0,
            'sipp_total': 0,
            'jsipp_total': 0,
            'onshore_bond_total': 0,
            'offshore_bond_total': 0,
            'lifetime_isa_total': 0 
        };
        this.ongoing_adviser_charges.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            'gia_total': 0,
            'isa_total': 0,
            'jisa_total': 0,
            'sipp_total': 0,
            'jsipp_total': 0,
            'onshore_bond_total': 0,
            'offshore_bond_total': 0,
            'lifetime_isa_total': 0 
        };

    }

    set_user_data(user_data) {
        this.user_data = user_data;
    }

    get_total_initial(year) {
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond','lifetime_isa'];
        let $this = this;

        // Start: Ticket#243
        let over_years = $this.user_data['investments_today'];
        let user_inputed_year = 0;
        let initial_advice_type = this.user_data.initial_advice_type;
        let initial_adviser_charges = this.user_data.initial_adviser_charges;
        if (over_years === 'in_x_years') {
            user_inputed_year = this.user_data.investments_in_x_years;
        } else if (over_years === 'over_years') {
            user_inputed_year = this.user_data.investments_over;
        }
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
        let total_key = key;
        if (key === 'ex_instruments') {
            total_key = 'shares'
        }
        _.each(products, function (product) {
            let cal_initial_adviser_charge = 0;
            $this.initial_adviser_charges[key][product] = 0;
            /*Begin: Ticket#243 - Cash value added  */
            let product_key = '';
            let product_key2 = '';

            if (key === 'cash') {
                product_key = 'funds_' + product + '_' + key;
                product_key2 = 'planning_cash_' + product;
            } else {
                product_key = key + '_' + product;
                product_key2 = 'planning_' + key === 'funds' ? '' : `${key}_` + product;
            }

            let amount = parseFloat($this.user_data['funds_' + product]) + parseFloat($this.user_data['ex_instruments_' + product]) + parseFloat($this.user_data['funds_' + product + '_cash']);
            let planning = ((parseFloat($this.user_data['planning_' + product]) - parseFloat($this.user_data['planning_ex_instruments_' + product])) + parseFloat($this.user_data['planning_ex_instruments_' + product]) + parseFloat($this.user_data['planning_cash_' + product])) || null;
            if (planning !== null && planning > 0) {
                if (year !== 1) {
                    amount = planning;
                }
            } else {
                if (year > 1) {
                    amount = 0;
                }
            }
            if (initial_advice_type === 'percentage') {
                cal_initial_adviser_charge = $this.get_percentage(amount, initial_adviser_charges);
            } else {
                let ratio_key = key === 'ex_instruments' ? 'shares' : key;
                cal_initial_adviser_charge = 0;
                if ($this.user_data[product_key] > 0) {
                    cal_initial_adviser_charge = (amount / $this.user_data['total_' + ratio_key]) * initial_adviser_charges;
                }
            }
            if (year === 1) {
                $this.user_data[product_key] -= cal_initial_adviser_charge;
            } else {
                $this.user_data[product_key2] -= cal_initial_adviser_charge;
            }

            $this.initial_adviser_charges_total += cal_initial_adviser_charge;
            $this.initial_adviser_charges_all[product] = cal_initial_adviser_charge;
            $this.initial_adviser_charges[key][product] = cal_initial_adviser_charge;


        });
        $this.user_data['total_' + total_key] -= $this.initial_adviser_charges_total;
        $this.user_data.initial_adviser_charges_total = $this.initial_adviser_charges_total;
        return $this.initial_adviser_charges_total;
    }

    get_total_ongoing(pp_charges, year, cash_int) {
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa' ];
        let $this = this;

        // Start: Ticket#243
        let annual_advice_type = this.user_data.annual_advice_type;
        let annaul_adviser_charges = this.user_data.annual_adviser_charges;

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
        let total_annual_adviser_charge = 0;
        _.each(products, function (product) {
            let cal_annual_adviser_charge = 0;
            $this.ongoing_adviser_charges[key][product] = 0;
            let amount = 0;
            let planning = 0;

            let product_key = '';
            if (key === 'cash') {
                product_key = 'funds_' + product + '_' + key;
            } else {
                product_key = key + '_' + product;
            }

            amount = parseFloat(pp_charges['funds'][product]) + parseFloat(pp_charges['ex_instruments'][product]) + (year === 1 ? parseFloat(pp_charges['cash_int_values'][product]) + cash_int[product] : parseFloat(pp_charges['cash_int_values'][product]));
            planning = pp_charges['planning_funds'][product] + pp_charges['planning_eti'][product] + (year === 1 ? pp_charges['planning_cash'][product] + cash_int[product] : pp_charges['planning_cash'][product]);
            if (planning !== null && planning > 0) {
                if (year == 1) {
                    amount = _.clone(amount + planning);
                }
            }
            if (annual_advice_type === 'percentage') {
                cal_annual_adviser_charge = $this.get_percentage(amount, annaul_adviser_charges);
            } else {
                let ratio_key = key === 'ex_instruments' ? 'shares' : key;
                cal_annual_adviser_charge = 0;
                if ($this.user_data[product_key] > 0) {
                    cal_annual_adviser_charge = (amount / $this.user_data['total_' + ratio_key]) * annaul_adviser_charges;
                }
            }
            total_annual_adviser_charge += cal_annual_adviser_charge;
            $this.ongoing_adviser_charges_total += cal_annual_adviser_charge;
            $this.ongoing_adviser_charges[key][product] = cal_annual_adviser_charge;
            $this.ongoing_adviser_charges_all[product] = cal_annual_adviser_charge;
            $this.ongoing_adviser_charges[key]['total_' + product] += cal_annual_adviser_charge;
        });
        return total_annual_adviser_charge;
    }

    get_percentage(amount, charge) {
        return amount * (parseFloat(charge) / 100);
    }

    get_flat_rate(charge) {
        return charge;
    }

    get_initial_charges() {
        let $this = this;
        let over_years = this.user_data.investments_today;
        let initial_advice_type = this.user_data.initial_advice_type;
        let initial_adviser_charges = this.user_data.initial_adviser_charges;
        let total_investment = this.user_data.total_savings_and_investments_total;
        // let annual_advice_type = user_data.annual_advice_type;
        // let annual_adviser_charges = user_data.annual_adviser_charges;
        let initial_adviser_charges_total = 0;
        if (over_years === 'today') {
            if (initial_advice_type === 'percentage') {
                initial_adviser_charges_total = total_investment * (initial_adviser_charges / 100);
            } else {
                initial_adviser_charges_total = initial_adviser_charges;
            }
            $this.user_data.initial_adviser_charges_total = initial_adviser_charges_total;
        }

        return initial_adviser_charges_total;

    }

    get_annual_charges(total_investment) {

        // let total_investment = this.user_data.total_savings_and_investments_total;
        let annual_advice_type = this.user_data.annual_advice_type;
        let annual_adviser_charges = this.user_data.annual_adviser_charges;
        let annual_adviser_charges_total = 0;

        //Annual Adviser charges calculation
        if (annual_advice_type === 'percentage') {
            annual_adviser_charges_total = total_investment * (annual_adviser_charges / 100);
        } else {
            annual_adviser_charges_total = annual_adviser_charges;
        }

        return annual_adviser_charges_total;
    }


    total_funds(year, is_funds = true, ex_custody, dealing_cr) {
        let product_charges = new cp.Calculator_Product_Charges(this.platform, this.platform_data);
        product_charges.set_user_data(_.clone(this.user_data));
        let annual_advice_type = this.user_data.annual_advice_type;
        let annaul_adviser_charges = this.user_data.annual_adviser_charges;

        let total = 0;
        let $this = this;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 
        'lifetime_isa' ];
        this.funds = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 
        'lifetime_isa': 0   };
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
            this.funds_temp_amount = {
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


        let is_growth = this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;
        /*End: Ticket#243*/


        /**
         * Calculate cost for each product
         */
        _.each(products, function (product) {
            let total_product = 0;
            // RSPL Task#134
            if (year == 1 || over_years === 'in_x_years') {
                funds_temp_amount[product] = $this.user_data['funds_' + product];
                funds_temp_amount[product] = funds_temp_amount[product] * growth_rate;
            } else {
                funds_temp_amount[product] = (parseFloat(funds_temp_amount[product]) + parseFloat(Funds_planning)) * growth_rate
            }

            /*Begin: Ticket#243*/
            let Funds_planning = $this.user_data['planning_' + product] - $this.user_data['planning_ex_instruments_' + product];
            let amount_for_calculation;
            if (over_years === 'in_x_years' || over_years === 'over_years') {

                if (year == 1 || over_years === 'in_x_years') {
                    $this.funds_temp_amount[product] = $this.user_data['funds_' + product];
                    $this.funds_temp_amount[product] = $this.funds_temp_amount[product] * growth_rate;
                } else {
                    $this.funds_temp_amount[product] = (parseFloat($this.funds_temp_amount[product]) + parseFloat(Funds_planning)) * growth_rate
                }

                amount_for_calculation = $this.funds_temp_amount[product];
            } else {
                amount_for_calculation = $this.user_data['funds_' + product];
            }
            /*End: Ticket#243*/
            if (annual_advice_type === 'percentage') {
                total_product = $this.get_percentage(amount_for_calculation, annaul_adviser_charges);
            } else {
                let ratio_key = key === 'ex_instruments' ? 'shares' : key;
                total_product = $this.get_flat_rate(amount_for_calculation, $this.user_data['total_' + ratio_key], annaul_adviser_charges);
                // initial_adviser_charges = initial_adviser_charges;
            }

            let totalAnnualCharges = product_charges.get_funds_total(year, product, total_product, ex_custody, dealing_cr);
            /*Begin: Ticket#243*/
            if (over_years === 'in_x_years' || over_years === 'over_years') {

                $this.funds_temp_amount[product] = $this.funds_temp_amount[product] - total_product - totalAnnualCharges;
                $this.funds[product] = total_product;

            } else {
                $this.funds[product] = total_product;
            }
            total = total + total_product;
            prod_fcustody[product] = total_product //test
        });

        return { 'total': total, 'prod_fcustody': prod_fcustody }
        //return total;
    }


};