<?php

namespace shop\forms\manage;

use shop\entities\Meta;
use yii\base\Model;

class MetaForm extends Model
{
    public $title_ru;
    public $title_en;
    public $description_ru;
    public $description_en;
    public $keywords_ru;
    public $keywords_en;

    public function __construct(Meta $meta = null, $config = [])
    {
        if ($meta) {
            $this->title_ru = $meta->title_ru;
            $this->title_en = $meta->title_en;
            $this->description_ru = $meta->description_ru;
            $this->description_en = $meta->description_en;
            $this->keywords_ru = $meta->keywords_ru;
            $this->keywords_en = $meta->keywords_en;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title_ru', 'title_en'], 'string', 'max' => 255],
            [['description_ru', 'description_en', 'keywords_ru', 'keywords_en'], 'string'],
        ];
    }
}