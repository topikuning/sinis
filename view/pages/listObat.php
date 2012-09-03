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
    </div>
</div>
<div id="winFaktur" class="easyui-window" title="Form Pembelian Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:900px;height:500px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name='frmFaktur' id='frmFaktur'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='20%'>Nomor Faktur</td>
                    <td width='30%'>
                        <input id="no_faktur" name="no_faktur" type="text" value="" size="5" disabled></input>
                        <input id="id_pembayaran_faktur" name="id_pembayaran_faktur" type="text" value="" size="2" disabled></input>
                    </td>
                    <td width='20%'>Total Pembayaran</td>
                    <td width='30%'>
                        <input id="total" name="total" type="text" value="" size="10" disabled></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='20%'>Tanggal Pembelian</td>
                    <td width='30%'><input id="tgl_pembelian" name="tgl_pembelian" disabled type="text" /></td>
                    <td width='20%'>Kurang Bayar</td>
                    <td width='30%'><input id="sisa" name="sisa" size="10" disabled type="text" /></td>
                </tr>
                <tr height="25">
                    <td width='20%'>Supplier</td>
                    <td width='30%'>
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
                    <td width='20%'>Jumlah Pembayaran</td>
                    <td width='30%'><input id="bayar" name="bayar" size="10" disabled type="text" /></td>
                </tr>
            </table>
        </form>
        <a id="approve" iconCls="icon-ok" href="javascript:void(0)" onclick="approveFaktur()" plain="true">Approve</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinFaktur()" plain="true">Close</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="detailObat" width='100%'></table>
        <table id="dataListPembayaran" width='100%'></table>
    </div>
</div>
