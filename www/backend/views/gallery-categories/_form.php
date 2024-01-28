<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GalleryCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-4">
                    <?php
                    $img = ($model->image_name) ? $model->image : '/files/default_thumb.png';
                    $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
                    ?>
                    <?= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'form-group img_input_block']])
                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>
                </div>
                <div class="col-8">
                    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <!--    <div class="box">-->
    <!--        <div class="box-body">-->
    <!--            <div class="row">-->
    <!--                <div class="col-lg-6">-->
    <!--                    --><? //= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
    <!--                    --><? //= $form->field($model, 'meta_keywords')->textarea(['maxlength' => true, 'rows' => 2]) ?>
    <!--                </div>-->
    <!--                <div class="col-lg-6">-->
    <!--                    --><? //= $form->field($model, 'meta_description')->textarea(['maxlength' => true, 'rows' => 6]) ?>
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
