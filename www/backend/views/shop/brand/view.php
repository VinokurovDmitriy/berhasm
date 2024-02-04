<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $brand shop\entities\Shop\Brand */

$this->title = $brand->name;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $brand->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $brand->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Общие</div>
        <div class="row">
            <div class="col-9">
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $brand,
                        'attributes' => [
                            'id',
                            'name',
                            'slug',
                            'country',
                            [
                                'attribute' => 'description',
                                'format' => 'raw'
                            ]
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="col-3" style="text-align: center">
                <div class="box-body">
                    <?= Html::a(
                        Html::img($brand->getThumbFileUrl('file', 'thumb')),
                        $brand->getUploadedFileUrl('file'),
                        ['class' => 'thumbnail', 'target' => '_blank']
                    ) ?>
                    Логотип
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $brand,
                'attributes' => [
                    'meta.title',
                    'meta.description',
                    'meta.keywords',
                ],
            ]) ?>
        </div>
    </div>
</div>