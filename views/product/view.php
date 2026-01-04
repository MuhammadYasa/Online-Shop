<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Produk', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-box"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-edit"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Apakah Anda yakin ingin menghapus produk ini?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <!-- GAMBAR PRODUK -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <?php if ($model->image): ?>
                        <img src="<?= Yii::getAlias('@web/uploads/products/' . $model->image) ?>" 
                             alt="<?= Html::encode($model->name) ?>" 
                             class="img-fluid rounded"
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

        <div class="col-md-8">
            <!-- DETAIL PRODUK -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detail Produk</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            [
                                'attribute' => 'description',
                                'format' => 'ntext',
                            ],
                            [
                                'attribute' => 'price',
                                'value' => 'Rp ' . number_format($model->price, 0, ',', '.'),
                            ],
                            [
                                'attribute' => 'stock',
                                'value' => function($model) {
                                    $badge = $model->stock < 10 ? 'danger' : 'success';
                                    return '<span class="badge bg-' . $badge . '">' . $model->stock . ' unit</span>';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'category_id',
                                'label' => 'Kategori',
                                'value' => function($model) {
                                    return $model->category ? 
                                        '<a href="' . Url::to(['category/view', 'id' => $model->category->id]) . '">' .
                                        Html::encode($model->category->name) . '</a>' : 
                                        '<span class="text-muted">-</span>';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => 'Tag',
                                'value' => function($model) {
                                    if (empty($model->tags)) {
                                        return '<span class="text-muted">Tidak ada tag</span>';
                                    }
                                    $badges = [];
                                    foreach ($model->tags as $tag) {
                                        $badges[] = '<span class="badge bg-info">' . Html::encode($tag->name) . '</span>';
                                    }
                                    return implode(' ', $badges);
                                },
                                'format' => 'raw',
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>

            <!-- STATISTIK PRODUK -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3 border rounded">
                                <h2 class="text-primary">Rp <?= number_format($model->price, 0, ',', '.') ?></h2>
                                <small class="text-muted">Harga Satuan</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded">
                                <h2 class="<?= $model->stock < 10 ? 'text-danger' : 'text-success' ?>">
                                    <?= $model->stock ?>
                                </h2>
                                <small class="text-muted">Stok Tersedia</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded">
                                <h2 class="text-info">Rp <?= number_format($model->price * $model->stock, 0, ',', '.') ?></h2>
                                <small class="text-muted">Total Nilai Stok</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
