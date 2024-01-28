<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/cartWidget.css',
        'css/magnifier.css',
        'css/style.css',
    ];
    public $js = [
        'js/jquery-ias.min.js',
        'js/pinch-zoom.umd.js',
//        'js/numeral.min.js',
        'js/cartWidget.js',
        'js/Event.js',
        'js/Magnifier.js',
        'js/script.js',
        'js/product.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
