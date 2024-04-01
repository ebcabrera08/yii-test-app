<?php

namespace app\models;

use Yii;

/**
 * This is the model class for extends *
 * 
 ** @property file|null $imageFile
 * 
 */
class UploadsModal extends \yii\db\ActiveRecord
{

    public $imageFile;

    public function upload($path) {       
        $path = $path . $this->id . "." .pathinfo($this->imageFile->name, PATHINFO_EXTENSION);
        $this->imageFile->saveAs($path);            
        $this->picture = $this->id . "." .pathinfo($this->imageFile->name, PATHINFO_EXTENSION);
        $this->imageFile=null;
        return  $this->save();     	
    }

}
