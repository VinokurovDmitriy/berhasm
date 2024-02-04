<?php
/**
 * Created by PhpStorm.
 * User: Hexagen
 * Date: 16.12.2016
 * Time: 7:08
 */

namespace frontend\assets;
use yii\web\AssetBundle;

class SliderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/slick.css',
    ];
    public $js = [
        'js/slick.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}