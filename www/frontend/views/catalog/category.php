<?php

use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
?>

<div id="topBlock">

</div>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['class' => 'pageContentBlock catalog '. Yii::$app->language, 'id' => 'contentBlock'],
    'itemOptions' => ['tag' => false,],
    'summary' => '',
    'itemView' => function ($model) {
        return $this->render('_product', ['product' => $model]);
    },
]);?>

<div class="hidden load-more-helper" data-text="<?= Yii::t('app', 'Load more'); ?>"></div>
