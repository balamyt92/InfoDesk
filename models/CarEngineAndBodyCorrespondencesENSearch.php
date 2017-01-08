<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CarEngineAndBodyCorrespondencesEN;

/**
 * CarEngineAndBodyCorrespondencesENSearch represents the model behind the search form of `app\models\CarEngineAndBodyCorrespondencesEN`.
 */
class CarEngineAndBodyCorrespondencesENSearch extends CarEngineAndBodyCorrespondencesEN
{
    public $engine = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Model', 'ID_Body', 'ID_Engine'], 'integer'],
            [['engine'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CarEngineAndBodyCorrespondencesEN::find()
            ->joinWith('iDEngine e')
            ->joinWith('iDMark ma')
            ->joinWith('iDModel mo')
            ->joinWith('iDBody b');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        $this->load($params);

        $dataProvider->setSort([
            'defaultOrder' => ['ID_Body' => SORT_ASC, 'engine' => SORT_ASC],
            'attributes' => [
                'ID_Mark' => [
                    'asc' => ['ma.Name' => SORT_ASC],
                    'desc' => ['ma.Name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'ID_Model' => [
                    'asc' => ['mo.Name' => SORT_ASC],
                    'desc' => ['mo.Name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'ID_Body' => [
                    'asc' => ['b.Name' => SORT_ASC],
                    'desc' => ['b.Name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'engine' => [
                    'asc' => ['e.Name' => SORT_ASC],
                    'desc' => ['e.Name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'CarEngineAndBodyCorrespondencesEN.ID_Mark' => $this->ID_Mark,
            'CarEngineAndBodyCorrespondencesEN.ID_Model' => $this->ID_Model,
            'CarEngineAndBodyCorrespondencesEN.ID_Body' => $this->ID_Body,
            'CarEngineAndBodyCorrespondencesEN.ID_Engine' => $this->ID_Engine,
        ]);
        $query->andWhere('e.Name LIKE "%' . $this->engine . '%" ');

        return $dataProvider;
    }
}
