#!/bin/bash
HOME_ROOT="/home/httpd"
MYSQL_ROOT="/usr/local/mysql/var"
BACK_ROOT="/backup/source"

MAKE_DATE="$(date +%y%m%d)"
RM_DATE=$(date +%y%m%d --date '2 days ago')

if [ ! -d "$BACK_ROOT/$MAKE_DATE" ]; then
    mkdir "$BACK_ROOT/$MAKE_DATE"
fi

tar cvfp $BACK_ROOT/$MAKE_DATE/web.tar.gz $HOME_ROOT --exclude=logs --exclude=*.tar --exclude=*.tar.gz
tar cvfp $BACK_ROOT/$MAKE_DATE/mysql.tar.gz $MYSQL_ROOT  --exclude=mysql-bin.*  --exclude=ib_logfile* --exclude=localh      ost.localdomain.*

if [ -d "$BACK_ROOT/$RM_DATE" ]; then
    rm -rf "$BACK_ROOT/$RM_DATE"
fi

