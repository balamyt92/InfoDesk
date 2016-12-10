<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServicePresence;
use yii\helpers\ArrayHelper;

/**
 * ServicePresenceSearch represents the model behind the search form of `app\models\ServicePresence`.
 */
class ServicePresenceSearch extends ServicePresence
{
    public $iDservice;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_Service', 'ID_Firm'], 'integer'],
            [['Comment', 'CarList', 'Coast', 'iDService'], 'safe'],
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
     * @param array $pagination
     *
     * @return ActiveDataProvider
     */
    public function search($params, array $pagination = [ 'pageSize' => 100 ])
    {
        $query = ServicePresence::find()
            ->joinWith(['iDService'])
            ->groupBy([
                'ServicePresence.ID_Service',
                'ServicePresence.Comment',
                'ServicePresence.CarList',
            ]);;

        $config = [
            'query' => $query,
        ];
        $config['pagination'] = $pagination;

        $dataProvider = new ActiveDataProvider($config);

        $dataProvider->setSort([
            'attributes' => [
                'ID_Service' => [
                    'asc' => ['Services.Name' => SORT_ASC],
                    'desc' => ['Services.Name' => SORT_DESC],
                    'label' => 'Услуга',
                    'default' => SORT_ASC
                ],
                'Comment',
                'CarList',
                'Coast'
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
            'ID_Service' => $this->ID_Service,
            'ID_Firm' => $params['id'],
        ]);

        $query->andFilterWhere(['like', 'Comment', $this->Comment])
            ->andFilterWhere(['like', 'CarList', $this->CarList])
            ->andFilterWhere(['like', 'Coast', $this->Coast]);

        return $dataProvider;
    }

    public function getServicesName($id)
    {
        return ArrayHelper::map(
            ServicePresence::find()
                ->select('*')
                ->joinWith('iDService s')
                ->andFilterWhere([
                    'ID_Firm' => $id,
                ])
                ->groupBy('Name')
                ->orderBy('Name')
                ->asArray()->all(),
            'id', 'Name');
    }
}
