<?php

use yii\helpers\Url;
use shop\helpers\PriceHelper;

/* @var $cart \shop\cart\Cart */
/* @var $addedItem \shop\cart\CartItem */
/* @var $item \shop\cart\CartItem */

$addedItem = array_pop($cart->getItems());
if ($_SESSION['cartStatus'] == 'active') {
    $_SESSION['cartStatus'] = null;
    $activeBasketDrop = 'active';
}
else {
    $activeBasketDrop = '';
}
?>

<div class="header_basket">
    <?php if ($cart->getItems() != null): ; ?>
        <div class="header_basket_block <?= $activeBasketDrop;?>">
            <div class="header_basket_block_title">
                <strong id="header-basket-amount"><?= $cart->getAmount(); ?></strong>
                <span> <?= Yii::t('app', 'items');?></span>
                <div class="header_basket_block_close cartClose">
                    <span class="icon-cross"></span>
                </div>
            </div>
            <div class="header_basket_block_items_container">
                <div class="header_basket_block_items_wrap">
                    <?php foreach ($cart->getItems() as $item):;?>
                        <div class="header_basket_block_item">
                            <div class="header_basket_block_item_img"
                                 style="background-image: url(<?= $item->getProduct()->mainPhoto->getThumbFileUrl('file', 'catalog_product_main'); ?>)">
                            </div>
                            <div class="header_basket_block_item_right">
                                <div class="header_basket_block_item_title">
                                    <?= $item->getProduct()->name; ?>
                                </div>
                                <div class="header_basket_block_item_price">
                                    <strong><?= PriceHelper::format($item->getCost()); ?></strong>
                                </div>
                                <div class="header_basket_block_item_mod">
                                    <span><?= Yii::t('app', 'Size');?>: </span>
                                    <?= $item->getModification()->name; ?>
                                </div>
                                <div class="header_basket_block_item_count">
                                    <span><?= Yii::t('app', 'Quantity'); ?>: </span>
                                    <a class="qtyBtn" href="<?= Url::to(['cart/minus', 'id' => $item->getId()]); ?>"><span
                                                class="icon-minus"></span></a>
                                    <div class="checkout_basket_item_count checkout_basket_count_col">
                                        <div class="checkout_basket_count_btns count_btns">
                                            <div class="checkout_basket_count_number count_number">
                                                <span>
                                                    <?= $item->getQuantity(); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="qtyBtn" href="<?= Url::to(['cart/plus', 'id' => $item->getId()]); ?>"><span
                                                class="icon-plus"></span></a>
                                    <a href="<?= Url::to(['/cart/remove', 'id' => $item->getId()]); ?>">
                                        <span><?= Yii::t('app', 'Remove'); ?></span>
                                    </a>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="clr"></div>
            </div>
            <div class="header_basket_block_footer">
                <div class="header_basket_block_cost">
                    <span> <?= Yii::t('app', 'Subtotal');?>: </span>
                    <strong class="header_basket_block_total"><?= PriceHelper::format($cart->getCost()->getTotal()); ?></strong>
                </div>
                <div class="header_basket_block_link">
<!--                    <span class="btn white cartClose">--><?//= Yii::t('app', 'Continue Shopping');?><!--</span>-->
<!--                    <a class="btn white" href="--><?//= Url::to(['/cart/clear']); ?><!--">-->
<!--                        <span> --><?//= Yii::t('app', 'Clear');?><!--</span>-->
<!--                    </a>-->
                    <a class="btn black" href="<?= Url::to(['/order/checkout']); ?>" onclick="fbq('track', 'InitiateCheckout', {currency: 'RUB', value: <?= $cart->getCost()->getTotal() ?>});">
                        <span> <?= Yii::t('app', 'Checkout');?></span>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <a class="header_basket_count" href="<?= Url::to('/cart/index'); ?>">
       <i class="icon-cart"></i>
        <?php if ($cart->getAmount() != null): ; ?>
            <div>
                <span><?= $cart->getAmount(); ?></span>
            </div>
        <?php endif; ?>
    </a>
</div>
