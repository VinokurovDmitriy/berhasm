<?php

namespace shop\services\auth;

use shop\entities\User\User;
use shop\repositories\UserRepository;
use shop\forms\auth\SignupForm;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $users;

    public function __construct(MailerInterface $mailer, UserRepository $users)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    public function signup(SignupForm $form): User
    {
        if (User::find()->andWhere(['email' => $form->email])->one()) {
            throw new \DomainException('Пользователь с таким E-mail уже зарегистрирован.');
        }
        $user = User::signup(
            $form->username,
            $form->email,
            $form->password,
            $form->role
        );
        $this->users->save($user);

        $sent = $this->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user' => $user]
            )
            ->setFrom(\Yii::$app->params['siteEmail'])
            ->setTo($form->email)
            ->setSubject('Подтверждение регистрации на сайте ' . \Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка при отправке письма.');
        }
        return $user;
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Пустой токен подтверждения.');
        }
        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }

}