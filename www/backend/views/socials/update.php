<?php

/* @var $this yii\web\View */
/* @var $model common\models\Socials */

$this->title = 'Изменить: ' . $model->labels_en[$model->icon];
$this->params['breadcrumbs'][] = ['label' => 'Соцсети', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->labels_en[$model->icon], 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="socials-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
