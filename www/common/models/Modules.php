<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * This is the model class for table "{{%modules}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $html
 * @property string $html_ru
 * @property string $html_en
 *
 * @property ModulesAttachments[] $modulesAttachments
 */
class Modules extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%modules}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => UploadBehavior::class,
                'attribute' => 'attachments',
                'multiple' => true,
                'uploadRelation' => 'modulesAttachments',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
                'orderAttribute' => 'order',
                'typeAttribute' => 'type',
                'sizeAttribute' => 'size',
                'nameAttribute' => 'name',
            ],
        ];
    }

    public $attachments;

    public function delete()
    {
        return parent::delete();
    }
    public function update($runValidation = true, $attributeNames = null)
    {
        return parent::update($runValidation, $attributeNames);
    }

    public function rules()
    {
        return [
            [['html_ru', 'html_en'], 'string'],
            [['title_ru', 'title_en'], 'string', 'max' => 255],
            [['attachments'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Заголовок Ru',
            'title_en' => 'Заголовок En',
            'html_ru' => 'Текст Ru',
            'html_en' => 'Текст En',
            'attachments' => 'Изображения',
        ];
    }

    public function getTitle()
    {
        return $this->getAttr('title');
    }

    public function getHtml()
    {
        return $this->getAttr('html');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }

    public function getModulesAttachments()
    {
        return $this->hasMany(ModulesAttachments::class, ['module_id' => 'id']);
    }


}
