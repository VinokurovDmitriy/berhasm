<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%contacts}}".
 *
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $value
 * @property string $value_ru
 * @property string $value_en
 * @property int $status
 * @property int $sort
 */
class Contacts extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%contacts}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    const VARIANTS = [
        'email' => 'Почта',
        'phone' => 'Телефон',
        'other' => 'Другое',
    ];

    public function rules()
    {
        return [
            [['title_ru', 'value_ru', 'type'], 'required'],
            [['status', 'sort'], 'integer'],
            [['title_ru', 'title_en', 'value_ru', 'value_en', 'type'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Название Ru',
            'title_en' => 'Название En',
            'value_ru' => 'Значение Ru',
            'value_en' => 'Значение En',
            'status' => 'Статус',
            'sort' => 'Порядок',
        ];
    }

    public function getTitle()
    {
        return $this->getAttr('title');
    }

    public function getValue()
    {
        return $this->getAttr('value');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }
}
