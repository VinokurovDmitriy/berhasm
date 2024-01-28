<?php
namespace common\widgets\MultiLang;

use yii\helpers\Url;
use Yii;

?>

<div class="lang">
    <div class="language-choosing">
        <a href="<?= Url::to(array_merge(
            \Yii::$app->request->get(),
            [\Yii::$app->controller->route, 'language' => 'ru']
        )) ?>"
           class="item-lang <?= Yii::$app->language == 'ru' ? 'active' : ''; ?>">
            RU
        </a>
        <a href="<?= Url::to(array_merge(
            \Yii::$app->request->get(),
            [\Yii::$app->controller->route, 'language' => 'en']
        )); ?>"
           class="item-lang <?= Yii::$app->language == 'en' ? 'active' : ''; ?>">
            EN
        </a>
    </div>
</div>