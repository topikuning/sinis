<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of function
 *
 * @author SAP
 */
require_once(dirname(__FILE__) . "/database.php");

class fungsi extends database {

    private $totalAllBanding = 0;

//put your code here
    public function isAuthorized() {
        return(isset($_SESSION['nip']) && isset($_SESSION['nama_pegawai']));
    }

    public function setAllBanding($banding) {
        $this->totalAllBanding = $banding;
    }

    public function getAllBanding() {
        return $this->totalAllBanding;
    }

    public function login($user, $pass) {
        $q_login = "select * from rm_keperawatan where usr_name='" . @mysql_escape_string($user) . "' and usr_pass='" . $pass . "' and del_flag<>'1'";
        $result = $this->runQuery($q_login);
        if (mysql_num_rows($result) > 0) {
            $nama_pegawai = mysql_result($result, 0, 'nama_pegawai');
            $level = mysql_result($result, 0, 'id_ruang');
            $nip = '000000';
            $jenis = 'perawat';
        } else {
            $q_login = "select * from rm_pegawai where usr_name='" . @mysql_escape_string($user) . "' and usr_pass='" . $pass . "' and del_flag<>'1'";
            $result = $this->runQuery($q_login);
            if (mysql_num_rows($result) > 0) {
                $nama_pegawai = mysql_result($result, 0, 'nama_pegawai');
                $level = mysql_result($result, 0, 'id_jabatan');
                $nip = mysql_result($result, 0, 'nip');
                $jenis = 'pegawai';
            } else {
                $q_login = "select * from rm_dokter where usr_name='" . @mysql_escape_string($user) . "' and usr_pass='" . $pass . "' and del_flag<>'1'";
                $result = $this->runQuery($q_login);
                if (mysql_num_rows($result) > 0) {
                    $jenis = 'dokter';
                    $nama_pegawai = mysql_result($result, 0, 'nama_dokter');
                    $level = 1;
                    $_SESSION['id'] = mysql_result($result, 0, 'id_dokter');
                    $nip = mysql_result($result, 0, 'nip');
                } else {
                    return false;
                }
            }
        }
        $_SESSION['usrName'] = $user;
        $_SESSION['nip'] = $nip;
        $_SESSION['nama_pegawai'] = $nama_pegawai;
        $_SESSION['level'] = $level;
        $_SESSION['jenis'] = $jenis;
        return $level;
    }

    function getTabel($jenis) {
        switch ($jenis) {
            case 'pegawai':
                return 'rm_pegawai';
                break;
            case 'dokter':
                return 'rm_dokter';
                break;
            case 'perawat':
                return 'rm_keperawatan';
                break;
            default :
                break;
        }
    }

    function logout() {
        session_start();
        session_destroy();
        header("location:index.php");
    }

