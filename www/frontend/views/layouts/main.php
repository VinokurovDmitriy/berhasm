<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\Contacts;
use common\models\Socials;
use shop\entities\Shop\Category;
use frontend\components\MenuHelper;
use frontend\components\MobileMenuHelper;

/** @var $this \yii\web\View
 * @var $content string

 */
$helper = new MenuHelper();
$mobileHelper = new MobileMenuHelper();
$socials = Socials::getDb()->cache(function () {
    return Socials::find()->having(['status' => 1])->orderBy('sort')->all();
}, Yii::$app->params['cacheTime']);
$email = Contacts::getDb()->cache(function () {
    return Contacts::find()->having(['status' => 1,'type'=>'email'])->orderBy('sort')->one();
}, Yii::$app->params['cacheTime']);
$phone = Contacts::getDb()->cache(function () {
    return Contacts::find()->having(['status' => 1,'type'=>'phone'])->orderBy('sort')->one();
}, Yii::$app->params['cacheTime']);

$categories = Category::getDb()->cache(function () {
    return Category::find()->andWhere(['depth' => 1])->orderBy('lft')->all();
}, Yii::$app->params['cacheTime']);

$items = $helper->setMenu($categories);

$menuItems = [
    [
        'label' => Yii::t('app', 'About'),
        'url' => ['site/about']
    ],
    [
        'label' => Yii::t('app', 'Pretty Images'),
        'url' => ['site/galleries']
    ],
//    [
//        'label' => Yii::t('app', 'Sounds'),
//        'url' => ['site/sounds']
//    ],
    [
        'label' => Yii::t('app', 'Stockists'),
        'url' => ['site/stockists']
    ],
    [
        'label' => Yii::t('app', 'Customer care'),
        'url' => ['site/customer-care'],
        'options' => ['class'=>'dropdownList'],
        'items' =>[
            [
                'label' => $phone->title.': <span>'.$phone->value.'</span>',
                'url' => 'tel:'. str_replace(['(',')',' ','-'],'',$phone->value)
            ],
            [
                'label' => $email->title.': <span>'.$email->value.'</span>',
                'url' => 'mailto:'. $email->value
            ],
            [
                'label' => Yii::t('app', 'Delivery'),
                'url' => ['site/customer-care','#'=>'2']
            ],
            [
                'label' => Yii::t('app', 'Returns & exchanges'),
                'url' => ['site/return']
            ],
            [
                'label' => Yii::t('app', 'Payment'),
                'url' => ['site/customer-care','#'=>'4']
            ]

        ]
    ],
];

$mobileItems = $mobileHelper->setMenu($categories);

AppAsset::register($this);

$arr100 = ['index'];
$is100 = in_array(Yii::$app->controller->action->id, $arr100) && (in_array(Yii::$app->controller->id, ['site','catalog']));


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('_tracking-head');?>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="preload" href="/css/style.css" as="style">
    <link rel='subresource' href='/css/style.css'>
    <?php $this->head() ?>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#333333">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="<?= Yii::$app->devicedetect->isMobile() ? "main-mobile" : ""; ?>">
<?php $this->beginBody() ?>

<div id="main" class="<?= ($is100) ? 'is100' : ''; ?>">

    <?= Alert::widget() ?>
    <div id="content" class="wrapper contentWrapper">
        <?= $this->render('mainNav', [
            'menuCatItems' => $items,
            'menuItems' => $menuItems,
            'mobileItems' => $mobileItems,
        ]); ?>
        <?= $content ?>
    </div>

    <?= $this->render('footer', [
        'socials' => $socials,
        'email' => $email,
        'phone' => $phone,
    ]); ?>

</div>

<?= $this->render('_tracking-bottom');?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
