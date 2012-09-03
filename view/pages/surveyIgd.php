<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:140px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%" >
                <tr height="25">
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'><input id="id_pasien" name="id_pasien" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataPasien();
                        }" onkeyup='IsNumeric(id_pasien)'/>
                        <input id="idd" name="idd" value="" disabled readonly type="hidden" /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'><input id="pasien" name="pasien" value="" onkeydown="
                        if (event.keyCode == 13){
                            loaddataDiagnosa();
                        }" /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Jenis Pasien</td>
                    <td width='30%'><input id="jns_pasien" name="jns_pasien" value="" disabled readonly /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'><input id="jns_kelamin" name="jns_kelamin" value="" disabled readonly /></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Usia</td>
                    <td width='30%'><input id="usia" name="usia" value="" disabled readonly /></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Survey Tanggal</td>
                    <td width='30%'>
                        <select id="surveyKe" name="surveyKe">
                            <option value="now">SEKARANG</option>
                            <?
                            $query = "SELECT a.id_pendaftaran as ids, date(b.tgl_pendaftaran) as tgl FROM rm_igd_survey a, rm_pendaftaran b WHERE 
                                      a.id_pasien=" . $_GET['pid'] . " AND a.del_flag<>1 AND a.id_pendaftaran = b.id_pendaftaran AND b.del_flag<>1";
                            $result = $fungsi->runQuery($query);
                            if (@mysql_num_rows($result) > 0) {
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    echo "<option value=".$dt[ids].">".$fungsi->formatDateDb($dt[tgl])."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataDiagnosa()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <form name="surveyIGD" id="surveyIGD">
            <table border="1" style="border-collapse: collapse;" width="100%" class="data">
                <tr>
                    <td colspan="100%" align="center">SURVEY</td><input type="hidden" id="id_survey" name="id_survey"></>
                </tr>
                <tr>
                    <td>
                        <label>Pekerjaan</label>
                    </td>
                    <td>
                        <input type="radio" id="pekerjaan1" name="pekerjaan" value="1"/>
                        <label>Pegawai Negeri</label>
                        <input type="radio" id="pekerjaan2" name="pekerjaan" value="2"/>
                        <label>Swasta</label>
                        <input type="radio" id="pekerjaan3" name="pekerjaan" value="3"/>
                        <label>Wiraswasta</label>
                        <input type="radio" id="pekerjaan4" name="pekerjaan" value="4"/>
                        <label>Petani</label>
                        <input type="radio" id="pekerjaan5" name="pekerjaan" value="5"/>
                        <label>Lain-Lain</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Triage <b>*</b></label>
                    </td>
                    <td>
                        <input type="radio" id="triage1" name="triage" value="1"/>
                        <label style="color: #3300CC;font-weight: bold">Biru</label>
                        <input type="radio" id="triage2" name="triage" value="2"/>
                        <label style="color: #00CC33;font-weight: bold">Hijau</label>
                        <input type="radio" id="triage3" name="triage" value="3"/>
                        <label style="color: #FFFF00;font-weight: bold">Kuning</label>
                        <input type="radio" id="triage4" name="triage" value="4"/>
                        <label style="color: #FF0000;font-weight: bold">Merah</label>
                        <input type="radio" id="triage5" name="triage" value="5"/>
                        <label style="font-weight: bold">Hitam</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Life Saving</label>
                    </td>
                    <td>
                        <input type="radio" id="saving1" name="saving" value="Berhasil"/>
                        <label>Berhasil</label>
                        <input type="radio" id="saving2" name="saving" value="Gagal"/>
                        <label>Gagal</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Transportasi</label>
                    </td>
                    <td>
                        <input type="radio" id="transportasi1" name="transportasi" value="1"/>
                        <label>Ambulance</label>
                        <input type="radio" id="transportasi2" name="transportasi" value="2"/>
                        <label>Pribadi</label>
                        <input type="radio" id="transportasi3" name="transportasi" value="3"/>
                        <label>Perusahaan</label>
                        <input type="radio" id="transportasi4" name="transportasi" value="4"/>
                        <label>Umum</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Jenis Transportasi</label>
                    </td>
                    <td>
                        <input type="radio" id="jTrans1" name="jTrans" value="1"/>
                        <label>Roda 2</label>
                        <input type="radio" id="jTrans2" name="jTrans" value="2"/>
                        <label>Roda 3</label>
                        <input type="radio" id="jTrans3" name="jTrans" value="3"/>
                        <label>Roda 4 / lebih</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Pengantar</label>
                    </td>
                    <td>
                        <input type="radio" id="pengantar1" name="pengantar" value="1"/>
                        <label>dr/Perawat/Bidan</label>
                        <input type="radio" id="pengantar2" name="pengantar" value="2"/>
                        <label>Keluarga</label>
                        <input type="radio" id="pengantar3" name="pengantar" value="3"/>
                        <label>Polisi</label>
                        <input type="radio" id="pengantar4" name="pengantar" value="4"/>
                        <label>Sendiri</label>
                        <input type="radio" id="pengantar5" name="pengantar" value="5"/>
                        <label>Lain</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Asuransi</label>
                    </td>
                    <td>
                        <input type="radio" id="asuransi1" name="asuransi" value="1"/>
                        <label>Ya</label>
                        <input type="radio" id="asuransi2" name="asuransi" value="2"/>
                        <label>Tidak</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Inform. Concern</label>
                    </td>
                    <td>
                        <input type="radio" id="inform1" name="inform" value="1"/>
                        <label>Tertulis</label>
                        <input type="radio" id="inform2" name="inform" value="2"/>
                        <label>Lesan</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Setuju IC</label>
                    </td>
                    <td>
                        <input type="radio" id="ic1" name="ic" value="1"/>
                        <label>Ya</label>
                        <input type="radio" id="ic2" name="ic" value="0"/>
                        <label>Tidak</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Kasus<b>*</b></label>
                    </td>
                    <td>
                        <input type="radio" id="kasus1" name="kasus" value="1"/>
                        <label>Medis</label>
                        <input type="radio" id="kasus2" name="kasus" value="2"/>
                        <label>Bedah</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Jenis Kasus<b>*</b></label>
                    </td>
                    <td>
                        <input type="radio" id="jKasus1" name="jKasus" value="1"/>
                        <label>Traffic Accident</label>
                        <input type="radio" id="jKasus2" name="jKasus" value="2"/>
                        <label>Home Accident</label>
                        <input type="radio" id="jKasus3" name="jKasus" value="3"/>
                        <label>Lainnya</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Emergency</label>
                    </td>
                    <td>
                        <input type="radio" id="emergency1" name="emergency" value="1"/>
                        <label>Medical Emergency</label>
                        <input type="radio" id="emergency2" name="emergency" value="2"/>
                        <label>Surgical Emergency</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>False/True</label>
                    </td>
                    <td>
                        <input type="radio" id="status1" name="status" value="0"/>
                        <label>False Emergency</label>
                        <input type="radio" id="status2" name="status" value="1"/>
                        <label>True Emergency</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="100%" align="center">Response Time</td>
                </tr>
                <tr>
                    <td>
                        <label>Datang</label>
                    </td>
                    <td>
                        <input type="text" id="jam_datang" name="jam_datang" value="<? echo date('d-m-Y'); ?>" class="easyui-datebox"/>
                        <input type="text" id="jam_d" name="jam_d" value="<?php echo date('H:i:s'); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Periksa</label>
                    </td>
                    <td>
                        <input type="text" id="jam_periksa" name="jam_periksa" value="<? echo date('d-m-Y'); ?>" class="easyui-datebox"/>
                        <input type="text" id="jam_p" name="jam_p" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Terapi</label>
                    </td>
                    <td>
                        <input type="text" id="jam_terapi" name="jam_terapi" value="<? echo date('d-m-Y'); ?>" class="easyui-datebox"/>
                        <input type="text" id="jam_t" name="jam_t" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Tindak Lanjut</label>
                    </td>
                    <td>
                        <input type="radio" id="lanjut1" name="lanjut" value="1"/>
                        <label>Pulang</label>
                        <input type="radio" id="lanjut2" name="lanjut" value="2"/>
                        <label>Pulang Paksa</label>
                        <input type="radio" id="lanjut3" name="lanjut" value="3"/>
                        <label>Referal</label>
                        <input type="radio" id="lanjut4" name="lanjut" value="7"/>
                        <label>MRS</label>
                        <input type="radio" id="lanjut5" name="lanjut" value="6"/>
                        <label>MD</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Waktu Tindak Lanjut</label>
                    </td>
                    <td>
                        <input type="text" id="jam_lanjut" name="jam_lanjut" value="<? echo date('d-m-Y'); ?>" class="easyui-datebox"/>
                        <input type="text" id="jam_l" name="jam_l" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Alergi</label>
                    </td>
                    <td>
                        <input type="radio" id="alergi1" name="alergi" value="1"/>
                        <label>Makanan</label>
                        <input type="radio" id="alergi2" name="alergi" value="2"/>
                        <label>Obat</label>
                        <input type="radio" id="alergi3" name="alergi" value="3"/>
                        <label>Cuaca</label>
                        <input type="radio" id="alergi4" name="alergi" value="4"/>
                        <label>Debu</label>
                        <input type="radio" id="alergi5" name="alergi" value="5"/>
                        <label>Lain</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Medikasi</label>
                    </td>
                    <td>
                        <input type="radio" id="medikasi1" name="medikasi" value="1"/>
                        <label>Ya</label>
                        <input type="radio" id="medikasi2" name="medikasi" value="0"/>
                        <label>Tidak</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Med. Teratur</label>
                    </td>
                    <td>
                        <input type="radio" id="teratur1" name="teratur" value="1"/>
                        <label>Ya</label>
                        <input type="radio" id="teratur2" name="teratur" value="0"/>
                        <label>Tidak</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>RPD</label>
                    </td>
                    <td>
                        <input type="radio" id="rpd1" name="rpd" value="1"/>
                        <label>HT</label>
                        <input type="radio" id="rpd2" name="rpd" value="2"/>
                        <label>DM</label>
                        <input type="radio" id="rpd3" name="rpd" value="3"/>
                        <label>Sesak</label>
                        <input type="radio" id="rpd4" name="rpd" value="4"/>
                        <label>Jantung</label>
                        <input type="radio" id="rpd5" name="rpd" value="5"/>
                        <label>Paru</label>
                        <input type="radio" id="rpd6" name="rpd" value="6"/>
                        <label>Ginjal</label>
                        <input type="radio" id="rpd7" name="rpd" value="7"/>
                        <label>Kanker</label>
                        <input type="radio" id="rpd8" name="rpd" value="8"/>
                        <label>Rematik</label>
                        <input type="radio" id="rpd9" name="rpd" value="9"/>
                        <label>Lain</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Amenorrhae</label>
                    </td>
                    <td>
                        <input type="radio" id="amenor1" name="amenor" value="1"/>
                        <label>Ya</label>
                        <input type="radio" id="amenor2" name="amenor" value="0"/>
                        <label>Tidak</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Kegiatan UGD<b>*</b></label>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>
                                    <select name="bagian" id="bagian">
                                        <option value=''>[Pilih Bagian]</option>
                                        <?
                                        $query = "SELECT * FROM rm_bagian WHERE del_flag<>'1'";

                                        $result = $fungsi->runQuery($query);
                                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                            echo "<option value=\"$dt[id_bagian]\" >$dt[bagian]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="peristiwa" id="peristiwa">
                                        <option value=''>[Pilih Peristiwa]</option>
                                        <?
                                        $query = "SELECT * FROM rm_peristiwa WHERE del_flag<>'1'";

                                        $result = $fungsi->runQuery($query);
                                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                            echo "<option value=\"$dt[id_peristiwa]\" >$dt[peristiwa]</option>";
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
            <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanSurvey()" plain="true">Simpan</a>
        </div>
    </div>
</div>
