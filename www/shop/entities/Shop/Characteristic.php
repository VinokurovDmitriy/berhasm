<?php

namespace shop\entities\Shop;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $type
 * @property string $required
 * @property string $default
 * @property string $variants_json
 * @property integer $ord
 * @property boolean $main
 *
 * @property Category $category
 * @property array $variants
 */
class Characteristic extends ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';

    public $variants;

    public static function create($name, $type, $required = null, $default, array $variants, $ord, $categoryId, $main): self
    {
        $object = new static();
        $object->name = $name;
        $object->type = $type;
        $object->category_id = $categoryId;
        if ($required) {
            $object->required = $required;
        }
        $object->default = $default;
        $object->variants = $variants;
        $object->ord = $ord;
        $object->main = $main;
        return $object;
    }

    public function edit($name, $type, $required = null, $default, array $variants, $ord, $categoryId, $main): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->category_id = $categoryId;
        if ($required) {
            $this->required = $required;
        }
        $this->default = $default;
        $this->variants = $variants;
        $this->ord = $ord;
        $this->main = $main;
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }

    public static function tableName()
    {
        return '{{%shop_characteristics}}';
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function afterFind(): void
    {
        $this->variants = array_filter(Json::decode($this->getAttribute('variants_json')));
        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('variants_json', Json::encode(array_filter($this->variants)));
        return parent::beforeSave($insert);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Название',
            'type' => 'Тип',
            'category_id' => 'Категория',
            'ord' => 'Порядок',
            'required' => 'Обязательно',
            'main' => 'Основная',
            'default' => 'Значение по умолчанию',
            'variants' => 'Варианты',
        ];
    }
}