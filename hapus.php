<!-- http://localhost/hapus.php -->

<?php
    include 'koneksi.php';
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM barang WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: index.php");
?>
