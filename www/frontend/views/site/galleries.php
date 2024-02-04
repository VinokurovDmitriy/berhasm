<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $galleries \common\models\GalleryCategories[] */
?>
<div id="topBlock">

</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <?php foreach ($galleries as $gallery): ?>
        <a class="galItem" href="<?= Url::to(['site/gallery', 'slug' => $gallery->slug]); ?>">
            <img src="<?= $gallery->image; ?>" alt="<?= $gallery->title; ?>">
            <h2><?= $gallery->title; ?></h2>
        </a>
    <?php endforeach; ?>
</div>

