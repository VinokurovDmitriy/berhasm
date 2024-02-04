<?php

namespace shop\services\auth;

use shop\entities\User\User;
use shop\forms\auth\LoginForm;
use shop\repositories\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->getByEmail($form->email);
        if(!$user || !$user->validatePassword($form->password)){
            throw new \DomainException('Неверный E-mail пользователя или пароль.');
        }
        if($user->isPending()){
            throw new \DomainException('Ожидается подтверждение E-mail от данного пользователя.');
        }
        if($user->isBlocked()){
            throw new \DomainException('Данный пользователь заблокирован.');
        }
        return $user;
    }

    public function authAdmin(LoginForm $form): User
    {
        $user = $this->users->getByEmail($form->email);
        if(!$user || !$user->isActive() || !$user->validatePassword($form->password)){
            throw new \DomainException('Неверный E-mail пользователя или пароль.');
        }
        if (!$user->isAdmin() && !$user->isManager()){
            throw new \DomainException('Только администраторы и модераторы могут выполнить данное действие.');
        }
        return $user;
    }
}