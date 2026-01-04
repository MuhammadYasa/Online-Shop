<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Customer $model */

$this->title = 'Edit Profile';
?>

<div class="customer-profile">
    
    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit"></i> Edit Profile</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php $form = ActiveForm::begin(); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Nama Lengkap']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'readonly' => true])->hint('Email tidak dapat diubah') ?>
                        </div>
                    </div>
                    
                    <?= $form->field($model, 'phone')->textInput(['placeholder' => '08xxxxxxxxxx']) ?>
                    
                    <?= $form->field($model, 'address')->textarea(['rows' => 3, 'placeholder' => 'Alamat lengkap untuk pengiriman']) ?>
                    
                    <hr>
                    
                    <h5 class="mb-3"><i class="fas fa-key"></i> Ubah Password (Opsional)</h5>
                    <p class="text-muted small">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password baru (min 6 karakter)'])->label('Password Baru') ?>
                    
                    <div class="d-grid gap-2 mt-4">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Simpan Perubahan', ['class' => 'btn btn-success btn-lg']) ?>
                        <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>

                    <?php ActiveForm::end(); ?>
                    
                </div>
            </div>
            
        </div>
    </div>

</div>

<style>
.card {
    border: none;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>
