<?php

/* @var $this yii\web\View */
/* @var $tag \shop\entities\Shop\Tag */
/* @var $model \shop\forms\manage\Shop\TagForm */

$this->title = 'Изменить: ' . $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Тэги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tag->name, 'url' => ['view', 'id' => $tag->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="articles-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>