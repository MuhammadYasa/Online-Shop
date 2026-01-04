<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Tag $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tag', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-tag"></i> 
            <span class="badge bg-info" style="font-size: 1em;"><?= Html::encode($this->title) ?></span>
        </h1>
        <div>
            <?= Html::a('<i class="fas fa-edit"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Yakin ingin menghapus tag ini?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Produk dengan Tag "<?= Html::encode($model->name) ?>"</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($model->products)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            Belum ada produk yang menggunakan tag ini.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($model->products as $product): ?>
                                        <tr>
                                            <td><?= $product->id ?></td>
                                            <td><?= Html::encode($product->name) ?></td>
                                            <td>
                                                <?php if ($product->category): ?>
                                                    <span class="badge bg-secondary"><?= Html::encode($product->category->name) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>Rp <?= number_format($product->price, 0, ',', '.') ?></td>
                                            <td>
                                                <span class="badge <?= $product->stock < 10 ? 'bg-danger' : 'bg-success' ?>">
                                                    <?= $product->stock ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= Html::a('<i class="fas fa-eye"></i>', ['product/view', 'id' => $product->id], [
                                                    'class' => 'btn btn-sm btn-info'
                                                ]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <strong>Total: <?= count($model->products) ?> produk</strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
