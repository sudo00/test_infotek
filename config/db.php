<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=' . (getenv('DB_HOST') ?: 'localhost') . ';dbname=infotek_books',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',
];
