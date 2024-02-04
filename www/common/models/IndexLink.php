<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%index_link}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $link
 */
class IndexLink extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%index_link}}';
    }

    public function rules()
    {
        return [
            [['title_ru', 'link'], 'required'],
            [['title_ru', 'title_en', 'link'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Заголовок Ru',
            'title_en' => 'Заголовок En',
            'link' => 'Ссылка',
        ];
    }

    #################### MULTI LANGUAGE ####################

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
