<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title></title>
<script type="text/javascript" src="date_js/calendarDateInput-Indo.js"></script>
<script type="text/javascript" src="jquery-1.2.3.min.js"></script>
<style type="text/css">
body, table, input, select, textarea { font: 12px/20px Verdana, sans-serif; }
h4 { font-size: 18px; }
</style>
<script type="text/javascript">
$(document).ready(function() {
	function ajaxLoading(){
		$().ajaxStart(function() {
			$('#loading').show();
			$('#result').hide();
		}).ajaxStop(function() {
			$('#loading').hide();
			$('#result').fadeIn('slow');
		});
	}

	$('#myForm').submit(function() {
		ajaxLoading();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				$('#result').html(data);
			}
		})
		return false;
	});
})
</script>
</head><body>
<TABLE border='0' cellpadding='3' cellspacing='3'  width='600' >
<TR>
	<TD>
	
	<form id="myForm" name="myForm" method="post" action="act_pasien.php">
	<table align="left" style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1'>
	<tr>
		<td width='1%'  align='center' bgcolor='#00CC00' colspan='4'><B>Search Pasien</B></td>
	</tr>
<!-- 	<tr bgcolor='#D1FDCC'>
		<td  width='200'>
			<label>Tipe Pasien:</label>
		</td>
		<td colspan='3'>
			<select id="tipe_pasien" name="tipe_pasien">
				<option value="">[Pilih Tipe Pasien]</option>
			<?
			$query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag='' order by tipe_pasien";

			$result = mysql_query($query);
			while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<option value=\"$dt[id_tipe_pasien]\">$dt[tipe_pasien]</option>";
			}
			?>
			</select>
		</td>
	</tr> -->
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Nama:</label>
		</td>
		<td width='10' colspan='3'>
			<INPUT TYPE="text" NAME="nama_pasien">
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Tanggal Lahir:</label>
		</td>
		<td width='10'>
			<script>DateInput('tgla', true, 'YYYY-MM-DD')</script> 
		</td>
		<td>antara</td>
		<td>
			<script>DateInput('tglb', true, 'YYYY-MM-DD')</script>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Kota:</label>
		</td>
		<td width='10' colspan='3'>
			<INPUT TYPE="text" NAME="kota" disabled>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Alamat:</label>
		</td>
		<td width='10' colspan='3'>
			<INPUT TYPE="text" NAME="alamat">
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Kelurahan:</label>
		</td>
		<td width='10' colspan='3'>
			<INPUT TYPE="text" NAME="kelurahan" disabled>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Kecamatan:</label>
		</td>
		<td width='10' colspan='3'>
			<INPUT TYPE="text" NAME="kecamatan" disabled>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td></td>
		<td colspan='3'>
			<input type="submit" value="Cari" name="cari_lap"/>
			<input type="reset" value="Reset" />
		</td>
	</tr>
	<tr>
		<td width='1%'  align='center' bgcolor='#00CC00' colspan='4'></td>
	</tr>
	</table>
	</form>

	</TD>
</TR>
</TABLE>


<div id="loading" style="display:none;"><img src="laporan/loading.gif" alt="loading..." /></div>
<div id="result" style="display:none;"></div>