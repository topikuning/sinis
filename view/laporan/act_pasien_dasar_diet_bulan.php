<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");


$nmabulan = array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DESEMBER");

function cekn($n){
	if($n<=0) return "&nbsp;";
	else return $n;
}

if(@$_GET['cetak']!=''){
	$tahun = $_GET['thn'];
	$bulan = $_GET['bln'];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}else{
	$tahun = $_POST['tahun'];
	$bulan = $_POST['bulan'];
}

if($tahun==0 || $bulan==0) { 
	echo "Silahkan Pilih Tahun & Bulan";
	exit;
}
?>

<div class='printArea'><p align='center'><strong><u>KAPASITAS PASIEN BERDASARKAN DIET<br>BULAN <? echo $nmabulan[$bulan-1]." ".$tahun; ?></u></strong></p>

<table style='font-family: verdana; font-size: 11px; border: 0px solid #000000' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000' >
<thead>
<tr align="center">
	<td  align='center' bgcolor='#999999'>&nbsp</td>
	<td colspan="5" align='center' bgcolor='#999999'><b>TKTP</b></td>
	<td colspan="5" align='center' bgcolor='#999999'><b>RENDAH GARAM</b></td>
	<td colspan="5" align='center' bgcolor='#999999'><b>DM</b></td>
	<td colspan="5" align='center' bgcolor='#999999'><b>KV</b></td>
	<td colspan="5" align='center' bgcolor='#999999'><b>RENDAH SERAT</b></td>
	<td colspan="5" align='center' bgcolor='#999999'><b>RENDAH PURIN</b></td>
</tr>
<tr align="center">
	<td align='center' bgcolor='#999999'>Tgl.</td>

	<td width="40px" align='center' bgcolor='#999999'>III</td>
	<td width="40px" align='center' bgcolor='#999999'>II</td>
	<td width="40px" align='center' bgcolor='#999999'>I</td>
	<td width="40px" align='center' bgcolor='#999999'>JPS</td>
	<td width="40px" align='center' bgcolor='#999999'>VIP</td>

	<td width="40px" align='center' bgcolor='#999999'>III</td>
	<td width="40px" align='center' bgcolor='#999999'>II</td>
	<td width="40px" align='center' bgcolor='#999999'>I</td>
	<td width="40px" align='center' bgcolor='#999999'>JPS</td>
	<td width="40px" align='center' bgcolor='#999999'>VIP</td>

	<td width="40px" align='center' bgcolor='#999999'>III</td>
	<td width="40px" align='center' bgcolor='#999999'>II</td>
	<td width="40px" align='center' bgcolor='#999999'>I</td>
	<td width="40px" align='center' bgcolor='#999999'>JPS</td>
	<td width="40px" align='center' bgcolor='#999999'>VIP</td>

	<td width="40px" align='center' bgcolor='#999999'>III</td>
	<td width="40px" align='center' bgcolor='#999999'>II</td>
	<td width="40px" align='center' bgcolor='#999999'>I</td>
	<td width="40px" align='center' bgcolor='#999999'>JPS</td>
	<td width="40px" align='center' bgcolor='#999999'>VIP</td>

	<td width="40px" align='center' bgcolor='#999999'>III</td>
	<td width="40px" align='center' bgcolor='#999999'>II</td>
	<td width="40px" align='center' bgcolor='#999999'>I</td>
	<td width="40px" align='center' bgcolor='#999999'>JPS</td>
	<td width="40px" align='center' bgcolor='#999999'>VIP</td>

	<td width="40px" align='center' bgcolor='#999999'>III</td>
	<td width="40px" align='center' bgcolor='#999999'>II</td>
	<td width="40px" align='center' bgcolor='#999999'>I</td>
	<td width="40px" align='center' bgcolor='#999999'>JPS</td>
	<td width="40px" align='center' bgcolor='#999999'>VIP</td>
</tr>
</thead>
<?
$bulan = sprintf("%02d", $bulan);	

$sqlh = "SELECT DAY(LAST_DAY('$tahun-$bulan-01')) AS jhari";
$resultk = @mysql_query($sqlh);	
$jhari = @mysql_result($resultk,0,"jhari");	

$totk = array();
for($i=1;$i<=$jhari;$i++){
	$tgl = $i;
	$tglx = sprintf("%02d", $tgl);

	$sqlhx = "SELECT
			  rm_detail_diet.id_jenis_diet,
			  rm_kelas.id_kelas, 
			  COUNT(rm_detail_diet.id_pasien) AS jm
			FROM
			  rm_detail_diet,
			  rm_pendaftaran,
			  rm_kelas
			WHERE
			  rm_detail_diet.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
			  rm_pendaftaran.id_kelas = rm_kelas.id_kelas AND
                          rm_detail_diet.del_flag <> '1' AND
                          rm_pendaftaran.del_flag <> '1' AND
			  rm_detail_diet.tgl_diet = '$tahun-$bulan-$tglx'
			GROUP BY rm_detail_diet.id_jenis_diet, rm_kelas.id_kelas, rm_detail_diet.id_pasien";
	$resultx = @mysql_query($sqlhx);	
	$kapasitas = array();
	while($datax = mysql_fetch_array($resultx)){
		$kapasitas[$datax['id_jenis_diet'].$datax['id_kelas']] = $datax['jm'];
		@$totk[$datax['id_jenis_diet'].$datax['id_kelas']] += $datax['jm'];
	}

?>
<tr align="center">
	<td align='center' bgcolor='#FFFFFF'><?=$tgl?></td>

	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[13]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[12]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[11]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[16]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[14]);?></td>

	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[23]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[22]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[21]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[26]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[24]);?></td>

	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[33]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[32]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[31]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[36]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[34]);?></td>

	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[43]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[42]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[41]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[46]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[44]);?></td>

	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[53]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[52]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[51]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[56]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[54]);?></td>

	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[63]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[62]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[61]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[66]);?></td>
	<td align='center' bgcolor='#FFFFFF'><?=cekn(@$kapasitas[64]);?></td>
</tr>

<?

}
?>

<tr align="center">
	<td align='center' bgcolor='#999999'>Total</td>

	<td align='center' bgcolor='#999999'><?=cekn(@$totk[13]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[12]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[11]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[16]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[14]);?></td>

	<td align='center' bgcolor='#999999'><?=cekn(@$totk[23]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[22]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[21]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[26]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[24]);?></td>

	<td align='center' bgcolor='#999999'><?=cekn(@$totk[33]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[32]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[31]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[36]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[34]);?></td>

	<td align='center' bgcolor='#999999'><?=cekn(@$totk[43]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[42]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[41]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[46]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[44]);?></td>

	<td align='center' bgcolor='#999999'><?=cekn(@$totk[53]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[52]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[51]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[56]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[54]);?></td>

	<td align='center' bgcolor='#999999'><?=cekn(@$totk[63]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[62]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[61]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[66]);?></td>
	<td align='center' bgcolor='#999999'><?=cekn(@$totk[64]);?></td>
</tr>
</table>