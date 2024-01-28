<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SizeGuide;

/* @var $this yii\web\View */
/* @var $model \common\models\SizeGuide */

$this->title = $model->title_ru;
$this->params['breadcrumbs'][] = ['label' => 'Таблица размеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-care-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' =>
            'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'title_ru',
                            [
                                'attribute' => 'html_ru',
                                'format' => 'raw'
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'title_en',
                            [
                                'attribute' => 'html_en',
                                'format' => 'raw'
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
