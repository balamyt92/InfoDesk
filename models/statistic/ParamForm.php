<?php
namespace app\models\statistic;

use yii\base\Model;

class ParamForm extends Model
{
    public $id_firm;
	public $sections;
	public $operators;
	public $date_start;
	public $date_end;

    public function attributeLabels()
    {
        return [
            'id_firm' => 'Фирма',
            'sections' => 'Секции',
            'operators' => 'Операторы',
            'date_start' => 'Начальная дата',
            'date_end' => 'Конечная дата',
        ];
    }

	public function rules()
	{
		return [
			[['date_start', 'date_end', 'sections'], 'required'],
            [['operators', 'id_firm', 'date_start', 'date_end', 'sections'], 'safe']
		];
	}
}
