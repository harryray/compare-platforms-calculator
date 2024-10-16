'use strict';
let conn = require('../model.js');
let promise = require('promise');
let constant = require('./const');
let _ = require('underscore-node');

//RSPL TASK#22

let cust_charge_defaults = {
    total: 0,
    fund: 0,
    cash: 0,
    ex: 0,
    show: 0,
    gia: 0,
    isa: 0,
    sipp: 0,
    jisa: 0,
    jsipp: 0,
    fund_gia: 0,
    fund_isa: 0,
    fund_jisa: 0,
    fund_sipp: 0,
    fund_jsipp: 0,
    fund_gia_cash: 0,
    fund_isa_cash: 0,
    fund_jisa_cash: 0,
    fund_sipp_cash: 0,
    fund_jsipp_cash: 0,
    fund_gia_total: 0,
    fund_isa_total: 0,
    fund_jisa_total: 0,
    fund_sipp_total: 0,
    fund_jsipp_total: 0,


    ex_gia: 0,
    ex_isa: 0,
    ex_jisa: 0,
    ex_sipp: 0,
    ex_jsipp: 0,
    ex_gia_cash: 0,
    ex_isa_cash: 0,
    ex_jisa_cash: 0,
    ex_sipp_cash: 0,
    ex_jsipp_cash: 0,
    ex_gia_total: 0,
    ex_isa_total: 0,
    ex_jisa_total: 0,
    ex_sipp_total: 0,
    ex_jsipp_total: 0

};

let charge_defaults = {
    aua_from: 0,
    aua_to: 0,
    inv_type: 0,
    calc_type: 0,
    vat: 0

};

