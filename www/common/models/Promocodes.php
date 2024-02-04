<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%promocodes}}".
 *
 * @property int $id
 * @property string $code
 * @property int $discount
 * @property int $status
 */
class Promocodes extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%promocodes}}';
    }

    public function rules()
    {
        return [
            [['code', 'discount'], 'required'],
            [['code'], 'unique'],
            [['status'], 'integer'],
            [['discount'], 'integer', 'min' => 0, 'max' => 100],
            [['code'], 'string', 'max' => 50],
            ['code', 'match', 'pattern' => '/^[a-zA-Z1-9]{2,255}$/u', 'message' => Yii::t('app', '{attribute} может содержать только буквы и цифры')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'discount' => 'Скидка, %',
            'status' => 'Статус',
        ];
    }
}
