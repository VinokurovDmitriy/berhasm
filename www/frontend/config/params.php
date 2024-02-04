<?php
return [
    'adminEmail' => 'you_got_it@berhasm.com',
    // 'siteEmail' => 'admin@otlr.net',
    'siteEmail' => 'you_got_it@berhasm.com',
    'user.passwordResetTokenExpire' => 3600,

    'defaultLanguage' => 'ru',
    'cacheTime' => 60*60,

    'payMethods' => [
//        'pay-pal' => Yii::t('app', 'PayPal'),
        'online' => Yii::t('app', 'Credit Card, Apple Pay, Google Pay'),
//        'cash' => Yii::t('app', 'Test'),
    ],

    'currencies' => [
        'ru' => '₽',
        'en' => '$',
        'eur' => '€'
    ],
    'freeShipment' => 25000,

    'paymentUrl' => 'https://secure.payture.com/apim/',

    'key' => 'BerhasmPSB3DS',
    'IP' => '46.149.80.64',
];
