<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User Model - Authentication dengan Database
 * 
 * Tabel: user
 * Kolom:
 * - id (int, PK, auto increment)
 * - username (varchar 255, unique)
 * - password_hash (varchar 255) - password di-hash pakai Yii::$app->security
 * - auth_key (varchar 32) - untuk remember me
 * - created_at (datetime)
 * - updated_at (datetime)
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password; // Field sementara untuk input password (tidak disimpan ke DB)


    /**
     * Nama tabel di database
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Aturan validasi
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'unique'],
            ['username', 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Label untuk form
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    /**
     * Cari user berdasarkan ID
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Cari user berdasarkan access token (untuk API)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Cari user berdasarkan username
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Get user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get auth key untuk remember me
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validasi auth key
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validasi password (compare dengan hash di database)
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generate auth key baru (untuk remember me)
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Set password dengan hashing
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Hook sebelum save - auto hash password jika ada input baru
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Jika ada password baru, hash dan generate auth key
            if ($this->password) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }
}
