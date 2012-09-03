<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<input id="checkBahan" name="checkBahan" type="hidden" value="" disabled />
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:140px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'><input id="id_pasien" name="id_pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 119) {
                            openWinTindakan();
                        } else if (event.keyCode == 120) {
                            openWinFasilitas();
                        }
                                           " onkeyup='IsNumeric(id_pasien)'></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'><input id="pasien" name="pasien" type="text" value="" ></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'><input id="jns_kelamin" name="jns_kelamin" type="text" value="" disabled></input></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Usia</td>
                    <td width='30%'><input id="usia" name="usia" type="text" value="" disabled></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Pasien</td>
                    <td width='30%'><input id="jns_pasien" name="jns_pasien" type="text" value="" disabled></input></td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinTindakan()" plain="true">Entry Tindakan</a>
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinFasilitas()" plain="true">Entry Fasilitas</a>
        <!--        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinBahan()" plain="true">Entry Bahan</a>-->
        <!--        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToDiagnosa()" plain="true">Diagnosa</a>-->
        <!--        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToRM()" plain="true">Rekam Medis Pasien</a>-->
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Close Pemeriksaan</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataTindakan" width='100%'></table>
        <table id="dataFasilitas" width='100%'></table>
<!--        <table id="dataBahan" width='100%'></table>-->
    </div>
</div>
<div id="winTindakan" class="easyui-window" title="Form Tindakan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="false" style="width:520px;height:230px;background: #fafafa;">
    <form id="frmTindakan" name="frmTindakan">
        <input id="id_tindakan_ruang_medis" name="id_tindakan_ruang_medis" type="hidden" value="" size="40">
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Tindakan</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="tindakanMedis" name="tindakanMedis" size="40" onkeydown="
                            if (event.keyCode == 123){
                                saveTindakan();
                                return false;
                            } else if (event.keyCode == 112){
                                openWinSearch('1');
                                return false;
                            }"/>
                        <input type="hidden" id="tindakanMedisId" name="tindakanMedisId" size="1" />
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Cito</td>
                <td width='60%'>
                    <input type="hidden" name="cekCitoTindakan" id="cekCitoTindakan">
                    <input type="checkbox" name="citoTindakanMedis" id="citoTindakanMedis" value="1">
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Dokter Operator</td>
                <td width='60%'>
                    <select name='dokter_operator' id='dokter_operator' onkeydown="
                        if (event.keyCode == 13){
                            dokter_anastesi.focus();
                            return false;
                        }
                            ">
                        <option value=''>[Pilih Dokter]</option>
                        <?
                        $query = "SELECT DISTINCT a.id_dokter, b.nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE b.id_dokter=a.id_dokter order by b.nama_dokter";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_dokter]\">$dt[nama_dokter]</option>";
                        }
                        ?>
                    </select>
                    <input type="hidden" name="cekAlatTamu" id="cekAlatTamu">
                    <input type="checkbox" name="alatTamu" id="alatTamu" value="1">
                    <label>Alat DT</label>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Dokter Anastesi</td>
                <td width='60%'>
                    <select name='dokter_anastesi' id='dokter_anastesi' onkeydown="
                        if (event.keyCode == 13){
                            saveTindakan();
                            return false;
                        }
                            ">
                        <option value=''>[Pilih Dokter]</option>
                        <?
                        $query = "SELECT DISTINCT a.id_dokter, b.nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE b.id_jenis_dokter!='3' AND b.id_dokter=a.id_dokter order by b.nama_dokter";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_dokter]\">$dt[nama_dokter]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <input id="advice" name="advice" type="hidden" value="" size="1" disabled readonly />
            <tr height="25">
                <td width='40%'>Tarif</td>
                <td width='60%'>
                    <input id="tarif" name="tarif" type="text" value="" disabled onkeydown="
                        if (event.keyCode == 123){
                            saveTindakan();
                            return false;
                        }">
                    <input id="tarifTambah" name="tarifTambah" type="text" value="" disabled onkeydown="
                        if (event.keyCode == 123){
                            saveTindakan();
                            return false;
                        }
                           ">
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveTindakan()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeTindakan()" plain="true">Close</a>
        </div>
    </form>
