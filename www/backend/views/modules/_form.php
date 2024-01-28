<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Modules */
/* @var $form yii\widgets\ActiveForm */

$plugins = [
    'table',
    'fontfamily',
    'fontsize',
    'clips',
    'fullscreen'
];
?>

<div class="modules-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?php if (in_array($model->id, [1, 2, 3, 4, 5]) or $model->isNewRecord): ; ?>
                        <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
                    <?php endif; ?>
                    <?php if (in_array($model->id, [1, 2, 3, 4, 5]) or $model->isNewRecord): ; ?>
                        <?= $form->field($model, 'html_ru')->widget(Widget::class, [
                            'settings' => [
                                'lang' => 'ru',
                                'minHeight' => 200,
                                'plugins' => $plugins
                            ]
                        ]) ?>
                    <?php endif; ?>
                </div>
                <div class="col-6">
                    <?php if (in_array($model->id, [1, 2, 3, 4, 5]) or $model->isNewRecord): ; ?>
                        <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
                    <?php endif; ?>
                    <?php if (in_array($model->id, [1, 2, 3, 4, 5]) or $model->isNewRecord): ; ?>
                        <?= $form->field($model, 'html_en')->widget(Widget::class, [
                            'settings' => [
                                'lang' => 'ru',
                                'minHeight' => 200,
                                'plugins' => $plugins
                            ]
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (in_array($model->id, []) or $model->isNewRecord):
                if (in_array($model->id, [])) {
                    $qty = 2;
                } elseif (in_array($model->id, [])) {
                    $qty = 1;
                }
                ?>
                <?= $form->field($model, 'attachments')->widget(
                'backend\components\widget\GalleryUpload',
                [
                    'url' => ['file-storage/upload'],
                    'sortable' => true,
                    'maxFileSize' => 2 * 1024 * 1024, // 10 MiB
                    'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
                    'multiple' => true,
                    'maxNumberOfFiles' => $qty,
                    'clientOptions' => []
                ]
            ); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>