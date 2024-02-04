<?php
/* @var $this yii\web\View */
/* @var $model \common\models\CustomerCare */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Забота о покупателе', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-care-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
