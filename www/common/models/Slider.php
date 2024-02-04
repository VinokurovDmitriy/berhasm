<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%slider}}".
 *
 * @property int $id
 * @property string $image_name
 * @property int $sort
 * @property int $status
 *
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class Slider extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%slider}}';
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
            [['image_name'], 'required'],
            [['image_name'], 'string', 'max' => 50],

            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg, mp4'],
            ['uploadedImageFile', 'required', 'when' => function () {
                return !$this->image_name;
            }, 'whenClient' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    #################### IMAGES ####################

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->_folder = '/files/' . self::folderName . '/';
        $this->_folderPath = Yii::getAlias('@files') . '/' . self::folderName . '/';
    }

    const folderName = 'slider';
    const imageFileName = 'file_';

    public $uploadedImageFile;

    private $imageWidth = 1920;
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
            if ($this->uploadedImageFile->extension != 'mp4') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path);
            }
        }
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (is_file($this->_folderPath . $this->image_name)){
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
