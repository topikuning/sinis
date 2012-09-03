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
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinClosePemeriksaan()" plain="true">Input Waktu Selesai</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="lihatHasil()" plain="true">Lihat Hasil</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakLaboratorium()" plain="true">Cetak Hasil Pemeriksaan</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="openCetakHasil()" plain="true">Cetak Hasil Lain</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPemeriksaan" width='100%'></table>
        <div id="panelHasil" class="easyui-panel" title="Interpretasi Hasil" collapsible="true" width='100%' style="height:150px;padding:2px;background:#fafafa;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="saveInterHasil()" plain="true">Simpan</a>
            <form id="frmInterHasil">
                <table width='100%' class="data">
                    <tr>
                        <td width='25%' valign='top'>Keterangan</td>
                        <td width="75%" valign='top'>
                            <textarea cols="80" rows="3" name='interHasil' id='interHasil'></textarea>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="panelHapusanDarah" class="easyui-panel" title="Hapusan Darah" collapsible="true" width='100%' style="height:150px;padding:2px;background:#fafafa;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="saveHapusanDarah()" plain="true">Simpan</a>
            <form id="frmHapusanDarah">
                <table width='100%' class="data">
                    <tr>
                        <td width='10%' valign='top'>Eritrosit</td>
                        <td width="25%" valign='top'>
                            <input type="text" size="15" name='eritrosit' id='eritrosit' onkeydown="
                                if (event.keyCode == 13){
                                    saveHapusanDarah();
                                }
                                   " >
                        </td>
                        <td width='10%' valign='top'>Leukosit</td>
                        <td width="25%" valign='top'>
                            <input type="text" size="15" name='leukosit' id='leukosit' onkeydown="
                                if (event.keyCode == 13){
                                    saveHapusanDarah();
                                }
                                   ">
                        </td>
                        <td width='10%' valign='top'>Trombosit</td>
                        <td width="25%" valign='top'>
                            <input type="text" size="15" name='trombosit' id='trombosit' onkeydown="
                                if (event.keyCode == 13){
                                    saveHapusanDarah();
                                }
                                   ">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<div id="winCetakHasil" class="easyui-window" title="Form Cetak Hasil Lab" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:200px;height:100px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:10px;">
        <form name='frmCetak' id='frmCetak'>
            <table class="data" width="100%">
                <tr>
                    <td>No Pemeriksaan</td>
                    <td><input type="text" id="nom" name="nom" value="" size="5" onkeyup="IsNumeric(nom)" onkeydown="
                        if(event.keyCode == 13){
                            cetakHasil();
                            return false;}"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winCetakHasil').window('close')" plain="true">Close</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div id="winClosePemeriksaan" class="easyui-window" title="Form Close Pemeriksaan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:220px;background: #fafafa;">
    <form name="frmClosePemeriksaan" action="" id="frmClosePemeriksaan">
        <table class="data" width='100%'>
            <tr height='25'>
                <td>
                    <label>Nomor Pemeriksaan:</label>
                </td>
                <td>
                    <input name="noPeriksa_edit" id="noPeriksa_edit" size="10" disabled>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Ambil:</label>
                </td>
                <td>
                    <input name="ambilSampel_edit" id="ambilSampel_edit" class="easyui-datetimebox" disabled>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Periksa:</label>
                </td>
                <td>
                    <input name="periksaSampel_edit" id="periksaSampel_edit" class="easyui-datetimebox" value="<? echo date("Y-m-d H:i:s"); ?>">
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Selesai:</label>
                </td>
                <td>
                    <input name="selesaiSampel_edit" id="selesaiSampel_edit" class="easyui-datetimebox" value="<? echo date("Y-m-d H:i:s"); ?>">
                </td>
            </tr>
        </table>
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanClosePemeriksaan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinClosePemeriksaan()" plain="true">Close</a>
    </div>
</div>