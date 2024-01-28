<?php

namespace shop\entities\Shop;

use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Product;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order_items}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $modification_id
 * @property string $title
 * @property int $qty_item
 * @property int $price_item
 *
 * @property Orders $order
 * @property Product $product
 * @property Modification $modification
 */
class OrderItems extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'qty_item', 'title'], 'safe'],
            [['order_id', 'product_id', 'qty_item', 'price_item'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['modification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modification::className(), 'targetAttribute' => ['modification_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModification()
    {
        return $this->hasOne(Modification::className(), ['id' => 'modification_id']);
    }
}