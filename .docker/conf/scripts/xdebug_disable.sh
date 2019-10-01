#!/bin/sh
sed -i -e "s/zend_extension/;zend_extension/g" /usr/local/etc/php/conf.d/xdebug.ini
supervisorctl restart php-fpm:php-fpmd