// Ticket 307 => changes will reflect over here
module.exports.platform = function (user) {
    let $this = this;
    return new promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(async function (err, connection) {

                if (err) {
                    return reject(err);
                }


                let sql_values = '';
                // Ticket 307
                if (user.ex_instruments_isa) {
                    sql_values = sql_values + "sup_ex_isa = 1 AND ";
                }
                if (user.ex_instruments_jisa) {
                    sql_values = sql_values + "sup_ex_jisa = 1 AND ";
                }
                if (user.ex_instruments_sipp) {
                    sql_values = sql_values + "sup_ex_sipp = 1 AND ";
                }
                if (user.ex_instruments_jsipp) {
                    sql_values = sql_values + "sup_ex_jsipp = 1 AND ";
                }
                if (user.ex_instruments_gia) {
                    sql_values = sql_values + "sup_ex_gia = 1 AND ";
                }
                if (user.ex_instruments_onshore_bond) {
                    sql_values = sql_values + "sup_ex_onshore_bond = 1 AND ";
                }
                if (user.ex_instruments_offshore_bond) {
                    sql_values = sql_values + "sup_ex_onshore_bond = 1 AND ";
                }
                // 307 changes
                if (user.ex_instruments_lifetime_isa) {
                    sql_values = sql_values + "sup_ex_lifetime_isa = 1 AND ";
                }

                // Recent additions to fix investment vs cash discrepancy on what gets excluded 
                // ("exclusion criteria" task) - HR

                if (user.funds_isa || user.funds_isa_cash) {
                    sql_values = sql_values + "sup_fund_isa = 1 AND ";
                }
                if (user.funds_jisa || user.funds_jisa_cash) {
                    sql_values = sql_values + "sup_fund_jisa = 1 AND ";
                }
                if (user.funds_sipp || user.funds_sipp_cash) {
                    sql_values = sql_values + "sup_fund_sipp = 1 AND ";
                }
                if (user.funds_jsipp || user.funds_jsipp_cash) {
                    sql_values = sql_values + "sup_fund_jsipp = 1 AND ";
                }
                if (user.funds_gia || user.funds_gia_cash) {
                    sql_values = sql_values + "sup_fund_gia = 1 AND ";
                }
                if (user.funds_offshore_bond || user.funds_offshore_bond_cash) {
                    sql_values = sql_values + "sup_fund_offshore_bond = 1 AND ";
                }
                if (user.funds_onshore_bond || user.funds_onshore_bond_cash) {
                    sql_values = sql_values + "sup_fund_onshore_bond = 1 AND ";
                }
                // 307 changes
                if (user.funds_lifetime_isa || user.funds_lifetime_isa_cash) {
                    sql_values = sql_values + "sup_fund_lifetime_isa = 1 AND ";
                }
                if (user.ethical_investment && user.ethical_investment == '1'  ) {
                    sql_values = sql_values + "ethical_investment = 1 AND ";
                }
                
                let token = '';
                /*let sql = "SELECT d.platform_id, p.platform_name, d.id, d.version, p.platform_type, p.calculation_method," +
                    " p.recommended, p.rating, p.info_url, p.url, p.img, d.active_from, d.active_to, d.all_cust_fee_min, d.all_cust_fee_max, " +
                    "d.fund_cust_fee_min, d.fund_cust_fee_max, d.ex_cust_fee_min, d.ex_cust_fee_max, d.ann_admin_fee_min, d.ann_admin_fee_max, d.ann_admin_amount, d.dealing_fee_credits FROM " +
                    "platforms p, platforms_data d WHERE p.platform_id = d.platform_id AND " + sql_values + " platform_type = " + user.platform_type +
                    " AND d.rec_status = 1 ORDER BY d.platform_id ASC, d.version, p.recommended,p.rating DESC ;";*/

                //RSPL Task#37 - Added cash_cust_fee_min and cash_cust_fee_max in SELECT query so that they can we use in calculation

                /*let sql_stmt = "SELECT d.platform_id, if(p.sandbox = 1, AES_DECRYPT(p.platform_name, ?),p.platform_name) platform_name, d.id, d.version, p.platform_type, p.calculation_method, " +
                    "p.recommended, p.rating, p.info_url, p.url, p.img, d.active_from, d.active_to, d.all_cust_fee_min, d.all_cust_fee_max, d.fund_cust_fee_min," +
                    " d.fund_cust_fee_max, d.ex_cust_fee_min, d.ex_cust_fee_max, d.ann_admin_fee_min, d.ann_admin_fee_max, d.ann_admin_amount, d.dealing_fee_credits, p.sandbox" +
                    " FROM platforms p, platforms_data d WHERE p.platform_id = d.platform_id AND " + sql_values + " platform_type = " + user.platform_type + " AND d.rec_status = 1 and p.published = 1";*/
                let sql_stmt = "SELECT d.platform_id, if(p.sandbox = 1, AES_DECRYPT(p.platform_name, ?),p.platform_name) platform_name, d.id, d.version, p.platform_type, p.calculation_method, " +
                    "p.recommended, p.rating, p.info_url, p.url, p.img, d.active_from, d.active_to, d.all_cust_fee_min, d.all_cust_fee_max, d.cash_cust_fee_min, d.cash_cust_fee_max, d.fund_cust_fee_min," +
                    " d.fund_cust_fee_max, d.ex_cust_fee_min, d.ex_cust_fee_max, d.ann_admin_fee_min, d.ann_admin_fee_max, d.ann_admin_amount, d.dealing_fee_credits, p.sandbox" +
                    " FROM platforms p, platforms_data d WHERE p.platform_id = d.platform_id AND " + sql_values + " platform_type = " + user.platform_type + " AND d.rec_status = 1 and p.published = 1";
                console.log('HR ---------->');
                console.log(sql_stmt);
                console.log('HR <----------');
                console.log(user.funds_isa,user.funds_jisa,user.funds_sipp,user.funds_jsipp,user.funds_gia,user.funds_offshore_bond,user.funds_onshore_bond,user.funds_lifetime_isa);
                console.log(user.funds_isa_cash,user.funds_jisa_cash,user.funds_sipp_cash,user.funds_jsipp_cash,user.funds_gia_cash,user.funds_offshore_bond_cash,user.funds_onshore_bond_cash,user.funds_lifetime_isa_cash);
                console.log('HR |||||||||||');

                let sandbox_data_found = false;
                if (user.platinum_vendor == 1 && $this.isset(user.platinum_user_id)) {
                    let pid = user.pid;
                    let sql = "select token, sandbox_id from sandbox_users where user_id = " + user.platinum_user_id + " and platform_id =" + pid;
                    if (_.isNull(user.pid)) {
                        sql = "select token, sandbox_id from sandbox_users where user_id = " + user.platinum_user_id;
                    }


                    let sandbox_data = await conn.query(sql, false);

                    if (!_.isEmpty(sandbox_data)) {
                        token = sandbox_data[0].token;

                        let sandbox_ids = _.pluck(sandbox_data, 'sandbox_id');

                        if (!_.isNull(sandbox_ids)) {
                            sandbox_data_found = true;

                            sql_stmt += " AND ((p.id = d.parent_id and p.id in(" + sandbox_ids.join() + ")) or  (d.parent_id is null and sandbox = 0 )) ";
                        }
                    }
                }
                if (!sandbox_data_found) {
                    sql_stmt += " and sandbox = 0 and (d.parent_id is null or p.id = d.parent_id) ";
                }
                sql_stmt += " ORDER BY platform_name asc, d.platform_id ASC, d.version, p.recommended,p.rating DESC ";


                connection.query(sql_stmt, token, function (err, rows) {

                    connection.release();

                    if (err) {
                        return reject(err);
                    }

                    /** @param [] rows */
                        //var row = rows.shift();
                    let result = _.isEmpty(rows)
                        ? {}
                        : rows;

                    return resolve(result);
                });
            });
        });
    })
};


