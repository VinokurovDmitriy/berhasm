<?php
/* @var $this yii\web\View */
/* @var $model common\models\Countries */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Страны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="countries-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
