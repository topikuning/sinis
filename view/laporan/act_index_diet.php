<?
session_start();
@require_once("../../common/koneksi.php");
@require_once("../../common/function.php");
$fungsi = new fungsi();
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");


######## Filter #########
if(@$_GET['cetak']!=''){
	$waktu = $_GET["idw"];
	$ruang = $_GET["idr"];
	$tgl_awal = $_GET["tgla"]; 
	$tgl_akhir = $_GET["tglb"]; 
}else{
	$waktu = $_POST["waktu"];
	$ruang = $_POST["ruang"];
	$tgl_awal = $_POST["tgla"]; 
	$tgl_akhir = $_POST["tglb"]; 
}
######## /Filter #########

#$tgl_awal='2011-07-14';
#$tgl_akhir='2011-07-14';
$wkt='';
if($waktu==1) $wkt = 'Pagi';
else if($waktu==2) $wkt = 'Siang';
else if($waktu==3) $wkt = 'Sore';

$queryK = "SELECT ruang FROM rm_ruang WHERE id_ruang=$ruang";
			$resultk = @mysql_query($queryK);	
			$nm_ruang = @mysql_result($resultk,0,"ruang");	

?>
<p align="center"><b>LAPORAN PESANAN DIET PASIEN</b></p>
<table>
<tr bgcolor='#FFFFFF'>
<td>
Tanggal
</td>
<td>
    : <?=$fungsi->codeDate($tgl_awal)?>
</td>
<td>
s.d. <?=$fungsi->codeDate($tgl_akhir)?>
</td>
<td>

</td>
</tr>
<tr bgcolor='#FFFFFF'>
<td>
Waktu Diet
</td>
<td>
: <?=$wkt?>
</td>
<td>
Pavilium
</td>
<td>
: <?=$nm_ruang?>
</td>
</tr>
</table>
<hr>
<table width="100%"  style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<thead>
<tr>
<td bgcolor='#999999' width="5%">
<b>No.</b>
</td>
<td bgcolor='#999999' width="25%">
<b>Nama Pasien</b>
</td>
<td bgcolor='#999999' width="10%">
<b>Kelas</b>
</td>
<td bgcolor='#999999' width="15%">
<b>Ruang - Bed</b>
</td>
<td bgcolor='#999999'  width="10%">
<b>Diet</b>
</td>
<td bgcolor='#999999' width="15%">
<b>Jenis Diet</b>
</td>
<td bgcolor='#999999' width="20%">
<b>Keterangan</b>
</td>
</tr>
</thead>

<?
$query11="SELECT
  rm_pasien.id_pasien,
  rm_pasien.nama_pasien,
  rm_diet.diet,
  rm_jenis_diet.jenis_diet,
  rm_detail_kamar.bed,
  rm_kamar.kamar,
  rm_ruang.ruang,
  rm_detail_diet.keterangan,
  rm_detail_diet.tgl_diet,
  rm_detail_diet.waktu_diet,
  rm_detail_diet.id_ruang,
  rm_kelas.kelas
FROM
  rm_detail_diet,
  rm_diet,
  rm_jenis_diet,
  rm_pendaftaran,
  rm_pasien,
  rm_detail_kamar,
  rm_ruang,
  rm_kelas,
  rm_kamar
WHERE
  rm_detail_diet.id_diet = rm_diet.id_diet AND
  rm_detail_diet.id_jenis_diet = rm_jenis_diet.id_jenis_diet AND
  rm_detail_diet.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
  rm_detail_diet.id_pasien = rm_pasien.id_pasien AND
  rm_detail_diet.id_detail_kamar = rm_detail_kamar.id_detail_kamar AND
  rm_detail_kamar.id_kamar = rm_kamar.id_kamar AND
  rm_detail_diet.id_ruang = rm_ruang.id_ruang AND
  rm_pendaftaran.id_kelas = rm_kelas.id_kelas AND
  rm_detail_diet.tgl_diet BETWEEN '$tgl_awal' AND '$tgl_akhir' AND
  rm_detail_diet.id_ruang = $ruang AND
  rm_detail_diet.waktu_diet = $waktu AND
  rm_detail_diet.del_flag <> '1'
ORDER BY rm_detail_diet.waktu_diet";

$result = @mysql_query($query11);	
$jum_n = @mysql_num_rows($result);
$no=1;
$waktu_diet_lama='x';
while($row = @mysql_fetch_array($result)){
	$nama_pasien =  $row['nama_pasien'];
	$id_pasien =  $row['id_pasien'];
	$diet =  $row['diet'];
	$jenis_diet =  $row['jenis_diet'];
	$bed =  $row['bed'];
	$ruang =  $row['ruang'];
	$kamar =  $row['kamar'];
	$keterangan =  $row['keterangan'];
	$tgl_diet =  $row['tgl_diet'];
	$waktu_diet =  $row['waktu_diet'];
	$id_ruang =  $row['id_ruang'];
	$kelas =  $row['kelas'];
	
	echo "<tr bgcolor='#FFFFFF'>
	<td>
		$no
	</td>
	<td>
		$nama_pasien
	</td>
	<td>
		$kelas
	</td>
	<td>
		$kamar - $bed
	</td>
	<td>
		$diet
	</td>
	<td>
		$jenis_diet
	</td>
	<td>
		$keterangan
	</td>
	</tr>";
	$no++;
	$waktu_diet_lama=$waktu_diet;

	}
?>

</table>


<table class="data">
<tr>
<td>
<b>SUB TOTAL DIET PER PAVILIUM </b>
</td>
<td>
<b><?=$ruang?></b>
</td>
<td>
<b>: <?=$no-1?></b>
</td>
</tr>
</table>
<table width="100%">
<tr><br></tr>
<tr>
<td align="center">
<b>Kepala Instalasi Gizi</b>
</td>
<td align="center">
<b>KEPALA PAVILIUM</b>
</td>
</tr>
<tr>
<td><br><br><br></td>
<td></td>
</tr>
<tr>
<td align="center">
( A.M. Purwaningtyas, Amd. G. )
</td>
<td align="center">
(&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp)
</td>
</tr>
</table>