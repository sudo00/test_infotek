#!/bin/bash
set -e

cd /app

if [ ! -d vendor/yiisoft/yii2 ]; then
    composer install --no-dev --prefer-dist --no-interaction
fi

mkdir -p runtime web/assets web/uploads/covers
chmod -R 777 runtime web/assets web/uploads/covers

echo "Waiting for MySQL..."
until mysqladmin ping -h"${DB_HOST:-db}" -u"${DB_USER:-root}" -p"${DB_PASSWORD:-root}" --skip-ssl --silent 2>/dev/null; do
    sleep 2
done

php yii migrate --interactive=0

exec "$@"
