#!/bin/bash

WEB_PATH='/data/wwwroot/dms/'
WEB_USER='www'
WEB_USERGROUP='www'

cd $WEB_PATH
git reset --hard origin/master
git clean -f
git pull
git checkout master
chown -R $WEB_USER:$WEB_USERGROUP $WEB_PATH
