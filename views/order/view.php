<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $order app\models\Order */

$this->title = 'Detail Pesanan: ' . $order->order_number;
$this->params['breadcrumbs'][] = ['label' => 'Kelola Pesanan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-view">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-receipt"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> Update Status', ['update-status', 'id' => $order->id], ['class' => 'btn btn-warning']) ?>
        </div>
    </div>

    <div class="row">
        <!-- INFO PESANAN -->
        <div class="col-lg-8">
            <!-- STATUS TIMELINE -->
            <?php if (!empty($order->statusHistory)): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Perubahan Status</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php 
                        $history = array_reverse($order->statusHistory); 
                        foreach ($history as $index => $item): 
                        ?>
                        <div class="timeline-item <?= $index == 0 ? 'timeline-item-latest' : '' ?>">
                            <div class="timeline-marker">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= htmlspecialchars($item['changes']) ?></strong>
                                        <div class="text-muted small mt-1">
                                            <i class="fas fa-user"></i> <?= htmlspecialchars($item['admin']) ?>
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary">
                                        <?= date('d M Y H:i', strtotime($item['timestamp'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <!-- Initial Status -->
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Pesanan Dibuat</strong>
                                        <div class="text-muted small mt-1">
                                            <i class="fas fa-shopping-cart"></i> Status awal: Pending
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary">
                                        <?= date('d M Y H:i', strtotime($order->created_at)) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">No. Pesanan</th>
                            <td><strong><?= Html::encode($order->order_number) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pesanan</th>
                            <td><?= date('d F Y, H:i', strtotime($order->created_at)) ?></td>
                        </tr>
                        <tr>
                            <th>Status Pesanan</th>
                            <td><?= $order->orderStatusBadge ?></td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td><?= $order->paymentStatusBadge ?></td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td><?= $order->paymentMethodLabel ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- ITEM PESANAN -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order->orderItems as $item): ?>
                                <tr>
                                    <td><?= Html::encode($item->product_name) ?></td>
                                    <td class="text-center"><?= $item->quantity ?></td>
                                    <td class="text-end">Rp <?= number_format($item->product_price, 0, ',', '.') ?></td>
                                    <td class="text-end"><strong>Rp <?= number_format($item->subtotal, 0, ',', '.') ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal:</th>
                                    <th class="text-end">Rp <?= number_format($order->subtotal, 0, ',', '.') ?></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Ongkir:</th>
                                    <th class="text-end">Rp <?= number_format($order->shipping_cost, 0, ',', '.') ?></th>
                                </tr>
                                <tr class="table-success">
                                    <th colspan="3" class="text-end">TOTAL:</th>
                                    <th class="text-end"><h5 class="mb-0">Rp <?= number_format($order->total, 0, ',', '.') ?></h5></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFO CUSTOMER -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Info Customer</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong><br><?= Html::encode($order->customer_name) ?></p>
                    <p><strong>Email:</strong><br><?= Html::encode($order->customer_email) ?></p>
                    <p><strong>Telepon:</strong><br><?= Html::encode($order->customer_phone) ?></p>
                    <p class="mb-0"><strong>Alamat Pengiriman:</strong><br><?= nl2br(Html::encode($order->shipping_address)) ?></p>
                </div>
            </div>

            <?php if ($order->notes): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(Html::encode($order->notes)) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($order->payment_proof): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Bukti Transfer</h5>
                </div>
                <div class="card-body text-center">
                    <img src="<?= Url::to('@web/uploads/payments/' . $order->payment_proof) ?>" 
                         alt="Bukti Transfer" 
                         class="img-fluid rounded" 
                         style="max-height: 300px;">
                    <div class="mt-3">
                        <a href="<?= Url::to('@web/uploads/payments/' . $order->payment_proof) ?>" 
                           target="_blank" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt"></i> Lihat Full
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 12px;
    width: 3px;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
}

.timeline-item {
    position: relative;
    padding-left: 45px;
    padding-bottom: 30px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: white;
    border: 3px solid #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.timeline-item-latest .timeline-marker {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
    animation: pulse 2s infinite;
}

.timeline-item-latest .timeline-marker i {
    color: white;
}

.timeline-marker i {
    font-size: 10px;
    color: #667eea;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #667eea;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.timeline-item-latest .timeline-content {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-left-color: #764ba2;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
    }
}
</style>
