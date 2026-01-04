<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Tag $model */

$this->title = 'Tambah Tag';
$this->params['breadcrumbs'][] = ['label' => 'Tag', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-plus-circle"></i> <?= Html::encode($this->title) ?></h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Contoh: Promo, Best Seller']) ?>

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-times"></i> Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
