<?php

namespace shop\helpers;

use shop\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function statusList(): array
    {
        return [
            User::STATUS_BLOCKED => 'Заблокирован',
            User::STATUS_PENDING => 'Ожидает',
            User::STATUS_ACTIVE => 'Активен',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case User::STATUS_BLOCKED:
                $class = 'label label-danger';
                break;
            case User::STATUS_PENDING:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function roleList(): array
    {
        return [
            User::ROLE_ADMIN => 'Администратор',
            User::ROLE_MANAGER => 'Менеджер',
            User::ROLE_CUSTOMER => 'Пользователь',
        ];
    }

    public static function roleName($role): string
    {
        return ArrayHelper::getValue(self::roleList(), $role);
    }

    public static function roleLabel($role): string
    {
        switch ($role) {
            case User::ROLE_ADMIN:
                $class = 'label label-danger';
                break;
            case User::ROLE_MANAGER:
                $class = 'label label-default';
                break;
            case User::ROLE_CUSTOMER:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-success';
        }
        return Html::tag('span', ArrayHelper::getValue(self::roleList(), $role), [
            'class' => $class,
        ]);
    }
}