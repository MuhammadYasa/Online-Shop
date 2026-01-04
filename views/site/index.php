<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Product[] $products */
/** @var app\models\Category[] $categories */

$this->title = 'Home';
?>

<div class="shop-home">

    <!-- HERO SECTION -->
    <div class="hero-section mb-5">
        <div class="jumbotron bg-gradient p-5 rounded text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h1 class="display-4"><i class="fas fa-gift"></i> Selamat Datang di My Olshop!</h1>
            <p class="lead">Belanja produk berkualitas dengan harga terbaik. Pengiriman cepat ke seluruh Indonesia.</p>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.3);">
            <p>Gratis ongkir untuk pembelian di atas Rp 100.000</p>
        </div>
    </div>

    <!-- SEARCH & FILTER -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= Html::encode($searchTerm) ?>">
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= $selectedCategory == $cat->id ? 'selected' : '' ?>>
                                <?= Html::encode($cat->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="row">
        <?php if (empty($products)): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
                <p class="text-muted">Produk tidak ditemukan.</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 product-card shadow-sm">
                        <div class="product-image" style="height: 200px; overflow: hidden; background: #f5f5f5;">
                            <?php if ($product->image): ?>
                                <img src="<?= Yii::getAlias('@web/uploads/products/' . $product->image) ?>" 
                                     class="card-img-top" 
                                     alt="<?= Html::encode($product->name) ?>"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <?php if ($product->category): ?>
                                <span class="badge bg-secondary mb-2" style="width: fit-content;">
                                    <?= Html::encode($product->category->name) ?>
                                </span>
                            <?php endif; ?>
                            
                            <h5 class="card-title">
                                <a href="<?= Url::to(['site/product', 'id' => $product->id]) ?>" class="text-decoration-none text-dark">
                                    <?= Html::encode($product->name) ?>
                                </a>
                            </h5>
                            
                            <p class="text-primary fw-bold fs-5 mb-2">
                                Rp <?= number_format($product->price, 0, ',', '.') ?>
                            </p>
                            
                            <p class="text-muted small mb-3">
                                Stok: <span class="<?= $product->stock < 10 ? 'text-danger' : 'text-success' ?>">
                                    <?= $product->stock ?> unit
                                </span>
                            </p>
                            
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="<?= Url::to(['site/product', 'id' => $product->id]) ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                    <form method="post" action="<?= Url::to(['cart/add', 'id' => $product->id]) ?>" class="m-0">
                                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-success btn-sm w-100" <?= $product->stock <= 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-cart-plus"></i> Tambah
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- PAGINATION -->
    <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Url::to(['site/index', 'page' => $currentPage - 1, 'category' => $selectedCategory, 'search' => $searchTerm]) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= Url::to(['site/index', 'page' => $i, 'category' => $selectedCategory, 'search' => $searchTerm]) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Url::to(['site/index', 'page' => $currentPage + 1, 'category' => $selectedCategory, 'search' => $searchTerm]) ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>

</div>

<style>
.product-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
}
</style>
