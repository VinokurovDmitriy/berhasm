<?php

namespace frontend\components;

use Yii;
use shop\entities\Shop\Category;
use shop\entities\Shop\Tag;
use yii\helpers\ArrayHelper;

class MobileMenuHelper
{
//    public function getMenuItems($categories)
//    {
//        return Yii::$app->cache->getOrSet('menuItems', function () use ($categories) {
//            return $this->setMenu($categories);
//        }, Yii::$app->params['cacheTime']);
//    }

    public function setMenu($categories)
    {
        /* @var  $category Category */
        $result = [];
        foreach ($categories as $key => $category) {
            $items = $this->setRootChildren($category::getDb()->cache(function () use ($category) {
                return $category->getChildren()->all();
            }, Yii::$app->params['cacheTime']), $category);
            $result[] = [
                'label' => $category->name,
                'url' => ['catalog/index', 'slug' => $category->slug],
                'items' => $items,
                'options'=>['class'=>'dropdownList']
            ];
        }
        return $result;
    }


    /**
     * @param $children  Category[]
     * @param $category  Category
     * @return array
     */

    public function setRootChildren($children, $category)
    {

        $result = [];

        foreach ($this->getTags() as $id => $tag) {
            $result[] = [
                'label' => Yii::t('app', $tag),
                'url' => ['catalog/index', 'slug' => $category->slug, 'tag' => $id],
            ];
        }
        $result[] = [
            'label' => Yii::t('app', 'Sale'),
            'url' => ['catalog/index', 'slug' => $category->slug, 'sale' => 'sale'],
        ];

        foreach ($children as $child) {
            if ($subChildren = $child::getDb()->cache(function () use ($child) {
                return $child->getChildren()->all();
            }, Yii::$app->params['cacheTime'])) {
                $items = $this->setChildren($subChildren);
                $result[] = [
                    'label' => $child->name,
                    'url' => ['catalog/index', 'slug' => $child->slug],
                    'items' => $items,
                ];
            } else {
                $result[] = [
                    'label' => $child->name,
                    'url' => ['catalog/index', 'slug' => $child->slug],
                    'active' => (
                        Yii::$app->controller->id == 'catalog' &&
                        Yii::$app->controller->actionParams['slug'] == $child->slug
                    )
                ];
            }
        }

        return $result;
    }

    /**
     * @param $children  Category[]
     * @return array
     */
    public function setChildren($children)
    {
        $result = [];
        foreach ($children as $child) {
            if ($subChildren = $child->getChildren()->all()) {
                $items = $this->setChildren($subChildren);
                $result[] = [
                    'label' => $child->name,
                    'url' => ['catalog/index', 'slug' => $child->slug],
                    'items' => $items,
                ];
            } else {
                $result[] = [
                    'label' => $child->name,
                    'url' => ['catalog/index', 'slug' => $child->slug],
                    'active' => (
                        Yii::$app->controller->id == 'catalog' &&
                        Yii::$app->controller->actionParams['slug'] == $child->slug
                    )
                ];
            }
        }
        return $result;
    }

    public function getTags()
    {
        $activeTags = Tag::getDb()->cache(function () {
            return Tag::find()->alias('t')->joinWith('products p')->andWhere(['p.status' => 1])->groupBy('t.id')->all();
        }, Yii::$app->params['cacheTime']);
        $tags = ArrayHelper::map($activeTags, 'id', 'name');
        asort($tags);
        return $tags;
    }
}
