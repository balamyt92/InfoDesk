<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ServicePresence */

$this->title = 'Изменеие фирмы:';
?>
<div class="firms-update" style="margin-top: 10px;">
    <?= $this->render('_service_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>
</div>
