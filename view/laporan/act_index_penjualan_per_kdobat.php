<?
session_start();
@require_once("../../common/koneksi.php");
require_once '../../common/function.php';
$fungsi = new fungsi();

$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");


######## Filter #########
if(@$_GET['cetak']!=''){
//	$id_tipe_pasien = $_GET["idt"];
	$tgl_awal = $_GET["tgla"]; 
	$tgl_akhir = $_GET["tglb"];
	$ob1 = $_GET["ob1"];
	$ob2 = $_GET["ob2"];
	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}else{
//	$id_tipe_pasien = $_POST["tipe_pasien"];
	$tgl_awal = $_POST["tgla"]; 
	$tgl_akhir = $_POST["tglb"];
	$ob1 = $_POST["obat1"];
	$ob2 = $_POST["obat2"];
}

$kondisi = "";
$kondisi1 = "";
if($ob1!=""){
    if($ob2!="") {
        $kondisi .= " and b.id_obat BETWEEN '".$ob1."' AND '".$ob2."'";
        $kondisi1 .= " and a.id_obat BETWEEN '".$ob1."' AND '".$ob2."'";
    } else {
        $kondisi .= " and b.id_obat='".$ob1."'";
        $kondisi1 .= " and a.id_obat='".$ob1."'";
    }
}

//'0AC03' AND '1AD03' 
######## /Filter #########
$queryK2="
SELECT a.nama_pasien, a.id_faktur_penjualan, b.id_obat, DATE(a.tgl_penjualan) as tgl_penjualan, c.kode_obat, c.nama_obat, sum(b.qty) as qty, b.harga
FROM rm_faktur_penjualan a, rm_penjualan_obat b, rm_obat c
WHERE DATE(a.tgl_penjualan) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
AND b.id_faktur_penjualan=a.id_faktur_penjualan AND c.id_obat=b.id_obat and a.del_flag <> 1 and b.del_flag <> 1
 ".$kondisi." group by b.id_obat, a.id_faktur_penjualan order by c.nama_obat";

$resultk2 = mysql_query($queryK2);	
$jum_n = @mysql_num_rows($resultk2);
if($jum_n<=0) { 
	echo "Data Kosong!";
	exit;
}
?>
<div class='printArea'><p align='center'><strong><u>RSUD Dr. SOEGIRI LAMONGAN</u></strong>
	<BR>Tgl <?=$fungsi->codeDate($tgl_awal)?> s/d <?=$fungsi->codeDate($tgl_akhir)?>
</p>
<table style=' font-family: verdana; font-size: 11px;' width='80%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
<tr>
	<td width='2'  align='center' bgcolor='#999999'><B>KODE</B></td>
	<td width='190'  align='center' bgcolor='#999999'><B>NAMA BARANG</B></td>
	<td width='190'  align='center' bgcolor='#999999'><B>CUSTOMER</B></td>
	<td width='90' align='center' bgcolor='#999999'><B>TANGGAL</B></td>
	<td align='center' bgcolor='#999999'><B>QTY</B></td>
	<td align='center' bgcolor='#999999'><B>HARGA JUAL</B></td>
	<td align='center' bgcolor='#999999'><B>DISC.</B></td>
	<td align='center' bgcolor='#999999'><B>JML DISC.</B></td>
	<td align='center' bgcolor='#999999'><B>JUMLAH</B></td>
	<td align='center' bgcolor='#999999'><B>FAKTUR</B></td>
