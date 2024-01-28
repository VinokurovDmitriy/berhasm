<?php

namespace backend\controllers;

use common\models\GalleryCategories;
use Yii;
use common\models\GalleryItems;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GalleryItemsController implements the CRUD actions for GalleryItems model.
 */
class GalleryItemsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
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

    public function actionIndex($slug)
    {
        if (!$category = GalleryCategories::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = new ActiveDataProvider([
            'query' => GalleryItems::find()->andWhere(['category_id' => $category['id']]),
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC]],
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category' => $category,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($slug)
    {
        if (!$category = GalleryCategories::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = new GalleryItems();
        $model->category_id = $category->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->setImage();
            if ($model->save()) {
                $model->saveImage();
                return $this->redirect(['index', 'slug' => $slug]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->setImage();
            if ($model->save()) {
                $model->saveImage();
                return $this->redirect(['index', 'slug' => $model->category->slug]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleteImage();
        $model->delete();

        return $this->redirect(['index', 'slug' => $model->category->slug]);
    }

    protected function findModel($id)
    {
        if (($model = GalleryItems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }
}
