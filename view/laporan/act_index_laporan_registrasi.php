<?
session_start();
ini_set("date.timezone", "Asia/Jakarta");
@require_once '../../common/function.php';
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");
$fungsi = new fungsi();

########## Filter Pencarian #################
$tgl_awal = $_POST["tgla"];
$tgl_akhir = $_POST["tglb"];
$id_ruang_s = @$_SESSION['level'];
########## /Filter Pencarian #################
$query="SELECT
  rm_pasien.id_pasien,
  rm_pasien.nama_pasien,
  rm_pasien.alamat,
  rm_pasien.id_kelamin,
  rm_diagnosa.id_diagnosa,
  rm_pasien.id_tipe_pasien,
  DATE(rm_pendaftaran.tgl_pendaftaran) AS tgl_pendaftaran,
  rm_pendaftaran.id_asal_pendaftaran,
  rm_diagnosa.penyakit_primer,
  rm_diagnosa.penyakit_sekunder,
  COUNT(rm_pendaftaran.id_pendaftaran) AS n,
  rm_diagnosa.id_dokter 
FROM
  rm_pasien,
  rm_pendaftaran,
  rm_ruang,
  rm_diagnosa,
  rm_pasien_keluar
WHERE
  rm_pendaftaran.id_ruang = $id_ruang_s AND
  rm_diagnosa.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
  rm_diagnosa.id_pasien = rm_pasien.id_pasien AND
  rm_pendaftaran.id_pendaftaran = rm_pasien_keluar.id_pendaftaran AND
  rm_diagnosa.del_flag <> 1 AND
  DATE(rm_diagnosa.tgl_diagnosa) BETWEEN '$tgl_awal' AND '$tgl_akhir'
GROUP BY rm_diagnosa.id_diagnosa
ORDER BY rm_pasien.id_pasien" ;

$result = mysql_query($query);	
$jum_n = @mysql_num_rows($result);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}
?>
<div class='printArea'><p align='center'><strong><u>LAPORAN DIAGNOSA RSUD Dr. SOEGIRI LAMONGAN</u></strong>
	<BR>Tgl <?=$fungsi->codeDate($tgl_awal)?> s/d <?=$fungsi->codeDate($tgl_akhir)?>
</p>
<hr>
<?

?>
<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<tr>
	<td width='1%' align='center' bgcolor='#999999'>No.</td>
	<td width='5%' align='center' bgcolor='#999999'>Tanggal Masuk</td>
	<td width='5%' align='center' bgcolor='#999999'>Register</td>
	<td width='5%' align='center' bgcolor='#999999'>Nama</td>
	<td width='15%' align='center' bgcolor='#999999'>Alamat</td>
	<td width='5%' align='center' bgcolor='#999999'>L/P</td>
	<td width='5%' align='center' bgcolor='#999999'>Diagnosa Primer</td>
	<td width='5%' align='center' bgcolor='#999999'>ICD</td>
	<td width='5%' align='center' bgcolor='#999999'>Diagnosa Sekunder</td>
	<td width='5%' align='center' bgcolor='#999999'>ICD</td>	
	<td width='5%' align='center' bgcolor='#999999'>Tipe</td>
	<td width='5%' align='center' bgcolor='#999999'>Konsulan</td>
	<td width='5%' align='center' bgcolor='#999999'>Dokter</td>
</tr>
<?

$nom = 1;
while($row = mysql_fetch_array($result)){	
	$kelas = "primer";
	$id_pasien =  $row['id_pasien'];
	$nama_pasien =  $row['nama_pasien'];
	$alamat =  $row['alamat'];	
	$id_kelamin =  $row['id_kelamin'];	
	if($id_kelamin==1) $jkel = "L";
	else $jkel = "P";

	$id_diagnosa =  $row['id_diagnosa'];	
	$id_tipe_pasien =  $row['id_tipe_pasien'];	
	$tgl_pendaftaran =  $fungsi->formatDateDb($row['tgl_pendaftaran']);
	$id_asal_pendaftaran =  $row['id_asal_pendaftaran'];	
	if($id_asal_pendaftaran==0) $id_asal_pendaftaran=1;

	$penyakit_primer =  $row['penyakit_primer'];
	$penyakit_sekunder =  $row['penyakit_sekunder'];

	$query4="SELECT nama_penyakit as nama_penyakit_prim, icd as icd_prim FROM rm_penyakit WHERE id_penyakit=$penyakit_primer";
	$result4 = @mysql_query($query4);	
	$nama_penyakit_prim = @mysql_result($result4,0,"nama_penyakit_prim");	
	$icd_prim = @mysql_result($result4,0,"icd_prim");	

	$query5="SELECT nama_penyakit as nama_penyakit_sek, icd as icd_sek FROM rm_penyakit WHERE id_penyakit=$penyakit_sekunder";
	$result5 = @mysql_query($query5);	
	$nama_penyakit_sek = @mysql_result($result5,0,"nama_penyakit_sek");	
	$icd_sek = @mysql_result($result5,0,"icd_sek");	


	$query2="SELECT tipe_pasien FROM rm_tipe_pasien WHERE id_tipe_pasien=$id_tipe_pasien";
	$result2 = mysql_query($query2);	
	$tipe_pasien = mysql_result($result2,0,"tipe_pasien");	

	$query3="SELECT ruang FROM rm_ruang WHERE id_ruang=$id_asal_pendaftaran";
	$result3 = mysql_query($query3);	
	$ruang = mysql_result($result3,0,"ruang");
	
	$id_dokter =  $row['id_dokter'];
	
	$query7="SELECT rm_dokter.id_dokter, rm_dokter.nama_dokter FROM rm_dokter WHERE rm_dokter.id_dokter = $id_dokter";
	$result7 = mysql_query($query7);	
	$id_dokter = mysql_result($result7,0,"id_dokter");
	$nama_dokter = mysql_result($result7,0,"nama_dokter");
	
	echo "<tr>
		<td align='center' bgcolor='#FFFFFF'>$nom</td>
		<td align='center' bgcolor='#FFFFFF'>$tgl_pendaftaran</td>
		<td align='center' bgcolor='#FFFFFF'>$id_pasien</td>
		<td align='center' bgcolor='#FFFFFF'>$nama_pasien</td>
		<td align='center' bgcolor='#FFFFFF'>$alamat</td>
		<td align='center' bgcolor='#FFFFFF'>$jkel</td>
		<td align='center' bgcolor='#FFFFFF'>$nama_penyakit_prim</td>
		<td align='center' bgcolor='#FFFFFF'>$icd_prim</td>
		<td align='center' bgcolor='#FFFFFF'>$nama_penyakit_sek</td>
		<td align='center' bgcolor='#FFFFFF'>$icd_sek</td>		
		<td align='center' bgcolor='#FFFFFF'>$tipe_pasien</td>
		<td align='center' bgcolor='#FFFFFF'>$ruang</td>
		<td align='center' bgcolor='#FFFFFF'>$nama_dokter</td>
	</tr>";

	$nom++;
}
?>
</table>