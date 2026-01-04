<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Category $model */

$this->title = 'Update Kategori: ' . $model->name;
// $this->params['breadcrumbs'][] = ['label' => 'Kategori', 'url' => ['index']];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
