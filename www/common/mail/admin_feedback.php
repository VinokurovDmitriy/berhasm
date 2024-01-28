<?php
/**
 * @var $user_name
 * @var $user_email
 * @var $user_phone
 * @var $message
 */
?>

<style>
    .h2 {
        font-size: 2em;
        font-weight: lighter;
        text-transform: uppercase;
    }
</style>
<table>
    <tr>
        <td colspan='2' class='form-heading'>
            <h2>Отзыв с сайта</h2>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>Имя</td>
        <td>: <?= $user_name; ?></td>
    </tr>
    <tr>
        <td>E-Mail</td>
        <td>: <?= $user_email ?></td>
    </tr>
    <tr>
        <td>Телефон</td>
        <td>: <?= $user_phone; ?></td>
    </tr>
    <tr>
        <td>Комментарий</td>
        <td>: <?= $message ?></td>
    </tr>
</table>