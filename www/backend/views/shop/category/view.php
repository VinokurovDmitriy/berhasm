<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $category shop\entities\Shop\Category */

$this->title = $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $category->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $category->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить данную позицию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Общие</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $category,
                'attributes' => [
                    'id',
                    'name_ru',
                    'name_en',
                    'slug',
//                    'title',
                ],
            ]) ?>
        </div>
    </div>

    <!--    <div class="box">-->
    <!--        <div class="box-header with-border">Описание</div>-->
    <!--        <div class="box-body">-->
    <!--            --><? //= Yii::$app->formatter->asHtml($category->description, [
    //                'Attr.AllowedRel' => array('nofollow'),
    //                'HTML.SafeObject' => true,
    //                'Output.FlashCompat' => true,
    //                'HTML.SafeIframe' => true,
    //                'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
    //            ]) ?>
    <!--        </div>-->
    <!--    </div>-->

    <div class="box">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6">
                    <?= DetailView::widget([
                        'model' => $category,
                        'attributes' => [
                            'meta.title_ru',
                            'meta.description_ru',
                            'meta.keywords_ru',
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= DetailView::widget([
                        'model' => $category,
                        'attributes' => [
                            'meta.title_ru',
                            'meta.description_ru',
                            'meta.keywords_ru',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
