<?php

namespace shop\entities\User;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_address".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $value
 *
 * @property User $user
 */
class UserAddresses extends ActiveRecord
{
    const SCENARIO_MACHINE = 'machine';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_addresses}}';
    }

    public function scenarios()
    {
        return [
            $this::SCENARIO_MACHINE => ['user_id'],
            $this::SCENARIO_DEFAULT => ['user_id', 'value'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'value' => 'Адрес',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}