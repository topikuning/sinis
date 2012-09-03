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
                    }" onkeyup='IsNumeric(no_rm_pasien)'/></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Nama Pasien</td>
                <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" /></td>
            </tr>
            <tr height="25">
                <td width='19%'>Tanggal Pendaftaran</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" />
                    Sampai
                <input id="endDate" name="endDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" /></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tampilkan</td>
                <td width='30%'><select id="perawatan" name="perawatan">
                        <option value="1">Perawatan</option>
                        <option value="2">Pulang</option>
                    </select></td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataPendaftaran()" plain="true">Cari</a>
        <?php
        if ($_SESSION['level'] == "20") {
            echo '<a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="goToSurvey()" plain="true">Entry Survey</a>';
        }
        ?>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPendaftaran" width='100%'></table>
    </div>
</div>
<div id="winDaftar" class="easyui-window" title="Form Konsul" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:270px;background: #fafafa;">
    <form name='frmDaftar' id='frmDaftar'>
        <input id="id_tipe_pasien" name="id_tipe_pasien" type="hidden" value="" readonly></input>
        <input id="id_asal_pendaftaran" name="id_asal_pendaftaran" type="hidden" value="" readonly></input>
        <input id="id_kelas_pendaftaran" name="id_kelas_pendaftaran" type="hidden" value="" readonly></input>
        <table class='data' width="100%">
            <tr height="25">
                <td width='40%'>No RM</td>
                <td width='60%'><input id="id_pasien" name="id_pasien" type="text" value="" readonly></input></td>
            </tr>
            <tr height="25">
                <td width='40%'>Tanggal Layanan</td>
                <td width='60%'><input id="tgl_pendaftaran" name="tgl_pendaftaran" type="hidden" value="<? echo date('d-m-Y'); ?>" readonly></input>
                    <input id="jadwal" size="7" name="jadwal" type="text" value="<? echo date('d-m-Y'); ?>" ></input>
                    <input id="waktu" size="5" name="waktu" type="text" value="<? echo date('H:i:s'); ?>" ></input></td>
            </tr>
            <tr height="25">
                <td width='40%'>Asal Ruang</td>
                <td width='60%'>
                    <select name='ruang_asal' id='ruang_asal' disabled>
                        <?
                        $query = "SELECT id_ruang, ruang FROM rm_ruang WHERE del_flag<>'1' and id_ruang='" . $_SESSION['level'] . "' order by id_tipe_ruang, ruang";

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
                        $query = "SELECT id_tipe_pendaftaran,tipe_pendaftaran FROM rm_tipe_pendaftaran WHERE del_flag<>'1' and id_tipe_pendaftaran='7' order by tipe_pendaftaran";

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
                    </select>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Kelas</td>
                <td width='60%'>
                    <input id="kelas" name="kelas" type="text" value="" readonly>
                    <input id="id_kelas" name="id_kelas" type="hidden" value="" readonly size='2'>
                </td>
            </tr>
            <tr height="25">
                <td width='40%'>Dokter</td>
                <td width='60%'>
                    <select name='dokter_konsul' id='dokter_konsul' >
                        <option value=''>[Pilih Dokter]</option>
                    </select>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanPendaftaran()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDaftar()" plain="true">Close</a>
        </div>
    </form>
