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
                    <td width='30%'><input id="tgl_awal" class="easyui-datebox" name="tgl_awal" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loaddataDiagnosa();
                    }
                " ></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Tanggal Akhir</td>
                    <td width='30%'><input id="tgl_akhir" name="tgl_akhir" type="text" value="" class="easyui-datebox"></input></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Jenis Pasien</td>
                    <td width='30%'>
                        <select name="tipe_pasien" id="tipe_pasien">
                        <option value=''>[Pilih Tipe Pasien]</option>
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
                </tr>
                <tr height="25">
                    <td width='19%'>Ruang</td>
                    <td width='30%'>
                        <select name="ruang" id="ruang">
                        <option value=''>[Pilih Ruang]</option>
                        <?
                            $query  = "SELECT * FROM rm_ruang WHERE del_flag<>'1' and id_tipe_ruang not in ('10', '11', '12')";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_ruang]\" >$dt[ruang]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='2%'>&nbsp;</td>
                    <td width='2%'>&nbsp;</td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadJasa()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataJasa" width='100%'></table>
    </div>
</div>
