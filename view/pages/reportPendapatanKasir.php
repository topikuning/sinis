<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:140px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Tanggal Awal</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" value="<? echo date("d-m-Y"); ?>" />
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Tanggal Akhir</td>
                    <td width='30%'><input id="endDate" name="endDate" value="<? echo date("d-m-Y"); ?>" /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Jam Awal</td>
                    <td width='30%'>
                        <input id="startHour" name="startHour" value="00:00:00"/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jam Akhir</td>
                    <td width='30%'><input id="endHour" name="endHour" value="23:59:59" /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Kasir</td>
                    <td width='30%'>
                        <select id="kasir" name="kasir" onkeydown="if(event.keyCode == 13) getLaporanKasir();">
                         <option value=''>[SEMUA]</option>
                        <?
                            $query = "SELECT nama_pegawai, nip FROM rm_pegawai WHERE del_flag<>1 AND id_jabatan=14";
                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[nip]\">$dt[nama_pegawai]</option>";
                        }
                        ?>
                    </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-data" iconCls="icon-search" href="javascript:void(0)" onclick="getLaporanKasir()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <span id="detailLaporan" width='100%' />
    </div>
</div>
