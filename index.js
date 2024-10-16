const bodyParser = require('body-parser');
const express = require('express');

let validator = require('./validator');
var fn = require('./includes/functions');

const app = express();

app.use(bodyParser.urlencoded({extended: true}));
app.use(bodyParser.json());

let _ = require('underscore-node');
//var routes = require('./routes.js');
let ctp_controller = require('./controller.js');

let cron = require('./cron.js');
let handlers = {};
let createResponse = function (statusCode, data) {
    return {
        statusCode: statusCode,
        body: JSON.stringify(data)
    };
};
exports.handler = function (event, context, callback) {

    // running an AWS test does not have "pathParameters", so we will return a ping
    let endpoint = "ping";


    if (!_.isUndefined(event.pathParameters) && !_.isNull(event.pathParameters)) {
        endpoint = event.pathParameters.function_name;
    }


    let fn = handlers[endpoint];
    let httpMethod = event.httpMethod;

    let response = null;

    if (_.isFunction(fn)) {

        let cb = function (err, data) {

            response = err
                ? createResponse(400, err)
                : createResponse(200, data);
            err ? console.error(err) : console.log('ok');
            context.callbackWaitsForEmptyEventLoop = false;
            // we will always call context.succeed() in case of Lambda, so the caller will get a result incl HTTP code.
            context.succeed(response);


        };

        fn(event, cb);

    } else {

        response = createResponse(405, "Invalid HTTP Method: " + httpMethod + "/" + endpoint);


        context.succeed(response);

    }
};

handlers["ping"] = function (event, callback) {
    ctp_controller.hello(event, callback).then(function (data) {
        callback(null, data);
    }).catch(function (err) {
        callback(err, null);
    });
};
handlers["calculate"] = function (event, callback) {


    validator.saveRequest(event).then(validator.validateUserSchema).then(
        ctp_controller.calculate).then(function (data) {
        callback(null, data);
    }).catch(function (err) {
        callback(err, null);
        fn.save_request(JSON.parse(event));
    });
};

/* Ticket#192 @ checklist -4 start  */
handlers["calculate_heat_map"] = function (event, callback) {


    validator.saveRequest(event).then(validator.validateUserSchema).then(
        ctp_controller.calculate_heat_map).then(function (data) {
        callback(null, data);
    }).catch(function (err) {
        callback(err, null);
        fn.save_request(JSON.parse(event));
    });
};
/* Ticket#192 @ checklist -4 end  */

handlers["platform"] = function (event, callback) {

    let platform_id = event.pathParameters.param1;
    let status = _.isUndefined(event.pathParameters.param2) ? null : event.pathParameters.param2;
    let version_id = _.isUndefined(event.pathParameters.param3) ? null : event.pathParameters.param3;
    let user_id = event.requestContext.authorizer.principalId;
    console.info('user_id:' + user_id);
    if (user_id != 1) {
        let err = new Error('Unauthorized');
        callback(err, null);
    }


    if (!_.isNull(status)) {

        ctp_controller.updatePlatformStatus(event.body, platform_id, status, version_id)
            .then(function (data) {

                callback(null, data);
            }).catch(function (err) {
            console.log(err);
            callback(err, null);

        });
    }
    else if (!_.isNull(platform_id)) {
        validator.validatePlatform(platform_id, event.body).then(ctp_controller.savePlatformData)
            .then(function (data) {
                callback(null, data);
            }).catch(function (err) {
            console.log(err);
            callback(err, null);

        });
    } else {
        let err = "Platform not found for update";
        callback(err, null);
    }

};
handlers["platforms"] = function (event, callback) {
    let platform_id = event.pathParameters.param1;

    validator.validatePlatform(platform_id, null).then(ctp_controller.getPlatform)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.log(err);
        callback(err, null);

    });

};

handlers["platforminfo"] = function (event, callback) {
    let platform_id = event.pathParameters.param1;

    validator.validatePlatform(platform_id, null).then(ctp_controller.getPlatformVersionData)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.log(err);
        callback(err, null);

    });

};
handlers["maxversion"] = function (event, callback) {
    let platform_id = event.pathParameters.param1;

    validator.validatePlatform(platform_id, null).then(ctp_controller.getMaxVersion)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.log(err);
        callback(err, null);

    });

};

