<?php

namespace frontend\controllers;

use common\models\Countries;
use common\models\Delivery;
use common\models\Modules;
use common\models\Promocodes;
use shop\helpers\PriceHelper;
use yii\helpers\Url;
use shop\cart\Cart;
use shop\entities\Shop\Orders;
use shop\forms\Shop\OrderForm;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\httpclient\Client;

class OrderController extends Controller
{
    private $cart;

    public function __construct(string $id, Module $module, Cart $cart, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->cart = $cart;
    }

    protected function setMeta($title = null, $keywords = null, $description = null)
    {
        $this->view->title = $title;
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => "$keywords"]);
        $this->view->registerMetaTag(['name' => 'description', 'content' => "$description"]);
    }

    public function actionCheckout()
    {
        if ($this->cart->getAmount() == null) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Корзина пуста'));
            return $this->redirect(['/']);
        }
        $this->setMeta(Yii::t('app', 'Checkout'));
        $ord = $_SESSION['orderId'] ? Orders::findOne($_SESSION['orderId']) : null;

        $checkoutForm = new OrderForm($ord);
        if ($this->cart->getCost()->getTotal() >= Yii::$app->params['freeShipment']) {
            $checkoutForm->delivery_method = 0;
        }

        if ($checkoutForm->load(Yii::$app->request->post()) && $checkoutForm->validate()) {
            if ($order = $checkoutForm->create($this->cart)) {
                $_SESSION['orderId'] = $order->id;
                if ($order->pay_method == 'card') {
                    return $this->redirect(['credit-card-payment', 'orderId' => $order->id]);
                } elseif ($order->pay_method == 'online') {
                    return $this->redirect(['online-payment', 'orderId' => $order->id]);
                } elseif ($order->pay_method == 'cash') {
                    return $this->redirect(['end-payment', 'orderId' => $order->id]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Произошла ошибка');
            }
        }
        return $this->render('checkout', [
            'cart' => $this->cart,
            'model' => $checkoutForm,
        ]);
    }

    public function actionOnlinePayment($orderId)
    {
        if (!$order = Orders::findOne($orderId)) {
            throw new NotFoundHttpException(Yii::t('app', 'Запрошенная вами страница не существует.'));
        }
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
            'baseUrl' => Yii::$app->params['paymentUrl']
        ]);
        $string = 'SessionType=' . 'Pay' . ';';
        $string .= 'OrderId=' . Yii::$app->security->generateRandomString(8) . ';';
        $string .= 'Amount=' . ($order->sum * 100) . ';';
        $string .= 'Total=' . PriceHelper::format($order->sum) . ';';
        $string .= 'Product=' . Yii::t('app', 'goods from berhasm.com') . ';';
        $string .= 'IP=' . Yii::$app->params['IP'] . ';';
        $string .= 'Url=' . Yii::$app->urlManager->hostInfo . Url::to(['/order/end-payment', 'orderId' => $orderId]) . ';';
        $string .= 'Language=' . Yii::$app->language . ';';
        $data = urlencode($string);

        $request = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('Init')
            ->setData([
                'Key' => Yii::$app->params['key'],
                'Data' => $data
            ]);

        $result = $client->send($request);
        if ($result->isOk) {
            $xml = htmlspecialchars_decode($result->content);
            $xml = simplexml_load_string($xml);
            if ($xml->attributes()->Success == 'True') {
                $url = Yii::$app->params['paymentUrl'] . 'Pay?SessionId=' . (string)$xml->attributes()->SessionId;
                return $this->redirect($url);
            }
        }
        Yii::$app->session->setFlash('error', Yii::t('app', 'A payment service error occurred'));
        return $this->redirect(['/order/checkout', 'orderId' => $orderId]);
    }

    public function actionEndPayment(int $orderId)
    {
        $order = Orders::findOne($orderId);
        $order->saveOrderItems($this->cart);
        if ($order->news) {
            $this->setMailChimp($order);
        }
        $country = Countries::findOne(['title_en' => $order->country]);

        Yii::$app->mailer->compose('customer_order', [
            'cart' => $this->cart,
            'order' => $order,
            'country' => $country,
        ])->setFrom([Yii::$app->params['siteEmail']])
            ->setTo([$order->email => $order->name])
            ->setSubject('Заказ товаров с сайта ' . Yii::$app->urlManager->hostInfo)
            ->send();

        Yii::$app->mailer->compose('admin_order', [
            'cart' => $this->cart,
            'order' => $order,
            'country' => $country,
        ])->setFrom([Yii::$app->params['siteEmail'] => Yii::$app->name])
            ->setTo([Yii::$app->params['adminEmail']])
            ->setSubject('Заказ от ' . $order->name)
            ->send();

//        Yii::$app->session->setFlash('success', 'Ваш заказ принят');
        $order->status = 0;
        $order->save();
        $_SESSION['orderId'] = null;
        $this->cart->clear();
        return $this->redirect(['/order/success', 'id' => $orderId]);
    }

    public function actionSuccess($id)
    {
        if (!$order = Orders::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('app', 'Запрошенная вами страница не существует.'));
        }
        $this->setMeta(Yii::t('app', 'Success'));
        $module = Modules::findOne(5);
        return $this->render('success', [
            'order' => $order,
            'module' => $module,
        ]);
    }

    private function setMailChimp(Orders $order)
    {
        $client = new Client([
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
            'transport' => 'yii\httpclient\CurlTransport',
            'baseUrl' => 'https://us20.api.mailchimp.com/3.0/',
        ]);
        $request = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('lists/83eb20ca70/members')
            ->setHeaders([
                'authorization' => 'Basic QmVyaGFzbTo0NTlkYjE4MWNiMmEwOTQzYjQwNzJmOTUxM2QwZDMzMS11czIw',
                'content-type' => 'application/json'
            ])
            ->setData([
                'email_address' => $order->email,
                'status' => 'subscribed',
                'language' => Yii::$app->language,
                'merge_fields' => [
                    'FNAME' => $order->name,
                    'LNAME' => $order->lastName,
                    'PHONE' => $order->phone,
                ]

            ]);
        $client->send($request);
    }

    public function actionRadioButtons($country)
    {
        $html = "<p>".Yii::t('app', 'Free shipment from ') . '<span>' . PriceHelper::format(Yii::$app->params['freeShipment']) . '</span>'."</p>";
        if (intval($this->cart->getCost()->getTotal()) > Yii::$app->params['freeShipment']) {
            $html .= '<div class="delivery_method_input hidden">
                         <div class="delivery_method_radio_wrap">
                            <input type="radio" class="delivery_method_radio" id="delivery_method-free" name="delivery" value="0" checked />
                         </div>
                         <label for="delivery_method-free"></label>
                     </div>';
        } else {
            if ($country == 'Russia') {
                /* @var $deliveries Delivery[] */
                /* @var $ord Orders */
                $ord = $_SESSION['orderId'] ? Orders::findOne($_SESSION['orderId']) : null;
                $deliveries = Delivery::getDb()->cache(function () {
                    return Delivery::find()->having(['status' => 1])->orderBy('sort')->all();
                }, Yii::$app->params['cacheTime']);
                //$html = null;
                foreach ($deliveries as $delivery) {
                    $checked = $ord->delivery_method == $delivery->price ? 'checked' : '';
                    $html .= '<div class="delivery_method_input">
                         <div class="delivery_method_radio_wrap">
                            <input type="radio" class="delivery_method_radio" id="delivery_method-' . $delivery->title_en . '" name="delivery" value="' . $delivery->price . '" ' . $checked . ' />
                         </div>
                         <label for="delivery_method-' . $delivery->title_en . '">' . PriceHelper::format($delivery->price) . ' ' . $delivery->title . '</label>
                     </div>';
                }
            } else {
                /* @var $cou Countries */
                $cou = Countries::getDb()->cache(function () use ($country) {
                    return Countries::findOne(['title_en' => $country]);
                });

                $html .= '<div class="delivery_method_input">
                         <div class="delivery_method_radio_wrap">
                            <input type="radio" class="delivery_method_radio" id="delivery_method-' . $cou->title_en . '" name="delivery" value="' . $cou->delivery . '" checked />
                         </div>
                         <label for="delivery_method-' . $cou->title_en . '">' . PriceHelper::format($cou->delivery) . '  ' . Yii::t('app', 'Shipment to ') . preg_replace(['/а\z/', '/я\z/'], ['у', 'ю'], $cou->title) . '</label>
                     </div>';
            }
        }
        return $html;
    }

    public function actionPromoCode($code = null)
    {


        if (!$code) {
            Yii::$app->session->set('discount', 0);
            return 'INVALID';
        } else {
            if($code === 'clear'){Yii::$app->session->destroy();return 'INVALID';}
            if ($code = Promocodes::findOne(['code' => $code, 'status' => 1])) {
                if ((Yii::$app->session->get('discount') != 0) && (Yii::$app->session->get('discount') >= $code->discount)) return 'exist';
                Yii::$app->session->set('discount', $code->discount);
                return $code->discount;
            } else {
                Yii::$app->session->set('discount', 0);
                return 'INVALID';
            }
        }
    }

    public function actionGetValues($delivery = null, $discount = null)
    {
        if ($delivery == 0 || !$delivery) {
            $message = $this->cart->getCost()->getTotal() >= Yii::$app->params['freeShipment'] ? Yii::t('app', 'Free') : Yii::t('app', 'Not yet calculated');
        } else {
            $message = PriceHelper::format($delivery);
        }
        $return['shipment'] = $message;
        $return['discount'] = $discount ? $discount . '%' : '';
        $return['total'] = PriceHelper::format(($this->cart->getCost()->getTotal()) - ($this->cart->getCost()->getTotal() * $discount / 100) + $delivery);
        return json_encode($return);
    }
}