    function checkOtorisasi($page, $level) {
        $query = "select * from rm_menu where target='" . @mysql_escape_string($page) . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $q = "select * FROM rm_detail_menu where id_menu='" . @mysql_result($result, 0, 'id_menu') . "' and id_jabatan='" . $level . "' and del_flag<>'1'";
            $r = $this->runQuery($q);

            if (mysql_num_rows($r) > 0) {
                return true;
            }
        }
    }

    function getLevelName($jenis, $level) {
        if ($jenis == 'perawat') {
            return 'Perawat';
        } else if ($jenis == 'dokter') {
            return 'Dokter';
        } else if ($jenis == 'pegawai') {
            $query = "select jabatan from rm_jabatan where id_jabatan='" . $level . "'";
            if ($result = $this->runQuery($query)) {
                $levelName = mysql_result($result, 0, 'jabatan');
            } else {
                $levelName = 'Not Authorized';
            }
            return $levelName;
        } else {
            return 'Not Authorized';
        }
    }

    function menu($jenis, $level) {
        $query = "select a.target as id, a.nama_menu as text from rm_menu as a, rm_detail_menu as b 
                  where a.id_menu=b.id_menu and a.display='1' and b.id_jabatan='" . $level . "' and b.del_flag <> '1' and jenis_login='" . $jenis . "' order by b.urut";

        $result = $this->runQuery($query);
        $jumlah = mysql_num_rows($result);
        if ($jumlah <= 0) {
            $this->base->close();
            header("location:index.php");
        }
        $tree = array();

        while ($menu = @mysql_fetch_object($result)) {
            $menu->leaf = true;
            $tree[] = $menu;
        }
        return $tree;
    }

    function jEncode($arr) {
        if (version_compare(PHP_VERSION, "5.2", "<")) {
            require_once("JSON.php");
            $json = new Services_JSON();
            $data = $json->encode($arr);
        } else {
            $data = json_encode($arr);
        }
        return $data;
    }

    function codeDate($date) {
        if ($date != "") {
            $tab = explode("-", $date);
            $r = $tab[2] . " " . $this->bln($tab[1]) . " " . $tab[0];
            return $r;
        } else {
            return "";
        }
    }

    function encodeDate($date) {
        $tab = explode("/", $date);
        $r = $tab[2] . "-" . $tab[1] . "-" . $tab[0];
        return $r;
    }

    function formatDateDb($date) {
        $tab = explode("-", $date);
        $r = $tab[2] . "-" . $tab[1] . "-" . $tab[0];
        return $r;
    }

    function cekDate($date) {
        $tab = explode("/", $date);
        if (count($tab) > 1)
            return $this->encodeDate($date);
        else
            return $date;
    }

    function getUmurTahun($unformattedDate) {
        if ($unformattedDate != "") {
            $friendlyAgeText = array();

            $oMD = explode('-', $unformattedDate);
            $yearDiff = date("Y") - $oMD[0];
            $monthDiff = date("m") - $oMD[1];
            $dayDiff = date("d") - $oMD[2];

            if ($monthDiff < 0) {
                $yearDiff--;
                $monthDiff += 12;
            }
            if ($dayDiff < 0) {
                $monthDiff--;
                $dayDiff += 30;
            }

            switch ($yearDiff):
                case 0:
                    break;
                default:
                    $friendlyAgeText[] = $yearDiff . " Th";
                    break;
            endswitch;

            return join(" ", $friendlyAgeText);
        } else {
            return "";
        }
    }

    function bln($bln) {
        if ($bln == "1" || $bln == "01") {
            $bulan = "Januari";
        } else if ($bln == "2" || $bln == "02") {
            $bulan = "Februari";
        } else if ($bln == "3" || $bln == "03") {
            $bulan = "Maret";
        } else if ($bln == "4" || $bln == "04") {
            $bulan = "April";
        } else if ($bln == "5" || $bln == "05") {
            $bulan = "Mei";
        } else if ($bln == "6" || $bln == "06") {
            $bulan = "Juni";
        } else if ($bln == "7" || $bln == "07") {
            $bulan = "Juli";
        } else if ($bln == "8" || $bln == "08") {
            $bulan = "Agustus";
        } else if ($bln == "9" || $bln == "09") {
            $bulan = "September";
        } else if ($bln == "10") {
            $bulan = "Oktober";
        } else if ($bln == "11") {
            $bulan = "Nopember";
        } else if ($bln == "12") {
            $bulan = "Desember";
        } else {
            $bulan = $bln;
        }

        return $bulan;
    }

    function jmlHari($date1, $date2) {

        $q_hari = "SELECT DATEDIFF('" . $date2 . "','" . $date1 . "') as lama";
        $r_hari = $this->runQuery($q_hari);
        return @mysql_result($r_hari, 0, 'lama');
    }

    function jmlJam($date1, $date2) {

        $q_hari = "SELECT HOUR(TIMEDIFF('" . $date2 . "','" . $date1 . "')) as lama";
        $r_hari = $this->runQuery($q_hari);
        return @mysql_result($r_hari, 0, 'lama');
    }

    public function getNoIdentitasPasien($id_pasien) {
        $query = "SELECT no_identitas FROM rm_det_identitas WHERE id_pasien='" . $id_pasien . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'no_identitas');
        else
            return '';
    }

    public function getSupplier($id_supplier) {
        $query = "SELECT supplier FROM rm_supplier WHERE id_supplier='" . $id_supplier . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'supplier');
        else
            return '';
    }

    public function getJenisIdentitas($id_pasien) {
        $query = "SELECT id_jenis_identitas FROM rm_det_identitas WHERE id_pasien='" . $id_pasien . "'";
        $result = $this->runQuery($query);

        $q_jenis = "SELECT jenis_identitas FROM rm_jenis_identitas WHERE id_jenis_identitas='" . @mysql_result($result, 0, 'id_jenis_identitas') . "'";
        $r_jenis = $this->runQuery($q_jenis);
        return @mysql_result($r_jenis, 0, 'jenis_identitas');
    }

    public function getJenisDokter($id_dokter) {
        $query = "SELECT id_jenis_dokter FROM rm_dokter WHERE id_dokter='" . $id_dokter . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_jenis_dokter');
    }

    public function getNamaObat($id_obat) {
        $query = "SELECT nama_obat FROM rm_obat WHERE id_obat='" . $id_obat . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'nama_obat');
    }

    public function checkBayarObat($id_faktur_penjualan) {
        $query = "select count(*) as jmlBayar from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'jmlBayar') == '0')
            return true;
    }

    public function checkDistObat($id_faktur) {
        $query = "select count(*) as jmlDist from rm_faktur where id_faktur='" . $id_faktur . "' and status_assign='1'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'jmlDist') == '0')
            return true;
    }

    public function getTarifBedah($id_tindakan_medis, $id_kelas) {
        $query = "SELECT tarif FROM rm_tarif_tindakan_medis where id_tindakan_medis='" . $id_tindakan_medis . "' and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function getTarifLaboratorium($id_laboratorium, $id_kelas) {
        $query = "SELECT tarif FROM rm_tarif_laboratorium where id_laboratorium='" . $id_laboratorium . "' and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function getTarifAllLaboratorium($id_pendaftaran, $id_kelas) {
        $tarif = 0;
        $q_lab = "select id_laboratorium from rm_detail_laboratorium where id_pendaftaran='" . $id_pendaftaran . "'";
        $r_lab = $this->runQuery($q_lab);
        while ($lab = @mysql_fetch_array($r_lab)) {
            $tarif += $this->getTarifLaboratorium($lab['id_laboratorium'], $id_kelas);
            ;
        }

        return $tarif;
    }

    public function getTarifRadiologi($id_radiologi, $id_kelas) {
        $query = "SELECT tarif FROM rm_tarif_radiologi where id_radiologi='" . $id_radiologi . "' and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function getTarifKamarInap($id_kamar, $id_kelas) {
        $query = "SELECT tarif FROM rm_tarif_kamar where id_kamar='" . $id_kamar . "' and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function getTarifAskepKamar($id_kamar, $id_kelas) {
        $query = "SELECT jasa_perawat FROM rm_tarif_kamar where id_kamar='" . $id_kamar . "' and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'jasa_perawat');
    }

    public function getJasaIbs($kelas, $field) {
        $query = "SELECT " . $field . " as jasa FROM rm_jasa_tindakan_ibs WHERE id_kelas='" . $kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'jasa');
    }

    public function cekJenisIdentitas($params) {
        $query = "SELECT id_jenis_identitas FROM rm_jenis_identitas WHERE id_jenis_identitas='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'jenis_identitas');
        }
        return $res;
    }

    public function getLastDaftarPasien($params) {
        $query = "SELECT max(id_pendaftaran) as id_daftar FROM rm_pendaftaran WHERE id_pasien='" . $params . "' and id_asal_pendaftaran='0' and status_pembayaran!='2' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'id_daftar');
        else
            return "";
    }

    public function checkIdentitasPasien($no_identitas, $tipe_pendaftaran) {
        $query = "SELECT count(*) as jml FROM rm_det_identitas_pegawai WHERE no_identitas='" . $no_identitas . "'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'jml') > 0 && $tipe_pendaftaran <= '5' && $tipe_pendaftaran != '4') {
            return true;
        }
    }

    public function checkDoubleBed($id_pendaftaran) {
        $query = "SELECT count(*) as jml FROM rm_penggunaan_kamar_extra WHERE id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'jml') > 0) {
            return true;
        }
    }

    public function checkIdentitasPasienFarmasi($no_identitas) {
        $query = "SELECT count(*) as jml FROM rm_det_identitas_pegawai WHERE no_identitas='" . $no_identitas . "'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'jml') > 0) {
            return true;
        }
    }

    public function checkTipeAsuransi($id_pasien) {
        $query = "SELECT id_tipe_asuransi FROM rm_pasien WHERE id_pasien='" . $id_pasien . "'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'id_tipe_asuransi') == '1') {
            return true;
        } else {
            return false;
        }
    }

    public function checkJmlDaftar($id_pasien) {
        $query = "SELECT count(*) as jml FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' and date(tgl_pendaftaran)='" . date('Y-m-d') . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'jml') > 1) {
            return true;
        }
    }

    public function jmlDaftar($id_pasien) {
        $query = "SELECT count(*) as jml FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'jml');
    }

    public function getTglDay($jmlHari) {
        $result = date("Y-m-d", strtotime("-" . $jmlHari . " days"));

        return $result;
    }

    public function checkRehabMedic($jmlHari, $id_pasien) {
        $tglAwal = $this->getTglDay(14);
        $tglAkhir = date('Y-m-d');

        $query = "SELECT count(*) as jml FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' and 
                 tgl_pendaftaran between '" . $tglAwal . "' and '" . $tglAkhir . "' and id_tipe_pendaftaran='5'";
        $result = $this->runQuery($query);

        if (@mysql_result($result, 0, 'jml') > 0 && @mysql_result($result, 0, 'jml') < 4) {
            return true;
        }
    }

    public function getPasienId($params) {
        $query = "SELECT id_pasien FROM rm_pasien WHERE nama_pasien like '%" . @mysql_escape_string($params) . "%'";
        $result = $this->runQuery($query);

        $jmlRow = mysql_num_rows($result);
        $i = 0;
        $field = '';
        while ($data = mysql_fetch_array($result)) {
            $field .= "'" . $data['id_pasien'] . "'";
            $i++;
            if ($i < $jmlRow)
                $field .= ", ";
            else
                $field .= "";
        }
        return $field;
    }

    public function getPasienIdDaftar($params) {
        $query = "SELECT id_pasien FROM rm_pendaftaran WHERE id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_pasien');
    }

    public function getPasienNama($params) {
        $query = "SELECT nama_pasien FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'nama_pasien');
        else
            return '';
    }

    public function getPasienLahir($params) {
        $query = "SELECT tgl_lahir FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tgl_lahir');
        else
            return '';
    }

    public function getJumlahReturObat($id_faktur_penjualan, $id_obat) {
        $query = "SELECT jumlah FROM rm_retur_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and id_obat='" . $id_obat . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'jumlah');
        else
            return '0';
    }

    public function getPasienInfo($params) {
        $query = "SELECT alamat, id_kota, id_kecamatan, id_kelurahan, id_tipe_pasien FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'alamat') . ';' . @mysql_result($result, 0, 'id_kota') . ';' .
            @mysql_result($result, 0, 'id_kecamatan') . ';' . @mysql_result($result, 0, 'id_kelurahan') . ';' .
            @mysql_result($result, 0, 'id_tipe_pasien');
        else
            return '';
    }

    public function getFieldId($params, $table) {
        $query = "select id_" . $table . " as res from rm_" . $table . " where " . $table . "='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'res');
    }

    public function getRujukan($params, $field) {
        $query = "select " . $field . " from rm_rujukan where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, $field);
        else
            return '';
    }

    public function getAsalRujukan($params) {
        $query = "select asal_rujukan from rm_asal_rujukan where id_asal_rujukan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'asal_rujukan');
        else
            return '';
    }

    public function getPerujuk($params) {
        $query = "select nama_perujuk from rm_perujuk where id_perujuk='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'nama_perujuk');
        else
            return '';
    }

    public function getTitle($params) {
        $query = "SELECT title FROM rm_title WHERE id_title='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'title');
        else
            return '';
    }

    public function getTindakanMedis($params) {
        $query = "SELECT tindakan_medis FROM rm_tindakan_medis WHERE id_tindakan_medis='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tindakan_medis');
        else
            return '';
    }

    public function cekTitle($params) {
        $query = "SELECT id_title FROM rm_title WHERE id_title='" . $params . "'";
        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'title');
        }
        return $res;
    }

    public function getKelaminPasien($params) {
        $query = "SELECT id_kelamin FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return $this->getKelamin(@mysql_result($result, 0, 'id_kelamin'));
        else
            return '';
    }

    public function getKelamin($params) {
        $query = "SELECT kelamin FROM rm_kelamin WHERE id_kelamin='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'kelamin');
        else
            return '';
    }

    public function cekKelamin($params) {
        $query = "SELECT id_kelamin FROM rm_kelamin WHERE id_kelamin='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'kelamin');
        }
        return $res;
    }

    public function getMarital($params) {
        $query = "SELECT marital FROM rm_marital WHERE id_marital='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'marital');
        else
            return '';
    }

    public function getAlamatPasien($params) {
        $query = "SELECT alamat FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'alamat');
        else
            return '';
    }

    public function cekMarital($params) {
        $query = "SELECT id_marital FROM rm_marital WHERE id_marital='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'marital');
        }
        return $res;
    }

    public function getPendidikan($params) {
        $query = "SELECT pendidikan FROM rm_pendidikan WHERE id_pendidikan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'pendidikan');
        else
            return '';
    }

    public function cekPendidikan($params) {
        $query = "SELECT id_pendidikan FROM rm_pendidikan WHERE id_pendidikan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'pendidikan');
        }
        return $res;
    }

    public function getTipeAsuransi($params) {
        $query = "SELECT tipe_asuransi FROM rm_tipe_asuransi WHERE id_tipe_asuransi='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tipe_asuransi');
        else
            return '';
    }

    public function getAsuransiPasien($params) {
        $query = "SELECT id_tipe_asuransi FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            if (@mysql_result($result, 0, 'id_tipe_asuransi') == 1)
                return TRUE;
        }
    }

    public function getTipeAsuransiPasien($params) {
        $query = "SELECT id_tipe_asuransi FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'id_tipe_asuransi');
        }
    }

    public function cekTipeAsuransi($params) {
        $query = "SELECT id_tipe_asuransi FROM rm_tipe_asuransi WHERE id_tipe_asuransi='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'tipe_asuransi');
        }
        return $res;
    }

    public function getTipePendaftaran($params) {
        $query = "SELECT tipe_pendaftaran FROM rm_tipe_pendaftaran WHERE id_tipe_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tipe_pendaftaran');
        else
            return '';
    }

    public function getDokter($params) {
        $query = "SELECT nama_dokter FROM rm_dokter WHERE id_dokter='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'nama_dokter');
        else
            return '';
    }

    public function getPegawai($params) {
        $query = "SELECT nama_pegawai FROM rm_pegawai WHERE id_pegawai='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'nama_pegawai');
        else
            return '';
    }
    
    public function getPegawaiNip($params) {
        $query = "SELECT nama_pegawai FROM rm_pegawai WHERE nip='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'nama_pegawai');
        else
            return '';
    }
    
    public function getPegawaiUser($params) {
        $query = "SELECT usr_name FROM rm_pegawai WHERE nip='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'usr_name');
        else
            return '';
    }

    public function getDetailKamarId($params) {
        $query = "SELECT id_detail_kamar FROM rm_penggunaan_kamar WHERE id_pendaftaran='" . $params . "' AND status<>'2'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'id_detail_kamar');
        else
            return '';
    }

    public function getIdDokterDaftar($params) {
        $query = "SELECT id_dokter FROM rm_pendaftaran WHERE id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'id_dokter');
        else
            return '';
    }

    public function getRadiologi($params) {
        $query = "SELECT radiologi FROM rm_radiologi WHERE id_radiologi='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'radiologi');
        else
            return '';
    }

    public function getFilm($params) {
        $query = "SELECT film FROM rm_film WHERE id_film='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'film');
        else
            return '';
    }

    public function getFlag($params) {
        if ($params == '1')
            return 'Ya';
        else
            return 'Tidak';
    }

    public function getOperator($params) {
        $query = "SELECT nama_pelaku FROM rm_pelaku_tindakan WHERE id_pelaku_tindakan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'nama_pelaku');
        else
            return '';
    }

    public function getTarif($params) {
        $query = "SELECT tarif FROM rm_tarif_tindakan WHERE id_tarif_tindakan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tarif');
        else
            return '';
    }

    public function getIdTindakan($params) {
        $query = "SELECT id_tindakan FROM rm_detail_tindakan WHERE id_detail_tindakan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'id_tindakan');
        else
            return '';
    }

    public function getDiagnosa($params, $jenis) {
        $query = "SELECT " . $jenis . " FROM rm_penyakit WHERE id_penyakit='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, $jenis);
        else
            return '';
    }

    public function getTindakan($params, $jenis) {
        $query = "SELECT b." . $jenis . " FROM rm_detail_tindakan a, rm_tindakan b WHERE b.id_tindakan=a.id_tindakan and a.id_detail_tindakan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, $jenis);
        else
            return '';
    }

    public function getTipePasien($params) {
        $query = "SELECT tipe_pasien FROM rm_tipe_pasien WHERE id_tipe_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tipe_pasien');
        else
            return '';
    }

    public function getTipePasienId($params) {
        $query = "SELECT id_tipe_pasien FROM rm_pasien WHERE id_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'id_tipe_pasien');
        else
            return '';
    }

    public function getTipeTarif($params) {
        $query = "SELECT tipe_tarif FROM rm_tipe_tarif WHERE id_tipe_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tipe_tarif');
        else
            return 'umum';
    }

    public function cekTipePasien($params) {
        $query = "SELECT id_tipe_pasien FROM rm_tipe_pasien WHERE id_tipe_pasien='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'tipe_pasien');
        }
        return $res;
    }

    public function getKelurahan($params) {
        $query = "SELECT kelurahan FROM rm_kelurahan WHERE id_kelurahan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'kelurahan');
        else
            return '';
    }

    public function getIdKelas($params) {
        $query = "SELECT id_kelas, id_tipe_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $params . "' and del_flag<>1";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            if (@mysql_result($result, 0, 'id_tipe_pendaftaran') == "6") {
                $q_kelas = "select id_kelas from rm_penggunaan_kamar where id_pendaftaran='" . $params . "' and status='1' and del_flag<>1";
                $r_kelas = $this->runQuery($q_kelas);
                return @mysql_result($r_kelas, 0, 'id_kelas');
            } else {
                return @mysql_result($result, 0, 'id_kelas');
            }
        else
            return '';
    }

    public function getIdKelasKeluar($params, $param2) {
        $query = "SELECT id_kelas, id_tipe_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $params . "' and del_flag<>1";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            if (@mysql_result($result, 0, 'id_tipe_pendaftaran') == "6") {
                $q_kelas = "select id_kelas from rm_penggunaan_kamar where id_pendaftaran='" . $params . "' and id_penggunaan_kamar='" . $param2 . "' and del_flag<>1";
                $r_kelas = $this->runQuery($q_kelas);
                return @mysql_result($r_kelas, 0, 'id_kelas');
            } else {
                return @mysql_result($result, 0, 'id_kelas');
            }
        else
            return '';
    }

    public function cekKelurahan($params) {
        $query = "SELECT id_kelurahan FROM rm_kelurahan WHERE id_kelurahan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'kelurahan');
        }
        return $res;
    }

    public function getKecamatan($params) {
        $query = "SELECT kecamatan FROM rm_kecamatan WHERE id_kecamatan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'kecamatan');
        else
            return '';
    }

    public function cekKecamatan($params) {
        $query = "SELECT id_kecamatan FROM rm_kecamatan WHERE id_kecamatan='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'kecamatan');
        }
        return $res;
    }

    public function getKota($params) {
        $query = "SELECT kota FROM rm_kota WHERE id_kota='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'kota');
        else
            return '';
    }

    public function cekKota($params) {
        $query = "SELECT id_kota FROM rm_kota WHERE id_kota='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'kota');
        }
        return $res;
    }

    public function getAgama($params) {
        $query = "SELECT agama FROM rm_agama WHERE id_agama='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'agama');
        else
            return '';
    }

    public function getAntrian($params) {
        $query = "SELECT no_antrian FROM rm_antrian WHERE id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'no_antrian');
        else
            return '';
    }

    public function cekAgama($params) {
        $query = "SELECT id_agama FROM rm_agama WHERE id_agama='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'agama');
        }
        return $res;
    }

    public function getGolDarah($params) {
        $query = "SELECT gol_darah FROM rm_gol_darah WHERE id_gol_darah='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'gol_darah');
        else
            return '';
    }

    public function cekGolDarah($params) {
        $query = "SELECT id_gol_darah FROM rm_gol_darah WHERE id_gol_darah='" . $params . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $res = $params;
        } else {
            $res = $this->getFieldId($params, 'gol_darah');
        }
        return $res;
    }

    public function cekPendaftaran($params) {
        $query = "SELECT status_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        $status = @mysql_result($result, 0, 'status_pendaftaran');

        if ($status == '1' || $status=='0')
            return true;
    }

    public function setStatusDaftar($params) {
        $q_daftar = "update rm_pendaftaran set status_pendaftaran='0' where id_pendaftaran='" . $params . "'";
        $r_daftar = $this->runQuery($q_daftar);
    }

    public function setCloseDaftar($params) {
        $q_daftar = "update rm_pendaftaran set status_pendaftaran='2' where id_pendaftaran='" . $params . "'";
        $r_daftar = $this->runQuery($q_daftar);
    }

    public function getDokterDiagnosa($params) {
        $query = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_dokter');
    }

    public function getDokterPenanggungJawab($params) {
        $query = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_dokter');
    }

    public function getRuangDaftar($params) {
        $query = "select id_ruang from rm_pendaftaran where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_ruang');
    }

    public function getKamarDaftar($params) {
        $query = "select id_detail_kamar as id from rm_penggunaan_kamar where id_pendaftaran='" . $params . "' AND status='1' AND del_flag<>'1'";
        $result = $this->runQuery($query);

        $k_query = "select id_kamar FROM rm_detail_kamar where id_detail_kamar='" . @mysql_result($result, 0, 'id') . "'";
        $r_query = $this->runQuery($k_query);

        return @mysql_result($r_query, 0, 'id_kamar');
    }

    public function getAsalDaftar($params) {
        $query = "select id_asal_pendaftaran from rm_pendaftaran where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_asal_pendaftaran');
    }

    public function getAsalRuang($params) {
        $query = "select id_ruang_asal from rm_pendaftaran where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_ruang_asal');
    }

    public function getTarifDaftar($params) {
        $query = "select biaya_pendaftaran from rm_pendaftaran where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'biaya_pendaftaran');
    }

    public function getTarifTindakanRuang($id_tindakan, $id_kelas) {
        $query = "select tarif from rm_tarif_tindakan where id_tindakan='" . $id_tindakan . "' and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function getTarifVisit($id_dokter, $id_kelas) {
        $query = "SELECT tarif FROM rm_tarif_visit a, rm_dokter b where b.id_dokter='" . $id_dokter . "' and a.id_kelas='" . $id_kelas . "' AND a.id_jenis_dokter=b.id_jenis_dokter";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function getTarifPemeriksaan($id_pendaftaran, $id_ruang) {
        $query = "select tarif from rm_visit where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $id_ruang . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function getJasaKarcis($field, $id_ruang) {
        $query = "select " . $field . " from rm_jasa_karcis where id_ruang='" . $id_ruang . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, $field);
    }

    public function getJasaTindakan($field, $id_ruang, $id_kelas, $id_pelaku_tindakan) {
        $query = "select " . $field . " from rm_jasa_tindakan where id_ruang='" . $id_ruang . "' and id_kelas='" . $id_kelas . "' and id_pelaku_tindakan='" . $id_pelaku_tindakan . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, $field);
    }

    public function getJasaTindakanMedis($field, $id_kelas) {
        $query = "select " . $field . " from rm_jasa_tindakan_ibs where id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, $field);
    }

    public function getJasaRadiologi($field, $id_kelompok_radiologi, $id_kelas) {
        $query = "select " . $field . " from rm_jasa_radiologi where id_kelompok_radiologi='" . $id_kelompok_radiologi . "' and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, $field);
    }

    public function getJasaLaboratorium($field, $id_kelas) {
        $query = "select " . $field . " from rm_jasa_laboratorium where id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, $field);
    }

    public function generateJasaPemeriksaan($id_pendaftaran) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $id_dokter = $this->getDokterDiagnosa($id_pendaftaran);
        $id_ruang = $this->getRuangDaftar($id_pendaftaran);
        $tarif = $this->getTarifPemeriksaan($id_pendaftaran, $id_ruang);

        $jasa_sarana = $this->getJasaKarcis('jasa_sarana', $id_ruang) * $tarif;
        $jasa_layanan = $this->getJasaKarcis('jasa_pelayanan', $id_ruang) * $tarif;
        $unit_penghasil = $this->getJasaKarcis('unit_penghasil', $id_ruang) * $tarif;
        $direksi = $this->getJasaKarcis('direksi', $id_ruang) * $tarif;
        $remunerasi = $this->getJasaKarcis('remunerasi', $id_ruang) * $tarif;
        $dokter = $this->getJasaKarcis('dokter', $id_ruang) * $tarif;
        $perawat = $this->getJasaKarcis('perawat', $id_ruang) * $tarif;

        $query = "insert into rm_jasa_pemeriksaan_dokter (
                    id_pendaftaran,
                    id_pasien,
                    id_ruang,
                    id_dokter,
                    tarif,
                    jasa_sarana,
                    jasa_layanan,
                    jasa_unit_penghasil,
                    jasa_direksi,
                    jasa_remunerasi,
                    jasa_dokter,
                    jasa_perawat,
                    id_tipe_pasien
                 ) values (
                    '" . $id_pendaftaran . "',
                    '" . $id_pasien . "',
                    '" . $id_ruang . "',
                    '" . $id_dokter . "',
                    '" . $tarif . "',
                    '" . $jasa_sarana . "',
                    '" . $jasa_layanan . "',
                    '" . $unit_penghasil . "',
                    '" . $direksi . "',
                    '" . $remunerasi . "',
                    '" . $dokter . "',
                    '" . $perawat . "',
                    '" . $tipe_pasien . "'
                 )";

        $result = $this->runQuery($query);
        if ($result)
            return TRUE;
    }

    public function generateJasaPendaftaran($id_pendaftaran) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $id_dokter = $this->getDokterDiagnosa($id_pendaftaran);
        $id_ruang = $this->getRuangDaftar($id_pendaftaran);
        $tarif = $this->getTarifDaftar($id_pendaftaran);

        $jasa_sarana = $this->getJasaKarcis('jasa_sarana', $id_ruang) * $tarif;
        $jasa_layanan = $this->getJasaKarcis('jasa_pelayanan', $id_ruang) * $tarif;
        $unit_penghasil = $this->getJasaKarcis('unit_penghasil', $id_ruang) * $tarif;
        $direksi = $this->getJasaKarcis('direksi', $id_ruang) * $tarif;
        $remunerasi = $this->getJasaKarcis('remunerasi', $id_ruang) * $tarif;
        $dokter = $this->getJasaKarcis('dokter', $id_ruang) * $tarif;
        $perawat = $this->getJasaKarcis('perawat', $id_ruang) * $tarif;

        $query = "insert into rm_jasa_pendaftaran (
                    id_pendaftaran,
                    id_pasien,
                    id_ruang,
                    id_dokter,
                    tarif,
                    jasa_sarana,
                    jasa_layanan,
                    jasa_unit_penghasil,
                    jasa_direksi,
                    jasa_remunerasi,
                    jasa_dokter,
                    jasa_perawat,
                    id_tipe_pasien
                 ) values (
                    '" . $id_pendaftaran . "',
                    '" . $id_pasien . "',
                    '" . $id_ruang . "',
                    '" . $id_dokter . "',
                    '" . $tarif . "',
                    '" . $jasa_sarana . "',
                    '" . $jasa_layanan . "',
                    '" . $unit_penghasil . "',
                    '" . $direksi . "',
                    '" . $remunerasi . "',
                    '" . $dokter . "',
                    '" . $perawat . "',
                    '" . $tipe_pasien . "'
                 )";

        $result = $this->runQuery($query);
        if ($result)
            return TRUE;
    }

    public function generateJasaPoli($id_pendaftaran) {
        $q_tindakan = "select * from rm_tindakan_ruang where id_pendaftaran='" . $id_pendaftaran . "' ORDER BY id_tindakan_ruang DESC";
        $r_tindakan = $this->runQuery($q_tindakan);
        $i = 0;

        while ($rec_tindakan = mysql_fetch_array($r_tindakan)) {
            $id_pasien = $rec_tindakan['id_pasien'];
            $tipe_pasien = $rec_tindakan['id_tipe_pasien'];
            $id_ruang = $rec_tindakan['id_ruang'];
            $id_kelas = $rec_tindakan['id_kelas'];
            if ($id_ruang == '48') {
                $id_ruang = 44;
            } else if ($id_ruang == '49') {
                $id_ruang = 32;
            }

            $jasa_sarana = $this->getJasaTindakan('jasa_sarana', $id_ruang, $id_kelas, $rec_tindakan['id_pelaku_tindakan']) * $rec_tindakan['tarif'];
            $jasa_layanan = $this->getJasaTindakan('jasa_pelayanan', $id_ruang, $id_kelas, $rec_tindakan['id_pelaku_tindakan']) * $rec_tindakan['tarif'];
            $unit_penghasil = $this->getJasaTindakan('unit_penghasil', $id_ruang, $id_kelas, $rec_tindakan['id_pelaku_tindakan']) * $rec_tindakan['tarif'];
            $direksi = $this->getJasaTindakan('direksi', $id_ruang, $id_kelas, $rec_tindakan['id_pelaku_tindakan']) * $rec_tindakan['tarif'];
            $remunerasi = $this->getJasaTindakan('remunerasi', $id_ruang, $id_kelas, $rec_tindakan['id_pelaku_tindakan']) * $rec_tindakan['tarif'];
            $dokter = $this->getJasaTindakan('dokter', $id_ruang, $id_kelas, $rec_tindakan['id_pelaku_tindakan']) * $rec_tindakan['tarif'];
            $perawat = $this->getJasaTindakan('perawat', $id_ruang, $id_kelas, $rec_tindakan['id_pelaku_tindakan']) * $rec_tindakan['tarif'];

            $query = "insert into rm_jasa_tindakan_poli (
                        jns_tindakan,
                        id_pendaftaran,
                        id_pasien,
                        id_detail_tindakan,
                        id_ruang,
                        id_kelas,
                        id_pelaku_tindakan,
                        id_dokter,
                        tarif,
                        jasa_sarana,
                        jasa_layanan,
                        jasa_unit_penghasil,
                        jasa_direksi,
                        jasa_remunerasi,
                        jasa_dokter,
                        jasa_perawat,
                        id_tindakan_ruang,
                        id_tipe_pasien
                     ) values (
                        '1',
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $rec_tindakan['id_detail_tindakan'] . "',
                        '" . $id_ruang . "',
                        '" . $id_kelas . "',
                        '" . $rec_tindakan['id_pelaku_tindakan'] . "',
                        '" . $rec_tindakan['id_dokter'] . "',
                        '" . $rec_tindakan['tarif'] . "',
                        '" . $jasa_sarana . "',
                        '" . $jasa_layanan . "',
                        '" . $unit_penghasil . "',
                        '" . $direksi . "',
                        '" . $remunerasi . "',
                        '" . $dokter . "',
                        '" . $perawat . "',
                        '" . $rec_tindakan['id_tindakan_ruang'] . "',
                        '" . $tipe_pasien . "'
                     )";

            $result = $this->runQuery($query);
            if ($result)
                $i++;
        }
        return true;
    }

    public function generateJasaMedis($id_pendaftaran) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $id_ruang = $this->getRuangDaftar($id_pendaftaran);
        $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);

        $i = 0;

        $q_tindakan = "select * from rm_tindakan_ruang_medis where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
        $r_tindakan = $this->runQuery($q_tindakan);

        while ($rec_tindakan = mysql_fetch_array($r_tindakan)) {
            $jasa_sarana = $this->getJasaTindakanMedis('jasa_sarana', $id_kelas) * $rec_tindakan['tarif'];
            $jasa_layanan = $this->getJasaTindakanMedis('jasa_pelayanan', $id_kelas) * $rec_tindakan['tarif'];
            $unit_penghasil = $this->getJasaTindakanMedis('unit_penghasil', $id_kelas) * $rec_tindakan['tarif'];
            $direksi = $this->getJasaTindakanMedis('direksi', $id_kelas) * $rec_tindakan['tarif'];
            $remunerasi = $this->getJasaTindakanMedis('remunerasi', $id_kelas) * $rec_tindakan['tarif'];
            $operator = $this->getJasaTindakanMedis('operator', $id_kelas) * $rec_tindakan['tarif'];
            $anastesi = $this->getJasaTindakanMedis('anastesi', $id_kelas) * $rec_tindakan['tarif'];
            $tim_operator = ($this->getJasaTindakanMedis('tim_operator', $id_kelas) * $rec_tindakan['tarif']) + $rec_tindakan['penambahan_tarif'];
            $tim_anastesi = $this->getJasaTindakanMedis('tim_anastesi', $id_kelas) * $rec_tindakan['tarif'];
            $ass_tim_operator = $this->getJasaTindakanMedis('ass_tim_operator', $id_kelas) * $rec_tindakan['tarif'];
            $ass_tim_anastesi = $this->getJasaTindakanMedis('ass_tim_anastesi', $id_kelas) * $rec_tindakan['tarif'];

            $query = "insert into rm_jasa_tindakan_bedah (
                        id_pendaftaran,
                        id_pasien,
                        id_tindakan_medis,
                        id_kelas,
                        tarif,
                        jasa_sarana,
                        jasa_pelayanan,
                        unit_penghasil,
                        direksi,
                        remunerasi,
                        operator,
                        anastesi,
                        tim_operator,
                        ass_tim_operator,
                        tim_anastesi,
                        ass_tim_anastesi,
                        id_tipe_pasien
                     ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $rec_tindakan['id_tindakan_medis'] . "',
                        '" . $id_kelas . "',
                        '" . $rec_tindakan['tarif'] . "',
                        '" . $jasa_sarana . "',
                        '" . $jasa_layanan . "',
                        '" . $unit_penghasil . "',
                        '" . $direksi . "',
                        '" . $remunerasi . "',
                        '" . $operator . "',
                        '" . $anastesi . "',
                        '" . $tim_operator . "',
                        '" . $ass_tim_operator . "',
                        '" . $tim_anastesi . "',
                        '" . $ass_tim_anastesi . "',
                        '" . $tipe_pasien . "'
                    )";

            $result = $this->runQuery($query);
            if ($result)
                $i++;
        }
        return true;
    }

    public function generateJasaRadiologi($id_pendaftaran) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $id_ruang = $this->getRuangDaftar($id_pendaftaran);
        $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);

        $i = 0;

        $q_radiologi = "select * from rm_detail_radiologi where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1' and tarif!=0";
        $r_radiologi = $this->runQuery($q_radiologi);

        while ($rec_radiologi = mysql_fetch_array($r_radiologi)) {
            $id_kelompok_radiologi = $this->getKelRadiologi($rec_radiologi['id_radiologi']);
            $jasa_sarana = $this->getJasaRadiologi('jasa_sarana', $id_kelompok_radiologi, $id_kelas) * $rec_radiologi['tarif'];
            $jasa_layanan = $this->getJasaRadiologi('jasa_pelayanan', $id_kelompok_radiologi, $id_kelas) * $rec_radiologi['tarif'];
            $unit_penghasil = $this->getJasaRadiologi('unit_penghasil', $id_kelompok_radiologi, $id_kelas) * $rec_radiologi['tarif'];
            $direksi = $this->getJasaRadiologi('direksi', $id_kelompok_radiologi, $id_kelas) * $rec_radiologi['tarif'];
            $remunerasi = $this->getJasaRadiologi('remunerasi', $id_kelompok_radiologi, $id_kelas) * $rec_radiologi['tarif'];
            $dokter = $this->getJasaRadiologi('dokter', $id_kelompok_radiologi, $id_kelas) * $rec_radiologi['tarif'];
            $perawat = $this->getJasaRadiologi('perawat', $id_kelompok_radiologi, $id_kelas) * $rec_radiologi['tarif'];

            $query = "insert into rm_jasa_tindakan_radiologi (
                        id_pendaftaran,
                        id_pasien,
                        id_kelas,
                        id_radiologi,
                        id_kelompok_radiologi,
                        id_dokter,
                        tarif,
                        jasa_sarana,
                        jasa_layanan,
                        jasa_unit_penghasil,
                        jasa_direksi,
                        jasa_remunerasi,
                        jasa_dokter,
                        jasa_perawat,
                        id_tipe_pasien
                     ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $id_kelas . "',
                        '" . $rec_radiologi['id_radiologi'] . "',
                        '" . $id_kelompok_radiologi . "',
                        '26',
                        '" . $rec_radiologi['tarif'] . "',
                        '" . $jasa_sarana . "',
                        '" . $jasa_layanan . "',
                        '" . $unit_penghasil . "',
                        '" . $direksi . "',
                        '" . $remunerasi . "',
                        '" . $dokter . "',
                        '" . $perawat . "',
                        '" . $tipe_pasien . "'
                     )";

            $result = $this->runQuery($query);
            if ($result)
                $i++;
        }
        return $i;
    }

    public function generateJasaLaboratorium($id_pendaftaran) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);

        $i = 0;

        $q_laboratorium = "select * from rm_detail_laboratorium where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1' and tarif!=0";
        $r_laboratorium = $this->runQuery($q_laboratorium);

        while ($rec_laboratorium = mysql_fetch_array($r_laboratorium)) {
            $jasa_sarana = $this->getJasaLaboratorium('jasa_sarana', $id_kelas) * $rec_laboratorium['tarif'];
            $jasa_layanan = $this->getJasaLaboratorium('jasa_pelayanan', $id_kelas) * $rec_laboratorium['tarif'];
            $unit_penghasil = $this->getJasaLaboratorium('unit_penghasil', $id_kelas) * $rec_laboratorium['tarif'];
            $direksi = $this->getJasaLaboratorium('direksi', $id_kelas) * $rec_laboratorium['tarif'];
            $remunerasi = $this->getJasaLaboratorium('remunerasi', $id_kelas) * $rec_laboratorium['tarif'];
            $dokter = $this->getJasaLaboratorium('dokter', $id_kelas) * $rec_laboratorium['tarif'];
            $perawat = $this->getJasaLaboratorium('perawat', $id_kelas) * $rec_laboratorium['tarif'];

            $query = "insert into rm_jasa_tindakan_laboratorium (
                        id_pendaftaran,
                        id_pasien,
                        id_laboratorium,
                        id_kelas,
                        id_dokter,
                        tarif,
                        jasa_sarana,
                        jasa_layanan,
                        jasa_unit_penghasil,
                        jasa_direksi,
                        jasa_remunerasi,
                        jasa_dokter,
                        jasa_perawat,
                        id_tipe_pasien
                     ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $rec_laboratorium['id_laboratorium'] . "',
                        '" . $id_kelas . "',
                        '29',
                        '" . $rec_laboratorium['tarif'] . "',
                        '" . $jasa_sarana . "',
                        '" . $jasa_layanan . "',
                        '" . $unit_penghasil . "',
                        '" . $direksi . "',
                        '" . $remunerasi . "',
                        '" . $dokter . "',
                        '" . $perawat . "',
                        '" . $tipe_pasien . "'
                     )";

            $result = $this->runQuery($query);
            if ($result)
                $i++;
        }
        return $i;
    }

    public function getKelLab($params) {
        $query = "SELECT kelompok_lab FROM rm_kelompok_lab WHERE id_kelompok_lab='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'kelompok_lab');
    }

    public function getKelasPendaftaran($params) {
        $query = "SELECT id_kelas FROM rm_pendaftaran WHERE id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_kelas');
    }

    public function getKelRadiologi($params) {
        $query = "SELECT id_kelompok_radiologi FROM rm_radiologi WHERE id_radiologi='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_kelompok_radiologi');
    }

    public function getKelas($params) {
        $query = "SELECT kelas FROM rm_kelas WHERE id_kelas='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'kelas');
    }

    public function getRuang($params) {
        $query = "SELECT ruang FROM rm_ruang WHERE id_ruang='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'ruang');
    }

    public function getTipeRuangId($params) {
        $query = "SELECT id_tipe_ruang FROM rm_ruang WHERE id_ruang='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_tipe_ruang');
    }

    public function getTipeRuang($params) {
        $id_tipe_ruang = $this->getTipeRuangId($params);
        $query = "SELECT tipe_ruang FROM rm_tipe_ruang WHERE id_tipe_ruang='" . $id_tipe_ruang . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tipe_ruang');
    }

    public function setStatusKamar($params) {
        $query = "update rm_detail_kamar set status='1' WHERE id_detail_kamar='" . $params . "'";
        $result = $this->runQuery($query);
    }

    public function getCitoLab($params) {
        $query = "SELECT cito FROM rm_pemeriksaan_lab WHERE id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'cito');
    }

    public function getKamar($params) {
        $query = "SELECT kamar FROM rm_kamar WHERE id_kamar='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'kamar');
    }

    public function getKamarDet($params) {
        $query = "SELECT id_kamar FROM rm_detail_kamar WHERE id_detail_kamar='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_kamar');
    }

    public function getBed($params) {
        $query = "SELECT id_kamar, bed FROM rm_detail_kamar WHERE id_detail_kamar='" . $params . "'";
        $result = $this->runQuery($query);

        $kamar = $this->getKamar(@mysql_result($result, 0, 'id_kamar'));

        return $kamar . " - " . @mysql_result($result, 0, 'bed');
    }

    public function jDecode($value) {
        if (version_compare(PHP_VERSION, "5.2", "<")) {
            require_once("JSON.php");
            $json = new Services_JSON();
            $data = $json->decode(stripslashes($value));
        } else {
            $data = json_decode(stripslashes($value));
        }

        return $data;
    }

    function getUmur($unformattedDate) {
        if ($unformattedDate != "") {
            $friendlyAgeText = array();

            $oMD = explode('-', $unformattedDate);
            $yearDiff = date("Y") - $oMD[0];
            $monthDiff = date("m") - $oMD[1];
            $dayDiff = date("d") - $oMD[2];

            if ($monthDiff < 0) {
                $yearDiff--;
                $monthDiff += 12;
            }
            if ($dayDiff < 0) {
                $monthDiff--;
                $dayDiff += 30;
            }

            switch ($yearDiff):
                case 0:
                    break;
                default:
                    $friendlyAgeText[] = $yearDiff . " Th";
                    break;
            endswitch;

            switch ($monthDiff):
                case 0: break;
                default:
                    $friendlyAgeText[] = $monthDiff . " Bl";
                    break;
            endswitch;

            switch ($dayDiff):
                case 0: break;
                default:
                    $friendlyAgeText[] = $dayDiff . " Hr";
                    break;
            endswitch;

            return join(" ", $friendlyAgeText);
        } else {
            return "";
        }
    }

    function replaceString($value) {
        $return = str_replace(',', '&cedil', $value);

        return $return;
    }

    function getDaysOld($date1, $date2) {
        return round(abs($date1 - $date2) / 60 / 60 / 24);
    }

    public function getIdTipePendaftaran($params) {
        $query = "select id_tipe_pendaftaran from rm_pendaftaran where id_pendaftaran='" . $params . "'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'id_tipe_pendaftaran');
        else
            return '';
    }

    public function saveClosePerawatan($id_pendaftaran, $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan) {
        $return = 0;
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $query = "insert into rm_pasien_keluar (
                        id_pendaftaran,
                        id_pasien,
                        id_keadaan,
                        id_cara_keluar,
                        tgl_keluar,
                        keterangan,
                        id_tipe_pasien
                    ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $id_keadaan . "',
                        '" . $id_cara_keluar . "',
			'" . $this->formatDateDb($tgl_keluar) . date(' H:i:s') . "',
                        '" . $keterangan . "',
                        '" . $tipe_pasien . "'
                    )";

        $result = $this->runQuery($query);

        if ($result) {
            $id_ruang = $this->getRuangDaftar($id_pendaftaran);
            $this->setCloseDaftar($id_pendaftaran);
            if ($this->getIdTipePendaftaran($id_pendaftaran) != '6' && $id_ruang != '48' && $id_ruang != '49') {
                if ($this->generateJasaPoli($id_pendaftaran)) {
                    if ($this->generateJasaPendaftaran($id_pendaftaran)) {
                        if ($id_ruang == '20') {
                            $this->generateJasaPemeriksaan($id_pendaftaran);
                        }
                        $return = '1';
                    } else {
                        $return = '0';
                    }
                } else {
                    $return = '0';
                }
            } else if ($id_ruang != '48' && $id_ruang != '49') {
                if ($this->generateJasaPoli($id_pendaftaran)) {
//                    $q_daftar = "select id_pendaftaran from rm_pendaftaran 
//                                    where id_asal_pendaftaran='" . $id_pendaftaran . "' and status_pendaftaran!='2'";
//                    $r_daftar = $this->runQuery($q_daftar);
//                    if (@mysql_num_rows($r_daftar) > 0) {
//                        while ($data = @mysql_fetch_array($r_daftar)) {
//                            if ($this->generateJasaPoli($data['id_pendaftaran'])) {
//                                $q_kamar = "select date(tgl_masuk) as tgl_masuk from rm_penggunaan_kamar 
//                                                where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
//                                $r_kamar = $this->runQuery($q_kamar);
//                                $lama = $this->jmlHari(@mysql_result($r_kamar, 0, 'tgl_masuk'), date('Y-m-d')) + 1;
//                                $q_update = "update rm_penggunaan_kamar set
//                                                    tgl_keluarDB='" . date('Y-m-d H:i:s') . "',
//                                                    keterangan_selesai='Keluar Rumah Sakit',
//                                                    lama_php='" . $lama . "',
//                                                    status='3'
//                                                where id_pendaftaran='" . $id_pendaftaran . "' and status<>'2'";
//                                $r_update = $this->runQuery($q_update);
//                                $this->setCloseDaftar($data['id_pendaftaran']);
//                                $return = '2';
//                            } else {
//                                $return = '0';
//                            }
//                        }
//                    } else {
                    $q_kamar = "select date(tgl_masuk) as tgl_masuk from rm_penggunaan_kamar where id_pendaftaran='" . $id_pendaftaran . "' ORDER BY id_penggunaan_kamar DESC LIMIT 0,1";
                    $r_kamar = $this->runQuery($q_kamar);
                    $lama = $this->jmlHari(@mysql_result($r_kamar, 0, 'tgl_masuk'), date('Y-m-d')) + 1;
                    $q_update = "update rm_penggunaan_kamar set
                                tgl_keluarDB='" . date('Y-m-d H:i:s') . "',
                                keterangan_selesai='Keluar Rumah Sakit',
                                lama_php='" . $lama . "',
                                status='3'
                                where id_pendaftaran='" . $id_pendaftaran . "' and status<>'2'";
                    $r_update = $this->runQuery($q_update);
                    $this->setCloseDaftar($id_pendaftaran);
                    $return = '2';
//                }
                } else {
                    $return = '0';
                }
            }
        } else {
            $return = '0';
        }
        return $return;
    }

    public function saveClosePerawatanMedis($id_pendaftaran, $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $query = "insert into rm_pasien_keluar (
                        id_pendaftaran,
                        id_pasien,
                        id_keadaan,
                        id_cara_keluar,
                        tgl_keluar,
                        keterangan,
                        id_tipe_pasien
                    ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $id_keadaan . "',
                        '" . $id_cara_keluar . "',
                            '" . $this->formatDateDb($tgl_keluar) . date(' H:i:s') . "',
                        '" . $keterangan . "',
                        '" . $tipe_pasien . "'
                            
                    )";

        $result = $this->runQuery($query);

        if ($result) {
            $this->setCloseDaftar($id_pendaftaran);
            if ($this->getIdTipePendaftaran($id_pendaftaran) != '6') {
                if ($this->generateJasaMedis($id_pendaftaran)) {
                    if ($this->generateJasaPoli($id_pendaftaran)) {
                        if ($this->generateJasaPendaftaran($id_pendaftaran)) {
                            $return = '1';
                            $q_kamar = "select date(tgl_masuk) as tgl_masuk from rm_penggunaan_kamar where id_pendaftaran='" . $id_pendaftaran . "'";
                            $r_kamar = $this->runQuery($q_kamar);
                            $lama = $this->jmlHari(@mysql_result($r_kamar, 0, 'tgl_masuk'), date('Y-m-d')) + 1;
                            $q_update = "update rm_penggunaan_kamar set
                                            tgl_keluarDB='" . date('Y-m-d H:i:s') . "',
                                            keterangan_selesai='Keluar Rumah Sakit',
                                            lama_php='" . $lama . "',
                                            status='3'
                                        where id_pendaftaran='" . $id_pendaftaran . "' and status<>'2'";
                            $r_update = $this->runQuery($q_update);
                        } else {
                            $return = '0';
                        }
                    } else {
                        $return = '0';
                    }
                } else {
                    $return = '0';
                }
            } else {
                if ($this->generateJasaMedis($id_pendaftaran)) {
                    if ($this->generateJasaPendaftaran($id_pendaftaran)) {
                        $q_daftar = "select id_pendaftaran from rm_pendaftaran 
                                    where id_asal_pendaftaran='" . $id_pendaftaran . "' and status_pendaftaran!='2'";
                        $r_daftar = $this->runQuery($q_daftar);
                        while ($data = @mysql_fetch_array($r_daftar)) {
                            if ($this->generateJasaMedis($data['id_pendaftaran'])) {
                                $this->setCloseDaftar($data['id_pendaftaran']);
                                $return = '2';
                            } else {
                                $return = '0';
                            }
                            $q_kamar = "select date(tgl_masuk) as tgl_masuk from rm_penggunaan_kamar where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                            $r_kamar = $this->runQuery($q_kamar);
                            $lama = $this->jmlHari(@mysql_result($r_kamar, 0, 'tgl_masuk'), date('Y-m-d')) + 1;
                            $q_update = "update rm_penggunaan_kamar set
                                            tgl_keluarDB='" . date('Y-m-d H:i:s') . "',
                                            keterangan_selesai='Keluar Rumah Sakit',
                                            lama_php='" . $lama . "',
                                            status='3'
                                        where id_pendaftaran='" . $data['id_pendaftaran'] . "' and status<>'2'";
                            $r_update = $this->runQuery($q_update);
                        }
                    } else {
                        $return = '0';
                    }
                } else {
                    $q_kamar = "select date(tgl_masuk) as tgl_masuk from rm_penggunaan_kamar where id_pendaftaran='" . $id_pendaftaran . "'";
                    $r_kamar = $this->runQuery($q_kamar);
                    $lama = $this->jmlHari(@mysql_result($r_kamar, 0, 'tgl_masuk'), date('Y-m-d')) + 1;
                    $q_update = "update rm_penggunaan_kamar set
                                    tgl_keluarDB='" . date('Y-m-d H:i:s') . "',
                                    keterangan_selesai='Keluar Rumah Sakit',
                                    lama_php='" . $lama . "',
                                    status='3'
                                where id_pendaftaran='" . $id_pendaftaran . "' and status<>'2'";
                    $r_update = $this->runQuery($q_update);
                    $return = '0';
                }
            }
        } else {
            $return = '0';
        }
        return $return;
    }

    public function getHargaObatField($id_obat, $field) {
        $query = "select " . $field . " from rm_tarif_obat where id_obat='" . $id_obat . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, $field);
    }

    public function getPenyakit($params) {
        $query = "select nama_penyakit from rm_penyakit where id_penyakit='" . $params . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'nama_penyakit');
    }

    public function getDiagnosaAkhir($id_pendaftaran, $id_pasien, $field) {
        $query = "SELECT " . $field . " FROM rm_diagnosa WHERE id_pasien='" . $id_pasien . "' 
                  AND id_pendaftaran <> '" . $id_pendaftaran . "' ORDER BY id_diagnosa DESC LIMIT 0,1";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, $field);
        else
            return '';
    }

    public function getDiagnosaAktif($id_pendaftaran, $id_pasien, $field) {
        $query = "SELECT " . $field . " FROM rm_diagnosa WHERE id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, $field);
        else
            return '';
    }

    public function getLabAkhir($id_pendaftaran) {
        $query = "SELECT hasil_laboratorium FROM rm_hasil_laboratorium WHERE id_hasil_laboratorium= 
                  (SELECT MAX(id_hasil_laboratorium) AS id FROM rm_hasil_laboratorium 
                  where id_pendaftaran!='" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'hasil_laboratorium');
        else
            return '';
    }

    public function getKonsulPasien($id_ruang, $id_pasien) {
        $query = "select b.ruang from rm_pendaftaran a, rm_ruang b 
                 where id_ruang_asal='" . $id_ruang . "' and id_pasien='" . $id_pasien . "' and id_tipe_pendaftaran='2'
                 and b.id_ruang=a.id_ruang";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            $return = "";
            $i = 1;
            while ($data = mysql_fetch_array($result)) {
                $return .= $data['ruang'];
                if ($i < @mysql_num_rows($result)) {
                    $return .= ", ";
                }
                $i++;
            }
            return $return;
        } else {
            return '';
        }
    }

    public function getRadiologiAkhir($id_pendaftaran, $id_pasien) {
        $query = "SELECT b.radiologi FROM rm_detail_radiologi a, rm_radiologi b WHERE a.id_pendaftaran= 
                 (SELECT MAX(id_pendaftaran) AS id FROM rm_detail_radiologi 
                 WHERE id_pendaftaran!='" . $id_pendaftaran . "' AND id_pasien='" . $id_pasien . "') AND b.id_radiologi=a.id_radiologi";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'radiologi');
        else
            return '';
    }

    public function getTagihanKamar($id_pasien) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and id_tipe_pendaftaran=6 and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<b>PENGGUNAAN KAMAR</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='11%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='8%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='15%' class='headerTagihan'>Masuk</td>";
            $html .= "<td width='15%' class='headerTagihan'>Keluar</td>";
            $html .= "<td width='8%' class='headerTagihan'>Lama</td>";
            $html .= "<td width='8%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='15%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $total = 0;

            while ($rec = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT b.ruang, c.kelas, DATE(a.tgl_masuk) AS tgl_masuk, DATE(a.tgl_keluar) AS tgl_keluar, a.lama_penggunaan, a.status, a.tarif, d.kamar
                              FROM rm_penggunaan_kamar a, rm_ruang b, rm_kelas c, rm_kamar d, rm_detail_kamar e
                              WHERE b.id_ruang=a.id_ruang AND c.id_kelas=a.id_kelas AND a.id_detail_kamar=e.id_detail_kamar AND e.id_kamar=d.id_kamar
                              AND id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.del_flag<>'1'";
                $result = $this->runQuery($query);

                while ($data = @mysql_fetch_array($result)) {
                    $tarif = $data['tarif'];
                    $plusRuang = "";
                    if ($data['lama_penggunaan'] == 0 && $data['status'] != 2) {
                        $tgl_keluar = "";
                        $lama = $this->jmlHari($data['tgl_masuk'], date('Y-m-d')) + 1;
                    } else {
                        $tgl_keluar = $data['tgl_keluar'];
                        $lama = $data['lama_penggunaan'];
                    }
                    $html .= "<tr>";
                    $html .= "<td width='11%'>" . $data['kamar'] . " " . $plusRuang . "</td>";
                    $html .= "<td width='8%'>" . $data['kelas'] . "</td>";
                    $html .= "<td width='15%'>" . $this->codeDate($data['tgl_masuk']) . "</td>";
                    $html .= "<td width='15%'>" . $this->codeDate($tgl_keluar) . "</td>";
                    $html .= "<td width='8%'>" . $lama . "</td>";
                    $html .= "<td width='8%' align='right'>Rp. " . number_format($tarif, 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format(($lama * $tarif), 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += ( $lama * $tarif);
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='6' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }

        return $html;
    }

    public function getTagihanKamarKeluar($id_pendaftaran) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<b>PENGGUNAAN KAMAR</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='11%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='8%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='15%' class='headerTagihan'>Masuk</td>";
            $html .= "<td width='15%' class='headerTagihan'>Keluar</td>";
            $html .= "<td width='8%' class='headerTagihan'>Lama</td>";
            $html .= "<td width='8%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='15%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $total = 0;

            while ($rec = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT b.ruang, c.kelas, DATE(a.tgl_masuk) AS tgl_masuk, DATE(a.tgl_keluar) AS tgl_keluar, a.lama_penggunaan, a.status, a.tarif, d.kamar
                              FROM rm_penggunaan_kamar a, rm_ruang b, rm_kelas c, rm_kamar d, rm_detail_kamar e
                              WHERE b.id_ruang=a.id_ruang AND c.id_kelas=a.id_kelas AND a.id_detail_kamar=e.id_detail_kamar AND e.id_kamar=d.id_kamar
                              AND id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.del_flag<>'1'";
                $result = $this->runQuery($query);

                while ($data = @mysql_fetch_array($result)) {
                    $tarif = $data['tarif'];
                    $plusRuang = "";
                    if ($data['lama_penggunaan'] == 0 && $data['status'] != 2) {
                        $tgl_keluar = "";
                        $lama = $this->jmlHari($data['tgl_masuk'], date('Y-m-d')) + 1;
                    } else {
                        $tgl_keluar = $data['tgl_keluar'];
                        $lama = $data['lama_penggunaan'];
                    }
                    $html .= "<tr>";
                    $html .= "<td width='11%'>" . $data['kamar'] . " " . $plusRuang . "</td>";
                    $html .= "<td width='8%'>" . $data['kelas'] . "</td>";
                    $html .= "<td width='15%'>" . $this->codeDate($data['tgl_masuk']) . "</td>";
                    $html .= "<td width='15%'>" . $this->codeDate($tgl_keluar) . "</td>";
                    $html .= "<td width='8%'>" . $lama . "</td>";
                    $html .= "<td width='8%' align='right'>Rp. " . number_format($tarif, 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format(($lama * $tarif), 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += ( $lama * $tarif);
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='6' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }

        return $html;
    }

    public function getTagihanKamarBanding($id_pasien, $id_kelas) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='8%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='8%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Masuk</td>";
            $html .= "<td width='10%' class='headerTagihan'>Keluar</td>";
            $html .= "<td width='8%' class='headerTagihan'>Lama</td>";
            $html .= "<td width='8%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='8%' class='headerTagihan'>Tarif Banding</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total Banding</td>";
            $html .= "<td width='10%' class='headerTagihan'>Selisih</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $total = 0;
            $totalBanding = 0;
            $totalSelisih = 0;

            while ($rec = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT d.id_kamar, b.ruang, c.kelas, DATE(a.tgl_masuk) AS tgl_masuk, DATE(a.tgl_keluar) AS tgl_keluar, a.lama_penggunaan, a.status, a.tarif, d.kamar
                          FROM rm_penggunaan_kamar a, rm_ruang b, rm_kelas c, rm_kamar d, rm_detail_kamar e
                          WHERE b.id_ruang=a.id_ruang AND c.id_kelas=a.id_kelas AND a.id_detail_kamar=e.id_detail_kamar AND e.id_kamar=d.id_kamar
                          AND id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.del_flag<>'1'";
                $result = $this->runQuery($query);

                while ($data = @mysql_fetch_array($result)) {
//                        if($this->checkDoubleBed($rec['id_pendaftaran'])){
//                            $tarif = $data['tarif'] * 2;
//                            $plusRuang = "(Double Bed)";
//                        } else {
                    $tarif = $data['tarif'];
                    $plusRuang = "";
//                        }
                    if ($data['lama_penggunaan'] == 0 && $data['status'] != 2) {
                        $tgl_keluar = "";
                        $lama = $this->jmlHari($data['tgl_masuk'], date('Y-m-d')) + 1;
                    } else {
                        $tgl_keluar = $data['tgl_keluar'];
                        $lama = $data['lama_penggunaan'];
                    }
                    $tarifBanding = $this->getTarifKamarInap($data['id_kamar'], $id_kelas);
                    $html .= "<tr>";
                    $html .= "<td width='8%'>" . $data['kamar'] . " " . $plusRuang . "</td>";
                    $html .= "<td width='8%'>" . $data['kelas'] . "</td>";
                    $html .= "<td width='10%'>" . $this->codeDate($data['tgl_masuk']) . "</td>";
                    $html .= "<td width='10%'>" . $this->codeDate($tgl_keluar) . "</td>";
                    $html .= "<td width='8%'>" . $lama . "</td>";
                    $html .= "<td width='8%' align='right'>Rp. " . number_format($tarif, 2, ',', '.') . "</td>";
                    $html .= "<td width='8%' align='right'>Rp. " . number_format($tarifBanding, 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format(($lama * $tarif), 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format(($lama * $tarifBanding), 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format((($lama * $tarif) - ($lama * $tarifBanding)), 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += ( $lama * $tarif);
                    $totalBanding += ( $lama * $tarifBanding);
                    $totalSelisih += ( $lama * $tarif) - ($lama * $tarifBanding);
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='7' class='total'>Sub Total</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalBanding, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalSelisih, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
            $banding = $this->getAllBanding() + $totalBanding;
            $this->setAllBanding($banding);
        }

        return $html;
    }

    public function checkStatusPembayaran($id_pendaftaran) {
        $q_check = "select status_pembayaran from rm_pendaftaran where id_pendaftaran='" . $id_pendaftaran . "'";
        $r_check = $this->runQuery($q_check);

        if (@mysql_result($r_check, 0, 'status_pembayaran') != "2")
            return true;
    }

    public function jenjangUmur($id_pasien, $tgl_pendaftaran) {
        $q_check = "select tgl_lahir from rm_pasien where id_pasien='" . $id_pasien . "'";
        $r_check = $this->runQuery($q_check);
        $tgl_lahir = @mysql_result($r_check, 0, 'tgl_lahir');

        if ($r_check) {
            $q_umur = "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('" . $tgl_pendaftaran . "') - TO_DAYS('" . $tgl_lahir . "')),'%Y')+0 as umur";
            $r_umur = $this->runQuery($q_umur);
            $tahun = @mysql_result($r_umur, 0, 'umur');
            if ($tahun < 1) {
                $q_day = "SELECT DATEDIFF('" . $tgl_pendaftaran . "','" . $tgl_lahir . "') as hari";
                $r_day = $this->runQuery($q_day);
                $hari = @mysql_result($r_day, 0, 'hari');
                if ($hari < 6 && hari == 0) {
                    $jenjang = 1;
                } else if ($hari > 6 && $hari < 28) {
                    $jenjang = 2;
                } else if ($hari >= 28) {
                    $jenjang = 3;
                }
            } else {
                if ($tahun <= 4 && $tahun >= 1) {
                    $jenjang = 4;
                } else if ($tahun <= 14 && $tahun >= 5) {
                    $jenjang = 5;
                } else if ($tahun <= 24 && $tahun >= 15) {
                    $jenjang = 6;
                } else if ($tahun <= 44 && $tahun >= 25) {
                    $jenjang = 7;
                } else if ($tahun <= 64 && $tahun >= 45) {
                    $jenjang = 8;
                } else if ($tahun >= 65) {
                    $jenjang = 9;
                }
            }
        }
        return $jenjang;
    }

    public function cekDaftarVisit($id_tindakan) {
        $q_check = "SELECT id_pendaftaran FROM rm_visit WHERE id_visit = " . $id_tindakan . "";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0)
            return @mysql_result($r_check, 0, 'id_pendaftaran');
    }

    public function cekDaftarKamar($id_penggunaan_kamar) {
        $q_check = "SELECT id_pendaftaran FROM rm_penggunaan_kamar WHERE id_penggunaan_kamar = " . $id_penggunaan_kamar . "";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0)
            return @mysql_result($r_check, 0, 'id_pendaftaran');
    }

    public function cekDaftarTindakan($id_tindakan) {
        $q_check = "SELECT id_pendaftaran FROM rm_tindakan_ruang WHERE id_tindakan_ruang = " . $id_tindakan . "";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0)
            return @mysql_result($r_check, 0, 'id_pendaftaran');
    }

    public function cekDaftarRadiologi($id_tindakan) {
        $q_check = "SELECT id_pendaftaran FROM rm_detail_radiologi WHERE id_detail_radiologi = " . $id_tindakan . "";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0)
            return @mysql_result($r_check, 0, 'id_pendaftaran');
    }

    public function cekDaftarLaborat($id_tindakan) {
        $q_check = "SELECT id_pendaftaran FROM rm_detail_laboratorium WHERE id_detail_laboratorium = " . $id_tindakan . "";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0)
            return @mysql_result($r_check, 0, 'id_pendaftaran');
    }

    public function cekDaftarFasilitas($id_tindakan) {
        $q_check = "SELECT id_pendaftaran FROM rm_fasilitas_ruang WHERE id_fasilitas_ruang = " . $id_tindakan . "";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0)
            return @mysql_result($r_check, 0, 'id_pendaftaran');
    }

    public function cekDaftarOK($id_tindakan) {
        $q_check = "SELECT id_pendaftaran FROM rm_tindakan_ruang_medis WHERE id_tindakan_ruang_medis = " . $id_tindakan . "";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0)
            return @mysql_result($r_check, 0, 'id_pendaftaran');
    }

    public function getTagihanVisit($id_pasien) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and id_tipe_pendaftaran in (4,6) and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<b>VISIT/PEMERIKSAAN DOKTER</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='20%' class='headerTagihan'>Dokter</td>";
            $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='20%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='15%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $total = 0;
            while ($rec = @mysql_fetch_array($r_daftar)) {
                //$q_kelas = "SELECT SUM(tarif) as tarif, kelas, id_kelas FROM visitKelas WHERE idp='" . $rec['id_pendaftaran'] . "' group by id_kelas";
		$q_kelas = "CALL visitKelas(" . $rec['id_pendaftaran'] . ")";
                $r_kelas = $this->runQuery($q_kelas);
                $subKelas = 0;
                while ($kelas = @mysql_fetch_array($r_kelas)) {
                    $subKelas = $kelas['tarif'];
                    $query = "SELECT b.nama_dokter, SUM(a.ctr) AS jml, a.tarif, c.ruang, a.tgl_visit, d.kelas
                          FROM rm_visit a, rm_dokter b, rm_ruang c, rm_kelas d
                          WHERE b.id_dokter=a.id_dokter AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.del_flag<>'1'
                          and c.id_ruang=a.id_ruang and a.id_kelas = d.id_kelas and a.id_kelas='" . $kelas['id_kelas'] . "'
                          GROUP BY a.id_pendaftaran, a.id_dokter, a.id_kelas,a.tarif";
                    $result = $this->runQuery($query);
                    while ($data = @mysql_fetch_array($result)) {
                        $html .= "<tr>";
                        $html .= "<td width='20%'>" . $data['nama_dokter'] . "</td>";
                        $html .= "<td width='10%'>" . $data['ruang'] . "</td>";
                        $html .= "<td width='10%'>" . $data['kelas'] . "</td>";
                        $html .= "<td width='20%'>" . $this->codeDate($data['tgl_visit']) . "</td>";
                        $html .= "<td width='5%'>" . $data['jml'] . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                        $html .= "<td width='15%' align='right'>Rp. " . number_format(($data['jml'] * $data['tarif']), 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $total += ( $data['jml'] * $data['tarif']);
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='6' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                    $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='6' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanVisitKeluar($id_pendaftaran) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $id_pendaftaran . "' 
                     and id_tipe_pendaftaran in (4,6) and del_flag<>'1' UNION SELECT idp FROM (SELECT max(id_pendaftaran) as idp FROM
                     rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 
                     and id_ruang=20) igd where idp IS NOT NULL";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<b>VISIT/PEMERIKSAAN DOKTER</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='20%' class='headerTagihan'>Dokter</td>";
            $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='20%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='15%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $total = 0;
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $q_kelas = "CALL visitKelas(" . $rec['id_pendaftaran'] . ")";
                $r_kelas = $this->runQuery($q_kelas);
                $subKelas = 0;
                while ($kelas = @mysql_fetch_array($r_kelas)) {
                    $subKelas = $kelas['tarif'];
                    $query = "SELECT b.nama_dokter, SUM(a.ctr) AS jml, a.tarif, c.ruang, a.tgl_visit, d.kelas
                          FROM rm_visit a, rm_dokter b, rm_ruang c, rm_kelas d
                          WHERE b.id_dokter=a.id_dokter AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.del_flag<>'1'
                          and c.id_ruang=a.id_ruang and a.id_kelas = d.id_kelas and a.id_kelas='" . $kelas['id_kelas'] . "'
                          GROUP BY a.id_pendaftaran, a.id_dokter, a.id_kelas";
                    $result = $this->runQuery($query);
                    while ($data = @mysql_fetch_array($result)) {
                        $html .= "<tr>";
                        $html .= "<td width='20%'>" . $data['nama_dokter'] . "</td>";
                        $html .= "<td width='10%'>" . $data['ruang'] . "</td>";
                        $html .= "<td width='10%'>" . $data['kelas'] . "</td>";
                        $html .= "<td width='20%'>" . $this->codeDate($data['tgl_visit']) . "</td>";
                        $html .= "<td width='5%'>" . $data['jml'] . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                        $html .= "<td width='15%' align='right'>Rp. " . number_format(($data['jml'] * $data['tarif']), 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $total += ( $data['jml'] * $data['tarif']);
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='6' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                    $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='6' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanVisitBanding($id_pasien, $id_kelas) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html = "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='15%' class='headerTagihan'>Dokter</td>";
            $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tarif Banding</td>";
            $html .= "<td width='15%' class='headerTagihan'>Total</td>";
            $html .= "<td width='15%' class='headerTagihan'>Total Banding</td>";
            $html .= "<td width='15%' class='headerTagihan'>Selisih</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $total = 0;
            $totalBanding = 0;
            $totalSelisih = 0;
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT a.id_dokter, b.nama_dokter, SUM(a.ctr) AS jml, a.tarif, c.ruang, a.tgl_visit
                          FROM rm_visit a, rm_dokter b, rm_ruang c
                          WHERE b.id_dokter=a.id_dokter AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.del_flag<>'1'
                          and c.id_ruang=a.id_ruang
                          GROUP BY a.id_pendaftaran, a.id_dokter";
                $result = $this->runQuery($query);

                while ($data = @mysql_fetch_array($result)) {
                    $tarifBanding = $this->getTarifVisit($data['id_dokter'], $id_kelas);
                    $html .= "<tr>";
                    $html .= "<td width='15%'>" . $data['nama_dokter'] . "</td>";
                    $html .= "<td width='10%'>" . $data['ruang'] . "</td>";
                    $html .= "<td width='10%'>" . $this->codeDate($data['tgl_visit']) . "</td>";
                    $html .= "<td width='5%'>" . $data['jml'] . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format($tarifBanding, 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format(($data['jml'] * $data['tarif']), 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format(($data['jml'] * $tarifBanding), 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format(($data['jml'] * $data['tarif']) - ($data['jml'] * $tarifBanding), 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += ( $data['jml'] * $data['tarif']);
                    $totalBanding += ( $data['jml'] * $tarifBanding);
                    $totalSelisih += ( $data['jml'] * $data['tarif']) - ($data['jml'] * $tarifBanding);
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='6' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($totalBanding, 2, ',', '.') . "</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($totalSelisih, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
            $banding = $this->getAllBanding() + $totalBanding;
            $this->setAllBanding($banding);
        }
        return $html;
    }

    public function getTagihanJasaMedis($id_pasien) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $html .= "<br>";
            $html .= "<b>JASA MEDIS / FASILITAS</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='40%' class='headerTagihan'>Fasilitas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='20%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='20%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $q_ruang = "select a.id_ruang, b.ruang, sum(a.jumlah) as jumlah, a.tarif from rm_fasilitas_ruang a, rm_ruang b 
                            where a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and b.id_ruang=a.id_ruang GROUP BY a.id_ruang";
                $r_ruang = $this->runQuery($q_ruang);
                $subtotal = 0;
                while ($ruang = @mysql_fetch_array($r_ruang)) {
                    //$subtotal += ( $ruang['jumlah'] * $ruang['tarif']);
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='5' ><b>" . $ruang['ruang'] . "</b></td>";
                    $html .= "</tr>";
                    $q_kelas = "SELECT sum(tarif) as tarif, id_kelas, kelas FROM utilitasKelas WHERE id_ruang='" . $ruang['id_ruang'] . "'
                                and id_pendaftaran='" . $rec['id_pendaftaran'] . "' GROUP BY id_kelas";
                    $r_kelas = $this->runQuery($q_kelas);
                    $subKelas = 0;
                    while ($kelas = @mysql_fetch_array($r_kelas)) {
                        $subKelas = $kelas['tarif'];
                        $subtotal += $subKelas;
                        $query = "SELECT c.tindakan, sum(a.jumlah) as jumlah, a.tarif
                              FROM rm_fasilitas_ruang a, rm_detail_tindakan b, rm_tindakan c
                              WHERE c.id_tindakan=b.id_tindakan AND b.id_detail_tindakan=a.id_detail_tindakan AND a.id_kelas='" . $kelas['id_kelas'] . "'
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang='" . $ruang['id_ruang'] . "' GROUP BY b.id_tindakan,a.id_kelas ORDER BY a.id_kelas, b.id_tindakan";
                        $result = $this->runQuery($query);
                        while ($data = @mysql_fetch_array($result)) {
                            $html .= "<tr>";
                            $html .= "<td width='40%'>" . $data['tindakan'] . "</td>";
                            $html .= "<td width='10%'>" . $kelas['kelas'] . "</td>";
                            $html .= "<td width='10%'>" . $data['jumlah'] . "</td>";
                            $html .= "<td width='20%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                            $html .= "<td width='20%' align='right'>Rp. " . number_format(($data['jumlah'] * $data['tarif']), 2, ',', '.') . "</td>";
                            $html .= "</tr>";
                            $total += ( $data['jumlah'] * $data['tarif']);
                        }
                        $html .= "<tr>";
                        $html .= "<td width='25%' colspan='4' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                        $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='4' class='total'>Sub Total " . $ruang['ruang'] . "</td>";
                    $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='4' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanJasaMedisKeluar($id_pendaftaran) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran=" . $id_pendaftaran . " and del_flag<>'1' 
                     UNION SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_asal_pendaftaran=" . $id_pendaftaran . " and del_flag<>'1'
                     UNION SELECT * FROM (SELECT max(id_pendaftaran) as idp FROM rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran 
                     where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) igd WHERE idp IS NOT NULL UNION SELECT id_pendaftaran from rm_pendaftaran WHERE
                     id_asal_pendaftaran = (SELECT max(id_pendaftaran) FROM rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran 
                     where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20)";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $html .= "<br>";
            $html .= "<b>JASA MEDIS / FASILITAS</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='40%' class='headerTagihan'>Fasilitas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='20%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='20%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $q_ruang = "select a.id_ruang, b.ruang, sum(a.jumlah) as jumlah, a.tarif from rm_fasilitas_ruang a, rm_ruang b 
                            where a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and b.id_ruang=a.id_ruang GROUP BY a.id_ruang";
                $r_ruang = $this->runQuery($q_ruang);
                $subtotal = 0;
                while ($ruang = @mysql_fetch_array($r_ruang)) {
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='5' ><b>" . $ruang['ruang'] . "</b></td>";
                    $html .= "</tr>";
                    $q_kelas = "CALL utilitasKelas(" . $ruang['id_ruang'] . "," . $rec['id_pendaftaran'] . ")";
                    $r_kelas = $this->runQuery($q_kelas);
                    $subKelas = 0;
                    while ($kelas = @mysql_fetch_array($r_kelas)) {
                        $subKelas = $kelas['tarif'];
                        $subtotal += $subKelas;
                        $query = "SELECT c.tindakan, sum(a.jumlah) as jumlah, a.tarif
                              FROM rm_fasilitas_ruang a, rm_detail_tindakan b, rm_tindakan c
                              WHERE c.id_tindakan=b.id_tindakan AND b.id_detail_tindakan=a.id_detail_tindakan AND a.id_kelas='" . $kelas['id_kelas'] . "'
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang='" . $ruang['id_ruang'] . "' GROUP BY b.id_tindakan,a.id_kelas ORDER BY a.id_kelas, b.id_tindakan";
                        $result = $this->runQuery($query);
                        while ($data = @mysql_fetch_array($result)) {
                            $html .= "<tr>";
                            $html .= "<td width='40%'>" . $data['tindakan'] . "</td>";
                            $html .= "<td width='10%'>" . $kelas['kelas'] . "</td>";
                            $html .= "<td width='10%'>" . $data['jumlah'] . "</td>";
                            $html .= "<td width='20%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                            $html .= "<td width='20%' align='right'>Rp. " . number_format(($data['jumlah'] * $data['tarif']), 2, ',', '.') . "</td>";
                            $html .= "</tr>";
                            $total += ( $data['jumlah'] * $data['tarif']);
                        }
                        $html .= "<tr>";
                        $html .= "<td width='25%' colspan='4' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                        $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='4' class='total'>Sub Total " . $ruang['ruang'] . "</td>";
                    $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='4' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanJasaMedisBanding($id_pasien, $id_kelas) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $totalBanding = 0;
            $totalSelisih = 0;
            $html = "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='30%' class='headerTagihan'>Fasilitas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tarif</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tarif Banding</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total Banding</td>";
            $html .= "<td width='10%' class='headerTagihan'>Selisih</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $q_ruang = "select a.id_ruang, b.ruang, sum(a.jumlah) as jumlah, a.tarif from rm_fasilitas_ruang a, rm_ruang b 
                            where a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and b.id_ruang=a.id_ruang GROUP BY a.id_ruang";
                $r_ruang = $this->runQuery($q_ruang);
                $subtotal = 0;
                $subtotalBanding = 0;
                $subtotalSelisih = 0;
                while ($ruang = @mysql_fetch_array($r_ruang)) {
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='5' ><b>" . $ruang['ruang'] . "</b></td>";
                    $html .= "</tr>";
                    $query = "SELECT b.id_tindakan, c.tindakan, d.kelas, sum(a.jumlah) as jumlah, a.tarif
                              FROM rm_fasilitas_ruang a, rm_detail_tindakan b, rm_tindakan c, rm_kelas d
                              WHERE c.id_tindakan=b.id_tindakan AND b.id_detail_tindakan=a.id_detail_tindakan AND d.id_kelas=a.id_kelas
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang='" . $ruang['id_ruang'] . "' GROUP BY b.id_tindakan,a.id_kelas ORDER BY a.id_kelas, b.id_tindakan";
                    $result = $this->runQuery($query);
                    while ($data = @mysql_fetch_array($result)) {
                        $tarifBanding = $this->getTarifTindakanRuang($data['id_tindakan'], $id_kelas);
                        $html .= "<tr>";
                        $html .= "<td width='30%'>" . $data['tindakan'] . "</td>";
                        $html .= "<td width='10%'>" . $data['kelas'] . "</td>";
                        $html .= "<td width='10%'>" . $data['jumlah'] . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format($tarifBanding, 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format(($data['jumlah'] * $data['tarif']), 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format(($data['jumlah'] * $tarifBanding), 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format(($data['jumlah'] * $data['tarif']) - ($data['jumlah'] * $tarifBanding), 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $subtotal += ( $data['jumlah'] * $data['tarif']);
                        $subtotalBanding += ( $data['jumlah'] * $tarifBanding);
                        $subtotalSelisih += ( $data['jumlah'] * $data['tarif']) - ($data['jumlah'] * $tarifBanding);
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='5' class='total'>Sub Total " . $ruang['ruang'] . "</td>";
                    $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($subtotalBanding, 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($subtotalSelisih, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += $subtotal;
                    $totalBanding += $subtotalBanding;
                    $totalSelisih += $subtotalSelisih;
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='5' class='total'>Sub Total</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalBanding, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalSelisih, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
            $banding = $this->getAllBanding() + $totalBanding;
            $this->setAllBanding($banding);
        }
        return $html;
    }

    public function getTagihanTindakanMedis($id_pasien) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $html .= "<br>";
            $html .= "<b>TINDAKAN RUANG</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='40%' class='headerTagihan'>Tindakan</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='20%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='30%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $q_ruang = "select a.id_ruang, b.ruang, sum(a.tarif) as tarif from rm_tindakan_ruang a, rm_ruang b 
                            where a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang not in ('19', '23','4') and b.id_ruang=a.id_ruang GROUP BY a.id_ruang";
                $r_ruang = $this->runQuery($q_ruang);
                $subtotal = 0;
                while ($ruang = @mysql_fetch_array($r_ruang)) {
                    $subtotal = $ruang['tarif'];
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='5' ><b>" . $ruang['ruang'] . "</b></td>";
                    $html .= "</tr>";
                    $q_kelas = " SELECT a.id_kelas, sum(a.tarif) as tarif, b.kelas FROM rm_tindakan_ruang a, rm_kelas b WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.id_kelas = b.id_kelas AND a.id_ruang='" . $ruang['id_ruang'] . "' 
                                 GROUP BY a.id_kelas";
                    $r_kelas = $this->runQuery($q_kelas);
                    $subKelas = 0;
                    while ($kelas = @mysql_fetch_array($r_kelas)) {
                        $subKelas = $kelas['tarif'];
                        $query = "SELECT c.tindakan, sum(a.tarif) as tarif, count(b.id_tindakan) as jml
                              FROM rm_tindakan_ruang a, rm_detail_tindakan b, rm_tindakan c
                              WHERE c.id_tindakan=b.id_tindakan AND b.id_detail_tindakan=a.id_detail_tindakan AND a.id_kelas='" . $kelas['id_kelas'] . "'
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang='" . $ruang['id_ruang'] . "' GROUP BY b.id_tindakan,a.id_kelas ORDER BY a.id_kelas, b.id_tindakan";
                        $result = $this->runQuery($query);
                        while ($data = @mysql_fetch_array($result)) {
                            $html .= "<tr>";
                            $html .= "<td width='40%'>" . $data['tindakan'] . "</td>";
                            $html .= "<td width='10%'>" . $data['jml'] . "</td>";
                            $html .= "<td width='20%'>" . $kelas['kelas'] . "</td>";
                            $html .= "<td width='30%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                            $html .= "</tr>";
                            $total += $data['tarif'];
                        }
                        $html .= "<tr>";
                        $html .= "<td width='25%' colspan='3' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                        $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='3' class='total'>Sub Total " . $ruang['ruang'] . "</td>";
                    $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='3' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanTindakanMedisKeluar($id_pendaftaran) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran=" . $id_pendaftaran . " and del_flag<>'1' UNION SELECT id_pendaftaran FROM 
                     rm_pendaftaran WHERE id_asal_pendaftaran=" . $id_pendaftaran . " and del_flag<>'1' UNION SELECT * FROM (SELECT max(id_pendaftaran) as idp FROM rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran 
                     where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) igd WHERE idp IS NOT NULL UNION SELECT 
                     id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran = (SELECT max(id_pendaftaran) FROM 
                     rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20)";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $html .= "<br>";
            $html .= "<b>TINDAKAN RUANG</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='40%' class='headerTagihan'>Tindakan</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='20%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='30%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $q_ruang = "select a.id_ruang, b.ruang, sum(a.tarif) as tarif from rm_tindakan_ruang a, rm_ruang b 
                            where a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang not in ('19', '23','4') and b.id_ruang=a.id_ruang GROUP BY a.id_ruang";
                $r_ruang = $this->runQuery($q_ruang);
                $subtotal = 0;
                while ($ruang = @mysql_fetch_array($r_ruang)) {
                    $subtotal = $ruang['tarif'];
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='5' ><b>" . $ruang['ruang'] . "</b></td>";
                    $html .= "</tr>";
                    $q_kelas = " SELECT a.id_kelas, sum(a.tarif) as tarif, b.kelas FROM rm_tindakan_ruang a, rm_kelas b WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.id_kelas = b.id_kelas AND a.id_ruang='" . $ruang['id_ruang'] . "' 
                                 GROUP BY a.id_kelas";
                    $r_kelas = $this->runQuery($q_kelas);
                    $subKelas = 0;
                    while ($kelas = @mysql_fetch_array($r_kelas)) {
                        $subKelas = $kelas['tarif'];
                        $query = "SELECT c.tindakan, sum(a.tarif) as tarif, count(b.id_tindakan) as jml
                              FROM rm_tindakan_ruang a, rm_detail_tindakan b, rm_tindakan c
                              WHERE c.id_tindakan=b.id_tindakan AND b.id_detail_tindakan=a.id_detail_tindakan AND a.id_kelas='" . $kelas['id_kelas'] . "'
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang='" . $ruang['id_ruang'] . "' GROUP BY b.id_tindakan,a.id_kelas ORDER BY a.id_kelas, b.id_tindakan";
                        $result = $this->runQuery($query);
                        while ($data = @mysql_fetch_array($result)) {
                            $html .= "<tr>";
                            $html .= "<td width='40%'>" . $data['tindakan'] . "</td>";
                            $html .= "<td width='10%'>" . $data['jml'] . "</td>";
                            $html .= "<td width='20%'>" . $kelas['kelas'] . "</td>";
                            $html .= "<td width='30%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                            $html .= "</tr>";
                            $total += $data['tarif'];
                        }
                        $html .= "<tr>";
                        $html .= "<td width='25%' colspan='3' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                        $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='3' class='total'>Sub Total " . $ruang['ruang'] . "</td>";
                    $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='3' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanTindakanMedisBanding($id_pasien, $id_kelas) {
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $totalBanding = 0;
            $totalSelisih = 0;
            $html = "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='50%' class='headerTagihan'>Tindakan</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total Banding</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total Selisih</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $q_ruang = "select a.id_ruang, b.ruang, sum(a.tarif) as tarif from rm_tindakan_ruang a, rm_ruang b 
                            where a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang not in ('19', '23') and b.id_ruang=a.id_ruang GROUP BY a.id_ruang";
                $r_ruang = $this->runQuery($q_ruang);
                $subtotal = 0;
                $subtotalBanding = 0;
                $subtotalSelisih = 0;
                while ($ruang = @mysql_fetch_array($r_ruang)) {
                    $subtotal += $ruang['tarif'];
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='5' ><b>" . $ruang['ruang'] . "</b></td>";
                    $html .= "</tr>";
                    $query = "SELECT b.id_tindakan, c.tindakan, d.kelas, sum(a.tarif) as tarif, count(b.id_tindakan) as jml
                              FROM rm_tindakan_ruang a, rm_detail_tindakan b, rm_tindakan c, rm_kelas d
                              WHERE c.id_tindakan=b.id_tindakan AND b.id_detail_tindakan=a.id_detail_tindakan AND d.id_kelas=a.id_kelas
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' and a.id_ruang='" . $ruang['id_ruang'] . "' GROUP BY b.id_tindakan,a.id_kelas ORDER BY a.id_kelas, b.id_tindakan";
                    $result = $this->runQuery($query);
                    while ($data = @mysql_fetch_array($result)) {
                        $tarifBanding = $this->getTarifTindakanRuang($data['id_tindakan'], $id_kelas);
                        $html .= "<tr>";
                        $html .= "<td width='50%'>" . $data['tindakan'] . "</td>";
                        $html .= "<td width='10%'>" . $data['jml'] . "</td>";
                        $html .= "<td width='10%'>" . $data['kelas'] . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format($tarifBanding, 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'] - $tarifBanding, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $total += $data['tarif'];
                        $subtotalBanding += $tarifBanding;
                        $subtotalSelisih += $data['tarif'] - $tarifBanding;
                        $totalBanding += $tarifBanding;
                        $totalSelisih += $data['tarif'] - $tarifBanding;
                    }
                    $html .= "<tr>";
                    $html .= "<td width='25%' colspan='3' class='total'>Sub Total " . $ruang['ruang'] . "</td>";
                    $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($subtotalBanding, 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($subtotalSelisih, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='3' class='total'>Sub Total</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalBanding, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalSelisih, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
            $banding = $this->getAllBanding() + $totalBanding;
            $this->setAllBanding($banding);
        }
        return $html;
    }

    public function getTagihanTindakanBedah($id_pasien) {
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and a.del_flag<>'1' and b.id_ruang=a.id_ruang and a.id_ruang='22' and a.id_pendaftaran in 
                     (select id_pendaftaran from rm_tindakan_ruang_medis where del_flag<> 1)";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $html .= "<br>";
            $html .= "<b>TINDAKAN BEDAH</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='50%' class='headerTagihan'>Tindakan/Fasilitas</td>";
            $html .= "<td width='5%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='20%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='20%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='5' ><b>" . $rec['ruang'] . "</b></td>";
                $html .= "</tr>";
                $q_kelas = "SELECT id_kelas, sum(tarif) as tarif, kelas FROM ibsKelas WHERE idp='" . $rec['id_pendaftaran'] . "' GROUP BY id_kelas";
                $r_kelas = $this->runQuery($q_kelas);
                $subKelas = 0;
                while ($kelas = @mysql_fetch_array($r_kelas)) {
                    $subKelas = $kelas['tarif'];
                    $query = "SELECT COUNT(*) as jumlah, c.tindakan, (sum(a.tarif)+sum(a.penambahan_tarif)) as tarif, date(a.tgl_tindakan) as tanggal, d.kelas
                              FROM rm_tindakan_ruang_medis a, rm_detail_tindakan b, rm_tindakan c, rm_kelas d
                              WHERE b.id_detail_tindakan=a.id_tindakan_medis AND c.id_tindakan=b.id_tindakan AND a.id_kelas=d.id_kelas and a.del_flag<>1
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' GROUP BY c.id_tindakan, date(a.tgl_tindakan), a.id_kelas ORDER BY date(a.tgl_tindakan), a.id_kelas, c.id_tindakan";
                    $result = $this->runQuery($query);

                    while ($data = @mysql_fetch_array($result)) {
                        $html .= "<tr>";
                        $html .= "<td width='50%'>" . $data['tindakan'] . "</td>";
                        $html .= "<td width='5%'>" . $data['jumlah'] . "</td>";
                        $html .= "<td width='20%'>" . $this->codeDate($data['tanggal']) . "</td>";
                        $html .= "<td width='5%'>" . $data['kelas'] . "</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $total += $data['tarif'];
                    }
                }
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='4' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                $html .= "</tr>";
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='4' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanTindakanBedahKeluar($id_pendaftaran) {
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_pendaftaran=" . $id_pendaftaran . " 
                     and a.del_flag<>'1' and b.id_ruang=a.id_ruang and a.id_ruang='22' and a.id_pendaftaran in (select id_pendaftaran from 
                     rm_tindakan_ruang_medis where del_flag<> 1) UNION SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, 
                     rm_ruang b WHERE id_asal_pendaftaran=" . $id_pendaftaran . " and a.del_flag<>'1' and b.id_ruang=a.id_ruang and a.id_ruang='22' 
                     and a.id_pendaftaran in (select id_pendaftaran from rm_tindakan_ruang_medis where del_flag<> 1) UNION SELECT id_pendaftaran, 
                     a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_asal_pendaftaran=(SELECT max(id_pendaftaran) FROM rm_pendaftaran 
                     where id_pasien = (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1
                     and id_ruang=20) and a.del_flag<>'1' and b.id_ruang=a.id_ruang and a.id_ruang='22' and a.id_pendaftaran in 
                     (select id_pendaftaran from rm_tindakan_ruang_medis where del_flag<> 1)";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $html .= "<br>";
            $html .= "<b>TINDAKAN BEDAH</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='50%' class='headerTagihan'>Tindakan/Fasilitas</td>";
            $html .= "<td width='5%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='20%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='20%' class='headerTagihan'>Total</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='5' ><b>" . $rec['ruang'] . "</b></td>";
                $html .= "</tr>";
                $q_kelas = "CALL ibsKelas(" . $rec['id_pendaftaran'] . ")";
                $r_kelas = $this->runQuery($q_kelas);
                $subKelas = 0;
                while ($kelas = @mysql_fetch_array($r_kelas)) {
                    $subKelas = $kelas['tarif'];
                    $query = "SELECT COUNT(*) as jumlah, c.tindakan, (sum(a.tarif)+sum(a.penambahan_tarif)) as tarif, date(a.tgl_tindakan) as tanggal, d.kelas
                              FROM rm_tindakan_ruang_medis a, rm_detail_tindakan b, rm_tindakan c, rm_kelas d
                              WHERE b.id_detail_tindakan=a.id_tindakan_medis AND c.id_tindakan=b.id_tindakan AND a.id_kelas=d.id_kelas and a.del_flag<>1
                              AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' GROUP BY c.id_tindakan, date(a.tgl_tindakan), a.id_kelas ORDER BY date(a.tgl_tindakan), a.id_kelas, c.id_tindakan";
                    $result = $this->runQuery($query);

                    while ($data = @mysql_fetch_array($result)) {
                        $html .= "<tr>";
                        $html .= "<td width='50%'>" . $data['tindakan'] . "</td>";
                        $html .= "<td width='5%'>" . $data['jumlah'] . "</td>";
                        $html .= "<td width='20%'>" . $this->codeDate($data['tanggal']) . "</td>";
                        $html .= "<td width='5%'>" . $data['kelas'] . "</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $total += $data['tarif'];
                    }
                }
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='4' class='total'>Sub Total " . $kelas['kelas'] . "</td>";
                $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subKelas, 2, ',', '.') . "</td>";
                $html .= "</tr>";
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='4' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanTindakanBedahBanding($id_pasien, $id_kelas) {
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and a.del_flag<>'1' and b.id_ruang=a.id_ruang and a.id_ruang='22'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $total = 0;
            $totalBanding = 0;
            $totalSelisih = 0;

            $html = "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='40%' class='headerTagihan'>Tindakan/Fasilitas</td>";
            $html .= "<td width='5%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='15%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Kelas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total</td>";
            $html .= "<td width='10%' class='headerTagihan'>Total Banding</td>";
            $html .= "<td width='10%' class='headerTagihan'>Selisih</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                //$kelas = $this->getKelas($this->getKelasPendaftaran($rec['id_pendaftaran']));
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='5' ><b>" . $rec['ruang'] . "</b></td>";
                $html .= "</tr>";
                $query = "SELECT b.id_tindakan, COUNT(*) as jumlah, c.tindakan, (sum(a.tarif)+sum(a.penambahan_tarif)) as tarif, date(a.tgl_tindakan) as tanggal, d.kelas
                          FROM rm_tindakan_ruang_medis a, rm_detail_tindakan b, rm_tindakan c, rm_kelas d
                          WHERE b.id_detail_tindakan=a.id_tindakan_medis AND c.id_tindakan=b.id_tindakan AND a.id_kelas=d.id_kelas
                          AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' GROUP BY c.id_tindakan, date(a.tgl_tindakan), a.id_kelas ORDER BY date(a.tgl_tindakan), a.id_kelas, c.id_tindakan";
                $result = $this->runQuery($query);

                while ($data = @mysql_fetch_array($result)) {
                    $tarifBanding = $this->getTarifTindakanRuang($data['id_tindakan'], $id_kelas);
                    $html .= "<tr>";
                    $html .= "<td width='40%'>" . $data['tindakan'] . "</td>";
                    $html .= "<td width='5%'>" . $data['jumlah'] . "</td>";
                    $html .= "<td width='15%'>" . $this->codeDate($data['tanggal']) . "</td>";
                    $html .= "<td width='5%'>" . $data['kelas'] . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format($tarifBanding, 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>Rp. " . number_format($data['tarif'] - $tarifBanding, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += $data['tarif'];
                    $totalBanding += $tarifBanding;
                    $totalSelisih += $data['tarif'] - $tarifBanding;
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='4' class='total'>Sub Total</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalBanding, 2, ',', '.') . "</td>";
            $html .= "<td width='10%' align='right' class='total'>Rp. " . number_format($totalSelisih, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
            $banding = $this->getAllBanding() + $totalBanding;
            $this->setAllBanding($banding);
        }
        return $html;
    }

    public function getDiskonTagihan($id_pasien) {
        $total = 0;
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and a.del_flag<>'1' and b.id_ruang=a.id_ruang 
                     AND a.id_pendaftaran in (select id_pendaftaran from rm_diskon_tindakan where del_flag<>1)";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<b>DISKON</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='10%' class='headerTagihan'>No</td>";
            $html .= "<td width='70%' class='headerTagihan'>Pemberi Diskon</td>";
            $html .= "<td width='20%' class='headerTagihan'>Diskon</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $i = 1;
            while ($data = @mysql_fetch_array($r_daftar)) {
                $q_check = "SELECT COUNT(*)as jml FROM rm_diskon_tindakan a, rm_ruang b
                            WHERE b.id_ruang=a.id_ruang AND a.pemberi_diskon='Perawat' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                $r_check = $this->runQuery($q_check);
                if (@mysql_result($r_check, 0, 'jml') > 0) {
                    $q_ruang = "SELECT b.nama_pegawai, SUM(a.diskon) AS diskon FROM rm_diskon_tindakan a, rm_keperawatan b
                                WHERE b.id_ruang=a.id_ruang AND a.pemberi_diskon='Perawat' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                    $r_ruang = $this->runQuery($q_ruang);
                    while ($rec = @mysql_fetch_array($r_ruang)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>" . $i . "</td>";
                        $html .= "<td width='70%'>" . $rec['nama_pegawai'] . "</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($rec['diskon'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $total += $rec['diskon'];
                    }
                }
                $q_check = "SELECT COUNT(*)as jml FROM rm_diskon_tindakan a, rm_dokter b
                            WHERE b.id_dokter=a.id_dokter AND a.pemberi_diskon='Dokter' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                $r_check = $this->runQuery($q_check);
                if (@mysql_result($r_check, 0, 'jml') > 0) {
                    $q_ruang = "SELECT b.nama_dokter, SUM(a.diskon) AS diskon FROM rm_diskon_tindakan a, rm_dokter b
                                WHERE b.id_dokter=a.id_dokter AND a.pemberi_diskon='Dokter' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1 GROUP BY a.id_dokter";
                    $r_ruang = $this->runQuery($q_ruang);
                    while ($rec = @mysql_fetch_array($r_ruang)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>" . $i . "</td>";
                        $html .= "<td width='70%'>" . $rec['nama_dokter'] . "</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($rec['diskon'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $total += $rec['diskon'];
                    }
                }
                $q_check = "SELECT COUNT(*)as jml FROM rm_diskon_tindakan a
                            WHERE a.pemberi_diskon='Manajemen' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                $r_check = $this->runQuery($q_check);
                if (@mysql_result($r_check, 0, 'jml') > 0) {
                    $q_ruang = "SELECT SUM(a.diskon) AS diskon FROM rm_diskon_tindakan a
                                WHERE a.pemberi_diskon='Manajemen' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                    $r_ruang = $this->runQuery($q_ruang);
                    while ($rec = @mysql_fetch_array($r_ruang)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>" . $i . "</td>";
                        $html .= "<td width='70%'>Manajemen</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($rec['diskon'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $total += $rec['diskon'];
                    }
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='2' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getDiskonTagihanKeluar($id_pendaftaran) {
        $total = 0;
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_pendaftaran=" . $id_pendaftaran . " 
                     and a.del_flag<>'1' and b.id_ruang=a.id_ruang UNION SELECT id_pendaftaran, a.id_ruang, 
                     ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_asal_pendaftaran=" . $id_pendaftaran . " and a.del_flag<>'1' and 
                     b.id_ruang=a.id_ruang UNION SELECT id_pendaftaran, a.id_ruang, ruang FROM 
                     rm_pendaftaran a, rm_ruang b WHERE id_pendaftaran=(SELECT max(id_pendaftaran) FROM rm_pendaftaran where id_pasien = 
                     (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) 
                     and a.del_flag<>'1' and b.id_ruang=a.id_ruang UNION SELECT id_pendaftaran, a.id_ruang, 
                     ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_asal_pendaftaran=(SELECT max(id_pendaftaran) FROM rm_pendaftaran where 
                     id_pasien = (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) 
                     and a.del_flag<>'1' and b.id_ruang=a.id_ruang";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";

        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<b>DISKON</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='10%' class='headerTagihan'>No</td>";
            $html .= "<td width='70%' class='headerTagihan'>Pemberi Diskon</td>";
            $html .= "<td width='20%' class='headerTagihan'>Diskon</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $i = 1;
            while ($data = @mysql_fetch_array($r_daftar)) {
                $q_check = "SELECT COUNT(*)as jml FROM rm_diskon_tindakan a, rm_ruang b
                            WHERE b.id_ruang=a.id_ruang AND a.pemberi_diskon='Perawat' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                $r_check = $this->runQuery($q_check);
                if (@mysql_result($r_check, 0, 'jml') > 0) {
                    $q_ruang = "SELECT b.nama_pegawai, SUM(a.diskon) AS diskon FROM rm_diskon_tindakan a, rm_keperawatan b
                                WHERE b.id_ruang=a.id_ruang AND a.pemberi_diskon='Perawat' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                    $r_ruang = $this->runQuery($q_ruang);
                    while ($rec = @mysql_fetch_array($r_ruang)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>" . $i . "</td>";
                        $html .= "<td width='70%'>" . $rec['nama_pegawai'] . "</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($rec['diskon'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $total += $rec['diskon'];
                    }
                }
                $q_check = "SELECT COUNT(*)as jml FROM rm_diskon_tindakan a, rm_dokter b
                            WHERE b.id_dokter=a.id_dokter AND a.pemberi_diskon='Dokter' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                $r_check = $this->runQuery($q_check);
                if (@mysql_result($r_check, 0, 'jml') > 0) {
                    $q_ruang = "SELECT b.nama_dokter, SUM(a.diskon) AS diskon FROM rm_diskon_tindakan a, rm_dokter b
                                WHERE b.id_dokter=a.id_dokter AND a.pemberi_diskon='Dokter' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1 GROUP BY a.id_dokter";
                    $r_ruang = $this->runQuery($q_ruang);
                    while ($rec = @mysql_fetch_array($r_ruang)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>" . $i . "</td>";
                        $html .= "<td width='70%'>" . $rec['nama_dokter'] . "</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($rec['diskon'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $total += $rec['diskon'];
                    }
                }
                $q_check = "SELECT COUNT(*)as jml FROM rm_diskon_tindakan a
                            WHERE a.pemberi_diskon='Manajemen' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                $r_check = $this->runQuery($q_check);
                if (@mysql_result($r_check, 0, 'jml') > 0) {
                    $q_ruang = "SELECT SUM(a.diskon) AS diskon FROM rm_diskon_tindakan a
                                WHERE a.pemberi_diskon='Manajemen' AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>1";
                    $r_ruang = $this->runQuery($q_ruang);
                    while ($rec = @mysql_fetch_array($r_ruang)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>" . $i . "</td>";
                        $html .= "<td width='70%'>Manajemen</td>";
                        $html .= "<td width='20%' align='right'>Rp. " . number_format($rec['diskon'], 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $total += $rec['diskon'];
                    }
                }
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='2' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTglTindakanLab($id_pendaftaran) {
        $query = "select date(ambil) as tgl_tindakan from rm_pemeriksaan_lab where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'tgl_tindakan');
        else
            return '';
    }

    public function getTagihanJasaPenunjangMedis($id_pasien) {
        $total = 0;
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and a.del_flag<>'1' and b.id_ruang=a.id_ruang and b.id_tipe_ruang in ('3', '7')";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<b>JASA PENUNJANG MEDIS</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='50%' class='headerTagihan'>Tindakan/Fasilitas</td>";
            $html .= "<td width='30%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='20%' class='headerTagihan'>Jumlah</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='5' ><b>" . $rec['ruang'] . "</b></td>";
                $html .= "</tr>";

                if ($rec['id_ruang'] == '17')
                    $q_tindakan = "SELECT SUM(a.tarif) AS tarif, kelompok_lab FROM rm_detail_laboratorium a, rm_kelompok_lab b
                                   WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND b.id_kelompok_lab=a.id_kelompok_lab AND a.del_flag<>'1' 
                                   GROUP BY b.id_kelompok_lab";
                else if ($rec['id_ruang'] == '18')
                    $q_tindakan = "SELECT COUNT(*) AS jumlah, DATE(a.tgl_pemeriksaan) AS tgl_tindakan, SUM(a.tarif) AS tarif, b.radiologi AS nm_tindakan FROM rm_detail_radiologi a, rm_radiologi b
                                   WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.del_flag<>'1' AND b.id_radiologi=a.id_radiologi GROUP BY a.id_radiologi, date(a.tgl_pemeriksaan)";
                else
                    $q_tindakan = "SELECT COUNT(*) AS jumlah, DATE(a.tgl_tindakan) AS tgl_tindakan, SUM(a.tarif) AS tarif, b.tindakan as nm_tindakan FROM rm_tindakan_ruang a, rm_tindakan b, rm_detail_tindakan c
                                   WHERE a.id_ruang='" . $rec['id_ruang'] . "' AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND c.id_detail_tindakan=a.id_detail_tindakan
                                   AND b.id_tindakan=c.id_tindakan GROUP BY DATE(a.tgl_tindakan), b.id_tindakan";
                $r_tindakan = $this->runQuery($q_tindakan);
                $subtotal = 0;
                while ($dt = @mysql_fetch_array($r_tindakan)) {
                    if ($rec['id_ruang'] == '17') {
                        $kelompok = $dt['kelompok_lab'];
                        $tgl_tindakan = $this->codeDate($this->getTglTindakanLab($rec['id_pendaftaran']));
                        $subtotal += $dt['tarif'];
                    } else {
                        $kelompok = $dt['nm_tindakan'] . '     - Jumlah ' . $dt['jumlah'];
                        $tgl_tindakan = $this->codeDate($dt['tgl_tindakan']);
                        $subtotal += $dt['tarif'];
                    }
                    $html .= "<tr>";
                    $html .= "<td width='50%'>" . $kelompok . "</td>";
                    $html .= "<td width='30%'>" . $tgl_tindakan . "</td>";
                    $html .= "<td width='20%' align='right'>Rp. " . number_format($dt['tarif'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += $dt['tarif'];
                }

                $subtotal = $subtotal;// + $tampung;
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='2' class='total'>Sub Total " . $rec['ruang'] . "</td>";
                $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='2' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanJasaPenunjangMedisKeluar($id_pendaftaran) {
        $total = 0;
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_asal_pendaftaran=" . $id_pendaftaran . " AND a.del_flag<>'1' 
                    AND b.id_ruang=a.id_ruang AND b.id_tipe_ruang IN (3,7) UNION SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, 
                    rm_ruang b WHERE id_pendaftaran=" . $id_pendaftaran . " AND a.del_flag<>1 AND b.id_ruang=a.id_ruang AND b.id_tipe_ruang IN (3,7) UNION
                    SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_asal_pendaftaran in (SELECT max(id_pendaftaran) 
                    FROM rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) AND a.del_flag<>'1' 
                    AND b.id_ruang=a.id_ruang AND b.id_tipe_ruang IN (3,7)";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<b>JASA PENUNJANG MEDIS</b>";
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='50%' class='headerTagihan'>Tindakan/Fasilitas</td>";
            $html .= "<td width='30%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='20%' class='headerTagihan'>Jumlah</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='5' ><b>" . $rec['ruang'] . "</b></td>";
                $html .= "</tr>";

                if ($rec['id_ruang'] == '17')
                    $q_tindakan = "SELECT SUM(a.tarif) AS tarif, kelompok_lab FROM rm_detail_laboratorium a, rm_kelompok_lab b
                                   WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND b.id_kelompok_lab=a.id_kelompok_lab AND a.del_flag<>'1' 
                                   GROUP BY b.id_kelompok_lab";
                else if ($rec['id_ruang'] == '18')
                    $q_tindakan = "SELECT COUNT(*) AS jumlah, DATE(a.tgl_pemeriksaan) AS tgl_tindakan, SUM(a.tarif) AS tarif, b.radiologi AS nm_tindakan FROM rm_detail_radiologi a, rm_radiologi b
                                   WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.del_flag<>'1' AND b.id_radiologi=a.id_radiologi GROUP BY a.id_radiologi, date(a.tgl_pemeriksaan)";
                else
                    $q_tindakan = "SELECT COUNT(*) AS jumlah, DATE(a.tgl_tindakan) AS tgl_tindakan, SUM(a.tarif) AS tarif, b.tindakan as nm_tindakan FROM rm_tindakan_ruang a, rm_tindakan b, rm_detail_tindakan c
                                   WHERE a.id_ruang='" . $rec['id_ruang'] . "' AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND c.id_detail_tindakan=a.id_detail_tindakan
                                   AND b.id_tindakan=c.id_tindakan GROUP BY DATE(a.tgl_tindakan), b.id_tindakan";
                $r_tindakan = $this->runQuery($q_tindakan);
                $subtotal = 0;
                while ($dt = @mysql_fetch_array($r_tindakan)) {
                    if ($rec['id_ruang'] == '17') {
                        $kelompok = $dt['kelompok_lab'];
                        $tgl_tindakan = $this->codeDate($this->getTglTindakanLab($rec['id_pendaftaran']));
                        $subtotal += $dt['tarif'];
                    } else {
                        $kelompok = $dt['nm_tindakan'] . '     - Jumlah ' . $dt['jumlah'];
                        $tgl_tindakan = $this->codeDate($dt['tgl_tindakan']);
                        $subtotal += $dt['tarif'];
                    }
                    $html .= "<tr>";
                    $html .= "<td width='50%'>" . $kelompok . "</td>";
                    $html .= "<td width='30%'>" . $tgl_tindakan . "</td>";
                    $html .= "<td width='20%' align='right'>Rp. " . number_format($dt['tarif'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += $dt['tarif'];
                }

                $subtotal = $subtotal;// + $tampung;
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='2' class='total'>Sub Total " . $rec['ruang'] . "</td>";
                $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='2' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        }
        return $html;
    }

    public function getTagihanJasaPenunjangMedisBanding($id_pasien, $id_kelas) {
        $total = 0;
        $totalBanding = 0;
        $totalSelisih = 0;
        $q_daftar = "SELECT id_pendaftaran, a.id_ruang, ruang FROM rm_pendaftaran a, rm_ruang b WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and a.del_flag<>'1' and b.id_ruang=a.id_ruang and b.id_tipe_ruang in ('3', '7')";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        if (@mysql_num_rows($r_daftar) > 0) {

            $html = "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='40%' class='headerTagihan'>Tindakan/Fasilitas</td>";
            $html .= "<td width='25%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='15%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='15%' class='headerTagihan'>Jumlah Banding</td>";
            $html .= "<td width='15%' class='headerTagihan'>Selisih</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            while ($rec = @mysql_fetch_array($r_daftar)) {
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='5' ><b>" . $rec['ruang'] . "</b></td>";
                $html .= "</tr>";

                if ($rec['id_ruang'] == '17')
                    $q_tindakan = "SELECT SUM(a.tarif) AS tarif, kelompok_lab FROM rm_detail_laboratorium a, rm_kelompok_lab b
                                   WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND b.id_kelompok_lab=a.id_kelompok_lab AND a.del_flag<>'1' 
                                   GROUP BY b.id_kelompok_lab";
                else if ($rec['id_ruang'] == '18')
                    $q_tindakan = "SELECT COUNT(*) AS jumlah, DATE(a.tgl_pemeriksaan) AS tgl_tindakan, SUM(a.tarif) AS tarif, b.id_radiologi, b.radiologi AS nm_tindakan FROM rm_detail_radiologi a, rm_radiologi b
                                   WHERE a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND a.del_flag<>'1' AND b.id_radiologi=a.id_radiologi GROUP BY a.id_radiologi, date(a.tgl_pemeriksaan)";
                else
                    $q_tindakan = "SELECT c.id_tindakan, COUNT(*) AS jumlah, DATE(a.tgl_tindakan) AS tgl_tindakan, SUM(a.tarif) AS tarif, b.tindakan as nm_tindakan FROM rm_tindakan_ruang a, rm_tindakan b, rm_detail_tindakan c
                                   WHERE a.id_ruang='" . $rec['id_ruang'] . "' AND a.id_pendaftaran='" . $rec['id_pendaftaran'] . "' AND c.id_detail_tindakan=a.id_detail_tindakan
                                   AND b.id_tindakan=c.id_tindakan GROUP BY DATE(a.tgl_tindakan), b.id_tindakan";
                $r_tindakan = $this->runQuery($q_tindakan);
                $subtotal = 0;
                $subtotalBanding = 0;
                $subtotalSelisih = 0;
                while ($dt = @mysql_fetch_array($r_tindakan)) {
                    if ($rec['id_ruang'] == '17') {
                        $kelompok = $dt['kelompok_lab'];
                        $tgl_tindakan = $this->codeDate($this->getTglTindakanLab($rec['id_pendaftaran']));
                        $tarifBanding = $this->getTarifAllLaboratorium($rec['id_pendaftaran'], $id_kelas);
                        $subtotal += $dt['tarif'];
                        $subtotalBanding += $tarifBanding;
                        $subtotalSelisih += $dt['tarif'] - $tarifBanding;
                    } else if ($rec['id_ruang'] == '18') {
                        $kelompok = $dt['nm_tindakan'] . '     - Jumlah ' . $dt['jumlah'];
                        $tarifBanding = $this->getTarifRadiologi($dt['id_radiologi'], $id_kelas);
                        $tgl_tindakan = $this->codeDate($dt['tgl_tindakan']);
                        $subtotal += $dt['tarif'];
                        $subtotalBanding += $tarifBanding;
                        $subtotalSelisih += $dt['tarif'] - $tarifBanding;
                    } else {
                        $kelompok = $dt['nm_tindakan'] . '     - Jumlah ' . $dt['jumlah'];
                        $tarifBanding = $this->getTarifTindakanRuang($dt['id_tindakan'], $id_kelas);
                        $tgl_tindakan = $this->codeDate($dt['tgl_tindakan']);
                        $subtotal += $dt['tarif'];
                        $subtotalBanding += $tarifBanding;
                        $subtotalSelisih += $dt['tarif'] - $tarifBanding;
                    }
                    $html .= "<tr>";
                    $html .= "<td width='40%'>" . $kelompok . "</td>";
                    $html .= "<td width='25%'>" . $tgl_tindakan . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format($dt['tarif'], 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format($tarifBanding, 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>Rp. " . number_format($dt['tarif'] - $tarifBanding, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $total += $dt['tarif'];
                    $totalBanding += $tarifBanding;
                    $totalSelisih += $dt['tarif'] - $tarifBanding;
                }
                $html .= "<tr>";
                $html .= "<td width='25%' colspan='2' class='total'>Sub Total " . $rec['ruang'] . "</td>";
                $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotal, 2, ',', '.') . "</td>";
                $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotalBanding, 2, ',', '.') . "</td>";
                $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($subtotalSelisih, 2, ',', '.') . "</td>";
                $html .= "</tr>";
            }
            $html .= "<tr>";
            $html .= "<td width='25%' colspan='2' class='total'>Sub Total</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($totalBanding, 2, ',', '.') . "</td>";
            $html .= "<td width='15%' align='right' class='total'>Rp. " . number_format($totalSelisih, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
            $banding = $this->getAllBanding() + $totalBanding;
            $this->setAllBanding($banding);
        }
        return $html;
    }

    public function getAllTagihanPasien($id_pasien) {
        $jmlTarif = 0;

//        $q_daftar = "SELECT id_pendaftaran, id_tipe_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
//                     AND status_pembayaran!='2' and del_flag<>'1'";
        $q_daftar = "CALL pendaftaranTagihan('" . $id_pasien . "')";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
//                $query = "SELECT *, date(tgl_masuk) as tgl_masuk FROM rm_penggunaan_kamar
//                          WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $query = "CALL lamaKamar('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                while ($dt = @mysql_fetch_array($result)) {
                    if ($dt['lama_penggunaan'] == 0 && $dt['status'] != 2) {
                        $lama = $this->jmlHari($dt['tgl_masuk'], date('Y-m-d')) + 1;
                    } else {
                        $lama = $dt['lama_penggunaan'];
                    }
                    if ($this->checkDoubleBed($data['id_pendaftaran']))
                        $jmlTarif += $lama * ($dt['tarif'] * 2);
                    else
                        $jmlTarif += $lama * $dt['tarif'];
                }

                //$query = "select sum(tarif) as tarif from rm_tindakan_ruang where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $query = "CALL tindakanRuangTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                //$query = "select (sum(tarif)+sum(penambahan_tarif)) as tarif from rm_tindakan_ruang_medis where id_pendaftaran ='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $query = "CALL tindakanMedisTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                //$query = "select (jumlah*tarif) as tarif from rm_fasilitas_ruang where id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $query = "CALL fasilitasTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);
                while ($dt = @mysql_fetch_array($result)) {
                    $jmlTarif += $dt['tarif'];
                }

//                $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_laboratorium
//                          WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' AND del_flag<>'1'";
                $query = "CALL labTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

//                $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_radiologi
//                          WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $query = "CALL radiologiTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

//                $query = "SELECT b.nama_dokter, SUM(a.ctr) AS jml, a.tarif
//                          FROM rm_visit a, rm_dokter b
//                          WHERE b.id_dokter=a.id_dokter AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' AND a.del_flag<>'1'
//                          GROUP BY a.id_pendaftaran, a.id_dokter";
                $query = "CALL visiteTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);
                $total = 0;
                while ($dt = @mysql_fetch_array($result)) {
                    $total += ( $dt['jml'] * $dt['tarif']);
                }

                $jmlTarif += $total;

                if ($data['id_tipe_pendaftaran'] == '4')
                    $jmlTarif += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '3')
                    $jmlTarif += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '8')
                    $jmlTarif += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '9')
                    $jmlTarif += $data['biaya_pendaftaran'];
                else if ($data['id_ruang'] == '56')
                    $jmlTarif += $data['biaya_pendaftaran'];

            }
            $totTagihan = $this->getAllTagihanObat($id_pasien);
            $jmlTarif += $totTagihan;
        }
        return $jmlTarif;
    }

    public function getAllTagihanPasienKeluar($id_pendaftaran) {
        $jmlTarif = 0;
	$q_daftar = "SELECT id_pendaftaran, id_tipe_pendaftaran, biaya_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran=" . $id_pendaftaran . " 
                     and del_flag<>'1' UNION SELECT id_pendaftaran, id_tipe_pendaftaran, biaya_pendaftaran FROM rm_pendaftaran WHERE 
                     id_asal_pendaftaran=" . $id_pendaftaran . " and del_flag<>'1' UNION SELECT id_pendaftaran, id_tipe_pendaftaran, 
                     biaya_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran=(SELECT max(id_pendaftaran) FROM rm_pendaftaran where 
                     id_pasien = (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") and 
                     DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 
                     and id_ruang=20) and del_flag<>'1' UNION SELECT id_pendaftaran, id_tipe_pendaftaran, biaya_pendaftaran FROM 
                     rm_pendaftaran WHERE id_asal_pendaftaran=(SELECT max(id_pendaftaran) FROM rm_pendaftaran where id_pasien = 
                     (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20)
                      and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "CALL lamaKamar('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                while ($dt = @mysql_fetch_array($result)) {
                    if ($dt['lama_penggunaan'] == 0 && $dt['status'] != 2) {
                        $lama = $this->jmlHari($dt['tgl_masuk'], date('Y-m-d')) + 1;
                    } else {
                        $lama = $dt['lama_penggunaan'];
                    }
                    if ($this->checkDoubleBed($data['id_pendaftaran']))
                        $jmlTarif += $lama * ($dt['tarif'] * 2);
                    else
                        $jmlTarif += $lama * $dt['tarif'];
                }

                $query = "CALL tindakanRuangTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "CALL tindakanMedisTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "CALL fasilitasTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);
                while ($dt = @mysql_fetch_array($result)) {
                    $jmlTarif += $dt['tarif'];
                }

                $query = "CALL labTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "CALL radiologiTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "CALL visiteTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);
                $total = 0;
                while ($dt = @mysql_fetch_array($result)) {
                    $total += ( $dt['jml'] * $dt['tarif']);
                }

                $jmlTarif += $total;

                if ($data['id_tipe_pendaftaran'] == '4')
                    $jmlTarif += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '3')
                    $jmlTarif += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '8')
                    $jmlTarif += $data['biaya_pendaftaran'];
            }
            $totTagihan = $this->getAllTagihanObatKeluar($id_pendaftaran);
            $jmlTarif += $totTagihan;
        }
        return $jmlTarif;
    }

    public function getAllTagihanPasienBanding($id_pasien, $id_kelas) {
        $jmlTarif = 0;

//        $q_daftar = "SELECT id_pendaftaran, id_tipe_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
//                     AND status_pembayaran!='2' and del_flag<>'1'";
        $q_daftar = "CALL pendaftaranTagihan('" . $id_pasien . "')";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT d.id_kamar, b.ruang, c.kelas, DATE(a.tgl_masuk) AS tgl_masuk, DATE(a.tgl_keluar) AS tgl_keluar, a.lama_penggunaan, a.status, a.tarif, d.kamar
                          FROM rm_penggunaan_kamar a, rm_ruang b, rm_kelas c, rm_kamar d, rm_detail_kamar e
                          WHERE b.id_ruang=a.id_ruang AND c.id_kelas=a.id_kelas AND a.id_detail_kamar=e.id_detail_kamar AND e.id_kamar=d.id_kamar
                          AND id_pendaftaran='" . $data['id_pendaftaran'] . "' and a.del_flag<>'1'";
                $result = $this->runQuery($query);

                while ($dt = @mysql_fetch_array($result)) {
                    if ($dt['lama_penggunaan'] == 0 && $dt['status'] != 2) {
                        $lama = $this->jmlHari($dt['tgl_masuk'], date('Y-m-d')) + 1;
                    } else {
                        $lama = $dt['lama_penggunaan'];
                    }
                    $tarifBanding = $this->getTarifKamarInap($data['id_kamar'], $id_kelas);
                    if ($this->checkDoubleBed($data['id_pendaftaran']))
                        $jmlTarif += $lama * ($tarifBanding * 2);
                    else
                        $jmlTarif += $lama * $tarifBanding;
                }

                $query = "select sum(tarif) as tarif from rm_tindakan_ruang where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "select (sum(tarif)+sum(penambahan_tarif)) as tarif from rm_tindakan_ruang_medis where id_pendaftaran ='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "select (jumlah*tarif) as tarif from rm_fasilitas_ruang where id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $result = $this->runQuery($query);
                while ($dt = @mysql_fetch_array($result)) {
                    $jmlTarif += $dt['tarif'];
                }

                $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_laboratorium
                          WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' AND del_flag<>'1'";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_radiologi
                          WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $result = $this->runQuery($query);

                $jmlTarif += @ mysql_result($result, 0, 'tarif');

                $query = "SELECT b.nama_dokter, SUM(a.ctr) AS jml, a.tarif
                          FROM rm_visit a, rm_dokter b
                          WHERE b.id_dokter=a.id_dokter AND a.id_pendaftaran='" . $data['id_pendaftaran'] . "' AND a.del_flag<>'1'
                          GROUP BY a.id_pendaftaran, a.id_dokter";
                $result = $this->runQuery($query);
                $total = 0;
                while ($dt = @mysql_fetch_array($result)) {
                    $total += ( $dt['jml'] * $dt['tarif']);
                }

                $jmlTarif += $total;

                if ($data['id_tipe_pendaftaran'] == '4')
                    $jmlTarif += 20000;
                else if ($data['id_tipe_pendaftaran'] == '3')
                    $jmlTarif += 60000;
            }
            $totTagihan = $this->getAllTagihanObat($id_pasien);
            $jmlTarif += $totTagihan;
        }
        return $jmlTarif;
    }

    public function getAllTagihanObat($id_pasien) {
        $q_tagihan = "SELECT id_faktur_penjualan FROM rm_faktur_penjualan WHERE id_pasien='" . $id_pasien . "'
                          AND ( status='1') AND del_flag<>'1'";
        $r_tagihan = $this->runQuery($q_tagihan);
        $totTagihan = 0;
        if (@mysql_num_rows($r_tagihan) > 0) {
            $totTagihan = 0;
            $diskon = 0;
            while ($rec_tagihan = @mysql_fetch_array($r_tagihan)) {
                $q_diskon = "SELECT diskon FROM rm_diskon_obat WHERE id_faktur = " . $rec_tagihan['id_faktur_penjualan'] . " and del_flag<>1 AND `status`=1";
                $r_diskon = $this->runQuery($q_diskon);
                $diskon = @mysql_result($r_diskon, 0, 'diskon');
                $q_t_ret = "SELECT b.jumlah AS qty, a.harga, a.r_code, b.pros_retur
                            FROM rm_penjualan_obat AS a INNER JOIN rm_retur_penjualan_obat AS b ON a.id_penjualan_obat = b.id_penjualan_obat AND a.id_faktur_penjualan = b.id_faktur_penjualan
                            WHERE b.del_flag <> '1' AND b.id_faktur_penjualan = '" . $rec_tagihan['id_faktur_penjualan'] . "' AND a.del_flag <> '1'";

                $r_t_ret = $this->runQuery($q_t_ret);
                $total = 0;
                $retur = 0;
                while ($rec = mysql_fetch_array($r_t_ret)) {
                    $retur = $rec['qty'] * $rec['harga'] * (1 - $rec['pros_retur']);
                    if ($rec['r_code'] == 'Ya')
                        $total += 200;
                    $total += $retur;
                }
                $t_obat = ($this->getTotalTagihanObat($rec_tagihan['id_faktur_penjualan']) - $total);
                $totTagihan += round($t_obat);
            }
        }

//        $rt = "SELECT b.id_retur faktur, date(b.tgl_retur) as tgl, SUM(((b.jumlah * c.harga) * (1 - b.pros_retur)) * (-1)) as jumlah
//               FROM rm_faktur_penjualan AS a INNER JOIN rm_retur_penjualan_obat AS b ON a.id_faktur_penjualan = b.id_faktur_penjualan 
//               INNER JOIN rm_penjualan_obat AS c ON b.id_faktur_penjualan = c.id_faktur_penjualan AND b.id_penjualan_obat = c.id_penjualan_obat
//               INNER JOIN rm_pendaftaran d ON (d.id_pasien = a.id_pasien AND a.id_pasien = " . $id_pasien . " AND d.status_pembayaran<>2 
//               AND a.id_pendaftaran=d.id_pendaftaran AND d.del_flag<>1) WHERE b.del_flag <> 1 AND c.del_flag <> 1 AND 
//               a.del_flag <> 1 AND a.asuransi=0 AND a.jns_customer='Pasien' AND b.jns_retur<>0 AND date(b.tgl_retur) >= '2012-02-16' GROUP BY b.id_retur";
//        $r_rt = $this->runQuery($rt);
//        $retur_tunai = 0;
//        if (@mysql_num_rows($r_rt) > 0) {
//            while ($rtt = mysql_fetch_array($r_rt)) {
//                $retur_tunai += $rtt['jumlah'];
//            }
//        }

        return $totTagihan + $retur_tunai;
    }

    public function getAllTagihanObatKeluar($id_pendaftaran) {
        $q_tagihan = "SELECT b.id_faktur_penjualan FROM rm_faktur_penjualan a, rm_pembayaran_obat b WHERE a.id_pendaftaran=" . $id_pendaftaran . " 
                      AND a.del_flag<>'1' AND b.del_flag<>1 AND a.id_faktur_penjualan = b.id_faktur_penjualan AND b.tipe_pembayaran = 'Kredit'
                      UNION SELECT b.id_faktur_penjualan FROM rm_faktur_penjualan a, rm_pembayaran_obat b WHERE a.id_pendaftaran=
                      (SELECT max(id_pendaftaran) FROM rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran where 
                      id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) AND a.del_flag<>'1' 
                      AND b.del_flag<>1 AND a.id_faktur_penjualan = b.id_faktur_penjualan AND b.tipe_pembayaran = 'Kredit'";
        $r_tagihan = $this->runQuery($q_tagihan);
        $totTagihan = 0;
        if (@mysql_num_rows($r_tagihan) > 0) {
            $totTagihan = 0;
            while ($rec_tagihan = @mysql_fetch_array($r_tagihan)) {
                $q_t_ret = "SELECT a.id_faktur_penjualan, c.id_obat, b.nama_obat, c.jumlah as qty, a.harga, a.r_code, c.pros_retur
                            FROM rm_penjualan_obat a, rm_obat b, rm_retur_penjualan_obat c, rm_faktur_penjualan d
                            WHERE a.id_penjualan_obat=c.id_penjualan_obat and c.jns_retur = 0 and c.del_flag<>'1' and b.id_obat=a.id_obat and a.id_faktur_penjualan=c.id_faktur_penjualan AND c.id_faktur_penjualan='" . $rec_tagihan['id_faktur_penjualan'] . "' and a.del_flag<>'1' AND a.id_faktur_penjualan = d.id_faktur_penjualan AND d.del_flag <>'1'";

                $r_t_ret = $this->runQuery($q_t_ret);
                $total = 0;
                $retur = 0;
                while ($rec = mysql_fetch_array($r_t_ret)) {
                    $retur = $rec['qty'] * $rec['harga'] * (1 - $rec['pros_retur']);
                    if ($rec['r_code'] == 'Ya')
                        $total += 200;
                    $total += $retur;
                }
                $t_obat = $this->getTotalTagihanObatKeluar($rec_tagihan['id_faktur_penjualan']) - $total;
                $totTagihan += $t_obat;
            }
        }
        return $totTagihan;
    }

    public function getAllDiskonPasien($id_pasien) {
        $diskon = 0;

//        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
//                     AND status_pembayaran!='2' and del_flag<>'1'";
        $q_daftar = "CALL pendaftaranTagihan('" . $id_pasien . "')";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                //$query = "select sum(diskon) as diskon from rm_diskon_tindakan where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $query = "CALL diskonPasien('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $diskon += @ mysql_result($result, 0, 'diskon');
            }
        }
        return $diskon;
    }

    public function getAllDiskonPasienKeluar($id_pendaftaran) {
        $diskon = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE (id_pendaftaran='" . $id_pendaftaran . "' 
                    OR id_asal_pendaftaran='" . $id_pendaftaran . "') and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "CALL diskonPasien('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $diskon += @ mysql_result($result, 0, 'diskon');
            }
        }
        return $diskon;
    }

    public function getAllKarcisPasien($id_pasien) {
        $karcis = 0;

        $q_daftar = "SELECT id_pendaftaran, id_ruang, id_tipe_pendaftaran, biaya_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                if ($data['id_tipe_pendaftaran'] == '4')
                    $karcis += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '3')
                    $karcis += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '8')
                    $karcis += $data['biaya_pendaftaran'];
                else if ($data['id_tipe_pendaftaran'] == '9')
                    $karcis += $data['biaya_pendaftaran'];
                else if ($data['id_ruang'] == '56')
                    $karcis += $data['biaya_pendaftaran'];
            }
        }
        return $karcis;
    }

    public function getAllKarcisDaftar($id_pasien) {
        $html = "";

        $q_daftar = "SELECT id_pendaftaran, id_tipe_pendaftaran, id_ruang, biaya_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<span class='data'><b>KARCIS</b></span>";
            while ($data = @mysql_fetch_array($r_daftar)) {
                if ($data['id_tipe_pendaftaran'] == '4') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Biaya Pendaftaran IGD</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                } else if ($data['id_tipe_pendaftaran'] == '3') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Biaya Pendaftaran Poli Eksekutif</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                } else if ($data['id_tipe_pendaftaran'] == '8') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Biaya Pendaftaran Poli Eksekutif</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                } else if ($data['id_tipe_pendaftaran'] == '9') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Konsulan ". $this->getRuang($data['id_ruang']) ." - Layanan Haji</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                } else if ($data['id_ruang'] == '56') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Biaya Pendaftaran Layanan Haji</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                }
            }
        }
        return $html;
    }

    public function getAllKarcisDaftarKeluar($id_pendaftaran) {
        $html = "";

        $q_daftar = "SELECT id_pendaftaran, id_tipe_pendaftaran, biaya_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran=" . $id_pendaftaran . " and 
                     del_flag<>'1' UNION SELECT id_pendaftaran, id_tipe_pendaftaran, biaya_pendaftaran FROM rm_pendaftaran WHERE 
                     id_asal_pendaftaran=" . $id_pendaftaran . " and del_flag<>'1' UNION SELECT id_pendaftaran, id_tipe_pendaftaran, biaya_pendaftaran 
                     FROM rm_pendaftaran WHERE id_pendaftaran=(SELECT max(id_pendaftaran) FROM rm_pendaftaran where id_pasien = 
                     (select id_pasien from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) 
                     and del_flag<>'1' UNION SELECT id_pendaftaran, id_tipe_pendaftaran, biaya_pendaftaran FROM rm_pendaftaran WHERE 
                     id_asal_pendaftaran=(SELECT max(id_pendaftaran) FROM rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran 
                     where id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            $html .= "<br>";
            $html .= "<span class='data'><b>KARCIS</b></span>";
            while ($data = @mysql_fetch_array($r_daftar)) {
                if ($data['id_tipe_pendaftaran'] == '4') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Biaya Pendaftaran IGD</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                } else if ($data['id_tipe_pendaftaran'] == '3') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Biaya Pendaftaran Poli Eksekutif</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                } else if ($data['id_tipe_pendaftaran'] == '8') {
                    $html .= "<table class='data' width='100%'>";
                    $html .= "<tbody>";
                    $html .= "<tr>";
                    $html .= "<td width='10%'>&nbsp;</td>";
                    $html .= "<td width='70%'>Biaya Pendaftaran Poli Eksekutif</td>";
                    $html .= "<td width='20%' align='right'>Rp." . number_format($data['biaya_pendaftaran'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $html .= "</tbody>";
                    $html .= "</table>";
                }
            }
        }
        return $html;
    }

    public function getAllBayarPasien($id_pasien) {
        $bayar = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND (status_pembayaran!='2' or status_pendaftaran!='2') and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                //$query = "select sum(bayar) as bayar from rm_pembayaran_tagihan where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $query = "CALL bayarTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $bayar += @ mysql_result($result, 0, 'bayar');
            }
        }
        return $bayar;
    }

    public function getAllBayarPasienKeluar($id_pendaftaran) {
        $bayar = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "CALL bayarTagihan('" . $data['id_pendaftaran'] . "')";
                $result = $this->runQuery($query);

                $bayar += @ mysql_result($result, 0, 'bayar');
            }
        }
        return $bayar;
    }

    public function getAllAsuransiPasien($id_pasien) {
        $bayar = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND (status_pembayaran!='2' or status_pendaftaran!='2') and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "select sum(asuransi) as asuransi from rm_tagihan_asuransi where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $result = $this->runQuery($query);

                $bayar += @ mysql_result($result, 0, 'asuransi');
            }
        }
        return $bayar;
    }

    public function getAllAsuransiPasienKeluar($id_pendaftaran) {
        $bayar = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "select sum(asuransi) as asuransi from rm_tagihan_asuransi where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $result = $this->runQuery($query);

                $bayar += @ mysql_result($result, 0, 'asuransi');
            }
        }
        return $bayar;
    }

    public function getTotalTagihanPasien($id_pasien) {
        $bayar = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            $jmlTarif = $this->getAllTagihanPasien($id_pasien);
            $obat = $this->getAllTagihanObat($id_pasien);
            $diskon = $this->getAllDiskonPasien($id_pasien);
            $bayar = $this->getAllBayarPasien($id_pasien);
            $asuransi = $this->getAllAsuransiPasien($id_pasien);

            $html = "<table class='data' width='100%'>";
            $html .= "<tbody>";
            $html .= "<tr>";
            $html .= "<td width='50%' align='right'>Lamongan, " . date('d-m-Y') . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $html .= "<td width='30%' align='right' class='total'>Total Tagihan</td>";
            $html .= "<td width='20%' align='right' class='total'>Rp. " . number_format($jmlTarif, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%' align='center'><b>KASIR</b></td>";
            $html .= "<td width='30%' align='right'>Tagihan Obat</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($obat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Tagihan Terbayar</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($bayar, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Asuransi</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($asuransi, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Diskon</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($diskon, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%' align='center'><b><u>" . $_SESSION['nama_pegawai'] . "</u></b></td>";
            $html .= "<td width='30%' align='right' class='total'>Tagihan Yang Harus Dibayar</td>";
            $html .= "<td width='20%' align='right' class='total'>Rp. " . number_format(($jmlTarif - $bayar - $asuransi - $diskon), 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html = "KOSONG";
        }
        return $html;
    }

    public function getTotalTagihanPasienKeluar($id_pendaftaran) {
        $bayar = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran=" . $id_pendaftaran . " and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            $jmlTarif = $this->getAllTagihanPasienKeluar($id_pendaftaran);
            $obat = $this->getAllTagihanObatKeluar($id_pendaftaran);
            $diskon = $this->getAllDiskonPasienKeluar($id_pendaftaran);
            $bayar = $this->getAllBayarPasienKeluar($id_pendaftaran);
            $asuransi = $this->getAllAsuransiPasienKeluar($id_pendaftaran);

            $html = "<table class='data' width='100%'>";
            $html .= "<tbody>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right' class='total'>Total Tagihan</td>";
            $html .= "<td width='20%' align='right' class='total'>Rp. " . number_format($jmlTarif, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Tagihan Obat</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($obat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Tagihan Terbayar</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($bayar, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Asuransi</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($asuransi, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Diskon</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($diskon, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right' class='total'>Tagihan Yang Harus Dibayar</td>";
            $html .= "<td width='20%' align='right' class='total'>Rp. " . number_format(($jmlTarif - $bayar - $asuransi - $diskon), 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html = "kosong";
        }
        return $html;
    }

    public function getTotalTagihanPasienBanding($id_pasien, $id_kelas) {
        $bayar = 0;

        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            $jmlTarif = $this->getAllTagihanPasien($id_pasien);
            $jmlTarifBanding = $this->getAllBanding();

            $html = "<table class='data' width='100%'>";
            $html .= "<tbody>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right' class='total'>Total Tagihan</td>";
            $html .= "<td width='20%' align='right' class='total'>Rp. " . number_format($jmlTarif, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right'>Total Tagihan Pembanding</td>";
            $html .= "<td width='20%' align='right'>Rp. " . number_format($jmlTarifBanding, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='50%'>&nbsp;</td>";
            $html .= "<td width='30%' align='right' class='total'>Selisih Tagihan</td>";
            $html .= "<td width='20%' align='right' class='total'>Rp. " . number_format($jmlTarif - $jmlTarifBanding, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html = "cok";
        }
        return $html;
    }

    function getBiayaLaborat($id_pendaftaran) {
        $tarif = 0;

        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_laboratorium WHERE id_pendaftaran='" . $id_pendaftaran . "' AND del_flag<>'1'";
        $result = $this->runQuery($query);

        $tarif += @ mysql_result($result, 0, 'tarif');

//        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_laboratorium WHERE id_pendaftaran in (
//                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1') AND del_flag<>'1'";
        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_laboratorium a JOIN rm_pendaftaran b ON (a.id_pendaftaran = b.id_pendaftaran and b.del_flag<>1 and 
                    b.id_asal_pendaftaran=" . $id_pendaftaran . ") WHERE a.del_flag<>'1'";
        $result = $this->runQuery($query);

        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaLaboratA($id_pasien) {
        $tarif = 0;
        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_laboratorium a JOIN (SELECT id_pendaftaran FROM rm_pendaftaran WHERE 
                  id_pasien=" . $id_pasien . " AND status_pembayaran!='2' and del_flag<>'1') b ON(a.id_pendaftaran = b.id_pendaftaran) WHERE a.del_flag<>'1'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');
        return $tarif;
    }

    function getBiayaFasilitasA($id_pasien) {
        $tarif = 0;
        $query = "SELECT SUM(harga) as tarif FROM (SELECT (jumlah*tarif) as harga FROM rm_fasilitas_ruang a JOIN (SELECT id_pendaftaran 
                  FROM rm_pendaftaran WHERE id_pasien=" . $id_pasien . " AND status_pembayaran!='2' and del_flag<>'1') b ON 
                  (a.id_pendaftaran = b.id_pendaftaran)) utilitas";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaRadiologiA($id_pasien) {
        $tarif = 0;
        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_radiologi a JOIN (SELECT id_pendaftaran FROM rm_pendaftaran WHERE 
                  id_pasien=" . $id_pasien . " AND status_pembayaran!='2' and del_flag<>'1') b ON (a.id_pendaftaran = b.id_pendaftaran)
                  WHERE del_flag<>'1'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaRadiologi($id_pendaftaran) {
        $tarif = 0;
        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_radiologi WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        $tarif += @ mysql_result($result, 0, 'tarif');

//        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_radiologi WHERE id_pendaftaran in (
//                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1') and del_flag<>'1'";
        $query = "SELECT cito, SUM(tarif) AS tarif FROM rm_detail_radiologi a JOIN rm_pendaftaran b ON (a.id_pendaftaran = b.id_pendaftaran and b.del_flag<>1 and 
                    b.id_asal_pendaftaran=" . $id_pendaftaran . ") WHERE a.del_flag<>'1'";
        $result = $this->runQuery($query);

        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaFasilitas($id_pendaftaran) {
        $tarif = 0;
        $query = "SELECT SUM(harga) as tarif FROM (SELECT jumlah*tarif as harga FROM rm_fasilitas_ruang WHERE id_pendaftaran='" . $id_pendaftaran . "') utilitas";
        $result = $this->runQuery($query);

        $tarif += @ mysql_result($result, 0, 'tarif');

//        $query = "SELECT SUM(harga) as tarif FROM (SELECT jumlah*tarif as harga FROM rm_fasilitas_ruang WHERE id_pendaftaran in (
//                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1')) fasilitas";
        $query = "SELECT SUM(harga) as tarif FROM (SELECT jumlah*tarif as harga FROM rm_fasilitas_ruang a JOIN rm_pendaftaran b ON (a.id_pendaftaran = 
                        b.id_pendaftaran and b.del_flag<>1 and b.id_asal_pendaftaran=" . $id_pendaftaran . ") WHERE a.del_flag<>1) fasilitas";
        $result = $this->runQuery($query);

        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaKarcis($id_pendaftaran) {
        $tarif = 0;
        $q_daftar = "SELECT biaya_pendaftaran FROM rm_pendaftaran WHERE id_pendaftaran='" . $id_pendaftaran . "' 
                     and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            $tarif = @mysql_result($r_daftar, 0, 'biaya_pendaftaran');
        }

        return $tarif;
    }

    function getBiayaTindakan($id_pasien) {
        $tarif = 0;
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $result = $this->runQuery($query);

                $tarif += @ mysql_result($result, 0, 'tarif');

                $query = "SELECT (SUM(penambahan_tarif)+SUM(tarif)) AS tarif FROM rm_tindakan_ruang_medis WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $result = $this->runQuery($query);

                $tarif += @ mysql_result($result, 0, 'tarif');
            }
        }

        return $tarif;
    }

    function getBiayaTindakanAkhir($id_pasien) {
        $tarif = 0;
        $q_daftar = "SELECT max(id_pendaftaran) as id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1' and id_asal_pendaftaran=0";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                $result = $this->runQuery($query);

                $tarif += @ mysql_result($result, 0, 'tarif');

                $query = "SELECT (SUM(penambahan_tarif)+SUM(tarif)) AS tarif FROM rm_tindakan_ruang_medis WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $result = $this->runQuery($query);

                $tarif += @ mysql_result($result, 0, 'tarif');
            }
        }

        return $tarif;
    }

    function getBiayaTindakanPoli($id_pendaftaran) {
        $tarif = 0;
        $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang WHERE id_pendaftaran='" . $id_pendaftaran . "'
                  and id_ruang!='19'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');
//        $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang WHERE id_pendaftaran in (
//                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "')
//                  and id_ruang!='19'";
        $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang a JOIN rm_pendaftaran b ON (a.id_pendaftaran = b.id_pendaftaran and b.del_flag<>1 and 
                    b.id_asal_pendaftaran = " . $id_pendaftaran . ") WHERE a.id_ruang!='19'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaTindakanPA($id_pendaftaran) {
        $tarif = 0;
        $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang WHERE id_pendaftaran='" . $id_pendaftaran . "'
                  and id_ruang='19'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');
//        $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang WHERE id_pendaftaran in (
//                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "')
//                  and id_ruang='19'";
        $query = "SELECT SUM(tarif) AS tarif FROM rm_tindakan_ruang a JOIN rm_pendaftaran b ON (a.id_pendaftaran = b.id_pendaftaran and b.del_flag<>1 and 
                    b.id_asal_pendaftaran=" . $id_pendaftaran . ") WHERE a.id_ruang='19'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaTindakanBedah($id_pendaftaran) {
        $tarif = 0;
        $query = "SELECT (SUM(penambahan_tarif)+SUM(tarif)) AS tarif FROM rm_tindakan_ruang_medis WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');
//        $query = "SELECT (SUM(penambahan_tarif)+SUM(tarif)) AS tarif FROM rm_tindakan_ruang_medis WHERE del_flag<>1 and id_pendaftaran in (
//                  select id_pendaftaran from rm_pendaftaran where del_flag<>1 and id_asal_pendaftaran='" . $id_pendaftaran . "')";
        $query = "SELECT (SUM(penambahan_tarif)+SUM(tarif)) AS tarif FROM rm_tindakan_ruang_medis a JOIN rm_pendaftaran b ON (a.id_pendaftaran = b.id_pendaftaran 
                    and b.del_flag<>1 and b.id_asal_pendaftaran = " . $id_pendaftaran . ") WHERE a.del_flag<>1";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBiayaVisitRuang($id_pasien) {
        $tarif = 0;
        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                $query = "SELECT SUM(tarif) AS tarif FROM rm_visit WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $result = $this->runQuery($query);

                $tarif += @ mysql_result($result, 0, 'tarif');

                $query = "SELECT *, date(tgl_masuk) as tgl_masuk FROM rm_penggunaan_kamar
                          WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>'1'";
                $result = $this->runQuery($query);

                while ($dt = @mysql_fetch_array($result)) {
                    if ($dt['lama_penggunaan'] == 0 && $dt['status'] != 2) {
                        $lama = $this->jmlHari($dt['tgl_masuk'], date('Y-m-d')) + 1;
                    } else {
                        $lama = $dt['lama_penggunaan'];
                    }
                    $tarif += $lama * $dt['tarif'];
                }
            }
        }
        return $tarif;
    }

    function getBiayaSewaRuang($id_pendaftaran) {
        $tarif = 0;
//        $query = "SELECT *, date(tgl_masuk) as tgl_masuk FROM rm_penggunaan_kamar
//                  WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $query = "CALL lamaKamar('" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        while ($dt = @mysql_fetch_array($result)) {
            if ($dt['lama_penggunaan'] == 0) {
                $lama = $this->jmlHari($dt['tgl_masuk'], date('Y-m-d')) + 1;
            } else {
                $lama = $dt['lama_penggunaan'];
            }
            if ($this->checkDoubleBed($id_pendaftaran))
                $tarif += $lama * ($dt['tarif'] * 2);
            else
                $tarif += $lama * $dt['tarif'];
        }

        return $tarif;
    }

    function getBiayaAllVisit($id_pendaftaran) {
        $tarif = 0;
        $query = "SELECT SUM(tarif) AS tarif FROM rm_visit WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');
//        $query = "SELECT SUM(tarif) AS tarif FROM rm_visit WHERE id_pendaftaran in (
//                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "') and del_flag<>'1'";
        $query = "SELECT SUM(tarif) AS tarif FROM rm_visit a JOIN rm_pendaftaran b ON (a.id_pendaftaran = b.id_pendaftaran and b.del_flag<>1 and 
                    b.id_asal_pendaftaran=" . $id_pendaftaran . ") WHERE a.del_flag<>1";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'tarif');

        return $tarif;
    }

    function getBayar($id_pembayaran) {
        $tarif = 0;
        $query = "SELECT bayar FROM rm_pembayaran_tagihan WHERE id_pembayaran_tagihan='" . $id_pembayaran . "' and del_flag<>1";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'bayar');

        return $tarif;
    }
    
    function getAdminBank($id_pembayaran) {
        $tarif = 0;
        $query = "SELECT administrasi FROM rm_pembayaran_tagihan WHERE id_pembayaran_tagihan='" . $id_pembayaran . "' and del_flag<>1";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'administrasi');

        return $tarif;
    }

    function getAsuransi($id_pendaftaran) {
        $tarif = 0;
        $query = "SELECT sum(asuransi) as bayar FROM rm_tagihan_asuransi WHERE id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);
        $tarif += @ mysql_result($result, 0, 'bayar');

        return $tarif;
    }

    function getAsuransiA($id_pasien) {
        $tarif = 0;
        $q_daftar = "CALL pendaftaranTagihan('" . $id_pasien . "')";
        $r_daftar = $this->runQuery($q_daftar);
        while ($dt = @mysql_fetch_array($r_daftar)) {
            $query = "SELECT sum(asuransi) as bayar FROM rm_tagihan_asuransi WHERE id_pendaftaran='" . $dt['id_pendaftaran'] . "'";
            $result = $this->runQuery($query);
            $tarif += @ mysql_result($result, 0, 'bayar');
        }
        return $tarif;
    }

    function getBiayaTotal($id_pendaftaran) {
        $jmlTarif = 0;
        $bayar = 0;
        $query = "SELECT *, date(tgl_masuk) as tgl_masuk FROM rm_penggunaan_kamar
                  WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        while ($dt = @mysql_fetch_array($result)) {
            if ($dt['lama_penggunaan'] == 0) {
                $lama = $this->jmlHari($dt['tgl_masuk'], date('Y-m-d')) + 1;
            } else {
                $lama = $dt['lama_penggunaan'];
            }
            if ($this->checkDoubleBed($id_pendaftaran))
                $jmlTarif += $lama * ($dt['tarif'] * 2);
            else
                $jmlTarif += $lama * $dt['tarif'];
        }

        $query = "select sum(tarif) as tarif from rm_tindakan_ruang where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "select sum(tarif) as tarif from rm_tindakan_ruang where id_pendaftaran in (
                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "select (sum(tarif)+sum(penambahan_tarif)) as tarif from rm_tindakan_ruang_medis where id_pendaftaran in (
                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "select SUM(jumlah)*sum(tarif) as tarif from rm_fasilitas_ruang where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "select SUM(jumlah)*sum(tarif) as tarif from rm_fasilitas_ruang where id_pendaftaran in (
                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "SELECT SUM(b.tarif) AS tarif FROM rm_pemeriksaan_lab a, rm_detail_laboratorium b
                  WHERE a.id_pendaftaran in (select id_pendaftaran from rm_pendaftaran 
                  where id_asal_pendaftaran='" . $id_pendaftaran . "') AND b.id_pendaftaran in (
                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "') and b.del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "SELECT SUM(b.tarif) AS tarif FROM rm_pemeriksaan_lab a, rm_detail_laboratorium b
                  WHERE a.id_pendaftaran='" . $id_pendaftaran . "' AND b.id_pendaftaran=a.id_pendaftaran and b.del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_radiologi
                  WHERE id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_radiologi
                  WHERE id_pendaftaran in (select id_pendaftaran from rm_pendaftaran 
                  where id_asal_pendaftaran='" . $id_pendaftaran . "' and status_pembayaran!='2') and del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');
        $id_ruang = $this->getRuangDaftar($id_pendaftaran);
        if ($id_ruang == '20')
            $jmlTarif += 20000;
        $query = "SELECT b.nama_dokter, SUM(a.ctr) AS jml, a.tarif
                  FROM rm_visit a, rm_dokter b
                  WHERE b.id_dokter=a.id_dokter AND a.id_pendaftaran='" . $id_pendaftaran . "' AND a.del_flag<>'1'
                  GROUP BY a.id_pendaftaran, a.id_dokter";
        $result = $this->runQuery($query);
        $total = 0;
        while ($data = @mysql_fetch_array($result)) {
            $total += ( $data['jml'] * $data['tarif']);
        }

        $jmlTarif += $total;

        return $jmlTarif;
    }

    function getDiskon($id_pendaftaran) {
        $query = "select sum(diskon) as diskon from rm_diskon_tindakan where id_pendaftaran='" . $id_pendaftaran . "'  and del_flag<>1";
        $result = $this->runQuery($query);

        $diskon = @mysql_result($result, 0, 'diskon');

        return $diskon;
    }

    function getDiskonAll($id_pasien) {
//        $q_daftar = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
//                     AND status_pembayaran!='2' and del_flag<>'1'";
//        $r_daftar = $this->runQuery($q_daftar);
//        if (@mysql_num_rows($r_daftar) > 0) {
//            $diskon = 0;
//            while ($data = @mysql_fetch_array($r_daftar)) {
//                $query = "select sum(diskon) as diskon from rm_diskon_tindakan where id_pendaftaran='" . $data['id_pendaftaran'] . "'  and del_flag<>1";
//                $result = $this->runQuery($query);
//                $diskon += @ mysql_result($result, 0, 'diskon');
//            }
//        }
        $diskon = 0;
        $query = "SELECT sum(b.diskon) as diskon FROM rm_pendaftaran a LEFT JOIN ( SELECT id_pendaftaran, sum(diskon) as diskon from rm_diskon_tindakan 
                  where del_flag<>1 GROUP BY id_pendaftaran) b ON (a.id_pendaftaran=b.id_pendaftaran) WHERE a.id_pasien=" . $id_pasien . " 
                  AND a.status_pembayaran!='2' and a.del_flag<>'1' ";
        $result = $this->runQuery($query);
        $diskon += @ mysql_result($result, 0, 'diskon');
        return $diskon;
    }

    function getTagihanTerbayar($id_pendaftaran) {
        $bayar = 0;
        $query = "select sum(bayar) as bayar from rm_pembayaran_tagihan where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
        $result = $this->runQuery($query);

        $bayar += @ mysql_result($result, 0, 'bayar');

        return $bayar;
    }

    function getAsuransiTagihan($id_pendaftaran) {
        $bayar = 0;
        //$query = "select sum(asuransi) as asuransi from rm_tagihan_asuransi where id_pendaftaran='" . $id_pendaftaran . "'";
        $query = "CALL asuransiTagihan('" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        $bayar += @ mysql_result($result, 0, 'asuransi');

        return $bayar;
    }

    function pembilang($n) {
        $this->dasar = array(1 => 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
        $this->angka = array(1000000000000, 1000000000, 1000000, 1000, 100, 10, 1);
        $this->satuan = array('Trilyun', 'Milyar', 'Juta', 'Ribu', 'Ratus', 'Puluh', '');
        $str = "";
        $i = 0;
        if ($n == 0) {
            $str = "nol";
        } else {
            while ($n != 0) {
                $count = (int) ($n / $this->angka[$i]);
                if ($count >= 10) {
                    $str .= $this->pembilang($count) . " " . $this->satuan[$i] . " ";
                } else if ($count > 0 && $count < 10) {
                    $str .= $this->dasar[$count] . " " . $this->satuan[$i] . " ";
                }
                $n -= $this->angka[$i] * $count;
                $i++;
            }
            $str = preg_replace("/satu puluh (\w+)/i", "\\1 belas", $str);
            $str = preg_replace("/satu (ribu|ratus|puluh|belas)/i", "Se\\1", $str);
        }
        return $str;
    }

    public function getTotalTagihanObat($id_faktur_penjualan) {
        $jmlTarif = 0;
        $query = "SELECT qty, harga, r_code FROM rm_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;

                $jmlTarif += $total;
            }
        }

        $query = "SELECT id_racikan FROM rm_racikan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_detail = "select qty, harga, r_code from rm_detail_racikan where id_racikan='" . $rec['id_racikan'] . "' and del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                while ($detail = mysql_fetch_array($r_detail)) {
                    $total = $detail['qty'] * $detail['harga'];
                    if ($detail['r_code'] == 'Ya')
                        $total = $total + 200;

                    $jmlTarif += $total;
                }
                $jmlTarif += 500;
            }
        }

        $q_pembayaran = "select sum(bayar) as pembayaran, asuransi, sum(diskon) as diskon from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
        $r_pembayaran = $this->runQuery($q_pembayaran);

        $jmlTarif = $jmlTarif - (@mysql_result($r_pembayaran, 0, 'pembayaran') + @mysql_result($r_pembayaran, 0, 'asuransi') + @mysql_result($r_pembayaran, 0, 'diskon'));

        return round($jmlTarif);
    }

    public function getTotalTagihanObatKeluar($id_faktur_penjualan) {
        $jmlTarif = 0;
        $query = "SELECT qty, harga, r_code FROM rm_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;

                $jmlTarif += $total;
            }
        }

        $query = "SELECT id_racikan FROM rm_racikan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_detail = "select qty, harga, r_code from rm_detail_racikan where id_racikan='" . $rec['id_racikan'] . "' and del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                while ($detail = mysql_fetch_array($r_detail)) {
                    $total = $detail['qty'] * $detail['harga'];
                    if ($detail['r_code'] == 'Ya')
                        $total = $total + 200;

                    $jmlTarif += $total;
                }
                $jmlTarif += 500;
            }
        }
        return $jmlTarif;
    }

    public function getTotalTagihanObatAsuransi($id_faktur_penjualan) {
        $jmlTarif = 0;
        $query = "SELECT qty, harga, r_code FROM rm_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;

                $jmlTarif += $total;
            }
        }

        $query = "SELECT id_racikan FROM rm_racikan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_detail = "select qty, harga, r_code from rm_detail_racikan where id_racikan='" . $rec['id_racikan'] . "' and del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                while ($detail = mysql_fetch_array($r_detail)) {
                    $total = $detail['qty'] * $detail['harga'];
                    if ($detail['r_code'] == 'Ya')
                        $total = $total + 200;

                    $jmlTarif += $total;
                }
                $jmlTarif += 500;
            }
        }

        $q_pembayaran = "select sum(bayar) as pembayaran, asuransi, sum(diskon) as diskon from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
        $r_pembayaran = $this->runQuery($q_pembayaran);

        $jmlTarif = $jmlTarif - (@mysql_result($r_pembayaran, 0, 'pembayaran') + @mysql_result($r_pembayaran, 0, 'diskon'));

        return $jmlTarif;
    }

    public function getJumlahObatAsuransi($id_faktur_penjualan) {
        $q_pembayaran = "select sum(asuransi) as asuransi from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
        $r_pembayaran = $this->runQuery($q_pembayaran);

        $hasil = @mysql_result($r_pembayaran, 0, 'asuransi');
        return $hasil;
    }

    public function getJumlahTagihanObat($id_faktur_penjualan) {
        $jmlTarif = 0;
        $query = "SELECT qty, harga, r_code FROM rm_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;

                $jmlTarif += $total;
            }
        }

        $query = "SELECT id_racikan FROM rm_racikan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_detail = "select qty, harga, r_code from rm_detail_racikan where id_racikan='" . $rec['id_racikan'] . "' and del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                while ($detail = mysql_fetch_array($r_detail)) {
                    $total = $detail['qty'] * $detail['harga'];
                    if ($detail['r_code'] == 'Ya')
                        $total = $total + 200;

                    $jmlTarif += $total;
                }
                $jmlTarif += 500;
            }
        }

        return $jmlTarif;
    }

    public function getJumlahReturTagihanObat($id_faktur_penjualan) {
        $jmlRetur = 0;
        $query = "CALL jmlRetTagihanObt('" . $id_faktur_penjualan . "')";
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $jmlRetur += $rec['jumlah'] * $rec['harga'] * (1 - $rec['pros_retur']);
            }
        }

        return $jmlRetur;
    }

    public function getPendapatanIGD($kondisi) {
        $total = 0;
        //karcis
        $query = "SELECT SUM(biaya_pendaftaran) as jumlah FROM rm_pendaftaran WHERE del_flag<>'1' AND id_ruang='20' AND status_pembayaran=2
                  and id_tipe_pasien NOT IN (2,3,4,5,10,11,12) and tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //tindakan
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_tindakan_ruang a, rm_pendaftaran b WHERE b.del_flag<>1 AND a.id_ruang='20' AND a.id_pendaftaran=b.id_pendaftaran
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND b.status_pembayaran=2 AND a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //fasilitas
        $query = "SELECT SUM(jumlah*tarif) AS jumlah FROM rm_fasilitas_ruang a, rm_pendaftaran b WHERE a.del_flag<>'1' and a.id_ruang='20' AND a.id_pendaftaran=b.id_pendaftaran
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //visit
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_visit a, rm_pendaftaran b WHERE a.del_flag<>'1' and a.id_ruang='20' AND a.id_pendaftaran=b.id_pendaftaran
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        return $total;
    }

    public function getPendapatanBedah($kondisi) {
        $total = 0;
        //ruang
        $query = "SELECT SUM(lama_penggunaan*tarif) as jumlah FROM rm_penggunaan_kamar
                  WHERE del_flag<>'1' and id_tipe_pasien NOT IN (2,3,4,5,10,11,12) and id_ruang='22' and tgl_keluar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //tindakan
        $query = "SELECT sum(tarif+penambahan_tarif) AS jumlah FROM rm_tindakan_ruang_medis a, rm_pendaftaran b WHERE a.del_flag<>'1' AND a.id_pendaftaran=b.id_pendaftaran
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //fasilitas
        $query = "SELECT SUM(jumlah*tarif) AS jumlah FROM rm_fasilitas_ruang a, rm_pendaftaran b WHERE a.del_flag<>'1' and a.id_ruang='22' AND a.id_pendaftaran=b.id_pendaftaran
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        return $total;
    }

    public function getPendapatanRawatJalan($kondisi) {
        $total = 0;
        //karcis
        $query = "SELECT SUM(biaya_pendaftaran) as jumlah FROM rm_pendaftaran WHERE del_flag<>'1' AND id_ruang in (
                    SELECT id_ruang FROM rm_ruang WHERE id_tipe_ruang IN ('2','9')
                  ) and id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND status_pembayaran=2 AND tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //tindakan
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_tindakan_ruang a, rm_pendaftaran b WHERE a.id_ruang in (
                    SELECT id_ruang FROM rm_ruang WHERE id_tipe_ruang IN ('2','9')
                  ) and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //fasilitas
        $query = "SELECT SUM(jumlah*tarif) AS jumlah FROM rm_fasilitas_ruang a, rm_pendaftaran b WHERE a.del_flag<>'1' AND a.id_ruang in (
                    SELECT id_ruang FROM rm_ruang WHERE id_tipe_ruang IN ('2','9')
                  ) and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        return $total;
    }

    public function getPendapatanRawatInap($kondisi) {
        $total = 0;
        //ruang
        $query = "SELECT SUM(lama_penggunaan*tarif) as jumlah FROM rm_penggunaan_kamar a, rm_pendaftaran b
                  WHERE a.del_flag<>'1' and a.id_ruang in (
                    SELECT id_ruang FROM rm_ruang WHERE id_tipe_ruang='8'
                  ) and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //tindakan
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_tindakan_ruang a, rm_pendaftaran b WHERE a.id_ruang in (
                    SELECT id_ruang FROM rm_ruang WHERE id_tipe_ruang='8'
                  ) and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //fasilitas
        $query = "SELECT SUM(jumlah*tarif) AS jumlah FROM rm_fasilitas_ruang a, rm_pendaftaran b WHERE a.del_flag<>'1' AND a.id_ruang in (
                    SELECT id_ruang FROM rm_ruang WHERE id_tipe_ruang='8'
                  ) and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //visit
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_visit a,rm_pendaftaran b WHERE a.del_flag<>'1' and a.id_ruang in (
                    SELECT id_ruang FROM rm_ruang WHERE id_tipe_ruang='8'
                  ) and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        return $total;
    }

    public function getPendapatanRuangRawatJalan($kondisi, $ruang) {
        $total = 0;
        //karcis
        $query = "SELECT SUM(biaya_pendaftaran) as jumlah FROM rm_pendaftaran WHERE del_flag<>'1' AND status_pembayaran=2 AND id_ruang='" . $ruang . "'
                  and id_tipe_pasien NOT IN (2,3,4,5,10,11,12) and tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //tindakan
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_tindakan_ruang a,rm_pendaftaran b WHERE a.id_ruang='" . $ruang . "'
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //fasilitas
        $query = "SELECT SUM(jumlah*tarif) AS jumlah FROM rm_fasilitas_ruang a, rm_pendaftaran b WHERE a.del_flag<>'1' AND a.id_ruang='" . $ruang . "'
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        return $total;
    }

    public function getPendapatanRuangRawatInap($kondisi, $ruang) {
        $total = 0;
        //ruang
        $query = "SELECT SUM(lama_penggunaan*tarif) as jumlah FROM rm_penggunaan_kamar a,rm_pendaftaran b
                  WHERE a.del_flag<>'1' and a.id_ruang='" . $ruang . "'
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //tindakan
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_tindakan_ruang a,rm_pendaftaran b WHERE a.id_ruang='" . $ruang . "'
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //fasilitas
        $query = "SELECT SUM(jumlah*tarif) AS jumlah FROM rm_fasilitas_ruang a,rm_pendaftaran b WHERE a.del_flag<>'1' AND a.id_ruang='" . $ruang . "'
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        //visit
        $query = "SELECT SUM(tarif) AS jumlah FROM rm_visit a, rm_pendaftaran b WHERE a.del_flag<>'1' and a.id_ruang='" . $ruang . "'
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 and a.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);
        $total += @ mysql_result($result, 0, 'jumlah');

        return $total;
    }

    public function getPendapatanLaboratorium($kondisi) {
        $total = 0;
        //ruang
        $query = "SELECT SUM(b.tarif) AS tarif FROM rm_pemeriksaan_lab a, rm_detail_laboratorium b, rm_pendaftaran c
                  WHERE b.id_pendaftaran=a.id_pendaftaran and b.del_flag<>'1' and b.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) 
                  AND c.id_pendaftaran=b.id_pendaftaran AND c.status_pembayaran=2 AND c.del_flag<>1 and b.tgl_bayar" . $kondisi;
        $result = $this->runQuery($query);

        $total += @ mysql_result($result, 0, 'tarif');


        return $total;
    }

    public function setJumlahObat($id_faktur) {
        $q_cek = "SELECT `faktur`, sum(a.jumlah) AS `jumlah` FROM (SELECT a.id_faktur_penjualan AS `faktur`, COUNT(*) as `jumlah` FROM
                  rm_penjualan_obat AS a WHERE a.del_flag <> 1 AND a.id_faktur_penjualan = " . $id_faktur . " GROUP BY a.id_faktur_penjualan UNION 
                  SELECT b.id_faktur_penjualan AS `faktur`, Sum(a.jml) AS `jumlah` FROM rm_detail_racikan AS a , rm_racikan AS b WHERE 
                  a.id_racikan = b.id_racikan AND a.del_flag <> 1 AND b.id_faktur_penjualan=" . $id_faktur . " AND b.del_flag <> 1 GROUP BY 
                  b.id_faktur_penjualan) a GROUP BY `faktur` ORDER BY faktur";
        $r_cek = $this->runQuery($q_cek);

        $qJr = "SELECT COUNT(id_faktur_penjualan) as `jumlah` FROM rm_racikan WHERE del_flag<>1 and id_faktur_penjualan=" . $id_faktur . " GROUP BY id_faktur_penjualan";
        $rJr = $this->runQuery($qJr);

        $qJo = "SELECT Count(0) AS `jumlah` FROM rm_detail_racikan AS a, rm_racikan AS b WHERE a.del_flag <> 1 AND b.del_flag <> 1 AND a.id_racikan = b.id_racikan 
                AND b.id_faktur_penjualan = " . $id_faktur . " GROUP BY b.id_faktur_penjualan";
        $rJo = $this->runQuery($qJo);

        $jmlRacikan = @mysql_result($rJr, 0, 'jumlah');
        $jmlObat = @mysql_result($rJo, 0, 'jumlah');

        if (!empty($jmlRacikan)) {
            $up2 = "UPDATE rm_racikan SET jml_racikan = " . $jmlRacikan . " WHERE id_faktur_penjualan=" . $id_faktur . "";
            $run2 = $this->runQuery($up2);
        }
        if (!empty($jmlObat)) {
            $up3 = "UPDATE rm_racikan SET jml_obat = " . $jmlObat . " WHERE id_faktur_penjualan=" . $id_faktur . "";
            $run3 = $this->runQuery($up3);
        }

        if ($r_cek) {
            $up = "UPDATE rm_faktur_penjualan SET jml_obat = " . @mysql_result($r_cek, 0, 'jumlah') . " WHERE id_faktur_penjualan=" . $id_faktur . "";
            $run = $this->runQuery($up);
        }
    }

    public function getPendapatanRadiologi($kondisi) {
        $total = 0;
        //ruang
        $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_radiologi a, rm_pendaftaran b
                  WHERE a.del_flag<>'1' AND a.id_pendaftaran=b.id_pendaftaran AND b.status_pembayaran=2 AND b.del_flag<>1 
                  and a.id_tipe_pasien NOT IN (2,3,4,5,10,11,12) and date(a.tgl_bayar)" . $kondisi;
        $result = $this->runQuery($query);

        $total += @ mysql_result($result, 0, 'tarif');

        return $total;
    }

    public function getPendapatanFarmasi($startDate, $endDate) {
        //tunai
        $tunai = 0;
        $kondisi = "";
        $kondisi .= " AND tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
        
        $query = "SELECT sum(bayar) as bayar FROM rm_pembayaran_obat WHERE del_flag<>1 AND auto=0 ".$kondisi;
        $result = $this->runQuery($query);
        $tunai = @mysql_result($result, 0, 'bayar');
        return $tunai;
    }
    
    public function getFarmasiDiskon($startDate, $endDate) {
        //tunai
        $tunai = 0;
        $kondisi = "";
        $kondisi .= " AND tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
        
        $query = "SELECT sum(diskon) as diskon FROM rm_pembayaran_obat WHERE del_flag<>1 AND auto=0 ".$kondisi;
        $result = $this->runQuery($query);
        $tunai = @mysql_result($result, 0, 'diskon') * (-1);
        return $tunai;
    }
    
    public function getTotalRetur($startDate, $endDate) {
        //tunai
        $tunai = 0;
        $kondisi = "";
        $kondisi .= " AND tgl_retur BETWEEN '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
        
        $q_retur = "SELECT a.id_obat, jumlah, pros_retur, harga FROM rm_retur_penjualan_obat a, rm_penjualan_obat b WHERE b.id_faktur_penjualan=a.id_faktur_penjualan 
                    and a.del_flag<>'1' and b.del_flag<>'1' and jns_retur='1' AND b.id_penjualan_obat=a.id_penjualan_obat " . $kondisi;
        $r_retur = $this->runQuery($q_retur);
        $setoran_retur_tunai = 0;
        $diskon_retur_tunai = 0;
        while ($ret = @mysql_fetch_array($r_retur)) {
            $setoran_retur_tunai += $ret['jumlah'] * $ret['harga'];
            $diskon_retur_tunai += ( $ret['jumlah'] * $ret['harga']) * (1 - $ret['pros_retur']);
        }
        $tunai = ($setoran_retur_tunai - $diskon_retur_tunai) * (-1);
        return $tunai;
    }

}

?>
