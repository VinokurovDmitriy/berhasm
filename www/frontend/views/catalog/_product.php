<?php

use Yii;
use yii\helpers\Url;
use shop\helpers\PriceHelper;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
$mainPhoto = $product->mainPhoto->getImageFileUrl('file');
$class = '';
if ($product->quantity < 1) {
    $class = $product->tagAssignments ? 'coming' : 'sold';
}
?>

<a class="galItem catProd <?= $class; ?>"
   href="<?= Url::to(['catalog/product', 'slug' => $product->slug]); ?>"
   onclick="ym(50136718,'reachGoal','ПросмотрКарточкиТовра'); return true;"
>
    <div class="catImg">
        <img class="mainPhoto"
             src="<?= $mainPhoto ?>"
             alt="<?= $product->name; ?>">
        <?php if(!Yii::$app->devicedetect->isMobile()) {?>
            <?php if ($product->secondaryPhoto): ; ?>
                <img class="secondaryPhoto"
                     src="<?= $product->secondaryPhoto->getImageFileUrl('file'); ?>"
                     alt="<?= $product->name; ?>">
            <?php else: ?>
                <img class="secondaryPhoto"
                     src="<?= $mainPhoto ?>"
                     alt="<?= $product->name; ?>">
            <?php endif; ?>
        <?php }?>
    </div>
    <div class="catAvailability">
        <?php $qty = ArrayHelper::getColumn($product->modifications, 'quantity');
        natcasesort($qty);
        $i = array_pop($qty);
        if ($i > 0): ?>
            <?= Yii::t('app', 'Available in: '); ?>
        <?php endif; ?>
        <?php foreach ($product->modifications as $modification): ; ?>
            <?= $modification->quantity > 0 ? $modification->name : null ?>
        <?php endforeach; ?>
    </div>
    <h2><?= $product->name; ?></h2>
    <?php if ($product->quantity < 1): ; ?>
        <?php if ($product->tagAssignments): ; ?>
            <?= Yii::t('app', 'Sold Out'); ?>
        <?php else:; ?>
            <?= Yii::t('app', 'Sold Out'); ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($product->price_old): ; ?>
        <div>
            <span style="text-decoration: line-through; color: gray"><?= PriceHelper::format($product->price_old); ?></span>
            <span> | </span>
            <span><?= Yii::t('app', 'Скидка');?>&nbsp;<?= number_format(100 - ($product->price_new / $product->price_old * 100), 0, '.', ' ');?>%</span>
        </div>
    <?php endif; ?>
    <div><strong <?= $product->price_old ? 'style="color:red"' : '';?>><?= PriceHelper::format($product->price_new); ?></strong></div>

</a>
