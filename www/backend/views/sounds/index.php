<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Sounds;
use arogachev\sortable\grid\SortableColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Звуки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sounds-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div class="question-index" id="question-sortable">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'sort',
                        ],
                        [
                            'class' => SortableColumn::class,
                            'gridContainerId' => 'question-sortable',
                            'baseUrl' => '/admin/sort/', // Optional, defaults to '/sort/'
                            'confirmMove' => false, // Optional, defaults to true
                            'template' => '<div class="sortable-section">{currentPosition}</div>
                                           <div class="sortable-section">{moveWithDragAndDrop}</div>'
                        ],
//                        'title_ru',
                        [
                            'attribute' => 'link',
                            'format' => 'raw',
//                            'value' => function (Sounds $data) {
//                                return $data->link ? Html::a($data->link, $data->link, ['target' => '_blank']) : false;
//                            }
                        ],
//                        [
//                            'label' => 'Изображение',
//                            'format' => 'raw',
//                            'value' => function (Sounds $data) {
//                                return $data->image_name ? Html::img($data->image, [
//                                    'alt' => 'yii2 - картинка в gridview',
//                                    'style' => 'width:100px;'
//                                ]) : null;
//                            },
//                        ],
//                        [
//                            'label' => 'Файл',
//                            'format' => 'raw',
//                            'value' => function (Sounds $data) {
//                                return $data->file_name ? Html::a($data->file_name, $data->file, [
//                                    'target' => '_blank',
//                                    'data-pjax' => 0,
//                                ]) : null;
//                            },
//                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function (Sounds $data) {
                                if ($data->status) {
                                    return Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $data->id], ['class' => 'btn btn-success btn-raised']);
                                } else {
                                    return Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
                                }
                            }
                        ],

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
