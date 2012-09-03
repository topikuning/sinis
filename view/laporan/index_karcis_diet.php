<?
session_start();
@require_once("../../common/koneksi.php");
$con = mysql_connect($mysql_host, $mysql_user, $mysql_passwd) or die("Cannot Connect To Database");
$db = mysql_select_db($mysql_db) or die("Database Does Not Exist");

$id_ruang_s = $_SESSION['level'];
if($id_ruang_s=='') { echo "Sesi habis"; exit; }
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
	/*
	$.ajax({  
		type: "POST",  
		url: "json/pendaftaran.php",  
		data: "task=cetakSJP&idDaftar=" + rows[0].id_pendaftaran,  
		success: function(dRet) {
			if(dRet=='1'){
				var win = window.open('report/cetakSJP.html','cetakKarcis','height=500,width=1000,resizable=1,scrollbars=0, menubar=0');
			}
		}
	});
	*/

	$('#myForm').submit(function() {
		ajaxLoading();
		var ruang = $('#ruang').val();
		var wkt = $('#waktu').val();
		var tgladay = $('#tgla_Day_ID').val();
		var tglamonth = $('#tgla_Month_ID').val();
		var tglayear = $('#tgla_Year_ID').val();
		tglamonth = parseInt(tglamonth)+1;
		if(tglamonth<10) tglamonth = '0'+tglamonth;
		if(tgladay<10) tgladay = '0'+tgladay;		
		var tgla = tglayear+"-"+tglamonth+"-"+tgladay;
		var tglbday = $('#tglb_Day_ID').val();
		var tglbmonth = $('#tglb_Month_ID').val();
		var tglbyear = $('#tglb_Year_ID').val();
		tglbmonth = parseInt(tglbmonth)+1;
		if(tglbmonth<10) tglbmonth = '0'+tglbmonth;
		if(tglbday<10) tglbday = '0'+tglbday;
		var tglb = tglbyear+"-"+tglbmonth+"-"+tglbday;	

		$.ajax({
			type: 'POST',
			url: "act_index_karcis_diet.php",
			data: $(this).serialize(),
			success: function(data) {
				//$('#result').html(data);
				var win = window.open('act_index_karcis_diet.php?cetak=print&wkt='+wkt+"&tgla="+tgla+"&tglb="+tglb+"&ruang="+ruang,'cetakKarcisDietA','height=500,width=1000,resizable=1,scrollbars=0, menubar=0');
			}
		})
		return false;
	});
})
</script>
</head><body>
<TABLE border='0' cellpadding='3' cellspacing='3'  width='600'  id="print">
<TR>
	<TD>	
	<form id="myForm" name="myForm" method="post" target="_blank">
	<table align="left" style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1'>
	<tr>
		<td width='1%'  align='center' bgcolor='#00CC00' colspan='4'><B>Search Diet Pasien</B></td>
	</tr>	
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Tanggal Diet:</label>
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
		<td  width='200'>
			<label>Ruang :</label>
		</td>
		<td colspan='3'>
			<select id="ruang" name="ruang">
				<option value="">[Ruang/Paviliun]</option>
			<?
			$query = "SELECT id_ruang,ruang FROM rm_ruang where  id_tipe_ruang=8 order by id_ruang";

			$result = mysql_query($query);
			while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<option value=\"$dt[id_ruang]\">$dt[ruang]</option>";
			}
			?>
			</select>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td  width='200'>
			<label>Waktu:</label>
		</td>
		<td colspan='3'>
			<select id="waktu" name="waktu">
				<option value="">[Waktu Diet]</option>
				<option value="1">Pagi</option>
				<option value="2">Siang</option>
				<option value="3">Sore</option>
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