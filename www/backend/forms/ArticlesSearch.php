<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Articles;

/**
 * ArticlesSearch represents the model behind the search form of `shop\entities\Articles`.
 */
class ArticlesSearch extends Model
{
    public $title;
    public $date;
    public $status;
    public $main;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'main'], 'integer'],
            [['title', 'date'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Articles::find();

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
            'status' => $this->status,
            'main' => $this->main,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['>=', 'date', $this->date ? strtotime($this->date . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'date', $this->date ? strtotime($this->date . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
