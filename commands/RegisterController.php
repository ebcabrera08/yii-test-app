<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RegisterController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public $username;
    public $password;

    public function options($actionID)
    {
        return ['username','password'];
    }
    
    public function optionAliases()
    {
        return ['user' => 'username', 'pass'=>'password'];
    }
    public function actionIndex()
    {
        if ($this->username && $this->password )
        {
           
            if ($this->username!=1 && $this->password!=1 ){

                $user = new User();
                $user->username = $this->username;                
                $user->setPassword($this->password);
                $user->generateAuthKey();  
                $user->generateEmailVerificationToken();           
                $user->save();

                echo "User created successfull" . "\n";
            }
            else {
                echo "The username and password  can not be empty". "\n";
            }
            
        }
        else {
            echo "Yu need to pass username and password". "\n";
        }
        

        return ExitCode::OK;
    }
}
