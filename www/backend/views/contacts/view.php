<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Contacts;

/* @var $this yii\web\View */
/* @var $model common\models\Contacts */

$this->title = $model->title_ru;
$this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' =>
            'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'sort',
                    'title_ru',
                    [
                        'attribute' => 'value_ru',
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'white-space: normal;'],
                        'value' => function (Contacts $data) {
                            switch ($data->type) {
                                case 'email':
                                    return Html::a($data->value_ru, 'mailto:' . $data->value_ru);
                                    break;
                                case 'phone':
                                    return Html::a($data->value_ru, 'tel:+' . str_replace(['+', ' ', '(', ')', '-'], '', $data->value_ru));
                                    break;
                                default:
                                    return $data->value_ru;
                            }
                        }
                    ],

                    'title_en',
                    [
                        'attribute' => 'value_en',
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'white-space: normal;'],
                        'value' => function (Contacts $data) {
                            switch ($data->type) {
                                case 'email':
                                    return Html::a($data->value_en, 'mailto:' . $data->value_en);
                                    break;
                                case 'phone':
                                    return Html::a($data->value_en, 'tel:+' . str_replace(['+', ' ', '(', ')', '-'], '', $data->value_en));
                                    break;
                                default:
                                    return $data->value_en;
                            }
                        }
                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>
