# 📘 CARA RUNNING SQL DI VS CODE - TUTORIAL LENGKAP

## 🎯 APA YANG AKAN DIPELAJARI?

Panduan ini akan mengajarkan Anda cara menjalankan file SQL di VS Code untuk membuat database online shop. Ada **3 metode** yang bisa dipilih sesuai preferensi Anda.

---

## 🔧 METODE 1: MENGGUNAKAN EXTENSION "MySQL" (RECOMMENDED)

### Langkah 1: Install Extension MySQL di VS Code

1. **Buka VS Code**
2. **Klik icon Extension** di sidebar kiri (atau tekan `Ctrl+Shift+X`)
3. **Ketik di search box**: `MySQL`
4. **Pilih extension**: "MySQL" by **cweijan** (yang paling populer, ada logo MySQL)
5. **Klik tombol "Install"**
6. **Tunggu sampai selesai** install

### Langkah 2: Koneksi ke Database MySQL

1. **Klik icon "Database"** yang muncul di sidebar kiri (setelah install extension)
2. **Klik tombol "+" (Create Connection)**
3. **Isi form koneksi**:
   ```
   Host: localhost
   Port: 3306
   Username: root
   Password: (kosongkan jika tidak ada password)
   ```
4. **Klik "Connect"**
5. **Koneksi berhasil!** Anda akan lihat daftar database di sidebar

### Langkah 3: Jalankan File SQL

1. **Buka file**: `database/olshop_setup.sql` (double click di VS Code)
2. **Klik kanan** di dalam editor SQL
3. **Pilih**: `Run MySQL Query` atau tekan `Ctrl+Shift+Q`
4. **Pilih koneksi**: pilih koneksi yang sudah dibuat tadi
5. **Tunggu proses** (loading akan muncul)
6. **Selesai!** Database `olshop` sudah dibuat

### Langkah 4: Cek Hasil

1. **Refresh database list** (klik icon refresh di sidebar Database)
2. **Expand database "olshop"**
3. **Anda akan lihat 4 tabel**:
   - category
   - product
   - tag
   - product_tag

---

## 🔧 METODE 2: MENGGUNAKAN TERMINAL VS CODE + MySQL CLI

### Langkah 1: Buka Terminal di VS Code

1. **Tekan**: `Ctrl + `` (backtick/tanda petik terbalik)
2. **Atau menu**: Terminal → New Terminal
3. **Terminal akan muncul** di bawah editor

### Langkah 2: Masuk ke MySQL CLI

#### Jika pakai XAMPP (Windows):

```bash
cd C:\xampp\mysql\bin
mysql -u root -p
```

#### Jika pakai MySQL langsung (terinstall global):

```bash
mysql -u root -p
```

**Tekan Enter** jika diminta password (default XAMPP tidak ada password)

### Langkah 3: Jalankan File SQL

Setelah masuk ke MySQL CLI (ada prompt `mysql>`):

```sql
SOURCE C:/xampp/htdocs/olshop/database/olshop_setup.sql;
```

**⚠️ PENTING**:

- Gunakan **forward slash** (`/`) bukan backslash (`\`)
- Path harus **absolute** (full path dari C:)

### Langkah 4: Cek Hasil

```sql
SHOW DATABASES;
USE olshop;
SHOW TABLES;
SELECT * FROM category;
```

**Keluar dari MySQL CLI**:

```sql
EXIT;
```

---

## 🔧 METODE 3: COPY-PASTE KE phpMyAdmin (PALING MUDAH)

### Langkah 1: Buka File SQL di VS Code

1. **Buka file**: `database/olshop_setup.sql`
2. **Tekan**: `Ctrl+A` (select all)
3. **Tekan**: `Ctrl+C` (copy)

### Langkah 2: Buka phpMyAdmin

1. **Start XAMPP** (Apache dan MySQL harus running)
2. **Buka browser**, ketik: `http://localhost/phpmyadmin`
3. **Klik tab "SQL"** di bagian atas

### Langkah 3: Paste dan Run

1. **Paste** kode SQL yang sudah dicopy (Ctrl+V)
2. **Scroll ke bawah**, klik tombol **"Go"** atau **"Kirim"**
3. **Tunggu proses**
4. **Sukses!** Akan muncul pesan "Query OK"

### Langkah 4: Cek Hasil

1. **Klik "olshop"** di sidebar kiri
2. **Lihat 4 tabel** yang sudah dibuat:
   - category (5 data)
   - product (13 data)
   - tag (5 data)
   - product_tag (banyak relasi)

---

## 📊 PENJELASAN DETAIL SETIAP BAGIAN SQL

### 1️⃣ DROP DATABASE (Baris 13-14)

```sql
DROP DATABASE IF EXISTS olshop;
```

**Penjelasan**:

