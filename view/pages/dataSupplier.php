<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:50px;">
        <table class='data' width="100%">
            <tr height="25">
                <td width='19%'>Nama Supplier</td>
                <td width='30%'><input id="nama_supplier" name="nama_supplier" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataSupplier();
                    }
                   " />
                <a class="easyui-linkbutton" id="cari" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataSupplier()" plain="true">Cari</a></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>&nbsp;</td>
                <td width='30%'>&nbsp;</td>
            </tr>
        </table>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataSupplier" width='100%'></table>
    </div>
</div>
<div id="winTambahSupplier" class="easyui-window" title="Form Tambah Data Supplier" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:380px;height:120px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:70px;">
        <form name='frmTambahSupplier' id='frmTambahSupplier'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Nama Supplier</td>
                    <td width='60%'>
                        <input id="supplier" name="supplier" type="text" value="" size="30"/>
                        <input id="id_supplier" name="id_supplier" type="text" value="" size="1" hidden />
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanSupplier()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinTambahSupplier()" plain="true">Close</a>
    </div>
</div>
