<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * jqGrid asset bundle.
 */
class JqGridAsset extends AssetBundle
{
    public $sourcePath = '@bower/jqgrid';
    public $css = [
        'css/ui.jqgrid.css',
        'css/ui.jqgrid-bootstrap.css',
        'css/ui.jqgrid-bootstrap-ui.css',
    ];
    public $js = [
        'js/i18n/grid.locale-ru.js',
        'js/jquery.jqGrid.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
