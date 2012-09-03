<?php
ini_set("date.timezone", "Asia/Jakarta");
require_once '../../common/function.php';
$fungsi = new fungsi();
$id_pasien = $_GET['id_pasien'];
$status_pembayaran = $_GET['status_pembayaran'];
?>
<div class='printArea'>
    <p><b>Detail Pembayaran</b></p>
    <table style=' font-family: verdana; font-size: 10px;' bgcolor="#0D8E13" width='70%' border='0' cellspacing='1' cellpadding='0'>
        <thead>
            <tr height='20' bgcolor="#54AF54" align='center' style="font-weight: bold;">
                <td width='5%'>x</td>
                <td width='5%'>No</td>
                <td width='5%'>Ke</td>
                <td width='30%'>Tanggal Pembayaran</td>
                <td width='60%'>Jumlah</td>
            </tr>
        </thead>
        <tbody>
            <?
            $q_diagnosa = "SELECT id_pembayaran_tagihan, pembayaran_ke, id_pendaftaran, DATE(tgl_pembayaran) AS tgl_bayar, 
                               TIME(tgl_pembayaran) AS wkt_bayar, bayar 
                               FROM rm_pembayaran_tagihan WHERE id_pasien='" . $id_pasien . "' and del_flag<>1";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    if ($status_pembayaran == 'Lunas' && $i == $jmlData)
                        $link = "<a href='javascript:void(0)' onclick='cetakLunas(" . $id_pasien . "," . $rec['id_pembayaran_tagihan'] . ")'><img src='themes/icons/print.png' border='0'></a>";
                    else
                        $link = "<a href='javascript:void(0)' onclick='cetakKwitansi(" . $rec['id_pembayaran_tagihan'] . ")'><img src='themes/icons/print.png' border='0'></a>";
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td width='5%' align='center'>" . $link . "</td>
                                <td width='5%' align='center'>" . $i . "</td>
                                <td width='5%' align='center'>" . $rec['pembayaran_ke'] . "</td>
                                <td width='30%'>" . $fungsi->codeDate($rec['tgl_bayar']) . " " . $rec['wkt_bayar'] . "</td>
                                <td width='60%' align='right'>" . number_format($rec['bayar'], 2, ',', '.') . "</td>
                              </tr>";
                    $i++;
                };
            }
            if ($i == 1) {
                echo "<tr height='20' bgcolor='#99FF99'>
                            <td width='5%' colspan='5'>Data Kosong</td>
                          </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    function cetakLunas(id_pasien, id_pembayaran){
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=cetakKwitansiLunas&id_pasien=" + id_pasien + "&id_pembayaran=" + id_pembayaran,  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/cetakKwitansiLunas.html','cetakKwitansi','height=400,width=300,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });
    }
    function cetakKwitansi(id_pembayaran){
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=cetakKwitansiTagihan&id_pembayaran=" + id_pembayaran,  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/cetakKwitansiTagihan.html','cetakKwitansi','height=400,width=300,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });
    }
</script>