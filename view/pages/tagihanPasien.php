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
                                           " onkeyup='IsNumeric(id_pasien)' /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataTagihanPasien();
                        }
                                           "  /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'><input id="jns_kelamin" name="jns_kelamin" type="text" value="" disabled /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Usia</td>
                    <td width='30%'><input id="usia" name="usia" type="text" value="" disabled /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Pasien</td>
                    <td width='30%'><input id="jns_pasien" name="jns_pasien" type="text" value="" disabled /></td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataTagihanPasien()" plain="true">Cari</a>
        <?
        if ($_SESSION['level'] == "14") {
            echo '<a class="easyui-linkbutton" id="statusBayar" iconCls="icon-bayar" href="javascript:void(0)" onclick="openWinBayar()" plain="true">Pembayaran</a>';
            //echo '<a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakLaporanTagihan()" plain="true">Cetak</a>';
            echo '<a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakTagihan()" plain="true">Cetak</a>';
            echo '<a class="easyui-linkbutton" id="cetakKwl" iconCls="icon-print" href="javascript:void(0)" onclick="openKWCetak()" plain="true">Cetak KW</a>';
            echo '<a class="easyui-linkbutton" id="checkout" iconCls="icon-logout" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Check Out Pasien</a>';
        } else if ($_SESSION['level'] == "1") {
            echo '<a class="easyui-linkbutton" id="statusBayar" iconCls="icon-bayar" href="javascript:void(0)" onclick="openWinDiskon()" plain="true">Diskon</a>';
        } else if ($_SESSION['level'] == "15") {
            echo '<a class="easyui-linkbutton" id="statusBayar" iconCls="icon-bayar" href="javascript:void(0)" onclick="openWinDiskon()" plain="true">Diskon</a>';
        }
        ?>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <div id="detailTagihan" width='100%' height="100%" />
    </div>
</div>
    </div>
<div id="winBayar" class="easyui-window" title="Form Pembayaran Tagihan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:325px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:145px;">
        <form name='frmBayar' id='frmBayar'>
            <input id="levelDiskon" name="levelDiskon" type="hidden" value="<? echo $_SESSION['level']; ?>" size="15" disabled />
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Total Tagihan</td>
                    <td width='60%'>
                        <input id="id_pendaftaran" name="id_pendaftaran" type="hidden" value="" size="15" disabled />
                        <input id="total" name="total" type="text" value="" size="15" disabled />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Admin Bank</td>
                    <td width='60%'>
                        <input id="admins" name="admins" type="text" value="0" size="15" disabled/>
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
                        <label>Asuransi</label>
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
                        <input type="text" id="kembalian" name="kembalian" value='' size='10' onkeyup="IsNumeric(kembali)" disabled />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Sisa</td>
                    <td width='60%'>
                        <input type="text" id="sisa" name="sisa" value='' size='10' onkeyup="IsNumeric(sisa)" disabled />
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="btBayar" iconCls="icon-bayar" href="javascript:void(0)" onclick="simpanPembayaran()" plain="true">Bayar</a>
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
                        <input id="total" name="total" type="text" value="" size="15" disabled />
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
                                    loadDataListPasien();
                                    return false;
                                }
                                   "  />
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Alamat</td>
                        <td width='30%'>
                            <input id="srcAlamat" name="srcAlamat" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    loadDataListPasien();
                                    return false;
                                }
                                   "  />
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='19%'>Tgl Lahir</td>
                        <td width='30%'>
                            <input id="srcTglLahir" name="srcTglLahir" class="easyui-datebox" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    loadDataListPasien();
                                    return false;
                                }
                                   "  />
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Sampai</td>
                        <td width='30%'>
                            <input id="srcTglLahirTo" name="srcTglLahirTo" class="easyui-datebox" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    loadDataListPasien();
                                    return false;
                                }
                                   "  />
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
<div id="winCetak" class="easyui-window" title="Form Cetak Kwitansi" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:210px;height:255px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name='frmCetak' id='frmCetak'>
            <table class="data" width="100%">
                <tr>
                    <td>Karcis</td>
                    <td><input type="checkbox" id="karcis" name="kw" value="8" /></td>
                </tr>
                <tr>
                    <td>Laborat</td>
                    <td><input type="checkbox" id="lab" name="kw" value="1" /></td>
                </tr>
                <tr>
                    <td>Radiologi</td>
                    <td><input type="checkbox" id="rad" name="kw" value="2" /></td>
                </tr>
                <tr>
                    <td>Utilitas</td>
                    <td><input type="checkbox" id="util" name="kw" value="3" /></td>
                </tr>
                <tr>
                    <td>Tindakan</td>
                    <td><input type="checkbox" id="tind" name="kw" value="4" /></td>
                </tr>
<!--                <tr>
                    <td>Tindakan Bedah</td>
                    <td><input type="checkbox" id="tindB" name="kw[]" value="5" /></td>
                </tr>-->
                <tr>
                    <td>Obat</td>
                    <td><input type="checkbox" id="obat" name="kw" value="6" /></td>
                </tr>
                <tr>
                    <td>ALL</td>
                    <td><input type="checkbox" id="allKW" name="kw" value="7" checked /></td>
                </tr>
                <tr>
                    <td><a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakKWL()" plain="true">Cetak</a></td>
                    <td><a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winCetak').window('close');" plain="true">Close</a></td>
                </tr>
            </table>
        </form>
    </div>
</div>