</div>
<div id="winLayanan" class="easyui-window" title="Form Layanan Medis" draggable="false" resizable="true" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:930px;height:500px;background: #fafafa;" onkeydown="if(event.keyCode==27){closeWinLayanan()}">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 1px;">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='10%'>Nomor RM</td>
                    <td width='20%'><input id="rm_pas" name="no_rm_pasien" type="text"></input>
                        <input id="idp" name="idp" type="hidden"  readonly disabled></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Nama Px</td>
                    <td width='20%'><input id="nm_pas" name="pasien" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='9%'>Usia Px</td>
                    <td width='20%'><input id="ag_pas" name="pasien" type="text"></input></td>
                </tr>
                <tr height="25">
                    <td width='10%'>Jenis Px</td>
                    <td width='20%'><input id="jn_pas" name="no_rm_pasien" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Kelas Px</td>
                    <td width='20%'><input id="kl_pas" name="pasien" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='9%'>JK Px</td>
                    <td width='20%'><input id="jk_pas" name="pasien" type="text"></input></td>
                </tr>
            </table>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <div style="background-color: #fcfcfc;">
                <table id="inTindakan" onkeydown="if (event.keyCode == 34){
                    dFasilitas.DOMelem_input.focus();
                    return false;
                }">
                    <tr style="font-size: 11px; font-weight: bold;" align="center">
                        <td><input id="id_tindakan_ruang" name="id_tindakan_ruang" type="hidden" value="" size="5"> <B>TINDAKAN</td>
                        <td>DOKTER</td>
                        <td>CITO</td>
                        <td>TANGGAL</td>
                        <td>OPERATOR</td>
                        <td>TARIF</td>
                        <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveTindakan()" plain="true"></a></td>
                    </tr>
                    <tr>
                        <td>
                            <div id="tindakan"></div>
                            <input type="hidden" id="tindakanId" name="tindakanId" size="1"/>

                        </td>
                        <td>
                            <select name='dokter' id='dokter' style="width: 200px;" />
                    <option value=''>[Pilih Dokter]</option>
                    <?
                    if ($_SESSION['level'] == "24" ||
                            $_SESSION['level'] == "25" ||
                            $_SESSION['level'] == "26" ||
                            $_SESSION['level'] == "27" ||
                            $_SESSION['level'] == "28" ||
                            $_SESSION['level'] == "29" ||
                            $_SESSION['level'] == "30" ||
                            $_SESSION['level'] == "31" ||
                            $_SESSION['level'] == "32" ||
                            $_SESSION['level'] == "33" ||
                            $_SESSION['level'] == "44" ||
                            $_SESSION['level'] == "22" ||
                            $_SESSION['level'] == "34" ||
                            $_SESSION['level'] == "20"
                    ) {
                        $query = "select * from rm_dokter where id_jenis_dokter!='3'";
                    } else if ($_SESSION['level'] == "17" || $_SESSION['level'] == "18") {
                        $query = "SELECT a.id_dokter, b.nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE a.id_ruang='" . $_SESSION['level'] . "' AND b.id_dokter=a.id_dokter order by b.nama_dokter";
                    } else {
                        $query = "SELECT a.id_dokter, b.nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE (a.id_ruang='" . $_SESSION['level'] . "' OR a.id_ruang='0') AND b.id_dokter=a.id_dokter order by b.nama_dokter";
                    }

                    $result = $fungsi->runQuery($query);
                    while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                        echo "<option value=\"$dt[id_dokter]\">$dt[nama_dokter]</option>";
                    }
                    ?>
                    </select>
                    </td>
                    <td>
                        <?
                        if ($_SESSION['level'] == '19')
                            echo '<input type="checkbox" name="cito" id="cito" value="1">';
                        else
                            echo '<input type="checkbox" name="cito" id="cito" value="1" disabled>';
                        ?>
                    </td>
                    <td>
                        <input id="tglInput" value="<? echo date('d-m-Y'); ?>" name="tglInput" size="9" onkeydown="
                        if (event.keyCode == 13){
                            operator.focus();
                        }
                               "></input>
                    </td>
                    <td>
                        <select name='operator' id='operator' onkeydown="
                        if (event.keyCode == 13){
                            saveTindakan();
                            return false;
                        }
                                ">
                            <option value=''>[Pilih Operator]</option>
                            <?
                            $query = "SELECT * FROM rm_pelaku_tindakan order by nama_pelaku";

                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_pelaku_tindakan]\">$dt[nama_pelaku]</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <input id="advice" name="advice" type="hidden" value="" size="20" />
                    <td>
                        <input id="id_tarif" name="id_tarif" type="hidden" value="">
                        <input id="tarif" name="tarif" type="text" value="" readonly />
                    </td>
                    </tr>
                </table>
            </div>
            <table id="dataTindakan" width='100%'></table>
            <div style="background-color: #fcfcfc;">
                <table id="inFasilitas" onkeydown="if (event.keyCode == 34){
                bahan.focus();
                return false;
            }">
                    <tr align="center" style="font-size: 11px; font-weight: bold;">
                        <td><input id="id_fasilitas_ruang" name="id_fasilitas_ruang" type="hidden" value="" size="1">FASILITAS</td>
                        <td>PELAKSANA</td>
                        <td>JUMLAH</td>
                        <td>TARIF</td>
                        <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveFasilitas()" plain="true"></a></td>
                    </tr>
                    <tr>
                        <td>
                            <div id="tindakanF"></div>
                            <input type="hidden" id="tindakanFId" name="tindakanFId" size="1" />
                        </td>
                        <td>
                            <select name='dokterF' id='dokterF' style="width: 200px;" onkeydown="
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
                        <td>
                            <input type="text" id="jumlah" name="jumlah" size="5" value="1" onkeydown="
                        if (event.keyCode == 13){
                            saveFasilitas();
                        }"/>
                        </td>
                    <input id="adviceF" name="adviceF" type="hidden" value="" size="1"/>
                    <td>
                        <input id="id_tarifF" name="id_tarifF" type="hidden" value="">
                        <input id="tarifF" name="tarifF" type="text" value="" readonly onkeydown="
                    if (event.keyCode == 123){
                        saveFasilitas();
                        return false;
                    }
                               ">
                    </td>
                    </tr>
                </table>
            </div>
            <table id="dataFasilitas" width='100%'></table>
            <?php
            if ($_SESSION['level'] == "20") {
                echo '<script>
                        var dokterV = dhtmlXComboFromSelect("dokterVisite");
                        dokterV.enableFilteringMode(true);
                        dokterV.attachEvent("onChange", changePeriksa);
                        dokterV.attachEvent("onKeyPressed", keyDokterV);
                  </script>';
                echo '<div style="background-color: #fcfcfc;">
                <table id="frmVisitDOkter">
                    <tr style="font-size: 11px; font-weight: bold;" align="center">
                        <td><input type="hidden" value="" name="visit" id="visit" size="5" disabled/>DOKTER</td>
                        <td>TANGGAL</td>
                        <td>TARIF</td>
                        <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanPemeriksaanDokter()" plain="true"></a></td>
                    </tr>
                    <tr>
                        <td>
                            <select id="dokterVisite" name="dokterVisite" style="width: 200px;">
                                <option value=""></option>';

                $query = "SELECT * FROM rm_dokter WHERE del_flag<>1 and id_jenis_dokter!=3";

                $result = $fungsi->runQuery($query);
                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    echo "<option value=\"$dt[id_dokter]\" >$dt[nama_dokter]</option>";
                }

                echo '</select>
                        </td>
                        <td>
                            <input id="tglVisite" name="tglVisite" value="';
                echo date('d-m-Y');
                echo'" onkeydown="
                if (event.keyCode == 13) {
                    simpanPemeriksaanDokter();
                }" />
                        </td>
                        <td>
                            <input id="tarifPemeriksaane" name="tarifPemeriksaane" readonly />
                        </td>
                    </tr>
                </table>
            </div>
            <table id="dataVisit" width="100%"></table>';
            }
            ?>
        </div>
    </div>
