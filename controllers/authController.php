<?php
include __DIR__ . '/../public/koneksi.php';
include __DIR__ . '/../models/UserModel.php';

$model = new UserModel($conn);
$error = '';

// Cek session
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=barang');
    exit;
}

// Cek cookie
if (empty($_SESSION['user_id']) && !empty($_COOKIE['user_id'])) {
    $user = $model->getUserById($_COOKIE['user_id']);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        header('Location: index.php?page=barang');
        exit;
    } else {
        setcookie('user_id',   '', time() - 3600, '/');
        setcookie('user_name', '', time() - 3600, '/');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $passw = $_POST['passw'];
    $user  = $model->getUserByName($name);

    if ($user) {
        if (password_verify($passw, $user['passw'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];

            if (isset($_POST['remember_me'])) {
                setcookie('user_id',   $user['id'],   time() + (7 * 24 * 60 * 60), '/');
                setcookie('user_name', $user['name'], time() + (7 * 24 * 60 * 60), '/');
            }

            header('Location: index.php?page=barang');
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}

include __DIR__ . '/../views/auth/login.php';
?>