<?php
/* @var $this yii\web\View */
/* @var $model \common\models\Delivery */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Стоимость доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
