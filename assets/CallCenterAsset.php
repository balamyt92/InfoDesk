<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 */
class CallCenterAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/select2.css',
        'css/select2-bootstrap.css',
        'css/select2-bootstrap.min.css',
    ];
    public $js = [
        'js/call-center.js',
        'js/select2.js',
        'js/select2_locale_ru.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
