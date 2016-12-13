<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ServicePresence */
/* @var $err \yii\db\IntegrityException */
/* @var $items array */

$this->title = 'Изменеие фирмы:';
?>
<div class="row" style="margin-top: 15px;">
    <?php if($err) {?>
        <div class="col-md-3"></div>
        <div class="col-md-6 alert alert-danger">
            <strong>Ошибка!</strong> Не могу обновить услугу. <br><br>
            <?= $err->getName()?><br>
            <?= $err->getMessage()?>
        </div>
    <?php }?>
</div>
<div class="firms-update" style="margin-top: 10px;">
    <?= $this->render('_service_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>
</div>