</tr>
<?
$no=1;
	while($rowk2 = mysql_fetch_array($resultk2)){
            $q_racikan = "SELECT b.id_faktur_penjualan, a.id_obat, SUM(a.qty) AS qty, a.harga
                          FROM rm_detail_racikan a, rm_racikan b
                          where a.del_flag <> 1 and b.del_flag <> 1 and a.id_racikan=b.id_racikan
                          and a.id_obat='".$rowk2['id_obat']."' and b.id_faktur_penjualan='".$rowk2['id_faktur_penjualan']."'
                          GROUP BY a.id_obat, b.id_faktur_penjualan";
            $r_racikan = $fungsi->runQuery($q_racikan);
            
		$q_retur = "select pros_retur, jumlah from rm_retur_penjualan_obat 
                            where id_faktur_penjualan='".$rowk2['id_faktur_penjualan']."' and id_obat='".$rowk2['id_obat']."'";
                $r_retur = $fungsi->runQuery($q_retur);
                if(@mysql_num_rows($r_retur)>0){
                    $disc = (@mysql_result($r_retur,0,'pros_retur')*100)."%";
                    $disc_harga = $rowk2['harga']*(1-@mysql_result($r_retur,0,'pros_retur'))*@mysql_result($r_retur,0,'jumlah');
                } else {
                    $disc = "";
                    $disc_harga = 0;
                }
                echo "<tr>
                        <td align='center' bgcolor='#FFFFFF'>".$rowk2['kode_obat']."</td>
                        <td align='left' bgcolor='#FFFFFF'>".$rowk2['nama_obat']."</td>
                        <td align='left' bgcolor='#FFFFFF'>".$rowk2['nama_pasien']."</td>
                        <td align='center' bgcolor='#FFFFFF'>".$fungsi->codeDate($rowk2['tgl_penjualan'])."</td>
                        <td align='center' bgcolor='#FFFFFF'>".number_format(($rowk2['qty'] + @mysql_num_rows($r_racikan, 0, 'qty')),0)."</td>
                        <td align='right' bgcolor='#FFFFFF'>".number_format($rowk2['harga'],0,',','.')."</td>
                        <td align='right' bgcolor='#FFFFFF'>".$disc."</td>
                        <td align='right' bgcolor='#FFFFFF'>- ".number_format($disc_harga,0,',','.')."</td>
                        <td align='right' bgcolor='#FFFFFF'>".number_format((($rowk2['harga']*$rowk2['qty'])-$disc_harga),0,',','.')."</td>
                        <td align='right' bgcolor='#FFFFFF'>".$rowk2['id_faktur_penjualan']."</td>
                </tr>";

                                $no++;	

	}
    $q_racik = "SELECT b.id_faktur_penjualan, DATE(d.tgl_penjualan) as tgl_penjualan, d.nama_pasien, a.id_obat, c.kode_obat, c.nama_obat, SUM(a.qty) AS qty, a.harga
              FROM rm_detail_racikan a, rm_racikan b, rm_obat c, rm_faktur_penjualan d
              WHERE a.del_flag <> 1 AND b.del_flag <> 1 AND a.id_racikan=b.id_racikan AND c.id_obat=a.id_obat
              AND a.id_obat NOT IN (SELECT DISTINCT(id_obat) FROM rm_penjualan_obat) ".$kondisi1." AND d.id_faktur_penjualan=b.id_faktur_penjualan
              AND DATE(d.tgl_penjualan) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
              GROUP BY a.id_obat, b.id_faktur_penjualan";
    $r_racik = $fungsi->runQuery($q_racik);
	while($rec = mysql_fetch_array($r_racik)){
            $q_retur = "select jumlah from rm_retur_penjualan_obat 
                        where id_faktur_penjualan='".$rec['id_faktur_penjualan']."' and id_obat='".$rec['id_obat']."'";
            $r_retur = $fungsi->runQuery($q_retur);
            if(@mysql_num_rows($r_retur)>0){
                $disc = "20%";
                $disc_harga = $rec['harga']*0.2*$rec['qty'];
            } else {
                $disc = "";
                $disc_harga = 0;
            }
            echo "<tr>
                    <td align='center' bgcolor='#FFFFFF'>".$rec['kode_obat']."</td>
                    <td align='left' bgcolor='#FFFFFF'>".$rec['nama_obat']."</td>
                    <td align='left' bgcolor='#FFFFFF'>".$rec['nama_pasien']."</td>
                    <td align='center' bgcolor='#FFFFFF'>".$fungsi->codeDate($rec['tgl_penjualan'])."</td>
                    <td align='center' bgcolor='#FFFFFF'>".number_format(($rec['qty'] + @mysql_num_rows($r_racikan, 0, 'qty')),0)."</td>
                    <td align='right' bgcolor='#FFFFFF'>".number_format($rec['harga'],0,',','.')."</td>
                    <td align='right' bgcolor='#FFFFFF'>".$disc."</td>
                    <td align='right' bgcolor='#FFFFFF'>- ".number_format($disc_harga,0,',','.')."</td>
                    <td align='right' bgcolor='#FFFFFF'>".number_format((($rec['harga']*$rec['qty'])-$disc_harga),0,',','.')."</td>
                    <td align='right' bgcolor='#FFFFFF'>".$rec['id_faktur_penjualan']."</td>
            </tr>";

            $no++;	

	}
	echo "</table>";

?>