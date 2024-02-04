<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Slider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slider-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6">
<!--                    --><?php
//                    $img = ($model->image_name) ? $model->image : '/files/default_thumb.png';
//                    $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
//                    ?>
<!--                    --><?//= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'form-group img_input_block']])
//                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => '.jpeg, .jpg, .png, .svg'])->label($label, ['class' => 'label-img']); ?>
                    <?= $form->field($model, 'image_name')->textInput(['disabled' => true])->label('Файл') ?>
                    <?= $form->field($model, 'uploadedImageFile')->fileInput(['accept' => '.jpeg, .jpg, .png, .mp4'])->label('Файл');?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
