<?php

namespace shop\forms\manage\Shop\Product;

use shop\helpers\CategoriesListHelper;
use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoriesForm extends Model
{
    public $main;
    public $others = [];

    public function __construct(Product $product = null, Characteristic $characteristic = null, $config = [])
    {
        if ($product) {
            $this->main = $product->category_id;
            $this->others = ArrayHelper::getColumn($product->categoryAssignments, 'category_id');
        }
        if ($characteristic) {
            $this->main = $characteristic->category_id;
        }
        parent::__construct($config);
    }

    public function categoriesList(): array
    {
        return CategoriesListHelper::categoriesList();
    }

    public function rules()
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'each', 'rule' => ['integer']],
        ];
    }

    public function beforeValidate(): bool
    {
        $this->others = array_filter((array)$this->others);
        return parent::beforeValidate();
    }
}