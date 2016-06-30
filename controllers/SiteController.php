<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Class SiteController.
 */
class SiteController extends Controller
{
    /**
     * Рендер гланой.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
