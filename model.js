var mysql = require('mysql');
var _ = require('underscore-node');
var db_config = {
    connectionLimit: 10,
    debug: true,
    host: process.env.DBHOST,
    user: process.env.DBUNAME,
    password: process.env.DBPASS,
    database: process.env.DB,
    port:  process.env.DBPORT,
    queueLimit: 30,
    acquireTimeout: 1000000,
    dateStrings : true
};
let pool = null;


module.exports.database = function (callback) {// get the connection details from the environment or from the config file
    if (_.isNull(pool)) {
        pool = mysql.createPool(db_config);
        callback(null, pool);

    } else {
        callback(null, pool);
    }
};


module.exports.query = function (sql, single = false) {
    return new Promise(function (resolve, reject) {
        module.exports.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                if (err) {
                    return reject(err);
                }

                // we hit the database to check connectivity

                connection.query(sql, function (err, rows) {

                    connection.release();

                    if (err) {
                        return reject(err);
                    }
                    var result = _.isNull(rows)
                        ? false
                        : rows;

                    if (single) {
                        /** @param {string} row.m */
                        result = rows.shift();
                    }

                    return resolve(result);
                });
            });
        });
    });
};

