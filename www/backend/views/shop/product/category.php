<?php

use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use shop\helpers\PriceHelper;
use shop\helpers\ProductHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

//use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category Category */

$this->title = $category->name_ru;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];;
$this->params['breadcrumbs'][] = $this->title;;
?>
<div class="user-index">

    <div class="row">
        <div class="col-9">
            <p>
                <?= Html::a('Добавить', ['create', 'cat_id' => $category->id], ['class' => 'btn btn-success']) ?>
            </p>
        </div>

        <div class="col-3">
            <div class="form-group input-group input-group-sm">
                <label class="input-group-addon" for="input-limit">Число строк:</label>
                <select id="input-limit" class="form-control" onchange="location = this.value;">
                    <?php
                    $values = [20, 50, 75, 100];
                    $current = $dataProvider->getPagination()->getPageSize();
                    ?>
                    <?php foreach ($values as $value): ?>
                        <option value="<?= Url::current(['per-page' => $value]) ?>"
                                <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'rowOptions' => function (Product $model) {
                    if ($model->quantity <= 0) {
                        return ['style' => 'background: #fdc'];
                    } else {
                        foreach ($model->modifications as $key => $modification) {
                            if ($modification->quantity <= 0) {
                                return ['style' => 'background: #f2e281'];
                            }
                        }
                        return [];
                    }
                },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'value' => function (Product $model) {
                            return $model->mainPhoto ? Html::img($model->mainPhoto->getThumbFileUrl('file', 'admin')) : null;
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width: 100px'],
                    ],
                    'code',
                    [
                        'attribute' => 'name_ru',
                        'value' => function (Product $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
//                    [
//                        'attribute' => 'brand_id',
//                        'value' => 'brand.name',
//                        'filter' => Select2::widget([
//                            'model' => $searchModel,
//                            'attribute' => 'brand_id',
//                            'data' => $searchModel->brandList(),
//                            'options' => ['placeholder' => ''],
//                            'pluginOptions' => [
//                                'allowClear' => true
//                            ],
//                        ]),
//                        'contentOptions' => ['style' => 'white-space: normal;'],
//                        'options' => ['style' => 'width: 150px; max-width: 150px;'],
//                    ],
                    [
                        'attribute' => 'price_new',
                        'format' => 'raw',
                        'value' => function (Product $model) {
                            $result = '';
                            if ($model->price_old) {
                                $result .= '<s>' . PriceHelper::format($model->price_old) . '</s><br>';
                            }
                            $result .= PriceHelper::format($model->price_new);
                            return $result;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'sale',
                            ['1' => 'Без скидки', '2' => 'Со скидкой'],
                            ['class' => 'form-control', 'prompt' => '']
                        ),
                    ],

                    [
                        'attribute' => 'quantity',
                        'value' => function (Product $model) {
                            if ($model->modifications) {
                                $qty = '';
                                foreach ($model->modifications as $key => $modification) {
                                    if ($key != 0) {
                                        $qty .= '/';
                                    }
                                    $qty .= $modification->quantity;
                                }
                                return $qty;
                            } else {
                                return $model->quantity;
                            }
                        },
                    ],

                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Product $model) {
                            return Html::a(ProductHelper::statusLabel($model->status), ['status', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
