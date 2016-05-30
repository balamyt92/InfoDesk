<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\FirmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Firms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Firms', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'Name',
            'Address:ntext',
            'Phone',
//            'Comment:ntext',
//            'Enabled',
//            'ActivityType:ntext',
//            'OrganizationType',
//            'District',
//            'Fax',
//            'Email:email',
//            'URL:url',
//            'OperatingMode:ntext',
//            'Identifier',
//            'Priority',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
