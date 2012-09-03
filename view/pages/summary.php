<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:140px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'><input id="id_pasien" name="id_pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loadDataPasien();
                        }
                                           " onkeyup='IsNumeric(id_pasien)'></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                        if (event.keyCode == 13){
                            loaddataDiagnosa();
                        }
                                           " ></input></td>
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
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataDiagnosa()" plain="true">Cari</a>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-print" href="javascript:void(0)" onclick="cetakSummary()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <form id="frmSummary" name="frmSummary">
            <input id="id_summary" name="id_summary" type="hidden" value="" disabled></input>
            <input id="id_diag" name="id_diag" value="" type="hidden" disabled></input>
            <input id="id_det" name="id_det" value="" type="hidden" disabled></input>
            <table width="100%" class="data" border="1" style="border-collapse: collapse;">
                <tr>
                    <td>
                        <label>Dokter</label>
                    </td>
                    <td colspan="3">
                        <select name='dokter' id='dokter'>
                            <option value=''>[Pilih Dokter]</option>
                            <?
                            $query = "select * from rm_dokter where id_jenis_dokter!='3'";

                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_dokter]\">$dt[nama_dokter]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Diagnosis Akhir</label>
                    </td>
                    <td colspan="3">
                        <div class="ausu-suggest">
                            <input type="text" id="penyakitPrimer" name="penyakitPrimer" size="40"/>
                            <input id="penyakitPrimerId" name="penyakitPrimerId" size="1" disabled readonly hidden />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Keluhan Utama</label>
                    </td>
                    <td colspan="3">
                        <textarea id="keluhan" name="keluhan" cols="60" rows="1" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Lama Penyakit</label>
                    </td>
                    <td colspan="3">
                        <textarea id="lama" name="lama" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Penyakit Terdahulu</label>
                    </td>
                    <td colspan="3">
                        <textarea id="penyakitLama" name="penyakitLama" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Obat Terakhir</label>
                    </td>
                    <td colspan="3">
                        <textarea id="obtAkhir" name="obtAkhir" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Faktor Etiologi</label>
                    </td>
                    <td colspan="3">
                        <textarea id="etiologi" name="etiologi"  cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Tinggi Badan</label>
                    </td>
                    <td>
                        <input name="tinggi_badan" id="tinggi_badan" size="10">
                    </td>
                    <td>
                        <label>Berat Badan</label>
                    </td>
                    <td>
                        <input name="berat_badan" id="berat_badan" size="10">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Nadi</label>
                    </td>
                    <td>
                        <input name="nadi" id="nadi" size="10">
                    </td>
                    <td>
                        <label>Tekanan Darah</label>
                    </td>
                    <td>
                        <input name="tekanan_darah" id="tekanan_darah" size="10">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Temperatur</label>
                    </td>
                    <td>
                        <input name="temperatur" id="temperatur" size="10">
                    </td>
                    <td>
                        <label>Nafas</label>
                    </td>
                    <td>
                        <input name="nafas" id="nafas" size="10">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Hasil Lab</label>
                    </td>
                    <td colspan="3">
                        <textarea name="hasilLab" id="hasilLab" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Radiologi</label>
                    </td>
                    <td colspan="3">
                        <textarea name="hasilRad" id="hasilRad" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Diagnosis PA</label>
                    </td>
                    <td colspan="3">
                        <textarea name="diagPa" id="diagPa" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Masalah Yang Dihadapi</label>
                    </td>
                    <td colspan="3">
                        <textarea name="masalah" id="masalah" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Konsultasi</label>
                    </td>
                    <td colspan="3">
                        <textarea name="konsul" id="konsul" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Pengobatan/Tindakan</label>
                    </td>
                    <td colspan="3">
                        <textarea name="tindakan" id="tindakan" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Fasilitas</label>
                    </td>
                    <td colspan="3">
                        <textarea name="fasilitas" id="fasilitas" cols="60" rows="1"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Perjalanan Penyakit</label>
                    </td>
                    <td colspan="3">
                        <textarea name="perjalanan" id="perjalanan" cols="60" rows="1"></textarea>
                    </td>
                </tr>
<!--                <tr>
                    <td>
                                                <label>Keadaan Waktu Keluar RS</label>
                                            </td>
                                            <td colspan="3">
                                                <select name="keadaan" id="keadaan">
                                                <option value=''>[Pilih Keadaan]</option>
                        <?
                        $query = "select * from rm_keadaan";

                        $result = $fungsi->runQuery($query);
                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo "<option value=\"$dt[id_keadaan]\">$dt[keadaan]</option>";
                        }
                        ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Prognosis</label>
                                            </td>
                                            <td colspan="3">
                                                <textarea name="progno" id="progno" cols="60" rows="1"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Sebab Meninggal</label>
                                            </td>
                                            <td colspan="3">
                                                <select name="sebabMati" id="sebabMati">
                                                <option value=''>[Pilih Sebab Meninggal]</option>
<?
$query = "select * from rm_sebab_mati";

$result = $fungsi->runQuery($query);
while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<option value=\"$dt[id_sebab_mati]\">$dt[sebab_mati]</option>";
}
?>
                                                </select>
                                            </td>
                                        </tr> -->
                <tr>
                    <td>
                        <label>Usul Tindak Lanjut</label>
                    </td>
                    <td colspan="3">
                        <textarea name="usul" id="usul" cols="60" rows="1"></textarea>
                    </td>
                </tr>
            </table>
        </form>
        <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
            <a class="easyui-linkbutton" iconCls="icon-save" href="javascript:void(0)" onclick="simpanSummary()" plain="true">Simpan</a>
        </div>
    </div>
</div>
