<?
#if(@$_POST["cari_lap"]){

@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

/*
echo "<br>".$_POST["tgla"];
echo "<br>".$_POST["tglb"];
echo "<br>".$_POST["tipe_pasien"];
echo "<br>".$_POST["tipe_ruang"];
*/
######## Filter #########
$cari_tipe_ruan = $_POST["tipe_ruang"];
$id_tipepasien = $_POST["tipe_pasien"];
$tgl_awal = $_POST["tgla"];
$tgl_akhir = $_POST["tglb"];
$sql_where = '';
if($cari_tipe_ruan==1){
	$sql_where=" AND rm_ruang.id_tipe_ruang = 8 ";
	
	$jdul = "RAWAT INAP";
} else if($cari_tipe_ruan==2){
	$sql_where=" AND rm_ruang.id_tipe_ruang != 8 ";
	$jdul = "RAWAT JALAN";
}

$sql_where2 =" AND rm_pasien.id_tipe_pasien = $id_tipepasien ";
$query2="SELECT tipe_pasien FROM rm_tipe_pasien WHERE id_tipe_pasien=$id_tipepasien";
$result2 = @mysql_query($query2);	
$tipe_pasien = @mysql_result($result2,0,"tipe_pasien");
######## /Filter #########

$query11="SELECT rm_tindakan_ruang_medis.dokter_operator, rm_tindakan_ruang_medis.dokter_anastesi, 
rm_tindakan.tindakan, rm_pasien_keluar.id_pasien, rm_pasien.nama_pasien, rm_pasien.alamat, 
COUNT(rm_tindakan_ruang_medis.id_tindakan_ruang_medis) AS jml, 
SUM(rm_tindakan_ruang_medis.tarif) AS tarif, rm_pendaftaran.id_asal_pendaftaran, 
rm_pasien.id_tipe_pasien, rm_ruang.id_tipe_ruang, rm_pasien_keluar.tgl_keluar,
rm_pendaftaran.id_ruang_asal

FROM rm_pendaftaran, rm_pasien_keluar, rm_tindakan_ruang_medis, rm_pasien, rm_ruang, rm_detail_tindakan, rm_tindakan 
WHERE rm_pendaftaran.id_pendaftaran = rm_pasien_keluar.id_pendaftaran AND 
rm_pasien_keluar.id_pasien = rm_pasien.id_pasien AND 
rm_pasien_keluar.id_pendaftaran = rm_tindakan_ruang_medis.id_pendaftaran AND 
rm_pendaftaran.id_ruang_asal = rm_ruang.id_ruang AND 
rm_detail_tindakan.id_detail_tindakan = rm_tindakan_ruang_medis.id_tindakan_medis AND rm_detail_tindakan.id_tindakan = rm_tindakan.id_tindakan

/*(rm_pendaftaran.id_asal_pendaftaran IN (SELECT id_pendaftaran FROM rm_pasien_keluar 
WHERE DATE(rm_pasien_keluar.tgl_keluar) BETWEEN '$tgl_awal' and '$tgl_akhir') OR
rm_pendaftaran.id_asal_pendaftaran=0) AND */
AND DATE(rm_pasien_keluar.tgl_keluar) BETWEEN '$tgl_awal' and '$tgl_akhir' $sql_where $sql_where2
GROUP BY rm_tindakan_ruang_medis.dokter_operator, rm_pasien_keluar.id_pasien, rm_tindakan_ruang_medis.id_tindakan_medis
ORDER BY rm_tindakan_ruang_medis.dokter_operator, rm_tindakan_ruang_medis.dokter_anastesi, 
rm_tindakan_ruang_medis.id_tindakan_medis, rm_tindakan.tindakan,
rm_pasien.id_tipe_pasien";

$result = @mysql_query($query11);	
$jum_n = @mysql_num_rows($result);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}

echo "<div class='printArea'><p align='center'><strong><u>IBS REKAP RSUD Dr. SOEGIRI LAMONGAN</u></strong>
	<BR>Tgl $tgl_awal s/d $tgl_akhir
