<?php
class BarangModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Ambil semua barang
    public function getAllBarang() {
        $stmt = $this->conn->prepare("SELECT * FROM barang");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil 1 barang berdasarkan id
    public function getBarangById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM barang WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah barang
    public function tambahBarang($nama_barang, $jumlah, $harga, $tanggal_masuk, $foto) {
        $stmt = $this->conn->prepare("INSERT INTO barang (nama_barang, jumlah, harga, tanggal_masuk, foto) 
                                      VALUES (:nama_barang, :jumlah, :harga, :tanggal_masuk, :foto)");
        return $stmt->execute([
            ':nama_barang'   => $nama_barang,
            ':jumlah'        => $jumlah,
            ':harga'         => $harga,
            ':tanggal_masuk' => $tanggal_masuk,
            ':foto'          => $foto
        ]);
    }

    // Edit barang
    public function editBarang($id, $nama_barang, $jumlah, $harga, $tanggal_masuk, $foto) {
        $stmt = $this->conn->prepare("UPDATE barang SET nama_barang=:nama_barang, jumlah=:jumlah, 
                                      harga=:harga, tanggal_masuk=:tanggal_masuk, foto=:foto 
                                      WHERE id=:id");
        return $stmt->execute([
            ':id'            => $id,
            ':nama_barang'   => $nama_barang,
            ':jumlah'        => $jumlah,
            ':harga'         => $harga,
            ':tanggal_masuk' => $tanggal_masuk,
            ':foto'          => $foto
        ]);
    }

    // Hapus barang
    public function hapusBarang($id) {
        $stmt = $this->conn->prepare("DELETE FROM barang WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>