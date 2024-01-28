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
        <link>
        https://berhasm.com/export/products</link>
        <description>Berhasm Products Feed for FaceBook</description>
        <?php
        /* @var $products \shop\entities\Shop\Product\Product[] */
        foreach ($products as $product): ?>
            <item>
                <g:id><?= $product->code ?></g:id>
                <g:title><?= str_replace('"','',$product->name) ?></g:title>
                <g:description><?= strip_tags(trim($product->description)) ?></g:description>
                <g:availability><?= $product->quantity > 0 ? 'in stock' : 'out of stock' ?></g:availability>
                <g:price><?= $product->price_new ?> RUB</g:price>
                <g:link>https://berhasm.com/product/<?= $product->slug ?></g:link>
                <g:condition>new</g:condition>
                <g:image_link>
                    https://berhasm.com<?= $product->mainPhoto->getThumbFileUrl('file', 'feed') ?></g:image_link>
                <g:inventory><?= $product->quantity ?></g:inventory>
                <g:brand>BERHASM</g:brand>

                <?php if(in_array($product->category->id,$arr)) {?>
                    <g:fb_product_category>Одежда и аксессуары &gt; Одежда &gt; Мужская одежда</g:fb_product_category>
                <?php } else {?>
                    <g:fb_product_category>Одежда и аксессуары &gt; Одежда &gt; Женская одежда</g:fb_product_category>
                <?php } ?>
                <g:google_product_category>1604</g:google_product_category>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>
