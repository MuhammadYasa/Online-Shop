<h1>Category</h1>
<a href="create">Tambah Category</a>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Aksi</th>
</tr>
<?php foreach ($categories as $c): ?>
<tr>
    <td><?= $c->id ?></td>
    <td><?= $c->name ?></td>
    <td>
        <a href="update?id=<?= $c->id ?>">Edit</a> |
        <a href="delete?id=<?= $c->id ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>
</table>