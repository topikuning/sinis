<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:130px;">
        <table class='data' width="100%">
            <tr height="25">
                <td width='19%'>No Faktur</td>
                <td width='30%'><input id="no_faktur" name="no_faktur" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataFakturPenjualan();
                    }
                                       " onkeyup='IsNumeric(no_pendaftaran)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Penjualan</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>" onkeydown="
                    if (event.keyCode == 13){
                        loadDataFakturPenjualan();
                    }
                                       " ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Pasien</td>
                <td width='30%'><input id="nama_pasien" name="nama_pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataFakturPenjualan();
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
            <tr height="25">
                <td width='19%'>Status Pembayaran</td>
                <td width='30%'>
                    <select name='status' id='status' >
                        <option value=''>[Pilih Status]</option>
                        <option value='2'>Lunas</option>
                        <option value='1'>Kredit</option>
                        <option value='0'>Belum Terbayar</option>
                    </select>
                </td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataFakturPenjualan()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataFaktur" width='100%'></table>
    </div>
</div>
