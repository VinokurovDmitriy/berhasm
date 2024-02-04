<?php

namespace shop\entities\Shop;

use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\Shop\Product\Product;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $country
 * @property string $description
 * @property string $file
 * @property Meta $meta
 *
 * @property Product[] $products
 * @mixin ImageUploadBehavior
 */
class Brand extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, Meta $meta, $file, $country, $description): self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->country = $country;
        $brand->description = $description;
        $brand->meta = $meta;
        if ($file) {
            $brand->file = $file;
        }
        return $brand;
    }

    public function edit($name, $slug, Meta $meta, $file, $country, $description): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->meta = $meta;
        $this->country = $country;
        $this->description = $description;
        if ($file) {
            $this->file = $file;
        }
    }

    ###################################################

    public static function tableName()
    {
        return '{{%shop_brands}}';
    }

    public function behaviors()
    {
        return [
            MetaBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
            ],
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@files/brands/[[id]]-[[filename]].[[extension]]',
                'fileUrl' => '@storageUrl/brands/[[id]]-[[filename]].[[extension]]',
                'thumbPath' => '@files/cache/brands/thumbs/[[profile]]_[[id]]-[[filename]].[[extension]]',
                'thumbUrl' => '@storageUrl/cache/brands/thumbs/[[profile]]_[[id]]-[[filename]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 200, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Название',
            'slug' => 'Адрес',
            'image_name' => 'Логотип',
            'country' => 'Страна',
            'description' => 'Описание',
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }
}