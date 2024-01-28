<?php
/**
 * @var $cart \shop\cart\Cart
 * @var $item \shop\cart\CartItem
 * @var $order \shop\entities\Shop\Orders
 * @var $country \common\models\Countries
 */
?>

<div class="table-responsive">
    <table style="width: 100%; border: 1px solid #ddd; border-collapse: collapse;">
        <thead>
        <tr style="background: #f9f9f9;">
            <th style="padding: 8px; border: 1px solid #ddd;">Артикул</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Наименование</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Размер</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Количество</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Цена</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Сумма</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart->getItems() as $item) : ?>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;"><?= $item->getProduct()->code ?></td>
                <td style="padding: 8px; border: 1px solid #ddd;"><?= $item->getProduct()->name_ru ?></td>
                <td style="padding: 8px; border: 1px solid #ddd;"><?= $item->getModification()->name ?></td>
                <td style="padding: 8px; border: 1px solid #ddd;"><?= $item->getQuantity(); ?></td>
                <td style="padding: 8px; border: 1px solid #ddd;"><?= $item->getPrice() ?></td>
                <td style="padding: 8px; border: 1px solid #ddd;"><?= $item->getCost() ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">Итого:</td>
            <td style="padding: 8px; border: 1px solid #ddd;"><?= $cart->getAmount() ?></td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">На сумму:</td>
            <td style="padding: 8px; border: 1px solid #ddd;"><?= $cart->getCost()->getTotal() ?></td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">Скидка:</td>
            <td style="padding: 8px; border: 1px solid #ddd;"><?= $order->discount ?: 0 ?>%</td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">Доставка:</td>
            <td style="padding: 8px; border: 1px solid #ddd;"><?= $order->delivery_method ? $order->delivery_method . '₽' : 'Бесплатно' ?></td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">Итоговая стоимость:</td>
            <td style="padding: 8px; border: 1px solid #ddd;"><?= $order->sum ?></td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">Способ оплаты:</td>
            <td style="padding: 8px; border: 1px solid #ddd;"><?= Yii::$app->params['payMethods'][$order->pay_method] ?></td>
        </tr>		
        <tr>
            <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Имя заказчика:</td>
            <td colspan="3" style="padding: 8px; border: 1px solid #ddd;"> <?= $order->name . ' ' . $order->lastName; ?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">E-mail заказчика:</td>
            <td colspan="3" style="padding: 8px; border: 1px solid #ddd;"><?= $order->email ?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Телефон заказчика:</td>
            <td colspan="3" style="padding: 8px; border: 1px solid #ddd;"><?= $order->phone ?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Адрес:</td>
            <td colspan="3" style="padding: 8px; border: 1px solid #ddd;"><?= $country->title . ', ' . $order->zip . ', ' . $order->city . ', ' . $order->address; ?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Время доставки:</td>
            <td colspan="3"
                style="padding: 8px; border: 1px solid #ddd;"><?= Yii::$app->formatter->asDate(time() + $country->time_max * 24 * 60 * 60, 'dd MMMM yyyy'); ?></td>
        </tr>
        <?php if ($order->remark) { ?>
            <tr>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Доп Информация:</td>
                <td colspan="3" style="padding: 8px; border: 1px solid #ddd;"><?= $order->remark ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>