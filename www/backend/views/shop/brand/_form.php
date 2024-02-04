<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use vova07\imperavi\Widget;

/**
 * @var $this \yii\web\View
 * @var $model \shop\forms\manage\Shop\BrandForm
 * @var $form \yii\widgets\ActiveForm
 */
?>

<div class="brand-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Общие</div>
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
                    <?= $form->field($model, 'slug')->textInput(['maxlength' => true])->hint('Если вы оставите данное поле пустым, оно сгенерируется автоматически'); ?>
                    <?= $form->field($model, 'country')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-6">
                    <?php if ($model->_brand): ; ?>
                        <div class="col-8">
                            <?= Html::a(
                                Html::img($model->_brand->getThumbFileUrl('file', 'thumb')),
                                $model->_brand->getUploadedFileUrl('file'),
                                ['class' => 'thumbnail', 'target' => '_blank']
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <?= $form->field($model, 'file')->label('Логотип')->widget(FileInput::class, [
                        'options' => [
                            'accept' => ' .jpg, .png, .gif',
                            'multiple' => false,
                        ]
                    ]) ?>
                </div>
            </div>
            <?= $form->field($model, 'description')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 80,
                    'plugins' => [
                        'clips',
                        'fullscreen',
                    ]
                ]
            ]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?= $form->field($model->meta, 'title')->textInput(); ?>
            <?= $form->field($model->meta, 'description')->textarea(['rows' => 2]); ?>
            <?= $form->field($model->meta, 'keywords')->textInput(); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
