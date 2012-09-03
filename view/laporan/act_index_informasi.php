<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");


########## Filter Pencarian #################
$tgl_awal = $_POST["tgla"];
$tgl_akhir = $_POST["tglb"]; 
$nama_pasien = $_POST["nama_pasien"]; 
$alamat = $_POST["alamat"];

########## /Filter Pencarian #################
$query="SELECT
  rm_pendaftaran.id_pendaftaran,
  rm_pendaftaran.id_ruang,
  rm_pasien.nama_pasien,
  date_format(rm_pendaftaran.tgl_pendaftaran,'%d-%m-%Y') as tgl_pendaftaran,
  rm_pasien.id_kecamatan,
  rm_pasien.id_kelurahan,
  rm_pasien.id_kelamin,
  rm_pasien.alamat,
  rm_pasien_keluar.id_keadaan,
  rm_pasien_keluar.keterangan,
  rm_pasien_keluar.tgl_keluar,
  rm_pasien_keluar.id_cara_keluar,
  rm_pendaftaran.id_tipe_pendaftaran
FROM
  rm_pasien,
  rm_pendaftaran,
  rm_pasien_keluar
WHERE
  rm_pasien.id_pasien = rm_pendaftaran.id_pasien AND
  rm_pendaftaran.id_pendaftaran = rm_pasien_keluar.id_pendaftaran AND
  date(rm_pendaftaran.tgl_pendaftaran) BETWEEN '$tgl_awal' AND '$tgl_akhir' ";

  if($nama_pasien!='') {
			$query .= " AND rm_pasien.nama_pasien LIKE '%$nama_pasien%' ";
  }
  
  if($alamat!='') {
			$query .= " AND rm_pasien.alamat LIKE '%$alamat%' ";
  }
	
  if($nama_pasien!='' || $alamat!='')
		$query .= " ORDER BY rm_pendaftaran.id_pendaftaran DESC";
  
  #date(rm_pasien_keluar.tgl_keluar) BETWEEN '$tgl_awal' AND '$tgl_akhir' AND
  #rm_ruang.id_tipe_ruang = $cari_tipe_ruan";

?>
<strong><u>INFORMASI PASIEN</u>
<hr width=50%' align='left'>
<?

echo "<table style='font-family: verdana; font-size: 11px;' width=50%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>";
echo "<tr>
		<td align='center' bgcolor='#999999'>No.</td>
		<td align='center' bgcolor='#999999'>Ruang</td>
		<td align='center' bgcolor='#999999'>Nama Pasien</td>
		<td align='center' bgcolor='#999999'>Gender</td>	
		<td align='center' bgcolor='#999999'>Alamat</td>
		<td align='center' bgcolor='#999999'>Status</td>
	</tr>";

