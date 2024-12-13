<?php
$tgl = '2024-10-30';
include '../inc/koneksi.php';
// Prepare the SQL query with placeholders
$query = "SELECT
A.kd_obat,
ROUND(LENGTH(A.kd_obat)) AS kt,
A.nm_obat,
A.sat_obat,
A.sat_jual,
A.hrg_obat,
A.nobatch,
A.hrgbeli_obat,
A.hrg_obat,
A.idrak,
A.margin,
COALESCE(B.Jml_fisik, 0) as jml_fisik,
COALESCE(H.jml_titipan, 0) as jml_titip,
COALESCE(C.tambah, 0) as jml_tambah,
COALESCE(D.Keluar, 0) as jml_keluar,
COALESCE(E.Returbeli, 0) as jml_rbl,
COALESCE(F.Returjual, 0) as jml_rjual,
COALESCE(G.Returkonsi, 0) as jml_rkonsi,
(COALESCE(B.jml_fisik, 0) + COALESCE(C.tambah, 0) + COALESCE(H.jml_titipan, 0)) -
(COALESCE(D.keluar, 0) + COALESCE(E.Returbeli, 0) + COALESCE(F.Returjual, 0) + COALESCE(G.Returkonsi, 0)) as Stok
FROM
tbl_dataobat A
LEFT JOIN
(SELECT kode_brg, SUM(jml_opname) as Jml_fisik FROM detail_opname
WHERE tgl_opname = :tgl
GROUP BY kode_brg) B
ON A.kd_obat = B.kode_brg
LEFT JOIN
(SELECT kode_brg, SUM(jumlah) as Tambah FROM detail_pembelian
WHERE tgl_faktur BETWEEN :tgl AND NOW()
GROUP BY kode_brg) C
ON A.kd_obat = C.kode_brg
LEFT JOIN
(SELECT kode_brg, SUM(jml_jual) as Keluar FROM detail_penjualan
WHERE tgl_jual BETWEEN :tgl AND NOW()
GROUP BY kode_brg) D
ON A.kd_obat = D.kode_brg
LEFT JOIN
(SELECT kode_brg, SUM(retur) as Returbeli FROM detail_returbeli
WHERE tgl_returbeli BETWEEN :tgl AND NOW()
GROUP BY kode_brg) E
ON A.kd_obat = E.kode_brg
LEFT JOIN
(SELECT kode_brg, SUM(retur_jual) as Returjual FROM detail_returjual
WHERE tgl_returjual BETWEEN :tgl AND NOW()
GROUP BY kode_brg) F
ON A.kd_obat = F.kode_brg
LEFT JOIN
(SELECT kode_brg, SUM(retur_konsi) as Returkonsi FROM detail_returkonsinasi
WHERE tgl_returkonsi BETWEEN :tgl AND NOW()
GROUP BY kode_brg) G
ON A.kd_obat = G.kode_brg
LEFT JOIN
(SELECT kode_brg, SUM(jumlah) as jml_titipan FROM detail_konsinasi
WHERE tgl_konsinasi BETWEEN :tgl AND NOW()
GROUP BY kode_brg) H
ON A.kd_obat = H.kode_brg
ORDER BY A.nm_obat
";

// Prepare and execute the query
$stmt = $koneksi->prepare($query);

// Bind the $tgl variable to the parameter :tgl
$stmt->bindParam(':tgl', $tgl, PDO::PARAM_STR);

// Execute the query
$stmt->execute();

// Fetch all results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output the results (just an example)
foreach ($results as $row) {
    echo "Kode Obat: " . $row['kd_obat'] . " - Nama Obat: " . $row['nm_obat'] . "<br>";
}
