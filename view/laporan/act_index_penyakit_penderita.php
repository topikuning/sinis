<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

########## Filter Pencarian #################
$tgl_awal = $_POST["tgla"];
$tgl_akhir = $_POST["tglb"]; 
$diagnosa = $_POST["diagnosa"]; 
$cari_tipe_ruan = $_POST["tipe_ruang"];

########## /Filter Pencarian #################
if($cari_tipe_ruan==1)
	$where = "SELECT id_ruang FROM rm_ruang WHERE id_ruang=8";
else if($cari_tipe_ruan==2)
	$where = "SELECT id_ruang FROM rm_ruang WHERE id_ruang<>8";
else {
	$where = "";
	echo "Silahkan Pilih Tipe Jenis<br>";
}

$query="SELECT
  rm_pasien.nama_pasien,
  rm_pasien.id_pasien,
  DATE(rm_diagnosa.tgl_diagnosa) AS tgl_diagnosa,
  rm_pasien.tgl_lahir,
  (YEAR(rm_diagnosa.tgl_diagnosa)-YEAR(rm_pasien.tgl_lahir)) AS umur,
  rm_pasien.id_kelamin,
  rm_pasien_keluar.id_keadaan,
  rm_diagnosa.id_dokter,
  DATE_FORMAT(rm_pendaftaran.tgl_pendaftaran,'%d-%m-%Y') AS tgl_pendaftaran,
  rm_pendaftaran.tgl_pendaftaran as tgl_pendaftaran2,
  DATE_FORMAT(rm_pasien_keluar.tgl_keluar,'%d-%m-%Y') AS tgl_keluar,
  rm_pasien_keluar.tgl_keluar as tgl_keluar2,
  rm_pasien.id_kecamatan,
  rm_pendaftaran.id_ruang,
  rm_penyakit.id_penyakit,
  rm_penyakit.nama_penyakit,
  rm_diagnosa.penyakit_primer,
  rm_diagnosa.penyakit_sekunder,
  rm_pendaftaran.id_kelas,
  rm_penyakit.icd
FROM
  rm_pasien_keluar,
  rm_pasien,
  rm_pendaftaran,
  rm_ruang,
  rm_diagnosa,
  rm_penyakit
WHERE
  rm_pendaftaran.id_pasien = rm_pasien.id_pasien AND
  rm_pendaftaran.id_ruang = rm_ruang.id_ruang AND
  rm_diagnosa.penyakit_primer = rm_penyakit.id_penyakit AND
  rm_pendaftaran.id_pendaftaran = rm_diagnosa.id_pendaftaran AND
  rm_diagnosa.id_pendaftaran = rm_pasien_keluar.id_pendaftaran AND
  rm_penyakit.id_penyakit = $diagnosa AND 
  date(rm_pasien_keluar.tgl_keluar) BETWEEN '$tgl_awal' AND '$tgl_akhir' AND
  rm_ruang.id_tipe_ruang IN ($where)
ORDER BY 
  rm_diagnosa.id_diagnosa DESC,
  rm_pasien.id_pasien";
//LIMIT 0,1";

$result2 = mysql_query($query);	
$jum_n = @mysql_num_rows($result2);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}

$queryK = "SELECT id_penyakit,nama_penyakit,icd FROM rm_penyakit where id_penyakit=$diagnosa";
$resultk = mysql_query($queryK);	
$nm_penykit = mysql_result($resultk,0,"nama_penyakit");	
$icd = mysql_result($resultk,0,"icd");	
?>
<div class='printArea'><p align='center'><strong><u>KARTU INDEKS PENYAKIT PENDERITA DIRAWAT DI RUMAH SAKIT</u></strong></p>

<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='1' cellspacing='1' bgcolor='#FFFFFF'>
<tr>
	<td width='80' bgcolor='#FFFFFF' align='left'>Nomor RS</td>
	<td width='1' bgcolor='#FFFFFF' align='left'>:</td>
	<td width='250' bgcolor='#FFFFFF' align='center'>
		<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
		<tr>
			<td bgcolor='#FFFFFF' align='center'>3</td>
			<td bgcolor='#FFFFFF' align='center'>5</td>
			<td bgcolor='#FFFFFF' align='center'>2</td>
			<td bgcolor='#FFFFFF' align='center'>4</td>
			<td bgcolor='#FFFFFF' align='center'>0</td>
			<td bgcolor='#FFFFFF' align='center'>1</td>
			<td bgcolor='#FFFFFF' align='center'>6</td>
		</tr>
		</table>
	</td>
	<td width='600' bgcolor='#FFFFFF' align='center' rowspan='2' valign='top'>
		<font style='font-size: 20px;'>Diagnosa : <?=$nm_penykit?></font>
	</td>
	<td width='100' bgcolor='#FFFFFF' align='left'>Nomor Kode</td>
	<td bgcolor='#FFFFFF' align='left'>.........</td>
