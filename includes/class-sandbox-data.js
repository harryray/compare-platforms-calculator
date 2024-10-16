const _ = require('underscore-node');
const constants = require('./const');
const conn = require('../model.js');

module.exports.Calculator_Acc_Opening_Charges = class Calculator_Acc_Opening_Charges {

    delete_sandbox_charges(ids) {
        return new Promise(function (resolve, reject) {

            conn.database(function (err, pool) {

                if (err) {
                    return reject(err);
                }

                pool.getConnection(function (err, connection) {

                    if (err) {
                        connection.release();
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
}