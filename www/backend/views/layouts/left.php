<?php

use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use yii\helpers\Url;
use common\models\GalleryCategories;
use common\models\StockistsCategories;
use yii\helpers\ArrayHelper;

/* @var $user \shop\entities\User\User */
/* @var $categories \common\models\GalleryCategories[] */
/* @var $stockCategories \common\models\StockistsCategories[] */
/* @var $productCategories Category[] */
/* @var $gal_items array */
/* @var $cat_items array */

$user = Yii::$app->user->identity;

$categories = GalleryCategories::find()->andWhere(['status' => 1])->orderBy('sort')->all();
$gal_items[] = [
    'label' => 'SEO',
    'icon' => 'file-code-o',
    'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'gallery'),
    'url' => ['/seo/view', 'page' => 'gallery']
];
$gal_items[] = ['label' => 'Категории галереи', 'icon' => 'bars', 'active' => ($this->context->id == 'gallery-categories'), 'url' => ['/gallery-categories']];
foreach ($categories as $category) {
    $ids = ArrayHelper::getColumn($category->galleryItems, 'id');
    $gal_items[] = ['label' => $category->title_ru,
        'icon' => ($this->context->id == 'gallery-items' && Yii::$app->controller->actionParams['slug'] == $category->slug
            or ($this->context->id == 'gallery-items' && in_array(Yii::$app->controller->actionParams['id'], $ids))) ? 'folder-open' : 'folder',
        'active' => (Yii::$app->controller->actionParams['slug'] == $category->slug
            or ($this->context->id == 'gallery-items' && in_array(Yii::$app->controller->actionParams['id'], $ids))),
        'url' => ['gallery-items/index', 'slug' => $category->slug]];
}

$stockCategories = StockistsCategories::find()->andWhere(['status' => 1])->orderBy('sort')->all();
$stock_items[] = [
    'label' => 'SEO',
    'icon' => 'file-code-o',
    'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'stockists'),
    'url' => ['/seo/view', 'page' => 'stockists']
];
$stock_items[] = ['label' => 'Регионы', 'icon' => 'bars', 'active' => ($this->context->id == 'stockists-categories'), 'url' => ['/stockists-categories']];
foreach ($stockCategories as $stockCategory) {
    $ids = ArrayHelper::getColumn($stockCategory->stockistsItems, 'id');
    $stock_items[] = ['label' => $stockCategory->title_ru,
        'icon' => ($this->context->id == 'stockists-items' && Yii::$app->controller->actionParams['slug'] == $stockCategory->slug
            or ($this->context->id == 'stockists-items' && in_array(Yii::$app->controller->actionParams['id'], $ids))) ? 'folder-open' : 'folder',
        'active' => (Yii::$app->controller->actionParams['slug'] == $stockCategory->slug
            or ($this->context->id == 'stockists-items' && in_array(Yii::$app->controller->actionParams['id'], $ids))),
        'url' => ['stockists-items/index', 'slug' => $stockCategory->slug]];
}

$productCategories = Category::find()->having(['depth' => 1])->all();

foreach ($productCategories as $category) {
    $childItems = [];
    foreach ($category->getChildren()->all() as $child) {
        /* @var $child Category */
        $chIds = ArrayHelper::getColumn(Product::find()->having(['category_id' => $child->id])->all(), 'id');
        $childItems[] = ['label' => $child->name_ru,
            'icon' => ($this->context->id == 'shop/product' && Yii::$app->controller->actionParams['catId'] == $child->id
                or ($this->context->id == 'shop/product' && in_array(Yii::$app->controller->actionParams['id'], $chIds))) ? 'folder-open' : 'folder',
            'active' => ($this->context->id == 'shop/product' && Yii::$app->controller->actionParams['catId'] == $child->id
                or ($this->context->id == 'shop/product' && in_array(Yii::$app->controller->actionParams['id'], $chIds))),
            'url' => ['shop/product/category', 'catId' => $child->id]];
    }
    $ids = [];
    $cat_items[] = ['label' => $category->name_ru,
        'icon' => 'folder',
        'url' => '#',
        'items' => $childItems,
    ];
}

