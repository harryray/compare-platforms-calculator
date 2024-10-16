/*let sql = "update platforms_data set dealing_fee_credits = 90 where platform_id=2291;";

let sql1 = "update platforms_data set ann_admin_amount = 200000 ,ann_admin_fee_max = 0 where platform_id = 581;";

let sql = "ALTER TABLE platforms ADD COLUMN `sandbox` BOOLEAN NOT NULL DEFAULT 0 AFTER `url`";
let sql = "ALTER TABLE  platforms  ADD COLUMN  created_at TIMESTAMP NOT NULL DEFAULT NOW()";
let sql = "ALTER TABLE  platforms  ADD COLUMN  updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()";
let sql = "ALTER TABLE platforms_data ADD COLUMN `platform_id` INT(20) NULL AFTER `rec_status`,ADD INDEX `idx_platform_id` (`platcalc_platform_id` ASC);";*/
let sql = "ALTER TABLE `platforms_data` " +
    "ADD INDEX `idx_gia` (`sup_fund_gia` ASC)," +
    "ADD INDEX `idx_isa` (`sup_fund_isa` ASC)," +
    "ADD INDEX `idx_jisa` (`sup_fund_jisa` ASC)," +
    "ADD INDEX `idx_sipp` (`sup_fund_sipp` ASC)," +
    "ADD INDEX `idx_jsipp` (`sup_fund_jsipp` ASC)," +
    "ADD INDEX `idx_lifetime_isa` (`sup_fund_lifetime_isa` ASC)," +
    "ADD INDEX `idx_ex_gia` (`sup_ex_gia` ASC)," +
    "ADD INDEX `idx_ex_isa` (`sup_ex_isa` ASC), " +
    "ADD INDEX `idx_ex_jisa` (`sup_ex_jisa` ASC)," +
    "ADD INDEX `idx_ex_sipp` (`sup_ex_sipp` ASC)," +
    "ADD INDEX `idx_ex_jsipp` (`sup_ex_jsipp` ASC)," +
    "ADD INDEX `idx_ex_lifetime_isa` (`sup_ex_lifetime_isa` ASC);";
/*
let alter_pd = "Alter table platform_data add column sup_fund_onshore_bond boolean DEFAULT 0 ";
let alter_pd = "Alter table platform_data add column sup_fund_offshore_bond boolean DEFAULT 0 ";
let alter_pd = "Alter table platform_data add column sup_ex_onshore_bond boolean DEFAULT 0 ";
let alter_pd = "Alter table platform_data add column sup_ex_offshore_bond boolean DEFAULT 0 ";
*/


let conn = require('./model');
module.exports.init = function () {
    return new Promise(function (resolve, reject) {
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }

            pool.getConnection(function (err, connection) {

                /* Begin transaction */


                connection.beginTransaction(function (err) {
                    if (err) {
                        connection.rollback(function () {
                            reject(err);
                            console.log(err);

                        });
                    }
                    let sql = "ALTER TABLE platforms ADD COLUMN `sandbox` BOOLEAN NOT NULL DEFAULT 0 AFTER `url`";
                    connection.query(sql, function (err, result) {

                        if (err) {
                            connection.rollback(function () {
                                reject(err);
                                console.log(err);

                            });
                        }
                        let sql = "ALTER TABLE platforms_data " +
                            "ADD COLUMN `parent_id` INT(20) NULL AFTER `rec_status`," +
                            "ADD INDEX `idx_platform_id` (`parent_id` ASC)," +
                            "ADD INDEX `idx_gia` (`sup_fund_gia` ASC)," +
                            "ADD INDEX `idx_isa` (`sup_fund_isa` ASC)," +
                            "ADD INDEX `idx_jisa` (`sup_fund_jisa` ASC)," +
                            "ADD INDEX `idx_sipp` (`sup_fund_sipp` ASC)," +
                            "ADD INDEX `idx_jsipp` (`sup_fund_jsipp` ASC)," +
                            "ADD INDEX `idx_lifetime_isa` (`sup_fund_lifetime_isa` ASC)," +
                            "ADD INDEX `idx_ex_gia` (`sup_ex_gia` ASC)," +
                            "ADD INDEX `idx_ex_isa` (`sup_ex_isa` ASC), " +
                            "ADD INDEX `idx_ex_jisa` (`sup_ex_jisa` ASC)," +
                            "ADD INDEX `idx_ex_sipp` (`sup_ex_sipp` ASC)," +
                            "ADD INDEX `idx_ex_jsipp` (`sup_ex_jsipp` ASC)," +
                            "ADD INDEX `idx_ex_lifetime_isa` (`sup_ex_lifetime_isa` ASC);";
                        connection.query(sql, function (err, result) {

                            if (err) {
                                connection.rollback(function () {
                                    reject(err);
                                    console.log(err);

                                });
                            }
                            let sql = "create table sandbox_users ( id int not null auto_increment unique," +
                                " token varchar(500) not null, " +
                                " user_id int not null, " +
                                " platform_id int not null, " +
                                " sandbox_id int not null, " +
                                " sequence_id int not null, " +
                                " created_at datetime default current_timestamp,  " +
                                " updated_at datetime default current_timestamp  on update current_timestamp, " +
                                "primary key(id), " +
                                "KEY `idx_token` (`token`), " +
                                "KEY `idx_created_at` (`created_at`), " +
                                "KEY `idx_updated_at` (`updated_at`), " +
                                "CONSTRAINT `fk_plat_data_id` FOREIGN KEY (`sandbox_id`) REFERENCES `platforms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION)";

                            connection.query(sql, function (err, result) {

                                if (err) {
                                    connection.rollback(function () {
                                        reject(err);
                                        console.log(err);

                                    });
                                }
                                console.info("table created");
                                connection.commit();
                                connection.release();
                            });
                        });
                    });

                })
            })
        })
    })
};

//platform info saving

let platform_info_sql = "create table platform_info ( id int not null auto_increment unique," +
    " platform_id int not null unique, " +
    " platform_info text not null, " +
    " user_id int not null, " +
    " created_at datetime default current_timestamp,  " +
    " updated_at datetime default current_timestamp  on update current_timestamp," +
    " primary key(id), " +
    " CONSTRAINT `fk_plat_id` FOREIGN KEY (`platform_id`) REFERENCES `platforms` (`platform_id`) ON DELETE NO ACTION ON UPDATE NO ACTION)";
module.exports.init_platform_info_table = function () {
    return new Promise(function (resolve, reject) {
        conn.database(function (err, pool) {

            if (err) {
                return reject(err);
            }
            pool.getConnection(function (err, connection) {

                /* Begin transaction */
                connection.query(platform_info_sql, function (err, result) {

                    if (err) {
                        console.log(err);
                        reject(err);


                    }
                    return resolve(result);
                })
            })
        })
    })
};
