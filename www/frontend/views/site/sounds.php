<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $sounds \common\models\Sounds[] */
?>

<div id="topBlock">

</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <?php foreach ($sounds as $sound): ?>
        <?php if($sound->link){?>
            <div class="soundItem">
                <?= $sound->link;?>
            </div>
        <?php } else { ?>
            <a href="<?= $sound->filePath; ?>" class="soundItem" target="_blank" style="background-image: url(<?= $sound->image; ?>)">
                <h2><?= $sound->title; ?></h2>
            </a>
        <?php };?>
    <?php endforeach; ?>
</div>