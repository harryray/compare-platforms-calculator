module.exports.INV_TYPE_FUND = 1;
module.exports.INV_TYPE_EX_TRADED = 2;
//RSPL TASK#21
module.exports.INV_TYPE_CASH = 4;
module.exports.INV_TYPE_BOTH = 3;
module.exports.RECOMENDED_YES = 1;
module.exports.RECOMENDED_NO = 0;
module.exports.PLATFORM_ADVISED = 1;
module.exports.PLATFORM_D2C = 2;
module.exports.TIER_TYPE_AD_VALORAM = 1;
module.exports.TIER_TYPE_FLAT_RATE = 2;
module.exports.TIER_TYPE_PER_INVESTMENT = 3;
module.exports.TIER_TYPE_PER_TRANSACTION = 4;
module.exports.TIER_TYPE_PER_OPENNING = 5;

module.exports.SORT_COST = 'cost';
module.exports.SORT_NAME = 'name';
module.exports.SORT_RATING = 'rating';
module.exports.SORT_REC = 'recommended';
module.exports.SORT_ASC = 'asc';
module.exports.SORT_DESC = 'desc';
module.exports.STATUS_APPROVEd = 1;
module.exports.STATUS_REJECTED = 0;
module.exports.STATUS_PENDING = -1;
module.exports.STATUS_PENDING_APPROVED = 2;
module.exports.ERR_CODE_SCHEMA_STR = 'ERR_CODE_INVALID_SCHEMA';
module.exports.ERR_CODE_TOTAL_FUNDS_STR = 'ERR_CODE_TOTAL_INVESTMENTS';
module.exports.ERR_CODE_TOTAL_EX_STR = 'ERR_CODE_TOTAL_EX';
module.exports.ERR_CODE_REQUIRED_STR = 'ERR_CODE_REQUIRED';
module.exports.ERR_CODE_SERVER_ERROR_STR = 'ERR_CODE_INTERNAL_ERROR';
module.exports.ERR_CODE_SCHEMA = '1001';
module.exports.ERR_CODE_TOTAL_INVESTMENTS = '1002';
module.exports.ERR_CODE_TOTAL_EX = '1003';
module.exports.ERR_CODE_REQUIRED = '1004';
module.exports.ERR_CODE_SERVER_ERROR = '500';
module.exports.CTP_URL = 'https://comparetheplatform.com/';
module.exports.PRODUCT_isa = 'isa';
module.exports.FEE_TYPE_CUSTODY = 1;
module.exports.FEE_TYPE_SIPP = 7;
module.exports.FEE_TYPE_TRANSFER_OUT = 6;
module.exports.FEE_TYPE_PRODUCT_CHARGES = 2;
module.exports.FEE_TYPE_INVESTMENT_CHARGES = 3;
module.exports.FEE_TYPE_DEALING = 4;
module.exports.FEE_TYPE_ACC_OPENNING = 5;
module.exports.FEE_TYPE_INCENTIVE = 8;

module.exports.user_keys_defaults = {
    total_shares: 0,
    funds: 0,
    over_years: 0,
    funds_isa: 0,
    funds_jisa: 0,
    funds_sipp: 0,
    funds_jsipp: 0,
    funds_gia: 0,
    funds_offshore_bond: 0,
    funds_onshore_bond: 0,
    funds_isa_cash: 0,
    funds_jisa_cash: 0,
    funds_sipp_cash: 0,
    funds_jsipp_cash: 0,
    funds_gia_cash: 0,
    funds_offshore_bond_cash: 0,
    funds_onshore_bond_cash: 0,
    point_future: 0,
    investment_products: 0,
    know_investments: 0,
    investment_stocks_shares: "no",
    investment_frequency: 0,
    investment_frequency_funds: 0,
    investment_frequency_ex_traded: 0,
    average_investment_funds: 0,
    average_investment_ex_traded: 0,
    avg_trade_funds: 0,
    avg_trade_ex: 0,
    platform_type: 1,
    investments_today: 'today',
    planning_isa: 0,
    planning_jisa: 0,
    planning_sipp: 0,
    planning_jsipp: 0,
    planning_offshore_bond: 0,
    planning_onshore_bond: 0,
    planning_gia: 0,
    planning_ex_isa: 0,
    planning_ex_jisa: 0,
    planning_ex_sipp: 0,
    planning_ex_jsipp: 0,
    planning_ex_offshore_bond: 0,
    planning_ex_onshore_bond: 0,
    planning_ex_gia: 0,
    ex_isa: 0,
    ex_jisa: 0,
    ex_gia: 0,
    ex_sipp: 0,
    ex_jsipp: 0,
    ex_instruments_isa: 0,
    ex_instruments_jisa: 0,
    ex_instruments_gia: 0,
    ex_instruments_sipp: 0,
    ex_instruments_offshore_bond: 0,
    ex_instruments_onshore_bond: 0,
    ex_instruments_jsipp: 0,
    planning_ex_instruments_isa: 0,
    planning_ex_instruments_jisa: 0,
    planning_ex_instruments_gia: 0,
    planning_ex_instruments_sipp: 0,
    planning_ex_instruments_onshore_bond: 0,
    planning_ex_instruments_jsipp: 0,
    planning_ex_instruments_offshore_bond: 0,
    //RSPL TASK#22
    planning_cash_isa: 0,
    planning_cash_jisa: 0,
    planning_cash_gia: 0,
    planning_cash_sipp: 0,
    planning_cash_onshore_bond: 0,
    planning_cash_jsipp: 0,
    planning_cash_offshore_bond: 0,
    investments_over: 1,
    investments_in_x_years: 1,
    ex_instruments_calculated: false,
    platinum_vendor: 0,
    yearly_trades_ex: 0,
    yearly_trades_funds: 0,
    annual_advice_type: '',
    annual_adviser_charges: 0,
    total_yearly_investment_total: 0,
    initial_advice_type: '',
    initial_adviser_charges: 0,
    // ticket 307
    funds_lifetime_isa: 0,
    ethical_investment: 0,
    funds_lifetime_isa_cash: 0,
    ex_lifetime_isa: 0,
    ex_instruments_lifetime_isa: 0,
    planning_cash_lifetime_isa: 0,
    planning_ex_lifetime_isa:0,
    planning_lifetime_isa: 0,
    planning_ex_instruments_lifetime_isa: 0,
};
module.exports.platform_defaults = {
    published: 1,
    rating: 3,
    info_url: '',
    url: '',
    recommended: 0,
    img: '',
    sandbox: 0

};

