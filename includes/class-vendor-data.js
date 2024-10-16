_ = require('underscore-node');
constant = require('./const');
const conn = require('../model');
const fn = require('./functions');

let promise = require('promise');

module.exports.vendorData = class VendorData {
    constructor(platform_data, platform_id) {
        this.platform_data = platform_data;
        this.platform_id = platform_id;
    }


    platDataStatusSql(platform_id, version_id, sql_stat) {

        let ret = {};
        if (sql_stat === 1) {
            ret.sql1 = "UPDATE platforms_data SET rec_status = case when version = " + version_id + " then 1" +
                " when rec_status = 1 then 0 else `rec_status` end  WHERE platform_id = " + platform_id;
            ret.sql2 = "SELECT id from platforms_data where version = " + version_id + " and platform_id= " + platform_id;
            ret.sql3 = "UPDATE platforms_data_charges SET `status` = IF(parent_id = ?, 1, 0) WHERE platform_id = " + platform_id;


        } else {
            ret.sql1 = "update platforms_data set rec_status =  case when version = " + version_id + " then " + sql_stat +
                " when rec_status = " + sql_stat + " then 0 else `rec_status` end  WHERE platform_id = " + platform_id;
            ret.sql2 = "select id from platforms_data where version = " + version_id + " and platform_id= " + platform_id;
            ret.sql3 = "update platforms_data_charges set `status` = 0 where platform_id = " + platform_id + " and parent_id = ?";
        }
        return ret;
    };

    custodyFeeSql(platform, sql_stat = -1) {

        let plat_data = platform.platform_data.data;
        let last_id = platform.plat_data_insert_id;
        let platform_id = platform.platform_id;
        let sql = "";
        if (_.isEmpty(plat_data['platform_fees'])) {
            return sql;
        }

        plat_data['platform_fees']['ids'] = [];

        let n = 0;
        let count = _.size(plat_data['platform_fees']);
        count = count > 0 && plat_data['platform_fees'].hasOwnProperty('deleted') ? count - 1 : count;
        if (count > 0) {
            _.each(plat_data['platform_fees'], function (tier, k) {

                let deleted_col_info = k === 'deleted' ? 1 : 0;
                if (!deleted_col_info) {

                    let sql_values = "";
                    sql_values = sql_values + last_id + ", ";
                    sql_values = sql_values + platform_id + ", ";
                    sql_values = sql_values + constant.FEE_TYPE_CUSTODY + ", ";
                    sql_values = sql_values + "'" + tier['fee_name'] + "', ";
                    switch (tier['investment_type']) {
                        case '1':
                            sql_values = sql_values + constant.INV_TYPE_FUND + ", ";
                            break;
                        case '2':
                            sql_values = sql_values + constant.INV_TYPE_EX_TRADED + ", ";
                            break;
                        //RSPL TASK#21
                        case '4':
                            sql_values = sql_values + constant.INV_TYPE_CASH + ", ";
                            break;
                        default:
                            sql_values = sql_values + "null, ";
                    }
                    switch (tier['type']) {
                        case '1':
                            sql_values = sql_values + constant.TIER_TYPE_AD_VALORAM + ", ";
                            break;
                        case '2':
                            sql_values = sql_values + constant.TIER_TYPE_FLAT_RATE + ", ";
                            break;
                        case '3':
                            sql_values = sql_values + constant.TIER_TYPE_PER_INVESTMENT + ", ";
                            break;
                        case '4':
                            sql_values = sql_values + constant.TIER_TYPE_PER_TRANSACTION + ", ";
                            break;
                        default:
                            sql_values = sql_values + "null, ";
                    }
                    let gia = fn.isset(tier['gia']) ? fn.sanitize_num(tier['gia']) : 0;
                    let isa = fn.isset(tier['isa']) ? fn.sanitize_num(tier['isa']) : 0;
                    let jisa = fn.isset(tier['jisa']) ? fn.sanitize_num(tier['jisa']) : 0;
                    let sipp = fn.isset(tier['sipp']) ? fn.sanitize_num(tier['sipp']) : 0;
                    let jsipp = fn.isset(tier['childsipp']) ? fn.sanitize_num(tier['childsipp']) : 0;
                    let onshore_bond = fn.isset(tier['onshore_bond']) ? fn.sanitize_num(tier['onshore_bond']) : 0;
                    let offshore_bond = fn.isset(tier['offshore_bond']) ? fn.sanitize_num(tier['offshore_bond']) : 0;
                    // ticket 307 changes
                    let lifetime_isa = fn.isset(tier['lifetime_isa']) ? fn.sanitize_num(tier['lifetime_isa']) : 0;
                    let interest_rate = fn.isset(tier['interest_rate']) ? fn.sanitize_num(tier['interest_rate']) : 0;
                    let tiered = fn.isset(tier['tiered']) ? fn.sanitize_num(tier['tiered']) : 0;
                    let aua_from = fn.isset(tier['aua_from']) ? fn.sanitize_num(tier['aua_from']) : null;
                    let aua_to = fn.isset(tier['aua_to']) ? fn.sanitize_num(tier['aua_to']) : null;
                    let vat = fn.isset(tier['vat']) ? fn.sanitize_num(tier['vat']) : 0;
                    let exclude = fn.isset(tier['exclude']) ? parseInt(fn.sanitize_num(tier['exclude'])) : null;
                    let max_cap = fn.isset(tier['max_cap']) ? parseFloat(fn.sanitize_num(tier['max_cap'])) : null;
                    let time_held_from = fn.isset(tier['time_held_from']) ? parseInt(fn.sanitize_num(tier['time_held_from'])) : null;
                    let time_held_to = fn.isset(tier['time_held_to']) ? parseInt(fn.sanitize_num(tier['time_held_to'])) : null;
                    let transfer_type = fn.isset(tier['transfer_type']) ? parseInt(fn.sanitize_num(tier['transfer_type'])) : null;
                    sql_values = sql_values + parseFloat(gia) +
                        ", ";
                    sql_values = sql_values + parseFloat(isa) +
                        ", ";
                    sql_values = sql_values + parseFloat(jisa) +
                        ", ";
                    sql_values = sql_values + parseFloat(sipp) +
                        ", ";
                    sql_values = sql_values + parseFloat(jsipp) +
                        ", ";
                    sql_values = sql_values + parseFloat(onshore_bond) +
                        ", ";
                    sql_values = sql_values + parseFloat(offshore_bond) +
                        ", ";
                    // ticket 307 changes
                    sql_values = sql_values + parseFloat(lifetime_isa) +
                        ", ";
                    sql_values = sql_values + parseFloat(interest_rate) +
                        ", ";
                    sql_values = sql_values + parseFloat(tiered) +
                        ", ";
                    sql_values = sql_values + aua_from +
                        ", ";
                    sql_values = sql_values + aua_to +
                        ", ";
                    sql_values = sql_values + parseInt(vat) +
                        ", ";
                    sql_values = sql_values + transfer_type +
                        ", ";
                    sql_values = sql_values + exclude +
                        ", ";
                    sql_values = sql_values + max_cap +
                        ", ";
                    sql_values = sql_values + time_held_from +
                        ", ";
                    sql_values = sql_values + time_held_to +
                        ", ";

                    sql_values = sql_values + sql_stat;


                    sql += "(" + sql_values + ")";
                    if (n !== count - 1) sql += ',';
                    n++;
                } else {
                    count -= 1;
                }

            });
        }
        return sql;
    };

    sippChargesSql(platform, sql_stat = -1) {

        let plat_data = platform.platform_data.data;
        let last_id = platform.plat_data_insert_id;
        let platform_id = platform.platform_id;
        let n = 0;
        let count = _.size(plat_data['platform_sipp_charges']);
        count = count > 0 && plat_data['platform_sipp_charges'].hasOwnProperty('deleted') ? count - 1 : count;
        let sql_statement = "";
        if (count > 0) {
            _.each(plat_data['platform_sipp_charges'], function (tier, k) {
                let deleted_col_info = k === 'deleted' ? 1 : 0;
                if (!deleted_col_info) {

                    let sql_values = "";
                    let fee_name = _.isUndefined(tier['fee_name']) || _.isNull(tier['fee_name']) ? '' : tier['fee_name'];
                    sql_values = sql_values + last_id + ", ";
                    sql_values = sql_values + platform_id + ", ";
                    sql_values = sql_values + constant.FEE_TYPE_SIPP + ", ";
                    sql_values = sql_values + "'" + fee_name + "', ";
                    switch (tier['investment_type']) {
                        case '1':
                            sql_values = sql_values + constant.INV_TYPE_FUND + ", ";
                            break;
                        case '2':
                            sql_values = sql_values + constant.INV_TYPE_EX_TRADED + ", ";
                            break;
                        case '4':
                            sql_values = sql_values + constant.INV_TYPE_CASH + ", ";
                            break;
                        default:
                            sql_values = sql_values + "null, ";
                    }
                    switch (tier['type']) {
                        case '1':
                            sql_values = sql_values + constant.TIER_TYPE_AD_VALORAM + ", ";
                            break;
                        case '2':
                            sql_values = sql_values + constant.TIER_TYPE_FLAT_RATE + ", ";
                            break;
                        case '3':
                            sql_values = sql_values + constant.TIER_TYPE_PER_INVESTMENT + ", ";
                            break;
                        case '4':
                            sql_values = sql_values + constant.TIER_TYPE_PER_TRANSACTION + ", ";
                            break;
                        default:
                            sql_values = sql_values + "null, ";
                    }

                    let gia = fn.isset(tier['gia']) ? fn.sanitize_num(tier['gia']) : 0;
                    let isa = fn.isset(tier['isa']) ? fn.sanitize_num(tier['isa']) : 0;
                    let jisa = fn.isset(tier['jisa']) ? fn.sanitize_num(tier['jisa']) : 0;
                    let sipp = fn.isset(tier['sipp']) ? fn.sanitize_num(tier['sipp']) : 0;
                    let jsipp = fn.isset(tier['childsipp']) ? fn.sanitize_num(tier['childsipp']) : 0;
                    let onshore_bond = fn.isset(tier['onshore_bond']) ? fn.sanitize_num(tier['onshore_bond']) : 0;
                    let offshore_bond = fn.isset(tier['offshore_bond']) ? fn.sanitize_num(tier['offshore_bond']) : 0;
                    let lifetime_isa =  fn.isset(tier['lifetime_isa']) ? fn.sanitize_num(tier['lifetime_isa']) : 0;
                    let interest_rate = fn.isset(tier['interest_rate']) ? fn.sanitize_num(tier['interest_rate']) : 0;

                    let tiered = fn.isset(tier['tiered']) ? tier['tiered'] : 0;
                    let aua_from = fn.isset(tier['aua_from']) ? fn.sanitize_num(tier['aua_from']) : null;
                    let aua_to = fn.isset(tier['aua_to']) ? fn.sanitize_num(tier['aua_to']) : null;
                    let vat = fn.isset(tier['vat']) ? tier['vat'] : 0;
                    let exclude = fn.isset(tier['exclude']) ? fn.sanitize_num(tier['exclude']) : 0;
                    let max_cap = fn.isset(tier['max_cap']) ? fn.sanitize_num(tier['max_cap']) : null;
                    let transfer_type = fn.isset(tier['transfer_type']) ? fn.sanitize_num(tier['transfer_type']) : null;
                    let time_held_from = fn.isset(tier['time_held_from']) ? fn.sanitize_num(tier['time_held_from']) : null;
                    let time_held_to = fn.isset(tier['time_held_to']) ? parseInt(fn.sanitize_num(tier['time_held_to'])) : null;

                    sql_values = sql_values + parseFloat(gia) +
                        ", ";
                    sql_values = sql_values + parseFloat(isa) +
                        ", ";
                    sql_values = sql_values + parseFloat(jisa) +
                        ", ";
                    sql_values = sql_values + parseFloat(sipp) +
                        ", ";
                    sql_values = sql_values + parseFloat(jsipp) +
                        ", ";
                    sql_values = sql_values + parseFloat(onshore_bond) +
                        ", ";
                    sql_values = sql_values + parseFloat(offshore_bond) +
                        ", ";
                    sql_values = sql_values + parseFloat(lifetime_isa) +
                        ", ";
                    sql_values = sql_values + parseFloat(interest_rate) +
                        ", ";
                    sql_values = sql_values + tiered +
                        ", ";
                    sql_values = sql_values + aua_from +
                        ", ";
                    sql_values = sql_values + aua_to +
                        ", ";
                    sql_values = sql_values + parseInt(vat) +
                        ", ";
                    sql_values = sql_values + transfer_type +
                        ", ";
                    sql_values = sql_values + exclude +
                        ", ";
                    sql_values = sql_values + max_cap +
                        ", ";
                    sql_values = sql_values + time_held_from +
                        ", ";
                    sql_values = sql_values + time_held_to +
                        ", ";
                    sql_values = sql_values + sql_stat;

                    sql_statement += "(" + sql_values + ")";
                    if (n !== count - 1) sql_statement += ',';
                    n++;
                } else {
                    count -= 1;
                }
            });
        }
        return sql_statement;
    };

    platQuery(data) {

        data = _.defaults( data, constant.platform_defaults,);
        let platform_type = 0;
        switch (data.type) {
            case 'advised':
                platform_type = constant.PLATFORM_ADVISED;
                break;
            case 'd2c':
                platform_type = constant.PLATFORM_D2C;
                break;

        }
        let method = 0;
        switch (data.method) {
            case 'method1':
                method = 1;
                break;
            case 'method2':
                method = 2;
                break;
            case 'method3':
                method = 3;
                break;
            case 'method4':
                method = 4;
                break;
            case 'method5':
                method = 5;
                break;

        }
        let recommended = 0;
        switch (data.recommended) {
            case 'yes':
                recommended = 1;
                break;
            case 'no':
                recommended = 0;
                break;

        }


        let sql_statement = "UPDATE `platforms` "
            + "SET `platform_name` = '" + data.name +
            "',`info_url` = '" + data.info_link + "'," +
            "`platform_type` = '" + platform_type
            + "',`calculation_method` =" + method
            + ",`recommended` =" + recommended +
            ", `img` ='" + data.img
            + "',`rating` =" + data.rating
            + ", `url` ='" + data.url
            +"', `published` =" + data.published +
            "  WHERE `platform_id` = " + this.platform_id +" and sandbox = 0";
        return sql_statement;
    };

    platDataQuery(sql_stat = -1) {
        let $this = this;
        return new promise(function (resolve, reject) {
            let plat_data = $this.platform_data.platform_data.data;
            let sql_values = "";
            sql_values = $this.platform_id + ", ";
            sql_values = sql_values + plat_data['version_id'] + ", ";
            let active_from = new Date(plat_data['active_from']);
            let active_from_formatted = fn.getDateFormat(active_from);
            sql_values = sql_values + "'" + active_from_formatted + "', ";
            let active_to = new Date(plat_data['active_to']);
            let active_to_formatted = fn.getDateFormat(active_to);
            sql_values = sql_values + "'" + active_to_formatted + "', ";
            let ethical_investment = fn.isset(plat_data['ethical_investment']) ? plat_data['ethical_investment'] : 0;
            sql_values = sql_values + parseInt(ethical_investment) + ", ";
            let dealing_fee_credits = fn.isset(plat_data['dealing_fee_credits']) && plat_data['dealing_fee_credits'] !== '' ? parseFloat(plat_data['dealing_fee_credits']) : null;
            let gia = fn.isset(plat_data['gia_supported']) ? plat_data['gia_supported'] : 0;
            sql_values = sql_values + parseInt(gia) + ", ";
            let isa = fn.isset(plat_data['isa_supported']) ? plat_data['isa_supported'] : 0;
            sql_values = sql_values + parseInt(isa) + ", ";
            let jisa = fn.isset(plat_data['jisa_supported']) ? plat_data['jisa_supported'] : 0;
            sql_values = sql_values + parseInt(jisa) + ", ";
            let sipp = fn.isset(plat_data['sipp_supported']) ? plat_data['sipp_supported'] : 0;
            sql_values = sql_values + parseInt(sipp) + ", ";
            let jsipp = fn.isset(plat_data['childsipp_supported']) ? plat_data['childsipp_supported'] : 0;
            sql_values = sql_values + parseInt(jsipp) + ", ";
            let onshore = fn.isset(plat_data['onshore_supported']) ? plat_data['onshore_supported'] : 0;
            sql_values = sql_values + parseInt(onshore) + ", ";
            let offshore = fn.isset(plat_data['offshore_supported']) ? plat_data['offshore_supported'] : 0;
            sql_values = sql_values + parseInt(offshore) + ", ";
            let lifetime_isa = fn.isset(plat_data['lifetime_isa_supported']) ? plat_data['lifetime_isa_supported'] : 0;
            sql_values = sql_values + parseInt(lifetime_isa) + ", ";


            let ex_gia = fn.isset(plat_data['ex_instruments_gia_supported']) ? plat_data['ex_instruments_gia_supported'] : 0;
            sql_values = sql_values + parseInt(ex_gia) + ", ";
            let ex_isa = fn.isset(plat_data['ex_instruments_isa_supported']) ? plat_data['ex_instruments_isa_supported'] : 0;
            sql_values = sql_values + parseInt(ex_isa) + ", ";
            let ex_jisa = fn.isset(plat_data['ex_instruments_jisa_supported']) ? plat_data['ex_instruments_jisa_supported'] : 0;
            sql_values = sql_values + parseInt(ex_jisa) + ", ";
            let ex_sipp = fn.isset(plat_data['ex_instruments_sipp_supported']) ? plat_data['ex_instruments_sipp_supported'] : 0;
            sql_values = sql_values + parseInt(ex_sipp) + ", ";
            let ex_jsipp = fn.isset(plat_data['ex_instruments_childsipp_supported']) ? plat_data['ex_instruments_childsipp_supported'] : 0;
            sql_values = sql_values + parseInt(ex_jsipp) + ", ";
            let ex_onshore = fn.isset(plat_data['ex_instruments_onshore_supported']) ? plat_data['ex_instruments_onshore_supported'] : 0;
            sql_values = sql_values + parseInt(ex_onshore) + ", ";
            let ex_offshore = fn.isset(plat_data['ex_instruments_offshore_supported']) ? plat_data['ex_instruments_offshore_supported'] : 0;
            sql_values = sql_values + parseInt(ex_offshore) + ", ";
            let ex_lifetime_isa = fn.isset(plat_data['ex_instruments_lifetime_isa_supported']) ? plat_data['ex_instruments_lifetime_isa_supported'] : 0;
            sql_values = sql_values + parseInt(ex_lifetime_isa) + ", ";

            let custody_fee_min = fn.isset(plat_data['custody_fees_min']) ? parseFloat(plat_data['custody_fees_min']) : null;
            sql_values = sql_values + custody_fee_min + ", ";
            let custody_fee_max = fn.isset(plat_data['custody_fees_max']) ? parseFloat(plat_data['custody_fees_max']) : null;
            sql_values = sql_values + custody_fee_max + ", ";
            //RSPL Task#73 Starts
            let cash_custody_fees_min = fn.isset(plat_data['cash_custody_fees_min']) ? parseFloat(plat_data['cash_custody_fees_min']) : null;
            sql_values = sql_values + cash_custody_fees_min + ", ";
            let cash_custody_fees_max = fn.isset(plat_data['cash_custody_fees_max']) ? parseFloat(plat_data['cash_custody_fees_max']) : null;
            sql_values = sql_values + cash_custody_fees_max + ", ";
            //RSPL Task#73 Ends
            let funds_custody_fees_min = fn.isset(plat_data['funds_custody_fees_min']) ? parseFloat(plat_data['funds_custody_fees_min']) : null;
            sql_values = sql_values + funds_custody_fees_min + ", ";
            let funds_custody_fees_max = fn.isset(plat_data['funds_custody_fees_max']) ? parseFloat(plat_data['funds_custody_fees_max']) : null;
            sql_values = sql_values + funds_custody_fees_max + ", ";
            let ex_instruments_custody_fees_min = fn.isset(plat_data['ex_instruments_custody_fees_min']) ? parseFloat(plat_data['ex_instruments_custody_fees_min']) : null;
            sql_values = sql_values + ex_instruments_custody_fees_min + ", ";
            let ex_instruments_custody_fees_max = fn.isset(plat_data['ex_instruments_custody_fees_max']) ? parseFloat(plat_data['ex_instruments_custody_fees_max']) : null;
            sql_values = sql_values + ex_instruments_custody_fees_max + ", ";
            let product_annual_charge_min = fn.isset(plat_data['product_annual_charge_min']) ? parseFloat(plat_data['product_annual_charge_min']) : null;
            sql_values = sql_values + product_annual_charge_min + ", ";
            let product_annual_charge_max = fn.isset(plat_data['product_annual_charge_max']) ? parseFloat(plat_data['product_annual_charge_max']) : null;
            sql_values = sql_values + product_annual_charge_max + ", ";
            let incentive_charge_max = fn.isset(plat_data['incentive_max']) ? parseFloat(plat_data['incentive_max']) : null;
            let incentive_charge_min = fn.isset(plat_data['incentive_min']) ? parseFloat(plat_data['incentive_min']) : null;
            let vat_val = fn.isset(plat_data['vat_val']) ? parseFloat(plat_data['vat_val']) : 20;

            let date_created = new Date(plat_data['date_created']);
            let date_created_formatted = fn.getDateFormat(date_created);
            sql_values = sql_values + "'" + date_created_formatted + "', ";

            sql_values = sql_values + sql_stat + ", ";
            sql_values = sql_values + incentive_charge_min + ", ";
            sql_values = sql_values + incentive_charge_max + ", ";
            sql_values = sql_values + vat_val + ", ";
            sql_values = sql_values + dealing_fee_credits;
            conn.database(function (err, pool) {

                if (err) {
                    reject(err);
                }

                pool.getConnection(function (err, connection) {
                    let sql_statement = "Select id from platforms_data where platform_id = " + $this.platform_id + " and version = " + plat_data['version_id'];
                    connection.query(sql_statement, function (err, result) {
                        connection.release();
                        if (err) {

                            console.log(err);
                            reject(err);


                        }
                        console.log('platform version sql');
                        console.log(result);
                        result = !_.isEmpty(result) ? result.shift() : null;
                        if (!_.isNull(result)) {

                            $this.platform_data.plat_data_insert_id = result.id;
                            let sql_statement = "Update  platforms_data set  active_from = '" + active_from_formatted + "', active_to = '"
                                + active_to_formatted + 
                                "', ethical_investment =" + ethical_investment +
                                ", sup_fund_gia = " + gia +
                                ", sup_fund_isa = " + isa +
                                ", sup_fund_jisa = " + jisa +
                                ", sup_fund_sipp = " + sipp +
                                ", sup_fund_jsipp =" + jsipp +
                                ", sup_fund_onshore_bond =" + onshore +
                                ", sup_fund_offshore_bond =" + offshore +
                                ", sup_fund_lifetime_isa =" + lifetime_isa +
                                ", sup_ex_gia = " + ex_gia +
                                ", sup_ex_isa = " + ex_isa +
                                ", sup_ex_jisa = " + ex_jisa +
                                ", sup_ex_sipp = " + ex_sipp +
                                ", sup_ex_jsipp = " + ex_jsipp +
                                ", sup_ex_onshore_bond = " + ex_onshore +
                                ", sup_ex_offshore_bond = " + ex_offshore +
                                ", sup_ex_lifetime_isa = " + ex_lifetime_isa +
                                ", all_cust_fee_min = " + custody_fee_min +
                                ", all_cust_fee_max = " + custody_fee_max +
                                ", cash_cust_fee_min = " + cash_custody_fees_min +
                                ", cash_cust_fee_max = " + cash_custody_fees_max +
                                ", fund_cust_fee_min = " + funds_custody_fees_min +
                                ", fund_cust_fee_max = " + funds_custody_fees_max +
                                ", ex_cust_fee_min = " + ex_instruments_custody_fees_min +
                                ", ex_cust_fee_max =" + ex_instruments_custody_fees_max +
                                ", ann_admin_fee_min =" + product_annual_charge_min +
                                ", ann_admin_fee_max = " +
                                product_annual_charge_max +
                                ", dealing_fee_credits =" + dealing_fee_credits +
                                ", vat_val =" + vat_val +
                                ", incentive_min = " + incentive_charge_min +
                                ", incentive_max = " + incentive_charge_max +
                                ", date_created = '" + date_created_formatted + "', rec_status=" + sql_stat +
                                " where platform_id = " + $this.platform_id + " and version = " + plat_data['version_id'];
                            return resolve(sql_statement);
                        } else {
                            let sql_statement = "INSERT INTO  platforms_data (platform_id, version, active_from, active_to, ethical_investment, sup_fund_gia, sup_fund_isa, sup_fund_jisa, sup_fund_sipp, sup_fund_jsipp,sup_fund_onshore_bond,sup_fund_offshore_bond, sup_fund_lifetime_isa, sup_ex_gia, sup_ex_isa, sup_ex_jisa, sup_ex_sipp, sup_ex_jsipp,sup_ex_onshore_bond,sup_ex_offshore_bond, sup_ex_lifetime_isa, all_cust_fee_min, all_cust_fee_max, cash_cust_fee_min, cash_cust_fee_max, fund_cust_fee_min, fund_cust_fee_max, ex_cust_fee_min, ex_cust_fee_max, ann_admin_fee_min, ann_admin_fee_max, date_created, rec_status, incentive_min, incentive_max, vat_val, dealing_fee_credits) VALUES (" + sql_values + ");";
                            return resolve(sql_statement);
                        }


                    });
                });
            });

        });


    };


    feeSql(fee, fee_type_id, sql_stat, platform_id, parent_id) {

        let sql = "";
        if (_.isEmpty(fee)) {
            return sql;
        }


        let n = 0;
        let count = _.size(fee, fee_type_id);
        count = count > 0 && fee.hasOwnProperty('deleted') ? count - 1 : count;
        if (count > 0) {
            _.each(fee, function (tier, k) {

                let deleted_col_info = k === 'deleted' ? 1 : 0;
                if (!deleted_col_info) {
                    let fee_name = _.isUndefined(tier['fee_name']) || _.isNull(tier['fee_name']) ? '' : tier['fee_name'];

                    let sql_values = "";
                    sql_values = sql_values + parent_id + ", ";
                    sql_values = sql_values + platform_id + ", ";
                    sql_values = sql_values + fee_type_id + ", ";
                    sql_values = sql_values + "'" + fee_name + "', ";

                    switch (tier['investment_type']) {
                        case '1':
                            sql_values = sql_values + constant.INV_TYPE_FUND + ", ";
                            break;
                        case '2':
                            sql_values = sql_values + constant.INV_TYPE_EX_TRADED + ", ";
                            break;
                        //RSPL TASK#21
                        case '4':
                            sql_values = sql_values + constant.INV_TYPE_CASH + ", ";
                            break;
                        default:
                            sql_values = sql_values + "null, ";
                    }
                    switch (tier['type']) {
                        case '1':
                            sql_values = sql_values + constant.TIER_TYPE_AD_VALORAM + ", ";
                            break;
                        case '2':
                            sql_values = sql_values + constant.TIER_TYPE_FLAT_RATE + ", ";
                            break;
                        case '3':
                            sql_values = sql_values + constant.TIER_TYPE_PER_INVESTMENT + ", ";
                            break;
                        case '4':
                            sql_values = sql_values + constant.TIER_TYPE_PER_TRANSACTION + ", ";
                            break;
                        default:
                            if (fee_name === 'Account opening fee') {
                                sql_values = sql_values + "5, ";
                            } else {
                                sql_values = sql_values + "null, ";
                            }
                    }

                    let gia = fn.isset(tier['gia']) ? fn.sanitize_num(tier['gia']) : 0;
                    let isa = fn.isset(tier['isa']) ? fn.sanitize_num(tier['isa']) : 0;
                    let jisa = fn.isset(tier['jisa']) ? fn.sanitize_num(tier['jisa']) : 0;
                    let sipp = fn.isset(tier['sipp']) ? fn.sanitize_num(tier['sipp']) : 0;
                    let jsipp = fn.isset(tier['childsipp']) ? fn.sanitize_num(tier['childsipp']) : 0;
                    let onshore_bond = fn.isset(tier['onshore_bond']) ? fn.sanitize_num(tier['onshore_bond']) : 0;
                    let offshore_bond = fn.isset(tier['offshore_bond']) ? fn.sanitize_num(tier['offshore_bond']) : 0;
                    let lifetime_isa = fn.isset(tier['lifetime_isa']) ? fn.sanitize_num(tier['lifetime_isa']) : 0;
                    let interest_rate = fn.isset(tier['interest_rate']) ? fn.sanitize_num(tier['interest_rate']) : 0;
                    let tiered = fn.isset(tier['tiered']) ? fn.sanitize_num(tier['tiered']) : 0;
                    let aua_from = fn.isset(tier['aua_from']) ? fn.sanitize_num(tier['aua_from']) : null;
                    let aua_to = fn.isset(tier['aua_to']) ? fn.sanitize_num(tier['aua_to']) : null;
                    let vat = fn.isset(tier['vat']) ? fn.sanitize_num(tier['vat']) : 0;
                    let exclude = fn.isset(tier['exclude']) ? parseInt(fn.sanitize_num(tier['exclude'])) : null;
                    let max_cap = fn.isset(tier['max_cap']) ? parseFloat(fn.sanitize_num(tier['max_cap'])) : null;
                    let time_held_from = fn.isset(tier['time_held_from']) ? parseInt(fn.sanitize_num(tier['time_held_from'])) : null;
                    let time_held_to = fn.isset(tier['time_held_to']) ? parseInt(fn.sanitize_num(tier['time_held_to'])) : null;
                    let transfer_type = fn.isset(tier['transfer_type']) ? parseInt(fn.sanitize_num(tier['transfer_type'])) : null;
                    sql_values = sql_values + parseFloat(gia) +
                        ", ";
                    sql_values = sql_values + parseFloat(isa) +
                        ", ";
                    sql_values = sql_values + parseFloat(jisa) +
                        ", ";
                    sql_values = sql_values + parseFloat(sipp) +
                        ", ";
                    sql_values = sql_values + parseFloat(jsipp) +
                        ", ";
                    sql_values = sql_values + parseFloat(onshore_bond) +
                        ", ";
                    sql_values = sql_values + parseFloat(offshore_bond) +
                        ", ";
                    sql_values = sql_values + parseFloat(lifetime_isa) +
                        ", ";
                    sql_values = sql_values + parseFloat(interest_rate) +
                        ", ";
                    sql_values = sql_values + parseFloat(tiered) +
                        ", ";
                    sql_values = sql_values + aua_from +
                        ", ";
                    sql_values = sql_values + aua_to +
                        ", ";
                    sql_values = sql_values + parseInt(vat) +
                        ", ";
                    sql_values = sql_values + transfer_type +
                        ", ";
                    sql_values = sql_values + exclude +
                        ", ";
                    sql_values = sql_values + max_cap +
                        ", ";
                    sql_values = sql_values + time_held_from +
                        ", ";
                    sql_values = sql_values + time_held_to +
                        ", ";

                    sql_values = sql_values + sql_stat;


                    sql += "(" + sql_values + ")";
                    if (n !== count - 1) sql += ',';
                    n++;
                } else {
                    count -= 1;
                }

            });
        }
        return sql;
    }

};
