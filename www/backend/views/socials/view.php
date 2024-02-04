<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Socials;

/* @var $this yii\web\View */
/* @var $model common\models\Socials */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Socials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socials-view">

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
                 'icon',
                 'link',
                 'sort',
                 'status',
            ],
            ]) ?>

        </div>
    </div>
</div>
