<?

session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");
ini_set("date.timezone", "Asia/Jakarta");
require_once '../../common/function.php';
$fungsi = new fungsi();


########## Filter Pencarian #################

if(@$_GET['cetak']!=''){
	$id_dokter = $_GET['iddok'];
	$id_ruang = $_GET['idrng'];
	$tgl_awal = $_GET['tgla'];
	$tgl_akhir = $_GET['tglb'];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}
else {
	$id_dokter = $_POST['id_dokter']; 
	$id_ruang = $_POST['ruang']; 
	$tgl_awal = $_POST['tgla'];
	$tgl_akhir = $_POST['tglb'];
}
########## /Filter Pencarian #################

$query="SELECT rm_dokter.id_dokter, rm_dokter.nama_dokter FROM	rm_dokter WHERE rm_dokter.id_dokter = $id_dokter";
$result = @mysql_query($query);	
$id_dokter = @mysql_result($result,0,"id_dokter");
$nama_dokter =@ mysql_result($result,0,"nama_dokter");

$query2="SELECT ruang FROM rm_ruang WHERE id_ruang= $id_ruang";
$result = @mysql_query($query2);	
$ruang = @mysql_result($result,0,"ruang");	

$n = @mysql_num_rows($result);
if($n<=0) {
	echo 'Data Tidak Ditemukan!';
	exit;
}
?>
	<div class='printArea'><p align='center'><strong>KARTU INDEX DOKTER<br><u>RSUD Dr. SOEGIRI LAMONGAN</u></strong></p><hr>
	<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1'>
	<tr height='25'>
		<td width='30%' bgcolor='#FFFFFF'>Index Dokter</td>
		<td width='10%' bgcolor='#FFFFFF'>Bagian</td>
		<td width='30%' bgcolor='#FFFFFF'><b><?=$ruang;?></b></td>
		<td width='10%' bgcolor='#FFFFFF'>Nama Dokter</td>
		<td width='20%' bgcolor='#FFFFFF'>: <b><?=$nama_dokter;?></b></td>
	</tr>
	</table>
	<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
	<tr>
		<td width='5%' align='center' bgcolor='#999999'>Nomor.</td>
		<td width='5%' align='center' bgcolor='#999999'>Service</td>
		<td width='5%' align='center' bgcolor='#999999'>Nama Penderita</td>
		<td width='5%' align='center' bgcolor='#999999'>Hasil</td>
	</tr>
	<?
###### Tindakan Ruang ########
$query2="SELECT
  rm_tindakan_ruang.id_tindakan_ruang,
  rm_tindakan_ruang.id_pelaku_tindakan,
  rm_tindakan_ruang.id_detail_tindakan,
  rm_tindakan_ruang.id_dokter,
  rm_tindakan_ruang.id_pendaftaran,
  rm_tindakan_ruang.id_tarif_tindakan,
  rm_tindakan_ruang.tarif,
  rm_tindakan_ruang.advice,
  rm_tindakan_ruang.tgl_tindakan
FROM
  rm_tindakan_ruang
WHERE
  rm_tindakan_ruang.id_dokter = $id_dokter";

			$result2 = @mysql_query($query2);	
			while($row2 = @mysql_fetch_array($result2)){
				$id_pendaftaran =  $row2['id_pendaftaran'];
			}

$query="
SELECT
  rm_pasien.id_pasien,  
   rm_ruang.ruang,
  rm_pasien_keluar.id_pasien_keluar,
  rm_pasien.nama_pasien,
  rm_keadaan.keadaan,
   rm_diagnosa.id_dokter,
  rm_pasien_keluar.tgl_keluar,
  rm_pendaftaran.id_ruang
FROM
  rm_pasien_keluar,
  rm_pasien,
  rm_pendaftaran,
  rm_ruang,
  rm_diagnosa,
  rm_keadaan
WHERE
  rm_pendaftaran.id_ruang = rm_ruang.id_ruang AND
  rm_diagnosa.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
  rm_diagnosa.id_pasien = rm_pasien.id_pasien AND
  rm_pendaftaran.id_pendaftaran = rm_pasien_keluar.id_pendaftaran AND
  rm_pasien_keluar.id_keadaan = rm_keadaan.id_keadaan   AND  
  rm_pendaftaran.id_ruang = $id_ruang AND 
  rm_diagnosa.id_dokter=$id_dokter AND
  DATE(rm_pasien_keluar.tgl_keluar) between '$tgl_awal' and '$tgl_akhir'" ;

	$result = @mysql_query($query);	
	while($row = @mysql_fetch_array($result)){				
		$id_pasien =  $row['id_pasien'];
		$nama_pasien =  $row['nama_pasien'];
		$keadaan =  $row['keadaan'];	
		$ruang =  $row['ruang'];	
		$id_ruang =  $row['id_ruang'];	

		echo "<tr>
			<td width='5%' align='center' bgcolor='#FFFFFF'>$id_pasien</td>
			<td width='5%' align='center' bgcolor='#FFFFFF'>$ruang</td>
			<td width='5%' align='center' bgcolor='#FFFFFF'>$nama_pasien</td>
			<td width='5%' align='center' bgcolor='#FFFFFF'>$keadaan</td>
		</tr>";
	}
?>
</table>