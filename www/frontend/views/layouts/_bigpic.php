<?php
use common\models\Slider;
/**
 * @var $slide \common\models\Slider
*/
$slide = Slider::getDb()->cache(function () {
    return Slider::findOne(1);
}, Yii::$app->params['cacheTime']);
$bgPicArr = ['about'];
$isBgPic = in_array(Yii::$app->controller->action->id, $bgPicArr) && (in_array(Yii::$app->controller->id, ['site']))
;?>
<?php if ($isBgPic): ?>
    <?php if (substr($slide->image_name, strpos($slide->image_name, '.') + 1) == 'mp4'): ; ?>
        <div id="bgVideo">
            <video autoplay="" muted="" loop="">
                <source src="<?= $slide->image; ?>">
            </video>
        </div>
        <?= $slide->image ?>
    <?php else: ; ?>
        <div id="bgPic" style="background-image: url(<?= $slide->image; ?>)">
            <div class="blackFader"></div>
        </div>
    <?php endif; ?>
<?php endif; ?>
