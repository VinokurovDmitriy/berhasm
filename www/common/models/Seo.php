<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%seo}}".
 *
 * @property int $id
 * @property string $page
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $description
 * @property string $description_ru
 * @property string $description_en
 * @property string $keywords
 * @property string $keywords_ru
 * @property string $keywords_en
 */
class Seo extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%seo}}';
    }

    public function rules()
    {
        return [
            [['page'], 'required'],
            [['page', 'title_ru', 'title_en', 'description_ru', 'description_en', 'keywords_ru', 'keywords_en'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page' => 'Page',
            'title_ru' => 'Title Ru',
            'title_en' => 'Title En',
            'description_ru' => 'Description Ru',
            'description_en' => 'Description En',
            'keywords_ru' => 'Keywords Ru',
            'keywords_en' => 'Keywords En',
        ];
    }

    public function getTitle()
    {
        return $this->getAttr('title');
    }

    public function getDescription()
    {
        return $this->getAttr('description');
    }

    public function getKeywords()
    {
        return $this->getAttr('keywords');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }
}
