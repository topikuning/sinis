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
                    }" onkeyup='IsNumeric(no_rm_pasien)'></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Tanggal Pendaftaran</td>
                <td width='30%'><input id="startDate" name="startDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }" ></input></td>
            </tr>
            <tr height="25">
                <td width='19%'>Nama Pasien</td>
                <td width='30%'><input id="pasien" name="pasien" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
                <td width='2%'>&nbsp;</td>
                <td width='19%'>Sampai</td>
                <td width='30%'><input id="endDate" name="endDate" class="easyui-datebox" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadDataPendaftaran();
                    }
                                       " ></input></td>
            </tr>
        </table>
        <a class="easyui-linkbutton" id="cari-pendaftaran" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataPendaftaran()" plain="true">Cari</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <table id="dataPendaftaran" width='100%'></table>
    </div>
</div>
<div id="winLayanan" class="easyui-window" title="Form Layanan Medis" draggable="false" resizable="true" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:930px;height:400px;background: #fafafa;" onkeydown="if(event.keyCode==27){closeWinDiet()}">
    <div class="easyui-layout" fit="true" style="background:#ccc;">
        <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 1px;">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='10%'>Nomor RM</td>
                    <td width='20%'><input id="rm_pas" name="no_rm_pasien" type="text"></input>
                        <input id="idp" name="idp" type="text" hidden readonly disabled></input></td>
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
        </div>
    </div>
</div>