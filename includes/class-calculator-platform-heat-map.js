_ = require('underscore-node');
m1 = require('./class-calculator-custody-charges-method-1');
m2 = require('./class-calculator-custody-charges-method-2');
m3 = require('./class-calculator-custody-charges-method-3');
m4 = require('./class-calculator-custody-charges-method-4');
m5 = require('./class-calculator-custody-charges-method-5');
cp = require('./class-calculator-product-charges');
custody = require('./class-calculator-custody-charges');
op = require('./class-acc-openning-charges');
dl = require('./class-calculator-dealing-charges');
ac = require('./class-calculator-adviser-charges');
constants = require('./const');

module.exports.cphm = class Calculator_Platform_Heat_Map {


    constructor(platforms, platforms_data, user_data) {
        this.order_by = _.isUndefined(user_data.order_by) ? 'cost' : user_data.order_by;
        this.order = _.isUndefined(user_data.order) ? 'asc' : user_data.order;
        this.is_excluded = false;
        this.sort_keys = [constants.SORT_COST, constants.SORT_NAME, constants.SORT_RATING, constants.SORT_REC];
        this.year_cost = {};
        this.totals = {};
        this.pp_charges = {};
        this.initialProducts = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };

        this.totals.adviser_charges = 0;
        this.totals.initial_adviser_charges_total = 0;
        this.adviser_annual_charges = {};
        this.initial_adviser_charges_all = {};
        this.initial_adviser_charges_total = 0;
        this.adviser_annual_charges.year_cost = {};
        this.platform_id = null;
        this.platforms = platforms;
        this.platforms_data = platforms_data;
        this.user_data = user_data;
        if (this.user_data['investments_today'] === 'today') {
            this.user_data['is_growth'] = 'no';
            this.user_data.growth_rate = 0;
        } else {
            if (!this.user_data.growth_rate && !this.user_data.is_growth) {
                this.user_data['is_growth'] = 'yes';
            }
        }
    }

    get_platform_cost(platform, platform_data, user_data, total_investment = 0, portfolio_with = '') {
        let total = 0;
        let over_years = user_data.investments_today;
        let year = 1;
        let planning_gia = 0;
        let planning_isa = 0;
        let planning_jisa = 0;
        let planning_sipp = 0;
        let planning_jsipp = 0;
        let planning_onshore_bond = 0;
        let planning_offshore_bond = 0;

        let planning_ex_instruments_gia = 0;
        let planning_ex_instruments_isa = 0;
        let planning_ex_instruments_jisa = 0;
        let planning_ex_instruments_sipp = 0;
        let planning_ex_instruments_jsipp = 0;
        let planning_ex_instruments_onshore_bond = 0;
        let planning_ex_instruments_offshore_bond = 0;

        //RSPL TASK#22
        let planning_cash_gia = 0;
        let planning_cash_isa = 0;
        let planning_cash_jisa = 0;
        let planning_cash_sipp = 0;
        let planning_cash_jsipp = 0;
        let planning_cash_onshore_bond = 0;
        let planning_cash_offshore_bond = 0;
        this.over_years = 0;
        //RSPL TASK#22
        this.totals.cash = 0;
        this.totals.funds = 0;
        this.totals.ex_instruments = 0;

        let funds_sipp = 0
        let funds_isa = 0;
        let funds_gia = 0;
        let funds_sipp_cash = 0
        let funds_isa_cash = 0;
        let funds_gia_cash = 0;
        let total_savings_and_investments = 0;
        let total_savings_and_investments_cash = 0;

        //chnge userdata for each investment Total
        // no cash
        if (portfolio_with == '100%') {
            funds_sipp = total_investment;
            funds_isa = 0;
            funds_gia = 0;
            funds_isa_cash = 0;
            funds_sipp_cash = 0;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment;
            total_savings_and_investments_cash = 0;
        } else if (portfolio_with == '50%') {
            funds_sipp = total_investment / 2;
            funds_isa = funds_sipp / 2;
            funds_gia = funds_sipp / 2;
            funds_isa_cash = 0;
            funds_sipp_cash = 0;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment;
            total_savings_and_investments_cash = 0;
        } else if (portfolio_with == 'ISA_ONLY') {
            funds_isa = total_investment;
            funds_sipp = 0;
            funds_gia = 0;
            funds_isa_cash = 0;
            funds_sipp_cash = 0;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment;
            total_savings_and_investments_cash = 0;
        }
        // cash 1
        else if (portfolio_with == '100%_CASH_1') {
            funds_sipp = (total_investment) * 0.98;
            funds_isa = 0;
            funds_gia = 0;
            funds_isa_cash = 0;
            funds_sipp_cash = (total_investment) * 0.02;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment * 0.98;
            total_savings_and_investments_cash = total_investment * 0.02;
        } else if (portfolio_with == '50%_CASH_1') {
            funds_sipp = (total_investment * 0.5) * 0.98;
            funds_isa = (total_investment * 0.25) * 0.98;
            funds_gia = (total_investment * 0.25) * 0.98;
            funds_isa_cash = total_investment * 0.005;
            funds_sipp_cash = total_investment * 0.01;
            funds_gia_cash = total_investment * 0.005;
            total_savings_and_investments = total_investment * 0.98;
            total_savings_and_investments_cash = total_investment * 0.02;
        } else if (portfolio_with == 'ISA_ONLY_CASH_1') {
            funds_isa = (total_investment) * 0.98;
            funds_sipp = 0;
            funds_gia = 0;
            funds_isa_cash = (total_investment) * 0.02;
            funds_sipp_cash = 0;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment * 0.98;
            total_savings_and_investments_cash = total_investment * 0.02;
        }
        // cash 2
        else if (portfolio_with == '100%_CASH_2') {
            funds_sipp = (total_investment) * 0.95;
            funds_isa = 0;
            funds_gia = 0;
            funds_isa_cash = 0;
            funds_sipp_cash = (total_investment) * 0.05;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment * 0.95;
            total_savings_and_investments_cash = total_investment * 0.05;
        } else if (portfolio_with == '50%_CASH_2') {
            funds_sipp = (total_investment * 0.5) * 0.95;
            funds_isa = (total_investment * 0.25) * 0.95;
            funds_gia = (total_investment * 0.25) * 0.95;
            funds_isa_cash = total_investment * 0.0125;
            funds_sipp_cash = total_investment * 0.025;
            funds_gia_cash = total_investment * 0.0125;
            total_savings_and_investments = total_investment * 0.95;
            total_savings_and_investments_cash = total_investment * 0.05;
        } else if (portfolio_with == 'ISA_ONLY_CASH_2') {
            funds_isa = (total_investment) * 0.95;
            funds_sipp = 0;
            funds_gia = 0;
            funds_isa_cash = (total_investment) * 0.05;
            funds_sipp_cash = 0;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment * 0.95;
            total_savings_and_investments_cash = total_investment * 0.05;
        }
        // cash 3
        else if (portfolio_with == '100%_CASH_3') {
            funds_sipp = (total_investment) * 0.90;
            funds_isa = 0;
            funds_gia = 0;
            funds_isa_cash = 0;
            funds_sipp_cash = (total_investment) * 0.1;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment * 0.9;
            total_savings_and_investments_cash = total_investment * 0.1;
        } else if (portfolio_with == '50%_CASH_3') {
            funds_sipp = (total_investment * 0.5) * 0.9;
            funds_isa = (total_investment * 0.25) * 0.9;
            funds_gia = (total_investment * 0.25) * 0.9;
            funds_isa_cash = total_investment * 0.025;
            funds_sipp_cash = total_investment * 0.05;
            funds_gia_cash = total_investment * 0.025;
            total_savings_and_investments = total_investment * 0.9;
            total_savings_and_investments_cash = total_investment * 0.1;
        } else if (portfolio_with == 'ISA_ONLY_CASH_3') {
            funds_isa = (total_investment) * 0.90;
            funds_sipp = 0;
            funds_gia = 0;
            funds_isa_cash = (total_investment) * 0.1;
            funds_sipp_cash = 0;
            funds_gia_cash = 0;
            total_savings_and_investments = total_investment * 0.9;
            total_savings_and_investments_cash = total_investment * 0.1;
        }

        user_data.total_savings_and_investments = total_savings_and_investments;
        user_data.total_shares = 0;
        user_data.total_savings_and_investments_cash = total_savings_and_investments_cash;
        user_data.total_savings_and_investments_total = total_investment;
        user_data.total_all = total_investment;
        user_data.investment_products = 'yes';

        user_data.funds_isa = funds_isa;
        user_data.funds_sipp = funds_sipp;
        user_data.funds_gia = funds_gia;
        user_data.funds_isa_cash = funds_isa_cash;
        user_data.funds_sipp_cash = funds_sipp_cash;
        user_data.funds_gia_cash = funds_gia_cash;
        user_data.funds_jisa = 0;
        user_data.funds_jsipp = 0;
        user_data.funds_onshore_bond = 0;
        user_data.funds_offshore_bond = 0;
        user_data.funds_lifetime_isa = 0;


        user_data.ex_instruments_isa = 0;
        user_data.ex_instruments_sipp = 0;
        user_data.ex_instruments_gia = 0;
        user_data.ex_instruments_jisa = 0;
        user_data.ex_instruments_jsipp = 0;
        user_data.ex_instruments_onshore_bond = 0;
        user_data.ex_instruments_offshore_bond = 0;
        user_data.ex_instruments_lifetime_isa = 0;

        user_data.age = 26;
        user_data.gender = 'male';
        let $this = this;
        year = 1;
        total = this.year_total(platform, platform_data, user_data, year, portfolio_with);

        if(portfolio_with == 'ISA_ONLY_CASH_3') {
            console.log('HTB CONSOLE ISA_ONLY_CASH_3||||||||||||++__+__+_++|||||')
            console.log(user_data)
            console.log('HTB CONSOLE ISA_ONLY_CASH_3||||||||||||++__+__+_++|||||')
        }
        
        return total;
    }


    year_total(platform, platform_data, user_data, year) {
        this.platform = platform;

        let method = platform.calculation_method;
        let total = 0;
        let is_growth = this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;
        switch (method) {
            case 1:
                this.custody_charges = new m1.Calculator_Custody_Charges_Method_1();
                this.tempCustodyCharges = new m1.Calculator_Custody_Charges_Method_1();
                break;

            case 2:
                this.custody_charges = new m2.Calculator_Custody_Charges_Method_2();
                this.tempCustodyCharges = new m2.Calculator_Custody_Charges_Method_2();
                break;

            case 3:
                this.custody_charges = new m3.Calculator_Custody_Charges_Method_3();
                this.tempCustodyCharges = new m3.Calculator_Custody_Charges_Method_3();
                break;

            case 4:
                this.custody_charges = new m4.Calculator_Custody_Charges_Method_4();
                this.tempCustodyCharges = new m4.Calculator_Custody_Charges_Method_4();
                break;

            case 5:
                this.custody_charges = new m5.Calculator_Custody_Charges_Method_5();
                this.tempCustodyCharges = new m5.Calculator_Custody_Charges_Method_5();
                break;

            default:
                this.custody_charges = new m1.Calculator_Custody_Charges_Method_1();
                this.tempCustodyCharges = new m1.Calculator_Custody_Charges_Method_1();
                break;
        }

        this.product_charges = new cp.Calculator_Product_Charges(platform, platform_data);
        this.dealing_charges = new dl.Calculator_Dealing_Charges();
        this.acc_opening_charges = new op.Calculator_Acc_Opening_Charges();
        this.adviser_charges = new ac.Calculator_Adviser_Charges(growth_rate);

        // Adviser Charges
        this.adviser_charges.set_user_data(_.clone(user_data));
        const isInitialAdviserCharge = this.user_data.initial_adviser_charges && parseFloat(this.user_data.initial_adviser_charges) > 0;
        const isAnnualAdviserCharge = this.user_data.annual_adviser_charges && parseFloat(this.user_data.annual_adviser_charges) > 0;
        let initial_adviser_charge = 0;
        let ongoing_adviser_charges = 0;

        if (isInitialAdviserCharge) {
            //Initial Adviser Charge
            initial_adviser_charge = this.adviser_charges.get_total_initial(year);
            user_data = _.clone(this.adviser_charges.user_data);
            this.initial_adviser_charges_total = initial_adviser_charge;
            this.initial_adviser_charges_all = this.adviser_charges.initial_adviser_charges_all;
        }

        this.custody_charges.set_user_data(_.clone(user_data));
        this.product_charges.set_user_data(_.clone(user_data));
        this.dealing_charges.set_user_data(_.clone(user_data));
        this.acc_opening_charges.set_user_data(_.clone(user_data));
        this.tempCustodyCharges.set_user_data(_.clone(user_data));

        this.custody_charges.set_platform_data(platform, platform_data);
        this.dealing_charges.set_platform_data(platform, platform_data);
        this.acc_opening_charges.set_platform_data(platform, platform_data);
        this.tempCustodyCharges.set_platform_data(platform, platform_data);

        this.calculate_for_transact = false;
        if (method === 5) {
            this.is_excluded = this.custody_charges.is_excluded_due_top_aua();
            if (this.is_excluded && this.user_data.investments_today === 'over_years' && platform.info_url === 'transact-portfolios-120k' && this.over_years > 1) {
                //calculate for transact and add data to over 100k transact
                this.calculate_for_transact = true;
                this.transact_total = this.year_cost;
                this.transact_total.totals = this.totals;
            }
        } else {
            this.is_excluded = false;
        }
        year = 1;
        let openingCharges = this.acc_opening_charges.get_total().openingCharges;
        let dealing = this.dealing_charges.get_total();
        let openning = this.acc_opening_charges.get_total().total;

        const chargesPerYear = this.get_per_product_total(year, user_data);
        if (year == 1) {
            const cash_int_adviser = this.tempCustodyCharges.interest_and_platform_cash_charges(year, true, this.initialProducts, this.initialProducts);
        } else {
            this.tempCustodyCharges.cash = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        }

        // On-going Adviser Charge
        if (isAnnualAdviserCharge) {
            ongoing_adviser_charges = this.adviser_charges.get_total_ongoing(chargesPerYear, year, this.tempCustodyCharges.cash);
            this.adviser_charges.ongoing_adviser_charges_all = this.adviser_charges.ongoing_adviser_charges_all && Object.keys(this.adviser_charges.ongoing_adviser_charges_all).length > 0 ? this.adviser_charges.ongoing_adviser_charges_all : this.initialProducts;
        }
        const productCharges = this.product_charges.get_total_product_charges(chargesPerYear, year, this.tempCustodyCharges.cash);
        let cash_charges_obj = this.custody_charges.interest_and_platform_cash_charges(year, true, openingCharges, this.adviser_charges.ongoing_adviser_charges_all, productCharges.allProducts); //Ticket#243

        // Year custody - funds
        let fund_dealing = this.dealing_charges.total_funds();
        let ext_dealing = this.dealing_charges.total_ex_instruments();
        let cash_int_amnt = cash_charges_obj.cash_interest
        let p_cash_amnt = cash_charges_obj.custody_cash

        let ex_custody = this.custody_charges.total_ex_instruments(year, true, ext_dealing.dealing, openingCharges, this.adviser_charges.ongoing_adviser_charges_all, productCharges.allProducts);
        let funds_custody = this.custody_charges.total_funds(year, true, ex_custody.prod_excustody, ext_dealing.dealing, openingCharges, this.adviser_charges.ongoing_adviser_charges_all, productCharges.allProducts);
        funds_custody = funds_custody.total
        ex_custody = ex_custody.total
        let custody = this.custody_charges.get_total(funds_custody, ex_custody, cash_int_amnt, p_cash_amnt)

        total = custody + productCharges.total + dealing + openning + ongoing_adviser_charges;
        if (this.custody_charges.is_excluded || this.product_charges.is_excluded || this.dealing_charges.is_excluded) {
            this.is_excluded = true;
        }
        return total;
    }

    get_platform_queue() {
        try {
            let $this = this;
            let platform_queue = {};
            let heat_map_ranges = {
                50000: Number,
                100000: Number,
                250000: Number,
                500000: Number,
                1000000: Number,
                2000000: Number,
            }
            let count_of_platforms = 0;
            let average_cost_fifty_percent_total = {}
            let average_cost_hundred_percent_total = {}
            let average_cost_isa_hundred_percent_total = {}
            
            let average_cost_fifty_percent_total_cash_1 = {}
            let average_cost_hundred_percent_total_cash_1 = {}
            let average_cost_isa_hundred_percent_total_cash_1 = {}
            
            let average_cost_fifty_percent_total_cash_2 = {}
            let average_cost_hundred_percent_total_cash_2 = {}
            let average_cost_isa_hundred_percent_total_cash_2 = {}
            
            let average_cost_fifty_percent_total_cash_3 = {}
            let average_cost_hundred_percent_total_cash_3 = {}
            let average_cost_isa_hundred_percent_total_cash_3 = {}

            _.each(this.platforms, function (platform, key) {

                console.log('||||||| HTB PLTFRM |||||||')
                console.log(platform)

                $this.year_cost = {};
                $this.totals = {};

                // Has current version expired date?
                let active_to = new Date(platform.active_to);
                let expiry_date = Date.now() > active_to.getTime();
                if (expiry_date) {
                    return;
                }
                $this.platform_id = platform.platform_id;
                platform_queue[key] = {};
                let platform_data = _.where($this.platforms_data, { 'parent_id': platform.id });
                platform_queue[key].fifty_percent_in_SIPP = {};
                platform_queue[key].hundred_percent_in_SIPP = {};

                platform_queue[key].fifty_percent_in_SIPP_cash_1 = {};
                platform_queue[key].hundred_percent_in_SIPP_cash_1 = {};

                platform_queue[key].fifty_percent_in_SIPP_cash_2 = {};
                platform_queue[key].hundred_percent_in_SIPP_cash_2 = {};
                
                platform_queue[key].fifty_percent_in_SIPP_cash_3 = {};
                platform_queue[key].hundred_percent_in_SIPP_cash_3 = {};

                let array_fifty_percent_in_SIPP = {};
                let array_hundred_percent_in_SIPP = {};
                let array_hundred_percent_in_ISA = {};

                let investment_level_at_fifty_percent = 0;
                let cost_at_fifty_percent = 0;

                let investment_level_at_hundred_percent = 0;
                let cost_at_hundred_percent = 0;

                let investment_level_at_isa_only = 0;
                let cost_at_isa_only = 0;
                
                // ||||||||||||||
                let array_fifty_percent_in_SIPP_cash_1 = {};
                let array_hundred_percent_in_SIPP_cash_1 = {};
                let array_hundred_percent_in_ISA_cash_1 = {};

                let investment_level_at_fifty_percent_cash_1 = 0;
                let cost_at_fifty_percent_cash_1 = 0;

                let investment_level_at_hundred_percent_cash_1 = 0;
                let cost_at_hundred_percent_cash_1 = 0;

                let investment_level_at_isa_only_cash_1 = 0;
                let cost_at_isa_only_cash_1 = 0;
                
                // ||||||||||||||
                let array_fifty_percent_in_SIPP_cash_2 = {};
                let array_hundred_percent_in_SIPP_cash_2 = {};
                let array_hundred_percent_in_ISA_cash_2 = {};

                let investment_level_at_fifty_percent_cash_2 = 0;
                let cost_at_fifty_percent_cash_2 = 0;

                let investment_level_at_hundred_percent_cash_2 = 0;
                let cost_at_hundred_percent_cash_2 = 0;

                let investment_level_at_isa_only_cash_2 = 0;
                let cost_at_isa_only_cash_2 = 0;
                
                // ||||||||||||||
                let array_fifty_percent_in_SIPP_cash_3 = {};
                let array_hundred_percent_in_SIPP_cash_3 = {};
                let array_hundred_percent_in_ISA_cash_3 = {};

                let investment_level_at_fifty_percent_cash_3 = 0;
                let cost_at_fifty_percent_cash_3 = 0;

                let investment_level_at_hundred_percent_cash_3 = 0;
                let cost_at_hundred_percent_cash_3 = 0;

                let investment_level_at_isa_only_cash_3 = 0;
                let cost_at_isa_only_cash_3 = 0;

                count_of_platforms++;
                let i = 0;
                _.each(heat_map_ranges, function (key1, range) {
                    range = parseFloat(range);
                    
                    //Cost At 50% in SIPP
                    cost_at_fifty_percent = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '50%');
                    investment_level_at_fifty_percent = (cost_at_fifty_percent / range) * 100;
                    investment_level_at_fifty_percent = parseFloat(investment_level_at_fifty_percent);
                    array_fifty_percent_in_SIPP[range] = {
                        'cost': cost_at_fifty_percent,
                        'investment_level': investment_level_at_fifty_percent,
                    };
                    cost_at_fifty_percent_cash_1 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '50%_CASH_1');
                    investment_level_at_fifty_percent_cash_1 = (cost_at_fifty_percent_cash_1 / range) * 100;
                    investment_level_at_fifty_percent_cash_1 = parseFloat(investment_level_at_fifty_percent_cash_1);
                    array_fifty_percent_in_SIPP_cash_1[range] = {
                        'cost': cost_at_fifty_percent_cash_1,
                        'investment_level': investment_level_at_fifty_percent_cash_1,
                    };
                    cost_at_fifty_percent_cash_2 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '50%_CASH_2');
                    investment_level_at_fifty_percent_cash_2 = (cost_at_fifty_percent_cash_2 / range) * 100;
                    investment_level_at_fifty_percent_cash_2 = parseFloat(investment_level_at_fifty_percent_cash_2);
                    array_fifty_percent_in_SIPP_cash_2[range] = {
                        'cost': cost_at_fifty_percent_cash_2,
                        'investment_level': investment_level_at_fifty_percent_cash_2,
                    };
                    cost_at_fifty_percent_cash_3 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '50%_CASH_3');
                    investment_level_at_fifty_percent_cash_3 = (cost_at_fifty_percent_cash_3 / range) * 100;
                    investment_level_at_fifty_percent_cash_3 = parseFloat(investment_level_at_fifty_percent_cash_3);
                    array_fifty_percent_in_SIPP_cash_3[range] = {
                        'cost': cost_at_fifty_percent_cash_3,
                        'investment_level': investment_level_at_fifty_percent_cash_3,
                    };
                    //Cost At 100% in SIPP
                    cost_at_hundred_percent = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '100%');
                    investment_level_at_hundred_percent = (cost_at_hundred_percent / range) * 100;
                    investment_level_at_hundred_percent = parseFloat(investment_level_at_hundred_percent);
                    array_hundred_percent_in_SIPP[range] = {
                        'cost': cost_at_hundred_percent,
                        'investment_level': investment_level_at_hundred_percent,
                    };
                    cost_at_hundred_percent_cash_1 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '100%_CASH_1');
                    investment_level_at_hundred_percent_cash_1 = (cost_at_hundred_percent_cash_1 / range) * 100;
                    investment_level_at_hundred_percent_cash_1 = parseFloat(investment_level_at_hundred_percent_cash_1);
                    array_hundred_percent_in_SIPP_cash_1[range] = {
                        'cost': cost_at_hundred_percent_cash_1,
                        'investment_level': investment_level_at_hundred_percent_cash_1,
                    };
                    cost_at_hundred_percent_cash_2 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '100%_CASH_2');
                    investment_level_at_hundred_percent_cash_2 = (cost_at_hundred_percent_cash_2 / range) * 100;
                    investment_level_at_hundred_percent_cash_2 = parseFloat(investment_level_at_hundred_percent_cash_2);
                    array_hundred_percent_in_SIPP_cash_2[range] = {
                        'cost': cost_at_hundred_percent_cash_2,
                        'investment_level': investment_level_at_hundred_percent_cash_2,
                    };
                    cost_at_hundred_percent_cash_3 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, '100%_CASH_3');
                    investment_level_at_hundred_percent_cash_3 = (cost_at_hundred_percent_cash_3 / range) * 100;
                    investment_level_at_hundred_percent_cash_3 = parseFloat(investment_level_at_hundred_percent_cash_3);
                    array_hundred_percent_in_SIPP_cash_3[range] = {
                        'cost': cost_at_hundred_percent_cash_3,
                        'investment_level': investment_level_at_hundred_percent_cash_3,
                    };

                    //Cost At 100% in ISA
                    cost_at_isa_only = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, 'ISA_ONLY');
                    investment_level_at_isa_only = (cost_at_isa_only / range) * 100;
                    investment_level_at_isa_only = parseFloat(investment_level_at_isa_only);
                    array_hundred_percent_in_ISA[range] = {
                        'cost': cost_at_isa_only,
                        'investment_level': investment_level_at_isa_only,
                    };
                    cost_at_isa_only_cash_1 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, 'ISA_ONLY_CASH_1');
                    investment_level_at_isa_only_cash_1 = (cost_at_isa_only_cash_1 / range) * 100;
                    investment_level_at_isa_only_cash_1 = parseFloat(investment_level_at_isa_only_cash_1);
                    array_hundred_percent_in_ISA_cash_1[range] = {
                        'cost': cost_at_isa_only_cash_1,
                        'investment_level': investment_level_at_isa_only_cash_1,
                    };
                    cost_at_isa_only_cash_2 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, 'ISA_ONLY_CASH_2');
                    investment_level_at_isa_only_cash_2 = (cost_at_isa_only_cash_2 / range) * 100;
                    investment_level_at_isa_only_cash_2 = parseFloat(investment_level_at_isa_only_cash_2);
                    array_hundred_percent_in_ISA_cash_2[range] = {
                        'cost': cost_at_isa_only_cash_2,
                        'investment_level': investment_level_at_isa_only_cash_2,
                    };
                    cost_at_isa_only_cash_3 = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data), range, 'ISA_ONLY_CASH_3');
                    investment_level_at_isa_only_cash_3 = (cost_at_isa_only_cash_3 / range) * 100;
                    investment_level_at_isa_only_cash_3 = parseFloat(investment_level_at_isa_only_cash_3);
                    array_hundred_percent_in_ISA_cash_3[range] = {
                        'cost': cost_at_isa_only_cash_3,
                        'investment_level': investment_level_at_isa_only_cash_3,
                    };


                    platform_queue.count_of_platforms = count_of_platforms;
                    if (average_cost_fifty_percent_total[range]) {
                        average_cost_fifty_percent_total[range] += investment_level_at_fifty_percent
                    } else {
                        average_cost_fifty_percent_total[range] = investment_level_at_fifty_percent;
                    }

                    if (average_cost_hundred_percent_total[range]) {
                        average_cost_hundred_percent_total[range] += investment_level_at_hundred_percent
                    } else {
                        average_cost_hundred_percent_total[range] = investment_level_at_hundred_percent;
                    }

                    if (average_cost_isa_hundred_percent_total[range]) {
                        average_cost_isa_hundred_percent_total[range] += investment_level_at_isa_only
                    } else {
                        average_cost_isa_hundred_percent_total[range] = investment_level_at_isa_only;
                    }
                    
                    if (average_cost_fifty_percent_total_cash_1[range]) {
                        average_cost_fifty_percent_total_cash_1[range] += investment_level_at_fifty_percent_cash_1;
                    } else {
                        average_cost_fifty_percent_total_cash_1[range] = investment_level_at_fifty_percent_cash_1;
                    }

                    if (average_cost_hundred_percent_total_cash_1[range]) {
                        average_cost_hundred_percent_total_cash_1[range] += investment_level_at_hundred_percent_cash_1;
                    } else {
                        average_cost_hundred_percent_total_cash_1[range] = investment_level_at_hundred_percent_cash_1;
                    }

                    if (average_cost_isa_hundred_percent_total_cash_1[range]) {
                        average_cost_isa_hundred_percent_total_cash_1[range] += investment_level_at_isa_only_cash_1;
                    } else {
                        average_cost_isa_hundred_percent_total_cash_1[range] = investment_level_at_isa_only_cash_1;
                    }
                    
                    if (average_cost_fifty_percent_total_cash_2[range]) {
                        average_cost_fifty_percent_total_cash_2[range] += investment_level_at_fifty_percent_cash_2;
                    } else {
                        average_cost_fifty_percent_total_cash_2[range] = investment_level_at_fifty_percent_cash_2;
                    }

                    if (average_cost_hundred_percent_total_cash_2[range]) {
                        average_cost_hundred_percent_total_cash_2[range] += investment_level_at_hundred_percent_cash_2;
                    } else {
                        average_cost_hundred_percent_total_cash_2[range] = investment_level_at_hundred_percent_cash_2;
                    }

                    if (average_cost_isa_hundred_percent_total_cash_2[range]) {
                        average_cost_isa_hundred_percent_total_cash_2[range] += investment_level_at_isa_only_cash_2;
                    } else {
                        average_cost_isa_hundred_percent_total_cash_2[range] = investment_level_at_isa_only_cash_2;
                    }
                    
                    if (average_cost_fifty_percent_total_cash_3[range]) {
                        average_cost_fifty_percent_total_cash_3[range] += investment_level_at_fifty_percent_cash_3;
                    } else {
                        average_cost_fifty_percent_total_cash_3[range] = investment_level_at_fifty_percent_cash_3;
                    }

                    if (average_cost_hundred_percent_total_cash_3[range]) {
                        average_cost_hundred_percent_total_cash_3[range] += investment_level_at_hundred_percent_cash_3;
                    } else {
                        average_cost_hundred_percent_total_cash_3[range] = investment_level_at_hundred_percent_cash_3;
                    }

                    if (average_cost_isa_hundred_percent_total_cash_3[range]) {
                        average_cost_isa_hundred_percent_total_cash_3[range] += investment_level_at_isa_only_cash_3;
                    } else {
                        average_cost_isa_hundred_percent_total_cash_3[range] = investment_level_at_isa_only_cash_3;
                    }

                });
                platform_queue[key].platform_id = platform.platform_id;
                platform_queue[key].platform_name = platform.platform_name;

                platform_queue[key].fifty_percent_in_SIPP = array_fifty_percent_in_SIPP;
                platform_queue[key].hundred_percent_in_SIPP = array_hundred_percent_in_SIPP;
                platform_queue[key].hundred_percent_in_ISA = array_hundred_percent_in_ISA;
                
                platform_queue[key].fifty_percent_in_SIPP_cash_1 = array_fifty_percent_in_SIPP_cash_1;
                platform_queue[key].hundred_percent_in_SIPP_cash_1 = array_hundred_percent_in_SIPP_cash_1;
                platform_queue[key].hundred_percent_in_ISA_cash_1 = array_hundred_percent_in_ISA_cash_1;
                
                platform_queue[key].fifty_percent_in_SIPP_cash_2 = array_fifty_percent_in_SIPP_cash_2;
                platform_queue[key].hundred_percent_in_SIPP_cash_2 = array_hundred_percent_in_SIPP_cash_2;
                platform_queue[key].hundred_percent_in_ISA_cash_2 = array_hundred_percent_in_ISA_cash_2;
                
                platform_queue[key].fifty_percent_in_SIPP_cash_3 = array_fifty_percent_in_SIPP_cash_3;
                platform_queue[key].hundred_percent_in_SIPP_cash_3 = array_hundred_percent_in_SIPP_cash_3;
                platform_queue[key].hundred_percent_in_ISA_cash_3 = array_hundred_percent_in_ISA_cash_3;

                platform_queue[key].count_of_platforms = count_of_platforms;
                platform_queue.average_cost_fifty_percent_total = average_cost_fifty_percent_total;
                platform_queue.average_cost_hundred_percent_total = average_cost_hundred_percent_total;
                platform_queue.average_cost_isa_hundred_percent_total = average_cost_isa_hundred_percent_total;
                
                platform_queue.average_cost_fifty_percent_total_cash_1 = average_cost_fifty_percent_total_cash_1;
                platform_queue.average_cost_hundred_percent_total_cash_1 = average_cost_hundred_percent_total_cash_1;
                platform_queue.average_cost_isa_hundred_percent_total_cash_1 = average_cost_isa_hundred_percent_total_cash_1;
                
                platform_queue.average_cost_fifty_percent_total_cash_2 = average_cost_fifty_percent_total_cash_2;
                platform_queue.average_cost_hundred_percent_total_cash_2 = average_cost_hundred_percent_total_cash_2;
                platform_queue.average_cost_isa_hundred_percent_total_cash_2 = average_cost_isa_hundred_percent_total_cash_2;
                
                platform_queue.average_cost_fifty_percent_total_cash_3 = average_cost_fifty_percent_total_cash_3;
                platform_queue.average_cost_hundred_percent_total_cash_3 = average_cost_hundred_percent_total_cash_3;
                platform_queue.average_cost_isa_hundred_percent_total_cash_3 = average_cost_isa_hundred_percent_total_cash_3;
                console.log('||||||||||||||||| HTB INFO ' + platform.platform_name)
                console.log(platform.platform_name)
                console.log(platform_queue[key])
                // if ($this.is_excluded === true) {
                //     delete platform_queue[key]
                //     console.log(platform.platform_name + ' deleted!')
                // }
            });
            let queue = {};
            if (_.indexOf(this.sort_keys, this.order_by) < 0) {
                this.order_by = 'cost';
            }
            queue = this.sort_queue(platform_queue, this.order_by, this.order);
            return platform_queue;
        } catch (err) {
            // .log(err);
            throw err;
        }
    }

    sort_queue(platform_queue, order_by = 'cost', order = constants.SORT_ASC) {
        if (order_by === 'cost') {
            if (this.order === constants.SORT_ASC) {
                platform_queue = _.sortBy(platform_queue, order_by);
            }
            if (this.order === constants.SORT_DESC) {
                platform_queue = _.sortBy(platform_queue, order_by).reverse();
            }
        } else {
            if (this.order === constants.SORT_ASC) {
                platform_queue = _.sortBy(platform_queue, function (o) {
                    return o.data[order_by]
                });
            }
            if (this.order === constants.SORT_DESC) {
                platform_queue = _.sortBy(platform_queue, function (o) {
                    return -o.data[order_by]
                });
            }
        }

        return platform_queue;
    }

    get_per_product_total(year, user_data) {
        let pp_charges = {};
        let is_growth = this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;
        pp_charges.funds = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            total: 0
        };
        pp_charges.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            total: 0
        };
        pp_charges.cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            total: 0
        };
        pp_charges.cash1 = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };
        pp_charges.planning_funds = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            total: 0
        };
        pp_charges.planning_eti = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            total: 0
        };
        pp_charges.planning_cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            total: 0
        };
        pp_charges.cash_int_values = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
            total: 0
        }

        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond','lifetime_isa'];
        let $this = this;
        pp_charges.all = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
        let temp_product_charges = {};
        temp_product_charges.cash = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
        };
        temp_product_charges.funds = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
        };
        temp_product_charges.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0,
        };

        _.each(products, function (product) {
            if (year === 1) {
                // Actual Investment
                pp_charges.funds[product] = parseFloat(user_data['funds_' + product]) * growth_rate;
                pp_charges.ex_instruments[product] = parseFloat(user_data['ex_instruments_' + product]) * growth_rate;
                pp_charges.cash_int_values[product] = user_data['funds_' + product + '_cash'];

                // Additional Investment
                pp_charges.planning_funds[product] = (parseFloat(user_data['planning_' + product]) - parseFloat(user_data['planning_ex_instruments_' + product])) * growth_rate;
                pp_charges.planning_eti[product] = parseFloat(user_data['planning_ex_instruments_' + product]) * growth_rate;
                pp_charges.planning_cash[product] = parseFloat(user_data['planning_cash_' + product]);
            } else {
                const tempFund = $this.custody_charges.get_fund_vals();
                const tempETI = $this.custody_charges.get_et_vals();
                const tempCash = $this.custody_charges.get_cash_vals();
                pp_charges.funds[product] = (tempFund && tempFund[product]) ? (parseFloat(tempFund[product]) * growth_rate) : 0;
                pp_charges.ex_instruments[product] = (tempETI && tempETI[product]) ? (parseFloat(tempETI[product]) * growth_rate) : 0;
                pp_charges.cash_int_values[product] = (tempCash && tempCash[product]) ? tempCash[product] : 0;
            }
            pp_charges.funds.total += pp_charges.funds[product];
            pp_charges.ex_instruments.total += pp_charges.ex_instruments[product];
        });
        return pp_charges;
    }
};