<?php

namespace shop\forms\manage\Shop;

use shop\entities\Shop\Brand;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\validators\SlugValidator;
use yii\web\UploadedFile;

/**
 * @property MetaForm $meta
 */
class BrandForm extends CompositeForm
{
    public $name;
    public $slug;
    public $file;
    public $country;
    public $description;

    public $_brand;

    public function __construct(Brand $brand = null, $config = [])
    {
        if ($brand) {
            $this->name = $brand->name;
            $this->slug = $brand->slug;
            $this->country = $brand->country;
            $this->description = $brand->description;
            $this->meta = new MetaForm($brand->meta);
            $this->_brand = $brand;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'country'], 'required'],
            [['name', 'slug', 'country'], 'string', 'max' => 50],
            [['description'], 'string'],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Brand::class, 'filter' => $this->_brand ? ['<>', 'id', $this->_brand->id] : null],
            [['file'], 'image'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->file = UploadedFile::getInstance($this, 'file');
            return true;
        }
        return false;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Название',
            'slug' => 'Адрес',
            'country' => 'Страна',
            'description' => 'Описание',
        ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}