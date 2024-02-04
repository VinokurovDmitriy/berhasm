<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Product\Modification;
use yii\base\Model;

class ModificationForm extends Model
{
    public $code;
    public $name;
    public $price;
    public $quantity;
    public $product_id;

    public function __construct(Modification $modification = null, $config = [])
    {
        if ($modification) {
            $this->code = $modification->code;
            $this->name = $modification->name;
            $this->price = $modification->price;
            $this->quantity = $modification->quantity;
            $this->product_id = $modification->product_id;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'quantity', 'code'], 'required'],
//            [['product_id', 'code'], 'unique', 'targetClass' => Modification::class, 'targetAttribute' => ['product_id', 'code'], 'message' => 'Для этого товара уже задан размер с таким порядком'],
            [['price'], 'integer'],
            [['code'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'name' => 'Размер',
          'quantity' => 'Количество',
          'code' => 'Порядок',
        ];
    }
}