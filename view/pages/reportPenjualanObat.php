<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:140px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Shift</td>
                    <td width='30%'>
                        <select id="shift" name="shift">
                            <option value=''>[Pilih Shift]</option>
                            <option value='P'>Pagi</option>
                            <option value='S'>Siang</option>
                            <option value='M'>Malam</option>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tanggal Penjualan</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value="<?echo date('d-m-Y');?>"/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Status Pembayaran</td>
                    <td width='30%'>
                        <select id="status" name="status">
                            <option value='0'>Tunai</option>
                            <option value='1'>Kredit</option>
                            <option value='2'>Asuransi</option>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getLaporanPenjualan()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <span id="detailLaporan" width='100%' />
    </div>
</div>
