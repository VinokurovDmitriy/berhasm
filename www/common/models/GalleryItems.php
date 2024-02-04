<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%gallery_items}}".
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $image_name
 * @property int $status
 * @property int $sort
 *
 * @property GalleryCategories $category
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class GalleryItems extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%gallery_items}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return GalleryItems::find()->where(['category_id' => $this->category_id]);
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['category_id', 'image_name'], 'required'],
            [['title_ru', 'title_en', 'image_name'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => GalleryCategories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['uploadedImageFile'], 'safe'],

            [['uploadedImageFile'], 'file', 'maxSize' => 30 * 1024 * 1024, 'extensions' => 'png, jpg, jpeg, tif'],
            ['uploadedImageFile', 'required', 'when' => function () {
                return !$this->image_name;
            }, 'whenClient' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'title_ru' => 'Заголовок Ru',
            'title_en' => 'Заголовок Eng',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(GalleryCategories::class, ['id' => 'category_id']);
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

    const folderName = 'gallery_items';
    const imageFileName = 'image_';

    public $uploadedImageFile;

    private $imageWidth = 1280;
    private $imageHeight = null;

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
