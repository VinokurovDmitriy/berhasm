<?php
namespace shop\forms\auth;

use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_confirm;

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 5],
            ['password_confirm', 'required'],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_confirm' => 'Подтвердите пароль',
        ];
    }
}
