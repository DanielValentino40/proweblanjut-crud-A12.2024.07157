<?php
include __DIR__ . '/../koneksi.php';
include __DIR__ . '/../models/BarangModel.php';

$model  = new BarangModel($conn);

$barang = $model->getAllBarang();

include __DIR__ . '/../views/barang/index.php';
?>