<?php

namespace app\controllers;
use app\models\Product;
use yii\web\UploadedFile;
use yii\filters\auth\HttpBearerAuth;
use yii\data\ActiveDataProvider;
use Yii;
class ProductController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Product';

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
       // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];


        return $actions;
    }

/**
 * Filter by Client
 *
 * @return ActiveDataProvider
 */
public function actionClient()
{
   
        return new ActiveDataProvider([
            'query' => $this->modelClass::find()->andWhere(['client_id' => Yii::$app->request->get('clientId')])
        ]);
    
   
}
/**
 * Store Product.
 *
 * @return Yii::json
 */
public function actionCreate()
{
    $request = Yii::$app->request;

    // Add  new Product
    $values = [        
        'name'       => $request->post('name') ,
        'price'      => $request->post('price'),        
        'client_id'  => $request->post('client_id'),    
        'picture'   => UploadedFile::getInstanceByName('imageFile')?UploadedFile::getInstanceByName('imageFile')->name :null     
    ];
    
    $product = new Product();
    $product->attributes = $values;

    // Validate the Address data and save     
    if ($product->validate()) {            
        $product->save();
    } else {        
        $errors = $product->errors;  
        return $this->asJson(
            $errors
        );     
    }   
   
    $product->save();
    //add picture to path and db
    if (Yii::$app->request->isPost && UploadedFile::getInstanceByName('imageFile')) {
        $product->imageFile = UploadedFile::getInstanceByName('imageFile'); 
        $product->upload($product->uploadPath());  
    }
    return $this->asJson(
        $product
    );
}


}
