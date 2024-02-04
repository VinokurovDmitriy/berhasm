<?php
/* @var $this yii\web\View */
/* @var $model common\models\Modules */

$title = $model->title_ru ?: 'Текст';
$this->title = 'Изменить: ' . $title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="modules-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
