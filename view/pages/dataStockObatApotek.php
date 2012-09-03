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
                        loadDataObat();
                    }"></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Kadaluarsa</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value=""></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Obat</td>
                <td width='30%'><input id="obat" name="obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataObat();
                    }" ></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Sampai</td>
                <td width='30%'><input id="endDate" name="endDate" class="easyui-datebox" value=""></input></td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataObat()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataObat" width='100%'></table>
    </div>
</div>
<div id="winDistObat" class="easyui-window" title="Form Distribusi Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:150px;background: #fafafa;">
    <form name='frmDistObat' id='frmDistObat'>
        <input type="hidden" size="5" name="id_obat" id="id_obat" readonly></input>
        <input type="hidden" size="5" name="id_penyimpanan" id="id_penyimpanan" readonly></input>
        <input type="hidden" size="5" name="tgl_kadaluarsa_baru" id="tgl_kadaluarsa_baru" readonly></input>
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Ruang Tujuan</td>
                <td width='60%'>
                    <select name='ruangTujuan' id='ruangTujuan' ></select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Jumlah</td>
                <td width='60%'>
                    <input type="text" size="5" name="jmlObat" id="jmlObat" onkeydown="
                        if (event.keyCode == 13){
                            simpanDistObat();
                            return false;
                        }"></input>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDistObat()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDistObat()" plain="true">Close</a>
        </div>
    </form>
</div>