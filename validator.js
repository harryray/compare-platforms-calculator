let promise = require('promise');
let _ = require('underscore-node');
let constants = require('./includes/const');
var fn = require('./includes/functions');
var conn = require('./model');
let err = require('./includes/class-calculator-error');
module.exports.validateUserSchema = function (user) {
    return new promise(function (resolve, reject) {
        let user_defaults = {};
        let ctp_err = {};

        if (_.isUndefined(user.total_savings_and_investments) || _.isUndefined(user.age) || _.isUndefined(user.gender)) {
            let ctp_err = new err.Calculator_Error(constants.ERR_CODE_REQUIRED);
            ctp_err.get_error_messages();
            return reject(ctp_err);
        }
        user.total_savings_and_investments = isString(user.total_savings_and_investments) ? user.total_savings_and_investments.replace(/[^\d\.]/g, '') : user.total_savings_and_investments;
        if (isNaN(user.total_savings_and_investments)) {
            let ctp_err = new err.Calculator_Error(constants.ERR_CODE_SCHEMA);
            ctp_err.get_error_messages();
            return reject(ctp_err);
        }
        user.total_savings_and_investments = parseFloat(user.total_savings_and_investments);
        if (!_.isUndefined(user.funds_isa)) {
            user.funds_isa = isString(user.funds_isa) ? (user.funds_isa.replace(/[^\d\.]/g, '')) : (_.isNull(user.funds_isa) ? 0 : user.funds_isa);
            ctp_err = validate_funds(user.funds_isa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_isa = parseFloat(user.funds_isa);
        }
        if (!_.isUndefined(user.funds_jisa)) {
            user.funds_jisa = isString(user.funds_jisa) ? (user.funds_jisa.replace(/[^\d\.]/g, '')) : (_.isNull(user.funds_jisa) ? 0 : user.funds_jisa);
            ctp_err = validate_funds(user.funds_jisa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_jisa = parseFloat(user.funds_jisa);
        }
        if (!_.isUndefined(user.funds_gia)) {
            user.funds_gia = isString(user.funds_gia) ? user.funds_gia.replace(/[^\d\.]/g, '') : (_.isNull(user.funds_gia) ? 0 : user.funds_gia);
            ctp_err = validate_funds(user.funds_gia);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_gia = parseFloat(user.funds_gia);
        }
        if (!_.isUndefined(user.funds_jsipp)) {
            user.funds_jsipp = isString(user.funds_jsipp) ? user.funds_jsipp.replace(/[^\d\.]/g, '') : (_.isNull(user.funds_jsipp) ? 0 : user.funds_jsipp);
            ctp_err = validate_funds(user.funds_jsipp);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_jsipp = parseFloat(user.funds_jsipp);
        }
        if (!_.isUndefined(user.funds_sipp)) {
            user.funds_sipp = isString(user.funds_sipp) ? user.funds_sipp.replace(/[^\d\.]/g, '') : (_.isNull(user.funds_sipp) ? 0 : user.funds_sipp);
            ctp_err = validate_funds(user.funds_sipp);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_sipp = parseFloat(user.funds_sipp);
        }
        if (!_.isUndefined(user.funds_onshore_bond)) {
            user.funds_onshore_bond = isString(user.funds_onshore_bond) ? user.funds_onshore_bond.replace(/[^\d\.]/g, '') : (_.isNull(user.funds_onshore_bond) ? 0 : user.funds_onshore_bond);
            ctp_err = validate_funds(user.funds_onshore_bond);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_onshore_bond = parseFloat(user.funds_onshore_bond);
        }
        if (!_.isUndefined(user.funds_offshore_bond)) {
            user.funds_offshore_bond = isString(user.funds_offshore_bond) ? user.funds_offshore_bond.replace(/[^\d\.]/g, '') : (_.isNull(user.funds_offshore_bond) ? 0 : user.funds_offshore_bond);
            ctp_err = validate_funds(user.funds_offshore_bond);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_offshore_bond = parseFloat(user.funds_offshore_bond);
        }
        // ticket 307 changes
        if (!_.isUndefined(user.funds_lifetime_isa)) {
            user.funds_lifetime_isa = isString(user.funds_lifetime_isa) ? (user.funds_lifetime_isa.replace(/[^\d\.]/g, '')) : (_.isNull(user.funds_lifetime_isa) ? 0 : user.funds_lifetime_isa);
            ctp_err = validate_funds(user.funds_lifetime_isa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.funds_lifetime_isa = parseFloat(user.funds_lifetime_isa);
        }

        if (!_.isUndefined(user.ex_instruments_jsipp)) {

            user.ex_instruments_jsipp = isString(user.ex_instruments_jsipp) ? user.ex_instruments_jsipp.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_jsipp) ? 0 : user.ex_instruments_jsipp);
            ctp_err = validate_funds(user.ex_instruments_jsipp);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_jsipp = parseFloat(user.ex_instruments_jsipp);
        }
        if (!_.isUndefined(user.ex_instruments_isa)) {
            user.ex_instruments_isa = isString(user.ex_instruments_isa) ? user.ex_instruments_isa.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_isa) ? 0 : user.ex_instruments_isa);
            ctp_err = validate_funds(user.ex_instruments_isa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_isa = parseFloat(user.ex_instruments_isa);
        }
        if (!_.isUndefined(user.ex_instruments_jisa)) {
            user.ex_instruments_jisa = isString(user.ex_instruments_jisa) ? user.ex_instruments_jisa.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_jisa) ? 0 : user.ex_instruments_jisa);
            ctp_err = validate_funds(user.ex_instruments_jisa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_jisa = parseFloat(user.ex_instruments_jisa);
        }
        if (!_.isUndefined(user.ex_instruments_gia)) {
            user.ex_instruments_gia = isString(user.ex_instruments_gia) ? user.ex_instruments_gia.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_gia) ? 0 : user.ex_instruments_gia);
            ctp_err = validate_funds(user.ex_instruments_gia);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_gia = parseFloat(user.ex_instruments_gia);
        }
        if (!_.isUndefined(user.ex_instruments_sipp)) {
            user.ex_instruments_sipp = isString(user.ex_instruments_sipp) ? user.ex_instruments_sipp.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_sipp) ? 0 : user.ex_instruments_sipp);
            ctp_err = validate_funds(user.ex_instruments_sipp);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_sipp = parseFloat(user.ex_instruments_sipp);
        }
        if (!_.isUndefined(user.ex_instruments_onshore_bond)) {
            user.ex_instruments_onshore_bond = isString(user.ex_instruments_onshore_bond) ? user.ex_instruments_onshore_bond.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_onshore_bond) ? 0 : user.ex_instruments_onshore_bond);
            ctp_err = validate_funds(user.ex_instruments_onshore_bond);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_onshore_bond = parseFloat(user.ex_instruments_onshore_bond);
        }
        if (!_.isUndefined(user.ex_instruments_offshore_bond)) {
            user.ex_instruments_offshore_bond = isString(user.ex_instruments_offshore_bond) ? user.ex_instruments_offshore_bond.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_offshore_bond) ? 0 : user.ex_instruments_offshore_bond);
            ctp_err = validate_funds(user.ex_instruments_offshore_bond);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_offshore_bond = parseFloat(user.ex_instruments_offshore_bond);
        }
        // ticket 307 changes
        if (!_.isUndefined(user.ex_instruments_lifetime_isa)) {
            user.ex_instruments_lifetime_isa = isString(user.ex_instruments_lifetime_isa) ? user.ex_instruments_lifetime_isa.replace(/[^\d\.]/g, '') : (_.isNull(user.ex_instruments_lifetime_isa) ? 0 : user.ex_instruments_lifetime_isa);
            ctp_err = validate_funds(user.ex_instruments_lifetime_isa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.ex_instruments_lifetime_isa = parseFloat(user.ex_instruments_lifetime_isa);
        }
        
        if (!_.isUndefined(user.planning_isa)) {
            user.planning_isa = isString(user.planning_isa) ? user.planning_isa.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_isa) ? 0 : user.planning_isa);
            ctp_err = validate_funds(user.planning_isa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_isa = parseFloat(user.planning_isa);
        }
        if (!_.isUndefined(user.planning_jisa)) {
            user.planning_jisa = isString(user.planning_jisa) ? user.planning_jisa.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_jisa) ? 0 : user.planning_jisa);
            ctp_err = validate_funds(user.planning_jisa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_jisa = parseFloat(user.planning_jisa);
        }
        if (!_.isUndefined(user.planning_gia)) {
            user.planning_gia = isString(user.planning_gia) ? user.planning_gia.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_gia) ? 0 : user.planning_gia);
            ctp_err = validate_funds(user.planning_gia);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_gia = parseFloat(user.planning_gia);
        }
        if (!_.isUndefined(user.planning_sipp)) {
            user.planning_sipp = isString(user.planning_sipp) ? user.planning_sipp.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_sipp) ? 0 : user.planning_sipp);
            ctp_err = validate_funds(user.planning_sipp);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_sipp = parseFloat(user.planning_sipp);
        }
        if (!_.isUndefined(user.planning_jsipp)) {
            user.planning_jsipp = isString(user.planning_jsipp) ? user.planning_jsipp.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_jsipp) ? 0 : user.planning_jsipp);
            ctp_err = validate_funds(user.planning_jsipp);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_jsipp = parseFloat(user.planning_jsipp);
        }
        if (!_.isUndefined(user.planning_onshore_bond)) {
            user.planning_onshore_bond = isString(user.planning_onshore_bond) ? user.planning_onshore_bond.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_onshore_bond) ? 0 : user.planning_onshore_bond);
            ctp_err = validate_funds(user.planning_onshore_bond);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_onshore_bond = parseFloat(user.planning_onshore_bond);
        }
        if (!_.isUndefined(user.planning_offshore_bond)) {
            user.planning_offshore_bond = isString(user.planning_offshore_bond) ? user.planning_offshore_bond.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_offshore_bond) ? 0 : user.planning_offshore_bond);
            ctp_err = validate_funds(user.planning_offshore_bond);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_offshore_bond = parseFloat(user.planning_offshore_bond);
        }
        // ticket 307 changes
        if (!_.isUndefined(user.planning_lifetime_isa)) {
            user.planning_lifetime_isa = isString(user.planning_lifetime_isa) ? user.planning_lifetime_isa.replace(/[^\d\.]/g, '') : (_.isNull(user.planning_lifetime_isa) ? 0 : user.planning_lifetime_isa);
            ctp_err = validate_funds(user.planning_lifetime_isa);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.planning_lifetime_isa = parseFloat(user.planning_lifetime_isa);
        }

        if (!_.isUndefined(user.average_investment_funds)) {
            user.average_investment_funds = isString(user.average_investment_funds) ? user.average_investment_funds.replace(/[^\d\.]/g, '') : user.average_investment_funds;
            ctp_err = validate_funds(user.average_investment_funds);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.average_investment_funds = parseFloat(user.average_investment_funds);
        }
        if (!_.isUndefined(user.average_investment_ex_traded)) {
            user.average_investment_ex_traded = isString(user.average_investment_ex_traded) ? user.average_investment_ex_traded.replace(/[^\d\.]/g, '') : user.average_investment_ex_traded;
            ctp_err = validate_funds(user.average_investment_ex_traded);
            if (ctp_err) {
                return reject(ctp_err);

            }
            user.average_investment_ex_traded = parseFloat(user.average_investment_ex_traded);
        }


        _.defaults(user, constants.user_keys_defaults);
        if (!_.isNumber(user.total_shares)) {
            user.total_shares = parseFloat(user.total_shares)
        }
        if (!_.isNumber(user.total_funds)) {
            user.total_funds = parseFloat(user.total_funds)
        }

