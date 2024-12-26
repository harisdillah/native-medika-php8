<?php

date_default_timezone_set('Asia/Jakarta');
$cari = isset($_GET['cari']) ? $_GET['cari'] : null;

include '../templet/header.php';
include '../inc/koneksi.php';


// Ambil item laporan laba Rugi 
$stmtItems = $koneksi->prepare("SELECT 
    a.kd_obat, 
    a.margin, 
    a.nm_obat, 
    a.sat_obat, 
    b.nobatch, 
    b.hrga_beli, 
    a.hrg_obat, 
    c.pajakper,
    -- Menghitung total pembelian (harga beli dikali jumlah)
    SUM(b.hrga_beli * b.jumlah) AS total_pembelian
   
FROM 
    tbl_dataobat a
INNER JOIN 
    detail_pembelian b ON a.kd_obat = b.kode_brg
INNER JOIN 
    tbl_pembelian c ON b.no_trans = c.no_tranxp
GROUP BY 
    a.kd_obat, a.nm_obat, a.sat_obat
ORDER BY 
    a.nm_obat
	");
//$stmtItems->bindParam(':invoice_id', $cari, PDO::PARAM_INT);
$stmtItems->execute();
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LR: <?php echo $cari; ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .judul {
            text-align: center;
            /* Teks rata tengah */
            color: #2c3e50;
            /* Warna teks (biru tua) */
            font-size: 24px;
            /* Ukuran font */
            font-weight: bold;
            /* Teks tebal */
            margin: 20px 0;
            /* Jarak atas dan bawah */
        }

        .subjudul {
            text-align: center;
            font-size: 18px;
            /* Ukuran font untuk subjudul */
            color: #555;
            /* Warna teks abu-abu */
            margin-bottom: 10px;
            /* Jarak bawah subjudul */
        }

        .line {
            width: 50%;
            /* Lebar garis */
            margin: 10px auto;
            /* Tengah secara horizontal */
            border-top: 2px solid #000;
            /* Garis pemisah di bawah judul */
        }

        .kiri {
            text-align: left;
            /* Teks rata kiri */
            color: #e74c3c;
            /* Warna teks (merah) */
            font-size: 18px;
            /* Ukuran font */
        }

        .kanan {
            text-align: right;
            /* Teks rata kanan */
            color: #27ae60;
            /* Warna teks (hijau) */
            font-size: 18px;
        }

        .justify {
            text-align: justify;
            /* Teks rata kiri dan kanan */
            color: #34495e;
            /* Warna teks (abu-abu gelap) */
            font-size: 16px;
            line-height: 1.6;
            /* Memberikan jarak antar baris */
        }



        .header-container {
            display: flex;
            justify-content: space-between;
            /* Header kanan dan kiri dipisahkan */
            align-items: flex-start;
            /* Menyelaraskan elemen di bagian atas */
            margin-bottom: 20px;
        }

        .header-left,
        .header-right {
            width: 48%;
            /* Header kiri dan kanan masing-masing menggunakan 48% dari lebar */
        }

        .header-left h2,
        .header-left p,
        .header-right p {
            margin: 0;
        }

        .header-right {
            text-align: right;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .header-table th,
        .header-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .header-table th {
            background-color: #f2f2f2;
        }

        .separator {
            border-bottom: 2px solid #000;
            margin: 20px 0;
        }

        .ttd {
            margin-top: 50px;
            width: 100%;
            text-align: center;
        }

        .ttd td {
            padding: 20px;
        }

        .ttd .line {
            margin-top: 50px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 200px;
        }


        .ttd-table td {
            padding: 50px;
            /* Memberi jarak antar tanda tangan */
            border: none;
            /* Menghapus border */
        }
    </style>
    </style>
</head>

<body>

    <div class="judul">
        <?= "Laba Rugi" ?>
    </div>



    <?php

    ?>
    <h3>Rincian Laba Rugi</h3>
    <table class='header-table'>
        <thead>
            <tr>
                <th class='col1' style=text-align: center;'>No.</th>
                <th class='col2'>KD Brg</th>
                <th class='col2'>Nama Barang</th>
                <th class='col3'>Harga Beli / table</th>
                <th class='col3'>PPN</th>
                <th class='col3'>Margin</th>
                <th class='col4'>Harga Jual</th>
                <th class='col4'>Laba</th>
            </tr>
        </thead>

        <?php
        $no = 1;

        foreach ($items as $row) {
            // $saldo += $row['awal'] + $row['masuk'] - $row['keluar'];
            $margin = $row['hrga_beli'] * $row['margin']/100;
            $pajak = ($row['pajakper']/100)* $row['hrga_beli'];
            $harga_jual = $row['hrga_beli'] + $margin;
            //$laba  =  $row['hrg_obat'] - $row['hrga_beli'] -  $pajak;
            $laba  =  $row['hrg_obat'] - $row['hrga_beli'];
            // $itemTotal = $row['awal'];
            // $itemMasuk = $row['masuk'];
            // $itemKeluar = $row['keluar'];
            //$totalAwal += $itemTotal;

            echo "<tr>
                    <td>{$no}</td>    
                    <td>{$row['kd_obat']}</td>
                    <td align='left'>{$row['nm_obat']}</td>
                    <td style='text-align: right;'>{$row['hrga_beli']}</td>
                    <td style='text-align: right;'>{$pajak}</td>
                    <td style='text-align: right;'>{$row['margin']}<br>$margin</td>
                    <td style='text-align: right;'>{$row['hrg_obat']}<br>$harga_jual</td>
                    <td style='text-align: right;'>{$laba}</td>
              </tr>";
            // $totalAwal += $itemTotal;
            // $totalMasuk += $itemMasuk;
            // $totalKeluar += $itemKeluar;
            // $totalSisa += $saldo;
            $no++;
        }
        ?>
    </table>


</body>

</html>