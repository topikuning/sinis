<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

if(@$_GET['cetak']!=''){
	$tahun = $_GET['thn'];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}
else
	$tahun = $_POST['tahun'];

if($tahun==0){ echo "Silahkan Pilih Tahun"; exit; }
function cekn($n){
	if($n<=0) return "&nbsp;";
	else return $n;
}
$nmabulan = array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DESEMBER");
?>
<p align="center">
<b>
KAPASITAS PASIEN BERDASARKAN KELAS PERAWATAN<br>
TAHUN <?=$tahun;?>
</b>
</p>
<table style='font-family: verdana; font-size: 11px; border: 0px solid #000000' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<thead>
<tr align="center" bgcolor='#999999'>
	<td>&nbsp</td>
	<td colspan="5"><b>NASI</b></td>
	<td colspan="5"><b>BUBUR KASAR</b></td>
	<td colspan="5"><b>BUBUR HALUS</b></td>
	<td colspan="5"><b>BUBUR CAIR</b></td>
</tr>
<tr align="center" bgcolor='#999999'>
	<td width="40px">BULAN</td>
	<td width="40px">III</td>
	<td width="40px">II</td>
	<td width="40px">IA</td>
	<td width="40px">JPS</td>
	<td width="40px">VIP</td>
	<td width="40px">III</td>
	<td width="40px">II</td>
	<td width="40px">IA</td>
	<td width="40px">JPS</td>
	<td width="40px">VIP</td>
	<td width="40px">III</td>
	<td width="40px">II</td>
	<td width="40px">IA</td>
	<td width="40px">JPS</td>
	<td width="40px">VIP</td>
	<td width="40px">III</td>
	<td width="40px">II</td>
	<td width="40px">IA</td>
	<td width="40px">JPS</td>
	<td width="40px">VIP</td>
</tr>
</thead>
<?
$totk = array();
for($i=1;$i<=12;$i++){
	
	$sqlhx = "SELECT
			  COUNT(rm_detail_diet.id_pasien) AS jm,
			  rm_detail_diet.id_diet,
			  rm_kelas.id_kelas,
			  rm_detail_diet.tgl_diet
			FROM
			  rm_detail_diet,
			  rm_pendaftaran,
			  rm_kelas,
			  rm_diet
			WHERE
			  rm_detail_diet.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
			  rm_pendaftaran.id_kelas = rm_kelas.id_kelas AND
			  rm_detail_diet.id_diet = rm_diet.id_diet AND
                          rm_detail_diet.del_flag <> '1' AND
                          rm_pendaftaran.del_flag <> '1' AND
			  MONTH(rm_detail_diet.tgl_diet) = $i AND
		      YEAR(rm_detail_diet.tgl_diet) = $tahun
			GROUP BY
			  rm_diet.id_diet,
			  rm_kelas.id_kelas";
	$resultx = @mysql_query($sqlhx);	
	$kapasitas = array();
	while($datax = mysql_fetch_array($resultx)){
		$kapasitas[$datax['id_diet'].$datax['id_kelas']] = $datax['jm'];
		@$totk[$datax['id_diet'].$datax['id_kelas']] += $datax['jm'];
	}
?>
<tr align="center" bgcolor='#FFFFFF'>
	<td align="left"><?=$nmabulan[$i-1]?></td>
	<td><?=cekn(@$kapasitas[183]);?></td>
	<td><?=cekn(@$kapasitas[182]);?></td>
	<td><?=cekn(@$kapasitas[181]);?></td>
	<td><?=cekn(@$kapasitas[186]);?></td>
	<td><?=cekn(@$kapasitas[184]);?></td>

	<td><?=cekn(@$kapasitas[43]);?></td>
	<td><?=cekn(@$kapasitas[42]);?></td>
	<td><?=cekn(@$kapasitas[41]);?></td>
	<td><?=cekn(@$kapasitas[46]);?></td>
	<td><?=cekn(@$kapasitas[44]);?></td>

	<td><?=cekn(@$kapasitas[33]);?></td>
	<td><?=cekn(@$kapasitas[32]);?></td>
	<td><?=cekn(@$kapasitas[31]);?></td>
	<td><?=cekn(@$kapasitas[36]);?></td>
	<td><?=cekn(@$kapasitas[34]);?></td>

	<td><?=cekn(@$kapasitas[23]);?></td>
	<td><?=cekn(@$kapasitas[22]);?></td>
	<td><?=cekn(@$kapasitas[21]);?></td>
	<td><?=cekn(@$kapasitas[26]);?></td>
	<td><?=cekn(@$kapasitas[24]);?></td>
</tr>
<?
}
?>
<tr align="center" bgcolor='#FFFFFF'>
	<td align="left"><b>TOTAL</b></td>
	<td><?=cekn(@$totk[183]);?></td>
	<td><?=cekn(@$totk[182]);?></td>
	<td><?=cekn(@$totk[181]);?></td>
	<td><?=cekn(@$totk[186]);?></td>
	<td><?=cekn(@$totk[184]);?></td>

	<td><?=cekn(@$totk[43]);?></td>
	<td><?=cekn(@$totk[42]);?></td>
	<td><?=cekn(@$totk[41]);?></td>
	<td><?=cekn(@$totk[46]);?></td>
	<td><?=cekn(@$totk[44]);?></td>

	<td><?=cekn(@$totk[33]);?></td>
	<td><?=cekn(@$totk[32]);?></td>
	<td><?=cekn(@$totk[31]);?></td>
	<td><?=cekn(@$totk[36]);?></td>
	<td><?=cekn(@$totk[34]);?></td>

	<td><?=cekn(@$totk[23]);?></td>
	<td><?=cekn(@$totk[22]);?></td>
	<td><?=cekn(@$totk[21]);?></td>
	<td><?=cekn(@$totk[26]);?></td>
	<td><?=cekn(@$totk[24]);?></td>
</tr>
</table>