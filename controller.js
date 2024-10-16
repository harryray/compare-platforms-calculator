'use strict';
const conn = require('./model.js');
const fn = require('./includes/functions');
const calculator_compare = require('./includes/class-calculator-compare');
const calculator_platform_heat_map = require('./includes/class-calculator-platform-heat-map'); //Ticket#192 @ checklist -3
const vendor = require('./includes/class-vendor-data');
let _ = require('underscore-node');
let promise = require('promise');
let constant = require('./includes/const');
global.ctp_clean_number = function (number) {
    if (_.isNaN(number)) {
        number = number.replace(/[^0-9.]/g, '');
    }

    return number;
};
let containerId;
module.exports.hello = (event) => {
    return new promise(function (resolve, reject) {
        try {
            if (!containerId) containerId = event.requestId;

            //console.log(containerId);

            resolve('pong');
        } catch (err) {
            reject(err);
        }
    });
};
module.exports.ping = function () {
    return new promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }

                // we hit the database to check connectivity
                var sql = "SELECT max(created_at) as m FROM platforms";
                connection.query(sql, function (err, rows) {

                    connection.release();

                    if (err) {
                        return reject(err);
                    }

                    /** @param {string} row.m */
                    var row = rows.shift();
                    var result = _.isNull(row.m)
                        ? "no records"
                        : "max(created_at):" + row.m;

                    return resolve(result);
                });
            });
        });
    });
};
module.exports.calculate = async function (user) {
    try {
        let data = {};
        fn.setUserData(user);
        let platforms = await fn.platform(user);
        let platforms_data = await fn.getPlatformDatas(platforms);
        var c = new calculator_compare.cc(platforms, platforms_data, user);
        var ret = await c.get_platform_queue();
        data.results = false;

        if(!user.investment_products_simplified) {
            data.results = ret;
        }

        if(user.investment_products_simplified) {

            let ETF_user = structuredClone(user);

            ETF_user.ex_instruments_isa = user.funds_isa
            ETF_user.ex_instruments_gia = user.funds_gia
            ETF_user.ex_instruments_sipp = user.funds_sipp
            ETF_user.total_shares = user.total_funds
            ETF_user.ex_isa = ETF_user.isa
            ETF_user.ex_gia = ETF_user.gia
            ETF_user.ex_sipp = ETF_user.sipp
            ETF_user.investment_stocks_shares = "yes"

            ETF_user.funds_isa = 0
            ETF_user.funds_gia = 0
            ETF_user.funds_sipp = 0
            ETF_user.total_funds = 0
            ETF_user.isa = 0
            ETF_user.gia = 0
            ETF_user.sipp = 0

            fn.setUserData(ETF_user)

            let platforms_simplifiedETF = await fn.platform_ETF_ONLY(ETF_user);
            
            if(platforms_simplifiedETF.length > 0) {

                let platforms_data_simplifiedETF = await fn.getPlatformDatas(platforms_simplifiedETF);

                var c_simplifiedETF = new calculator_compare.cc(platforms_simplifiedETF, platforms_data_simplifiedETF, ETF_user);
                var ret_simplifiedETF = await c_simplifiedETF.get_platform_queue();
                data.results = {...ret, ...ret_simplifiedETF}
                ret = {...ret, ...ret_simplifiedETF}
            }
        }

        // console.log('data results HTB')
        // console.log(data.results)
        // console.log('|||')

        if (user.platinum_vendor != 1) {
            fn.save_request(data, user.request_id);
        }
        //console.log( 'retretretret ' + JSON.stringify(ret) );
        return ret;
    } catch (err) {
        //console.log(err);
    }

};

/* Ticket#192 @ checklist -3 start  */
module.exports.calculate_heat_map = async function (user) {
    try {
        let data = {};
        fn.setUserData(user);
        let platforms = await fn.platform(user);
        let platforms_data = await fn.getPlatformDatas(platforms);
        var c = new calculator_platform_heat_map.cphm(platforms, platforms_data, user);
        var ret = await c.get_platform_queue();
        data.results = ret;
        //console.log( 'calculator_platform_heat_map ' + JSON.stringify(ret) );
        return ret;
    } catch (err) {
        //console.log(err);
    }

};
 /* Ticket#192 @ checklist -3 end  */

