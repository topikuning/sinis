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
		var thn = $('#tahun').val();
		var bln = $('#bulan').val();

		$.ajax({
			type: 'POST',
			url: "act_pasien_dasar_kelas_perawatan_bulan.php",
			data: $(this).serialize(),
			success: function(data) {
				//$('#result').html(data);
				var win = window.open('act_pasien_dasar_kelas_perawatan_bulan.php?cetak=print&thn='+thn+'&bln='+bln,'CetakDong','height=500,width=1200,resizable=1,scrollbars=0, menubar=0,scrolling=yes');
			}
		})
	});
})
</script>
</head><body>

<TABLE border='0' cellpadding='3' cellspacing='3'  width='600' >
<TR>
	<TD>
	
	<form id="myForm" name="myForm" method="post" action="act_pasien_dasar_kelas_perawatan_bulan.php">
	<table align="left" style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1'>
	<tr>
		<td width='1%'  align='center' bgcolor='#00CC00' colspan='4'><B>Kelas Perawatan Bulanan</B></td>
	</tr>	
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Tahun:</label>
		</td>
		<td colspan='3'>
			<select id="tahun" name="tahun">
				<option value="0">[Pilih Tahun]</option>
				<?
				for($thn=2011;$thn<=date("Y");$thn++){
				?>
				<option value="<?=$thn;?>"><?=$thn;?></option>
				<?
				}
				?>
			</select>
		</td>
	</tr>
	<tr bgcolor='#D1FDCC'>
		<td>
			<label>Bulan:</label>
		</td>
		<td colspan='3'>
			<select id="bulan" name="bulan">
				<option value="0">[Pilih Bulan]</option>
				<?
				$nmabulan = array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DESEMBER");
				for($i=1;$i<=12;$i++){
				?>
				<option value="<?=$i?>"><?=$nmabulan[$i-1]?></option>
				<?
				}
				?>
			</select>
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