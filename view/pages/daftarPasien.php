<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:130px;">
        <table class='data' width="100%">
            <tr>
                <td>No. RM</td>
                <td>
                    <input id="no_rm" name="no_rm" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataPendaftaran();
                        }" onkeyup='IsNumeric(no_rm)'></input>
                </td>
                <td>&nbsp;</td>
                <td>Nama Pasien</td>
                <td>
                    <input id="pasien" name="pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataPendaftaran();
                        }" ></input>
                </td>
                <td>&nbsp;</td>
                <td>Alamat</td>
                <td>
                    <input id="alamat" name="alamat" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataPendaftaran();
                        }" ></input>
                </td>
            </tr>
            <tr>
                <td>Tanggal Pendaftaran</td>
                <td>
                    <input id="startDate" name="startDate" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>" onkeydown="
                        if (event.keyCode == 13){
                            loadDataPendaftaran();
                        }" ></input>
                </td>
                <td>&nbsp;</td>
                <td>Sampai</td>
                <td>
                    <input id="endDate" name="endDate" class="easyui-datebox" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataPendaftaran();
                        }" ></input>
                </td>
                <td>&nbsp;</td>
                <td>Tipe Pasien</td>
                <td>
                    <select id="tipe_pasien" name="tipe_pasien">
                        <option value="">[TAMPILKAN SEMUA]</option>
                        <?
                        $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag<>'1' order by tipe_pasien";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_tipe_pasien]\">$dt[tipe_pasien]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipe Pendaftaran</td>
                <td>
                    <select id="status" name="status">
                        <option value="">[TAMPILKAN SEMUA]</option>
                        <option value="1">Rawat Jalan</option>
                        <option value="2">Rawat Inap</option>
                        <option value="3">IGD</option>
                    </select>
                </td>
                <td>&nbsp;</td>
                <td>Status</td>
                <td>
                    <select id="closed" name="closed">
                        <option value="1">Perawatan</option>
                        <option value="2">Pulang</option>
                        <option value="">[TAMPILKAN SEMUA]</option>
                    </select>
                </td>
                <td>&nbsp;</td>
                <td>Ruang</td>
                <td>
                    <select id="ruangane" name="ruangane">
                        <option value="">[TAMPILKAN SEMUA]</option>
                        <?
                        $query = "SELECT id_ruang, ruang FROM rm_ruang WHERE del_flag<>'1' AND id_tipe_ruang NOT IN (1,10,11,12) order by ruang";
                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_ruang]\">$dt[ruang]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataPendaftaran()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPendaftaran" width='100%'></table>
    </div>
</div>