<?php

namespace app\models;

use yii\base\Model;
use Yii;

/**
 * CustomerLoginForm - Form login untuk customer
 */
class CustomerLoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_customer = false;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'rememberMe' => 'Ingat Saya',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = $this->getCustomer();
            if (!$customer || !$customer->validatePassword($this->password)) {
                $this->addError($attribute, 'Email atau password salah.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $customer = $this->getCustomer();
            if ($customer) {
                // Simpan customer ke session (bukan Yii::$app->user karena itu untuk admin)
                Yii::$app->session->set('customer_id', $customer->id);
                Yii::$app->session->set('customer_name', $customer->name);
                Yii::$app->session->set('customer_email', $customer->email);
                return true;
            }
        }
        return false;
    }

    public function getCustomer()
    {
        if ($this->_customer === false) {
            $this->_customer = Customer::findByEmail($this->email);
        }
        return $this->_customer;
    }
}
