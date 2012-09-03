<?php
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:190px;">
        <form name='frmFakturPenjualan' id='frmFakturPenjualan'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>No Faktur</td>
                    <td width='30%'>
                        <input id="id_faktur_penjualan" name="id_faktur_penjualan" type="text" value="" size="5" onkeydown="if (event.keyCode == 13){
                            panggilFaktur();
                        }" onkeyup="IsNumeric(id_faktur_penjualan)"/>
                        
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>No Resep</td>
                    <td width='30%'><input id="no_resep" name="no_resep" type="text" value="" disabled readonly /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Customer</td>
                    <td width='30%'>
                        <select name='jns_customer' id='jns_customer' disabled readonly>
                            <option value='Umum'>Umum</option>
                            <option value='Pasien'>Pasien</option>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Dokter</td>
                    <td width='30%'>
                        <select name='dokter' id='dokter' disabled readonly>
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
                        <input type="text" name="id_pasien" id="id_pasien" size="5" disabled readonly />
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Ruang</td>
                    <td width='30%'>
                        <input type="text" name="ruang" id="ruang" size="30" disabled readonly />
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'>
                        <input type="text" name="nama_pasien" id="nama_pasien" size="30" disabled readonly />
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Alamat</td>
                    <td width='30%'>
                        <input type="text" name="alamat" id="alamat" size="30"  disabled readonly />
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe Pasien</td>
                    <td width='30%'>
                        <input type="text" name="tipe_pasien" id="tipe_pasien" size="30" disabled readonly />
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
            <input type="hidden" name="tipe_pendaftaran" id="tipe_pendaftaran" size="1" disabled>
            <input type="hidden" name="tipe_asuransi" id="tipe_asuransi" size="1" disabled>
            <input type="hidden" name="karyawan" id="karyawan" size="1" disabled>
            <input type="hidden" name="boleh" id="boleh" size="1" disabled />
            <input type="hidden" name="idp" id="idp" size="1" disabled />
            <input type="hidden" name="idr" id="idr" size="1" disabled />
        </form>
        <a class="easyui-linkbutton" iconCls="icon-bayar" href="javascript:void(0)" onclick="openWinPembayaran()" plain="true">Pembayaran</a>
        <a class="easyui-linkbutton" iconCls="icon-ok" href="javascript:void(0)" onclick="newFakturPenjualan()" plain="true">NEW</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakKW()" plain="true">Cetak KW</a>
    </div>
    <div region="center" border="false" style="background:#FFFFFF;padding:5px">
        <div id="detailTagihan" width='100%' height='100%'></div>
    </div>
</div>
<div id="winPembayaran" class="easyui-window" title="Form Pembayaran" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:275px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:185px;">
        <form name='frmPembayaran' id='frmPembayaran'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Total Tagihan</td>
                    <td width='60%'>
                            <input type="text" id="total" name="total" value='' size='10' disabled />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Administrasi</td>
                    <td width='60%'>
                        <input type="text" id="administrasi" name="administrasi" value='' size='10' disabled readonly />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Diskon</td>
                    <td width='60%'>
                        <input type="text" id="diskonObat" name="diskonObat" value='' size='10' disabled />
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
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <a class="easyui-linkbutton" id="bayarF" iconCls="icon-save" href="javascript:void(0)" onclick="simpanPembayaran()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPembayaran()" plain="true">Close</a>
    </div>
</div>