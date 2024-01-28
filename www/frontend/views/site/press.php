<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $press \common\models\Press[] */

?>
<div id="topBlock">

</div>
<div id="contentBlock" class="pageContentBlock press">
    <?php foreach ($press as $item): ; ?>
    <div class="pressLink">
        <a target="_blank"  href="<?= $item->link; ?>"><?= $item->title;?> <?= ($item->date) ? " ({$item->date})":''; ?></a>
    </div>
    <?php endforeach; ?>
</div>

