<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string|null $name
 * @property string $cpf
 * @property string|null $picture
 * @property string|null $sex
 * @property string|null $created_at
 * @property int|null $created_by
 * @property int|null $address_id 
 * @property Address $address
 * @property User $createdBy
 * @property Product[] $products
 */
class Client extends UploadsModal
{ 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }
     public function fields()
     {
        return ['id','name','cpf','picture','sex','address'];
     }
     public function extraFields()
     {
         return ['created_at','created_by'];
     }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,                
                               
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['cpf', 'validateCPF'],
            [['cpf'], 'required'],
            [['created_at'], 'safe'],
            [['created_by', 'address_id'], 'integer'],
            [['name', 'cpf', 'picture', 'sex'], 'string', 'max' => 255],
            [['cpf'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::class, 'targetAttribute' => ['address_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cpf' => 'Cpf',
            'picture' => 'Picture',
            'sex' => 'Sex',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'address_id' => 'Address ID',
        ];
    }

    /**
     * Gets query for [[Address]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::class, ['id' => 'address_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['client_id' => 'id']);
    }
  
     /**
     * Gets path to upload picture.
     *
     * @return string
     */
    public function uploadPath() {
        return  'uploads/clients/';
    }
   public  function validateCPF($attribute, $params) 
   {   

        // extract just numbers
        $cpf = preg_replace( '/[^0-9]/is', '',$this->$attribute );
       
        // Check only digits
        if (strlen($cpf) != 11) {
             $this->addError($attribute, 'The format of CPF is incorrect".');
             return false;
        }
    
        // Non secuencies Example: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
             $this->addError($attribute, 'The format of CPF is incorrect".');
             return false;
        }
    
        // Calculation to validate
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                 $this->addError($attribute, 'The format of CPF is incorrect".');
                 return false;
            }
        }
       // return true;
    
    }
}
