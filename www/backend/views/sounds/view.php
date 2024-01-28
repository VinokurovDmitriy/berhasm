<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Sounds;

/* @var $this yii\web\View */
/* @var $model common\models\Sounds */

$this->title = $model->title_ru ?: $model->sort;
$this->params['breadcrumbs'][] = ['label' => 'Звуки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sounds-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' =>
            'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'sort',
//                    'title_ru',
//                    'title_en',
                    [
                        'attribute' => 'link',
                        'format' => 'raw',
//                        'value' => function (Sounds $data) {
//                            return $data->link ? Html::a($data->link, $data->link, ['target' => '_blank']) : false;
//                        }
                    ],
//                    [
//                        'label' => 'Изображение',
//                        'format' => 'raw',
//                        'value' => function (Sounds $data) {
//                            return $data->image_name ? Html::img($data->image, [
//                                'alt' => 'yii2 - картинка в gridview',
//                                'style' => 'width:100px;'
//                            ]) : null;
//                        },
//                    ],
//                    [
//                        'label' => 'Файл',
//                        'format' => 'raw',
//                        'value' => function (Sounds $data) {
//                            return $data->file_name ? Html::a($data->file_name, $data->file, [
//                                'target' => '_blank',
//                                'data-pjax' => 0,
//                            ]) : null;
//                        },
//                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>