module.exports.platform_ETF_ONLY = function (user) {
    let $this = this;
    return new promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(async function (err, connection) {

                if (err) {
                    return reject(err);
                }


                let sql_values = '';
                // Ticket 307
                if (user.ex_instruments_isa) {
                    sql_values = sql_values + "sup_ex_isa = 1 AND ";
                }
                if (user.ex_instruments_jisa) {
                    sql_values = sql_values + "sup_ex_jisa = 1 AND ";
                }
                if (user.ex_instruments_sipp) {
                    sql_values = sql_values + "sup_ex_sipp = 1 AND ";
                }
                if (user.ex_instruments_jsipp) {
                    sql_values = sql_values + "sup_ex_jsipp = 1 AND ";
                }
                if (user.ex_instruments_gia) {
                    sql_values = sql_values + "sup_ex_gia = 1 AND ";
                }
                if (user.ex_instruments_onshore_bond) {
                    sql_values = sql_values + "sup_ex_onshore_bond = 1 AND ";
                }
                if (user.ex_instruments_offshore_bond) {
                    sql_values = sql_values + "sup_ex_onshore_bond = 1 AND ";
                }
                // 307 changes
                if (user.ex_instruments_lifetime_isa) {
                    sql_values = sql_values + "sup_ex_lifetime_isa = 1 AND ";
                }

                // Recent additions to fix investment vs cash discrepancy on what gets excluded 
                // ("exclusion criteria" task) - HR

                sql_values = sql_values + "sup_fund_isa = 0 AND sup_fund_jisa = 0 AND sup_fund_sipp = 0 AND sup_fund_jsipp = 0 AND sup_fund_gia = 0 AND sup_fund_offshore_bond = 0 AND sup_fund_onshore_bond = 0 AND sup_fund_lifetime_isa = 0 AND ";
                
                if (user.ethical_investment && user.ethical_investment == '1'  ) {
                    sql_values = sql_values + "ethical_investment = 1 AND ";
                }
                
                let token = '';
                /*let sql = "SELECT d.platform_id, p.platform_name, d.id, d.version, p.platform_type, p.calculation_method," +
                    " p.recommended, p.rating, p.info_url, p.url, p.img, d.active_from, d.active_to, d.all_cust_fee_min, d.all_cust_fee_max, " +
                    "d.fund_cust_fee_min, d.fund_cust_fee_max, d.ex_cust_fee_min, d.ex_cust_fee_max, d.ann_admin_fee_min, d.ann_admin_fee_max, d.ann_admin_amount, d.dealing_fee_credits FROM " +
                    "platforms p, platforms_data d WHERE p.platform_id = d.platform_id AND " + sql_values + " platform_type = " + user.platform_type +
                    " AND d.rec_status = 1 ORDER BY d.platform_id ASC, d.version, p.recommended,p.rating DESC ;";*/

                //RSPL Task#37 - Added cash_cust_fee_min and cash_cust_fee_max in SELECT query so that they can we use in calculation

                /*let sql_stmt = "SELECT d.platform_id, if(p.sandbox = 1, AES_DECRYPT(p.platform_name, ?),p.platform_name) platform_name, d.id, d.version, p.platform_type, p.calculation_method, " +
                    "p.recommended, p.rating, p.info_url, p.url, p.img, d.active_from, d.active_to, d.all_cust_fee_min, d.all_cust_fee_max, d.fund_cust_fee_min," +
                    " d.fund_cust_fee_max, d.ex_cust_fee_min, d.ex_cust_fee_max, d.ann_admin_fee_min, d.ann_admin_fee_max, d.ann_admin_amount, d.dealing_fee_credits, p.sandbox" +
                    " FROM platforms p, platforms_data d WHERE p.platform_id = d.platform_id AND " + sql_values + " platform_type = " + user.platform_type + " AND d.rec_status = 1 and p.published = 1";*/
                let sql_stmt = "SELECT d.platform_id, if(p.sandbox = 1, AES_DECRYPT(p.platform_name, ?),p.platform_name) platform_name, d.id, d.version, p.platform_type, p.calculation_method, " +
                    "p.recommended, p.rating, p.info_url, p.url, p.img, d.active_from, d.active_to, d.all_cust_fee_min, d.all_cust_fee_max, d.cash_cust_fee_min, d.cash_cust_fee_max, d.fund_cust_fee_min," +
                    " d.fund_cust_fee_max, d.ex_cust_fee_min, d.ex_cust_fee_max, d.ann_admin_fee_min, d.ann_admin_fee_max, d.ann_admin_amount, d.dealing_fee_credits, p.sandbox" +
                    " FROM platforms p, platforms_data d WHERE p.platform_id = d.platform_id AND " + sql_values + " platform_type = " + user.platform_type + " AND d.rec_status = 1 and p.published = 1";
                // console.log('HTB SQL STATEMENT FROM ETF ---------->');
                // console.log(sql_stmt);
                // console.log('HR <----------');
                //console.log(user.funds_isa,user.funds_jisa,user.funds_sipp,user.funds_jsipp,user.funds_gia,user.funds_offshore_bond,user.funds_onshore_bond,user.funds_lifetime_isa);
                //console.log(user.funds_isa_cash,user.funds_jisa_cash,user.funds_sipp_cash,user.funds_jsipp_cash,user.funds_gia_cash,user.funds_offshore_bond_cash,user.funds_onshore_bond_cash,user.funds_lifetime_isa_cash);
                //console.log('HR |||||||||||');

                let sandbox_data_found = false;
                if (user.platinum_vendor == 1 && $this.isset(user.platinum_user_id)) {
                    let pid = user.pid;
                    let sql = "select token, sandbox_id from sandbox_users where user_id = " + user.platinum_user_id + " and platform_id =" + pid;
                    if (_.isNull(user.pid)) {
                        sql = "select token, sandbox_id from sandbox_users where user_id = " + user.platinum_user_id;
                    }


                    let sandbox_data = await conn.query(sql, false);

                    if (!_.isEmpty(sandbox_data)) {
                        token = sandbox_data[0].token;

                        let sandbox_ids = _.pluck(sandbox_data, 'sandbox_id');

                        if (!_.isNull(sandbox_ids)) {
                            sandbox_data_found = true;

                            sql_stmt += " AND ((p.id = d.parent_id and p.id in(" + sandbox_ids.join() + ")) or  (d.parent_id is null and sandbox = 0 )) ";
                        }
                    }
                }
                if (!sandbox_data_found) {
                    sql_stmt += " and sandbox = 0 and (d.parent_id is null or p.id = d.parent_id) ";
                }
                sql_stmt += " ORDER BY platform_name asc, d.platform_id ASC, d.version, p.recommended,p.rating DESC ";


                connection.query(sql_stmt, token, function (err, rows) {

                    connection.release();

                    if (err) {
                        return reject(err);
                    }

                    /** @param [] rows */
                        //var row = rows.shift();
                    let result = _.isEmpty(rows)
                        ? {}
                        : rows;

                    return resolve(result);
                });
            });
        });
    })
};

