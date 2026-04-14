<!DOCTYPE html>
<html>
    <head>
        <title>CRUD PHP Inventaris MySQL</title>
        <link rel="stylesheet" href="css.css">
    </head>
    <body>
        <div class="container">
            <h2>Daftar Barang</h2>
            <a href="index.php?page=tambah">Tambah Barang</a>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Barang</th>
                    <th>Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($barang as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['harga'] ?></td>
                    <td><?= $row['tanggal_masuk'] ?></td>
                    <td>
                        <?php if ($row['foto']): ?>
                            <img src="../uploads/thumbnails/<?= $row['foto'] ?>" width="60" height="60"
                                style="object-fit:cover; border-radius:4px;">
                        <?php else: ?>
                            <span style="color:#999">Tidak ada foto</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?page=edit&id=<?= $row['id'] ?>">✏️Edit</a> |
                        <a href="index.php?page=hapus&id=<?= $row['id'] ?>"
                           onclick="return confirm('Hapus data?')">🗑️Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <a href="index.php?page=logout">Logout</a>
        </div>
    </body>
</html>