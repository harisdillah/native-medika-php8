<?php
// Koneksi ke database menggunakan PDO
$host = 'localhost'; // Ganti dengan host Anda
$dbname = 'db_medika'; // Ganti dengan nama database Anda
$username = 'root'; // Ganti dengan username Anda
$password = ''; // Ganti dengan password Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit();
}

// Ambil data barang dari database
$query = "SELECT * FROM tbl_dataobat";
$stmt = $pdo->prepare($query);
$stmt->execute();
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Laporan Laba Rugi</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th> <!-- Kolom nomor urut -->
                    <th>KD Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>PPN 11%</th>
                    <th>Margin</th>
                    <th>Harga Jual</th>
                    <th>Laba</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; // Inisialisasi nomor urut 
                ?>
                <?php foreach ($barang as $row): ?>
                    <?php
                    $harga_beli = $row['hrgbeli_obat'];
                    $margin = '0.22';
                    //$margin = $row['margin'];
                    $ppn = $harga_beli * 0.11;  // Menghitung PPN 11%
                    $harga_jual = $harga_beli + $margin + $ppn;  // Harga jual = harga beli + margin + PPN
                    $laba = $harga_jual - $harga_beli;  // Laba = harga jual - harga beli
                    ?>
                    <tr>
                        <td><?php echo $no++; // Menampilkan nomor urut dan meningkatkannya setiap baris 
                            ?></td>
                        <td><?php echo htmlspecialchars($row['kd_obat']); ?></td>
                        <td><?php echo htmlspecialchars($row['nm_obat']); ?></td>
                        <td class="text-right"><?php echo number_format($harga_beli, 2, ',', '.'); ?></td>
                        <td class="text-right"><?php echo number_format($ppn, 2, ',', '.'); ?></td>
                        <td class="text-right"><?php echo number_format($margin, 2, ',', '.'); ?></td>
                        <td class="text-right"><?php echo number_format($harga_jual, 2, ',', '.'); ?></td>
                        <td class="text-right"><?php echo number_format($laba, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>