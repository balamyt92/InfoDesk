<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * CarPresenceSearch represents the model behind the search form of `app\models\CarPresenceEN`.
 */
class CarPresenceSearch extends CarPresenceEN
{
    public $iDMark;
    public $iDModel;
    public $iDBody;
    public $iDEngine;
    public $iDName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Model', 'ID_Name', 'ID_Firm', 'ID_Body', 'ID_Engine'], 'integer'],
            [['CarYear', 'Comment', 'Hash_Comment', 'TechNumber', 'Catalog_Number',
                'iDMark', 'iDModel', 'iDBody', 'iDEngine', 'iDName', ], 'safe'],
            [['Cost'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @param array $pagination
     *
     * @return ActiveDataProvider
     */
    public function search($params, array $pagination = ['pageSize' => 100])
    {
        $this->load($params);

        if (!$this->validate()) {
            return new ActiveDataProvider([
                'query' => CarPresenceEN::find(),
            ]);
        }

        $query = CarPresenceEN::find()
            ->joinWith(['iDName n', 'iDMark ma', 'iDModel mo', 'iDBody b', 'iDEngine e'])
            ->groupBy([
                'CarPresenceEN.ID_Name',
                'CarPresenceEN.ID_Mark',
                'CarPresenceEN.ID_Model',
                'CarPresenceEN.ID_Body',
                'CarPresenceEN.ID_Engine',
                'CarYear',
                'Hash_Comment',
                'TechNumber',
                'Catalog_Number',
            ]);

        $query->andFilterWhere([
            'CarPresenceEN.ID_Mark'   => $this->ID_Mark,
            'CarPresenceEN.ID_Model'  => $this->ID_Model,
            'CarPresenceEN.ID_Name'   => $this->ID_Name,
            'CarPresenceEN.ID_Firm'   => $params['id'],
            'CarPresenceEN.ID_Body'   => $this->ID_Body,
            'CarPresenceEN.ID_Engine' => $this->ID_Engine,
            'Cost'                    => $this->Cost,
        ]);

        $query->andFilterWhere(['like', 'CarYear', $this->CarYear])
            ->andFilterWhere(['like', 'Comment', $this->Comment])
            ->andFilterWhere(['like', 'Hash_Comment', $this->Hash_Comment])
            ->andFilterWhere(['like', 'TechNumber', $this->TechNumber])
            ->andFilterWhere(['like', 'Catalog_Number', $this->Catalog_Number]);

        $totalCount = CarPresenceEN::find()
            ->andFilterWhere([
                'CarPresenceEN.ID_Mark'   => $this->ID_Mark,
                'CarPresenceEN.ID_Model'  => $this->ID_Model,
                'CarPresenceEN.ID_Name'   => $this->ID_Name,
                'CarPresenceEN.ID_Firm'   => $params['id'],
                'CarPresenceEN.ID_Body'   => $this->ID_Body,
                'CarPresenceEN.ID_Engine' => $this->ID_Engine,
                'Cost'                    => $this->Cost,
            ]);

        $totalCount->andFilterWhere(['like', 'CarYear', $this->CarYear])
            ->andFilterWhere(['like', 'Comment', $this->Comment])
            ->andFilterWhere(['like', 'Hash_Comment', $this->Hash_Comment])
            ->andFilterWhere(['like', 'TechNumber', $this->TechNumber])
            ->andFilterWhere(['like', 'Catalog_Number', $this->Catalog_Number]);

        $config = [
            'query'      => $query,
            'totalCount' => $totalCount->count(),
        ];
        $config['pagination'] = $pagination;

        $dataProvider = new ActiveDataProvider($config);
        $dataProvider->setSort([
            'attributes' => [
                'ID_Name' => [
                    'asc'     => ['n.Name' => SORT_ASC],
                    'desc'    => ['n.Name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'ID_Mark' => [
                    'asc'     => ['ma.Name' => SORT_ASC],
                    'desc'    => ['ma.Name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'ID_Model' => [
                    'asc'     => ['mo.Name' => SORT_ASC],
                    'desc'    => ['mo.Name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'ID_Body' => [
                    'asc'     => ['b.Name' => SORT_ASC],
                    'desc'    => ['b.Name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'ID_Engine' => [
                    'asc'     => ['e.Name' => SORT_ASC],
                    'desc'    => ['e.Name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'Cost',
                'Comment',
                'TechNumber',
                'Catalog_Number',
            ],
        ]);

        return $dataProvider;
    }

    public function getDetailNames($firm_id)
    {
        return ArrayHelper::map(
            CarPresenceEN::find()
                ->select('*')
                ->joinWith('iDName n')
                ->andFilterWhere([
                    'CarPresenceEN.ID_Mark'   => $this->ID_Mark,
                    'CarPresenceEN.ID_Model'  => $this->ID_Model,
                    'CarPresenceEN.ID_Firm'   => $firm_id,
                    'CarPresenceEN.ID_Body'   => $this->ID_Body,
                    'CarPresenceEN.ID_Engine' => $this->ID_Engine,
                ])
                ->orderBy('Name')
                ->asArray()->all(),
            'id', 'Name');
    }

    public function getMarksName($firm_id)
    {
        return ArrayHelper::map(
            CarPresenceEN::find()
                ->select('*')
                ->joinWith('iDMark m')
                ->andFilterWhere([
                    'CarPresenceEN.ID_Model'  => $this->ID_Model,
                    'CarPresenceEN.ID_Name'   => $this->ID_Name,
                    'CarPresenceEN.ID_Firm'   => $firm_id,
                    'CarPresenceEN.ID_Body'   => $this->ID_Body,
                    'CarPresenceEN.ID_Engine' => $this->ID_Engine,
                ])
                ->groupBy('id')
                ->orderBy('Name')
                ->asArray()->all(),
            'id', 'Name');
    }

    public function getModelsName($firm_id)
    {
        return ArrayHelper::map(
            CarPresenceEN::find()
                ->select('*')
                ->joinWith('iDModel m')
                ->andFilterWhere([
                    'CarPresenceEN.ID_Mark'   => $this->ID_Mark,
                    'CarPresenceEN.ID_Name'   => $this->ID_Name,
                    'CarPresenceEN.ID_Firm'   => $firm_id,
                    'CarPresenceEN.ID_Body'   => $this->ID_Body,
                    'CarPresenceEN.ID_Engine' => $this->ID_Engine,
                ])
                ->groupBy('Name')
                ->orderBy('Name')
                ->asArray()->all(),
            'id', 'Name');
    }

    public function getBodysName($firm_id)
    {
        return ArrayHelper::map(
            CarPresenceEN::find()
                ->select('*')
                ->joinWith('iDBody b')
                ->andFilterWhere([
                    'CarPresenceEN.ID_Model'  => $this->ID_Model,
                    'CarPresenceEN.ID_Mark'   => $this->ID_Mark,
                    'CarPresenceEN.ID_Name'   => $this->ID_Name,
                    'CarPresenceEN.ID_Firm'   => $firm_id,
                    'CarPresenceEN.ID_Engine' => $this->ID_Engine,
                ])
                ->groupBy('Name')
                ->orderBy('Name')
                ->asArray()->all(),
            'id', 'Name');
    }

    public function getEnginesName($firm_id)
    {
        return ArrayHelper::map(
            CarPresenceEN::find()
                ->select('*')
                ->joinWith('iDEngine e')
                ->andFilterWhere([
                    'CarPresenceEN.ID_Model' => $this->ID_Model,
                    'CarPresenceEN.ID_Mark'  => $this->ID_Mark,
                    'CarPresenceEN.ID_Name'  => $this->ID_Name,
                    'CarPresenceEN.ID_Body'  => $this->ID_Body,
                    'CarPresenceEN.ID_Firm'  => $firm_id,
                ])
                ->groupBy('Name')
                ->orderBy('Name')
                ->asArray()->all(),
            'id', 'Name');
    }
}