//        console.log('HTB CHECK VALUES TOTAL_SAVINGS_AND_INVESTMENTS: ' + constants.calc_num(user.total_savings_and_investments) + ' || TOTAL_SAVINGS_AND_INVESTMENTS_CASH: ' + constants.calc_num(user.total_savings_and_investments_cash));
        if ((user.investment_products === 'no' && constants.calc_num(user.total_savings_and_investments)) || (constants.calc_num(user.total_savings_and_investments) && (
            user.funds_gia === 0 &&
            user.funds_isa === 0 &&
            user.funds_jisa === 0 &&
            user.funds_sipp === 0 &&
            user.funds_jsipp === 0 &&
            user.funds_onshore_bond === 0 &&
            user.funds_offshore_bond === 0 &&
            user.funds_lifetime_isa === 0
        ))) {

            //Split total by 3
            let slice = user.total_savings_and_investments / 3;
            /*Begin: #346*/
            let sipp_slice = slice;
            if (user.investment_products_simplified) {
                slice = user.total_savings_and_investments / 2;
                sipp_slice = 0;
            }
            /*End: #346*/
            user.funds_gia = slice;
            user.funds_isa = slice;
            user.funds_jisa = 0;
            user.funds_sipp = sipp_slice;   //#346
            user.funds_jsipp = 0;
            user.funds_onshore_bond = 0;
            user.funds_offshore_bond = 0;
            user.funds_lifetime_isa = 0;
            user.ex_instruments_gia = 0;
            user.ex_instruments_isa = 0;
            user.ex_instruments_jisa = 0;
            user.ex_instruments_sipp = 0;
            user.ex_instruments_jsipp = 0;
            user.ex_instruments_onshore_bond = 0;
            user.ex_instruments_offshore_bond = 0;
            user.ex_instruments_lifetime_isa = 0;

        }
        //console.log('HTB CASH VALUES: ' + user.total_savings_and_investments_cash);
        if ((user.investment_products === 'no' && user.total_savings_and_investments_cash) || (user.total_savings_and_investments_cash && (
            user.funds_gia_cash === 0 &&
            user.funds_isa_cash === 0 &&
            user.funds_jisa_cash === 0 &&
            user.funds_sipp_cash === 0 &&
            user.funds_jsipp_cash === 0 &&
            user.funds_onshore_bond_cash === 0 &&
            user.funds_offshore_bond_cash === 0 &&
            user.funds_lifetime_isa_cash === 0
        ))) {
            
            //Split total by 3
            let slice_cash = user.total_savings_and_investments_cash / 3;

            user.funds_gia_cash = slice_cash;
            user.funds_isa_cash = slice_cash;
            user.funds_jisa_cash = 0;
            user.funds_sipp_cash = slice_cash;
            user.funds_jsipp_cash = 0;
            user.funds_onshore_bond_cash = 0;
            user.funds_offshore_bond_cash = 0;
            user.funds_lifetime_isa_cash = 0;

        }
        //console.log('HTB CASH VALUES FILLED: ' + user.funds_gia_cash,user.funds_isa_cash,user.funds_jisa_cash,user.funds_sipp_cash,user.funds_jsipp_cash,user.funds_onshore_bond_cash,user.funds_offshore_bond_cash,user.funds_lifetime_isa_cash);
        // user.total_funds = user.funds_isa + user.funds_gia + user.funds_jisa + user.funds_jsipp + user.funds_sipp;

