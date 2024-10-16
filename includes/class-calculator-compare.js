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
sd = require('./class-calculator-special-deals');
constants = require('./const');
module.exports.cc = class Calculator_Compare {


    constructor(platforms, platforms_data, user_data) {
        console.log("🚀 ~ constructor ~ platforms", platforms, platforms_data, user_data);
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

    get_platform_cost(platform, platform_data, user_data) {
        let total = 0;
        let discountMatch = false;
        let discountTotal = 0;
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


        // ticket 307
        let planning_lifetime_isa = 0;
        let planning_ex_instruments_lifetime_isa = 0;
        let planning_cash_lifetime_isa = 0;

        this.over_years = 0;
        //RSPL TASK#22
        this.totals.cash = 0;
        this.totals.funds = 0;
        this.totals.ex_instruments = 0;
        this.totals.product_charges = 0;
        this.totals.dealing_charges_funds = 0;
        this.totals.dealing_charges_ex_instruments = 0;
        this.totals.dealing_charges_total = 0;
        this.totals.openning_fee = 0;


        //Ticket#192 - checklist -2 
        let is_growth = this.user_data.is_growth;
        let growth_rate = (is_growth == 'yes' ? 4 : this.user_data.growth_rate);
        growth_rate = 1 + growth_rate / 100;
        let roles_commas = this.user_data.roles_commas;
        let roles_array = ['subscriber'];
        if (roles_commas) {
            const tempRoles = roles_commas.split(",");
            if (tempRoles.includes("adviser") || tempRoles.includes("subscriber")) {
                roles_array = _.clone(tempRoles);
            } else {
                roles_array = tempRoles.concat(roles_array);
            }
            this.user_data.roles_commas = roles_array.toString();
        }
        //Ticket#192 - checklist -2 end 
        let $this = this;
        //Ticket#192 - checklist -3 Start

        let total_yearly_investment_total = user_data.total_yearly_investment_total;
        let total_investment = user_data.total_savings_and_investments_total;

        // 307 changes - declaration of planning
        let user_planning_lifetime_isa = $this.user_data.planning_lifetime_isa;
        let user_planning_ex_instruments_lifetime_isa = $this.user_data.planning_ex_instruments_lifetime_isa;
        let user_planning_cash_lifetime_isa = $this.user_data.planning_cash_lifetime_isa;

        // 307 changes - declaration of funds
        let user_funds_lifetime_isa = $this.user_data.funds_lifetime_isa;
        let user_ex_instruments_lifetime_isa = $this.user_data.ex_instruments_lifetime_isa;
        let user_funds_lifetime_isa_cash = $this.user_data.funds_lifetime_isa_cash

        //Ticket#192 - checklist -3 End
        if (over_years === 'over_years') {
            let range = parseFloat(user_data.investments_over) + 1;
            $this.over_years = range;
            _.each(_.range(1, range), function (year) {
                planning_gia = ($this.user_data.planning_gia - $this.user_data.planning_ex_instruments_gia);
                planning_isa = ($this.user_data.planning_isa - $this.user_data.planning_ex_instruments_isa);
                planning_jisa = ($this.user_data.planning_jisa - $this.user_data.planning_ex_instruments_jisa);
                planning_sipp = ($this.user_data.planning_sipp - $this.user_data.planning_ex_instruments_sipp);
                planning_jsipp = ($this.user_data.planning_jsipp - $this.user_data.planning_ex_instruments_jsipp);
                planning_onshore_bond = ($this.user_data.planning_onshore_bond - $this.user_data.planning_ex_instruments_onshore_bond);
                planning_offshore_bond = ($this.user_data.planning_offshore_bond - $this.user_data.planning_ex_instruments_offshore_bond);
                planning_lifetime_isa = user_planning_lifetime_isa - user_planning_ex_instruments_lifetime_isa;

                planning_ex_instruments_gia = parseFloat($this.user_data.planning_ex_instruments_gia);
                planning_ex_instruments_isa = parseFloat($this.user_data.planning_ex_instruments_isa);
                planning_ex_instruments_jisa = parseFloat($this.user_data.planning_ex_instruments_jisa);
                planning_ex_instruments_sipp = parseFloat($this.user_data.planning_ex_instruments_sipp);
                planning_ex_instruments_jsipp = parseFloat($this.user_data.planning_ex_instruments_jsipp);
                planning_ex_instruments_onshore_bond = parseFloat($this.user_data.planning_ex_instruments_onshore_bond);
                planning_ex_instruments_offshore_bond = parseFloat($this.user_data.planning_ex_instruments_offshore_bond);
                planning_ex_instruments_lifetime_isa = parseFloat(user_planning_ex_instruments_lifetime_isa);

                //RSPL TASK#22
                planning_cash_gia = parseFloat($this.user_data.planning_cash_gia);
                planning_cash_isa = parseFloat($this.user_data.planning_cash_isa);
                planning_cash_jisa = parseFloat($this.user_data.planning_cash_jisa);
                planning_cash_sipp = parseFloat($this.user_data.planning_cash_sipp);
                planning_cash_jsipp = parseFloat($this.user_data.planning_cash_jsipp);
                planning_cash_onshore_bond = parseFloat($this.user_data.planning_cash_onshore_bond);
                planning_cash_offshore_bond = parseFloat($this.user_data.planning_cash_offshore_bond);
                planning_cash_lifetime_isa = parseFloat(user_planning_cash_lifetime_isa);
                // RSPL Task#153

                if (roles_array.includes("adviser") || roles_array.includes("subscriber")) {
                    /*Begin: Ticket:#243 */
                    //Funds calculation
                    user_data.funds_gia = planning_gia + $this.user_data.funds_gia;
                    user_data.funds_isa = planning_isa + $this.user_data.funds_isa;
                    user_data.funds_jisa = planning_jisa + $this.user_data.funds_jisa;
                    user_data.funds_sipp = planning_sipp + $this.user_data.funds_sipp;
                    user_data.funds_jsipp = planning_jsipp + $this.user_data.funds_jsipp;
                    user_data.funds_onshore_bond = planning_onshore_bond + $this.user_data.funds_onshore_bond;
                    user_data.funds_offshore_bond = planning_offshore_bond + $this.user_data.funds_offshore_bond;
                    user_data.funds_lifetime_isa = planning_lifetime_isa + user_funds_lifetime_isa;
                    //Exchange-traded calculation
                    user_data.ex_instruments_gia = planning_ex_instruments_gia + $this.user_data.ex_instruments_gia;
                    user_data.ex_instruments_isa = planning_ex_instruments_isa + $this.user_data.ex_instruments_isa;
                    user_data.ex_instruments_jisa = planning_ex_instruments_jisa + $this.user_data.ex_instruments_jisa;
                    user_data.ex_instruments_sipp = planning_ex_instruments_sipp + $this.user_data.ex_instruments_sipp;
                    user_data.ex_instruments_jsipp = planning_ex_instruments_jsipp + $this.user_data.ex_instruments_jsipp;
                    user_data.ex_instruments_onshore_bond = planning_ex_instruments_onshore_bond + $this.user_data.ex_instruments_onshore_bond;
                    user_data.ex_instruments_offshore_bond = planning_ex_instruments_offshore_bond + $this.user_data.ex_instruments_offshore_bond;
                    user_data.ex_instruments_lifetime_isa = planning_ex_instruments_lifetime_isa + user_ex_instruments_lifetime_isa;
                    /*End: Ticket:#243 */
                    //Ticket#192 - checklist 3 Start
                    total_investment = (parseFloat(total_investment) + parseFloat(total_yearly_investment_total)) * growth_rate;
                    //Ticket#192 - checklist 3 End
                } else {
                    //Funds calculation
                    user_data.funds_gia = planning_gia + $this.user_data.funds_gia;
                    user_data.funds_isa = planning_isa + $this.user_data.funds_isa;
                    user_data.funds_jisa = planning_jisa + $this.user_data.funds_jisa;
                    user_data.funds_sipp = planning_sipp + $this.user_data.funds_sipp;
                    user_data.funds_jsipp = planning_jsipp + $this.user_data.funds_jsipp;
                    user_data.funds_onshore_bond = planning_onshore_bond + $this.user_data.funds_onshore_bond;
                    user_data.funds_offshore_bond = planning_offshore_bond + $this.user_data.funds_offshore_bond;
                    user_data.funds_lifetime_isa = planning_lifetime_isa + user_funds_lifetime_isa;
                    //Exchange-traded calculation
                    user_data.ex_instruments_gia = planning_ex_instruments_gia + $this.user_data.ex_instruments_gia;
                    user_data.ex_instruments_isa = planning_ex_instruments_isa + $this.user_data.ex_instruments_isa;
                    user_data.ex_instruments_jisa = planning_ex_instruments_jisa + $this.user_data.ex_instruments_jisa;
                    user_data.ex_instruments_sipp = planning_ex_instruments_sipp + $this.user_data.ex_instruments_sipp;
                    user_data.ex_instruments_jsipp = planning_ex_instruments_jsipp + $this.user_data.ex_instruments_jsipp;
                    user_data.ex_instruments_onshore_bond = planning_ex_instruments_onshore_bond + $this.user_data.ex_instruments_onshore_bond;
                    user_data.ex_instruments_offshore_bond = planning_ex_instruments_offshore_bond + $this.user_data.ex_instruments_offshore_bond;
                    user_data.ex_instruments_lifetime_isa = planning_ex_instruments_lifetime_isa + user_ex_instruments_lifetime_isa;
                }
                /*Begin: Ticket#243*/
                user_data.funds_gia_cash = planning_cash_gia + $this.user_data.funds_gia_cash;
                user_data.funds_isa_cash = planning_cash_isa + $this.user_data.funds_isa_cash;
                user_data.funds_jisa_cash = planning_cash_jisa + $this.user_data.funds_jisa_cash;
                user_data.funds_sipp_cash = planning_cash_sipp + $this.user_data.funds_sipp_cash;
                user_data.funds_jsipp_cash = planning_cash_jsipp + $this.user_data.funds_jsipp_cash;
                user_data.funds_onshore_bond_cash = planning_cash_onshore_bond + $this.user_data.funds_onshore_bond_cash;
                user_data.funds_offshore_bond_cash = planning_cash_offshore_bond + $this.user_data.funds_offshore_bond_cash;
                user_data.funds_lifetime_isa_cash = planning_cash_lifetime_isa + user_funds_lifetime_isa_cash;
                /*End: Ticket#243*/

                user_data.total_savings_and_investments = (user_data.funds_gia
                    + user_data.funds_isa
                    + user_data.funds_sipp
                    + user_data.funds_jsipp
                    + user_data.funds_jisa
                    + user_data.funds_onshore_bond
                    + user_data.funds_offshore_bond
                    + user_data.ex_instruments_gia
                    + user_data.ex_instruments_isa
                    + user_data.ex_instruments_sipp
                    + user_data.ex_instruments_jsipp
                    + user_data.ex_instruments_jisa
                    + user_data.ex_instruments_onshore_bond
                    + user_data.ex_instruments_offshore_bond
                    + user_data.funds_lifetime_isa
                    + user_data.ex_instruments_lifetime_isa
                );
                //RSPL TASK#37
                user_data.total_savings_and_investments_cash = (user_data.funds_gia_cash
                    + user_data.funds_isa_cash
                    + user_data.funds_sipp_cash
                    + user_data.funds_jsipp_cash
                    + user_data.funds_jisa_cash
                    + user_data.funds_onshore_bond_cash
                    + user_data.funds_offshore_bond_cash
                    + user_data.funds_lifetime_isa_cash
                );

                user_data.total_shares = user_data.ex_instruments_isa + user_data.ex_instruments_gia
                    + user_data.ex_instruments_jsipp + user_data.ex_instruments_jisa +
                    user_data.ex_instruments_sipp + user_data.ex_instruments_onshore_bond + user_data.ex_instruments_offshore_bond +
                    user_data.ex_instruments_lifetime_isa;
                user_data.total_funds = user_data.total_savings_and_investments - user_data.total_shares;
                user_data.total_cash = user_data.total_savings_and_investments_cash;
                total += $this.year_total(platform, platform_data, user_data, year);
            });
        } else if (over_years === 'in_x_years') {
            year = user_data.investments_in_x_years;
            planning_gia = ($this.user_data.planning_gia - $this.user_data.planning_ex_instruments_gia) * year;
            planning_isa = ($this.user_data.planning_isa - $this.user_data.planning_ex_instruments_isa) * year;
            planning_jisa = ($this.user_data.planning_jisa - $this.user_data.planning_ex_instruments_jisa) * year;
            planning_sipp = ($this.user_data.planning_sipp - $this.user_data.planning_ex_instruments_sipp) * year;
            planning_jsipp = ($this.user_data.planning_jsipp - $this.user_data.planning_ex_instruments_jsipp) * year;
            planning_onshore_bond = ($this.user_data.planning_onshore_bond - $this.user_data.planning_ex_instruments_onshore_bond) * year;
            planning_offshore_bond = ($this.user_data.planning_offshore_bond - $this.user_data.planning_ex_instruments_offshore_bond) * year;
            planning_lifetime_isa = (user_planning_lifetime_isa - user_planning_ex_instruments_lifetime_isa) * year;

            planning_ex_instruments_gia = $this.user_data.planning_ex_instruments_gia * year;
            planning_ex_instruments_isa = $this.user_data.planning_ex_instruments_isa * year;
            planning_ex_instruments_jisa = $this.user_data.planning_ex_instruments_jisa * year;
            planning_ex_instruments_sipp = $this.user_data.planning_ex_instruments_sipp * year;
            planning_ex_instruments_jsipp = $this.user_data.planning_ex_instruments_jsipp * year;
            planning_ex_instruments_onshore_bond = $this.user_data.planning_ex_instruments_onshore_bond * year;
            planning_ex_instruments_offshore_bond = $this.user_data.planning_ex_instruments_offshore_bond * year;
            planning_ex_instruments_lifetime_isa = user_planning_ex_instruments_lifetime_isa * year;
            //RSPL TASK#22
            // RSPL Task#153
            planning_cash_gia = $this.user_data.planning_cash_gia * year;
            planning_cash_isa = $this.user_data.planning_cash_isa * year;
            planning_cash_jisa = $this.user_data.planning_cash_jisa * year;
            planning_cash_sipp = $this.user_data.planning_cash_sipp * year;
            planning_cash_jsipp = $this.user_data.planning_cash_jsipp * year;
            planning_cash_onshore_bond = $this.user_data.planning_cash_onshore_bond * year;
            planning_cash_offshore_bond = $this.user_data.planning_cash_offshore_bond * year;
            planning_cash_lifetime_isa = user_planning_cash_lifetime_isa * year;
            /*Begin: Ticket#243*/
            //Funds calculation
            user_data.funds_gia = (planning_gia + $this.user_data.funds_gia);
            user_data.funds_isa = (planning_isa + $this.user_data.funds_isa);
            user_data.funds_jisa = (planning_jisa + $this.user_data.funds_jisa);
            user_data.funds_sipp = (planning_sipp + $this.user_data.funds_sipp);
            user_data.funds_jsipp = (planning_jsipp + $this.user_data.funds_jsipp);
            user_data.funds_onshore_bond = (planning_onshore_bond + $this.user_data.funds_onshore_bond);
            user_data.funds_offshore_bond = (planning_offshore_bond + $this.user_data.funds_offshore_bond);
            user_data.funds_lifetime_isa = planning_lifetime_isa + user_funds_lifetime_isa;
            //Exchange-traded calculation
            user_data.ex_instruments_gia = (planning_ex_instruments_gia + $this.user_data.ex_instruments_gia);
            user_data.ex_instruments_isa = (planning_ex_instruments_isa + $this.user_data.ex_instruments_isa);
            user_data.ex_instruments_jisa = (planning_ex_instruments_jisa + $this.user_data.ex_instruments_jisa);
            user_data.ex_instruments_sipp = (planning_ex_instruments_sipp + $this.user_data.ex_instruments_sipp);
            user_data.ex_instruments_jsipp = (planning_ex_instruments_jsipp + $this.user_data.ex_instruments_jsipp);
            user_data.ex_instruments_onshore_bond = (planning_ex_instruments_onshore_bond + $this.user_data.ex_instruments_onshore_bond);
            user_data.ex_instruments_offshore_bond = (planning_ex_instruments_offshore_bond + $this.user_data.ex_instruments_offshore_bond);
            user_data.ex_instruments_lifetime_isa = planning_ex_instruments_lifetime_isa + user_ex_instruments_lifetime_isa;
            /*End: Ticket#243*/
            if (roles_array.includes("adviser") || roles_array.includes("subscriber")) {
                total_investment = (parseFloat(total_investment) + parseFloat(total_yearly_investment_total)) * growth_rate;


            }
            //RSPL TASK#243
            user_data.funds_gia_cash = planning_cash_gia + this.user_data.funds_gia_cash;
            user_data.funds_isa_cash = planning_cash_isa + this.user_data.funds_isa_cash;
            user_data.funds_jisa_cash = planning_cash_jisa + this.user_data.funds_jisa_cash;
            user_data.funds_sipp_cash = planning_cash_sipp + this.user_data.funds_sipp_cash;
            user_data.funds_jsipp_cash = planning_cash_jsipp + this.user_data.funds_jsipp_cash;
            user_data.funds_onshore_bond_cash = planning_cash_onshore_bond + this.user_data.funds_onshore_bond_cash;
            user_data.funds_offshore_bond_cash = planning_cash_offshore_bond + this.user_data.funds_offshore_bond_cash;
            user_data.funds_lifetime_isa_cash = planning_cash_lifetime_isa + user_funds_lifetime_isa_cash;
            //RSPL TASK#22
            user_data.total_savings_and_investments = (user_data.funds_gia
                + user_data.funds_isa
                + user_data.funds_sipp
                + user_data.funds_jsipp
                + user_data.funds_jisa
                + user_data.funds_onshore_bond
                + user_data.funds_offshore_bond
                + user_data.ex_instruments_gia
                + user_data.ex_instruments_isa
                + user_data.ex_instruments_sipp
                + user_data.ex_instruments_jsipp
                + user_data.ex_instruments_jisa
                + user_data.ex_instruments_onshore_bond
                + user_data.ex_instruments_offshore_bond
                + user_data.ex_instruments_lifetime_isa
                + user_data.funds_lifetime_isa
            );
            //RSPL TASK#37
            user_data.total_savings_and_investments_cash = (user_data.funds_gia_cash
                + user_data.funds_isa_cash
                + user_data.funds_sipp_cash
                + user_data.funds_jsipp_cash
                + user_data.funds_jisa_cash
                + user_data.funds_onshore_bond_cash
                + user_data.funds_offshore_bond_cash
                + user_data.funds_lifetime_isa_cash
            );

            user_data.total_shares = user_data.ex_instruments_isa + user_data.ex_instruments_gia
                + user_data.ex_instruments_jsipp + user_data.ex_instruments_jisa + user_data.ex_instruments_sipp
                + user_data.ex_instruments_onshore_bond + user_data.ex_instruments_offshore_bond
                + user_data.ex_instruments_lifetime_isa;
            user_data.total_funds = user_data.total_savings_and_investments - user_data.total_shares;
            user_data.total_cash = user_data.total_savings_and_investments_cash;
            total += this.year_total(platform, platform_data, user_data, year);

        } else {
            year = 1;
            user_data.planning_cash_gia = parseFloat($this.user_data.planning_cash_gia) || 0;
            user_data.planning_cash_isa = parseFloat($this.user_data.planning_cash_isa) || 0;
            user_data.planning_cash_jisa = parseFloat($this.user_data.planning_cash_jisa) || 0;
            user_data.planning_cash_sipp = parseFloat($this.user_data.planning_cash_sipp) || 0;
            user_data.planning_cash_jsipp = parseFloat($this.user_data.planning_cash_jsipp) || 0;
            user_data.planning_cash_onshore_bond = parseFloat($this.user_data.planning_cash_onshore_bond) || 0;
            user_data.planning_cash_offshore_bond = parseFloat($this.user_data.planning_cash_offshore_bond) || 0;
            user_data.planning_cash_lifetime_isa = parseFloat(user_planning_cash_lifetime_isa) || 0;
            this.user_data.planning_cash_gia = parseFloat($this.user_data.planning_cash_gia) || 0;
            this.user_data.planning_cash_isa = parseFloat($this.user_data.planning_cash_isa) || 0;
            this.user_data.planning_cash_jisa = parseFloat($this.user_data.planning_cash_jisa) || 0;
            this.user_data.planning_cash_sipp = parseFloat($this.user_data.planning_cash_sipp) || 0;
            this.user_data.planning_cash_jsipp = parseFloat($this.user_data.planning_cash_jsipp) || 0;
            this.user_data.planning_cash_onshore_bond = parseFloat($this.user_data.planning_cash_onshore_bond) || 0;
            this.user_data.planning_cash_offshore_bond = parseFloat($this.user_data.planning_cash_offshore_bond) || 0;
            this.user_data.planning_cash_lifetime_isa = parseFloat(user_planning_cash_lifetime_isa) || 0;
            
            //console.log('HTB RETURNED YEAR TOTAL');
            //console.log(this.year_total(platform, platform_data, user_data, year));
            //console.log('-----------');


            total = this.year_total(platform, platform_data, user_data, year)[0];
            discountMatch = this.year_total(platform, platform_data, user_data, year)[2]
            discountTotal = this.year_total(platform, platform_data, user_data, year)[1];
        }
        return [total, discountMatch, discountTotal];
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
        this.adviser_special_deals = new sd.Calculator_Special_Deals();

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
            console.log(`${platform.platform_name} excluded due to aua ` + this.custody_charges.is_excluded_due_top_aua());
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
        if (!this.is_excluded || this.calculate_for_transact) {
            let dealing = this.dealing_charges.get_total();
            let openning = this.acc_opening_charges.get_total().total;
            let openingCharges = this.acc_opening_charges.get_total().openingCharges;
            this.year_cost['year_' + year] = {};
            this.year_cost['year_' + year].funds_total = {};
            this.pp_charges['year_' + year] = this.initialProducts;

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

            // Year custody - funds
            let fund_dealing = this.dealing_charges.total_funds();
            let ext_dealing = this.dealing_charges.total_ex_instruments();

            let cash_charges_obj = this.custody_charges.interest_and_platform_cash_charges(year, openingCharges, this.adviser_charges.ongoing_adviser_charges_all, productCharges.allProducts); //Ticket#243
            let ex_custody = this.custody_charges.total_ex_instruments(year, ext_dealing.dealing, openingCharges, this.adviser_charges.ongoing_adviser_charges_all, productCharges.allProducts);
            let funds_custody = this.custody_charges.total_funds(year, fund_dealing.dealing, openingCharges, this.adviser_charges.ongoing_adviser_charges_all, productCharges.allProducts);

            let d_fund = funds_custody.total;
            let d_ex = ex_custody.total;
            let d_cash = cash_charges_obj.cash_interest;
            console.log('|||')
            console.log('cash_obj for ' + platform.platform_name + ' are ')
            console.log(cash_charges_obj)
            console.log('All Custody Charges for ' + platform.platform_name + ' are ')
            console.log(this.custody_charges)
            console.log('year')
            console.log(year)
            console.log('openingCharges')
            console.log(openingCharges)
            console.log('this.adviser_charges.ongoing_adviser_charges_all')
            console.log(this.adviser_charges.ongoing_adviser_charges_all)
            console.log('productCharges.allProducts')
            console.log(productCharges.allProducts)
            console.log('|||')
            let d_pcash = cash_charges_obj.custody_cash;
            const tempCustodyTotal = parseFloat(d_fund + d_ex + d_cash + d_pcash);
            let custody = this.custody_charges.get_total(d_fund, d_ex, d_cash, d_pcash);
            this.year_cost['year_' + year].custody_total = custody;
            this.year_cost['year_' + year].dealing_charges_ex_instruments = ext_dealing.total;

            if (tempCustodyTotal > custody && constants.isPlatformCapped(this.platform.info_url)) {
                const perClientCappedCharges = constants.generateCappedPerClientCharges(this.user_data, custody);
                d_fund = 0;
                d_ex = 0;
                d_pcash = 0;
                this.custody_charges.platform_custody_cash = this.initialProducts;
                this.custody_charges.funds = this.initialProducts;
                this.custody_charges.ex_instruments = this.initialProducts;
                if (perClientCappedCharges.isCash) {
                    d_pcash = custody;
                    this.custody_charges.platform_custody_cash = perClientCappedCharges.custody;
                } else if (perClientCappedCharges.isFund) {
                    d_fund = custody;
                    this.custody_charges.funds = perClientCappedCharges.custody;
                } else {
                    d_ex = custody;
                    this.custody_charges.ex_instruments = perClientCappedCharges.custody;
                }
            }
            // Year custody
            this.year_cost['year_' + year].funds_total = d_fund;
            this.year_cost['year_' + year].ex_instruments = d_ex;
            this.year_cost['year_' + year].cash_total = d_cash;

            // Yearly cash rate
            this.year_cost['year_' + year].platform_custody_cash_total = d_pcash;

            // Yearly product charges
            this.year_cost['year_' + year].product_charges = productCharges.total;

            // Yearly dealing - funds
            this.year_cost['year_' + year].dealing_charges_funds = this.dealing_charges.funds_total;

            // Yearly dealing - eti
            this.year_cost['year_' + year].dealing_charges_ex_instruments = this.dealing_charges.ex_total;

            // Yearly dealing - total
            this.year_cost['year_' + year].dealing_charges_total = dealing;

            // Yearly adviser charges
            this.year_cost['year_' + year].initial_adviser_charges_total = initial_adviser_charge;
            this.year_cost['year_' + year].ongoing_adviser_charges_total = ongoing_adviser_charges;


            if (year > 1 && user_data.investments_today !== 'in_x_years') {
                this.totals.custody_total = this.totals.custody_total + this.year_cost['year_' + year].custody_total;
                this.totals.funds = this.totals.funds + this.year_cost['year_' + year].funds_total;
                this.totals.ex_instruments = this.totals.ex_instruments + this.year_cost['year_' + year].ex_instruments;
                this.totals.cash = this.totals.cash + this.year_cost['year_' + year].cash_total;
                // RSPL Task#98
                this.totals.platform_custody_cash_total = this.totals.platform_custody_cash_total + this.year_cost['year_' + year].platform_custody_cash_total;
                this.totals.product_charges += productCharges.total;
                this.totals.product_charges_all = this.product_charges.all;
                this.totals.dealing_charges_funds = this.totals.dealing_charges_funds + this.year_cost['year_' + year].dealing_charges_funds;
                this.totals.dealing_charges_ex_instruments = this.totals.dealing_charges_ex_instruments + this.year_cost['year_' + year].dealing_charges_ex_instruments;
                this.totals.dealing_charges_total = dealing;
                this.totals.initial_adviser_charges_total += initial_adviser_charge;
                this.totals.ongoing_adviser_charges_total += ongoing_adviser_charges;
                this.totals.initial_adviser_charges_all = this.adviser_charges.initial_adviser_charges_all;
                this.totals.ongoing_adviser_charges_all = this.adviser_charges.ongoing_adviser_charges_all;
            } else {
                this.totals.custody_total = custody;
                this.totals.funds = this.year_cost['year_' + year].funds_total;
                this.totals.ex_instruments = this.year_cost['year_' + year].ex_instruments;
                // RSPL TASK#22
                this.totals.cash = this.year_cost['year_' + year].cash_total;
                // RSPL Task#98
                this.totals.platform_custody_cash_total = this.year_cost['year_' + year].platform_custody_cash_total;
                this.totals.product_charges = productCharges.total;
                this.totals.product_charges_all = this.product_charges.all;
                this.totals.dealing_charges_funds = this.year_cost['year_' + year].dealing_charges_funds;
                this.totals.dealing_charges_ex_instruments = this.year_cost['year_' + year].dealing_charges_ex_instruments;
                this.totals.dealing_charges_total = dealing;
                this.totals.initial_adviser_charges_total = initial_adviser_charge;
                this.totals.ongoing_adviser_charges_total = ongoing_adviser_charges;
                this.totals.initial_adviser_charges_all = this.adviser_charges.initial_adviser_charges_all;
                this.totals.ongoing_adviser_charges_all = this.adviser_charges.ongoing_adviser_charges_all;
                if (constants.calc_num(openning)) {
                    this.totals.openning_fee = openning;
                }
            }

            total = custody + productCharges.total + dealing + ongoing_adviser_charges;
            if (this.custody_charges.is_excluded || this.product_charges.is_excluded || this.dealing_charges.is_excluded) {
                
                console.log(platform.platform_name + ' custody_charges.is_excluded? ' + this.custody_charges.is_excluded);
                console.log(platform.platform_name + ' product_charges.is_excluded? ' + this.product_charges.is_excluded);
                console.log(platform.platform_name + ' dealing_charges.is_excluded? ' + this.dealing_charges.is_excluded);

                this.is_excluded = true;
            }
            let over_years = user_data.investments_today;
            if (over_years === 'in_x_years') {

                if (year === 1) {

                    this.totals.openning_charges_all = this.acc_opening_charges.all;

                    this.totals.openning_fee = openning;
                    this.year_cost.year_1.openning_fee = openning;

                    total = total + openning;
                } else {
                    this.totals.openning_fee = 0;
                }

            } else {

                if (_.isNumber(openning) && year === 1) {
                    this.totals.openning_charges_all = this.acc_opening_charges.all;

                    this.totals.openning_fee = openning;
                    this.year_cost.year_1.openning_fee = openning;
                    total = total + openning;
                }
            }
        }

        // Special Deals
        this.adviser_special_deals.set_user_data(_.clone(user_data));
        let hasSpecialDeals = false;
        if(this.adviser_special_deals.user_data.active_special_deals !== undefined) {
            hasSpecialDeals = true;
        }
        //console.log('hasSpecialDeals: ' + hasSpecialDeals);

        let discountedTotal = 0;
        let dealMatch = false;

        if (hasSpecialDeals) {
            //Adviser Has Special Deals
            let calcTotals = this.totals;
            let $this = this;
            _.each(JSON.parse(this.adviser_special_deals.user_data.active_special_deals), function(deal){
                if(deal.platform_id == platform.platform_id) {
                    //console.log('htb platform log');
                    //console.log(platform);
                    discountedTotal = $this.adviser_special_deals.calculate_discount(total, calcTotals.ongoing_adviser_charges_total, deal)
                    dealMatch = true
                    //console.log('Deal Match!');
                    //console.log(deal, platform);
                    //console.log('discountedTotal: ' + discountedTotal);
                }
            })
        }

        return [total, discountedTotal, dealMatch];
    }

    get_platform_queue() {
        try {
            let $this = this;
            let platform_queue = {};

            _.each(this.platforms, function (platform, key) {
                $this.year_cost = {};
                $this.totals = {};
                // Has current version expired date?
                let active_to = new Date(platform.active_to);
                let expiry_date = Date.now() > active_to.getTime();
                if (expiry_date) {
                    return;
                }


                //platform_queue.ID = platform.platform_id;
                $this.platform_id = platform.platform_id;
                platform_queue[key] = {};

                platform_queue[key].year_cost = {};
                let platform_data = _.where($this.platforms_data, { 'parent_id': platform.id });

                platform_queue[key].data = {};
                //@todo::add role to check this
                if ($this.user_data.user_id == 1) {
                    platform_queue[key].data = {};
                    platform_queue[key].data = platform;
                    platform_queue[key].platform_id = platform.platform_id;
                    platform_queue[key].sandbox = platform.sandbox;

                }

                platform_queue[key].cost = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data))[0] + $this.initial_adviser_charges_total;
                if($this.get_platform_cost(platform, platform_data, _.clone($this.user_data))[1] === true) {
                    platform_queue[key].discountedCost = $this.get_platform_cost(platform, platform_data, _.clone($this.user_data))[2] + $this.initial_adviser_charges_total;
                }

                if ($this.is_excluded === true) {
                    console.log(platform.platform_name + ' deleted');

                    delete platform_queue[key]

                    console.log(' yes deleted ');
                } else {
                    console.log(' not deleted ');
                    platform_queue[key].data.platform_name = platform.platform_name;
                    platform_queue[key].data.rating = platform.rating;
                    platform_queue[key].data.url = platform.url;
                    platform_queue[key].data.recommended = platform.recommended;
                    platform_queue[key].data.platform_type = platform.platform_type === 1 ? 'Advisor' : 'Myself';
                    platform_queue[key].year_cost = $this.year_cost;
                    platform_queue[key].data.info_url = constants.CTP_URL + 'platform/' + platform.info_url;
                    platform_queue[key].custody_charges = {};
                    platform_queue[key].product_charges = {};
                    platform_queue[key].dealing_charges = {};
                    platform_queue[key].dealing_charges.ex_instruments = {};
                    platform_queue[key].acc_openning_fee = {};
                    /*Begin #346*/
                    let simlified_total = $this.totals.funds + $this.totals.product_charges;
                    platform_queue[key].simplified_total = simlified_total;
                    /*End #346*/
                    platform_queue[key].custody_charges.total = $this.totals.custody_total;
                    //Ticket#192 checklist-3 Start
                    platform_queue[key].adviser_charges = {};


                    platform_queue[key].adviser_charges.annual_adviser_charges = {};
                    platform_queue[key].adviser_charges.initial_adviser_charges = {};
                    //Ticket#192 checklist-3 End
                    platform_queue[key].custody_charges.funds = $this.custody_charges.funds;
                    platform_queue[key].custody_charges.funds_total = $this.totals.funds;

                    //RSPL TASK#22
                    platform_queue[key].custody_charges.cash = $this.custody_charges.cash;
                    platform_queue[key].custody_charges.cash_total = $this.totals.cash;

                    // RSPL Task#98
                    platform_queue[key].custody_charges.platform_custody_cash = $this.custody_charges.platform_custody_cash;
                    platform_queue[key].custody_charges.platform_custody_cash_total = $this.totals.platform_custody_cash_total;

                    platform_queue[key].custody_charges.ex_instruments = $this.custody_charges.ex_instruments;
                    platform_queue[key].custody_charges.ex_instruments_total = $this.totals.ex_instruments;

                    platform_queue[key].product_charges.total = $this.totals.product_charges;
                    platform_queue[key].product_charges.all = $this.totals.product_charges_all;

                    platform_queue[key].dealing_charges.funds = $this.dealing_charges.funds;
                    platform_queue[key].dealing_charges.funds_total = $this.totals.dealing_charges_funds;

                    platform_queue[key].dealing_charges.ex_instruments = $this.dealing_charges.ex_instruments;
                    platform_queue[key].dealing_charges.ex_instruments_total = $this.totals.dealing_charges_ex_instruments;
                    platform_queue[key].dealing_charges.total = $this.totals.dealing_charges_funds + $this.totals.dealing_charges_ex_instruments + $this.totals.openning_fee;

                    platform_queue[key].acc_openning_fee.total = $this.totals.openning_fee;
                    platform_queue[key].acc_openning_fee.all = $this.totals.openning_charges_all;

                    platform_queue[key].adviser_charges.annual_adviser_charges = $this.totals.ongoing_adviser_charges_all;
                    platform_queue[key].adviser_charges.annual_adviser_charges_total = $this.totals.ongoing_adviser_charges_total;
                    platform_queue[key].adviser_charges.initial_adviser_charges = $this.initial_adviser_charges_all;
                    platform_queue[key].adviser_charges.initial_adviser_charges_total = $this.totals.initial_adviser_charges_total;
                    platform_queue[key].adviser_charges.total = $this.totals.ongoing_adviser_charges_total + $this.totals.initial_adviser_charges_total;
                    platform_queue[key].year_cost = $this.year_cost;
                }

            });
            let transact = {};
            let transact_key = null;
            //grab transact
            _.every(platform_queue, function (plat, key) {
                if (plat.data.info_url === constants.CTP_URL + 'platform/' + 'transact') {
                    transact = plat;
                    transact_key = key;
                    return false;
                }
                return true;
            });

            if (!_.isEmpty(transact) && !_.isEmpty($this.transact_total) && $this.over_years > 1) {


                _.every(_.range(1, $this.over_years), function (year) {

                    if (_.isEmpty(platform_queue[transact_key].year_cost['year_' + year])) {
                        platform_queue[transact_key].year_cost['year_' + year] = $this.transact_total['year_' + year];
                        platform_queue[transact_key].custody_charges.funds_total += $this.transact_total['year_' + year].funds_total;
                        platform_queue[transact_key].custody_charges.ex_instruments_total += $this.transact_total['year_' + year].ex_instruments;
                        //RSPL TASK#22 && RSPL Task#98
                        platform_queue[transact_key].custody_charges.cash_total -= $this.transact_total['year_' + year].cash_total;
                        // RSPL Task#98
                        platform_queue[transact_key].custody_charges.platform_custody_cash_total += $this.transact_total['year_' + year].platform_custody_cash_total;
                        platform_queue[transact_key].product_charges.total += $this.transact_total['year_' + year].product_charges;
                        // RSPL Task#98
                        platform_queue[transact_key].custody_charges.total = platform_queue[transact_key].custody_charges.funds_total +
                            platform_queue[transact_key].custody_charges.ex_instruments_total -
                            platform_queue[transact_key].custody_charges.cash_total +
                            platform_queue[transact_key].custody_charges.platform_custody_cash_total
                            + platform_queue[transact_key].product_charges.total;
                        platform_queue[transact_key].dealing_charges.funds_total += $this.transact_total['year_' + year].dealing_charges_funds;
                        platform_queue[transact_key].dealing_charges.ex_instruments_total += $this.transact_total['year_' + year].dealing_charges_ex_instruments;
                        platform_queue[transact_key].dealing_charges.total = platform_queue[transact_key].dealing_charges.funds_total
                            + platform_queue[transact_key].dealing_charges.ex_instruments_total + platform_queue[transact_key].acc_openning_fee.total;
                        // RSPL Task#98
                        platform_queue[transact_key].cost +=
                            +$this.transact_total['year_' + year].product_charges
                            + $this.transact_total['year_' + year].funds_total
                            - $this.transact_total['year_' + year].cash_total
                            + $this.transact_total['year_' + year].platform_custody_cash_total
                            + $this.transact_total['year_' + year].ex_instruments
                            + $this.transact_total['year_' + year].dealing_charges_funds
                            + $this.transact_total['year_' + year].dealing_charges_ex_instruments;
                        return true;
                    } else {
                        return false;
                    }

                });
            }


            let queue = {};


            if (_.indexOf(this.sort_keys, this.order_by) < 0) {
                this.order_by = 'cost';
            }
            queue = this.sort_queue(platform_queue, this.order_by, this.order);
            return queue;
        } catch (err) {

            throw err;
        }
    }

    sort_queue(platform_queue, order_by = 'cost', order = constants.SORT_ASC) {
        if (order_by === 'cost') {
            /*Begin: #346*/
            if (this.user_data.investment_products_simplified) {
                order_by = 'simplified_total';
            }
            /*End: #346*/
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
            'lifetime_isa': 0,
            total: 0
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

        let products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
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
            'lifetime_isa': 0
        };
        temp_product_charges.funds = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };
        temp_product_charges.ex_instruments = {
            'gia': 0,
            'isa': 0,
            'jisa': 0,
            'sipp': 0,
            'jsipp': 0,
            'onshore_bond': 0,
            'offshore_bond': 0,
            'lifetime_isa': 0
        };

        _.each(products, function (product) {
            if (year === 1 || user_data['investments_today'] === 'in_x_years') {
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