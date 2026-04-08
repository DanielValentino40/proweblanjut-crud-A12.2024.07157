<!-- http://localhost/tambah.php -->

<?php
    include 'koneksi.php';
    $error = '';
    $upload_dir = __DIR__ . '/uploads';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

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
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . "/" . $foto)) {
                    $error = "Gagal menyimpan file upload.";
                }
            }
        }
        $tanggal_masuk = $_POST['tanggal_masuk'];
        if (!$error) {
            $stmt = $conn->prepare("INSERT INTO barang (nama_barang, jumlah, harga, tanggal_masuk, foto) 
                        VALUES (:nama_barang, :jumlah, :harga, :tanggal_masuk, :foto)");
            $stmt->bindParam(':nama_barang', $nama_barang);
            $stmt->bindParam(':jumlah', $jumlah);
            $stmt->bindParam(':harga', $harga);
            $stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
            $stmt->bindParam(':foto', $foto);
            $stmt->execute();
            header("Location: index.php");
            exit;
        }
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
        <?php if ($error): ?>
            <p style="color:#d93025; margin-bottom:12px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
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
                <img id="foto-preview" src="" alt="Preview foto" style="display:none; margin-top:10px; width:120px; height:120px; object-fit:cover; border-radius:6px;">
            </div>

            <button type="submit">Simpan</button>
        </form>
    </div>
    <script>
        const fotoInput = document.querySelector('input[name="foto"]');
        const preview = document.getElementById('foto-preview');

        if (fotoInput && preview) {
            fotoInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) {
                    preview.style.display = 'none';
                    preview.src = '';
                    return;
                }
                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.style.display = 'block';
            });
        }
    </script>
</body>
</html>