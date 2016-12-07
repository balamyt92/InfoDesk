<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FirmsSearch represents the model behind the search form about `app\models\Firms`.
 */
class FirmsSearch extends Firms
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'Priority'], 'integer'],
            [['Enabled'], 'boolean'],
            [['Name', 'Address', 'Phone', 'Comment', 'ActivityType', 'OrganizationType', 'District', 'Fax', 'Email', 'URL', 'OperatingMode', 'Identifier'], 'safe'],
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
        $query = Firms::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
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
            'Enabled'  => $this->Enabled,
            'id'       => $this->id,
            'Priority' => $this->Priority,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Address', $this->Address])
            ->andFilterWhere(['like', 'Phone', $this->Phone])
            ->andFilterWhere(['like', 'Comment', $this->Comment])
            ->andFilterWhere(['like', 'ActivityType', $this->ActivityType])
            ->andFilterWhere(['like', 'OrganizationType', $this->OrganizationType])
            ->andFilterWhere(['like', 'District', $this->District])
            ->andFilterWhere(['like', 'Fax', $this->Fax])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'URL', $this->URL])
            ->andFilterWhere(['like', 'OperatingMode', $this->OperatingMode])
            ->andFilterWhere(['like', 'Identifier', $this->Identifier]);

        return $dataProvider;
    }
}
