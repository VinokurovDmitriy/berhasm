<?php

namespace backend\forms\Shop;

use shop\entities\Shop\Brand;
use shop\entities\Shop\Category;
use shop\helpers\ProductHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Product\Product;
use yii\helpers\ArrayHelper;

class ProductSearch extends Model
{
    public $id;
    public $code;
    public $name_ru;
    public $category_id;
    public $brand_id;
    public $quantity;
    public $status;
    public $sale;

    public function rules(): array
    {
        return [
            [['id', 'category_id', 'brand_id', 'status', 'quantity'], 'integer'],
            [['code', 'name_ru', 'sale'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Product::find()->alias('p')->with('mainPhoto')->with('secondaryPhoto')->with(['tagAssignments', 'modifications']);
//        $query->having(['status' => 1]);
        $query->joinWith(['tagAssignments ta'], false);
        $query->joinWith(['categoryAssignments ca'], false);
//        $query->joinWith(['values va'], false);
        $query->joinWith(['modifications mod'], false);
        $query->groupBy('p.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['sort' => SORT_ASC]
            ],
            'pagination' => [
                'pageSizeLimit' => [20, 100],
                'defaultPageSize' => 100,
            ]
        ]);

        $this->load($params);

        if ($this->category_id) {
            $category = Category::findOne($this->category_id);
            $ids = ArrayHelper::merge([$this->category_id], $category->getLeaves()->select('id')->column());
            $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        }

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'p.id' => $this->id,
            'p.brand_id' => $this->brand_id,
            'p.status' => $this->status,
            'p.quantity' => $this->quantity,
        ]);

        if ($this->sale == 2) {
            $query->andFilterWhere(['not', ['price_old' => 0]]);
        }

        if ($this->sale == 1) {
            $query->andWhere(['price_old' => null]);
        }

        $query
            ->andFilterWhere(['like', 'p.code', $this->code])
            ->andFilterWhere(['like', 'p.name_ru', $this->name_ru]);

        return $dataProvider;
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name_ru'];
        });
    }

    public function statusList(): array
    {
        return ProductHelper::statusList();
    }

    public function brandList()
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->all(), 'id', 'name');
    }
}