module.exports.savePlatformData = function (platform_data) {
    return new promise(function (resolve, reject) {
        let save_req = {};
        save_req.request = platform_data;
        fn.savePlatformRequest(save_req);
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                /* Begin transaction */
                try {
                    connection.beginTransaction(function (err) {
                        let data = platform_data.platform_data;
                        let plat_data = platform_data.platform_data.data;
                        let platform_id = platform_data.platform_id;
                        let sql_stat = 0;
                        let vendor_obj = new vendor.vendorData(platform_data, platform_id);
                        switch (plat_data['status']) {
                            case 'rejected':
                                sql_stat = 0;
                                break;
                            case 'approved':
                                sql_stat = 1;
                                break;
                            case 'pending':
                                sql_stat = -1;
                                break;
                            default:
                                sql_stat = -1;
                        }
                        if (err) {
                            reject(err);
                        }
                        let sql = vendor_obj.platQuery(data);
                        connection.query(sql, async function (err, result) {
                            if (err) {
                                connection.rollback(function () {
                                    //console.log(err);
                                    reject(err);
                                });
                            }


                            //console.log('platform update ' + result);

                            let sql = await vendor_obj.platDataQuery(sql_stat);

                            connection.query(sql, async function (err, result) {
                                try {
                                    if (err) {
                                        connection.rollback(function () {
                                            reject(err);
                                            //console.log(err);

                                        });
                                    }
                                    if (_.isUndefined(result.insertId) && _.isUndefined(platform_data.plat_data_insert_id)) {

                                        throw new Error('Platform data id not found');
                                    } else if (result.insertId) {
                                        vendor_obj.platform_data.plat_data_insert_id = result.insertId;
                                    }
                                    let deleteSql = "Delete from platforms_data_charges where parent_id = ?";
                                    connection.query(deleteSql, platform_data.plat_data_insert_id, function (err, result) {
                                        if (err) {
                                            connection.rollback(function () {

                                                throw err;
                                            });

                                        }
                                        // Ticket 307 => changes will reflect over here
                                        let sql = "INSERT INTO platforms_data_charges (parent_id, platform_id, fee_type_id, fee_name, inv_type, calc_type, gia, isa, jisa, sipp, jsipp,onshore_bond, offshore_bond, lifetime_isa, interest_rate, tiered, aua_from, aua_to, vat, transfer_type, exclude, max_cap, time_held_from, time_held_to, status) VALUES";

                                        let plat_data = platform_data.platform_data.data;
                                        let last_id = platform_data.plat_data_insert_id;
                                        let platform_id = platform_data.platform_id;

                                        let feeSql = vendor_obj.feeSql(plat_data['platform_fees'], constant.FEE_TYPE_CUSTODY, sql_stat, platform_id, last_id);


                                        let retAnnCahrge = vendor_obj.feeSql(plat_data['platform_product_annual_chares'], constant.FEE_TYPE_PRODUCT_CHARGES, sql_stat, platform_id, last_id);
                                        feeSql += (feeSql !== '' && retAnnCahrge !== '') ? ',' + retAnnCahrge : retAnnCahrge;

                                        // RSPL TASK#37
                                        // Second parameter is set as 10 for FEE_TYPE_ID when storing CASH values
                                        let cashSql = vendor_obj.feeSql(plat_data['cash_fees'], 10, sql_stat, platform_id, last_id);
                                        feeSql += (feeSql !== '' && cashSql !== '') ? ',' + cashSql : cashSql;

                                        vendor_obj.feeSql(plat_data['platform_incentive_charges'], constant.FEE_TYPE_INCENTIVE, sql_stat, platform_id, last_id);
                                        let retInvCharge = vendor_obj.feeSql(plat_data['platform_investment_charges'], constant.FEE_TYPE_INVESTMENT_CHARGES, sql_stat, platform_id, last_id);

                                        feeSql += (feeSql !== '' && retInvCharge !== '') ? ',' + retInvCharge : retInvCharge;
                                        let retSippCharge = vendor_obj.feeSql(plat_data['platform_sipp_charges'], constant.FEE_TYPE_SIPP, sql_stat, platform_id, last_id);
                                        feeSql += (feeSql !== '' && retSippCharge !== '') ? ',' + retSippCharge : retSippCharge;
                                        let retClosureCharge = vendor_obj.feeSql(plat_data['platform_transfer_out_closure_charges'], constant.FEE_TYPE_TRANSFER_OUT, sql_stat, platform_id, last_id);

                                        feeSql += (feeSql !== '' && retClosureCharge !== '') ? ',' + retClosureCharge : retClosureCharge;
                                        let dealingCharge = vendor_obj.feeSql(plat_data['platform_dealing_fa_instruments_fees'], constant.FEE_TYPE_DEALING, sql_stat, platform_id, last_id);
                                        feeSql += (feeSql !== '' && dealingCharge !== '') ? ',' + dealingCharge : dealingCharge;
                                        let accOpenningCharge = vendor_obj.feeSql(plat_data['platform_transfer_account_opening_charges'], constant.FEE_TYPE_ACC_OPENNING, sql_stat, platform_id, last_id);
                                        feeSql += (feeSql !== '' && accOpenningCharge !== '') ? ',' + accOpenningCharge : accOpenningCharge;
                                        let incentiveCharge = vendor_obj.feeSql(plat_data['platform_incentive_charges'], constant.FEE_TYPE_INCENTIVE, sql_stat, platform_id, last_id);
                                        feeSql += (feeSql !== '' && incentiveCharge !== '') ? ',' + incentiveCharge : incentiveCharge;
                                        feeSql = sql + feeSql;
                                        console.info(feeSql);
                                        connection.query(feeSql, function (err, result) {
                                            if (err) {
                                                connection.rollback(function () {

                                                    throw err;
                                                });
                                            }

                                            connection.commit(function (err) {
                                                connection.release();
                                                if (err) {
                                                    connection.rollback(function () {

                                                        throw err;
                                                    });
                                                }
                                                console.log('Transaction Complete.');


                                                resolve('success');
                                            });

                                        });
                                    });


                                } catch (err) {

                                    connection.rollback(function () {
                                        console.log(err);


                                    });
                                    connection.release();
                                    return reject(err);
                                }

                            })


                        });

                    })
                } catch (err) {

                    connection.rollback(function () {
                        console.log(err);


                    });
                    connection.release();
                    return reject(err);
                }

            })
        })
    });

};
module.exports.updatePlatformStatus = function (data, platform_id, status, version_id) {
    return new promise(function (resolve, reject) {
        let platform = JSON.parse(data);
        let save_req = {};
        save_req.request = platform;
        fn.savePlatformRequest(save_req);
        if (_.isNull(status)) {
            return reject('No status found');
        }
        if (_.isNull(version_id)) {
            return reject('No version found');
        }
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }
///@todo add platform update
            pool.getConnection(function (err, connection) {

                /* Begin transaction */
                let vendor_obj = new vendor.vendorData(platform, platform_id);
                try {
                    connection.beginTransaction(async function (err) {

                        if (err) {
                            reject(err);
                        }
                        let sql_stat = -1;
                        switch (status) {
                            case 'rejected':
                                sql_stat = 0;
                                break;
                            case 'approved':
                                sql_stat = 1;
                                break;
                            case 'pending_approved':
                                sql_stat = 2;
                                break;
                            case 'pending':
                                sql_stat = -1;
                                break;
                            case 'delete':
                                sql_stat = -2;
                                break;
                            default:
                                sql_stat = -1;
                        }
                        if (sql_stat !== -2) {
                            let plat_sql = vendor_obj.platQuery(platform);

                            connection.query(plat_sql, async function (err, result) {
                                if (err) {
                                    connection.rollback(function () {
                                        console.log(err);
                                        reject(err);
                                    });
                                }
                                let platform_data_queries = vendor_obj.platDataStatusSql(platform_id, version_id, sql_stat);

                                connection.query(platform_data_queries.sql1, function (err, result) {
                                    if (err) {
                                        connection.rollback(function () {

                                            throw err;
                                        });
                                    }
                                    connection.query(platform_data_queries.sql2, function (err, result) {
                                            if (err) {
                                                connection.rollback(function () {

                                                    throw err;
                                                });
                                            }
                                            let parent_id = 0;
                                            if (_.isEmpty(result[0]) || result.length === 0) {
                                                connection.release();
                                                throw new Error("No platform data for the version");
                                            } else {
                                                parent_id = result[0].id;


                                            }
                                            connection.query(platform_data_queries.sql3, result[0].id, async function (err, result) {
                                                if (err) {
                                                    connection.rollback(function () {

                                                        throw err;
                                                    });
                                                }

                                                connection.commit(function (err) {
                                                    if (err) {
                                                        connection.rollback(function () {
                                                            reject(err);
                                                            throw err;
                                                        });
                                                    }
                                                    console.log('Transaction Complete.');


                                                    connection.release();
                                                    return resolve('success');
                                                });

                                            });


                                        }
                                    )
                                })
                            })
                        } else {
                            let sql1 = "select id from platforms_data where version = " + version_id + " and platform_id = " + platform_id;
                            let sql2 = "delete from platforms_data_charges where parent_id = ? and platform_id = " + platform_id;
                            let sql3 = "delete from platforms_data where id = ? ";

                            connection.query(sql1, function (err, result) {
                                if (err) {
                                    connection.rollback(function () {
                                        connection.release();
                                        throw err;
                                    });
                                }
                                let parent_id = 0;
                                if (_.isEmpty(result[0]) || result.length === 0) {

                                    throw new Error("No platform data for the version");
                                } else {
                                    parent_id = result[0].id;


                                }
                                connection.query(sql2, result[0].id, function (err, result) {
                                    if (err) {
                                        connection.rollback(function () {
                                            connection.release();
                                            throw err;
                                        });
                                    }
                                    connection.query(sql3, parent_id, function (err, result) {
                                        if (err) {
                                            connection.rollback(function () {
                                                connection.release();
                                                throw err;
                                            });
                                        }
                                        connection.commit(function (err) {
                                            if (err) {
                                                connection.rollback(function () {
                                                    connection.release();

                                                    throw err;
                                                });
                                            }
                                            console.log('Transaction Complete.');


                                            connection.release();
                                            return resolve('success');
                                        });
                                    });
                                });
                            });
                        }
                    })
                } catch
                    (err) {

                    connection.rollback();
                    connection.release();
                    return reject(err);

                }
            })
        })
    })
};
module.exports.getPlatform = function (platform, sandbox = 0) {
    let version_id = _.isUndefined(platform.version_id) ? false : platform.version_id;
    let sql = "Select * from  platforms where platform_id = ? and sandbox= ?";
    let ret = {};
    let plat_data_sql = "Select * from platforms_data where platform_id = ? and parent_id is null";
    let platform_id = platform.platform_id;
    let args = [platform.platform_id];
    if (version_id) {
        plat_data_sql += " and version = ?";
        args = [platform_id, version_id];
    }

    let plat_data_charges_sql = "Select * from platforms_data_charges where parent_id in (?) order by parent_id";
    return new Promise(function (resolve, reject) {
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }
            pool.getConnection(function (err, connection) {
                connection.query(sql, [platform.platform_id, sandbox], function (err, result) {
                    if (err) {

                        connection.release();
                        return reject(err);

                    }
                    if (_.isNull(result) || _.isEmpty(result)) {
                        return reject('Platform not found');
                    }
                    //console.log('result ' + result);
                    ret.platform = result;
                    connection.query(plat_data_sql, args, function (err, result) {
                        if (err) {
                            connection.release();

                            return reject(err);

                        }
                        let platform_data_all = result;
                        let parent_ids = _.pluck(result, 'id');
                        connection.query(plat_data_charges_sql, [parent_ids], function (err, result) {
                            connection.release();
                            if (err) {
                                return reject(err);
                            }
                            ret.platform_data = {};
                            _.each(platform_data_all, function (platform_data) {
                                ret.platform_data[platform_data.version] = platform_data;
                                let charges = _.where(result, {'parent_id': platform_data.id});
                                ret.platform_data[platform_data.version].charges = charges;
                                ret.platform_data[platform_data.version]['platform_fees'] = _.where(charges, {'fee_type_id': 1});
                                ret.platform_data[platform_data.version]['platform_product_annual_chares'] = _.where(charges, {'fee_type_id': 2});
                                ret.platform_data[platform_data.version]['platform_investment_charges'] = _.where(charges, {'fee_type_id': 3});
                                ret.platform_data[platform_data.version]['platform_dealing_fa_instruments_fees'] = _.where(charges, {'fee_type_id': 4});
                                ret.platform_data[platform_data.version]['acc_openning_fee'] = _.where(charges, {
                                    'fee_type_id': 5,
                                    'fee_name': 'Account opening fee'
                                }, true);
                                let opening_charges = _.where(charges, {'fee_type_id': 5});
                                if (!_.isEmpty(opening_charges)) {
                                    ret.platform_data[platform_data.version]['platform_transfer_account_opening_charges'] = _.filter(charges, function (fee) {
                                        return fee.fee_type_id === 5 && fee.fee_name !== 'Account opening fee'
                                    });
                                }
                                ret.platform_data[platform_data.version]['platform_transfer_out_closure_charges'] = _.where(charges, {'fee_type_id': 6});
                                ret.platform_data[platform_data.version]['platform_sipp_charges'] = _.where(charges, {'fee_type_id': 7});
                                ret.platform_data[platform_data.version]['platform_incentive_charges'] = _.where(charges, {'fee_type_id': 8});
                                //RSPL TASK#37
                                ret.platform_data[platform_data.version]['cash_fees'] = _.where(charges, {'fee_type_id': 10});
                            });

                            return resolve(ret);
                        })
                    })
                })
            });
        });
    });
};
module.exports.getPlatformVersionData = function (platform) {
    let version_id = _.isUndefined(platform.version_id) ? false : platform.version_id;
    let sql = "Select * from  platforms where platform_id = ?";
    let ret = {};
    let plat_data_sql = "Select *  from platforms_data where platform_id = ?";
    let platform_id = platform.platform_id;
    let args = [platform.platform_id];
    if (version_id) {
        plat_data_sql += " and version = ?";
        args = [platform_id, version_id];
    }
    plat_data_sql += " order by version desc";


    return new Promise(function (resolve, reject) {
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }
            pool.getConnection(function (err, connection) {
                connection.query(sql, platform.platform_id, function (err, result) {
                    if (err) {

                        connection.release();
                        return reject(err);

                    }
                    if(_.isEmpty(result)){
                        return reject(new Error('No platform found'))
                    }
                    ret.platform = result;
                    connection.query(plat_data_sql, args, function (err, result) {
                        connection.release();
                        if (err) {
                            return reject(err);
                        }
                        if(!_.isEmpty(result)) {

                            let platform_data_all = result;

                            ret.platform_data = {};
                            _.each(platform_data_all, function (platform_data) {
                                ret.platform_data[platform_data.version] = platform_data;



                            });
                        }
                        return resolve(ret);
                    })
                });
            });
        });
    });
};
module.exports.getMaxVersion = function (platform) {
    return new Promise(function (resolve, reject) {
        let platform_id = platform.platform_id;
        let sql = "Select max(version) as version from platforms_data where platform_id = ?";

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {
                if (err) {

                    console.log(err);
                    return reject(err);

                }

                connection.query(sql, platform_id, function (err, result) {
                    connection.release();
                    if (err) {

                        console.log(err);
                        return reject(err);

                    }
                    console.log('result1:' + result);
                    if (!_.isEmpty(result)) {
                        result = result.shift();
                    }

                    return resolve(result);

                });
            });


        });

    })

};
module.exports.addPlatform = function (platform_data) {
    let $this = this;
    return new Promise(function (resolve, reject) {
        platform_data = JSON.parse(platform_data);

        let platform = _.defaults(platform_data.platform, constant.platform_defaults);
        let platform_id = platform.platform_id;
        let plat_data = platform_data.platform_data;

        plat_data.seq = 1;
        let ret = {};
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {
                if (err) {

                    console.log(err);
                    return reject(err);

                }
                let sql = "START TRANSACTION";
                connection.query(sql, async function (err, result) {

                        if (err) {
                            connection.release();
                            console.log(err);
                            return reject(err);

                        }
                        let sql_values = platform_id + ", ";


                        if (platform.sandbox == 1) {
                            let key = platform.key;
                            try {
                                let seq = await $this.getNextSandboxSequence(platform.platinum_vendor_id, platform.platform_id);
                                plat_data.seq = seq;
                            } catch (err) {
                                connection.release();
                                console.log(err);
                                return reject(err);
                            }
                            if (plat_data.seq > 10) {
                                return reject("Not allowed to have more than 10 sandboxes");


                            }
                            platform.platform_name = "Scenario " + plat_data.seq;
                            sql_values = sql_values + "AES_ENCRYPT('" + platform.platform_name + "', '" + platform.key + "'), ";
                            sql_values = sql_values + "AES_ENCRYPT('" + platform.info_url + "', '" + platform.key + "'), ";

                        } else {
                            sql_values = sql_values + "'" + platform.platform_name + "', ";
                            sql_values = sql_values + "'" + platform.info_url + "' , ";
                        }
                        sql_values = sql_values + platform.platform_type + ", ";
                        sql_values = sql_values + platform.calculation_method + ", ";
                        sql_values = sql_values + platform.recommended + ", ";


                        sql_values = sql_values + "'" + platform.img + "', ";
                        sql_values = sql_values + platform.rating + ", ";
                        sql_values = sql_values + "'" + platform.url + "', ";
                        sql_values = sql_values + platform.sandbox+ ", ";
                        sql_values = sql_values + platform.published;

                        let sql_statement = "INSERT INTO platforms (platform_id, platform_name, info_url, platform_type, calculation_method, recommended, img, rating, url, sandbox, published)" +
                            " VALUES (" + sql_values + ");";

                        connection.query(sql_statement, function (err, result) {

                            if (err) {
                                connection.release();
                                console.log(err);
                                return reject(err);

                            }
                            //console.log('result1:' + result);
                            if (platform.sandbox == 1) {
                                platform_id = result.insertId;
                            } else {
                                platform_id = null;
                            }
                            if (result.insertId) {
                                ret.platform_id = result.insertId;
                                /*
            **********************************************
            ****  Platform Data
            **********************************************
            */

                                let sql_values = "";
                                sql_values = platform.platform_id + ", ";
                                sql_values = sql_values + plat_data['version'] + ", ";
                                let active_from = new Date(plat_data['active_from']);
                                let active_from_formatted = fn.getDateFormat(active_from);
                                sql_values = sql_values + "'" + active_from_formatted + "', ";
                                let active_to = new Date(plat_data['active_to']);
                                let active_to_formatted = fn.getDateFormat(active_to);
                                sql_values = sql_values + "'" + active_to_formatted + "', ";
                                
                                // ticket 336
                                let ethical_investment = fn.isset(plat_data['ethical_investment']) ? plat_data['ethical_investment'] : 0;
                                sql_values = sql_values + parseInt(ethical_investment) + ", ";

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

                                let custody_fee_min = fn.isset(plat_data['custody_fees_min']) && !_.isNull(plat_data['custody_fees_min']) ? parseFloat(plat_data['custody_fees_min']) : null;
                                sql_values = sql_values + custody_fee_min + ", ";
                                let custody_fee_max = fn.isset(plat_data['custody_fees_max']) && !_.isNull(plat_data['custody_fees_max']) ? parseFloat(plat_data['custody_fees_max']) : null;
                                sql_values = sql_values + custody_fee_max + ", ";
                                //RSPL Task#73 Starts
                                let cash_custody_fees_min = fn.isset(plat_data['cash_custody_fees_min']) && !_.isNull(plat_data['cash_custody_fees_min']) ? parseFloat(plat_data['cash_custody_fees_min']) : null;
                                sql_values = sql_values + cash_custody_fees_min + ", ";
                                let cash_custody_fees_max = fn.isset(plat_data['cash_custody_fees_max']) && !_.isNull(plat_data['cash_custody_fees_max']) ? parseFloat(plat_data['cash_custody_fees_max']) : null;
                                sql_values = sql_values + cash_custody_fees_max + ", ";
                                //RSPL Task#73 Ends
                                let funds_custody_fees_min = fn.isset(plat_data['funds_custody_fees_min']) && !_.isNull(plat_data['funds_custody_fees_min']) ? parseFloat(plat_data['funds_custody_fees_min']) : null;
                                sql_values = sql_values + funds_custody_fees_min + ", ";
                                let funds_custody_fees_max = fn.isset(plat_data['funds_custody_fees_max']) && !_.isNull(plat_data['funds_custody_fees_max']) ? parseFloat(plat_data['funds_custody_fees_max']) : null;
                                sql_values = sql_values + funds_custody_fees_max + ", ";
                                let ex_instruments_custody_fees_min = fn.isset(plat_data['ex_instruments_custody_fees_min']) && !_.isNull(plat_data['ex_instruments_custody_fees_min']) ? parseFloat(plat_data['ex_instruments_custody_fees_min']) : null;
                                sql_values = sql_values + ex_instruments_custody_fees_min + ", ";
                                let ex_instruments_custody_fees_max = fn.isset(plat_data['ex_instruments_custody_fees_max']) && !_.isNull(plat_data['ex_instruments_custody_fees_max']) ? parseFloat(plat_data['ex_instruments_custody_fees_max']) : null;
                                sql_values = sql_values + ex_instruments_custody_fees_max + ", ";
                                let product_annual_charge_min = fn.isset(plat_data['product_annual_charge_min']) && !_.isNull(plat_data['product_annual_charge_min']) ? parseFloat(plat_data['product_annual_charge_min']) : null;
                                sql_values = sql_values + product_annual_charge_min + ", ";
                                let product_annual_charge_max = fn.isset(plat_data['product_annual_charge_max']) && !_.isNull(plat_data['product_annual_charge_max']) ? parseFloat(plat_data['product_annual_charge_max']) : null;
                                sql_values = sql_values + product_annual_charge_max + ", ";

                                let date_created = new Date();
                                let date_created_formatted = fn.getDateFormat(date_created);
                                sql_values = sql_values + "'" + date_created_formatted + "', ";

                                sql_values = sql_values + plat_data.rec_status + ", ";

                                sql_values = sql_values + platform_id;

                                //RSPL Task#73
                                sql_statement = "INSERT INTO platforms_data (platform_id, version, active_from, active_to, ethical_investment, sup_fund_gia, sup_fund_isa, sup_fund_jisa, sup_fund_sipp, sup_fund_jsipp,sup_fund_onshore_bond,sup_fund_offshore_bond,sup_fund_lifetime_isa," +
                                    " sup_ex_gia, sup_ex_isa, sup_ex_jisa, sup_ex_sipp, sup_ex_jsipp,sup_ex_onshore_bond,sup_ex_offshore_bond, sup_ex_lifetime_isa," +
                                    " all_cust_fee_min, all_cust_fee_max, cash_cust_fee_min, cash_cust_fee_max, fund_cust_fee_min, " +
                                    "fund_cust_fee_max, ex_cust_fee_min, ex_cust_fee_max, ann_admin_fee_min, ann_admin_fee_max, date_created,rec_status, parent_id) " +
                                    "VALUES (" + sql_values + ");";

                                /*sql_statement = "INSERT INTO platforms_data (platform_id, version, active_from, active_to, sup_fund_gia, sup_fund_isa, sup_fund_jisa, sup_fund_sipp, sup_fund_jsipp,sup_fund_onshore_bond,sup_fund_offshore_bond," +
                                    " sup_ex_gia, sup_ex_isa, sup_ex_jisa, sup_ex_sipp, sup_ex_jsipp,sup_ex_onshore_bond,sup_ex_offshore_bond," +
                                    " all_cust_fee_min, all_cust_fee_max, fund_cust_fee_min, " +
                                    "fund_cust_fee_max, ex_cust_fee_min, ex_cust_fee_max, ann_admin_fee_min, ann_admin_fee_max, date_created,rec_status, parent_id) " +
                                    "VALUES (" + sql_values + ");";*/
                                connection.query(sql_statement, function (err, result) {
                                    if (platform.sandbox == 1) {

                                        let sql = "Insert into sandbox_users(token, user_id, platform_id, sandbox_id, sequence_id) values(?,?,?,?,?)";

                                        connection.query(sql, [platform.key, platform.platinum_vendor_id, platform.platform_id, platform_id, plat_data.seq], function (err, result) {
                                            if (err) {
                                                connection.rollback();
                                                connection.release();
                                                console.log(err);
                                                return reject(err);

                                            }
                                        })
                                    }

                                    if (err) {
                                        connection.rollback();
                                        connection.release();
                                        console.log(err);
                                        return reject(err);

                                    }

                                    connection.commit();
                                    connection.release();
                                    ret.message = 'success';
                                    return resolve(ret);
                                });
                            } else {
                                connection.rollback();
                                connection.release();
                            }
                        });
                    }
                );
            });


        });

    });

};