- `DROP DATABASE` = **hapus database**
- `IF EXISTS` = **hanya hapus jika sudah ada** (tidak error jika belum ada)
- ⚠️ **HATI-HATI**: Ini akan **menghapus semua data** di database olshop!

**Kapan dipakai**?

- Saat install fresh (pertama kali)
- Saat ingin reset database ke kondisi awal
- Saat development/testing

---

### 2️⃣ CREATE DATABASE (Baris 16-17)

```sql
CREATE DATABASE olshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Penjelasan**:

- `CREATE DATABASE olshop` = **buat database baru** dengan nama "olshop"
- `CHARACTER SET utf8mb4` = **encoding karakter** yang support:
  - Emoji 😊💖
  - Karakter Asia (中文, 日本語, 한국어)
  - Karakter special lainnya
- `COLLATE utf8mb4_unicode_ci` = **aturan sorting** (case-insensitive)
  - "Budi" = "budi" (dianggap sama)

**Kenapa utf8mb4 bukan utf8?**

- `utf8` lama di MySQL hanya support 3 byte (tidak bisa emoji)
- `utf8mb4` support 4 byte (bisa emoji dan karakter lengkap)

---

### 3️⃣ USE DATABASE (Baris 20)

```sql
USE olshop;
```

**Penjelasan**:

- `USE` = **pilih database** yang akan digunakan
- Semua query setelah ini akan masuk ke database "olshop"
- Seperti "buka folder olshop" sebelum membuat file

---

### 4️⃣ CREATE TABLE CATEGORY (Baris 28-39)

```sql
CREATE TABLE `category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  INDEX `idx_name` (`name`)
) ENGINE=InnoDB;
```

**Penjelasan per bagian**:

#### a. Kolom `id`

```sql
`id` INT(11) NOT NULL AUTO_INCREMENT
```

- `INT(11)` = **tipe data integer** (angka bulat) dengan lebar tampilan 11 digit
- `NOT NULL` = **tidak boleh kosong** (wajib ada nilai)
- `AUTO_INCREMENT` = **otomatis naik** (1, 2, 3, 4, ...)
  - Kita tidak perlu isi manual
  - Database yang isi otomatis

#### b. Kolom `name`

```sql
`name` VARCHAR(100) NOT NULL
```

- `VARCHAR(100)` = **tipe data string** (text) dengan **maksimal 100 karakter**
- `NOT NULL` = **wajib diisi**
- Contoh: "Elektronik", "Fashion"

#### c. Kolom `description`

```sql
`description` TEXT NULL
```

- `TEXT` = **tipe data text panjang** (bisa sampai 65,535 karakter)
- `NULL` = **boleh kosong** (opsional)

#### d. Kolom `created_at`

```sql
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
```

- `DATETIME` = **tipe data tanggal + waktu** (format: 2026-01-03 14:30:45)
- `DEFAULT CURRENT_TIMESTAMP` = **otomatis isi tanggal sekarang** saat data dibuat
- Kita tidak perlu isi manual!

#### e. Kolom `updated_at`

```sql
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

- `ON UPDATE CURRENT_TIMESTAMP` = **otomatis update tanggal** setiap kali data diubah
- Contoh:
  - Data dibuat: 2026-01-03 10:00:00
  - Data diedit: otomatis jadi 2026-01-03 14:30:00

#### f. PRIMARY KEY

```sql
PRIMARY KEY (`id`)
```

- **PRIMARY KEY** = **kunci utama** tabel
- Setiap tabel **wajib punya** primary key
- Sifat:
  - **UNIQUE** (tidak boleh duplikat)
  - **NOT NULL** (tidak boleh kosong)
  - Untuk **identifikasi unik** setiap record

#### g. INDEX

```sql
INDEX `idx_name` (`name`)
```

- **INDEX** = **indeks** untuk **mempercepat pencarian**
- Seperti "daftar isi" di buku
- Contoh:
  - Tanpa index: MySQL harus scan 1000 baris
  - Dengan index: MySQL langsung tahu lokasinya
- **Kapan pakai?** Kolom yang sering di-WHERE atau ORDER BY

#### h. ENGINE=InnoDB

```sql
ENGINE=InnoDB
```

- **InnoDB** = engine database MySQL yang support:
  - **FOREIGN KEY** (relasi antar tabel)
  - **TRANSACTION** (rollback jika error)
  - **ACID compliance** (data konsisten)
- Engine lain: MyISAM (lebih cepat tapi tidak support foreign key)

---

### 5️⃣ FOREIGN KEY CONSTRAINT (Baris 75-78)

```sql
CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`)
  REFERENCES `category` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE
```

**Penjelasan**:

#### a. FOREIGN KEY

