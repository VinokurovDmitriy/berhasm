<?php
/* @var $this yii\web\View */
/* @var $model common\models\Sounds */


$title = $model->title_ru ?: $model->sort;
$this->title = 'Изменить: ' . $title;

$this->params['breadcrumbs'][] = ['label' => 'Звуки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="sounds-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
