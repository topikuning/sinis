<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:102px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr>
                    <td width='10%'>Nomor RM</td>
                    <td width='4%'>:</td>
                    <td width='10%'>
                        <select name='id_pasien' id='id_pasien' onkeydown="
                            if (event.keyCode == 119) {
                                diet.focus();
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
                    <td width='10%'>Nama Px</td>
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
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataDiagnosa()" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToDiagnosa()" plain="true">Diagnosa</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToTindakan(<? echo $_SESSION['level']; ?>)" plain="true">Tindakan</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToVisitDokter()" plain="true">Visit Dokter</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToRM()" plain="true">Rekam Medis Pasien</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Close Perawatan</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
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
</div>