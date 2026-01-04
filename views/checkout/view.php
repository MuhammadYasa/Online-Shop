<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Order $order */

$this->title = 'Detail Pesanan #' . $order->order_number;
?>

<div class="checkout-view">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-file-invoice"></i> Detail Pesanan</h2>
        <a href="<?= Url::to(['checkout/orders']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <hr>

    <div class="row">
        
        <!-- Order Info -->
        <div class="col-md-8">
            
            <!-- Order Header -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>No. Pesanan:</strong> <?= Html::encode($order->order_number) ?></p>
                            <p class="mb-2"><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($order->created_at, 'php:d M Y H:i') ?></p>
                            <p class="mb-2"><strong>Status Pesanan:</strong> <?= $order->getOrderStatusBadge() ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Metode Pembayaran:</strong> <?= $order->getPaymentMethodLabel() ?></p>
                            <p class="mb-2"><strong>Status Pembayaran:</strong> <?= $order->getPaymentStatusBadge() ?></p>
                            <p class="mb-2"><strong>Total:</strong> <span class="text-success fs-5"><strong>Rp <?= number_format($order->total, 0, ',', '.') ?></strong></span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Penerima</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Nama:</strong> <?= Html::encode($order->customer_name) ?></p>
                    <p class="mb-2"><strong>Email:</strong> <?= Html::encode($order->customer_email) ?></p>
                    <p class="mb-2"><strong>Telepon:</strong> <?= Html::encode($order->customer_phone) ?></p>
                    <p class="mb-0"><strong>Alamat Pengiriman:</strong><br><?= nl2br(Html::encode($order->shipping_address)) ?></p>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th width="150">Harga</th>
                                    <th width="100">Qty</th>
                                    <th width="150">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order->orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= Html::encode($item->product_name) ?></strong>
                                        </td>
                                        <td>
                                            Rp <?= number_format($item->product_price, 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <?= $item->quantity ?>
                                        </td>
                                        <td>
                                            <strong>Rp <?= number_format($item->subtotal, 0, ',', '.') ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total Pesanan:</th>
                                    <th>
                                        <span class="text-success">Rp <?= number_format($order->total, 0, ',', '.') ?></span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            
            <!-- Payment Info -->
            <?php if ($order->payment_method === 'transfer'): ?>
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Informasi Transfer</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Bank BCA</strong></p>
                        <p class="mb-2">No. Rek: <strong>1234567890</strong></p>
                        <p class="mb-3">a.n <strong>Toko Online</strong></p>
                        
                        <div class="alert alert-info mb-0">
                            <small>Transfer sebesar:<br>
                            <strong class="fs-5">Rp <?= number_format($order->total, 0, ',', '.') ?></strong>
                            </small>
                        </div>
                        
                        <?php if ($order->payment_proof): ?>
                            <hr>
                            <p class="mb-2"><strong>Bukti Transfer:</strong></p>
                            <img src="<?= Yii::getAlias('@web/uploads/payments/' . $order->payment_proof) ?>" 
                                 class="img-fluid rounded" 
                                 alt="Bukti Transfer">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Order Timeline -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Status Pesanan</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <li class="<?= in_array($order->order_status, ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                            <i class="fas fa-check-circle"></i>
                            <strong>Pesanan Dibuat</strong><br>
                            <small><?= Yii::$app->formatter->asDate($order->created_at, 'php:d M Y H:i') ?></small>
                        </li>
                        <li class="<?= in_array($order->order_status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                            <i class="fas fa-spinner"></i>
                            <strong>Diproses</strong>
                        </li>
                        <li class="<?= in_array($order->order_status, ['shipped', 'delivered']) ? 'completed' : '' ?>">
                            <i class="fas fa-shipping-fast"></i>
                            <strong>Dikirim</strong>
                        </li>
                        <li class="<?= $order->order_status === 'delivered' ? 'completed' : '' ?>">
                            <i class="fas fa-box-open"></i>
                            <strong>Diterima</strong>
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
        
    </div>

</div>

<style>
.timeline {
    list-style: none;
    padding: 0;
    margin: 0;
}
.timeline li {
    padding: 15px 0 15px 40px;
    position: relative;
    border-left: 2px solid #ddd;
}
.timeline li:last-child {
    border-left: 0;
}
.timeline li i {
    position: absolute;
    left: -12px;
    top: 15px;
    background: #fff;
    color: #ddd;
    font-size: 20px;
}
.timeline li.completed {
    border-left-color: #28a745;
}
.timeline li.completed i {
    color: #28a745;
}
</style>
