<?php

date_default_timezone_set('Asia/Jakarta');
$cari = isset($_GET['cari']) ? $_GET['cari'] : null;

include '../inc/koneksi.php';

$stmt = $koneksi->prepare("");
$stmt->bindParam(':id', $cari, PDO::PARAM_INT);
$stmt->execute();
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil item terkait dengan faktur
$stmtItems = $koneksi->prepare("SELECT
	t.kode_brg, 
	t.nama, 
	t.jumlah, 
	t.satuan, 
	t.no_keluar
FROM
	detail_keluar AS t
	INNER JOIN
	m_barang
	ON 
		t.kode_brg = m_barang.kode_brg WHERE t.no_keluar = :invoice_id");
$stmtItems->bindParam(':invoice_id', $cari, PDO::PARAM_INT);
$stmtItems->execute();
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBK: <?php echo $cari; ?></title>

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
        <?= "Bukti Barang Keluar" ?>
    </div>
    <!-- Header Kanan dan Kiri -->


    <!-- <div class="header-container">
        <?php

        try {

            if ($invoice) {
                echo "    
        <!-- Header Kiri -->
        <div class='header-left'>
            <h2>" . htmlspecialchars($invoice['ket']) . "</h2>
            <!-- <p>Alamat: Jl. Contoh No. 123</p>
            <p>Kota: Jakarta</p>
            <p>Telepon: (021) 123-4567</p> -->
        </div>

        <!-- Header Kanan -->
        <div class='header-right'>
            <p><strong>Nomor:" . htmlspecialchars($invoice['no_bbk']) . "</strong></p>
            <p><strong>Tanggal:</strong>" . htmlspecialchars($invoice['tgl_keluar']) . "</p>
            <p><strong>Nama Pemesan:<br>" . htmlspecialchars($invoice['nama']) . "</strong> </p>
        </div>";
            } else {
                echo '<div class="alert alert-danger" role="alert">
        <strong>Data header Keluar - </strong> Tidak ada data yang ditemukan
      </div>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div> -->

    <div class='separator'></div>

    <!-- <p class='kiri'>
        Ini adalah paragraf yang diratakan ke kiri. Teks ini cenderung dimulai dari batas kiri tanpa mengikuti lebar kontainer secara simetris.
    </p> -->

    <?php

    ?>
    <h3>Rincian Barang Keluar</h3>
    <table class='header-table'>
        <thead>
            <tr>
                <th class='col1' style=text-align: center;'>No.</th>
                <th class='col2'>Nama Barang</th>
                <th class='col3'>Jumlah</th>
                <th class='col4'>Satuan</th>
            </tr>
        </thead>
        <!-- <tbody>
            <?php
            $i = 1;
            $total = 0;

            foreach ($items as $row):

                //{

                $itemJumlah = number_format($row['jumlah'], 0, ',', '.');
                $itemTotal = $row['jumlah'];

                echo "<tr>
                <td>" . $i++ . "</td>
                <td align='left'>{$row['nama']}</td>
                <td style='text-align: right;'>$itemJumlah</td>
                <td>{$row['satuan']}</td>
              </tr>";
                $total += $itemTotal;
            // $totalMasuk += $itemMasuk;
            // $totalKeluar += $itemKeluar;
            // $totalSisa += $saldo;
            //}
            endforeach;
            ?>

            <tr>
                <td colspan="2" style="text-align: right;"><strong>Jml Brg</strong></td>
                <td style="text-align: right;"><strong><?= number_format($total, 0, ',', '.') ?></strong></td>
            </tr>
        </tbody> -->
    </table>

    <table class='ttd-table'>
        <tr>

            <td>
                <strong>Atasan Langsung</strong>
                <br><br><br>
                ()
            </td>
            <td>
                <strong>Pemegang Barang</strong>
                <br><br><br>
                ()
            </td>
            <td>

                <?php

                try {

                    if ($invoice) {
                        echo "    
                <td>
                <strong>Peminta Barang</strong>
                <br><br><br>
                ({$invoice['nama']})
            </td>
                ";
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                <strong>Data header Keluar - </strong> Tidak ada data yang ditemukan
              </div>';
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </td>
        </tr>
    </table>
</body>

</html>