<?php

/* @var $this yii\web\View */
/* @var $characteristic shop\entities\Shop\Characteristic */
/* @var $model shop\forms\manage\Shop\CharacteristicForm */

$this->title = 'Изменить: ' . $characteristic->category->name_ru;
$this->params['breadcrumbs'][] = ['label' => 'Размеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $characteristic->category->name_ru, 'url' => ['view', 'id' => $characteristic->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="characteristic-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
