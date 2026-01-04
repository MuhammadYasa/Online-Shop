<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Model Category (Kategori Produk)
 * 
 * Properti Database:
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * 
 * Relasi:
 * @property Product[] $products - Semua produk dalam kategori ini
 */
class Category extends ActiveRecord
{
    /**
     * Nama tabel di database
     * Fungsi ini memberitahu Yii tabel mana yang digunakan
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * Aturan validasi data
     * Seperti "aturan main" sebelum data disimpan
     */
    public function rules()
    {
        return [
            // name wajib diisi (required)
            [['name'], 'required', 'message' => 'Nama kategori harus diisi'],
            
            // name maksimal 100 karakter
            [['name'], 'string', 'max' => 100],
            
            // description opsional, bisa kosong
            [['description'], 'string'],
        ];
    }

    /**
     * Label untuk form (nama field yang ditampilkan)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nama Kategori',
            'description' => 'Deskripsi',
            'created_at' => 'Dibuat Tanggal',
            'updated_at' => 'Diupdate Tanggal',
        ];
    }

    /**
     * RELASI ONE-TO-MANY
     * 1 Kategori punya banyak Produk
     * 
     * Cara kerja: Category "Elektronik" → ambil semua Product yang category_id = id category ini
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    /**
     * Hitung jumlah produk dalam kategori ini
     */
    public function getProductCount()
    {
        return $this->getProducts()->count();
    }
}