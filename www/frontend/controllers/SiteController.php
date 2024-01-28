<?php

namespace frontend\controllers;

use common\models\Contacts;
use common\models\CustomerCare;
use common\models\GalleryCategories;
use common\models\IndexLink;
use common\models\IndexLinks;
use common\models\Modules;
use common\models\Press;
use common\models\Slider;
use common\models\Sounds;
use common\models\StockistsCategories;
use Yii;
use common\models\LoginForm;
use frontend\components\FrontendController;
use yii\helpers\Html;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class SiteController extends FrontendController
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionCurrency($currency)
    {
        Yii::$app->response->cookies->add(
            new Cookie([
                'name' => 'currency',
                'value' => $currency,
            ])
        );
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAgreement()
    {
        Yii::$app->response->cookies->add(
            new Cookie([
                'name' => 'agreement',
                'value' => 1,
            ])
        );
        return $this->redirect(['site/index']);
    }

    public function actionRemoveAgreement()
    {
        Yii::$app->response->cookies->remove('agreement');
        return $this->redirect(['site/index']);
    }

    public function actionCookiePolicy()
    {
        $this->setMetAndGetHeader('cookie');
        $module = Modules::getDb()->cache(function () {
            return Modules::findOne(4);
        }, Yii::$app->params['cacheTime']);
        return $this->render('cookie', [
            'module' => $module,
        ]);
    }

    public function actionIndex()
    {
        $this->setMetAndGetHeader('index');
        if (!Yii::$app->request->cookies->get('agreement')) {
            Yii::$app->session->setFlash('info',
                '<p>' . Yii::t('app', 'We use cookies to offer you a better browsing experience, analyse site traffic, personalise content.')
                . ' ' . Html::a(Yii::t('app', 'Read about how we use cookies.'), ['site/cookie-policy'])
                . ' ' . Yii::t('app', 'If you continue to use this site, you consent to our use of cookies.') . '</p>'
                . '   ' . Html::a('Ok', ['site/agreement'], ['class' => 'btn black']));
        }
        $indexLinks = IndexLinks::find()->having(['status' => 1])->orderBy('sort')->all();
        $link = IndexLink::findOne(1);
        $file = Slider::findOne(1);
        return $this->render('index', [
            'indexLinks' => $indexLinks,
            'link' => $link,
            'file' => $file,
        ]);
    }

    public function actionUnderConstruction()
    {
        $this->setMetAndGetHeader('index');
        return $this->render('construction');
    }

    public function actionSounds()
    {
        $this->setMetAndGetHeader('sounds');
        $sounds = Sounds::getDb()->cache(function () {
            return Sounds::find()->having(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        return $this->render('sounds', [
            'sounds' => $sounds,
        ]);
    }

    public function actionAbout()
    {
        $this->setMetAndGetHeader('about');
        $module = Modules::getDb()->cache(function () {
            return Modules::findOne(1);
        }, Yii::$app->params['cacheTime']);
        return $this->render('about', [
            'content' => $module->html,
        ]);
    }

    public function actionContacts()
    {
        $this->setMetAndGetHeader('contacts');
        $contacts = Contacts::getDb()->cache(function () {
            return Contacts::find()->having(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        return $this->render('contacts', [
            'contacts' => $contacts,
        ]);
    }

    public function actionGalleries()
    {
        $this->setMetAndGetHeader('gallery');
        $galleries = GalleryCategories::getDb()->cache(function () {
            return GalleryCategories::find()->having(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        return $this->render('galleries', [
            'galleries' => $galleries,
        ]);
    }

    public function actionStockists()
    {
        $this->setMetAndGetHeader('stockists');
        $regions = StockistsCategories::getDb()->cache(function () {
            return StockistsCategories::find()->having(['status' => 1])->with('activeStockistsItems')->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        return $this->render('stockists', [
            'regions' => $regions,
        ]);
    }

    public function actionPress()
    {
        $this->setMetAndGetHeader('press');
        $press = Press::getDb()->cache(function () {
            return Press::find()->having(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        return $this->render('press', [
            'press' => $press,
        ]);
    }

    public function actionCustomerCare()
    {
        $this->setMetAndGetHeader('customer-care');
        $cares = CustomerCare::getDb()->cache(function () {
            return CustomerCare::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        return $this->render('customer-care', [
            'cares' => $cares,
        ]);
    }

    public function actionReturn()
    {
        $this->setMetAndGetHeader('return');
        $module = Modules::getDb()->cache(function () {
            return Modules::findOne(3);
        }, Yii::$app->params['cacheTime']);
        return $this->render('page', [
            'title' => $module->title,
            'content' => $module->html,
        ]);
    }

    public function actionGallery($slug)
    {
        if (!$gallery = GalleryCategories::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $this->render('gallery', [
            'gallery' => $gallery,
        ]);
    }

    public function actionEmailTest()
    {
        $message = Yii::$app->mailer->compose('admin_feedback', [
            'user_name' => 'name',
            'user_email' => 'email',
            'user_phone' => 'phone',
            'message' => 'message',
        ])->setFrom([Yii::$app->params['siteEmail'] => Yii::$app->name])
            ->setTo([Yii::$app->params['adminEmail']]);
        return $message->send();
    }
}