?>

<aside class="main-sidebar">

    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $user->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'files/anonymous.jpg')) ?>"
                     class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->getPublicIdentity() ?></p>
                <a href="<?php echo Url::to(['/sign-in/profile']) ?>">
                    <i class="fa fa-circle text-success"></i>
                    <?php echo Yii::$app->formatter->asDatetime(time()) ?>
                </a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Редактор', 'options' => ['class' => 'header']],
//                    ['label' => 'Файл-менеджер', 'icon' => 'file-image-o', 'url' => ['/file-manager']],
//                    ['label' => 'Модули', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'modules'), 'url' => ['/modules']],
                    ['label' => 'Главная',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'index'
                            or Yii::$app->controller->id == 'slider'
                            or Yii::$app->controller->id == 'index-link'
                            or Yii::$app->controller->id == 'index-links'
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'index'),
                                'url' => ['/seo/view', 'page' => 'index']
                            ],
                            ['label' => 'Переходы', 'icon' => 'image', 'active' => ($this->context->id == 'index-links'), 'url' => ['/index-links']],
                            ['label' => 'Фоновое видео/изображение', 'icon' => 'video-camera', 'active' => ($this->context->id == 'slider' && Yii::$app->controller->actionParams['id'] == 1), 'url' => ['/slider/view', 'id' => 1]],
                            ['label' => 'Кнопка', 'icon' => 'bars', 'active' => ($this->context->id == 'index-link' && Yii::$app->controller->actionParams['id'] == 1), 'url' => ['/index-link/view', 'id' => 1]],
                        ]
                    ],
                    ['label' => 'Магазин',
                        'icon' => (
                            $this->context->id == 'shop/brand'
                            || $this->context->id == 'shop/tag'
                            || $this->context->id == 'shop/category'
                            || $this->context->id == 'shop/characteristic'
                            || $this->context->id == 'shop/mod-characteristic'
                            || $this->context->id == 'shop/product'
                            || $this->context->id == 'shop/modification'
                        ) ? 'folder-open' : 'folder',
                        'url' => ['#'],
                        'items' => [
//                            ['label' => 'Бренды', 'icon' => 'copyright', 'active' => ($this->context->id == 'shop/brand'), 'url' => ['/shop/brand']],
                            ['label' => 'Категории', 'icon' => 'sitemap', 'active' => ($this->context->id == 'shop/category'), 'url' => ['shop/category']],
                            ['label' => 'Теги', 'icon' => 'tag', 'active' => ($this->context->id == 'shop/tag'), 'url' => ['shop/tag']],
//                            ['label' => 'Характеристики', 'icon' => 'cogs', 'active' => ($this->context->id == 'shop/characteristic'), 'url' => ['shop/characteristic']],
                            ['label' => 'Размеры', 'icon' => 'cog', 'active' => ($this->context->id == 'shop/mod-characteristic'), 'url' => ['shop/mod-characteristic']],
                            ['label' => 'Продукты', 'icon' => 'shopping-cart', 'active' => ($this->context->id == 'shop/product' || $this->context->id == 'shop/modification'), 'url' => ['shop/product']],
                            ['label' => 'Продукты по категориям', 'icon' => 'folder', 'url' => '#', 'items' => $cat_items],
                        ]
                    ],
                    ['label' => 'О нас',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'about'
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 1)
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'about'),
                                'url' => ['/seo/view', 'page' => 'about']
                            ],
                            [
                                'label' => 'О нас',
                                'icon' => 'info',
                                'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 1),
                                'url' => ['/modules/view', 'id' => 1]
                            ],
                        ]
                    ],
                    ['label' => 'Галерея',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'gallery'
                            or $this->context->id == 'gallery-items'
                            or $this->context->id == 'gallery-categories'
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => $gal_items,
                    ],
