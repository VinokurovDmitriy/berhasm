<?php

namespace shop\forms\Shop;

use shop\entities\Shop\Product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AddToCartForm extends Model
{
    public $modification;
    public $quantity;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->_product = $product;
        $this->quantity = 1;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return array_filter([
            $this->_product->modifications ? ['modification', 'required', 'message' => \Yii::t('app', 'Выберите {attribute}')] : false,
            ['quantity', 'required'],
            ['quantity', 'integer'],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'modification' => \Yii::t('app', 'Size'),
        ];
    }

    public function modificationsList(): array
    {
        $return = [];
        foreach ($this->_product->modifications as $modification) {
            $return[$modification->id] = $modification->name;
        }
        return $return;
//        return ArrayHelper::map($this->_product->modifications, 'id', 'name');
    }

    public function quantities()
    {
        return $this->_product->modifications ? ArrayHelper::map($this->_product->modifications, 'id', 'quantity') : $this->_product->quantity;
    }
}