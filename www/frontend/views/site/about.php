<?php
use yii\helpers\Url;
/**
 * @var $title /frontend/controllers/SiteController
 * @var $content /frontend/controllers/SiteController
 */
;?>
<div id="topBlock">

</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <?php if($title){?>
        <h1><?= $title;?></h1>
    <?php };?>
    <?= ($content)?$content:'';?>
</div>
