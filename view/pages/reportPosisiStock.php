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
                            <?
                            if($_SESSION['level']=='18' || $_SESSION['level']=='15'){
                                echo "<option value='37' selected>Gudang</option>
                                    <option value='36' >Apotek Depan</option>
                                    <option value='46' >Apotek Belakang</option>
                                    <option value='47' >Apotek VIP</option>
                                    <option value='50' >Apotek IBS</option>";
                            } else if($_SESSION['level']=='36'){
                                echo "<option value='36' >Apotek Depan</option>";
                            } else if($_SESSION['level']=='46'){
                                echo "<option value='46' >Apotek Belakang</option>";
                            } else if($_SESSION['level']=='47'){
                                echo "<option value='47' >Apotek VIP</option>";
                            } else if($_SESSION['level']=='50'){
                                echo "<option value='50' >Apotek IBS</option>";
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
        <a class="easyui-linkbutton" id="cari-dtlDiagnosa" iconCls="icon-search" href="javascript:void(0)" onclick="getLaporanPosisiStock()" plain="true">Cari</a>
        <a class="easyui-linkbutton"  iconCls="icon-print" href="javascript:void(0)" onclick="cetakAja()" plain="true">Cetak</a>
        <a class="easyui-linkbutton"  iconCls="icon-save" href="javascript:void(0)" onclick="toExcel()" plain="true">Excel</a>
    </div>
    <div region="center" border="false" style="background:#fcfcfc;padding:5px">
        <span id="loading" width='100%'></span>
        <span id="detailLaporan" width='100%' />
    </div>
</div>
