<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property double $delivery
 * @property int $status
 * @property int $time_min
 * @property int $time_max
 */
class Countries extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%countries}}';
    }

    public function rules()
    {
        return [
            [['title_ru', 'title_en', 'delivery'], 'required'],
            [['delivery'], 'number', 'min' => 0],
            [['title_ru', 'title_en'], 'string', 'max' => 255],
            [['time_min', 'time_max'], 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Название Ru',
            'title_en' => 'Название En',
            'status' => 'Статус',
            'delivery' => 'Доставка',
            'time_min' => 'Мин дней',
            'time_max' => 'Макс дней',
        ];
    }

    public function getTitle()
    {
        return $this->getAttr('title');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }
}
