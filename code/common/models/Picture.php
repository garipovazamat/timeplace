<?php

namespace common\models;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;
use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;

/**
 * This is the model class for table "picture".
 *
 * @property integer $id
 * @property string $name
 * @property string $extention
 *
 * @property Category[] $categories
 * @property PictureEvent[] $pictureEvents
 * @property User[] $users
 */
class Picture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     * @var UploadedFile $imageFile
     */

    const IMAGE_DIR = '@frontend/web/upload/';
    const THUMBNAILS_DIR = '/thumbnails/';
    const LENTA_THUMBNAIL_PREFIX = 'lenta';

    public $imageFile;
    public $miniature;
    public $bigMiniature;

    public static function tableName()
    {
        return 'picture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'extention'], 'string', 'max' => 250],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 6291456],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full' => 'Full',
            'imageFile' => 'Изображение',
            //'miniature' => 'Miniature',
            //'big_miniature' => 'Big Miniature',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id_picture' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPictureEvents()
    {
        return $this->hasMany(PictureEvent::className(), ['id_picture' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id_picture' => 'id']);
    }

    public function getSaveDir(){
        $year = date('Y');
        $mounth = date('m');
        $dir = Yii::getAlias('@frontend/web/upload/');
        $dir = $dir . $year . '/' . $mounth . '/';
        return $dir;
    }

    public function upload(){
        if ($this->validate()) {
            $dir = $this->getSaveDir();
            Picture::createDirectory($dir);
            if ($this->isNewRecord)
                $name = uniqid();
            else {
                $name = basename($this->name);
            }
            $extention = $this->imageFile->extension;
            $this->imageFile->saveAs($dir . $name . '.' . $extention);

            /*
            $miniature = new SimpleImage($dir . $name . '.' . $extention);
            $miniature->thumbnail(50, 50)->save($dir . $name . '-mini.' . $extention);
            $bigminiature = new SimpleImage($dir . $name . '.' . $extention);
            $bigminiature->thumbnail(100, 100)->save($dir . $name . '-bigmini.' . $extention);
            */

            $miniature = Image::thumbnail($dir . $name . '.' . $extention, 50, 50, ManipulatorInterface::THUMBNAIL_OUTBOUND)
                ->save(($dir . $name . '-mini.' . $extention), ['quality' => 80]);
            $bigminiature = Image::thumbnail($dir . $name . '.' . $extention, 100, 100)
                ->save(($dir . $name . '-bigmini.' . $extention), ['quality' => 80]);

            $year = date('Y');
            $mounth = date('m');
            $this->name = $year . '/' . $mounth . '/' . $name;
            $this->extention = $extention;
            //$this->miniature = $client_dir . $name . '-mini.' . $extention;
            //$this->big_miniature = $client_dir . $name . '-bigmini.' . $extention;
            $this->save(false);
            return true;
        } else {
            return false;
        }
    }

    public function justUpload(){
        if ($this->validate()) {
            $dir = $this->getSaveDir();
            Picture::createDirectory($dir);
            if ($this->isNewRecord)
                $name = uniqid();
            else {
                $name = basename($this->name);
            }
            $extention = $this->imageFile->extension;
            $this->imageFile->saveAs($dir . $name . '.' . $extention);
            $year = date('Y');
            $mounth = date('m');
            $this->name = $year . '/' . $mounth . '/' . $name;
            $this->extention = $extention;
            $this->save(false);
            return true;
        }
        else return false;
    }

    /**
     * @param bool|false $isMicro
     * если $isMicro = true обрезание и масштабирование фото производиться не будет
     */
    public function uploadForCategory($isMicro = false) {
        if ($this->validate()) {
            $dir = Yii::getAlias('@frontend/web/upload/');
            $name = uniqid();
            $extention = $this->imageFile->extension;
            $this->imageFile->saveAs($dir . $name . '.' . $extention);
            if($isMicro == false)
                $categirySizeImage = Image::thumbnail($dir . $name . '.' . $extention, 395, 317)
                    ->save(($dir . $name . '-category.' . $extention), ['quality' => 50]);
            $this->name = $name;
            $this->extention = $extention;
            $this->save(false);
        }
    }

    public function getImagePath(){
        if (!$this->isNewRecord)
            return Yii::$app->params['imageUrl'] . $this->name . '.' . $this->extention;
        else return Yii::$app->params['imageUrl'] . 'withoutphoto.png';
    }
    public function getCategoryImagePath(){
        return Yii::$app->params['imageUrl'] . $this->name . '-category.' . $this->extention;
    }
    public  function getMiniaturePath(){
        if (!$this->isNewRecord)
            return Yii::$app->params['imageUrl'] . $this->name . '-mini.' . $this->extention;
        else return Yii::$app->params['imageUrl'] . 'withoutphoto-mini.png';
    }
    public  function getBigMiniaturePath(){
        if (!$this->isNewRecord)
            return Yii::$app->params['imageUrl'] . $this->name . '-bigmini.' . $this->extention;
        else return Yii::$app->params['imageUrl'] . 'withoutphoto-bigmini.png';
    }

    public static function createDirectory($path){
        if (file_exists($path)) {
        } else {
            mkdir($path, 0775, true);
        }
    }

    /**
     * @return bool
     * удаляет файл изображения и все миниатюры с диска
     */
    public function deletePicture(){
        $dir = Yii::getAlias('@frontend/web/upload/');
        $filepath = $dir . $this->name . '.' . $this->extention;
        if(file_exists($filepath))
            unlink($filepath);
        $filepath = $dir . $this->name . '-mini.' . $this->extention;
        if(file_exists($filepath))
         unlink($filepath);
        $filepath = $dir . $this->name . '-bigmini.' . $this->extention;
        if(file_exists($filepath))
            unlink($filepath);
    }

    public function afterDelete(){
        return $this->deletePicture();
    }

    public function downloadFromOut($full_image, $miniature, $bigminiature){
        $year = date('Y');
        $mounth = date('m');
        $dir = $this->getSaveDir();
        Picture::createDirectory($dir);
        $name = uniqid();
        //Полная картинка
        $extention = pathinfo($full_image, PATHINFO_EXTENSION);
        $image = file_get_contents($full_image);
        file_put_contents($dir . $name . '.' . $extention, $image);
        //Миниатюра 100 х 100
        $image = file_get_contents($bigminiature);
        file_put_contents($dir . $name . '-bigmini.' . $extention, $image);
        //Миниатюра 50 х 50
        $image = file_get_contents($miniature);
        file_put_contents($dir . $name . '-mini.' . $extention, $image);

        $this->name = $year . '/' . $mounth . '/' . $name;
        $this->extention = $extention;
        return $this->save();
    }

    public function getFilepath(){
        $mainPath = Yii::getAlias(self::IMAGE_DIR);
        $filepath = $mainPath . $this->name . '.' . $this->extention;
        return $filepath;
    }

    private function createThumbnail($width, $heidht, $prefix = ''){
        $mainPath = Yii::getAlias(self::IMAGE_DIR);
        $filepath = $mainPath . $this->name . '.' . $this->extention;
        $filepath_arrray = pathinfo($filepath);
        $name = $filepath_arrray['filename'];
        $exten = $filepath_arrray['extension'];
        $newPath = $mainPath . self::THUMBNAILS_DIR . $name . '-'. $prefix. '.' . $exten;
        Image::thumbnail($filepath, $width, $heidht)->save($newPath);
    }

    private function getLentaThumbnailFilepath(){
        $filePath = $this->getFilepath();
        $filePath_array = pathinfo($filePath);
        $name = $filePath_array['filename'];
        $extention = $filePath_array['extension'];

        $uploadPath = Yii::getAlias(self::IMAGE_DIR);
        $path = $uploadPath . self::THUMBNAILS_DIR . $name . '-' . self::LENTA_THUMBNAIL_PREFIX . '.' . $extention;
        return $path;
    }


    private function getLentaThumbnailUrl(){
        $filePath = $this->getFilepath();
        $filePath_array = pathinfo($filePath);
        $name = $filePath_array['filename'];
        $extention = $filePath_array['extension'];
        $url = Yii::$app->params['imageUrl']
            . self::THUMBNAILS_DIR . $name . '-' . self::LENTA_THUMBNAIL_PREFIX . '.' . $extention;
        return $url;
    }

    public function getLentaThumbnail(){
        $thumb_path = $this->getLentaThumbnailFilepath();
        if(!file_exists($thumb_path)){
            $this->createThumbnail(357, 146, self::LENTA_THUMBNAIL_PREFIX);
        }
        return $this->getLentaThumbnailUrl();
    }
}

?>
