#!/bin/sh

if [ ! -d /var/www/html/webcell/bin/log/`date '+%Y%m%d'` ]; then
   mkdir /var/www/html/webcell/bin/log/`date '+%Y%m%d'`
fi

/usr/bin/php /var/www/html/webcell/bin/import_tre.php > /var/www/html/webcell/bin/log/`date '+%Y%m%d'`/import_tre_`date '+%H%M'`.log
