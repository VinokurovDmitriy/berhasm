<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $product_id
 * @property string $code
 * @property string $name
 * @property string $price
 * @property int $quantity
 *
 * @property Product $product
 */
class Modification extends ActiveRecord
{
    public static function create($code, $name, $price, $quantity): self
    {
        $modification = new static();
        $modification->code = $code;
        $modification->name = $name;
        $modification->price = $price;
        $modification->quantity = $quantity;
        return $modification;
    }

    public function edit($code, $name, $price, $quantity): void
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function changeQty($quantity): void
    {
        $this->quantity = $quantity;
    }

    public function checkout($quantity): void
    {
//        if ($quantity > $this->quantity) {
//            throw new \DomainException('Доступно только ' . $this->quantity . ' едениц товара ' . $this->product->name . ', размера ' . $this->name);
//        }
        $this->quantity -= $quantity;
    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    public function isCodeEqualTo($code)
    {
        return $this->code == $code;
    }

    public function isNameEqualTo($name)
    {
        return $this->name == $name;
    }

    public static function tableName(): string
    {
        return '{{%shop_modifications}}';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Размер',
            'code' => 'Порядок',
            'price' => 'Цена',
            'quantity' => 'Количество',
        ];
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}