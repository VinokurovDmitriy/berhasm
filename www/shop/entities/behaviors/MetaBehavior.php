<?php

namespace shop\entities\behaviors;

use shop\entities\Meta;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class MetaBehavior extends Behavior
{
    public $attribute = 'meta';
    public $jsonAttribute = 'meta_json';

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    public function onAfterFind(Event $event): void
    {
        $model = $event->sender;
        $meta = Json::decode($model->getAttribute($this->jsonAttribute));
        $model->{$this->attribute} = new Meta(
            ArrayHelper::getValue($meta, 'title_ru'),
            ArrayHelper::getValue($meta, 'title_en'),
            ArrayHelper::getValue($meta, 'description_ru'),
            ArrayHelper::getValue($meta, 'description_en'),
            ArrayHelper::getValue($meta, 'keywords_ru'),
            ArrayHelper::getValue($meta, 'keywords_en')
        );
    }

    public function onBeforeSave(Event $event): void
    {
        $model = $event->sender;
        $model->setAttribute($this->jsonAttribute, Json::encode([
            'title_ru' => $model->{$this->attribute}->title_ru,
            'title_en' => $model->{$this->attribute}->title_en,
            'description_ru' => $model->{$this->attribute}->description_ru,
            'description_en' => $model->{$this->attribute}->description_en,
            'keywords_ru' => $model->{$this->attribute}->keywords_ru,
            'keywords_en' => $model->{$this->attribute}->keywords_en,
        ]));
    }
}