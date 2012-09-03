<div class="easyui-layout" fit="true" style="background:#ccc;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:190px;">
        <form name='frmFakturPenjualan' id='frmFakturPenjualan'>
            <table class='data' width="100%">
                <tr height="25">
<?php
if($_SESSION['level']!=14){
echo		    '<td width="19%">No Faktur</td>
                    <td width="30%">
                        <input id="id_faktur_penjualan" name="id_faktur_penjualan" type="text" value="" size="5" onkeydown="
                            if (event.keyCode == 13){
                                getDtlFaktur();
                            } else if (event.keyCode == 46 || event.keyCode == 8){
                                bersihkan();
                            }
                               " />
                    </td>
                    <td width="2%">&nbsp;</td>
                    <td width="19%">No Resep</td>
                    <td width="30%"><input id="no_resep" name="no_resep" type="text" value=""  disabled /></td>
                </tr>
                <tr height="25">
                    <td width="19%">Customer</td>
                    <td width="30%">
                        <select name="jns_customer" id="jns_customer" disabled>
                            <option value="Pasien">Pasien</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </td>
                    <td width="2%">&nbsp;</td>
                    <td width="19%">Dokter</td>
                    <td width="30%">
			 <select name="dokter" id="dokter" disabled>
                            <option value="">[Pilih Dokter]</option>';
                            
                            $query = "SELECT id_dokter, nama_dokter FROM rm_dokter WHERE del_flag<>1 order by nama_dokter";

                            $result = $fungsi->runQuery($query);
                            while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                echo "<option value=\"$dt[id_dokter]\">$dt[nama_dokter]</option>";
                            }
                            
echo                        '</select>
                    </td>
                </tr>
                <tr height="25">
                    <td width="19%">No RM</td>
                    <td width="30%">
                        <input type="text" name="id_pasien" id="id_pasien" size="5" onkeydown="
                            if (event.keyCode == 13){
                                getDtlFaktur();
                            }
                               " >
                    </td>
                    <td width="2%">&nbsp;</td>
			<td width="19%">Ruang</td>
                    <td width="30%">
                        <input type="text" name="ruang" id="ruang" size="30" disabled>
                    </td>
                </tr>
                <tr height="25">
                    <td width="19%">Nama Pasien</td>
                    <td width="30%">
                        <input type="text" name="nama_pasien" id="nama_pasien" size="30" onkeydown="
                            if (event.keyCode == 13){
                                getDtlFaktur();
                            }
                               " >
                    </td>
                    <td width="2%">&nbsp;</td>
                    <td width="19%">Alamat</td>
                    <td width="30%">
                        <input type="text" name="alamat" id="alamat" size="30" disabled>
                    </td>
                </tr>
                <tr height="25">
                    <td width="19%">Tipe Pasien</td>
                    <td width="30%">
                        <input type="text" name="tipe_pasien" id="tipe_pasien" size="30" disabled />
                    </td>
                    <td width="2%">&nbsp;</td>';
} else {
echo '<input type="text" name="id_faktur_penjualan" id="id_faktur_penjualan" disabled hidden/>';
}

?>
                    <td width="19%">ID Retur</td>
                    <td width="30%">
                        <input type="text" name="id_retur" id="id_retur" size="5" onkeydown=" 
				if (event.keyCode == 13){
					cetakUlang();
				} "/>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="tipe_pendaftaran" id="tipe_pendaftaran" size="5" disabled />
            <input type="hidden" name="tipe_asuransi" id="tipe_asuransi" size="5" disabled />
            <input type="hidden" name="karyawan" id="karyawan" size="5" disabled />
            <input type="hidden" name="tunaiF" id="tunaiF" size="5" disabled />
        </form>
<?php
if($_SESSION['level']!=14){
echo        '<a class="easyui-linkbutton" iconCls="icon-search" href="javascript:void(0)" onclick="getDtlFaktur()" plain="true">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakKwitansi()" plain="true">Cetak Kwitansi</a>';
}?>
        <a class="easyui-linkbutton" iconCls="icon-print" href="javascript:void(0)" onclick="cetakUlang()" plain="true">Cetak Ulang Kwitansi</a>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
<?php
if($_SESSION['level']!=14){
echo
	'<table id="dataPenjualanObat" width="100%"></table>
        <table id="dataReturPenjualanObat" width="100%"></table>';}?>
    </div>
</div>
<?php
if($_SESSION['level']!=14){
echo
'<div id="winDetailObat" class="easyui-window" title="Form Retur Obat" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:210px;background: #fafafa;">
    <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:120px;">
        <form name="frmDetailObat" id="frmDetailObat">
            <table class="data" width="100%">
                <tr height="25">
                    <td width="40%">Nama Obat</td>
                    <td width="60%">
                        <input type="text" id="nama_obat" name="nama_obat" value="" size="30" disabled />
                        <input type="hidden" id="id_obat" name="id_obat" value="" disabled />
                        <input type="hidden" id="id_jual" name="id_jual" value="" disabled />
                    </td>
                </tr>
                <tr height="25">
                    <td width="40%">Jumlah Retur</td>
                    <td width="60%">
                        <input type="text" id="jmlRetur" name="jmlRetur" value="" size="5" onkeyup="IsNumeric(jmlRetur)" 
                               onkeydown="if(event.keyCode == 13){
                                   prosRetur.focus();
                               }"/>
                        <input type="hidden" id="qty" name="qty" value="" size="5" onkeydown="if(event.keyCode == 13){
                        prosRetur.focus();
                    }"/>
                    </td>
                </tr>
                <tr height="25">
                    <td width="40%">Prosentase Retur</td>
                    <td width="60%">
                        <input type="text" id="prosRetur" name="prosRetur" value="0" size="2" onkeyup="IsNumeric(prosRetur)" 
                               onkeydown="if(event.keyCode == 13){simpanReturObat();}"/> %
                    </td>
                </tr>
                <tr height="25">
                    <td width="40%">Jenis Retur</td>
                    <td width="60%">
                        <input type="checkbox" name="tunai" id="tunai" value="1" disabled/>
                        <label>Tunai</label>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div region="center" border="false" style="background:#99FF99;padding:5px">
        <a class="easyui-linkbutton" id="btnRetur" iconCls="icon-save" href="javascript:void(0)" onclick="simpanReturObat()" plain="true">Simpan</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="closeWinDetailObat()" plain="true">Close</a>
    </div>';}?>
</div>
