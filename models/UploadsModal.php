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
        $pathImg = $path . $this->id . "." .pathinfo($this->imageFile->name, PATHINFO_EXTENSION);      

        if (!file_exists($path)) {
            mkdir($path, 0777, true);        
        }
        $this->imageFile->saveAs($pathImg);            
        $this->picture = $this->id . "." .pathinfo($this->imageFile->name, PATHINFO_EXTENSION);
        $this->imageFile=null;
        return  $this->save();     	
    }

}
