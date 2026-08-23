# Каталог книг

Yii2 + MySQL + nginx + PHP-FPM.

## Docker

```bash
docker compose up -d --build
```

Приложение: http://localhost:8090

Логин: `user` / `user123`

Остановка:

```bash
docker compose down
```

## Локально без Docker

```bash
composer install --no-dev
mysql -u root -e "CREATE DATABASE infotek_books CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php yii migrate --interactive=0
chmod -R 777 runtime web/assets web/uploads/covers
php yii serve
```

SMS: ключ `эмулятор`, env `SMSPILOT_API_KEY`.
