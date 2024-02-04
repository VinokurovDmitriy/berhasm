<?php
/* @var $this yii\web\View */
/* @var $model \common\models\Promocodes */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Promocodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promocodes-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
