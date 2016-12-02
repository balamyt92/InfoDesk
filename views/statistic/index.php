<?php
/*
 * @var $this yii\web\View
 * @var $model \app\models\statistic\ParamForm
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JsExpression;

$this->title = 'Статистика';

\app\assets\StatisticAsset::register($this);

?>

<div class="row" style="margin-top: 20px;">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Элементы управления
            </div>
            <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'method' => 'get'
            ]);
                $url = \yii\helpers\Url::to(['firms/search']);
            ?>


                <?= $form->field($model, 'id_firm')->widget(\kartik\select2\Select2::className(), [
                    'initValueText' => empty($model->id_firm) ? '' : \app\models\Firms::findOne($model->id_firm)->Name,
                    'options' => ['placeholder' => 'Поиск фирмы...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Подождите...'; }"),
                        ],
                        'ajax' => [
                            'url' => $url,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) {return {q:params.term, page: params.address}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('
                            function(firm) {
                                firm.address = firm.address ? firm.address : "";
                                return `${firm.text} ${firm.address}`; 
                            }'),
                        'templateSelection' => new JsExpression('
                            function (firm) {
                                firm.address = firm.address ? firm.address : "";
                                return `${firm.text} ${firm.address}`; 
                            }'),
                    ],
                ]) ?>

                <?= $form->field($model, 'sections')->checkboxList(
                    ['Запчасти', 'Сервисы', 'Поиск по фирмам']
                ) ?>


                <?= $form->field($model, 'operators')->checkboxList(
                    \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'username')) ?>

                <?= $form->field($model, 'date_start')->widget(\kartik\datetime\DateTimePicker::className(), [
                    'options' => ['placeholder' => 'Выберите начальную дату...'],
                    'language' => 'ru',
                    'type' => \kartik\datetime\DateTimePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'format' => 'yyyy-m-d h:i:s',
                        'todayHighlight' => true
                    ]]) ?>

                <?= $form->field($model, 'date_end')->widget(\kartik\datetime\DateTimePicker::className(), [
                    'options' => ['placeholder' => 'Выберите начальную дату...'],
                    'language' => 'ru',
                    'type' => \kartik\datetime\DateTimePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'format' => 'yyyy-m-d h:i:s',
                        'todayHighlight' => true
                    ]]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <?php
    $sections = isset($model->sections) ? $model->sections : [];
    $operators = $model->operators ? ' AND q.id_operator IN (' . implode(',', $model->operators) . ')' : ' ';
    ?>

    <div class="col-sm-8">
        <?php
            if(in_array('0', $sections)) {
                $setting_parts = [
                    'pjax'=>true,
                    'panel'=>[
                        'type'=>'default',
                        'heading'=>'Поиск по запчастям'
                    ],
                ];

                $id_firm = '';
                $position = '';
                $columns = [
                    'date_time',
                    'username',
                    'detail',
                    'mark',
                    'model',
                    'body',
                    'engine'
                ];

                $order_parts = " ORDER BY q.id";
                if($model->id_firm) {
                    $id_firm = ' AND f.id_firm='. $model->id_firm . ' ';
                    $position = ', f.position + 1 as position, f.opened ';
                    $columns[] = 'position';
                    $columns[] = [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'opened',
                        'pageSummary' => true,
                    ];
                    $setting_parts['showPageSummary'] = true;
                    $order_parts = "";
                }

                $sql_parts = "
                            SELECT
                                q.date_time,
                                u.username,
                                ma.Name as mark,
                                d.Name as detail,
                                mo.Name as model,
                                b.Name as boyd,
                                e.Name as engine
                                {$position}
                            FROM stat_parts_query as q
                            LEFT JOIN stat_parts_firms as f ON (q.id = f.id_query)
                            LEFT JOIN user as u ON (q.id_operator = u.id)
                            LEFT JOIN CarMarksEN as ma ON (ma.id = q.mark_id)
                            LEFT JOIN CarENDetailNames as d ON (d.id = q.detail_id)
                            LEFT JOIN CarModelsEN as mo ON (mo.id = q.model_id)
                            LEFT JOIN CarBodyModelsEN as b ON (b.id = q.body_id)
                            LEFT JOIN CarEngineModelsEN as e ON (e.id = q.engine_id)
                            WHERE
                                (q.date_time BETWEEN :d_start AND :d_end)
                                {$operators}
                                {$id_firm}
                            {$order_parts}";

                $count = Yii::$app->db->createCommand("
                            SELECT
                              COUNT(DISTINCT q.id)
                            FROM stat_parts_query as q
                            LEFT JOIN stat_parts_firms as f ON (q.id = f.id_query)
                            WHERE
                                (q.date_time BETWEEN :d_start AND :d_end)
                                {$id_firm}",
                    [
                        ':d_start' => $model->date_start,
                        ':d_end' => $model->date_end
                    ])->queryScalar();

                if($model->id_firm) {
                    $opened_firms = Yii::$app->db->createCommand("
                                SELECT
                                    COALESCE(sum(f.opened),0)
                                FROM stat_parts_query as q
                                LEFT JOIN stat_parts_firms as f ON (q.id = f.id_query)
                                LEFT JOIN user as u ON (q.id_operator = u.id)
                                WHERE
                                    (q.date_time BETWEEN :d_start AND :d_end)
                                    {$operators}
                                    {$id_firm}",
                        [
                            ':d_start' => $model->date_start,
                            ':d_end' => $model->date_end
                        ])->queryScalar();
                } else {
                    $opened_firms = 'не известно сколько';
                }

                $dataProviderParts = new \yii\data\SqlDataProvider([
                    'sql' => $sql_parts,
                    'params' => [
                        ':d_start' => $model->date_start,
                        ':d_end' => $model->date_end
                    ],
                    'totalCount' => $count,
                    'pagination' => [
                        'pageSize' =>10,
                    ],
                ]);

                $setting_parts['dataProvider'] = $dataProviderParts;
                $setting_parts['columns'] = $columns;
                $setting_parts['toolbar'] = [
                    "<span class=\"btn-group\">
                        <h3 style=\"margin-top: 5px;\"> назван {$opened_firms} раз из {$count}</h3>
                    </span>",
                    '{export}',
                    '{toggleData}',
                ];

                Pjax::begin();
                echo kartik\grid\GridView::widget($setting_parts);
                Pjax::end();

            }
        ?>
        <?php
            if(in_array('2', $sections)) {
                $setting_firms = [
                    'pjax'=>true,
                    'panel'=>[
                        'type'=>'default',
                        'heading'=>'Поиск по фирмам'
                    ],
                ];

                $id_firm = '';
                $position = '';
                $columns = [
                    'date_time',
                    'username',
                    'search',
                ];

                if($model->id_firm) {
                    $id_firm = ' AND f.id_firm='. $model->id_firm . ' ';
                    $position = ', f.position + 1 as position, f.opened ';
                    $columns[] = 'position';
                    $columns[] = [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'opened',
                        'pageSummary' => true,
                    ];
                    $setting_firms['showPageSummary'] = true;
                }

                $sql_firms = "
                            SELECT
                                q.date_time,
                                u.username,
                                q.search
                                {$position}
                            FROM stat_firms_query as q
                            LEFT JOIN stat_firms_firms as f ON (q.id = f.id_query)
                            LEFT JOIN user as u ON (q.id_operator = u.id)
                            WHERE
                                (q.date_time BETWEEN :d_start AND :d_end)
                                {$operators}
                                {$id_firm}
                            GROUP BY q.id";

                $count = Yii::$app->db->createCommand("
                            SELECT
                              COUNT(DISTINCT q.id)
                            FROM stat_firms_query as q
                            LEFT JOIN stat_firms_firms as f ON (q.id = f.id_query)
                            WHERE
                                (q.date_time BETWEEN :d_start AND :d_end)
                                {$id_firm}",
                    [
                        ':d_start' => $model->date_start,
                        ':d_end' => $model->date_end
                    ])->queryScalar();

                if($model->id_firm) {
                    $opened_firms = Yii::$app->db->createCommand("
                                SELECT
                                    COALESCE(sum(f.opened),0)
                                FROM stat_firms_query as q
                                LEFT JOIN stat_firms_firms as f ON (q.id = f.id_query)
                                LEFT JOIN user as u ON (q.id_operator = u.id)
                                WHERE
                                    (q.date_time BETWEEN :d_start AND :d_end)
                                    {$operators}
                                    {$id_firm}",
                        [
                            ':d_start' => $model->date_start,
                            ':d_end' => $model->date_end
                        ])->queryScalar();
                } else {
                    $opened_firms = 'не известно сколько';
                }

                $dataProviderFirms = new \yii\data\SqlDataProvider([
                    'sql' => $sql_firms,
                    'params' => [
                        ':d_start' => $model->date_start,
                        ':d_end' => $model->date_end
                    ],
                    'totalCount' => $count,
                    'pagination' => [
                        'pageSize' =>10,
                    ],
                ]);

                $setting_firms['dataProvider'] = $dataProviderFirms;
                $setting_firms['columns'] = $columns;
                $setting_firms['toolbar'] = [
                    "<span class=\"btn-group\">
                        <h3 style=\"margin-top: 5px;\"> назван {$opened_firms} раз из {$count}</h3>
                    </span>",
                    '{export}',
                    '{toggleData}',
                ];

                Pjax::begin();
                echo kartik\grid\GridView::widget($setting_firms);
                Pjax::end();
            }
        ?>

        <?php
            if(in_array('1', $sections)) {
                $setting_service = [
                    'pjax'=>true,
                    'panel'=>[
                        'type'=>'default',
                        'heading'=>'Поиск по сервисам'
                    ],
                ];

                $id_firm = '';
                $position = '';
                $columns = [
                    'date_time',
                    'username',
                    'service',
                ];

                if($model->id_firm) {
                    $id_firm = ' AND f.id_firm='. $model->id_firm . ' ';
                    $position = ', f.position + 1 as position, f.opened ';
                    $columns[] = 'position';
                    $columns[] = [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'opened',
                        'pageSummary' => true,
                    ];
                    $setting_service['showPageSummary'] = true;
                }

                $sql_service = "
                            SELECT
                                q.date_time,
                                u.username,
                                s.Name as service
                                {$position}
                            FROM stat_service_query as q
                            LEFT JOIN stat_service_firms as f ON (q.id = f.id_query)
                            LEFT JOIN user as u ON (q.id_operator = u.id)
                            LEFT JOIN Services as s ON (s.id = q.id_service)
                            WHERE
                                (q.date_time BETWEEN :d_start AND :d_end)
                                {$operators}
                                {$id_firm}
                            GROUP BY q.id";

                $count = Yii::$app->db->createCommand("
                            SELECT
                              COUNT(DISTINCT q.id)
                            FROM stat_service_query as q
                            LEFT JOIN stat_service_firms as f ON (q.id = f.id_query)
                            WHERE
                                (q.date_time BETWEEN :d_start AND :d_end)
                                {$id_firm}",
                    [
                        ':d_start' => $model->date_start,
                        ':d_end' => $model->date_end
                    ])->queryScalar();

                if($model->id_firm) {
                    $opened_firms = Yii::$app->db->createCommand("
                                SELECT
                                    COALESCE(sum(f.opened),0)
                                FROM stat_service_query as q
                                LEFT JOIN stat_service_firms as f ON (q.id = f.id_query)
                                LEFT JOIN user as u ON (q.id_operator = u.id)
                                WHERE
                                    (q.date_time BETWEEN :d_start AND :d_end)
                                    {$operators}
                                    {$id_firm}",
                        [
                            ':d_start' => $model->date_start,
                            ':d_end' => $model->date_end
                        ])->queryScalar();
                } else {
                    $opened_firms = 'не известно сколько';
                }

                $dataProviderService = new \yii\data\SqlDataProvider([
                    'sql' => $sql_service,
                    'params' => [
                        ':d_start' => $model->date_start,
                        ':d_end' => $model->date_end
                    ],
                    'totalCount' => $count,
                    'pagination' => [
                        'pageSize' =>10,
                    ],
                ]);

                $setting_service['dataProvider'] = $dataProviderService;
                $setting_service['columns'] = $columns;
                $setting_service['toolbar'] = [
                    "<span class=\"btn-group\">
                        <h3 style=\"margin-top: 5px;\"> назван {$opened_firms} раз из {$count}</h3>
                    </span>",
                    '{export}',
                    '{toggleData}',
                ];

                Pjax::begin();
                echo kartik\grid\GridView::widget($setting_service);
                Pjax::end();
            }
        ?>
    </div>
</div>