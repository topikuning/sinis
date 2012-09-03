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
                    <td width='19%'>Jenis Perawatan</td>
                    <td width='30%'>
                        <select id="jenis_perawatan" name="jenis_perawatan">
                            <option value='rawatJalan'>Rawat Jalan</option>
                            <option value='rawatInap'>Rawat Inap</option>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tanggal</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value="<?echo date('d-m-Y');?>"/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%'>
                        <input id="endDate" name="endDate" class="easyui-datebox" value="<?echo date('d-m-Y');?>"/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Jenis Pasien</td>
                    <td width='30%'>
                        <select name="tipe_pasien" id="tipe_pasien">
                        <option value="" >[Pilih Jenis Pasien]</option>
                        <?
                            $query  = "SELECT * FROM rm_tipe_pasien WHERE del_flag<>'1'";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_tipe_pasien]\" >$dt[tipe_pasien]</option>";					
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
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getRekapResep()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakRekapResep()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <span id="detailLaporan" width='100%' />
    </div>
</div>
