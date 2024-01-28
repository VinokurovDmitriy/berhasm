<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $contacts \common\models\Contacts[] */
?>

<div id="topBlock">

</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <?php foreach ($contacts as $contact): ; ?>
    <div class="contactItem">
        <h2><?= $contact->title; ?>:</h2>
        <?php switch ($contact->type) {
            case 'email':
                echo Html::a($contact->value, 'mailto:' . $contact->value);
                break;
            case 'phone':
                echo Html::a($contact->value, 'tel:+' . str_replace(['+', ' ', '(', ')', '-'], '', $contact->value));
                break;
            default:
                echo $contact->value;
        } ?>
    </div>
    <?php endforeach; ?>
</div>

