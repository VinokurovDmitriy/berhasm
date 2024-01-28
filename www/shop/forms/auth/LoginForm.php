<?php
namespace shop\forms\auth;

use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'email'   => 'E-mail',
          'password'   => 'Пароль',
          'rememberMe'   => 'Запомнить меня',
        ];
    }
}