<!-- http://localhost/edit.php -->

<?php
    include 'koneksi.php';
    $id = $_GET['id'];
    $barang = $conn->query("SELECT * FROM barang WHERE id=$id")->
    fetch_assoc();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $conn->query("UPDATE barang SET nama_barang='$nama_barang', jumlah='$jumlah', harga='$harga', tanggal_masuk='$tanggal_masuk' WHERE id=$id");
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<body>
    <h2>Edit Barang</h2>
    <form method="POST">
        Nama Barang: <input type="text" name="nama_barang" value="<?= $barang['nama_barang'] ?>"
        required><br>
        Jumlah: <input type="number" name="jumlah" value="<?= $barang['jumlah'] ?>"
        required><br>
        Harga: <input type="number" name="harga" value="<?= $barang['harga'] ?>"
        required><br>
        Tanggal Masuk: <input type="date" name="tanggal_masuk" value="<?= $barang['tanggal_masuk'] ?>"
        required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
