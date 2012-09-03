<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:110px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr>
                    <td width='10%'>Nomor RM</td>
                    <td width='4%'>:</td>
                    <td width='10%'>
                        <select name='id_pasien' id='id_pasien' onkeydown="
                            if (event.keyCode == 119) {
                                tindakan.focus();
                            } else if (event.keyCode == 120) {
                                tindakanF.focus();
                            } else if (event.keyCode == 13) {
                                loadDiagnosaPasien();
                            } else if (event.keyCode == 112){
                                openWinSearchPasien();
                                return false;
                            }">
                            <option value=''>[Pilih Pasien]</option>
                            <?
                            $query = "SELECT a.id_pasien, b.nama_pasien FROM rm_pendaftaran a, rm_pasien b WHERE id_ruang='" . $_SESSION['level'] . "' AND status_pendaftaran IN (0,1) AND a.id_pasien = b.id_pasien order by id_pendaftaran, id_pasien";
                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_pasien]\">$dt[id_pasien] | $dt[nama_pasien]</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width="2%">&nbsp;</td>
                    <td width='10%'>Nama Px </td>
                    <td width='2%'>:</td>
                    <td width='12%'><input id="pasien" name="pasien" size="30" type="text" value="" ></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='10%'>Usia</td>
                    <td width='4%'>:</td>
                    <td width='10%'><input id="usia" name="usia" type="text" size="16" value="" readonly></input></td>
                </tr>

                <tr>
                    <td width='10%'>Jenis Kelamin</td>
                    <td width='4%'>:</td>
                    <td width='10%'><input id="jns_kelamin" name="jns_kelamin" type="text" size="10" value="" readonly></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='10%'>Jenis Pasien</td>
                    <td width='4%'>:</td>
                    <td width='10%'><input id="jns_pasien" name="jns_pasien" type="text" value="" readonly></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='10%'>Kelas</td>
                    <td width='4%'>:</td>
                    <td width='10%'><input id="kls_rwt" name="kls_rwt" type="text" value="" readonly></input></td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" plain="true">Cari</a>
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
                $_SESSION['level'] == "44"
        ) {
            echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToDiet()" plain="true">Diet Pasien</a>';
            echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToVisitDokter()" plain="true">Visit Dokter</a>';
        }
        if ($_SESSION['level'] == "22") {
            echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToTindakanMedis()" plain="true">Tindakan</a>';
        }
        ?>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToDiagnosa()" plain="true">Diagnosa</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToRM()" plain="true">Rekam Medis Pasien</a>
        <?
        if ($_SESSION['level'] == "22") {
            echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="simpanClosePerawatanMedis()" plain="true">Close Perawatan</a>';
        } else {
            echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Close Perawatan</a>';
        }
        ?>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <div style="background-color: #fcfcfc;">
            <table id="inTindakan" onkeydown="if (event.keyCode == 34){
                tindakanF.focus();
                return false;
            }">
                <tr style="font-family: verdana; font-size: 11; font-weight: bold;" align="center">
                    <td><input id="id_tindakan_ruang" name="id_tindakan_ruang" type="hidden" value="" size="5"> <B>TINDAKAN</td>
                    <td>CITO</td>
                    <td>TANGGAL</td>
                    <td>DOKTER</td>
                    <td>OPERATOR</td>
                    <td>KETERANGAN</td>
                    <td>TARIF</td>
                    <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveTindakan()" plain="true"></a></td>
                </tr>
                <tr>
                    <td>
                        <div class="ausu-suggest">
                            <input type="text" id="tindakan" name="tindakan" size="30" onkeydown="
                            if (event.keyCode == 123){
                                saveTindakan();
                                return false;
                            } else if (event.keyCode == 112){
                                openWinSearch('1');
                                return false;
                            }"/>
                            <input type="hidden" id="tindakanId" name="tindakanId" size="1"/>
                        </div>
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
                        <input id="tglInput" value="<? echo date('d-m-Y'); ?>" name="tglInput" size="9"></input>
                    </td>
                    <td>
                        <select class="easyui-combobox" name='dokter' id='dokter' style="width: 200px;" onkeydown="
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
                        <select name='operator' id='operator' onkeydown="
                        if (event.keyCode == 123){
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
                    <td>
                        <input id="advice" name="advice" type="text" value="" size="20" onkeydown="
                        if (event.keyCode == 123){
                            saveTindakan();
                            return false;
                        }
                               ">
                    </td>
                    <td>
                        <input id="id_tarif" name="id_tarif" type="hidden" value="">
                        <input id="tarif" name="tarif" type="text" value="" readonly onkeydown="
                        if (event.keyCode == 123){
                            saveTindakan();
                            return false;
                        }
                               ">
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
                <tr align="center" style="font-family: verdana; font-size: 11; font-weight: bold;">
                    <td><input id="id_fasilitas_ruang" name="id_fasilitas_ruang" type="hidden" value="" size="1">FASILITAS</td>
                    <td>JUMLAH</td>
                    <td>DOKTER</td>
                    <td>KETERANGAN</td>
                    <td>TARIF</td>
                    <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveFasilitas()" plain="true"></a></td>
                </tr>
                <tr>
                    <td>
                        <div class="ausu-suggestF">
                            <input type="text" id="tindakanF" name="tindakanF" size="30" onkeydown="
                        if (event.keyCode == 123){
                            saveFasilitas();
                            return false;
                        } else if (event.keyCode == 112){
                            openWinSearch('2');
                            return false;
                        }"/>
                            <input type="hidden" id="tindakanFId" name="tindakanFId" size="1" />
                        </div>
                    </td>
                    <td>
                        <input type="text" id="jumlah" name="jumlah" size="5" onkeydown="
                    if (event.keyCode == 123){
                        saveFasilitas();
                        return false;
                    }
                               "/>
                    </td>
                    <td>
                        <select name='dokterF' id='dokterF' class="easyui-combobox" style="width: 200px;" onkeydown="
                    if (event.keyCode == 123){
                        saveFasilitas();
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
                        <input id="adviceF" name="adviceF" type="text" value="" size="40" onkeydown="
                    if (event.keyCode == 123){
                        saveFasilitas();
                        return false;
                    }
                               ">
                    </td>
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
        <div style="background-color: #fcfcfc;">
            <table id="inBahan">
                <tr style="font-family: verdana; font-size: 11; font-weight: bold;" align="center">
                    <td><input id="id_barang_tindakan" name="id_barang_tindakan" type="hidden" value="" size="40">NAMA BARANG</td>
                    <td>JUMLAH</td>
                    <td>STOCK</td>
                    <td>SATUAN</td>
                    <td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveBahan()" plain="true"></a></td>
                </tr>
                <tr>
                    <td>
                        <div class="prediksi">
                            <input type="text" id="bahan" name="bahan" size="30" onkeydown="
                        if (event.keyCode == 123){
                            saveBahan();
                            return false;
                        }"/>
                            <input type="hidden" id="bahanId" name="bahanId" size="1" />
                        </div>
                    </td>
                    <td>
                        <input type="text" id="jumlahBarang" name="jumlahBarang" onkeydown="
                    if (event.keyCode == 123){
                        saveBahan();
                        return false;
                    }
                               "/>
                    </td>
                    <td>
                        <input type="text" id="stock" name="stock" size="5" readonly />
                    </td>
                    <td>
                        <input type="text" id="satuan" name="satuan" size="5" readonly />
                    </td>
                </tr>
            </table>
        </div>
        <table id="dataBahan" width='100%'></table>
    </div>
</div>
<div id="winSearch" class="easyui-window" title="Tindakan" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:475px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
            <form id="frmSrcTindakan" >
                <input id="idTindakan" name="idTindakan" type="hidden" value="" disabled></input>
                <table class='data' width="100%">
                    <tr height="25">
                        <td width='19%'>Tindakan</td>
                        <td width='30%'><input id="srcTindakan" name="srcTindakan" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataListTindakan();
                        return false;
                    } else if (event.keyCode == 27){
                        $('#winSearch').window('close');
                        tindakan.focus();
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
