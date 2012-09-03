<?
ini_set("date.timezone", "Asia/Jakarta");
require_once '../common/function.php';
$fungsi = new fungsi();

if (!$fungsi->isAuthorized())
    header("location:login.php");

$docPath = dirname(__FILE__);
$ctxPath = dirname($_SERVER["SCRIPT_NAME"]);
$prm = isset($_GET['page']) ? $_GET['page'] : "#";

$pageRef = array(
    "obal|pages/obatBalance",
    "amfra|pages/dataStockObatApotek",
    "distri|pages/laporanDistribusiObat",
    "payMed|pages/bayarObat",
    "labPul|pages/pmrLabPulang",
    "recRm|pages/listPendaftaranRR",
    "openPindah|pages/bukaPindahRuang",
    "reCured|pages/listPerawatanUlang",
    "dftr|pages/pendaftaran",
    "pxDiet|pages/pasienDiet",
    "buka|pages/unClose",
    "pux|pages/pasienUtilitas",
    "bb|pages/balanceBarang",
    "balanceobat|pages/balanceObat",
    "lstdftr|pages/listPendaftaran",
    "lstdftrpr|pages/listPendaftaranRuang",
    "lstrad|pages/listPendaftaranRadiologi",
    "vkkonsul|pages/listPendaftaranRuangVK",
    "lstlab|pages/listPendaftaranRuangLab",
    "dgns|pages/diagnosa",
    "tndkn|pages/tindakan",
    "rmpx|pages/rekamMedis",
    "pmrrdlg|pages/pmrRadiologi",
    "pmrlab|pages/pmrLaboratorium",
    "tndknibs|pages/tindakanIbs",
    "lstobt|pages/obat",
    "lstblobt|pages/listObat",
    "lstblobtfrm|pages/listObatFarmasi",
    "pjlobt|pages/penjualanObat",
    "byrfktr|pages/bayarFaktur",
    "dtbyrfktr|pages/dataBayarFaktur",
    "stkobt|pages/dataStockGudangObat",
    "lstrawat|pages/listPerawatan",
    "diet|pages/dietPasien",
    "vstdktr|pages/visitDokter",
    "dftrkonsul|pages/listPendaftaranKonsul",
    "dftrtghnpx|pages/tagihanPasien",
    "reprint|pages/tagihanPasienKeluar",
    "igdsrv|pages/surveyIgd",
    "smry|pages/summary",
    "pmrdktr|pages/periksaDokter",
    "byrfrm|pages/pembayaranFarmasi",
    "rptjstndkn|pages/reportJasaTindakan",
    "dftrkrmobt|pages/kirimObat",
    "rptjsanl|pages/reportJasaTindakanLab",
    "rptjsrdg|pages/reportJasaTindakanRad",
    "rtrobt|pages/returObat",
    "rptjsdktr|pages/reportJasaDokter",
    "rptjslab|pages/reportJasaDokterLab",
    "rptjsrad|pages/reportJasaDokterRad",
    "rptjsbdh|pages/reportJasaDokterBedah",
    "lstfktrobt|pages/listFakturObat",
    "rptjsdftrdktr|pages/reportJasaPendaftaranDokter",
    "rptjsdftr|pages/reportJasaPendaftaran",
    "rptjsvisit|pages/reportJasaVisit",
    "rptjsprks|pages/reportJasaPemeriksaanDokter",
    "dftrjualobt|pages/listPenjualanObat",
    "dftrpxrwt|pages/listPasienPerawatan",
    "rptblobt|pages/reportPembelianObat",
    "rptjlobtshift|pages/reportPenjualanObat",
    "rptPendKasir|pages/reportPendapatanKasir",
    "rptjualhr|pages/reportPenjualanHarian",
    "strksr|pages/setoranKasir",
    "tghnrwtinp|pages/listTagihanRwtInp",
    "tghnrwtjln|pages/listTagihanRwtJln",
    "dtobt|pages/dataObat",
    "dtSup|pages/dataSupplier",
    "rawat|pages/daftarPasien",
    "darurat|pages/darurat",
    "rptposstock|pages/reportPosisiStock",
    "rptobtpx|pages/reportObatPasien",
    "rekappndptn|pages/rekapPendapatan",
    "rptjsdftrmnj|pages/reportJasaPendaftaranMnj",
    "rptjstnkdnmnj|pages/reportJasaTindakanMnj",
    "rptjslabmnj|pages/reportJasaLaboratoriumMnj",
    "rptjsradmnj|pages/reportJasaRadiologiMnj",
    "rptjsbedahmnj|pages/reportJasaBedahMnj",
    "rptjsvisitmnj|pages/reportJasaVisitMnj",
    "rptjsrawatmnj|pages/reportJasaPerawatanMnj",
    "rptjsrawat|pages/reportJasaPerawatan",
    "rekapJasaMnj|pages/rekapJasaMnj",
    "rekapresep|pages/rekapResep",
    "rekapjualobat|pages/rekapPenjualanObat",
    "rekapkeuangan|pages/rekapKeuangan",
    "dftrkrmbrg|pages/kirimBarang",
    "stkbrg|pages/dataStockGudangBarang",
    "dftrtagihbanding|pages/tagihanPasienBanding",
    "#|pages/news",
    /* laporan */
    "lprApt|../reports/frm_Apotek",
    "lprLab|../reports/frm_Lab",
    "lprPend|../reports/frm_Pendaftaran",
    "lprRad|../reports/frm_Rad",
    "lpribs|laporan/frm_index_ibs",
    "lpribs2|laporan/frm_2index_ibs",
    "lprkrcs|laporan/frm_index_krcis",
    "lprreg|laporan/frm_index_registrasi",
    "lprradio|laporan/frm_index_radiologi",
    "lprmati|laporan/frm_index_kematian",
    "lprdiet|laporan/frm_index_diet",
    "ctkkpn|laporan/frm_index_tiket",
    "lprdiag|laporan/frm_index_penyakit_penderita",
    "lprindexdokter|laporan/frm_index_dokter_cari",
    "lprklsperawatanbln|laporan/frm_index_kelas_perawatan_bln",
    "lprklsperawatanthn|laporan/frm_index_kelas_perawatan_thn",
    "lprkapdiettahunan|laporan/frm_index_diet_thn",
    "lprkapdietbulanan|laporan/frm_index_diet_bln",
    "lprpenjualanobat|laporan/frm_index_penjualan_per_kdobat",
    "lpr10besar|laporan/frm_index_10diagnosa",
    "kunjungan|laporan/frm_kunjungan"
        /* "lproperasi|laporan/frm_index_tindakan_operasi_cari",
          "lprregistrasikonsulan|laporan/act_index_laporan_registrasi_b_frm", */
        /* laporan */
        /* BELUM DIGUNAKAN
         * "dftrkrmobtrg|pages/kirimObatRuang",
         * "lprpoli|laporan/frm_index_rwtjln",
         * "lprcaripasien|laporan/frm_index_pasien",
         * "informasi|laporan/frm_index_informasi",
         */
);

