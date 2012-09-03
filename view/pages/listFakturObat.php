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
                <td width='19%'>Kode Obat</td>
                <td width='30%'><input id="kode_obat" name="kode_obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " onkeyup='IsNumeric(no_pendaftaran)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Kadaluarsa</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value="<? echo date('Y-m-d'); ?>" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Obat</td>
                <td width='30%'><input id="obat" name="obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Sampai</td>
                <td width='30%'><input id="endDate" name="endDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataPendaftaran()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataFaktur" width='100%'></table>
        <table id="dataObat" width='100%'></table>
    </div>
</div>
<div id="winFaktur" class="easyui-window" title="Form Pembelian Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:520px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:150px;">
        <form name='frmFaktur' id='frmFaktur'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Nomor Faktur</td>
                    <td width='60%'>
                        <input id="no_faktur" name="no_faktur" type="text" value="" size="5"></input>
                        <input id="id_faktur" name="id_faktur" type="hidden" value="" size="2"></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Tanggal Pembelian</td>
                    <td width='60%'><input id="tgl_pembelian" name="tgl_pembelian" type="text" value="<? echo date('Y-m-d'); ?>" class="easyui-datebox" /></td>
                </tr>
                <tr height="25">
                    <td width='40%'>Tanggal Jatuh Tempo</td>
                    <td width='60%'><input id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" type="text" value="<? echo date('Y-m-d'); ?>" class="easyui-datebox" /></td>
                </tr>
                <tr height="25">
                    <td width='40%'>Supplier</td>
                    <td width='60%'>
                        <select name='supplier' id='supplier' >
                            <option value=''>[Pilih Supplier]</option>
                            <?
                            $query = "SELECT id_supplier, supplier FROM rm_supplier WHERE del_flag<>'1' order by supplier";

                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_supplier]\">$dt[supplier]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id='simpanFaktur' iconCls="icon-save" href="javascript:void(0)" onclick="saveFaktur()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinDetailFaktur()" plain="true">Tambah Obat</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="newFaktur()" plain="true">Faktur Baru</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinFaktur()" plain="true">Close</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="detailObat" width='100%'></table>
    </div>
</div>
<div id="winDetailFaktur" class="easyui-window" title="Form Detail Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:300px;background: #fafafa;">
    <form name='frmDetailFaktur' id='frmDetailFaktur'>
        <input type="hidden" id="id_pembelian_obat" name="id_pembelian_obat" value='' size='30'></input>
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Nama Obat</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="nama_obat" name="nama_obat" value='' size='30'></input>
                        <input type="hidden" id="nama_obatId" name="nama_obatId" value=''></input>
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Qty</td>
                <td width='60%'>
                    <input type="text" id="qty" name="qty" value='' size='5' onkeyup="IsNumeric(qty)"></input>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Retur</td>
                <td width='60%'>
                    <input type="text" id="retur" name="retur" value='' size='10' onkeyup="IsNumeric(retur)"></input>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Harga Satuan</td>
                <td width='60%'>
                    <input type="text" id="harga" name="harga" value='' size='10' onkeyup="IsNumeric(harga)"></input>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Diskon</td>
                <td width='60%'>
                    <input type="text" id="diskon" name="diskon" value='' size='10' onkeyup="IsNumeric(diskon)"></input>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Pajak</td>
                <td width='60%'>
                    <input type="text" id="pajak" name="pajak" value='' size='10' onkeyup="IsNumeric(pajak)"></input>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Tanggal Kadaluarsa</td>
                <td width='60%'>
                    <input id="tgl_kadaluarsa" name="tgl_kadaluarsa" type="text" value="" class="easyui-datebox" />
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Disimpan di-</td>
                <td width='60%'>
                    <select name='penyimpanan' id='penyimpanan' >
                        <?
                        $query = "SELECT id_penyimpanan,penyimpanan FROM rm_penyimpanan WHERE del_flag<>'1' order by penyimpanan";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_penyimpanan]\">$dt[penyimpanan]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanBeliObat()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDetailFaktur()" plain="true">Close</a>
        </div>
    </form>
</div>
<div id="winHarga" class="easyui-window" title="Form Update Harga" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:250px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name='frmHarga' id='frmHarga'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Nama Obat</td>
                    <td width='60%' colspan="3">
                        <input id="nama" name="nama" type="text" value="" size="30" disabled></input>
                        <input id="id_obat" name="id_obat" type="hidden" value="" size="2" disabled></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>HPP Asal</td>
                    <td width='60%'>
                        <input id="hpp_asli" name="hpp_asli" type="text" value="" size="10" disabled/>
                    </td>
                    <td width='40%'>HPP</td>
                    <td width='60%'>
                        <input id="hpp" name="hpp" type="text" value="" size="5"/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Umum Asal</td>
                    <td width='60%'>
                        <input id="umum_asli" name="umum_asli" type="text" value="" size="10" disabled />
                    </td>
                    <td width='40%'>Umum</td>
                    <td width='60%'>
                        <input id="umum" name="umum" type="text" value="" size="5" onkeyup="IsNumeric(umum)" />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Askes Asal</td>
                    <td width='60%'>
                        <input id="askes_asli" name="askes_asli" type="text" value="" size="10" disabled/>
                    </td>
                    <td width='40%'>Askes</td>
                    <td width='60%'>
                        <input id="askes" name="askes" type="text" value="" size="5" onkeyup="IsNumeric(askes)" />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>JPS Asal</td>
                    <td width='60%'>
                        <input id="jps_asli" name="jps_asli" type="text" value="" size="10"  disabled/>
                    </td>
                    <td width='40%'>JPS</td>
                    <td width='60%'>
                        <input id="jps" name="jps" type="text" value="" size="5" onkeyup="IsNumeric(jps)" />
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="updateHarga()" plain="true">Update Harga</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinHarga()" plain="true">Close</a>
    </div>
</div>
