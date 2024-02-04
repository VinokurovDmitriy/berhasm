<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use shop\entities\Shop\Orders;

/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

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
                        'contentOptions' => ['style' => 'white-space:normal'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'contentOptions' => ['style' => 'white-space:normal'],
                    ],
                    [
                        'attribute' => 'email',
                        'format' => 'email',
                        'contentOptions' => ['style' => 'white-space:normal'],
                    ],
//                    [
//                        'attribute' => 'datetime',
//                        'format' => 'raw',
//                        'value' => function (Orders $data) {
//                            return $data->datetime ? Yii::$app->formatter->asDate($data->datetime, 'dd.MM.yyyy') : '';
//                        },
//                        'filter' => DatePicker::widget([
//                            'model' => $searchModel,
//                            'attribute' => 'datetime',
//                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
//                            'pluginOptions' => [
//                                'todayHighlight' => true,
//                                'todayBtn' => true,
//                                'autoclose' => true,
//                                'format' => 'dd.mm.yyyy',
//                            ]
//                        ]),
//                        'options' => ['width' => '200'],
//                    ],
                    'sum',
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
//                        'filter' => Html::activeDropDownList(
//                            $searchModel,
//                            'user_status',
//                            ['0' => 'Да', '1' => 'Нет'],
//                            ['class' => 'form-control', 'prompt' => 'Все']
//                        ),
//                        'options' => ['style' => 'width: 100px; max-width: 100px;'],
//                    ],

                    [
                        'label' => 'Товары',
                        'format' => 'raw',
                        'value' => function (Orders $data) {
                            $string = '';
                            foreach ($data->orderItems as $item) {
                                $string .= Html::a($item->title, ['shop/product/view', 'id' => $item->product_id]) . ' (р. ' . $item->modification->name . ' / ' . $item->qty_item . ' шт)<br>';
                            }
                            return $string;
                        }
                    ],
                    'pay_method',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function (Orders $data) {
                            $status = ($data->pay_method == 'online' && $data->status == 0) ? 10 : $data->status;

                            $items = [];
                            foreach ($data::VARIANTS as $key => $value) {
                                if ($key == 10) {
                                    $items[] = ['label' => $value, 'url' => '#'];
                                } else {
                                    $items[] = ['label' => $value, 'url' => Url::to(['status', 'id' => $data->id, 'value' => $key])];
                                }
                            }
                            $return = '<div class="dropdown">
                                 <a href="#" data-toggle="dropdown" class="btn dropdown-toggle" style="color:white;min-width: 200px; 
                                        background-color:' . $data::COLORS[$status] . '" >' . $data::VARIANTS[$status] . '<b class="caret"></b>
                                 </a>';
                            $return .= \yii\bootstrap\Dropdown::widget([
                                'items' => $items
                            ],  ['options' => [10 => ['disabled' => true]]]);
                            $return .= "</div>";
                            return $return;
                        },

                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'status',
                            $searchModel->getVariants(),
                            ['class' => 'form-control', 'prompt' => 'Все']
                        ),
                        'options' => ['style' => 'width: 150px; max-width: 125px;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['width' => '80'],
                        'template' => '{view} {delete} {link}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
