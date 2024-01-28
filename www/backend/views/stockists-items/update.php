<?php
/* @var $this yii\web\View */
/* @var $model common\models\StockistsItems */

$title = $model->value_ru ?: $model->sort;
$this->title = 'Изменить: ' . $title;

$this->params['breadcrumbs'][] = ['label' => $model->category->title_ru, 'url' => ['index', 'slug' => $model->category->slug]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="stockists-items-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
