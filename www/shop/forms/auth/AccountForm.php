<?php

namespace shop\forms\auth;

use shop\entities\User\CustomerProfile;
use shop\entities\User\User;
use yii\base\Model;

class AccountForm extends Model
{
    public $username;
    public $phone;
    public $email;
    public $address;

    private $_phone;
    private $_email;
    private $_user;

    public function __construct(array $config = [])
    {
        /* @var User $user */
        parent::__construct($config);
        $user = \Yii::$app->user->identity;
        $this->_user = $user;
        $this->_email = $user->email;
        $this->_phone = $user->userProfile->phone;
        $this->username = $user->userProfile->username;
        $this->phone = $user->userProfile->phone;
        $this->email = $user->email;
        $this->address = $user->addresses[0]->value;
    }

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => $this->_email ? ['not like','email', $this->_email] : null, 'message' => 'Этот e-mail адрес уже занят.'],

            ['phone', 'string', 'max' => 255],
            ['phone', 'unique', 'targetClass' => CustomerProfile::class, 'filter' => $this->_phone ? ['not like','phone', $this->_phone] : null, 'message' => 'Этот телефон уже занят.'],

            ['address', 'string', 'min' => 2, 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'address' => 'Адрес доставки',
        ];
    }

    public function editAccount(): User
    {
        if (!$this->validate()) {
            return null;
        }
        /* @var User $user */
        $user = \Yii::$app->user->identity;
        $user->edit($this->username, $this->phone, $this->email, $this->address);
        return $user->save() ? $user : $this->_user;
    }
}