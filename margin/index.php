<?php
include '../inc/koneksi.php';
$tgl = '2024-10-30';


$query = "SELECT A.kd_obat,ROUND (LENGTH(A.kd_obat)) AS kt,A.nm_obat,A.sat_obat,A.sat_jual,A.hrg_obat,A.nobatch,A.hrgbeli_obat,A.hrg_obat,
A.idrak,A.margin,
-- ,(B.Awal) as StokAwal, 
Coalesce(B.Jml_fisik,0) as jml_fisik,
Coalesce(H.jml_titipan,0) as jml_titip,
Coalesce(C.tambah,0) as jml_tambah,
Coalesce(D.Keluar,0) as jml_keluar,
Coalesce(E.Returbeli,0) as jml_rbl,
Coalesce(F.Returjual,0) as jml_rjual,
Coalesce(G.Returkonsi,0) as jml_rkonsi,
-- Stok
(COALESCE(B.jml_fisik,0)+Coalesce(C.tambah,0)+Coalesce(H.jml_titipan,0)) -
(Coalesce(D.keluar,0) + Coalesce(E.Returbeli,0)+Coalesce(F.Returjual,0)+Coalesce(G.Returkonsi,0)) as Stok
FROM tbl_dataobat A
/* Opname */
LEFT JOIN	 
-- JUMLAH = jml_opname
(SELECT kode_brg, Sum(jml_opname) as Jml_fisik FROM detail_opname 
  WHERE tgl_opname ='$tgl' 
GROUP	BY	kode_brg) B
ON A.kd_obat=B.kode_brg
/* Pembelian */
LEFT JOIN	 
(SELECT kode_brg, Sum(jumlah) as Tambah FROM detail_pembelian 
 WHERE tgl_faktur BETWEEN '$tgl' AND NOW() 
GROUP BY	 kode_brg) C
ON A.kd_obat=C.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(jml_jual) as Keluar FROM detail_penjualan 
 WHERE tgl_jual BETWEEN '$tgl' AND NOW() 
GROUP BY	kode_brg) D
ON A.kd_obat=D.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(retur) as Returbeli FROM detail_returbeli
WHERE tgl_returbeli BETWEEN '$tgl' AND NOW() 
GROUP BY kode_brg) E
ON A.kd_obat=E.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(retur_jual) as Returjual FROM detail_returjual
WHERE tgl_returjual BETWEEN '$tgl' AND NOW()
GROUP BY kode_brg) F
ON A.kd_obat=F.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(retur_konsi) as Returkonsi FROM detail_returkonsinasi 
WHERE tgl_returkonsi BETWEEN '$tgl' AND NOW()
GROUP BY kode_brg) G
ON A.kd_obat=G.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(jumlah) as jml_titipan FROM detail_konsinasi 
WHERE tgl_konsinasi BETWEEN '$tgl' AND NOW()
GROUP BY kode_brg) H
ON A.kd_obat=H.kode_brg

