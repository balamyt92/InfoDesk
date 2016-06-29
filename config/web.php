<?php

$db = require __DIR__.'/db.php';

$config = [
    'id'         => 'InfoDesk',
    'basePath'   => dirname(__DIR__),
    'components' => [
        'request' => [
            'cookieValidationKey' => 'secret',
        ],
        'db' => $db,
    ],

];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.33.1'], // настройка доступа gii
    ];
}

return $config;
