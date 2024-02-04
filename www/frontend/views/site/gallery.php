<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $gallery \common\models\GalleryCategories */
?>


<div id="topBlock">
    <a href="<?= Url::to(['site/galleries']);?>" class="backLink"><span class="icon-back"></span><?= Yii::t('app', 'Go back'); ?></a>
</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <?php foreach ($gallery->activeGalleryItems as $item): ?>
        <img src="<?= $item->image; ?>" class="galPic" alt="<?= $item->title; ?>">
    <?php endforeach; ?>
</div>



