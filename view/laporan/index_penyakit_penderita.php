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
	
	<form id="myForm" name="myForm" method="post" action="act_index_penyakit_penderita.php">
	<table align="left" style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1'>
	<tr>
		<td width='1%'  align='center' bgcolor='#00CC00' colspan='4'><B>Search Indeks Penyakit Penderita dirawat</B></td>
	</tr>
<tr bgcolor='#D1FDCC'>
		<td  width='200'>
			<label>Diagnosa:</label>
		</td>
		<td colspan='3'>
			<select id="diagnosa" name="diagnosa">
				<option value="">[Diagnosa]</option>
			<?
			$query = "SELECT id_penyakit,nama_penyakit FROM rm_penyakit order by nama_penyakit";

			$result = mysql_query($query);
			while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
				// $dt[id_penyakit] - 
			echo "<option value=\"$dt[id_penyakit]\">$dt[nama_penyakit]</option>";
			}
			?>
			</select>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Tanggal Keluar:</label>
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
			<label>Tipe Jenis:</label>
		</td>
		<td colspan='3'>
			<select id="tipe_ruang" name="tipe_ruang">
				<option value="0">[Pilih Jenis]</option>
				<option value="1">Rawat Inap</option>
				<option value="2">Rawat Jalan</option>
			</select>
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