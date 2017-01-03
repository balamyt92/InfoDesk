<?php


/* @var $this yii\web\View */
/* @var $model app\models\CarPresenceEN */
/* @var $err \yii\db\IntegrityException */
/* @var $names array */
/* @var $marks array */
/* @var $models array */
/* @var $bodys array */
/* @var $engines array */

$this->title = 'Изменеие позиции в прайсе:';
?>
<div class="row" style="margin-top: 15px;">
    <?php if ($err) {
    ?>
        <div class="col-md-3"></div>
        <div class="col-md-6 alert alert-danger">
            <strong>Ошибка!</strong> Не могу изменить позицию. <br><br>
            <?= $err->getName()?><br>
            <?= $err->getMessage()?>
        </div>
    <?php 
}?>
</div>
<div class="service-update" style="margin-top: 10px;">
    <?= $this->render('_price_form', [
        'model'   => $model,
        'names'   => $names,
        'marks'   => $marks,
        'models'  => $models,
        'bodys'   => $bodys,
        'engines' => $engines,
    ]) ?>
</div>