//                    ['label' => 'Звуки',
//                        'icon' => (
//                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'sounds'
//                            or $this->context->id == 'sounds'
//                        ) ? 'folder-open' : 'folder', 'url' => '#',
//                        'items' => [
//                            [
//                                'label' => 'SEO',
//                                'icon' => 'file-code-o',
//                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'sounds'),
//                                'url' => ['/seo/view', 'page' => 'sounds']
//                            ],
//                            ['label' => 'Звуки', 'icon' => 'file-audio-o', 'active' => ($this->context->id == 'sounds'), 'url' => ['/sounds']],
//                        ]
//                    ],
                    ['label' => 'Поставщики',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'stockists' ||
                            $this->context->id == 'stockists-categories' ||
                            $this->context->id == 'stockists-items')
                            ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => $stock_items,
                    ],
                    ['label' => 'Пресса',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'press'
                            or $this->context->id == 'sounds'
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'press'),
                                'url' => ['/seo/view', 'page' => 'press']
                            ],
                            ['label' => 'Пресса', 'icon' => 'newspaper-o', 'active' => ($this->context->id == 'press'), 'url' => ['/press']],
                        ]
                    ],
                    ['label' => 'Контакты',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'contacts'
                            or $this->context->id == 'contacts'
                            or $this->context->id == 'socials'
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'contacts'),
                                'url' => ['/seo/view', 'page' => 'contacts']
                            ],
                            ['label' => 'Контакты', 'icon' => 'address-book', 'active' => ($this->context->id == 'contacts'), 'url' => ['/contacts']],
                            ['label' => 'Соцсети', 'icon' => 'facebook', 'active' => ($this->context->id == 'socials'), 'url' => ['/socials']],
                        ]
                    ],
                    ['label' => 'Забота о покупателе',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'press'
                            or $this->context->id == 'sounds'
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 5)
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'customer-care'),
                                'url' => ['/seo/view', 'page' => 'customer-care']
                            ],
                            ['label' => 'Забота о покупателе', 'icon' => 'info', 'active' => ($this->context->id == 'customer-care'), 'url' => ['/customer-care']],
                            [
                                'label' => 'Success',
                                'icon' => 'file-text-o',
                                'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 5),
                                'url' => ['/modules/view', 'id' => 5]
                            ],
                        ]
                    ],
                    ['label' => 'Возврат',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'return'
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 3)
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'return'),
                                'url' => ['/seo/view', 'page' => 'return']
                            ],
                            [
                                'label' => 'Возврат',
                                'icon' => 'info',
                                'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 3),
                                'url' => ['/modules/view', 'id' => 3]
                            ],
                        ]
                    ],
                    ['label' => 'Cookie policy',
                        'icon' => (
                            Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'cookie'
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 4)
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'cookie'),
                                'url' => ['/seo/view', 'page' => 'cookie']
                            ],
                            [
                                'label' => 'Cookie',
                                'icon' => 'info',
                                'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 4),
                                'url' => ['/modules/view', 'id' => 4]
                            ],
                        ]
                    ],
                    ['label' => 'Справочные материалы',
                        'icon' => (
                            $this->context->id == 'size-guide'
                            or $this->context->id == 'countries'
                            or $this->context->id == 'delivery'
                            or $this->context->id == 'promocodes'
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            ['label' => 'Таблица размеров', 'icon' => 'info', 'active' => ($this->context->id == 'size-guide'), 'url' => ['/size-guide']],
                            ['label' => 'Страны', 'icon' => 'globe', 'active' => ($this->context->id == 'countries'), 'url' => ['/countries']],
                            ['label' => 'Стоимость доставки по России', 'icon' => 'database', 'active' => ($this->context->id == 'delivery'), 'url' => ['/delivery']],
                            ['label' => 'Промокоды', 'icon' => 'ticket', 'active' => ($this->context->id == 'promocodes'), 'url' => ['/promocodes']],
                        ]
                    ],
                    ['label' => 'Заказы', 'icon' => 'exclamation-triangle', 'active' => ($this->context->id == 'orders'), 'url' => ['/orders']],
                    ['label' => 'Очистить кэш', 'icon' => 'exclamation-circle', 'url' => ['site/clear-cache']],

                ]
            ]
        ) ?>
    </section>
</aside>