- **FOREIGN KEY** = **kunci asing** (kunci yang menunjuk ke tabel lain)
- Fungsi: **menjaga integritas data**
- Contoh:
  - Product punya `category_id = 5`
  - Tapi di tabel category tidak ada `id = 5`
  - **Error!** Foreign key mencegah ini

#### b. REFERENCES

```sql
REFERENCES `category` (`id`)
```

- **REFERENCES** = **menunjuk ke tabel lain**
- `category` (`id`) = tabel category, kolom id
- Artinya: `category_id` di tabel product **harus ada** di tabel category

#### c. ON DELETE CASCADE

```sql
ON DELETE CASCADE
```

- **Apa yang terjadi** jika **category dihapus**?
- `CASCADE` = **ikut hapus semua product** di kategori itu
- Pilihan lain:
  - `RESTRICT` = **tidak bisa hapus** jika masih ada product
  - `SET NULL` = `category_id` jadi NULL

**Contoh**:

```
Category "Elektronik" (id=1) punya 10 product
Jika category "Elektronik" dihapus → 10 product ikut terhapus
```

#### d. ON UPDATE CASCADE

```sql
ON UPDATE CASCADE
```

- **Apa yang terjadi** jika **id category diubah**?
- `CASCADE` = **ikut update** semua `category_id` di product
- Contoh:
  ```
  Category id 1 → diubah jadi 100
  Semua product category_id=1 → otomatis jadi 100
  ```

---

### 6️⃣ TABEL PENGHUBUNG (Product_Tag) - Baris 98-121

```sql
CREATE TABLE `product_tag` (
  `product_id` INT(11) NOT NULL,
  `tag_id` INT(11) NOT NULL,
  PRIMARY KEY (`product_id`, `tag_id`)
)
```

**Penjelasan**:

#### a. Kenapa Butuh Tabel Penghubung?

**Skenario Many-to-Many**:

```
Product "Laptop Asus" → Tag: Promo, Best Seller, Limited Stock
Product "HP Samsung" → Tag: New Arrival, Recommended

Tag "Promo" → Product: Laptop Asus, Sneakers
Tag "Best Seller" → Product: Laptop Asus, Kaos Polos, Kopi
```

**Tidak bisa disimpan langsung** di tabel product karena:

- 1 produk bisa punya banyak tag
- 1 tag bisa untuk banyak produk

**Solusi**: Tabel penghubung!

#### b. Composite Primary Key

```sql
PRIMARY KEY (`product_id`, `tag_id`)
```

- **Composite** = **gabungan 2 kolom** jadi primary key
- Artinya: **kombinasi** product_id + tag_id **harus unik**
- Contoh yang **VALID**:
  ```
  (product_id=1, tag_id=1) ✅
  (product_id=1, tag_id=2) ✅
  (product_id=2, tag_id=1) ✅
  ```
- Contoh yang **INVALID** (duplikat):
  ```
  (product_id=1, tag_id=1) ✅
  (product_id=1, tag_id=1) ❌ Error! Duplikat
  ```

**Manfaat**: 1 produk tidak bisa punya tag yang sama 2 kali

---

### 7️⃣ INSERT DATA (Baris 128-214)

```sql
INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Elektronik', 'Produk elektronik, gadget, dan perangkat teknologi'),
(2, 'Fashion', 'Pakaian, sepatu, dan aksesoris fashion');
```

**Penjelasan**:

- `INSERT INTO` = **masukkan data** ke tabel
- `category` = nama tabel
- `(`id`, `name`, `description`)` = kolom yang akan diisi
- `VALUES (...)` = nilai yang dimasukkan
- **Bisa insert banyak baris sekaligus** dengan koma

**Tips**:

- Kolom dengan `AUTO_INCREMENT` bisa **dikosongkan** atau **diisi manual**
- Kolom dengan `DEFAULT` bisa **tidak disebutkan**

---

### 8️⃣ QUERY TESTING (Baris 217-249)

#### a. JOIN untuk relasi One-to-Many

```sql
SELECT
    p.name AS produk,
    c.name AS kategori,
    p.price
FROM product p
INNER JOIN category c ON c.id = p.category_id
ORDER BY p.id;
```

**Penjelasan**:

- `p` = **alias** untuk tabel product (singkatan)
- `INNER JOIN` = **gabungkan** tabel product dan category
- `ON c.id = p.category_id` = **kondisi join** (kunci penghubung)
- **Hasil**: Daftar produk dengan nama kategorinya

**Jenis JOIN**:

- `INNER JOIN` = hanya data yang **ada relasi** di kedua tabel
- `LEFT JOIN` = ambil **semua dari kiri**, meski tidak ada relasi
- `RIGHT JOIN` = ambil **semua dari kanan**, meski tidak ada relasi

#### b. GROUP_CONCAT untuk Many-to-Many

