#!/bin/sh

if [ ! -d /var/www/html/webcell/bin/log/`date '+%Y%m%d'` ]; then
   mkdir /var/www/html/webcell/bin/log/`date '+%Y%m%d'`
fi

/usr/bin/php /var/www/html/webcell/bin/import_priorities.php > /var/www/html/webcell/bin/log/`date '+%Y%m%d'`/import_priorities_`date '+%H%M'`.log
