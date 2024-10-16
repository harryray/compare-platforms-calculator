_ = require('underscore-node');
constants = require('./const');
module.exports.Calculator_Acc_Opening_Charges = class Calculator_Acc_Opening_Charges {
    constructor() {
        this.vat_rate = '20';
        this.fee_type_id = 5;
    }

    set_user_data(user_data) {
        this.user_data = user_data;
        this.is_excluded = false;
        // User didn't provide product values
        if (this.user_data['investment_products'] === 'no' && constants.calc_num(this.user_data['total_savings_and_investments'])) {

            let slice = this.user_data['total_savings_and_investments'] / 3;
            /*Begin: #346*/
            // let sipp_slice = slice;
            // if (this.user_data['investment_products_simplified']) {
            //     slice = user_data.total_savings_and_investments / 2;
            //     sipp_slice = 0;
            // }
            /*End: #346*/
            this.user_data['funds_gia'] = slice;
            this.user_data['funds_isa'] = slice;
            this.user_data['funds_jisa'] = 0;
            this.user_data['funds_sipp'] = slice; //#346
            this.user_data['funds_jsipp'] = 0;
            this.user_data['funds_onshore_bond'] = 0;
            this.user_data['funds_offshore_bond'] = 0;
            this.user_data['funds_lifetime_isa'] = 0;
        }
        // User didn't provide product values
        if (this.user_data['investment_products'] === 'no' && constants.calc_num(this.user_data['total_savings_and_investments_cash'])) {

            let slice_cash = this.user_data['total_savings_and_investments_cash'] / 3;

            this.user_data['funds_gia_cash'] = slice_cash;
            this.user_data['funds_isa_cash'] = slice_cash;
            this.user_data['funds_jisa_cash'] = 0;
            this.user_data['funds_sipp_cash'] = slice_cash;
            this.user_data['funds_jsipp_cash'] = 0;
            this.user_data['funds_onshore_bond_cash'] = 0;
            this.user_data['funds_offshore_bond_cash'] = 0;
            this.user_data['funds_lifetime_isa_cash'] = 0;
        }
    }

    set_platform_data(platform, platform_data) {
        this.platform = platform;
        this.acc_openning_fee = _.where(platform_data, { 'fee_type_id': this.fee_type_id, 'calc_type': 5 }, true) || null;

    }

    get_total() {
        let $this = this;
        this.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        let total = 0;
        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
        if (!_.isEmpty($this.acc_openning_fee)) {
            _.every(products, function (product) {
                let product_amount = 0;
                if (constants.calc_num($this.user_data['funds_' + product]) || constants.calc_num($this.user_data['ex_traded_' + product])) {
                    product_amount = $this.acc_openning_fee[product];

                    if ($this.has_vat()) {
                        product_amount += $this.get_vat_amount(product_amount);
                    }
                }
                $this.all[product] = product_amount;

                total = total + product_amount;
                return true;
            });
        }
        return { 'total': total, 'openingCharges': $this.all }

    }

    has_vat() {
        return (!_.isNull(this.acc_openning_fee['vat'])
            &&
            this.acc_openning_fee['vat'] == 1);


    }

    get_vat_amount(price_exc_vat) {
        let vat_amount = this.vat_rate * (price_exc_vat / 100);
        // vat_amount = Math.round(vat_amount); // Math.round to 2 decimal places
        return vat_amount;
    };
};