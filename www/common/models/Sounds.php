<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%sounds}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ru
 * @property string $title_en
 * @property string $file_name
 * @property string $image_name
 * @property string $link
 * @property int $sort
 * @property int $status
 *
 * @property UploadedFile $uploadedFile
 * @property string $file
 * @property string $filePath
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class Sounds extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sounds}}';
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
        return [
            [['sort', 'status'], 'integer'],
//            [['title_ru', 'title_en'], 'string', 'max' => 255],
            [['link'], 'string'],
//            [['file_name'], 'string', 'max' => 50],

//            [['uploadedFile'], 'safe'],
//            [['uploadedFile'], 'file', 'extensions' => 'mp3'],
//
//            [['uploadedImageFile'], 'safe'],
//            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Загололвок Ru',
            'title_en' => 'Загололвок En',
            'file_name' => 'Название файла',
            'uploadedFile' => 'Файл',
            'link' => 'Ссылка',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getTitle()
    {
        return $this->getAttr('title');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }

    #################### FILES ####################

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->_folder = '/files/' . self::folderName . '/';
        $this->_folderPath = Yii::getAlias('@files') . '/' . self::folderName . '/';
    }

    const folderName = 'sounds';
    const imageFileName = 'image_';
    const fileName = 'file_';

    public $uploadedImageFile;
    public $uploadedFile;

    private $imageWidth = 500;
    private $imageHeight = 500;

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
        $this->uploadedFile = UploadedFile::getInstance($this, 'uploadedFile');
        if ($this->uploadedFile) {
            $this->deleteFile();
            $this->file_name = self::fileName . date('YmdHis') . '.' . $this->uploadedFile->extension;
        }
    }

    public function saveImage()
    {
        if ($this->uploadedImageFile) {
            $path = $this->_folderPath . $this->image_name;
            $this->uploadedImageFile->saveAs($path);
            if ($this->uploadedImageFile->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path);
            }
        }
        if ($this->uploadedFile) {
            $path = $this->_folderPath . $this->file_name;
            $this->uploadedFile->saveAs($path);
        }
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            FileHelper::unlink($this->_folderPath . $this->image_name);
        }
    }

    public function deleteFile()
    {
        if ($this->file_name) {
            FileHelper::unlink($this->_folderPath . $this->file_name);
        }
    }

    public function getImage()
    {
        return $this->_folder . $this->image_name;
    }
    
    public function getFile()
    {
        return $this->_folder . $this->file_name;
    }

    public function getFilePath()
    {
        return $this->_folderPath . $this->file_name;
    }
}
