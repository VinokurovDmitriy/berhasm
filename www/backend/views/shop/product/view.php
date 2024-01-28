<?php

use kartik\file\FileInput;
//use shop\entities\Shop\Product\Modification;
//use shop\entities\Shop\Product\Value;
use shop\helpers\PriceHelper;
use shop\helpers\ProductHelper;
//use shop\helpers\WeightHelper;
use yii\bootstrap\ActiveForm;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use shop\entities\Shop\Product\Product;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $photosForm shop\forms\manage\Shop\Product\PhotosForm */
/* @var $modificationsProvider yii\data\ActiveDataProvider */
/* @var $relatesProvider yii\data\ActiveDataProvider */

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?php if ($product->isActive()): ?>
            <?= Html::a('Черновик', ['draft', 'id' => $product->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Активировать', ['activate', 'id' => $product->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
        <?= Html::a('Изменить', ['update', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $product->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		<?= Html::a('Дублировать', ['duplicate', 'id' => $product->id], ['class' => 'btn btn-primary pull-right']) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">Общие</div>
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'status',
                                'value' => ProductHelper::statusLabel($product->status),
                                'format' => 'raw',
                            ],
//                            [
//                                'attribute' => 'brand_id',
//                                'value' => ArrayHelper::getValue($product, 'brand.name'),
//                            ],
                            'code',
                            'name_ru',
                            'name_en',
                            [
                                'attribute' => 'category_id',
                                'value' => ArrayHelper::getValue($product, 'category.name'),
                            ],
                            [
                                'label' => 'Другие категории',
                                'value' => implode(', ', ArrayHelper::getColumn($product->categories, 'name')),
                            ],
                            [
                                'label' => 'Тэги',
                                'value' => implode(', ', ArrayHelper::getColumn($product->tags, 'name')),
                            ],

                            [
                                'attribute' => 'quantity',
                                'value' => function () use ($product) {
                                    if ($product->modifications) {
                                        $qty = '';
                                        foreach ($product->modifications as $key => $modification) {
                                            if ($key != 0) {
                                                $qty .= '/';
                                            }
                                            $qty .= $modification->quantity;
                                        }
                                        return $qty;
                                    } else {
                                        return $product->quantity;
                                    }
                                },
                            ],
                            [
                                'attribute' => 'price_new',
                                'value' => PriceHelper::format($product->price_new),
                            ],
                            [
                                'attribute' => 'price_old',
                                'value' => PriceHelper::format($product->price_old),
                            ],
                        ],
                    ]) ?>
                    <br/>
                    <p>
                        <?= Html::a('Сменить цену', ['price', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                        <!--                        --><?php //if ($product->canChangeQuantity()): ?>
                        <!--                            --><? //= Html::a('Измеить количество', ['quantity', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                        <!--                        --><?php //endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <!--            <div class="box box-default">-->
            <!--                <div class="box-header with-border">Характеристики</div>-->
            <!--                <div class="box-body">-->
            <!---->
            <!--                    --><? //= DetailView::widget([
            //                        'model' => $product,
            //                        'attributes' => array_map(function (Value $value) {
            //                            return [
            //                                'label' => $value->characteristic->name,
            //                                'value' => $value->value,
            //                            ];
            //                        }, $product->values),
            //                    ]) ?>
            <!--                </div>-->
            <!--            </div>-->

            <?php if ($product->category->getModChar()): ; ?>
                <div class="box" id="modifications">
                    <div class="box-header with-border">Размеры</div>
                    <div class="box-body">
                        <p>
                            <?= Html::a('Добавить размер', ['shop/modification/create', 'product_id' => $product->id], ['class' => 'btn btn-success']) ?>
                        </p>
                        <?= GridView::widget([
                            'dataProvider' => $modificationsProvider,
                            'columns' => [
                                'code',
                                'name',
//                            [
//                                'attribute' => 'price',
//                                'value' => function (Modification $model) {
//                                    return PriceHelper::format($model->price);
//                                },
//                            ],
                                'quantity',
                                [
                                    'class' => ActionColumn::class,
                                    'controller' => 'shop/modification',
                                    'template' => '{update} {delete}',
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="box box-default">
                <div class="box-header with-border">Связанный товар</div>
                <div class="box-body">

                    <?php if(count($relatesProvider->models) < 1): ?>
                        <p>
                            <?= Html::a('Добавить связь', ['create-relation', 'product_id' => $product->id], ['class' => 'btn btn-success']) ?>
                        </p>
                    <?php endif ?>

                    <?= GridView::widget([
                        'dataProvider' => $relatesProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'label' => 'Фото',
                                'value' => function (Product $data) {
                                    return $data->mainPhoto ? Html::img($data->mainPhoto->getThumbFileUrl('file', 'admin')) : null;
                                },
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width: 100px'],
                            ],
                            [
                                'attribute' => 'name_ru',
                                'value' => function (Product $data) {
                                    return Html::a(Html::encode($data->name), ['view', 'id' => $data->id]);
                                },
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'white-space: normal;'],
                            ],
                            [
                                'format' => 'raw',
                                'value' => function (Product $data) use ($product) {
                                    return Html::a('<span class="glyphicon glyphicon-remove"></span> Удалить', ['remove-relation', 'product_id' => $product->id, 'relation_id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
                                }
                            ]
                        ]
                    ]) ?>
                </div>
            </div>

        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6">
                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => [
                            [
                                'attribute' => 'description_ru',
                                'format' => 'raw',
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => [
                            [
                                'attribute' => 'description_en',
                                'format' => 'raw',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box" id="photos">
        <div class="box-header with-border">Фотографии</div>
        <div class="box-body">

            <div class="row">
                <?php foreach ($product->photos as $photo): ?>
                    <div class="col-md-2 col-xs-3" style="text-align: center">
                        <div class="btn-group">
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-photo-up', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                                'data-confirm' => 'Удалить фото?',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-photo-down', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                        </div>
                        <div>
                            <?= Html::a(
                                Html::img($photo->getThumbFileUrl('file', 'thumb')),
                                $photo->getUploadedFileUrl('file'),
                                ['class' => 'thumbnail', 'target' => '_blank']
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

                <?= $form->field($photosForm, 'files[]')->label(false)->widget(FileInput::class, [
                    'options' => [
                        'accept' => ' .jpg, .png, .gif',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'showPreview' => true,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false
                    ]
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6">
                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => [
                            'meta.title_ru',
                            'meta.description_ru',
                            'meta.keywords_ru',
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => [
                            'meta.title_ru',
                            'meta.description_ru',
                            'meta.keywords_ru',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>
