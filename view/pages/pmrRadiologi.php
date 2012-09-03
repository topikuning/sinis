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
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'>
                        <input id="id_pasien" name="id_pasien" type="text" value="" onkeydown="
                            if (event.keyCode == 13){
                                loaddataDiagnosa();
                            } else if (event.keyCode == 119) {
                                openWinPemeriksaan();
                            }
                               " onkeyup='IsNumeric(id_pasien)'></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'>
                        <input id="pasien" name="pasien" type="text" value="" disabled></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'><input id="jns_kelamin" name="jns_kelamin" type="text" value="" disabled></input></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Usia</td>
                    <td width='30%'><input id="usia" name="usia" type="text" value="" disabled></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Pasien</td>
                    <td width='30%'><input id="jns_pasien" name="jns_pasien" type="text" value="" disabled></input></td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataDiagnosa()" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinPemeriksaan()" plain="true">Entry Pemeriksaan</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="goToTindakan()" plain="true">Entry Bahan</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Close Pemeriksaan</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPemeriksaan" width='100%'></table>
        <table id="dataBahan" width='100%'></table>
    </div>
</div>
<div id="winPemeriksaan" class="easyui-window" title="Form Pemeriksaan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:500px;height:385px;background: #fafafa;">
    <form name="pemeriksaanRadiologi" action="" id="pemeriksaanRadiologi">
        <table class='data' width="100%">
            <tr height='25'>
                <td>
                    <label>Pemeriksaan:</label>
                </td>
                <td>
                    <div class="ausu-suggest">
                        <input type="text" size="40" value="" id="radiologiField" name="radiologiField" autocomplete="off" />
                        <input type="hidden" size="2" value="" id="radiologiFieldId" name="radiologiFieldId" autocomplete="off" Disabled/>
                    </div>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Tarif:</label>
                </td>
                <td>
                    <input name="tarif" id="tarif" readonly>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Ukuran Film:</label>
                </td>
                <td>
                    <label>Jumlah:</label>
                </td>
            </tr>
            <tr height='25'>
                <td>
<!--                    <input type="checkbox" value="1" name="ukuranA" id="ukuranA">-->
                    <label for="ukuranA">30x40</label>
                </td>
                <td>
                    <input name="jumlahA" id="jumlahA" onkeyup='IsNumeric(jumlahA)'>
                </td>
            </tr>
            <tr height='25'>
                <td>
<!--                    <input type="checkbox" value="1" name="ukuranB" id="ukuranB">-->
                    <label for="ukuranA">35x35</label>
                </td>
                <td>
                    <input name="jumlahB" id="jumlahB" onkeyup='IsNumeric(jumlahB)'>
                </td>
            </tr>
            <tr height='25'>
                <td>
<!--                    <input type="checkbox" value="1" name="ukuranC" id="ukuranC">-->
                    <label for="ukuranA">24x30</label>
                </td>
                <td>
                    <input name="jumlahC" id="jumlahC" onkeyup='IsNumeric(jumlahC)'>
                </td>
            </tr>
            <tr height='25'>
                <td>
<!--                    <input type="checkbox" value="1" name="ukuranD" id="ukuranD">-->
                    <label for="ukuranD">18x24</label>
                </td>
                <td>
                    <input name="jumlahD" id="jumlahD" onkeyup='IsNumeric(jumlahD)'>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>CITO:</label>
                </td>
                <td>
                    <input id="cito" name="cito" type="checkbox" value="1"/>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>CITO Bed:</label>
                </td>
                <td>
                    <input id="citoBed" name="citoBed" type="checkbox" value="1"/>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Keterangan:</label>
                </td>
                <td>
                    <textarea id="keterangan" name="keterangan"></textarea>
                </td>
            </tr>
        </table>
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanPemeriksaan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPemeriksaan()" plain="true">Close</a>
    </div>
</div>