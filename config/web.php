<?php

use app\repositories\AuthorRepository;
use app\repositories\BookRepository;
use app\repositories\ReportRepository;
use app\repositories\SubscriptionRepository;
use app\services\AuthorService;
use app\services\BookNotificationService;
use app\services\BookService;
use app\services\CoverUploadService;
use app\services\ReportService;
use app\services\SmsPilotService;
use app\services\SubscriptionService;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

return [
    'id' => 'infotek-books',
    'name' => 'Каталог книг',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'container' => [
        'singletons' => [
            SmsPilotService::class => static function () use ($params) {
                return new SmsPilotService([
                    'apiKey' => $params['smsPilot']['apiKey'],
                    'sender' => $params['smsPilot']['sender'],
                ]);
            },
            CoverUploadService::class => CoverUploadService::class,
            BookRepository::class => BookRepository::class,
            AuthorRepository::class => AuthorRepository::class,
            SubscriptionRepository::class => SubscriptionRepository::class,
            ReportRepository::class => ReportRepository::class,
            BookNotificationService::class => BookNotificationService::class,
            BookService::class => BookService::class,
            AuthorService::class => AuthorService::class,
            SubscriptionService::class => SubscriptionService::class,
            ReportService::class => ReportService::class,
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'infotek-books-catalog-test-key-2024',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'books' => 'book/index',
                'books/<id:\d+>' => 'book/view',
                'authors' => 'author/index',
                'authors/<id:\d+>' => 'author/view',
                'report/top-authors' => 'report/top-authors',
            ],
        ],
    ],
    'params' => $params,
];
