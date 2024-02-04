<?php

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $modification shop\entities\Shop\Product\Modification */
/* @var $model shop\forms\manage\Shop\Product\ModificationForm */
/* @var $mod_characteristic \shop\entities\Shop\ModCharacteristic */

$this->title = 'Изменить модификацию: ' . $modification->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['shop/product/index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['shop/product/view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $modification->name;
?>
<div class="modification-update">

    <?= $this->render('_form', [
        'model' => $model,
        'mod_characteristic' => $mod_characteristic,
    ]) ?>

</div>
