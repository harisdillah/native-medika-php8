<?php
function sukses_masuk($username,$pass,$level){
		
	
	$login	=mysql_query("SELECT * FROM admins WHERE username='$username' AND password='$pass'");
	//$login	=mysql_query("SELECT * FROM users WHERE user_id='$username' AND password='$pass'");
	$ketemu	=mysql_num_rows($login);
	$r = mysql_fetch_array($login);
	if ($ketemu > 0){
		session_start();
		 include "timeout.php";
	  	
		$_SESSION[namauser]     = $r[username];
		$_SESSION[namalengkap]  = $r[nama_lengkap];
		$_SESSION[passuser]     = $r[password];
		$_SESSION[leveluser]    = $r[level];
		//header('location:media.php?module=home');
		//tambah untuk login
		$_SESSION[login] = 1;
		timer();
		$ipaddress = empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
			$sql	= "UPDATE admins SET lastlogin=now(),ipaddress='$ipaddress'  WHERE username='$username' AND password='$pass'";
		mysql_query($sql);
		
		//hal level login					
				if ($level== 'admin'){
					header('location:media.php?module=home');
					echo "Selamat datang <b>$_SESSION[leveluser] </b>, di Aplikasi Gudang Persedian v 0.1.";
				}
				elseif ($level== 'user'){
					header('location:media2.php?module=home');
				echo "Selamat datang <b>$_SESSION[leveluser] </b>, di Aplikasi Gudang Persedian v 0.1.";
				}
				elseif ($level== 'kasir'){
					header('location:media3.php?module=home');
				echo "Selamat datang <b>$_SESSION[leveluser] </b>, di Aplikasi Gudang Persedian v 0.1.";
				}
				
	}
	return false;
	}
	

function msg(){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, silahkan cek kembali <b>Username</b> dan <b>Password</b> Anda<br><br>Kesalahan $_SESSION[salah]";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";
  return false;
}

function salah_blokir($username){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, Username <b>$username</b> telah <b>TERBLOKIR</b>, silahkan hubungi Administrator.";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";
  return false;
}
function salah_username($username){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, Username <b>$username</b> tidak dikenal.";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";	
  return false;
}

function salah_password(){
  echo "<link href='css/screen.css' rel='stylesheet' type='text/css'>
  <link href='css/reset.css' rel='stylesheet' type='text/css'>
  <link href='css/style_button.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, silahkan cek kembali <b>Password</b> Anda<br><br>Kesalahan $_SESSION[salah]";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";
   return false;
}

function blokir($username){
	$ipaddress = empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
	$sql	= "UPDATE admins SET lastlogin=now(),ipaddress='$ipaddress',blokir='Y'  WHERE username='$username'";
	mysql_query($sql);		
	session_start();
	session_destroy();
	 return false;
}

function cari_jml_masuk($kode){
	$sql	= "SELECT no_masuk,sum(jumlah) as jml FROM detail_masuk WHERE 
	no_masuk='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_jml_total_masuk($kode){
	$sql	= "SELECT no_masuk,sum(jumlah*hrga_satuan) as jml_total FROM detail_masuk 
				WHERE no_masuk='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml_total];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

// Barang Keluar
function cari_jml_brg_keluar($kode){
	$sql	= "SELECT no_keluar,sum(jumlah) as jml FROM detail_keluar WHERE 
	no_keluar='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


// Total Barang Keluar Per Barang
function cari_per_jml_brg_keluar($kode){
	$sql	= "SELECT no_keluar,sum(jumlah) as jml 
	FROM detail_keluar 
	WHERE kode_brg='$kode'
	";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_per_jml_brg_masuk($kode){
	$sql	= "SELECT no_masuk,sum(jumlah) as jml 
	FROM detail_masuk 
	WHERE kode_brg='$kode'
	";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_jml_brg_pesanan($kode){
	$sql	= "SELECT no_pesanan,sum(jumlah) as jml FROM detail_pesanan WHERE 
	no_pesanan='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_jml_total_pesanan($kode){
	$sql	= "SELECT no_pesanan,sum(jumlah*hrga_satuan) as jml_total 
				FROM detail_pesanan 
				WHERE no_pesanan='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml_total];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

/*
function cari_stok_awal($kode) {
	$sql	= "SELECT kode_barang,stok_awal as jml FROM barang WHERE kode_barang='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

function cari_jml_beli($kode){
	$sql	= "SELECT kode_barang,sum(jumlah_beli) as jml FROM pembelian WHERE kode_barang='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}


function cari_jml_jual($kode){
	$sql	= "SELECT kode_barang,sum(jumlah_jual) as jml FROM penjualan WHERE kode_barang='$kode'";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[jml];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

//total jual

function cari_total_jual($kode){
	$sql	= "SELECT kode_jual,sum(jumlah_jual*harga_jual) as total
				FROM penjualan WHERE kode_jual='$kode'
				GROUP BY kode_jual
				ORDER BY kode_jual
				";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[total];
	}else{
		$hasil = 0;
	}
	return $hasil;
}

//---barang Masuk---
function cari_total_beli($kode){
	$sql	= "SELECT kode_beli,tgl_beli,count(kode_barang) as kodebrg,sum(jumlah_beli*harga_beli) as total
				FROM pembelian
				WHERE kode_beli = '$kode'
				GROUP BY kode_beli
				ORDER BY kode_beli
				";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[total];
	}else{
		$hasil = 0;
	}
	return $hasil;
}
*/

/*
function cari_cv($kode){
	$sql	= "SELECT *
				FROM supplier
				WHERE kode_supplier = '$kode'
				";
	$query	= mysql_query($sql);
	$row	= mysql_num_rows($query);
	if ($row>0){
		$data	= mysql_fetch_array($query);
		$hasil	= $data[nama_supplier];
	}else{
		$hasil = '-';
	}
	return $hasil;
}
*/
			
/*
function cari_stok_akhir($kode){
	$stok_awal	= cari_stok_awal($kode);
	$jml_beli = cari_jml_beli($kode);
	$jml_jual = cari_jml_jual($kode);
	
	$hasil	= ($stok_awal+$jml_beli)-$jml_jual;
	return $hasil;
}


//--total jual--

function total_jual($kode) {
	$sql	= "select a.kode_beli, 
			a.jumlah_beli,a.harga_beli, sum(a.jumlah_beli * a.harga_beli) as total
			from pembelian a 
				WHERE a.kode_beli='$kode'";
	$data	= mysql_fetch_array(mysql_query($sql));
	$row		= mysql_num_rows(mysql_query($sql));
	if ($row>0){
		$hasil		= $data['total'];
	}else{
		$hasil		= 0;
	}
	return $hasil;
}
*/


?>