<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $orders */

$this->title = 'Pesanan Saya';
?>

<div class="checkout-orders">
    
    <h2><i class="fas fa-list"></i> Pesanan Saya</h2>
    <hr>

    <?php if (empty($orders)): ?>
        
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-bag fa-3x mb-3"></i>
            <h4>Belum Ada Pesanan</h4>
            <p>Anda belum pernah melakukan pemesanan.</p>
            <a href="<?= Url::to(['site/index']) ?>" class="btn btn-primary mt-3">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>
        
    <?php else: ?>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status Bayar</th>
                        <th>Status Pesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <strong><?= Html::encode($order->order_number) ?></strong>
                            </td>
                            <td>
                                <?= Yii::$app->formatter->asDate($order->created_at, 'php:d M Y H:i') ?>
                            </td>
                            <td>
                                <strong>Rp <?= number_format($order->total, 0, ',', '.') ?></strong>
                            </td>
                            <td>
                                <?= $order->getPaymentMethodLabel() ?>
                            </td>
                            <td>
                                <?= $order->getPaymentStatusBadge() ?>
                            </td>
                            <td>
                                <?= $order->getOrderStatusBadge() ?>
                            </td>
                            <td>
                                <a href="<?= Url::to(['checkout/view', 'id' => $order->id]) ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
    <?php endif; ?>

</div>
