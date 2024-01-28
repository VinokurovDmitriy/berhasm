<?php

use shop\helpers\PriceHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $cart \shop\cart\Cart */
/* @var $item \shop\cart\CartItem */

$amount = $cart->getAmount();
$text = $amount == 1 ? 'item' : 'items';
?>
<div>
    <?= $amount . ' ' . Yii::t('app', $text); ?>
    <?php foreach ($cart->getItems() as $item): ?>
        <?= $item->product->mainPhoto->getThumbFileUrl('file', 'admin'); ?>
        <?= $item->product->name; ?>
        <?= PriceHelper::format($item->getCost()); ?>
        <?php if ($item->modificationId): ; ?>
            <?= Yii::t('app', 'Size'); ?>:  <?= $item->getModification()->name; ?>
        <?php endif; ?>
        <?= Yii::t('app', 'Quantity'); ?>:  <?= $item->quantity; ?>
        <a href="<?= Url::to(['cart/remove', 'id' => $item->getId()]); ?>"><?= Yii::t('app', 'Remove'); ?></a>
    <?php endforeach; ?>
    <?= Yii::t('app', 'Subtotal'); ?>:
    <?= PriceHelper::format($cart->getCost()->getTotal()); ?>
    <a onclick="fbq('track', 'InitiateCheckout', {currency: "RUB", value: <?= $cart->getCost()->getTotal() ?>});" href="<?= Url::to(['/order/checkout']); ?>"><?= Yii::t('app', 'Checkout'); ?></a>
</div>
