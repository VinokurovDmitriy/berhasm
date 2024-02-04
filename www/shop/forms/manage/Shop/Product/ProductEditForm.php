<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Brand;
use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;


/**
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */
class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name_ru;
    public $name_en;
    public $description_ru;
    public $description_en;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->brandId = $product->brand_id;
        $this->code = $product->code;
        $this->name_ru = $product->name_ru;
        $this->name_en = $product->name_en;
        $this->description_ru = $product->description_ru;
        $this->description_en = $product->description_en;
        $this->meta = new MetaForm($product->meta);
        $this->categories = new CategoriesForm($product);
        $this->tags = new TagsForm($product);
        $this->values = array_map(function (Characteristic $characteristic) use ($product) {
            return new ValueForm($characteristic, $product->getValue($characteristic->id));
        }, Characteristic::find()->andWhere(['category_id' => $product->category_id])->orderBy('ord')->all());
        $this->_product = $product;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['code', 'name_ru'], 'required'],
            [['code', 'name_ru', 'name_en'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            [['description_ru', 'description_en'], 'string'],
            [['code'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function internalForms(): array
    {
        return ['meta', 'categories', 'tags', 'values'];
    }
}