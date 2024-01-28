<?php
/* @var $this yii\web\View */
/* @var $model common\models\Sounds */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Звуки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sounds-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
