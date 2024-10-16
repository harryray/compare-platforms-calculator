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
module.exports.info = function (event, context, callback) {

    // running an AWS test does not have "pathParameters", so we will return a ping
    let platform_id = null;


    if (!_.isUndefined(event.pathParameters.param1) && !_.isNull(event.pathParameters.param1)) {
        platform_id = event.pathParameters.param1;
    }

    let httpMethod = event.httpMethod;

    let response = null;

    if (!_.isNull(platform_id)) {
        let cb = function (err, data) {

            response = err
                ? createResponse(400, err)
                : createResponse(200, data);
            err ? console.error(err) : console.log('ok');
            context.callbackWaitsForEmptyEventLoop = false;
            // we will always call context.succeed() in case of Lambda, so the caller will get a result incl HTTP code.
            context.succeed(response);


        };
        switch (httpMethod) {
            case "POST":
                module.exports.save_platform_info(event, cb);
                break;
            case "GET":
                module.exports.get_platform_info(event, cb);
                break;
            default:
                let err = new Error('Http method not supported');
                createResponse(400, err);
                break;
        }
    } else {
        response = createResponse(405, "Invalid HTTP Method: " + httpMethod + "/" + endpoint);
        context.succeed(response);


    }
    ;


};
module.exports.save_platform_info = function (event, callback) {
    let platform_id = event.pathParameters.param1;
    let data = JSON.parse(event.body);
    let user_id = data.user_id;
    let platform_info = data.data;
    let sql = "Insert into platform_info (`platform_id`," +
        "`platform_info`," +
        "`user_id`) values (" + platform_id + ", '" +
        platform_info + "', " + user_id + ")";
    conn.database(function (err, pool) {

        if (err) {
            return callback(err);
        }

        pool.getConnection(function (err, connection) {

            connection.query(sql, function (err, result) {
                connection.release();
                if (err) {

                    callback(err);
                    console.log(err);


                }
                callback(null, result);
            })
        })
    })

};
module.exports.get_platform_info = function (event, callback) {
    let platform_id = event.pathParameters.param1;

    conn.database(function (err, pool) {

        if (err) {
            return reject(err);
        }

        pool.getConnection(function (err, connection) {


            let sql = "select platform_info, user_id,recommended,url,platform_type,url,info_url," +
                "calculation_method,img,rating from platform_info pi, platforms p where pi.platform_id=p.platform_id and platform_id=?";
            connection.query(sql, platform_id, function (err, result) {
                connection.release();

                if (err) {

                    console.log(err);
                    callback(err);


                }
                if (_.isArray(result)) {
                    result = result.shift();
                }
                return callback(null, result);
            })
        })
    })
};