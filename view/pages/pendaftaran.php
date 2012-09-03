<?php ?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
        <div class="easyui-layout" fit="true" style="background:#ccc;">
            <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;height:30px;">
                <a class="easyui-linkbutton" iconCls="icon-addUser" href="javascript:void(0)" onclick="openWinPasien()" plain="true">Tambah Pasien</a>
                <a class="easyui-linkbutton" iconCls="icon-editUser" href="javascript:void(0)" onclick="openWinEditPasien()" plain="true">Edit Pasien</a>
                <a class="easyui-linkbutton" iconCls="icon-searchUser" href="javascript:void(0)" onclick="openWinSearchPasien()" plain="true">Cari Pasien</a>
                <a class="easyui-linkbutton" iconCls="icon-searchUser" href="/master/" target="_blank" onclick="" plain="true">Maintain Data Karyawan</a>
            </div>
            <div region="center" border="false" style="background:#99FF99;padding:5px">
                No Rekam Medis : 
                <input name="noRm" id="noRm" class="easyui-validatebox" required="true" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPasien(this.value);
                    } else if (event.keyCode == 119){
                        openWinDaftar();
                        ruang_asal.focus();
                    } else if (event.keyCode == 112){
                        openWinSearchPasien();
                        return false;
                    } else if (event.keyCode == 113){
                        openWinEditPasien();
                    }
                       " onkeyup='IsNumeric(noRm)' />
                <a class="easyui-linkbutton" iconCls="icon-daftar" href="javascript:void(0)" onclick="openWinDaftar()" plain="true">Daftar</a>
            </div>
        </div>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <strong>Data Pasien</strong>
        <table id="detailPasien"></table>
        <div id="winDaftar" class="easyui-window" title="Form Pendaftaran" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;background: #fafafa;">
            <form name='frmDaftar' id='frmDaftar' onkeydown="
                if (event.keyCode == 123){
                    simpanPendaftaran();
                }
                  ">
                <table class='data' width="100%">
                    <tr height="25">
                        <td width='40%'>Nomor RM</td>
                        <td width='60%'><input id="id_pasien" name="id_pasien" type="text" value="" readonly></input></td>
                    </tr>
                    <tr height="25">
                        <td width='40%'>Tanggal Pendaftaran</td>
                        <td width='60%'><input id="tgl_pendaftaran" name="tgl_pendaftaran" type="text" value="<? echo date('d-m-Y'); ?>" /></td>
                    </tr>
                    <tr height="25">
                        <td width='40%'>Asal Ruang</td>
                        <td width='60%'>
                            <select name='ruang_asal' id='ruang_asal' onkeydown="
                                if (event.keyCode == 13){
                                    tipe_pendaftaran.focus();
                                }">
                                        <?
                                        $query = "SELECT id_ruang, ruang FROM rm_ruang WHERE del_flag<>'1' and id_tipe_ruang not in ('3', '6', '10', '11', '12') order by id_ruang, ruang";

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
                            <select name='tipe_pendaftaran' id='tipe_pendaftaran' onkeydown="
                                if (event.keyCode == 13){
                                    ruang.focus();
                                }">
                                <option value=''></option>
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
                            <select name='ruang' id='ruang' onkeydown="
                                if (event.keyCode == 13){
                                    kelas.focus();
                                }">
                                <option value=''></option>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='40%'>Kelas</td>
                        <td width='60%'>
                            <select name='kelas' id='kelas' onkeydown="
                                if (event.keyCode == 13){
                                    dokter.focus();
                                }">
                                <option value=''></option>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='40%'>Kamar</td>
                        <td width='60%'>
                            <select name='kamar' id='kamar' disabled onkeydown="
                                if (event.keyCode == 112){
                                    checkKamar();
                                } else if (event.keyCode == 13){
                                    bed.focus();
                                }
                                    ">
                                <option value=''></option>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='40%'>Bed</td>
                        <td width='60%'>
                            <select name='bed' id='bed' disabled onkeydown="
                                if (event.keyCode == 13){
                                    dokter.focus();
                                }">
                                <option value=''></option>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td width='40%'>Dokter</td>
                        <td width='60%'>
                            <select name='dokter' id='dokter' class="easyui-combobox" style="width:200px;">
                                <option value=''></option>
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
                                                <select name='asal_rujukan' id='asal_rujukan' onkeydown="
                                                    if (event.keyCode == 13){
                                                        perujuk.focus();
                                                    }">
                                                    <option value=''></option>
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
                                                <select name='perujuk' id='perujuk' onkeydown="
                                                    if (event.keyCode == 13){
                                                        alasan_rujuk.focus();
                                                    }">
                                                    <option value=''></option>
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
    </div>
