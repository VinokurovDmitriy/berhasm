<?php

namespace common\models;

use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%press}}".
 *
 * @property int $id
 * @property string $title
 * @property string $date
 * @property string $link
 * @property int $sort
 * @property int $status
 */
class Press extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%press}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
				'prependAdded' => true,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'link'], 'required'],
            [['sort', 'status'], 'integer'],
            [['title', 'date', 'link'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Издание',
            'date' => 'Дата',
            'link' => 'Ссылка',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }
}
