<?php

use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $model shop\forms\manage\Shop\Product\ProductEditForm */

$this->title = 'Изменить: ' . $product->name_ru;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<div class="product-update">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Общие</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true])->label('Код'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true])->label('Название'); ?>
                    <?= $form->field($model, 'description_ru')->label('Описание')->widget(Widget::class, [
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
                    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true])->label('Название'); ?>
                    <?= $form->field($model, 'description_en')->label('Описание')->widget(Widget::class, [
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

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Категории</div>
                <div class="box-body">
                    <?= $form->field($model->categories, 'main')->label('Главная')->dropDownList($model->categories->categoriesList(), ['prompt' => '']) ?>
                    <?= $form->field($model->categories, 'others')->label('Прочие')->checkboxList($model->categories->categoriesList()) ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Тэги</div>
                <div class="box-body">
                    <?= $form->field($model->tags, 'existing')->label('Имеющиеся')->inline()->checkboxList($model->tags->tagsList()) ?>
                    <?= $form->field($model->tags, 'textNew')->label('Новый')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <!--    <div class="box box-default">-->
    <!--        <div class="box-header with-border">Характеристики</div>-->
    <!--        <div class="box-body">-->
    <!--            --><?php //foreach ($model->values as $i => $value): ?>
    <!--                --><?php //if ($variants = $value->variantsList()): ?>
    <!--                    --><? //= $form->field($value, '[' . $i . ']value')->dropDownList($variants, ['prompt' => '--Выберите--']) ?>
    <!--                --><?php //else: ?>
    <!--                    --><? //= $form->field($value, '[' . $i . ']value')->textInput() ?>
    <!--                --><?php //endif ?>
    <!--            --><?php //endforeach; ?>
    <!--        </div>-->
    <!--    </div>-->

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
