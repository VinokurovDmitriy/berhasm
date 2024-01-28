<?php
namespace backend\forms;

use shop\entities\User\User;
use yii\base\Model;
use yii;

/**
 * Account form
 */
class AccountForm extends Model
{
    public $email;
    public $password;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => '\shop\entities\User\User',
                'message' => 'Пользователь с таким E-mail уже зарегистрирован.',
                'filter' => function ($query) {
                    /* @var $query yii\db\Query */
                    $query->andWhere(['not', ['id' => Yii::$app->user->getId()]]);
                }
            ],

            ['password', 'string'],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password' => 'Новый пароль',
            'password_confirm' => 'Подтверждение пароля',
        ];
    }

    /**
     * Changes user account.
     * @return boolean
     */
    public function account()
    {
        if (!$this->validate()) {
            return null;
        }
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $user->userProfile->username = $this->username;
        $user->userProfile->save();
        $user->email = $this->email;
        if ($this->password) {
            $user->setPassword($this->password);
        }
        $user->save();
        return $user->save() ? true : false;
    }
}