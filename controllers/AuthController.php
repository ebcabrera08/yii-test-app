<?php

namespace app\controllers;
use yii\web\Controller;
use app\models\User;
use Yii;

class AuthController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\User';
    public function actions()
    {
        $actions = parent::actions();
        // disable all default actions
        unset($actions['delete'], $actions['create'],$actions['index'],$actions['update']);
        return $actions;
    }
    public function actionLogin()
    {       
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        if (!$username || !$password)
        {            
            throw new \yii\web\HttpException(401, Yii::t('app','User and Password are required.'));
        }     
              
        $user = User::find()
                ->where(['username' =>$username])               
                ->one();       

         if($user)
         {
            if(Yii::$app->getSecurity()->validatePassword($password,$user->password)){
                Yii::$app->user->login($user,  0);
                return $this->asJson(
                    [
                        'status' =>  "Successful",
                        'token'  => $user->accessToken
                    ]
                );
            }
          
           throw new \yii\web\HttpException(401, Yii::t('app','User o Password are Incorrect.'));

         } else {          
            throw new \yii\web\HttpException(401, Yii::t('app','User o Password are Incorrect..'));
         }        
    } 

}
