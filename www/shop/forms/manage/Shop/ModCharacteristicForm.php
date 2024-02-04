<?php

namespace shop\forms\manage\Shop;

use shop\forms\manage\Shop\Product\CategoriesForm;
use shop\entities\Shop\ModCharacteristic;
use shop\helpers\CharacteristicHelper;
use yii\base\Model;

/**
 * @property array $variants
 * @property CategoriesForm $categories
 */

class ModCharacteristicForm extends Model
{
    public $name;
    public $type;
    public $category_id;
    public $required;
    public $default;
    public $textVariants;
    public $ord;

    private $_characteristic;

    public function __construct(ModCharacteristic $characteristic = null, $config = [])
    {
        if ($characteristic) {
            $this->name = $characteristic->name;
            $this->type = $characteristic->type;
            $this->category_id = $characteristic->category_id;
            $this->required = $characteristic->required;
            $this->default = $characteristic->default;
            $this->textVariants = implode(PHP_EOL, $characteristic->variants);
            $this->ord = $characteristic->ord;
            $this->_characteristic = $characteristic;
        } else {
            $this->ord = ModCharacteristic::find()->max('ord') +1;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'type', 'ord', 'category_id'], 'required'],
            [['required'], 'boolean'],
            [['default'], 'string', 'max' => 255],
            [['textVariants'], 'string'],
            [['ord'], 'integer'],
            [['name', 'category_id'], 'unique', 'targetClass' => ModCharacteristic::class,
                'targetAttribute' => ['name', 'category_id'],
                'filter' => $this->_characteristic ? ['<>', 'id', $this->_characteristic->id] : null]
        ];
    }

    public function getVariants(): array
    {
        return preg_split('#[\r\n]+#i', $this->textVariants);
    }

    public function typesList(): array
    {
        return CharacteristicHelper::typeList();
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
            'default' => 'Значение по умолчанию',
            'textVariants' => 'Варианты',
        ];
    }

}