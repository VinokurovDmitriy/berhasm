<?php

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $model shop\forms\manage\Shop\Product\ModificationForm */
/* @var $mod_characteristic \shop\entities\Shop\ModCharacteristic */

$this->title = 'Добавить модификацию';
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['shop/product/index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['shop/product/view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modification-create">

    <?= $this->render('_form', [
        'model' => $model,
        'mod_characteristic' => $mod_characteristic,
    ]) ?>

</div>
