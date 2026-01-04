<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Order Model - Pesanan
 */
class Order extends ActiveRecord
{
    public static function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return [
            [['customer_name', 'customer_email', 'customer_phone', 'shipping_address', 'payment_method'], 'required'],
            ['customer_email', 'email'],
            [['payment_method'], 'in', 'range' => ['cod', 'transfer']],
            [['payment_status'], 'in', 'range' => ['pending', 'paid', 'failed']],
            [['order_status'], 'in', 'range' => ['pending', 'processing', 'shipped', 'delivered', 'cancelled']],
            [['subtotal', 'shipping_cost', 'total'], 'number'],
            [['notes', 'payment_proof'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'customer_name' => 'Nama Penerima',
            'customer_email' => 'Email',
            'customer_phone' => 'No. Telepon',
            'shipping_address' => 'Alamat Pengiriman',
            'payment_method' => 'Metode Pembayaran',
            'notes' => 'Catatan',
            'payment_proof' => 'Bukti Transfer',
        ];
    }

    // Relasi
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    // Generate order number
    public function beforeValidate()
    {
        if ($this->isNewRecord && !$this->order_number) {
            $this->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        }
        return parent::beforeValidate();
    }

    // Helper: Payment method label
    public function getPaymentMethodLabel()
    {
        $labels = [
            'cod' => 'Cash on Delivery (COD)',
            'transfer' => 'Transfer Bank',
        ];
        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    // Helper: Payment status badge
    public function getPaymentStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Menunggu Pembayaran</span>',
            'paid' => '<span class="badge bg-success">Lunas</span>',
            'failed' => '<span class="badge bg-danger">Gagal</span>',
        ];
        return $badges[$this->payment_status] ?? $this->payment_status;
    }

    // Helper: Order status badge
    public function getOrderStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge bg-secondary">Pending</span>',
            'processing' => '<span class="badge bg-info">Diproses</span>',
            'shipped' => '<span class="badge bg-primary">Dikirim</span>',
            'delivered' => '<span class="badge bg-success">Diterima</span>',
            'cancelled' => '<span class="badge bg-danger">Dibatalkan</span>',
        ];
        return $badges[$this->order_status] ?? $this->order_status;
    }
}