module.exports.getPlatformDatas = function (data) {
    let platform_ids = _.pluck(data, 'id').join(',');
    let res = platformData(platform_ids);

    return res;
};
let platformData = function (platform_data_ids) {
    return new promise(function (resolve, reject) {
        conn.database(function (err, pool) {
            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }
                let sql = "SELECT * FROM platforms_data_charges WHERE parent_id in (" + platform_data_ids +
                    ") AND status = 1 ORDER BY fee_type_id, inv_type, aua_from ASC ;";
                connection.query(sql, function (err, rows) {

                    connection.release();

                    if (err) {
                        return reject(err);
                    }

                    /** @param [] rows */
                        //var row = rows.shift();
                    let result = _.isEmpty(rows)
                        ? {}
                        : rows;

                    return resolve(result);
                });
            });
        });
    });
};

// Ticket 307 => changes will reflect over here
module.exports.setUserData = function (user, year_count) {
    try {
        let funds_lifetime_isa = user.funds_lifetime_isa;
        let funds_lifetime_isa_cash = user.funds_lifetime_isa_cash;
        let ex_instruments_lifetime_isa = user.ex_instruments_lifetime_isa;

        // planning 307
        let planning_lifetime_isa = user.planning_lifetime_isa;
        let planning_ex_instruments_lifetime_isa = user.planning_ex_instruments_lifetime_isa;
        let planning_lifetime_isa_cash = user.planning_lifetime_isa_cash;

        //RSPL TASK#22
        // user.funds = parseFloat(user.total_savings_and_investments) - parseFloat(user.total_shares);
        user.funds = parseFloat(user.total_savings_and_investments) - parseFloat(user.total_shares);

        user.isa = parseFloat(user.funds_isa);
        user.jisa = parseFloat(user.funds_jisa);
        user.sipp = parseFloat(user.funds_sipp);
        user.jsipp = parseFloat(user.funds_jsipp);
        user.gia = parseFloat(user.funds_gia);
        user.lifetime_isa = parseFloat(funds_lifetime_isa);

        //RSPL TASK#22
        user.isa_cash = parseFloat(user.funds_isa_cash);
        user.jisa_cash = parseFloat(user.funds_jisa_cash);
        user.sipp_cash = parseFloat(user.funds_sipp_cash);
        user.jsipp_cash = parseFloat(user.funds_jsipp_cash);
        user.gia_cash = parseFloat(user.funds_gia_cash);
        user.lifetime_isa_cash = parseFloat(funds_lifetime_isa_cash);

        // user.total = parseFloat(user.total_savings_and_investments);
        user.total = parseFloat(user.total_savings_and_investments);
        user.ex = parseFloat(user.total_shares);
        user.funds = user.total - user.ex;
        user.cash = parseFloat(user.total_savings_and_investments_cash);


        user.funds_isa = parseFloat(user.funds_isa);
        user.funds_jisa = parseFloat(user.funds_jisa);
        user.funds_sipp = parseFloat(user.funds_sipp);
        user.funds_jsipp = parseFloat(user.funds_jsipp);
        user.funds_gia = parseFloat(user.funds_gia);
        funds_lifetime_isa = parseFloat(funds_lifetime_isa);

        //RSPL TASK#22
        user.funds_isa_cash = parseFloat(user.funds_isa_cash);
        user.funds_jisa_cash = parseFloat(user.funds_jisa_cash);
        user.funds_sipp_cash = parseFloat(user.funds_sipp_cash);
        user.funds_jsipp_cash = parseFloat(user.funds_jsipp_cash);
        user.funds_gia_cash = parseFloat(user.funds_gia_cash);
        funds_lifetime_isa_cash = parseFloat(funds_lifetime_isa_cash);

        if (user.investment_stocks_shares === "yes") {
            user.ex_instruments_traded = 1;
            user.ex_instruments_isa = parseFloat(user.ex_instruments_isa);
            user.ex_instruments_jisa = parseFloat(user.ex_instruments_jisa);
            user.ex_instruments_sipp = parseFloat(user.ex_instruments_sipp);
            user.ex_instruments_jsipp = parseFloat(user.ex_instruments_jsipp);
            user.ex_instruments_gia = parseFloat(user.ex_instruments_gia);
            ex_instruments_lifetime_isa = parseFloat(ex_instruments_lifetime_isa); 
        }
        if (!constant.calc_num(user.total_shares)) {
            user.ex_instruments_traded = 0;
            user.ex_instruments_isa = 0;
            user.ex_instruments_jisa = 0;
            user.ex_instruments_sipp = 0;
            user.ex_instruments_jsipp = 0;
            user.ex_instruments_gia = 0;
            ex_instruments_lifetime_isa = 0
        }

        //RSPL TASK#22
        user.isa = user.funds_isa + user.funds_isa_cash + user.ex_instruments_isa;
        user.jisa = user.funds_jisa + user.funds_jisa_cash + user.ex_instruments_jisa;
        user.sipp = user.funds_sipp + user.funds_sipp_cash + user.ex_instruments_sipp;
        user.jsipp = user.funds_jsipp + user.funds_jsipp_cash + user.ex_instruments_jsipp;
        user.gia = user.funds_gia + user.funds_gia_cash + user.ex_instruments_gia;
        user.lifetime_isa = funds_lifetime_isa + funds_lifetime_isa_cash + ex_instruments_lifetime_isa;


        user.yearly_trades_funds = parseFloat(user.investment_frequency_funds);
        user.yearly_trades_ex = parseFloat(user.investment_frequency_ex_traded);
        if (!_.isNumber(user.yearly_trades_funds)) {
            user.yearly_trades_funds = 0
        }
        if (!_.isNumber(user.yearly_trades_ex)) {
            user.yearly_trades_ex = 0
        }

        user.avg_trade_funds = parseFloat(user.average_investment_funds);
        user.avg_trade_ex = parseFloat(user.average_investment_ex_traded);

        user.planning_isa = parseFloat(user.planning_isa);
        user.planning_jisa = parseFloat(user.planning_jisa);
        user.planning_sipp = parseFloat(user.planning_sipp);
        user.planning_jsipp = parseFloat(user.planning_jsipp);
        user.planning_gia = parseFloat(user.planning_gia);
        planning_lifetime_isa = parseFloat(planning_lifetime_isa);
        
        user.planning_ex_isa = parseFloat(user.planning_ex_instruments_isa);
        user.planning_ex_jisa = parseFloat(user.planning_ex_instruments_jisa);
        user.planning_ex_sipp = parseFloat(user.planning_ex_instruments_sipp);
        user.planning_ex_jsipp = parseFloat(user.planning_ex_instruments_jsipp);
        user.planning_ex_gia = parseFloat(user.planning_ex_instruments_gia);
        user.planning_ex_lifetime_isa = parseFloat(planning_ex_instruments_lifetime_isa);

        //RSPL TASK#22
        /*user.planning_cash_isa = parseFloat(user.planning_cash_isa);
        user.planning_cash_jisa = parseFloat(user.planning_cash_jisa);
        user.planning_cash_sipp = parseFloat(user.planning_cash_sipp);
        user.planning_cash_jsipp = parseFloat(user.planning_cash_jsipp);
        user.planning_cash_gia = parseFloat(user.planning_cash_gia);*/
        // RSPL TASK#153
        user.planning_cash_isa = parseFloat(user.planning_isa_cash);
        user.planning_cash_jisa = parseFloat(user.planning_jisa_cash);
        user.planning_cash_sipp = parseFloat(user.planning_sipp_cash);
        user.planning_cash_jsipp = parseFloat(user.planning_jsipp_cash);
        user.planning_cash_gia = parseFloat(user.planning_gia_cash);
        user.planning_cash_lifetime_isa = parseFloat(planning_lifetime_isa_cash);

        user.age = parseFloat(user.age);

        if (user.inv_management_type === "myself") {
            user.platform_type = 2;
        } else {
            user.platform_type = 1;
        }
        let inv_today = 1;
        switch (user.investments_today) {
            case 'today':
                inv_today = 1;
                break;
            case  'over_years':
                inv_today = 2;
                break;
            case 'in_x_years':
                inv_today = 3;
                break;
        }

        user.over_years = parseFloat(user.investments_over);

        user.point_future = parseFloat(user.investments_over);
        user.results = inv_today;
        user.funds_lifetime_isa = funds_lifetime_isa;
        user.ex_instruments_lifetime_isa = ex_instruments_lifetime_isa;
        user.funds_lifetime_isa_cash = funds_lifetime_isa_cash;
        user.ex = user.ex_instruments_isa + user.ex_instruments_jisa + user.ex_instruments_sipp + user.ex_instruments_jsipp + user.ex_instruments_gia + user.ex_instruments_lifetime_isa;
        user.funds = user.funds_isa + user.funds_jisa + user.funds_sipp + user.funds_jsipp + user.funds_gia + user.funds_lifetime_isa;
        //RSPL TASK#22
        user.cash = user.funds_isa_cash + user.funds_jisa_cash + user.funds_sipp_cash + user.funds_jsipp_cash + user.funds_gia_cash + user.funds_lifetime_isa_cash;

        // console.log(' useruseruseruser ' + JSON.stringify(user));
        return user;

    } catch (err) {
        // console.log(' errerrerr ' + err);
        console.log(err);

    }


};