$q_menu = "SELECT nama_menu FROM rm_menu WHERE target='" . $_GET['page'] . "'";
$r_menu = $fungsi->runQuery($q_menu);
$title = @mysql_result($r_menu, 0, 'nama_menu');

$notfound = "pages/reqNotFound.php";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title> <?php echo $title ?> </title>
        <link rel="shortcut icon" href="images/medic.png" />
        <link rel="stylesheet" type="text/css" href="themes/default/easyui.css" />
        <link rel="stylesheet" type="text/css" href="style/style.css" />
        <link rel="stylesheet" type="text/css" href="style/icon.css" />
        <link rel="stylesheet" type="text/css" href="js/codebase/dhtmlxcombo.css"/>
        <link rel="stylesheet" type="text/css" href="js/codebase/dhtmlxcalendar.css"/>
        <link rel="stylesheet" type="text/css" href="js/codebase/skins/dhtmlxcalendar_simplecolordark.css"/>
    </head>
    <body class="easyui-layout">
        <div region="north" border="false" style="height:68px;background:#98FE98;">
            <table width='100%' height='67' cellspacing='0' cellpadding='0' border='0' id='tabel_utama' style="background-repeat: repeat-x;">
                <tr>
                    <td width='1%' bgcolor='#0D8E13'></td>
                    <td width='99%' id='divTitle'>
                        <TABLE>
                            <TR>
                                <TD width='100'><img src='images/logo.png' width='65' height='60'></TD>
                                <TD valign='top'>
                                    <span style='font-family: sans-serif; font-weight: bold; font-size: 28px; color: #000000;'>
                                        Sistem Informasi RSUD Dr. SOEGIRI
                                    </span>
                                    <br>
                                    <span style='font-family: sans-serif; font-size: 18px; color: #000000;'>
                                        Pemerintah Kabupaten Lamongan
                                    </span>
                                </TD>
                            </TR>
                        </TABLE>
                    </td>
                </tr>
                <tr height='2'>
                    <td colspan='2' bgcolor='#0D8E13'></td>
                </tr>
            </table>
        </div>
        <div region="west" split="true" title="Welcome" style="width:190px;">
            <div style="background:#96FC96;padding:1px;width:180px;">
                <a href="javascript:void(0)" id="sb1" class="easyui-linkbutton" plain="true" menu="#mm1" iconCls="icon-home" onclick="window.location.href='index.php';">Home</a>
                <a href="javascript:void(0)" id="sb2" class="easyui-linkbutton" plain="true" menu="#mm2" iconCls="icon-logout" onclick="window.location.href='logout.php';">Logout</a>
            </div>
            <div id="welcome">
                <p class="details-info">Selamat Datang<br>
                    <b><? echo $_SESSION["nama_pegawai"] ?></b><br>
                    Login Sebagai :<br>
                    <b><? echo $fungsi->getLevelName($_SESSION["jenis"], $_SESSION["level"]) ?></b>
                </p>
                <input type='hidden' id='level' name='level' value='<? echo $_SESSION["level"] ?>'>
            </div>
            <hr>
            <ul id="tt" url="json/treedata.php" animate="true"></ul>
        </div>
        <!--DULUNYA SWITCH DISINI-->
        <div region="center" title="<? echo $title; ?>">
            <?php
            $isFound = false;
            $otorisasi = true;
            foreach ($pageRef as $line) {
                $col = explode("|", $line);
                if ($fungsi->checkOtorisasi($prm, $_SESSION['level'])) {
                    if ($prm == $col[0]) {
                        $isFound = true;
                        include $col[1] . ".php";
                        $javaScript = 'js/' . $col[1] . '.js';
                        break;
                    }
                }
            }
            if ($isFound == false) {
                include $notfound;
            }
            ?>
        </div>
        <div id="winClosePerawatan" class="easyui-window" title="Close Perawatan" draggable="true" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:400px;height:218px;background: #fafafa;">
            <form id="frmClosePerawatan" onkeydown="
                if (event.keyCode == 123){
                    simpanClosePerawatan();
                }
                  ">
                <table class="data" width="100%">
                    <tr height="25">
                        <td>
                            <label>Kondisi</label>
                        </td>
                        <td>
                            <select id="kondisiKeluar" name="kondisiKeluar">
                                <option value=''>[Pilih Kondisi]</option>
                                <?
                                $query = "SELECT * FROM rm_keadaan WHERE del_flag<>'1'";

                                $result = $fungsi->runQuery($query);
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    echo "<option value=\"$dt[id_keadaan]\" >$dt[keadaan]</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td>
                            <label>Cara Keluar</label>
                        </td>
                        <td>
                            <select id="caraKeluar" name="caraKeluar">
                                <option value=''>[Pilih Cara Keluar]</option>
                                <?
                                $query = "SELECT * FROM rm_cara_keluar WHERE del_flag<>'1'";

                                $result = $fungsi->runQuery($query);
                                while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                    echo "<option value=\"$dt[id_cara_keluar]\" >$dt[cara_keluar]</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr height="25">
                        <td>
                            <label>Tanggal Keluar</label>
                        </td>
                        <td>
                            <input name="tglKeluar" id="tglKeluar" class="easyui-datebox" value="<? echo date('d-m-Y'); ?>">
                        </td>
                    </tr>
                    <tr height="25">
                        <td>
                            <label>Keterangan</label>
                        </td>
                        <td>
                            <textarea id="keteranganKeluar" name="keteranganKeluar" cols="25" rows="2"></textarea>
                        </td>
                    </tr>
                </table>
            </form>
            <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
                <?
                if ($_SESSION['level'] == "22") {
                    echo '<a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" id="simpan" onclick="simpanClosePerawatanMedis()" plain="true">Simpan Medis</a>';
                } else {
                    echo '<a class="easyui-linkbutton" iconCls="icon-Save" href="javascript:void(0)" id="simpan" onclick="simpanClosePerawatan()" plain="true">Simpan</a>';
                }
                ?>
                <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="$('#winClosePerawatan').window('close');" plain="true">Batal</a>
            </div>
        </div>
        <div id="spinner" class="easyui-window" style="background: transparent; border-color: transparent;" noheader="true" draggable="false" border="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true">
            <img src="images/loader.gif" alt="Sistem Sedang Padat, Mohon Tunggu Sebentar..."/>    <b>Sistem Sedang Padat, Mohon Tunggu Sebentar...</b>
        </div>
        <div id="winSearchPasien" class="easyui-window" title="Data Pasien" draggable="false" resizable="false" closable="true" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true" style="width:800px;height:510px;background: #fafafa;">
            <div class="easyui-layout" fit="true" style="background:#ccc;">
                <div region="north" border="false" style="background-color: #fcfcfc;text-align:left;padding: 5px;height:160px;">
                    <form id="frmSrcPasien">
                        <table class='data' width="100%">
                            <tr height="25">
                                <td width='19%'>Nama</td>
                                <td width='30%'>
                                    <input id="srcNamaPasien" name="srcTindakan" type="text" value="" onkeydown="
                                        if (event.keyCode == 13){
                                    
                                            loadDataListPasien();
                                            return false;
                                        }
                                           " ></input>
                                </td>
                                <td width='2%'>&nbsp;</td>
                                <td width='19%'>Alamat</td>
                                <td width='30%'>
                                    <input id="srcAlamat" name="srcAlamat" type="text" value="" onkeydown="
                                        if (event.keyCode == 13){
                                    
                                            loadDataListPasien();
                                            return false;
                                        }
                                           " ></input>
                                </td>
                            </tr>
                            <tr height="25">
                                <td width='19%'>Tgl Lahir</td>
                                <td width='30%'>
                                    <input id="srcTglLahir" name="srcTglLahir" class="easyui-datebox" type="text" value="" onkeydown="
                                        if (event.keyCode == 13){
                                    
                                            loadDataListPasien();
                                            return false;
                                        }
                                           " ></input>
                                </td>
                                <td width='2%'>&nbsp;</td>
                                <td width='19%'>Sampai</td>
                                <td width='30%'>
                                    <input id="srcTglLahirTo" name="srcTglLahirTo" class="easyui-datebox" type="text" value="" onkeydown="
                                        if (event.keyCode == 13){
                                    
                                            loadDataListPasien();
                                            return false;
                                        }
                                           " ></input>
                                </td>
                            </tr>
                            <tr height="25">
                                <td width='19%'>Kecamatan</td>
                                <td width='30%'>
                                    <select id="srcKecamatan" name="srcKecamatan">
                                        <option value="">[Pilih Kecamatan]</option>
                                        <?
                                        $query = "SELECT id_kecamatan, kecamatan FROM rm_kecamatan WHERE del_flag<>'1' order by kecamatan";

                                        $result = $fungsi->runQuery($query);
                                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                            echo "<option value=\"$dt[id_kecamatan]\">$dt[kecamatan]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td width='2%'>&nbsp;</td>
                                <td width='19%'>Kelurahan</td>
                                <td width='30%'>
                                    <select id="srcKelurahan" name="srcKelurahan">
                                        <option value="">[Pilih Kelurahan]</option>
                                        <?
                                        $query = "SELECT id_kelurahan, kelurahan FROM rm_kelurahan WHERE del_flag<>'1' order by kelurahan";

                                        $result = $fungsi->runQuery($query);
                                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                            echo "<option value=\"$dt[id_kelurahan]\">$dt[kelurahan]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr height="25">
                                <td width='19%'>Asuransi</td>
                                <td width='30%'>
                                    <select id="srcAsuransi" name="srcAsuransi">
                                        <option value="">[Pilih Asuransi]</option>
                                        <?
                                        $query = "SELECT id_tipe_asuransi, tipe_asuransi FROM rm_tipe_asuransi WHERE del_flag<>'1'";

                                        $result = $fungsi->runQuery($query);
                                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                            echo "<option value=\"$dt[id_tipe_asuransi]\">$dt[tipe_asuransi]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td width='2%'>&nbsp;</td>
                                <td width='19%'>Tipe Pasien</td>
                                <td width='30%'>
                                    <select id="srcTipePasien" name="srcTipePasien">
                                        <option value="">[Pilih Tipe Pasien]</option>
                                        <?
                                        $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag<>'1'";

                                        $result = $fungsi->runQuery($query);
                                        while ($dt = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                            echo "<option value=\"$dt[id_tipe_pasien]\">$dt[tipe_pasien]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <a class="easyui-linkbutton" iconCls="icon-search" href="javascript:void(0)" onclick="loadDataListPasien()" plain="true">Cari</a>
                </div>
                <div region="center" border="false" style="background:#99FF99;padding:5px">
                    <table id="dataListPasien" width='100%'></table>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/function.js"></script>
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/jquery.inputmask.js"></script>
    <script type="text/javascript" src="js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="js/jquery.ausu-autosuggest.js"></script>
    <script type="text/javascript" src="js/datagrid-groupview.js"></script>
    <script type="text/javascript" src="js/datagrid-detailview.js"></script>
    <!-- UNTUK DHTMLX -->
    <script>window.dhx_globalImgPath = "js/codebase/imgs/";</script>
    <script  src="js/codebase/dhtmlxcommon.js"></script>
    <script  src="js/codebase/dhtmlxcombo.js"></script>
    <script  src="js/codebase/dhtmlxcalendar.js"></script>
    <!-- AKHIR DHTMLX -->
    <script type="text/javascript">$(function(){$('#tt').tree({onClick:function(node){var redirect = 'index.php?page=' + node.id;window.location = redirect;}});
        
    });
    function clickIE(){if(document.all){return false;}}function clickNS(e){if(document.layers||(document.getElementById&&!document.all)){if(e.which==2||e.which==3){return false;}}}if (document.layers){document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}
    </script>
    <?php
    include $javaScript;
    ?>
<!--    <script>
    $(window).load(function(){
        var alertTimerId = 0;
        $("#spinner").bind("ajaxStart", function() {
            alertTimerId = setTimeout("$('#spinner').window('open')", 1000);
        }).bind("ajaxStop", function() {
            clearTimeout (alertTimerId);
            $('#spinner').window('close');
        }).bind("ajaxError", function() {
            alert('Terjadi Galat, Halaman Akan Direload...')
            window.location.reload();
        });
    });
    </script>-->
</html>