</div>
<div id="winPasien" class="easyui-window" title="Form Pasien" draggable="true" resizable="true" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="false" closed="true" style="width:800px;height:400px;background: #fafafa;">
    <form name="frmPasien" id="frmPasien" onkeydown="if (event.keyCode == 27){
        closeWinPasien();
    }">
        <input id="pasienId" name='pasienId' type="hidden">
        <input id="tipe" name='tipe' type="hidden">
        <table width="100%" class='data'>
            <tr>
                <td valign="top" width="50%">
                    <table width="100%">
                        <tr height="25">
                            <td>
                                <label>Panggilan:</label>
                            </td>
                            <td>
                                <select id="titleDepanField" name="titleDepanField" onkeydown="if (event.keyCode==13){
                                namaPasienField.focus();
                            }">
                                    <option value=""></option>
                                    <option value="An">Anak</option>
                                    <option value="Ny">Nyonya</option>
                                    <option value="Sdr">Saudara</option>
                                    <option value="Sdri">Saudari</option>
                                    <option value="Tn">Tuan</option>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Nama Pasien:</label>
                            </td>
                            <td>
                                <div class="ausu-suggest">
                                    <input type="text" id="namaPasienField" name="namaPasienField" onkeydown="if (event.keyCode==13){
                                kelaminField.focus();
                            }">
                                </div>
                            </td>
                        </tr>

                        <tr height="25">
                            <td>
                                <label>Jenis Kelamin:</label>
                            </td>
                            <td>
                                <select id="kelaminField" name="kelaminField" onkeydown="if (event.keyCode==13){tmpLahirField.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_kelamin, kelamin FROM rm_kelamin WHERE del_flag<>'1' order by kelamin";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_kelamin]\">$dt[kelamin]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Tempat Lahir:</label>
                            </td>
                            <td>
                                <input id="tmpLahirField" name="tmpLahirField" onkeydown="if (event.keyCode==13){tglLahirField.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Tanggal Lahir:</label>
                            </td>
                            <td>
                                <input id="tglLahirField" name="tglLahirField" onkeydown="if (event.keyCode==13){maritalField.focus();}">
                                <input id="usia" name="usia" size="1" onkeydown="if (event.keyCode==13){maritalField.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Status Pernikahan:</label>
                            </td>
                            <td>
                                <select id="maritalField" name="maritalField" onkeydown="if (event.keyCode==13){listTipeAsuransi.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_marital, marital FROM rm_marital WHERE del_flag<>'1' order by marital";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_marital]\">$dt[marital]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr height="25">
                            <td>
                                <label>Asuransi:</label>
                            </td>
                            <td>
                                <select id="listTipeAsuransi" name="listTipeAsuransi" onkeydown="if (event.keyCode==13){listTipePasien.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_tipe_asuransi, tipe_asuransi FROM rm_tipe_asuransi WHERE del_flag<>'1' order by tipe_asuransi";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_tipe_asuransi]\">$dt[tipe_asuransi]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Tipe Pasien:</label>
                            </td>
                            <td>
                                <select id="listTipePasien" name="listTipePasien" onkeydown="if (event.keyCode==13){alamatField.focus();}">
                                    <option value=""></option>
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
                            <td>
                                <label>Alamat:</label>
                            </td>
                            <td>
                                <input id="alamatField" name="alamatField" onkeydown="if (event.keyCode==13){listKota.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Kota:</label>
                            </td>
                            <td>
                                <select id="listKota" name="listKota" onkeydown="if (event.keyCode==13){listKecamatan.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_kota, kota FROM rm_kota WHERE del_flag<>'1' order by kota";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_kota]\">$dt[kota]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Kecamatan:</label>
                            </td>
                            <td>
                                <select id="listKecamatan" name="listKecamatan" onkeydown="if (event.keyCode==13){listKelurahan.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_kecamatan, kecamatan FROM rm_kecamatan WHERE del_flag<>'1' order by kecamatan";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_kecamatan]\">$dt[kecamatan]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top" width="50%">
                    <table width="100%">
                        <tr height="25">
                            <td>
                                <label>Kelurahan:</label>
                            </td>
                            <td>
                                <select id="listKelurahan" name="listKelurahan" onkeydown="if (event.keyCode==13){jenisIdField.focus();}">
                                    <option value=""></option>
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
                            <td>
                                <label>Jenis Identitas:</label>
                            </td>
                            <td>
                                <select id="jenisIdField" name="jenisIdField" onkeydown="if (event.keyCode==13){noIdField.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_jenis_identitas, jenis_identitas FROM rm_jenis_identitas WHERE del_flag<>'1' order by jenis_identitas";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_jenis_identitas]\">$dt[jenis_identitas]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Nomor Identitas:</label>
                            </td>
                            <td>
                                <input id="noIdField" name="noIdField" onkeydown="if (event.keyCode==13){agamaField.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Agama:</label>
                            </td>
                            <td>
                                <select id="agamaField" name="agamaField" onkeydown="if (event.keyCode==13){sukuField.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_agama, agama FROM rm_agama WHERE del_flag<>'1' order by agama";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_agama]\">$dt[agama]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Suku:</label>
                            </td>
                            <td>
                                <input id="sukuField" name="sukuField" onkeydown="if (event.keyCode==13){kebangsaanField.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Kebangsaan:</label>
                            </td>
                            <td>
                                <input id="kebangsaanField" name="kebangsaanField" onkeydown="if (event.keyCode==13){listGolDarah.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Golongan Darah:</label>
                            </td>
                            <td>
                                <select id="listGolDarah" name="listGolDarah" onkeydown="if (event.keyCode==13){telpField.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_gol_darah, gol_darah FROM rm_gol_darah WHERE del_flag<>'1' order by gol_darah";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_gol_darah]\">$dt[gol_darah]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>No. Telp.:</label>
                            </td>
                            <td>
                                <input id="telpField" name="telpField" onkeydown="if (event.keyCode==13){hpField.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>No. HP:</label>
                            </td>
                            <td>
                                <input id="hpField" name="hpField" onkeydown="if (event.keyCode==13){gelarField.focus();}">
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Gelar:</label>
                            </td>
                            <td>
                                <select id="gelarField" name="gelarField" onkeydown="if (event.keyCode==13){listPendidikan.focus();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_title, title FROM rm_title WHERE del_flag<>'1' order by title";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_title]\">$dt[title]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr height="25">
                            <td>
                                <label>Pendidikan:</label>
                            </td>
                            <td>
                                <select id="listPendidikan" name="listPendidikan" onkeydown="if (event.keyCode==13){simpanPasien();}">
                                    <option value=""></option>
                                    <?
                                    $query = "SELECT id_pendidikan, pendidikan FROM rm_pendidikan WHERE del_flag<>'1' order by pendidikan";

                                    $result = $fungsi->runQuery($query);
                                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                        echo "<option value=\"$dt[id_pendidikan]\">$dt[pendidikan]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanPasien()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-reload" href="javascript:void(0)" onclick="pasienReload()" plain="true">Reload</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinPasien()" plain="true">Close</a>
    </div>
</div>
<div id="winSearchKamar" class="easyui-window" title="Pencarian Kamar" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:400px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <table id="dataListKamar" width='100%'></table>
        </div>
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
                                <option value=""></option>
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
                                <option value=""></option>
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
                                <option value=""></option>
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
                                <option value=""></option>
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
