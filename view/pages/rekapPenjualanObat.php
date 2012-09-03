<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:160px;">
        <form id="frmDtlPasien">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Obat</td>
                    <td width='30%'>
                        <div class="ausu-suggest">
                            <input type="text" id="nama_obatJ" name="nama_obatJ" value='' size='50'></input>
                            <input type="hidden" id="nama_obatJId" name="nama_obatJId" value=''></input>
                        </div>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Sampai</td>
                    <td width='30%'>
                        <div class="ausu-suggest">
                            <input type="text" id="nama_obatSJ" name="nama_obatSJ" value='' size='50'></input>
                            <input type="hidden" id="nama_obatSJId" name="nama_obatSJId" value=''></input>
                        </div>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tanggal</td>
                    <td width='30%'>
                        <input id="startDate" name="startDate" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>"/>
                        <input id="endDate" name="endDate" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>"/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='2%'>Ruang</td>
                    <td width='2%'>
                        <select id="ruang" name="ruang">
                            <?
                            if ($_SESSION['level'] != '36' && $_SESSION['level'] != '46' && $_SESSION['level'] != '47' && $_SESSION['level'] != '50') {
                                echo "<option value='36' >Apotek Depan</option>
                                    <option value='46' >Apotek Belakang</option>
                                    <option value='47' >Apotek VIP</option>
                                    <option value='50' >Apotek IBS</option>";
                            } else if ($_SESSION['level'] == '36') {
                                echo "<option value='36' >Apotek Depan</option>";
                            } else if ($_SESSION['level'] == '46') {
                                echo "<option value='46' >Apotek Belakang</option>";
                            } else if ($_SESSION['level'] == '47') {
                                echo "<option value='47' >Apotek VIP</option>";
                            } else if ($_SESSION['level'] == '50') {
                                echo "<option value='50' >Apotek IBS</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Jam</td>
                    <td width='30%'>
<!--                        <select id="shift" name="shift">
                            <option value=''>[Pilih Shift]</option>
                            <option value='P'>Pagi</option>
                            <option value='S'>Siang</option>
                            <option value='M'>Malam</option>
                        </select>-->
                        <input id="startHour" name="startHour" value="<?php echo date('H:i:s'); ?>"/>
                        <input id="endHour" name="endHour" value="<?php echo date('H:i:s'); ?>"/>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Status</td>
                    <td width='30%'>
                        <select id="status" name="status">
                            <option value='3'>ALL</option>
			    <option value='2'>Tunai</option>
                            <option value='0'>Kredit</option>
                        </select>
                    </td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe</td>
                    <td width='30%' colspan='4'>
                        <input type="radio" name="tipeLaporan" id="perCustomer" value="1" checked>
                        <label>Per Customer</label>
                        <input type="radio" name="tipeLaporan" id="perBarang" value="1">
                        <label>Per Obat</label>
                        <input type="radio" name="tipeLaporan" id="perFaktur" value="1">
                        <label>Per Faktur/Nota</label>
                        <input type="radio" name="tipeLaporan" id="perBarangPasien" value="1">
                        <label>Per Obat/Pasien</label>
                        <input type="radio" name="tipeLaporan" id="perTanggal" value="1">
                        <label>Per Tanggal</label>
                        <input type="radio" name="tipeLaporan" id="perRuang" value="1">
                        <label>Per Ruang</label>
                        <input type="radio" name="tipeLaporan" id="perDokter" value="1">
                        <label>Per Dokter</label>
                        <input type="radio" name="tipeLaporan" id="perObat" value="1">
                        <label>Per Obat/Dokter</label>
                    </td>
                </tr>
            </table>
        </form>
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getRekapPenjualanObat()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja()" plain="true">Cetak</a>
        <a class="easyui-linkbutton"  iconCls="icon-save" href="javascript:void(0)" onclick="toExcel()" plain="true">Excel</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <span id="detailLaporan" width='100%' />
    </div>
</div>
