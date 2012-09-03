<?php
ini_set("date.timezone", "Asia/Jakarta");
require_once '../../common/function.php';
$fungsi = new fungsi();
$id_faktur_penjualan = $_GET['id_faktur_penjualan'];
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
            $q_diagnosa = "SELECT id_pembayaran_obat, pembayaran_ke, id_faktur_penjualan, DATE(tgl_pembayaran) AS tgl_bayar, 
                               TIME(tgl_pembayaran) AS wkt_bayar, bayar 
                               FROM rm_pembayaran_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td width='5%' align='center'><a href='javascript:void(0)' onclick='cetakBayarObat(" . $rec['id_pembayaran_obat'] . ")'><img src='themes/icons/print.png' border='0'></a></td>
                                <td width='5%' align='center'>$i</td>
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
    function cetakBayarObat(idBayar){
        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: "task=cetakBayarObat&id_pembayaran_obat=" + idBayar,  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/bayarObat.html','cetakPembayaranObat','height=600,width=1000,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });        
    }
</script>