<?php

    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=export.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title></title>
<script src='../js/jquery-1.4.4.js'></script>
<script src='../js/jquery.print.js'></script>
<script>
$(function() {
$( '.printArea' ).print();
});
</script>
</head><body><div class='printArea'><table style=' font-family: verdana; font-size: 12px; width: 18.96850412672em' cellpadding='0' cellspacing='1'><tr height='25px'><td colspan=2 align='center'>BUKTI PENDAFTARAN<br><u>RSUD Dr. SOEGIRI LAMONGAN</u><br><br></td></tr><tr height='25px'><td width='8em' style='outline: solid; outline-width: 1px; outline-color: #000000;' align='center'>9</td><td align='right'>Umum</td></tr><tr height='25px'><td width='8em'>ID</td><td> : 97</td></tr><tr height='25px'><td width='8em'>REGISTER</td><td> : 1</td></tr><tr height='25px'><td width='8em'>Tanggal</td><td> : 2011-06-14</td></tr><tr height='25px'><td width='8em'>Nama</td><td> : Pasien Loro Untu</td></tr><tr height='25px'><td width='8em'>Alamat</td><td> : Jl. Buntu</td></tr><tr height='25px'><td width='8em'>Kelurahan</td><td> : Kelurahan 1</td></tr><tr height='25px'><td width='8em'>Kecamatan</td><td> : Lamongan</td></tr><tr height='25px'><td width='8em'>Kota</td><td> : Lamongan</td></tr><tr height='25px'><td width='8em'>Jam</td><td> : 11:50:21</td></tr><tr height='25px'><td width='8em'>Umur</td><td> :  31 Th 1 Bl 10 Hr</td></tr><tr height='25px'><td width='8em'>Layanan</td><td> : Rawat Jalan</td></tr><tr height='25px'><td width='8em'>Spesialis</td><td> : URJ Anak</td></tr><tr height='25px'><td width='8em'>Dokter</td><td> : Rini</td></tr><tr height='25px'><td width='8em'>Biaya Karcis</td><td> : Rp. 15000</td></tr></table></div></body></html><script language='javascript'>setTimeout('self.close();',2000)</script>