-- WHERE A.nm_obat like concat('%',cari,'%') 
ORDER BY A.nm_obat
";
$stmt = $koneksi->prepare($query);
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
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- <style>
        /* Mengatur lebar kolom berdasarkan indeksnya */
        #dataTable th:nth-child(1),
        #dataTable td:nth-child(1) {
            width: 50px;
            /* Lebar kolom pertama */
        }

        #dataTable th:nth-child(2),
        #dataTable td:nth-child(2) {
            width: 200px;
            /* Lebar kolom kedua */
        }

        #dataTable th:nth-child(3),
        #dataTable td:nth-child(3) {
            width: 100px;
            /* Lebar kolom ketiga */
        }

        #dataTable th:nth-child(4),
        #dataTable td:nth-child(4) {
            width: 150px;
            /* Lebar kolom keempat */
        }

        /* Agar DataTable lebih responsif dan rapi */
        #dataTable {
            width: 100%;
            /* Membuat tabel menggunakan lebar penuh kontainer */
            table-layout: fixed;
            /* Menggunakan layout tetap agar kolom lebih teratur */
        }
    </style> -->
    <style>
        .stok-kosong {
            color: red;
            font-weight: bold;
        }

        .stok-warning {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container mt-5">

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'sukses'): ?>
                <div class="alert alert-success" role="alert">
                    Margin berhasil diperbarui!
                </div>
            <?php elseif ($_GET['status'] == 'gagal'): ?>
                <div class="alert alert-danger" role="alert">
                    Gagal memperbarui margin.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <h2>Laporan Laba Rugi</h2>
        <form method="POST" action="update_margin.php">
            <table id="dataTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>KD Barang</th>
                        <th>Nama Barang</th>
                        <th class="text-right">Stok</th>
                        <th class="text-right">Harga Beli</th>
                        <th class="text-right">PPN 11%</th>
                        <th class="text-right">Margin</th>
                        <th class="text-right">Harga Jual</th>
                        <th class="text-right">Laba</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($barang as $row): ?>
                        <?php
                        $harga_beli = $row['hrgbeli_obat'];
                        $stok = $row['Stok'];
                        $ppn = $harga_beli * 0.11;  // Menghitung PPN 11%
                        $margin = $row['margin'];   // Mengambil margin dari database
                        $harga_jual = $harga_beli + $margin + $ppn;  // Harga jual = harga beli + margin + PPN
                        $laba = $harga_jual - $harga_beli;  // Laba = harga jual - harga beli
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['kd_obat']); ?></td>
                            <td><?php echo htmlspecialchars($row['nm_obat']); ?></td>

                            <td>
                                <?php if ($stok == 0): ?>
                                    <span class="stok-kosong">Stok Kosong</span>
                                <?php elseif ($stok < 0): ?>
                                    <span class="stok-warning">Stok kurang (<?php echo $stok; ?>)</span>
                                <?php else: ?>
                                    <?= $stok ?>
                                <?php endif; ?>
                            </td>


                            <!-- <td class="text-right"><?php echo number_format($stok, 0, ',', '.'); ?></td> -->
                            <!-- <td class="text-right">
                        
                                <input type="number" name="stok" value="<?php echo number_format($stok, 2, ',', '.'); ?>" class="form-control">
                            </td> -->
                            <td class="text-right"><?php echo number_format($harga_beli, 2, ',', '.'); ?></td>
                            <td class="text-right"><?php echo number_format($ppn, 2, ',', '.'); ?></td>
                            <td class="text-right">
                                <!-- Input Margin -->
                                <input type="text" name="margin[<?php echo $row['kd_obat']; ?>]" value="<?php echo number_format($margin, 0, ',', '.'); ?>" class="form-control" step="1">
                            </td>
                            <td class="text-right"><?php echo number_format($harga_jual, 2, ',', '.'); ?></td>
                            <td class="text-right"><?php echo number_format($laba, 2, ',', '.'); ?></td>
                            <td>
                                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            // var table = $('#dataTable').DataTable();
            $('#dataTable').DataTable({
                "columnDefs": [{
                        "targets": 0, // Kolom pertama
                        "width": "50px"
                    },
                    {
                        "targets": 1, // Kolom kedua
                        "width": "50px"
                    },
                    {
                        "targets": 2, // Kolom ketiga
                        "width": "160px"
                    },
                    {
                        "targets": 3, // Kolom keempat
                        "width": "150px"
                    }
                ]
            });

            $('#dataTable tbody tr').each(function() {
                var stok = $(this).find('.stok').text();

                if (parseInt(stok) < 0) {
                    $(this).css('background-color', 'red'); // Warna merah jika stok kurang dari 0
                    $(this).css('color', 'white'); // Mengubah warna teks agar tetap terlihat
                }
            });


            // Handle form submission
            // $('#dataForm').submit(function(event) {
            //     event.preventDefault(); // Prevent the form from submitting normally

            //     // Get form data
            //     var name = $('#name').val();
            //     var email = $('#email').val();
            //     var age = $('#age').val();

            //     // Add new row to the DataTable
            //     table.row.add([
            //         name,
            //         email,
            //         age
            //     ]).draw();

            //     // Clear the form
            //     $('#name').val('');
            //     $('#email').val('');
            //     $('#age').val('');
            // });
        });
    </script>
</body>

</html>