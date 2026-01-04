<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $totalOrders int */
/* @var $pendingOrders int */
/* @var $processingOrders int */
/* @var $shippedOrders int */
/* @var $deliveredOrders int */
/* @var $cancelledOrders int */
/* @var $totalRevenue float */
/* @var $pendingPayment float */

$this->title = 'Statistik Pesanan';
$this->params['breadcrumbs'][] = ['label' => 'Kelola Pesanan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-stats">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-chart-bar"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <!-- REVENUE CARDS -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Pendapatan (Lunas)</div>
                            <div class="h2 mb-0 fw-bold text-success">
                                Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="text-success" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Pending Pembayaran</div>
                            <div class="h2 mb-0 fw-bold text-warning">
                                Rp <?= number_format($pendingPayment, 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="text-warning" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ORDER STATUS CARDS -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Status Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2">
                            <div class="p-3 border rounded">
                                <h3 class="text-primary"><?= $totalOrders ?></h3>
                                <small class="text-muted">Total Pesanan</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded">
                                <h3 class="text-secondary"><?= $pendingOrders ?></h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded">
                                <h3 class="text-info"><?= $processingOrders ?></h3>
                                <small class="text-muted">Diproses</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded">
                                <h3 class="text-primary"><?= $shippedOrders ?></h3>
                                <small class="text-muted">Dikirim</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded">
                                <h3 class="text-success"><?= $deliveredOrders ?></h3>
                                <small class="text-muted">Diterima</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded">
                                <h3 class="text-danger"><?= $cancelledOrders ?></h3>
                                <small class="text-muted">Dibatalkan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK LINKS -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-link"></i> Quick Links</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <a href="<?= Url::to(['order/index', 'status' => 'pending']) ?>" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-clock"></i> Lihat Pending
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?= Url::to(['order/index', 'status' => 'processing']) ?>" class="btn btn-outline-info w-100 mb-2">
                        <i class="fas fa-cog"></i> Lihat Diproses
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?= Url::to(['order/index', 'payment_status' => 'pending']) ?>" class="btn btn-outline-warning w-100 mb-2">
                        <i class="fas fa-exclamation-circle"></i> Belum Bayar
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?= Url::to(['order/index']) ?>" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-list"></i> Semua Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
