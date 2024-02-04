<?php
/* @var $this yii\web\View */
/* @var $model \common\models\SizeGuide */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Таблица размеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-care-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
