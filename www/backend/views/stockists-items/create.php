<?php
/* @var $this yii\web\View */
/* @var $model common\models\StockistsItems */
/* @var $category \common\models\StockistsCategories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $category->title_ru, 'url' => ['index', 'slug' => $category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockists-items-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
