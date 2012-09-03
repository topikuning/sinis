<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:100px;">
        <form id="frmFaktur">
            <input id="id_faktur" name="id_faktur" type="hidden" value="">
        </form>
        <table class='data' width="400">
            <tr height="25">
                <td width='150'>No Faktur</td>
                <td width='250'><input id="no_fakturID" name="no_fakturID" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        getFaktur();
                    } else if (event.keyCode == 112){
                        openWinSearch();
                        return false;
                    }
                   "></input></td>
                <td width='2%'>&nbsp;</td>
            </tr>
        </table>
        <a class="easyui-linkbutton" iconCls="icon-search" href="javascript:void(0)" onclick="getFaktur()" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-bayar" href="javascript:void(0)" onclick="bayarFaktur()" plain="true">Bayar Faktur</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataFaktur" width='100%'></table>
    </div>
</div>
<div id="winSearch" class="easyui-window" title="List Faktur" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:475px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
            <form id="frmSrcFaktur">
                <table class='data' width="100%">
                    <tr height="25">
                        <td width='19%'>Supplier</td>
                        <td width='30%'>
                            <select id="supplier" name="supplier">
                            <option value=''>[Pilih Supplier]</option>
                            <?
                                $query  = "SELECT * FROM rm_supplier WHERE del_flag<>'1'";

                                $result = $fungsi->runQuery($query);
                                while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                                {
                                    echo "<option value=\"$dt[id_supplier]\" >$dt[supplier]</option>";					
                                }
                            ?>
                            </select>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Tgl Pembelian</td>
                        <td width='30%'>
                            <input type="text" id="tgl_beli" name="tgl_beli" class="easyui-datebox"/>
                        </td>
                    </tr>
                </table>
            </form>
            <a class="easyui-linkbutton" id="srcDiagnosaSearch" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataListFaktur()" plain="true">Cari</a>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <table id="dataListFaktur" width='100%'></table>
        </div>
    </div>
</div>
<div id="winBayar" class="easyui-window" title="Pembayaran Faktur" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:320px;height:220px;background: #fafafa;">
    <form id="frmBayarFaktur">
        <table class='data' width="100%">
            <tr height="25">
                <td width='19%'>Pembayaran Ke-</td>
                <td width='30%'>
                    <input type="text" id="bayarKe" name="bayarKe" size="5" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='19%'>Total Pembayaran</td>
                <td width='30%'>
                    <input type="text" id="total_bayar" name="total_bayar" size="15" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='19%'>Terbayar</td>
                <td width='30%'>
                    <input type="text" id="terbayar" name="terbayar" size="15" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='19%'>Sisa Pembayaran</td>
                <td width='30%'>
                    <input type="text" id="sisa_bayar" name="sisa_bayar" size="15" disabled/>
                    <input type="hidden" id="kurang_bayar" name="kurang_bayar" size="15" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='19%'>Bayar</td>
                <td width='30%'>
                    <input type="text" id="bayar" name="bayar" size="15"/>
                </td>
            </tr>
        </table>
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="saveBayarFaktur()" plain="true">Bayar</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winBayar').window('close')" plain="true">Close</a>
    </div>
</div>
