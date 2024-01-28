<?php
/* @var $this yii\web\View */
/* @var $model common\models\Press */

$title = $model->title . ' (' . $model->date . ')';
$this->title = 'Изменить: ' . $title;

$this->params['breadcrumbs'][] = ['label' => 'Пресса', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="press-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
