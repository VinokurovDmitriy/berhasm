<?php

use yii\helpers\Html;
use yii\grid\GridView;
use shop\entities\Shop\Tag;
use yii\grid\ActionColumn;

/**
 * @var $this \yii\web\View
 * @var $searchModel \backend\forms\Shop\TagSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'Тэги';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tag-index">

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
                        'attribute' => 'name',
                        'value' => function (Tag $model) {
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
