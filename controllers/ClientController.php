<?php

namespace app\controllers;
use app\models\Address;
use app\models\Client;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;
use Yii;

class ClientController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Client';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        return $actions;
    }

 /**
 * Store Client and address.
 *
 * @return Yii::json
 */
    public function actionCreate()
    {
        $request = Yii::$app->request;
    //return  $_FILES['imageFile'];
        // Store models errors
        $errorsClient = [];
        $errorsAddress = [];

        //Add  new client with new address
        $valuesClient = [        
            'name'       => $request->post('name') ,
            'cpf'        => $cpf = preg_replace( '/[^0-9]/is', '',$request->post('cpf') ),        
            'sex'        => $request->post('sex'),         
        ];

        $client = new Client();    
        $client->attributes = $valuesClient;

        //Validate Client data
        if (!$client->validate()) {            
            $errorsClient  = $client->errors;       
        }   

        // Add  new address ,, here we can make some logic with existing addresses
        $valuesAddress = [        
            'zip'         => $request->post('zip') ,
            'public_place'=> $request->post('public_place'),
            'number'      => $request->post('number') ,
            'complement'  => $request->post('complement'),  
            'city'        => $request->post('city') , 
            'state'       => $request->post('state') , 
        ];
        
        $address = new Address();
        $address->attributes = $valuesAddress;

        // Validate the Address data and save     
        if (!$address->validate()) {           
             
            $errorsAddress = $address->errors;       
        }
        // Cheking if exist errors
        if (count($errorsClient) || count($errorsAddress) )
        {
            return $this->asJson(
                array_merge($errorsClient, $errorsAddress)
            );            
        }
        // Save Address
        $address->save();
        //update  address_id field
        $client->address_id = $address->id;
        $client->save();
        //add picture to path and db
        if (Yii::$app->request->isPost && UploadedFile::getInstanceByName('imageFile')) {
            $client->imageFile = UploadedFile::getInstanceByName('imageFile'); 
            $client->upload($client->uploadPath());  
        }
        return $this->asJson(
            $client
        );
    }

}
