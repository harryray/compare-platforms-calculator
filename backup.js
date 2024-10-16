// [START drive_quickstart]
const fs = require('fs');
const exec = require('child_process').exec;
//const readline = require('readline');
const {google} = require('googleapis');
const spawn = require('child_process').spawn;
const days = 5;
var cmd = require('node-cmd');
const ctpApiFolderId = '1tcWemUI-DIkk8G6SfdFbCdy8M1_cxTe0';

// If modifying these scopes, delete token.json.
const SCOPES = ['https://www.googleapis.com/auth/drive'];
const TOKEN_PATH = 'token.json';

// Load client secrets from a local file.
module.exports.backup = function () {
    fs.readFile('credentials.json', (err, content) => {
        if (err) return console.log('Error loading client secret file:', err);
        // Authorize a client with credentials, then call the Google Drive API.
        let json_content = JSON.parse(content);
        authorize(json_content, createDbBackup);
        authorize(json_content, deleteFile);
    });
};


/**
 * Create an OAuth2 client with the given credentials, and then execute the
 * given callback function.
 * @param {Object} credentials The authorization client credentials.
 * @param {function} callback The callback to call with the authorized client.
 */
function authorize(credentials, callback) {
    const {client_secret, client_id, redirect_uris} = credentials.installed;
    const oAuth2Client = new google.auth.OAuth2(
        client_id, client_secret, redirect_uris[0]);

    // Check if we have previously stored a token.
    fs.readFile(TOKEN_PATH, (err, token) => {
        if (err) return getAccessToken(oAuth2Client, callback);
        oAuth2Client.setCredentials(JSON.parse(token));
        callback(oAuth2Client);
    });
}

/**
 * Get and store new token after prompting for user authorization, and then
 * execute the given callback with the authorized OAuth2 client.
 * @param {google.auth.OAuth2} oAuth2Client The OAuth2 client to get token for.
 * @param {callback} callback The callback for the authorized client.
 */
function getAccessToken(oAuth2Client, callback) {
    const authUrl = oAuth2Client.generateAuthUrl({
        access_type: 'offline',
        scope: SCOPES,
    });
    console.log('Authorize this app by visiting this url:', authUrl);
    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout,
    });
    rl.question('Enter the code from that page here: ', (code) => {
        rl.close();
        oAuth2Client.getToken(code, (err, token) => {
            if (err) return console.error('Error retrieving access token', err);
            oAuth2Client.setCredentials(token);
            // Store the token to disk for later program executions
            fs.writeFile(TOKEN_PATH, JSON.stringify(token), (err) => {
                if (err) console.error(err);
                console.log('Token stored to', TOKEN_PATH);
            });
            callback(oAuth2Client);
        });
    });
}

/**
 * Lists the names and IDs of up to 10 files.
 * @param {google.auth.OAuth2} auth An authorized OAuth2 client.
 */
async function createDbBackup(auth) {
    const drive = google.drive({version: 'v3', auth});
    getBackup(function (err, filename) {
        if (err) {
            console.error(err);
            return;
        }
        var fileMetadata = {
            'name': filename,
            parents: [ctpApiFolderId],
            mimeType: 'text/plain'
        };
        console.info('ret' + filename);
        let filepath = 'tmp/' + filename + '.gz';
        drive.files.create({
            resource: fileMetadata,
            media: {
                mimeType: 'text/plain',
                body: fs.createReadStream(filepath)
            }
        }, function (err, data) {
            if (err) {
                console.error(err);
                throw err;
            } else {
                console.log(data);
                console.info('backup file id:' + data.data.id);
                exec('rm ' + filepath);

            }
        });
    });
}

function listFiles(auth, cb) {
    const drive = google.drive({version: 'v3', auth});
    let date_today = new Date();
    let backup_date = new Date(date_today.getTime() - (days * 24 * 60 * 60 * 1000));
    let backup_date_str = backup_date.getFullYear() + '-' + backup_date.getMonth() + '-' + backup_date.getUTCDate();
    drive.files.list({
        q: "name contains '.sql' and '" + ctpApiFolderId + "' in parents and  createdTime < '" + backup_date.toISOString() + "'",
        fields: 'nextPageToken, files(id, name)'
    }, (err, res) => {
        if (err) {
            cb(err);
        }
        const files = res.data.files;
        cb(null, files);

    });

}

function getBackup(cb) {
    var username = process.env.DBUNAME;
    var host = process.env.DBHOST;
    var pass = process.env.DBPASS;
    var db = process.env.DB;
    var port = process.env.DBPORT;
    let env = Object.create(process.env);
    //env.EXPORT_DB_NAME = database;
    var filename = getFileName('ctp_api.sql');
    let which = exec('which mysql');
    which.stdout.on('data', (data) => {
        cb(data.toString());
    });
    //var sql = 'mysqldump   --user=' + username + ' -p' + pass + ' --host=' + host + ' --protocol=tcp --port=' + port + ' --default-character-set=utf8  "' + db + '" >  backups/' + filename;
    let backup = spawn('./backup.sh', {}, {
        DBHOST: process.env.DBHOST,
        DBUNAME: process.env.DBUNAME,
        DBPASS: process.env.DBPASS,
        DB: process.env.DB,
        FILENAME:filename
    });
    backup.stderr.on('data', (data) => {
        cb(data.toString());
    });

    backup.on('close', (code) => {
        if (code === 0) {
            console.log(`Successfully exported`);
            cb(null, filename);
        } else {
            console.log(`Error exporting`);
            cb(code);
        }
    });

    /*var sql = 'mysqldump   --user=' + username + ' -p' + pass + ' --host=' + host + ' --protocol=tcp --port=' + port + ' --default-character-set=utf8  "' + db + '" >  backups/' + filename;
    cmd.get(
        sql
        ,
        function (err, data) {
            if (!err) {
                console.info('in' + data);
                console.log('the node-cmd cloned dir contains these files :\n\n', data);
                cb(null, filename);
            } else {
                console.log('error', err);
                cb(err);
            }

        }
    );*/
}

function getFileName(suffix) {
    var date = new Date();
    var filename = date.getUTCDate() + '_' + date.getUTCMonth() + '_' + date.getFullYear() + '_' + suffix;
    return filename;
}

function deleteFile(auth) {
    const drive = google.drive({version: 'v3', auth});

    listFiles(auth, function (err, files) {
        if (err) {
            console.error(err);
            return;
        }
        if (files.length) {
            files.map((file) => {
                console.log(`file to be deleted ${file.name} (${file.id})`);
                drive.files.delete({
                        'fileId': file.id
                    },
                    (err, res) => {
                        if (err) return console.log('The API returned an error: ' + err);
                        console.log(res);
                    });
            });

        }
    });

}