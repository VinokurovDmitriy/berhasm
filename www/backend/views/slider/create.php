<?php
/* @var $this yii\web\View */
/* @var $model common\models\Slider */

$this->title = 'Добавить';
//$this->params['breadcrumbs'][] = ['label' => 'Слайдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