_ = require('underscore-node');
module.exports.calc_num = function (num) {
    return (_.isNumber(num) && num > 0);
};
module.exports.round = function (num) {
    if (_.isNumber(num)) {
        num = Math.round((num + 0.00001) * 100) / 100
    } else {
        num = 0;
    }
    return num;
};

module.exports.isPlatformCapped = function (platformInfoUrl) {
    if (!platformInfoUrl) {
        return false;
    }
    const platforms = ['interactive-investor-investor', 'interactive-investor-funds-fan', 'interactive-investor-super-investor', 'barclays-smart-investor', 'charles-stanley', 'hubwise', 'raymond-james-tiered-bundled', 'ascentric'];
    if (platforms.includes(platformInfoUrl)) {
        return true;
    }
    return false;
}

module.exports.generateCappedPerClientCharges = function (userData, totalCustody) {

    let totalFunds = parseFloat(userData['funds_gia']);
    totalFunds += parseFloat(userData['funds_isa']);
    totalFunds += parseFloat(userData['funds_jisa']);
    totalFunds += parseFloat(userData['funds_sipp']);
    totalFunds += parseFloat(userData['funds_jsipp']);
    totalFunds += parseFloat(userData['funds_onshore_bond']);
    totalFunds += parseFloat(userData['funds_offshore_bond']);
    totalFunds += parseFloat(userData['funds_lifetime_isa']);

    let totalETI = parseFloat(userData['ex_instruments_gia']);
    totalETI += parseFloat(userData['ex_instruments_isa']);
    totalETI += parseFloat(userData['ex_instruments_jisa']);
    totalETI += parseFloat(userData['ex_instruments_sipp']);
    totalETI += parseFloat(userData['ex_instruments_jsipp']);
    totalETI += parseFloat(userData['ex_instruments_onshore_bond']);
    totalETI += parseFloat(userData['ex_instruments_offshore_bond']);
    totalETI += parseFloat(userData['ex_instruments_lifetime_isa']);

    let totalCash = parseFloat(userData['funds_gia_cash']);
    totalCash += parseFloat(userData['funds_isa_cash']);
    totalCash += parseFloat(userData['funds_jisa_cash']);
    totalCash += parseFloat(userData['funds_sipp_cash']);
    totalCash += parseFloat(userData['funds_jsipp_cash']);
    totalCash += parseFloat(userData['funds_onshore_bond_cash']);
    totalCash += parseFloat(userData['funds_offshore_bond_cash']);
    totalCash += parseFloat(userData['funds_lifetime_isa_cash']);

    const totalAll = totalFunds + totalETI + totalCash;
    let isCash = false;
    let isFund = false;
    let isETI = false;
    if (totalCash > 0) {
        isCash = true;
    } else if (totalFunds > 0) {
        isFund = true;
    } else if (totalETI > 0) {
        isETI = true;
    }

    const productRatio = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };
    const products = ['gia', 'isa', 'jisa', 'sipp', 'jsipp', 'onshore_bond', 'offshore_bond', 'lifetime_isa'];
    const cappedCustody = { 'gia': 0, 'isa': 0, 'jisa': 0, 'sipp': 0, 'jsipp': 0, 'onshore_bond': 0, 'offshore_bond': 0, 'lifetime_isa': 0 };

    _.each(products, function (product) {
        if (isCash) {
            productRatio[product] = parseFloat(userData['funds_' + product + '_cash'] / totalCash);
        } else if (isFund) {
            productRatio[product] = parseFloat(userData['funds_' + product] / totalFunds);
        } else {
            productRatio[product] = parseFloat(userData['ex_instruments_' + product] / totalETI);
        }

        if (productRatio[product] > 0) {
            cappedCustody[product] = totalCustody * productRatio[product];
        } else {
            cappedCustody[product] = 0;
        }
    });
    return {
        isCash: isCash,
        isFund: isFund,
        isETI: isETI,
        custody: cappedCustody
    };
}