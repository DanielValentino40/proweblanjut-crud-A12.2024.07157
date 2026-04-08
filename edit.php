<!-- http://localhost/edit.php -->

<?php
    include 'koneksi.php';
    $error = '';
    $id = $_GET['id'];
    $barang = $conn->prepare("SELECT * FROM barang WHERE id=:id");
    $barang->bindParam(':id', $id);
    $barang->execute();
    $barang = $barang->fetch(PDO::FETCH_ASSOC);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
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
        if (empty($nama_barang)) $error = "Nama barang tidak boleh kosong!";
        if (!is_numeric($jumlah) || $jumlah < 0) $error = "Jumlah harus angka positif!";
        if (!is_numeric($harga) || $harga < 0) $error = "Harga harus angka positif!";
        $stmt = $conn->prepare("UPDATE barang SET nama_barang=:nama_barang, jumlah=:jumlah, harga=:harga, tanggal_masuk=:tanggal_masuk WHERE id=:id");
    $stmt->bindParam(':nama_barang', $nama_barang);
    $stmt->bindParam(':jumlah', $jumlah);
    $stmt->bindParam(':harga', $harga);
    $stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
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

            <div class="form-group">
                Foto Barang:
                <input type="file" name="foto" accept="image/*"
                    value="<?= htmlspecialchars($barang['foto']) ?>"><br>
            </div>

            <button type="submit">Update</button>
        </form>
    </div>    
</body>
</html>
