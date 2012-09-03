<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

########## Filter Pencarian #################
$nama_pasien = $_POST["nama_pasien"]; 
$alamat = $_POST["alamat"]; 
$tgl_awal = $_POST["tgla"];
$tgl_akhir = $_POST["tglb"]; 

$sql_where = '';

if($nama_pasien!='') 
	$sql_where = " rm_pasien.nama_pasien LIKE '%$nama_pasien%' AND ";

if($alamat!='') 
	$sql_where .= " rm_pasien.alamat LIKE '%$alamat%' AND ";


########## /Filter Pencarian #################

$query="SELECT
  rm_pasien.nama_pasien,
  rm_pasien.tgl_lahir,
  rm_pasien.alamat,
  rm_pasien.id_kecamatan,
  rm_pasien.id_kelurahan,
  rm_pasien.id_kota,
  rm_pasien.id_pasien
FROM
  rm_pasien
WHERE 
  $sql_where
  rm_pasien.tgl_lahir BETWEEN '$tgl_awal' AND '$tgl_akhir'" ;

$result = mysql_query($query);	
$jum_n = @mysql_num_rows($result);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}
?>
<div class='printArea'><p align='center'><strong><u>DAFTAR PASIEN RSUD Dr. SOEGIRI LAMONGAN</u></strong></p>
<hr>
<?
$nom = 1;
echo "<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>";
echo "<tr>
		<td width='1%' align='center' bgcolor='#999999'>Kode</td>
		<td width='5%' align='center' bgcolor='#999999'>Nama</td>
		<td width='5%' align='center' bgcolor='#999999'>Tgl.Lahir</td>
		<td width='5%' align='center' bgcolor='#999999'>Alamat</td>
		<!-- <td width='5%' align='center' bgcolor='#999999'>Kota</td>
		<td width='5%' align='center' bgcolor='#999999'>Kecamtan</td>
		<td width='5%' align='center' bgcolor='#999999'>Kelurahan</td> -->
	</tr>";
$tipe_pendaftaran_lama = 'x';
$total_karcis = 0;
while($row = mysql_fetch_array($result)){	
	$nama_pasien =  $row['nama_pasien'];
	$tgl_lahir =  $row['tgl_lahir'];
	$alamat =  $row['alamat'];
	$id_kecamatan =  $row['id_kecamatan'];
	$id_kelurahan =  $row['id_kelurahan'];
	$id_kota =  $row['id_kota'];
	$id_pasien =  $row['id_pasien'];

	echo "
	<tr>
		<td align='center' bgcolor='#FFFFFF'>$id_pasien</td>
		<td align='left' bgcolor='#FFFFFF'>$nama_pasien</td>
		<td align='center' bgcolor='#FFFFFF'>$tgl_lahir</td>	
		<td align='left' bgcolor='#FFFFFF'>$alamat</td>
		<!-- <td align='center' bgcolor='#FFFFFF'>$id_kota</td>
		<td align='center' bgcolor='#FFFFFF'>$id_kecamatan</td>	
		<td align='center' bgcolor='#FFFFFF'>$id_kelurahan</td> -->
	</tr>";
	$nom++;
}

?>
</table>