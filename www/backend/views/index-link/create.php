<?php
/* @var $this yii\web\View */
/* @var $model common\models\IndexLink */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Index Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-link-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
