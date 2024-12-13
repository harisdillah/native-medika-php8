<?php
include '../inc/koneksi.php';

// Ambil data barang
$sql = "SELECT A.kd_obat,ROUND (LENGTH(A.kd_obat)) AS kt,A.nm_obat,A.sat_obat,A.sat_jual,A.hrg_obat,A.nobatch,A.hrgbeli_obat,A.hrg_obat,
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
  WHERE tgl_opname ='2024-11-29' 
GROUP	BY	kode_brg) B
ON A.kd_obat=B.kode_brg
/* Pembelian */
LEFT JOIN	 
(SELECT kode_brg, Sum(jumlah) as Tambah FROM detail_pembelian 
 WHERE tgl_faktur BETWEEN '2024-11-29' AND NOW() 
GROUP BY	 kode_brg) C
ON A.kd_obat=C.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(jml_jual) as Keluar FROM detail_penjualan 
 WHERE tgl_jual BETWEEN '2024-11-29' AND NOW() 
GROUP BY	kode_brg) D
ON A.kd_obat=D.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(retur) as Returbeli FROM detail_returbeli
WHERE tgl_returbeli BETWEEN '2024-10-30' AND NOW() 
GROUP BY kode_brg) E
ON A.kd_obat=E.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(retur_jual) as Returjual FROM detail_returjual
WHERE tgl_returjual BETWEEN '2024-11-29' AND NOW()
GROUP BY kode_brg) F
ON A.kd_obat=F.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(retur_konsi) as Returkonsi FROM detail_returkonsinasi 
WHERE tgl_returkonsi BETWEEN '2024-11-29' AND NOW()
GROUP BY kode_brg) G
ON A.kd_obat=G.kode_brg

LEFT JOIN	
(SELECT	kode_brg, Sum(jumlah) as jml_titipan FROM detail_konsinasi 
WHERE tgl_konsinasi BETWEEN '2024-11-29' AND NOW()
GROUP BY kode_brg) H
ON A.kd_obat=H.kode_brg

-- WHERE A.nm_obat like concat('%',cari,'%') 
ORDER BY A.nm_obat";
$stmt = $koneksi->query($sql);
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
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

    <div class="container mt-5">
        <h2>Data Barang</h2>
        <table id="dataTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Margin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $row): ?>
                    <tr>
                        <td><?= $row['kd_obat'] ?></td>
                        <td><?= $row['nm_obat'] ?></td>
                        <td><?= number_format($row['hrgbeli_obat'], 0, ',', '.') ?></td>
                        <td><?= number_format($row['margin'], 0, ',', '.') ?>%</td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editModal<?= $row['kd_obat'] ?>">Edit Margin</button>
                        </td>
                    </tr>

                    <!-- Modal Edit Margin -->
                    <div class="modal fade" id="editModal<?= $row['kd_obat'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $row['kd_obat'] ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['kd_obat'] ?>">Edit Margin Barang</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="update.php" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="margin">Margin Baru (%)</label>
                                            <input type="number" name="margin" class="form-control" value="<?= $row['margin'] ?>" required>
                                            <input type="hidden" name="id" value="<?= $row['kd_obat'] ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS, jQuery, Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
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