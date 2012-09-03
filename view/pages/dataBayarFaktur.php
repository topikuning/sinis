<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:100px;">
        <table class='data' width="400">
            <tr height="25">
                <td width='150'>Kode Obat</td>
                <td width='250'><input id="no_fakturID" name="no_fakturID" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        getFaktur();
                    }
                   "></input></td>
                <td width='2%'>&nbsp;</td>
            </tr>
        </table>
        <a class="easyui-linkbutton" iconCls="icon-search" href="javascript:void(0)" onclick="getFaktur()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataFaktur" width='100%'></table>
    </div>
</div>
