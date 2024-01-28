<?php

/** @var $this yii\web\View
 * @var $categories \shop\entities\Shop\Category[]
 * @var $indexLinks \common\models\IndexLinks[]
 * @var $link \common\models\IndexLink
 * @var $file \common\models\Slider
 */
?>
<div id="indexShopLinks" class="block fullW">
    <?php foreach ($indexLinks as $indexLink) { ?>
        <a href="<?= $indexLink->link ?>" class="indexShopLink">
            <span class="indexLinkTitle first"><?= $indexLink->title ?></span>
            <span class="indexLinkTitle second"><?= $indexLink->sub_title ?></span>
            <img class="indexLinkBg" src="<?= $indexLink->image ?>"/>
        </a>
    <?php } ?>
</div>
<div id="indexVideo" class="block fullW">

    <?php if (!in_array(substr($file->image_name, strpos($file->image_name, '.') + 1), ['mp4'])): ?>
        <img class="indexVideoImage" src="<?= $file->image; ?>" alt="berhasm store">
    <?php else: ?>
        <div class="videoControls mute">
            <div class="icon icon-mute"></div>
        </div>
        <video id="indexVideoPlayer" class="block fullW" autoplay loop muted playsinline>
            <source src="<?= $file->image ?>" type="video/mp4">
        </video>
    <?php endif ?>
</div>
<div id="indexNewLink" class="block fullW">
    <a class="squareLink" href="<?= $link->link ?>"><?= $link->title ?></a>
</div>
