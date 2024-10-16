#!/usr/bin/env bash
# EXPORT_DB_HOST database host
# EXPORT_DB_USERNAME database username
# EXPORT_DB_PASSWORD
# EXPORT_DB_NAME name of database to export
# EXPORT_S3_PATH path to file. Will be saved in format ${EXPORT_S3_PATH}/${EXPORT_DB_NAME}.gz
#
# Example usage:

#!/bin/bash
cd /tmp
file=$(date +%a).sql
mysql \
  --host ${DBHOST} \
  --port ${DBPORT} \
  -u ${DBUNAME} \
  --password="${DBPASS}" \
${MYSQL_DB} > ${file}
if [ "${?}" -eq 0 ]; then
  gzip ${file}

else
echo "Error backing up mysql"
exit 255
fi

scp /data/www/fundscape/wp-content/themes/bridge-child/blog-single-loop.php -P18765 fundscap@fundscape.co.uk:~/www/wp-content/themes/bridge-child/blog-single-loop.php



#mysqldump   --user=' + username + ' -p' + pass + ' --host=' + host + ' --protocol=tcp --port=' + port + ' --default-character-set=utf8  "' + db + '" >  backups/' + filename