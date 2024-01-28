<?php
$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'name' => 'Berhasm',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@frontend/runtime/cache'
        ],

        'fileStorage' => [
            'class' => 'trntv\filekit\Storage',
            'baseUrl' => '@storageUrl/uploads',
            'filesystem' => [
                'class' => 'common\components\filesystem\LocalFlysystemBuilder',
                'path' => '@files/uploads'
            ],
        ],

        'urlManager' => [
//            'class' => 'codemix\localeurls\UrlManager',
//            'languages' => [$params['defaultLanguage'], 'en'],
//            'enableDefaultLanguageUrlCode' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
            ],
        ],
    ],
    'params' => $params,
];