<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Modules */

$this->title = $model->title_ru ?: 'Текст';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modules-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="row">
        <div class="col-6">
            <div class="box box-default">
                <div class="box-header with-border">Ru</div>
                <div class="box-body">
                    <h1><?= $model->title_ru ?></h1>
                    <?= $model->html_ru ?>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="box box-default">
                <div class="box-header with-border">En</div>
                <div class="box-body">
                    <h1><?= $model->title_en ?></h1>
                    <?= $model->html_en ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($model->modulesAttachments) { ?>
        <h2>Изображения</h2>
        <div class='gallery'>
            <?php foreach ($model->modulesAttachments as $attachment) { ?>
                <div class="galImg" style="background-image: url(<?= $attachment->getUrl(); ?>);"></div>
            <?php }; ?>
        </div>
    <?php }; ?>

</div>
