<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CarEngineAndModelCorrespondencesEN;

/**
 * CarEngineAndModelCorrespondencesENSearch represents the model behind the search form of `app\models\CarEngineAndModelCorrespondencesEN`.
 */
class CarEngineAndModelCorrespondencesENSearch extends CarEngineAndModelCorrespondencesEN
{
    public $engine = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Engine', 'ID_Model'], 'integer'],
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
        $query = CarEngineAndModelCorrespondencesEN::find()
            ->joinWith('iDEngine e')
            ->joinWith('iDMark ma')
            ->joinWith('iDModel mo');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['engine' => SORT_ASC],
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
                'engine' => [
                    'asc' => ['e.Name' => SORT_ASC],
                    'desc' => ['e.Name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'CarEngineAndModelCorrespondencesEN.ID_Mark' => $this->ID_Mark,
            'CarEngineAndModelCorrespondencesEN.ID_Engine' => $this->ID_Engine,
            'CarEngineAndModelCorrespondencesEN.ID_Model' => $this->ID_Model,
        ]);
        $query->andWhere('e.Name LIKE "%' . $this->engine . '%" ');

        return $dataProvider;
    }
}
