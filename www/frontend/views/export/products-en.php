<?php

use shop\entities\Shop\Category;
use yii\helpers\ArrayHelper;

/* @var $category Category */
$category = Category::getDb()->cache(function () {
    return Category::findOne(10);
}, Yii::$app->params['cacheTime']);
$arr = ArrayHelper::getColumn($category->getLeaves()->all(), 'id');
?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>Berhasm Products Feed</title>
        <link>https://berhasm.com/export/products-en</link>
        <description>Berhasm Products Feed for FaceBook</description>
        <?php
        /* @var $products \shop\entities\Shop\Product\Product[] */
        foreach ($products as $product): ?>
            <item>
                <g:id><?= $product->code ?></g:id>
                <g:override>en_XX</g:override>
                <g:title><?= $product->name_en ?></g:title>
                <g:description><?= strip_tags(trim($product->description_en)) ?></g:description>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>
