<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "bhm_stockists_items".
 *
 * @property int $id
 * @property int $category_id
 * @property string $value_ru
 * @property string $value_en
 * @property string $value
 * @property int $sort
 * @property int $status
 *
 * @property StockistsCategories $category
 */
class StockistsItems extends ActiveRecord
{
    public static function tableName()
    {
        return 'bhm_stockists_items';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return StockistsItems::find()->where(['category_id' => $this->category_id]);
                }
            ],
        ];
    }


    public function rules()
    {
        return [
            [['category_id', 'value_ru'], 'required'],
            [['category_id', 'sort', 'status'], 'integer'],
            [['value_ru', 'value_en'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockistsCategories::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'value_ru' => 'Название Ru',
            'value_en' => 'Название En',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(StockistsCategories::class, ['id' => 'category_id']);
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
