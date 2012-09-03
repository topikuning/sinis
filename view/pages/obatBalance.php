<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:90px;">
        <table class='data' width="100%">
            <tr height="25" style="font-size: 11px; font-weight: bold;" align="center">
                <td width='15%'>Ruang</td>
                <td width='40%'>Nama Obat</td>
                <td width='20%'>Stock Sistem</td>
                <td width='20%'>Stock Real</td>
                <td width='5%' rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="setJumlah()" plain="true"></a></td>
            </tr>
            <tr height="25" align="center">
                <td>
                    <select id="ruang" name="ruang" onkeydown="
                        if (event.keyCode == 13){
                            dObat.DOMelem_input.select();
                        }">
                                <?
                                if ($_SESSION['level'] == '18' || $_SESSION['level'] == '15') {
                                    echo "<option value='37' selected>Gudang</option>
                                    <option value='36' >Apotek Depan</option>
                                    <option value='46' >Apotek Belakang</option>
                                    <option value='47' >Apotek VIP</option>
                                    <option value='50' >Apotek IBS</option>";
                                } else if ($_SESSION['level'] == '36') {
                                    echo "<option value='36' >Apotek Depan</option>";
                                } else if ($_SESSION['level'] == '46') {
                                    echo "<option value='46' >Apotek Belakang</option>";
                                } else if ($_SESSION['level'] == '47') {
                                    echo "<option value='47' >Apotek VIP</option>";
                                } else if ($_SESSION['level'] == '50') {
                                    echo "<option value='50' >Apotek IBS</option>";
                                }
                                ?>
                    </select>
                </td>
                <td>
                    <div id="nama_obat"></div>
                </td>
                <td>
                    <input id="s_sistem" name="s_sistem" type="text" readonly/>
                </td>
                <td>
                    <input id="s_real" name="s_real" type="text" onkeydown="
                        if (event.keyCode == 13){
                            setJumlah();
                        }" onkeyup='IsNumeric(s_real)'/>
                </td>
            </tr>
        </table>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <?php
        include "stok.php";
        ?>
    </div>
</div>
