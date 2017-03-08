<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TextBlock */

$this->title = 'Create Text Block';
$this->params['breadcrumbs'][] = ['label' => 'Text Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="text-block-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
