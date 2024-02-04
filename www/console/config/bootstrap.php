<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@files', dirname(dirname(__DIR__)) . '/files');
Yii::setAlias('@shop', dirname(dirname(__DIR__)) . '/shop');

// Setting url aliases

Yii::setAlias('@storageUrl', '' . Yii::$app->homeUrl . '/files');