<?php
/* @var $this yii\web\View */
/* @var $model common\models\StockistsCategories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockists-categories-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
