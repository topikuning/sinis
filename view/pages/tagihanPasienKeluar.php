<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:100px;">
        <form id="frmDtlPasien">
            <input id="id_pendaftaran" name="id_pendaftaran" type="hidden" value="">
            <input id="tipe_asuransi" name="tipe_asuransi" type="hidden" value="">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>ID Pendaftaran</td>
                    <td width='30%'><input id="id_daftar" name="id_daftar" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataTagihanPasien();
                            return false;
                        }" onkeyup='IsNumeric(id_daftar)' /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataTagihanPasien()" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakTagihan()" plain="true">Cetak</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="exExcel()" plain="true">Excel</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <div id="detailTagihan" width='100%' height="100%" />
    </div>
</div></div>
