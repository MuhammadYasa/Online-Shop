<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Order $order */

$this->title = 'Pesanan Berhasil';
?>

<div class="checkout-success">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h2 class="text-success mb-3">Pesanan Berhasil Dibuat!</h2>
                    
                    <p class="lead mb-4">Terima kasih telah berbelanja di toko kami.</p>
                    
                    <div class="alert alert-info">
                        <h5>Nomor Pesanan Anda:</h5>
                        <h3 class="mb-0"><strong><?= Html::encode($order->order_number) ?></strong></h3>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Order Details -->
                    <div class="row text-start mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-user"></i> Informasi Penerima</h5>
                            <p class="mb-1"><strong>Nama:</strong> <?= Html::encode($order->customer_name) ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= Html::encode($order->customer_email) ?></p>
                            <p class="mb-1"><strong>Telepon:</strong> <?= Html::encode($order->customer_phone) ?></p>
                            <p class="mb-0"><strong>Alamat:</strong><br><?= nl2br(Html::encode($order->shipping_address)) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-money-bill-wave"></i> Pembayaran</h5>
                            <p class="mb-1"><strong>Metode:</strong> <?= $order->getPaymentMethodLabel() ?></p>
                            <p class="mb-1"><strong>Status:</strong> <?= $order->getPaymentStatusBadge() ?></p>
                            <p class="mb-1"><strong>Total:</strong> <span class="text-success fs-4"><strong>Rp <?= number_format($order->total, 0, ',', '.') ?></strong></span></p>
                            
                            <?php if ($order->payment_method === 'transfer'): ?>
                                <div class="alert alert-warning mt-3">
                                    <small>
                                        <strong>Transfer ke:</strong><br>
                                        BCA: 1234567890 a.n Toko Online<br>
                                        Nominal: <strong>Rp <?= number_format($order->total, 0, ',', '.') ?></strong>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Item Pesanan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order->orderItems as $item): ?>
                                            <tr>
                                                <td><?= Html::encode($item->product_name) ?></td>
                                                <td>Rp <?= number_format($item->product_price, 0, ',', '.') ?></td>
                                                <td><?= $item->quantity ?></td>
                                                <td>Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total:</th>
                                            <th>Rp <?= number_format($order->total, 0, ',', '.') ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="<?= Url::to(['checkout/orders']) ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-list"></i> Lihat Pesanan Saya
                        </a>
                        <a href="<?= Url::to(['site/index']) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-store"></i> Lanjut Belanja
                        </a>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>

</div>
