<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $items */
/** @var float $total */

$this->title = 'Keranjang Belanja';
?>

<div class="cart-index">
    
    <h2><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h2>
    <hr>

    <?php if (empty($items)): ?>
        
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h4>Keranjang Anda Kosong</h4>
            <p>Mulai belanja sekarang dan temukan produk favorit Anda!</p>
            <a href="<?= Url::to(['site/index']) ?>" class="btn btn-primary mt-3">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>
        
    <?php else: ?>

        <div class="row">
            
            <!-- Cart Items -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th width="150">Harga</th>
                                        <th width="150">Jumlah</th>
                                        <th width="150">Subtotal</th>
                                        <th width="80">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($item->product->image): ?>
                                                        <img src="<?= Yii::getAlias('@web/uploads/products/' . $item->product->image) ?>" 
                                                             width="60" height="60" 
                                                             class="rounded me-3 object-fit-cover"
                                                             alt="<?= Html::encode($item->product->name) ?>">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?= Html::encode($item->product->name) ?></strong><br>
                                                        <small class="text-muted">Stok: <?= $item->product->stock ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                Rp <?= number_format($item->product->price, 0, ',', '.') ?>
                                            </td>
                                            <td class="align-middle">
                                                <form method="post" action="<?= Url::to(['cart/update', 'id' => $item->id]) ?>" class="d-inline">
                                                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                                    <div class="input-group" style="width: 130px;">
                                                        <input type="number" 
                                                               name="quantity" 
                                                               class="form-control form-control-sm" 
                                                               value="<?= $item->quantity ?>" 
                                                               min="1" 
                                                               max="<?= $item->product->stock ?>"
                                                               onchange="this.form.submit()">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="align-middle">
                                                <strong>Rp <?= number_format($item->product->price * $item->quantity, 0, ',', '.') ?></strong>
                                            </td>
                                            <td class="align-middle">
                                                <a href="<?= Url::to(['cart/remove', 'id' => $item->id]) ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   data-method="post"
                                                   data-confirm="Hapus produk ini dari keranjang?">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-end mt-3">
                            <a href="<?= Url::to(['cart/clear']) ?>" 
                               class="btn btn-outline-danger"
                               data-method="post"
                               data-confirm="Kosongkan seluruh keranjang?">
                                <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item:</span>
                            <strong><?= count($items) ?> produk</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Quantity:</span>
                            <strong><?= array_sum(array_column($items, 'quantity')) ?> unit</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Total Harga:</h5>
                            <h5 class="text-primary">Rp <?= number_format($total, 0, ',', '.') ?></h5>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="<?= Url::to(['checkout/index']) ?>" class="btn btn-success btn-lg">
                                <i class="fas fa-credit-card"></i> Checkout
                            </a>
                            <a href="<?= Url::to(['site/index']) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    <?php endif; ?>

</div>
