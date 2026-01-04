<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Category $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="category-form">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-folder"></i> 
                <?= $model->isNewRecord ? 'Form Tambah Kategori' : 'Form Edit Kategori' ?>
            </h5>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'needs-validation'],
            ]); ?>

            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Contoh: Elektronik, Fashion, Makanan & Minuman',
                        'class' => 'form-control form-control-lg'
                    ])->label('<i class="fas fa-tag"></i> Nama Kategori', ['class' => 'fw-bold']) ?>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Tips:</strong> Gunakan nama kategori yang jelas dan mudah dipahami pelanggan.
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-lightbulb text-warning"></i> Contoh Kategori:</h6>
                            <ul class="small mb-0">
                                <li>Elektronik</li>
                                <li>Fashion</li>
                                <li>Makanan & Minuman</li>
                                <li>Olahraga</li>
                                <li>Kecantikan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group mb-0">
                <?= Html::submitButton(
                    '<i class="fas fa-save"></i> ' . ($model->isNewRecord ? 'Simpan Kategori' : 'Update Kategori'),
                    ['class' => 'btn btn-primary btn-lg me-2']
                ) ?>
                <?= Html::a(
                    '<i class="fas fa-times"></i> Batal',
                    ['index'],
                    ['class' => 'btn btn-secondary btn-lg']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="card-footer text-muted">
            <small><i class="fas fa-shield-alt"></i> Semua data akan tersimpan dengan aman</small>
        </div>
    </div>

</div>

<style>
.category-form .form-control-lg {
    font-size: 1.1rem;
    padding: 0.75rem 1rem;
}

.category-form .card {
    border-radius: 10px;
    overflow: hidden;
}

.category-form .card-header {
    border-bottom: 3px solid rgba(255,255,255,0.2);
}

.category-form .form-label {
    font-size: 1rem;
    margin-bottom: 0.75rem;
}

.category-form .btn-lg {
    padding: 0.6rem 2rem;
    font-weight: 600;
    border-radius: 8px;
}

.category-form .alert-info {
    border-left: 4px solid #0dcaf0;
    background: #e7f6fd;
    border-radius: 8px;
}
</style>