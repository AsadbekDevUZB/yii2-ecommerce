<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $imageFile
 * @property float $price
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property CartItem[] $cartItems
 * @property OrderItem[] $ordersItems
 */
class Product extends ActiveRecord
{
    /**
    * @var $imageFile UploadedFile;
    */

    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'price'], 'required'],
            [['imageFile'],'image','extensions'=>'png,jpg,jpeg','maxSize'=>5 * 1024 * 1024],
            [['price'], 'number'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description', 'image'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'image' => 'Product Image',
            'imageFile' => 'Product Image',
            'price' => 'Price',
            'status' => 'Published',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CartItemQuery
     */
    public function getCartItems()
    {
        return $this->hasMany(CartItem::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[OrdersItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemQuery
     */
    public function getOrdersItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProductQuery(get_called_class());
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        if($this->imageFile){
            $this->image = '/product/'.Yii::$app->security->generateRandomString().'/'.$this->imageFile->name;
        }
        $saveModel = parent::save($runValidation, $attributeNames); // TODO: Change the autogenerated stub
        $transaction = Yii::$app->db->beginTransaction();
        if($saveModel){
            $fullPath = Yii::getAlias('@frontend/web/storage').$this->image;
            $path = dirname($fullPath);
            if(!FileHelper::createDirectory($path) || !$this->imageFile->saveAs($fullPath)){
                $transaction->rollBack();
            }
            $transaction->commit();
        }
        return $saveModel;
    }
}
