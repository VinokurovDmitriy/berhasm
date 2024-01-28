<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Orders;

class OrdersSearch extends Orders
{
    public function rules()
    {
        return [
            [['status', 'user_id', 'user_status'], 'integer'],
            [['name', 'email', 'phone', 'datetime'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getVariants()
    {
        return Orders::VARIANTS;
    }

    public function search($params)
    {
//        $query = Orders::find()->andWhere(['NOT', ['status' => 9]]);
        $query = Orders::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'status' => $this->status,
            'user_status' => $this->user_status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['>=', 'datetime', $this->datetime ? strtotime($this->datetime . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'datetime', $this->datetime ? strtotime($this->datetime . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
