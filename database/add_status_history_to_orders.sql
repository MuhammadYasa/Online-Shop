-- ================================================================
-- ADD STATUS HISTORY COLUMN TO ORDERS TABLE
-- ================================================================
-- File: add_status_history_to_orders.sql
-- Deskripsi: Menambahkan kolom untuk menyimpan riwayat perubahan status
-- Cara Run: Jalankan di phpMyAdmin atau MySQL Extension
-- ================================================================

USE olshop;

-- Tambahkan kolom status_history ke tabel orders
ALTER TABLE `orders` 
ADD COLUMN `status_history` TEXT NULL COMMENT 'Riwayat perubahan status dalam format JSON' 
AFTER `updated_at`;

-- Verifikasi struktur tabel
DESCRIBE orders;

-- ================================================================
-- PENJELASAN:
-- ================================================================
-- Kolom status_history akan menyimpan data dalam format JSON seperti:
-- [
--   {
--     "timestamp": "2026-01-04 10:30:00",
--     "order_status": "processing",
--     "payment_status": "paid",
--     "changes": "Status pesanan: pending → processing",
--     "admin": "admin"
--   }
-- ]
-- 
-- Setiap kali admin mengubah status, sistem akan menambahkan entry baru
-- ke dalam array JSON ini beserta informasi waktu dan admin yang mengubah.
-- ================================================================
