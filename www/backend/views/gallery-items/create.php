<?php
/* @var $this yii\web\View */
/* @var $model \common\models\GalleryItems */
/* @var $category \common\models\GalleryCategories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $category->title_ru, 'url' => ['index', 'slug' => $category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-images-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