/*


*/

        if (constants.calc_num(user.total_shares) && (
            user.ex_instruments_gia === 0 &&
            user.ex_instruments_isa === 0 &&
            user.ex_instruments_jisa === 0 &&
            user.ex_instruments_sipp === 0 &&
            user.ex_instruments_jsipp === 0 &&
            user.ex_instruments_onshore_bond === 0 &&
            user.ex_instruments_offshore_bond === 0 &&
            user.ex_instruments_lifetime_isa === 0 
        )) {

            user.isa_ratio = user.funds_isa / user.total_savings_and_investments;
            user.jisa_ratio = user.funds_jisa / user.total_savings_and_investments;
            user.sipp_ratio = user.funds_sipp / user.total_savings_and_investments;
            user.jsipp_ratio = user.funds_jsipp / user.total_savings_and_investments;
            user.gia_ratio = user.funds_gia / user.total_savings_and_investments;
            user.onshore_bond_ratio = user.funds_onshore_bond / user.total_savings_and_investments;
            user.offshore_bond_ratio = user.funds_offshore_bond / user.total_savings_and_investments;
            user.lifetime_isa_ratio = user.funds_lifetime_isa / user.total_savings_and_investments;


            user.ex_instruments_gia = user.total_shares * user.gia_ratio;
            user.ex_instruments_isa = user.total_shares * user.isa_ratio;
            user.ex_instruments_jisa = user.total_shares * user.jisa_ratio;
            user.ex_instruments_sipp = user.total_shares * user.sipp_ratio;
            user.ex_instruments_jsipp = user.total_shares * user.jsipp_ratio;
            user.ex_instruments_onshore_bond = user.total_shares * user.onshore_bond_ratio;
            user.ex_instruments_onshore_bond = user.total_shares * user.offshore_bond_ratio;
            user.ex_instruments_lifetime_isa = user.total_shares * user.lifetime_isa_ratio;
            user.ex_instrument_calculated = true;


        }

        if (user.investment_products === 'yes' && user.investment_stocks_shares == 'no') {
            user.total_shares = 0;
        } else {
            user.funds_gia = user.funds_gia - user.ex_instruments_gia;
            user.funds_isa = user.funds_isa - user.ex_instruments_isa;
            user.funds_jisa = user.funds_jisa - user.ex_instruments_jisa;
            user.funds_sipp = user.funds_sipp - user.ex_instruments_sipp;
            user.funds_jsipp = user.funds_jsipp - user.ex_instruments_jsipp;
            user.funds_onshore_bond = user.funds_onshore_bond - user.ex_instruments_onshore_bond;
            user.funds_offshore_bond = user.funds_offshore_bond - user.ex_instruments_offshore_bond;
            user.funds_lifetime_isa = user.funds_lifetime_isa - user.ex_instruments_lifetime_isa;

            user.total_shares = user.ex_instruments_isa + user.ex_instruments_gia
                + user.ex_instruments_jsipp + user.ex_instruments_jisa + user.ex_instruments_sipp
                 + user.ex_instruments_onshore_bond + user.ex_instruments_offshore_bond 
                 + user.ex_instruments_lifetime_isa;
        }
        user.total_funds = parseFloat(user.total_savings_and_investments) - parseFloat(user.total_shares);
        user.total_cash = user.total_savings_and_investments_cash;


        if(user.total_cash == "undefined" || user.total_cash === undefined) {
            user.total_cash = 0
        }
        user.total_all = parseFloat(user.total_funds) + parseFloat(user.total_shares) + parseFloat(user.total_cash);
        
        //console.log(user.total_funds);
        //console.log(user.total_shares);
        //console.log(user.total_cash);
        //console.log(user.total_savings_and_investments);
        //console.log(user.total_all);
        //console.log(user);

        // if (user.total_all !== (user.total_savings_and_investments + user.total_funds + user.total_shares + user.total_cash)) {
        // //if (parseFloat(user.total_all) !== parseFloat(user.total_savings_and_investments_total) ) {
        //     let ctp_err = new err.Calculator_Error(constants.ERR_CODE_TOTAL_INVESTMENTS);
        //     ctp_err.get_error_messages();
        //     return reject(ctp_err);
        // }
        if (user.total_shares > user.total_all) {
            let ctp_err = new err.Calculator_Error(constants.ERR_CODE_TOTAL_EX);
            ctp_err.get_error_messages();
            return reject(ctp_err);
        }
        resolve(user);
    });

};

