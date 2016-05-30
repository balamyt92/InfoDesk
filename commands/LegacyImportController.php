<?php

namespace app\commands;

use yii\console\Controller;

class LegacyImportController extends Controller
{
    public function actionIndex($message = 'hello world')
    {
        sleep(5);
        echo $message . "\n";
    }
}
