<form method="post">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

    <label>Nama Category</label><br>
    <input type="text" name="Category[name]" value="<?= $model->name ?? '' ?>">
    <br><br>
    <button type="submit">Simpan</button>
</form>