function validate_funds(val) {
    if (!_.isNull(val) && isNaN(val)) {
        let ctp_err = new err.Calculator_Error(constants.ERR_CODE_SCHEMA);
        ctp_err.get_error_messages();
        return ctp_err;

    }


}

module.exports.saveRequest = function (req) {
    return new promise(async function (resolve, reject) {
        try {
            let user_id = req.requestContext.authorizer.principalId;
            let user = JSON.parse(req.body);
            let data = {};
            data.request = user;
            data.user_id = user_id;
            let id = await fn.save_request(data);
            user.request_id = id;
            user.user_id = user_id;
            resolve(user);
        } catch (e) {
            console.error(e);
            reject(e.message);
        }
    });
};
module.exports.validatePlatform = function (platform_id, req) {
    return new promise(async function (resolve, reject) {
        try {
            let sql = 'select 1 from platforms where platform_id = ' + platform_id;
            let result = await conn.query(sql);
            let data = {};

            if (result) {
                if (!_.isNull(req)) {
                    let platform = JSON.parse(req);

                    data.platform_data = platform;
                    data.user_id = platform.user_id;
                }
                data.platform_id = platform_id;

            }
            return resolve(data);
        } catch (e) {

            console.error(e.message);
            return reject(e.message);
        }
    });
};

function isString(s) {
    return typeof (s) === 'string' || s instanceof String;
}