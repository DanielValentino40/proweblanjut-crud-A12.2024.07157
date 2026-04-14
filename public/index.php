<?php
session_start();

// Tentukan halaman yang diminta
$page = $_GET['page'] ?? 'barang';

switch ($page) {
    case 'barang':
        include 'controllers/BarangController.php';
        break;

    case 'tambah':
        include 'controllers/TambahController.php';
        break;

    case 'edit':
        include 'controllers/EditController.php';
        break;

    case 'hapus':
        include 'controllers/HapusController.php';
        break;

    case 'login':
        include 'controllers/AuthController.php';
        break;

    case 'logout':
        include 'controllers/LogoutController.php';
        break;

    default:
        include 'controllers/BarangController.php';
        break;
}
?>