module.exports.save_request = function (data, id = null) {
    return new promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }
                if (_.isUndefined(data.results) || _.isNull(data.results)) {
                    data.results = null;
                }
                if (_.isUndefined(data.user_id) || _.isNull(data.user_id)) {
                    reject('user not found')
                }
                let request = JSON.stringify(data.request);
                _.object(data.results);
                let results = JSON.stringify(data.results);
                let sql = '';
                //@todo query with params
                if (_.isNull(id)) {
                    // we hit the database to check connectivity
                    sql = "INSERT INTO `platform_request` (`request`,`result`,`user_id`) VALUES " + "('" + request + "', '" + results + "', " + data.user_id + ")";
                } else {
                    sql = "Update `platform_request`" +
                        " SET `result`='" + results + "'  where id=" + id;
                }
                connection.query(sql, function (err, res) {

                    connection.release();
                    let ret = null;
                    if (err) {
                        return reject(err);
                    }
                    if (!res.affectedRows) {
                        console.error('api request insert failed');
                    } else {
                        if (_.isNull(id)) {
                            ret = res.insertId;
                        } else {
                            ret = res.affectedRows;
                        }
                        console.info('api request :' + res.insertId);
                    }

                    return resolve(ret);
                });
            });
        });
    });

};


module.exports.getDateFormat = function (date) {
    return date.getFullYear() + '-' + ("0" + (date.getMonth() + 1)).slice(-2) + '-' + ("0" + date.getDate()).slice(-2)
};
module.exports.isset = function (val) {
    return !_.isNull(val) && !_.isUndefined(val) && val !== '' && !_.isNaN(val);
};

