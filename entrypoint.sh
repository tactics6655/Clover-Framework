#!/bin/bash
set -e

# PHP 모듈 및 설정 정보 로깅
php --ini > ./php-ini-info.log 2>&1
php -m | grep -E "memcached|apcu" > ./php-modules.log 2>&1
find /usr/local/etc/php -name "php.ini" > ./php-ini-path.log 2>&1
php -i | grep "memcached\|apcu" > ./php-module-details.log 2>&1

# 원래의 도커 엔트리포인트 실행 (기존 CMD 또는 ENTRYPOINT 유지)
exec "$@"