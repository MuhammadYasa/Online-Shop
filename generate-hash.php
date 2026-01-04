<?php
/**
 * Script untuk generate password hash
 * Jalankan: php generate-hash.php
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_ENV_DEV') or define('YII_ENV_DEV', true);

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

// Inisialisasi Yii application
$config = require(__DIR__ . '/config/console.php');
$application = new yii\console\Application($config);

// Generate password hash untuk "admin123"
$password = 'admin123';
$hash = Yii::$app->security->generatePasswordHash($password);
$authKey = Yii::$app->security->generateRandomString();

echo "Password: {$password}\n";
echo "Hash: {$hash}\n";
echo "Auth Key: {$authKey}\n\n";

echo "Copy SQL ini ke phpMyAdmin:\n\n";
echo "UPDATE user SET password_hash = '{$hash}', auth_key = '{$authKey}' WHERE username = 'admin';\n";
