<?php
/* @var $this yii\web\View */
/* @var $model \common\models\GalleryItems */

$title = $model->title_ru ?: $model->sort;
$this->title = 'Изменить: ' . $title;

$this->params['breadcrumbs'][] = ['label' => $model->category->title_ru, 'url' => ['index', 'slug' => $model->category->slug]];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="gallery-images-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
