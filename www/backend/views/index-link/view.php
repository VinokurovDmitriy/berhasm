<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\IndexLink;

/* @var $this yii\web\View */
/* @var $model common\models\IndexLink */

$this->title = $model->title_ru;
//$this->params['breadcrumbs'][] = ['label' => 'Index Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-link-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title_ru',
                    'title_en',
                    'link:url'
                ],
            ]) ?>
        </div>
    </div>
</div>
