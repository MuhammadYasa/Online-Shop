<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Order $order */
/** @var array $cartItems */
/** @var float $total */

$this->title = 'Checkout';
?>

<div class="checkout-index">
    
    <h2><i class="fas fa-credit-card"></i> Checkout</h2>
    <hr>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        
        <!-- Shipping Information -->
        <div class="col-md-7">
            
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Penerima</h5>
                </div>
                <div class="card-body">
                    <?= $form->field($order, 'customer_name')->textInput(['placeholder' => 'Nama lengkap penerima']) ?>
                    
                    <?= $form->field($order, 'customer_phone')->textInput(['placeholder' => '08xxxxxxxxxx']) ?>
                    
                    <?= $form->field($order, 'customer_email')->textInput(['placeholder' => 'email@example.com']) ?>
                    
                    <?= $form->field($order, 'shipping_address')->textarea(['rows' => 3, 'placeholder' => 'Alamat lengkap pengiriman']) ?>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    
                    <?= $form->field($order, 'payment_method')->radioList([
                        'cod' => '<strong>COD (Cash on Delivery)</strong><br><small class="text-muted">Bayar saat barang sampai</small>',
                        'transfer' => '<strong>Transfer Bank</strong><br><small class="text-muted">Transfer ke BCA 1234567890 a.n Toko Online</small>'
                    ], [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return '<div class="form-check mb-3">' .
                                Html::radio($name, $checked, ['value' => $value, 'class' => 'form-check-input payment-method-radio', 'id' => 'payment-' . $value]) .
                                '<label class="form-check-label" for="payment-' . $value . '">' . $label . '</label>' .
                                '</div>';
                        }
                    ])->label(false) ?>
                    
                    <!-- Upload Bukti Transfer (only for transfer method) -->
                    <div id="payment-proof-upload" style="display: none;">
                        <div class="alert alert-warning">
                            <strong>Bank BCA: 1234567890 a.n Toko Online</strong><br>
                            Silakan upload bukti transfer Anda
                        </div>
                        <?= $form->field($order, 'payment_proof')->fileInput(['accept' => 'image/*']) ?>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
        <!-- Order Summary -->
        <div class="col-md-5">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <strong><?= Html::encode($item->product->name) ?></strong><br>
                                    <small class="text-muted"><?= $item->quantity ?> x Rp <?= number_format($item->product->price, 0, ',', '.') ?></small>
                                </div>
                                <strong>Rp <?= number_format($item->product->price * $item->quantity, 0, ',', '.') ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>Rp <?= number_format($total, 0, ',', '.') ?></strong>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkir:</span>
                        <strong>Gratis</strong>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Total:</h5>
                        <h5 class="text-success">Rp <?= number_format($total, 0, ',', '.') ?></h5>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <?= Html::submitButton('<i class="fas fa-check-circle"></i> Buat Pesanan', ['class' => 'btn btn-success btn-lg']) ?>
                        
                        <a href="<?= Url::to(['cart/index']) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
    // Toggle payment proof upload based on payment method
    $('input.payment-method-radio').on('change', function() {
        if ($(this).val() === 'transfer') {
            $('#payment-proof-upload').slideDown();
        } else {
            $('#payment-proof-upload').slideUp();
        }
    });
    
    // Trigger on page load
    if ($('input.payment-method-radio:checked').val() === 'transfer') {
        $('#payment-proof-upload').show();
    }
");
?>
