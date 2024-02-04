<?php

namespace shop\entities;

use Yii;

class Meta
{
    public $title_ru;
    public $title_en;
    public $description_ru;
    public $description_en;
    public $keywords_ru;
    public $keywords_en;

    public function __construct($title_ru, $title_en,  $description_ru, $description_en,  $keywords_ru, $keywords_en)
    {
        $this->title_ru = $title_ru;
        $this->description_ru = $description_ru;
        $this->keywords_ru = $keywords_ru;        
        $this->title_en = $title_en;
        $this->description_en = $description_en;
        $this->keywords_en = $keywords_en;
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