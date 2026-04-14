<?php
session_start();

$page = $_GET['page'] ?? 'login';

// Proteksi halaman — wajib login dulu
$protected = ['barang', 'tambah', 'edit', 'hapus'];
if (in_array($page, $protected) && empty($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

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
    case 'register':
        include 'controllers/RegisterController.php';
        break;
    case 'logout':
        include 'controllers/LogoutController.php';
        break;
    default:
        include 'controllers/AuthController.php';
        break;
}
?>