```sql
SELECT
    p.name AS produk,
    GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
FROM product p
LEFT JOIN product_tag pt ON pt.product_id = p.id
LEFT JOIN tag t ON t.id = pt.tag_id
GROUP BY p.id, p.name;
```

**Penjelasan**:

- `GROUP_CONCAT` = **gabungkan banyak baris jadi 1 string**
- `SEPARATOR ', '` = pemisah pakai koma
- **Hasil**:
  ```
  Laptop Asus → "Promo, Best Seller, Limited Stock"
  HP Samsung → "New Arrival, Recommended"
  ```

---

## ✅ CHECKLIST SETELAH RUNNING SQL

Pastikan:

- ✅ Database `olshop` sudah terbuat
- ✅ Ada 4 tabel: category, product, tag, product_tag
- ✅ Tabel category ada 5 data
- ✅ Tabel product ada 13 data
- ✅ Tabel tag ada 5 data
- ✅ Tabel product_tag ada banyak relasi

**Cara cek**:

```sql
USE olshop;
SELECT COUNT(*) FROM category;  -- Harus 5
SELECT COUNT(*) FROM product;   -- Harus 13
SELECT COUNT(*) FROM tag;       -- Harus 5
SELECT COUNT(*) FROM product_tag; -- Harus banyak
```

---

## 🎓 KONSEP PENTING UNTUK INTERVIEW

### Q1: Apa perbedaan VARCHAR dan TEXT?

**Jawaban**:

- `VARCHAR(n)` = text dengan **panjang maksimal** yang ditentukan (1-65,535)
  - **Index lebih cepat**
  - Butuh prefix untuk index (misal: VARCHAR(100))
  - Cocok untuk: nama, email, judul
- `TEXT` = text panjang **tanpa batas** (sampai 65,535 karakter)
  - **Tidak bisa di-index** langsung
  - Cocok untuk: deskripsi, konten artikel

### Q2: Apa itu ACID dalam database?

**Jawaban**:

- **A**tomicity = Transaksi **all or nothing** (semua sukses atau semua gagal)
- **C**onsistency = Data selalu dalam **kondisi valid**
- **I**solation = Transaksi **tidak saling ganggu**
- **D**urability = Data yang sudah commit **tidak hilang**

### Q3: Kapan pakai INDEX?

**Jawaban**:

- Kolom yang **sering di-WHERE** (misal: email untuk login)
- Kolom yang **sering di-ORDER BY** (misal: created_at)
- **FOREIGN KEY** (untuk percepat JOIN)
- **Jangan berlebihan** karena index bikin INSERT/UPDATE lambat

### Q4: Apa perbedaan PRIMARY KEY dan UNIQUE KEY?

**Jawaban**:

- **PRIMARY KEY**:
  - **Tidak boleh NULL**
  - **Hanya 1** per tabel
  - Untuk **identifikasi record**
- **UNIQUE KEY**:
  - **Boleh NULL** (tapi hanya 1 NULL)
  - **Bisa banyak** per tabel
  - Untuk **data yang harus unik** (misal: email, username)

### Q5: Kenapa pakai InnoDB bukan MyISAM?

**Jawaban**:

- **InnoDB** (Recommended):
  - ✅ Support **FOREIGN KEY**
  - ✅ Support **TRANSACTION** (ACID)
  - ✅ **Row-level locking** (concurrency lebih baik)
  - ❌ Sedikit lebih lambat
- **MyISAM**:
  - ✅ **Lebih cepat** untuk read-only
  - ❌ Tidak support foreign key
  - ❌ Tidak support transaction
  - ❌ Table-level locking

---

## 🚀 SELANJUTNYA

Setelah database siap, lanjut ke:

1. ✅ **Buat Model** (Category, Product, Tag, ProductTag)
2. ⏭️ **Buat Controller** (CRUD logic)
3. ⏭️ **Buat View** (tampilan form dan list)
4. ⏭️ **Buat Admin Dashboard**

Ketik **"lanjut step 2"** untuk tutorial Controller dan View!

---

## ❓ TROUBLESHOOTING

### Error: "Access denied for user 'root'@'localhost'"

**Solusi**:

- Pastikan **XAMPP MySQL sudah running**
- Cek password (default XAMPP tidak ada password)
- Gunakan username dan password yang benar

### Error: "Table already exists"

**Solusi**:

- Hapus database dulu: `DROP DATABASE olshop;`
- Atau edit SQL, hapus baris `CREATE TABLE` yang sudah ada

### Error: "Cannot add foreign key constraint"

**Solusi**:

- Pastikan **tipe data sama** (INT vs INT, bukan INT vs VARCHAR)
- Pastikan **kolom referensi** sudah ada dan **PRIMARY KEY**
- Pastikan **ENGINE=InnoDB** di kedua tabel

---

**🎉 Selamat! Database Anda sudah siap digunakan untuk tutorial Yii2 CRUD!**
