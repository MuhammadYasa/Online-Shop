<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Model Product (Produk)
 * 
 * Properti Database:
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property int $stock
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 * 
 * Relasi:
 * @property Category $category - Kategori produk ini
 * @property Tag[] $tags - Semua tag yang dimiliki produk ini
 */
class Product extends ActiveRecord
{
    /**
     * Variable untuk menyimpan tag sementara (dari checkbox form)
     * Tidak disimpan di database, hanya untuk proses
     */
    public $tagIds = [];

    public static function tableName()
    {
        return 'product';
    }

    public function rules()
    {
        return [
            // Field wajib diisi
            [['category_id', 'name', 'price'], 'required'],
            
            // Field yang berupa angka integer
            [['category_id', 'stock'], 'integer'],
            
            // Price harus angka dengan 2 desimal, minimal 0
            [['price'], 'number', 'min' => 0],
            
            // String dengan panjang maksimal
            [['name'], 'string', 'max' => 200],
            [['image'], 'string', 'max' => 255],
            
            // Text panjang
            [['description'], 'string'],
            
            // Stock default 0 jika tidak diisi
            [['stock'], 'default', 'value' => 0],
            
            // tagIds untuk checkbox (array)
            [['tagIds'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Kategori',
            'name' => 'Nama Produk',
            'description' => 'Deskripsi',
            'price' => 'Harga (Rp)',
            'stock' => 'Stok',
            'image' => 'Gambar',
            'created_at' => 'Dibuat Tanggal',
            'updated_at' => 'Diupdate Tanggal',
            'tagIds' => 'Tag Produk',
        ];
    }

    /**
     * RELASI MANY-TO-ONE (Kebalikan One-to-Many)
     * Banyak Produk → 1 Kategori
     * 
     * Cara kerja: Product "Laptop" → ambil Category yang id = category_id produk ini
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * RELASI MANY-TO-MANY
     * Produk ↔ Tag (melalui tabel penghubung product_tag)
     * 
     * Cara kerja:
     * 1. Dari Product, cari di tabel product_tag yang product_id = id produk ini
     * 2. Ambil tag_id dari hasil step 1
     * 3. Cari di tabel tag yang id = tag_id dari step 2
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable('product_tag', ['product_id' => 'id']);
    }

    /**
     * Relasi ke tabel penghubung (untuk akses langsung)
     */
    public function getProductTags()
    {
        return $this->hasMany(ProductTag::class, ['product_id' => 'id']);
    }

    /**
     * Format harga untuk tampilan (15000000 → Rp 15.000.000)
     */
    public function getFormattedPrice()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Override method save untuk handle many-to-many tags
     * Dipanggil otomatis saat $product->save()
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        // Hapus semua tag lama
        ProductTag::deleteAll(['product_id' => $this->id]);
        
        // Simpan tag baru dari checkbox
        if (!empty($this->tagIds)) {
            foreach ($this->tagIds as $tagId) {
                $productTag = new ProductTag();
                $productTag->product_id = $this->id;
                $productTag->tag_id = $tagId;
                $productTag->save();
            }
        }
    }

    /**
     * Load tag IDs saat edit (untuk checked checkbox)
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // Ambil semua tag_id yang dimiliki produk ini
        $this->tagIds = ProductTag::find()
            ->select('tag_id')
            ->where(['product_id' => $this->id])
            ->column(); // Hasilkan array [1, 2, 3]
    }
}