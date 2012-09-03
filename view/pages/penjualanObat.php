<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:190px;">
        <form name='frmFakturPenjualan' id='frmFakturPenjualan'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>No Faktur</td>
                    <td width='30%'>
                        <input id="idf" name="idf" type="text" value="" size="5" onkeydown="if (event.keyCode == 13){
                            panggilFaktur();
                        }"/>
                        <input id="id_faktur_penjualan" name="id_faktur_penjualan" type="text" value="" disabled size="5" />
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>No Resep</td>
                    <td width='30%'><input id="no_resep" name="no_resep" type="text" value="" ></input></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Customer</td>
                    <td width='30%'>
                        <select name='jns_customer' id='jns_customer'>
                            <option value='Umum'>Umum</option>
                            <option value='Pasien'>Pasien</option>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Dokter</td>
                    <td width='30%'>
                        <select name='dokter' id='dokter' onkeydown="if (event.keyCode == 13){
                        simpanFakturPenjualan();
                    }">
                            <option value=''>[Pilih Dokter]</option>
                            <?
                            $query = "SELECT id_dokter, nama_dokter FROM rm_dokter WHERE del_flag<>'1' order by nama_dokter";

                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_dokter]\">$dt[nama_dokter]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>No RM</td>
                    <td width='30%'>
                        <input type="text" name="id_pasien" id="id_pasien" size="5" onkeydown="
                    if (event.keyCode == 13){
                        getDtlPasien();
                    } else if (event.keyCode == 38){
                        idf.focus();
                    }"/>
                        Usia:
                        <input type="text" name="usia" id="usia" size="5" readonly/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Ruang</td>
                    <td width='30%'>
                        <input type="text" name="ruang" id="ruang" size="30" disabled>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'>
                        <input type="text" name="nama_pasien" id="nama_pasien" size="30" onkeydown="
                    if (event.keyCode == 13){
                        dokter.focus();
                    }" />
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Alamat</td>
                    <td width='30%'>
                        <input type="text" name="alamat" id="alamat" size="30"/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe Pasien</td>
                    <td width='30%'>
                        <input type="text" name="tipe_pasien" id="tipe_pasien" size="30" disabled/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Operator</td>
                    <td width='30%'>
                        <input type="text" name="operator" id="operator" size="30" disabled/>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="tipe_pendaftaran" id="tipe_pendaftaran" size="1" disabled>
            <input type="hidden" name="tipe_asuransi" id="tipe_asuransi" size="1" disabled>
            <input type="hidden" name="karyawan" id="karyawan" size="1" disabled>
            <input type="hidden" name="boleh" id="boleh" size="1" disabled />
            <input type="hidden" name="idp" id="idp" size="1" disabled />
            <input type="hidden" name="idr" id="idr" size="1" disabled />
        </form>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinDetailObat()" plain="true">Entry Detail Obat</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="newFakturPenjualan()" plain="true">Faktur Baru</a>
        <a class="easyui-linkbutton" iconCls="icon-daftar" href="javascript:void(0)" onclick="$('#winFakturHapus').window('open');" plain="true">Data Faktur</a>
        <a class="easyui-linkbutton" iconCls="icon-remove" href="javascript:void(0)" onclick="hapusFaktur()" plain="true">Hapus Faktur</a>
        <a class="easyui-linkbutton" iconCls="icon-bayar" href="javascript:void(0)" onclick="openWinPembayaran()" plain="true">Pembayaran</a>
        <a class="easyui-linkbutton" iconCls="icon-bayar" href="javascript:void(0)" onclick="getStruk()" plain="true">Struk</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPenjualanObat" width='100%'></table>
        <table id="dataRacikanObat" width='100%'></table>
    </div>
</div>
<div id="winDetailObat" class="easyui-window" title="Form Penjualan Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:250px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:160px;">
        <form name='frmDetailObat' id='frmDetailObat'>
            <input type="hidden" id="id_penjualan_obat" name="id_penjualan_obat" value='' size='30'></input>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Nama Obat</td>
                    <td width='60%'>
                        <div class="ausu-suggest">
                            <input type="text" id="nama_obat" name="nama_obat" value='' size='60' onkeydown="
                        if (event.keyCode == 27){
                            closeWinDetailObat();
                            return false;
                        }"></input>
                            <input type="hidden" id="nama_obatId" name="nama_obatId" value=''></input>
                        </div>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Qty</td>
                    <td width='60%'>
                        <input type="text" id="qty" name="qty" value='' size='5' onkeyup="IsNumeric(qty)" onkeydown="
                    if (event.keyCode == 13){
                        simpanDetailObat();
                        return false;
                    } else if (event.keyCode == 27){
                        closeWinDetailObat();
                        return false;
                    }"></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Harga Satuan</td>
                    <td width='60%'>
                        <input type="text" id="harga" name="harga" value='' size='10' disabled />
                        <input type="checkbox" name="r_code" id="r_code" value="1" disabled />
                    </td>
                </tr>
