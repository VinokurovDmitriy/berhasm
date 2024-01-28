<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Slider;

/* @var $this yii\web\View */
/* @var $model common\models\Slider */

$this->title = 'Фоновое видео/изображение';
//$this->params['breadcrumbs'][] = ['label' => 'Слайдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' =>
            'btn btn-primary']) ?>
        <!--        --><? //= Html::a('Удалить', ['delete', 'id' => $model->id], [
        //            'class' => 'btn btn-danger',
        //            'data' => [
        //                'confirm' => 'Вы точно хотите удалить эту запись?',
        //                'method' => 'post',
        //            ],
        //        ]) ?>
        <!--        --><?php //if ($model->status) {
        //            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        //        } else {
        //            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        //        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Видеофайл/изображение',
                        'format' => 'raw',
                        'value' => function (Slider $data) {
                            if (substr($data->image_name, strpos($data->image_name, '.') + 1) == 'mp4') {
                                $icon = '@storageUrl/file_icons/' . substr($data->image_name, strpos($data->image_name, '.') + 1) . '-icon.png';
                                return Html::a(
                                    Html::img($icon, [
                                        'alt' => 'yii2 - картинка в gridview',
                                        'style' => 'width:30px;',
                                    ]) . ' ' . $data->image_name,
                                    $data->image,
                                    [
                                        'target' => '_blank',
                                        'data-pjax' => 0,
                                    ]);
                            } else {
                                return Html::img($data->image, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'width:600px;'
                                ]);
                            }
                        }
                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>
