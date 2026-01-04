<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $categories app\models\Category[] */

$this->title = 'Kategori';
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-index">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-folder"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <a href="<?= Url::to(['category/create']) ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Category
            </a>
        </div>
    </div>

    <!-- INFO CARD -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Kategori</div>
                            <div class="h2 mb-0 fw-bold text-primary"><?= count($categories) ?></div>
                        </div>
                        <div class="text-primary" style="font-size: 3rem; opacity: 0.2;">
                            <i class="fas fa-folder-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Kategori</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Nama Kategori</th>
                            <th style="width: 200px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada kategori. Silakan tambah kategori baru.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $c): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $c->id ?></span></td>
                                <td>
                                    <i class="fas fa-folder text-primary"></i>
                                    <strong><?= Html::encode($c->name) ?></strong>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= Url::to(['category/update', 'id' => $c->id]) ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="<?= Url::to(['category/delete', 'id' => $c->id]) ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                           title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
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
        <div class="card-footer text-muted">
            <small><i class="fas fa-info-circle"></i> Total: <?= count($categories) ?> kategori</small>
        </div>
    </div>
</div>

<style>
.category-index .card {
    border-radius: 10px;
    transition: transform 0.2s;
}

.category-index .card:hover {
    transform: translateY(-5px);
}

.category-index .table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.category-index .btn-group .btn {
    border-radius: 0;
}

.category-index .btn-group .btn:first-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.category-index .btn-group .btn:last-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}
</style>