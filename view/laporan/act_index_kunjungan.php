<?
ini_set("date.timezone", "Asia/Jakarta");
require_once '../../common/function.php';
$fungsi = new fungsi();


########## Inisialisasi Data #################
if(@$_GET['cetak']!=''){
	$tgl_awal = $_GET["tgla"]; 
	$tgl_akhir = $_GET["tglb"]; 
	$id_ruang = $_GET["idruang"];
	$id_tipe_pasien = $_GET["idtpp"];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}else{
	$tgl_awal = $_POST["tgla"]; 
	$tgl_akhir = $_POST["tglb"]; 
	$id_tipe_pasien = $_POST["tipe_pasien"];
	$id_ruang = $_POST["ruang"];
}
########## /Filter Pencarian #################

$query="SELECT
  c.ruang,
  date(a.tgl_pendaftaran) AS tgl,
  d.tipe_pasien,
  b.id_pasien,
  b.nama_pasien,
  b.alamat,
  e.tipe_pendaftaran
FROM
  rm_pendaftaran a,
  rm_pasien b,
  rm_ruang c,
  rm_tipe_pasien d,
  rm_tipe_pendaftaran e
WHERE
  b.id_pasien = a.id_pasien AND
  c.id_ruang = a.id_ruang AND
  e.id_tipe_pendaftaran = a.id_tipe_pendaftaran AND
  d.id_tipe_pasien = b.id_tipe_pasien AND
  a.id_tipe_pendaftaran NOT IN (6,7) AND
  a.del_flag <> '1' AND
  DATE(a.tgl_pendaftaran) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'";

	if(isset($_POST["ruang"]) && $_POST["ruang"]!='')
        $query.=" AND a.id_ruang = '".$_POST["ruang"]."'";
		
	if(isset($_POST["tipe_pasien"]) && $_POST["tipe_pasien"]!='')
        $query.=" AND b.id_tipe_pasien = '".$_POST["tipe_pasien"]."'";
		
	if(isset($_GET["idruang"]) && $_GET["idruang"]!='')
        $query.=" AND a.id_ruang = '".$_GET["idruang"]."'";
		
	if(isset($_GET["idtpp"]) && $_GET["idtpp"]!='')
        $query.=" AND b.id_tipe_pasien = '".$_GET["idtpp"]."'";
  
$query.="
 ORDER BY
  a.id_ruang,
  date(a.tgl_pendaftaran),
  a.id_tipe_pendaftaran,
  b.id_tipe_pasien,
  b.id_pasien";
  

$result = $fungsi->runQuery($query);
$jum_n = @mysql_num_rows($result);
if($jum_n<=0) { 
	echo "Data Tidak Ditemukan!";
	exit;
}
?>
<div class='printArea'><p align='center'><strong><u>LAPORAN KUNJUNGAN RSUD Dr. SOEGIRI LAMONGAN</u></strong>
        <BR>Tgl <?=$fungsi->codeDate($tgl_awal)?> s/d <?=$fungsi->codeDate($tgl_akhir)?>
</p>
<hr>
<?
$nom = 1;
echo "<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>";

$ruang_lama = 'x';
$total_kunjungan = 0;
while($row = mysql_fetch_array($result)){
	$ruang = $row['ruang'];
	$tgl = $fungsi->formatDateDb($row['tgl']);
	$tipe_pasien = $row['tipe_pasien'];
	$id_pasien = $row['id_pasien'];
	$nama_pasien = $row['nama_pasien'];
	$alamat = $row['alamat'];
	$tipe_pendaftaran = $row['tipe_pendaftaran'];
	
	if($ruang!=$ruang_lama){
		echo "<tr>
				<td align='left' bgcolor='#FFFFFF' colspan='7'>&nbsp;<B>$ruang</B></td>
			</tr>
			<tr>
				<td width='1%' align='center' bgcolor='#999999'>No.</td>
				<td width='1%' align='center' bgcolor='#999999'>Tanggal</td>
				<td width='5%' align='center' bgcolor='#999999'>Tipe Pasien</td>
				<td width='5%' align='center' bgcolor='#999999'>No. RM</td>
				<td width='5%' align='center' bgcolor='#999999'>Nama Px</td>
				<td width='5%' align='center' bgcolor='#999999'>Alamat</td>
				<td width='5%' align='center' bgcolor='#999999'>Status</td>
			</tr>";
			$nom=1;
	}

	echo "
	<tr>
		<td align='center' bgcolor='#FFFFFF'>$nom</td>
		<td align='center' bgcolor='#FFFFFF'>$tgl</td>
		<td align='center' bgcolor='#FFFFFF'>$tipe_pasien</td>		
		<td align='center' bgcolor='#FFFFFF'>$id_pasien</td>
		<td align='center' bgcolor='#FFFFFF'>$nama_pasien</td>		
		<td align='center' bgcolor='#FFFFFF'>$alamat</td>		
		<td align='center' bgcolor='#FFFFFF'>$tipe_pendaftaran</td>		
	</tr>";

	$ruang_lama = $ruang;

	$nom++;
}
if ($id_tipe_pasien != '')
echo "Tipe Pasien: <B>$tipe_pasien</B>";
echo "
	<tr>
		<td align='center' bgcolor='#FFFFFF' colspan='6'><b>TOTAL</b></td>
                <td align='center' bgcolor='#FFFFFF'>$jum_n</td>
	</tr>";
?>

</table>