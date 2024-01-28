<?php

use yii\helpers\Url;
use yii\widgets\Menu;
use common\widgets\MultiLang\MultiLang;
use frontend\widgets\Shop\CartWidget;
use yii\helpers\ArrayHelper;

/* @var $menuItems array */
/* @var $menuCatItems array */
/* @var $mobileItems array */
$current = Yii::$app->request->cookies->getValue('currency', Yii::$app->language);
$navMobileItems = ArrayHelper::merge($mobileItems, $menuItems);

?>
<div id="topNav">
    <div class="wrapper">
        <a id="logo" href="<?= Url::to(['site/index']); ?>">
            <!--        <span class="icon-logo"></span>-->
            berhasm
        </a>
        <div id="mainNav">
            <?php if (Yii::$app->devicedetect->isMobile()) { ?>
                <?= Menu::widget([
                    'items' => $navMobileItems,
                    'encodeLabels' => false,
                    'options' => ['id' => 'mobileNavMenu', 'class' => 'navMenu mobileMenu'],
                    'activateParents' => true,
                    'activeCssClass' => 'active',
                    'linkTemplate' => '<a href="{url}" data-text="{label}">{label}</a>',
                    'submenuTemplate' => "\n<ul class='mobileNavSub' role='menu'>\n{items}\n</ul>\n",
                ]); ?>
            <?php } else { ?>
                <?= Menu::widget([
                    'items' => $menuCatItems,
//                'itemOptions' => ['tag'=>'div','class'=>'blockItem'],
                    'encodeLabels' => false,
                    'options' => ['id' => 'catNavMenu', 'class' => 'navMenu leftMenu'],
                    'activateParents' => true,
                    'activeCssClass' => 'active',
                    'linkTemplate' => '<a href="{url}" data-text="{label}">{label}</a>',
                    'submenuTemplate' => "\n<div class='mainNavSub' role='menu'>\n{items}\n</div>\n",
                ]); ?>

                <?= Menu::widget([
                    'items' => $menuItems,
                    'encodeLabels' => false,
                    'options' => ['id' => 'mainNavMenu', 'class' => 'navMenu rightMenu'],
                    'activateParents' => true,
                    'activeCssClass' => 'active',
                    'linkTemplate' => '<a href="{url}" data-text="{label}">{label}</a>',
                    'submenuTemplate' => "\n<ul class='mainSecondNavSub' role='menu'>\n{items}\n</ul>\n",
                ]); ?>
            <?php }; ?>


            <!--        <ul id="secondaryNavMenu" class="navMenu">-->
            <!--            <li class="searchLi"><a id="searchButton" class="ajaxLink" href="-->
            <? //= Url::to(['site/search']); ?><!--"><span-->
            <!--                            class="icon-search"></span></a></li>-->
            <!--            <li>-->
            <!--                <a href="--><? //= Url::to(['cart/index']); ?><!--">-->
            <? //= Yii::t('app', 'My cart'); ?><!--</a>-->
            <!--            </li>-->
            <!--        </ul>-->
            <!--        <div class="currency-holder">-->
            <!--            <div class="currency-drop">-->
            <!--                <div class="current">-->
            <!--                    --><? //= Yii::$app->params['currencies'][$current]; ?>
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->

        </div>

        <div class="right-controls">
            <div class="currency">
                <ul>
                    <?php foreach (Yii::$app->params['currencies'] as $key => $currency): ; ?>
                        <?php if ($key == 'ru') continue; ?>
                        <li class="<?= $key == $current ? 'active' : '' ?>">
                            <a href="<?= Url::to(['site/currency', 'currency' => $key]); ?>"><?= $currency; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
<!--            <div class="top-nav-block">-->
<!--                --><?//= MultiLang::widget(); ?>
<!--            </div>-->
            <a href="<?= Url::to(['cart/index']); ?>" class="mobile-card-btn">
                <span class="icon-cart-round"></span>
                <?php if (!Yii::$app->devicedetect->isMobile()) { ?>
                    <?= Yii::t('app', 'Bag'); ?>
                <?php }; ?>
            </a>
        </div>
        <?php if (Yii::$app->devicedetect->isMobile()) { ?>
            <div id="mobNavBtn">
                <div class="burger">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>
        <?php }; ?>
    </div>
</div>
<?= CartWidget::widget() ?>
