<!-- http://localhost/index.php -->

<?php
    include 'koneksi.php';
    $result = $conn->query("SELECT * FROM barang");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CRUD PHP Inventaris MySQL</title>
        <link rel="stylesheet" href="css.css">
    </head>
    <body>
        <div class="container">
            <h2>Daftar Barang</h2>
            <a href="tambah.php">Tambah Barang</a>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Barang</th>
                    <th>Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['harga'] ?></td>
                    <td><?= $row['tanggal_masuk'] ?></td>
                    <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">✏️Edit</a> |
                    <a href="hapus.php?id=<?= $row['id'] ?>" onclick=" return confirm('Hapus data?')">🗑️Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <br>
            <a href="login.php">Logout</a>
        </div>
    </body>
</html>