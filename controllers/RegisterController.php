<?php
include __DIR__ . '/../koneksi.php';
include __DIR__ . '/../models/UserModel.php';

$model   = new UserModel($conn);
$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = $_POST['name'];
    $email            = $_POST['email'];
    $passw            = $_POST['passw'];
    $confirm_password = $_POST['confirm_password'];

    if ($passw !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } elseif (strlen($passw) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $passw_hash = password_hash($passw, PASSWORD_DEFAULT);
        if ($model->registerUser($name, $email, $passw_hash)) {
            $success = true;
        } else {
            $error = "Gagal mendaftar!";
        }
    }
}

include __DIR__ . '/../views/auth/register.php';
?>