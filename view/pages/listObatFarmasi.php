<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
        <table class='data' width="100%">
            <tr height="25">
                <td width='19%'>Nama Obat</td>
                <td width='30%'><input id="nama_obat" name="nama_obat" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataObat();
                    }
                   " onkeyup='IsNumeric(no_pendaftaran)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>&nbsp;</td>
                <td width='30%'>&nbsp;</td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataObat()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataObat" width='100%'></table>
    </div>
</div>
<div id="winFaktur" class="easyui-window" title="Form Pembelian Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:900px;height:500px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name='frmFaktur' id='frmFaktur'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Nomor Faktur</td>
                    <td width='60%'>
                        <input id="no_faktur" name="no_faktur" type="text" value="" size="5" disabled></input>
                        <input id="id_faktur" name="id_faktur" type="hidden" value="" size="2" disabled></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Tanggal Pembelian</td>
                    <td width='60%'><input id="tgl_pembelian" name="tgl_pembelian" disabled type="text" value="<? echo date('Y-m-d'); ?>" class="easyui-datebox" /></td>
                </tr>
                <tr height="25">
                    <td width='40%'>Supplier</td>
                    <td width='60%'>
                        <select name='supplier' id='supplier' disabled>
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
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinFaktur()" plain="true">Close</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="detailObat" width='100%'></table>
    </div>
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
                        <input id="hpp_asli" name="hpp_asli" type="text" value="" size="5" disabled/>
                    </td>
                    <td width='40%'>HPP</td>
                    <td width='60%'>
                        <input id="hpp" name="hpp" type="text" value="" size="5"/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Umum Asal</td>
                    <td width='60%'>
                        <input id="umum_asli" name="umum_asli" type="text" value="" size="5" disabled />
                    </td>
                    <td width='40%'>Umum</td>
                    <td width='60%'>
                        <input id="umum" name="umum" type="text" value="" size="5" onkeyup="IsNumeric(umum)" />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Askes Asal</td>
                    <td width='60%'>
                        <input id="askes_asli" name="askes_asli" type="text" value="" size="5" disabled/>
                    </td>
                    <td width='40%'>Askes</td>
                    <td width='60%'>
                        <input id="askes" name="askes" type="text" value="" size="5" onkeyup="IsNumeric(askes)" />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>JPS Asal</td>
                    <td width='60%'>
                        <input id="jps_asli" name="jps_asli" type="text" value="" size="5"  disabled/>
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
