<?php

namespace backend\controllers\shop;

use DomainException;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Product;
use shop\forms\manage\Shop\Product\CategoriesForm;
use shop\forms\manage\Shop\Product\PhotosForm;
use shop\forms\manage\Shop\Product\PriceForm;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use shop\forms\manage\Shop\Product\QuantityForm;
use shop\forms\manage\Shop\Product\RelationForm;
use shop\forms\manage\Shop\Product\TagsForm;
use shop\entities\Shop\Characteristic;
use shop\forms\manage\MetaForm;
use Yii;
use backend\forms\Shop\ProductSearch;
use shop\services\manage\Shop\ProductManageService;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProductController extends Controller
{
    private $service;

    public function __construct($id, $module, ProductManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                    'delete-photo' => ['POST'],
                    'move-photo-up' => ['POST'],
                    'move-photo-down' => ['POST'],
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

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCategory($catId)
    {
        if (!$category = Category::findOne($catId)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $searchModel = new ProductSearch();
        $searchModel->category_id = $catId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('category', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */

    public function actionView(int $id)
    {
        $product = $this->findModel($id);

        $modificationsProvider = new ActiveDataProvider([
            'query' => $product->getModifications()->orderBy(['code' => SORT_ASC, 'name' => SORT_ASC]),
            'key' => function (Modification $modification) use ($product) {
                return [
                    'product_id' => $product->id,
                    'id' => $modification->id,
                ];
            },
            'pagination' => false,
        ]);

        $relatesProvider = new ActiveDataProvider([
            'query' => $product->getRelateds()->orderBy('name_ru'),
            'pagination' => false,
        ]);

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($product->id, $photosForm);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'product' => $product,
            'modificationsProvider' => $modificationsProvider,
            'photosForm' => $photosForm,
            'relatesProvider' => $relatesProvider,
        ]);
    }

    /**
     * @param null $cat_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($cat_id = null)
    {
        $form = new ProductCreateForm();
        if ($cat_id) {
            if (!$category = Category::findOne($cat_id)) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        $form->categories->main = $cat_id;
        $form->quantity->quantity = 0;
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                try {
                    $product = $this->service->create($form);
                    return $this->redirect(['view', 'id' => $product->id]);
                } catch (DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $product = $this->findModel($id);

        $form = new ProductEditForm($product);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'product' => $product,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDuplicate(int $id)
    {
        $product = $this->findModel($id);

        $form = new ProductCreateForm();
		$form->brandId = $product->brand_id;
        $form->name_ru = $product->name_ru;
        $form->name_en = $product->name_en;
        $form->description_ru = $product->description_ru;
        $form->description_en = $product->description_en;
        $form->meta = new MetaForm($product->meta);
        $form->categories = new CategoriesForm($product);
        $form->tags = new TagsForm($product);
        $form->values = array_map(function (Characteristic $characteristic) use ($product) {
            return new ValueForm($characteristic, $product->getValue($characteristic->id));
        }, Characteristic::find()->andWhere(['category_id' => $product->category_id])->orderBy('ord')->all());
		$form->price = new PriceForm($product);
		
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                try {
                    $product = $this->service->create($form);
                    return $this->redirect(['view', 'id' => $product->id]);
                } catch (DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionPrice(int $id)
    {
        $product = $this->findModel($id);

        $form = new PriceForm($product);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changePrice($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('price', [
            'model' => $form,
            'product' => $product,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionQuantity(int $id)
    {
        $product = $this->findModel($id);

        $form = new QuantityForm($product);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changeQuantity($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('quantity', [
            'model' => $form,
            'product' => $product,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(int $id)
    {
        try {
            $this->service->remove($id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionDeletePhoto(int $id, $photo_id)
    {
        try {
            $this->service->removePhoto($id, $photo_id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionMovePhotoUp(int $id, $photo_id)
    {
        $this->service->movePhotoUp($id, $photo_id);
        return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionMovePhotoDown(int $id, $photo_id)
    {
        $this->service->movePhotoDown($id, $photo_id);
        return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
    }

    /**
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel(int $id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDraft($id)
    {
        try {
            $this->service->draft($id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreateRelation(int $product_id)
    {
        $product = $this->findModel($product_id);
        $form = new RelationForm($product);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->addRelatedProduct($product->id, $form->relationId);
                $this->service->addRelatedProduct($form->relationId, $product->id);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->redirect(['view', 'id' => $product->id]);
        }
        return $this->render('relation', [
            'model' => $form,
            'product' => $product,
        ]);
    }

    public function actionRemoveRelation(int $product_id, int $relation_id)
    {
        try {
            $this->service->removeRelatedProduct($product_id, $relation_id);
            $this->service->removeRelatedProduct($relation_id, $product_id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $product_id]);
    }

    public function actionActivateBulk(array $ids)
    {
        foreach ($this->getProducts($ids) as $product) {
            $product->status = 1;
            $product->save();
        }
        Yii::$app->session->setFlash('success', 'Операция выполнена');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactivateBulk(array $ids)
    {
        foreach ($this->getProducts($ids) as $product) {
            $product->status = 0;
            $product->save();
        }
        Yii::$app->session->setFlash('success', 'Операция выполнена');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteBulk(array $ids)
    {
        foreach ($this->getProducts($ids) as $product) {
            $product->delete();
        }
        Yii::$app->session->setFlash('success', 'Операция выполнена');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param array $ids
     * @return Product[]
     */
    private function getProducts(array $ids)
    {
        return Product::find()->where(['id' => $ids])->all();
    }
}