</div>
<div id="winFasilitas" class="easyui-window" title="Form Fasilitas" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:500px;height:220px;background: #fafafa;">
    <form id="frmFasilitas" name="frmFasilitas">
        <input id="id_fasilitas_ruang" name="id_fasilitas_ruang" type="hidden" value="" size="40">
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Fasilitas</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="tindakanF" name="tindakanF" size="30" onkeydown="
                            if (event.keyCode == 123){
                                saveTindakan();
                                return false;
                            } else if (event.keyCode == 112){
                                openWinSearch('1');
                                return false;
                            }"/>
                        <input type="hidden" id="tindakanFId" name="tindakanFId" size="1" />
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Jumlah</td>
                <td width='60%'>
                    <input type="text" id="jumlah" name="jumlah" size="5"/>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Pelaksana</td>
                <td width='60%'>
                    <select name='dokterF' id='dokterF' onkeydown="
                        if (event.keyCode == 123){
                            saveFasilitas();
                            return false;
                        }
                            ">
                        <option value='9999999'>Perawat</option>
                        <?
                        $query = "select nama_pegawai, id_pegawai from rm_pegawai where id_jabatan='49' and del_flag<>'1'";
                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_pegawai]\">$dt[nama_pegawai]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <input id="adviceF" name="adviceF" type="hidden" value="" size="1" readonly disabled />
            <tr height="25">
                <td width='40%'>Tarif</td>
                <td width='60%'>
                    <input id="id_tarifF" name="id_tarifF" type="hidden" value="">
                    <input id="tarifF" name="tarifF" type="text" value="" disabled onkeydown="
                        if (event.keyCode == 123){
                            saveFasilitas();
                            return false;
                        }
                           ">
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveFasilitas()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winFasilitas').window('close')" plain="true">Tutup</a>
        </div>
    </form>
</div>
<div id="winBahan" class="easyui-window" title="Form Bahan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:500px;height:220px;background: #fafafa;">
    <form id="frmBahan" name="frmFasilitas">
        <input id="id_barang_tindakan" name="id_barang_tindakan" type="hidden" value="" size="40">
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>Nama Barang</td>
                <td width='60%'>
                    <div class="ausu-suggest">
                        <input type="text" id="bahan" name="bahan" size="30" onkeydown="
                            if (event.keyCode == 123){
                                saveBahan();
                                return false;
                            } else if (event.keyCode == 112){
                                openWinSearch('1');
                                return false;
                            }"/>
                        <input type="hidden" id="bahanId" name="bahanId" size="1" />
                    </div>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Jumlah</td>
                <td width='60%'>
                    <input type="text" id="jumlahBarang" name="jumlahBarang" size="5"/>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Stock</td>
                <td width='60%'>
                    <input type="text" id="stock" name="stock" size="5" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Satuan</td>
                <td width='60%'>
                    <input type="text" id="satuan" name="satuan" size="5" disabled/>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Tarif</td>
                <td width='60%'>
                    <input type="text" id="tarifBahan" name="tarifBahan" size="5" disabled/>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveBahan()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winBahan').window('close')" plain="true">Cancel</a>
        </div>
    </form>
</div>
<div id="winSearch" class="easyui-window" title="Tindakan" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:475px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
            <form id="frmSrcTindakan">
                <input id="idTindakan" name="idTindakan" type="hidden" value="" disabled></input>
                <table class='data' width="100%">
                    <tr height="25">
                        <td width='19%'>Tindakan</td>
                        <td width='30%'><input id="srcTindakan" name="srcTindakan" type="text" value="" onkeydown="
                            if (event.keyCode == 13){
                                loadDataListTindakan();
                                return false;
                            }
                                               " ></input></td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>&nbsp;</td>
                        <td width='30%'>&nbsp;</td>
                    </tr>
                </table>
            </form>
            <a class="easyui-linkbutton" id="srcDiagnosaSearch" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataListTindakan()" plain="true">Cari</a>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <table id="dataListTindakan" width='100%'></table>
        </div>
    </div>
</div>