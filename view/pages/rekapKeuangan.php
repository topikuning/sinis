<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:190px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Tanggal</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value="<?echo "01-".date('m-Y');?>"/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%'>
                        <input id="endDate" name="endDate" class="easyui-datebox" value="<?echo date('d-m-Y');?>"/>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Ruang</td>
                    <td width='30%'>
                        <select id="ruang" name="ruang">
                        <option value=''>[Pilih Ruang]</option>
                        <?
                            $query  = "SELECT * FROM rm_ruang WHERE del_flag='' and id_tipe_ruang not in ('10', '11', '12')";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_ruang]\" >$dt[ruang]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Tipe Pasien</td>
                    <td width='30%'>
                        <select id="tipe_pasien" name="tipe_pasien">
                        <option value=''>[Pilih Tipe Pasien]</option>
                        <?
                            $query  = "SELECT * FROM rm_tipe_pasien WHERE del_flag=''";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_tipe_pasien]\" >$dt[tipe_pasien]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Dokter</td>
                    <td width='30%'>
                        <select id="dokter" name="dokter">
                        <option value=''>[Pilih Dokter]</option>
                        <?
                            $query  = "SELECT * FROM rm_dokter WHERE del_flag=''";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_dokter]\" >$dt[nama_dokter]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Kelas</td>
                    <td width='30%'>
                        <select id="kelas" name="kelas">
                        <option value=''>[Pilih Kelas]</option>
                        <?
                            $query  = "SELECT * FROM rm_kelas WHERE del_flag=''";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_kelas]\" >$dt[kelas]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe Perawatan</td>
                    <td width='30%'>
                        <select id="tipe_perawatan" name="tipe_perawatan">
                            <option value=''>[Pilih Tipe Perawatan]</option>
                            <option value='1'>Rawat Jalan</option>
                            <option value='2'>Rawat Inap</option>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='2%'>&nbsp;</td>
                    <td width='2%'>&nbsp;</td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe</td>
                    <td width='30%' colspan='4'>
                        <input type="radio" name="tipeLaporan" id="hariRawat" value="1" checked>
                        <label>Hari Rawat</label>
                        <input type="radio" name="tipeLaporan" id="ibs" value="1">
                        <label>IBS</label>
                        <input type="radio" name="tipeLaporan" id="tindakanRuang" value="1">
                        <label>Tindakan Ruang</label>
                        <input type="radio" name="tipeLaporan" id="radiologi" value="1">
                        <label>Radiologi</label>
                        <input type="radio" name="tipeLaporan" id="laborat" value="1">
                        <label>Laborat</label>
                        <input type="radio" name="tipeLaporan" id="fasilitas" value="1">
                        <label>Fasilitas</label>
                        <input type="radio" name="tipeLaporan" id="visit" value="1">
                        <label>Visit</label>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getRekapKeuangan()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja()" plain="true">Cetak</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <span id="detailLaporan" width='100%' />
    </div>
</div>
