-- ============================================
-- SQL SETUP TABEL USER UNTUK AUTHENTICATION
-- ============================================

USE olshop;

-- 1. CREATE TABLE USER
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. INSERT DATA USER DEFAULT
-- Username: admin
-- Password: admin123

INSERT INTO `user` (`username`, `password_hash`, `auth_key`) VALUES
('admin', '$2y$13$DI4bG0pkySX.VIrwGVt6V.x/SGUbUtJjqJ3PIifEXgkbjYdauDoRu', 'Lxiipq-mIJgjig6WDY6WPoYKTz370iLq');

-- Password hash di atas sudah VALID dan bisa langsung digunakan untuk login
-- Username: admin
-- Password: admin123

-- CARA GENERATE PASSWORD HASH YANG BENAR:
-- 1. Buka file baru: test-hash.php di folder olshop
-- 2. Isi dengan:
--    <?php
--    require(__DIR__ . '/vendor/autoload.php');
--    $app = new yii\console\Application([
--        'id' => 'test',
--        'basePath' => __DIR__,
--    ]);
--    echo Yii::$app->security->generatePasswordHash('admin123');
-- 3. Jalankan: php test-hash.php
-- 4. Copy hasil hash dan update di tabel user

-- CARA RUNNING SQL DI VS CODE (phpMyAdmin):
-- 1. Buka http://localhost/phpmyadmin di browser
-- 2. Pilih database "olshop" di sidebar kiri
-- 3. Klik tab "SQL" di atas
-- 4. Copy paste SQL ini
-- 5. Klik tombol "Go" atau "Kirim"
-- 6. Jika sukses akan muncul pesan "Query berhasil dijalankan"

-- ATAU via MySQL Command Line:
-- 1. Buka CMD/Terminal
-- 2. Masuk ke MySQL: mysql -u root -p
-- 3. Ketik password MySQL (default XAMPP: kosong, tekan Enter)
-- 4. Copy paste SQL ini line by line
