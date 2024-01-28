<?php
/* @var $this yii\web\View */
/* @var $model common\models\IndexLinks */

$this->title = 'Изменить: ' . $model->title_ru;

$this->params['breadcrumbs'][] = ['label' => 'Переходы', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-links-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
