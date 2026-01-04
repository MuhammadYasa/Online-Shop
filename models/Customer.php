<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Customer Model - Authentication untuk pembeli
 * 
 * Tabel: customer
 * Berbeda dengan User (admin), Customer untuk pembeli yang belanja
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    public $password; // Field temporary untuk input password

    public static function tableName()
    {
        return 'customer';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required', 'on' => 'register'],
            [['name', 'email'], 'required', 'on' => 'update'],
            ['email', 'email'],
            ['email', 'unique'],
            ['password', 'string', 'min' => 6],
            [['phone', 'address'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Nama Lengkap',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'No. Telepon',
            'address' => 'Alamat Lengkap',
        ];
    }

    // IdentityInterface methods
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->password) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    // Relasi
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['customer_id' => 'id']);
    }

    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['customer_id' => 'id']);
    }
}
