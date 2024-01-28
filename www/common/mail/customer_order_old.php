<?php

use shop\helpers\PriceHelper;
use common\models\Socials;

/* @var $cart \shop\cart\Cart */
/* @var $order \shop\entities\Shop\Orders */
/* @var $country \common\models\Countries */
/* @var $item \shop\cart\CartItem */

$socials = Socials::getDb()->cache(function () {
    return Socials::find()->having(['status' => 1])->orderBy('sort')->all();
}, Yii::$app->params['cacheTime']);
$labels = 'labels_' . Yii::$app->language;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Заголовок письма</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<table width="100%" border="0" cellpadding="0" cellspacing="0"
       style="margin:0 auto; padding:0; max-width: 100%;  background-color: #F6F6F6;">
    <tbody>
    <tr>
        <td>
            <table width="860px" border="0" cellpadding="0" cellspacing="0"
                   style="margin:0 auto; padding:0; max-width: 100%;  background-color: #ffffff;">
                <tbody>
                <tr>
                    <td align="center" valign="middle" style="padding-top: 30px; padding-bottom: 30px">
                        <img src="<?= Yii::$app->urlManager->hostInfo; ?>/files/logo-main.png" height="75px"
                             alt="Berhasm" style="display: block">
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="middle">
                        <table width="570px" border="0" cellpadding="0" cellspacing="0"
                               style="margin:0 auto; padding:0;">
                            <tbody>
                            <tr>
                                <td align="center" valign="middle"
                                    style="font-family: Arial, Helvetica, sans-serif; font-size: 40px; color: #000000; line-height: 1.5;">
                                    <?= Yii::t('app', 'Congratulations <br> on your purchase'); ?>

                                </td>
                            </tr>
                            <tr>
                                <td height="140px" align="center" valign="middle"
                                    style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;">
                                    <b> <?= Yii::t('app', 'Order Summary'); ?> </b>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="570px" border="0" cellpadding="0" cellspacing="0"
                                           style="width:100%; ;margin:0 auto; padding:0;">
                                        <tbody>

                                        <?php foreach ($order->orderItems as $item): ?>

                                            <tr>
                                                <td width="170px" style="padding-top: 30px;padding-bottom: 30px;">
                                                    <img src="<?= Yii::$app->urlManager->hostInfo . $item->product->mainPhoto->getThumbFileUrl('file', 'catalog_product_main'); ?>"
                                                         width="130px" alt="<?= $item->product->name; ?>"
                                                         style="display: block">
                                                </td>
                                                <td valign="bottom" width="400px"
                                                    style="padding-top: 30px;padding-bottom: 30px;">
                                                    <table width="400px" border="0" cellpadding="0" cellspacing="0"
                                                           style="margin:0 auto; padding:0;max-width: 100%">
                                                        <tbody>
                                                        <tr>

                                                            <td height="30px"
                                                                style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;">
                                                                <?= $item->product->name; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>

                                                            <td height="30px"
                                                                style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;">
                                                                <?= $item->product->price_old ? ' <span style="text-decoration: line-through">' . PriceHelper::format($item->product->price_old) . '</span>' : ''; ?>
                                                                <strong><?= PriceHelper::format($item->price_item); ?></strong>
                                                            </td>
                                                        </tr>
                                                        <tr>

                                                            <td height="30px"
                                                                style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;">
                                                                <?= Yii::t('app', 'Size'); ?>
                                                                : <?= $item->modification->name; ?>
                                                            </td>
                                                        </tr>

                                                        <tr>

                                                            <td height="30px"
                                                                style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;">
                                                                <?= Yii::t('app', 'Quantity'); ?>
                                                                : <?= $item->qty_item; ?>
                                                            </td>
                                                        </tr>


                                                        </tbody>


                                                    </table>

                                                </td>
                                            </tr>

                                        <?php endforeach; ?>


                                        </tbody>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td style="padding-top: 20px; padding-bottom: 20px">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                           style="margin:0 auto; padding:0;">
                                        <tbody>

                                        <tr>
                                            <td height="40px"
                                                style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;">
                                                <?= Yii::t('app', 'Total:'); ?>
                                            </td>
                                            <td height="40px" align="right"
                                                style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;">
                                                <b><?= PriceHelper::format($order->sum); ?></b>
                                            </td>
                                        </tr>

                                        <?php if ($order->discount): ; ?>
                                            <tr>
                                                <td style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; padding-top: 20px;">
                                                    <?= Yii::t('app', 'Discount:'); ?>
                                                </td>
                                                <td align="right"
                                                    style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; padding-top: 20px;">
                                                    <?= $order->discount; ?>%
                                                </td>
                                            </tr>


                                        <?php endif; ?>


                                        </tbody>
                                    </table>


                                </td>
                            </tr>


                            <tr>
                                <td style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; padding-top: 20px;">
                                    <?= Yii::t('app', 'Address:'); ?> <?= $country->title . ', ' . $order->zip . ', ' . $order->city . ', ' . $order->address; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; padding-top: 20px;">
                                    <?= Yii::t('app', 'Name:'); ?> <?= $order->name . ' ' . $order->lastName; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; padding-top: 20px;padding-bottom: 40px">
                                    <?= Yii::t('app', 'Delivery:'); ?> <?= Yii::$app->formatter->asDate(time() + $country->time_max * 24 * 60 * 60, 'dd MMMM yyyy'); ?>
                                </td>
                            </tr>

                            </tbody>
                        </table>


                    </td>
                </tr>

                <tr>
                    <td align="center" valign="middle"
                        style="margin:0 auto; padding: 30px 0; background-color: #000000;">
                        <table width="860px" border="0" cellpadding="0" cellspacing="0"
                               style="margin:0 auto; padding:0; max-width: 100%;">
                            <tbody>
                            <tr>
                                <td align="center" valign="middle"
                                    style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;padding-top: 30px;">
                                    <?php foreach ($socials as $social): ; ?>
                                        <a target="_blank" href="<?= $social->link; ?>"
                                           style="color: #ffffff; text-decoration: none; margin: 0 5px;"><?= $social->$labels[$social->icon]; ?></a>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="middle"
                                    style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;padding-top: 20px;">
                                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['site/customer-care']); ?>"
                                       style="color: #ffffff; text-decoration: none; margin: 0 5px;"><?= Yii::t('app', 'Customer care'); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="middle"
                                    style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #000000;padding-top: 40px; padding-bottom: 40px;">
                                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['site/return']); ?>"
                                       style="color: #ffffff; text-decoration: none; margin: 0 5px;"><?= Yii::t('app', 'Return'); ?></a>
                                </td>
                            </tr>

                            </tbody>
                        </table>


                    </td>
                </tr>


                </tbody>


            </table>
        </td>
    </tr>


    </tbody>

</table>


</body>
</html>