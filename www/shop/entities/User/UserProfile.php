<?php

namespace shop\entities\User;

use trntv\filekit\behaviors\UploadBehavior;
use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property string $username
 * @property string $avatar
 * @property string $avatar_path
 * @property string $avatar_base_url
 * @property string $phone
 *
 * @property string $status
 * @property string $role
 * @property User $user
 * @property string $email
 */
class UserProfile extends ActiveRecord
{
    /**
     * @var
     */
    public $picture;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'picture' => [
                'class' => UploadBehavior::class,
                'attribute' => 'picture',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['username', 'avatar_path', 'avatar_base_url'], 'string', 'max' => 255],
            ['picture', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'ФИО',
            'picture' => 'Аватар',
            'email' => 'E-mail',
            'role' => 'Роль',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getEmail()
    {
        return $this->user->email;
    }

    public function getRole()
    {
        return $this->user->role;
    }

    public function getStatus()
    {
        return $this->user->status;
    }

    /**
     * @param null $default
     * @return bool|null|string
     */
    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url . '/' . $this->avatar_path)
            : $default;
    }
}