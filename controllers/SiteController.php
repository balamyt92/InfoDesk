<?php

namespace app\controllers;
use \yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */

class SiteController extends Controller
{
    /**
     * Рендер гланой
     * 
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Рендер страницы импорта
     * 
     * @return string
     */
    public function actionImport() {
        return $this->render('import');
    }

    /**
     * Рендер страницы работы с таблицей фирм
     * @return string
     */
    public function actionFirms() {
        return $this->render('firms');
    }
}