handlers["platformadd"] = function (event, callback) {

    let user_id = event.requestContext.authorizer.principalId;
    console.info('user_id:' + user_id);
    if (user_id != 1) {
        let err = new Error('Unauthorized');
        callback(err, null);
    }


    ctp_controller.addPlatform(event.body)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.log(err);
        callback(err, null);

    });

};
handlers['sandboxids'] = function (event, callback) {
    // let user_id = event.requestContext.authorizer.principalId;
    //console.info('user_id:' + user_id);
    let sandbox_user_id = event.pathParameters.param1;
    let platform_id = event.pathParameters.param2;

    ctp_controller.getSandboxIds(sandbox_user_id, platform_id)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.log(err);
        callback(err, null);

    });

};

handlers['nextsandboxid'] = function (event, callback) {
    let user_id = event.requestContext.authorizer.principalId;
    console.info('user_id:' + user_id);

    if (user_id != 1) {
        let err = new Error('Unauthorized');
        callback(err, null);
    }
    let vendor_id = event.pathParameters.param1;
    let platform_id = event.pathParameters.param2;

    if (!_.isNull(status)) {
        ctp_controller.getNextSandboxSequence(vendor_id, platform_id)
            .then(function (data) {

                callback(null, data);
            }).catch(function (err) {
            console.log(err);
            callback(err, null);

        });
    }
};
handlers["sandboxdata"] = function (event, callback) {

    //let user_id = event.requestContext.authorizer.principalId;
    ///console.info('user_id:' + user_id);
    /*if (user_id != 1) {
        let err = new Error('Unauthorized');
        callback(err, null);
    }*/

    let sandbox_id = event.pathParameters.param1;
    ctp_controller.getSandboxData(sandbox_id)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.log(err);
        callback(err, null);

    });

};

handlers["sandboxdelete"] = function (event, callback) {

    //let user_id = event.requestContext.authorizer.principalId;
    ///console.info('user_id:' + user_id);
    /*if (user_id != 1) {
        let err = new Error('Unauthorized');
        callback(err, null);
    }*/

    let sandbox_id = event.pathParameters.param1;
    ctp_controller.deleteSandbox(sandbox_id)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.log(err);
        callback(err, null);

    });

};
handlers["updatesandboxcharges"] = function (event, callback) {


    ctp_controller.addUpdateSandboxCharges(event.body)
        .then(function (data) {

            callback(null, data);
        }).catch(function (err) {
        console.error(err);
        callback(err, null);

    });

};
handlers["platformlist"] = function (event, callback) {
    let platform_type = event.pathParameters.param1;
    ctp_controller.getPlatformList(platform_type)
        .then(function (data) {
            callback(null, data);
        }).catch(function (err) {
        console.error(err);
        callback(err, null);

    });
};
module.exports.ping = function (event, context, callback) {
    ctp_controller.hello(event, callback).then(function (data) {

        let response = createResponse(200, data);
        context.succeed(response);
    }).catch(function (err) {
        let response = createResponse(400, err);
        context.succeed(response);

    });

};

module.exports.status_cron = function (event, context, callback) {
    cron.status_cron(event, callback).then(function (data) {

        let response = createResponse(200, data);
        context.succeed(response);
    }).catch(function (err) {
        let response = createResponse(400, err);
        context.succeed(response);

    });
};

handlers["userList"] = function (event, callback) {
    let user = require('./user.js');

    user.list(event,callback)
        .then(function (data) {
            callback(null, data);
        }).catch(function (err) {
        console.error(err);
        callback(err, null);

    });
};
handlers["userAdd"] = function (event, callback) {
    let user = require('./user.js');
    user.add(event,callback)
        .then(function (data) {
            callback(null, data);
        }).catch(function (err) {
        console.error(err);
        callback(err, null);

    });
};
