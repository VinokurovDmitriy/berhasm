<?php

namespace shop\forms\Shop;

use common\models\Countries;
use common\models\Delivery;
use common\models\Promocodes;
use shop\cart\Cart;
use shop\cart\CartItem;
use shop\entities\Shop\OrderItems;
use shop\entities\Shop\Product\Product;
//use shop\entities\User\User;
use Yii;
use yii\base\Model;
use shop\entities\Shop\Orders;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrderForm extends Model
{
    public $user_id;
    public $name;
    public $lastName;
    public $email;

    public $phone;
    public $address;
    public $country;
    public $city;
    public $company;
    public $zip;
//    public $date;
//    public $time;
    public $pay_method;
    public $delivery_method;
//    public $discount;
    public $remark;
    public $user_status;
    public $news;
    public $promoCode;

    private $_order;


    public function __construct(Orders $order = null, $config = [])
    {
        parent::__construct($config);
        if (!$order) {
            $this->pay_method = 'online';
        } else {
            $this->_order = $order;
            $this->name = $order->name;
            $this->lastName = $order->lastName;
            $this->email = $order->email;
            $this->phone = $order->phone;
            $this->address = $order->address;
            $this->country = $order->country;
            $this->city = $order->city;
            $this->zip = $order->zip;
            $this->pay_method = $order->pay_method;
            $this->delivery_method = $order->delivery_method;
            $this->remark = $order->remark;
            $this->news = $order->news;
            $this->promoCode = $order->promo_code;
        }
    }

    public function getPayMethods()
    {
        return [
//        'pay-pal' => Yii::t('app', 'PayPal'),
            'online' => Yii::t('app', 'Pay by card, Apple Pay, Google Pay'),
        'cash' => Yii::t('app', 'Payment on delivery (Moscow only)'),
        ];
    }

    public function rules()
    {
        return [
            [['name', 'lastName', 'address', 'country', 'city', 'zip', 'email', 'phone', 'pay_method', 'delivery_method'], 'required'],
            ['name', 'match', 'pattern' => '/^[А-Яа-яa-zA-ZЁё ]{2,255}$/u', 'message' => Yii::t('app', '{attribute} can contain only letters, numbers and space')],
            [['pay_method', 'delivery_method', 'country', 'news', 'discount'], 'safe'],
            [['delivery_method', 'news'], 'integer'],
//            [['discount'], 'integer'],
            [['remark'], 'string'],
            [['phone', 'zip', 'pay_method'], 'string', 'max' => 20],
            [['name', 'city', 'lastName', 'address'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['promoCode'], 'string', 'min' => 3, 'max' => 50],
            ['promoCode', 'match', 'pattern' => '/^[a-zA-Z1-9]{2,255}$/u', 'message' => Yii::t('app', '{attribute} can contain only letters and numbers')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => 'День',
            'time' => 'Время',
            'name' => Yii::t('app', 'Имя'),
            'lastName' => Yii::t('app', 'Фамилия'),
            'email' => 'E-mail',
            'phone' => Yii::t('app', 'Номер телефона'),
            'address' => Yii::t('app', 'Адрес'),
            'country' => Yii::t('app', 'Страна'),
            'city' => Yii::t('app', 'Город'),
            'zip' => Yii::t('app', 'Почтовый индекс'),
            'remark' => Yii::t('app', 'Комментарий'),
            'pay_method' => Yii::t('app', 'Способ оплаты'),
            'delivery_method' => Yii::t('app', 'Способ доставки'),
            'news' => Yii::t('app', 'Register for newsletter'),
            'promoCode' => Yii::t('app', 'Discount Code'),
        ];
    }

    public function create(Cart $cart)
    {
//        //Yii::$app->response->format = Response::FORMAT_JSON;
//        if ($this->_user) {
//            if (!$this->_user->phone) {
//                $this->_user->userProfile->phone = $this->phone;
//                $this->_user->userProfile->save();
//            }
//            if (!$this->_user->addresses[0]->value) {
//                $this->_user->addresses[0]->value = $this->address;
//                $this->_user->addresses[0]->save();
//            }
//        }
        $order = $this->_order ?: new Orders();
        $promo = null;
        if ($this->promoCode) {
            $promo = Promocodes::findOne(['code' => $this->promoCode, 'status' => 1]);
        }

        $order->user_status = $this->user_status;
        $order->name = $this->name;
        $order->lastName = $this->lastName;
        $order->email = $this->email;
        $order->phone = $this->phone;
        $order->address = $this->address;
        $order->country = $this->country;
        $order->city = $this->city;
        $order->zip = $this->zip;
//        $order->datetime = strtotime($this->date . ' ' . $this->time);
        $order->remark = $this->remark;
        $order->qty = $cart->getAmount();
        $order->sum = round($cart->getCost()->getTotal() - ($cart->getCost()->getTotal() * $promo->discount / 100), 0, PHP_ROUND_HALF_UP) + $this->delivery_method;
        $order->user_id = $this->user_id;
        $order->news = $this->news;
        $order->pay_method = $this->pay_method;
        $order->delivery_method = $this->delivery_method;
        $order->discount = $promo->discount;
        $order->promo_code = $this->promoCode;
        $order->cart_json = Json::encode($cart->getItems());
        $order->status = 9;
        $order->save();
//        $this->saveOrderItems($cart, $order->id);
        return $order;
    }

    protected function saveOrderItems(Cart $cart, int $order_id)
    {
        foreach ($cart->getItems() as $id => $item) {
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
            $order_item->order_id = $order_id;
            $order_item->product_id = $item->getProductId();
            $order_item->modification_id = $item->getModificationId();
            $order_item->title = $item->getProduct()->name;
            $order_item->qty_item = $item->getQuantity();
            $order_item->price_item = $item->getPrice();
            $order_item->save();
        }
    }

    public function getDeliveries()
    {
        return Delivery::find()->having(['status' => 1])->orderBy('sort')->all();
    }

    public function getDeliveriesArr()
    {
        return ArrayHelper::map($this->getDeliveries(), 'price', 'price');
    }

    private function getCountriesModels()
    {
        return Countries::getDb()->cache(function () {
            $title = 'title_' . Yii::$app->language;
            return Countries::find()->andWhere(['NOT', ['title_en' => 'Russia']])->having(['status' => 1])->orderBy($title)->all();
        }, Yii::$app->params['cacheTime']);
    }

    public function getDeliveryPrices()
    {
        return ArrayHelper::map($this->getCountriesModels(), 'title_en', 'delivery');
    }

    public function getCountries()
    {
        $title = 'title_' . Yii::$app->language;

        return array_merge(['Russia' => Yii::t('app', 'Russia')], ArrayHelper::map($this->getCountriesModels(), 'title_en', $title));
    }
}