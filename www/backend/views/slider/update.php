<?php
/* @var $this yii\web\View */
/* @var $model common\models\Slider */

$this->title = 'Изменить: Фоновое видео/изображение';

//$this->params['breadcrumbs'][] = ['label' => 'Слайдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Фоновое видео/изображение', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="slider-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
