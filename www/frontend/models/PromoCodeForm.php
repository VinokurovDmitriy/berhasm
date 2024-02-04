<?php

namespace frontend\models;

use common\models\Promocodes;
use Yii;
use yii\base\Model;

class PromoCodeForm extends Model
{
    public $promoCode;

    public function rules()
    {
        return [
//            [['promoCode'], 'required'],
            ['promoCode', 'match', 'pattern' => '/^[a-zA-Z1-9]{2,255}$/u', 'message' => Yii::t('app', '{attribute} can contain only letters and numbers')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'promoCode' => Yii::t('app', 'Discount Code'),
        ];
    }

    public function checkPromoCode()
    {
        if ($code = Promocodes::findOne(['code' => $this->promoCode])) {
            return $code->discount;
        } else {
            return false;
        }
    }
}