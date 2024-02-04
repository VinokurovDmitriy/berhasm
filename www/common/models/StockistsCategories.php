<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "bhm_stockists_categories".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $title_ru
 * @property string $title_en
 * @property int $sort
 * @property int $status
 *
 * @property StockistsItems[] $stockistsItems
 * @property StockistsItems[] $activeStockistsItems
 */
class StockistsCategories extends ActiveRecord
{
    public static function tableName()
    {
        return 'bhm_stockists_categories';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title_ru',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title_ru'], 'required'],
            [['sort', 'status'], 'integer'],
            [['title_ru', 'title_en', 'slug'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Название Ru',
            'title_en' => 'Название En',
            'sort' => 'Порядок',
            'status' => 'Статус',
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

    public function getStockistsItems()
    {
        return $this->hasMany(StockistsItems::class, ['category_id' => 'id'])->orderBy('sort');
    }

    public function getActiveStockistsItems()
    {
        return $this->hasMany(StockistsItems::class, ['category_id' => 'id'])->having(['status' => 1])->orderBy('sort');
    }
}
