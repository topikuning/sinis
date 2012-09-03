<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

########## Filter Pencarian #################
$diagnosa = $_POST["diagnosa"]; 
$tgl_awal = $_POST["tgla"];
$tgl_akhir = $_POST["tglb"];
$cari_tipe_ruan = $_POST["tipe_ruang"];

########## /Filter Pencarian #################
$where='';
if($cari_tipe_ruan==1)
	$where = "SELECT
			  rm_ruang.id_ruang
			FROM
			  rm_ruang,
			  rm_tipe_ruang
			WHERE
			  rm_ruang.id_tipe_ruang = rm_tipe_ruang.id_tipe_ruang AND
			  rm_ruang.id_tipe_ruang = 8";
else if($cari_tipe_ruan==2)
	$where = "SELECT
			  rm_ruang.id_ruang
			FROM
			  rm_ruang,
			  rm_tipe_ruang
			WHERE
			  rm_ruang.id_tipe_ruang = rm_tipe_ruang.id_tipe_ruang AND
			  rm_ruang.id_tipe_ruang <> 8";

$query="SELECT
  rm_pasien.nama_pasien,
  rm_pasien.id_pasien,
  rm_diagnosa.tgl_diagnosa,
  rm_pasien.tgl_lahir,
  (YEAR(rm_diagnosa.tgl_diagnosa)-YEAR(rm_pasien.tgl_lahir)) AS umur,
  rm_pasien.id_kelamin,
  rm_pasien_keluar.id_keadaan,
  rm_diagnosa.id_dokter,
  DATE_FORMAT(rm_pendaftaran.tgl_pendaftaran,'%d-%m-%Y') AS tgl_pendaftaran,
  DATE_FORMAT(rm_pasien_keluar.tgl_keluar,'%d-%m-%Y') AS tgl_keluar,
  (YEAR(rm_pasien_keluar.tgl_keluar)-YEAR(rm_pendaftaran.tgl_pendaftaran))+1 AS hri_rawat,
  rm_pasien.id_kecamatan,
  rm_pendaftaran.id_ruang,
  rm_penyakit.id_penyakit,
  rm_penyakit.nama_penyakit,
  rm_diagnosa.tgl_diagnosa as tgl_diagnosa2,
  rm_pasien.tgl_lahir as tgl_lahir2
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
  rm_pasien_keluar.id_keadaan in (4,5) AND
  rm_penyakit.id_penyakit= $diagnosa AND
  date(rm_pasien_keluar.tgl_keluar) BETWEEN '$tgl_awal' AND '$tgl_akhir' AND
  rm_pendaftaran.id_ruang IN ($where)
GROUP BY rm_pasien.id_pasien";

$result = mysql_query($query);	
$jum_n = @mysql_num_rows($result);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}

$queryK = "SELECT id_penyakit,nama_penyakit,icd FROM rm_penyakit where id_penyakit=$diagnosa";
$resultk = mysql_query($queryK);	
$nm_penykit = mysql_result($resultk,0,"nama_penyakit");	
$icd = mysql_result($resultk,0,"icd");	

?>
<div class='printArea'><p align='center'><strong><u>KARTU INDEKS KEMATIAN</u></strong></p>

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
		<td align='center' bgcolor='#999999' rowspan='2'>No.</td>
		<td width='150' align='center' bgcolor='#999999' rowspan='2'>Nama Penderita</td>
		<td width='80' align='center' bgcolor='#999999' rowspan='2'>No.Register</td>
		<td width='90' align='center' bgcolor='#999999' colspan='2'>Umur</td>
		<td align='center' bgcolor='#999999' colspan='2'>Kematian</td>
		<td width='160' align='center' bgcolor='#999999' rowspan='2'>Dokter yang merawat</td>
		<td width='40' align='center' bgcolor='#999999' colspan='2'>Tanggal</td>
		<td width='30' align='center' bgcolor='#999999' rowspan='2'>Hari Perawatan</td>
		<td width='100' align='center' bgcolor='#999999' rowspan='2'>Wilayah</td>
		<td align='center' bgcolor='#999999' rowspan='2'>Keterangan</td>
	</tr>
	<tr>
		<td bgcolor='#999999' align='center' >L</td>
		<td bgcolor='#999999' align='center' >P</td>
		<td bgcolor='#999999' align='center' ><48 jam</td>
		<td bgcolor='#999999' align='center' >>48 jam</td>
		<td bgcolor='#999999' align='center' >Masuk</td>
		<td bgcolor='#999999' align='center' >Keluar</td>
	</tr>";

