<?php

namespace frontend\controllers;

use yii\helpers\Url;
use common\models\SizeGuide;
use frontend\models\CatalogSearchForm;
use shop\entities\Shop\Product\Product;
use shop\forms\Shop\AddToCartForm;
use Yii;
use frontend\components\FrontendController;
use shop\entities\Shop\Category;
use yii\web\NotFoundHttpException;


class CatalogController extends FrontendController
{

    public function actionIndex($slug, $sale = null, $tag = null)
    {
        if (!$category = Category::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $this->setMeta($category->meta->getTitle() ?: $category->name, $category->meta->getDescription(), $category->meta->getKeywords());
        $searchModel = new CatalogSearchForm($category->id);
        if ($sale) {
            $searchModel->sale = 1;
        }
        if ($tag) {
            $searchModel->tag = $tag;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Url::remember();

        return $this->render('category', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProduct($slug)
    {
        if (!$product = Product::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $this->setMeta($product->meta->getTitle() ?: $product->name, $product->meta->getDescription(), $product->meta->getKeywords());
        $sizes = SizeGuide::getDb()->cache(function () {
            return SizeGuide::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        $cartForm = new AddToCartForm($product);
        return $this->render('product', [
            'product' => $product,
            'cartForm' => $cartForm,
            'sizes' => $sizes,
        ]);
    }

//    public function actionConstruction()
//    {
//        $this->setMetAndGetHeader('index');
//        return $this->render('construction');
//    }
}
