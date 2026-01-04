<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CustomerLoginForm $model */

$this->title = 'Login Customer';
?>

<div class="customer-login">
    
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Login Customer</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email']) ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="d-grid">
                        <?= Html::submitButton('<i class="fas fa-sign-in-alt"></i> Login', ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                    
                </div>
                <div class="card-footer text-center">
                    Belum punya akun? 
                    <a href="<?= \yii\helpers\Url::to(['site/register']) ?>">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>

</div>
