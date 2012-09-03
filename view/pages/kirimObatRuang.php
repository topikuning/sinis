<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:100px;">
        <table class='data' width="100%">
            <tr height="25">
                <td width='19%'>Kode Obat</td>
                <td width='30%'><input id="kode_obat" name="kode_obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " onkeyup='IsNumeric(no_pendaftaran)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Kadaluarsa</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value="<? echo date('Y-m-d'); ?>" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Obat</td>
                <td width='30%'><input id="obat" name="obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Sampai</td>
                <td width='30%'><input id="endDate" name="endDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataPendaftaran()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataObat" width='100%'></table>
        <table id="dataDistribusiObat" width='100%'></table>
    </div>
</div>
<div id="winSimpanDistObat" class="easyui-window" title="Form Simpan Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:190px;background: #fafafa;">
    <form name='frmSimpanDistObat' id='frmSimpanDistObat'>
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Nama Obat</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="nama_obat" name="nama_obat" value='' size='30' disabled></input>
                        <input type="hidden" id="id_obat" name="id_obat" value=''></input>
                        <input type="hidden" id="id_distribusi_obat" name="id_distribusi_obat" value=''></input>
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Jumlah</td>
                <td width='60%'>
                    <input type="text" id="jumlah" name="jumlah" value='' size='5' disabled></input>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Tanggal Kadaluarsa</td>
                <td width='60%'>
                    <input id="tgl_kadaluarsa" name="tgl_kadaluarsa" type="text" value="" class="easyui-datebox" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Disimpan di-</td>
                <td width='60%'>
                    <select name='penyimpanan' id='penyimpanan' >
                        <option value=''>[Pilih Penyimpanan]</option>
                        <?
                        $query = "SELECT id_penyimpanan,penyimpanan FROM rm_penyimpanan WHERE del_flag<>'1' order by penyimpanan";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_penyimpanan]\">$dt[penyimpanan]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDistObat()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDistObat()" plain="true">Close</a>
        </div>
    </form>
</div>