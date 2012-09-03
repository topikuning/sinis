<?
ob_start();
session_start();
?>
<!-- Print -->
<link href="cetakprint.css" rel="stylesheet" type="text/css"/>
<?
@require_once("../../common/function.php");
$fungsi = new fungsi();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");


if(@$_GET['cetak']!=''){
        $id_ruang_s = $_GET['ruang'];
	$waktu = $_GET['wkt'];
	$tgl_awal = $_GET['tgla'];
	$tgl_akhir = $_GET['tglb'];

	$atgl = explode("-",$tgl_awal);
	$tgla_cari = $atgl[0].'-'.$atgl[1].'-'.$atgl[2];

	$btgl = explode("-",$tgl_akhir);
	$tglb_cari = $btgl[0].'-'.$btgl[1].'-'.$btgl[2];

	$tgl_awal = $tgla_cari;
	$tgl_akhir = $tglb_cari;

	echo "<SCRIPT LANGUAGE='JavaScript'>
	print();
	</SCRIPT>";
}
else {
	######## Filter #########
	$waktu = $_POST["waktu"];
	//$ruang = $_POST["ruang"];

	$tgl_awal = $_POST["tgla"]; 
	$tgl_akhir = $_POST["tglb"]; 
	######## /Filter #########
}

echo "<TABLE border='0' style='font-family: verdana; font-size: 8px;'>";

$query="SELECT
  rm_pendaftaran.id_pendaftaran,
  rm_pasien.id_pasien,
  rm_pasien.nama_pasien,
  rm_diet.diet,
  rm_jenis_diet.jenis_diet,
  rm_detail_kamar.bed,
  rm_ruang.ruang,
  rm_kamar.kamar,
  rm_detail_diet.keterangan,
  rm_detail_diet.tgl_diet,
  rm_detail_diet.waktu_diet,
  rm_detail_diet.id_ruang,
  rm_kelas.kelas
FROM
  rm_detail_diet,
  rm_diet,
  rm_jenis_diet,
  rm_pendaftaran,
  rm_pasien,
  rm_detail_kamar,
  rm_ruang,
  rm_kelas,
  rm_kamar
WHERE
  rm_detail_diet.id_diet = rm_diet.id_diet AND
  rm_detail_diet.id_jenis_diet = rm_jenis_diet.id_jenis_diet AND
  rm_detail_diet.id_pendaftaran = rm_pendaftaran.id_pendaftaran AND
  rm_detail_diet.id_pasien = rm_pasien.id_pasien AND
  rm_detail_diet.id_detail_kamar = rm_detail_kamar.id_detail_kamar AND
  rm_detail_kamar.id_kamar = rm_kamar.id_kamar AND
  rm_detail_diet.id_ruang = rm_ruang.id_ruang AND
  rm_pendaftaran.id_kelas = rm_kelas.id_kelas AND
  rm_detail_diet.tgl_diet BETWEEN '$tgl_awal' AND '$tgl_akhir' AND
  rm_detail_diet.id_ruang = $id_ruang_s AND
  rm_detail_diet.waktu_diet = $waktu AND
  rm_detail_diet.del_flag <> '1'
