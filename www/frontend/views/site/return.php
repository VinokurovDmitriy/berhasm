<?php

/* @var $this yii\web\View */
/* @var $module \common\models\Modules */
?>

    <a href="<?= Yii::$app->request->absoluteUrl != Yii::$app->request->referrer ? Yii::$app->request->referrer : '/'; ?>"><?= Yii::t('app', 'Go back'); ?></a>

<?= $module->title; ?>
<?= $module->html; ?>