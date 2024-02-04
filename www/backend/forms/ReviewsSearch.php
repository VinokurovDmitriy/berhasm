<?php

namespace backend\forms;

use yii\base\Model;
use shop\entities\Shop\Product\Review;
use yii\data\ActiveDataProvider;

class ReviewsSearch extends Model
{
    public $active;
    public $user_id;
    public $product_id;
    public $created_at;
    public $vote;

    public function rules()
    {
        return [
            [['active', 'vote'], 'integer'],
            [['user_id', 'product_id', 'created_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Review::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'active' => $this->active,
        ]);

        $query
            ->andFilterWhere(['>=', 'created_at', $this->created_at ? strtotime($this->created_at . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->created_at ? strtotime($this->created_at . ' 23:59:59') : null]);

        return $dataProvider;
    }
}