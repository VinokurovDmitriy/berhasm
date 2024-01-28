<?php

namespace shop\helpers;

use Yii;

class PriceHelper
{
    private static function getCurrency()
    {
        $currency = Yii::$app->cache->getOrSet('currency', function () {
            return self::CurrencyFunction();
        }, 60 * 60 * 24);
        if (empty($currency['eur'])) {
           $currency = self::CurrencyFunction();
        }
        return $currency;
    }

    private static function CurrencyFunction()
    {
        /**
         * @var $rub float
         * @var $usd float
         */
        $cursXML = json_decode(file_get_contents('https://v6.exchangerate-api.com/v6/634204f87bbe444541df2650/latest/RUB'), true);

        // foreach ($cursXML->Cube->Cube->Cube as $item) {
        //     if ($item->attributes()->currency == 'USD') {
        //         $usd = floatval($item->attributes()->rate);
        //     }
        //     if ($item->attributes()->currency == 'RUB') {
        //         $rub = floatval($item->attributes()->rate);
        //     }
        // }
        $curs = [
            'eur' => $cursXML['conversion_rates']['EUR'],
            'en' => $cursXML['conversion_rates']['USD'],
            'ru' => 1
        ];
        return $curs;
    }

    public static function format($price): string
    {
        $cookies = Yii::$app->request->cookies;
        $curs = self::getCurrency()[$cookies->getValue('currency', Yii::$app->language)];
        $value = round($price * $curs, 0, PHP_ROUND_HALF_UP);
//        $value = $price / $curs;

        if ($price) {
            return Yii::$app->params['currencies'][$cookies->getValue('currency', Yii::$app->language)] . ' ' . number_format($value, 0, '.', ' ');
        }
        return '';
    }
}