<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Tag[] $tags */
/** @var int $currentPage */
/** @var int $totalPages */
/** @var int $totalCount */

$this->title = 'Daftar Tag';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-tags"></i> <?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-plus"></i> Tambah Tag', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Nama Tag</th>
                            <th style="width: 150px;">Jumlah Produk</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tags)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Belum ada tag. Silakan tambah tag baru.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tags as $tag): ?>
                                <tr>
                                    <td><?= $tag->id ?></td>
                                    <td>
                                        <span class="badge bg-info" style="font-size: 1em;">
                                            <?= Html::encode($tag->name) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= count($tag->products) ?> produk
                                        </span>
                                    </td>
                                    <td>
                                        <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $tag->id], [
                                            'class' => 'btn btn-sm btn-info',
                                            'title' => 'Lihat'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $tag->id], [
                                            'class' => 'btn btn-sm btn-primary',
                                            'title' => 'Edit'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $tag->id], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Hapus',
                                            'data' => [
                                                'confirm' => 'Yakin ingin menghapus tag ini?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- PAGINATION -->
                <?php if ($totalPages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">
                                Halaman <?= $currentPage ?> dari <?= $totalPages ?> 
                                (Total: <?= $totalCount ?> tag)
                            </small>
                        </div>
                        <nav>
                            <ul class="pagination mb-0">
                                <!-- Previous -->
                                <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= Url::to(['tag/index', 'page' => $currentPage - 1]) ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                <!-- Page Numbers -->
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= Url::to(['tag/index', 'page' => $i]) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <!-- Next -->
                                <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= Url::to(['tag/index', 'page' => $currentPage + 1]) ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
