<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:102px;">
    <form id="frmFilter">
            <table class='data' width="100%">
                <tr height="25">
                    <td width='19%'>Tanggal Awal</td>
                    <td width='30%'><input id="tgl_awal" class="easyui-datebox" name="tgl_awal" type="text" value="" onkeydown="
                    if (event.keyCode == 13){
                        loadBahan();
                    }
                " ></input></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Tanggal Akhir</td>
                    <td width='30%'><input id="tgl_akhir" name="tgl_akhir" type="text" value="" class="easyui-datebox"></input></td>
                </tr>
                <tr height="25">
                    <td width='19%'>Tipe Balance</td>
                    <td width='30%'>
                        <select name="tp_bal" id="tp_bal">
                        <option value=''>[Pilih Tipe Balance]</option>
                        <?
                            $query  = "SELECT * FROM rm_tipe_balance WHERE del_flag<>'1'";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_keperluan]\" >$dt[keperluan]</option>";					
                            }
                        ?>
                        </select>
                    </td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
            </table>
        </form>
		<a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="loadBahan()" plain="true">Cari</a>
	</div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
	<div style="background-color: #fcfcfc;">
	<table id="inBahan" width="100%">
			<tr style="font-family: verdana; font-size: 11; font-weight: bold;" align="center">
				<td><input id="id_barang_tindakan" name="id_barang_tindakan" type="hidden" value="" size="40">NAMA BARANG</td>
				<td>JUMLAH</td>
				<td>TIPE BALANCE</td>
				<td>STOCK</td>
				<td>SATUAN</td>
				<td rowspan="2"> <a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" onclick="saveBahan()" plain="true"></a></td>
            </tr>
            <tr align="center">
                <td>
                    <div class="ausu-suggest">
                        <input type="text" id="bahanBal" name="bahanBal" size="30" onkeydown="
                            if (event.keyCode == 123){
                                saveBahan();
                                return false;
                            }"/>
                        <input type="" id="bahanId" name="bahanId" size="1" disabled />
                    </div>
                </td>
				<td>
				     <input type="text" id="jumlahBarang" name="jumlahBarang" onkeydown="
                        if (event.keyCode == 123){
                            saveBahan();
                            return false;
                        }"/>
				</td>
                <td>
                    <select id="balance" name="balance" onkeydown="
                        if (event.keyCode == 123){
                            saveBahan();
                            return false;
                        }">
					<option value=''>[Pilih Tipe Balance]</option>
                        <?
                            $query  = "SELECT * FROM rm_tipe_balance WHERE del_flag<>'1'";

                            $result = $fungsi->runQuery($query);
                            while($dt = mysql_fetch_array($result, MYSQL_ASSOC))
                            {
                                echo "<option value=\"$dt[id_keperluan]\" >$dt[keperluan]</option>";					
                            }
                        ?>
					</select>
                </td> 
				<td>
                    <input type="text" id="stock" name="stock" readonly />
                </td>
				                <td>
                    <input type="text" id="satuan" name="satuan" readonly />
                </td>
            </tr>
        </table>
		</div>
		<table id="dataBahan" width='100%'></table>
    </div>
</div>
