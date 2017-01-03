<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CarModelsEnSearch represents the model behind the search form of `app\models\CarModelsEN`.
 */
class CarModelsEnSearch extends CarModelsEN
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ID_Mark', 'ID_Type'], 'integer'],
            [['Name'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CarModelsEN::find()->joinWith('iDType t');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => false,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'CarModelsEN.id'      => $this->id,
            'CarModelsEN.ID_Mark' => $params['ID_Mark'],
            'CarModelsEN.ID_Type' => $this->ID_Type,
        ]);

        $query->andFilterWhere(['like', 'CarModelsEN.Name', $this->Name]);

        $query->orderBy(['Name' => SORT_ASC]);

        return $dataProvider;
    }
}
