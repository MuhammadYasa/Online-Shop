<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * OrderItem Model - Item dalam pesanan
 */
class OrderItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'order_items';
    }

    public function rules()
    {
        return [
            [['order_id', 'product_id', 'product_name', 'product_price', 'quantity', 'subtotal'], 'required'],
            [['quantity'], 'integer', 'min' => 1],
            [['product_price', 'subtotal'], 'number'],
        ];
    }

    // Relasi
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
