var fn = require('./includes/functions');


let conn = require('./model');
let _ = require('underscore-node');
//var routes = require('./routes.js');
let ctp_controller = require('./controller.js');
let handlers = {};
let createResponse = function (statusCode, data) {
    return {
        statusCode: statusCode,
        body: JSON.stringify(data)
    };
};
module.exports.add = function (event,  callback) {
    return new Promise(function (resolve, reject) {

        let data = JSON.parse(event.body);
        if (_.isUndefined(data.username) || _.isUndefined(data.secret) || _.isUndefined(data.token)) {
            return reject(new Error('Required parameters missing'));
        } else {
            conn.database(function (err, pool) {

                if (err) {
                    return reject(err);
                }

                pool.getConnection(function (err, connection) {
                    let sql = "Insert into users(`name`,`token`, `secret`) value (?,?,?)";
                    connection.query(sql, [data.username, data.token, data.secret], function (err, result) {
                        connection.release();

                        if (err) {
                            console.log(err);
                            return reject(err);
                        }
                        let ret = {};
                        if (result.insertId) {
                            ret.msg = 'success';
                            ret.id = result.insertId;
                        }
                        return resolve(ret);
                    })
                })
            })
        }
    });

};
module.exports.list = function (event, context, callback) {

    return new Promise(function (resolve, reject) {

        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }
            pool.getConnection(function (err, connection) {
                let sql = "Select id, name, token, secret from users;";
                connection.query(sql, function (err, result) {
                    connection.release();

                    if (err) {
                        console.log(err);
                        return reject(err);
                    }
                    return resolve(result);
                })
            })
        })
    });

};