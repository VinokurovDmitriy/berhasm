<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%delivery}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $price
 * @property int $sort
 * @property int $status
 * @property int $time_min
 * @property int $time_max
 */
class Delivery extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%delivery}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title_ru'], 'required'],
            [['sort', 'status'], 'integer'],
            [['title_ru', 'title_en'], 'string', 'max' => 255],
            [['price'], 'integer'],
            [['time_min', 'time_max'], 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Заголовок Ru',
            'title_en' => 'Заголовок En',
            'price' => 'Стоимость',
            'status' => 'Статус',
            'sort' => 'Порядок',
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
