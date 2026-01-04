<?php
/**
 * FORM PRODUCT - Create & Update
 * 
 * File ini digunakan oleh:
 * - views/product/create.php
 * - views/product/update.php
 * 
 * FITUR RELASI:
 * 1. Dropdown Kategori (Many-to-One)
 * 2. Checkbox Tag (Many-to-Many)
 * 3. Upload Gambar Produk
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var app\models\Category[] $categories */
/** @var app\models\Tag[] $tags */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <!-- NAMA PRODUK -->
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Nama Produk']) ?>

    <!-- DESKRIPSI -->
    <?= $form->field($model, 'description')->textarea(['rows' => 4, 'placeholder' => 'Deskripsi produk...']) ?>

    <!-- HARGA -->
    <?= $form->field($model, 'price')->textInput([
        'type' => 'number',
        'step' => '0.01',
        'min' => '0',
        'placeholder' => '0.00'
    ])->label('Harga (Rp)') ?>

    <!-- STOK -->
    <?= $form->field($model, 'stock')->textInput([
        'type' => 'number',
        'min' => '0',
        'placeholder' => '0'
    ])->label('Stok') ?>

    <!-- DROPDOWN KATEGORI (Many-to-One) -->
    <div class="form-group">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <?= Html::activeDropDownList(
            $model,
            'category_id',
            ArrayHelper::map($categories, 'id', 'name'),
            [
                'class' => 'form-control',
                'prompt' => '-- Pilih Kategori --'
            ]
        ) ?>
        <div class="form-text">Setiap produk harus memiliki 1 kategori (relasi Many-to-One)</div>
    </div>

    <!-- CHECKBOX TAG (Many-to-Many) -->
    <div class="form-group mt-3">
        <label class="form-label">Tag (Opsional)</label>
        <div class="border rounded p-3 bg-light">
            <?php foreach ($tags as $tag): ?>
                <div class="form-check">
                    <?= Html::checkbox(
                        'Product[tagIds][]',
                        in_array($tag->id, $model->tagIds),
                        [
                            'value' => $tag->id,
                            'id' => 'tag-' . $tag->id,
                            'class' => 'form-check-input'
                        ]
                    ) ?>
                    <label class="form-check-label" for="tag-<?= $tag->id ?>">
                        <?= Html::encode($tag->name) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-text">Produk bisa memiliki banyak tag (relasi Many-to-Many via tabel product_tag)</div>
    </div>

    <!-- UPLOAD GAMBAR -->
    <div class="form-group mt-3">
        <label class="form-label">Gambar Produk</label>
        <?= $form->field($model, 'image')->fileInput([
            'accept' => 'image/*',
            'class' => 'form-control'
        ])->label(false) ?>
        
        <?php if (!$model->isNewRecord && $model->image): ?>
            <div class="mt-2">
                <strong>Gambar saat ini:</strong><br>
                <img src="<?= Yii::getAlias('@web/uploads/products/' . $model->image) ?>" 
                     alt="<?= Html::encode($model->name) ?>" 
                     class="img-thumbnail" 
                     style="max-width: 200px;">
                <div class="form-text">Upload gambar baru untuk mengganti gambar ini</div>
            </div>
        <?php endif; ?>
        
        <div class="form-text">Format: JPG, PNG, GIF. Max 2MB</div>
    </div>

    <!-- TOMBOL SUBMIT -->
    <div class="form-group mt-4">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . ($model->isNewRecord ? 'Tambah Produk' : 'Update Produk'), [
            'class' => 'btn btn-success'
        ]) ?>
        <?= Html::a('<i class="fas fa-times"></i> Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<style>
.product-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.product-form .form-label {
    font-weight: 600;
    color: #2c3e50;
}

.product-form .form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}
</style>