<!--                <tr height="25">
                    <td width='40%'>Re-Stock Obat</td>
                    <td width='60%'>
                        <a class="easyui-linkbutton" iconCls="icon-redo" href="javascript:void(0)" onclick="reStock()" plain="true">RE-STOCK</a>
                    </td>
                </tr>-->
            </table>
        </form>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDetailObat()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDetailObat()" plain="true">Close</a>
    </div>
</div>
<div id="winRacikanObat" class="easyui-window" title="Form Penjualan Obat Racikan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:400px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
        <form name='frmRacikan' id='frmRacikan'>
            <input id="id_racikan" name="id_racikan" type="hidden" value="" size="30"></input>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Racikan</td>
                    <td width='30%'><input id="racikan" name="racikan" type="text" value="" size="30" onkeydown="
                if (event.keyCode == 13){
                    return false;
                }"/></td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanRacikan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinDetailRacikan()" plain="true">Entry Detail Racikan</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="newRacikan()" plain="true">Tambah Racikan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinRacikan()" plain="true">Close</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataRacikan" width='100%'></table>
    </div>
</div>
<div id="winDetailRacikan" class="easyui-window" title="Form Detail Racikan Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:200px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:100px;">
        <form name='frmDetailRacikan' id='frmDetailRacikan'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Nama Obat</td>
                    <td width='60%'>
                        <div class="ausu-racikan">
                            <input type="text" id="nama_obat_racikan" name="nama_obat_racikan" value='' size='30'></input>
                            <input type="hidden" id="nama_obat_racikanId" name="nama_obat_racikanId" value=''></input>
                        </div>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Qty</td>
                    <td width='60%'>
                        <input type="text" id="qtyRacikan" name="qtyRacikan" value='' size='5' onkeyup="IsNumeric(qty)" onkeydown="
                    if (event.keyCode == 13){
                        simpanDetailRacikan();
                        return false;
                    }"/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Harga Satuan</td>
                    <td width='60%'>
                        <input type="text" id="hargaRacikan" name="hargaRacikan" value='' size='10' disabled />
                        <input type="checkbox" name="r_codeRacikan" id="r_codeRacikan" value="1" disabled />
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDetailRacikan()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDetailRacikan()" plain="true">Close</a>
    </div>
</div>
<div id="winPembayaran" class="easyui-window" title="Form Pembayaran" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:250px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:160px;">
        <form name='frmPembayaran' id='frmPembayaran'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Total Tagihan</td>
                    <td width='60%'>
                        <div class="ausu-suggest">
                            <input type="text" id="total" name="total" value='' size='10' onkeyup="IsNumeric(diskonObat)" />
                        </div>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Diskon</td>
                    <td width='60%'>
                        <input type="text" id="diskonObat" name="diskonObat" value='' size='10' onkeyup="IsNumeric(diskonObat);" onblur="diskonC();" />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Asuransi</td>
                    <td width='60%'>
                        <input type="text" id="asuransi" name="asuransi" value='' size='10' onkeyup="IsNumeric(bayar)" disabled />
                        <input type="checkbox" name="allAsuransi" id="allAsuransi" value="1" disabled />
                        <label>All Asuransi</label>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Bayar</td>
                    <td width='60%'>
                        <input type="text" id="bayar" name="bayar" value='' size='10' onkeyup="IsNumeric(bayar)" onkeydown="
                    if (event.keyCode == 13){
                        getSisaBayar();
                    }
                               " disabled />
                        <input type="checkbox" name="kredit" id="kredit" value="1" />
                        <label>Kredit</label>
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
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <a class="easyui-linkbutton" id="bayarF" iconCls="icon-save" href="javascript:void(0)" onclick="simpanPembayaran()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPembayaran()" plain="true">Close</a>
    </div>
</div>
<div id="winFakturHapus" class="easyui-window" title="Data Faktur" draggable="true" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:500px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:60px;">
        <table class='data' width="100%">
            <tr height="25">
                <td width='20%'>Kode Obat</td>
                <td width='30%'>
                    <input type="text" id="cari_kode_obat" name="cari_kode_obat" value='' size='30' onkeydown="if (event.keyCode == 13){
                cariDataFaktur();
            }"/>
                </td>
                <td width='20%'>Operator</td>
                <td width='30%'>
                    <select name='cr_op' id='cr_op' onkeydown="if (event.keyCode == 13){
            cariDataFaktur();
        }">
                        <option value=''>[Pilih Operator]</option>
                        <?
                        $query = "SELECT nip, nama_pegawai FROM rm_pegawai WHERE id_jabatan = " . $_SESSION['level'] . " and del_flag<>'1'";
                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[nip]\">$dt[nama_pegawai]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='20%'>Nama Obat</td>
                <td width='30%'>
                    <input type="text" id="cari_obat" name="cari_obat" value='' size='30' onkeydown="if (event.keyCode == 13){
        cariDataFaktur();
    }"/>
                </td>
                <td width='20%'>&nbsp;</td>
                <td width='30%'>&nbsp;</td>
            </tr>
        </table>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataFaktur" width='100%'></table>
    </div>
</div>
<span id="detailLaporan" width='100%' />
