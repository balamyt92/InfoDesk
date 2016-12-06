<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Firms */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Firms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firms-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => 'Вы действительно хотите удалить фирму?',
                'method'  => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'Name',
            'Address:ntext',
            'Phone',
            'Comment:ntext',
            'Enabled:boolean',
            'ActivityType:ntext',
            'OrganizationType',
            'District',
            'Fax',
            'Email:email',
            'URL:url',
            'OperatingMode:ntext',
            'Identifier',
            'Priority',
        ],
    ]) ?>

</div>
