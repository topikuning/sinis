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
                <td width='19%'>Nomor RM</td>
                <td width='30%'><input id="no_rm_pasien" name="no_rm_pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" onkeyup='IsNumeric(no_rm_pasien)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Pendaftaran</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Pasien</td>
                <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
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
        <table id="dataPendaftaran" width='100%'></table>
    </div>
</div>
<div id="winDiet" class="easyui-window" title="Form Diet Pasien" draggable="false" resizable="true" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="false" closed="true" style="width:930px;height:350px;background: #fafafa;" onkeydown="if(event.keyCode==27){closeWinDiet()}">
    <div style="background-color: #fcfcfc;">
        <table id="dietPasien">
            <tr style="font-family: verdana; font-size: 11; font-weight: bold;" align="center">
                <td>
                    <input type="hidden" value="" name="id_detail_diet" id="id_detail_diet"/>
                    <input type="hidden" value="" name="checkWaktu" id="checkWaktu"/>
		DIET PASIEN
                </td>
                <td>&nbsp;</td>
                <td>JENIS PASIEN</td>
                <td>&nbsp;</td>
                <td>WAKTU DIET</td>
                <td>&nbsp;</td>
                <td>TANGGAL</td>
                <td>&nbsp;</td>
                <td>KETERANGAN</td>
                <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanDetailDiet()" plain="true"></a></td>
            </tr>
            <tr>
                <td>
                    <select name="diet" id="diet" onkeydown="if (event.keyCode == 123){
                        simpanDetailDiet();
                        return false;
                    }">
                        <option value=''>[Pilih Diet]</option>
                        <?
                        $query = "SELECT * FROM rm_diet WHERE del_flag<>'1'";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_diet]\" >$dt[diet]</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>&nbsp;</td>
                <td>
                    <select name="jns_diet" id="jns_diet" onkeydown="if (event.keyCode == 123){
                    simpanDetailDiet();
                    return false;
                }"/>
            <option value=''>[Pilih Diet]</option>
            <?
            $query = "SELECT * FROM rm_jenis_diet WHERE del_flag<>'1'";

            $result = $fungsi->runQuery($query);
            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo "<option value=\"$dt[id_jenis_diet]\" >$dt[jenis_diet]</option>";
            }
            ?>
            </select>
            </td>
            <td>&nbsp;</td>
            <td>
                <input type="radio" value="1" name="waktuDiet" id="dietPagi" onkeydown="if (event.keyCode == 123){
            simpanDetailDiet();
            return false;
        }"/>
                <label>Pagi</label>
                <input type="radio" value="2" name="waktuDiet" id="dietSiang" onkeydown="if (event.keyCode == 123){
        simpanDetailDiet();
        return false;
    }"/>
                <label>Siang</label>
                <input type="radio" value="3" name="waktuDiet" id="dietSore" onkeydown="if (event.keyCode == 123){
    simpanDetailDiet();
    return false;
}"/>
                <label>Sore</label>
            </td>
            <td>&nbsp;</td>
            <td>
                <input name="tanggalDiet" id="tanggalDiet" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>">
            </td>
            <td>&nbsp;</td>
            <td>
                <textarea id="keterangan" name="keterangan" cols="25" rows="1" onkeydown="if (event.keyCode == 123){
simpanDetailDiet();
return false;
}"></textarea>
            </td>
            </tr>
        </table>
    </div>
    <table id="dataDiet" width='100%'></table>
</div>