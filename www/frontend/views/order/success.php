<?php
use yii\helpers\Url;

/***
 * @var $order \shop\entities\Shop\Orders
 * @var $module \common\models\Modules
 */

?>
<script>
    fbq('track', 'Purchase', {currency: "RUB", value: <?= $order->sum ?>});
</script>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <h1><?= $module->title ?></h1>
    <div class="successIcon"><div class="icon-cart-round"></div></div>
    <div class="successOrderId"><?= Yii::t('app', 'Your Order'); ?>#: <?= $order->id ?></div>
    <div class="successText"><?= $module->html ?></div>
</div>
