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

	$('#cetkar').click(function(){
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
			url: "act_index_tindakan_operasi.php",
			data: $(this).serialize(),
			success: function(data) {
				//$('#result').html(data);
				var win = window.open('act_index_laporan_radiologi.php?cetak=print&&tgla='+tgla+"&tglb="+tglb,'CetakDong','height=500,width=1200,resizable=1,scrollbars=0, menubar=0');
			}
		})
	});
})
</script>
</head><body>
<TABLE border='0' cellpadding='3' cellspacing='3'  width='600' >
<TR>
	<TD>
	
	<form id="myForm" name="myForm" method="post" action="act_index_laporan_radiologi.php">
	<table align="left" style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1'>
	<tr>
		<td width='1%'  align='center' bgcolor='#00CC00' colspan='4'><B>Laporan Radiologi</B></td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Tgl. Keluar:</label>
		</td>
		<td width='10'>
			<script>DateInput('tgla', true, 'YYYY-MM-DD')</script> 
		</td>
		<td>s/d</td>
		<td>
			<script>DateInput('tglb', true, 'YYYY-MM-DD')</script>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td></td>
		<td colspan='3'>
			<input type="submit" value="Cari" name="cari_lap"/>
			<input type="reset" value="Reset" />
			<input type="button" value="Cetak" id="cetkar"/>
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