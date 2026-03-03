<!-- http://localhost/tambah.php -->

<?php
    include 'koneksi.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
        $tanggal_masuk = $_POST['tanggal_masuk'];
        $conn->query("INSERT INTO barang (nama_barang, jumlah, harga, tanggal_masuk) VALUES ('$nama_barang', '$jumlah', '$harga', '$tanggal_masuk')");
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
<body>
    <h2>Tambah Pengguna</h2>
    <form method="POST">
        Nama Barang: <input type="text" name="nama_barang" required><br>
        Jumlah: <input type="number" name="jumlah" required><br>
        Harga: <input type="number" name="harga" required><br>
        Tanggal Masuk: <input type="date" name="tanggal_masuk" required><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>