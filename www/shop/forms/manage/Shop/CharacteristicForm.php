<?php

namespace shop\forms\manage\Shop;

use shop\forms\manage\Shop\Product\CategoriesForm;
use shop\entities\Shop\Characteristic;
use shop\helpers\CharacteristicHelper;
use yii\base\Model;

/**
 * @property array $variants
 * @property CategoriesForm $categories
 */
class CharacteristicForm extends Model
{
    public $name;
    public $type;
    public $category_id;
    public $required;
    public $default;
    public $textVariants;
    public $ord;
    public $main;

    private $_characteristic;

    public function __construct(Characteristic $characteristic = null, $config = [])
    {
        if ($characteristic) {
            $this->name = $characteristic->name;
            $this->type = $characteristic->type;
            $this->category_id = $characteristic->category_id;
            $this->required = $characteristic->required;
            $this->default = $characteristic->default;
            $this->textVariants = implode(PHP_EOL, $characteristic->variants);
            $this->ord = $characteristic->ord;
            $this->main = $characteristic->main;
            $this->_characteristic = $characteristic;
        } else {
            $this->ord = Characteristic::find()->max('ord') +1;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'type', 'ord', 'category_id'], 'required'],
            [['required', 'main'], 'boolean'],
            [['default'], 'string', 'max' => 255],
            [['textVariants'], 'string'],
            [['ord'], 'integer'],
            [['name', 'category_id'], 'unique', 'targetClass' => Characteristic::class,
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
            'main' => 'Основная',
            'required' => 'Обязательно',
            'default' => 'Значение по умолчанию',
            'textVariants' => 'Варианты',
        ];
    }
}