<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");


######## Filter #########
if(@$_GET['cetak']!=''){
	$id_tipe_pasien = $_GET["idt"];
	$tgl_awal = $_GET["tgla"]; 
	$tgl_akhir = $_GET["tglb"];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}else{
	$id_tipe_pasien = $_POST["tipe_pasien"];
	$tgl_awal = $_POST["tgla"]; 
	$tgl_akhir = $_POST["tglb"];

}
######## /Filter #########

$queryK2="SELECT
  rm_tindakan_ruang.tgl_tindakan,
  rm_tindakan_ruang.id_dokter,
  rm_pendaftaran.id_pendaftaran,
  rm_pasien.id_tipe_pasien,
  rm_detail_tindakan.id_tindakan,
  rm_detail_tindakan.id_ruang
FROM
  rm_tindakan_ruang,
  rm_pendaftaran,
  rm_pasien,
  rm_detail_tindakan
WHERE
  rm_tindakan_ruang.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
  rm_pendaftaran.id_pasien = rm_pasien.id_pasien AND
  rm_tindakan_ruang.id_detail_tindakan = rm_detail_tindakan.id_detail_tindakan AND
  rm_pasien.id_tipe_pasien = $id_tipe_pasien AND
  date(rm_tindakan_ruang.tgl_tindakan) between '$tgl_awal' and '$tgl_akhir' AND rm_pendaftaran.id_tipe_pendaftaran NOT IN (6,7)

  GROUP BY rm_detail_tindakan.id_ruang,
  rm_tindakan_ruang.id_dokter 
  ORDER BY rm_detail_tindakan.id_ruang,
  rm_tindakan_ruang.id_dokter";

$resultk2 = mysql_query($queryK2);	
$jum_n = @mysql_num_rows($resultk2);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}

$queryK = "SELECT tipe_pasien FROM rm_tipe_pasien WHERE id_tipe_pasien=$id_tipe_pasien";
$resultk = @mysql_query($queryK);	
$nama_tipe_pasien = @mysql_result($resultk,0,"tipe_pasien");
?>
<div class='printArea'><p align='center'><strong><u>TINDAKAN RAWAT JALAN RSUD Dr. SOEGIRI LAMONGAN</u></strong>
	<BR>Tgl <?=$tgl_awal?> s/d <?=$tgl_akhir?>
</p><hr>

<CENTER><?=$nama_tipe_pasien;?></CENTER>


<table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<tr>
	<td width='2'  align='center' bgcolor='#999999'><B>No.</B></td>
	<td width='100'  align='center' bgcolor='#999999'><B>Ruang</B></td>
	<td width='190'  align='center' bgcolor='#999999'><B>Dokter</B></td>
	<td width='300' align='center' bgcolor='#999999'><B>Tindakan</B></td>
	<td align='center' bgcolor='#999999'><B>Pelaku</B></td>
	<td align='center' bgcolor='#999999'><B>Jml</B></td>
	<td align='center' bgcolor='#999999'><B>Bruto</B></td>
</tr>
<?
$no=1;
	while($rowk2 = mysql_fetch_array($resultk2)){
		$id_ruang =  $rowk2['id_ruang'];
		$id_dokter =  $rowk2['id_dokter'];
		
		$queryK = "SELECT ruang FROM rm_ruang WHERE id_ruang=$id_ruang";
		$resultk = mysql_query($queryK);	
		$namaruang = mysql_result($resultk,0,"ruang");	
		$queryK = "SELECT nama_dokter FROM rm_dokter WHERE id_dokter=$id_dokter";
		$resultk = mysql_query($queryK);	
		$namadokter = mysql_result($resultk,0,"nama_dokter");

		/*
		echo "<table><tr>
			<td width='5%' align='left' bgcolor='#FFFFFF'>Ruang : ";
			
			$queryK = "SELECT ruang FROM rm_ruang WHERE id_ruang=$id_ruang";
			$resultk = mysql_query($queryK);	
			echo $namaruang = mysql_result($resultk,0,"ruang");			

			echo " / Dokter : ";
			
			$queryK = "SELECT nama_dokter FROM rm_dokter WHERE id_dokter=$id_dokter";
			$resultk = mysql_query($queryK);	
			echo $namadokter = mysql_result($resultk,0,"nama_dokter");	

			echo "</td>
		</tr>
		</table>";
		*/


		#detail--------------------------------------------------------------------------------------------

		$query11="SELECT
  rm_tindakan_ruang.tgl_tindakan,
  rm_tindakan_ruang.id_dokter,
  rm_pendaftaran.id_pendaftaran,
  rm_pasien.id_tipe_pasien,
  rm_detail_tindakan.id_tindakan,
  rm_detail_tindakan.id_ruang,
  COUNT(rm_detail_tindakan.id_tindakan) AS jml,
  SUM(rm_tindakan_ruang.tarif) AS bruto,
  rm_tindakan_ruang.id_pelaku_tindakan 
FROM
  rm_tindakan_ruang,
  rm_pendaftaran,
  rm_pasien,
  rm_detail_tindakan
WHERE
  rm_tindakan_ruang.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
  rm_pendaftaran.id_pasien = rm_pasien.id_pasien AND
  rm_tindakan_ruang.id_detail_tindakan = rm_detail_tindakan.id_detail_tindakan AND
  rm_tindakan_ruang.id_dokter = $id_dokter AND
  rm_detail_tindakan.id_ruang = $id_ruang AND
  rm_pasien.id_tipe_pasien = $id_tipe_pasien AND
  date(rm_tindakan_ruang.tgl_tindakan) between '$tgl_awal' and '$tgl_akhir'
GROUP BY
  rm_tindakan_ruang.id_dokter,
  rm_detail_tindakan.id_tindakan";

			$result = mysql_query($query11);	
			$jum_n = mysql_num_rows($result);
			
			while($row = mysql_fetch_array($result)){
				$id_ruang =  $row['id_ruang'];
				$id_tindakan =  $row['id_tindakan'];
				$jml =  $row['jml'];
				$bruto =  $row['bruto'];
				$id_dokter =  $row['id_dokter'];
				$id_tipe_pasien =  $row['id_tipe_pasien'];
				$id_pelaku_tindakan =  $row['id_pelaku_tindakan'];
				
				if($namaruang_lama==$namaruang) $namaruang='';
				if($namadokter_lama=$namadokter && $namaruang=='') $namadokter='';

				echo "<tr>
					<td align='center' bgcolor='#FFFFFF'>$no</td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;$namaruang</td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;$namadokter</td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;&nbsp;";
					
					$queryK = "SELECT tindakan FROM rm_tindakan WHERE id_tindakan=$id_tindakan";
					$resultk = mysql_query($queryK);	
					echo $rowk = mysql_result($resultk,0,"tindakan");

					echo "</td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;&nbsp;";
					
					$queryK = "SELECT nama_pelaku FROM  rm_pelaku_tindakan WHERE id_pelaku_tindakan=$id_pelaku_tindakan";
					$resultk = mysql_query($queryK);	
					echo $rowk = mysql_result($resultk,0,"nama_pelaku");

					echo "</td>
					<td align='center' bgcolor='#FFFFFF'>$jml</td>
					<td align='right' bgcolor='#FFFFFF'>$bruto</td>
				</tr>";

					$no++;

					$namaruang_lama = $namaruang;
					$namadokter_lama = $namadokter;

			}
			
		#/detail---------------------------------------------------------------------------------------
		

	}
	echo "</table>";

?>