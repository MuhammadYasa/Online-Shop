-- ================================================================
-- TUTORIAL YII2 ONLINE SHOP - DATABASE SETUP
-- ================================================================
-- File: olshop_setup.sql
-- Deskripsi: Script untuk membuat database online shop dengan relasi
--            One-to-Many dan Many-to-Many
-- Cara Run: Gunakan MySQL Extension di VS Code atau phpMyAdmin
-- ================================================================

-- ----------------------------------------------------------------
-- STEP 1: BUAT DATABASE BARU
-- ----------------------------------------------------------------
-- DROP DATABASE jika sudah ada (hati-hati, ini akan hapus semua data!)
DROP DATABASE IF EXISTS olshop;

-- CREATE DATABASE dengan encoding UTF8MB4 (support emoji dan karakter internasional)
CREATE DATABASE olshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Pilih database yang akan digunakan
USE olshop;

-- ----------------------------------------------------------------
-- STEP 2: BUAT TABEL CATEGORY (Kategori Produk)
-- ----------------------------------------------------------------
-- Tabel ini menyimpan kategori produk seperti: Elektronik, Fashion, dll
-- Relasi: One-to-Many dengan Product (1 Category punya banyak Product)

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID unik kategori (Primary Key)',
  `name` VARCHAR(100) NOT NULL COMMENT 'Nama kategori (wajib diisi)',
  `description` TEXT NULL COMMENT 'Deskripsi kategori (opsional)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal dibuat (otomatis)',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Tanggal diupdate (otomatis)',
  
  PRIMARY KEY (`id`),
  INDEX `idx_name` (`name`) COMMENT 'Index untuk percepat pencarian berdasarkan nama'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel master kategori produk';

