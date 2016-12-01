<?php

$db = require __DIR__.'/db.php';

$config = [
    'id'         => 'InfoDesk',
    'basePath'   => dirname(__DIR__),
    'language'   => 'ru_RU',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'secret',
        ],
        'db'           => $db,
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'user' => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-info-desk', 'httpOnly' => true],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            // this is the name of the session cookie used for login
            'name' => 'info-desk',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ]
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.33.1'], // настройка доступа gii
    ];
    // включаем дебаг панель
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class'      => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.33.1'],
    ];
}

return $config;
