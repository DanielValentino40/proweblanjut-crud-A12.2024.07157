<!-- http://localhost/tambah.php -->

<?php
    include 'koneksi.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
        $tanggal_masuk = $_POST['tanggal_masuk'];
        $stmt = $conn->prepare("INSERT INTO barang (nama_barang, jumlah, harga, tanggal_masuk) 
                    VALUES (:nama_barang, :jumlah, :harga, :tanggal_masuk)");
        $stmt->bindParam(':nama_barang', $nama_barang);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
        $stmt->execute();
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Form Tambah Barang</title>
        <link rel="stylesheet" href="css.css">
    </head>
<body>
    <div class="container">
        <h2>Tambah Barang</h2>
        <form method="POST">
            <div class="form-group">
                Nama Barang: <input type="text" name="nama_barang" required><br>
            </div>

            <div class="form-group">
                Jumlah: <input type="number" name="jumlah" required><br>
            </div>

            <div class="form-group">
                Harga: <input type="number" name="harga" required><br>
            </div>

            <div class="form-group">
                Tanggal Masuk: <input type="date" name="tanggal_masuk" required><br>
            </div>

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>