var sanitize_data = function (user) {
    user.total_savings_and_investments = parseInt(user.total_savings_and_investments);
    //RSPL TASK#22
    user.total_savings_and_investments_total = parseInt(user.total_savings_and_investments_total);
    user.total_savings_and_investments_cash = parseInt(user.total_savings_and_investments_cash);
    user.total_shares = parseInt(user.total_shares);
    return user;
};


module.exports.delete_sandbox_charges = function (ids) {
    return new Promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }

                let sql = "Delete from platforms_data_charges where ids in(?)";
                connection.query(sql, ids, function (err, result) {
                    connection.release();
                    if (err) {
                        connection.rollback(function () {
                            reject(err);
                            console.log(err);

                        });
                    }
                });
            });
        })
    })
};

module.exports.getSandboxIds = function (user_id, platform_id) {

    return new Promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }

                let sql = "Select * from sandbox_users where user_id = ? and platform_id = ? order by created_at asc";
                connection.query(sql, [user_id, platform_id], function (err, result) {
                    connection.release();
                    if (err) {
                        console.log(err);
                        reject(err);


                    }
                    return resolve(result);
                });
            });
        })
    })

};
module.exports.getNextSandboxSequence = function (user_id, platform_id) {
    let sql = "Select * from sandbox_users where user_id = ? and platform_id = ?";
    return new Promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }

                let sql = "Select sequence_id from sandbox_users where user_id = ? and platform_id = ? order by sequence_id asc";
                connection.query(sql, [user_id, platform_id], function (err, result) {
                    connection.release();
                    if (err) {

                        reject(err);
                        console.log(err);
                    }
                    let ret = 1;

                    if (!_.isEmpty(result)) {
                        let seq_obj = _.pluck(result, 'sequence_id');
                        if (_.size(seq_obj) > 10) {
                            return reject('Not allowed to have more than 10 sandboxes')
                        }
                        ret = _.max(seq_obj);
                        ret += 1;
                        for (var i = 0; i < 11; i++) {
                            if ((seq_obj[i] !== i + 1)) {
                                ret = i + 1;
                                break;
                            }
                        }


                    }

                    return resolve(ret);
                });
            });
        })
    })

};
module.exports.getSandboxData = function (sandbox_id) {

    let sql = "Select `token` from  sandbox_users where sandbox_id = ?";
    let ret = {};

    let plat_data_sql = "SELECT d.platform_id, cast(aes_decrypt(p.platform_name, ?) as char) platform_name, d.id, p.id sandbox_id, d.version version_id, p.platform_type, p.calculation_method, p.recommended, p.rating, p.info_url, p.url, p.img," +
        "d.sup_fund_gia gia_supported, d.sup_fund_isa isa_supported, d.sup_fund_jisa jisa_supported,d.sup_fund_sipp sipp_supported, d.sup_fund_jsipp childsipp_supported, d.sup_fund_lifetime_isa lifetime_isa_supported," +
        " d.sup_ex_gia ex_instruments_gia_supported, d.sup_ex_isa ex_instruments_isa_supported, d.sup_ex_jisa ex_instruments_jisa_supported,d.sup_ex_sipp ex_instruments_sipp_supported, d.sup_ex_jsipp ex_instruments_childsipp_supported, d.sup_ex_lifetime_isa ex_instruments_lifetime_isa_supported," +
        " d.active_from, d.active_to, d.all_cust_fee_min custody_fees_min, d.all_cust_fee_max custody_fees_max, d.cash_cust_fee_min cash_custody_fees_min, d.cash_cust_fee_max cash_custody_fees_max, d.fund_cust_fee_min funds_custody_fees_min, d.fund_cust_fee_max funds_custody_fees_max," +
        " d.ex_cust_fee_min ex_instruments_custody_fees_min, d.ex_cust_fee_max ex_instruments_custody_fees_max, d.ann_admin_fee_min product_annual_charge_min, d.ann_admin_fee_max product_annual_charge_max, d.ann_admin_amount" +
        " FROM platforms p, platforms_data d WHERE  p.id = d.parent_id and p.id = ? and sandbox = 1";

    /*let plat_data_sql = "SELECT d.platform_id, cast(aes_decrypt(p.platform_name, ?) as char) platform_name, d.id, p.id sandbox_id, d.version version_id, p.platform_type, p.calculation_method, p.recommended, p.rating, p.info_url, p.url, p.img," +
        "d.sup_fund_gia gia_supported, d.sup_fund_isa isa_supported, d.sup_fund_jisa jisa_supported,d.sup_fund_sipp sipp_supported, d.sup_fund_jsipp childsipp_supported," +
        " d.sup_ex_gia ex_instruments_gia_supported, d.sup_ex_isa ex_instruments_isa_supported, d.sup_ex_jisa ex_instruments_jisa_supported,d.sup_ex_sipp ex_instruments_sipp_supported, d.sup_ex_jsipp ex_instruments_childsipp_supported," +
        " d.active_from, d.active_to, d.all_cust_fee_min custody_fees_min, d.all_cust_fee_max custody_fees_max, d.fund_cust_fee_min funds_custody_fees_min, d.fund_cust_fee_max funds_custody_fees_max," +
        " d.ex_cust_fee_min ex_instruments_custody_fees_min, d.ex_cust_fee_max ex_instruments_custody_fees_max, d.ann_admin_fee_min product_annual_charge_min, d.ann_admin_fee_max product_annual_charge_max, d.ann_admin_amount" +
        " FROM platforms p, platforms_data d WHERE  p.id = d.parent_id and p.id = ? and sandbox = 1";*/
    let plat_data_charges_sql = "SELECT  `id`,`parent_id`,`platform_id`,`fee_type_id`,`fee_name`, inv_type , `calc_type`,`gia`,`isa`,`jisa`,`sipp`,`jsipp`,`lifetime_isa`,`interest_rate` ,`tiered`,`aua_from`,`aua_to`,`vat` FROM  platforms_data_charges where parent_id = ? ";
    return new Promise(function (resolve, reject) {
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }
            pool.getConnection(function (err, connection) {
                connection.query(sql, sandbox_id, function (err, result) {
                    if (err) {

                        connection.release();
                        return reject(err);

                    }
                    if (_.isNull(result) || _.isEmpty(result)) {
                        return reject('Platform not found');
                    }
                    //console.log('result ' + result);
                    let row = result.shift();
                    connection.query(plat_data_sql, [row.token, sandbox_id], function (err, result) {
                        if (err) {
                            connection.release();

                            return reject(err);

                        }
                        if (_.isNull(result) || _.isEmpty(result)) {
                            return reject('Platform data not found');
                        }
                        let platform_data_all = result.shift();
                        ret = platform_data_all;
                        let parent_ids = platform_data_all.id;
                        connection.query(plat_data_charges_sql, parent_ids, function (err, result) {
                            connection.release();
                            if (err) {
                                return reject(err);
                            }
                            try {
                                ret.platform_data = {};
                                if (!_.isEmpty(result)) {
                                    ret['platform_fees'] = _.where(result, {'fee_type_id': 1});
                                    ret['platform_product_annual_chares'] = _.where(result, {'fee_type_id': 2});
                                    ret['platform_investment_charges'] = _.where(result, {'fee_type_id': 3});
                                    ret['platform_dealing_fa_instruments_fees'] = _.where(result, {'fee_type_id': 4});
                                    ret['acc_openning_fee'] = _.where(result, {
                                        'fee_type_id': 5,
                                        'fee_name': 'Account opening fee'
                                    }, true);
                                    let opening_charges = _.where(result, {'fee_type_id': 5});
                                    if (!_.isEmpty(opening_charges)) {
                                        ret['platform_transfer_account_opening_charges'] = _.filter(result, function (fee) {
                                            return fee.fee_type_id === 5 && fee.fee_name !== 'Account opening fee'
                                        });
                                    }
                                    ret['platform_transfer_out_closure_charges'] = _.where(result, {'fee_type_id': 6});
                                    ret['platform_sipp_charges'] = _.where(result, {'fee_type_id': 7});
                                    //RSPL TASK#37
                                    ret['cash_fees'] = _.where(result, {'fee_type_id': 10});


                                }
                                //console.log('debug sand' + ret);

                            } catch (err) {
                                return reject(err);
                            }
                            return resolve(ret);
                        })
                    })
                })
            });
        });
    });
};
module.exports.deleteSandbox = function (sandbox_id) {
    return new Promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {
                let sandbox_sql = "select id from platforms where id = " + sandbox_id + " and sandbox = 1";

                connection.beginTransaction(function () {
                    if (err) {
                        connection.rollback();
                        connection.release();
                        return reject(err);

                    }

                    connection.query(sandbox_sql, sandbox_id, function (err, result) {
                        if (err) {
                            connection.rollback();
                            connection.release();
                            return reject(err);

                        }
                        if (_.isNull(result) || _.isEmpty(result)) {
                            return reject('Sandbox not found');
                        }
                        //console.log('result ' + result);
                        let row = result.shift();
                        let platform_data_sql = "select id from platforms_data where parent_id = ?";
                        connection.query(platform_data_sql, row.id, function (err, result) {
                            if (err) {
                                connection.release();
                                return reject(err);

                            }
                            if (_.isNull(result) || _.isEmpty(result)) {
                                connection.rollback();
                                connection.release();
                                return reject('Sandbox data not found');
                            }
                            let row = result.shift();

                            let plat_charge_delete_sql = "Delete from platforms_data_charges where parent_id = ? ";
                            connection.query(plat_charge_delete_sql, row.id, function (err, result) {
                                if (err) {
                                    connection.rollback();
                                    connection.release();
                                    return reject(err);

                                }
                                let plat_data_delete_sql = "Delete from platforms_data where parent_id = ? ";
                                connection.query(plat_data_delete_sql, sandbox_id, function (err, result) {
                                    if (err) {
                                        connection.rollback();
                                        connection.release();
                                        return reject(err);

                                    }

                                    let sandbox_delete_sql = "Delete from sandbox_users where sandbox_id = ? ";
                                    connection.query(sandbox_delete_sql, sandbox_id, function (err, result) {
                                        if (err) {
                                            connection.rollback();
                                            connection.release();
                                            return reject(err);

                                        }
                                        let plat_delete_sql = "Delete from platforms where id = ?  and sandbox = 1";
                                        connection.query(plat_delete_sql, sandbox_id, function (err, result) {
                                            if (err) {
                                                connection.rollback();
                                                connection.release();
                                                return reject(err);

                                            }
                                            connection.commit();
                                            connection.release();
                                            resolve('success');
                                        })
                                    })
                                })
                            })
                        });
                    })
                })
            })
        })
    })
};
module.exports.addUpdateSandboxCharges = function (data) {
    return new Promise(function (resolve, reject) {
        data = JSON.parse(data);
        let return_val = {};
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {
                if (err) {
                    return reject(err);
                }
                let plat_data = data;
                let active_from = new Date(plat_data['active_from']);
                let active_from_formatted = fn.getDateFormat(active_from);

                let active_to = new Date(plat_data['active_to']);
                let active_to_formatted = fn.getDateFormat(active_to);

                let ethical_investment = fn.isset(plat_data['ethical_investment']) ? plat_data['ethical_investment'] : 0;
                let gia = fn.isset(plat_data['gia_supported']) ? plat_data['gia_supported'] : 0;

                let isa = fn.isset(plat_data['isa_supported']) ? plat_data['isa_supported'] : 0;

                let jisa = fn.isset(plat_data['jisa_supported']) ? plat_data['jisa_supported'] : 0;

                let sipp = fn.isset(plat_data['sipp_supported']) ? plat_data['sipp_supported'] : 0;

                let jsipp = fn.isset(plat_data['childsipp_supported']) ? plat_data['childsipp_supported'] : 0;

                let onshore = fn.isset(plat_data['onshore_supported']) ? plat_data['onshore_supported'] : 0;

                let offshore = fn.isset(plat_data['offshore_supported']) ? plat_data['offshore_supported'] : 0;

                let lifetime_isa = fn.isset(plat_data['lifetime_isa_supported']) ? plat_data['lifetime_isa_supported'] : 0;

                let ex_gia = fn.isset(plat_data['ex_instruments_gia_supported']) ? plat_data['ex_instruments_gia_supported'] : 0;

                let ex_isa = fn.isset(plat_data['ex_instruments_isa_supported']) ? plat_data['ex_instruments_isa_supported'] : 0;

                let ex_jisa = fn.isset(plat_data['ex_instruments_jisa_supported']) ? plat_data['ex_instruments_jisa_supported'] : 0;

                let ex_sipp = fn.isset(plat_data['ex_instruments_sipp_supported']) ? plat_data['ex_instruments_sipp_supported'] : 0;

                let ex_jsipp = fn.isset(plat_data['ex_instruments_childsipp_supported']) ? plat_data['ex_instruments_childsipp_supported'] : 0;

                let ex_onshore = fn.isset(plat_data['ex_instruments_onshore_supported']) ? plat_data['ex_instruments_onshore_supported'] : 0;

                let ex_offshore = fn.isset(plat_data['ex_instruments_offshore_supported']) ? plat_data['ex_instruments_offshore_supported'] : 0;

                let ex_lifetime_isa = fn.isset(plat_data['ex_instruments_lifetime_isa_supported']) ? plat_data['ex_instruments_lifetime_isa_supported'] : 0;

                let custody_fee_min = fn.isset(plat_data['custody_fees_min']) ? parseFloat(plat_data['custody_fees_min']) : null;

                let custody_fee_max = fn.isset(plat_data['custody_fees_max']) ? parseFloat(plat_data['custody_fees_max']) : null;

                //RSPL Task#73 Starts
                let cash_custody_fees_min = fn.isset(plat_data['cash_custody_fees_min']) ? parseFloat(plat_data['cash_custody_fees_min']) : null;

                let cash_custody_fees_max = fn.isset(plat_data['cash_custody_fees_max']) ? parseFloat(plat_data['cash_custody_fees_max']) : null;
                //RSPL Task#73 Ends

                let funds_custody_fees_min = fn.isset(plat_data['funds_custody_fees_min']) ? parseFloat(plat_data['funds_custody_fees_min']) : null;

                let funds_custody_fees_max = fn.isset(plat_data['funds_custody_fees_max']) ? parseFloat(plat_data['funds_custody_fees_max']) : null;

                let ex_instruments_custody_fees_min = fn.isset(plat_data['ex_instruments_custody_fees_min']) ? parseFloat(plat_data['ex_instruments_custody_fees_min']) : null;

                let product_annual_charge_min = fn.isset(plat_data['product_annual_charge_min']) ? parseFloat(plat_data['product_annual_charge_min']) : null;

                let ex_instruments_custody_fees_max = fn.isset(plat_data['ex_instruments_custody_fees_max']) ? parseFloat(plat_data['ex_instruments_custody_fees_max']) : null;

                let product_annual_charge_max = fn.isset(plat_data['product_annual_charge_max']) ? parseFloat(plat_data['product_annual_charge_max']) : null;

                let incentive_charge_max = fn.isset(plat_data['incentive_max']) ? parseFloat(plat_data['incentive_max']) : null;

                let incentive_charge_min = fn.isset(plat_data['incentive_min']) ? parseFloat(plat_data['incentive_min']) : null;

                let dealing_fee_credits = fn.isset(plat_data['dealing_fee_credits']) ? plat_data['dealing_fee_credits'] : 0;

                let vat_val = fn.isset(plat_data['vat_val']) ? plat_data['vat_val'] : 20;

                let date_created = new Date(plat_data['date_created']);

                let date_created_formatted = fn.getDateFormat(date_created);

                let sql_statement = "Update  platforms_data set  active_from = '" + active_from_formatted + "', active_to = '"
                    + active_to_formatted + "', ethical_investment = '" + ethical_investment + "', sup_fund_gia = " + gia +
                    ", sup_fund_isa = " + isa +
                    ", sup_fund_jisa = " + jisa +
                    ", sup_fund_sipp = " + sipp +
                    ", sup_fund_jsipp=" + jsipp +
                    ", sup_fund_jsipp=" + onshore +
                    ", sup_fund_jsipp=" + offshore +
                    ", sup_fund_lifetime_isa=" + lifetime_isa +
                    ", sup_ex_gia = " + ex_gia +
                    ", sup_ex_isa = " + ex_isa +
                    ", sup_ex_jisa = " + ex_jisa +
                    ", sup_ex_sipp = " + ex_sipp +
                    ", sup_ex_jsipp= " + ex_jsipp +
                    ", sup_ex_onshore_bond= " + ex_onshore +
                    ", sup_ex_offshore_bond= " + ex_offshore +
                    ", sup_ex_lifetime_isa= " + ex_lifetime_isa +
                    ", all_cust_fee_min = " + custody_fee_min +
                    ", all_cust_fee_max = " + custody_fee_max +
                    ", cash_cust_fee_min = " + cash_custody_fees_min +
                    ", cash_cust_fee_max = " + cash_custody_fees_max +
                    ", fund_cust_fee_min = " + funds_custody_fees_min +
                    ", fund_cust_fee_max = " + funds_custody_fees_max +
                    ", ex_cust_fee_min = " + ex_instruments_custody_fees_min +
                    ", ex_cust_fee_max = " + ex_instruments_custody_fees_max +
                    ", ann_admin_fee_min = " + product_annual_charge_min +
                    ", ann_admin_fee_max = " +
                    product_annual_charge_max +
                    ", dealing_fee_credits =" + dealing_fee_credits +
                    ", incentive_min = " + incentive_charge_min +
                    ", incentive_max = " + incentive_charge_max +
                    ", vat_val = " + vat_val +
                    ", date_created = '" + date_created_formatted + "', rec_status = 1 " +
                    " where parent_id = " + data.sandbox_id;

                connection.query(sql_statement, function (err, result) {

                        if (err) {

                            connection.release();
                            return reject(err);

                        }
                        let sql = "Select id from platforms_data where parent_id = ?";
                        if (!_.isEmpty(data.charges)) {
                            let charges_exist = false;
                            connection.query(sql, data.sandbox_id, async function (err, result) {

                                if (err) {

                                    connection.release();
                                    return reject(err);

                                }
                                if (_.isEmpty(result)) {
                                    return reject('Platform data not found');
                                }
                                let res = result.shift();
                                let parent_id = res.id;
                                let charges = data.charges;
                                let sandbox_id = data.sandbox_id;

                                let sql = "INSERT INTO platforms_data_charges ( id, parent_id, platform_id, fee_type_id, fee_name, inv_type, calc_type, gia, isa, jisa, sipp, jsipp, onshore_bond, offshore_bond, lifetime_isa, interest_rate, tiered, aua_from, aua_to, vat, transfer_type, exclude, max_cap, time_held_from, sandbox_id, status) VALUES ";


                                let count = _.size(charges);
                                let charges_deleted = '';
                                
                                if (count > 0) {


                                    let i = 0;
                                    if (!_.isNull(charges) && !_.isNull(charges.deleted) && !_.isUndefined(charges.deleted)) {
                                        charges_deleted += charges.deleted;
                                        delete charges.deleted;
                                        count -= 1;

                                    }


                                    _.each(charges, function (tier, k) {
                                        if (fn.isset(tier)) {
                                            charges_exist = true;
                                            let sql_values = "";
                                            let fee_id = _.isUndefined(tier['fee_id']) ? null : tier['fee_id'];
                                            let fee_name = _.isUndefined(tier['fee_name']) ? '' : tier['fee_name'];
                                            let inv_type = _.isUndefined(tier['investment_type']) || !fn.isset(tier['investment_type']) ? null : tier['investment_type'];
                                            let fee_type_id = !fn.isset(tier['fee_type_id']) ? null : tier['fee_type_id'];
                                            sql_values = sql_values + fee_id + ", ";
                                            sql_values = sql_values + parent_id + ", ";
                                            sql_values = sql_values + sandbox_id + ", ";
                                            sql_values = sql_values + fee_type_id + ", ";
                                            sql_values = sql_values + "'" + fee_name + "', ";
                                            sql_values = sql_values + inv_type + ", ";
                                            if (fee_name === 'Account opening fee') {
                                                tier['type'] = 5;
                                            }
                                            sql_values = sql_values + tier['type'] + ", ";
                                            let gia = fn.isset(tier['gia']) ? _.isNumber(tier['gia']) ? tier['gia'] : fn.sanitize_num(tier['gia']) : 0;
                                            let isa = fn.isset(tier['isa']) ? _.isNumber(tier['isa']) ? tier['isa'] : fn.sanitize_num(tier['isa']) : 0;
                                            let jisa = fn.isset(tier['jisa']) ? _.isNumber(tier['jisa']) ? tier['jisa'] : fn.sanitize_num(tier['jisa']) : 0;
                                            let sipp = fn.isset(tier['sipp']) ? _.isNumber(tier['sipp']) ? tier['sipp'] : fn.sanitize_num(tier['sipp']) : 0;
                                            let jsipp = fn.isset(tier['childsipp']) ? _.isNumber(tier['childsipp']) ? tier['childsipp'] : fn.sanitize_num(tier['childsipp']) : 0;
                                            let onshore_bond = fn.isset(tier['onshore_bond']) ? _.isNumber(tier['onshore_bond']) ? tier['onshore_bond'] : fn.sanitize_num(tier['onshore_bond']) : 0;
                                            let offshore_bond = fn.isset(tier['offshore_bond']) ? _.isNumber(tier['offshore_bond']) ? tier['offshore_bond'] : fn.sanitize_num(tier['offshore_bond']) : 0;
                                            let lifetime_isa = fn.isset(tier['lifetime_isa']) ? _.isNumber(tier['lifetime_isa']) ? tier['lifetime_isa'] : fn.sanitize_num(tier['lifetime_isa']) : 0;
                                            let interest_rate = fn.isset(tier['interest_rate']) ? _.isNumber(tier['interest_rate']) ? tier['interest_rate'] : fn.sanitize_num(tier['interest_rate']) : 0;
                                            let tiered = fn.isset(tier['tiered']) ? _.isNumber(tier['tiered']) ? tier['tiered'] : fn.sanitize_num(tier['tiered']) : 0;
                                            let aua_from = fn.isset(tier['aua_from']) ? _.isNumber(tier['aua_from']) ? tier['aua_from'] : fn.sanitize_num(tier['aua_from']) : null;
                                            let aua_to = fn.isset(tier['aua_to']) ? fn.sanitize_num(tier['aua_to']) : null;
                                            let vat = fn.isset(tier['vat']) ? fn.sanitize_num(tier['vat']) : 0;
                                            let exclude = fn.isset(tier['exclude']) ? parseInt(fn.sanitize_num(tier['exclude'])) : null;
                                            let max_cap = fn.isset(tier['max_cap']) ? parseFloat(fn.sanitize_num(tier['max_cap'])) : null;
                                            let time_held_from = fn.isset(tier['time_held_from']) ? parseInt(fn.sanitize_num(tier['time_held_from'])) : null;
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
                                            sql_values = sql_values + sandbox_id +
                                                ", ";
                                            sql_values = sql_values + 1;


                                            sql += "(" + sql_values + ")";
                                            if (i < count - 1) {
                                                sql += ',';
                                            }
                                        }
                                        i++;


                                    });


                                }
                                sql += "ON DUPLICATE KEY UPDATE `fee_name` = values(fee_name),\n" +
                                    "`inv_type` = values(inv_type),\n" +
                                    "`calc_type` = values(calc_type ),\n" +
                                    "`time_held_from` = values(time_held_from ),\n" +
                                    "`max_cap` = values(max_cap),\n" +
                                    "`exclude` = values(exclude),\n" +
                                    "`transfer_type` = values(transfer_type),\n" +
                                    "`gia` = values(gia),\n" +
                                    "`isa` = values(isa),\n" +
                                    "`jisa` = values(jisa),\n" +
                                    "`sipp` = values(sipp),\n" +
                                    "`jsipp` = values(jsipp),\n" +
                                    "`onshore_bond` = values(onshore_bond),\n" +
                                    "`offshore_bond` = values(offshore_bond),\n" +
                                    "`lifetime_isa` = values(lifetime_isa),\n" +
                                    "`interest_rate` = values(interest_rate),\n" +
                                    "`tiered` = values(tiered),\n" +
                                    "`aua_from` = values(aua_from),\n" +
                                    "`aua_to` = values(aua_to),\n" +
                                    "`sandbox_id` = values(sandbox_id),\n" +
                                    "`vat` = values(vat)";
                                console.info('charge_sql  ' + sql);
                                if (charges_exist) {
                                    connection.query(sql, function (err, result) {

                                        if (err) {

                                            return reject(err);

                                        }

                                        let delete_ids = '';

                                        if (charges_deleted !== "") {
                                            delete_ids += charges_deleted.split('|');
                                        }

                                        if (delete_ids !== '') {
                                            delete_ids = delete_ids.replace(/\,*$/, '');
                                            let delete_sql = "delete from platforms_data_charges where id in(" + delete_ids + ")";
                                            connection.query(delete_sql, function (err, result) {
                                                connection.release();
                                                if (err) {


                                                    return reject(err);

                                                }

                                                console.info("charges deleted" + delete_ids);
                                                console.info("charges deleted result " + result);

                                                return_val.message = 'success';
                                                return resolve(return_val);
                                            });

                                        } else {
                                            connection.release();
                                            return_val.message = 'success';
                                            return resolve(return_val);
                                        }
                                    });
                                } else {
                                    connection.release();
                                    return_val.message = 'success';
                                    return resolve(return_val);
                                }


                            });

                        } else {
                            connection.release();
                            return_val.message = 'success';
                            return resolve(return_val);
                        }
                    }
                );
            })
            ;
        });
    })

};
module.exports.getPlatformList = function (platform_type) {
    return new Promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {
                if (err) {

                    console.log(err);
                    return reject(err);

                }
                let sql = "Select platform_id, platform_name from platforms where platform_type = ? and sandbox = 0";
                connection.query(sql, platform_type, function (err, result) {
                    connection.release();
                    if (err) {

                        console.log(err);
                        return reject(err);

                    }
                    //console.log('result1:' + result);
                    let platform_list = {};
                    _.each(result, function (val) {
                        platform_list[val.platform_id] = val.platform_name;
                    });
                    return resolve(platform_list);
                })
            })
        })
    })
};

