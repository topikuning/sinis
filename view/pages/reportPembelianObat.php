<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:200px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Obat</td>
                    <td width='30%'>
                        <div class="ausu-suggest">
                            <input type="text" id="nama_obatJ" name="nama_obatJ" value='' size='50'></input>
                            <input type="hidden" id="nama_obatJId" name="nama_obatJId" value=''></input>
                        </div>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%'>
                        <div class="ausu-suggest">
                            <input type="text" id="nama_obatSJ" name="nama_obatSJ" value='' size='50'></input>
                            <input type="hidden" id="nama_obatSJId" name="nama_obatSJId" value=''></input>
                        </div>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tanggal Entry</td>
                    <td width='30%'>
                        <input id="startEntryDate" name="startEntryDate" class="easyui-datebox" value=""/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%'>
                        <input id="endEntryDate" name="endEntryDate" class="easyui-datebox" value=""/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tanggal Pembelian</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value=""/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%'>
                        <input id="endDate" name="endDate" class="easyui-datebox" value=""/>
                    </td>
                </tr>
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
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe Laporan</td>
                    <td width='30%' colspan='4'>
                        <input type="radio" name="tipeLaporan" id="byObat" value="1" checked>
                        <label>By Obat</label>
                        <input type="radio" name="tipeLaporan" id="byTglEntry" value="1">
                        <label>By Tanggal Entry</label>
                        <input type="radio" name="tipeLaporan" id="byTglBeli" value="1">
                        <label>By Tanggal Pembelian</label>
                        <input type="radio" name="tipeLaporan" id="bySupplier" value="1">
                        <label>By Supplier</label>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getLaporanPembelian()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja()" plain="true">Cetak</a>
		<a class="easyui-linkbutton"  iconCls="icon-save" href="javascript:void(0)" onclick="toExcel()" plain="true">Excel</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="detailLaporan" width='100%' />
    </div>
</div>
