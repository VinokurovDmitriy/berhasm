<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use shop\helpers\PriceHelper;
use kartik\select2\Select2;
use yii\widgets\Pjax;

/* @var $model \shop\forms\Shop\OrderForm */
/* @var $promoModel \frontend\models\PromoCodeForm */
/* @var $cart \shop\cart\Cart */
/* @var $item \shop\cart\CartItem */
/* @var $delivery \common\models\Delivery */

$deliveries = $model->getDeliveryPrices();
?>
<div id="topBlock">
    <a href="<?= Yii::$app->request->referrer; ?>" class="backLink"><span
                class="icon-back"></span><?= Yii::t('app', 'Go back'); ?></a>
</div>
<div id="checkout">
    <div class="hidden-array check-json-arr">
        <?php echo json_encode($deliveries) ?>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'checkoutForm']); ?>
    <div class="row">
        <div class="col-lg-4 checkout-col">
            <h3><?= Yii::t('app', 'One Step Checkout'); ?></h3>
            <h5><?= Yii::t('app', 'Shipping Address'); ?></h5>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            <div class="row">
                <div class="col-sm-6 country-var">
                    <?= $form->field($model, 'country')->widget(Select2::class, [
                        'data' => $model->getCountries(),
                    ]); ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                        'mask' => '+9 (999) 999-99-99',
                    ]) ?>
                </div>
            </div>
        </div>
        <!--                --><? //= $form->field($model, 'remark')->textarea(['rows' => 5]); ?>
        <div class="col-lg-4 checkout-col">
            <h5><?= Yii::t('app', 'Shipping fee'); ?></h5>
<!--            <p class="freeDeliveryDesc">--><?//= Yii::t('app','Free delivery on orders over');?><!--:<br>--><?//= PriceHelper::format(Yii::$app->params['freeShipment']); ?><!--</p>-->
            <div id="delivery_radio_cont"></div>

            <!--            <div class="checkout_block">-->
            <!--                --><?php //$deliveryArr = []; ?>
            <!--                --><?php //foreach ($model->getDeliveries() as $delivery): ; ?>
            <!--                    --><?php //$deliveryArr[$delivery->price] = PriceHelper::format($delivery->price) . ' ' . $delivery->title; ?>
            <!--                    <div class="delivery_prop">-->
            <!--                        <label class="delivery_prop_label delivery_prop_mod">-->
            <!--                            <input type="radio"-->
            <!--                                   class="delivery_prop_btn"-->
            <!--                                   name="delivery_prop_weight"-->
            <!--                                   value="--><? //= $delivery->price; ?><!--"-->
            <!--                            >-->
            <!--                            <div class="delivery_prop_text">-->
            <!--                                --><? //= PriceHelper::format($delivery->price) . ' ' . $delivery->title; ?>
            <!--                            </div>-->
            <!--                        </label>-->
            <!--                    </div>-->
            <!--                --><?php //endforeach; ?>
            <!--            </div>-->
            <?= $form->field($model, 'delivery_method')->textInput()->label('HIDDEN INPUT'); ?>

            <h5><?= Yii::t('app', 'Payment Methods'); ?></h5>
            <div class="checkout_block">
                <?= $form->field($model, 'pay_method')->radioList(
                    $model->getPayMethods(),
                    [
                        'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                            $ch = $checked ? 'checked' : '';
                            return
                                ' 
                      <div class="checkout_pay_input">
                          <div class="checkout_pay_radio_wrap">
                             <input type="radio" class="pay_radio" id="pay_method-' . $value . '" name="' . $name . '" value="' . $value . '" ' . $ch . ' />
                          </div>
                          <label for="pay_method-' . $value . '">' . $label . '</label>
                      </div>
                      ';
                        }
                    ]
                )->label(false); ?>
            </div>

            <h5 class="promo-pop-btn">
                <div class="ico"></div>
                <?= Yii::t('app', 'Apply Discount Code'); ?>
            </h5>
            <div class="hidden-promo-result" data-discount="<?= Yii::$app->session->get('discount');?>">
                <?= $form->field($model, 'promoCode')->textInput(['maxlength' => true, 'minlenght' => true]); ?>
            </div>
        </div>

        <div class="col-lg-4 checkout-col">
            <h5><?= Yii::t('app', 'Order Summary'); ?></h5>
            <?php foreach ($cart->getItems() as $item): ?>
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
                            <span><?= Yii::t('app', 'Size'); ?>: </span>
                            <?= $item->getModification()->name; ?>
                        </div>
                        <div class="header_basket_block_item_count">
                            <span><?= Yii::t('app', 'Quantity'); ?>: </span>
