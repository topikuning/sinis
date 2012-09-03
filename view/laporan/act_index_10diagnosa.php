<?
session_start();
@require_once("../../common/koneksi.php");
require_once '../../common/function.php';
$fungsi = new fungsi();
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

if(!isset($_SESSION['level'])) { echo "Silahkan login kembali"; exit; }

######## Filter #########
$id_ruange = $_SESSION['level'];
$kondisikan = "";
if($_SESSION['jenis'] != 'pegawai')
	$kondisikan = " AND id_ruang=".$id_ruange."";
$query2="SELECT ruang FROM rm_ruang WHERE id_ruang= $id_ruange ";
$result = @mysql_query($query2);	
$ruangan = @mysql_result($result,0,"ruang");	

if(@$_GET['cetak']!=''){
	$tgl_awal = $_GET["tgla"]; 
	$tgl_akhir = $_GET["tglb"];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}else{
	$tgl_awal = $_POST["tgla"]; 
	$tgl_akhir = $_POST["tglb"];
}
######## /Filter #########
$queryK2="SELECT penyakit_primer as idp,  COUNT(*) AS byk
FROM rm_diagnosa a,  rm_pendaftaran b
WHERE a.id_pendaftaran = b.id_pendaftaran AND 
DATE(tgl_diagnosa) BETWEEN '$tgl_awal' AND '$tgl_akhir' ".$kondisikan."
GROUP BY penyakit_primer
ORDER BY COUNT(*) DESC
LIMIT 0,10";

$resultk2 = mysql_query($queryK2);	
$jum_n = @mysql_num_rows($resultk2);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}
?>
<div class='printArea'><p align='center'><strong><u>TOP 10 TINDAKAN <?=strtoupper($ruangan)?><br>RSUD Dr. SOEGIRI LAMONGAN</u></strong>
	<BR>Tgl <?=$fungsi->codeDate($tgl_awal)?> s/d <?=$fungsi->codeDate($tgl_akhir)?>
</p>
<table style=' font-family: verdana; font-size: 11px;' width='700' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<tr>
	<td width='2'  align='center' bgcolor='#999999'><B>No.</B></td>
	<td width='680' align='center' bgcolor='#999999'><B>Nama Tindakan</B></td>
	<td width='5' align='center' bgcolor='#999999'><B>Jumlah</B></td>
</tr>
<?
$no=1;
	while($rowk2 = mysql_fetch_array($resultk2)){
		$idp =  $rowk2['idp'];		
		$byk =  $rowk2['byk'];		

		$queryK = "SELECT id_penyakit,nama_penyakit,icd FROM rm_penyakit where id_penyakit=$idp";
		$resultk = mysql_query($queryK);	
		$nm_penykit = mysql_result($resultk,0,"nama_penyakit");	

		echo "<tr>
			<td align='center' bgcolor='#FFFFFF'>$no</td>
			<td align='left' bgcolor='#FFFFFF'>&nbsp;$nm_penykit</td>
			<td align='center' bgcolor='#FFFFFF'>$byk</td>
		</tr>";

		$no++;	

	}
	echo "</table>";
?>
