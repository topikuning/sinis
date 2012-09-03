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
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinPemeriksaan()" plain="true">Pemeriksaan Kelompok</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinPemeriksaanPlus()" plain="true">Pemeriksaan Tunggal</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinClosePemeriksaan()" plain="true">Input Waktu Selesai</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="lihatHasil()" plain="true">Lihat Hasil</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakLaboratorium()" plain="true">Cetak Hasil Pemeriksaan</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="openCetakHasil()" plain="true">Cetak Hasil Lain</a>
        <!--        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinBahan()" plain="true">Entry Bahan</a>-->
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Pasien Selesai</a>
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
        <table id="dataBahan" width='100%'></table>
    </div>
</div>
<div id="winPemeriksaan" class="easyui-window" title="Form Pemeriksaan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:800px;height:300px;background: #fafafa;">
    <form name="pemeriksaanLab" action="" id="pemeriksaanLab">
        <table class="data" width='100%'>
            <tr height='25'>
                <td>
                    <label>Nomor Pemeriksaan:</label>
                </td>
                <td colspan="100%">
                    <input name="noPeriksa" id="noPeriksa" onkeyup='IsNumeric(noPeriksa)'>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Ambil:</label>
                </td>
                <td>
                    <input name="ambilSampel" id="ambilSampel" class="easyui-datetimebox">
                </td>
                <td>
                    <label>Periksa:</label>
                </td>
                <td>
                    <input name="periksaSampel" id="periksaSampel" class="easyui-datetimebox">
                </td>
                <td>
                    <label>Selesai:</label>
                </td>
                <td>
                    <input name="selesaiSampel" id="selesaiSampel" class="easyui-datetimebox">
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Kelompok Pemeriksaan:</label>
                </td>
                <td>
                    <div class="ausu-suggest">
                        <input type="text" id="kelompokPeriksa" name="kelompokPeriksa"/>
                        <input type="hidden" id="kelompokPeriksaId" name="kelompokPeriksaId"/>
                    </div>
                </td>
                <td colspan='4'>
                    <input type="checkbox" id="cito" name="cito" value="1"/>
                    <label>CITO</label>
                </td>
            </tr>
        </table>
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanPemeriksaan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPemeriksaan()" plain="true">Close</a>
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
<div id="winPemeriksaanPlus" class="easyui-window" title="Form Pemeriksaan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:500px;height:300px;background: #fafafa;">
    <form name="pemeriksaanLabPlus" action="" id="pemeriksaanLabPlus">
        <table class="data" width='100%'>
            <tr height='25'>
                <td>
                    <label>Kelompok Pemeriksaan:</label>
                </td>
                <td>
                    <select name='kelompokPlus' id='kelompokPlus' >
                        <option value="">[Pilih Kelompok Lab]</option>
                        <?
                        $query = "SELECT * FROM rm_kelompok_lab WHERE del_flag<>'1' order by kelompok_lab";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_kelompok_lab]\">$dt[kelompok_lab]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr height='25'>
                <td>
                    <label>Jenis Pemeriksaan:</label>
                </td>
                <td colspan="100%">
                    <select name='periksa' id='periksa' >
                        <option value="">[Pilih Pemeriksaan]</option>
                    </select>
                </td>
            </tr>
            <tr height='25'>
                <td colspan='2'>
                    <input type="checkbox" id="citoPlus" name="citoPlus" value="1"/>
                    <label>CITO</label>
                </td>
            </tr>
        </table>
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanPemeriksaanPlus()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPemeriksaanPlus()" plain="true">Close</a>
    </div>
</div>
<div id="winBahan" class="easyui-window" title="Form Bahan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:500px;height:220px;background: #fafafa;">
    <form id="frmBahan" name="frmFasilitas">
        <input id="id_barang_tindakan" name="id_barang_tindakan" type="hidden" value="" size="40">
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Nama Barang</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="bahan" name="bahan" size="30" onkeydown="
                            if (event.keyCode == 123){
                                saveBahan();
                                return false;
                            }"/>
                        <input type="hidden" id="bahanId" name="bahanId" size="1" />
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Jumlah</td>
                <td width='60%'>
                    <input type="text" id="jumlahBarang" name="jumlahBarang" size="5"/>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Stock</td>
                <td width='60%'>
                    <input type="text" id="stock" name="stock" size="5" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Satuan</td>
                <td width='60%'>
                    <input type="text" id="satuan" name="satuan" size="5" disabled/>
                </td>
            </tr>
<!--            <tr height="25">
                <td width='40%'>Tarif</td>
                <td width='60%'>
                    <input type="text" id="tarifBahan" name="tarifBahan" size="5" disabled/>
                </td>
            </tr>-->
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveBahan()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winBahan').window('close')" plain="true">Close</a>
        </div>
    </form>
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
<div id="winDiskon" class="easyui-window" title="Form Diskon Tagihan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:250px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name='frmDiskon' id='frmDiskon'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Total Tagihan</td>
                    <td width='60%'>
                        <input id="total" name="total" type="text" value="" size="15" disabled></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Tagihan Terbayar</td>
                    <td width='60%'>
                        <input id="terbayar" name="terbayar" type="text" value="" size="15" disabled/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Diskon</td>
                    <td width='60%'>
                        <input id="diskon_all" name="diskon_all" type="text" value="" size="15" disabled/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Kurang Bayar</td>
                    <td width='60%'>
                        <input id="kurang_bayar" name="kurang_bayar" type="text" value="" size="15" disabled />
                        <input id="kurang" name="kurang" type="hidden" value="" size="10" disabled />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Diskon</td>
                    <td width='60%'>
                        <input id="diskon" name="diskon" type="text" value="" size="15" onkeyup="IsNumeric(diskon)" />
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDiskon()" plain="true">Simpan Diskon</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winDiskon').window('close');" plain="true">Close</a>
    </div>
</div>