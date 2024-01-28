<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\IndexLink;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Index Links';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-link-index">
        
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>

    <div class="box">
        <div class="box-body">
                                   <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                                 'id',
                 'title_ru',
                 'title_en',
                 'link',

                ['class' => 'yii\grid\ActionColumn'],
                ],
                ]); ?>
                                </div>
    </div>
</div>
