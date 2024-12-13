<?php
include '../inc/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $margin = $_POST['margin'];

    // Update margin barang
    $sql = "UPDATE tbl_dataobat SET margin = :margin WHERE kd_obat = :id";
    $stmt = $koneksi->prepare($sql);
    $stmt->bindParam(':margin', $margin, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect setelah update sukses
        header('Location: index.php?status=sukses');
    } else {
        // Redirect dengan status gagal
        header('Location: index.php?status=gagal');
    }
}
