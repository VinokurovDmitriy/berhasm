<?php
namespace common\widgets\MultiLang;

use yii\bootstrap\Widget;

class MultiLang extends Widget
{
    public function init(){}

    public function run() {

        return $this->render('view');

    }
}