$result = mysql_query($query);	
$jum_n = @mysql_num_rows($result);

			################## Data Pasien ada di Tabel Pasien Keluar ########################
			
			$nom = 1;
			while($row = mysql_fetch_array($result)){	
				$id_ruang =  $row['id_ruang'];
				$nama_pasien1 =  $row['nama_pasien'];
				$tgl_pendaftaran =  $row['tgl_pendaftaran'];
				$id_kelamin =  $row['id_kelamin'];
				$alamatx =  $row['alamat'];
				$id_keadaan =  $row['id_keadaan'];
				$keterangan =  $row['keterangan'];
				$tgl_keluar =  $row['tgl_keluar'];
				$id_cara_keluar =  $row['id_cara_keluar'];
				$id_tipe_pendaftaran =  $row['id_tipe_pendaftaran'];

				$query3="SELECT ruang FROM rm_ruang WHERE id_ruang=$id_ruang";
				$result3 = mysql_query($query3);	
				$ruangx = mysql_result($result3,0,"ruang");	

				if($id_kelamin==1) $sex = 'L';
				else $sex = 'P';

				echo "<tr bgcolor='#FFFFFF'>
						<td align='center' >$nom</td>
						<td>$ruangx</td>
						<td>$nama_pasien1</td>
						<td align='center'>$sex</td>
						<td>$alamatx</td>
						<td align='center'><img src='out.png' widht='25' height='25' title='Sudah keluar'></td>
				</tr>";
				$nom++;
			}

			########################### Dalam Perawatan #################################
			$sqlx = "SELECT
					  rm_pasien.id_pasien,
					  rm_pasien.nama_pasien,
					  rm_pasien.alamat,
					  rm_pasien.id_kelamin,
					  rm_penggunaan_kamar.id_ruang,
					  rm_penggunaan_kamar.tgl_masuk,
					  rm_penggunaan_kamar.tgl_keluar,
					  rm_penggunaan_kamar.keterangan_selesai,
					  rm_penggunaan_kamar.id_ruang_asal,
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
					  rm_detail_kamar.id_kamar = rm_kamar.id_kamar ";
			
			if($nama_pasien!='') {
				$sqlx .= " AND rm_pasien.nama_pasien LIKE '%$nama_pasien%' ";
			}

			if($alamat!='') {
				$sqlx .= " AND rm_pasien.alamat LIKE '%$alamat%' ";
			}

			if($nama_pasien!='' || $alamat!='')
				$sqlx .= " ORDER BY rm_penggunaan_kamar.id_penggunaan_kamar DESC";
			#echo $sqlx;
			$hasil = @mysql_query($sqlx);	
			
			#$nom=1;
			$list_id_pasien='';
			$p1=1;
			while($rowd = @mysql_fetch_array($hasil)){	
				$nama_pasienx =  $rowd['nama_pasien'];
				$alamatx =  $rowd['alamat'];
				$id_kelamin =  $rowd['id_kelamin'];
				$id_ruang =  $rowd['id_ruang'];
				$bed =  $rowd['bed'];
				$kamar =  $rowd['kamar'];				

				if($p1==1) $koma = "";
				else $koma = ",";
				$list_id_pasien .=  $koma." ".$rowd['id_pasien'];

				$query3="SELECT ruang FROM rm_ruang WHERE id_ruang=$id_ruang";
				$result3 = mysql_query($query3);	
				$ruangx = mysql_result($result3,0,"ruang");	

				if($id_kelamin==1) $sex = 'L';
				else $sex = 'P';

				echo "<tr bgcolor='#FFFFFF'>
						<td align='center' >$nom</td>
						<td>$kamar - $bed</td>
						<td>$nama_pasienx</td>
						<td align='center'>$sex</td>
						<td>$alamatx</td>
						<td align='center'><img src='care.jpeg' widht='25' height='25' title='Perawatan'></td>
				</tr>";
				$nom++;
			}

			$sqlx2 = "SELECT
						  rm_pendaftaran.id_pendaftaran,
						  rm_pendaftaran.id_ruang,
						  rm_pendaftaran.id_pasien,
						  rm_pasien.nama_pasien,
						  rm_pasien.alamat,
						  rm_pasien.id_kelamin
						FROM
						  rm_pendaftaran,
						  rm_pasien
						WHERE
						  rm_pendaftaran.id_pasien = rm_pasien.id_pasien";

			if($list_id_pasien!='') 
				$sqlx2 .= " AND rm_pendaftaran.id_pasien NOT IN ($list_id_pasien) ";
			if($nama_pasien!='') {
				$sqlx2 .= " AND rm_pasien.nama_pasien LIKE '%$nama_pasien%' ";
			}

			if($alamat!='') {
				$sqlx2 .= " AND rm_pasien.alamat LIKE '%$alamat%' ";
			}
			#echo $sqlx2;
			$hasil3 = @mysql_query($sqlx2);	
			while($rowd3 = @mysql_fetch_array($hasil3)){	
				$nama_pasien =  $rowd3['nama_pasien'];
				$alamat =  $rowd3['alamat'];
				$id_kelamin =  $rowd3['id_kelamin'];
				$id_ruang =  $rowd3['id_ruang'];

				$query3="SELECT ruang FROM rm_ruang WHERE id_ruang=$id_ruang";
				$result3 = mysql_query($query3);	
				$ruangx = mysql_result($result3,0,"ruang");	

				if($id_kelamin==1) $sex = 'L';
				else $sex = 'P';

				echo "<tr bgcolor='#FFFFFF'>
						<td align='center' >$nom</td>
						<td>$ruangx</td>
						<td>$nama_pasien</td>
						<td align='center'>$sex</td>
						<td>$alamat</td>
						<td align='center'><img src='care.jpeg' widht='25' height='25' title='Perawatan'></td>
				</tr>";
				$nom++;
			}

			echo "</table>
			<BR><BR><BR>";

?>
