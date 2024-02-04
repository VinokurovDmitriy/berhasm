<?php

namespace frontend\controllers;

use shop\entities\Shop\Product\Product;
use Yii;
use frontend\components\FrontendController;
use yii\helpers\Html;
use yii\web\Response;
use yii\helpers\FileHelper;
use shop\entities\Shop\Category;
use yii\web\NotFoundHttpException;

class ExportController extends FrontendController
{

    public function actionProducts()
    {

        $products = Product::find()->having(['status' => 1])->with('mainPhoto')->with('category')->orderBy('sort')->all();
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $test_file = FileHelper::normalizePath(Yii::getAlias('@files')) . '/test.xml';
        file_put_contents($test_file, $this->renderPartial('products', [
            'products' => $products,
        ]));

        return $this->renderPartial('products', [
            'products' => $products,
        ]);

//        $test_file = FileHelper::normalizePath(Yii::getAlias('@files')).'/test.xml';
//        file_put_contents($test_file,$this->renderPartial('products'));
    }

    public function actionProductsEn()
    {
        $products = Product::find()->having(['status' => 1])->with('mainPhoto')->with('category')->orderBy('sort')->all();
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $test_file = FileHelper::normalizePath(Yii::getAlias('@files')) . '/test.xml';
        file_put_contents($test_file, $this->renderPartial('products-en', [
            'products' => $products,
        ]));
        return $this->renderPartial('products-en', [
            'products' => $products,
        ]);

    }

    public function actionProductsRu()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        return $this->renderPartial('products-ru');
    }
}
