<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\GalleryItems;

/* @var $this yii\web\View */
/* @var $model GalleryItems */

$this->title = $model->title_ru ?: $model->sort;
$this->params['breadcrumbs'][] = ['label' => $model->category->title_ru, 'url' => ['index', 'slug' => $model->category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-images-view">

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
                    [
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function (GalleryItems $data) {
                            if ($data->image_name) {
                                return Html::img($data->image, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'width:300px;'
                                ]);
                            }
                            return null;
                        },
                    ],
                    'title_ru',
                    'title_en',
                ],
            ]) ?>

        </div>
    </div>
</div>
