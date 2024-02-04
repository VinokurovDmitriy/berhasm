<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%gallery-categories}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $slug
 * @property string $image_name
 * @property int $ord
 * @property int $status
 *
 * @property GalleryItems[] $galleryItems
 * @property GalleryItems[] $activeGalleryItems
 *
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class GalleryCategories extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%gallery_categories}}';
    }


    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title_ru',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
        ];
    }

    public function rules()
    {
        return [
            [['image_name', 'title_ru'], 'required'],
            [['title_ru', 'title_en', 'slug', 'image_name'], 'string', 'max' => 50],
            [['slug'], 'unique'],

            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'maxSize' => 30*1024*1024, 'extensions' => 'png, jpg, jpeg'],
            ['uploadedImageFile', 'required', 'when' => function () {
                return !$this->image_name;
            }, 'whenClient' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Заголовок Ru',
            'title_en' => 'Заголовок En',
            'slug' => 'Slug',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getGalleryItems()
    {
        return $this->hasMany(GalleryItems::class, ['category_id' => 'id'])->orderBy('sort');
    }

    public function getActiveGalleryItems()
    {
        return $this->hasMany(GalleryItems::class, ['category_id' => 'id'])->having(['status' => 1])->orderBy('sort');
    }

    public function getTitle()
    {
        $attr = "title_" . Yii::$app->language;
        $def_attr = "title_" . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }

    #################### IMAGES ####################

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->_folder = '/files/' . self::folderName . '/';
        $this->_folderPath = Yii::getAlias('@files') . '/' . self::folderName . '/';
    }

    const folderName = 'gallery-categories';
    const imageFileName = 'image_';

    public $uploadedImageFile;

    private $imageWidth = 400;
    private $imageHeight = 700;

    private $_folder;
    private $_folderPath;

    public function setImage()
    {
        FileHelper::createDirectory($this->_folderPath);
        $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
        if ($this->uploadedImageFile) {
            $this->deleteImage();
            $this->image_name = self::imageFileName . date('YmdHis') . '.' . $this->uploadedImageFile->extension;
        }
    }

    public function saveImage()
    {
        if ($this->uploadedImageFile) {
            $path = $this->_folderPath . $this->image_name;
            $this->uploadedImageFile->saveAs($path);
            if ($this->uploadedImageFile->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path, ['quality' => 75]);
            }
        }
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (is_file($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
            $this->image_name = null;
            $this->save();
        }
    }

    public function getImage()
    {
        return $this->_folder . $this->image_name;
    }
}
