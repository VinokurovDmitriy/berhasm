<?php

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Shop\CharacteristicForm */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Размеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
