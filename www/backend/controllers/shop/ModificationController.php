<?php

namespace backend\controllers\shop;

use shop\entities\Shop\ModCharacteristic;
use shop\forms\manage\Shop\Product\ModificationForm;
use shop\services\manage\Shop\ProductManageService;
use Yii;
use shop\entities\Shop\Product\Product;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ModificationController extends Controller
{
    private $service;

    public function __construct($id, $module, ProductManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->redirect('shop/product');
    }


    public function actionCreate($product_id)
    {
        $product = $this->findModel($product_id);
        if (!$mod_characteristic = ModCharacteristic::findOne(['category_id' => $product->category_id])) {
            Yii::$app->session->setFlash('error', 'Сперва нужно задать перечень размеров для данной категории');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $form = new ModificationForm();
        $form->product_id = $product_id;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->addModification($product->id, $form);
                if (count($product->getRelateds()->all()) > 0) {
                    /* @var $related Product */
                    $related = $product->getRelateds()->all()[0];
                    try {
                        $this->service->addModification($related->id, $form);
                    } catch (\DomainException $e) {
                    }
                }
                return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'product' => $product,
            'model' => $form,
            'mod_characteristic' => $mod_characteristic,
        ]);
    }

    public function actionUpdate(int $product_id, int $id)
    {
        $product = $this->findModel($product_id);
        $mod_characteristic = ModCharacteristic::findOne(['category_id' => $product->category_id]);
        $modification = $product->getModification($id);

        $form = new ModificationForm($modification);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editModification($product->id, $modification->id, $form);
                if (count($product->getRelateds()->all()) > 0) {
                    /* @var $related Product */
                    $related = $product->getRelateds()->all()[0];
                    try {
                        $related->getModificationByName($modification->name);
                    } catch (\DomainException $e) {
                        $this->service->addModification($related->id, $form);
                    }
                    $relMod = $related->getModificationByName($modification->name);
                    $this->service->editModification($related->id, $relMod->id, $form);
                }
                return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'product' => $product,
            'model' => $form,
            'modification' => $modification,
            'mod_characteristic' => $mod_characteristic,
        ]);
    }

    public function actionDelete($product_id, $id)
    {
        $product = $this->findModel($product_id);
        $modification = $product->getModification($id);
        try {
            $this->service->removeModification($product->id, $id);
            if (count($product->getRelateds()->all()) > 0) {
                /* @var $related Product */
                $related = $product->getRelateds()->all()[0];
                try {
                    $relMod = $related->getModificationByName($modification->name);
                    $this->service->removeModification($related->id, $relMod->id);
                } catch (\DomainException $e) {
                }
            }
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);
    }

    /**
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Product
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
