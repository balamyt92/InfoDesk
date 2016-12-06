<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Firms */

$this->title = 'Изменеие фирмы:';
?>
<div class="firms-update">

    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6"><h1><?= Html::encode($this->title) ?></h1></div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
