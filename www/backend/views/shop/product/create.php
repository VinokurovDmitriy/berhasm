<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget;
use kartik\file\FileInput;

/**
 * @var $this \yii\web\View
 * @var $model \shop\forms\manage\Shop\Product\ProductCreateForm
 */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="box box-default">
        <div class="box-header with-border">Общие</div>
        <div class="box-body">
            <div class="row">
<!--                <div class="col-md-4">-->
<!--                    --><?//= $form->field($model, 'brandId')->dropDownList($model->brandsList())->label('Бренд'); ?>
<!--                </div>-->
                <div class="col-md-2">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true])->label('Код'); ?>
                </div>
                <div class="col-md-5">

                </div>
                <div class="col-md-5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true])->label('Название Ru'); ?>
                    <?= $form->field($model, 'description_ru')->label('Описание Ru')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 100,
                            'plugins' => [
                                'clips',
                                'fullscreen'
                            ]
                        ]
                    ]); ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true])->label('Название En'); ?>
                    <?= $form->field($model, 'description_en')->label('Описание En')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 100,
                            'plugins' => [
                                'clips',
                                'fullscreen'
                            ]
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default hidden">
        <div class="box-header with-border">Склад</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model->quantity, 'quantity')->input('number', ['min' => 0])->label('Количество') ?>
                </div>
                <div class="col-md-6">
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Цена</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model->price, 'new')->label('Новая')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model->price, 'old')->label('Старая')->textInput(['maxlength' => true]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Категории</div>
                <div class="box-body">
                    <?= $form->field($model->categories, 'main')->label('Главная')->dropDownList($model->categories->categoriesList(), ['prompt' => '--Выберите--', 'class'=>'form-control catID']); ?>
                    <?= $form->field($model->categories, 'others')->label('Прочие')->checkboxList($model->categories->categoriesList()) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 taste">
            <div class="box box-default">
                <div class="box-header with-border">Тэги</div>
                <div class="box-body">
                    <?= $form->field($model->tags, 'existing')->label('Имеющиеся')->inline()->checkboxList($model->tags->tagsList()); ?>
                    <?= $form->field($model->tags, 'textNew')->label('Новый')->textInput(); ?>
                </div>
            </div>
        </div>
    </div>

<!--    <div class="box box-default">-->
<!--        <div class="box-header with-border">Характеристики</div>-->
<!--        <div class="box-body">-->
<!--            --><?php //foreach ($model->values as $key => $value): ?>
<!--                --><?php //if ($variants = $value->variantsList()): ?>
<!--                    --><?//= $form->field($value, '[' . $key . ']value',['options'=>['class'=>'variant'.$value->getCategoryId(). ' form-group variants']])->dropDownList($variants, ['prompt' => '--Выберите--']) ?>
<!--                --><?php //else: ?>
<!--                    --><?//= $form->field($value, '[' . $key . ']value',['options'=>['class'=>'variant'.$value->getCategoryId() . ' form-group variants']])->textInput() ?>
<!--                --><?php //endif ?>
<!--            --><?php //endforeach; ?>
<!--        </div>-->
<!--    </div>-->

    <div class="box box-default">
        <div class="box-header with-border">Фотографии</div>
        <div class="box-body">
            <?= $form->field($model->photos, 'files[]')->label('Файлы')->widget(FileInput::class, [
                'options' => [
                    'accept' => ' .jpg, .png, .gif',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                ]
            ]); ?>
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
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>