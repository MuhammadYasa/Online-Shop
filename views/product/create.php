<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var app\models\Category[] $categories */
/** @var app\models\Tag[] $tags */

$this->title = 'Tambah Produk Baru';
// $this->params['breadcrumbs'][] = ['label' => 'Produk', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-plus-circle"></i> <?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'tags' => $tags,
    ]) ?>

</div>
