<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $status string */
/* @var $paymentStatus string */
/* @var $search string */

$this->title = 'Kelola Pesanan';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-index">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-shopping-cart"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-chart-bar"></i> Statistik', ['stats'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter & Pencarian</h5>
        </div>
        <div class="card-body">
            <form method="get" action="<?= Url::to(['order/index']) ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status Pesanan</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $status == 'processing' ? 'selected' : '' ?>>Diproses</option>
                        <option value="shipped" <?= $status == 'shipped' ? 'selected' : '' ?>>Dikirim</option>
                        <option value="delivered" <?= $status == 'delivered' ? 'selected' : '' ?>>Diterima</option>
                        <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="payment_status" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="pending" <?= $paymentStatus == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="paid" <?= $paymentStatus == 'paid' ? 'selected' : '' ?>>Lunas</option>
                        <option value="failed" <?= $paymentStatus == 'failed' ? 'selected' : '' ?>>Gagal</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="No. Order, Nama, Email..." value="<?= Html::encode($search) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE PESANAN -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Pesanan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 140px;">No. Order</th>
                            <th>Customer</th>
                            <th style="width: 120px;">Total</th>
                            <th style="width: 150px;">Status Pesanan</th>
                            <th style="width: 150px;">Pembayaran</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 180px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dataProvider->models)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada pesanan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dataProvider->models as $order): ?>
                            <tr>
                                <td>
                                    <strong><?= Html::encode($order->order_number) ?></strong>
                                </td>
                                <td>
                                    <strong><?= Html::encode($order->customer_name) ?></strong><br>
                                    <small class="text-muted"><?= Html::encode($order->customer_email) ?></small>
                                </td>
                                <td>
                                    <strong class="text-success">Rp <?= number_format($order->total, 0, ',', '.') ?></strong>
                                </td>
                                <td><?= $order->orderStatusBadge ?></td>
                                <td><?= $order->paymentStatusBadge ?></td>
                                <td>
                                    <small><?= date('d M Y', strtotime($order->created_at)) ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= Url::to(['order/view', 'id' => $order->id]) ?>" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= Url::to(['order/update-status', 'id' => $order->id]) ?>" 
                                           class="btn btn-warning" title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= Url::to(['order/delete', 'id' => $order->id]) ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Yakin ingin menghapus pesanan ini?')"
                                           title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <?= LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options' => ['class' => 'pagination justify-content-center mb-0'],
                'linkOptions' => ['class' => 'page-link'],
                'activePageCssClass' => 'active',
                'disabledPageCssClass' => 'disabled',
            ]) ?>
        </div>
    </div>
</div>

<style>
.order-index .table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.order-index .btn-group .btn {
    border-radius: 0;
}

.order-index .btn-group .btn:first-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.order-index .btn-group .btn:last-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}
</style>
