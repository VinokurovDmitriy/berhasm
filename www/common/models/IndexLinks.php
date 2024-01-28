<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%index_links}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $sub_title
 * @property string $sub_title_ru
 * @property string $sub_title_en
 * @property string $image_name
 * @property string $banner_name
 * @property string $banner_name_ru
 * @property string $banner_name_en
 * @property string $link
 * @property string $sex
 * @property int $sort
 * @property int $status
 *
 * @property UploadedFile $uploadedImageFile
 * @property UploadedFile $uploadedBannerFileRu
 * @property UploadedFile $uploadedBannerFileEn
 * @property string $image
 * @property string $banner
 * @property string $banner_ru
 * @property string $banner_en
 */
class IndexLinks extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%index_links}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return array_filter([
            [['title_ru', 'sub_title_ru', 'image_name', 'link'], 'required'],
            [['sort', 'status'], 'integer'],
            [['title_ru', 'title_en', 'sub_title_ru', 'sub_title_en', 'image_name', 'link', 'banner_name_ru', 'banner_name_en'], 'string', 'max' => 255],

            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            $this->isNewRecord ? ['uploadedImageFile', 'required'] : null,

            [['uploadedBannerFileRu'], 'safe'],
            [['uploadedBannerFileRu'], 'file', 'extensions' => 'png, jpg, jpeg, svg'],
            $this->isNewRecord ? ['uploadedBannerFileRu', 'required'] : null,

            [['uploadedBannerFileEn'], 'safe'],
            [['uploadedBannerFileEn'], 'file', 'extensions' => 'png, jpg, jpeg, svg'],

        ]);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Заголовок Ru',
            'title_en' => 'Заголовок En',
            'sub_title_ru' => 'Подзаголовок Ru',
            'sub_title_en' => 'Подзаголовок En',
            'image_name' => 'Image Name',
            'link' => 'Ссылка',
            'sort' => 'Порядок',
            'status' => 'Статус',
            'banner_name_ru' => 'Банер Ru',
            'uploadedImageFileRu' => 'Банер Ru',
            'banner_name_en' => 'Банер En',
            'uploadedImageFileEn' => 'Банер En',
        ];
    }

    #################### MULTI LANGUAGE ####################

    public function getTitle()
    {
        return $this->getAttr('title');
    }

    public function getSub_title()
    {
        return $this->getAttr('sub_title');
    }

    public function getBanner_name()
    {
        return $this->getAttr('banner_name');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }

    #################### IMAGES ####################

    private $imageWidth = 1000;
    private $imageHeight = null;

    public function __construct(array $config = [])
    {
        $folderName = str_replace(['{', '}', '%'], '', $this::tableName());
        parent::__construct($config);
        $this->_folder = '/files/' . $folderName . '/';
        $this->_folderPath = Yii::getAlias('@files') . '/' . $folderName . '/';
    }

    public $uploadedImageFile;
    public $uploadedBannerFileRu;
    public $uploadedBannerFileEn;
    private $_folder;
    private $_folderPath;

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
            $this->uploadedBannerFileRu = UploadedFile::getInstance($this, 'uploadedBannerFileRu');
            $this->uploadedBannerFileEn = UploadedFile::getInstance($this, 'uploadedBannerFileEn');
            $id = $this->isNewRecord ? self::find()->max('[[id]]') + 1 : $this->id;
            if ($this->uploadedImageFile) {
                $this->deleteImage();
                $this->image_name = $id . '_' . time() . '.' . $this->uploadedImageFile->extension;
            }
            if ($this->uploadedBannerFileRu) {
                $this->deleteBannerRu();
                $this->banner_name_ru = $id . '_ru_' . time() . '.' . $this->uploadedBannerFileRu->extension;
            }
            if ($this->uploadedBannerFileEn) {
                $this->deleteBannerEn();
                $this->banner_name_en = $id . '_en_' . time() . '.' . $this->uploadedBannerFileEn->extension;
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->uploadedImageFile) {
            $path = $this->_folderPath . $this->image_name;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedImageFile->saveAs($path);
            if ($this->uploadedImageFile->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path);
            }
        }
        if ($this->uploadedBannerFileRu) {
            $path = $this->_folderPath . $this->banner_name_ru;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedBannerFileRu->saveAs($path);
            if ($this->uploadedBannerFileRu->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path);
            }
        }
        if ($this->uploadedBannerFileEn) {
            $path = $this->_folderPath . $this->banner_name_en;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedBannerFileEn->saveAs($path);
            if ($this->uploadedBannerFileEn->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path);
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
    }

    public function deleteBannerRu()
    {
        if ($this->banner_name_ru) {
            if (file_exists($this->_folderPath . $this->banner_name_ru)) {
                unlink($this->_folderPath . $this->banner_name_ru);
            }
        }
    }

    public function deleteBannerEn()
    {
        if ($this->banner_name_en) {
            if (file_exists($this->_folderPath . $this->banner_name_en)) {
                unlink($this->_folderPath . $this->banner_name_en);
            }
        }
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (file_exists($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
        }
    }

    public function removeImage()
    {
        $this->deleteImage();
        $this->image_name = null;
        $this->save();
    }

    public function removeBannerRu()
    {
        $this->deleteBannerRu();
        $this->banner_name_ru = null;
        $this->save();
    }

    public function removeBannerEn()
    {
        $this->deleteBannerEn();
        $this->banner_name_en = null;
        $this->save();
    }

    public function getImage()
    {
        return $this->image_name ? $this->_folder . $this->image_name : null;
    }

    public function getBanner()
    {
        return $this->_folder . $this->banner_name;
    }

    public function getBannerPath()
    {
        return $this->_folderPath . $this->banner_name;
    }

    public function getBanner_ru()
    {
        return $this->_folder . $this->banner_name_ru;
    }

    public function getBanner_en()
    {
        return $this->_folder . $this->banner_name_en;
    }
}
