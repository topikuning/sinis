<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;">
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
                        }
                                           ">
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
        <a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="openWinDiagnosa()" plain="true">Entry Diagnosa</a>
        <?
            if($_SESSION['level']=="24" ||
               $_SESSION['level']=="25" ||
               $_SESSION['level']=="26" ||
               $_SESSION['level']=="27" ||
               $_SESSION['level']=="28" ||
               $_SESSION['level']=="29" ||
               $_SESSION['level']=="30" ||
               $_SESSION['level']=="31" ||
               $_SESSION['level']=="32" ||
               $_SESSION['level']=="33" ||
               $_SESSION['level']=="44"
              ){
                echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToDiet()" plain="true">Diet Pasien</a>';
                echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToVisitDokter()" plain="true">Visit Dokter</a>';
            } else if($_SESSION['level']=="20"){
                echo '<a class="easyui-linkbutton" iconCls="icon-add" href="javascript:void(0)" onclick="goToSurvey()" plain="true">Entry Survey</a>';
                echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToPeriksaDokter()" plain="true">Pemeriksaan Dokter</a>';
            }
        ?>
<!--        <?
            if($_SESSION['level']=="22"){
                echo '<a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToTindakanKeperawatan()" plain="true">Tindakan Keperawatan</a>';
            }
        ?>-->
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToTindakan(<?echo $_SESSION['level'];?>)" plain="true">Tindakan</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToRM()" plain="true">Rekam Medis Pasien</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Close Perawatan</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataDiagnosa" width='100%'></table>
        <table id="dataDetailDiagnosa" width='100%'></table>
    </div>
