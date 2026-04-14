<!-- http://localhost/edit.php -->

<?php
    include 'koneksi.php';
    $error = '';
    $upload_dir = __DIR__ . '/../uploads/thumbnails';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    function saveCompressedImage($tmp_file, $mime_type, $destination)
    {
        if (!extension_loaded('gd')) {
            return move_uploaded_file($tmp_file, $destination);
        }

        switch ($mime_type) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($tmp_file);
                if (!$image) return false;
                $saved = imagejpeg($image, $destination, 75);
                imagedestroy($image);
                return $saved;

            case 'image/png':
                $image = imagecreatefrompng($tmp_file);
                if (!$image) return false;
                imagealphablending($image, false);
                imagesavealpha($image, true);
                $saved = imagepng($image, $destination, 6);
                imagedestroy($image);
                return $saved;

            case 'image/webp':
                if (!function_exists('imagecreatefromwebp') || !function_exists('imagewebp')) {
                    return move_uploaded_file($tmp_file, $destination);
                }
                $image = imagecreatefromwebp($tmp_file);
                if (!$image) return false;
                $saved = imagewebp($image, $destination, 80);
                imagedestroy($image);
                return $saved;

            case 'image/gif':
                $image = imagecreatefromgif($tmp_file);
                if (!$image) return false;
                $transparentIndex = imagecolortransparent($image);
                if ($transparentIndex >= 0) {
                    imagecolortransparent($image, $transparentIndex);
                }
                $saved = imagegif($image, $destination);
                imagedestroy($image);
                return $saved;

            default:
                return false;
        }
    }

    $id = $_GET['id'];
    $barang = $conn->prepare("SELECT * FROM barang WHERE id=:id");
    $barang->bindParam(':id', $id);
    $barang->execute();
    $barang = $barang->fetch(PDO::FETCH_ASSOC);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
        $foto = $barang['foto'];
        $hapus_foto = isset($_POST['hapus_foto']) && $_POST['hapus_foto'] === '1';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $max_size = 2 * 1024 * 1024; // 2MB
            $file_type = mime_content_type($_FILES['foto']['tmp_name']);

            if (!in_array($file_type, $allowed_types, true)) {
                $error = "Tipe file tidak diizinkan! Hanya JPG, PNG, GIF, WEBP.";
            } elseif ($_FILES['foto']['size'] > $max_size) {
                $error = "Ukuran file terlalu besar! Maksimal 2MB.";
            } else {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $new_foto = uniqid() . '.' . $ext; // nama unik agar tidak bentrok
                $saved = saveCompressedImage(
                    $_FILES['foto']['tmp_name'],
                    $file_type,
                    $upload_dir . "/" . $new_foto
                );
                if (!$saved) {
                    $error = "Gagal menyimpan file upload.";
                } else {
                    if (!empty($barang['foto']) && file_exists($upload_dir . "/" . $barang['foto'])) {
                        @unlink($upload_dir . "/" . $barang['foto']);
                    }
                    $foto = $new_foto;
                }
            }
        } elseif ($hapus_foto) {
            if (!empty($barang['foto']) && file_exists($upload_dir . "/" . $barang['foto'])) {
                @unlink($upload_dir . "/" . $barang['foto']);
            }
            $foto = null;
        }
        $tanggal_masuk = $_POST['tanggal_masuk'];
        if (empty($nama_barang)) $error = "Nama barang tidak boleh kosong!";
        if (!is_numeric($jumlah) || $jumlah < 0) $error = "Jumlah harus angka positif!";
        if (!is_numeric($harga) || $harga < 0) $error = "Harga harus angka positif!";
        if (!$error) {
            $stmt = $conn->prepare("UPDATE barang SET nama_barang=:nama_barang, jumlah=:jumlah, harga=:harga, tanggal_masuk=:tanggal_masuk, foto=:foto WHERE id=:id");
            $stmt->bindParam(':nama_barang', $nama_barang);
            $stmt->bindParam(':jumlah', $jumlah);
            $stmt->bindParam(':harga', $harga);
            $stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
            $stmt->bindParam(':foto', $foto);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: index.php");
            exit;
        }
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
        <?php if ($error): ?>
            <p style="color:#d93025; margin-bottom:12px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
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
                <input type="file" name="foto" accept="image/*"><br>
                <input type="hidden" name="hapus_foto" id="hapus_foto" value="0">
                <?php if (!empty($barang['foto'])): ?>
                    <img id="foto-preview" src="../uploads/thumbnails/<?= htmlspecialchars($barang['foto']) ?>" alt="Preview foto" style="margin-top:10px; width:120px; height:120px; object-fit:cover; border-radius:6px;">
                <?php else: ?>
                    <img id="foto-preview" src="" alt="Preview foto" style="display:none; margin-top:10px; width:120px; height:120px; object-fit:cover; border-radius:6px;">
                <?php endif; ?>
                <button type="button" id="hapus-foto-btn" style="margin-top:8px;">Hapus gambar</button>
            </div>

            <button type="submit">Update</button>
        </form>
    </div>
    <script>
        const fotoInput = document.querySelector('input[name="foto"]');
        const preview = document.getElementById('foto-preview');
        const clearButton = document.getElementById('hapus-foto-btn');
        const hapusFotoInput = document.getElementById('hapus_foto');

        function clearPreview(markDelete = false) {
            if (!fotoInput || !preview || !clearButton || !hapusFotoInput) return;
            fotoInput.value = '';
            preview.style.display = 'none';
            preview.src = '';
            if (markDelete) {
                hapusFotoInput.value = '1';
            }
        }

        if (fotoInput && preview && clearButton && hapusFotoInput) {
            fotoInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) {
                    return;
                }
                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.style.display = 'block';
                hapusFotoInput.value = '0';
            });

            clearButton.addEventListener('click', function () {
                clearPreview(true);
            });
        }
    </script>
</body>
</html>
