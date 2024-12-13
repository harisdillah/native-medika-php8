<?php
// Koneksi ke database menggunakan PDO
$host = 'localhost';
$dbname = 'db_medika';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

//include '../../inc/koneksi.php';

// Menangani permintaan dari DataTables
$start = isset($_GET['start']) ? $_GET['start'] : 0;
$length = isset($_GET['length']) ? $_GET['length'] : 10;
$search_value = isset($_GET['search']['value']) ? $_GET['search']['value'] : ''; // Pencarian umum
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : ''; // Tanggal mulai
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : ''; // Tanggal akhir

// Menyiapkan query SQL dengan parameter tanggal
$query = "
    SELECT 
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
        COALESCE(B.Jml_fisik, 0) AS jml_fisik,
        COALESCE(H.jml_titipan, 0) AS jml_titip,
        COALESCE(C.tambah, 0) AS jml_tambah,
        COALESCE(D.Keluar, 0) AS jml_keluar,
        COALESCE(E.Returbeli, 0) AS jml_rbl,
        COALESCE(F.Returjual, 0) AS jml_rjual,
        COALESCE(G.Returkonsi, 0) AS jml_rkonsi,
        -- Stok
        (COALESCE(B.jml_fisik, 0) + COALESCE(C.tambah, 0) + COALESCE(H.jml_titipan, 0)) -
        (COALESCE(D.keluar, 0) + COALESCE(E.Returbeli, 0) + COALESCE(F.Returjual, 0) + COALESCE(G.Returkonsi, 0)) AS Stok
    FROM 
        tbl_dataobat A
    LEFT JOIN
        (SELECT kode_brg, SUM(jml_opname) AS Jml_fisik 
         FROM detail_opname 
         WHERE tgl_opname BETWEEN :date_start AND :date_end 
         GROUP BY kode_brg) B
    ON A.kd_obat = B.kode_brg
    LEFT JOIN
        (SELECT kode_brg, SUM(jumlah) AS Tambah 
         FROM detail_pembelian 
         WHERE tgl_faktur BETWEEN :date_start AND NOW() 
         GROUP BY kode_brg) C
    ON A.kd_obat = C.kode_brg
    LEFT JOIN
        (SELECT kode_brg, SUM(jml_jual) AS Keluar 
         FROM detail_penjualan 
         WHERE tgl_jual BETWEEN :date_start AND NOW() 
         GROUP BY kode_brg) D
    ON A.kd_obat = D.kode_brg
    LEFT JOIN
        (SELECT kode_brg, SUM(retur) AS Returbeli 
         FROM detail_returbeli 
         WHERE tgl_returbeli BETWEEN :date_start AND NOW() 
         GROUP BY kode_brg) E
    ON A.kd_obat = E.kode_brg
    LEFT JOIN
        (SELECT kode_brg, SUM(retur_jual) AS Returjual 
         FROM detail_returjual 
         WHERE tgl_returjual BETWEEN :date_start AND NOW() 
         GROUP BY kode_brg) F
    ON A.kd_obat = F.kode_brg
    LEFT JOIN
        (SELECT kode_brg, SUM(retur_konsi) AS Returkonsi 
         FROM detail_returkonsinasi 
         WHERE tgl_returkonsi BETWEEN :date_start AND NOW() 
         GROUP BY kode_brg) G
    ON A.kd_obat = G.kode_brg
    LEFT JOIN
        (SELECT kode_brg, SUM(jumlah) AS jml_titipan 
         FROM detail_konsinasi 
         WHERE tgl_konsinasi BETWEEN :date_start AND NOW() 
         GROUP BY kode_brg) H
    ON A.kd_obat = H.kode_brg
    ORDER BY A.nm_obat
";

// Menyiapkan statement
$stmt = $pdo->prepare($query);

// Mengikat parameter
$stmt->bindParam(':date_start', $date_start);
$stmt->bindParam(':date_end', $date_end);

// Eksekusi query
$stmt->execute();

// Ambil hasil data
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total data (untuk paginasi)
$stmt = $pdo->query("SELECT COUNT(*) FROM tbl_dataobat");
$total_data = $stmt->fetchColumn();

// Return data dalam format JSON untuk DataTable
$response = [
    'draw' => isset($_GET['draw']) ? $_GET['draw'] : 1,
    'recordsTotal' => $total_data,
    'recordsFiltered' => $total_data, // Sesuaikan jika ingin filter data
    'data' => $data
];

echo json_encode($response);
