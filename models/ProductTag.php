<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Model ProductTag (Tabel Penghubung Many-to-Many)
 * 
 * Tabel ini menghubungkan Product dan Tag
 * 
 * Properti Database:
 * @property int $product_id
 * @property int $tag_id
 * @property string $created_at
 */
class ProductTag extends ActiveRecord
{
    public static function tableName()
    {
        return 'product_tag';
    }

    public function rules()
    {
        return [
            [['product_id', 'tag_id'], 'required'],
            [['product_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * Relasi ke Product
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Relasi ke Tag
     */
    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }
}