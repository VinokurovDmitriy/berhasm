<?php

namespace backend\forms\Shop;

use shop\helpers\CharacteristicHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Characteristic;
use shop\helpers\CategoriesListHelper;

class CharacteristicSearch extends Model
{
    public $id;
    public $category_id;
    public $name;
    public $type;
    public $required;
    public $main;

    public function rules(): array
    {
        return [
            [['id', 'category_id', 'type', 'required', 'main'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Characteristic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['ord' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'type' => $this->type,
            'required' => $this->required,
            'main' => $this->main,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function typesList(): array
    {
        return CharacteristicHelper::typeList();
    }

    public function requiredList(): array
    {
        return [
            1 => \Yii::$app->formatter->asBoolean(true),
            0 => \Yii::$app->formatter->asBoolean(false),
        ];
    }

    public function categoriesList(): array
    {
        return CategoriesListHelper::categoriesList();
    }
}