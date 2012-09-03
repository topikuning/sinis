<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:140px;">
        <form id="frmDtlPasien">
            <input id="id_pendaftaran" name="id_pendaftaran" type="hidden" value="">
            <input id="tipe_asuransi" name="tipe_asuransi" type="hidden" value="">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'><input id="id_pasien" name="id_pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataTagihanPasien();
                    } else if (event.keyCode == 112){
                        openWinSearchPasien();
                        return false;
                    }
                " onkeyup='IsNumeric(id_pasien)'></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Kelas Pembanding</td>
                    <td width='30%'>
                        <select name='kelas' id='kelas' >
                            <option value=''></option>
                            <?
                            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag='' and id_kelas!='7' order by kelas";

                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_kelas]\">$dt[kelas]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataTagihanPasien();
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
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataTagihanPasien()" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakLaporanTagihan()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <div id="detailTagihan" width='100%' height="100%" />
    </div>
</div></div>
<div id="winBayar" class="easyui-window" title="Form Pembayaran Tagihan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:300px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name='frmBayar' id='frmBayar'>
            <input id="levelDiskon" name="levelDiskon" type="hidden" value="<?echo $_SESSION['level'];?>" size="15" disabled></input>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Total Tagihan</td>
                    <td width='60%'>
                        <input id="id_pendaftaran" name="id_pendaftaran" type="hidden" value="" size="15" disabled></input>
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
                    <td width='40%'>Asuransi</td>
                    <td width='60%'>
                        <input id="asuransi" name="asuransi" type="text" value="" size="15" onkeyup="IsNumeric(asuransi)" onkeydown="
                        if (event.keyCode == 13){
                            getSisaBayar();
                        }
                    "  />
                        <input type="checkbox" name="allAsuransi" id="allAsuransi" value="1">
                        <label>All Asuransi</label>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Bayar</td>
                    <td width='60%'>
                        <input id="bayar" name="bayar" type="text" value="" size="15" onkeyup="IsNumeric(bayar)" onkeydown="
                        if (event.keyCode == 13){
                            getSisaBayar();
                        }
                    "  />
                        <input type="checkbox" name="lunas" id="lunas" value="1">
                        <label>Lunas</label>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Kembalian</td>
                    <td width='60%'>
                        <input type="text" id="kembalian" name="kembalian" value='' size='10' onkeyup="IsNumeric(kembali)" disabled></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Sisa</td>
                    <td width='60%'>
                        <input type="text" id="sisa" name="sisa" value='' size='10' onkeyup="IsNumeric(sisa)" disabled></input>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-bayar" href="javascript:void(0)" onclick="simpanPembayaran()" plain="true">Bayar</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winBayar').window('close');" plain="true">Close</a>
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
                        <input id="kurang_diskon" name="kurang_diskon" type="hidden" value="" size="10" disabled />
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
<div id="winSearchPasien" class="easyui-window" title="Data Pasien" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:800px;height:510px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:160px;">
            <form id="frmSrcPasien">
                <table class='data' width="100%">
                    <tr height="25">
                        <td width='19%'>Nama</td>
                        <td width='30%'>
                            <input id="srcNamaPasien" name="srcTindakan" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                            " ></input>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Alamat</td>
                        <td width='30%'>
                            <input id="srcAlamat" name="srcAlamat" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                            " ></input>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='19%'>Tgl Lahir</td>
                        <td width='30%'>
                            <input id="srcTglLahir" name="srcTglLahir" class="easyui-datebox" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                            " ></input>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Sampai</td>
                        <td width='30%'>
                            <input id="srcTglLahirTo" name="srcTglLahirTo" class="easyui-datebox" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                            " ></input>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='19%'>Kecamatan</td>
                        <td width='30%'>
                            <select id="srcKecamatan" name="srcKecamatan">
                                <option value="">[Pilih Kecamatan]</option>
                                <?
                                    $query = "SELECT id_kecamatan, kecamatan FROM rm_kecamatan WHERE del_flag<>'1' order by kecamatan";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_kecamatan]\">$dt[kecamatan]</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Kelurahan</td>
                        <td width='30%'>
                            <select id="srcKelurahan" name="srcKelurahan">
                                <option value="">[Pilih Kelurahan]</option>
                                <?
                                    $query = "SELECT id_kelurahan, kelurahan FROM rm_kelurahan WHERE del_flag<>'1' order by kelurahan";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_kelurahan]\">$dt[kelurahan]</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='19%'>Asuransi</td>
                        <td width='30%'>
                            <select id="srcAsuransi" name="srcAsuransi">
                                <option value="">[Pilih Asuransi]</option>
                                <?
                                    $query = "SELECT id_tipe_asuransi, tipe_asuransi FROM rm_tipe_asuransi WHERE del_flag<>'1'";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_tipe_asuransi]\">$dt[tipe_asuransi]</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Tipe Pasien</td>
                        <td width='30%'>
                            <select id="srcTipePasien" name="srcTipePasien">
                                <option value="">[Pilih Tipe Pasien]</option>
                                <?
                                    $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag<>'1'";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_tipe_pasien]\">$dt[tipe_pasien]</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
            <a class="easyui-linkbutton" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataListPasien()" plain="true">Cari</a>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <table id="dataListPasien" width='100%'></table>
        </div>
    </div>
</div>
<div id="winClosePerawatan" class="easyui-window" title="Check Out Pasien" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:200px;background: #fafafa;">
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
$query = "SELECT * FROM rm_keadaan WHERE del_flag<>'1'";

$result = $fungsi->runQuery($query);
while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<option value=\"$dt[id_keadaan]\" >$dt[keadaan]</option>";
}
?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td>
                    <label>Cara Keluar</label>
                </td>
                <td>
                    <select id="caraKeluar" name="caraKeluar">
                        <option value=''>[Pilih Cara Keluar]</option>
<?
$query = "SELECT * FROM rm_cara_keluar WHERE del_flag<>'1'";

$result = $fungsi->runQuery($query);
while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<option value=\"$dt[id_cara_keluar]\" >$dt[cara_keluar]</option>";
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
        <input id="no_rm_pasien" name="no_rm_pasien" cols="25" rows="2" type="hidden">
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanClosePerawatan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winClosePerawatan').window('close');" plain="true">Close</a>
    </div>
</div>
