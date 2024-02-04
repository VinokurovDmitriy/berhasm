<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Brand;
use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;

/**
 * @property PriceForm $price
 * @property MetaForm $meta
 * @property QuantityForm $quantity
 * @property CategoriesForm $categories
 * @property PhotosForm $photos
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name_ru;
    public $name_en;
    public $description_ru;
    public $description_en;

    public function __construct(array $config = [])
    {
        $this->price = new PriceForm();
        $this->quantity = new QuantityForm();
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->photos = new PhotosForm();
        $this->tags = new TagsForm();
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('ord')->all());
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['code', 'description_ru', 'name_ru'], 'required'],
            [['code', 'name_ru', 'name_en'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            [['description_ru', 'description_en'], 'string'],
            [['code'], 'unique', 'targetClass' => Product::class],
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function internalForms(): array
    {
        return ['price', 'meta', 'photos', 'categories', 'tags', 'values', 'quantity'];
    }
}