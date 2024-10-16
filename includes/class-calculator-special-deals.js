_ = require('underscore-node');
constants = require('./const');
module.exports.Calculator_Special_Deals = class Calculator_Special_Deals {
    constructor(platform_id) {
        
        this.platform_id = platform_id;
        //this.special_deals_discounts_total = 0;
        //this.special_deals_discounts = {};
        this.user_data = {};
    }

    set_user_data(user_data) {
        this.user_data = user_data;
    }

    calculate_discountable_total(total, advisor_charges, deal) {
        // initial advisor charges are not included in the total that we receive, for some reason.
        // annual ones still are tho!
        // take the total that we're getting, minus advisor_charges to figure out what it is without advisor
        // and then calculate on that
        return (total - advisor_charges);
    }

    calculate_discount(total, advisor_charges, deal) {
        let discountableTotal = this.calculate_discountable_total(total, advisor_charges);
        let totalWithDiscount = total;
        if(deal.type == "bips") {
            totalWithDiscount = parseFloat(this.calculate_bips_discount(this.user_data.total_savings_and_investments, deal)) + parseFloat(advisor_charges)
        } else {
            totalWithDiscount = parseFloat(this.calculate_flat_rate_discount(discountableTotal, deal)) + parseFloat(advisor_charges)
        }
        return totalWithDiscount
    }

    calculate_bips_discount(discountableTotal, deal) {
        //console.log('calculate_bips_discount is ' + (discountableTotal) + ' deal is ' + (deal.number))
        let numberBPS = deal.number * 0.0001
        let discountedTotal = discountableTotal * numberBPS
        //console.log('discountedTotal is ' + discountedTotal + ' and the ultimate bips are ' + numberBPS);
        return discountedTotal
    }

    calculate_flat_rate_discount(discountableTotal, deal) {
        // TODO: ensure this number doesn't go below 0 (for instance, if fees are £90 and flat rate discount is £100)
        // DONE accomplished via Math.max(0, value)
        // let discountedTotal = Math.max(0, discountableTotal - deal.number)
        // return discountedTotal
        // UPDATE re Feedback from Sam: Flat Rate Discounts are a misnomer; they're just flat rates. 
        // This should actually just output the exact number entered as the "Flat Rate".
        let discountedTotal = deal.number
        return discountedTotal
    }
};