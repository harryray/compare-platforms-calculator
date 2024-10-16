'use strict';
const conn = require('./model.js');
const fn = require('./includes/functions');
let _ = require('underscore-node');
let constant = require('./includes/const');
module.exports.status_cron = function () {
    return new Promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {
                if (err) {
                    return reject(err);
                }
                let date = new Date();
                let sql_date = fn.getDateFormat(date);
                // we hit the database to check connectivity
                let sql = "SELECT id , platform_id from platforms_data pd where rec_status =? and active_from = ? and active_to >= ?";
                connection.query(sql, [constant.STATUS_PENDING_APPROVED, sql_date, sql_date], function (err, rows) {
                    if (err) {
                        connection.release();
                        return reject(err);
                    }
                    let pids = [];
                    let ids = [];
                    _.each(rows, function (row) {
                        ids.push(row.id);
                        pids.push(row.platform_id);
                    });
                    console.log(ids);
                    console.log(pids);
                    if(_.isEmpty(pids)){
                        connection.release();
                        return(resolve({'msg': 'No platforms found with switch status'}))
                    }
                    let update_sql = "UPDATE platforms_data pd left join platforms_data_charges pc on pd.id=pc.parent_id SET pd.rec_status = case when pd.id in (?) then 1 " +
                        "when pd.rec_status = 1 then 0 else pd.rec_status end , pc.status=case when pc.parent_id in (?) then 1 " +
                        " else pc.status = 0 end where pd.platform_id in (?) ";
                    connection.query(update_sql, [[ids],[ids],[pids]], function (err, result) {

                        connection.release();
                        if (err) {

                            return reject(err);
                        }

                        /** @param {string} row.m */
                        var row = rows.shift();
                        console.log("Updates to status:\n" + result.toString());
                        var ret = "updates : " + result.affectedRows;

                        return resolve(ret);
                    });
                });
            });
        });
    });
};