</div>
<div id="winDiskon" class="easyui-window" title="Form Diskon Tagihan" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:250px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name='frmDiskon' id='frmDiskon'>
            <table class='data' width="100%">
                <tr height="25">
                    <td width='40%'>Total Tagihan</td>
                    <td width='60%'>
                        <input id="total" name="total" type="text" value="" size="15" disabled></input>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Tagihan Terbayar</td>
                    <td width='60%'>
                        <input id="terbayar" name="terbayar" type="text" value="" size="15" disabled/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Diskon</td>
                    <td width='60%'>
                        <input id="diskon_all" name="diskon_all" type="text" value="" size="15" disabled/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Kurang Bayar</td>
                    <td width='60%'>
                        <input id="kurang_bayar" name="kurang_bayar" type="text" value="" size="15" disabled />
                        <input id="kurang" name="kurang" type="hidden" value="" size="10" disabled />
                    </td>
                </tr>
                <tr height="25">
                    <td width='40%'>Diskon</td>
                    <td width='60%'>
                        <input id="diskon" name="diskon" type="text" value="" size="15" onkeyup="IsNumeric(diskon)" />
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanDiskon()" plain="true">Simpan Diskon</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winDiskon').window('close');" plain="true">Close</a>
    </div>
</div>
<div id="winDiagnosa" class="easyui-window" title="Form Diagnosa" draggable="false" resizable="true" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:930px;height:500px;background: #fafafa;" onkeydown="if(event.keyCode==27){closeWinDiet()}">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 1px;">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='10%'>Nomor RM</td>
                    <td width='20%'><input id="rm_px" name="rm_px" type="text"></input>
                        <input id="idx" name="idx" type="hidden"  readonly disabled></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Nama Px</td>
                    <td width='20%'><input id="nm_px" name="nm_px" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='9%'>Usia Px</td>
                    <td width='20%'><input id="ag_px" name="ag_px" type="text"></input></td>
                </tr>
                <tr height="25">
                    <td width='10%'>Jenis Px</td>
                    <td width='20%'><input id="jn_px" name="jn_px" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Kelas Px</td>
                    <td width='20%'><input id="kl_px" name="kl_px" type="text"></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='9%'>JK Px</td>
                    <td width='20%'><input id="jk_px" name="jk_px" type="text"></input></td>
                </tr>
            </table>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <div style="background-color: #fcfcfc;">
                <table id="inDiagnosa">
                    <tr style="font-size: 11px; font-weight: bold;" align="center">
                        <td><input id="id_diagnosa" name="id_diagnosa" type="hidden" value="" size="5" /> <B>DOKTER</td>
                        <td>DIAGNOSA PRIMER</td>
                        <td>DIAGNOSA SEKUNDER</td>
                        <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveDiagnosa()" plain="true"></a></td>
                    </tr>
                    <tr>
                        <td>
                            <select name='dokterD' id='dokterD' style="width: 200px;" onkeydown="
                        if (event.keyCode == 123){
                            saveTindakan();
                            return false;
                        }
                                    ">
                                <option value=''>[Pilih Dokter]</option>
                                <?
                                if ($_SESSION['level'] == "24" ||
                                        $_SESSION['level'] == "25" ||
                                        $_SESSION['level'] == "26" ||
                                        $_SESSION['level'] == "27" ||
                                        $_SESSION['level'] == "28" ||
                                        $_SESSION['level'] == "29" ||
                                        $_SESSION['level'] == "30" ||
                                        $_SESSION['level'] == "31" ||
                                        $_SESSION['level'] == "32" ||
                                        $_SESSION['level'] == "33" ||
                                        $_SESSION['level'] == "44" ||
                                        $_SESSION['level'] == "20"
                                ) {
                                    $query = "select * from rm_dokter where id_jenis_dokter!='3'";
                                } else {
                                    $query = "SELECT a.id_dokter, b.nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE b.id_dokter=a.id_dokter order by b.nama_dokter";
                                }

                                $result = $fungsi->runQuery($query);
                                $dokter_id = $fungsi->getIdDokterDaftar($_GET['fid']);
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    if ($dt['id_dokter'] == $dokter_id) {
                                        $selected = "true";
                                    } else {
                                        $selected = "false";
                                    }
                                    echo "<option value=\"$dt[id_dokter]\" selected=\"$selected\">$dt[nama_dokter]</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <div id="penyakitPrimer"></div>
                            <input type="hidden" id="penyakitPrimerId" name="penyakitPrimerId" size="1"/>

                        </td>
                        <td>
                            <div id="penyakitSekunder"></div>
                            <input type="hidden" id="penyakitSekunderId" name="penyakitSekunderId" size="1"/>

                        </td>
                    </tr>
                </table>
            </div>
            <table id="dataDiagnosa" width='100%'></table>
            <div style="background-color: #fcfcfc;">
                <div id="grpDtlDiagnosa" class="easyui-accordion" style="width:920px;height:165px;">
                    <div title="Detail Diagnosa" style="overflow:auto;padding:1px;">
                        <form id="frmDetailDiagnosa">
                            <input id="id_detail_diagnosa" name="id_detail_diagnosa" type="hidden" value="" size="5">
                            <table class='data' width="100%">
                                <tr height='25'>
                                    <td>
                                        <label>Diagnosa Lain</label>
                                    </td>
                                    <td>
                                        <textarea name="diagnosa_lain" id="diagnosa_lain" cols="20" rows='1'></textarea>
                                    </td>
                                    <td>
                                        <label>Keluhan Utama, Anamnesa</label>
                                    </td>
                                    <td>
                                        <textarea name="keluhan_lain" id="keluhan_lain" cols="20" rows='1'></textarea>
                                    </td>
                                    <td>
                                        <label>Terapi</label>
                                    </td>
                                    <td>
                                        <textarea name="terapi" id="terapi" cols="20" rows='1'></textarea>
                                    </td>
                                </tr>
                                <tr height='25'>
                                    <td>
                                        <label>Tinggi Badan</label>
                                    </td>
                                    <td>
                                        <input name="tinggi_badan" id="tinggi_badan" size="5">
                                    </td>
                                    <td>
                                        <label>Berat Badan</label>
                                    </td>
                                    <td>
                                        <input name="berat_badan" id="berat_badan" size="5">
                                    </td>
                                    <td rowspan="2">
                                        <label>Hasil Pemeriksaan</label>
                                    </td>
                                    <td rowspan="2">
                                        <textarea name="hasil_pemeriksaan" id="hasil_pemeriksaan" cols="20" rows='1'></textarea>
                                    </td>
                                </tr>
                                <tr height='25'>
                                    <td>
                                        <label>Nadi</label>
                                    </td>
                                    <td>
                                        <input name="nadi" id="nadi" size="5" />
                                    </td>
                                    <td>
                                        <label>Tekanan Darah</label>
                                    </td>
                                    <td>
                                        <input name="tensi" id="tensi" size="5" />
                                    </td>
                                </tr>
                                <tr height='25'>
                                    <td>
                                        <label>Temperatur</label>
                                    </td>
                                    <td>
                                        <input name="temperatur" id="temperatur" size="5" />
                                    </td>
                                    <td>
                                        <label>Nafas</label>
                                    </td>
                                    <td>
                                        <input name="nafas" id="nafas" size="5" onkeydown=" if(event.keyCode == 13){simpanDetailDiagnosa()}" />
                                    </td>
                                    <td colspan="2">
                                        <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanDetailDiagnosa()" plain="true"></a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
