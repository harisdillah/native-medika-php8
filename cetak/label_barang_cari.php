<?php
date_default_timezone_set('Asia/Jakarta');
$cari = isset($_GET['cari']) ? $_GET['cari'] : null;

$tgl_awal = date("Y-01-01");
$tgl_akhir = date("Y-m-d");

$title = 'Label : ' . $cari;
include '../templet/header.php';
// data pangil Koneksi
require '../inc/koneksi.php';
// fuctions vIEW
include $view;
$lihat = new view($koneksi);


if ($cari) {
    // data
    $barang = $lihat->dataLabelbarang($cari);
}
?>


</head>

<body>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .label-container {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            /* 6 kolom */
            grid-auto-rows: 150px;
            /* Tinggi label */
            gap: 10px;
            /* Jarak antar label */
            margin-top: 20px;
        }

        .label-item {
            width: 200px;
            height: 150px;
            page-break-inside: avoid;
            /* Mencegah label terpotong */
        }

        .label-item h3 {
            font-size: 14px;
            margin: 0 0 10px;
        }


        /* .label-item img {
		max-width: 100%;
		height: auto;
	}  */

        .label-item img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            /* Supaya barcode menyesuaikan ukuran */
            height: auto;
        }

        /* .label-item small {
		display: block;
		font-size: 12px;
		margin-top: 5px;
	}

	@media print {
		.label-item {
			page-break-inside: avoid;
		}

		.label-container {
			break-inside: avoid;
		}
	} */


        @media print {
            .label-container {
                break-inside: avoid;
            }
        }
    </style>

    <?php

    try {

        if ($barang) {


            echo "<h1>Cetak Label 6x5</h1>";
            echo '<div class="label-container">';


            foreach ($barang as $r_data) {

                for ($x = 1; $x <= 30; $x++) {
                    //echo '<div class="label-item">';

                    echo '<div style="border: 1px solid #000; padding: 10px; margin: 10px; width: 200px; text-align: center;" class="label-item">';
                    echo '<h3>' . htmlspecialchars($r_data['nm_obat']) . '</h3>';
                    // Generate barcode dalam format PNG
                    //$barcode = base64_encode($generator->getBarcode($row['barcode'], $generator::TYPE_CODE_128));
                    //echo '<img src="data:image/png;base64,' . $barcode . '" alt="Barcode"><br>';
                    //echo '<small>' . htmlspecialchars($row['barcode']) . '</small>';
                    echo '<img src="../inc/barcode.php?text=' . $r_data['kd_obat'] . '&codetype=code128&print=true&size=40"class="label-item img"><br>';
                    echo '</div>';
                }
            }

            echo '</div>'; // Penutup div label-container
            echo "</body></html>";
            echo '<br>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
                      <strong>Data Barang - </strong> Tidak ada data yang ditemukan
                    </div>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    ?>




    <!-- <p>Tanggal Faktur: <?php date('d-m-Y', strtotime(date('d-m-Y H:i:s')))  ?></p> -->
    <p>Tanggal : <?php echo date('d-m-Y H:i:s'); // Hasil: 20-01-2017 05:32:15  
                    ?></p>
    <!-- <div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                Company Slogan
            </td>
        </tr>

    </table>
</div> -->