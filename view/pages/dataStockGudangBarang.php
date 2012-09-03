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
                <td width='19%'>Nama Barang</td>
                <td width='30%'>
                    <div class="ausu-suggest">
                        <input type="text" id="namaBarang1" name="namaBarang1" size="30" onkeydown="
                            if (event.keyCode == 123){
                                cariStockBarangRuang();
                                return false;
                            }"/>
                        <input type="hidden" id="namaBarang1Id" name="namaBarang1Id" size="1" disabled />
                    </div>
                </td>
                <td width='19%'>Jenis Barang</td>
                <td width='30%'>
                    <select id="jenis_barang" name="jenis_barang" onkeydown="
                        if (event.keyCode == 123){
                            cariStockBarangRuang();
                            return false;
                        }">
                        <option value=''>[Pilih Jenis Barang]</option>
                        <option value='1'>Laboratorium</option>
                        <option value='2'>Lain - lain</option>
                    </select>
                </td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="cariStockBarangRuang()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataObat" width='100%'></table>
    </div>
</div>
<div id="winTambahStock" class="easyui-window" title="Form Penambahan Stock Barang" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:350px;background: #fafafa;">
    <form name='frmAddStock' id='frmAddStock'>
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Nama Barang</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="namaBarang" name="namaBarang"/>
                        <input type="hidden" id="namaBarangId" name="namaBarangId"/>
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Jumlah</td>
                <td width='60%'>
                    <input type="text" size="5" name="jmlBarang" id="jmlBarang" onkeyup='IsNumeric(jmlBarang)' onkeydown="
					if (event.keyCode == 123){
                        simpanStockBarang();
                    }"></input>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanStockBarang()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinTambahStock()" plain="true">Close</a>
        </div>
    </form>
</div>
<div id="winDistObat" class="easyui-window" title="Form Distribusi Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:150px;background: #fafafa;">
    <form name='frmDistObat' id='frmDistObat'>
        <input type="hidden" size="5" name="id_barang" id="id_barang"></input>
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
                    <input type="text" size="5" name="jmlBarangDist" id="jmlBarangDist" onkeyup='IsNumeric(jmlBarang)' onkeydown="
					if (event.keyCode == 123){
                        simpanDistBarang();
                    }"></input>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDistBarang()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDistObat()" plain="true">Close</a>
        </div>
    </form>
</div>