$nom = 1;
while($row = mysql_fetch_array($result)){	
	$nama_pasien =  $row['nama_pasien'];
	$id_pasien =  $row['id_pasien'];
	
if($cari_tipe_ruan==2){
	#Cari Ruang Keluar terakhir ###########################
		$query3="SELECT
		  rm_penggunaan_kamar.id_penggunaan_kamar ,
		  rm_pasien.id_pasien,
		  rm_pasien.nama_pasien,
		  rm_penggunaan_kamar.id_ruang,
		  rm_penggunaan_kamar.tgl_keluar,
		  rm_penggunaan_kamar.keterangan_selesai,
		  rm_detail_kamar.bed,
		  rm_kamar.kamar
		FROM
		  rm_penggunaan_kamar,
		  rm_pasien,
		  rm_detail_kamar,
		  rm_kamar
		WHERE
		  (rm_penggunaan_kamar.id_pasien = rm_pasien.id_pasien) AND
		  rm_penggunaan_kamar.id_detail_kamar = rm_detail_kamar.id_detail_kamar AND
		  rm_detail_kamar.id_kamar = rm_kamar.id_kamar AND
		  rm_pasien.id_pasien = $id_pasien
		ORDER BY  rm_penggunaan_kamar.id_penggunaan_kamar DESC
		LIMIT 0,1";
	$result3 = @mysql_query($query3);	
	$kamarx = @mysql_result($result3,0,"kamar");	
	$bedx = @mysql_result($result3,0,"bed");	
	#########################################################
}

	$tgl_diagnosa =  $row['tgl_diagnosa'];
	$tgl_lahir =  $row['tgl_lahir'];
	$id_kelamin =  $row['id_kelamin'];
	$id_keadaan =  $row['id_keadaan'];
	$id_dokter =  $row['id_dokter'];
	$tgl_pendaftaran =  $row['tgl_pendaftaran'];
	$tgl_keluar =  $row['tgl_keluar'];
	$id_kecamatan =  $row['id_kecamatan'];
	$id_ruang =  $row['id_ruang'];
	$id_penyakit =  $row['id_penyakit'];
	$nama_penyakit =  $row['nama_penyakit'];
	$umur =  $row['umur'];
	$tgl_diagnosa2 =  $row['tgl_diagnosa2'];
	$tgl_lahir2 =  $row['tgl_lahir2'];
	$hri_rawat =  $row['hri_rawat'];	

	$queryK = "SELECT DATEDIFF('$tgl_diagnosa2','$tgl_lahir2')+1 AS jhari;";
	$resultk = @mysql_query($queryK);	
	$umur_hri = @mysql_result($resultk,0,"jhari");	

	$queryK = "SELECT id_keadaan,keadaan FROM rm_keadaan WHERE id_keadaan=$id_keadaan";
	$resultk = mysql_query($queryK);	
	$keadaanx = mysql_result($resultk,0,"keadaan");	

	echo "
	<tr>
		<td align='center' bgcolor='#FFFFFF'>$nom</td>
		<td align='left' bgcolor='#FFFFFF'>$nama_pasien</td>
		<td align='center' bgcolor='#FFFFFF'>$id_pasien</td>";
	
		if($umur<=0) $umur = $umur_hri.' hr';
		if($id_kelamin==1)
			echo "
			<td align='center' width='15' bgcolor='#FFFFFF'>$umur</td>
			<td align='center' width='15' bgcolor='#FFFFFF'></td>";
		else if($id_kelamin==2)
			echo "
			<td align='center' width='15' bgcolor='#FFFFFF'></td>
			<td align='center' width='15' bgcolor='#FFFFFF'>$umur</td>";
		
		if($id_keadaan==4)
			echo "<td align='center' bgcolor='#FFFFFF'><font style='font-size: 16px;'>&#x2713;</font></td>	
			<td align='center' bgcolor='#FFFFFF'></td>";
		else if($id_keadaan==5)
			echo "<td align='center' bgcolor='#FFFFFF'></td>	
			<td align='center' bgcolor='#FFFFFF'><font style='font-size: 16px;'>&#x2713;</font></td>";
	
		$queryK = "SELECT nama_dokter FROM rm_dokter WHERE id_dokter=$id_dokter";
		$resultk = @mysql_query($queryK);	
		$nama_dokter = @mysql_result($resultk,0,"nama_dokter");	

		$queryKc = "SELECT kecamatan FROM rm_kecamatan WHERE id_kecamatan=$id_kecamatan";
		$resultkc = @mysql_query($queryKc);	
		$nama_kec = @mysql_result($resultkc,0,"kecamatan");
		
		$query3="SELECT ruang FROM rm_ruang WHERE id_ruang=$id_ruang";
		$result3 = mysql_query($query3);	
		$ruangx = mysql_result($result3,0,"ruang");	
		if($kamarx!='') $lbl_kamarx =  $kamarx." - ".$bedx;
		else $lbl_kamarx = "";

		if($cari_tipe_ruan==2) $lbl_kamarx= $ruangx;

		echo "<td align='left' bgcolor='#FFFFFF'>$nama_dokter</td>
		<td align='center' bgcolor='#FFFFFF'>$tgl_pendaftaran</td>
		<td align='center' bgcolor='#FFFFFF'>$tgl_keluar</td>	
		<td align='center' bgcolor='#FFFFFF'>$hri_rawat</td>
		<td align='center' bgcolor='#FFFFFF'>$nama_kec</td>	
		<td align='left' bgcolor='#FFFFFF'>$lbl_kamarx</td>
	</tr>";
	$nom++;
}

?>
</table>