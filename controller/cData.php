<?php

session_start();
require_once '../../common/function.php';
require("../js/codebase/connector/combo_connector.php");

class cData extends fungsi {

    //put your code here
    public function koneksine() {
        $res = mysql_connect('localhost', 'aplikasi', 'k3r4m1kp3c4h');
        mysql_select_db('production');
        return $res;
    }

    public function cariGolDarah() {
        $query = "SELECT * from rm_gol_darah where del_flag<>'1'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('id_gol_darah' => $rec['id_gol_darah'], 'gol_darah' => $rec['gol_darah']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '0';
        }
    }

    public function cariTipeAsuransi() {
        $query = "SELECT * from rm_tipe_asuransi where del_flag<>'1'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('id_tipe_asuransi' => $rec['id_tipe_asuransi'], 'tipe_asuransi' => $rec['tipe_asuransi']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '({"results":""})';
        }
    }

    public function cariTipePasien($id_tipe_asuransi) {
        $query = "SELECT * from rm_tipe_pasien where del_flag<>'1' and id_tipe_asuransi='" . $id_tipe_asuransi . "'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_tipe_pasien'], 'optionDisplay' => $rec['tipe_pasien']);
            }
        }

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function cariPendidikan() {
        $query = "SELECT * from rm_pendidikan where del_flag<>'1'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('id_pendidikan' => $rec['id_pendidikan'], 'pendidikan' => $rec['pendidikan']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '({"results":""})';
        }
    }

    public function cariKelurahan($id_kecamatan) {
        $query = "SELECT * from rm_kelurahan where del_flag<>'1' and id_kecamatan='" . $id_kecamatan . "'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kelurahan'], 'optionDisplay' => $rec['kelurahan']);
            }
        } else {
            $arr[] = array('optionValue' => '', 'optionDisplay' => '');
        }

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function cariKecamatan($id_kota) {
        $query = "SELECT * from rm_kecamatan where del_flag<>'1' and id_kota='" . $id_kota . "'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kecamatan'], 'optionDisplay' => $rec['kecamatan']);
            }
        } else {
            $arr[] = array('optionValue' => '', 'optionDisplay' => '');
        }


        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function cariKota() {
        $query = "SELECT * from rm_kota where del_flag<>'1'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('id_kota' => $rec['id_kota'], 'kota' => $rec['kota']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '0';
        }
    }

    public function cariGelar() {
        $query = "SELECT * from rm_title where del_flag<>'1'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('id_title' => $rec['id_title'], 'title' => $rec['title']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '0';
        }
    }

    public function cariAgama() {
        $query = "SELECT * from rm_agama where del_flag<>'1'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('agamaValue' => $rec['id_agama'], 'agamaName' => $rec['agama']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '({"results":""})';
        }
    }

    public function cariIdentitas() {
        $query = "SELECT * from rm_jenis_identitas where del_flag<>'1'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('jenisIdValue' => $rec['id_jenis_identitas'], 'jenisIdName' => $rec['jenis_identitas']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '0';
        }
    }

    public function getListTipeRuang() {
        $query = "SELECT id_tipe_ruang,tipe_ruang FROM rm_tipe_ruang WHERE del_flag<>'1' ORDER BY tipe_ruang";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('id_tipe_ruang' => $rec['id_tipe_ruang'], 'tipe_ruang' => $rec['tipe_ruang']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '0';
        }
    }

    public function getListPerujuk($asal_rujukan) {
        $query = "SELECT id_perujuk,nama_perujuk FROM rm_perujuk where id_asal_rujukan='" . $asal_rujukan . "' ORDER BY nama_perujuk";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_perujuk'], 'optionDisplay' => $rec['nama_perujuk']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getListKamar($id_ruang, $id_kelas) {
        $query = "SELECT b.id_kamar, a.kamar FROM rm_kamar a, rm_kelas_kamar b WHERE a.id_ruang='" . $id_ruang . "' AND b.id_kelas='" . $id_kelas . "' AND a.id_kamar=b.id_kamar AND a.del_flag<>'1' AND b.del_flag<>'1'";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kamar'], 'optionDisplay' => $rec['kamar']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListKamarAll($id_ruang) {
        $query = "SELECT id_kamar, kamar FROM rm_kamar WHERE id_ruang='" . $id_ruang . "' AND del_flag<>'1'";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kamar'], 'optionDisplay' => $rec['kamar']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListBed($id_kamar) {
        $query = "SELECT * FROM rm_detail_kamar where id_kamar='" . $id_kamar . "' and status='0' and del_flag<>'1'";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_detail_kamar'], 'optionDisplay' => $rec['bed']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListBedAll($id_kamar) {
        $query = "SELECT * FROM rm_detail_kamar where id_kamar='" . $id_kamar . "' and del_flag<>'1'";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_detail_kamar'], 'optionDisplay' => $rec['bed']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getTarifKamar($id_kamar) {
        $query = "SELECT * FROM rm_kamar where id_kamar='" . $id_kamar . "' AND del_flag<>'1'";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('dataValue' => $rec['tarif']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getListRuangByType($id_tipe) {
        $query = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_tipe_ruang='" . $id_tipe . "' AND del_flag<>'1' ORDER BY ruang";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_ruang'], 'optionDisplay' => $rec['ruang']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getListRuang($id_tipe_pendaftaran, $id_pasien) {
        $q = "SELECT id_tipe_ruang FROM rm_ruang_pendaftaran WHERE id_tipe_pendaftaran='" . $id_tipe_pendaftaran . "' AND del_flag<>'1'";

        $rs = $this->runQuery($q);

        while ($data = mysql_fetch_array($rs)) {
            $query = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_tipe_ruang='" . $data['id_tipe_ruang'] . "' AND del_flag<>'1' ORDER BY ruang";

            $result = $this->runQuery($query);

            if (mysql_num_rows($result) > 0) {
                while ($rec = mysql_fetch_array($result)) {
                    $arr[] = array('optionValue' => $rec['id_ruang'], 'optionDisplay' => $rec['ruang']);
                }
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getListRuangDaftar($id_tipe_pendaftaran, $id_pasien) {
        $q = "SELECT id_tipe_ruang FROM rm_ruang_pendaftaran WHERE id_tipe_pendaftaran='" . $id_tipe_pendaftaran . "' AND init_daftar=1 AND del_flag<>'1'";

        $rs = $this->runQuery($q);

        while ($data = mysql_fetch_array($rs)) {
            $query = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_tipe_ruang='" . $data['id_tipe_ruang'] . "' AND del_flag<>'1' ORDER BY ruang";

            $result = $this->runQuery($query);

            if (mysql_num_rows($result) > 0) {
                while ($rec = mysql_fetch_array($result)) {
                    $arr[] = array('optionValue' => $rec['id_ruang'], 'optionDisplay' => $rec['ruang']);
                }
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getListRuangDistribusi($kode_obat) {
        if (substr($kode_obat, 0, 1) == "8")
            $query = "SELECT id_ruang,ruang FROM rm_ruang WHERE del_flag<>'1' and id_ruang<>" . $_SESSION['level'] . " order by ruang";
        else
            $query = "select id_ruang, ruang from rm_ruang where del_flag<>'1' and id_ruang in ('36', '46', '47','50') and id_ruang<>" . $_SESSION['level'] . "";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_ruang'], 'optionDisplay' => $rec['ruang']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getListRuangDistribusiBarang() {
        $query = "SELECT id_ruang,ruang FROM rm_ruang WHERE del_flag<>'1' order by ruang";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_ruang'], 'optionDisplay' => $rec['ruang']);
            }
        }
        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getListKelasRuang($id_ruang, $id_pasien) {
        $id_tipe_pasien = $this->getTipePasienId($id_pasien);
        $id_tipe_ruang = $this->getTipeRuangId($id_ruang);
        if ($id_ruang == 20) {
            $query = "SELECT a.id_kelas, b.kelas FROM rm_kelas_ruang a, rm_kelas b WHERE b.id_kelas=a.id_kelas AND b.id_kelas > 8 AND a.id_ruang='" . $id_ruang . "' AND a.init_daftar=1 AND a.del_flag<>'1' order by b.kelas";
        } else if (($id_ruang == 51 || $id_ruang == 52 || $id_ruang == 53 || $id_ruang == 34) && ($id_tipe_pasien == 4 || $id_tipe_pasien == 5 || $id_tipe_pasien == 10 || $id_tipe_pasien == 11)) {
            $query = "SELECT a.id_kelas, b.kelas FROM rm_kelas_ruang a, rm_kelas b WHERE b.id_kelas=a.id_kelas AND b.id_kelas = 17 AND a.id_ruang='" . $id_ruang . "' AND a.init_daftar=1 AND a.del_flag<>'1' order by b.kelas";
        } else if (($id_ruang == 51 || $id_ruang == 52 || $id_ruang == 53 || $id_ruang == 34) && ($id_tipe_pasien == 1)) {
            $query = "SELECT a.id_kelas, b.kelas FROM rm_kelas_ruang a, rm_kelas b WHERE b.id_kelas=a.id_kelas AND b.id_kelas = 16 AND a.id_ruang='" . $id_ruang . "' AND a.init_daftar=1 AND a.del_flag<>'1' order by b.kelas";
        } else {
            if ($id_tipe_pasien == '2' || $id_tipe_pasien == '12' || $id_tipe_pasien == '3')
                $query = "SELECT a.id_kelas, b.kelas FROM rm_kelas_ruang a, rm_kelas b WHERE b.id_kelas=a.id_kelas AND (b.id_kelas='6' OR b.id_kelas='13' OR b.id_kelas='15') AND a.id_ruang='" . $id_ruang . "' AND a.init_daftar=1 AND a.del_flag<>'1' order by b.kelas";
            else
                $query = "SELECT a.id_kelas, b.kelas FROM rm_kelas_ruang a, rm_kelas b WHERE b.id_kelas=a.id_kelas AND b.id_kelas!='8' AND b.id_kelas!='6' AND b.id_kelas!='13' AND b.id_kelas!='15' AND a.id_ruang='" . $id_ruang . "' AND a.init_daftar=1 AND a.del_flag<>'1' order by b.kelas";
        }
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kelas'], 'optionDisplay' => $rec['kelas']);
            }
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListKelasJPS() {
        if ($_SESSION['level'] == 31) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '13' order by kelas";
        } else if ($_SESSION['level'] == 32) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '15' order by kelas";
        } else {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '6' order by kelas";
        }

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kelas'], 'optionDisplay' => $rec['kelas']);
            }
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListKelasHarga() {

        if ($_SESSION['level'] == 31) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = 13 order by kelas";
        } else if ($_SESSION['level'] == 32) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = 15 order by kelas";
        } else {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = 6 order by kelas";
        }

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kelas'], 'optionDisplay' => $rec['kelas']);
            }
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListTipePasien($tipe) {

        if ($tipe == 1) {
            $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag<>'1' and id_tipe_pasien = 1 order by tipe_pasien";
        } else if ($tipe == 2) {
            $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag<>'1' and id_tipe_pasien <> 1 order by tipe_pasien";
        } else {
            $query = "SELECT id_tipe_pasien, tipe_pasien FROM rm_tipe_pasien WHERE del_flag<>'1' and id_tipe_pasien NOT IN (2,3,12) order by tipe_pasien";
        }

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_tipe_pasien'], 'optionDisplay' => $rec['tipe_pasien']);
            }
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListKelasUmum() {

        if ($_SESSION['level'] == 31) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '9' order by kelas";
        } else if ($_SESSION['level'] == 32) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '14' order by kelas";
        } else if ($_SESSION['level'] == 30) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '1' order by kelas";
        } else if ($_SESSION['level'] == 29) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '4' order by kelas";
        } else if ($_SESSION['level'] == 28) {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas = '3' order by kelas";
        } else {
            $query = "SELECT id_kelas, kelas FROM rm_kelas WHERE del_flag<>'1' and id_kelas < '5' and id_kelas not in(1,4) order by kelas";
        }

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_kelas'], 'optionDisplay' => $rec['kelas']);
            }
            return $this->jEncode($arr);
        } else {
            $arr[] = array('optionValue' => "", 'optionDisplay' => "");
            return $this->jEncode($arr);
        }
    }

    public function getListDokterRuang($id_ruang) {
        if ($id_ruang == "24" ||
                $id_ruang == "25" ||
                $id_ruang == "26" ||
                $id_ruang == "27" ||
                $id_ruang == "28" ||
                $id_ruang == "29" ||
                $id_ruang == "30" ||
                $id_ruang == "31" ||
                $id_ruang == "32" ||
                $id_ruang == "33" ||
                $id_ruang == "44" ||
                $id_ruang == "34" ||
                $id_ruang == "20"
        ) {
            $query = "select id_dokter, lcase(nama_dokter) as nama_dokter from rm_dokter where del_flag<>1";
        } else if ($id_ruang == "17" || $id_ruang == "18") {
            $query = "SELECT a.id_dokter, lcase(b.nama_dokter) as nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE a.id_ruang='" . $id_ruang . "' AND b.id_dokter=a.id_dokter order by b.nama_dokter";
        } else {
            $query = "SELECT a.id_dokter, lcase(nama_dokter) as nama_dokter FROM rm_dokter_ruang a, rm_dokter b WHERE (a.id_ruang='" . $id_ruang . "' OR a.id_ruang='0') AND a.id_dokter=b.id_dokter ORDER BY nama_dokter";
        }

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('optionValue' => $rec['id_dokter'], 'optionDisplay' => $rec['nama_dokter']);
            }
            return $this->jEncode($arr);
        }
    }

    public function getListTipePendaftaran() {
        $query = "SELECT id_tipe_pendaftaran,tipe_pendaftaran FROM rm_tipe_pendaftaran ORDER BY tipe_pendaftaran";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array('id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'], 'tipe_pendaftaran' => $rec['tipe_pendaftaran']);
            }
            return '({"results":' . $this->jEncode($arr) . '})';
        } else {
            return '({"results":""})';
        }
    }

    public function getListLaboratorium($params) {
        $query = "SELECT id_laboratorium, laboratorium FROM rm_laboratorium where id_kelompok_lab='" . $params . "' AND kelompok = 0 ORDER BY laboratorium";

        $result = $this->runQuery($query);

        while ($rec = mysql_fetch_array($result)) {
            $arr[] = array('optionValue' => $rec['id_laboratorium'], 'optionDisplay' => $rec['laboratorium']);
        }
        return $this->jEncode($arr);
    }

    public function getBiayaVisit($id_dokter, $id_pendaftaran) {
        $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);
        $id_jenis_dokter = $this->getJenisDokter($id_dokter);
        $ruangan = $this->getKamarDaftar($id_pendaftaran);
