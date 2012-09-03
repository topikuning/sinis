<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:160px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Tanggal</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value=""/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%'>
                        <input id="endDate" name="endDate" class="easyui-datebox" value=""/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Dokter</td>
                    <td width='30%'>
                        <select name="dokter" id="dokter">
                        <option value=''>[Pilih Dokter]</option>
                        <?
                            $query  = "SELECT * FROM rm_dokter WHERE del_flag<>'1'";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_dokter]\" >$dt[nama_dokter]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Ruang</td>
                    <td width='30%'>
                        <select name="ruang" id="ruang">
                        <option value=''>[Pilih Ruang]</option>
                        <?
                            $query  = "SELECT * FROM rm_ruang WHERE del_flag<>'1' and id_tipe_ruang='8'";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_ruang]\" >$dt[ruang]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getLaporanPembelian()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakLaporanPembelian()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="detailLaporan" width='100%' />
    </div>
</div>
