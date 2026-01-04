<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $totalCategories int */
/* @var $totalProducts int */
/* @var $totalTags int */
/* @var $totalStock int */
/* @var $recentProducts app\models\Product[] */
/* @var $topCategories array */
/* @var $popularTags array */
/* @var $lowStockProducts app\models\Product[] */

$this->title = 'Dashboard';
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-tachometer-alt"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <!-- <span class="text-muted">
                <i class="far fa-calendar"></i> 
                <?= date('d F Y, H:i') ?>
            </span> -->
        </div>
    </div>

    <!-- STATISTIK CARDS -->
    <div class="row mb-4">
        <!-- Total Kategori -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Kategori</div>
                            <div class="h2 mb-0 fw-bold"><?= $totalCategories ?></div>
                        </div>
                        <div class="text-primary" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                    <hr>
                    <a href="<?= Url::to(['/category/index']) ?>" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-arrow-right"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Produk</div>
                            <div class="h2 mb-0 fw-bold"><?= $totalProducts ?></div>
                        </div>
                        <div class="text-success" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <hr>
                    <a href="<?= Url::to(['/product/index']) ?>" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-arrow-right"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Tag -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Tag</div>
                            <div class="h2 mb-0 fw-bold"><?= $totalTags ?></div>
                        </div>
                        <div class="text-warning" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                    <hr>
                    <a href="<?= Url::to(['/tag/index']) ?>" class="btn btn-sm btn-warning w-100">
                        <i class="fas fa-arrow-right"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-info shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Stok</div>
                            <div class="h2 mb-0 fw-bold"><?= number_format($totalStock) ?></div>
                        </div>
                        <div class="text-info" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-cubes"></i>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">Unit tersedia</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- PRODUK TERBARU -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Produk Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentProducts)): ?>
                        <div class="text-center text-muted p-4">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada produk</p>
                            <a href="<?= Url::to(['/product/create']) ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentProducts as $product): ?>
                                <a href="<?= Url::to(['/product/view', 'id' => $product->id]) ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= Html::encode($product->name) ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-folder"></i> <?= Html::encode($product->category->name) ?>
                                            </small>
                                        </div>
                                        <div class="text-end ms-3">
                                            <div class="text-primary fw-bold">
                                                Rp <?= number_format($product->price, 0, ',', '.') ?>
                                            </div>
                                            <small class="text-muted">Stok: <?= $product->stock ?></small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- KATEGORI TERATAS -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Top Kategori</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($topCategories)): ?>
                        <div class="text-center text-muted p-4">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada data</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($topCategories as $category): ?>
                                <a href="<?= Url::to(['/category/view', 'id' => $category['id']]) ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-folder text-success me-2"></i>
                                            <strong><?= Html::encode($category['name']) ?></strong>
                                        </div>
                                        <span class="badge bg-success rounded-pill">
                                            <?= $category['product_count'] ?> produk
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- TAG POPULER -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Tag Populer</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($popularTags)): ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-tags fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada tag</p>
                            <a href="<?= Url::to(['/tag/create']) ?>" class="btn btn-warning">
                                <i class="fas fa-plus"></i> Tambah Tag
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($popularTags as $tag): ?>
                                <a href="<?= Url::to(['/tag/view', 'id' => $tag['id']]) ?>" 
                                   class="badge rounded-pill text-decoration-none" 
                                   style="background-color: <?= $tag['color'] ?>; font-size: 1rem; padding: 8px 15px;">
                                    <?= Html::encode($tag['name']) ?>
                                    <span class="badge bg-light text-dark ms-1"><?= $tag['usage_count'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- PRODUK STOK RENDAH -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-danger h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Stok Rendah</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($lowStockProducts)): ?>
                        <div class="text-center text-success p-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p class="mb-0"><strong>Semua stok aman!</strong></p>
                            <small class="text-muted">Tidak ada produk dengan stok rendah</small>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($lowStockProducts as $product): ?>
                                <a href="<?= Url::to(['/product/view', 'id' => $product->id]) ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= Html::encode($product->name) ?></h6>
                                            <small class="text-muted">
                                                <?= Html::encode($product->category->name) ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-danger">
                                            Stok: <?= $product->stock ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- INFO BOX -->
    <div class="alert alert-info shadow-sm">
        <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Informasi Dashboard</h5>
        <p class="mb-0">Dashboard ini menampilkan ringkasan data online shop Anda. Klik pada card atau item untuk melihat detail lebih lanjut.</p>
    </div>
</div>

<!-- Custom Styling untuk Dashboard -->
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
    }
    
    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>
