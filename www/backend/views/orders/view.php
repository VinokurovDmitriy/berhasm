<?php

use shop\entities\Shop\Orders;
use shop\entities\Shop\OrderItems;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Product;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model Orders */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="orders-view">

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'created_at:datetime',
                    'qty',
                    'sum',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function (Orders $data) {
                            if ($data->user_id) {
                                return Html::a($data->user->username, ['user/view', 'id' => $data->user_id]);
                            } else {
                                return $data->name;
                            }
                        },
                    ],
                    'email:email',
                    'address',
                    'phone',
//            [
//                'attribute' => 'datetime',
//                'format' => 'raw',
//                'value' => function (Orders $data) {
//                    return $data->datetime ? Yii::$app->formatter->asDate($data->datetime, 'dd.MM.yyyy HH:mm:ss') : '';
//                }
//            ],
                    [
                        'attribute' => 'pay_method',
                        'value' => Yii::$app->params['payMethods'][$model->pay_method]
                    ],
//                    'remark:ntext',
//                    [
//                        'attribute' => 'user_status',
//                        'format' => 'raw',
//                        'value' => function ($data) {
//                            if ($data->user_id && $data->user_status) {
//                                return '<span class="glyphicon glyphicon-remove text-danger"></span> Нет';
//                            } else {
//                                return '<span class="glyphicon glyphicon-ok text-success"></span> Да';
//                            }
//                        },
//                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function (Orders $data) {
                            $items = [];
                            foreach ($data::VARIANTS as $key => $value) {
                                $items[] = ['label' => $value, 'url' => Url::to(['status', 'id' => $data->id, 'value' => $key])];
                            }
                            $return = '<div class="dropdown"><a href="#" data-toggle="dropdown" class="btn dropdown-toggle" style="color:white;min-width: 200px; 
                            background-color:' . $data::COLORS[$data->status] . '" >' . $data::VARIANTS[$data->status] . '<b class="caret"></b></a>';
                            $return .= \yii\bootstrap\Dropdown::widget([
                                'items' => $items
                            ]);
                            $return .= "</div>";
                            return $return;
                        }
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="box">
        <div class="box-body">
            <h3>Товары</h3>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function (OrderItems $data) {
                            if ($data->product) {
                                return Html::img($data->product->mainPhoto->getThumbFileUrl('file', 'thumb'), [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'width:100px;'
                                ]);
                            }
                            return false;
                        },
                    ],
                    [
                        'label' => 'Наименование',
                        'format' => 'raw',
                        'value' => function (OrderItems $data) {
                            if ($data->product) {
                                return Html::a($data->title, ['shop/product/view', 'id' => $data->product_id]);
                            } else {
                                return false;
                            }
                        },
                    ],
                    [
                        'label' => 'Размер',
                        'format' => 'raw',
                        'value' => function (OrderItems $data) {
                            if ($data->product) {
                                return $data->modification->name;
                            } else {
                                return false;
                            }
                        },
                    ],
                    [
                        'label' => 'Количество',
                        'format' => 'raw',
                        'value' => function (OrderItems $data) {
                            return $data->qty_item;
                        },
                    ],
                    [
                        'label' => 'Цена',
                        'format' => 'raw',
                        'value' => function (OrderItems $data) {
                            return $data->price_item;
                        },
                    ],
                    [
                        'label' => 'Сумма',
                        'format' => 'raw',
                        'value' => function (OrderItems $data) {
                            return $data->price_item * $data->qty_item;
                        },
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div class="box">
        <div class="box-body">

            <h3 style="font-size: 2em">Товары</h3>
            <?php
            $json = json_decode($model->cart_json, true);
            ?>

            <div class="row" style="font-size: 18px; font-weight: 500; padding: 5px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.5)">
                <div class="col-lg-3">Название</div>
                <div class="col-lg-3">Размер</div>
                <div class="col-lg-2">Количество</div>
                <div class="col-lg-2">Цена</div>
                <div class="col-lg-2">Сумма</div>
            </div>

            <?php foreach ($json as $value):
                $product = Product::findOne($value['product']['id']);
                $modification = Modification::findOne($value['modificationId']);
                $url = Url::to(['shop/product/view', 'id' => $product->id]);
                ;?>
                <div class="row" style="font-size: 18px; font-weight: 400; line-height: 1.5; padding: 5px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.5)">
                    <div class="col-lg-3">
                        <a href="<?= $url;?>" target="_blank"><?= $value['product']['name_ru'];?></a>
                    </div>
                    <div class="col-lg-3">
                        <span><?= $modification->name;?></span>
                    </div>
                    <div class="col-lg-2">
                        <?= $value['quantity'];?>
                    </div>
                    <div class="col-lg-2">
                        <?= $value['product']['price_new'];?>
                    </div>
                    <div class="col-lg-2">
                        <?= $value['product']['price_new'] * $value['quantity'];?>
                    </div>
                </div>
            <?php endforeach;?>

            <div style="display: none">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'cart_json',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

</div>

