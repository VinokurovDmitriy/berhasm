<?php

namespace common\bootstrap;

use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\HybridStorage;
use shop\services\contact\ContactService;
use yii\base\BootstrapInterface;
use shop\services\auth\PasswordResetService;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(PasswordResetService::class, [], [
            [$app->params['siteEmail'] => $app->name . ' robot'],
        ]);

        $container->setSingleton(ContactService::class, [], [
            [$app->params['siteEmail'] => $app->name . ' robot'],
            $app->params['adminEmail'],
        ]);

        $container->setSingleton(Cart::class, function () use ($app) {
            return new Cart(
                new HybridStorage($app->get('user'), 'cart', 3600 * 24, $app->db),
                new DynamicCost(new SimpleCost())
            );
        });
    }
}