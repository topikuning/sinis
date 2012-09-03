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
                        <input id="id_faktur_penjualan" name="id_faktur_penjualan" type="text" value="" size="5" onkeydown="
                            if (event.keyCode == 13){
                                getDtlFaktur();
                            } else if (event.keyCode == 46 || event.keyCode == 8){
                                bersihkan();
                            }
                               " ></input>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>No Resep</td>
                    <td width='30%'><input id="no_resep" name="no_resep" type="text" value=""  disabled></input></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Customer</td>
                    <td width='30%'>
                        <select name='jns_customer' id='jns_customer' disabled>
                            <option value='Pasien'>Pasien</option>
                            <option value='Umum'>Umum</option>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Dokter</td>
                    <td width='30%'>
                        <select name='dokter' id='dokter' disabled>
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
                                getDtlFaktur();
                            }
                               " >
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
                                getDtlFaktur();
                            }
                               " >
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Alamat</td>
                    <td width='30%'>
                        <input type="text" name="alamat" id="alamat" size="30" disabled>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe Pasien</td>
                    <td width='30%'>
                        <input type="text" name="tipe_pasien" id="tipe_pasien" size="30" disabled>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
            <input type="hidden" name="tipe_pendaftaran" id="tipe_pendaftaran" size="30" disabled>
            <input type="hidden" name="tipe_asuransi" id="tipe_asuransi" size="30" disabled>
            <input type="hidden" name="karyawan" id="karyawan" size="30" disabled>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-search" href="javascript:void(0)" onclick="getDtlFaktur()" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-bayar" href="javascript:void(0)" onclick="openWinPembayaran()" plain="true">Pembayaran</a>
    </div>
    <div region="center" border="false" style="background:#FFFF;padding:5px">
        <div id="detailTagihan" width='100%' height="100%" />
        </div>
    </div>
</div>
<div id="winPembayaran" class="easyui-window" title="Form Hapus Pembayaran" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:500px;height:150px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;">
        <form name='frmPembayaran' id='frmPembayaran'>
            <table class='data' width="100%">
                <tr>
                    <td>Terbayar</td>
                    <td>
                        <input type="checkbox" name="lunas" id="lunas" value="1" />
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <label style="color: red;"><b>HILANGKAN CENTANG LALU KLIK OK UNTUK MENGHAPUS PEMBAYARAN</b></label>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanPembayaran()" plain="true">OK</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPembayaran()" plain="true">Close</a>
    </div>
</div>
