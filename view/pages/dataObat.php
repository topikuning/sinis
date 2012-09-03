<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
        <table class='data' width="100%">
            <tr height="25">
                <td width='19%'>Nama Obat</td>
                <td width='30%'><input id="nama_obat" name="nama_obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataObat();
                    }
                                       " /></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Kode Obat</td>
                <td width='30%'><input id="k_obat" name="k_obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataObat();
                    }
                                       " /></td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataObat()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataObat" width='100%'></table>
    </div>
</div>
<div id="winTambahObat" class="easyui-window" title="Form Tambah Data Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:150px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:85px;">
        <form name='frmTambahObat' id='frmTambahObat'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Kode Obat</td>
                    <td width='60%'>
                        <input id="kode_obat" name="kode_obat" type="text" value="" size="5"></input>
                        <input id="id_obat" name="id_obat" type="hidden" value="" size="5"></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Nama Obat</td>
                    <td width='60%'>
                        <input id="obat" name="obat" type="text" value="" size="30" onkeydown="
                            if (event.keyCode == 13){
                                simpanObat();
                            }" />
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanObat()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinTambahObat()" plain="true">Close</a>
    </div>
</div>