</div>
<div id="winDiagnosa" class="easyui-window" title="Form Diagnosa" draggable="true" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:620px;height:460px;background: #fafafa;" onkeydown="if (event.keyCode == 27){$('#winDiagnosa').window('close')}">
		<table class='data' width="100%">
            <tr height="25">
                <td colspan="2">
                    <form name='frmDiagnosa' id='frmDiagnosa' onkeydown="
                                        if (event.keyCode == 27){
											$('#winDiagnosa').window('close')
                                            return false;										
										}">
                        <input id="id_diagnosa" name="id_diagnosa" type="hidden" value="" size="5">
                        <table class='data' width="100%">
                            <tr height="25">
                                <td width='40%'>Dokter</td>
                                <td width='60%'>
                                    <select name='dokter' id='dokter' onkeydown="
                                        if (event.keyCode == 123){
                                            saveDiagnosa();
                                            return false;
                                        }
                                    ">
                                    <option value=''>[Pilih Dokter]</option>
                                    <?
                                        if($_SESSION['level']=="24" ||
                                           $_SESSION['level']=="25" ||
                                           $_SESSION['level']=="26" ||
                                           $_SESSION['level']=="27" ||
                                           $_SESSION['level']=="28" ||
                                           $_SESSION['level']=="29" ||
                                           $_SESSION['level']=="30" ||
                                           $_SESSION['level']=="31" ||
                                           $_SESSION['level']=="32" ||
                                           $_SESSION['level']=="33" ||
                                           $_SESSION['level']=="44" ||
                                           $_SESSION['level']=="20"
                                          ){
                                            $query = "select * from rm_dokter where id_jenis_dokter!='3'";
                                        } else {
                                            $query  = "SELECT a.id_dokter, b.nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE b.id_dokter=a.id_dokter order by b.nama_dokter";
                                        }

                                        $result = $fungsi->runQuery($query);
                                        $dokter_id = $fungsi->getIdDokterDaftar($_GET['fid']);
                                        while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                                        {
                                            if($dt['id_dokter']==$dokter_id) {
                                                $selected = "true";
                                            } else {
                                                $selected = "false";
                                            }
                                            echo "<option value=\"$dt[id_dokter]\" selected=\"$selected\">$dt[nama_dokter]</option>";					
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <tr height="25">
                                <td width='40%'>Diagnosa Primer</td>
                                <td width='60%'>
                                    <div class="ausu-suggest">
                                        <input type="text" id="penyakitPrimer" name="penyakitPrimer" size="40" onkeydown="
                                        if (event.keyCode == 123){
                                            saveDiagnosa();
                                            return false;
                                        } else if (event.keyCode == 112){
                                            openWinSearch('primer');
                                            return false;
                                        }"/>
                                        <input type="hidden" id="penyakitPrimerId" name="penyakitPrimerId" size="1" />
                                    </div>
                                </td>
                            </tr>
                            <tr height="25">
                                <td width='40%'>Diagnosa Sekunder</td>
                                <td width='60%'>
                                    <div class="ausu-suggest">
                                        <input type="text" id="penyakitSekunder" name="penyakitSekunder" size="40" onkeydown="
                                        if (event.keyCode == 123){
                                            saveDiagnosa();
                                            return false;
                                        } else if (event.keyCode == 112){
                                            openWinSearch('sekunder');
                                            return false;
                                        }"/>
                                        <input type="hidden" id="penyakitSekunderId" name="penyakitSekunderId" size="1" />
                                    </div>
                                </td>
                            </tr>
                            <tr height="25">
                                <td width='40%' colspan="2" align="right">
                                    <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveDiagnosa()" plain="true">Simpan</a>
                                    <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winDiagnosa').window('close')" plain="true">Close</a>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
            <tr height="25">
                <td colspan="2">
                    <div id="grpDtlDiagnosa" class="easyui-accordion" style="width:600px;height:250px;">
                        <div title="Detail Diagnosa" style="overflow:auto;padding:1px;">
                            <form id='frmDetailDiagnosa' onkeydown="
                                        if (event.keyCode == 123){
                                            simpanDetailDiagnosa();
                                            return false;
                                        } else if (event.keyCode == 27){
											$('#winDiagnosa').window('close')
                                            return false;										
										}">
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
                                    </tr>
                                    <tr height='25'>
                                        <td>
                                            <label>Nadi</label>
                                        </td>
                                        <td>
                                            <input name="nadi" id="nadi" size="5">
                                        </td>
                                        <td>
                                            <label>Tekanan Darah</label>
                                        </td>
                                        <td>
                                            <input name="tensi" id="tensi" size="5">
                                        </td>
                                    </tr>
                                    <tr height='25'>
                                        <td>
                                            <label>Temperatur</label>
                                        </td>
                                        <td>
                                            <input name="temperatur" id="temperatur" size="5">
                                        </td>
                                        <td>
                                            <label>Nafas</label>
                                        </td>
                                        <td>
                                            <input name="nafas" id="nafas" size="5">
                                        </td>
                                    </tr>
                                    <tr height='25'>
                                        <td>
                                            <label>Hasil Pemeriksaan</label>
                                        </td>
                                        <td>
                                            <textarea name="hasil_pemeriksaan" id="hasil_pemeriksaan" cols="20" rows='1'></textarea>
                                        </td>
                                        <td>
                                            <label>Terapi</label>
                                        </td>
                                        <td>
                                            <textarea name="terapi" id="terapi" cols="20" rows='1'></textarea>
                                        </td>
                                    </tr>
                                    <!-- <tr height='25'>
                                        <td>
                                            <label>Konsultasi</label>
                                        </td>
                                        <td>
                                            <select name="jKonsultasi" id="jKonsultasi" />
                                                <option value=''>[Pilih Jenis Konsul]</option>
                                                <option value="Dari">Dari</option>
                                                <option value="Ke">Ke</option>
                                            </select>
                                        </td>
                                        <td colspan='2'>
                                            <select name="ruangKonsul" id="ruangKonsul">
                                                <option value=''>[Pilih Ruang]</option>
                                                <?
                                                $query = "SELECT id_ruang,ruang FROM rm_ruang WHERE del_flag<>'1' and id_tipe_ruang in (select distinct(id_tipe_ruang) from rm_ruang_pendaftaran where del_flag<>'1') order by ruang";

                                                $result = $fungsi->runQuery($query);
                                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                    echo "<option value=\"$dt[id_ruang]\">$dt[ruang]</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr> -->
                                </table>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanDetailDiagnosa()" plain="true">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winDiagnosa').window('close')" plain="true">Close</a>
        </div>
<!--    </form>-->
</div>
<div id="winSearch" class="easyui-window" title="Diagnosa" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:600px;height:475px;background: #fafafa;">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:80px;">
            <form id="frmSrcDiagnosa">
                <table class='data' width="100%">
                    <tr height="25">
                        <td width='19%'>Diagnosa</td>
                        <td width='30%'><input id="srcDiagnosa" name="srcDiagnosa" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataListDiagnosa();
                        }
                    " ></input></td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>ICD</td>
                        <td width='30%'>
                            <input id="idDiagnosa" name="idDiagnosa" type="hidden" value="" disabled></input>
                            <input id="srcICD" name="srcICD" type="text" value=""></input>
                        </td>
                    </tr>
                </table>
            </form>
            <a class="easyui-linkbutton" id="srcDiagnosaSearch" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataDiagnosa()" plain="true">Cari</a>
        </div>
        <div region="center" border="false" style="background:#99FF99;padding:5px">
            <table id="dataListDiagnosa" width='100%'></table>
        </div>
    </div>
</div>