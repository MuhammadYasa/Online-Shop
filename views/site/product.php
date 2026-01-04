<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Product $product */

$this->title = $product->name;
?>

<div class="product-detail">
    
    <!-- <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Home</a></li>
            <li class="breadcrumb-item active"><?= Html::encode($product->name) ?></li>
        </ol>
    </nav> -->

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body text-center p-4">
                    <?php if ($product->image): ?>
                        <img src="<?= Yii::getAlias('@web/uploads/products/' . $product->image) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= Html::encode($product->name) ?>"
                             style="max-height: 400px;">
                    <?php else: ?>
                        <div class="bg-light p-5 rounded">
                            <i class="fas fa-image fa-5x text-muted"></i>
                            <p class="mt-3 text-muted">Tidak ada gambar</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <!-- Category -->
                    <?php if ($product->category): ?>
                        <span class="badge bg-secondary mb-2">
                            <?= Html::encode($product->category->name) ?>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Product Name -->
                    <h2 class="mb-3"><?= Html::encode($product->name) ?></h2>
                    
                    <!-- Price -->
                    <h3 class="text-primary mb-3">
                        Rp <?= number_format($product->price, 0, ',', '.') ?>
                    </h3>
                    
                    <hr>
                    
                    <!-- Stock -->
                    <p class="mb-3">
                        <strong>Stok:</strong> 
                        <span class="badge <?= $product->stock < 10 ? 'bg-danger' : 'bg-success' ?>">
                            <?= $product->stock ?> unit
                        </span>
                    </p>
                    
                    <!-- Tags -->
                    <?php if (!empty($product->tags)): ?>
                        <p class="mb-3">
                            <strong>Tag:</strong>
                            <?php foreach ($product->tags as $tag): ?>
                                <span class="badge bg-info"><?= Html::encode($tag->name) ?></span>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <!-- Description -->
                    <h5>Deskripsi Produk</h5>
                    <p class="text-muted"><?= nl2br(Html::encode($product->description)) ?></p>
                    
                    <hr>
                    
                    <!-- Add to Cart Form -->
                    <form method="post" action="<?= Url::to(['cart/add', 'id' => $product->id]) ?>">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?= $product->stock ?>">
                            </div>
                            <div class="col-md-9">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg" <?= $product->stock <= 0 ? 'disabled' : '' ?>>
                                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="mt-3">
                        <a href="<?= Url::to(['site/index']) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
