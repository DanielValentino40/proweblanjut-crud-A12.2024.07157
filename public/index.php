<!-- http://localhost/public/index.php -->
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
        include __DIR__ . '/../controllers/BarangController.php';
        break;
    case 'tambah':
        include __DIR__ . '/../controllers/TambahController.php';
        break;
    case 'edit':
        include __DIR__ . '/../controllers/EditController.php';
        break;
    case 'hapus':
        include __DIR__ . '/../controllers/HapusController.php';
        break;
    case 'login':
        include __DIR__ . '/../controllers/AuthController.php';
        break;
    case 'register':
        include __DIR__ . '/../controllers/RegisterController.php';
        break;
    case 'logout':
        include __DIR__ . '/../controllers/LogoutController.php';
        break;
    default:
        include __DIR__ . '/../controllers/AuthController.php';
        break;
}
?>