<?php

session_start();
require_once '../../common/function.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cPendaftaran
 *
 * @author SAP
 */
class cDiagnosa extends fungsi {

    //put your code here
    public function getDetailPasien($id_pendaftaran) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);

        $query = "select a.nama_pasien, a.tgl_lahir, a.id_kelamin, a.id_tipe_pasien, b.id_kelas from rm_pasien a, rm_pendaftaran b where a.id_pasien='" . $id_pasien . "' and b.id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);


        if (mysql_num_rows($result) > 0) {
            $return = array(
                "id_pasien" => $id_pasien,
                "pasien" => @mysql_result($result, 0, "nama_pasien"),
                "usia" => $this->getUmur(@mysql_result($result, 0, "tgl_lahir")),
                "jns_kelamin" => $this->getKelamin(@mysql_result($result, 0, "id_kelamin")),
                "jns_pasien" => $this->getTipePasien(@mysql_result($result, 0, "id_tipe_pasien")),
                "kls_rwt" => $this->getKelas(@mysql_result($result, 0, "id_kelas"))
            );

            return $this->jEncode($return);
        }
    }

    public function getDetailDiagnosa($id_pendaftaran, $rows, $offset) {
        $query = "select * from rm_diagnosa where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $tanggal = explode(' ', $rec['tgl_diagnosa']);
                $tgl_diagnosa = $tanggal[0];
                $jam_diagnosa = $tanggal[1];
                $arr[] = array(
                    'id_diagnosa' => $rec['id_diagnosa'],
                    'nama_dokter' => $this->getDokter($rec['id_dokter']),
                    'tgl_diagnosa' => $this->formatDateDb($tgl_diagnosa),
                    'jam_diagnosa' => $jam_diagnosa,
                    'diagnosa_primer' => $this->getDiagnosa($rec['penyakit_primer'], 'nama_penyakit'),
                    'icd_primer' => $this->getDiagnosa($rec['penyakit_primer'], 'icd'),
                    'diagnosa_sekunder' => $this->getDiagnosa($rec['penyakit_sekunder'], 'nama_penyakit'),
                    'icd_sekunder' => $this->getDiagnosa($rec['penyakit_sekunder'], 'icd'),
                    'primer' => $rec['penyakit_primer'],
                    'sekun' => $rec['penyakit_sekunder'],
                    'dokter' => $rec['id_dokter'],
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":[], "rows":[]}';
        }
    }

    public function getDataDiagnosa($id_diagnosa) {
        $query = "select * from rm_diagnosa where id_diagnosa='" . $id_diagnosa . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr = array(
                    'id_diagnosa' => $rec['id_diagnosa'],
                    'dokter' => $rec['id_dokter'],
                    'penyakitPrimer' => $this->getDiagnosa($rec['penyakit_primer'], 'nama_penyakit'),
                    'penyakitPrimerId' => $rec['penyakit_primer'],
                    'penyakitSekunder' => $this->getDiagnosa($rec['penyakit_sekunder'], 'nama_penyakit'),
                    'penyakitSekunderId' => $rec['penyakit_sekunder']
                );
            }
            return $this->jEncode($arr);
        }
    }

    public function getDetailDiagnosaLain($id_pendaftaran, $rows, $offset) {
        $query = "select * from rm_detail_diagnosa where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $tanggal = explode(' ', $rec['tgl_diagnosa']);
                $tgl_diagnosa = $tanggal[0];
                $jam_diagnosa = $tanggal[1];
                $arr[] = array(
                    'id_detail_diagnosa' => $rec['id_detail_diagnosa'],
                    'tgl_diagnosa' => $tgl_diagnosa,
                    'jam_diagnosa' => $jam_diagnosa,
                    'diagnosa' => $rec['diagnosa'],
                    'keluhan' => $rec['keluhan'],
                    'hasil_pemeriksaan' => $rec['hasil_pemeriksaan'],
                    'terapi' => $rec['terapi'],
                    'nadi' => $rec['nadi'],
                    'tensi' => $rec['tensi'],
                    'temp' => $rec['temp'],
                    'nafas' => $rec['nafas'],
                    'berat_badan' => $rec['berat_badan'],
                    'tinggi_badan' => $rec['tinggi_badan'],
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
    }

    public function getDtlDiagnosa($id_pendaftaran) {
        $query = "select * from rm_detail_diagnosa where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr = array(
                    'id_detail_diagnosa' => $rec['id_detail_diagnosa'],
                    'diagnosa_lain' => $rec['diagnosa'],
                    'keluhan_lain' => $rec['keluhan'],
                    'hasil_pemeriksaan' => $rec['hasil_pemeriksaan'],
                    'terapi' => $rec['terapi'],
                    'nadi' => $rec['nadi'],
                    'tensi' => $rec['tensi'],
                    'temperatur' => $rec['temp'],
                    'nafas' => $rec['nafas'],
                    'berat_badan' => $rec['berat_badan'],
                    'tinggi_badan' => $rec['tinggi_badan'],
                    'jKonsultasi' => $rec['konsul'],
                    'ruangKonsul' => $rec['id_ruang']
                );
            }
            return $this->jEncode($arr);
        } else {
            $q_dokter = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $id_pendaftaran . "'";
            $r_dokter = $this->runQuery($q_dokter);

            if (@mysql_num_rows($r_dokter)) {
                
            }
        }
    }

    public function getDokterJb($id_pendaftaran) {
        $query = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr = array(
                    'dokter' => $rec['id_dokter'],
                    'dokterF' => $rec['id_dokter']
                );
            }
            return $this->jEncode($arr);
        }
    }

    public function saveDetailDiagnosa(
    $id_pendaftaran, $id_detail_diagnosa, $diagnosa_lain, $keluhan_lain, $hasil_pemeriksaan, $terapi, $nadi, $tensi, $temperatur, $nafas, $berat_badan, $tinggi_badan, $konsul, $id_ruang
    ) {
        if ($id_detail_diagnosa == '') {
            $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            $query = "insert into rm_detail_diagnosa (
                        id_pendaftaran,
                        tgl_diagnosa,
                        diagnosa,
                        keluhan,
                        hasil_pemeriksaan,
                        terapi,
                        nadi,
                        tensi,
                        temp,
                        nafas,
                        berat_badan,
                        tinggi_badan,
                        konsul,
                        id_ruang,
                        id_pasien,
                        id_tipe_pasien
                     ) values (
                        '" . $id_pendaftaran . "',
                        '" . date('Y-m-d H:i:s') . "',
                        '" . @mysql_escape_string($diagnosa_lain) . "',
                        '" . @mysql_escape_string($keluhan_lain) . "',
                        '" . @mysql_escape_string($hasil_pemeriksaan) . "',
                        '" . @mysql_escape_string($terapi) . "',
                        '" . @mysql_escape_string($nadi) . "',
                        '" . @mysql_escape_string($tensi) . "',
                        '" . @mysql_escape_string($temperatur) . "',
                        '" . @mysql_escape_string($nafas) . "',
                        '" . @mysql_escape_string($berat_badan) . "',
                        '" . @mysql_escape_string($tinggi_badan) . "',
                        '" . @mysql_escape_string($konsul) . "',
                        '" . $id_ruang . "',
                        '" . $id_pasien . "',
                        '" . $tipe_pasien . "'
                     )";
        } else {
            $query = "update rm_detail_diagnosa set
                        id_pendaftaran = '" . $id_pendaftaran . "',
                        tgl_diagnosa = '" . date('Y-m-d H:i:s') . "',
                        diagnosa = '" . @mysql_escape_string($diagnosa_lain) . "',
                        keluhan = '" . @mysql_escape_string($keluhan_lain) . "',
                        hasil_pemeriksaan = '" . @mysql_escape_string($hasil_pemeriksaan) . "',
                        terapi = '" . @mysql_escape_string($terapi) . "',
                        nadi = '" . @mysql_escape_string($nadi) . "',
                        tensi = '" . @mysql_escape_string($tensi) . "',
                        temp = '" . @mysql_escape_string($temperatur) . "',
                        nafas = '" . @mysql_escape_string($nafas) . "',
                        berat_badan = '" . @mysql_escape_string($berat_badan) . "',
                        tinggi_badan = '" . @mysql_escape_string($tinggi_badan) . "',
                        konsul = '" . @mysql_escape_string($konsul) . "',
                        id_ruang = '" . $id_ruang . "'
                     where id_detail_diagnosa='" . $id_detail_diagnosa . "'";
        }

        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getDataListDiagnosa($diagnosa, $icd, $rows, $offset) {
        $kondisi = "";

        if ($diagnosa != '') {
            if ($icd != '') {
                $kondisi = " and nama_penyakit like '%" . $diagnosa . "%' and icd='" . $icd . "'";
            } else {
                $kondisi = " and nama_penyakit like '%" . $diagnosa . "%'";
            }
        } else {
            if ($icd != '') {
                $kondisi = " and icd='" . $icd . "'";
            }
        }

        $query = "select count(*) as jml from rm_penyakit where del_flag<>'1' " . $kondisi;
        $result = $this->runQuery($query);

        $jmlData = mysql_result($result, 0, 'jml');

        $query = "select * from rm_penyakit where del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_penyakit' => $rec['id_penyakit'],
                    'nama_penyakit' => $this->replaceString($rec['nama_penyakit']),
                    'icd' => $rec['icd']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
    }

    public function saveDiagnosa($id_diagnosa, $id_pendaftaran, $id_pasien, $id_dokter, $diagnosa_primer, $diagnosa_sekunder) {
        if (isset($_SESSION['level'])) {
            $q_dokter = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $id_pendaftaran . "'";
            $r_dokter = $this->runQuery($q_dokter);
            $tipe_pasien = $this->getTipePasienId($id_pasien);

            if ($id_dokter != @mysql_result($r_dokter, 0, 'id_dokter')) {
                $q_update = "update rm_dr_jb set id_dokter='" . $id_dokter . "' where id_pendaftaran='" . $id_pendaftaran . "'";
                $this->runQuery($q_update);
            }
            if ($id_diagnosa == '') {
                $query = "insert into rm_diagnosa (
                            tgl_diagnosa,
                            id_pendaftaran,
                            id_pasien,
                            id_dokter,
                            id_pegawai,
                            penyakit_primer,
                            penyakit_sekunder,
                            id_tipe_pasien
                        ) values (
                            '" . date('Y-m-d H:i:s') . "',
                            '" . $id_pendaftaran . "',
                            '" . $id_pasien . "',
                            '" . $id_dokter . "',
                            '" . $_SESSION['level'] . "',
                            '" . $diagnosa_primer . "',
                            '" . $diagnosa_sekunder . "',
                            '" . $tipe_pasien . "'
                        )";
            } else {
                $query = "update rm_diagnosa set
                            id_dokter='" . $id_dokter . "',
                            penyakit_primer='" . $diagnosa_primer . "',
                            penyakit_sekunder='" . $diagnosa_sekunder . "'
                        where id_diagnosa='" . $id_diagnosa . "'";
            }
            $result = $this->runQuery($query);

            if ($result) {
                return '1';
            } else {
                return '0';
            }
        }
        return 'LOGIN';
    }

    public function hapusDiagnosa($id_diagnosa) {
        $query = "delete from rm_diagnosa where id_diagnosa='" . $id_diagnosa . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

}

?>