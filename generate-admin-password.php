<?php
/**
 * Script untuk Generate Password Hash untuk User Admin
 * Jalankan: php generate-admin-password.php
 */

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

// Minimal Yii app config
$config = require(__DIR__ . '/config/console.php');
$application = new yii\console\Application($config);

// Password yang ingin di-hash
$password = 'admin123';

// Generate password hash menggunakan Yii security
$passwordHash = Yii::$app->security->generatePasswordHash($password);
$authKey = Yii::$app->security->generateRandomString();

echo "\n";
echo "==============================================\n";
echo "PASSWORD HASH GENERATOR\n";
echo "==============================================\n";
echo "Username      : admin\n";
echo "Password      : $password\n";
echo "Password Hash : $passwordHash\n";
echo "Auth Key      : $authKey\n";
echo "==============================================\n\n";
echo "SQL Query untuk Update:\n\n";
echo "UPDATE user SET \n";
echo "    password_hash = '$passwordHash',\n";
echo "    auth_key = '$authKey'\n";
echo "WHERE username = 'admin';\n\n";
echo "==============================================\n";
echo "Copy SQL di atas dan jalankan di phpMyAdmin!\n";
echo "==============================================\n\n";
