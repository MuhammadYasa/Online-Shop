<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $order app\models\Order */

$this->title = 'Update Status Pesanan: ' . $order->order_number;
$this->params['breadcrumbs'][] = ['label' => 'Kelola Pesanan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $order->order_number, 'url' => ['view', 'id' => $order->id]];
$this->params['breadcrumbs'][] = 'Update Status';
?>

<div class="order-update-status">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['view', 'id' => $order->id], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Update Status</h5>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['method' => 'post']); ?>

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-box"></i> Status Pesanan
                        </label>
                        <select name="order_status" class="form-select form-select-lg">
                            <option value="pending" <?= $order->order_status == 'pending' ? 'selected' : '' ?>>
                                Pending
                            </option>
                            <option value="processing" <?= $order->order_status == 'processing' ? 'selected' : '' ?>>
                                Diproses
                            </option>
                            <option value="shipped" <?= $order->order_status == 'shipped' ? 'selected' : '' ?>>
                                Dikirim
                            </option>
                            <option value="delivered" <?= $order->order_status == 'delivered' ? 'selected' : '' ?>>
                                Diterima
                            </option>
                            <option value="cancelled" <?= $order->order_status == 'cancelled' ? 'selected' : '' ?>>
                                Dibatalkan
                            </option>
                        </select>
                        <div class="form-text">Status pengiriman pesanan</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill"></i> Status Pembayaran
                        </label>
                        <select name="payment_status" class="form-select form-select-lg">
                            <option value="pending" <?= $order->payment_status == 'pending' ? 'selected' : '' ?>>
                                Pending
                            </option>
                            <option value="paid" <?= $order->payment_status == 'paid' ? 'selected' : '' ?>>
                                Lunas
                            </option>
                            <option value="failed" <?= $order->payment_status == 'failed' ? 'selected' : '' ?>>
                                Gagal
                            </option>
                        </select>
                        <div class="form-text">Status pembayaran dari customer</div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Simpan Perubahan', ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Panduan Status</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Status Pesanan:</h6>
                    <ul class="small">
                        <li><strong>Pending:</strong> Pesanan baru masuk</li>
                        <li><strong>Diproses:</strong> Pesanan sedang disiapkan</li>
                        <li><strong>Dikirim:</strong> Pesanan dalam pengiriman</li>
                        <li><strong>Diterima:</strong> Pesanan sudah sampai</li>
                        <li><strong>Dibatalkan:</strong> Pesanan dibatalkan</li>
                    </ul>

                    <h6 class="fw-bold mt-3">Status Pembayaran:</h6>
                    <ul class="small mb-0">
                        <li><strong>Pending:</strong> Belum bayar</li>
                        <li><strong>Lunas:</strong> Sudah dibayar</li>
                        <li><strong>Gagal:</strong> Pembayaran ditolak</li>
                    </ul>
                </div>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Perhatian!</strong>
                <p class="small mb-0">Pastikan status yang dipilih sudah sesuai dengan kondisi pesanan yang sebenarnya.</p>
            </div>
        </div>
    </div>
</div>

<style>
.form-select-lg {
    font-size: 1.1rem;
    padding: 0.75rem 1rem;
}
</style>
