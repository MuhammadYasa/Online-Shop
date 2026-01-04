-- ============================================
-- UPDATE PASSWORD ADMIN YANG VALID
-- ============================================
-- Username: admin
-- Password: admin123
-- 
-- CARA RUNNING:
-- 1. Buka http://localhost/phpmyadmin
-- 2. Pilih database "olshop" di sidebar kiri
-- 3. Klik tab "SQL"
-- 4. Copy paste SQL ini
-- 5. Klik "Go"
-- ============================================

USE olshop;

-- Update password admin dengan hash yang valid
UPDATE user SET 
    password_hash = '$2y$13$DI4bG0pkySX.VIrwGVt6V.x/SGUbUtJjqJ3PIifEXgkbjYdauDoRu',
    auth_key = 'Lxiipq-mIJgjig6WDY6WPoYKTz370iLq'
WHERE username = 'admin';

-- Verifikasi update berhasil
SELECT id, username, created_at, updated_at 
FROM user 
WHERE username = 'admin';
