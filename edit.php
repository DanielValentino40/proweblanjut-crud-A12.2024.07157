<!-- http://localhost/edit.php -->

<?php
    include 'koneksi.php';
    $id = $_GET['id'];
    $barang = $conn->query("SELECT * FROM barang WHERE id=$id")->
    fetch(PDO::FETCH_ASSOC);
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
    <head>
        <title>Form Edit Barang</title>
        <link rel="stylesheet" href="css.css">
    </head>
<body>
    <div class="container">
        <h2>Edit Barang</h2>
        <form method="POST">
            <div class="form-group">
                Nama Barang: 
                <input type="text" name="nama_barang"
                    value="<?= htmlspecialchars($barang['nama_barang']) ?>" required><br>
            </div>

            <div class="form-group">
                Jumlah: 
                <input type="number" name="jumlah"
                    value="<?= htmlspecialchars($barang['jumlah']) ?>" required><br>
            </div>

            <div class="form-group">
                Harga: 
                <input type="number" name="harga"
                    value="<?= htmlspecialchars($barang['harga']) ?>" required><br>
            </div>

            <div class="form-group">
                Tanggal Masuk: 
                <input type="date" name="tanggal_masuk"
                    value="<?= htmlspecialchars($barang['tanggal_masuk']) ?>" required><br>
            </div>

            <button type="submit">Update</button>
        </form>
    </div>    
</body>
</html>
