<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use shop\entities\Shop\Tag;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;

//use shop\entities\Shop\Product\Modification;

class CatalogSearchForm extends Model
{
    public function __construct($categoryId = null, array $config = [])
    {
        parent::__construct($config);
        if ($categoryId) {
            $this->cat = $categoryId;
            $category = Category::findOne($categoryId);
            $this->ids = ArrayHelper::merge([$categoryId], $category->getLeaves()->select('id')->column());
        }
    }

    public $cat; //category
    public $prl; //price low
    public $prh; //price high
    public $mod; //modification
    public $tag; //tag
    public $sale; //sale

    public $sort; // Sort by price or Novelty
    private $ids;

    public function rules()
    {
        return [
            [['prl', 'prh'], 'string'],
            [['prl', 'prh', 'ch', 'mod', 'br', 'tag', 'cat', 'cou', 'sort', 'sale'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Product::find()->alias('p')->with('mainPhoto')->with('secondaryPhoto')->with(['tagAssignments', 'modifications']);
        $query->having(['status' => 1]);
        if ($this->ids) {
            $query->andWhere(['or', ['p.category_id' => $this->ids], ['ca.category_id' => $this->ids]]);
        }
        $query->joinWith(['tagAssignments ta'], false);
        $query->joinWith(['categoryAssignments ca'], false);
//        $query->joinWith(['values va'], false);
        $query->joinWith(['modifications mod'], false);
        $query->groupBy('p.id');

        $name = 'name_' . \Yii::$app->language;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12
            ],
            'sort' => [
                'defaultOrder' => ['alphabet' => SORT_ASC],
                'attributes' => [
                    'price_min' => [
                        'asc' => ['price_new' => SORT_ASC],
                        'desc' => ['price_new' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'date' => [
                        'asc' => ['updated_at' => SORT_ASC],
                        'desc' => ['updated_at' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'alphabet' => [
                        'asc' => ['p.' . $name => SORT_ASC],
                        'desc' => ['p.' . $name => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                ]
            ],
        ]);

        if (!$params) return $dataProvider;


        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        if ($this->prl) {
            $query->andFilterCompare('p.price_new', $this->prl, '>=');
        }

        if ($this->prh) {
            $query->andFilterCompare('p.price_new', $this->prh, '<=');
        }

        if ($this->mod) {
            $query->andWhere(['or like', 'mod.name', $this->mod]);
            $query->groupBy('p.id');
        }

        if ($this->tag) {
            $query->andWhere(['ta.tag_id' => $this->tag]);
            $query->groupBy('p.id');
        }
        if (($this->tag == null) && ($this->sale == null)) {
            $query->andWhere(['not', ['ta.tag_id' => 2]]);
            $query->groupBy('p.id');
        }

        if ($this->sale) {
            $query->andWhere(['not', ['p.price_old' => null]]);
            $query->groupBy('p.id');
        }

        return $dataProvider;
    }

    public function getTags()
    {
        $activeTags = Tag::find()->alias('t')->joinWith('products p')->andWhere(['p.status' => 1])->andWhere(['>', 'p.quantity', 0])->groupBy('t.id')->all();
        $tags = ArrayHelper::map($activeTags, 'id', 'name');
        asort($tags);
        return $tags;
    }

    public function getMinMax()
    {
        $minMax = Product::find()->andWhere(['status' => 1, 'category_id' => $this->ids])->select(['MIN(price_new)', 'MAX(price_new)'])->createCommand()->queryOne();
        return $minMax;
    }
}