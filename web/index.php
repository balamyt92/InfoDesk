<?php

define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// подключаем панель дебага
if (YII_DEBUG) {
    require __DIR__.'/../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
    $config->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify('');
}

require __DIR__.'/../vendor/yiisoft/yii2/Yii.php';
require_once __DIR__.'/../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';

$config = require __DIR__.'/../config/web.php';

ini_set('display_errors', true);

(new yii\web\Application($config))->run();