</tr>
<tr>
	<td width='80' bgcolor='#FFFFFF' align='left'>Nama RS</td>
	<td width='1' bgcolor='#FFFFFF' align='left'>:</td>
	<td bgcolor='#FFFFFF' align='left'>RSUD Dr. SOEGIRI LAMONGAN</td>
	<td bgcolor='#FFFFFF' align='left'>(ICD Revisi IX)</td>
	<td bgcolor='#FFFFFF' align='left'><font style='font-size: 20px;'><?=$icd?></font></td>
</tr>
</table>

<hr>
<?
echo "<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>";
echo "<tr>
		<td align='center' bgcolor='#999999' rowspan='3'>No.</td>
		<td width='50' align='center' bgcolor='#999999' rowspan='3'>No.soegiri Medik</td>
		<td width='80' align='center' bgcolor='#999999' rowspan='3'>Tanggal Masuk Rumah Sakit</td>
		<td width='60' align='center' bgcolor='#999999' rowspan='3'>Tanggal Keluar Rumah Sakit</td>
		<td width='15' align='center' bgcolor='#999999' rowspan='3'>Lamanya dirawat</td>
		<td width='15' align='center' bgcolor='#999999' rowspan='3'>Kelas Perawatan</td>
		<td width='40' align='center' bgcolor='#999999' rowspan='3'>Angka Kode ke 4 ICDIX</td>
		<td width='30' align='center' bgcolor='#999999' colspan='2' rowspan='2'>Seks</td>
		<td width='100' align='center' bgcolor='#999999' rowspan='2' colspan='7'>Kelompok Umur Penderita</td>
		<td width='15' align='center' bgcolor='#999999' rowspan='3'>Komplikasi (Nomor Kode)</td>
		<td align='center' bgcolor='#999999' rowspan='3'>Diagnosa Sekunder</td>
		<td align='center' bgcolor='#999999' rowspan='3'>Operasi</td>
		<td align='center' bgcolor='#999999' rowspan='3'>Meninggal</td>
		<td align='center' bgcolor='#999999' rowspan='3'>Asal Penderita</td>
		<td align='center' bgcolor='#999999' rowspan='3' colspan='2'>Keterangan</td>
	</tr>
	<tr>
	</tr>
	<tr>
		<td bgcolor='#999999' align='center'>L</td>
		<td bgcolor='#999999' align='center'>P</td>
		<td bgcolor='#999999' align='center'><1</td>
		<td bgcolor='#999999' align='center'>1-4</td>
		<td bgcolor='#999999' align='center'>5-14</td>
		<td bgcolor='#999999' align='center'>15-24</td>
		<td bgcolor='#999999' align='center'>25-44</td>
		<td bgcolor='#999999' align='center'>45-64</td>
		<td bgcolor='#999999' align='center'>65+</td>
	</tr>
	<tr>";
		for($s=0;$s<=21;$s++)
			echo "<td bgcolor='#999999' align='center'>$s</td>";

	echo "</tr>";

