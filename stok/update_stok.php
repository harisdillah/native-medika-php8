<?php
// Koneksi ke database menggunakan PDO
// $host = 'localhost'; // Ganti dengan host Anda
// $dbname = 'nama_database'; // Ganti dengan nama database Anda
// $username = 'username'; // Ganti dengan username Anda
// $password = 'password'; // Ganti dengan password Anda

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo "Koneksi gagal: " . $e->getMessage();
//     exit();
// }

include '../inc/koneksi.php';
// Memeriksa apakah form telah disubmit
if (isset($_POST['submit'])) {
    if (isset($_POST['stok'])) {
        foreach ($_POST['stok'] as $id_barang => $margin) {
            // Update margin di database
            $query = "UPDATE detail_opname SET jml_opname = :margin WHERE kode_brg = :id";
            $stmt = $koneksi->prepare($query);
            $stmt->execute([
                ':margin' => $margin,
                ':id' => $id_barang
            ]);
            // if ($stmt->execute()) {
            //     // Redirect setelah update sukses
            //     header('Location: x.php?status=sukses');
            // } else {
            //     // Redirect dengan status gagal
            //     header('Location: x.php?status=gagal');
            // }
        }

        header('Location: index.php?status=sukses');




        //echo "Data margin berhasil diperbarui!";
        //     echo '<div class="alert alert-danger" role="alert">
        //     <strong>Data Barang - </strong> Data margin berhasil diperbarui!
        //   </div>';
    }
}
