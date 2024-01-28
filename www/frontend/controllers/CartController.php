<?php

namespace frontend\controllers;

use shop\cart\Cart;
use shop\entities\Shop\Orders;
use shop\forms\Shop\AddToCartForm;
use shop\forms\Shop\OrderForm;
use shop\readModels\Shop\ProductReadRepository;
use shop\services\Shop\CartService;
use Yii;
use frontend\components\FrontendController;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class CartController extends FrontendController
{

    private $products;
    private $service;
    private $cart;

    public function __construct($id, $module, CartService $service, ProductReadRepository $products, Cart $cart, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->products = $products;
        $this->cart = $cart;
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = ($action->id !== 'remove');
        return parent::beforeAction($action);
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'quantity' => ['POST'],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $this->setMeta(Yii::t('app', 'Корзина'));
        $cart = $this->service->getCart();
        if ($cart->getAmount() != null) {
            Yii::$app->session->set('cartStatus', 'active');
            return $this->redirect(Yii::$app->request->referrer);
//            return $this->render('index', [
//                'cart' => $cart,
//            ]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Корзина пуста'));
            return $this->redirect(Yii::$app->request->referrer);
//            return $this->redirect(['/']);
        }
    }

    public function actionCheckout()
    {
        $this->setMeta(Yii::t('app', 'Оформление заказа'));
        $checkoutForm = new OrderForm();
        if ($checkoutForm->load(Yii::$app->request->post()) && $checkoutForm->validate()) {
            if ($order = $checkoutForm->create($this->cart)) {
                if ($order->pay_method == 'pay-pal') {
                    return $this->redirect(['pay-pal-payment', 'orderId' => $order->id]);
                } elseif ($order->pay_method == 'online') {
                    return $this->redirect(['online-payment', 'orderId' => $order->id]);
                } elseif ($order->pay_method == 'cash') {
                    return $this->redirect(['end-payment', 'orderId' => $order->id]);
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Произошла ошибка'));
            }
        }
        return $this->render('checkout', [
            'cart' => $this->cart,
            'model' => $checkoutForm,
        ]);
    }

    public function actionOnlinePayment()
    {
        var_dump('someaction');
    }

    public function actionPayPalPayment()
    {
        var_dump('someaction');
    }

    public function actionEndPayment(int $orderId)
    {
        $order = Orders::findOne($orderId);
        Yii::$app->mailer->compose('customer_order', [
            'cart' => $this->cart,
        ])
            ->setFrom([Yii::$app->params['siteEmail']])
            ->setTo([$order->email => $order->name])
            ->setSubject('Заказ товаров с сайта ' . Yii::$app->homeUrl)
            ->send();
        Yii::$app->mailer->compose('admin_order', [
            'cart' => $this->cart,
            'order' => $order,
        ])->setFrom([Yii::$app->params['siteEmail'] => Yii::$app->name])
            ->setTo([Yii::$app->params['adminEmail']])
            ->setSubject('Заказ от ' . $order->name)
            ->send();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Ваш заказ принят'));
        $this->cart->clear();
        return $this->redirect('/');
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException;
     */
    public function actionAdd($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $form = new AddToCartForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->add($product->id, $form->modification, $form->quantity);
                Yii::$app->session->set('cartStatus', 'active');
                return $this->redirect(Yii::$app->request->referrer);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->render('add', [
            'product' => $product,
            'model' => $form,
        ]);
    }

    public function actionQuantity($id)
    {
        try {
            $this->service->set($id, (int)Yii::$app->request->post('quantity'));
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionPlus($id)
    {
        try {
            $this->service->plus($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionMinus($id)
    {
        try {
            $this->service->minus($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionRemove($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionClear()
    {
        $this->cart->clear();

        return $this->redirect(['site/index']);
    }

}