<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 * @property integer $ord
 *
 * @mixin ImageUploadBehavior
 */
class Photo extends ActiveRecord
{
    public static function create(UploadedFile $file):self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    public function setSort($ord):void
    {
        $this->ord = $ord;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public static function tableName()
    {
        return '{{%shop_photos}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@files/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'fileUrl' => '@storageUrl/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'thumbPath' => '@files/cache/products/[[attribute_product_id]]/thumbs/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@storageUrl/cache/products/[[attribute_product_id]]/thumbs/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 60, 'height' => 100],
                    'thumb' => ['width' => 300, 'height' => 500],
                    'catalog_full' => ['width' => 1920, 'height' => null],
                    'feed' => ['width' => 1024, 'height' => null],
                    'catalog_product_main' => ['width' => 600, 'height' => 1000],
                    'catalog_product_grid' => ['width' => 300, 'height' => 500],
                ],
            ]
        ];
    }
}