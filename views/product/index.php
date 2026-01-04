<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Product[] $products */
/** @var app\models\Category[] $categories */
/** @var app\models\Tag[] $tags */
/** @var string $searchTerm */
/** @var int $selectedCategory */
/** @var int $selectedTag */

$this->title = 'Daftar Produk';
// $this->params['breadcrumbs'][] = $this->title;   
?>
<div class="product-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-box"></i> <?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-plus"></i> Tambah Produk', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <!-- SEARCH & FILTER FORM -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-search"></i> Cari & Filter Produk</h5>
        </div>
        <div class="card-body">
            <form method="get" action="<?= Url::to(['product/index']) ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Nama Produk</label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Ketik nama produk..." 
                           value="<?= Html::encode($searchTerm) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->id ?>" <?= $selectedCategory == $category->id ? 'selected' : '' ?>>
                                <?= Html::encode($category->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Tag</label>
                    <select name="tag_id" class="form-select">
                        <option value="">-- Semua Tag --</option>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?= $tag->id ?>" <?= $selectedTag == $tag->id ? 'selected' : '' ?>>
                                <?= Html::encode($tag->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
            
            <?php if ($searchTerm || $selectedCategory || $selectedTag): ?>
                <div class="mt-3">
                    <strong>Filter aktif:</strong>
                    <?php if ($searchTerm): ?>
                        <span class="badge bg-secondary">Search: "<?= Html::encode($searchTerm) ?>"</span>
                    <?php endif; ?>
                    <?php if ($selectedCategory): ?>
                        <?php $cat = ArrayHelper::map($categories, 'id', 'name')[$selectedCategory] ?? ''; ?>
                        <span class="badge bg-secondary">Kategori: <?= Html::encode($cat) ?></span>
                    <?php endif; ?>
                    <?php if ($selectedTag): ?>
                        <?php $tagName = ArrayHelper::map($tags, 'id', 'name')[$selectedTag] ?? ''; ?>
                        <span class="badge bg-secondary">Tag: <?= Html::encode($tagName) ?></span>
                    <?php endif; ?>
                    <?= Html::a('<i class="fas fa-times"></i> Reset Filter', ['index'], ['class' => 'btn btn-sm btn-outline-secondary ms-2']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TABEL PRODUK -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th style="width: 100px;">Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Tag</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Belum ada produk. Silakan tambah produk baru.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product->id ?></td>
                                    <td>
                                        <?php if ($product->image): ?>
                                            <img src="<?= Yii::getAlias('@web/uploads/products/' . $product->image) ?>" 
                                                 alt="<?= Html::encode($product->name) ?>" 
                                                 class="img-thumbnail" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light text-center p-2" style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= Html::encode($product->name) ?></strong><br>
                                        <small class="text-muted"><?= Html::encode(mb_substr($product->description, 0, 50)) ?>...</small>
                                    </td>
                                    <td>
                                        <?php if ($product->category): ?>
                                            <span class="badge bg-secondary"><?= Html::encode($product->category->name) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp <?= number_format($product->price, 0, ',', '.') ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeClass = $product->stock < 10 ? 'bg-danger' : 'bg-success';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $product->stock ?> unit</span>
                                    </td>
                                    <td>
                                        <?php if (!empty($product->tags)): ?>
                                            <?php foreach ($product->tags as $tag): ?>
                                                <span class="badge bg-info"><?= Html::encode($tag->name) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $product->id], [
                                            'class' => 'btn btn-sm btn-info',
                                            'title' => 'Lihat Detail'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $product->id], [
                                            'class' => 'btn btn-sm btn-primary',
                                            'title' => 'Edit'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $product->id], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Hapus',
                                            'data' => [
                                                'confirm' => 'Apakah Anda yakin ingin menghapus produk ini?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Menampilkan halaman <?= $currentPage ?> dari <?= $totalPages ?> 
                            (Total: <?= $totalCount ?> produk)
                        </small>
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <!-- Previous Button -->
                            <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= Url::to(['product/index', 'page' => $currentPage - 1, 'search' => $searchTerm, 'category_id' => $selectedCategory, 'tag_id' => $selectedTag]) ?>">
                                    <i class="fas fa-chevron-left"></i> Prev
                                </a>
                            </li>
                            
                            <!-- Page Numbers -->
                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= Url::to(['product/index', 'page' => $i, 'search' => $searchTerm, 'category_id' => $selectedCategory, 'tag_id' => $selectedTag]) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <!-- Next Button -->
                            <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= Url::to(['product/index', 'page' => $currentPage + 1, 'search' => $searchTerm, 'category_id' => $selectedCategory, 'tag_id' => $selectedTag]) ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<style>
.product-index .table th {
    font-weight: 600;
    font-size: 0.9em;
}

.product-index .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.product-index .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>
