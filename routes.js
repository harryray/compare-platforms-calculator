'use strict';

var ctp_controller = require('./controller.js');
var handlers = {};
var _ = require('underscore-node');
var createResponse = function (statusCode, data) {
    return {
        statusCode: statusCode,
        body: JSON.stringify(data)
    };
};
