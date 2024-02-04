<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \shop\forms\manage\Shop\TagForm
 * @var $form \yii\widgets\ActiveForm
 */
?>

<div class="tag-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true])->hint('Если вы оставите данное поле пустым, оно сгенерируется автоматически'); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