$nom = 1; $id_pasien_lama='x';
while($row = mysql_fetch_array($result2)){	
	//$nama_pasien =  $row['nama_pasien'];
	$id_pasien =  $row['id_pasien'];

			#List Diagnosa Sekunder ################################################
			
			$query2="SELECT
			  rm_diagnosa.penyakit_sekunder
			FROM
			  rm_pasien_keluar,
			  rm_pasien,
			  rm_pendaftaran,
			  rm_ruang,
			  rm_diagnosa,
			  rm_penyakit
			WHERE
			  rm_pendaftaran.id_pasien = rm_pasien.id_pasien AND
			  rm_pendaftaran.id_ruang = rm_ruang.id_ruang AND
			  rm_diagnosa.penyakit_primer = rm_penyakit.id_penyakit AND
			  rm_pendaftaran.id_pendaftaran = rm_diagnosa.id_pendaftaran AND
			  rm_diagnosa.id_pendaftaran = rm_pasien_keluar.id_pendaftaran AND
			  rm_penyakit.id_penyakit = $diagnosa AND 
			  date(rm_pasien_keluar.tgl_keluar) BETWEEN '$tgl_awal' AND '$tgl_akhir' AND
			  rm_ruang.id_tipe_ruang IN ($where) AND
			  rm_penyakit.id_penyakit = $diagnosa AND 
			  rm_pendaftaran.id_pasien = $id_pasien AND
			  rm_diagnosa.penyakit_sekunder <> 0
			ORDER BY 
			  rm_diagnosa.id_diagnosa DESC,
			  rm_pasien.id_pasien";
			$result2a = mysql_query($query2); $list_pnykit_sek =""; 
			while($row2a = mysql_fetch_array($result2a)){	
				$penyakit_sekunder =  $row2a['penyakit_sekunder'];
				$queryK = "SELECT id_penyakit,nama_penyakit FROM rm_penyakit where id_penyakit=$penyakit_sekunder";
				$resultk = @mysql_query($queryK);
				$komas = ",<br>";
				$list_pnykit_sek = @mysql_result($resultk,0,"nama_penyakit").$komas.$list_pnykit_sek;	
			}
			
			#/List Diagnosa Sekunder  ################################################


	$tgl_diagnosa =  $row['tgl_diagnosa'];
	$tgl_lahir =  $row['tgl_lahir'];
	$id_kelamin =  $row['id_kelamin'];
	$id_keadaan =  $row['id_keadaan'];
	$id_dokter =  $row['id_dokter'];
	$tgl_pendaftaran =  $row['tgl_pendaftaran'];
	$tgl_pendaftaran2 =  $row['tgl_pendaftaran2'];
	$tgl_keluar =  $row['tgl_keluar'];
	$tgl_keluar2 =  $row['tgl_keluar2'];
	$id_kecamatan =  $row['id_kecamatan'];
	$id_ruang =  $row['id_ruang'];
	$id_penyakit =  $row['id_penyakit'];
	$nama_penyakit =  $row['nama_penyakit'];
	$umur =  $row['umur'];
	$penyakit_sekunder =  $row['penyakit_sekunder'];
	$id_kelas =  $row['id_kelas'];
	$icd =  $row['icd'];	

	$queryK = "SELECT DATEDIFF('$tgl_keluar2','$tgl_pendaftaran2')+1 AS jhari;";
	$resultk = @mysql_query($queryK);	
	$jhari = @mysql_result($resultk,0,"jhari");	

	if($id_pasien==$id_pasien_lama) continue;

	echo "<tr bgcolor='#FFFFFF'>			
			<td align='center'>$nom</td>
			<td>$id_pasien</td>
			<td>$tgl_pendaftaran</td>
			<td>$tgl_keluar</td>";
			
			$queryK2 = "SELECT id_kelas, kelas FROM rm_kelas WHERE id_kelas=$id_kelas";
			$resultk2 = @mysql_query($queryK2);	
			$kelas = @mysql_result($resultk2,0,"kelas");	

			echo "<td align='center'>$jhari</td>
			<td>$kelas</td>
			<td></td>";
			
			if($id_kelamin==1)
				echo "<td><font style='font-size: 16px;'>&#x2713;</font></td><td></td>";
			else if($id_kelamin==2)
				echo "<td></td><td><font style='font-size: 16px;'>&#x2713;</font></td>";
			
			if($umur>=1 && $umur<=4)
				echo "				
				<td></td>
				<td align='center'>$umur</td>
				<td></td>				
				<td></td>
				<td></td>
				<td></td>
				<td></td>";
			else if($umur>=5 && $umur<=14)
				echo "				
				<td></td>				
				<td></td>		
				<td align='center'>$umur</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>";
			else if($umur>=15 && $umur<=24)
				echo "				
				<td></td>
				<td></td>
				<td></td>
				<td align='center'>$umur</td>
				<td></td>
				<td></td>
				<td></td>";
			else if($umur>=25 && $umur<=44)
				echo "				
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td align='center'>$umur</td>
				<td></td>
				<td></td>";
			else if($umur>=45 && $umur<=64)
				echo "				
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td align='center'>$umur</td>
				<td></td>";
			else if($umur>=65)
				echo "				
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td align='center'>$umur</td>";
			else if($umur<=0){
				$queryK = "SELECT DATEDIFF('$tgl_diagnosa','$tgl_lahir') AS jhari;";
				$resultk = @mysql_query($queryK);	
				$jhari = @mysql_result($resultk,0,"jhari");	

				echo "				
				<td align='center'>$jhari hr</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>";
			}

			$lbl='';
			if($id_keadaan==4)
				$lbl = '+<48';
			else if($id_keadaan==5)
				$lbl = '+>48';
			
			$queryK = "SELECT id_penyakit,nama_penyakit FROM rm_penyakit where id_penyakit=$penyakit_sekunder";
			$resultk = @mysql_query($queryK);	
			$nm_penykit = @mysql_result($resultk,0,"nama_penyakit");	

			$queryK = "SELECT id_kecamatan, kecamatan FROM rm_kecamatan WHERE id_kecamatan=$id_kecamatan";
			$resultk = @mysql_query($queryK);	
			$nm_kecmtn = @mysql_result($resultk,0,"kecamatan");	
			
			$queryK = "SELECT nama_dokter FROM rm_dokter WHERE id_dokter=$id_dokter";
			$resultk = @mysql_query($queryK);	
			$nama_dokter = @mysql_result($resultk,0,"nama_dokter");	
			
			//$nm_penykit  $list_pnykit_sek

			echo "
			<td></td>
			<td>$list_pnykit_sek</td>
			<td></td>
			<td>$lbl</td>
			<td>$nm_kecmtn</td>
			<td>$nama_dokter</td>
	</tr>";
	if($id_pasien!=$id_pasien_lama) $nom++;
	$id_pasien_lama = $id_pasien;
}

?>
</table>
<BR><BR><BR>