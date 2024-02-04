<?php

use yii\helpers\Html;
use yii\grid\GridView;
use shop\entities\Shop\Brand;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;

/**
 * @var $this \yii\web\View
 * @var $searchModel \backend\forms\Shop\BrandSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'Бренды';
$this->params['breadcrumbs'][] = $this->title;

$countries = ArrayHelper::map(Brand::find()->groupBy('country')->asArray()->all(), 'country', 'country');
?>

<div class="brand-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'country',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'country',
                            $countries,
                            ['class' => 'form-control', 'prompt' => 'Все']
                        ),
                        'options' => ['style' => 'width: 150px; max-width: 350px;'],
                    ],
                    [
                        'value' => function (Brand $data) {
                            return $data->file ? Html::img($data->getThumbFileUrl('file', 'admin')) : null;
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width: 100px'],
                    ],
                    [
                        'attribute' => 'name',
                        'value' => function (Brand $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'slug',
                    ['class' => ActionColumn::class]
                ],
            ]); ?>
        </div>
    </div>
</div>
