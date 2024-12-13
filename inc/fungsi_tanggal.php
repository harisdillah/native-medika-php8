<?php
function jin_date_sql($date){
	$exp = explode('-',$date);
	if(count($exp) == 3) {
		$date = $exp[2].'-'.$exp[1].'-'.$exp[0];
	}
	return $date;
}
 
function jin_date_str($date){
	$exp = explode('-',$date);
	if(count($exp) == 3) {
		$date = $exp[2].'-'.$exp[1].'-'.$exp[0];
	}
	return $date;
}


// Fungsi untuk Merubah susunan format tanggal
	function ubahformatTgl($tanggal) {
		$pisah = explode('/',$tanggal);
		$urutan = array($pisah[2],$pisah[1],$pisah[0]);
		$satukan = implode('-',$urutan);
		return $satukan;
	}
// Cara penggunaan function ubahTgl
//	$ubahtgl = ubahformatTgl($tglterbit);

// Konvesi yyyy-mm-dd -> dd-mm-yyyy dan memberi nama bulan
	function tgl_eng_to_ind($tgl) {
		$tanggal	= explode('-',$tgl);
		$kdbl		= $tanggal[1];

		if ($kdbl == '01')	{
			$nbln = 'Januari';
		}
		else if ($kdbl == '02') {
			$nbln = 'Februari';
		}
		else if ($kdbl == '03') {
			$nbln = 'Maret';
		}
		else if ($kdbl == '04') {
			$nbln = 'April';
		}
		else if ($kdbl == '05') {
			$nbln = 'Mei';
		}	
		else if ($kdbl == '06') {
			$nbln = 'Juni';
		}
		else if ($kdbl == '07') {
			$nbln = 'Juli';
		}
		else if ($kdbl == '08') {
			$nbln = 'Agustus';
		}
		else if ($kdbl == '09') {
			$nbln = 'September';
		}
		else if ($kdbl == '10') {
			$nbln = 'Oktober';
		}
		else if ($kdbl == '11') {
			$nbln = 'November';
		}
		else if ($kdbl == '12') {
			$nbln = 'Desember';
		}
		else {
			$nbln = '';
		}
		
		$tgl_ind = $tanggal[0]." ".$nbln." ".$tanggal[2];
		return $tgl_ind;
	}

// 07 Mar 17
function TanggalIndo($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tgl   = substr($date, 8, 2);
 	$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;	
	//$result = date($tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun,"Y-m-d H:i:s");
	
	return($result);
}


function tanggal_indo($tanggal)
{
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal);
	return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}

?>