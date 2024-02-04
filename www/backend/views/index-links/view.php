<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\IndexLinks;

/* @var $this yii\web\View */
/* @var $model common\models\IndexLinks */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Index Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-links-view">

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
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                             'id',
                 'title_ru',
                 'title_en',
                 'sub_title_ru',
                 'sub_title_en',
                 'image_name',
                 'link',
                 'sort',
                 'status',
            ],
            ]) ?>

        </div>
    </div>
</div>
