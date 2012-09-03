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
                <td width='19%'>Nomor RM</td>
                <td width='30%'><input id="no_pendaftaran" name="no_pendaftaran" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " onkeyup='IsNumeric(no_pendaftaran)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Pendaftaran</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Pasien</td>
                <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
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
        <table id="dataPendaftaran" width='100%'></table>
    </div>
</div>
<div id="winClosePerawatan" class="easyui-window" title="Check Out Pasien" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:200px;background: #fafafa;">
    <form id="frmClosePerawatan">
        <table class="data" width="100%">
            <tr height="25">
                <td>
                    <label>Kondisi</label>
                </td>
                <td>
                    <select id="kondisiKeluar" name="kondisiKeluar">
                        <option value=''>[Pilih Kondisi]</option>
<?
$query = "SELECT * FROM rm_keadaan WHERE del_flag<>'1'";

$result = $fungsi->runQuery($query);
while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<option value=\"$dt[id_keadaan]\" >$dt[keadaan]</option>";
}
?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td>
                    <label>Cara Keluar</label>
                </td>
                <td>
                    <select id="caraKeluar" name="caraKeluar">
                        <option value=''>[Pilih Cara Keluar]</option>
<?
$query = "SELECT * FROM rm_cara_keluar WHERE del_flag<>'1'";

$result = $fungsi->runQuery($query);
while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<option value=\"$dt[id_cara_keluar]\" >$dt[cara_keluar]</option>";
}
?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td>
                    <label>Keterangan</label>
                </td>
                <td>
                    <textarea id="keteranganKeluar" name="keteranganKeluar" cols="25" rows="2"></textarea>
                </td>
            </tr>
        </table>
        <input id="no_rm_pasien" name="no_rm_pasien" cols="25" rows="2" type="hidden">
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanClosePerawatan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winClosePerawatan').window('close');" plain="true">Close</a>
    </div>
</div>
