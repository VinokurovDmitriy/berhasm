<?php

/* @var $this yii\web\View */
/* @var $cares \common\models\CustomerCare[] */
$fragment = parse_url(Yii::$app->request->url, PHP_URL_FRAGMENT);

?>

<div id="topBlock" data-id="<?= Yii::$app->request->url;?>">
    <a href="<?= Yii::$app->request->absoluteUrl != Yii::$app->request->referrer ? Yii::$app->request->referrer : '/'; ?>" class="backLink"><span class="icon-back"></span><?= Yii::t('app', 'Go back'); ?></a>
</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?> customerCare">
    <?php foreach ($cares as $care): ; ?>
    <div class="customer_care_block" data-id="<?= $care->id;?>">
        <h2 class="customer_care_title">
            <span><?= $care->title; ?></span>
        </h2>
        <div class="customer_care_html">
            <?= $care->html; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
