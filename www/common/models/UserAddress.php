<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $value
 *
 * @property User $user
 */
class UserAddress extends ActiveRecord
{
    const SCENARIO_MACHINE = 'machine';

    public static function tableName()
    {
        return '{{%user_address}}';
    }

    public function scenarios()
    {
        return [
            $this::SCENARIO_MACHINE => ['user_id'],
            $this::SCENARIO_DEFAULT => ['user_id', 'value'],
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'value'], 'required'],
            [['user_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'value' => 'Адрес',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}