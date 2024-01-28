<?php

use yii\helpers\Url;

/* @var $socials \common\models\Socials[] */
/* @var $phone \common\models\Contacts */
/* @var $email \common\models\Contacts */
$labels = 'labels_' . Yii::$app->language;
?>
<?php if(Yii::$app->devicedetect->isMobile()) {?>
    <div id="footerMobile">
        <ul>
            <li><a href="tel:<?= str_replace(['(',')',' ','-'],'',$phone->value); ?>"><?= $phone->title ?>: <span><?= $phone->value;?></span></a></li>
            <li><a href="mailto:<?= $email->value; ?>">Email: <?= $email->value;?></a></li>
            <li><p>&nbsp;</p></li>
            <li class="dropdownList footer">
                <h3><?= Yii::t('app', 'Help'); ?></h3>
                <ul class="footerSubNav">
                    <li><a href="<?= Url::to(['site/customer-care','#'=>'1']); ?>"><?= Yii::t('app', 'Customer care'); ?></a></li>
                    <li><a href="<?= Url::to(['site/customer-care','#'=>'2']); ?>"><?= Yii::t('app', 'Delivery'); ?></a></li>
                    <li><a href="<?= Url::to(['site/return']); ?>"><?= Yii::t('app', 'Returns & exchanges'); ?></a></li>
                    <li><a href="<?= Url::to(['site/customer-care','#'=>'4']); ?>"><?= Yii::t('app', 'Payment'); ?></a></li>
                    <li><a href="<?= Url::to(['site/customer-care','#'=>'5']); ?>"><?= Yii::t('app', 'Legal'); ?></a></li>
                </ul>
            </li>
            <li class="dropdownList footer">
                <h3><?= Yii::t('app', 'Company'); ?></h3>
                <ul class="footerSubNav">
                    <li><a href="<?= Url::to(['site/about']); ?>"><?= Yii::t('app', 'About'); ?></a></li>
                    <li><a href="<?= Url::to(['site/press']); ?>"><?= Yii::t('app', 'Press'); ?></a></li>
                    <li><a href="<?= Url::to(['site/contacts']); ?>"><?= Yii::t('app', 'Contacts'); ?></a></li>
                    <li><a href="<?= Url::to(['site/cookie-policy']); ?>"><?= Yii::t('app', 'Terms & conditions'); ?></a></li>
                    <li><a href="<?= Url::to(['site/cookie-policy']); ?>"><?= Yii::t('app', 'Privacy policy'); ?></a></li>
                </ul>
            </li>
            <?php foreach ($socials as $social): ; ?>
                <li><a target="_blank" href="<?= $social->link; ?>"><?= $social->$labels[$social->icon]; ?></a></li>
            <?php endforeach; ?>
            <li><h4>©<?= date('Y');?> Berhasm Global</h4></li>
        </ul>
    </div>
<?php } else { ?>

<div id="footer">
    <div class="wrapper">
        <div class="footerBlock footerContact">
            <h3><a href="<?= 'tel:' . str_replace(['(',')',' ','-'],'',$phone->value); ?>"><?= $phone->title ?></a></h3>
            <h3><a href="<?= 'mailto:' . $email->value; ?>">Email</a></h3>
        </div>
        <div class="footerBlock">
<!--            <h3>Subscribe</h3>-->
<!--            <p>Sign up to receive news about Berhasm collections, events and sales</p>-->
        </div>
        <div class="footerBlock">
            <h3><?= Yii::t('app', 'Help'); ?></h3>
            <ul>
                <li><a href="<?= Url::to(['site/customer-care','#'=>'1']); ?>"><?= Yii::t('app', 'Customer care'); ?></a></li>
                <li><a href="<?= Url::to(['site/customer-care','#'=>'2']); ?>"><?= Yii::t('app', 'Delivery'); ?></a></li>
                <li><a href="<?= Url::to(['site/return']); ?>"><?= Yii::t('app', 'Returns & exchanges'); ?></a></li>
                <li><a href="<?= Url::to(['site/customer-care','#'=>'4']); ?>"><?= Yii::t('app', 'Payment'); ?></a></li>
                <li><a href="<?= Url::to(['site/customer-care','#'=>'5']); ?>"><?= Yii::t('app', 'Legal'); ?></a></li>
            </ul>
        </div>
        <div class="footerBlock">
            <h3><?= Yii::t('app', 'Company'); ?></h3>
            <ul class="footerCompanyList">
                <li><a href="<?= Url::to(['site/about']); ?>"><?= Yii::t('app', 'About'); ?></a></li>
                <li><a href="<?= Url::to(['site/press']); ?>"><?= Yii::t('app', 'Press'); ?></a></li>
                <li><a href="<?= Url::to(['site/contacts']); ?>"><?= Yii::t('app', 'Contacts'); ?></a></li>
                <li><a href="<?= Url::to(['site/cookie-policy']); ?>"><?= Yii::t('app', 'Terms & conditions'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="wrapper">
        <div class="footerBlock">
            <h3>©<?= date('Y');?> Berhasm Global</h3>
        </div>
        <div class="footerBlock">

        </div>
        <div class="footerBlock">

        </div>
        <div class="footerBlock">
            <h3>Social</h3>
            <ul class="footerSocialsList">
                <?php foreach ($socials as $social): ; ?>
                    <li><a target="_blank" href="<?= $social->link; ?>"><?= $social->$labels[$social->icon]; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php };?>

