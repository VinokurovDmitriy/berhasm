<?php
/* @var $this yii\web\View */
/* @var $model common\models\IndexLinks */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Переходы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-links-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
