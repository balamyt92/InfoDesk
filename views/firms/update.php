<?php


/* @var $this yii\web\View */
/* @var $model app\models\Firms */

$this->title = 'Изменеие фирмы:';
?>
<div class="firms-update" style="margin-top: 10px;">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
