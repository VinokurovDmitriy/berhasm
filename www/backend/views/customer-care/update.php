<?php
/* @var $this yii\web\View */
/* @var $model \common\models\CustomerCare */

$this->title = 'Изменить: ' . $model->title_ru;

$this->params['breadcrumbs'][] = ['label' => 'Забота о покупателе', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title_ru, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="customer-care-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
