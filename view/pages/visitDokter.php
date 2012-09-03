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
							dokter.focus();
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
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToTindakan(<?echo $_SESSION['level'];?>)" plain="true">Tindakan</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToDiet()" plain="true">Diet Pasien</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="goToRM()" plain="true">Rekam Medis Pasien</a>
        <a class="easyui-linkbutton" iconCls="icon-openrm" href="javascript:void(0)" onclick="openClosePerawatan()" plain="true">Close Perawatan</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
		<div style="background-color: #fcfcfc;">
		<table id="frmVisitDOkter">
		<tr style="font-family: verdana; font-size: 11; font-weight: bold;" align="center">
		<td><input type="hidden" value="" name="visit" id="visit" size="5" disabled/>DOKTER</td>
		<td>TANGGAL</td>
		<td>TARIF</td>
		<td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="simpanVisitDokter()" plain="true"></a></td>
		</tr>
		<tr>
                <td>
                    <select id="dokter" name="dokter" onkeydown="
                        if (event.keyCode == 123) {
							simpanVisitDokter();
                        }">
                    <option value=''>[Pilih Dokter]</option>
                    <?
                        $query  = "SELECT * FROM rm_dokter WHERE del_flag<>'1' and id_jenis_dokter!='3'";

                        $result = $fungsi->runQuery($query);
                        while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                            echo "<option value=\"$dt[id_dokter]\" >$dt[nama_dokter]</option>";					
                        }
                    ?>
                    </select>
                </td>
				<td>
                    <input id="tglVisite" name="tglVisite" class="easyui-datebox" value="<?echo date('d-m-Y');?>"/>
                </td>
				<td>
					<input id="tarifVisite" name="tarifVisite" readonly />
				</td>
		</tr>
		</table>
		</div>
        <table id="dataVisit" width='100%'></table>
    </div>
</div>