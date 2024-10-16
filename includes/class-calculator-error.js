constants = require('./const');

module.exports.Calculator_Error = class Calculator_Error {
    constructor(err_code) {

        this.errorCode = err_code;
        this.error = '';
    }

    get_error_messages() {
        let err_msg = '';
        let err = '';
        switch (this.errorCode) {
            case constants.ERR_CODE_SCHEMA:
                err_msg = 'Invalid values in request';
                err = constants.ERR_CODE_SCHEMA_STR;
                break;
            case constants.ERR_CODE_TOTAL_INVESTMENTS:
                err_msg = 'Total investment not correct';
                err = constants.ERR_CODE_TOTAL_FUNDS_STR;
                break;
            case constants.ERR_CODE_TOTAL_EX:
                err_msg = 'Total shares should not exceed total investment';
                err = constants.ERR_CODE_TOTAL_EX_STR;
                break;
            case constants.ERR_CODE_REQUIRED:
                err_msg = 'Required parameters not present';
                err = constants.ERR_CODE_REQUIRED_STR;
                break;
            default:
                err_msg = 'Some error occurred, please try again';
                this.errorCode = constants.ERR_CODE_SERVER_ERROR;
                err = constants.ERR_CODE_SERVER_ERROR_STR;
                break;
        }
        this.message = err_msg;
        this.error = err;
        return err_msg;
    }


};


