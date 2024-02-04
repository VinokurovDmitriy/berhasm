<?php

use shop\entities\Shop\ModCharacteristic;
use shop\helpers\CharacteristicHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\ModCharacteristicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Размеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'category_id',
                        'filter' => $searchModel->categoriesList(),
                        'value' => function (ModCharacteristic $model) {
                            return Html::encode($model->category->name);
                        },
                        'format' => 'raw',
                    ],
//                    [
//                        'attribute' => 'name',
//                        'value' => function (ModCharacteristic $model) {
//                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
//                        },
//                        'format' => 'raw',
//                    ],
                    [
                        'attribute' => 'variants',
                        'value' => function (ModCharacteristic $model) {
                            return implode(' | ', $model->variants);
                        },
                    ],
//                    [
//                        'attribute' => 'type',
//                        'filter' => $searchModel->typesList(),
//                        'value' => function (ModCharacteristic $model) {
//                            return CharacteristicHelper::typeName($model->type);
//                        },
//                    ],
//                    [
//                        'attribute' => 'required',
//                        'filter' => $searchModel->requiredList(),
//                        'format' => 'boolean',
//                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
