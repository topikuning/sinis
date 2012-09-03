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
                <td width='19%'>No RM</td>
                <td width='30%'><input id="no_rm" name="no_rm" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " onkeyup='IsNumeric(no_pendaftaran)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Nama Pasien</td>
                <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Tanggal Pendaftaran</td>
                <td width='30%'>
                    <input id="startDate" name="startDate" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input>
                    <label> sampai </label>
                <input id="endDate" name="endDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input>
                </td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tipe Pasien</td>
                <td width='30%'>
                    <select id="tipe_pasien" name="tipe_pasien">
                        <option value="">[Pilih Tipe Pasien]</option>
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
            <tr height="25">
                <td width='19%'>Tipe Pendaftaran</td>
                <td width='30%'>
                    <select id="status" name="status">
                        <option value="">&nbsp;</option>
                        <option value="1">Rawat Jalan</option>
                        <option value="2">Rawat Inap</option>
                        <option value="3">IGD</option>
                    </select>
                </td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Status</td>
                <td width='30%'>
                    <select id="closed" name="closed">
                        <option value="">[Pilih Status]</option>
                        <option value="1">Perawatan</option>
                        <option value="2">Antrian</option>
                        <option value="3">Closed</option>
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
<div id="winDaftar" class="easyui-window" title="Form Pendaftaran" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:550px;background: #fafafa;">
    <form name='frmDaftar' id='frmDaftar'>
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Nomor ID</td>
                <td width='60%'><input id="id_pendaftaran" name="id_pendaftaran" type="text" value="" readonly></input></td>
            </tr>
            <tr height="25">
                <td width='40%'>Nomor RM</td>
                <td width='60%'><input id="id_pasien" name="id_pasien" type="text" value="" readonly></input></td>
            </tr>
            <tr height="25">
                <td width='40%'>Tanggal Pendaftaran</td>
                <td width='60%'><input id="tgl_pendaftaran" name="tgl_pendaftaran" type="text" value="<? echo date('d-m-Y'); ?>" readonly></input></td>
            </tr>
            <tr height="25">
                <td width='40%'>Asal Ruang</td>
                <td width='60%'>
                    <select name='ruang_asal' id='ruang_asal' >
                        <?
                        $query = "SELECT id_ruang, ruang FROM rm_ruang WHERE del_flag<>'1' and id_tipe_ruang in ('1', '2') order by id_tipe_ruang, ruang";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_ruang]\">$dt[ruang]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Tipe Pendaftaran</td>
                <td width='60%'>
                    <select name='tipe_pendaftaran' id='tipe_pendaftaran' >
                        <option value=''>[Pilih Tipe Pendaftaran]</option>
                        <?
                        $query = "SELECT id_tipe_pendaftaran,tipe_pendaftaran FROM rm_tipe_pendaftaran WHERE del_flag<>'1' order by tipe_pendaftaran";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_tipe_pendaftaran]\">$dt[tipe_pendaftaran]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Ruang</td>
                <td width='60%'>
                    <select name='ruang' id='ruang' >
                        <option value=''>[Pilih Ruang]</option>
                        <?
                        $query = "SELECT id_ruang,ruang FROM rm_ruang WHERE del_flag<>'1' order by ruang";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_ruang]\">$dt[ruang]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Kelas</td>
                <td width='60%'>
                    <select name='kelas' id='kelas' >
                        <option value=''>[Pilih Kelas]</option>
                        <?
                        $query = "SELECT id_kelas,kelas FROM rm_kelas WHERE del_flag<>'1' order by kelas";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_kelas]\">$dt[kelas]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Kamar</td>
                <td width='60%'>
                    <select name='kamar' id='kamar' disabled onkeydown="
                        if (event.keyCode == 112){
                            checkKamar();
                        }
                    ">
                        <option value=''>[Pilih Kamar]</option>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Bed</td>
                <td width='60%'>
                    <select name='bed' id='bed' disabled>
                        <option value=''>[Pilih Bed]</option>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Dokter</td>
                <td width='60%'>
                    <select name='dokter' id='dokter' >
                        <option value=''>[Pilih Dokter]</option>
                        <?
                        $query = "SELECT id_dokter,nama_dokter FROM rm_dokter WHERE del_flag<>'1' order by nama_dokter";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_dokter]\">$dt[nama_dokter]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Biaya Pendaftaran</td>
                <td width='60%'><input id="biaya" name="biaya" type="text" value=""></input></td>
            </tr>
            <tr height="25">
                <td colspan="2">
                    <div id="grpRujuk" class="easyui-accordion" style="width:380px;height:160px;">
                        <div title="Rujukan" style="overflow:auto;padding:1px;">
                            <table class='data' width="100%">
                                <tr height="25">
                                    <td width='40%'>Asal Rujukan</td>
                                    <td width='60%'>
                                        <select name='asal_rujukan' id='asal_rujukan' >
                                            <option value=''>[Pilih Asal Rujukan]</option>
                                            <option value='1'>Dokter</option>
                                            <option value='2'>Klinik</option>
                                            <option value='3'>Puskesmas</option>
                                            <option value='4'>Dinas</option>
                                            <option value='5'>Lain - lain</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr height="25">
                                    <td width='40%'>Perujuk</td>
                                    <td width='60%'>
                                        <select name='perujuk' id='perujuk' >
                                            <option value=''>[Pilih Perujuk]</option>
                                            <?
                                            $query = "SELECT id_perujuk,nama_perujuk FROM rm_perujuk WHERE del_flag<>'1' order by nama_perujuk";

                                            $result = $fungsi->runQuery($query);
                                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                echo "<option value=\"$dt[id_perujuk]\">$dt[nama_perujuk]</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr height="25">
                                    <td width='40%' valign='top'>Alasan Rujukan</td>
                                    <td width='60%'>
                                        <textarea name="alasan_rujuk" id="alasan_rujuk" cols="25" rows='3'></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" id="simpan-pendaftaran" iconCls="icon-save" href="javascript:void(0)" onclick="simpanPendaftaran()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDaftar()" plain="true">Close</a>
        </div>
    </form>
</div>
