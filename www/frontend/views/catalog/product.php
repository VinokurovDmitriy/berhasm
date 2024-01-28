<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use shop\helpers\PriceHelper;
use frontend\assets\SliderAsset;
SliderAsset::register($this);

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $module \common\models\Modules */
/* @var $cartForm shop\forms\Shop\AddToCartForm */
/* @var $sizes \common\models\SizeGuide */
?>
<script>
    fbq('track', 'ViewContent', {content_name:"<?= $product->name; ?>",content_category:"<?= $product->category->name;?>",currency: "RUB", value: <?= $product->price_new ?>});
</script>
<div id="topBlock">
    <a href="<?= preg_replace (['/&page=(\d+)/', '/&per-page=(\d+)/'], '', Url::previous()); ?>" class="backLink"><span class="icon-back"></span><?= Yii::t('app', 'Go back'); ?></a>
</div>
<div id="contentBlock" class="pageContentBlock <?= Yii::$app->controller->action->id;?>">
    <div id="productImage">
        <div class="sliderContainer">
            <?php if(!Yii::$app->devicedetect->isMobile()) {?>
                <div class="slider_nav">
                    <div class="nav prev">
                        <span class="icon-chevron-left"></span>
                    </div>
                    <div class="nav next">
                        <span class="icon-chevron-right"></span>
                    </div>
                </div>
            <?php }?>
            <div id="productSlider">
                <?php foreach ($product->photos as $key=>$photo): ; ?>
                <div class="slide">
                    <img src="<?= $photo->getImageFileUrl('file') ?>" class="galPic <?= (Yii::$app->devicedetect->isMobile())?"transparent":"";?>" alt="<?= $product->name; ?>" data-slide="<?= $key;?>">
                    <?php if(Yii::$app->devicedetect->isMobile()) {?>
                        <div class="zoomBlockWrapper">
                            <div class="zoomBlock">
                                <img src="<?= $photo->getImageFileUrl('file') ?>" class="zoomPic" alt="<?= $product->name; ?>" data-slide="<?= $key;?>">
                            </div>
                        </div>
                    <?php }?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="sliderContainer">
            <div id="productSliderNav">
                <?php foreach ($product->photos as $photo): ; ?>
                    <img src="<?= $photo->getImageFileUrl('file') ?>" class="galPic" alt="<?= $product->name; ?>">
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div id="productText">
        <h2><?= $product->name; ?></h2>

        <p class="productPrice">
            <?php if ($product->quantity <= 0): ; ?>
                <?php if (in_array(1, ArrayHelper::getColumn($product->tags, 'id'))): ; ?>
                    <?= Yii::t('app', 'Sold Out'); ?>
                <?php else: ; ?>
                    <?= Yii::t('app', 'Sold Out'); ?>
                <?php endif; ?>
            <?php else: ; ?>
            <?php if ($product->price_old) {;?>
                    <span style="text-decoration: line-through; color: gray"><?= PriceHelper::format($product->price_old); ?></span>
                    <span> | </span>
                    <span><?= Yii::t('app', 'Скидка');?>&nbsp;<?= number_format(100 - ($product->price_new / $product->price_old * 100), 0, '.', ' ');?>% </span>
            <?php };?>
                <strong <?= $product->price_old ? 'style="color:red"' : '';?>><?= PriceHelper::format($product->price_new); ?></strong>
            <?php endif; ?>
        </p>

        <div class="productDescription">
            <?= $product->description; ?>
        </div>

        <p id="sizeGuide"><?= Yii::t('app', 'Size guide'); ?></p>

        <?php if ($product->quantity > 0): ; ?>
            <?php $form = ActiveForm::begin([
                'action' => ['/cart/add', 'id' => $product->id],
            ]); ?>

            <div>
                <span><?= Yii::t('app', 'Size'); ?> </span><span id="item-prop-mod"></span>
            </div>
            <div class="item_prop_list">
                <?php $token = 0; foreach ($cartForm->modificationsList() as $key => $modification): ; ?>
                    <?php $disabled = $cartForm->quantities()[$key] > 0 ? ' ' : 'disabled'; ?>
                        <div class="item_prop item_prop_button <?= $disabled; ?>">
                            <input id="size_<?= $modification; ?>" type="radio"
                                   data-qty="<?= $cartForm->quantities()[$key]; ?>"
                                   class="item_prop_btn"
                                   name="item_prop_weight"
                                    <?= $disabled; ?>
                                    <?= ($disabled === ' ' && $token === 0)?"checked":"" ;?>
                              >
                            <label class="item_prop_label item_prop_mod" for="size_<?= $modification; ?>">
                                <div class="item_prop_text">
                                    <?= $modification; ?>
                                </div>

                            </label>
                        </div>
                <?php $token++; ?>
                <?php endforeach; ?>
            </div>

            <div class="item_prop_hidden item_prop_hidden">
<!--                --><?php //$cartForm->modification = array_keys($cartForm->modificationsList())[0]; ?>
                <?= $form->field($cartForm, 'modification', ['radioTemplate' => "{input}{label}\n{hint}\n{error}"])
                    ->radioList(
                        $cartForm->modificationsList()
                    )->label(false); ?>
            </div>

            <div class="item_buy_btn">
                <button class="btn black" type="submit" onclick="ym(50136718,'reachGoal','ДобавлениеВКорзину'); fbq('track', 'AddToCart'); return true;">
                    <div class="item_buy_btn_ins">
                        <i class="icon-cart"></i>
                        <span><?= Yii::t('app', 'Add to cart');?></span>
                    </div>
                </button>
            </div>

            <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </div>
</div>
<div id="sizeGuideBlock">
    <div class="close"><span class="icon-cross"></span></div>
    <h3 class="size_guide_title"><?= Yii::t('app', 'Size guide'); ?>:</h3>
    <div class="size_guide_container">
        <?php foreach ($sizes as $size): ;?>
            <div class="customer_care_block">
                <div class="customer_care_title">
                    <span><?= $size->title;?></span>
                </div>
                <div class="customer_care_html">
                    <?= $size->html;?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
<?php if(!Yii::$app->devicedetect->isMobile()) {?>
<div id="fullPageBlock">
    <div class="fullPageClose"><span class="icon-cross"></span></div>
    <div class="sliderContainer productFullSlider">
        <div class="fullpage_slider_nav">
            <div class="fp_nav fp_prev">
                <span class="icon-chevron-left"></span>
            </div>
            <div class="fp_nav fp_next">
                <span class="icon-chevron-right"></span>
            </div>
        </div>
        <div id="fullpageSlider">
            <?php foreach ($product->photos as $key=>$photo): ; ?>
                <div class="slide">
                    <div  class="image-block magnifier-thumb-wrapper" data-large-img-url="<?= $photo->getImageFileUrl('file') ?>">
                        <img src="<?= $photo->getImageFileUrl('file') ?>" class="thumb galPic" alt="<?= $product->name; ?>" data-slide="<?= $key;?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="sliderContainer">
        <div id="fullpageSliderNav">
            <?php foreach ($product->photos as $photo): ; ?>
                <img src="<?= $photo->getImageFileUrl('file') ?>" class="galPic" alt="<?= $product->name; ?>">
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php };?>
<script defer type="text/javascript">
    window.dataLayer.push({
        "ecommerce": {
            "detail": {
                "products": [
                    {
                        "id": "<?= $product->code ?>",
                        "name" : "<?= str_replace('"','',$product->name) ?>",
                        "price": <?= $product->price_new;?>,
                        "brand": "BERHASM",
                        "category": "Одежда",
                    }
                ]
            }
        }
    });
</script>
