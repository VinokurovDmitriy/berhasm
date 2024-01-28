<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Seo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <?php if ($model->isNewRecord): ; ?>
                <?= $form->field($model, 'page')->textInput(['maxlength' => true]) ?>
            <?php endif; ?>

            <div class="box-body">
                <div class="row">
                    <div class="col-6">
                        <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'description_ru')->textarea(['rows' => 2, 'maxlength' => true]); ?>
                        <?= $form->field($model, 'keywords_ru')->textarea(['rows' => 2, 'maxlength' => true]); ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'description_en')->textarea(['rows' => 2, 'maxlength' => true]); ?>
                        <?= $form->field($model, 'keywords_en')->textarea(['rows' => 2, 'maxlength' => true]); ?>
                    </div>
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
