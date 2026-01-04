<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Cart Model - Keranjang Belanja
 */
class Cart extends ActiveRecord
{
    public static function tableName()
    {
        return 'cart';
    }

    public function rules()
    {
        return [
            [['product_id', 'quantity'], 'required'],
            ['quantity', 'integer', 'min' => 1],
            [['customer_id', 'session_id'], 'safe'],
        ];
    }

    // Relasi
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    // Helper method: Total harga item
    public function getSubtotal()
    {
        return $this->product ? $this->product->price * $this->quantity : 0;
    }

    // Static method: Get cart items by session or customer
    public static function getItems($sessionId = null, $customerId = null)
    {
        $query = self::find()->with('product');
        
        if ($customerId) {
            $query->where(['customer_id' => $customerId]);
        } elseif ($sessionId) {
            $query->where(['session_id' => $sessionId, 'customer_id' => null]);
        }
        
        return $query->all();
    }

    // Static method: Add to cart
    public static function addItem($productId, $quantity = 1, $sessionId = null, $customerId = null)
    {
        // Cek apakah item sudah ada di cart
        $cart = self::findOne([
            'product_id' => $productId,
            'customer_id' => $customerId,
            'session_id' => $sessionId,
        ]);

        if ($cart) {
            // Update quantity jika sudah ada
            $cart->quantity += $quantity;
        } else {
            // Buat cart baru
            $cart = new self();
            $cart->product_id = $productId;
            $cart->quantity = $quantity;
            $cart->customer_id = $customerId;
            $cart->session_id = $sessionId;
        }

        return $cart->save();
    }

    // Static method: Update quantity
    public static function updateQuantity($cartId, $quantity)
    {
        $cart = self::findOne($cartId);
        if ($cart) {
            if ($quantity <= 0) {
                return $cart->delete();
            }
            $cart->quantity = $quantity;
            return $cart->save();
        }
        return false;
    }

    // Static method: Remove item
    public static function removeItem($cartId)
    {
        $cart = self::findOne($cartId);
        return $cart ? $cart->delete() : false;
    }

    // Static method: Clear cart
    public static function clearCart($sessionId = null, $customerId = null)
    {
        $deleted = 0;
        
        // Hapus cart berdasarkan customer_id jika ada
        if ($customerId) {
            $deleted += self::deleteAll(['customer_id' => $customerId]);
        }
        
        // Hapus juga cart berdasarkan session_id (untuk guest atau cart yang belum di-merge)
        if ($sessionId) {
            $deleted += self::deleteAll(['session_id' => $sessionId, 'customer_id' => null]);
        }
        
        return $deleted > 0;
    }

    // Static method: Get total cart
    public static function getTotal($sessionId = null, $customerId = null)
    {
        $items = self::getItems($sessionId, $customerId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item->getSubtotal();
        }
        return $total;
    }
}
