<?php
include __DIR__ . '/../public/koneksi.php';
include __DIR__ . '/../models/BarangModel.php';

$model      = new BarangModel($conn);
$error      = '';
$id         = $_GET['id'] ?? null;
$upload_dir = __DIR__ . '/../uploads';
$barang     = $model->getBarangById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang   = $_POST['nama_barang'];
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $foto          = $barang['foto'];
    $hapus_foto    = isset($_POST['hapus_foto']) && $_POST['hapus_foto'] === '1';

    if (empty($nama_barang)) $error = "Nama barang tidak boleh kosong!";
    if (!is_numeric($jumlah) || $jumlah < 0) $error = "Jumlah harus angka positif!";
    if (!is_numeric($harga)  || $harga  < 0) $error = "Harga harus angka positif!";

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size      = 2 * 1024 * 1024;
        $file_type     = mime_content_type($_FILES['foto']['tmp_name']);

        if (!in_array($file_type, $allowed_types, true)) {
            $error = "Tipe file tidak diizinkan!";
        } elseif ($_FILES['foto']['size'] > $max_size) {
            $error = "Ukuran file maksimal 2MB!";
        } else {
            // Hapus foto lama
            if (!empty($barang['foto']) && file_exists($upload_dir . '/' . $barang['foto'])) {
                @unlink($upload_dir . '/' . $barang['foto']);
            }
            $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . '/' . $foto);
        }
    } elseif ($hapus_foto) {
        if (!empty($barang['foto']) && file_exists($upload_dir . '/' . $barang['foto'])) {
            @unlink($upload_dir . '/' . $barang['foto']);
        }
        $foto = null;
    }

    if (!$error) {
        $model->editBarang($id, $nama_barang, $jumlah, $harga, $tanggal_masuk, $foto);
        header('Location: index.php?page=barang');
        exit;
    }
}

include __DIR__ . '/../views/barang/edit.php';
?>