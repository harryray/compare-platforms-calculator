'use strict';
let conn = require('./model');
let _ = require('underscore-node');
const generatePolicy = function (principalId, effect, resource) {
    const authResponse = {};
    authResponse.principalId = principalId;
    if (effect && resource) {
        const policyDocument = {};
        policyDocument.Version = '2012-10-17';
        policyDocument.Statement = [];
        const statementOne = {};
        statementOne.Action = 'execute-api:Invoke';
        statementOne.Effect = effect;
        statementOne.Resource = resource;
        policyDocument.Statement[0] = statementOne;
        authResponse.policyDocument = policyDocument;
    }
    return authResponse;
};


module.exports.user =  function (event, context, callback) {

    return new Promise(async function(resolve, reject){try {
        // Get Token
        if (typeof event.authorizationToken === 'undefined') {
            if (process.env.DEBUG === 'true') {
                console.log('AUTH: No token');
            }
            reject('Unauthorized');
        }
        const string_identifier = event.authorizationToken.split('Basic');
        if (string_identifier.length !== 2) {


            reject('Unauthorized');
        }
        let auth = Buffer.from(string_identifier[1], 'base64').toString('utf-8');
        const token_secret = auth.split(':');
        if (token_secret.length !== 2) {
            if (process.env.DEBUG === 'true') {
                console.log('AUTH: no token in Bearer');
            }
            reject('Unauthorized');
        }
        const token = token_secret[0].trim();
        const secret = token_secret[1].trim();
        let sql = "select id from users where token = '" + token + "' and secret = '" + secret + "'";
        let user = await conn.query(sql, true);

        if (!_.isEmpty(user) && !_.isNull(user.id)) {
            return resolve(generatePolicy(user.id, 'Allow', event.methodArn));
        }
        return reject('Unauthorized')

    } catch (e) {
        console.error(e.message);
        reject(e.message)
    }});
};
