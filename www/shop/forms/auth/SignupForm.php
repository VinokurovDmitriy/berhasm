<?php
namespace shop\forms\auth;

use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;
    public $role;

    public $data_collection_checkbox;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['role', 'required'],

            ['username', 'required'],
            ['username', 'string', 'min' => 3, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
//            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким E-mail уже зарегистрирован.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 5],

            ['password_confirm', 'required'],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password'],

            [['data_collection_checkbox'], 'required', 'requiredValue' => 1, 'message' => 'Необходимо ваше согласие'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'role' => 'Роль',
            'password' => 'Пароль',
            'password_confirm' => 'Подтвердите пароль',
            'email' => 'E-mail',
            'data_collection_checkbox' => 'Согласие на обработку персональных данных'
        ];
    }
}
