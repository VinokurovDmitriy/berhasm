<?php
/* @var $this yii\web\View */
/* @var $model \common\models\Promocodes */

$this->title = 'Изменить: ' . $model->code;

$this->params['breadcrumbs'][] = ['label' => 'Промокоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="promocodes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
