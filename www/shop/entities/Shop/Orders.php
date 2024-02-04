<?php

namespace shop\entities\Shop;

use shop\cart\Cart;
use shop\cart\CartItem;
use shop\entities\Shop\Product\Product;
use shop\entities\User\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $qty
 * @property int $sum
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $name
 * @property string $lastName
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $country
 * @property string $city
 * @property string $zip
 *
 * @property int $status
 * @property string $pay_method
 * @property string $promo_code
 * @property int $delivery_method
 * @property int $discount
 * @property int $news
 * @property int $user_status
 * @property string $datetime
 * @property string $remark
 * @property string $cart_json
 *
 * @property OrderItems[] $orderItems
 * @property User $user
 */
class Orders extends ActiveRecord
{
    public $date;
    public $time;

    const VARIANTS = [
        '0' => 'В обработке',
        '1' => 'На доставке',
        '2' => 'Доставлено',
//        '3' => 'Ожидание самовывоза',
//        '4' => 'Самовывоз состоялся',
        '5' => 'Отказ',
        '9' => 'Не оплачен',
        '10' => 'Оплачен',
    ];

    const COLORS = [
        '0' => '#a2a2a2',
        '1' => '#f39d0a',
        '2' => '#00a75a',
        '3' => '#f39d0a',
        '4' => '#00a75a',
        '5' => '#aa4240',
        '9' => '#f77',
        '10' => '#01d774',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'pay_method'], 'required'],
            [['remark', 'date', 'time', 'promo_code'], 'string'],
            [['phone', 'pay_method'], 'string', 'max' => 20],
            [['discount'], 'integer'],
            [['name', 'lastName', 'country', 'city', 'zip', 'email', 'address'], 'string', 'max' => 255],
            [['email'], 'email'],
//            [['date', 'time'], 'required', 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qty' => 'Количество товаров',
            'sum' => 'Сумма',
            'created_at' => 'Оформлен',
            'updated_at' => 'Обработан',
            'datetime' => 'Время доставки',
            'date' => 'День',
            'time' => 'Время',
            'name' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'address' => 'Адрес доставки',
            'remark' => 'Комментарии',
            'status' => 'Статус',
            'user_status' => 'Доступно к удалению',
            'pay_method' => 'Способ оплаты',
            'delivery_method' => 'Способ оплаты',
            'promo_code' => 'Промокод',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['order_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function saveOrderItems(Cart $cart)
    {
        foreach ($cart->getItems() as $id => $item) {
            /* @var $item CartItem */
            $product = Product::findOne($item->getProductId());
            $product->checkout($item->getModificationId(), $item->getQuantity());
            if (count($product->getRelateds()->all()) > 0) {
                /* @var $related Product */
                $related = $product->getRelateds()->all()[0];
                $modification = $item->getModification();
                try {
                    $relMod = $related->getModificationByName($modification->name);
                    $related->checkout($relMod->id, $item->getQuantity());
                    $related->save();
                } catch (\DomainException $e) {
                }
            }
            $product->save();
            $order_item = new OrderItems();
            $order_item->order_id = $this->id;
            $order_item->product_id = $item->getProductId();
            $order_item->modification_id = $item->getModificationId();
            $order_item->title = $item->getProduct()->name;
            $order_item->qty_item = $item->getQuantity();
            $order_item->price_item = $item->getPrice();
            $order_item->save();
        }
    }
}