<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Categories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
