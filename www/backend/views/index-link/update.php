<?php
/* @var $this yii\web\View */
/* @var $model common\models\IndexLink */

$this->title = 'Изменить: ' . $model->title_ru;

$this->params['breadcrumbs'][] = ['label' => 'Index Links', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-link-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
