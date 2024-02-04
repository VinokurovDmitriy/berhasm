<?php

namespace shop\helpers;

use shop\entities\Shop\Category;
use yii\helpers\ArrayHelper;

class CategoriesListHelper
{
    public static function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name_ru'];
        });
    }
}