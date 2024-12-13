<?php
/*
* PROSES TAMPIL
*/
class view
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    // public function user_data()
    // {
    //     $sql = "select *
    //             from tbl_user";
    //     $row = $this->db->prepare($sql);
    //     $row->execute();
    //     $hasil = $row->fetchAll();
    //     return $hasil;
    // }

    // public function jumlah_user()
    // {
    //     $sql = "SELECT COUNT(id_user) as jml FROM tbl_user";
    //     $row = $this->db->prepare($sql);
    //     $row->execute();
    //     $hasil = $row->fetch();
    //     return $hasil;
    // }

    // public function data_user()
    // {
    //     $sql = "select *
    //             from data_users";
    //     $row = $this->db->prepare($sql);
    //     $row->execute();
    //     //$row->execute(array($id));
    //     $hasil = $row->fetchAll();
    //     return $hasil;
    // }


    // public function data_user_row()
    // {
    //     $sql = "select*from data_users";
    //     $row = $this->db->prepare($sql);
    //     $row->execute();
    //     $hasil = $row->rowCount();
    //     return $hasil;
    // }


    public function databarang($id)
    {
        $sql = "SELECT * FROM tbl_dataobat WHERE kd_obat = ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($id));
        $hasil = $row->fetch();
        return $hasil;
    }

    public function databarangSemua()
    {
        $sql = "SELECT * FROM tbl_dataobat ";
        $row = $this->db->prepare($sql);
        $row->execute();
        //$row->execute(array($id));
        $hasil = $row->fetchAll();
        return $hasil;
    }

    public function dataLabelbarang($id)
    {
        $sql = "SELECT * FROM tbl_dataobat WHERE kd_obat = ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($id));
        $hasil = $row->fetchAll();
        return $hasil;
    }


    public function data_detail_kartu_barang($id)
    {
        $sql = "SELECT t.tgl,t.nomor,t.ket,t.awal,t.masuk,t.keluar,b.nama_barang,b.kode_brg
 FROM kartu_test t
 JOIN m_barang b ON t.kode_brg = b.kode_brg
 WHERE b.kode_brg =?
 ORDER BY t.tgl ASC";
        $row = $this->db->prepare($sql);
        $row->execute(array($id));
        $hasil = $row->fetchAll();
        return $hasil;
    }


    //     public function data_Rekap_kartu_barang($tgl_awal, $tgl_akhir, $id)
    //     {
    //         $sql = "SELECT a.kode_brg,
    // a.barcode,
    // a.harga_beli, 
    // a.nama_barang,
    // a.merk,
    // a.satuan,
    // a.stok_min,
    // a.tgl_ed,
    // a.bidang_brg,a.gudang,
    // 		COALESCE(b.TOTAL1,0) AS awal,
    // 		COALESCE(c.TOTAL2,0) AS masuk, 
    // 		COALESCE(d.TOTAL3,0) AS keluar,
    // 		(COALESCE(b.TOTAL1,0)+ COALESCE(c.TOTAL2,0)) - (COALESCE(d.TOTAL3,0)) AS stok
    // 		FROM m_barang a  
    // 		 LEFT JOIN 
    // 		-- Awal
    // 		 (SELECT a1.kode_brg,SUM(a1.jumlah) TOTAL1 FROM detail_awal_tutup_depo a1
    // 			WHERE a1.tgl_tutup BETWEEN '$tgl_awal' AND '$tgl_akhir'
    // 			GROUP BY a1.kode_brg)B ON a.kode_brg=B.kode_brg 
    // 			LEFT JOIN 
    // 		-- masuk
    // 		(		
    // 		SELECT a2.kode_brg,SUM(a2.jumlah) TOTAL2 FROM detail_masuk a2 
    // 		INNER JOIN h_masuk aa2 ON aa2.no_trans_m = a2.no_masuk
    // 		WHERE a2.tgl_masuk BETWEEN '$tgl_awal' AND '$tgl_akhir'
    // 			GROUP BY a2.kode_brg)C ON a.kode_brg=C.kode_brg 
    // 		LEFT JOIN 
    // 		-- Keluar
    // 		(SELECT a3.kode_brg,SUM(a3.jumlah) TOTAL3  FROM detail_keluar a3 
    // 		INNER JOIN h_keluar aa3 ON aa3.no_trans_k = a3.no_keluar
    // 		WHERE a3.tgl_keluar BETWEEN '$tgl_awal' AND '$tgl_akhir'
    // 		GROUP BY a3.kode_brg)D ON a.kode_brg=D.kode_brg 
    // 		WHERE a.gudang = 101 AND a.kode_brg = ?
    //  ORDER BY a.kode_brg,a.nama_barang ASC";
    //         $row = $this->db->prepare($sql);
    //         $row->execute(array($id));
    //         $hasil = $row->fetchAll();
    //         return $hasil;
    //     }


    //     public function datakeluar($id)
    //     {
    //         $sql = "SELECT
    // 	a.no_trans_k, 
    // 	a.tgl_keluar, 
    // 	a.no_bbk, 
    // 	a.kd_memberi, 
    // 	a.kd_menerima, 
    // 	a.gudang, 
    // 	a.tgl_create,
    // 	a.ket, 
    // 	b.Nama AS nama
    // FROM
    // 	h_keluar a
    // INNER JOIN	db_spt24.m_pegawai17 b ON b.NIP = a.kd_menerima 
    //     WHERE no_trans_k = ?";
    //         $row = $this->db->prepare($sql);
    //         $row->execute(array($id));
    //         $hasil = $row->fetch();
    //         return $hasil;
    //     }


    //     public function list_detail_keluar($id)
    //     {
    //         $sql = "SELECT
    // 	t.kode_brg, 
    // 	t.nama, 
    // 	t.jumlah, 
    // 	t.satuan, 
    // 	t.no_keluar
    // FROM
    // 	detail_keluar AS t
    // 	INNER JOIN
    // 	m_barang
    // 	ON 
    // 		t.kode_brg = m_barang.kode_brg

    //  WHERE t.no_keluar =?
    //  ORDER BY t.id_keluar ASC";
    //         $row = $this->db->prepare($sql);
    //         $row->execute(array($id));
    //         $hasil = $row->fetchAll();
    //         return $hasil;
    //     }
}
