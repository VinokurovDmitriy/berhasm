<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Shop\Product\ModificationForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $mod_characteristic \shop\entities\Shop\ModCharacteristic */

$variants = array_combine($mod_characteristic->variants, $mod_characteristic->variants);
?>

<div class="modification-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'code')->label('Порядок')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'name')->label('Размер')->dropDownList($variants, ['prompt' => '--Выберите--']) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'quantity')->label('Количество')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
