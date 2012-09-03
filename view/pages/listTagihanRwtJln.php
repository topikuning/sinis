<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Tipe Pasien</td>
                    <td width='30%'>
                        <select id="tipe_pasien" name="tipe_pasien">
                            <option value=''>[Pilih Jenis Pasien]</option>
                            <?
                            $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien order by tipe_pasien";

                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_tipe_pasien]\">$dt[tipe_pasien]</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Status</td>
                    <td width='15%'>
                        <select id="status" name="status">
                            <option value=''>[Pilih Status]</option>
                            <option value='A'>ALL</option>
                            <option value='L'>Lunas</option>
                            <option value='B'>Belum Lunas</option>
                        </select>
                    </td>
                    <td width='15%'>
                        <select id="rawat" name="rawat">
                            <option value='0'>[Pilih Tipe Rawat]</option>
                            <option value='1'>Rawat Jalan</option>
                            <option value='2'>Konsulan</option>
                        </select>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tanggal Keluar</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>"/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%' colspan="2">
                        <input id="endDate" name="endDate" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>"/>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getLaporanTagihanRawatJalan()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja();" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <span id="detailLaporan" width='100%'></span>
    </div>
</div>
