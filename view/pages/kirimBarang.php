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
                        <input type="text" id="namaBarang" name="namaBarang" size="30" onkeydown="
                            if (event.keyCode == 123){
                                cariStockBarangRuang();
                                return false;
                            }"/>
                        <input type="hidden" id="namaBarangId" name="namaBarangId" size="1" disabled />
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
        <table id="dataDistribusiObat" width='100%'></table>
    </div>
</div>
<div id="winSimpanDistBarang" class="easyui-window" title="Form Simpan Barang" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:140px;background: #fafafa;">
    <form name='frmSimpanDistBarang' id='frmSimpanDistBarang'>
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Nama Barang</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="nama_barang" name="nama_barang" value='' size='30' disabled></input>
                        <input type="hidden" id="id_barang" name="id_barang" value=''></input>
                        <input type="hidden" id="id_distribusi_barang" name="id_distribusi_barang" value=''></input>
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Jumlah</td>
                <td width='60%'>
                    <input type="text" id="jumlah" name="jumlah" value='' size='5' disabled></input>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDistBarang()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDistBarang()" plain="true">Close</a>
        </div>
    </form>
</div>