<?php
/* @var $this yii\web\View */
/* @var $model common\models\StockistsCategories */

$this->title = 'Изменить: ' . $model->title_ru;

$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="stockists-categories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
