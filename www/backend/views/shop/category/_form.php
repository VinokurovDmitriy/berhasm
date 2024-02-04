<?php

use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Shop\CategoryForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Общие</div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'parentId')->dropDownList($model->parentCategoriesList()) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'slug')->textInput(['maxlength' => true])->hint('Если вы оставите данное поле пустым, оно сгенерируется автоматически'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <!--            --><? //= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <!--            --><? //= $form->field($model, 'description')->widget(Widget::className(), [
            //                'settings' => [
            //                    'lang' => 'ru',
            //                    'minHeight' => 100,
            //                    'plugins' => [
            //                        'clips',
            //                        'fullscreen'
            //                    ]
            //                ]
            //            ]) ?>

        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model->meta, 'title_ru')->textInput() ?>
                    <?= $form->field($model->meta, 'description_ru')->textarea(['rows' => 2]) ?>
                    <?= $form->field($model->meta, 'keywords_ru')->textInput() ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model->meta, 'title_en')->textInput() ?>
                    <?= $form->field($model->meta, 'description_en')->textarea(['rows' => 2]) ?>
                    <?= $form->field($model->meta, 'keywords_en')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
