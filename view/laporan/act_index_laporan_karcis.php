<?
ini_set("date.timezone", "Asia/Jakarta");
require_once '../../common/function.php';
$fungsi = new fungsi();


########## Filter Pencarian #################
if(@$_GET['cetak']!=''){
	$tgl_awal = $_GET["tgla"]; 
	$tgl_akhir = $_GET["tglb"]; 
	$id_tipe_pasien = $_GET["idtpp"];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}else{
	$tgl_awal = $_POST["tgla"]; 
	$tgl_akhir = $_POST["tglb"]; 
	$id_tipe_pasien = $_POST["tipe_pasien"];
}
########## /Filter Pencarian #################
if($id_tipe_pasien=="") {
    echo "Tipe Pasien Harus Dipilih.";
    exit;
}

$query="
SELECT f.tipe_pasien, d.tipe_pendaftaran, c.ruang, COUNT(a.id_pendaftaran) AS jml, e.biaya as karcis FROM rm_pendaftaran a, rm_pasien b, rm_ruang c, rm_tipe_pendaftaran d, rm_biaya_pendaftaran e, rm_tipe_pasien f
WHERE b.id_pasien=a.id_pasien AND b.id_tipe_pasien='".$id_tipe_pasien."' AND c.id_ruang=a.id_ruang AND d.id_tipe_pendaftaran=a.id_tipe_pendaftaran and e.id_tipe_pendaftaran=a.id_tipe_pendaftaran
AND DATE(a.tgl_pendaftaran) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
AND a.del_flag <> 1 AND f.id_tipe_pasien='".$id_tipe_pasien."' AND a.id_tipe_pendaftaran NOT IN (6,7) AND a.id_asal_pendaftaran='0' GROUP BY a.id_ruang, a.id_tipe_pendaftaran order by d.tipe_pendaftaran" ;

$result = $fungsi->runQuery($query);
$jum_n = @mysql_num_rows($result);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}
?>
<div class='printArea'><p align='center'><strong><u>LAPORAN PENDAFTARAN KARCIS RSUD Dr. SOEGIRI LAMONGAN</u></strong>
        <BR>Tgl <?=$fungsi->codeDate($tgl_awal)?> s/d <?=$fungsi->codeDate($tgl_akhir)?>
</p>
<hr>
<?
$nom = 1;
echo "<table style='font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>";
$tipe_pendaftaran_lama = 'x';
$total_karcis = 0;
while($row = mysql_fetch_array($result)){
	$ruang =  $row['ruang'];
	$tipenya = $row['tipe_pasien'];
	$jml =  $row['jml'];
	$karcis =  $row['karcis'];
	$tipe_pendaftaran =  $row['tipe_pendaftaran'];
	$id_tipe_pendaftaran =  $row['id_tipe_pendaftaran'];	
	
	if($tipe_pendaftaran!=$tipe_pendaftaran_lama){
		echo "<tr>
				<td align='left' bgcolor='#FFFFFF' colspan='5'>&nbsp;<B>$tipe_pendaftaran</B></td>
			</tr>
			<tr>
				<td width='1%' align='center' bgcolor='#999999'>No.</td>
				<td width='5%' align='center' bgcolor='#999999'>SubLayanan</td>
				<td width='5%' align='center' bgcolor='#999999'>Tarif</td>
				<td width='5%' align='center' bgcolor='#999999'>Jlh Px.</td>
				<td width='5%' align='center' bgcolor='#999999'>Total</td>
			</tr>";
			$nom=1;
	}

	echo "
	<tr>
		<td align='center' bgcolor='#FFFFFF'>$nom</td>
		<td align='center' bgcolor='#FFFFFF'>$ruang</td>
		<td align='right' bgcolor='#FFFFFF'>Rp. ".number_format($karcis,0,',','.')."</td>		
		<td align='center' bgcolor='#FFFFFF'>$jml</td>
		<td align='right' bgcolor='#FFFFFF'>Rp. ".number_format(($karcis*$jml),0,',','.')."</td>		
	</tr>";

	$tipe_pendaftaran_lama = $tipe_pendaftaran;
	$total_karcis += ($karcis*$jml);

	$nom++;
}
echo "Tipe Pasien: <B>$tipenya</B>";
echo "
	<tr>
		<td align='left' bgcolor='#FFFFFF' colspan='5'><hr>
			<B>Grand Total</B> : Rp. ".number_format($total_karcis,0,',','.')."
		</td>	
	</tr>";
?>
</table>