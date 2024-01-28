<?php

namespace shop\entities\Shop;

use Yii;
use paulzi\nestedsets\NestedSetsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\Shop\queries\CategoryQuery;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property string $name_ru
 * @property string $name_en
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property Meta $meta
 *
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 *
 * @property Category $parent
 * @property Category[] $parents
 * @property Category[] $children
 * @property Category $prev
 * @property Category $next
 * @mixin NestedSetsBehavior
 *
 * @property ModCharacteristic[] $modChar
 */
class Category extends ActiveRecord
{
    public $meta;

    public static function create($name_ru, $name_en, $slug, $title, $description, Meta $meta): self
    {
        $category = new static();
        $category->name_ru = $name_ru;
        $category->name_en = $name_en;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;
        $category->meta = $meta;
        return $category;
    }

    public function edit($name_ru, $name_en, $slug, $title, $description, Meta $meta): void
    {
        $this->name_ru = $name_ru;
        $this->name_en = $name_en;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->getTitle() ?: $this->getHeadingTile();
    }

    public function getHeadingTile(): string
    {
        return $this->title ?: $this->name;
    }

//    public function getTitle()
//    {
//        return $this->getAttr('title');
//    }

    public function getName()
    {
        return $this->getAttr('name');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }

    public static function tableName()
    {
        return '{{%shop_categories}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            NestedSetsBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name_en',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
                'immutable' => true,
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'parentId' => 'Родительская категория',
            'name_ru' => 'Название Ru',
            'name_en' => 'Название En',
            'slug' => 'Адрес',
            'title' => 'Заголовок',
            'description' => 'Описание',
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): CategoryQuery
{
    return new CategoryQuery(static::class);
}

    public function getModChar()
    {
        return $this->hasMany(ModCharacteristic::class, ['category_id' => 'id']);
    }
}