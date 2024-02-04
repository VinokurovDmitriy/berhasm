<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Press;

/* @var $this yii\web\View */
/* @var $model common\models\Press */

$title = $model->title . ' ' . $model->date;
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Пресса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="press-view">

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
                    'title',
                    'date',
                    'link',
                    'sort',
                    'status',
                ],
            ]) ?>

        </div>
    </div>
</div>
