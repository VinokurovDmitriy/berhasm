<?php
/* @var $this yii\web\View */
/* @var $model common\models\Seo */

$title = 'SEO страницы ' . $model->page;
$this->title = 'Изменить: ' . $title;

$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['view', 'page' => $model->page]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="seo-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