</p><hr>";


		echo "<table><tr>
			<td width='5%' align='left' bgcolor='#FFFFFF'>$tipe_pasien</td>
		</tr>
		</table>";

		echo "<table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
		<tr>
			<td width='1%'  align='center' bgcolor='#999999'><B>No.</B></td>
			<td align='center' bgcolor='#999999'><B>Operator 1</B></td>
			<td align='center' bgcolor='#999999'><B>Operator 2</B></td>
			<td align='center' bgcolor='#999999'><B>Nama Jenis</B></td>
			<td align='center' bgcolor='#999999'><B>Ruang Asal</B></td>
			<td align='center' bgcolor='#999999'><B>Nama</B></td>
			<td align='center' bgcolor='#999999'><B>Alamat</B></td>
			<td width='2%' align='center' bgcolor='#999999'><B>Jml</B></td>
			<td width='3%' align='center' bgcolor='#999999'><B>Bruto</B></td>
		</tr>";

			$no=1;
			$dokter_operator_lama='z';
			$dokter_anastesi_lama='z';
			$tot_jml = 0;
			$tot_tot = 0;
			while($row = mysql_fetch_array($result)){
				$dokter_operator =  $row['dokter_operator'];
				$dokter_anastesi =  $row['dokter_anastesi'];

				$queryK = "SELECT nama_dokter FROM rm_dokter WHERE id_dokter=$dokter_operator";
				$resultk = mysql_query($queryK);	
				$nama_doktera = mysql_result($resultk,0,"nama_dokter");	
				
				$queryK = "SELECT nama_dokter FROM rm_dokter WHERE id_dokter=$dokter_anastesi";
				$resultk = mysql_query($queryK);	
				$nama_dokterb = mysql_result($resultk,0,"nama_dokter");	
				
				echo "<tr>";

				if($dokter_operator_lama==$dokter_operator){
					$nama_doktera='';
					$x_a = 'sama';
					if($dokter_anastesi_lama==$dokter_anastesi){
						$nama_dokterb='';
						$x_b = 'sama';
					}
					echo "<td align='center' bgcolor='#FFFFFF'></td>";
				}else{
					if($dokter_anastesi_lama==$dokter_anastesi) {
						#$nama_dokterb='';
						$x_b = 'tdk';
					}
					$x_a = 'tdk';					
					echo "<td align='center' bgcolor='#FFFFFF'>$no</td>";
					$no++;
				}
				
				
				$id_tindakan_medis =  $row['id_tindakan_medis'];
				$tindakan_medis =  $row['tindakan'];
				$id_pasien =  $row['id_pasien'];
				$nama_pasien =  $row['nama_pasien'];
				$alamat =  $row['alamat'];
				
				$jml =  $row['jml'];
				$id_asal_pendaftaran =  $row['id_asal_pendaftaran'];

				$id_tipe_pasien =  $row['id_tipe_pasien'];

				$id_tipe_ruang =  $row['id_tipe_ruang'];
				$tgl_keluar =  $row['tgl_keluar'];
				$tarif =  $row['tarif'];

				$id_ruang_asal =  $row['id_ruang_asal'];
				$query3="SELECT ruang FROM rm_ruang WHERE id_ruang=$id_ruang_asal";
				$result3 = mysql_query($query3);	
				$ruangx = mysql_result($result3,0,"ruang");	
				
					echo "<td align='left' bgcolor='#FFFFFF'>&nbsp;$nama_doktera </td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;$nama_dokterb</td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;$tindakan_medis</td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;$ruangx</td>
					<td align='left' bgcolor='#FFFFFF'>&nbsp;$nama_pasien</td>
					<td align='left' bgcolor='#FFFFFF'>$alamat</td>
					<td align='center' bgcolor='#FFFFFF'>$jml</td>
					<td align='right' bgcolor='#FFFFFF'>$tarif&nbsp;&nbsp;</td>
				</tr>";
				

				$dokter_operator_lama = $dokter_operator;
				$dokter_anastesi_lama =  $dokter_anastesi;

				$tot_jml += $jml;
				$tot_tot += $tarif;
			}

			echo "<tr>
					<td align='left' bgcolor='#FFFFFF' colspan='9'><br></td>
				</tr>
				<tr>
					<td align='right' bgcolor='#FFFFFF' colspan='7'>Grand Total&nbsp;</td>
					<td align='center' bgcolor='#FFFFFF'>$tot_jml</td>
					<td align='right' bgcolor='#FFFFFF'>$tot_tot&nbsp;&nbsp;</td>
				</tr>";
			echo "</table>";

#}//end post
?>