<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sounds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sounds-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
<!--            <div class="row">-->
<!--                <div class="col-lg-6">-->
<!--                    --><?php
//                    $img = ($model->image_name) ? $model->image : '/files/default_thumb.png';
//                    $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
//                    ?>
<!--                    --><?//= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'form-group img_input_block']])
//                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => '.jpeg, .jpg, .png, .svg'])->label($label, ['class' => 'label-img']); ?>
<!--                </div>-->
<!--                <div class="col-lg-12">-->
<!--                    --><?//= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
<!--                    --><?//= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'link')->textarea(['rows' => 3]) ?>
<!--                    --><?//= $form->field($model, 'file_name')->textInput(['disabled' => true]) ?>
<!--                    --><?//= $form->field($model, 'uploadedFile')->fileInput(['accept' => '.mp3']) ?>
<!--                    --><?php //if (!$model->isNewRecord && $model->file_name): ; ?>
<!--                        --><?//= Html::a('Удалить файл', ['remove-file', 'id' => $model->id], ['class' => 'btn btn-danger']); ?>
<!--                    --><?php //endif; ?>
<!--                </div>-->
<!--            </div>-->

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
