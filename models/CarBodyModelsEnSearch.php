<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CarBodyModelsEN;

/**
 * CarBodyModelsEnSearch represents the model behind the search form of `app\models\CarBodyModelsEN`.
 */
class CarBodyModelsEnSearch extends CarBodyModelsEN
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ID_Mark', 'ID_Model', 'ID_Type'], 'integer'],
            [['Name'], 'safe'],
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
        $query = CarBodyModelsEN::find()
            ->joinWith('iDType t')
            ->joinWith('iDModel mo')
            ->joinWith('iDMark ma');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
            'sort'       => [
                'defaultOrder' => ['ID_Model' => SORT_ASC,'Name' => SORT_ASC],
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
            'CarBodyModelsEN.id' => $this->id,
            'CarBodyModelsEN.ID_Mark' => $params['ID_Mark'],
            'CarBodyModelsEN.ID_Model' => $this->ID_Model,
            'CarBodyModelsEN.ID_Type' => $this->ID_Type,
        ]);

        $query->andFilterWhere(['like', 'CarBodyModelsEN.Name', $this->Name]);

        return $dataProvider;
    }
}