//        if ($ruangan == '80')
//            $id_kelas = 9;
        if ($ruangan == '81')
            $id_kelas = 10;
        if ($ruangan == '85')
            $id_kelas = 11;

        $query = "select tarif from rm_tarif_visit where id_kelas='" . $id_kelas . "' and id_jenis_dokter='" . $id_jenis_dokter . "'";
        $result = $this->runQuery($query);

        $biaya[] = array('dataValue' => @mysql_result($result, 0, 'tarif'));

        return $this->jEncode($biaya);
    }

    public function checkOutPasien($id_pasien, $id_keadaan, $id_cara_keluar, $keterangan, $tgl_keluar) {

        $setJmlObat = "SELECT id_faktur_penjualan as idf FROM rm_faktur_penjualan WHERE id_pasien='" . $id_pasien . "' AND status='1'";
        $hasilSet = $this->runQuery($setJmlObat);

        if (@mysql_num_rows($hasilSet) > 0) {
            $l_obat = "UPDATE rm_faktur_penjualan SET status='2', auto='1' WHERE id_pasien='" . $id_pasien . "' AND status='1'";
            $r_obat = $this->runQuery($l_obat);
            while ($rec = mysql_fetch_array($hasilSet)) {
                $this->setJumlahObat($rec['idf']);
            }
        }

        $q_daftar = "SELECT id_pendaftaran, status_pendaftaran, id_ruang, id_tipe_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $id_pasien . "' 
                     AND status_pembayaran!='2' and del_flag<>'1'";
        $r_daftar = $this->runQuery($q_daftar);
        $html = "";
        $success = 0;
        $failed = 0;
        if (@mysql_num_rows($r_daftar) > 0) {
            while ($data = @mysql_fetch_array($r_daftar)) {
                // INI DIGUNAKAN UNTUK CLOSE RUANGAN APABILA KASIR MENCEKOUT
//                if ($data['id_ruang'] == '17') {
//                    if ($data['status_pendaftaran'] != '2') {
//                        $this->saveClosePerawatan($data['id_pendaftaran'], $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan, $tgl_keluar);
//                        $this->generateJasaLaboratorium($data['id_pendaftaran']);
//                    }
//                } else if ($data['id_ruang'] == '18') {
//                    if ($data['status_pendaftaran'] != '2') {
//                        $this->saveClosePerawatan($data['id_pendaftaran'], $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan, $tgl_keluar);
//                        $this->generateJasaRadiologi($data['id_pendaftaran']);
//                    }
//                } else if ($data['id_ruang'] == '22') {
//                    if ($data['status_pendaftaran'] != '2') {
//                        $this->saveClosePerawatanMedis($data['id_pendaftaran'], $id_keadaan, $id_cara_keluar, $keterangan, $tgl_keluar);
//                    }
//                } else {
//                    if ($data['status_pendaftaran'] != '2') {
//                        $this->saveClosePerawatan($data['id_pendaftaran'], $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan, $tgl_keluar);
//                    }
//                }
//                $query = "update rm_pendaftaran set status_pembayaran='2', status_pendaftaran='2' where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                if ($this->getIdTipePendaftaran($data['id_pendaftaran']) == '6') {
                    if ($data['status_pendaftaran'] != '2')
                        $this->saveClosePerawatan($data['id_pendaftaran'], $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan, $tgl_keluar);
                    $query = "update rm_pendaftaran set status_pembayaran='2', status_pendaftaran='2' where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                } else {
                    $query = "update rm_pendaftaran set status_pembayaran='2' where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                }
                if ($this->runQuery($query)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
        }
        if ($failed == 0 and $success > 0)
            return '1';
        else
            return '0';
    }

    public function getPendaftaran($id_pasien) {
        $query = "select max(id_pendaftaran) as id_pendaftaran from rm_pendaftaran where id_pasien='" . $id_pasien . "' and id_ruang='" . $_SESSION['level'] . "' and status_pendaftaran!='2' and status_pembayaran!='2' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0)
            return @mysql_result($result, 0, 'id_pendaftaran');
        else
            return '0';
    }

    public function getLaporanTagihanRawatInap(
    $tipe_pasien, $status, $startDate, $endDate
    ) {
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>LAPORAN PENDAPATAN RUMAH SAKIT RAWAT INAP</b></u><br>
                                Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate)) . "
                                <br></td>
                            </tr>";
        $html .="</table>";
        $html .="Jenis Pasien : " . $this->getTipePasien($tipe_pasien);
        $kondisi = "";
        if ($status != "A") {
            if ($status == "L")
                $kondisi .= " and a.status_pembayaran='2'";
            else if ($status == "B")
                $kondisi .= " and a.status_pembayaran!='2'";
        }
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(c.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(c.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
        }

        $q_kamar = "SELECT e.id_detail_kamar, f.kamar
                    FROM rm_pendaftaran a, rm_pasien b, rm_pasien_keluar c, rm_ruang d, rm_penggunaan_kamar e, rm_kamar f, rm_detail_kamar g
                    WHERE a.id_tipe_pendaftaran='6' AND e.id_pendaftaran=a.id_pendaftaran AND c.id_pendaftaran=a.id_pendaftaran AND b.id_pasien=a.id_pasien
                    " . $kondisi . " AND e.id_tipe_pasien='" . $tipe_pasien . "' AND f.id_kamar=g.id_kamar AND g.id_detail_kamar=e.id_detail_kamar 
                    AND d.id_ruang=a.id_ruang and e.status!='2' and e.del_flag <> '1' and a.del_flag<>'1'
                    GROUP BY e.id_detail_kamar
                    ORDER BY e.id_detail_kamar";
        $r_kamar = $this->runQuery($q_kamar);
        if (@mysql_num_rows($r_kamar) > 0) {
            $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='2%' class='headerTagihan'>No</td>";
            $html .= "<td width='10%' class='headerTagihan'>No RM</td>";
            $html .= "<td width='10%' class='headerTagihan'>Nama PX</td>";
            $html .= "<td width='10%' class='headerTagihan'>Sewa Ruang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Fasilitas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Laborat</td>";
            $html .= "<td width='10%' class='headerTagihan'>Radiologi</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tindakan</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tindakan IBS</td>";
            $html .= "<td width='10%' class='headerTagihan'>Visit</td>";
            $html .= "<td width='10%' class='headerTagihan'>Biaya Obat</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Terbayar</td>";
            $html .= "<td width='10%' class='headerTagihan'>Terhutang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
            $html .= "<td width='10%' class='headerTagihan'>Asuransi</td>";
            $html .= "<td width='10%' class='headerTagihan'>PULANG</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $Sewa = 0;
            $Fasilitas = 0;
            $Laborat = 0;
            $Radiologi = 0;
            $TindakanPoli = 0;
            $TindakanBedah = 0;
            $Visit = 0;
            $Obat = 0;
            $Biaya = 0;
            $Bayar = 0;
            $totAllDiskon = 0;
            $totAllAsuransi = 0;
            while ($kamar = @mysql_fetch_array($r_kamar)) {
                $query = "SELECT date(c.tgl_keluar) `tgl_keluar`, a.id_pendaftaran, a.id_pasien, b.nama_pasien, d.ruang, date(c.tgl_keluar) as keluar, date(a.tgl_pendaftaran) as masuk FROM rm_pendaftaran a, rm_pasien b, rm_pasien_keluar c, rm_ruang d, rm_penggunaan_kamar e
                          WHERE b.id_pasien=a.id_pasien AND a.id_tipe_pendaftaran='6' AND c.id_pendaftaran=a.id_pendaftaran AND c.id_pasien=a.id_pasien and e.id_pendaftaran=a.id_pendaftaran
                          and e.id_detail_kamar='" . $kamar['id_detail_kamar'] . "' AND a.del_flag<>'1' AND c.del_flag<>'1' AND e.del_flag<>'1' AND e.status<>2 AND d.id_ruang=a.id_ruang AND e.id_tipe_pasien='" . $tipe_pasien . "' " . $kondisi;
                $result = $this->runQuery($query);
                if (@mysql_num_rows($result) > 0) {
                    $html .= "<tr>";
                    $html .= "<td class='total' colspan='16'>" . $kamar['kamar'] . "</td>";
                    $html .= "</tr>";
                    $i = 1;
                    $totSewa = 0;
                    $totFasilitas = 0;
                    $totLaborat = 0;
                    $totRadiologi = 0;
                    $totTindakanPoli = 0;
                    $totTindakanBedah = 0;
                    $totVisit = 0;
                    $totObat = 0;
                    $totBiaya = 0;
                    $totBayar = 0;
                    $totAsuransi = 0;
                    $totDiskon = 0;
                    $obatPasien = array();
                    while ($data = @mysql_fetch_array($result)) {
                        $q_obat = "SELECT id_faktur_penjualan FROM rm_faktur_penjualan
                                   WHERE id_pasien='" . $data['id_pasien'] . "' AND date(tgl_penjualan) between '" . $data['masuk'] . "' and '" . $data['keluar'] . "' and del_flag<>'1'";
                        $r_obat = $this->runQuery($q_obat);
                        $biayaObat = 0;
                        $bayarObat = 0;
                        $diskonO = 0;
                        $asuransiO = 0;
                        if (@mysql_num_rows($r_obat) > 0) {
                            if (@($obatPasien[0]["id_pasien"] != $data['id_pasien'])) {
                                while ($blObat = @mysql_fetch_array($r_obat)) {
                                    $jumlahRetur = $this->getJumlahReturTagihanObat($blObat['id_faktur_penjualan']);
                                    $biayaObat += ( $this->getJumlahTagihanObat($blObat['id_faktur_penjualan']) - $jumlahRetur);
                                    $o_pembayaran = "select sum(bayar) as pembayaran, sum(diskon) as diskon, sum(asuransi) as asuransi, id_faktur_penjualan from rm_pembayaran_obat where id_faktur_penjualan='" . $blObat['id_faktur_penjualan'] . "' AND del_flag<>'1'";
                                    $h_pembayaran = $this->runQuery($o_pembayaran);
                                    if (@mysql_result($h_pembayaran, 0, 'pembayaran') == 0 && $jumlahRetur > 0) {
                                        $bayarObat = 0;
                                    } else {
                                        $bayarObat += @ mysql_result($h_pembayaran, 0, 'pembayaran') - $jumlahRetur;
                                    }
                                    if (@mysql_result($h_pembayaran, 0, 'diskon') != 0) {
                                        $diskonO += @ mysql_result($h_pembayaran, 0, 'diskon') - $jumlahRetur;
                                    } else {
                                        $diskonO += @ mysql_result($h_pembayaran, 0, 'diskon');
                                    }
                                    if (@mysql_result($h_pembayaran, 0, 'asuransi') != 0) {
                                        $asuransiO += @ mysql_result($h_pembayaran, 0, 'asuransi') - $jumlahRetur;
                                    } else {
                                        $asuransiO += @ mysql_result($h_pembayaran, 0, 'asuransi');
                                    }
                                }
                            }
                            $obatPasien[] = array("id_pasien" => $data['id_pasien']);
                        }
                        $html .= "<tr>";
                        $html .= "<td>" . $i . "</td>";
                        $html .= "<td>" . $data['id_pasien'] . "</td>";
                        $html .= "<td>" . $data['nama_pasien'] . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaSewa = $this->getBiayaSewaRuang($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaFasilitas = $this->getBiayaFasilitas($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayalaborat = $this->getBiayaLaborat($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaRadiologi = $this->getBiayaRadiologi($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanPoli = $this->getBiayaTindakanPoli($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanBedah = $this->getBiayaTindakanBedah($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaAllVisit = $this->getBiayaAllVisit($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format($biayaObat, 2, ',', '.') . "</td>";
                        $jml = ($biayaSewa + $biayaFasilitas + $biayalaborat + $biayaRadiologi + $biayaTindakanPoli + $biayaTindakanBedah + $biayaAllVisit + $biayaObat);
                        $html .= "<td align='right'>" . number_format($jml, 2, ',', '.') . "</td>";
                        $q_pembayaran = "select sum(bayar) as pembayaran from rm_pembayaran_tagihan where id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>1";
                        $r_pembayaran = $this->runQuery($q_pembayaran);
                        $bayarTagihan = @mysql_result($r_pembayaran, 0, 'pembayaran');
                        $diskonA = $this->getAllDiskonPasien($data['id_pasien']);
                        $q_asuransi = "select asuransi from rm_tagihan_asuransi where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                        $r_asuransi = $this->runQuery($q_asuransi);
                        $asuransiA = @mysql_result($r_asuransi, 0, 'asuransi');
                        $asuransiT = $asuransiO + $asuransiA;
                        $diskonT = $diskonO + $diskonA;
                        $html .= "<td align='right'>" . number_format($bayar = ($bayarTagihan + $bayarObat), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($jml - $bayar - $asuransiT - $diskonT), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($diskonT), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($asuransiT), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . $this->formatDateDb($data['tgl_keluar']) . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $totSewa += $biayaSewa;
                        $totFasilitas += $biayaFasilitas;
                        $totLaborat += $biayalaborat;
                        $totRadiologi += $biayaRadiologi;
                        $totTindakanPoli += $biayaTindakanPoli;
                        $totTindakanBedah += $biayaTindakanBedah;
                        $totVisit += $biayaAllVisit;
                        $totObat += $biayaObat;
                        $totBiaya += $jml;
                        $totBayar += $bayar;
                        $totAsuransi += $asuransiT;
                        $totDiskon += $diskonT;
                    }
                    $html .= "<tr>";
                    $html .= "<td colspan='3' class='total'>Sub Total</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totSewa, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totFasilitas, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totLaborat, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totRadiologi, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totTindakanPoli, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totTindakanBedah, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totVisit, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totObat, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totBiaya, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totBayar, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format(($totBiaya - $totBayar - $totAsuransi - $totDiskon), 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totDiskon, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($totAsuransi, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $Sewa += $totSewa;
                    $Fasilitas += $totFasilitas;
                    $Laborat += $totLaborat;
                    $Radiologi += $totRadiologi;
                    $TindakanPoli += $totTindakanPoli;
                    $TindakanBedah += $totTindakanBedah;
                    $Visit += $totVisit;
                    $Obat += $totObat;
                    $Biaya += $totBiaya;
                    $Bayar += $totBayar;
                    $totAllDiskon += $totDiskon;
                    $totAllAsuransi += $totAsuransi;
                }
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Grand Total</td>";
            $html .= "<td align='right' class='total'>" . number_format($Sewa, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($Fasilitas, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($Laborat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($Radiologi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($TindakanPoli, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($TindakanBedah, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($Visit, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($Obat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($Biaya, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($Bayar, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format(($Biaya - $Bayar - $totAllAsuransi - $totAllDiskon), 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllDiskon, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllAsuransi, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</html>";
        } else {
            $html = "Data Tidak ditemukan.";
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getLaporanTagihanRawatJalan(
    $tipe_pasien, $status, $rawat, $startDate, $endDate
    ) {
        $kondisi = "";
        if ($rawat != "0") {
            if ($rawat == "1")
                $kondisi .= " and a.id_tipe_pendaftaran <> 7";
            else if ($rawat == "2")
                $kondisi .= " and a.id_tipe_pendaftaran = 7";
        }
        if ($status != "A") {
            if ($status == "L")
                $kondisi .= " and a.status_pembayaran='2'";
            else if ($status == "B")
                $kondisi .= " and a.status_pembayaran!='2'";
        }
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and a.tgl_pendaftaran BETWEEN '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
            else
                $kondisi .= " and a.tgl_pendaftaran BETWEEN '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($startDate) . " 23:59:59'";
        }

        $q_ruang = "SELECT DISTINCT(a.id_ruang) AS id_ruang, d.ruang FROM rm_pendaftaran a, rm_pasien b, rm_ruang d
                    WHERE a.id_tipe_pendaftaran!='6' AND a.del_flag<>1 AND a.id_asal_pendaftaran='0' AND b.id_pasien=a.id_pasien AND d.id_ruang=a.id_ruang
                    AND b.id_tipe_pasien='" . $tipe_pasien . "' " . $kondisi . " ORDER BY a.id_ruang";
        $r_ruang = $this->runQuery($q_ruang);
        if (@mysql_num_rows($r_ruang) > 0) {
            $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>LAPORAN PENDAPATAN RUMAH SAKIT RAWAT JALAN</b></u><br>
                                Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate)) . "
                                <br></td>
                            </tr>";
            $html .="</table>";
            $html .="<br><b>Jenis Pasien : " . $this->getTipePasien($tipe_pasien) . "</b>";
            $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='2%' class='headerTagihan'>No</td>";
            $html .= "<td width='10%' class='headerTagihan'>No RM</td>";
            $html .= "<td width='10%' class='headerTagihan'>Nama PX</td>";
            $html .= "<td width='10%' class='headerTagihan'>Karcis</td>";
            $html .= "<td width='10%' class='headerTagihan'>Fasilitas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Laborat</td>";
            $html .= "<td width='10%' class='headerTagihan'>Radiologi</td>";
            $html .= "<td width='10%' class='headerTagihan'>PA</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tindakan Poli</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tindakan IBS</td>";
            $html .= "<td width='10%' class='headerTagihan'>Biaya Obat</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Terbayar</td>";
            $html .= "<td width='10%' class='headerTagihan'>Terhutang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
            $html .= "<td width='10%' class='headerTagihan'>Asuransi</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $totAllKarcis = 0;
            $totAllSewa = 0;
            $totAllFasilitas = 0;
            $totAllLaborat = 0;
            $totAllRadiologi = 0;
            $totAllTindakanPoli = 0;
            $totAllTindakanPA = 0;
            $totAllTindakanBedah = 0;
            $totAllVisit = 0;
            $totAllObat = 0;
            $totAllBiaya = 0;
            $totAllBayar = 0;
            $totAllAsuransi = 0;
            $totAllDiskon = 0;
            $obatPasien = array();
            while ($ruang = @mysql_fetch_array($r_ruang)) {
                $html .= "<tr>";
                $html .= "<td colspan='16' class='total'><b> Ruang " . $ruang['ruang'] . "</b></td>";
                $html .= "</tr>";
                $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, d.ruang, date(a.tgl_pendaftaran) as masuk FROM rm_pendaftaran a, rm_pasien b, rm_ruang d
                          WHERE b.id_pasien=a.id_pasien and a.id_ruang='" . $ruang['id_ruang'] . "' AND a.id_tipe_pendaftaran!='6' AND a.del_flag<>1 and a.id_asal_pendaftaran='0'
                          AND d.id_ruang=a.id_ruang AND b.id_tipe_pasien='" . $tipe_pasien . "' " . $kondisi . " order by b.nama_pasien, d.ruang";
                $result = $this->runQuery($query);
                if (@mysql_num_rows($result) > 0) {
                    $i = 1;
                    $totKarcis = 0;
                    $totSewa = 0;
                    $totFasilitas = 0;
                    $totLaborat = 0;
                    $totRadiologi = 0;
                    $totTindakanPoli = 0;
                    $totTindakanPA = 0;
                    $totTindakanBedah = 0;
                    $totVisit = 0;
                    $totObat = 0;
                    $totBiaya = 0;
                    $totBayar = 0;
                    $totDiskon = 0;
                    $totAsuransi = 0;
                    $statusObat = 0;
                    while ($data = @mysql_fetch_array($result)) {
                        $q_obat = "SELECT id_faktur_penjualan FROM rm_faktur_penjualan
                                   WHERE id_pendaftaran='" . $data['id_pendaftaran'] . "' AND tgl_penjualan BETWEEN '" . $data['masuk'] . "' AND '" . $data['masuk'] . " 23:59:59' AND del_flag<>'1'";
                        $r_obat = $this->runQuery($q_obat);
                        $biayaObat = 0;
                        $bayarObat = 0;
                        $diskonO = 0;
                        $asuransiO = 0;
                        if (@mysql_num_rows($r_obat) > 0) {
                            if (@($obatPasien[0]["id_pendaftaran"] != $data['id_pendaftaran'])) {
                                while ($blObat = @mysql_fetch_array($r_obat)) {
                                    $jumlahRetur = $this->getJumlahReturTagihanObat($blObat['id_faktur_penjualan']);
                                    $biayaObat += ( $this->getJumlahTagihanObat($blObat['id_faktur_penjualan']) - $this->getJumlahReturTagihanObat($blObat['id_faktur_penjualan']));
                                    $o_pembayaran = "select sum(bayar) as pembayaran, sum(diskon) as diskon, sum(asuransi) as asuransi, id_faktur_penjualan from rm_pembayaran_obat where id_faktur_penjualan='" . $blObat['id_faktur_penjualan'] . "' AND del_flag<>'1'";
                                    $h_pembayaran = $this->runQuery($o_pembayaran);
                                    if (@mysql_result($h_pembayaran, 0, 'pembayaran') == 0 && $jumlahRetur > 0) {
                                        $bayarObat = 0;
                                    } else {
                                        $bayarObat += @ mysql_result($h_pembayaran, 0, 'pembayaran') - $jumlahRetur;
                                    }
                                    if (@mysql_result($h_pembayaran, 0, 'diskon') != 0) {
                                        $diskonO += @ mysql_result($h_pembayaran, 0, 'diskon') - $jumlahRetur;
                                    } else {
                                        $diskonO += @ mysql_result($h_pembayaran, 0, 'diskon');
                                    }
                                    if (@mysql_result($h_pembayaran, 0, 'asuransi') != 0) {
                                        $asuransiO += @ mysql_result($h_pembayaran, 0, 'asuransi') - $jumlahRetur;
                                    } else {
                                        $asuransiO += @ mysql_result($h_pembayaran, 0, 'asuransi');
                                    }
                                }
                            }
                            $obatPasien[] = array("id_pendaftaran" => $data['id_pendaftaran']);
                        }
                        $html .= "<tr>";
                        $html .= "<td>" . $i . "</td>";
                        $html .= "<td>" . $data['id_pasien'] . "</td>";
                        $html .= "<td>" . $data['nama_pasien'] . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaKarcis = $this->getBiayaKarcis($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaFasilitas = $this->getBiayaFasilitas($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayalaborat = $this->getBiayaLaborat($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaRadiologi = $this->getBiayaRadiologi($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanPA = $this->getBiayaTindakanPA($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanPoli = $this->getBiayaTindakanPoli($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanBedah = $this->getBiayaTindakanBedah($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format($biayaObat, 2, ',', '.') . "</td>";
                        $jml = ($biayaKarcis + $biayaFasilitas + $biayalaborat + $biayaRadiologi + $biayaTindakanPA + $biayaTindakanPoli + $biayaTindakanBedah + $biayaObat);
                        $html .= "<td align='right'>" . number_format($jml, 2, ',', '.') . "</td>";
                        $q_pembayaran = "select sum(bayar) as pembayaran from rm_pembayaran_tagihan where id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>1";
                        $r_pembayaran = $this->runQuery($q_pembayaran);
                        $bayarTagihan = @mysql_result($r_pembayaran, 0, 'pembayaran');
                        $diskonA = $this->getAllDiskonPasien($data['id_pasien']);
                        $q_asuransi = "select asuransi from rm_tagihan_asuransi where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                        $r_asuransi = $this->runQuery($q_asuransi);
                        $asuransiA = @mysql_result($r_asuransi, 0, 'asuransi');
                        $asuransiT = $asuransiO + $asuransiA;
                        $diskonT = $diskonO + $diskonA;
                        if ($ruang['id_ruang'] != 20 && $ruang['id_ruang'] != 34 && $ruang['id_ruang'] != 51 && $ruang['id_ruang'] != 52 && $ruang['id_ruang'] != 53)
                            $bayarTagihan += $biayaKarcis;
                        $html .= "<td align='right'>" . number_format(($bayar = ($bayarTagihan + $bayarObat)), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($jml - $bayar - $asuransiT - $diskonT), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($diskonT), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($asuransiT), 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $totKarcis += $biayaKarcis;
                        $totFasilitas += $biayaFasilitas;
                        $totLaborat += $biayalaborat;
                        $totRadiologi += $biayaRadiologi;
                        $totTindakanPA += $biayaTindakanPA;
                        $totTindakanPoli += $biayaTindakanPoli;
                        $totTindakanBedah += $biayaTindakanBedah;
                        $totObat += $biayaObat;
                        $totBiaya += $jml;
                        $totBayar += $bayar;
                        $totDiskon += $diskonT;
                        $totAsuransi += $asuransiT;
                    }
                }
                $html .= "<tr>";
                $html .= "<td colspan='3' class='total'>Sub Total</td>";
                $html .= "<td align='right' class='total'>" . number_format($totKarcis, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totFasilitas, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totLaborat, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totRadiologi, 2, ',', '.') . "</td>";

                $html .= "<td align='right' class='total'>" . number_format($totTindakanPA, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totTindakanPoli, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totTindakanBedah, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totObat, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totBiaya, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totBayar, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format(($totBiaya - $totBayar - $totAsuransi - $totDiskon), 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totDiskon, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totAsuransi, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $totAllKarcis += $totKarcis;
                $totAllFasilitas += $totFasilitas;
                $totAllLaborat += $totLaborat;
                $totAllRadiologi += $totRadiologi;
                $totAllTindakanPA += $totTindakanPA;
                $totAllTindakanPoli += $totTindakanPoli;
                $totAllTindakanBedah += $totTindakanBedah;
                $totAllObat += $totObat;
                $totAllBiaya += $totBiaya;
                $totAllBayar += $totBayar;
                $totAllDiskon += $totDiskon;
                $totAllAsuransi += $totAsuransi;
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Grand Total</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllKarcis, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllFasilitas, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllLaborat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllRadiologi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllTindakanPA, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllTindakanPoli, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllTindakanBedah, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllObat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllBiaya, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllBayar, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format(($totAllBiaya - $totAllBayar - $totAllAsuransi - $totAllDiskon), 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllDiskon, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllAsuransi, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</html>";
        } else {
            $html = "Data Tidak ditemukan.";
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getRekapPendapatan(
    $startDate, $endDate
    ) {

        $total = 0;

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
            else
                $kondisi .= "='" . $this->formatDateDb($startDate) . "'";
        }
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>REKAP PENDAPATAN RUMAH SAKIT</b></u><br>
                            Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate)) . "
                            <br></td>
                        </tr>";
        $html .="</table>";
        $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<td width='2%' class='headerTagihan'>No</td>";
        $html .= "<td width='10%' class='headerTagihan'>Uraian Rincian Obyek</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        $html .= "<tr>";
        $html .= "<td>1</td>";
        $html .= "<td>Instalasi Gawat Darurat</td>";
        $igd = $this->getPendapatanIGD($kondisi);
        $total += $igd;
        $html .= "<td align='right'>" . number_format($igd, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>2</td>";
        $html .= "<td>Instalasi Bedah Sentral</td>";
        $ibs = $this->getPendapatanBedah($kondisi);
        $total += $ibs;
        $html .= "<td align='right'>" . number_format($ibs, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>3</td>";
        $html .= "<td>Instalasi Rawat Jalan</td>";
        $irj = $this->getPendapatanRawatJalan($kondisi);
        $total += $irj;
        $html .= "<td align='right'>" . number_format($irj, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $q_ruang = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_tipe_ruang IN ('2','9')";
        $r_ruang = $this->runQuery($q_ruang);
        while ($rec = @mysql_fetch_array($r_ruang)) {
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<td>" . $rec['ruang'] . "</td>";
            $html .= "<td align='right'>" . number_format($this->getPendapatanRuangRawatJalan($kondisi, $rec['id_ruang']), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        $html .= "<tr>";
        $html .= "<td>4</td>";
        $html .= "<td>Instalasi Rawat Inap</td>";
        $iri = $this->getPendapatanRawatInap($kondisi);
        $total += $iri;
        $html .= "<td align='right'>" . number_format($iri, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $q_ruang = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_tipe_ruang='8'";
        $r_ruang = $this->runQuery($q_ruang);
        while ($rec = @mysql_fetch_array($r_ruang)) {
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<td>" . $rec['ruang'] . "</td>";
            $html .= "<td align='right'>" . number_format($this->getPendapatanRuangRawatInap($kondisi, $rec['id_ruang']), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        $html .= "<tr>";
        $html .= "<td>5</td>";
        $html .= "<td>Instalasi Farmasi</td>";
        $farmasi = $this->getPendapatanFarmasi($startDate, $endDate);
        $total += $farmasi;
        $html .= "<td align='right'>" . number_format($farmasi, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td></td>";
        $html .= "<td>Diskon Obat</td>";
        $diskon = $this->getFarmasiDiskon($startDate, $endDate);
        $total += $diskon;
        $html .= "<td align='right'>" . number_format($diskon, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td></td>";
        $html .= "<td>Retur Obat</td>";
        $retur = $this->getTotalRetur($startDate, $endDate);
        $total += $retur;
        $html .= "<td align='right'>" . number_format($retur, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>6</td>";
        $html .= "<td>Instalasi Laboratorium</td>";
        $lab = $this->getPendapatanLaboratorium($kondisi);
        $total += $lab;
        $html .= "<td align='right'>" . number_format($lab, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>7</td>";
        $html .= "<td>Instalasi Radiologi</td>";
        $radio = $this->getPendapatanRadiologi($kondisi);
        $total += $radio;
        $html .= "<td align='right'>" . number_format($radio, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td class='total' colspan='2'>Total</td>";
        $html .= "<td class='total' align='right'>" . number_format($total, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "</tbody>";
        $html .= "</table>";

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getRekapKeuangan(
    $startDate, $endDate, $id_tipe_pasien, $id_ruang, $id_dokter, $id_kelas, $tipe_perawatan, $tipe_laporan
    ) {
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>LAPORAN REKAPITULASI PENDAPATAN</b></u><br>";
        if ($endDate == "")
            $html .="Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . "
                                <br></td>
                            </tr>";
        else
            $html .="Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate)) . "
                                <br></td>
                            </tr>";
        $html .="</table>";
        $kondisi = "";
        $kondKelas = "";
        $kondRuang = "";
        if ($tipe_laporan == '1') {
            if ($startDate != "") {
                if ($endDate != "")
                    $kondisi .= " and date(a.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                else
                    $kondisi .= " and date(a.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
            }
            if ($id_ruang != "")
                $kondisi .= " and a.id_ruang='" . $id_ruang . "'";
            if ($id_tipe_pasien != "")
                $kondisi .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";
            if ($id_kelas != "") {
                $kondisi .= " and a.id_kelas='" . $id_kelas . "'";
                $kondKelas .= " and a.id_kelas='" . $id_kelas . "'";
            }

            $query = "SELECT DISTINCT(a.id_ruang), a.id_kelas, c.ruang FROM rm_penggunaan_kamar a, rm_pasien b, rm_ruang c 
                      WHERE b.id_pasien=a.id_pasien AND a.lama_penggunaan<>'0' AND a.status<>'1' AND c.id_ruang=a.id_ruang and a.del_flag<>'1' " . $kondisi . " group by id_ruang";

            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $jmlTotHari = 0;
                $jmlTotTarif = 0;
                $jmlTotCatering = 0;
                $jmlTotAkomodasi = 0;
                $jmlTotJasa = 0;
                $html .= "<br><b>HARI RAWAT</b>";
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
                $html .= "<td width='8%' class='headerTagihan'>Nomor RM</td>";
                $html .= "<td width='32%' class='headerTagihan'>Nama Pasien</td>";
                $html .= "<td width='5%' class='headerTagihan'>Lama</td>";
                $html .= "<td width='15%' class='headerTagihan'>Netto</td>";
                $html .= "<td width='10%' class='headerTagihan'>Catering</td>";
                $html .= "<td width='10%' class='headerTagihan'>Kamar</td>";
                $html .= "<td width='10%' class='headerTagihan'>Askep</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";

                while ($data = @mysql_fetch_array($result)) {
                    $jmlHari = 0;
                    $jmlTarif = 0;
                    $jmlCatering = 0;
                    $jmlAkomodasi = 0;
                    $jmlJasa = 0;
                    $i = 1;
                    $q_detail = "SELECT a.id_pasien, b.nama_pasien, a.lama_penggunaan, d.akomodasi, d.catering, d.jasa_perawat, d.tarif 
                                 FROM rm_penggunaan_kamar a, rm_pasien b, rm_detail_kamar c, rm_tarif_kamar d
                                 WHERE b.id_pasien=a.id_pasien AND a.status<>'1' AND a.del_flag<>'1' AND c.id_detail_kamar=a.id_detail_kamar
                                 AND d.id_kamar=c.id_kamar AND d.id_kelas=a.id_kelas and a.id_ruang='" . $data['id_ruang'] . "'" . $kondKelas;
                    $r_detail = $this->runQuery($q_detail);
                    if (@mysql_num_rows($r_detail) > 0) {
                        while ($rec = @mysql_fetch_array($r_detail)) {
                            $html .= "<tr>";
                            if ($i == '1')
                                $html .= "<td >" . $data['ruang'] . "</td>";
                            else
                                $html .= "<td >&nbsp;</td>";
                            $html .= "<td >" . $rec['id_pasien'] . "</td>";
                            $html .= "<td >" . $rec['nama_pasien'] . "</td>";
                            $html .= "<td align='right'>" . number_format($rec['lama_penggunaan'], 0, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format(($rec['tarif'] * $rec['lama_penggunaan']), 0, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format(($rec['catering'] * $rec['lama_penggunaan']), 0, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format(($rec['akomodasi'] * $rec['lama_penggunaan']), 0, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format(($rec['jasa_perawat'] * $rec['lama_penggunaan']), 0, ',', '.') . "</td>";
                            $html .= "</tr>";
                            $jmlHari += $rec['lama_penggunaan'];
                            $jmlTarif += ( $rec['tarif'] * $rec['lama_penggunaan']);
                            $jmlCatering += ( $rec['catering'] * $rec['lama_penggunaan']);
                            $jmlAkomodasi += ( $rec['akomodasi'] * $rec['lama_penggunaan']);
                            $jmlJasa += ( $rec['jasa_perawat'] * $rec['lama_penggunaan']);
                            $i++;
                        }
                    }
                    $html .= "<tr>";
                    $html .= "<td class='total' colspan='3'>Sub Total</td>";
                    $html .= "<td class='total' align='right'>" . number_format($jmlHari, 0, ',', '.') . "</td>";
                    $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTarif, 0, ',', '.') . "</td>";
                    $html .= "<td class='total' align='right'>Rp. " . number_format($jmlCatering, 0, ',', '.') . "</td>";
                    $html .= "<td class='total' align='right'>Rp. " . number_format($jmlAkomodasi, 0, ',', '.') . "</td>";
                    $html .= "<td class='total' align='right'>Rp. " . number_format($jmlJasa, 0, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $jmlTotHari += $jmlHari;
                    $jmlTotTarif += $jmlTarif;
                    $jmlTotCatering += $jmlCatering;
                    $jmlTotAkomodasi += $jmlAkomodasi;
                    $jmlTotJasa += $jmlJasa;
                }
                $html .= "<tr>";
                $html .= "<td class='total' colspan='3'>Total</td>";
                $html .= "<td class='total' align='right'>" . number_format($jmlTotHari, 0, ',', '.') . "</td>";
                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotTarif, 0, ',', '.') . "</td>";
                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotCatering, 0, ',', '.') . "</td>";
                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotAkomodasi, 0, ',', '.') . "</td>";
                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotJasa, 0, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak Ditemukan.";
            }
        } else if ($tipe_laporan == '2') {
            if ($id_tipe_pasien != "") {
                $kondisi .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";
                $tgl .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";
            }
            if ($id_dokter != "")
                $kondisi .= " and a.dokter_operator='" . $id_dokter . "'";
            if ($tipe_perawatan == "") {
                if ($startDate != "") {
                    if ($endDate != "")
                        $rule .= " and a.tgl_tindakan between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    else
                        $rule .= " and a.tgl_tindakan between '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($startDate) . " 23:59:59'";
                }
            } else if ($tipe_perawatan == 1) {
                $kondisi .= " and d.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang<>'8')";
                if ($startDate != "") {
                    if ($endDate != "")
                        $rule .= " and date(d.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $rule .= " and date(d.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
                }
            } else if ($tipe_perawatan == 2) {
                $kondisi .= " and d.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang='8')";
                $keluar = ", rm_pasien_keluar e";
                $rule .= " and e.id_pendaftaran=d.id_asal_pendaftaran ";
                if ($startDate != "") {
                    if ($endDate != "")
                        $rule .= " and date(e.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "' ";
                    else
                        $rule .= " and date(e.tgl_keluar)='" . $this->formatDateDb($startDate) . "' ";
                }
            }

            $query = "SELECT DISTINCT(a.dokter_operator), b.nama_dokter FROM rm_tindakan_ruang_medis a, rm_dokter b, rm_pasien c, rm_pendaftaran d" . $keluar . "
                      WHERE a.del_flag<>'1' and d.id_pendaftaran=a.id_pendaftaran AND b.id_dokter=a.dokter_operator AND c.id_pasien=a.id_pasien " . $kondisi . $rule;
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $jmlTot = 0;
                $jmlTotBruto = 0;
                $jmlTotNetto = 0;
                $html .= "<br><b>IBS</b>";
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='12%' class='headerTagihan'>Dokter Operator</td>";
                $html .= "<td width='12%' class='headerTagihan'>Dokter Anastesi</td>";
                $html .= "<td width='5%' class='headerTagihan'>RM</td>";
                $html .= "<td width='15%' class='headerTagihan'>Nama</td>";
                $html .= "<td width='20%' class='headerTagihan'>Alamat</td>";
                $html .= "<td width='21%' class='headerTagihan'>Nama Jenis</td>";
                $html .= "<td width='5%' class='headerTagihan'>Jml</td>";
                $html .= "<td width='10%' class='headerTagihan'>Bruto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";

                while ($data = @mysql_fetch_array($result)) {
                    $jml = 0;
                    $jmlBruto = 0;
                    $i = 1;
                    $q_detail = "SELECT c.tindakan, d.nama_pasien, d.alamat, SUM(jml) AS jumlah, (a.tarif + a.penambahan_tarif) as tarif, e.nama_dokter, a.id_pasien
                                 FROM rm_tindakan_ruang_medis a, rm_detail_tindakan b, rm_tindakan c, rm_pasien d, rm_dokter e
                                 WHERE a.del_flag<>'1' AND b.id_detail_tindakan=a.id_tindakan_medis AND c.id_tindakan=b.id_tindakan AND d.id_pasien=a.id_pasien 
                                 and a.dokter_operator='" . $data['dokter_operator'] . "' and a.dokter_anastesi=e.id_dokter " . $tgl . $rule . " GROUP BY a.id_tindakan_medis, a.id_pasien,a.id_kelas";
                    $r_detail = $this->runQuery($q_detail);
                    if (@mysql_num_rows($r_detail) > 0) {
                        while ($rec = @mysql_fetch_array($r_detail)) {
                            $html .= "<tr>";
                            if ($i == '1')
                                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                            else
                                $html .= "<td >&nbsp;</td>";
                            if ($nmd != $rec['nama_dokter'])
                                $html .= "<td >" . $rec['nama_dokter'] . "</td>";
                            else
                                $html .= "<td >&nbsp;</td>";
                            if ($rm != $rec['id_pasien'])
                                $html .= "<td >" . $rec['id_pasien'] . "</td>";
                            else
                                $html .= "<td >&nbsp;</td>";
                            if ($nmp != $rec['nama_pasien'])
                                $html .= "<td >" . $rec['nama_pasien'] . "</td>";
                            else
                                $html .= "<td >&nbsp;</td>";
                            if ($alm != $rec['alamat'])
                                $html .= "<td >" . $rec['alamat'] . "</td>";
                            else
                                $html .= "<td >&nbsp;</td>";
                            $html .= "<td >" . $rec['tindakan'] . "</td>";
                            $html .= "<td align='right'>" . number_format($rec['jumlah'], 0, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format(($rec['tarif'] * $rec['jumlah']), 2, ',', '.') . "</td>";
                            $html .= "</tr>";
                            $jml += $rec['jumlah'];
                            $jmlBruto += ( $rec['tarif'] * $rec['jumlah']);
                            $nmd = $rec['nama_dokter'];
                            $rm = $rec['id_pasien'];
                            $nmp = $rec['nama_pasien'];
                            $alm = $rec['alamat'];
                            $i++;
                        }
                    }
                    $html .= "<tr>";
                    $html .= "<td class='total' colspan='6'>Sub Total</td>";
                    $html .= "<td class='total' align='right'>" . number_format($jml, 0, ',', '.') . "</td>";
                    $html .= "<td class='total' align='right'>Rp. " . number_format($jmlBruto, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $jmlTot += $jml;
                    $jmlTotBruto += $jmlBruto;
                }
                $html .= "<tr>";
                $html .= "<td class='total' colspan='6'>Total</td>";
                $html .= "<td class='total' align='right'>" . number_format($jmlTot, 0, ',', '.') . "</td>";
                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotBruto, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak Ditemukan.";
            }
        } else if ($tipe_laporan == '3') {
            if ($id_tipe_pasien != "") {
                $kondisi .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";
                $kondKelas .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";
            }

            if ($id_ruang != "")
                $kondisi .= " and a.id_ruang='" . $id_ruang . "'";

            if ($id_dokter != "") {
                $kondKelas .= " and a.id_dokter='" . $id_dokter . "'";
                $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
            }

            if ($id_kelas != "") {
                $kondisi .= " and a.id_kelas='" . $id_kelas . "'";
                $kondKelas .= " and a.id_kelas='" . $id_kelas . "'";
            }
            if ($tipe_perawatan == "") {
                if ($startDate != "") {
                    if ($endDate != "")
                        $rule .= " and a.tgl_tindakan between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    else
                        $rule .= " and a.tgl_tindakan between '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($startDate) . " 23:59:59'";
                }
            } else if ($tipe_perawatan == 1) {
                $kondisi .= " and b.id_tipe_ruang<>'8'";
                if ($startDate != "") {
                    if ($endDate != "")
                        $rule .= " and date(b.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $rule .= " and date(b.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
                }
            } else if ($tipe_perawatan == 2) {
                $kondisi .= " and b.id_tipe_ruang='8'";
                $keluar = ", rm_pasien_keluar e";
                $rule .= " and e.id_pendaftaran=b.id_pendaftaran ";
                if ($startDate != "") {
                    if ($endDate != "")
                        $rule .= " and date(e.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $rule .= " and date(e.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
                }
            }

            $query = "SELECT DISTINCT(a.id_ruang), b.ruang FROM rm_tindakan_ruang a, rm_ruang b, rm_pasien c, rm_pendaftaran d
                      WHERE b.id_ruang=a.id_ruang AND d.id_pendaftaran=a.id_pendaftaran AND d.del_flag<>1 AND c.id_pasien=d.id_pasien " . $kondisi . $rule;
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $jmlTot = 0;
                $jmlTotBruto = 0;
                $jmlTotNetto = 0;
                $html .= "<br><b>TINDAKAN RUANG</b>";
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='5%' class='headerTagihan'>Pavilium</td>";
                $html .= "<td width='5%' class='headerTagihan'>No RM</td>";
                $html .= "<td width='20%' class='headerTagihan'>Nama Px</td>";
                $html .= "<td width='15%' class='headerTagihan'>Operator</td>";
                $html .= "<td width='15%' class='headerTagihan'>Dokter</td>";
                $html .= "<td width='20%' class='headerTagihan'>Tindakan</td>";
                $html .= "<td width='5%' class='headerTagihan'>Jml</td>";
                $html .= "<td width='15%' class='headerTagihan'>Netto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";

                while ($data = @mysql_fetch_array($result)) {
                    $jmlSub = 0;
                    $jmlSubBruto = 0;
                    $jmlSubNetto = 0;
                    $i = 1;
                    $q_pasien = "SELECT b.id_pasien, c.nama_pasien FROM rm_tindakan_ruang a, rm_pendaftaran b, rm_pasien c" . $keluar . "
                                 WHERE b.id_pendaftaran=a.id_pendaftaran AND c.id_pasien=b.id_pasien and b.del_flag<>1 and a.id_ruang='" . $data['id_ruang'] . "'" . $rule . " group by b.id_pasien";
                    $r_pasien = $this->runQuery($q_pasien);
                    if (@mysql_num_rows($r_pasien) > 0) {
                        while ($pas = @mysql_fetch_array($r_pasien)) {
                            $j = 1;
                            $q_detail = "SELECT c.tindakan, SUM(a.jml) AS jumlah, a.tarif, a.id_pelaku_tindakan, z.nama_dokter FROM rm_tindakan_ruang a, rm_detail_tindakan b, rm_tindakan c, rm_pendaftaran d, rm_dokter z
                                         WHERE b.id_detail_tindakan=a.id_detail_tindakan AND c.id_tindakan=b.id_tindakan AND d.id_pendaftaran=a.id_pendaftaran AND z.id_dokter=a.id_dokter
                                         AND d.id_pasien='" . $pas['id_pasien'] . "' AND a.id_ruang='" . $data['id_ruang'] . "' " . $kondKelas . $rule . "
                                         GROUP BY a.id_detail_tindakan, a.id_pelaku_tindakan, a.id_dokter,a.id_kelas ORDER BY a.id_pelaku_tindakan, a.id_dokter, a.id_detail_tindakan ";
                            $r_detail = $this->runQuery($q_detail);
                            if (@mysql_num_rows($r_detail) > 0) {
                                $jml = 0;
                                $jmlBruto = 0;
                                $jmlNetto = 0;
                                while ($rec = @mysql_fetch_array($r_detail)) {
                                    $html .= "<tr>";
                                    if ($i == '1')
                                        $html .= "<td >" . $data['ruang'] . "</td>";
                                    else
                                        $html .= "<td >&nbsp;</td>";
                                    if ($j == '1') {
                                        $html .= "<td >" . $pas['id_pasien'] . "</td>";
                                        $html .= "<td >" . $pas['nama_pasien'] . "</td>";
                                    } else {
                                        $html .= "<td >&nbsp;</td>";
                                        $html .= "<td >&nbsp;</td>";
                                    }
                                    if ($pelaku != $rec['id_pelaku_tindakan'] || $j == '1') {
                                        if ($rec['id_pelaku_tindakan'] == 2)
                                            $html .= "<td >Perawat " . $data['ruang'] . "</td>";
                                        else
                                            $html .= "<td >Dokter</td>";
                                    } else {
                                        $html .= "<td >&nbsp;</td>";
                                    }
                                    if ($dkt != $rec['nama_dokter'] || $j == '1')
                                        $html .= "<td >" . $rec['nama_dokter'] . "</td>";
                                    else
                                        $html .= "<td >&nbsp;</td>";
                                    $html .= "<td >" . $rec['tindakan'] . "</td>";
                                    $html .= "<td align='right'>" . number_format($rec['jumlah'], 0, ',', '.') . "</td>";
                                    $html .= "<td align='right'>Rp. " . number_format(($rec['tarif'] * $rec['jumlah']), 2, ',', '.') . "</td>";
                                    $html .= "</tr>";
                                    $jml += $rec['jumlah'];
                                    $jmlNetto += ( $rec['tarif'] * $rec['jumlah']);
                                    $j++;
                                    $i++;
                                    $pelaku = $rec['id_pelaku_tindakan'];
                                    $dkt = $rec['nama_dokter'];
                                }
                                $html .= "<tr>";
                                $html .= "<td >&nbsp;</td>";
                                $html .= "<td class='total' colspan='5'>Sub Total</td>";
                                $html .= "<td class='total' align='right'>" . number_format($jml, 0, ',', '.') . "</td>";
                                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlNetto, 2, ',', '.') . "</td>";
                                $html .= "</tr>";
                                $jmlSub += $jml;
                                $jmlSubNetto += $jmlNetto;
                            }
                        }
                        $html .= "<tr>";
                        $html .= "<td class='total' colspan='6'>Sub Total</td>";
                        $html .= "<td class='total' align='right'>" . number_format($jmlSub, 0, ',', '.') . "</td>";
                        $html .= "<td class='total' align='right'>Rp. " . number_format($jmlSubNetto, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $jmlTot += $jmlSub;
                        $jmlTotNetto += $jmlSubNetto;
                    }
                }
                $html .= "<tr>";
                $html .= "<td class='total' colspan='6'>Total</td>";
                $html .= "<td class='total' align='right'>" . number_format($jmlTot, 0, ',', '.') . "</td>";
                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotNetto, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak Ditemukan.";
            }
        } else if ($tipe_laporan == '4') {
            if ($id_tipe_pasien != "") {
                $kondisi .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";
            }
            if ($tipe_perawatan == "") {
                if ($startDate != "") {
                    if ($endDate != "") {
                        $kondisi .= " and a.tgl_pemeriksaan between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    } else {
                        $kondisi .= " and a.tgl_pemeriksaan between '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($startDate) . " 23:59:59'";
                    }
                }
            } else if ($tipe_perawatan == "1") {
                $kondisi .= " and (c.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang<>'8'))";
                $kondRuang .= " and (b.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang<>'8'))";
                if ($startDate != "") {
                    if ($endDate != "") {
                        $kondisi .= " and date(c.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    } else {
                        $kondisi .= " and date(c.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
                    }
                }
            } else if ($tipe_perawatan == "2") {
                $kondisi .= " and (c.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang='8'))";
                $kondRuang .= " and (b.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang='8'))";
                $keluar .= ", rm_pasien_keluar e";
                $kondisi .= " and e.id_pendaftaran=c.id_asal_pendaftaran ";
                if ($startDate != "") {
                    if ($endDate != "") {
                        $kondisi .= " and e.tgl_keluar between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    } else {
                        $kondisi .= " and e.tgl_keluar between '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($startDate) . " 23:59:59'";
                    }
                }
            }

            if ($id_ruang != "")
                $kondisi .= " and c.id_ruang_asal = '" . $id_ruang . "'";

            $query = "SELECT DISTINCT(a.id_radiologi), b.radiologi FROM rm_detail_radiologi a, rm_radiologi b, rm_pendaftaran c, rm_pasien d" . $keluar . "
                      WHERE a.del_flag<>'1' AND b.id_radiologi=a.id_radiologi AND c.id_pendaftaran=a.id_pendaftaran 
                      AND d.id_pasien=a.id_pasien " . $kondisi;
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $jmlTot = 0;
                $jmlTotBruto = 0;
                $jmlTotNetto = 0;
                $html .= "<br><b>RADIOLOGI</b>";
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='40%' class='headerTagihan'>Periksa</td>";
                $html .= "<td width='15%' class='headerTagihan'>Sub Layanan</td>";
                $html .= "<td width='5%' class='headerTagihan'>RM Px</td>";
                $html .= "<td width='20%' class='headerTagihan'>Nama Px</td>";
                $html .= "<td width='5%' class='headerTagihan'>Jml</td>";
                $html .= "<td width='15%' class='headerTagihan'>Netto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";

                while ($data = @mysql_fetch_array($result)) {
                    $i = 1;
                    $q_ruang = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_ruang IN (
                                 SELECT id_ruang_asal FROM rm_detail_radiologi a, rm_pendaftaran b WHERE a.del_flag<>'1' 
                                 AND b.id_pendaftaran=a.id_pendaftaran and a.id_radiologi='" . $data['id_radiologi'] . "'
                                 " . $kondRuang . ")";
                    $r_ruang = $this->runQuery($q_ruang);
                    if (@mysql_num_rows($r_ruang) > 0) {
                        $jmlSub = 0;
                        $jmlSubNetto = 0;
                        while ($ruang = @mysql_fetch_array($r_ruang)) {
                            $j = 1;
                            $q_detail = "SELECT b.id_pasien, b.nama_pasien, a.tarif, SUM(a.jml) AS jumlah FROM rm_detail_radiologi a, rm_pasien b, rm_pendaftaran c" . $keluar . "
                                         WHERE a.del_flag<>'1' AND b.id_pasien=a.id_pasien AND c.id_pendaftaran=a.id_pendaftaran and c.id_ruang_asal='" . $ruang['id_ruang'] . "' and a.id_radiologi='" . $data['id_radiologi'] . "'
                                         " . $kondisi . "GROUP BY a.id_pasien, a.id_radiologi, a.id_kelas, a.cito, a.cito_bed";
                            $r_detail = $this->runQuery($q_detail);
                            if (@mysql_num_rows($r_detail) > 0) {
                                $jml = 0;
                                $jmlBruto = 0;
                                $jmlNetto = 0;
                                while ($rec = @mysql_fetch_array($r_detail)) {
                                    $html .= "<tr>";
                                    if ($i == '1')
                                        $html .= "<td >" . $data['radiologi'] . "</td>";
                                    else
                                        $html .= "<td >&nbsp;</td>";
                                    if ($j == '1') {
                                        $html .= "<td >" . $ruang['ruang'] . "</td>";
                                    } else {
                                        $html .= "<td >&nbsp;</td>";
                                    }
                                    if ($rmpx != $rec['id_pasien'] || $j == '1') {
                                        $html .= "<td >" . $rec['id_pasien'] . "</td>";
                                        $html .= "<td >" . $rec['nama_pasien'] . "</td>";
                                    } else {
                                        $html .= "<td >&nbsp;</td>";
                                        $html .= "<td >&nbsp;</td>";
                                    }
                                    $html .= "<td align='right'>" . number_format($rec['jumlah'], 0, ',', '.') . "</td>";
                                    $html .= "<td align='right'>Rp. " . number_format(($rec['tarif'] * $rec['jumlah']), 2, ',', '.') . "</td>";
                                    $html .= "</tr>";
                                    $jml += $rec['jumlah'];
                                    $jmlNetto += ( $rec['tarif'] * $rec['jumlah']);
                                    $j++;
                                    $i++;
                                    $rmpx = $rec['id_pasien'];
                                }
                                $html .= "<tr>";
                                $html .= "<td >&nbsp;</td>";
                                $html .= "<td class='total' colspan='3'>Sub Total</td>";
                                $html .= "<td class='total' align='right'>" . number_format($jml, 0, ',', '.') . "</td>";
                                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlNetto, 2, ',', '.') . "</td>";
                                $html .= "</tr>";
                                $jmlSub += $jml;
                                $jmlSubNetto += $jmlNetto;
                            }
                        }
                        $html .= "<tr>";
                        $html .= "<td class='total' colspan='4'>Sub Total</td>";
                        $html .= "<td class='total' align='right'>" . number_format($jmlSub, 0, ',', '.') . "</td>";
                        $html .= "<td class='total' align='right'>Rp. " . number_format($jmlSubNetto, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $jmlTot += $jmlSub;
                        $jmlTotNetto += $jmlSubNetto;
                    }
                }
                $html .= "<tr>";
                $html .= "<td class='total' colspan='4'>Total</td>";
                $html .= "<td class='total' align='right'>" . number_format($jmlTot, 0, ',', '.') . "</td>";
                $html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotNetto, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak Ditemukan.";
            }
        } else if ($tipe_laporan == '5') {
            if ($id_tipe_pasien != "")
                $kondisi .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";

            if ($tipe_perawatan == "") {
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(a.tgl_pemeriksaan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(a.tgl_pemeriksaan)='" . $this->formatDateDb($startDate) . "'";
                }
            } else if ($tipe_perawatan == "1") {
                $kondisi .= " and (d.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang<>'8'))";
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(d.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(d.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
                }
            } else if ($tipe_perawatan == "2") {
                $kondisi .= " and (d.id_ruang_asal in (select id_ruang from rm_ruang where id_tipe_ruang='8'))";
                $keluar .= ", rm_pasien_keluar e";
                $kondisi .= " and e.id_pendaftaran=d.id_asal_pendaftaran ";
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(e.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(e.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
                }
            }

            $query = "SELECT b.laboratorium, SUM(a.jml) AS jumlah, a.tarif FROM rm_detail_laboratorium a, rm_laboratorium b, rm_pasien c, rm_pendaftaran d" . $keluar . "
                      WHERE a.del_flag<>'1' AND b.id_laboratorium=a.id_laboratorium AND d.del_flag<>'1' AND a.id_pendaftaran=d.id_pendaftaran AND c.id_pasien=a.id_pasien " . $kondisi . "
                      GROUP BY a.id_laboratorium, a.id_kelas, a.cito, a.tarif";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $jmlTot = 0;
                $jmlTotBruto = 0;
                $jmlTotNetto = 0;
                $html .= "<br><b>LABORAT</b>";
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='45%' class='headerTagihan'>Periksa</td>";
                $html .= "<td width='5%' class='headerTagihan'>Jumlah</td>";
                $html .= "<td width='25%' class='headerTagihan'>Biaya</td>";
                $html .= "<td width='25%' class='headerTagihan'>Netto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";

                while ($rec = @mysql_fetch_array($result)) {
                    $html .= "<tr>";
                    $html .= "<td >" . $rec['laboratorium'] . "</td>";
                    $html .= "<td align='right'>" . number_format($rec['jumlah'], 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format(($rec['tarif']), 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format(($rec['tarif'] * $rec['jumlah']), 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $jmlTot += $rec['jumlah'];
                    //$jmlTotBruto += ( $rec['tarif'] * $rec['jumlah']);
                    $jmlTotNetto += ( $rec['tarif'] * $rec['jumlah']);
                }
                $html .= "<tr>";
                $html .= "<td class='total'>Total</td>";
                $html .= "<td class='total' align='right'>" . number_format($jmlTot, 0, ',', '.') . "</td>";
                //$html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotBruto, 2, ',', '.') . "</td>";
                $html .= "<td class='total' colspan='2' align='right'>Rp. " . number_format($jmlTotNetto, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak Ditemukan.";
            }
        } else if ($tipe_laporan == '6') {
            if ($id_tipe_pasien != "")
                $kondisi .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";
            if ($tipe_perawatan == "") {
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(a.tgl_tindakan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(a.tgl_tindakan)='" . $this->formatDateDb($startDate) . "'";
                }
            } else if ($tipe_perawatan == "1") {
                $kondisi .= " and x.id_tipe_ruang<>'8'";
                //$kondRuang .= " and d.id_tipe_ruang<>'8'";
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(c.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(c.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
                }
            } else if ($tipe_perawatan == "2") {
                $kondisi .= " and x.id_tipe_ruang='8' and e.id_pendaftaran=c.id_pendaftaran";
                //$kondRuang .= " and d.id_tipe_ruang='8' and e.id_pendaftaran = c.id_pendaftaran ";
                $tKeluar = ", rm_pasien_keluar e";
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(e.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(e.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
                }
            }

            if ($id_ruang != "")
                $kondisi .= " and a.id_ruang='" . $id_ruang . "'";

            $query = "SELECT a.id_ruang, x.ruang FROM rm_fasilitas_ruang a, rm_ruang x, rm_pendaftaran c, rm_pasien d" . $tKeluar . "
                      WHERE a.del_flag<>'1' AND x.id_ruang=a.id_ruang AND c.id_pendaftaran=a.id_pendaftaran AND d.id_pasien=c.id_pasien" . $kondisi . "
                      GROUP BY a.id_ruang ";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $jmlTot = 0;
                $jmlTotBruto = 0;
                $jmlTotNetto = 0;
                $html .= "<br><b>UTILITAS / FASILITAS</b>";
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='25%' class='headerTagihan'>Pavilium</td>";
                $html .= "<td width='40%' class='headerTagihan'>Tindakan</td>";
                $html .= "<td width='5%' class='headerTagihan'>Jml</td>";
                $html .= "<td width='15%' class='headerTagihan'>Tarif</td>";
                $html .= "<td width='15%' class='headerTagihan'>Netto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";

                while ($data = @mysql_fetch_array($result)) {
                    $i = 1;
                    $q_detail = "SELECT f.tindakan, SUM(a.jumlah) AS jumlah, a.tarif FROM rm_fasilitas_ruang a, rm_detail_tindakan b, rm_pendaftaran c , rm_ruang x, rm_tindakan f" . $tKeluar . "
                                WHERE b.id_detail_tindakan=a.id_detail_tindakan AND f.id_tindakan=b.id_tindakan AND a.id_pendaftaran=c.id_pendaftaran and x.id_ruang=a.id_ruang " . $kondisi . " and a.id_ruang='" . $data['id_ruang'] . "'
                                GROUP BY a.id_detail_tindakan, a.id_kelas,a.tarif";
                    $r_detail = $this->runQuery($q_detail);
                    if (@mysql_num_rows($r_detail) > 0) {
                        $jml = 0;
                        $jmlBruto = 0;
                        $jmlNetto = 0;
                        while ($rec = @mysql_fetch_array($r_detail)) {
                            $html .= "<tr>";
                            if ($i == '1')
                                $html .= "<td >" . $data['ruang'] . "</td>";
                            else
                                $html .= "<td >&nbsp;</td>";
                            $html .= "<td >" . $rec['tindakan'] . "</td>";
                            $html .= "<td align='right'>" . number_format($rec['jumlah'], 0, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format(($rec['tarif']), 2, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format(($rec['tarif'] * $rec['jumlah']), 2, ',', '.') . "</td>";
                            $html .= "</tr>";
                            $jml += $rec['jumlah'];
                            //$jmlBruto += ( $rec['tarif']);
                            $jmlNetto += ( $rec['tarif'] * $rec['jumlah']);
                            $i++;
                        }
                        $html .= "<tr>";
                        $html .= "<td class='total' colspan='2'>Sub Total</td>";
                        $html .= "<td class='total' align='right'>" . number_format($jml, 0, ',', '.') . "</td>";
                        //$html .= "<td class='total' align='right'>Rp. " . number_format($jmlBruto, 2, ',', '.') . "</td>";
                        $html .= "<td class='total' colspan='2' align='right'>Rp. " . number_format($jmlNetto, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $jmlTot += $jml;
                        //$jmlTotBruto += $jmlBruto;
                        $jmlTotNetto += $jmlNetto;
                    }
                }
                $html .= "<tr>";
                $html .= "<td class='total' colspan='2'>Total</td>";
                $html .= "<td class='total' align='right'>" . number_format($jmlTot, 0, ',', '.') . "</td>";
                //$html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotBruto, 2, ',', '.') . "</td>";
                $html .= "<td class='total' colspan='2' align='right'>Rp. " . number_format($jmlTotNetto, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak Ditemukan.";
            }
        } else if ($tipe_laporan == '7') {
            if ($tipe_perawatan == "" || $tipe_perawatan == 1) {
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(a.tgl_visit) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(a.tgl_visit)='" . $this->formatDateDb($startDate) . "'";
                }
            } else if ($tipe_perawatan == 2) {
                $tKeluar .= ", rm_pasien_keluar e";
                $kondisi .= " and a.id_pendaftaran = e.id_pendaftaran";
                if ($startDate != "") {
                    if ($endDate != "")
                        $kondisi .= " and date(e.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                    else
                        $kondisi .= " and date(e.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
                }
            }

            if ($id_tipe_pasien != "")
                $kondisi .= " and a.id_tipe_pasien='" . $id_tipe_pasien . "'";

            if ($id_ruang != "")
                $kondisi .= " and a.id_ruang='" . $id_ruang . "'";

            if ($id_dokter != "")
                $kondisi .= " and a.id_dokter='" . $id_dokter . "'";

            $query = "SELECT a.id_ruang, b.ruang FROM rm_visit a, rm_ruang b, rm_pasien c" . $tKeluar . "
                      WHERE b.id_ruang=a.id_ruang AND c.id_pasien=a.id_pasien AND a.del_flag<>'1' 
                      AND a.id_ruang!='20' " . $kondisi . " GROUP by a.id_ruang";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $jmlTot = 0;
                $jmlTotBruto = 0;
                $jmlTotNetto = 0;
                $html .= "<br><b>VISITE</b>";
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='15%' class='headerTagihan'>Pavilium</td>";
                $html .= "<td width='25%' class='headerTagihan'>Dokter</td>";
                $html .= "<td width='25%' class='headerTagihan'>Nama Px</td>";
                $html .= "<td width='5%' class='headerTagihan'>Jml</td>";
                $html .= "<td width='15%' class='headerTagihan'>Biaya Visit</td>";
                $html .= "<td width='15%' class='headerTagihan'>Netto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";

                while ($data = @mysql_fetch_array($result)) {
                    $i = 1;
                    $q_ruang = "SELECT a.id_dokter, b.nama_dokter FROM rm_visit a, rm_dokter b, rm_pasien c" . $tKeluar . "
                                WHERE b.id_dokter=a.id_dokter AND c.id_pasien=a.id_pasien AND a.del_flag<>'1' 
                                AND a.id_ruang='" . $data['id_ruang'] . "' " . $kondisi . " group by a.id_dokter";
                    $r_ruang = $this->runQuery($q_ruang);
                    if (@mysql_num_rows($r_ruang) > 0) {
                        $jmlSub = 0;
                        $jmlSubBruto = 0;
                        $jmlSubNetto = 0;
                        while ($ruang = @mysql_fetch_array($r_ruang)) {
                            $j = 1;
                            $q_detail = "SELECT c.nama_pasien, SUM(a.ctr) AS jumlah, a.tarif FROM rm_visit a, rm_pasien c" . $tKeluar . "
                                         WHERE c.id_pasien=a.id_pasien AND a.del_flag<>'1' and a.id_dokter='" . $ruang['id_dokter'] . "'
                                         AND a.id_ruang='" . $data['id_ruang'] . "' " . $kondisi . " group by a.id_pasien, a.id_kelas, a.tarif";
                            $r_detail = $this->runQuery($q_detail);
                            if (@mysql_num_rows($r_detail) > 0) {
                                $jml = 0;
                                $jmlBruto = 0;
                                $jmlNetto = 0;
                                while ($rec = @mysql_fetch_array($r_detail)) {
                                    $html .= "<tr>";
                                    if ($i == '1')
                                        $html .= "<td >" . $data['ruang'] . "</td>";
                                    else
                                        $html .= "<td >&nbsp;</td>";
                                    if ($j == '1') {
                                        $html .= "<td >" . $ruang['nama_dokter'] . "</td>";
                                    } else {
                                        $html .= "<td >&nbsp;</td>";
                                    }
                                    $html .= "<td >" . $rec['nama_pasien'] . "</td>";
                                    $html .= "<td align='right'>" . number_format($rec['jumlah'], 0, ',', '.') . "</td>";
                                    $html .= "<td align='right'>Rp. " . number_format(($rec['tarif']), 2, ',', '.') . "</td>";
                                    $html .= "<td align='right'>Rp. " . number_format(($rec['tarif'] * $rec['jumlah']), 2, ',', '.') . "</td>";
                                    $html .= "</tr>";
                                    $jml += $rec['jumlah'];
                                    //$jmlBruto += ( $rec['tarif'] * $rec['jumlah']);
                                    $jmlNetto += ( $rec['tarif'] * $rec['jumlah']);
                                    $j++;
                                    $i++;
                                }
                                $html .= "<tr>";
                                $html .= "<td >&nbsp;</td>";
                                $html .= "<td class='total' colspan='2'>Sub Total</td>";
                                $html .= "<td class='total' align='right'>" . number_format($jml, 0, ',', '.') . "</td>";
                                //$html .= "<td class='total' align='right'>Rp. " . number_format($jmlBruto, 2, ',', '.') . "</td>";
                                $html .= "<td class='total' colspan='2' align='right'>Rp. " . number_format($jmlNetto, 2, ',', '.') . "</td>";
                                $html .= "</tr>";
                                $jmlSub += $jml;
                                //$jmlSubBruto += $jmlBruto;
                                $jmlSubNetto += $jmlNetto;
                            }
                        }
                        $html .= "<tr>";
                        $html .= "<td class='total' colspan='3'>Sub Total</td>";
                        $html .= "<td class='total' align='right'>" . number_format($jmlSub, 0, ',', '.') . "</td>";
                        //$html .= "<td class='total' align='right'>Rp. " . number_format($jmlSubBruto, 2, ',', '.') . "</td>";
                        $html .= "<td class='total' colspan='2' align='right'>Rp. " . number_format($jmlSubNetto, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $jmlTot += $jmlSub;
                        //$jmlTotBruto += $jmlSubBruto;
                        $jmlTotNetto += $jmlSubNetto;
                    }
                }
                $html .= "<tr>";
                $html .= "<td class='total' colspan='3'>Total</td>";
                $html .= "<td class='total' align='right'>" . number_format($jmlTot, 0, ',', '.') . "</td>";
                //$html .= "<td class='total' align='right'>Rp. " . number_format($jmlTotBruto, 2, ',', '.') . "</td>";
                $html .= "<td class='total' colspan='2' align='right'>Rp. " . number_format($jmlTotNetto, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak Ditemukan.";
            }
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getRekapJasa(
    $startDate, $endDate, $id_dokter, $id_ruang
    ) {
        $html = "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<td width='2%' class='headerTagihan'>No</td>";
        $html .= "<td width='8%' class='headerTagihan'>Ruang</td>";
        $html .= "<td width='8%' class='headerTagihan'>Nama Dokter</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Sarana</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Layanan</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Unit Penghasil</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Direksi</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Remunerasi</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Dokter</td>";
        $html .= "<td width='10%' class='headerTagihan'>Pajak Dokter</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Perawat</td>";
        $html .= "<td width='10%' class='headerTagihan'>Pajak Perawat</td>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";

        $tot_jasa_sarana = 0;
        $tot_jasa_layanan = 0;
        $tot_unit_penghasil = 0;
        $tot_jasa_direksi = 0;
        $tot_jasa_remunerasi = 0;
        $tot_jasa_dokter = 0;
        $tot_jasa_perawat = 0;
        $tot_pajak_dokter = 0;
        $tot_pajak_perawat = 0;

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and b.id_ruang='" . $id_ruang . "'";
        $q_daftar = "SELECT
                          e.ruang,
                          g.nama_dokter,
                          SUM(a.jasa_layanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(a.jasa_direksi) AS jasa_direksi,
                          SUM(a.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(a.jasa_dokter) AS jasa_dokter,
                          SUM(a.jasa_perawat) AS jasa_perawat,
                          SUM((h.pajak * a.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * a.jasa_perawat)) AS pajakPerawat
                        FROM
                          rm_jasa_pendaftaran a,
                          rm_pendaftaran b,
                          rm_pasien c,
                          rm_tipe_pasien d,
                          rm_ruang e,
                          rm_tipe_pendaftaran f,
                          rm_dokter g,
                          rm_golongan h
                        WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          c.id_pasien = a.id_pasien AND
                          a.id_pasien = b.id_pasien AND
                          d.id_tipe_pasien = c.id_tipe_pasien AND
                          e.id_ruang = a.id_ruang AND
                          f.id_tipe_pendaftaran = b.id_tipe_pendaftaran AND
                          g.id_dokter = b.id_dokter AND
                          f.id_tipe_pendaftaran!='6' AND
                          b.biaya_pendaftaran > 0 AND
                          h.id_golongan = g.id_golongan " . $kondisi . "
                    GROUP BY b.id_ruang, a.id_dokter";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Pendaftaran</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_daftar)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_tindakan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_tindakan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and g.id_ruang='" . $id_ruang . "'";

        $q_poli = "SELECT
                          i.ruang,
                          e.nama_dokter,
                          SUM(a.jasa_layanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(a.jasa_direksi) AS jasa_direksi,
                          SUM(a.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(a.jasa_dokter) AS jasa_dokter,
                          SUM(a.jasa_perawat) AS jasa_perawat,
                          SUM((j.pajak * a.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * a.jasa_perawat)) AS pajakPerawat
                      FROM
                          rm_jasa_tindakan_poli a,
                          rm_tindakan_ruang b,
                          rm_pasien c,
                          rm_tipe_pasien d,
                          rm_dokter e,
                          rm_pelaku_tindakan f,
                          rm_detail_tindakan g,
                          rm_tindakan h,
                          rm_ruang i,
                          rm_golongan j
                      WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          c.id_pasien = a.id_pasien AND
                          a.id_tindakan_ruang = b.id_tindakan_ruang AND
                          d.id_tipe_pasien = c.id_tipe_pasien AND
                          e.id_dokter = a.id_dokter AND
                          f.id_pelaku_tindakan = a.id_pelaku_tindakan AND
                          h.id_tindakan = g.id_tindakan AND
                          a.id_detail_tindakan = g.id_detail_tindakan AND
                          i.id_ruang = g.id_ruang AND
                          j.id_golongan = e.id_golongan AND
                          j.id_golongan = e.id_golongan " . $kondisi . "
                      GROUP BY
                          g.id_ruang, a.id_dokter";
        $r_poli = $this->runQuery($q_poli);

        if (@mysql_num_rows($r_poli)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Tindakan</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_poli)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_visit) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_visit)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and a.id_ruang='" . $id_ruang . "'";

        $q_visit = "SELECT
                          f.ruang,
                          d.nama_dokter,
                          SUM(a.tarif) AS jasa_dokter,
                          SUM((e.pajak * a.tarif)) AS pajak_dokter
                        FROM
                          rm_visit a,
                          rm_pasien b,
                          rm_tipe_pasien c,
                          rm_dokter d,
                          rm_golongan e,
                          rm_ruang f
                        WHERE
                          b.id_pasien = a.id_pasien AND
                          a.del_flag<>'1' AND
                          c.id_tipe_pasien = b.id_tipe_pasien AND
                          d.id_dokter = a.id_dokter AND
                          e.id_golongan = d.id_golongan AND
                          f.id_ruang=a.id_ruang " . $kondisi . "
                        GROUP BY
                          a.id_ruang, a.id_dokter";
        $r_visit = $this->runQuery($q_visit);
        if (@mysql_num_rows($r_visit)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Visit & Pemeriksaan</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_visit)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "</tr>";
                $i++;
                $jasa_dokter += $data['jasa_dokter'];
                $pajak_dokter += $data['pajak_dokter'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "</tr>";
            $tot_jasa_dokter += $jasa_dokter;
            $tot_pajak_dokter += $pajak_dokter;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_ruang != "")
            $kondisi .= " and a.id_ruang='" . $id_ruang . "'";
        $q_perawatan = "SELECT
                                  d.ruang,
                                  SUM((g.jasa_perawat * a.lama_penggunaan)) AS jasa_perawat,
                                  SUM((0.05 * (g.jasa_perawat * a.lama_penggunaan))) AS pajak
                              FROM
                                  rm_penggunaan_kamar a,
                                  rm_pasien b,
                                  rm_tipe_pasien c,
                                  rm_ruang d,
                                  rm_detail_kamar e,
                                  rm_kamar f,
                                  rm_tarif_kamar g
                              WHERE
                                  b.id_pasien = a.id_pasien AND
                                  c.id_tipe_pasien = b.id_tipe_pasien AND
                                  d.id_ruang = a.id_ruang AND
                                  e.id_detail_kamar = a.id_detail_kamar AND
                                  f.id_kamar = e.id_kamar AND
                                  f.id_kamar = g.id_kamar AND
                                  g.id_kelas = a.id_kelas AND a.del_flag<>'1'
                                  AND a.tgl_keluar!='' " . $kondisi . "
                              GROUP BY a.id_ruang";
        $r_perawatan = $this->runQuery($q_perawatan);
        if (@mysql_num_rows($r_perawatan)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Perawatan</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_perawatan)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >&nbsp;</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_perawat += $data['pajak'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.ambil) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.ambil)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and e.id_ruang_asal='" . $id_ruang . "'";

        $q_lab = "SELECT
                          f.ruang,
                          i.nama_dokter,
                          SUM(a.jasa_layanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(a.jasa_direksi) AS jasa_direksi,
                          SUM(a.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(a.jasa_dokter) AS jasa_dokter,
                          SUM(a.jasa_perawat) AS jasa_perawat,
                          SUM((j.pajak * a.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * a.jasa_perawat)) AS pajakPerawat
                        FROM
                          rm_jasa_tindakan_laboratorium a,
                          rm_pemeriksaan_lab b,
                          rm_pasien c,
                          rm_tipe_pasien d, 
                          rm_pendaftaran e,
                          rm_ruang f,
                          rm_kelompok_lab g,
                          rm_laboratorium h,
                          rm_dokter i,
                          rm_golongan j
                        WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          b.id_pasien = a.id_pasien AND
                          c.id_pasien = a.id_pasien AND
                          d.id_tipe_pasien = c.id_tipe_pasien AND
                          e.id_pendaftaran = a.id_pendaftaran AND
                          f.id_ruang = e.id_ruang_asal AND
                          h.id_laboratorium = a.id_laboratorium AND
                          g.id_kelompok_lab = h.id_kelompok_lab AND
                          j.id_golongan = i.id_golongan AND
                          i.id_dokter = a.id_dokter " . $kondisi . "
                        GROUP BY
                          e.id_ruang_asal, a.id_dokter";
        $r_lab = $this->runQuery($q_lab);
        if (@mysql_num_rows($r_lab)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Laboratorium</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_lab)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pemeriksaan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pemeriksaan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and i.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and d.id_ruang_asal='" . $id_ruang . "'";

        $q_rad = "SELECT
                          e.ruang,
                          h.nama_dokter,
                          SUM(i.jasa_layanan) AS jasa_layanan,
                          SUM(i.jasa_sarana) AS jasa_sarana,
                          SUM(i.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(i.jasa_direksi) AS jasa_direksi,
                          SUM(i.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(i.jasa_dokter) AS jasa_dokter,
                          SUM(i.jasa_perawat) AS jasa_perawat,
                          SUM((j.pajak * i.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * i.jasa_perawat)) AS pajakPerawat
                      FROM
                          rm_detail_radiologi a,
                          rm_pasien b,
                          rm_tipe_pasien c,
                          rm_pendaftaran d,
                          rm_ruang e,
                          rm_radiologi f,
                          rm_kelompok_radiologi g,
                          rm_dokter h,
                          rm_jasa_tindakan_radiologi i,
                          rm_golongan j
                      WHERE
                          b.id_pasien = a.id_pasien AND
                          c.id_tipe_pasien = b.id_tipe_pasien AND
                          d.id_pendaftaran = a.id_pendaftaran AND
                          e.id_ruang = d.id_ruang_asal AND
                          f.id_radiologi = a.id_radiologi AND
                          f.id_radiologi = i.id_radiologi AND
                          g.id_kelompok_radiologi = f.id_kelompok_radiologi AND
                          i.id_pendaftaran = a.id_pendaftaran AND
                          i.id_pasien = a.id_pasien AND
                          j.id_golongan = h.id_golongan AND
                          h.id_dokter = i.id_dokter " . $kondisi . "
                      GROUP BY d.id_ruang_asal, i.id_dokter";
        $r_rad = $this->runQuery($q_rad);
        if (@mysql_num_rows($r_rad)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Radiologi</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_rad)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_tindakan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_tindakan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and e.id_dokter='" . $id_dokter . "'";

        $q_bedah = "SELECT
                          e.nama_dokter AS dokter_operator,
                          f.nama_dokter AS dokter_anastesi,
                          SUM(a.jasa_pelayanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.unit_penghasil) AS unit_penghasil,
                          SUM(a.direksi) AS jasa_direksi,
                          SUM(a.remunerasi) AS jasa_remunerasi,
                          SUM(a.tim_operator) AS tim_operator,
                          SUM(a.ass_tim_operator) AS ass_tim_operator,
                          SUM((i.pajak * a.tim_operator)) AS pajak_operator,
                          SUM((0.05 * a.ass_tim_operator)) AS pajak_ass_operator
                      FROM
                          rm_jasa_tindakan_bedah a,
                          rm_tindakan_ruang_medis b,
                          rm_pasien c,
                          rm_tipe_pasien g,
                          rm_tindakan d,
                          rm_detail_tindakan h,
                          rm_dokter e,
                          rm_dokter f,
                          rm_golongan i
                      WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          a.id_tindakan_medis = b.id_tindakan_medis AND
                          a.id_pasien = c.id_pasien AND
                          c.id_tipe_pasien = g.id_tipe_pasien AND
                          h.id_detail_tindakan = a.id_tindakan_medis AND
                          d.id_tindakan = h.id_tindakan AND
                          b.dokter_operator = e.id_dokter AND
                          b.dokter_anastesi = f.id_dokter AND
                          i.id_golongan=e.id_golongan " . $kondisi . "
                      GROUP BY b.dokter_operator";
        $r_bedah = $this->runQuery($q_bedah);

        if (@mysql_num_rows($r_bedah)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Bedah Operator</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_bedah)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >&nbsp;</td>";
                $html .= "<td >" . $data['dokter_operator'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['tim_operator'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_operator'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['ass_tim_operator'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_ass_operator'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['tim_operator'];
                $jasa_perawat += $data['ass_tim_operator'];
                $pajak_dokter += $data['pajak_operator'];
                $pajak_perawat += $data['pajak_ass_operator'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_tindakan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_tindakan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and f.id_dokter='" . $id_dokter . "'";

        $q_bedah = "SELECT
                          e.nama_dokter AS dokter_operator,
                          f.nama_dokter AS dokter_anastesi,
                          SUM(a.jasa_pelayanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.unit_penghasil) AS unit_penghasil,
                          SUM(a.direksi) AS jasa_direksi,
                          SUM(a.remunerasi) AS jasa_remunerasi,
                          SUM(a.tim_anastesi) AS tim_anastesi,
                          SUM(a.ass_tim_anastesi) AS ass_tim_anastesi,
                          SUM((i.pajak * a.tim_anastesi)) AS pajak_anastesi,
                          SUM((0.05 * a.ass_tim_anastesi)) AS pajak_ass_anastesi
                      FROM
                          rm_jasa_tindakan_bedah a,
                          rm_tindakan_ruang_medis b,
                          rm_pasien c,
                          rm_tipe_pasien g,
                          rm_tindakan d,
                          rm_detail_tindakan h,
                          rm_dokter e,
                          rm_dokter f,
                          rm_golongan i
                      WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          a.id_tindakan_medis = b.id_tindakan_medis AND
                          a.id_pasien = c.id_pasien AND
                          c.id_tipe_pasien = g.id_tipe_pasien AND
                          h.id_detail_tindakan = a.id_tindakan_medis AND
                          d.id_tindakan = h.id_tindakan AND
                          b.dokter_operator = e.id_dokter AND
                          b.dokter_anastesi = f.id_dokter AND
                          i.id_golongan=f.id_golongan " . $kondisi . "
                      GROUP BY b.dokter_anastesi";
        $r_bedah = $this->runQuery($q_bedah);

        if (@mysql_num_rows($r_bedah)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Bedah Anastesi</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_bedah)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >&nbsp;</td>";
                $html .= "<td >" . $data['dokter_anastesi'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['tim_anastesi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_anastesi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['ass_tim_anastesi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_ass_anastesi'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['tim_anastesi'];
                $jasa_perawat += $data['ass_tim_anastesi'];
                $pajak_dokter += $data['pajak_anastesi'];
                $pajak_perawat += $data['pajak_ass_anastesi'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $html .= "<tr>";
        $html .= "<td colspan='3' class='total'>Grand Total</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_sarana, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_layanan, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_unit_penghasil, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_direksi, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_remunerasi, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_dokter, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_pajak_dokter, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_perawat, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_pajak_perawat, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "</tbody>";
        $html .= "</table>";

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getBiayaPendaftaran($tipe_pendaftaran, $id_pasien) {
        $query = "SELECT biaya FROM rm_biaya_pendaftaran where id_tipe_pendaftaran='" . $tipe_pendaftaran . "'";

        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $identitasPasien = $this->getNoIdentitasPasien($id_pasien);
            if ($this->checkIdentitasPasien($identitasPasien, $tipe_pendaftaran)) {
                $result = 0;
            } else {
                if ($tipe_pendaftaran == '5') {
                    if ($this->checkRehabMedic(14, $id_pasien)) {
                        $result = 0;
                    } else {
                        $result = mysql_result($result, 0, 'biaya');
                    }
                } else {
//                    if ($this->checkTipeAsuransi($id_pasien)) {
//                        if ($this->checkJmlDaftar($id_pasien)) {
//                            $result = mysql_result($result, 0, 'biaya');
//                        } else {
//                            $result = 0;
//                        }
//                    } else {
//                        $result = mysql_result($result, 0, 'biaya');
//                    }
                    $result = mysql_result($result, 0, 'biaya');
                }
            }
            $biaya[] = array('dataValue' => $result);

            return $this->jEncode($biaya);
        }
    }

    public function getJasaDokterTamu($kelas,$id_tindakan,$dokter){
        $query = "SELECT jasa FROM rm_jasa_dokter_tamu WHERE id_tindakan=". $id_tindakan ." AND id_kelas=".$kelas." AND id_dokter=".$dokter." AND del_flag<>1";
        $result = $this->runQuery($query);
        if($query)
            return @mysql_result($result, 0, 'jasa');
        else
            return 0;
    }

    public function getTarifTindakanMedis($id_tindakan_medis, $id_pendaftaran, $dokter_operator, $cito, $alat_tamu) {
        $kelas = $this->getKelasPendaftaran($id_pendaftaran);
        $selisih = 0;

        $q_tindakan = "select id_tindakan from rm_detail_tindakan where id_detail_tindakan='" . $id_tindakan_medis . "'";
        $r_tindakan = $this->runQuery($q_tindakan);
	
	$id_tindakan = @mysql_result($r_tindakan, 0, 'id_tindakan');

        $query = "select a.id_tarif_tindakan, a.tarif, b.id_jenis_tindakan from rm_tarif_tindakan a, rm_tindakan b where a.id_tindakan='" . $id_tindakan  . "' and a.id_kelas='" . $kelas . "' and a.del_flag<>'1' and b.id_tindakan=a.id_tindakan";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $tarif = @mysql_result($result, 0, 'tarif');
            if ($cito == '1') {
                $tarif = $tarif + ($tarif * 0.25);
            }
            $jasa_operator = $this->getJasaIbs($kelas, 'tim_operator') * $tarif;
            if ($this->getJenisDokter($dokter_operator) == '3') {
                if ($dokter_operator == '6') {
                    if ($kelas == '1' || $kelas == '4') {
                        $jasa_tamu = $this->getJasaDokterTamu($kelas, $id_tindakan, $dokter_operator);
			if($jasa_tamu != 0)
                            $selisih = $jasa_tamu - $jasa_operator;
                        else
                            $selisih = 0;
                    }
                } else if ($dokter_operator == '5') {
                    if ($kelas == '1' || $kelas == '4') {
                        if ($alat_tamu == '1') {
                            $selisih = 5000000 - $jasa_operator;
                        } else {
                            $selisih = 4000000 - $jasa_operator;
                        }
                    } else if ($kelas == '2' || $kelas == '3') {
                        if ($alat_tamu == '1') {
                            $selisih = 3500000 - $jasa_operator;
                        } else {
                            $selisih = 2500000 - $jasa_operator;
                        }
                    }
                }
            }
            $biaya[] = array('dataValue' => $tarif, 'dataSelisih' => $selisih);

            return $this->jEncode($biaya);
        }
    }

    public function getDataListRuang($tipe_ruang, $rows, $offset) {
        $kondisi = "";

        if ($tipe_ruang != '') {
            $kondisi = " and id_tipe_ruang='" . $tipe_ruang . "'";
        } else {
            $kondisi = "";
        }

        $query = "select count(*) as jml from rm_ruang where del_flag<>'1' " . $kondisi;
        $result = $this->runQuery($query);

        $jmlData = mysql_result($result, 0, 'jml');

        $query = "select * from rm_ruang where del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $this->replaceString($rec['ruang'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
    }

    public function getDataListPasien(
    $nama_pasien, $alamat, $tgl_lahir, $tgl_lahir_to, $kecamatan, $kelurahan, $asuransi, $tipe_pasien, $rows, $offset
    ) {
        $kondisi = "";

        if ($nama_pasien != '')
            $kondisi .= " and nama_pasien like '" . @mysql_escape_string($nama_pasien) . "%'";
        if ($alamat != '')
            $kondisi .= " and alamat like '%" . $alamat . "%'";
        if ($tgl_lahir != '') {
            if ($tgl_lahir_to != '')
                $kondisi .= " and tgl_lahir between '" . $this->formatDateDb($tgl_lahir) . "' and '" . $this->formatDateDb($tgl_lahir_to) . "'";
            else
                $kondisi .= " and tgl_lahir='" . $this->formatDateDb($tgl_lahir) . "'";
        }
        if ($kecamatan != '')
            $kondisi .= " and id_kecamatan='" . $kecamatan . "'";
        if ($kelurahan != '')
            $kondisi .= " and id_kelurahan='" . $kelurahan . "'";
        if ($asuransi != '')
            $kondisi .= " and id_tipe_asuransi='" . $asuransi . "'";
        if ($tipe_pasien != '')
            $kondisi .= " and id_tipe_pasien='" . $tipe_pasien . "'";

        $query = "SELECT id_pasien, nama_pasien, id_kelamin, tmp_lahir, tgl_lahir, alamat, id_kecamatan, id_kelurahan, id_kota, id_marital, id_tipe_asuransi, id_tipe_pasien 
                  FROM rm_pasien use index (idx_pasien) where del_flag<>'1' " . $kondisi;
        $result = $this->runQuery($query);

        $jmlData = @mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['id_kelamin'] == '1')
                    $kel = "L";
                else
                    $kel = "P";
                if ($_SESSION['level'] != '1')
                    $kond_daftar = " AND id_ruang='" . $_SESSION['level'] . "'";
                else
                    $kond_daftar = "";

                $q_daftar = "SELECT MAX(id_pendaftaran) AS no_pendaftaran FROM rm_pendaftaran WHERE id_pasien='" . $rec['id_pasien'] . "' AND status_pendaftaran!='2'" . $kond_daftar;
                $r_daftar = $this->runQuery($q_daftar);
                $no_daftar = @mysql_result($r_daftar, 0, 'no_pendaftaran');
                $arr[] = array(
                    'no_pendaftaran' => $no_daftar,
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'jns_kelamin' => $kel,
                    'tmp_lahir' => $rec['tmp_lahir'],
                    'tgl_lahir' => $this->codeDate($rec['tgl_lahir']),
                    'alamat' => $rec['alamat'],
                    'kelurahan' => $this->getKelurahan($rec['id_kelurahan']),
                    'kecamatan' => $this->getKecamatan($rec['id_kecamatan']),
                    'kota' => $this->getKota($rec['id_kota']),
                    'marital' => $this->getMarital($rec['id_marital']),
                    'asuransi' => $this->getTipeAsuransi($rec['id_tipe_asuransi']),
                    'tipe_pasien' => $this->getTipePasien($rec['id_tipe_pasien'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function getListRadiologi($params) {
        $query = "select * from rm_radiologi where del_flag<>'1' and radiologi like '" . @mysql_escape_string($params) . "%'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['radiologi'];
                $dataList[] = '<li id="' . $row['id_radiologi'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getListKelompokLab($params) {
        $query = "select * from rm_kelompok_lab where del_flag<>'1' and kelompok_lab like '" . @mysql_escape_string($params) . "%'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['kelompok_lab'];
                $dataList[] = '<li id="' . $row['id_kelompok_lab'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getListPenyakit($params) {
        $query = "select * from rm_penyakit where del_flag<>'1' and nama_penyakit like '" . @mysql_escape_string($params) . "%'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['nama_penyakit'];
                $dataList[] = '<li id="' . $row['id_penyakit'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function listDiagnosa() {
        $combo = new ComboConnector($this->koneksine());
        //$combo->enable_log("temp.log");
        $combo->render_sql("SELECT * from rm_penyakit where del_flag<>'1' ORDER BY nama_penyakit ASC", "id_penyakit", "nama_penyakit");
    }

    public function getTindakanPoli($params) {
//        //MODIF
//        if ($_SESSION['level'] == 20 || $_SESSION['level'] == 31 || $_SESSION['level'] == 32) {
//            //ASLI
//            $query = "select a.id_detail_tindakan, b.tindakan from rm_detail_tindakan a, rm_tindakan b where b.del_flag<>'1' and a.del_flag<>'1' and b.tindakan like '" . @mysql_escape_string($params) . "%' and a.id_tindakan=b.id_tindakan and a.id_ruang='" . $_SESSION['level'] . "' and b.id_jenis_tindakan='1'";
//            //MODIF
//        } else {
//            $query = "select a.id_detail_tindakan, b.tindakan from rm_detail_tindakan a, rm_tindakan b where b.del_flag<>'1' and a.del_flag<>'1' and b.tindakan like '" . @mysql_escape_string($params) . "%' and a.id_tindakan=b.id_tindakan and a.id_ruang='0' and b.id_jenis_tindakan='1'";
//        }
//        //END MODIF
        if ($_SESSION['level'] == 20 || $_SESSION['level'] == 31 || $_SESSION['level'] == 32) {
            $ruang = $_SESSION['level'];
        } else {
            $ruang = 0;
        }
        $query = "CALL tindakanPoli('" . @mysql_escape_string($params) . "%',$ruang)";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['tindakan'];
                $dataList[] = '<li id="' . $row['id_detail_tindakan'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function dRadiologi() {
        $combo = new ComboConnector($this->koneksine());
        //$combo->enable_log("temp.log");
        $combo->render_sql("SELECT * FROM rm_radiologi WHERE del_flag<>'1' ORDER BY radiologi", "id_radiologi", "radiologi");
    }

    public function dObat() {
        $combo = new ComboConnector($this->koneksine());
        //$combo->enable_log("temp.log");
        $combo->render_sql("SELECT a.id_obat, CONCAT (a.nama_obat,' | ',b.stock) as nama_obat FROM rm_obat a, rm_stock_obat_apotik b WHERE b.id_obat=a.id_obat and b.id_ruang='" . $_SESSION['level'] . "' and a.del_flag<>'1' ORDER BY nama_obat", "id_obat", "nama_obat");
    }
    
    public function dObat2() {
        $combo = new ComboConnector($this->koneksine());
        //$combo->enable_log("temp.log");
        $combo->render_sql("SELECT a.kode_obat, a.nama_obat FROM rm_obat a WHERE a.del_flag<>'1' ORDER BY nama_obat", "kode_obat", "nama_obat");
    }

    public function dTindakan() {
        if (isset($_SESSION['level'])) {
            if ($_SESSION['level'] == 20 || $_SESSION['level'] == 31 || $_SESSION['level'] == 32 || $_SESSION['level'] == 19 || $_SESSION['level'] == 23 || $_SESSION['level'] == 55) {
                $ruang = $_SESSION['level'];
            } else {
                $ruang = 0;
            }
            $combo = new ComboConnector($this->koneksine());
            //$combo->enable_log("temp.log");
            $combo->render_sql("SELECT a.id_detail_tindakan, b.tindakan from rm_detail_tindakan a, rm_tindakan b where b.del_flag<>'1' and a.del_flag<>'1' and a.id_tindakan=b.id_tindakan and a.id_ruang='" . $ruang . "' and b.id_jenis_tindakan='1' ORDER BY b.tindakan ASC", "id_detail_tindakan", "tindakan");
        }
    }

    public function getPasienAlamat($params) {
        $query = "select a.nama_pasien, a.alamat from rm_pasien a where a.del_flag<>'1' and a.nama_pasien like '" . @mysql_escape_string($params) . "%' ORDER BY a.nama_pasien";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['nama_pasien'] . " | " . $row['alamat'];
                $dataList[] = '<li id="' . $row['nama_pasien'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getTindakanMedis($params) {
        $query = "select id_tindakan_medis, tindakan_medis from rm_tindakan_medis where del_flag<>'1' and tindakan_medis like '" . @mysql_escape_string($params) . "%'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['tindakan_medis'];
                $dataList[] = '<li id="' . $row['id_tindakan_medis'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getFasilitasPoli($params) {
        //ASLI
        //$query = "select a.id_detail_tindakan, b.tindakan from rm_detail_tindakan a, rm_tindakan b where b.del_flag<>'1' and a.del_flag<>'1' and b.tindakan like '" . $params . "%' and a.id_tindakan=b.id_tindakan and a.id_ruang='" . $_SESSION['level'] . "' and b.id_jenis_tindakan='2'";
        //MODIF
        $query = "select a.id_detail_tindakan, b.tindakan from rm_detail_tindakan a, rm_tindakan b where b.del_flag<>'1' and a.del_flag<>'1' and b.tindakan like '" . @mysql_escape_string($params) . "%' and a.id_tindakan=b.id_tindakan and a.id_ruang='0' and b.id_jenis_tindakan='2'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['tindakan'];
                $dataList[] = '<li id="' . $row['id_detail_tindakan'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function dFasilitas() {
        $combo = new ComboConnector($this->koneksine());
        //$combo->enable_log("temp.log");
        $combo->render_sql("SELECT a.id_detail_tindakan, b.tindakan from rm_detail_tindakan a, rm_tindakan b where b.del_flag<>'1' and a.del_flag<>'1' and a.id_tindakan=b.id_tindakan and a.id_ruang='0' and b.id_jenis_tindakan='2' ORDER BY b.tindakan ASC", "id_detail_tindakan", "tindakan");
    }

    public function getBahan($params) {
        $query = "select id_barang, barang from rm_barang where del_flag<>'1' and barang like '" . @mysql_escape_string($params) . "%' and id_jenis_barang='2'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['barang'];
                $dataList[] = '<li id="' . $row['id_barang'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getBahanBal($params) {
        $query = "select b.id_barang, a.barang from rm_barang a, rm_stock_barang b where a.del_flag<>'1' and b.del_flag<>'1' and a.barang like '" . @mysql_escape_string($params) . "%' and a.id_barang=b.id_barang and b.id_ruang = '" . $_SESSION['level'] . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['barang'];
                $dataList[] = '<li id="' . $row['id_barang'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getObat($params) {
        $query = "SELECT a.id_obat, a.nama_obat, (b.stock_lama + b.stock_baru) as stock FROM rm_obat a, rm_stock_obat_apotik b
                  WHERE b.id_obat=a.id_obat and b.id_ruang='" . $_SESSION['level'] . "' and a.del_flag<>'1'
		  and nama_obat like '" . @mysql_escape_string($params) . "%' order by nama_obat";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['stock'] . " | " . $row['nama_obat'];
                $dataList[] = '<li id="' . $row['id_obat'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getObatBeli($params) {
        $query = "SELECT id_obat, nama_obat FROM rm_obat
                  WHERE nama_obat like '" . @mysql_escape_string($params) . "%' and del_flag<>1 order by nama_obat";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['nama_obat'];
                $dataList[] = '<li id="' . $row['id_obat'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getObatJual($params) {
        $query = "SELECT id_obat, kode_obat, nama_obat FROM rm_obat
                  WHERE nama_obat like '" . $params . "%' or kode_obat like '" . @mysql_escape_string($params) . "%' and del_flag<>1 order by kode_obat";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['kode_obat'] . " - " . $row['nama_obat'];
                $dataList[] = '<li id="' . $row['kode_obat'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getBarang($params) {
        $query = "SELECT barang, id_barang FROM rm_barang
                  WHERE barang like '" . $params . "%' order by barang";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dataList = array();

            while ($row = mysql_fetch_array($result)) {
                $toReturn = $row['barang'];
                $dataList[] = '<li id="' . $row['id_barang'] . '"><a href="#">' . $toReturn . '</a></li>';
            }

            if (count($dataList) >= 1) {
                $dataOutput = join("\n", $dataList);
                return $dataOutput;
            } else {
                return '<li><a href="#">No Results</a></li>';
            }
        }
    }

    public function getDetailPasienTagih($id_pasien) {

        $query = "select nama_pasien, tgl_lahir, id_kelamin, id_tipe_asuransi, id_tipe_pasien from rm_pasien 
                  where id_pasien='" . $id_pasien . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $id_pendaftaran = $this->getLastDaftarPasien($id_pasien);
            $return = array(
                "id_pendaftaran" => $id_pendaftaran,
                "id_pasien" => $id_pasien,
                "pasien" => @mysql_result($result, 0, "nama_pasien"),
                "usia" => $this->getUmur(@mysql_result($result, 0, "tgl_lahir")),
                "jns_kelamin" => $this->getKelamin(@mysql_result($result, 0, "id_kelamin")),
                "jns_pasien" => $this->getTipePasien(@mysql_result($result, 0, "id_tipe_pasien")),
                "tipe_asuransi" => @mysql_result($result, 0, "id_tipe_asuransi")
            );

            return $this->jEncode($return);
        }
    }

    public function getResumeTagihanPasien($id_pasien) {
        $id_pendaftaran = $this->getLastDaftarPasien($id_pasien);

        $bayar = 0;
        $jmlTarif = $this->getAllTagihanPasien($id_pasien);
        $diskon = $this->getAllDiskonPasien($id_pasien);
        $bayar = $this->getAllBayarPasien($id_pasien);

        $query = "select sum(asuransi) as asuransi from rm_tagihan_asuransi where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $asuransi = @mysql_result($result, 0, 'asuransi');
        if ($asuransi == "")
            $asuransi = 0;

        $arr = array(
            'id_pendaftaran' => $id_pendaftaran,
            'total' => "Rp. " . number_format($jmlTarif, 2, ',', '.'),
            'terbayar' => "Rp. " . number_format(($bayar + $asuransi), 2, ',', '.'),
            'diskon_all' => "Rp. " . number_format($diskon, 2, ',', '.'),
            //'asuransi' => $asuransi,
            'kurang_bayar' => "Rp. " . number_format(($jmlTarif - $bayar - $asuransi - $diskon), 2, ',', '.'),
            'kurang' => ($jmlTarif - $bayar - $asuransi - $diskon),
            'kurang_diskon' => ($jmlTarif - $bayar - $asuransi - $diskon)
        );


        return $this->jEncode($arr);
    }

    public function simpanDiskonDokter($id_pendaftaran, $id_pasien, $diskon, $level) {
        if (isset($_SESSION['id'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                if ($level == '1') {
                    $levelDiskon = 'Dokter';
                    $id_dokter = $_SESSION['id'];
                } else if ($level == '15') {
                    $levelDiskon = "Manajemen";
                    $id_dokter = "";
                } else {
                    $levelDiskon = '';
                    $id_dokter = "";
                }
                $query = "insert into rm_diskon_tindakan (
                        id_pendaftaran,
                        id_pasien,
                        id_dokter,
                        pemberi_diskon,
                        diskon
                    ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $id_dokter . "',
                        '" . $levelDiskon . "',
                        '" . $diskon . "'
                    )";
                $result = $this->runQuery($query);

                if ($result) {
                    return '1';
                } else {
                    return '0';
                }
            } else {
                return '2';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function getPendapatanBank($startHour, $endHour, $startDate, $endDate, $kasir) {
        $kondisi = "";
        $kondisi2 = "";
        if ($startDate != "") {
            if ($endDate != "") {
                if ($startHour != "") {
                    if ($endHour != "") {
                        $kondisi .= " and tgl_pembayaran between '" . $this->formatDateDb($startDate) . " " . $startHour . "' and '" . $this->formatDateDb($endDate) . " " . $endHour . "'";
                    } else {
                        $kondisi .= " and tgl_pembayaran between '" . $this->formatDateDb($startDate) . " " . $startHour . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    }
                }
            } else {
                if ($startHour != "") {
                    if ($endHour != "") {
                        $kondisi .= " and tgl_pembayaran between '" . $this->formatDateDb($startDate) . " " . $startHour . "' and '" . $this->formatDateDb($startDate) . " " . $endHour . "'";
                    } else {
                        $kondisi .= " and tgl_pembayaran between '" . $this->formatDateDb($startDate) . " " . $startHour . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                    }
                }
            }
        }

        if ($kasir != '')
            $kondisi .= " and a.level='" . $kasir . "' ";

        $query = "SELECT a.id_pasien, b.nama_pasien, date(a.tgl_pembayaran) as tgl_pembayaran, sum(a.bayar) as bayar, sum(a.administrasi) as administrasi 
                  FROM rm_pembayaran_tagihan a, rm_pasien b WHERE a.del_flag<>1 AND a.id_pasien=b.id_pasien AND a.bayar<>0 " . $kondisi . $kondisi2 . "GROUP BY a.id_pasien";
        $result = $this->runQuery($query);

        $query2 = "SELECT b.id_pasien, b.nama_pasien, date(a.tgl_pembayaran) as tgl_pembayaran, sum(a.bayar) as bayar, sum(a.administrasi) as administrasi 
                  FROM rm_pembayaran_obat a, rm_faktur_penjualan b WHERE a.del_flag<>1 AND b.del_flag<>1 AND a.id_faktur_penjualan=b.id_faktur_penjualan 
                  AND a.bayar<>0 AND a.auto=0 " . $kondisi . "GROUP BY b.id_faktur_penjualan";
        $result2 = $this->runQuery($query2);

        if (@mysql_num_rows($result) > 0 || @mysql_num_rows($result2) > 0) {
            $html = "";
            $html .= "<html><table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>LAPORAN PENDAPATAN TANGGAL " . $startDate . " s.d. " . $endDate . "</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>TANGGAL " . $startDate . " s.d. " . $endDate . "</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>JAM " . $startHour . " s.d. " . $endHour . "</b></u><br><br></td>
                        </tr>";
            $html .= "<table class='data' width='100%'>
                          <thead>
                              <tr>
                                <td class='headerTagihan'>No.</td>
                                <td class='headerTagihan'>RM Px.</td>
                                <td class='headerTagihan'>Nama Px.</td>
                                <td class='headerTagihan'>Tanggal Pembayaran</td>
                                <td class='headerTagihan'>Jumlah</td>
                                <td class='headerTagihan'>Administrasi</td>
                                <td class='headerTagihan'>Total</td>
                              </tr>
                          </thead>
                          <tbody>";
            $i = 1;
            $jumNet = 0;
            $jumAdm = 0;
            $jumBayar = 0;
            while ($data = @mysql_fetch_array($result)) {
                $html .= "<tr>";
                $html .= "<td>" . $i . "</td>";
                $html .= "<td>" . $data['id_pasien'] . "</td>";
                $html .= "<td>" . $data['nama_pasien'] . "</td>";
                $html .= "<td>" . $this->formatDateDb($data['tgl_pembayaran']) . "</td>";
                $html .= "<td align='right'>" . number_format($data['bayar'] - $data['administrasi'], 2) . "</td>";
                $html .= "<td align='right'>" . number_format($data['administrasi'], 2) . "</td>";
                $html .= "<td align='right'>" . number_format($data['bayar'], 2) . "</td>";
                $html .= "</tr>";
                $i++;
                $jumNet += $data['bayar'] - $data['administrasi'];
                $jumAdm += $data['administrasi'];
                $jumBayar += $data['bayar'];
            }
            $jumNet2 = 0;
            $jumAdm2 = 0;
            $jumBayar2 = 0;
            while ($data2 = @mysql_fetch_array($result2)) {
                $html .= "<tr>";
                $html .= "<td>" . $i . "</td>";
                $html .= "<td>" . $data2['id_pasien'] . "</td>";
                $html .= "<td>" . $data2['nama_pasien'] . "</td>";
                $html .= "<td>" . $this->formatDateDb($data2['tgl_pembayaran']) . "</td>";
                $html .= "<td align='right'>" . number_format($data2['bayar'] - $data['administrasi'], 2) . "</td>";
                $html .= "<td align='right'>" . number_format($data2['administrasi'], 2) . "</td>";
                $html .= "<td align='right'>" . number_format($data2['bayar'] + $data2['administrasi'], 2) . "</td>";
                $html .= "</tr>";
                $i++;
                $jumNet2 += $data2['bayar'] - $data2['administrasi'];
                $jumAdm2 += $data2['administrasi'];
                $jumBayar2 += $data2['bayar'] + $data2['administrasi'];
            }
            $html .= "</tbody><tr>";
            $html .= "<td colspan='4' class='total'>TOTAL</td>";
            $html .= "<td align='right' class='total'>" . number_format($jumNet + $jumNet2, 2) . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($jumAdm + $jumAdm2, 2) . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($jumBayar + $jumBayar2, 2) . "</td>";
            $html .= "</tr></table></html>";
        } else {
            $html = 'Data Kosong';
        }
        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getTagihanPasien($id_pendaftaran, $id_pasien) {
	if($id_pasien > 0){
        if ($id_pendaftaran == "")
            $id_pendaftaran = $this->getLastDaftarPasien($id_pasien);
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>LAPORAN TAGIHAN PASIEN</b></u><br><br></td>
                        </tr>";
        $html .="</table>";
        $html .= "<table class='data' width='100%'>
                <tr height='17'>
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'>: <b>" . $id_pasien . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height='17'>
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'>: <b>" . $this->getPasienNama($id_pasien) . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'>: <b>" . $this->getKelaminPasien($id_pasien) . "</td>
                </tr>
                <tr height='17'>
                    <td width='19%'>Alamat</td>
                    <td width='30%'>: <b>" . $this->getAlamatPasien($id_pasien) . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Umur</td>
                    <td width='30%'>: <b>" . $this->getUmur($this->getPasienLahir($id_pasien)) . "</b></td>
                </tr>
            </table>";

        if ($id_pendaftaran != "") {
            $html .= "<hr>";
            //$html .= "<b>PENGGUNAAN KAMAR</b>";
            $html .= $this->getTagihanKamar($id_pasien);
//            $html .= "<br>";
//            $html .= "<b>VISIT/PEMERIKSAAN DOKTER</b>";
            $html .= $this->getTagihanVisit($id_pasien);
//            $html .= "<br>";
//            $html .= "<b>JASA MEDIS / FASILITAS</b>";
            $html .= $this->getTagihanJasaMedis($id_pasien);
//            $html .= "<br>";
//            $html .= "<b>JASA PENUNJANG MEDIS</b>";
            $html .= $this->getTagihanJasaPenunjangMedis($id_pasien);
//            $html .= "<br>";
//            $html .= "<b>TINDAKAN RUANG</b>";
            $html .= $this->getTagihanTindakanMedis($id_pasien);
//            $html .= "<br>";
//            $html .= "<b>TINDAKAN BEDAH</b>";
            $html .= $this->getTagihanTindakanBedah($id_pasien);
//            $html .= "<br>";
//            $html .= "<b>DISKON</b>";
            $html .= $this->getDiskonTagihan($id_pasien);
            $html .= $this->getAllKarcisDaftar($id_pasien);

            $q_tagihan = "SELECT id_faktur_penjualan FROM rm_faktur_penjualan WHERE id_pasien='" . $id_pasien ."' 
                          AND `status`=1 AND del_flag<>1";
            $r_tagihan = $this->runQuery($q_tagihan);
//            $rt = "SELECT b.id_retur faktur, date(b.tgl_retur) as tgl, SUM(((b.jumlah * c.harga) * (1 - b.pros_retur)) * (-1)) as jumlah
//                   FROM rm_faktur_penjualan AS a INNER JOIN rm_retur_penjualan_obat AS b ON a.id_faktur_penjualan = b.id_faktur_penjualan 
//                   INNER JOIN rm_penjualan_obat AS c ON b.id_faktur_penjualan = c.id_faktur_penjualan AND b.id_penjualan_obat = c.id_penjualan_obat
//                   INNER JOIN rm_pendaftaran d ON (d.id_pasien = a.id_pasien AND a.id_pasien = " . $id_pasien . " AND d.status_pembayaran<>2 
//                   AND a.id_pendaftaran=d.id_pendaftaran AND d.del_flag<>1) WHERE b.del_flag <> 1 AND c.del_flag <> 1 AND 
//                   a.del_flag <> 1 AND a.asuransi=0 AND a.jns_customer='Pasien' AND b.jns_retur<>0 AND date(b.tgl_retur) >= '2012-02-16' GROUP BY b.id_retur";
//            $r_rt = $this->runQuery($rt);
            if (@mysql_num_rows($r_tagihan) > 0/* || @mysql_num_rows($r_rt) > 0*/) {
                $html .= "<br><br>";
                $html .= "<b>Tagihan Obat</b><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                      <tr>
                            <td width='5%' align='center' bgcolor='#FFFFFF'>No</td>
                            <td width='25%' align='center' bgcolor='#FFFFFF'>No Faktur</td>
                            <td width='25%' align='center' bgcolor='#FFFFFF'>Tanggal Faktur</td>
                            <td width='45%' align='center' bgcolor='#FFFFFF'>Jumlah</td>
                      </tr>";
                $k = 1;
                $totTagihan = 0;
                while ($rec_tagihan = @mysql_fetch_array($r_tagihan)) {
                    $q_t_ret = "SELECT b.id_faktur_penjualan faktur, date(b.tgl_penjualan) tgl, SUM(a.qty * round(a.harga)) jumlah
                                FROM rm_penjualan_obat AS a INNER JOIN rm_faktur_penjualan AS b ON a.id_faktur_penjualan = b.id_faktur_penjualan
                                WHERE a.del_flag <> 1 AND b.del_flag <> 1 AND (b.`status` = 1) AND b.id_faktur_penjualan='" . $rec_tagihan['id_faktur_penjualan'] . "' GROUP BY a.id_faktur_penjualan UNION
                                SELECT b.id_retur faktur, date(b.tgl_retur) as tgl, ROUND(SUM(((b.jumlah * c.harga) * (1 - b.pros_retur)) * (-1))) as jumlah
                                FROM rm_faktur_penjualan AS a INNER JOIN rm_retur_penjualan_obat AS b ON a.id_faktur_penjualan = b.id_faktur_penjualan 
                                INNER JOIN rm_penjualan_obat AS c ON b.id_faktur_penjualan = c.id_faktur_penjualan AND b.id_penjualan_obat = c.id_penjualan_obat
                                WHERE b.del_flag <> 1 AND b.jns_retur = 0 AND c.del_flag <> 1 AND a.del_flag <> 1 AND c.id_faktur_penjualan='" . $rec_tagihan['id_faktur_penjualan'] . "' GROUP BY b.id_retur";
                    $r_t_ret = $this->runQuery($q_t_ret);
                    $total = 0;
                    $retur = 0;
                    $diskon = 0;
                    while ($rec = mysql_fetch_array($r_t_ret)) {
                        $q_diskon = "SELECT diskon FROM rm_diskon_obat WHERE id_faktur = " . $rec['faktur'] . " and del_flag<>1 AND `status`=1";
                        $r_diskon = $this->runQuery($q_diskon);
                        $diskon = @mysql_result($r_diskon, 0, 'diskon');
                        $totale += $rec['jumlah'];
                        $diskonA += $diskon;
                        $html .= "<tr>
                            <td width='5%' align='center' bgcolor='#FFFFFF'>" . $k . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $rec['faktur'] . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $this->codeDate($rec['tgl']) . "</td>
                            <td width='45%' align='right' bgcolor='#FFFFFF'>" . number_format($rec['jumlah'] - $diskon, 2, ',', '.') . "</td>
                      </tr>";
                        $k++;
                    }
                }
                while ($rtt = mysql_fetch_array($r_rt)) {
                    $totale += $rtt['jumlah'];
                    $html .= "<tr>
                            <td width='5%' align='center' bgcolor='#FFFFFF'>" . $k . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $rtt['faktur'] . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $this->codeDate($rtt['tgl']) . "</td>
                            <td width='45%' align='right' bgcolor='#FFFFFF'>" . number_format($rtt['jumlah'], 2, ',', '.') . "</td>
                      </tr>";
                    $k++;
                }
                $html .= "<tr>
                        <td width='5%' align='center' bgcolor='#FFFFFF' colspan='3'><b>Sub Total</b></td>
                        <td width='45%' align='right' bgcolor='#FFFFFF'><b>" . number_format($totale - $diskonA, 2, ',', '.') . "</b></td>
                  </tr></table>";
            }
            $html .= "<br>";
            $html .= $this->getTotalTagihanPasien($id_pasien);

            $jmlTarif = $this->getAllTagihanPasien($id_pasien);
            $jmlObat = $this->getAllTagihanObat($id_pasien);
            $diskon = $this->getAllDiskonPasien($id_pasien);
            $bayar = $this->getAllBayarPasien($id_pasien);
            $asuransi = $this->getAllAsuransiPasien($id_pasien);
            //$sisa = $jmlTarif - $jmlObat - $diskon - $bayar - $asuransi;
            $sisa = round((round(($jmlTarif - $bayar), 2) - $diskon), 2) - round($asuransi, 2);
            if ($sisa == 0)
                $status = 0;
            else
                $status = 1;
        } else {
            $html = "Data tidak ditemukan/Tidak ada tagihan untuk pasien ini.";
            $status = 2;
        }
	} else {
		$html = "Data tidak ditemukan/Tidak ada tagihan untuk pasien ini.";
	        $status = 2;
	}
        $arr[] = array('display' => $html, 'status' => $status);

        if ($arr) {
            $halaman = $this->jEncode($arr);
            if ($status != 2) {
                $cek_cetak = "SELECT id_pendaftaran FROM rm_cetakan where id_pendaftaran='" . $id_pendaftaran . "'";
                $run_cek = $this->runQuery($cek_cetak);
                if (@mysql_num_rows($run_cek) > 0) {
                    $cetakan = "UPDATE rm_cetakan set detail='" . @mysql_escape_string($halaman) . "' WHERE id_pendaftaran='" . $id_pendaftaran . "'";
                } else {
                    $cetakan = "insert into rm_cetakan (id_pendaftaran,detail) values ('" . $id_pendaftaran . "','" . @mysql_escape_string($halaman) . "')";
        }
                $r_cetak = $this->runQuery($cetakan);
    }
            return $halaman;
        }
    }

    public function getTagihanPasienKeluarOld($id_pendaftaran) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>LAPORAN TAGIHAN PASIEN</b></u><br><br></td>
                        </tr>";
        $html .="</table>";
        $html .= "<table class='data' width='100%'>
                <tr height='17'>
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'>: <b>" . $id_pasien . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>&nbsp;</td>
                    <td width='30%'>&nbsp;</td>
                </tr>
                <tr height='17'>
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'>: <b>" . $this->getPasienNama($id_pasien) . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'>: <b>" . $this->getKelaminPasien($id_pasien) . "</td>
                </tr>
                <tr height='17'>
                    <td width='19%'>Alamat</td>
                    <td width='30%'>: <b>" . $this->getAlamatPasien($id_pasien) . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Umur</td>
                    <td width='30%'>: <b>" . $this->getUmur($this->getPasienLahir($id_pasien)) . "</b></td>
                </tr>
            </table>";

        if ($id_pendaftaran != "") {
            $html .= "<hr>";
            $html .= $this->getTagihanKamarKeluar($id_pendaftaran);
            $html .= $this->getTagihanVisitKeluar($id_pendaftaran);
            $html .= $this->getTagihanJasaMedisKeluar($id_pendaftaran);
            $html .= $this->getTagihanJasaPenunjangMedisKeluar($id_pendaftaran);
            $html .= $this->getTagihanTindakanMedisKeluar($id_pendaftaran);
            $html .= $this->getTagihanTindakanBedahKeluar($id_pendaftaran);
            $html .= $this->getDiskonTagihanKeluar($id_pendaftaran);
            $html .= $this->getAllKarcisDaftarKeluar($id_pendaftaran);

            $q_tagihan = "SELECT b.id_faktur_penjualan FROM rm_faktur_penjualan a, rm_pembayaran_obat b WHERE a.id_pendaftaran=" . $id_pendaftaran . " 
                      AND a.del_flag<>'1' AND b.del_flag<>1 AND a.id_faktur_penjualan = b.id_faktur_penjualan AND b.tipe_pembayaran = 'Kredit'
                      UNION SELECT b.id_faktur_penjualan FROM rm_faktur_penjualan a, rm_pembayaran_obat b WHERE a.id_pendaftaran=
                      (SELECT max(id_pendaftaran) FROM rm_pendaftaran where id_pasien = (select id_pasien from rm_pendaftaran where 
                      id_pendaftaran=" . $id_pendaftaran . ") and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))<=0 
                     and DATEDIFF(date(tgl_pendaftaran),(select date(tgl_pendaftaran) from rm_pendaftaran where id_pendaftaran=" . $id_pendaftaran . "))>=-1 and id_ruang=20) AND a.del_flag<>'1' 
                      AND b.del_flag<>1 AND a.id_faktur_penjualan = b.id_faktur_penjualan AND b.tipe_pembayaran = 'Kredit'";
            $r_tagihan = $this->runQuery($q_tagihan);
            if (@mysql_num_rows($r_tagihan) > 0) {
                $html .= "<br><br>";
                $html .= "<b>Tagihan Obat</b><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                      <tr>
                            <td width='5%' align='center' bgcolor='#FFFFFF'>No</td>
                            <td width='25%' align='center' bgcolor='#FFFFFF'>No Faktur</td>
                            <td width='25%' align='center' bgcolor='#FFFFFF'>Tanggal Faktur</td>
                            <td width='45%' align='center' bgcolor='#FFFFFF'>Jumlah</td>
                      </tr>";
                $k = 1;
                $totTagihan = 0;
                while ($rec_tagihan = @mysql_fetch_array($r_tagihan)) {
                    $q_t_ret = "SELECT b.id_faktur_penjualan faktur, date(b.tgl_penjualan) tgl, SUM(a.qty * a.harga) jumlah
                                FROM rm_penjualan_obat AS a INNER JOIN rm_faktur_penjualan AS b ON a.id_faktur_penjualan = b.id_faktur_penjualan
                                WHERE a.del_flag <> 1 AND b.del_flag <> 1 AND b.id_faktur_penjualan='" . $rec_tagihan['id_faktur_penjualan'] . "' GROUP BY a.id_faktur_penjualan UNION
                                SELECT b.id_retur faktur, date(b.tgl_retur) as tgl, SUM(((b.jumlah * c.harga) * (1 - b.pros_retur)) * (-1)) as jumlah
                                FROM rm_faktur_penjualan AS a INNER JOIN rm_retur_penjualan_obat AS b ON a.id_faktur_penjualan = b.id_faktur_penjualan 
                                INNER JOIN rm_penjualan_obat AS c ON b.id_faktur_penjualan = c.id_faktur_penjualan AND b.id_penjualan_obat = c.id_penjualan_obat
                                WHERE b.del_flag <> 1 AND b.jns_retur = 0 AND c.del_flag <> 1 AND a.del_flag <> 1 AND c.id_faktur_penjualan='" . $rec_tagihan['id_faktur_penjualan'] . "' GROUP BY b.id_retur";
                    $r_t_ret = $this->runQuery($q_t_ret);
                    $total = 0;
                    $retur = 0;
                    while ($rec = mysql_fetch_array($r_t_ret)) {
                        $totale += $rec['jumlah'];
                        $html .= "<tr>
                            <td width='5%' align='center' bgcolor='#FFFFFF'>" . $k . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $rec['faktur'] . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $this->codeDate($rec['tgl']) . "</td>
                            <td width='45%' align='right' bgcolor='#FFFFFF'>" . number_format($rec['jumlah'], 2, ',', '.') . "</td>
                      </tr>";
                        $k++;
                    }
                }
                $html .= "<tr>
                        <td width='5%' align='center' bgcolor='#FFFFFF' colspan='3'><b>Sub Total</b></td>
                        <td width='45%' align='right' bgcolor='#FFFFFF'><b>" . number_format($totale, 2, ',', '.') . "</b></td>
                  </tr></table>";
            }
            $html .= "<br>";
            $html .= $this->getTotalTagihanPasienKeluar($id_pendaftaran);

            $jmlTarif = $this->getAllTagihanPasienKeluar($id_pendaftaran);
            $jmlObat = $this->getAllTagihanObatKeluar($id_pendaftaran);
            $diskon = $this->getAllDiskonPasienKeluar($id_pendaftaran);
            $bayar = $this->getAllBayarPasienKeluar($id_pasien);
            $asuransi = $this->getAllAsuransiPasienKeluar($id_pasien);
            $sisa = $jmlTarif - $diskon - $bayar - $asuransi;
            if ($sisa == 0)
                $status = 0;
            else
                $status = 1;
        } else {
            $html = "Data tidak ditemukan/Tidak ada tagihan untuk pasien ini.";
            $status = 2;
        }

        $arr[] = array('display' => $html, 'status' => $status);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getTagihanPasienKeluar($id_pendaftaran) {
        $query = "SELECT detail from rm_cetakan where id_pendaftaran = '" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0)
            $halaman = @mysql_result($result, 0, 'detail');
        else
            echo $this->getTagihanPasienKeluarOld($id_pendaftaran);
        return $halaman;
    }

    public function getTagihanPasienBanding($id_pendaftaran, $id_pasien, $id_kelas) {
        if ($id_pendaftaran == "")
            $id_pendaftaran = $this->getLastDaftarPasien($id_pasien);

        if ($id_pendaftaran != "") {
            $html = "<hr>";
            $html .= "<b>PENGGUNAAN KAMAR</b>";
            $html .= $this->getTagihanKamarBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<b>VISIT/PEMERIKSAAN DOKTER</b>";
            $html .= $this->getTagihanVisitBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<b>JASA MEDIS / FASILITAS</b>";
            $html .= $this->getTagihanJasaMedisBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<b>JASA PENUNJANG MEDIS</b>";
            $html .= $this->getTagihanJasaPenunjangMedisBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<b>TINDAKAN RUANG</b>";
            $html .= $this->getTagihanTindakanMedisBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<b>TINDAKAN BEDAH</b>";
            $html .= $this->getTagihanTindakanBedahBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= $this->getTotalTagihanPasienBanding($id_pasien, $id_kelas);

            $status = 1;
        } else {
            $html = "Data tidak ditemukan/Tidak ada tagihan untuk pasien ini.";
            $status = 2;
        }

        $arr[] = array('display' => $html, 'status' => $status);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function simpanPembayaranTagihan($id_pendaftaran, $id_pasien, $status, $asuransi, $bayar) {
        if (isset($_SESSION['nip'])) {
            $tipe_pendaftaran = $this->getIdTipePendaftaran($id_pendaftaran);
            $valid = true;
            $tipe_pasien = $this->getTipePasienId($id_pasien);

            if ($this->getTipePendaftaran($id_pendaftaran) == '6') {
                $q_jml = "select count(*) as jml from rm_pembayaran_tagihan where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
                $r_jml = $this->runQuery($q_jml);
                $ke = @mysql_result($r_jml, 0, 'jml') + 1;
                $query = "insert into rm_pembayaran_tagihan (
                                    id_pendaftaran,
                                    id_pasien,
                                    pembayaran_ke,
                                    level,
                                    bayar,
                                    id_tipe_pasien
                                 ) values (
                                    '" . $id_pendaftaran . "',
                                    '" . $id_pasien . "',
                                    '" . $ke . "',
                                    '" . $_SESSION['nip'] . "',
                                    '" . $bayar . "',
                                    '" . $tipe_pasien . "'
                                 )";

                $result = $this->runQuery($query);
                if ($result) {
                    $q_id_bayar = "select max(id_pembayaran_tagihan) as idAkhir from rm_pembayaran_tagihan where del_flag<>1";
                    $r_bayar = $this->runQuery($q_id_bayar);

                    if ($r_bayar) {
                        $q_cek_asuransi = "select * from rm_tagihan_asuransi where id_pendaftaran='" . $id_pendaftaran . "'";
                        $r_cek_asuransi = $this->runQuery($q_cek_asuransi);

                        if (@mysql_num_rows($r_cek_asuransi) > 0)
                            $q_asuransi = "update rm_tagihan_asuransi
                                                    set asuransi='" . (@mysql_result($r_cek_asuransi, 0, 'asuransi') + $asuransi) . "'
                                                  where id_pendaftaran='" . $id_pendaftaran . "'";
                        else
                            $q_asuransi = "insert into rm_tagihan_asuransi(
                                                    id_pendaftaran,
                                                    id_pasien,
                                                    level,
                                                    asuransi,
                                                    id_tipe_pasien
                                                  ) values (
                                                    '" . $id_pendaftaran . "',
                                                    '" . $id_pasien . "',
                                                    '" . $_SESSION['level'] . "',
                                                    '" . $asuransi . "',
                                                    '" . $tipe_pasien . "'
                                                  )";

                        $this->runQuery($q_asuransi);

                        if ($asuransi > 0)
                            $return = "SUCCESS:" . $status . " Asuransi:" . mysql_result($r_bayar, 0, 'idAkhir') . ":1:" . $bayar;
                        else
                            $return = "SUCCESS:" . $status . ":" . mysql_result($r_bayar, 0, 'idAkhir');
                    }
                } else {
                    $return = "FAILED";
                }
            } else {
                $q_jml = "select count(*) as jml from rm_pembayaran_tagihan where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
                $r_jml = $this->runQuery($q_jml);
                $ke = @mysql_result($r_jml, 0, 'jml') + 1;
                $query = "insert into rm_pembayaran_tagihan (
                                id_pendaftaran,
                                id_pasien,
                                pembayaran_ke,
                                level,
                                bayar,
                                id_tipe_pasien
                             ) values (
                                '" . $id_pendaftaran . "',
                                '" . $id_pasien . "',
                                '" . $ke . "',
                                '" . $_SESSION['nip'] . "',
                                '" . $bayar . "',
                                '" . $tipe_pasien . "'
                             )";

                $result = $this->runQuery($query);
                if ($result) {
                    $q_id_bayar = "select max(id_pembayaran_tagihan) as idAkhir from rm_pembayaran_tagihan where del_flag<>1";
                    $r_bayar = $this->runQuery($q_id_bayar);
                    if ($r_bayar) {
                        $q_cek_asuransi = "select * from rm_tagihan_asuransi where id_pendaftaran='" . $id_pendaftaran . "'";
                        $r_cek_asuransi = $this->runQuery($q_cek_asuransi);

                        if (@mysql_num_rows($r_cek_asuransi) > 0)
                            $q_asuransi = "update rm_tagihan_asuransi
                                                set asuransi='" . (@mysql_result($r_cek_asuransi, 0, 'asuransi') + $asuransi) . "'
                                              where id_pendaftaran='" . $id_pendaftaran . "'";
                        else
                            $q_asuransi = "insert into rm_tagihan_asuransi(
                                                id_pendaftaran,
                                                id_pasien,
                                                level,
                                                asuransi,
                                                id_tipe_pasien
                                              ) values (
                                                '" . $id_pendaftaran . "',
                                                '" . $id_pasien . "',
                                                '" . $_SESSION['nip'] . "',
                                                '" . $asuransi . "',
                                                '" . $tipe_pasien . "'
                                              )";

                        $this->runQuery($q_asuransi);

                        if ($asuransi > 0)
                            $return = "SUCCESS:" . $status . " Asuransi:" . mysql_result($r_bayar, 0, 'idAkhir') . ":1:" . $bayar;
                        else
                            $return = "SUCCESS:" . $status . ":" . mysql_result($r_bayar, 0, 'idAkhir');
                    }
                } else {
                    $return = "FAILED";
                }
            }

            return $return;
        }
        return 'LOGIN';
    }

    public function cetakKwitansiTagihan($id_pembayaran) {
        $query = "select * from rm_pembayaran_tagihan where id_pembayaran_tagihan='" . $id_pembayaran . "' and del_flag<>1";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $date = explode(' ', @mysql_result($result, 0, 'tgl_pembayaran'));
            $tanggal = $this->codeDate($date[0]);
            $nama = $this->getPasienNama(@mysql_result($result, 0, 'id_pasien'));
            $file = fopen("../report/cetakKwitansiTagihan.html", 'w');
            fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
            fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
            fwrite($file, "<div class='printArea'>");
            $nama_kasir = $_SESSION['nama_pegawai'];
            $biaya = $this->getAllTagihanPasien(@mysql_result($result, 0, 'id_pasien'));
            $terbayar = $this->getAllBayarPasien(@mysql_result($result, 0, 'id_pasien'));
            $diskon = $this->getAllDiskonPasien(@mysql_result($result, 0, 'id_pasien'));
            $asuransi = $this->getAsuransiTagihan(@mysql_result($result, 0, 'id_pendaftaran'));
            $kurang = ($biaya - $terbayar - $diskon - $asuransi);
            $admins = $this->getAdminBank($id_pembayaran);

            $html = "<table class='data' cellspacing='0' cellpadding='0' width='100%'>
                            <tr height='21'>
                            <td height='21' colspan='5'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                            <td height='21' colspan='5'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                            <td height='21' colspan='5' align='center'><u><b>KWITANSI PEMBAYARAN</b></u><br><br></td>
                            </tr>
                            <tr height='21'>
                            <td width='45' height='21' align='center' class='headerTagihan'>No.</td>
                            <td width='137' class='headerTagihan'>Tanggal</td>
                            <td width='215' class='headerTagihan'>Terima dari</td>
                            <td width='289' class='headerTagihan'>Keterangan</td>
                            <td width='153' class='headerTagihan'>Jumlah</td>
                            </tr>
                            <tr height='20'>
                            <td height='20' align='center'>1</td>
                            <td>" . $tanggal . "</td>
                            <td>$nama</td>
                            <td>Pembayaran Tagihan No. <b>" . @mysql_result($result, 0, 'id_pendaftaran') . "</b></td>
                            <td align='right'>Rp. " . number_format(@mysql_result($result, 0, 'bayar'), 2, ',', '.') . "</td>
                            </tr>
                            <tr height='20'>
                            <td height='20' align='center'></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr height='20'>
                            <td height='20' align='center'></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr height='20'>
                            <td colspan='4' align='right' class='total'>Total Biaya</td>
                            <td class='total' align='right'>Rp. " . number_format($biaya, 2, ',', '.') . "</td>
                            </tr>
                            <tr height='20'>
                            <td colspan='4' align='right'>Admin Bank</td>
                            <td align='right'>Rp. " . number_format($admins, 2, ',', '.') . "</td>
                            </tr>
                            <tr height='20'>
                            <td colspan='4' align='right'>Total Diskon</td>
                            <td align='right'>Rp. " . number_format($diskon, 2, ',', '.') . "</td>
                            </tr>
                            <tr height='20'>
                            <td colspan='4' align='right'>Terbayar</td>
                            <td align='right'>Rp. " . number_format($terbayar, 2, ',', '.') . "</td>
                            </tr>
                            <tr height='20'>
                            <td colspan='4' align='right'>ASURANSI</td>
                            <td align='right'>Rp. " . number_format($asuransi, 2, ',', '.') . "</td>
                            </tr>
                            <tr height='20'>
                            <td colspan='4' align='right' class='total'>Kekurangan</td>
                            <td align='right' class='total'>Rp. " . number_format($kurang, 2, ',', '.') . "</td>
                            </tr>
                            <tr height='20'>
                            <td height='20' align='center'></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr height='20'>
                            <td height='20' colspan='5'>Jumlah Uang : <b>" . $this->pembilang(round(@mysql_result($result, 0, 'bayar'))) . " Rupiah</b></td>
                            </tr>
                            <tr height='20'>
                            <td height='20' align='center'></td>
                            <td></td>
                            <td></td>
                            <td colspan='2' align='center'>
                            Kasir<br><br><br><br><b><u>" . $nama_kasir . "</u></b>
                            </td>
                            </tr></table>";
            fwrite($file, $html);
            fwrite($file, "</div></body></html>");
            fclose($file);

            return '1';
        } else {
            return '0';
        }
    }

    public function cetakLaporanTagihanRawatJalan(
    $tipe_pasien, $status, $startDate, $endDate
    ) {
        $kondisi = "";
        if ($status != "A") {
            if ($status == "L")
                $kondisi .= " and a.status_pembayaran='2'";
            else if ($status == "B")
                $kondisi .= " and a.status_pembayaran!='2'";
        }
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $q_ruang = "SELECT DISTINCT(a.id_ruang) AS id_ruang, d.ruang FROM rm_pendaftaran a, rm_pasien b, rm_ruang d
                    WHERE a.id_tipe_pendaftaran!='6' AND a.id_asal_pendaftaran='0' AND b.id_pasien=a.id_pasien AND d.id_ruang=a.id_ruang
                    AND b.id_tipe_pasien='" . $tipe_pasien . "' " . $kondisi . " ORDER BY a.id_ruang";
        $r_ruang = $this->runQuery($q_ruang);
        if (@mysql_num_rows($r_ruang) > 0) {
            $file = fopen("../report/cetakLaporanTagihanRawatJalan.html", 'w');
            fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
            fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
            fwrite($file, "<div class='printArea'>");
            $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>LAPORAN PENDAPATAN RUMAH SAKIT RAWAT JALAN</b></u><br>
                                Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate)) . "
                                <br></td>
                            </tr>";
            $html .="</table>";
            $html .="<br>Jenis Pasien : " . $this->getTipePasien($tipe_pasien);
            $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='2%' class='headerTagihan'>No</td>";
            $html .= "<td width='10%' class='headerTagihan'>No RM</td>";
            $html .= "<td width='10%' class='headerTagihan'>Nama PX</td>";
            $html .= "<td width='10%' class='headerTagihan'>Karcis</td>";
            $html .= "<td width='10%' class='headerTagihan'>Fasilitas</td>";
            $html .= "<td width='10%' class='headerTagihan'>Laborat</td>";
            $html .= "<td width='10%' class='headerTagihan'>Radiologi</td>";
            $html .= "<td width='10%' class='headerTagihan'>PA</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tindakan Poli</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tindakan IBS</td>";
            $html .= "<td width='10%' class='headerTagihan'>Biaya Obat</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='10%' class='headerTagihan'>Terbayar</td>";
            $html .= "<td width='10%' class='headerTagihan'>Terhutang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
            $html .= "<td width='10%' class='headerTagihan'>Asuransi</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $totAllKarcis = 0;
            $totAllSewa = 0;
            $totAllFasilitas = 0;
            $totAllLaborat = 0;
            $totAllRadiologi = 0;
            $totAllTindakanPoli = 0;
            $totAllTindakanPA = 0;
            $totAllTindakanBedah = 0;
            $totAllVisit = 0;
            $totAllObat = 0;
            $totAllBiaya = 0;
            $totAllBayar = 0;
            $totAllAsuransi = 0;
            $totAllDiskon = 0;
            $obatPasien = array();
            while ($ruang = @mysql_fetch_array($r_ruang)) {
                $html .= "<tr>";
                $html .= "<td colspan='16' class='total'><b> Ruang " . $ruang['ruang'] . "</b></td>";
                $html .= "</tr>";
                $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, d.ruang, date(tgl_pendaftaran) as masuk FROM rm_pendaftaran a, rm_pasien b, rm_ruang d
                          WHERE b.id_pasien=a.id_pasien and a.id_ruang='" . $ruang['id_ruang'] . "' AND a.id_tipe_pendaftaran!='6' and a.id_asal_pendaftaran='0'
                          AND d.id_ruang=a.id_ruang AND b.id_tipe_pasien='" . $tipe_pasien . "' " . $kondisi . " order by b.nama_pasien, d.ruang";
                $result = $this->runQuery($query);
                if (@mysql_num_rows($result) > 0) {
                    $i = 1;
                    $totKarcis = 0;
                    $totSewa = 0;
                    $totFasilitas = 0;
                    $totLaborat = 0;
                    $totRadiologi = 0;
                    $totTindakanPoli = 0;
                    $totTindakanPA = 0;
                    $totTindakanBedah = 0;
                    $totVisit = 0;
                    $totObat = 0;
                    $totBiaya = 0;
                    $totBayar = 0;
                    $totDiskon = 0;
                    $totAsuransi = 0;
                    $statusObat = 0;
                    while ($data = @mysql_fetch_array($result)) {
                        $q_obat = "SELECT id_faktur_penjualan FROM rm_faktur_penjualan
                                   WHERE id_pasien='" . $data['id_pasien'] . "' AND date(tgl_penjualan)= '" . $data['masuk'] . "' and del_flag<>'1'";
                        $r_obat = $this->runQuery($q_obat);
                        $biayaObat = 0;
                        $bayarObat = 0;
                        $diskonO = 0;
                        $asuransiO = 0;
                        if (@mysql_num_rows($r_obat) > 0) {
                            if (@($obatPasien[0]["id_pasien"] != $data['id_pasien'])) {
                                while ($blObat = @mysql_fetch_array($r_obat)) {
                                    $biayaObat += ( $this->getJumlahTagihanObat($blObat['id_faktur_penjualan']) - $this->getJumlahReturTagihanObat($blObat['id_faktur_penjualan']));
                                    $o_pembayaran = "select sum(bayar) as pembayaran, sum(diskon) as diskon, sum(asuransi) as asuransi, id_faktur_penjualan from rm_pembayaran_obat where id_faktur_penjualan='" . $blObat['id_faktur_penjualan'] . "' AND del_flag<>'1'";
                                    $h_pembayaran = $this->runQuery($o_pembayaran);
                                    $bayarObat += @ mysql_result($h_pembayaran, 0, 'pembayaran') - $this->getJumlahReturTagihanObat($blObat['id_faktur_penjualan']);
                                    if (@mysql_result($h_pembayaran, 0, 'diskon') != 0) {
                                        $diskonO += @ mysql_result($h_pembayaran, 0, 'diskon') - $this->getJumlahReturTagihanObat($blObat['id_faktur_penjualan']);
                                    } else {
                                        $diskonO += @ mysql_result($h_pembayaran, 0, 'diskon');
                                    }
                                    if (@mysql_result($h_pembayaran, 0, 'asuransi') != 0) {
                                        $asuransiO += @ mysql_result($h_pembayaran, 0, 'asuransi') - $this->getJumlahReturTagihanObat($blObat['id_faktur_penjualan']);
                                    } else {
                                        $asuransiO += @ mysql_result($h_pembayaran, 0, 'asuransi');
                                    }
                                }
                            }
                            $obatPasien[] = array("id_pasien" => $data['id_pasien']); //, "id_faktur_penjualan"=>$blObat['id_faktur_penjualan']);
                        }
                        $html .= "<tr>";
                        $html .= "<td>" . $i . "</td>";
                        $html .= "<td>" . $data['id_pasien'] . "</td>";
                        $html .= "<td>" . $data['nama_pasien'] . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaKarcis = $this->getBiayaKarcis($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaFasilitas = $this->getBiayaFasilitas($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayalaborat = $this->getBiayaLaborat($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaRadiologi = $this->getBiayaRadiologi($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanPA = $this->getBiayaTindakanPA($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanPoli = $this->getBiayaTindakanPoli($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($biayaTindakanBedah = $this->getBiayaTindakanBedah($data['id_pendaftaran'])), 2, ',', '.') . "</td>";
                        //$html .= "<td align='right'>".  number_format(($biayaAllVisit = $this->getBiayaAllVisit($data['id_pendaftaran'])),2,',','.')."</td>";
                        $html .= "<td align='right'>" . number_format($biayaObat, 2, ',', '.') . "</td>";
                        $jml = ($biayaKarcis + $biayaFasilitas + $biayalaborat + $biayaRadiologi + $biayaTindakanPA + $biayaTindakanPoli + $biayaTindakanBedah + $biayaObat);
                        $html .= "<td align='right'>" . number_format($jml, 2, ',', '.') . "</td>";
                        $q_pembayaran = "select sum(bayar) as pembayaran from rm_pembayaran_tagihan where id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>1";
                        $r_pembayaran = $this->runQuery($q_pembayaran);
                        $bayarTagihan = @mysql_result($r_pembayaran, 0, 'pembayaran');
                        $q_diskon = "select sum(diskon) as diskon from rm_diskon_tindakan where id_pendaftaran='" . $data['id_pendaftaran'] . "' and del_flag<>1";
                        $r_diskon = $this->runQuery($q_diskon);
                        $diskonA = @mysql_result($r_diskon, 0, 'diskon');
                        $q_asuransi = "select asuransi from rm_tagihan_asuransi where id_pendaftaran='" . $data['id_pendaftaran'] . "'";
                        $r_asuransi = $this->runQuery($q_asuransi);
                        $asuransiA = @mysql_result($r_asuransi, 0, 'asuransi');
                        $asuransiT = $asuransiO + $asuransiA;
                        $diskonT = $diskonO + $diskonA;
                        if ($ruang['id_ruang'] != '20' || $ruang['id_ruang'] != '34')
                            $bayarTagihan += $biayaKarcis;
                        $html .= "<td align='right'>" . number_format(($bayar = ($bayarTagihan + $bayarObat)), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($jml - $bayar - $asuransiT - $diskonT), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($diskonT), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format(($asuransiT), 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $totKarcis += $biayaKarcis;
                        $totFasilitas += $biayaFasilitas;
                        $totLaborat += $biayalaborat;
                        $totRadiologi += $biayaRadiologi;
                        $totTindakanPA += $biayaTindakanPA;
                        $totTindakanPoli += $biayaTindakanPoli;
                        $totTindakanBedah += $biayaTindakanBedah;
                        $totObat += $biayaObat;
                        $totBiaya += $jml;
                        $totBayar += $bayar;
                        $totDiskon += $diskonT;
                        $totAsuransi += $asuransiT;
                    }
                }
                $html .= "<tr>";
                $html .= "<td colspan='3' class='total'>Sub Total</td>";
                $html .= "<td align='right' class='total'>" . number_format($totKarcis, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totFasilitas, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totLaborat, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totRadiologi, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totTindakanPA, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totTindakanPoli, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totTindakanBedah, 2, ',', '.') . "</td>";
                //$html .= "<td align='right' class='total'>".number_format($totVisit,2,',','.')."</td>";
                $html .= "<td align='right' class='total'>" . number_format($totObat, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totBiaya, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totBayar, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format(($totBiaya - $totBayar - $totAsuransi - $totDiskon), 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totDiskon, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($totAsuransi, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $totAllKarcis += $totKarcis;
                $totAllFasilitas += $totFasilitas;
                $totAllLaborat += $totLaborat;
                $totAllRadiologi += $totRadiologi;
                $totAllTindakanPA += $totTindakanPA;
                $totAllTindakanPoli += $totTindakanPoli;
                $totAllTindakanBedah += $totTindakanBedah;
                //$totAllVisit += $totAllVisit;
                $totAllObat += $totObat;
                $totAllBiaya += $totBiaya;
                $totAllBayar += $totBayar;
                $totAllDiskon += $totDiskon;
                $totAllAsuransi += $totAsuransi;
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Grand Total</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllKarcis, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllFasilitas, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllLaborat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllRadiologi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllTindakanPA, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllTindakanPoli, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllTindakanBedah, 2, ',', '.') . "</td>";
            //$html .= "<td align='right' class='total'>".number_format($totAllVisit,2,',','.')."</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllObat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllBiaya, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllBayar, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format(($totAllBiaya - $totAllBayar - $totAllAsuransi - $totAllDiskon), 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllDiskon, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>" . number_format($totAllAsuransi, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</html>";

            fwrite($file, $html);
            fwrite($file, "</div></body></html>");
            fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");
            fclose($file);

            return '1';
        } else {
            return '0';
        }
    }

    public function cetakRekapPendapatan(
    $startDate, $endDate
    ) {
        $file = fopen("../report/rekapPendapatan.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                       'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                       <html xmlns='http://www.w3.org/1999/xhtml'>
                       <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                       <title></title>
                       <script src='../js/jquery-1.4.4.min.js'></script>
                       <script src='../js/jquery.print.js'></script>
                       <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                       <script>
                       $(function() {
                        $( '.printArea' ).print();
                       });
                       </script>
                       </head>");
        fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>REKAP PENDAPATAN RUMAH SAKIT</b></u><br>
                            Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate)) . "
                            <br></td>
                        </tr>";
        $html .="</table>";
        $total = 0;

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= "='" . $this->formatDateDb($startDate) . "'";
        }

        $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<td width='2%' class='headerTagihan'>No</td>";
        $html .= "<td width='10%' class='headerTagihan'>Uraian Rincian Obyek</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        $html .= "<tr>";
        $html .= "<td>1</td>";
        $html .= "<td>Instalasi Gawat Darurat</td>";
        $total += $this->getPendapatanIGD($kondisi);
        $html .= "<td align='right'>" . number_format($this->getPendapatanIGD($kondisi), 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>2</td>";
        $html .= "<td>Instalasi Bedah Sentral</td>";
        $total += $this->getPendapatanBedah($kondisi);
        $html .= "<td align='right'>" . number_format($this->getPendapatanBedah($kondisi), 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>3</td>";
        $html .= "<td>Instalasi Rawat Jalan</td>";
        $total += $this->getPendapatanRawatJalan($kondisi);
        $html .= "<td align='right'>" . number_format($this->getPendapatanRawatJalan($kondisi), 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $q_ruang = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_tipe_ruang IN ('2','9')";
        $r_ruang = $this->runQuery($q_ruang);
        while ($rec = @mysql_fetch_array($r_ruang)) {
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<td>" . $rec['ruang'] . "</td>";
            $html .= "<td align='right'>" . number_format($this->getPendapatanRuangRawatJalan($kondisi, $rec['id_ruang']), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        $html .= "<tr>";
        $html .= "<td>4</td>";
        $html .= "<td>Instalasi Rawat Inap</td>";
        $total += $this->getPendapatanRawatInap($kondisi);
        $html .= "<td align='right'>" . number_format($this->getPendapatanRawatInap($kondisi), 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $q_ruang = "SELECT id_ruang, ruang FROM rm_ruang WHERE id_tipe_ruang='8'";
        $r_ruang = $this->runQuery($q_ruang);
        while ($rec = @mysql_fetch_array($r_ruang)) {
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<td>" . $rec['ruang'] . "</td>";
            $html .= "<td align='right'>" . number_format($this->getPendapatanRuangRawatInap($kondisi, $rec['id_ruang']), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        $html .= "<tr>";
        $html .= "<td>5</td>";
        $html .= "<td>Instalasi Farmasi</td>";
        $total += $this->getPendapatanFarmasi($startDate, $endDate);
        $html .= "<td align='right'>" . number_format($this->getPendapatanFarmasi($startDate, $endDate), 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>6</td>";
        $html .= "<td>Instalasi Laboratorium</td>";
        $total += $this->getPendapatanLaboratorium($kondisi);
        $html .= "<td align='right'>" . number_format($this->getPendapatanLaboratorium($kondisi), 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>7</td>";
        $html .= "<td>Instalasi Radiologi</td>";
        $total += $this->getPendapatanRadiologi($kondisi);
        $html .= "<td align='right'>" . number_format($this->getPendapatanRadiologi($kondisi), 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td class='total' colspan='2'>Total</td>";
        $html .= "<td class='total' align='right'>" . number_format($total, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "</tbody>";
        $html .= "</table>";
        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");
        fclose($file);

        return '1';
    }

    public function cetakRekapJasa(
    $startDate, $endDate, $id_dokter, $id_ruang
    ) {
        $file = fopen("../report/rekapJasa.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                           <style>
                                .data{
                                    font-family: verdana;
                                    font-size: 9pt;
                                }
                                .printArea{
                                    font-family: verdana;
                                    font-size: 9pt;
                                }
                                .headerTagihan{
                                    font-weight: bold;
                                    border-bottom: 1px solid #000000;
                                    border-top: 1px solid #000000;
                                }
                                .total{
                                    font-weight: bold;
                                    border-top: 1px solid #000000;
                                }
                           </style>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
        fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>LAPORAN REKAPITULASI JASA</b></u><br>
                                Tanggal " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate)) . "
                                <br></td>
                            </tr>";
        $html .="</table>";
        $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<td width='2%' class='headerTagihan'>No</td>";
        $html .= "<td width='8%' class='headerTagihan'>Ruang</td>";
        $html .= "<td width='8%' class='headerTagihan'>Nama Dokter</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Sarana</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Layanan</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Unit Penghasil</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Direksi</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Remunerasi</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Dokter</td>";
        $html .= "<td width='10%' class='headerTagihan'>Pajak Dokter</td>";
        $html .= "<td width='10%' class='headerTagihan'>Jasa Perawat</td>";
        $html .= "<td width='10%' class='headerTagihan'>Pajak Perawat</td>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";

        $tot_jasa_sarana = 0;
        $tot_jasa_layanan = 0;
        $tot_unit_penghasil = 0;
        $tot_jasa_direksi = 0;
        $tot_jasa_remunerasi = 0;
        $tot_jasa_dokter = 0;
        $tot_jasa_perawat = 0;
        $tot_pajak_dokter = 0;
        $tot_pajak_perawat = 0;

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and b.id_ruang='" . $id_ruang . "'";
        $q_daftar = "SELECT
                          e.ruang,
                          g.nama_dokter,
                          SUM(a.jasa_layanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(a.jasa_direksi) AS jasa_direksi,
                          SUM(a.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(a.jasa_dokter) AS jasa_dokter,
                          SUM(a.jasa_perawat) AS jasa_perawat,
                          SUM((h.pajak * a.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * a.jasa_perawat)) AS pajakPerawat
                        FROM
                          rm_jasa_pendaftaran a,
                          rm_pendaftaran b,
                          rm_pasien c,
                          rm_tipe_pasien d,
                          rm_ruang e,
                          rm_tipe_pendaftaran f,
                          rm_dokter g,
                          rm_golongan h
                        WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          c.id_pasien = a.id_pasien AND
                          a.id_pasien = b.id_pasien AND
                          d.id_tipe_pasien = c.id_tipe_pasien AND
                          e.id_ruang = a.id_ruang AND
                          f.id_tipe_pendaftaran = b.id_tipe_pendaftaran AND
                          g.id_dokter = b.id_dokter AND
                          f.id_tipe_pendaftaran!='6' AND
                          b.biaya_pendaftaran > 0 AND
                          h.id_golongan = g.id_golongan " . $kondisi . "
                    GROUP BY b.id_ruang, a.id_dokter";
        $r_daftar = $this->runQuery($q_daftar);
        if (@mysql_num_rows($r_daftar)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Pendaftaran</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_daftar)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_tindakan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_tindakan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and g.id_ruang='" . $id_ruang . "'";

        $q_poli = "SELECT
                          i.ruang,
                          e.nama_dokter,
                          SUM(a.jasa_layanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(a.jasa_direksi) AS jasa_direksi,
                          SUM(a.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(a.jasa_dokter) AS jasa_dokter,
                          SUM(a.jasa_perawat) AS jasa_perawat,
                          SUM((j.pajak * a.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * a.jasa_perawat)) AS pajakPerawat
                      FROM
                          rm_jasa_tindakan_poli a,
                          rm_tindakan_ruang b,
                          rm_pasien c,
                          rm_tipe_pasien d,
                          rm_dokter e,
                          rm_pelaku_tindakan f,
                          rm_detail_tindakan g,
                          rm_tindakan h,
                          rm_ruang i,
                          rm_golongan j
                      WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          c.id_pasien = a.id_pasien AND
                          a.id_tindakan_ruang = b.id_tindakan_ruang AND
                          d.id_tipe_pasien = c.id_tipe_pasien AND
                          e.id_dokter = a.id_dokter AND
                          f.id_pelaku_tindakan = a.id_pelaku_tindakan AND
                          h.id_tindakan = g.id_tindakan AND
                          a.id_detail_tindakan = g.id_detail_tindakan AND
                          i.id_ruang = g.id_ruang AND
                          j.id_golongan = e.id_golongan AND
                          j.id_golongan = e.id_golongan " . $kondisi . "
                      GROUP BY
                          g.id_ruang, a.id_dokter";
        $r_poli = $this->runQuery($q_poli);

        if (@mysql_num_rows($r_poli)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Tindakan</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_poli)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_visit) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_visit)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and a.id_ruang='" . $id_ruang . "'";

        $q_visit = "SELECT
                          f.ruang,
                          d.nama_dokter,
                          SUM(a.tarif) AS jasa_dokter,
                          SUM((e.pajak * a.tarif)) AS pajak_dokter
                        FROM
                          rm_visit a,
                          rm_pasien b,
                          rm_tipe_pasien c,
                          rm_dokter d,
                          rm_golongan e,
                          rm_ruang f
                        WHERE
                          b.id_pasien = a.id_pasien AND
                          a.del_flag<>'1' AND
                          c.id_tipe_pasien = b.id_tipe_pasien AND
                          d.id_dokter = a.id_dokter AND
                          e.id_golongan = d.id_golongan AND
                          f.id_ruang=a.id_ruang " . $kondisi . "
                        GROUP BY
                          a.id_ruang, a.id_dokter";
        $r_visit = $this->runQuery($q_visit);
        if (@mysql_num_rows($r_visit)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Visit & Pemeriksaan</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_visit)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "</tr>";
                $i++;
                $jasa_dokter += $data['jasa_dokter'];
                $pajak_dokter += $data['pajak_dokter'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "</tr>";
            $tot_jasa_dokter += $jasa_dokter;
            $tot_pajak_dokter += $pajak_dokter;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_ruang != "")
            $kondisi .= " and a.id_ruang='" . $id_ruang . "'";
        $q_perawatan = "SELECT
                                  d.ruang,
                                  SUM((g.jasa_perawat * a.lama_penggunaan)) AS jasa_perawat,
                                  SUM((0.05 * (g.jasa_perawat * a.lama_penggunaan))) AS pajak
                              FROM
                                  rm_penggunaan_kamar a,
                                  rm_pasien b,
                                  rm_tipe_pasien c,
                                  rm_ruang d,
                                  rm_detail_kamar e,
                                  rm_kamar f,
                                  rm_tarif_kamar g
                              WHERE
                                  b.id_pasien = a.id_pasien AND
                                  c.id_tipe_pasien = b.id_tipe_pasien AND
                                  d.id_ruang = a.id_ruang AND
                                  e.id_detail_kamar = a.id_detail_kamar AND
                                  f.id_kamar = e.id_kamar AND
                                  f.id_kamar = g.id_kamar AND
                                  g.id_kelas = a.id_kelas AND a.del_flag<>'1'
                                  AND a.tgl_keluar!='' " . $kondisi . "
                              GROUP BY a.id_ruang";
        $r_perawatan = $this->runQuery($q_perawatan);
        if (@mysql_num_rows($r_perawatan)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Perawatan</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_perawatan)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >&nbsp;</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. 0</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_perawat += $data['pajak'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. 0</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.ambil) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.ambil)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and a.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and e.id_ruang_asal='" . $id_ruang . "'";

        $q_lab = "SELECT
                          f.ruang,
                          i.nama_dokter,
                          SUM(a.jasa_layanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(a.jasa_direksi) AS jasa_direksi,
                          SUM(a.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(a.jasa_dokter) AS jasa_dokter,
                          SUM(a.jasa_perawat) AS jasa_perawat,
                          SUM((j.pajak * a.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * a.jasa_perawat)) AS pajakPerawat
                        FROM
                          rm_jasa_tindakan_laboratorium a,
                          rm_pemeriksaan_lab b,
                          rm_pasien c,
                          rm_tipe_pasien d, 
                          rm_pendaftaran e,
                          rm_ruang f,
                          rm_kelompok_lab g,
                          rm_laboratorium h,
                          rm_dokter i,
                          rm_golongan j
                        WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          b.id_pasien = a.id_pasien AND
                          c.id_pasien = a.id_pasien AND
                          d.id_tipe_pasien = c.id_tipe_pasien AND
                          e.id_pendaftaran = a.id_pendaftaran AND
                          f.id_ruang = e.id_ruang_asal AND
                          h.id_laboratorium = a.id_laboratorium AND
                          g.id_kelompok_lab = h.id_kelompok_lab AND
                          j.id_golongan = i.id_golongan AND
                          i.id_dokter = a.id_dokter " . $kondisi . "
                        GROUP BY
                          e.id_ruang_asal, a.id_dokter";
        $r_lab = $this->runQuery($q_lab);
        if (@mysql_num_rows($r_lab)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Laboratorium</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_lab)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pemeriksaan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pemeriksaan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and i.id_dokter='" . $id_dokter . "'";
        if ($id_ruang != "")
            $kondisi .= " and d.id_ruang_asal='" . $id_ruang . "'";

        $q_rad = "SELECT
                          e.ruang,
                          h.nama_dokter,
                          SUM(i.jasa_layanan) AS jasa_layanan,
                          SUM(i.jasa_sarana) AS jasa_sarana,
                          SUM(i.jasa_unit_penghasil) AS unit_penghasil,
                          SUM(i.jasa_direksi) AS jasa_direksi,
                          SUM(i.jasa_remunerasi) AS jasa_remunerasi,
                          SUM(i.jasa_dokter) AS jasa_dokter,
                          SUM(i.jasa_perawat) AS jasa_perawat,
                          SUM((j.pajak * i.jasa_dokter)) AS pajakDokter,
                          SUM((0.05 * i.jasa_perawat)) AS pajakPerawat
                      FROM
                          rm_detail_radiologi a,
                          rm_pasien b,
                          rm_tipe_pasien c,
                          rm_pendaftaran d,
                          rm_ruang e,
                          rm_radiologi f,
                          rm_kelompok_radiologi g,
                          rm_dokter h,
                          rm_jasa_tindakan_radiologi i,
                          rm_golongan j
                      WHERE
                          b.id_pasien = a.id_pasien AND
                          c.id_tipe_pasien = b.id_tipe_pasien AND
                          d.id_pendaftaran = a.id_pendaftaran AND
                          e.id_ruang = d.id_ruang_asal AND
                          f.id_radiologi = a.id_radiologi AND
                          f.id_radiologi = i.id_radiologi AND
                          g.id_kelompok_radiologi = f.id_kelompok_radiologi AND
                          i.id_pendaftaran = a.id_pendaftaran AND
                          i.id_pasien = a.id_pasien AND
                          j.id_golongan = h.id_golongan AND
                          h.id_dokter = i.id_dokter " . $kondisi . "
                      GROUP BY d.id_ruang_asal, i.id_dokter";
        $r_rad = $this->runQuery($q_rad);
        if (@mysql_num_rows($r_rad)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Radiologi</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_rad)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >" . $data['ruang'] . "</td>";
                $html .= "<td >" . $data['nama_dokter'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_dokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakDokter'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_perawat'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajakPerawat'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['jasa_dokter'];
                $jasa_perawat += $data['jasa_perawat'];
                $pajak_dokter += $data['pajakDokter'];
                $pajak_perawat += $data['pajakPerawat'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_tindakan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_tindakan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and e.id_dokter='" . $id_dokter . "'";

        $q_bedah = "SELECT
                          e.nama_dokter AS dokter_operator,
                          f.nama_dokter AS dokter_anastesi,
                          SUM(a.jasa_pelayanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.unit_penghasil) AS unit_penghasil,
                          SUM(a.direksi) AS jasa_direksi,
                          SUM(a.remunerasi) AS jasa_remunerasi,
                          SUM(a.tim_operator) AS tim_operator,
                          SUM(a.ass_tim_operator) AS ass_tim_operator,
                          SUM((i.pajak * a.tim_operator)) AS pajak_operator,
                          SUM((0.05 * a.ass_tim_operator)) AS pajak_ass_operator
                      FROM
                          rm_jasa_tindakan_bedah a,
                          rm_tindakan_ruang_medis b,
                          rm_pasien c,
                          rm_tipe_pasien g,
                          rm_tindakan d,
                          rm_detail_tindakan h,
                          rm_dokter e,
                          rm_dokter f,
                          rm_golongan i
                      WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          a.id_tindakan_medis = b.id_tindakan_medis AND
                          a.id_pasien = c.id_pasien AND
                          c.id_tipe_pasien = g.id_tipe_pasien AND
                          h.id_detail_tindakan = a.id_tindakan_medis AND
                          d.id_tindakan = h.id_tindakan AND
                          b.dokter_operator = e.id_dokter AND
                          b.dokter_anastesi = f.id_dokter AND
                          i.id_golongan=e.id_golongan " . $kondisi . "
                      GROUP BY b.dokter_operator";
        $r_bedah = $this->runQuery($q_bedah);

        if (@mysql_num_rows($r_bedah)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Bedah Operator</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_bedah)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >&nbsp;</td>";
                $html .= "<td >" . $data['dokter_operator'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['tim_operator'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_operator'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['ass_tim_operator'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_ass_operator'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['tim_operator'];
                $jasa_perawat += $data['ass_tim_operator'];
                $pajak_dokter += $data['pajak_operator'];
                $pajak_perawat += $data['pajak_ass_operator'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(b.tgl_tindakan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(b.tgl_tindakan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_dokter != "")
            $kondisi .= " and f.id_dokter='" . $id_dokter . "'";

        $q_bedah = "SELECT
                          e.nama_dokter AS dokter_operator,
                          f.nama_dokter AS dokter_anastesi,
                          SUM(a.jasa_pelayanan) AS jasa_layanan,
                          SUM(a.jasa_sarana) AS jasa_sarana,
                          SUM(a.unit_penghasil) AS unit_penghasil,
                          SUM(a.direksi) AS jasa_direksi,
                          SUM(a.remunerasi) AS jasa_remunerasi,
                          SUM(a.tim_anastesi) AS tim_anastesi,
                          SUM(a.ass_tim_anastesi) AS ass_tim_anastesi,
                          SUM((i.pajak * a.tim_anastesi)) AS pajak_anastesi,
                          SUM((0.05 * a.ass_tim_anastesi)) AS pajak_ass_anastesi
                      FROM
                          rm_jasa_tindakan_bedah a,
                          rm_tindakan_ruang_medis b,
                          rm_pasien c,
                          rm_tipe_pasien g,
                          rm_tindakan d,
                          rm_detail_tindakan h,
                          rm_dokter e,
                          rm_dokter f,
                          rm_golongan i
                      WHERE
                          b.id_pendaftaran = a.id_pendaftaran AND
                          a.id_tindakan_medis = b.id_tindakan_medis AND
                          a.id_pasien = c.id_pasien AND
                          c.id_tipe_pasien = g.id_tipe_pasien AND
                          h.id_detail_tindakan = a.id_tindakan_medis AND
                          d.id_tindakan = h.id_tindakan AND
                          b.dokter_operator = e.id_dokter AND
                          b.dokter_anastesi = f.id_dokter AND
                          i.id_golongan=f.id_golongan " . $kondisi . "
                      GROUP BY b.dokter_anastesi";
        $r_bedah = $this->runQuery($q_bedah);

        if (@mysql_num_rows($r_bedah)) {
            $html .= "<tr>";
            $html .= "<td colspan='12'><b>Jasa Bedah Anastesi</b></td>";
            $html .= "</tr>";
            $i = 1;
            $jasa_sarana = 0;
            $jasa_layanan = 0;
            $unit_penghasil = 0;
            $jasa_direksi = 0;
            $jasa_remunerasi = 0;
            $jasa_dokter = 0;
            $jasa_perawat = 0;
            $pajak_dokter = 0;
            $pajak_perawat = 0;
            while ($data = @mysql_fetch_array($r_bedah)) {
                $html .= "<tr>";
                $html .= "<td align='center'>" . $i . "</td>";
                $html .= "<td >&nbsp;</td>";
                $html .= "<td >" . $data['dokter_anastesi'] . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_sarana'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_layanan'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['unit_penghasil'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_direksi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['jasa_remunerasi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['tim_anastesi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_anastesi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['ass_tim_anastesi'], 2, ',', '.') . "</td>";
                $html .= "<td align='right'>Rp. " . number_format($data['pajak_ass_anastesi'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $i++;
                $jasa_sarana += $data['jasa_sarana'];
                $jasa_layanan += $data['jasa_layanan'];
                $unit_penghasil += $data['unit_penghasil'];
                $jasa_direksi += $data['jasa_direksi'];
                $jasa_remunerasi += $data['jasa_remunerasi'];
                $jasa_dokter += $data['tim_anastesi'];
                $jasa_perawat += $data['ass_tim_anastesi'];
                $pajak_dokter += $data['pajak_anastesi'];
                $pajak_perawat += $data['pajak_ass_anastesi'];
            }
            $html .= "<tr>";
            $html .= "<td colspan='3' class='total'>Sub Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_sarana, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_layanan, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($unit_penghasil, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_direksi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_remunerasi, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_dokter, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($jasa_perawat, 2, ',', '.') . "</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($pajak_perawat, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $tot_jasa_sarana += $jasa_sarana;
            $tot_jasa_layanan += $jasa_layanan;
            $tot_unit_penghasil += $unit_penghasil;
            $tot_jasa_direksi += $jasa_direksi;
            $tot_jasa_remunerasi += $jasa_remunerasi;
            $tot_jasa_dokter += $jasa_dokter;
            $tot_jasa_perawat += $jasa_perawat;
            $tot_pajak_dokter += $pajak_dokter;
            $tot_pajak_perawat += $pajak_perawat;
        }

        $html .= "<tr>";
        $html .= "<td colspan='3' class='total'>Grand Total</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_sarana, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_layanan, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_unit_penghasil, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_direksi, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_remunerasi, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_dokter, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_pajak_dokter, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_jasa_perawat, 2, ',', '.') . "</td>";
        $html .= "<td align='right' class='total'>Rp. " . number_format($tot_pajak_perawat, 2, ',', '.') . "</td>";
        $html .= "</tr>";
        $html .= "</tbody>";
        $html .= "</table>";

        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");
        fclose($file);

        return '1';
    }

    public function cetakLaporanTagihanBanding($id_pasien, $id_kelas) {
        //$date = explode(' ', @mysql_result($result, 0, 'tgl_pembayaran'));
        //$tanggal = $this->codeDate($date[0]);
        //$nama = $this->getPasienNama(@mysql_result($result, 0, 'id_pasien'));
        $file = fopen("../report/cetakLaporanTagihan.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                       'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                       <html xmlns='http://www.w3.org/1999/xhtml'>
                       <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                       <title></title>
                       <script src='../js/jquery-1.4.4.min.js'></script>
                       <script src='../js/jquery.print.js'></script>
                       <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                       <style>
                            .data{
                                font-family: verdana;
                                font-size: 9pt;
                            }
                            .printArea{
                                font-family: verdana;
                                font-size: 9pt;
                            }
                            .headerTagihan{
                                font-weight: bold;
                                border-bottom: 1px solid #000000;
                                border-top: 1px solid #000000;
                            }
                            .total{
                                font-weight: bold;
                                border-top: 1px solid #000000;
                            }
                       </style>
                       <script>
                       $(function() {
                        $( '.printArea' ).print();
                       });
                       </script>
                       </head>");
        fwrite($file, "<body style='font-family:calibri; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>LAPORAN TAGIHAN PASIEN PEMBANDING</b></u><br><br></td>
                        </tr>";
        $html .="</table>";
        $html .= "<table class='data' width='100%'>
                <tr height='17'>
                    <td width='19%'>Nomor RM</td>
                    <td width='30%'>: <b>" . $id_pasien . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Kelas Pembanding</td>
                    <td width='30%'>" . $this->getKelas($id_kelas) . "</td>
                </tr>
                <tr height='17'>
                    <td width='19%'>Nama Pasien</td>
                    <td width='30%'>: <b>" . $this->getPasienNama($id_pasien) . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Jenis Kelamin</td>
                    <td width='30%'>: <b>" . $this->getKelaminPasien($id_pasien) . "</td>
                </tr>
                <tr height='17'>
                    <td width='19%'>Alamat</td>
                    <td width='30%'>: <b>" . $this->getAlamatPasien($id_pasien) . "</b></td>
                    <td width='2%'>&nbsp;</td>
                    <td width='19%'>Umur</td>
                    <td width='30%'>: <b>" . $this->getUmur($this->getPasienLahir($id_pasien)) . "</b></td>
                </tr>
            </table>";

        $id_pendaftaran = $this->getLastDaftarPasien($id_pasien);

        if ($id_pendaftaran != "") {
            $html .= "<hr>";
            $html .= "<span class='data'><b>PENGGUNAAN KAMAR</b></span>";
            $html .= $this->getTagihanKamarBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<span class='data'><b>VISIT/PEMERIKSAAN DOKTER</b></span>";
            $html .= $this->getTagihanVisitBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<span class='data'><b>JASA MEDIS / FASILITAS</b></span>";
            $html .= $this->getTagihanJasaMedisBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<span class='data'><b>JASA PENUNJANG MEDIS</b></span>";
            $html .= $this->getTagihanJasaPenunjangMedisBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<span class='data'><b>TINDAKAN RUANG</b></span>";
            $html .= $this->getTagihanTindakanMedisBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= "<span class='data'><b>TINDAKAN BEDAH</b></span>";
            $html .= $this->getTagihanTindakanBedahBanding($id_pasien, $id_kelas);
            $html .= "<br>";
            $html .= $this->getTotalTagihanPasienBanding($id_pasien, $id_kelas);
        } else {
            $html = "Data tidak ditemukan/Tidak ada tagihan untuk pasien ini.";
        }

        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");
        fclose($file);

        return '1';
    }

    public function cetakKwitansiBebas($id_pasien, $pilihan) {
        $q_id_bayar = "select max(id_pembayaran_tagihan) as idAkhir, max(id_pendaftaran) as id_pendaftaran from rm_pembayaran_tagihan where id_pasien='" . $id_pasien . "' and del_flag<>1";
        $r_bayar = $this->runQuery($q_id_bayar);
        $id_pembayaran = @mysql_result($r_bayar, 0, 'idAkhir');

        $q_asuransi = "SELECT asuransi FROM rm_tagihan_asuransi where del_flag<>1 and id_pendaftaran='" . @mysql_result($r_bayar, 0, 'id_pendaftaran') . "'";
        $r_asuransi = $this->runQuery($q_asuransi);
        if (@mysql_num_rows($r_asuransi) > 0)
            $asuransi = @mysql_result($r_asuransi, 0, 'asuransi');

        $tanggal = $this->codeDate(date('Y-m-d'));
        $nama = $this->getPasienNama($id_pasien);
        $file = fopen("../report/cetakKwitansiLunas.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                       'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                       <html xmlns='http://www.w3.org/1999/xhtml'>
                       <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                       <title></title>
                       <script src='../js/jquery-1.4.4.min.js'></script>
                       <script src='../js/jquery.print.js'></script>
                       <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                       <script>
                       $(function() {
                        $( '.printArea' ).print();
                       });
                       </script>
                       </head>");
        fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $nama_kasir = $_SESSION['nama_pegawai'];
        $lab = 0;
        $rad = 0;
        $fasilitas = 0;
        $tindakan = 0;
        $jmlObat = 0;
        $karcis = 0;
        $visitRuang = 0;
        while (list($key, $val) = @each($pilihan)) {
            if ($val == 1 || $val == 7)
                $lab = $this->getBiayaLaboratA($id_pasien);
            if ($val == 2 || $val == 7)
                $rad = $this->getBiayaRadiologiA($id_pasien);
            if ($val == 3 || $val == 7)
                $fasilitas = $this->getBiayaFasilitasA($id_pasien);
            if ($val == 4)
                $tindakan = $this->getBiayaTindakanAkhir($id_pasien);
            if ($val == 7)
                $tindakan = $this->getBiayaTindakan($id_pasien);
            if ($val == 6 || $val == 7)
                $jmlObat = $this->getAllTagihanObat($id_pasien);
            if ($val == 8 || $val == 7) {
                $karcis = $this->getAllKarcisPasien($id_pasien);
                $visitRuang = $this->getBiayaVisitRuang($id_pasien);
            }
        }

        $diskon = $this->getDiskonAll($id_pasien);
        $biaya = $lab + $rad + $fasilitas + $tindakan + $jmlObat + $karcis + $visitRuang + $this->getAdminBank($id_pembayaran);
        $um = 0;

        $html = "<table class='data' cellspacing='0' cellpadding='0' width='100%'>
                        <tr height='17'>
                        <td height='17' colspan='5'><span style='font-family:verdana;font-size:14'><b>RSUD Dr. SOEGIRI</b></span></td>
                        </tr>
                        <tr height='17'>
                        <td height='17' colspan='5'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        <tr height='17'>
                            <td width='10%'>Kwitansi No</td>
                            <td width='39%'>: <b>" . $id_pembayaran . "</b></td>
                            <td width='2%'>&nbsp;</td>
                            <td width='10%'>Nama PX</td>
                            <td width='39%'>: <b>" . $nama . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>Tgl Pulang</td>
                            <td width='39%'>: <b>" . $tanggal . "</b></td>
                            <td width='2%'>&nbsp;</td>
                            <td width='10%'>Alamat</td>
                            <td width='39%'>: <b>" . $this->getAlamatPasien($id_pasien) . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>Ruang</td>
                            <td width='39%'>: <b>" . $this->getNamaRuang($this->getRuangDaftar($this->getLastDaftarPasien($id_pasien))) . "</b></td>
                            <td width='2%'>&nbsp;</td>
                            <td width='10%'>No RM</td>
                            <td width='39%'>: <b>" . $id_pasien . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $this->getTipePasien($this->getTipePasienId($id_pasien)) . "</b></td>
                        </tr>
                   </table>
                   <hr>
                   <table class='data' cellspacing='0' cellpadding='0' width='100%'>
                        <tr height='17'>
                            <td width='10%' colspan='6'><i>Sudah terima dari</i></td>
                        </tr>
                        <tr height='17' valign='top'>
                            <td width='10%'>Jumlah Uang</td>
                            <td width='39%' colspan='5'><b><i># " . $this->pembilang(round(($biaya - $asuransi) - $diskon)) . " Rupiah #</i></b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>Untuk Pembayaran</td>
                            <td width='21%'>- KARCIS & JASA PERIKSA</td>
                            <td width='21%' align='right'><b>" . number_format($karcis, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- LABORAT</td>
                            <td width='21%' align='right'><b>" . number_format($lab, 2, ',', '.') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>&nbsp;</td>
                            <td width='21%'>- VISITE & RUANGAN</td>
                            <td width='21%' align='right'><b>" . number_format($visitRuang, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- RADIOLOGI</td>
                            <td width='21%' align='right'><b>" . number_format($rad, 2, ',', '.') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>&nbsp;</td>
                            <td width='21%'>- JASA MEDIS</td>
                            <td width='21%' align='right'><b>" . number_format($fasilitas, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- TINDAKAN</td>
                            <td width='21%' align='right'><b>" . number_format($tindakan, 2, ',', '.') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>&nbsp;</td>
                            <td width='21%'>- BIAYA OBAT</td>
                            <td width='21%' align='right'><b>" . number_format($jmlObat, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- Admin Bank</td>
                            <td width='21%' align='right'><b>" . number_format(500, 2, ',', '.') . "</b></td>
                        </tr>
                   </table>
                   <hr>
                   <table width='100%'>
                   <tr>
                   <td width='40%'>
                       <table class='data' cellspacing='0' cellpadding='0' width='100%'>
                            <tr height='17' valign='top'>
                                <td width='40%'>TERBILANG</td>
                                <td width='60%' align='right'><b>" . number_format($biaya, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%'>UANG MUKA</td>
                                <td width='60%' align='right'><b>" . number_format($um, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%'>BEBAN ASKES</td>
                                <td width='60%' align='right'><b>" . number_format($asuransi, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%'>DISKON</td>
                                <td width='60%' align='right'><b>" . number_format($diskon, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%' class='total'>GRAND TOTAL</td>
                                <td width='60%' class='total' align='right'><b>" . number_format((($biaya - $asuransi) - $diskon), '2', ',', '.') . "</b></td>
                            </tr>
                       </table>
                   </td>
                   <td align='center'>
                        Kasir,<br>" . $tanggal . "<br><br><br><b><u>" . $nama_kasir . "</u></b>
                   </td>
               </tr></table>";
        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fclose($file);

        return '1';
    }

    public function cetakKwitansiLunas($id_pasien, $id_pembayaran) {
        $tanggal = $this->codeDate(date('Y-m-d'));
        $nama = $this->getPasienNama($id_pasien);
        $file = fopen("../report/cetakKwitansiLunas.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                       'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                       <html xmlns='http://www.w3.org/1999/xhtml'>
                       <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                       <title></title>
                       <script src='../js/jquery-1.4.4.min.js'></script>
                       <script src='../js/jquery.print.js'></script>
                       <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                       <script>
                       $(function() {
                        $( '.printArea' ).print();
                       });
                       </script>
                       </head>");
        fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $nama_kasir = $_SESSION['nama_pegawai'];
        $biaya = $this->getAllTagihanPasien($id_pasien) + $this->getAdminBank($id_pembayaran);
        $karcis = $this->getAllKarcisPasien($id_pasien);
        $jmlObat = $this->getAllTagihanObat($id_pasien);
        $lab = $this->getBiayaLaboratA($id_pasien);
        $rad = $this->getBiayaRadiologiA($id_pasien);
        $visitRuang = $this->getBiayaVisitRuang($id_pasien);
        $fasilitas = $this->getBiayaFasilitasA($id_pasien);
        $tindakan = $this->getBiayaTindakan($id_pasien);
        $diskon = $this->getDiskonAll($id_pasien);
        $bayar = $this->getBayar($id_pembayaran);
        $asuransi = $this->getAsuransiA($id_pasien);
        if ($bayar > 0)
            $um = $biaya - ($bayar + $asuransi + $diskon);
        $html = "<table class='data' cellspacing='0' cellpadding='0' width='100%'>
                        <tr height='17'>
                        <td height='17' colspan='5'><span style='font-family:verdana;font-size:14'><b>RSUD Dr. SOEGIRI</b></span></td>
                        </tr>
                        <tr height='17'>
                        <td height='17' colspan='5'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        <tr height='17'>
                            <td width='10%'>Kwitansi No</td>
                            <td width='39%'>: <b>" . $id_pembayaran . "</b></td>
                            <td width='2%'>&nbsp;</td>
                            <td width='10%'>Nama PX</td>
                            <td width='39%'>: <b>" . $nama . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>Tgl Pulang</td>
                            <td width='39%'>: <b>" . $tanggal . "</b></td>
                            <td width='2%'>&nbsp;</td>
                            <td width='10%'>Alamat</td>
                            <td width='39%'>: <b>" . $this->getAlamatPasien($id_pasien) . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>Ruang</td>
                            <td width='39%'>: <b>" . $this->getNamaRuang($this->getRuangDaftar($this->getLastDaftarPasien($id_pasien))) . "</b></td>
                            <td width='2%'>&nbsp;</td>
                            <td width='10%'>No RM</td>
                            <td width='39%'>: <b>" . $id_pasien . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $this->getTipePasien($this->getTipePasienId($id_pasien)) . "</b></td>
                        </tr>
                   </table>
                   <hr>
                   <table class='data' cellspacing='0' cellpadding='0' width='100%'>
                        <tr height='17'>
                            <td width='10%' colspan='6'><i>Sudah terima dari</i></td>
                        </tr>
                        <tr height='17' valign='top'>
                            <td width='10%'>Jumlah Uang</td>
                            <td width='39%' colspan='5'><b><i># " . $this->pembilang(round($bayar)) . " Rupiah #</i></b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>Untuk Pembayaran</td>
                            <td width='21%'>- KARCIS & JASA PERIKSA</td>
                            <td width='21%' align='right'><b>" . number_format($karcis, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- LABORAT</td>
                            <td width='21%' align='right'><b>" . number_format($lab, 2, ',', '.') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>&nbsp;</td>
                            <td width='21%'>- VISITE & RUANGAN</td>
                            <td width='21%' align='right'><b>" . number_format($visitRuang, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- RADIOLOGI</td>
                            <td width='21%' align='right'><b>" . number_format($rad, 2, ',', '.') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>&nbsp;</td>
                            <td width='21%'>- JASA MEDIS</td>
                            <td width='21%' align='right'><b>" . number_format($fasilitas, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- TINDAKAN</td>
                            <td width='21%' align='right'><b>" . number_format($tindakan, 2, ',', '.') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td width='10%'>&nbsp;</td>
                            <td width='21%'>- BIAYA OBAT</td>
                            <td width='21%' align='right'><b>" . number_format($jmlObat, 2, ',', '.') . "</b></td>
                            <td width='5%'></td>
                            <td width='21%'>- Admin Bank</td>
                            <td width='21%' align='right'><b>" . number_format(500, 2, ',', '.') . "</b></td>
                        </tr>
                   </table>
                   <hr>
                   <table width='100%'>
                   <tr>
                   <td width='40%'>
                       <table class='data' cellspacing='0' cellpadding='0' width='100%'>
                            <tr height='17' valign='top'>
                                <td width='40%'>TERBILANG</td>
                                <td width='60%' align='right'><b>" . number_format($biaya, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%'>UANG MUKA</td>
                                <td width='60%' align='right'><b>" . number_format($um, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%'>BEBAN ASKES</td>
                                <td width='60%' align='right'><b>" . number_format($asuransi, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%'>DISKON</td>
                                <td width='60%' align='right'><b>" . number_format($diskon, '2', ',', '.') . "</b></td>
                            </tr>
                            <tr height='17' valign='top'>
                                <td width='40%' class='total'>GRAND TOTAL</td>
                                <td width='60%' class='total' align='right'><b>" . number_format($bayar, '2', ',', '.') . "</b></td>
                            </tr>
                       </table>
                   </td>
                   <td align='center'>
                        Kasir,<br>" . $tanggal . "<br><br><br><b><u>" . $nama_kasir . "</u></b>
                   </td>
               </tr></table>";
        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fclose($file);

        $id_pendaftaran = $this->getLastDaftarPasien($id_pasien);
        $arr[] = array('display' => $html);
        $halaman = $this->jEncode($arr);
        $cek_cetak = "SELECT id_pendaftaran FROM rm_cetakan where id_pendaftaran='" . $id_pendaftaran . "'";
        $run_cek = $this->runQuery($cek_cetak);
        if (@mysql_num_rows($run_cek) > 0) {
            $cetakan = "UPDATE rm_cetakan set kwitansi='" . @mysql_escape_string($halaman) . "' WHERE id_pendaftaran='" . $id_pendaftaran . "'";
        } else {
            $cetakan = "insert into rm_cetakan (id_pendaftaran,kwitansi) values ('" . $id_pendaftaran . "','" . @mysql_escape_string($halaman) . "')";
        }
        $r_cetak = $this->runQuery($cetakan);

        return '1';
    }

    public function getBarangBal($tgl_awal, $tgl_akhir, $tipe_balance, $rows, $offset) {
        $kondisi = "";

        if ($tipe_balance != "")
            $kondisi .= " and a.id_keperluan='" . $tipe_balance . "'";

        if ($tgl_awal != "") {
            if ($tgl_akhir != "")
                $kondisi .= " and DATE(a.tgl_pemakaian) between '" . $this->formatDateDb($tgl_awal) . "' and '" . $this->formatDateDb($tgl_akhir) . "'";
            else
                $kondisi .= " and DATE(a.tgl_pemakaian)='" . $this->formatDateDb($tgl_awal) . "'";
        }

        $query = "SELECT a.id_barang_tindakan, b.barang, c.jumlah_stock, a.jumlah, b.satuan, d.keperluan
                    FROM rm_barang_tindakan a, rm_barang b, rm_stock_barang c, rm_tipe_balance d
                    WHERE b.id_barang=a.id_barang AND c.id_barang=a.id_barang AND a.id_ruang='" . $_SESSION['level'] . "' 
					AND a.id_ruang = c.id_ruang AND a.id_keperluan = d.id_keperluan " . $kondisi . " ";

        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_barang_tindakan' => $rec['id_barang_tindakan'],
                    'barang' => $rec['barang'],
                    'stock' => $rec['jumlah_stock'],
                    'jumlah' => $rec['jumlah'],
                    'satuan' => $rec['satuan'],
                    'tipe' => $rec['keperluan']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

}
