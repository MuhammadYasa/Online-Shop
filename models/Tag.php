<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Model Tag (Label Produk)
 * 
 * Properti Database:
 * @property int $id
 * @property string $name
 * @property string $color
 * @property string $created_at
 * 
 * Relasi:
 * @property Product[] $products - Semua produk dengan tag ini
 */
class Tag extends ActiveRecord
{
    public static function tableName()
    {
        return 'tag';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['color'], 'string', 'max' => 20],
            [['color'], 'default', 'value' => '#007bff'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nama Tag',
            'color' => 'Warna (Hex Code)',
            'created_at' => 'Dibuat Tanggal',
        ];
    }

    /**
     * RELASI MANY-TO-MANY (kebalikan dari Product)
     * Tag ↔ Produk
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->viaTable('product_tag', ['tag_id' => 'id']);
    }

    /**
     * Hitung berapa produk yang pakai tag ini
     */
    public function getProductCount()
    {
        return $this->getProducts()->count();
    }
}