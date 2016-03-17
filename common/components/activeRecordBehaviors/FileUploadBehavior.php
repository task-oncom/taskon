<?php
namespace common\components\activeRecordBehaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\imagine\Image;

class FileUploadBehavior extends Behavior
{
    public $path;

    public $folder;

    public $file;

    public $unlinkFile = false;

    public $field;

    public $thumb = false;

    public $thumbSize = ['100', '100'];

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'Save',
            ActiveRecord::EVENT_BEFORE_INSERT => 'Insert',
            ActiveRecord::EVENT_BEFORE_DELETE => 'Delete',
        ];
    }

    private function getAbsolutePath()
    {
        return Yii::getAlias($this->path);
    }

    private function uploadFile()
    {
        if(!file_exists($this->getAbsolutePath() . $this->folder))
        {
            mkdir($this->getAbsolutePath() . $this->folder, 0777, true);
        }

        $field = $this->field;

        $filename = date('dmYHis-') . uniqid();

        $this->owner->$field = $this->folder . $filename  . '.' . $this->file->extension;
        $this->file->saveAs($this->getAbsolutePath() . $this->owner->$field);

        if($this->thumb)
        {
            list($width, $height) = $this->thumbSize;
            try 
            {
                Image::$driver = [Image::DRIVER_GD2];
                Image::thumbnail($this->path . $this->owner->$field, $width, $height)
                    ->save($this->getAbsolutePath().$this->folder.$filename.'.thumb.'.$this->file->extension, ['quality' => 80]);
            } 
            catch (Exception $e) 
            {
                throw $e;                
            }
            
        }
    }

    private function deleteFile()
    {
        $field = $this->field;

        if($this->owner->$field)
        {
            // Удаление Thumbnail если есть
            $path = pathinfo(Yii::getAlias($this->path . $this->owner->$field));
            $thumb = $path['dirname'] . '/' . $path['filename'] . '.thumb.' . $path['extension'];

            if(file_exists($thumb))
            {
                unlink($thumb);
            }

            // Удаление файла
            if(file_exists($this->getAbsolutePath() . $this->owner->$field))
            {
                unlink($this->getAbsolutePath() . $this->owner->$field);
            }

            $this->owner->$field = null;
        }
    }

    public function Save($event)
    {
        $this->file = UploadedFile::getInstance($this->owner, 'file');

        if($this->owner->unlinkFile || $this->file)
        {
            $this->deleteFile();
        }

        if($this->file)
        {
            $this->uploadFile();
        }

        return true;
    }

    public function Insert($event)
    {
        $this->file = UploadedFile::getInstance($this->owner, 'file');

        if($this->file)
        {
            $this->uploadFile();
        }

        return true;
    }

    public function Delete($event)
    {
        $this->deleteFile();
        
        return true;
    }
}
