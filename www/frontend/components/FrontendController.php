<?php

namespace frontend\components;

use Yii;
use common\models\Seo;
use yii\web\Controller;

class FrontendController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@frontend/views/common/error.php'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    protected function setMetAndGetHeader($action)
    {
        $seo = Seo::getDb()->cache(function () use ($action) {
            return Seo::findOne(['page' => $action]);
        }, Yii::$app->params['cacheTime']);
        $this->setMeta($seo->title ?: $action, $seo->description, $seo->keywords);
//        return $header = Headers::getDb()->cache(function () use ($action) {
//            return Headers::findOne(['page' => $action]);
//        }, Yii::$app->params['cacheTime']);
    }

    protected function setMeta($title = null, $description = null, $keywords = null)
    {
        $this->view->title = $title;
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => "$keywords"]);
        $this->view->registerMetaTag(['name' => 'description', 'content' => "$description"]);
    }
}