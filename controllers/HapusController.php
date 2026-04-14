<?php
include __DIR__ . '/../public/koneksi.php';
include __DIR__ . '/../models/BarangModel.php';

$model      = new BarangModel($conn);
$id         = $_GET['id'] ?? null;
$upload_dir = __DIR__ . '/../uploads';

// Hapus foto dari folder jika ada
$barang = $model->getBarangById($id);
if (!empty($barang['foto']) && file_exists($upload_dir . '/' . $barang['foto'])) {
    @unlink($upload_dir . '/' . $barang['foto']);
}

$model->hapusBarang($id);
header('Location: index.php?page=barang');
exit;
?>