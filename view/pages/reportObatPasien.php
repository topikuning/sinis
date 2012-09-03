<?php
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:140px;">
        <form id="frmDtlPasien">
            <input id="id_pendaftaran" name="id_pendaftaran" type="hidden" value="">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'><input id="id_pasien" name="id_pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            getDataObatPasien();
                        } else if (event.keyCode == 112){
                            openWinSearchPasien();
                            return false;
                        }
                                           " onkeyup='IsNumeric(id_pasien)'></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataTagihanPasien();
                        }" /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Jenis Pasien</td>
                    <td width='30%'><input id="jns_pasien" name="jns_pasien" type="text" value="" disabled /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'><input id="jns_kelamin" name="jns_kelamin" type="text" value="" disabled /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Ruang</td>
                    <td width='30%'><select id="ruang" name="ruang">
                            <option value="">ALL</option>
                            <?php 
                            $query = "SELECT id_ruang, ruang FROM rm_ruang where del_flag<>1 AND ruang like 'Apot%'";
                            $result = $fungsi->runQuery($query);
                            if (@mysql_num_rows($result) > 0) {
                                while ($dt = @mysql_fetch_array($result, @MYSQL_ASSOC)) {
                                    echo "<option value=".$dt[id_ruang].">".$dt[ruang]."</option>";
                                }
                            }
                            ?>
                        </select></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Status</td>
                    <td width='30%'><select id="status" name="status">
                            <option value="3">ALL</option>
                            <option value="2">LUNAS</option>
                            <option value="1">KREDIT</option>
                        </select></td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getDataObatPasien()" plain="true">Cari</a>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="toExcel()" plain="true">Excel</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <div id="detailLaporan" width='100%' height="100%" /></div>
    </div>
</div>
<div id="winSearchPasien" class="easyui-window" title="Data Pasien" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:800px;height:510px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:160px;">
            <form id="frmSrcPasien">
                <table class='data' width="100%">
                    <tr height="25">
                        <td width='19%'>Nama</td>
                        <td width='30%'>
                            <input id="srcNamaPasien" name="srcTindakan" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                                   " ></input>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Alamat</td>
                        <td width='30%'>
                            <input id="srcAlamat" name="srcAlamat" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                                   " ></input>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='19%'>Tgl Lahir</td>
                        <td width='30%'>
                            <input id="srcTglLahir" name="srcTglLahir" class="easyui-datebox" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                                   " ></input>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Sampai</td>
                        <td width='30%'>
                            <input id="srcTglLahirTo" name="srcTglLahirTo" class="easyui-datebox" type="text" value="" onkeydown="
                                if (event.keyCode == 13){
                                    //alert('ok');
                                    loadDataListPasien();
                                    return false;
                                }
                                   " ></input>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='19%'>Kecamatan</td>
                        <td width='30%'>
                            <select id="srcKecamatan" name="srcKecamatan">
                                <option value="">[Pilih Kecamatan]</option>
                                <?
                                $query = "SELECT id_kecamatan, kecamatan FROM rm_kecamatan WHERE del_flag<>'1' order by kecamatan";

                                $result = $fungsi->runQuery($query);
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    echo "<option value=\"$dt[id_kecamatan]\">$dt[kecamatan]</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Kelurahan</td>
                        <td width='30%'>
                            <select id="srcKelurahan" name="srcKelurahan">
                                <option value="">[Pilih Kelurahan]</option>
                                <?
                                $query = "SELECT id_kelurahan, kelurahan FROM rm_kelurahan WHERE del_flag<>'1' order by kelurahan";

                                $result = $fungsi->runQuery($query);
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    echo "<option value=\"$dt[id_kelurahan]\">$dt[kelurahan]</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='19%'>Asuransi</td>
                        <td width='30%'>
                            <select id="srcAsuransi" name="srcAsuransi">
                                <option value="">[Pilih Asuransi]</option>
                                <?
                                $query = "SELECT id_tipe_asuransi, tipe_asuransi FROM rm_tipe_asuransi WHERE del_flag<>'1'";

                                $result = $fungsi->runQuery($query);
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    echo "<option value=\"$dt[id_tipe_asuransi]\">$dt[tipe_asuransi]</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Tipe Pasien</td>
                        <td width='30%'>
                            <select id="srcTipePasien" name="srcTipePasien">
                                <option value="">[Pilih Tipe Pasien]</option>
                                <?
                                $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag<>'1'";

                                $result = $fungsi->runQuery($query);
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    echo "<option value=\"$dt[id_tipe_pasien]\">$dt[tipe_pasien]</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
            <a class="easyui-linkbutton" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataListPasien()" plain="true">Cari</a>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <table id="dataListPasien" width='100%'></table>
        </div>
    </div>
</div>
