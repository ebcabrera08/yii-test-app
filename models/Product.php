<?php

namespace app\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string|null $picture
 * @property string|null $created_at
 * @property int|null $client_id
 * @property int|null $created_by
 *
 * @property Client $client
 * @property User $createdBy
 */
class Product extends UploadsModal
{

    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    public function fields()
     {
        return ['id','name','price','picture','client'];
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
            [['name', 'price','client_id','picture'], 'required'],
            [['price'], 'number'],
            [['created_at'], 'safe'],
            [['client_id', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['picture'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
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
            'price' => 'Price',
            'picture' => 'Picture',
            'created_at' => 'Created At',
            'client_id' => 'Client ID',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
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
     * Gets path to upload picture.
     *
     * @return string
     */
    public function uploadPath() {
        return  'uploads/products/';
    }
}
