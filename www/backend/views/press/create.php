<?php
/* @var $this yii\web\View */
/* @var $model common\models\Press */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Пресса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="press-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
