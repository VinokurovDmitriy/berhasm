<?php

namespace common\models;

use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%socials}}".
 *
 * @property int $id
 * @property string $icon
 * @property string $link
 * @property int $sort
 * @property int $status
 */
class Socials extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%socials}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    const Facebook = 'fb';
    const Instagram = 'ins';
    const Soundcloud = 'sound';

    public $labels_ru = [
        self::Facebook => 'Фейсбук',
        self::Instagram => 'Инстаграм',
        self::Soundcloud => 'Саудклауд',
    ];

    public $labels_en = [
        self::Facebook => 'Facebook',
        self::Instagram => 'Instagram',
        self::Soundcloud => 'Soundcloud',
    ];

    public function rules()
    {
        return [
            [['icon'], 'required'],
            [['sort', 'status'], 'integer'],
            [['icon'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icon' => 'Соцсеть',
            'link' => 'Ссылка',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }
}
