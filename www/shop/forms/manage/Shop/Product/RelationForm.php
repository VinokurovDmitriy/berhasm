<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class RelationForm extends Model
{
    private $productId;
    public $relationId;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->productId = $product->id;
        $this->_product = $product;
        $this->relationId = $product->relatedAssignments[0]->related_id;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['relationId'], 'required'],
            [['relationId'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'relationId' => 'Связанный товар',
        ];
    }

    public function relatesList(): array
    {
        $result = [];
        /** @var  $items Product[] */
        $items = Product::find()->orderBy('name_ru')
            ->andWhere(['name_ru' => $this->_product->name_ru])
            ->andWhere(['not', ['id' => $this->_product->id]])
            ->all();
        foreach ($items as $item) {
            $result[$item->id] = $item->name_ru . ' ' . $item->code;
        }
        return $result;
    }
}