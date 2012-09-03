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
                <td width='30%'><input id="no_rm_pasien" name="no_rm_pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " onkeyup='IsNumeric(no_rm_pasien)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Pendaftaran</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Pasien</td>
                <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Sampai</td>
                <td width='30%'><input id="endDate" name="endDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input></td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataPendaftaran()" plain="true">Cari</a>
        <?php
        if ($_SESSION['level'] == "20") {
            echo '<a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="goToSurvey()" plain="true">Entry Survey</a>';
        }
        ?>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPendaftaran" width='100%'></table>
    </div>
</div>
<div id="winLayanan" class="easyui-window" title="Form Layanan Medis" draggable="false" resizable="true" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:930px;height:500px;background: #fafafa;" onkeydown="if(event.keyCode==27){closeWinDiet()}">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 1px;">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='10%'>Nomor RM</td>
                    <td width='20%'><input id="rm_pas" name="no_rm_pasien" type="text"></input>
                        <input id="idp" name="idp" type="hidden"  readonly disabled></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Nama Px</td>
                    <td width='20%'><input id="nm_pas" name="pasien" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='9%'>Usia Px</td>
                    <td width='20%'><input id="ag_pas" name="pasien" type="text"></input></td>
                </tr>
                <tr height="25">
                    <td width='10%'>Jenis Px</td>
                    <td width='20%'><input id="jn_pas" name="no_rm_pasien" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Kelas Px</td>
                    <td width='20%'><input id="kl_pas" name="pasien" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='9%'>JK Px</td>
                    <td width='20%'><input id="jk_pas" name="pasien" type="text"></input></td>
                </tr>
            </table>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <div style="background-color: #fcfcfc;">
                <form name="pemeriksaanRadiologi" action="" id="pemeriksaanRadiologi">
                    <table class="data" >
                        <tr style="font-size: 11px; font-weight: bold;" align="center">
                            <td><B>PEMERIKSAAN</td>
                            <td>TARIF</td>
                            <td>30x40</td>
                            <td>35x35</td>
                            <td>24x30</td>
                            <td>18x24</td>
                            <td>CITO</td>
                            <td>CITO BED</td>
                            <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanPemeriksaan()" plain="true"></a></td>
                        </tr>
                        <tr>
                            <td>
                                <div id="radiologiField"></div>
                                <input type="hidden" size="2" value="" id="radiologiFieldId" name="radiologiFieldId" autocomplete="off" Disabled/>
                            </td>
                            <td>
                                <input name="tarif" id="tarif" readonly />
                            </td>
                            <td>
                                <input style="width: 30px;" name="jumlahA" id="jumlahA" onkeyup='IsNumeric(jumlahA)' onkeydown="
                                    if(event.keyCode == 13){
                                        jumlahB.focus();
                                    }"/>
                            </td>
                            <td>
                                <input name="jumlahB" id="jumlahB" style="width: 30px;" onkeyup='IsNumeric(jumlahB)' onkeydown="
                                    if(event.keyCode == 13){
                                        jumlahC.focus();
                                    }"/>
                            </td>
                            <td>
                                <input name="jumlahC" id="jumlahC" style="width: 30px;" onkeyup='IsNumeric(jumlahC)' onkeydown="
                                    if(event.keyCode == 13){
                                        jumlahD.focus();
                                    }"/>
                            </td>
                            <td>
                                <input name="jumlahD" id="jumlahD" style="width: 30px;" onkeyup='IsNumeric(jumlahD)' onkeydown="
                                    if(event.keyCode == 13){
                                        simpanPemeriksaan();
                                    }"/>
                            </td>
                            <td>
                                <input id="cito" name="cito" type="checkbox" value="1"/>
                            </td>
                            <td>
                                <input id="citoBed" name="citoBed" type="checkbox" value="1"/>
                                <input id="keterangan" type="hidden" name="keterangan" readonly/>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <table id="dataPemeriksaan" width='100%'></table>
            <table id="dataBahan" width='100%'></table>
        </div>
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