<!--                            <a class="qtyBtn" href="--><?//= Url::to(['cart/minus', 'id' => $item->getId()]); ?><!--"><span-->
<!--                                        class="icon-minus"></span></a>-->
                            <div class="checkout_basket_item_count checkout_basket_count_col">
                                <div class="checkout_basket_count_btns count_btns">
                                    <div class="checkout_basket_count_number count_number">
                                                <span>
                                                    <?= $item->getQuantity(); ?>
                                                </span>
                                    </div>
                                </div>
                            </div>
<!--                            <a class="qtyBtn" href="--><?//= Url::to(['cart/plus', 'id' => $item->getId()]); ?><!--"><span-->
<!--                                        class="icon-plus"></span></a>-->
<!--                            <a href="--><?//= Url::to(['/cart/remove', 'id' => $item->getId()]); ?><!--">-->
<!--                                <span>--><?//= Yii::t('app', 'Remove'); ?><!--</span>-->
<!--                            </a>-->
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
            <?php endforeach; ?>


            <?php //Pjax::begin(); ?>
            <div class="checkout_cart_subtotal">
                <span><?= Yii::t('app', 'Cart Subtotal'); ?>:</span>
                <strong class="txtRight"
                        data-val="<?= preg_replace(['/\s/', '/€/', '/₽/', '/$/'], '', PriceHelper::format($cart->getCost()->getTotal())); ?>"><?= PriceHelper::format($cart->getCost()->getTotal()); ?></strong>
            </div>
<!--            --><?php //if (Yii::$app->session->get('discount')): ; ?>
<!--                <div class="checkout_cart_promo">-->
<!--                    <span>--><?//= Yii::t('app', 'Discount'); ?><!--:</span>-->
<!--                    <span class="txtRight">--><?//= Yii::$app->session->get('discount'); ?><!--%</span>-->
<!--                </div>-->
<!--            --><?php //endif; ?>

            <?php if (Yii::$app->session->get('discount')):?>
                <div class="checkout_discount">
                    <span><?= Yii::t('app', 'Discount'); ?>:</span>
                    <span class="txtRight"><?= Yii::$app->session->get('discount');?>%</span>
                </div>
            <?php else: ?>
            <div class="checkout_discount hidden">
                <span><?= Yii::t('app', 'Discount'); ?>:</span>
                <span class="txtRight">0%</span>
            </div>
            <?php endif; ?>
            <div class="checkout_shipping_cost">
                <span><?= Yii::t('app', 'Shipping'); ?>:</span>
                <span class="txtRight"><?= $cart->getCost()->getTotal() >= Yii::$app->params['freeShipment'] ? Yii::t('app', 'Free') : Yii::t('app', 'Not yet calculated') ?></span>
            </div>
            <div class="checkout_order_total">
                <strong><?= Yii::t('app', 'Order Total'); ?>:</strong>
                <?php
                    $totalCost = $cart->getCost()->getTotal();
                    $discCost = $totalCost * Yii::$app->session->get('discount')*0.01;
                ;?>
                <strong class="txtRight"><?= PriceHelper::format($totalCost - $discCost + $deliveryValue); ?></strong>
            </div>
            <?php //Pjax::end(); ?>


            <div>
                <?= $form->field($model, 'news',
                    [
                        'options' => ['class' => 'form-group data-checkbox'],
                        'checkboxTemplate' => "{input}{label}\n{hint}\n{error}"
                    ])->checkbox(); ?>
            </div>
            <div class="checkout_submit_block">
                <?= Html::submitButton(
                        Yii::t('app', 'Go to checkout'),
                        [
                            'class' => 'btn black btn-submit',
                            'onclick'=>"ym(50136718,'reachGoal','ПереходНаСтраницуОплаты'); fbq('track', 'AddPaymentInfo'); return true;"
                        ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

        <div class="checkout-promo-popup" data-discount="0">
            <div class="checkout_block">
                <form>
                    <div class="form-group">
                        <label for="promo_test"><?= Yii::t('app', 'Discount Code'); ?></label>
                        <input type="text" name="promo-test" id="promo_test" class="form-control">
                    </div>
                    <span class="btn black btn-submit promo-btn">
                            <?= Yii::t('app', 'Apply Code') ?>
                    </span>

                </form>
            </div>
        </div>

    <div class="promo-message succes">
        <div class="message-close-btn">
            X
        </div>

        <div class="text">
            <?= Yii::t('app', 'Discount Code is valid, your discount is '); ?> <span class="counter"></span> %
        </div>

    </div>

    <div class="promo-message error invalid">
        <div class="message-close-btn">
            X
        </div>
        <div class="text">
            <?= Yii::t('app', 'Discount Code is invalid'); ?>
        </div>
    </div>
    <div class="promo-message error exist">
        <div class="message-close-btn">
            X
        </div>
        <div class="text">
            <?= Yii::t('app', 'Discount Code already applied'); ?>
        </div>
    </div>


</div>
