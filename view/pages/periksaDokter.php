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
                    <td width='30%'><input id="id_pasien" name="id_pasien" type="text" value="" onkeydown="
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
                    <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loaddataDiagnosa();
                    }
                " ></input></td>
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
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinPemeriksaan()" plain="true">Entry Pemeriksaan Dokter</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToDiagnosa()" plain="true">Diagnosa</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToTindakan(<?echo $_SESSION['level'];?>)" plain="true">Tindakan</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToRM()" plain="true">Rekam Medis Pasien</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Close Perawatan</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPemeriksaan" width='100%'></table>
    </div>
</div>
<div id="winPemeriksaan" class="easyui-window" title="Form Pemeriksaan Dokter" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:190px;background: #fafafa;">
    <form id="frmPemeriksaanDOkter">
        <table class='data' width="100%">
            <tr>
                <td>
                    <label>ID Pemeriksaan</label>
                </td>
                <td>
                    <input type="text" value="" name="visit" id="visit" size="5" disabled/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Dokter</label>
                </td>
                <td>
                    <select id="dokter" name="dokter">
                    <option value=''>[Pilih Dokter]</option>
                    <?
                        $query  = "SELECT * FROM rm_dokter WHERE del_flag<>'1' and id_jenis_dokter!='3'";

                        $result = $fungsi->runQuery($query);
                        while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                            echo "<option value=\"$dt[id_dokter]\" >$dt[nama_dokter]</option>";					
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Tanggal</label>
                </td>
                <td>
                    <input id="tglPemeriksaane" name="tglPemeriksaane" class="easyui-datebox" value="<?echo date('d-m-Y');?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Tarif</label>
                </td>
                <td>
                    <input id="tarifPemeriksaane" name="tarifPemeriksaane" disabled/>
                </td>
            </tr>
        </table>
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanPemeriksaanDokter()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPemeriksaan()" plain="true">Close</a>
    </div>
</div>
<div id="winClosePerawatan" class="easyui-window" title="Close Perawatan" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:180px;background: #fafafa;">
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
                        $query  = "SELECT * FROM rm_keadaan WHERE del_flag<>'1'";

                        $result = $fungsi->runQuery($query);
                        while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                            echo "<option value=\"$dt[id_keadaan]\" >$dt[keadaan]</option>";					
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
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanClosePerawatan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winClosePerawatan').window('close');" plain="true">Close</a>
    </div>
</div>