module.exports.sanitize_num = function (val) {

    if (_.isNumber(val)) {
        return val;
    }
    if (module.exports.isset(val) && !_.isNull(val)) {
        let new_val = val.replace(/[^\d\.]/g, '');
        if (new_val === '') {
            new_val = 0;
        }

        return new_val;
    } else {
        return val;
    }
};
module.exports.savePlatformRequest = function (data, id = null) {
    return new promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }
                if (_.isUndefined(data.results) || _.isNull(data.results)) {
                    data.results = null;
                }
                if (_.isUndefined(data.request.user_id) || _.isNull(data.request.user_id)) {
                    reject('user not found')
                }
                let json_request = JSON.stringify(data.request);

                _.object(data.results);
                let results = JSON.stringify(data.results);
                let sql = '';
                //@todo query with params
                if (_.isNull(id)) {

                    sql = "INSERT INTO `platform_data_request` (`request`,`response`,`user_id`) VALUES ('" + json_request + "', '" + results + "', " + data.request.user_id + ")";
                } else {
                    sql = "Update `platform_data_request`  SET `response` ='" + results + "' where id=" + id;
                }
                connection.query(sql, function (err, res) {

                    connection.release();
                    let ret = null;
                    if (err) {
                        return reject(err);
                    }
                    if (!res.affectedRows) {
                        console.error('platform data api request insert failed');
                    } else {
                        if (_.isNull(id)) {
                            ret = res.insertId;
                        } else {
                            ret = res.affectedRows;
                        }
                        console.info('platform data api request :' + res.insertId);
                    }

                    return resolve(json_request);
                });
            });
        });
    });

};

