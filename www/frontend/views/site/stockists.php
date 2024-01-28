<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $regions \common\models\StockistsCategories[] */
?>
<div id="topBlock">

</div>
<div id="contentBlock" class="pageContentBlock stock">
    <?php foreach ($regions as $region): ; ?>
        <div class="stockRegion">
            <h2><?= $region->title; ?></h2>
            <div class="stockItems">
            <?php if(count($region->activeStockistsItems) < 1){?>
                <div class="stockItem">Empty</div>
            <?php } else { ?>
                <?php foreach ($region->activeStockistsItems as $item): ; ?>
                    <div class="stockItem"><?= $item->value; ?></div>
                <?php endforeach; ?>
            <?php };?>
            </div>
        </div>
    <?php endforeach; ?>
</div>


