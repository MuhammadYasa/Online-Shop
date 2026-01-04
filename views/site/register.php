<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Customer $model */

$this->title = 'Register Customer';
?>

<div class="customer-register">
    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> Daftar Akun Baru</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Nama Lengkap']) ?>

                    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email']) ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password (min 6 karakter)']) ?>

                    <?= $form->field($model, 'phone')->textInput(['placeholder' => '08xxxxxxxx']) ?>

                    <?= $form->field($model, 'address')->textarea(['rows' => 3, 'placeholder' => 'Alamat lengkap']) ?>

                    <div class="d-grid">
                        <?= Html::submitButton('<i class="fas fa-user-plus"></i> Daftar', ['class' => 'btn btn-success btn-lg']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                    
                </div>
                <div class="card-footer text-center">
                    Sudah punya akun? 
                    <a href="<?= \yii\helpers\Url::to(['site/customer-login']) ?>">Login Sekarang</a>
                </div>
            </div>
        </div>
    </div>

</div>
