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
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'><input id="id_pasien" name="id_pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataRekamMedis();
                        }
                    " onkeyup='IsNumeric(id_pasien)'></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'><input id="pasien" name="pasien" type="text" value=""  onkeydown="
                        if (event.keyCode == 13){
                            loadDataRekamMedis();
                        }
                    "></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'><input id="jns_kelamin" name="jns_kelamin" type="text" value="" disabled></input></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tanggal Awal</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value="">
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Tanggal Akhir</td>
                    <td width='30%'>
                        <input id="endDate" name="endDate" class="easyui-datebox" value="">
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Ruang</td>
                    <td width='30%'>
                        <input id="id_ruang" name="id_ruang" type='hidden' value="">
                        <input id="ruang" name="ruang" type="text" value="" onkeydown="
                            if (event.keyCode == 112){
                                openWinSrcRuang();
                                return false;
                            } else if (event.keyCode == 13){
                                loadDataRekamMedis();
                            }
                        ">
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataRekamMedis()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataRekamMedis" width='100%'></table>
    </div>
</div>
<div id="winSearchRuang" class="easyui-window" title="Diagnosa" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:475px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Tipe Ruang</td>
                    <td width='30%'>
                        <select name='tipe_ruang' id='tipe_ruang' onkeydown="
                            if (event.keyCode == 13){
                                loadDataListRuang();
                                return false;
                            }
                        ">
                        <option value=''>[Pilih Tipe Ruang]</option>
                        <?
                            $query  = "SELECT id_tipe_ruang, tipe_ruang FROM rm_tipe_ruang WHERE id_tipe_ruang IN(SELECT DISTINCT(id_tipe_ruang) FROM rm_ruang_pendaftaran WHERE del_flag<>'1') AND del_flag<>'1'";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {				
                                echo "<option value=\"$dt[id_tipe_ruang]\">$dt[tipe_ruang]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
            <a class="easyui-linkbutton" id="srcDiagnosaSearch" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataListRuang()" plain="true">Cari</a>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <table id="dataListRuang" width='100%'></table>
        </div>
    </div>
</div>