ORDER BY rm_detail_diet.waktu_diet";
$result = @mysql_query($query);	
$jum_n = @mysql_num_rows($result);
if($jum_n<=0) { echo "Data Kosong"; exit; }
$kolom=1;
$x_nomor_x = 1;
while($row = @mysql_fetch_array($result)){	
	$nama_pasien =  $row['nama_pasien'];
	$id_pasien =  $row['id_pasien'];
	$diet =  $row['diet'];
	$jenis_diet =  $row['jenis_diet'];
	$bed =  $row['bed'];
	$ruang =  $row['ruang'];
	$kamar =  $row['kamar'];
	$keterangan =  $row['keterangan'];
	$tgl_diet =  $fungsi->codeDate($row['tgl_diet']);
	$waktu_diet =  $row['waktu_diet'];
	$id_ruang =  $row['id_ruang'];
	$kelas =  $row['kelas'];
	$id_pendaftaran =  $row['id_pendaftaran'];	
	if($waktu_diet==1) $waktu_diet = 'Pagi';
	else if($waktu_diet==2) $waktu_diet = 'Siang';
	else if($waktu_diet==3) $waktu_diet = 'Sore';
	
	if($kolom==1){
		echo "<TR>
			  <TD>
				  <TABLE border=0 width='350' style='font-family: verdana; font-size: 12px;'>
					<TR>
						<TD colspan='3' align='center' bgcolor='#FFFFFF'  height='3'>
							<TABLE style='font-family: verdana; font-size: 11px;' width='100%' border='1' cellpadding='0' cellspacing='0' bgcolor='#000000'>
							<TR>
								<TD colspan='3' align='middle' valign='middle'  bgcolor='#FFFFFF'><font style='font-family: verdana; font-size: 12px;'>$x_nomor_x</font></TD>
							</TR>
							</TABLE>
						</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD width='140'>ID</TD>
						<TD>:</TD>
						<TD>$id_pendaftaran</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>NO. REKAM MEDIK</TD>
						<TD>:</TD>
						<TD>$id_pasien</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>NAMA Px.</TD>
                                                <TD>:</TD>
						<TD>$nama_pasien</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>PAVILIUN</TD>
						<TD>:</TD>
						<TD>$ruang</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>KELAS</TD>
						<TD>:</TD>
						<TD>$kelas</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>RUANG</TD>
						<TD>:</TD>
						<TD>$kamar - $bed</TD>
					</TR>
				    <TR bgcolor='#FFFFFF'>
						<TD colspan='3'><HR></TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>NAMA DIET</TD>
						<TD>:</TD>
						<TD>$jenis_diet</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>KETERANGAN</TD>
						<TD>:</TD>
						<TD>$keterangan</TD>
					</TR>
				    <TR bgcolor='#FFFFFF'>
						<TD>TANGGAL</TD>
						<TD>:</TD>
						<TD>$tgl_diet</TD>
					</TR>
				    <TR bgcolor='#FFFFFF'>
						<TD>WAKTU</TD>
						<TD>:</TD>
						<TD>$waktu_diet</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD colspan='4'>
						------------------------
						<I>Potong disini</I>
						------------------------
						</TD>
					</TR>
					</TABLE>
			  </TD>";
			  $kolom++;
			  $x_nomor_x++;
			  continue;
	}
	
	#$kolom++;
	if($kolom==2){
		echo "<TD>
					<TABLE border=0 width='350' style='font-family: verdana; font-size: 12px;'>
					<TR>
						<TD colspan='3' align='center' bgcolor='#FFFFFF'  height='3'>
							<TABLE style='font-family: verdana; font-size: 11px;' width='100%' border='1' cellpadding='0' cellspacing='0' bgcolor='#000000'>
							<TR>
								<TD colspan='3' align='middle' valign='middle'  bgcolor='#FFFFFF'><font style='font-family: verdana; font-size: 12px;'>$x_nomor_x</font></TD>
							</TR>
							</TABLE>
						</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD width='140'>ID</TD>
						<TD>:</TD>
						<TD>$id_pendaftaran</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>NO. REKAM MEDIK</TD>
						<TD>:</TD>
						<TD>$id_pasien</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>NAMA Px</TD>
                                                <TD>:</TD>
						<TD>$nama_pasien</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>PAVILIUN</TD>
						<TD>:</TD>
						<TD>$ruang</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>KELAS</TD>
						<TD>:</TD>
						<TD>$kelas</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>RUANG</TD>
						<TD>:</TD>
						<TD>$kamar - $bed</TD>
					</TR>
				    <TR bgcolor='#FFFFFF'>
						<TD colspan='3'><HR></TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>NAMA DIET</TD>
						<TD>:</TD>
						<TD>$jenis_diet</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD>KETERANGAN</TD>
						<TD>:</TD>
						<TD>$keterangan</TD>
					</TR>
				    <TR bgcolor='#FFFFFF'>
						<TD>TANGGAL</TD>
						<TD>:</TD>
						<TD>$tgl_diet</TD>
					</TR>
				    <TR bgcolor='#FFFFFF'>
						<TD>WAKTU</TD>
						<TD>:</TD>
						<TD>$waktu_diet</TD>
					</TR>
					<TR bgcolor='#FFFFFF'>
						<TD colspan='4'>
						------------------------
						<I>Potong disini</I>
						------------------------
						</TD>
					</TR>
					</TABLE>
			  </TD>
			  </TR>";
		$kolom=1;
		$x_nomor_x++;
		continue;
	}
}


echo "
</TABLE>";

?>