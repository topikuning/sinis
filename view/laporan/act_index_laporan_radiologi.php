<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

########## Filter Pencarian #################
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
########## /Filter Pencarian #################

$query="SELECT
  DISTINCT rm_radiologi.id_radiologi,
  rm_radiologi.radiologi,
  rm_pendaftaran.id_ruang_asal,
  rm_ruang.ruang
FROM
  rm_pendaftaran,
  rm_pasien,
  rm_radiologi,
  rm_detail_radiologi,
  rm_ruang,
  rm_pasien_keluar
WHERE
  rm_pendaftaran.id_pendaftaran = rm_detail_radiologi.id_pendaftaran AND
  rm_detail_radiologi.id_pasien = rm_pasien.id_pasien AND
  rm_detail_radiologi.id_radiologi = rm_radiologi.id_radiologi AND
  rm_pendaftaran.id_pendaftaran = rm_pasien_keluar.id_pendaftaran   AND
  rm_ruang.id_ruang = rm_pendaftaran.id_ruang_asal AND
  date(rm_pasien_keluar.tgl_keluar) between '$tgl_awal' and '$tgl_akhir'
GROUP BY rm_radiologi.id_radiologi, rm_pendaftaran.id_ruang_asal, rm_pendaftaran.id_pasien" ;

$result = mysql_query($query);	
$jum_a = mysql_num_rows($result);
if($jum_a<=0) { 
	echo "Data Kosong!";
	exit;
}
?>
<div class='printArea'><p align='center'><strong><u>LAPORAN RADIOLOGI RSUD Dr. SOEGIRI LAMONGAN</u></strong>
	<BR>Tgl <?=$tgl_awal?> s/d <?=$tgl_akhir?>
</p>
<hr>
<?

$nom = 1;
echo "<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<tr>
			<td width='2%'  align='center' bgcolor='#999999'><B>No.</B></td>
			<td width='8%' align='center' bgcolor='#999999'><B>Jenis</B></td>
			<td width='28%' align='center' bgcolor='#999999'><B>Periksa</B></td>
			<td width='10%' align='center' bgcolor='#999999'><B>SubLayanan</B></td>
			<td width='10%' align='left' bgcolor='#999999'><B>Nama</B></td>
			<td width='5%' align='center' bgcolor='#999999'><B>Jml</B></td>
			<td width='10%' align='center' bgcolor='#999999'><B>Netto</B></td>
		</tr>";
$id_radiologi_lama='x';
$id_ruang_asal_lama='x';

$tot_jum = 0;
$tot_netto = 0;
while($row = mysql_fetch_array($result)){	
	$id_radiologi =  $row['id_radiologi'];	
	$radiologi =  $row['radiologi'];	
	$id_ruang_asal =  $row['id_ruang_asal'];	
	$ruang =  $row['ruang'];	

	#echo "<BR><BR>";
	$sql2="SELECT
			  rm_radiologi.id_radiologi,
			  rm_radiologi.radiologi,
			  rm_pendaftaran.id_ruang_asal,
			  rm_ruang.ruang,
			  rm_pendaftaran.id_pasien,
			  rm_pasien.nama_pasien,
			  /*rm_radiologi.id_jenis_radiologi,*/
			  rm_radiologi.id_kelompok_radiologi,
			  COUNT(rm_detail_radiologi.id_detail_radiologi) AS jum,
			  SUM(rm_detail_radiologi.tarif) AS netto
			FROM
			  rm_pendaftaran,
			  rm_pasien,
			  rm_radiologi,
			  rm_detail_radiologi,
			  rm_ruang,
			  rm_pasien_keluar
			WHERE
			  rm_pendaftaran.id_pendaftaran = rm_detail_radiologi.id_pendaftaran AND
			  rm_detail_radiologi.id_pasien = rm_pasien.id_pasien AND
			  rm_detail_radiologi.id_radiologi = rm_radiologi.id_radiologi AND
			  rm_ruang.id_ruang = rm_pendaftaran.id_ruang_asal AND
			  rm_pendaftaran.id_pendaftaran = rm_pasien_keluar.id_pendaftaran AND
			  rm_radiologi.id_radiologi= $id_radiologi AND
			  rm_pendaftaran.id_ruang_asal = $id_ruang_asal  AND
				date(rm_pasien_keluar.tgl_keluar) between '$tgl_awal' and '$tgl_akhir'
			GROUP BY rm_radiologi.id_radiologi, rm_pendaftaran.id_ruang_asal, rm_pendaftaran.id_pasien";
	$result2 = mysql_query($sql2);	
	$jum_b = mysql_num_rows($result2);
	$x=1;
	while($row2 = mysql_fetch_array($result2)){	
			#echo "Jum:".$jum_b;
			$jum =  $row2['jum'];
			$netto =  $row2['netto'];
			$nama_pasien =  $row2['nama_pasien'];
			/*$id_jenis_radiologi =  $row2['id_jenis_radiologi'];

			$queryK = "SELECT nam_jnis_radiologi FROM rm_jenis_radiologi WHERE id_jenis_radiologi=$id_jenis_radiologi";
			$resultk = mysql_query($queryK);	
			$nam_jnis_radiologi = @mysql_result($resultk,0,"nam_jnis_radiologi");*/
			
			$id_kelompok_radiologi =  $row2['id_kelompok_radiologi'];

			$queryK = "SELECT kelompok_radiologi FROM rm_kelompok_radiologi WHERE id_kelompok_radiologi=$id_kelompok_radiologi";
			$resultk = mysql_query($queryK);	
			$nam_jnis_radiologi = @mysql_result($resultk,0,"kelompok_radiologi");
			
			if($id_radiologi_lama == $id_radiologi) $colspan = "sama";
			else $colspan = 'no';

			if($colspan=='sama'){
				echo "
				<tr>
					<td align='center' bgcolor='#FFFFFF' colspan='4'></td>
					<td align='left' bgcolor='#FFFFFF'>$nama_pasien</td>
					<td align='center' bgcolor='#FFFFFF'>$jum</td>
					<td align='center' bgcolor='#FFFFFF'>$netto</td>
				</tr>";			
				$tot_jum += $jum;
				$tot_netto += $netto;
			} else {
				echo "
				<tr>
					<td align='center' bgcolor='#FFFFFF'>$nom</td>
					<td align='left' bgcolor='#FFFFFF'>$nam_jnis_radiologi</td>
					<td align='left' bgcolor='#FFFFFF'>$radiologi</td>					
					<td align='left' bgcolor='#FFFFFF'>$ruang</td>
					<td align='left' bgcolor='#FFFFFF'>$nama_pasien</td>
					<td align='center' bgcolor='#FFFFFF'>$jum</td>
					<td align='right' bgcolor='#FFFFFF'>$netto&nbsp;</td>
				</tr>";		
				$tot_jum += $jum;
				$tot_netto += $netto;
				$nom++;
			}

			$x++;

			$id_radiologi_lama = $id_radiologi;
			$id_ruang_asal_lama = $id_ruang_asal;			
	}
}

echo "<tr>
		<td align='center' bgcolor='#FFFFFF' colspan='5'>Grand Total</td>
		<td align='center' bgcolor='#FFFFFF'>$tot_jum</td>
		<td align='right' bgcolor='#FFFFFF'>$tot_netto&nbsp;</td>
	</tr>";
?>
</table>