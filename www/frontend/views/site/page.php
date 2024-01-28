<?php
use yii\helpers\Url;
/**
 * @var $title /frontend/controllers/SiteController
 * @var $content /frontend/controllers/SiteController
 */
;?>
<div id="topBlock">
    <a href="<?= Url::to(['catalog/index']);?>" class="backLink"><span class="icon-back"></span><?= Yii::t('app', 'Back to catalog'); ?></a>
</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <?php if($title){?>
        <h1><?= $title;?></h1>
    <?php };?>
    <?= ($content)?$content:'';?>
</div>
