<!-- http://localhost/tambah.php -->

<?php
    include 'koneksi.php';
    $error = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
        if (empty($nama_barang)) $error = "Nama barang tidak boleh kosong!";
        if (!is_numeric($jumlah) || $jumlah < 0) $error = "Jumlah harus angka positif!";
        if (!is_numeric($harga) || $harga < 0) $error = "Harga harus angka positif!";
        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($_FILES['foto']['type'], $allowed_types)) {
                $error = "Tipe file tidak diizinkan! Hanya JPG, PNG, GIF, WEBP.";
            } elseif ($_FILES['foto']['size'] > $max_size) {
                $error = "Ukuran file terlalu besar! Maksimal 2MB.";
            } else {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = uniqid() . '.' . $ext; // nama unik agar tidak bentrok
                move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);
            }
        }
        $tanggal_masuk = $_POST['tanggal_masuk'];
        $stmt = $conn->prepare("INSERT INTO barang (nama_barang, jumlah, harga, tanggal_masuk, foto) 
                    VALUES (:nama_barang, :jumlah, :harga, :tanggal_masuk, :foto)");
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
        <form method="POST" enctype="multipart/form-data">
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

            <div class="form-group">
                Foto Barang: <input type="file" name="foto" accept="image/*"><br>
            </div>

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>