-- ----------------------------------------------------------------
-- STEP 3: BUAT TABEL TAG (Label/Tag Produk)
-- ----------------------------------------------------------------
-- Tabel ini menyimpan tag seperti: Promo, Best Seller, New Arrival
-- Relasi: Many-to-Many dengan Product (via tabel product_tag)

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID unik tag (Primary Key)',
  `name` VARCHAR(50) NOT NULL COMMENT 'Nama tag (wajib diisi)',
  `color` VARCHAR(20) NOT NULL DEFAULT '#007bff' COMMENT 'Warna tag dalam format HEX (misal: #ff0000 untuk merah)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal dibuat (otomatis)',
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) COMMENT 'Nama tag harus unik (tidak boleh duplikat)',
  INDEX `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel master tag produk';

-- ----------------------------------------------------------------
-- STEP 4: BUAT TABEL PRODUCT (Produk)
-- ----------------------------------------------------------------
-- Tabel ini menyimpan data produk yang dijual
-- Relasi: 
--   - Many-to-One dengan Category (banyak Product -> 1 Category)
--   - Many-to-Many dengan Tag (via tabel product_tag)

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID unik produk (Primary Key)',
  `category_id` INT(11) NOT NULL COMMENT 'ID kategori produk (Foreign Key ke tabel category)',
  `name` VARCHAR(200) NOT NULL COMMENT 'Nama produk (wajib diisi)',
  `description` TEXT NULL COMMENT 'Deskripsi produk (opsional)',
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Harga produk dalam Rupiah (maksimal 99.999.999,99)',
  `stock` INT(11) NOT NULL DEFAULT 0 COMMENT 'Jumlah stok barang',
  `image` VARCHAR(255) NULL COMMENT 'Nama file gambar produk (opsional)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal dibuat (otomatis)',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Tanggal diupdate (otomatis)',
  
  PRIMARY KEY (`id`),
  INDEX `idx_category` (`category_id`) COMMENT 'Index untuk percepat pencarian berdasarkan kategori',
  INDEX `idx_name` (`name`),
  INDEX `idx_price` (`price`) COMMENT 'Index untuk percepat sorting berdasarkan harga',
  
  -- FOREIGN KEY CONSTRAINT (Kunci untuk relasi)
  -- Jika category dihapus, semua product di kategori itu juga ikut terhapus (CASCADE)
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) 
    REFERENCES `category` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel master produk';

-- ----------------------------------------------------------------
-- STEP 5: BUAT TABEL PRODUCT_TAG (Tabel Penghubung Many-to-Many)
-- ----------------------------------------------------------------
-- Tabel ini menghubungkan Product dan Tag
-- Contoh: Product "Laptop Asus" (id=1) punya Tag "Promo" (id=1) dan "Best Seller" (id=2)
-- Maka ada 2 record: (1,1) dan (1,2)

DROP TABLE IF EXISTS `product_tag`;

CREATE TABLE `product_tag` (
  `product_id` INT(11) NOT NULL COMMENT 'ID produk (Foreign Key ke tabel product)',
  `tag_id` INT(11) NOT NULL COMMENT 'ID tag (Foreign Key ke tabel tag)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal dibuat (otomatis)',
  
  -- PRIMARY KEY adalah kombinasi product_id dan tag_id
  -- Artinya: 1 produk tidak bisa punya tag yang sama 2 kali
  PRIMARY KEY (`product_id`, `tag_id`),
  
  INDEX `idx_tag` (`tag_id`) COMMENT 'Index untuk percepat pencarian berdasarkan tag',
  
  -- FOREIGN KEY ke tabel product
  -- Jika product dihapus, relasi di tabel ini juga ikut terhapus (CASCADE)
  CONSTRAINT `fk_pt_product` FOREIGN KEY (`product_id`) 
    REFERENCES `product` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE,
  
  -- FOREIGN KEY ke tabel tag
  -- Jika tag dihapus, relasi di tabel ini juga ikut terhapus (CASCADE)
  CONSTRAINT `fk_pt_tag` FOREIGN KEY (`tag_id`) 
    REFERENCES `tag` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel penghubung product dan tag (many-to-many)';

-- ================================================================
-- DATA AWAL (SEED DATA)
-- ================================================================

-- ----------------------------------------------------------------
-- Insert Data CATEGORY
-- ----------------------------------------------------------------
INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Elektronik', 'Produk elektronik, gadget, dan perangkat teknologi'),
(2, 'Fashion', 'Pakaian, sepatu, dan aksesoris fashion'),
(3, 'Makanan & Minuman', 'Makanan, minuman, dan kebutuhan dapur'),
(4, 'Olahraga', 'Peralatan olahraga dan fitness'),
(5, 'Kecantikan', 'Produk perawatan kulit dan kosmetik');

-- ----------------------------------------------------------------
-- Insert Data TAG
-- ----------------------------------------------------------------
INSERT INTO `tag` (`id`, `name`, `color`) VALUES
(1, 'Promo', '#dc3545'),           -- Merah untuk promo
(2, 'Best Seller', '#ffc107'),     -- Kuning untuk best seller
(3, 'New Arrival', '#28a745'),     -- Hijau untuk produk baru
(4, 'Recommended', '#17a2b8'),     -- Biru untuk rekomendasi
(5, 'Limited Stock', '#fd7e14');   -- Orange untuk stok terbatas

-- ----------------------------------------------------------------
-- Insert Data PRODUCT
-- ----------------------------------------------------------------
INSERT INTO `product` (`id`, `category_id`, `name`, `description`, `price`, `stock`, `image`) VALUES
-- Elektronik
(1, 1, 'Laptop ASUS ROG Strix G15', 'Laptop gaming dengan processor Intel Core i7 Gen 12, RAM 16GB, SSD 512GB, RTX 3060', 15000000.00, 10, 'laptop-asus-rog.jpg'),
(2, 1, 'HP Samsung Galaxy S23', 'Smartphone flagship dengan kamera 50MP, RAM 8GB, Storage 256GB', 12000000.00, 25, 'samsung-s23.jpg'),
(3, 1, 'Smart TV LG 43 inch', 'Smart TV 4K UHD dengan webOS, built-in Netflix dan YouTube', 5500000.00, 15, 'tv-lg-43.jpg'),
(4, 1, 'Earbuds Sony WF-1000XM4', 'TWS earbuds dengan Active Noise Cancellation terbaik', 3200000.00, 30, 'sony-earbuds.jpg'),

-- Fashion
(5, 2, 'Kaos Polos Premium Cotton Combed 30s', 'Kaos polos berbahan cotton combed 30s, nyaman dan adem', 75000.00, 100, 'kaos-polos.jpg'),
(6, 2, 'Jaket Hoodie Fleece', 'Jaket hoodie berbahan fleece tebal, cocok untuk musim hujan', 150000.00, 50, 'hoodie-fleece.jpg'),
(7, 2, 'Sepatu Sneakers Casual', 'Sepatu sneakers casual untuk pria, nyaman untuk aktivitas sehari-hari', 250000.00, 40, 'sneakers-casual.jpg'),

-- Makanan & Minuman
(8, 3, 'Kopi Arabica Premium 250gr', 'Biji kopi arabica pilihan dari dataran tinggi, medium roast', 85000.00, 200, 'kopi-arabica.jpg'),
(9, 3, 'Madu Murni 500ml', 'Madu murni 100% tanpa campuran, dari lebah hutan', 120000.00, 75, 'madu-murni.jpg'),

-- Olahraga
(10, 4, 'Matras Yoga Anti Slip', 'Matras yoga tebal 8mm dengan permukaan anti slip', 180000.00, 60, 'matras-yoga.jpg'),
(11, 4, 'Dumbbell Set 5kg', 'Set dumbbell 5kg sepasang dengan coating rubber', 200000.00, 35, 'dumbbell-5kg.jpg'),

-- Kecantikan
(12, 5, 'Serum Vitamin C 30ml', 'Serum wajah dengan kandungan vitamin C untuk mencerahkan kulit', 150000.00, 80, 'serum-vitc.jpg'),
(13, 5, 'Sunscreen SPF 50 PA++++', 'Sunscreen dengan perlindungan maksimal dari sinar UV', 95000.00, 120, 'sunscreen-spf50.jpg');

-- ----------------------------------------------------------------
-- Insert Data PRODUCT_TAG (Relasi Many-to-Many)
-- ----------------------------------------------------------------
-- Format: (product_id, tag_id)

INSERT INTO `product_tag` (`product_id`, `tag_id`) VALUES
-- Laptop ASUS ROG: Promo + Best Seller + Limited Stock
(1, 1), (1, 2), (1, 5),

-- HP Samsung: New Arrival + Recommended
(2, 3), (2, 4),

-- Smart TV LG: Promo + Best Seller
(3, 1), (3, 2),

-- Earbuds Sony: Recommended
(4, 4),

-- Kaos Polos: Best Seller + Recommended
(5, 2), (5, 4),

-- Hoodie: New Arrival
(6, 3),

-- Sneakers: Promo + Limited Stock
(7, 1), (7, 5),

-- Kopi Arabica: Best Seller + Recommended
(8, 2), (8, 4),

-- Madu Murni: New Arrival + Recommended
(9, 3), (9, 4),

-- Matras Yoga: Promo
(10, 1),

-- Dumbbell: Limited Stock
(11, 5),

-- Serum Vitamin C: Best Seller + New Arrival
(12, 2), (12, 3),

-- Sunscreen: Recommended
(13, 4);

-- ================================================================
-- QUERY TESTING (Optional - untuk cek hasil)
-- ================================================================

-- Cek semua kategori dengan jumlah produknya
SELECT 
    c.id,
    c.name AS kategori,
    COUNT(p.id) AS jumlah_produk
FROM category c
LEFT JOIN product p ON p.category_id = c.id
GROUP BY c.id, c.name
ORDER BY c.id;

-- Cek semua produk dengan kategori dan harganya
SELECT 
    p.id,
    p.name AS produk,
    c.name AS kategori,
    CONCAT('Rp ', FORMAT(p.price, 0, 'id_ID')) AS harga,
    p.stock
FROM product p
INNER JOIN category c ON c.id = p.category_id
ORDER BY p.id;

-- Cek produk dengan tag-nya (relasi many-to-many)
SELECT 
    p.name AS produk,
    GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
FROM product p
LEFT JOIN product_tag pt ON pt.product_id = p.id
LEFT JOIN tag t ON t.id = pt.tag_id
GROUP BY p.id, p.name
ORDER BY p.id;

-- Cek tag dengan jumlah produknya
SELECT 
    t.id,
    t.name AS tag,
    t.color,
    COUNT(pt.product_id) AS jumlah_produk
FROM tag t
LEFT JOIN product_tag pt ON pt.tag_id = t.id
GROUP BY t.id, t.name, t.color
ORDER BY jumlah_produk DESC;

-- ================================================================
-- SELESAI!
-- ================================================================
-- Database olshop sudah siap digunakan untuk tutorial Yii2 CRUD
-- ================================================================
