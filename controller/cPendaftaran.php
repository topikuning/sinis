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
class cPendaftaran extends fungsi {

    //put your code here
    public function simpanPendaftaran(
    $id_pendaftaran, $id_pasien, $tgl_pendaftaran, $jadwal, $waktu, $tipe_pendaftaran, $ruang_asal, $ruang, $kelas, $id_kamar, $id_detail_kamar, $dokter, $biaya_pendaftaran, $id_perujuk, $asal_rujukan, $alasan_rujukan
    ) {
        if (isset($_SESSION['nip']) || isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                if ($jadwal == '')
                    $jadwal = date('d-m-Y');
                if ($waktu == '')
                    $waktu = date('H:i:s');
                if ($ruang == 49 && ($kelas != 6 || $kelas != 13))
                    $kelas = 2;

                $qpas = "SELECT * from rm_pasien where del_flag<>'1' AND id_pasien = '" . $id_pasien . "'";
                $rpas = $this->runQuery($qpas);

                $jUmur = $this->jenjangUmur($id_pasien, $this->formatDateDb($tgl_pendaftaran));

                if (mysql_num_rows($rpas) > 0) {
                    $biaya = $this->getIdBiaya($tipe_pendaftaran, $id_pasien);
                    $tipe_pasien = $this->getTipePasienId($id_pasien);

                    if ($tipe_pendaftaran == 6) {
                        $qcek = "select count(*) as jml from rm_pendaftaran where id_tipe_pendaftaran='" . $tipe_pendaftaran . "' 
                    and id_pasien='" . $id_pasien . "' and del_flag<>'1' and status_pendaftaran<>'2' ";
                    } else {
                        $qcek = "select count(*) as jml from rm_pendaftaran where id_ruang_asal='" . $ruang_asal . "' and id_ruang='" . $ruang . "' and 
                    id_tipe_pendaftaran='" . $tipe_pendaftaran . "' and id_pasien='" . $id_pasien . "' and date(tgl_pendaftaran)='" . $this->formatDateDb($tgl_pendaftaran) . "' 
                    and del_flag<>'1' and status_pendaftaran<>'2' ";
                    }
                    $rcek = $this->runQuery($qcek);
                    if (@mysql_result($rcek, 0, 'jml') == 0) {
                        $query = "insert into rm_pendaftaran (
                        id_ruang_asal,
                        id_ruang,
                        id_kelas,
                        id_tipe_pendaftaran,
                        id_asal_pendaftaran,
                        biaya_pendaftaran,
                        id_pasien,
                        id_pegawai,
                        id_dokter,
                        tgl_pendaftaran,
                        status_pendaftaran,
                        id_tipe_pasien,
                        jadwal_daftar,
                        ip,
                        id_jenjang_umur
                     ) values (
                        '" . $ruang_asal . "', 
                        '" . $ruang . "', 
                        '" . $kelas . "', 
                        '" . $tipe_pendaftaran . "', 
                        '" . $id_pendaftaran . "', 
                        '" . $biaya_pendaftaran . "', 
                        '" . $id_pasien . "', 
                        '" . $_SESSION['nip'] . "',
                        '" . $dokter . "',
                        '" . $this->formatDateDb($tgl_pendaftaran) . " " . date('H:i:s') . "', 
                        '1',
                        '" . $tipe_pasien . "',
                        '" . $this->formatDateDb($jadwal) . " " . $waktu . "',
                        '" . $_SERVER['REMOTE_ADDR'] . "',
                        '" . $jUmur . "'
                     )";
                        $result = $this->runQuery($query);
                        if ($result) {
                            $q_daftar_id = "SELECT max(id_pendaftaran) as idakhir from rm_pendaftaran where id_pasien='" . $id_pasien . "'";
                            $r_daftar_id = $this->runQuery($q_daftar_id);
                            $idAkhir = @mysql_result($r_daftar_id, 0, "idakhir");
                            $q_cek_antri = "select max(no_antrian) as no_antri from rm_antrian
                                where tgl_antrian='" . $this->formatDateDb($tgl_pendaftaran) . "' and id_ruang='" . $ruang . "'";
                            $r_antri_id = $this->runQuery($q_cek_antri);
                            $no_antri = @mysql_result($r_antri_id, 0, "no_antri");
                            $no_antrian = $no_antri + 1;
                            $q_antrian = "insert into rm_antrian (id_pasien, id_ruang, id_pendaftaran, no_antrian, tgl_antrian, status_antrian) 
                                values ('" . $id_pasien . "','" . $ruang . "','" . $idAkhir . "','" . $no_antrian . "','" . $this->formatDateDb($tgl_pendaftaran) . "','0')";
                            $r_antrian = $this->runQuery($q_antrian);
                            if ($r_antrian) {
                                if ($tipe_pendaftaran == "6") {
                                    $tarifKamar = $this->getTarifKamarInap($id_kamar, $kelas);
                                    $tarifAskep = $this->getTarifAskepKamar($id_kamar, $kelas);
                                    $q_kamar = "insert into rm_penggunaan_kamar (
                                        id_pendaftaran,
                                        id_pasien,
                                        id_ruang_asal,
                                        id_ruang,
                                        id_kelas,
                                        id_detail_kamar,
                                        tgl_masuk,
                                        tarif,
                                        id_tipe_pasien,
                                        askep,
                                        ip
                                    ) values (
                                        '" . $idAkhir . "',
                                        '" . $id_pasien . "',
                                        '" . $ruang_asal . "',
                                        '" . $ruang . "',
                                        '" . $kelas . "',
                                        '" . $id_detail_kamar . "',
                                        '" . $this->formatDateDb($tgl_pendaftaran) . " " . date('H:i:s') . "',
                                        '" . $tarifKamar . "',
                                        '" . $tipe_pasien . "',
                                        '" . $tarifAskep . "',
                                        '" . $_SERVER['REMOTE_ADDR'] . "'
                                    )";
                                    $r_kamar = $this->runQuery($q_kamar);
                                    if ($r_kamar) {
                                        $this->setStatusKamar($id_detail_kamar);
                                    }
                                }
                                if ($id_perujuk != '' || $asal_rujukan != '' || $alasan_rujukan != '') {
                                    $q_rujukan = "insert into rm_rujukan (
                                        id_pendaftaran,
                                        id_perujuk,
                                        id_asal_rujukan,
                                        alasan_rujuk
                                     ) values (
                                        '" . $idAkhir . "', 
                                        '" . $id_perujuk . "', 
                                        '" . $asal_rujukan . "', 
                                        '" . @mysql_escape_string($alasan_rujukan) . "'
                                     )";
                                    $this->runQuery($q_rujukan);
                                }
                                $q_dr = "insert into rm_dr_jb (
                                id_pendaftaran,
                                id_dokter
                            ) values (
                                '" . $idAkhir . "', 
                                '" . $dokter . "'
                            )";
                                $this->runQuery($q_dr);
                                $nama_ruang = $this->getNamaRuang($ruang);
                                $tipe_pasien = $this->getTipePasienId($id_pasien);
                                $return = 'TRUE:' . $idAkhir . ':' . $no_antrian . ':' . $nama_ruang . ':' . $tipe_pasien;
                            } else {
                                $return = 'WARNING:Nomor Antrian tidak bisa di create';
                            }
                        } else {
                            $return = 'FALSE:Gagal Menyimpan Antrian';
                        }
                    } else {
                        $return = 'FALSE:Pasien sudah terdaftar';
                    }
                } else {
                    $return = 'KOSONG:No RM Tidak Terdaftar';
                }
                return $return;
            } else {
                return 'LUNAS';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanPendaftaranKonsul(
    $id_pendaftaran, $id_pasien, $tipe_pendaftaran, $ruang_asal, $kelas, $biaya_pendaftaran
    ) {
        if (isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                $biaya = $this->getIdBiaya($tipe_pendaftaran, $id_pasien);
                $tipe_pasien = $this->getTipePasienId($id_pasien);

                $dquery = " SELECT id_dokter FROM rm_dokter_ruang WHERE id_ruang = '" . $_SESSION['level'] . "'";
                $hdok = $this->runQuery($dquery);

                $dokter = @mysql_result($hdok, 0, 'id_dokter');
                $tgl_pendaftaran = date('d-m-Y');
                $ruang = $_SESSION['level'];
                $jUmur = $this->jenjangUmur($id_pasien, $this->formatDateDb($tgl_pendaftaran));
                $qcek = "select count(*) as jml from rm_pendaftaran where
                id_ruang_asal='" . $ruang_asal . "' and
                id_ruang='" . $ruang . "' and 
                id_tipe_pendaftaran='" . $tipe_pendaftaran . "' and
                id_pasien='" . $id_pasien . "' and
                date(tgl_pendaftaran)='" . $this->formatDateDb($tgl_pendaftaran) . "' and
                status_pendaftaran<>2 and del_flag<>'1'
                ";
                $rcek = $this->runQuery($qcek);
                if (@mysql_result($rcek, 0, 'jml') == 0) {
                    $query = "insert into rm_pendaftaran (
                        id_ruang_asal,
                        id_ruang,
                        id_kelas,
                        id_tipe_pendaftaran,
                        id_asal_pendaftaran,
                        biaya_pendaftaran,
                        id_pasien,
                        id_pegawai,
                        id_dokter,
                        tgl_pendaftaran,
                        status_pendaftaran,
                        id_tipe_pasien,
                        ip,
                        id_jenjang_umur
                     ) values (
                        '" . $ruang_asal . "', 
                        '" . $ruang . "', 
                        '" . $kelas . "', 
                        '" . $tipe_pendaftaran . "', 
                        '" . $id_pendaftaran . "', 
                        '" . $biaya_pendaftaran . "', 
                        '" . $id_pasien . "', 
                        '" . $_SESSION['nip'] . "',
                        '" . $dokter . "',
                        '" . $this->formatDateDb($tgl_pendaftaran) . " " . date('H:i:s') . "', 
                        '1',
                        '" . $tipe_pasien . "',
                        '" . $_SERVER['REMOTE_ADDR'] . "',
                        '" . $jUmur . "'
                     )";
                    $result = $this->runQuery($query);
                    if ($result) {
                        $q_daftar_id = "SELECT max(id_pendaftaran) as idakhir from rm_pendaftaran where id_pasien='" . $id_pasien . "'";
                        $r_daftar_id = $this->runQuery($q_daftar_id);
                        $idAkhir = @mysql_result($r_daftar_id, 0, "idakhir");
                        $q_cek_antri = "select max(no_antrian) as no_antri from rm_antrian
                                where tgl_antrian='" . $this->formatDateDb($tgl_pendaftaran) . "' and id_ruang='" . $ruang . "'";
                        $r_antri_id = $this->runQuery($q_cek_antri);
                        $no_antri = @mysql_result($r_antri_id, 0, "no_antri");
                        $no_antrian = $no_antri + 1;
                        $q_antrian = "insert into rm_antrian (id_pasien, id_ruang, id_pendaftaran, no_antrian, tgl_antrian, status_antrian) 
                                values ('" . $id_pasien . "','" . $ruang . "','" . $idAkhir . "','" . $no_antrian . "','" . $this->formatDateDb($tgl_pendaftaran) . "','0')";
                        $r_antrian = $this->runQuery($q_antrian);
                        if ($r_antrian) {
                            $q_dr = "insert into rm_dr_jb (
                                id_pendaftaran,
                                id_dokter
                            ) values (
                                '" . $idAkhir . "', 
                                '" . $dokter . "'
                            )";
                            $this->runQuery($q_dr);
                            $nama_ruang = $this->getNamaRuang($ruang);
                            $tipe_pasien = $this->getTipePasienId($id_pasien);
                            $return = 'TRUE:' . $idAkhir . ':' . $no_antrian . ':' . $nama_ruang . ':' . $tipe_pasien;
                        } else {
                            $return = 'WARNING:Nomor Antrian tidak bisa di create';
                        }
                    } else {
                        $return = 'FALSE:Gagal Menyimpan Antrian';
                    }
                } else {
                    $return = 'FALSE:Pasien sudah terdaftar';
                }
                return $return;
            } else {
                return 'LUNAS';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function bukaUlang($id_pendaftaran) {
        $query = " UPDATE rm_pendaftaran SET status_pendaftaran = '1' WHERE id_pendaftaran = '" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);
        if ($result) {
            $return = 'TRUE:BERHASIL';
        } else {
            $return = 'FALSE:GAGAL';
        }
        return $return;
    }

    public function updatePendaftaran(
    $id_pendaftaran, $id_pasien, $tgl_pendaftaran, $tipe_pendaftaran, $ruang_asal, $ruang, $kelas, $id_kamar, $id_detail_kamar, $dokter, $biaya_pendaftaran, $id_perujuk, $asal_rujukan, $alasan_rujukan
    ) {
        if (isset($_SESSION['nip'])) {
            $biaya = $biaya_pendaftaran;
            $jUmur = $this->jenjangUmur($id_pasien, $tgl_pendaftaran);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            $query = "update rm_pendaftaran set
                    id_ruang_asal='" . $ruang_asal . "',
                    id_ruang='" . $ruang . "',
                    id_kelas='" . $kelas . "',
                    id_tipe_pendaftaran='" . $tipe_pendaftaran . "',
                    biaya_pendaftaran='" . $biaya . "',
                    id_pasien='" . $id_pasien . "',
                    id_pegawai='" . $_SESSION['nip'] . "',
                    id_dokter='" . $dokter . "',
                    tgl_pendaftaran='" . $tgl_pendaftaran . " " . date('H:i:s') . "',
                    status_pendaftaran='1',
                    ip='" . $_SERVER['REMOTE_ADDR'] . "',
                    id_jenjang_umur='" . $jUmur . "',
                    id_tipe_pasien='" . $tipe_pasien . "'
                 where id_pendaftaran='" . $id_pendaftaran . "'";
            $result = $this->runQuery($query);
            if ($result) {
                if ($tipe_pendaftaran == '6') {
                    $ckamar = " SELECT id_ruang FROM rm_penggunaan_kamar where id_pendaftaran='" . $id_pendaftaran . "'";
                    $hasil = $this->runQuery($ckamar);
                    $tarifKamar = $this->getTarifKamarInap($id_kamar, $kelas);
                    $askep = $this->getTarifAskepKamar($id_kamar, $kelas);
                    if (@mysql_num_rows($hasil) > 0) {
                        $q_kamar = "update rm_penggunaan_kamar set
                                id_ruang='" . $ruang . "',
                                id_detail_kamar='" . $id_detail_kamar . "',
                                id_kelas='" . $kelas . "',
                                id_ruang_asal='" . $ruang_asal . "',
                                id_ruang='" . $ruang . "',
                                tgl_masuk='" . $tgl_pendaftaran . " " . date('H:i:s') . "',
                                tarif='" . $tarifKamar . "',
                                ip='" . $_SERVER['REMOTE_ADDR'] . "',
                                askep='" . $askep . "'
                                where id_pendaftaran='" . $id_pendaftaran . "'";
                        $this->runQuery($q_kamar);
                    } else {
                        $q_kamar = "insert into rm_penggunaan_kamar (
                                        id_pendaftaran,
                                        id_pasien,
                                        id_ruang_asal,
                                        id_ruang,
                                        id_kelas,
                                        id_detail_kamar,
                                        tgl_masuk,
                                        tarif,
                                        askep,
                                        id_tipe_pasien,
                                        ip
                                    ) values (
                                        '" . $id_pendaftaran . "',
                                        '" . $id_pasien . "',
                                        '" . $ruang_asal . "',
                                        '" . $ruang . "',
                                        '" . $kelas . "',
                                        '" . $id_detail_kamar . "',
                                        '" . date('Y-m-d H:i:s') . "',
                                        '" . $tarifKamar . "',
                                        '" . $askep . "',
                                        '" . $tipe_pasien . "',
                                        '" . $_SERVER['REMOTE_ADDR'] . "'
                                    )";
                        $r_kamar = $this->runQuery($q_kamar);
                        if ($r_kamar) {
                            $this->setStatusKamar($id_detail_kamar);
                        }
                    }
                }
                if ($id_perujuk != '' || $asal_rujukan != '' || $alasan_rujukan != '') {
                    $q_check_rujukan = "select * from rm_rujukan where id_pendaftaran='" . $id_pendaftaran . "'";
                    $r_check = $this->runQuery($q_check_rujukan);
                    if (mysql_num_rows($r_check) > 0) {
                        $q_rujukan = "update rm_rujukan set
                                    id_perujuk='" . $id_perujuk . "',
                                    id_asal_rujukan='" . $asal_rujukan . "',
                                    alasan_rujuk='" . $alasan_rujukan . "'
                                 where id_pendaftaran='" . $id_pendaftaran . "'";
                    } else {
                        $q_rujukan = "insert into rm_rujukan (
                                    id_pendaftaran,
                                    id_perujuk,
                                    id_asal_rujukan,
                                    alasan_rujuk
                                 ) values (
                                    '" . $id_pendaftaran . "', 
                                    '" . $id_perujuk . "', 
                                    '" . $asal_rujukan . "', 
                                    '" . $alasan_rujukan . "'
                                 )";
                    }
                    $this->runQuery($q_rujukan);
                }
                $nama_ruang = $this->getNamaRuang($ruang);
                $return = 'TRUE';
            } else {
                $return = 'FALSE';
            }
            return $return;
        }
        return 'LOGIN';
    }

    public function cariPendaftaran(
    $no_rm, $pasien, $startDate, $endDate, $tipe_pasien, $status, $closed, $rows, $offset
    ) {

        $kondisi = "";
        if ($no_rm != "")
            $kondisi .= " and a.id_pasien='" . $no_rm . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '%" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($tipe_pasien != "")
            $kondisi .= " and b.id_tipe_pasien='" . $tipe_pasien . "'";
        if ($status == "1")
            $kondisi .= " and a.id_tipe_pendaftaran IN (1,2,3,5)";
        if ($status == "2")
            $kondisi .= " and a.id_tipe_pendaftaran ='6'";
        if ($status == "3")
            $kondisi .= " and a.id_tipe_pendaftaran ='4'";
        if ($closed == "1")
            $kondisi .= " and a.status_pendaftaran ='0'";
        if ($closed == "2")
            $kondisi .= " and a.status_pendaftaran ='1'";
        if ($closed == "3")
            $kondisi .= " and a.status_pendaftaran ='2'";

        $query = "SELECT count(*) as jml  FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND a.id_asal_pendaftaran='0' AND a.del_flag<>'1' " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_result($result, 0, 'jml');

        $query = "SELECT a.id_pendaftaran, a.id_pasien, date(b.tgl_lahir) as tl,b.nama_pasien, c.tipe_pasien, e.kelas, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, b.alamat,
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, a.status_pendaftaran, a.id_pegawai  FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND a.id_asal_pendaftaran='0' AND a.del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        //return $query;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['status_pendaftaran'] == '1') {
                    $status = "Antrian";
                } else if ($rec['status_pendaftaran'] == '0') {
                    $status = "Perawatan";
                } else if ($rec['status_pendaftaran'] == '2') {
                    $status = "Closed";
                }
                $arr[] = array(
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'kelas' => $rec['kelas'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran_view' => $this->formatDateDb($rec['tgl_pendaftaran']),
                    'tgl_pendaftaran' => $this->formatDateDb($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'status' => $status,
                    "usia" => $this->getUmur($rec['tl']),
                    "alamat" => $this->getAlamatPasien($rec['id_pasien']),
                    'biaya_pendaftaran' => $rec['biaya_pendaftaran'],
                    'alamat' => $rec['alamat'],
                    'operator' => $this->getPegawaiUser($rec['id_pegawai'])
                );
                $jmlBiaya += $rec['biaya_pendaftaran'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_pasien":"Jumlah","biaya_pendaftaran":' . $jmlBiaya . '}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function cariPendaftaranInformasi($no_rm, $pasien, $alamat, $startDate, $endDate, $tipe_pasien, $status, $closed, $ruangane, $rows, $offset) {

        $kondisi = "";
        if ($no_rm != "")
            $kondisi .= " and a.id_pasien='" . $no_rm . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '%" . @mysql_escape_string($pasien) . "%'";
        if ($alamat != "")
            $kondisi .= " and b.alamat like '%" . @mysql_escape_string($alamat) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($tipe_pasien != "")
            $kondisi .= " and b.id_tipe_pasien='" . $tipe_pasien . "'";
        if ($status == "1")
            $kondisi .= " and a.id_tipe_pendaftaran IN (1,2,3,5)";
        if ($status == "3")
            $kondisi .= " and a.id_tipe_pendaftaran ='4'";
        if ($closed == "1")
            $kondisi .= " and a.status_pendaftaran <>'2'";
        if ($closed == "2")
            $kondisi .= " and a.status_pendaftaran ='2'";
        if ($ruangane != "")
            $kondisi .= " AND a.id_ruang = '" . $ruangane . "'";

        if ($status == "2") {
            if ($closed == 1)
                $kondisi .= " and g.status = '1'";

            $query = "SELECT a.id_pendaftaran, g.tgl_keluar, a.id_pasien, b.nama_pasien, c.tipe_pasien, e.kelas, h.kamar AS ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, b.alamat,
                      TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, a.status_pendaftaran FROM rm_pendaftaran a,
                      rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f, rm_kelas e, rm_penggunaan_kamar g, rm_kamar h, rm_detail_kamar i WHERE
                      b.id_pasien = a.id_pasien AND d.id_ruang = a.id_ruang AND e.id_kelas = a.id_kelas AND b.id_tipe_pasien = c.id_tipe_pasien AND f.id_tipe_pendaftaran = a.id_tipe_pendaftaran AND
                      a.id_asal_pendaftaran = '0' AND a.del_flag <> '1' AND i.id_kamar = h.id_kamar AND a.id_pendaftaran = g.id_pendaftaran AND g.id_detail_kamar = i.id_detail_kamar " . $kondisi;
        } else {
            $query = "SELECT a.id_pendaftaran, k.tgl_keluar, a.id_pasien, b.nama_pasien, c.tipe_pasien, e.kelas, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, b.alamat,
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, a.status_pendaftaran FROM 
                  (rm_pendaftaran a LEFT JOIN rm_pasien_keluar k ON (a.id_pendaftaran = k.id_pendaftaran)), rm_pasien b, rm_tipe_pasien c, rm_ruang d, 
                  rm_tipe_pendaftaran f, rm_kelas e WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang AND e.id_kelas=a.id_kelas AND 
                  b.id_tipe_pasien=c.id_tipe_pasien AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND a.id_asal_pendaftaran ='0' 
                  AND a.del_flag<>'1' " . $kondisi;
        }
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['status_pendaftaran'] == '1') {
                    $status = "ANTRIAN";
                } else if ($rec['status_pendaftaran'] == '0') {
                    $status = "PERAWATAN";
                } else if ($rec['status_pendaftaran'] == '2') {
                    $status = "PULANG";
                }
                $arr[] = array(
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'kelas' => $rec['kelas'],
                    'ruang' => $rec['ruang'],
                    'tgl_keluar' => $rec['tgl_keluar'],
                    'tgl_pendaftaran' => $this->formatDateDb($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'status' => $status,
                    'alamat' => $rec['alamat']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function cariPasienKeluar(
    $no_rm, $pasien, $alamat, $startDate, $endDate, $tipe_pasien, $status, $id_ruang
    ) {

        $kondisi = "";
        if ($no_rm != "")
            $kondisi .= " and a.id_pasien='" . $no_rm . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($alamat != "")
            $kondisi .= " and b.alamat like '%" . @mysql_escape_string($alamat) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(j.tgl_keluar) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(j.tgl_keluar)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($tipe_pasien != "")
            $kondisi .= " and j.id_tipe_pasien='" . $tipe_pasien . "'";
        if ($status == "1")
            $kondisi .= " and a.id_tipe_pendaftaran IN (1,2,3,5)";
        if ($status == "3")
            $kondisi .= " and a.id_tipe_pendaftaran ='4'";
        if ($status == "4")
            $kondisi .= " and a.id_tipe_pendaftaran ='7'";
        if ($id_ruang != "")
            $kondisi .= " AND a.id_ruang = '" . $id_ruang . "'";

        if ($status == "2") {
            $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, c.tipe_pasien, e.kelas, h.kamar AS ruang, DATE(j.tgl_keluar) AS tgl_keluar, b.alamat, TIME(j.tgl_keluar) AS jam_keluar,
                      a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, a.status_pendaftaran FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                      rm_kelas e, rm_penggunaan_kamar g, rm_kamar h, rm_detail_kamar i, rm_pasien_keluar j WHERE j.id_pasien = b.id_pasien AND d.id_ruang = a.id_ruang AND e.id_kelas = a.id_kelas AND
                      j.id_tipe_pasien = c.id_tipe_pasien AND f.id_tipe_pendaftaran = a.id_tipe_pendaftaran AND a.del_flag <> '1' AND j.del_flag<>1 AND i.id_kamar = h.id_kamar AND j.id_pendaftaran = a.id_pendaftaran AND
                      g.id_detail_kamar = i.id_detail_kamar AND g.id_pendaftaran = j.id_pendaftaran " . $kondisi;
        } else {
            $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, c.tipe_pasien, e.kelas, d.ruang AS ruang, DATE(j.tgl_keluar) AS tgl_keluar, b.alamat, TIME(j.tgl_keluar) AS jam_keluar, 
                      a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, a.status_pendaftaran FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, 
                      rm_tipe_pendaftaran f, rm_kelas e, rm_pasien_keluar j WHERE j.id_pasien = b.id_pasien AND d.id_ruang = a.id_ruang AND e.id_kelas = a.id_kelas AND 
                      j.id_tipe_pasien = c.id_tipe_pasien AND f.id_tipe_pendaftaran = a.id_tipe_pendaftaran AND a.del_flag <> '1' AND j.id_pendaftaran = a.id_pendaftaran " . $kondisi;
        }
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'kelas' => $rec['kelas'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran_view' => $this->formatDateDb($rec['tgl_keluar']),
                    'tgl_pendaftaran' => $this->formatDateDb($rec['tgl_keluar']),
                    'jam_daftar' => $rec['jam_keluar'],
                    'status' => $status,
                    'alamat' => $rec['alamat']
                );
            }
            return '{"rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"rows":[]}';
        }
    }

    public function cariPasienPindah(
    $no_rm, $pasien, $alamat, $startDate, $endDate, $id_ruang
    ) {

        $kondisi = "";
        if ($no_rm != "")
            $kondisi .= " and g.id_pasien='" . $no_rm . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($alamat != "")
            $kondisi .= " and b.alamat like '%" . @mysql_escape_string($alamat) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(g.tgl_masuk) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(g.tgl_masuk)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($id_ruang != "")
            $kondisi .= " AND g.id_ruang = '" . $id_ruang . "'";

        $query = "SELECT g.id_pendaftaran, g.lama_penggunaan, c.tipe_pasien,  g.id_penggunaan_kamar, date(g.tgl_masuk) as tgl_masuk, i.ruang, g.id_pasien, b.nama_pasien, h.ruang as ruang_asal, g.id_ruang, c.tipe_pasien, d.kamar, e.bed, g.tarif, f.kelas, DATE(g.tgl_masuk) AS tgl_pendaftaran, g.keterangan_selesai,
                  TIME(g.tgl_masuk) AS jam_daftar FROM rm_pasien b, rm_tipe_pasien c, rm_kamar d, rm_detail_kamar e, rm_kelas f, rm_penggunaan_kamar g, 
                  rm_ruang h, rm_ruang i WHERE g.del_flag<>'1' and i.id_ruang=g.id_ruang and b.id_pasien = g.id_pasien AND 
                  f.id_kelas=g.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien AND h.id_ruang=g.id_ruang_asal AND 
                  e.id_detail_kamar=g.id_detail_kamar AND d.id_kamar=e.id_kamar AND g.keterangan_selesai='Pindah Ruang' and g.reopen=0" . $kondisi . " 
                  order by b.id_pasien";
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_dok = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                $r_dok = $this->runQuery($q_dok);
                $arr[] = array(
                    'id_penggunaan_kamar' => $rec['id_penggunaan_kamar'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'ruang' => $rec['ruang'],
                    'ruang_asal' => $rec['ruang_asal'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'kamar' => $rec['kamar'],
                    'bed' => $rec['bed'],
                    'kelas' => $rec['kelas'],
                    'lama_perawatan' => $rec['lama_penggunaan'] . " Hari",
                    'tgl_pendaftaran_view' => $this->formatDateDb($rec['tgl_masuk']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'tarif' => $rec['tarif'],
                    'status' => $rec['keterangan_selesai']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function cariPendaftaranAll(
    $no_pendaftaran, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        if ($pasien != '')
            $id_pasien = $this->getPasienId($pasien);
        else
            $id_pasien = '';

        $kondisi = "";
        if ($no_pendaftaran != '')
            $kondisi .= " and a.id_pasien='" . $no_pendaftaran . "'";
        if ($pasien != '')
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and tgl_pendaftaran between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
            else
                $kondisi .= " and tgl_pendaftaran BETWEEN '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($startDate) . " 23:59:59'";
        }

        $query = "SELECT max(a.id_pendaftaran) as id_pendaftaran, a.status_pembayaran, a.id_pasien, b.nama_pasien, c.tipe_pasien, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, 
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, a.status_pendaftaran  FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran and (a.status_pembayaran!='2' or a.status_pendaftaran!='2') and a.id_asal_pendaftaran='0' AND a.del_flag<>'1' " . $kondisi . " GROUP BY a.id_pasien ";

        $result = $this->runQuery($query);
        $jmlData = @mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        $jmlTerbayar = 0;
        $jmlKurang = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['status_pendaftaran'] == '1') {
                    $status = "Antrian";
                } else if ($rec['status_pendaftaran'] == '0') {
                    $status = "Perawatan";
                } else if ($rec['status_pendaftaran'] == '2') {
                    $status = "Closed";
                }
                $biaya = $this->getAllTagihanPasien($rec['id_pasien']);
                $terbayar = $this->getAllBayarPasien($rec['id_pasien']);
                $diskon = $this->getAllDiskonPasien($rec['id_pasien']);
                $asuransi = $this->getAsuransiTagihan($rec['id_pendaftaran']);
                $kurang = ($biaya - $terbayar - $diskon - $asuransi);
                if ($biaya > 0) {
                    if ($kurang == 0) {
                        $status_bayar = "Lunas";
                    } else {
                        if ($terbayar == 0)
                            $status_bayar = "Belum Terbayar";
                        else
                            $status_bayar = "Kredit";
                    }
                } else {
                    $status_bayar = "Tidak ada tagihan";
                }
                $arr[] = array(
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran' => $this->codeDate($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'status' => $status,
                    'status_pembayaran' => $status_bayar,
                    'total' => $biaya,
                    'diskon' => $diskon,
                    'terbayar' => $terbayar,
                    'sisa' => $kurang,
                );
                $jmlBiaya += $biaya;
                $jmlTerbayar += $terbayar;
                $jmlKurang += $kurang;
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_pasien":"Jumlah",
                    "total":' . $jmlBiaya . ',
                    "terbayar":' . $jmlTerbayar . ',
                    "sisa":' . $jmlKurang . '}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function detailPendaftaran($no_pendaftaran) {

        $query = "SELECT *, date(tgl_pendaftaran) as tgl_daftar from rm_pendaftaran where id_pendaftaran='" . $no_pendaftaran . "'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            //$return =   array(
            $data = $no_pendaftaran;
            $data .= ":" . @mysql_result($result, 0, "id_pasien");
            $data .= ":" . @mysql_result($result, 0, "id_tipe_pendaftaran");
            $data .= ":" . @mysql_result($result, 0, "tgl_daftar");
            $data .= ":" . @mysql_result($result, 0, "id_ruang_asal");
            $data .= ":" . @mysql_result($result, 0, "id_ruang");
            $data .= ":" . @mysql_result($result, 0, "id_kelas");
            if (@mysql_result($result, 0, "id_tipe_pendaftaran") == '6') {
                $q_kamar = "SELECT b.id_kamar, a.id_detail_kamar FROM rm_penggunaan_kamar a, rm_detail_kamar b
                            WHERE b.id_detail_kamar=a.id_detail_kamar AND a.id_pendaftaran='" . $no_pendaftaran . "'";
                $r_kamar = $this->runQuery($q_kamar);
                $data .= ":" . @mysql_result($r_kamar, 0, "id_kamar");
                $data .= ":" . @mysql_result($r_kamar, 0, "id_detail_kamar");
            } else {
                $data .= ":";
                $data .= ":";
            }
            $data .= ":" . @mysql_result($result, 0, "id_dokter");
            $data .= ":" . @mysql_result($result, 0, "biaya_pendaftaran");
            $data .= ":" . $this->getRujukan($no_pendaftaran, 'id_asal_rujukan');
            $data .= ":" . $this->getRujukan($no_pendaftaran, 'id_perujuk');
            $data .= ":" . $this->getRujukan($no_pendaftaran, 'alasan_rujuk');
            //);

            return $data;
        }
    }

    public function cariPendaftaranRuang(
    $id_pasien, $pasien, $perawatan, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($perawatan == 2)
            $kondisi .= " AND a.status_pendaftaran=2 ";
        else
            $kondisi .= " AND a.status_pendaftaran<>2 ";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT count(*) as jml FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang='" . $_SESSION['level'] . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and g.del_flag<>'1' and a.del_flag<>'1' " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_result($result, 0, 'jml');

        $query = "SELECT a.status_pendaftaran as `stat`, a.id_pendaftaran, a.id_pasien, b.nama_pasien, b.tgl_lahir, b.id_kelamin, b.id_tipe_pasien, a.id_kelas, e.kelas, c.tipe_pasien, a.id_ruang, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, h.ruang AS ruang_asal, 
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, g.no_antrian, date(a.jadwal_daftar) as jadwal, time(a.jadwal_daftar) as waktu FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g, rm_ruang h WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang='" . $_SESSION['level'] . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND h.id_ruang=a.id_ruang_asal and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and a.del_flag<>'1' and g.del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['id_tipe_pendaftaran'] == "2" || $rec['id_tipe_pendaftaran'] == "3" || $rec['id_tipe_pendaftaran'] == "7") {
                    $q_check = "select id_asal_pendaftaran, id_ruang_asal from rm_pendaftaran where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                    $r_check = $this->runQuery($q_check);
                    if (@mysql_result($r_check, 0, 'id_asal_pendaftaran') != '0') {
                        $asal_rujukan = $this->getNamaRuang(@mysql_result($r_check, 0, 'id_ruang_asal'));
                        $perujuk = $this->getDokter($this->getDokterPenanggungJawab(@mysql_result($r_check, 0, 'id_asal_pendaftaran')));
                    } else {
                        $asal_rujukan = $this->getAsalRujukan($this->getRujukan($rec['id_pendaftaran'], 'id_asal_rujukan'));
                        $perujuk = $this->getPerujuk($this->getRujukan($rec['id_pendaftaran'], 'id_perujuk'));
                    }
                } else {
                    $asal_rujukan = "";
                    $perujuk = "";
                }
                $layani = $this->formatDateDb($rec['jadwal']) . ' ' . $rec['waktu'];
                $q_dok = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                $r_dok = $this->runQuery($q_dok);
                $arr[] = array(
                    'no_antrian' => $rec['no_antrian'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'perawatan' => $rec['stat'],
                    'kelas' => $rec['kelas'],
                    'id_kelas_pendaftaran' => $rec['id_kelas'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'asal_ruang' => $rec['ruang_asal'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran' => $this->codeDate($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'asal_rujukan' => $asal_rujukan,
                    'perujuk' => $perujuk,
                    'jadwal' => $layani,
                    "usia" => $this->getUmur($rec['tgl_lahir']),
                    "alamat" => $this->getAlamatPasien($rec['id_pasien']),
                    "kelamin" => $this->getKelamin($rec['id_kelamin']),
                    "dokter" => @mysql_result($r_dok, 0, 'id_dokter')
                );
                $jmlBiaya += $rec['biaya_pendaftaran'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_pasien":"Jumlah","biaya_pendaftaran":' . $jmlBiaya . '}]}';
        } else {
            return '{"total":0, "rows":[], "footer":[]}';
        }
    }

    public function cariIGDPulang(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $q_jum = "SELECT COUNT(*) as jml FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g, rm_ruang h WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang='" . $_SESSION['level'] . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND h.id_ruang=a.id_ruang_asal and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and a.del_flag<>'1' and g.del_flag<>'1' " . $kondisi;
        $r_jum = $this->runQuery($q_jum);
        $jmlData = @mysql_result($r_jum, 0, 'jml');

        $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, b.tgl_lahir, b.id_kelamin, b.id_tipe_pasien, a.id_kelas, e.kelas, c.tipe_pasien, a.id_ruang, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, h.ruang AS ruang_asal, 
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, g.no_antrian, date(a.jadwal_daftar) as jadwal, time(a.jadwal_daftar) as waktu FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g, rm_ruang h WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang='" . $_SESSION['level'] . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND h.id_ruang=a.id_ruang_asal and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and a.del_flag<>'1' and g.del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['id_tipe_pendaftaran'] == "2" || $rec['id_tipe_pendaftaran'] == "3" || $rec['id_tipe_pendaftaran'] == "7") {
                    $q_check = "select id_asal_pendaftaran, id_ruang_asal from rm_pendaftaran where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                    $r_check = $this->runQuery($q_check);
                    if (@mysql_result($r_check, 0, 'id_asal_pendaftaran') != '0') {
                        $asal_rujukan = $this->getNamaRuang(@mysql_result($r_check, 0, 'id_ruang_asal'));
                        $perujuk = $this->getDokter($this->getDokterPenanggungJawab(@mysql_result($r_check, 0, 'id_asal_pendaftaran')));
                    } else {
                        $asal_rujukan = $this->getAsalRujukan($this->getRujukan($rec['id_pendaftaran'], 'id_asal_rujukan'));
                        $perujuk = $this->getPerujuk($this->getRujukan($rec['id_pendaftaran'], 'id_perujuk'));
                    }
                } else {
                    $asal_rujukan = "";
                    $perujuk = "";
                }
                $layani = $this->formatDateDb($rec['jadwal']) . ' ' . $rec['waktu'];
                $q_dok = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                $r_dok = $this->runQuery($q_dok);
                $arr[] = array(
                    'no_antrian' => $rec['no_antrian'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'kelas' => $rec['kelas'],
                    'id_kelas_pendaftaran' => $rec['id_kelas'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'asal_ruang' => $rec['ruang_asal'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran' => $this->codeDate($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'asal_rujukan' => $asal_rujukan,
                    'perujuk' => $perujuk,
                    'jadwal' => $layani,
                    "usia" => $this->getUmur($rec['tgl_lahir']),
                    "alamat" => $this->getAlamatPasien($rec['id_pasien']),
                    "kelamin" => $this->getKelamin($rec['id_kelamin']),
                    "dokter" => @mysql_result($r_dok, 0, 'id_dokter')
                );
                $jmlBiaya += $rec['biaya_pendaftaran'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_pasien":"Jumlah","biaya_pendaftaran":' . $jmlBiaya . '}]}';
        } else {
            return '{"total":0, "rows":[], "footer":[]}';
        }
    }

    public function cariPendaftaranRR(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT count(*) as jml FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang=22 AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND a.status_pendaftaran!='2' and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and g.del_flag<>'1' and a.del_flag<>'1' " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_result($result, 0, 'jml');

        $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, b.tgl_lahir, b.id_kelamin, b.id_tipe_pasien, a.id_kelas, e.kelas, c.tipe_pasien, a.id_ruang, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, h.ruang AS ruang_asal, 
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, g.no_antrian, date(a.jadwal_daftar) as jadwal, time(a.jadwal_daftar) as waktu FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g, rm_ruang h WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang=22 AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND a.status_pendaftaran!='2' AND h.id_ruang=a.id_ruang_asal and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and a.del_flag<>'1' and g.del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['id_tipe_pendaftaran'] == "2" || $rec['id_tipe_pendaftaran'] == "3" || $rec['id_tipe_pendaftaran'] == "7") {
                    $q_check = "select id_asal_pendaftaran, id_ruang_asal from rm_pendaftaran where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                    $r_check = $this->runQuery($q_check);
                    if (@mysql_result($r_check, 0, 'id_asal_pendaftaran') != '0') {
                        $asal_rujukan = $this->getNamaRuang(@mysql_result($r_check, 0, 'id_ruang_asal'));
                        $perujuk = $this->getDokter($this->getDokterPenanggungJawab(@mysql_result($r_check, 0, 'id_asal_pendaftaran')));
                    } else {
                        $asal_rujukan = $this->getAsalRujukan($this->getRujukan($rec['id_pendaftaran'], 'id_asal_rujukan'));
                        $perujuk = $this->getPerujuk($this->getRujukan($rec['id_pendaftaran'], 'id_perujuk'));
                    }
                } else {
                    $asal_rujukan = "";
                    $perujuk = "";
                }
                $layani = $this->formatDateDb($rec['jadwal']) . ' ' . $rec['waktu'];
                $q_dok = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                $r_dok = $this->runQuery($q_dok);
                $arr[] = array(
                    'no_antrian' => $rec['no_antrian'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'kelas' => $rec['kelas'],
                    'id_kelas_pendaftaran' => $rec['id_kelas'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'asal_ruang' => $rec['ruang_asal'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran' => $this->codeDate($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'asal_rujukan' => $asal_rujukan,
                    'perujuk' => $perujuk,
                    'jadwal' => $layani,
                    "usia" => $this->getUmur($rec['tgl_lahir']),
                    "alamat" => $this->getAlamatPasien($rec['id_pasien']),
                    "kelamin" => $this->getKelamin($rec['id_kelamin']),
                    "dokter" => @mysql_result($r_dok, 0, 'id_dokter')
                );
                $jmlBiaya += $rec['biaya_pendaftaran'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_pasien":"Jumlah","biaya_pendaftaran":' . $jmlBiaya . '}]}';
        } else {
            return '{"total":0, "rows":[], "footer":[]}';
        }
    }

    public function cariPendaftaranRuangvk(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($_SESSION['level'] == '44')
            $ruangan = 48;
        if ($_SESSION['level'] == '32')
            $ruangan = 49;

        $query = "SELECT count(*) as jml FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang='" . $ruangan . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND a.status_pendaftaran!='2' and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and g.del_flag<>'1' and a.del_flag<>'1' " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_result($result, 0, 'jml');

        $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, b.tgl_lahir, b.id_kelamin, b.id_tipe_pasien, a.id_kelas, e.kelas, c.tipe_pasien, a.id_ruang, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, h.ruang AS ruang_asal, 
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, g.no_antrian  FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g, rm_ruang h WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang='" . $ruangan . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND a.status_pendaftaran!='2' AND h.id_ruang=a.id_ruang_asal and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and a.del_flag<>'1' and g.del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['id_tipe_pendaftaran'] == "2" || $rec['id_tipe_pendaftaran'] == "3" || $rec['id_tipe_pendaftaran'] == "7") {
                    $q_check = "select id_asal_pendaftaran, id_ruang_asal from rm_pendaftaran where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                    $r_check = $this->runQuery($q_check);
                    if (@mysql_result($r_check, 0, 'id_asal_pendaftaran') != '0') {
                        $asal_rujukan = $this->getNamaRuang(@mysql_result($r_check, 0, 'id_ruang_asal'));
                        $perujuk = $this->getDokter($this->getDokterPenanggungJawab(@mysql_result($r_check, 0, 'id_asal_pendaftaran')));
                    } else {
                        $asal_rujukan = $this->getAsalRujukan($this->getRujukan($rec['id_pendaftaran'], 'id_asal_rujukan'));
                        $perujuk = $this->getPerujuk($this->getRujukan($rec['id_pendaftaran'], 'id_perujuk'));
                    }
                } else {
                    $asal_rujukan = "";
                    $perujuk = "";
                }
                $arr[] = array(
                    'no_antrian' => $rec['no_antrian'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'kelas' => $rec['kelas'],
                    'id_kelas_pendaftaran' => $rec['id_kelas'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'asal_ruang' => $rec['ruang_asal'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran' => $this->codeDate($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'asal_rujukan' => $asal_rujukan,
                    'perujuk' => $perujuk,
                    "usia" => $this->getUmur($rec['tgl_lahir']),
                    "kelamin" => $this->getKelamin($rec['id_kelamin'])
                );
                $jmlBiaya += $rec['biaya_pendaftaran'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_pasien":"Jumlah","biaya_pendaftaran":' . $jmlBiaya . '}]}';
        } else {
            return '{"total":0, "rows":[], "footer":[]}';
        }
    }

    public function cariPendaftaranRuang2(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $tanggale = date('Y-m-d');

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT count(*) as jml FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND a.del_flag<>'1' AND a.status_pembayaran <> '2' AND a.id_tipe_pendaftaran<>'7'" . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_result($result, 0, 'jml');

        $query = "SELECT a.id_pendaftaran, a.id_pasien, b.nama_pasien, b.id_tipe_pasien, a.id_kelas, e.kelas, c.tipe_pasien, a.id_ruang, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, h.ruang AS ruang_asal, 
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_ruang h WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND (a.status_pembayaran <> '2' OR DATE(a.tgl_pendaftaran) = '" . $tanggale . "') AND a.del_flag<>1 AND h.id_ruang=a.id_ruang_asal AND a.id_tipe_pendaftaran<>'7' AND a.id_ruang<>'" . $_SESSION['level'] . "' " . $kondisi . " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                if ($rec['id_tipe_pendaftaran'] == "2" || $rec['id_tipe_pendaftaran'] == "3" || $rec['id_tipe_pendaftaran'] == "7") {
                    $q_check = "select id_asal_pendaftaran, id_ruang_asal from rm_pendaftaran where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                    $r_check = $this->runQuery($q_check);
                    if (@mysql_result($r_check, 0, 'id_asal_pendaftaran') != '0') {
                        $asal_rujukan = $this->getNamaRuang(@mysql_result($r_check, 0, 'id_ruang_asal'));
                        $perujuk = $this->getDokter($this->getDokterPenanggungJawab(@mysql_result($r_check, 0, 'id_asal_pendaftaran')));
                    } else {
                        $asal_rujukan = $this->getAsalRujukan($this->getRujukan($rec['id_pendaftaran'], 'id_asal_rujukan'));
                        $perujuk = $this->getPerujuk($this->getRujukan($rec['id_pendaftaran'], 'id_perujuk'));
                    }
                } else {
                    $asal_rujukan = "";
                    $perujuk = "";
                }
                $arr[] = array(
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'kelas' => $rec['kelas'],
                    'id_kelas_pendaftaran' => $rec['id_kelas'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'asal_ruang' => $rec['ruang_asal'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran' => $this->formatDateDb($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'asal_rujukan' => $asal_rujukan,
                    'perujuk' => $perujuk
                );
                $jmlBiaya += $rec['biaya_pendaftaran'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[], "footer":[]}';
        }
    }

    public function cariKonsulRuang(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT count(*) as jml FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang_asal='" . $_SESSION['level'] . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran and g.status_antrian='0' and g.tgl_antrian=date(a.tgl_pendaftaran) and a.del_flag<>'1' " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_result($result, 0, 'jml');

        $query = "SELECT a.status_pendaftaran, a.id_pendaftaran, a.id_pasien, b.nama_pasien, c.tipe_pasien, a.id_ruang, d.ruang, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, h.ruang AS ruang_asal, 
                  TIME(a.tgl_pendaftaran) AS jam_daftar, a.biaya_pendaftaran, f.tipe_pendaftaran, a.id_tipe_pendaftaran, g.no_antrian  FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_ruang d, rm_tipe_pendaftaran f,
                  rm_kelas e, rm_antrian g, rm_ruang h WHERE b.id_pasien = a.id_pasien AND d.id_ruang=a.id_ruang and a.id_ruang_asal='" . $_SESSION['level'] . "' AND e.id_kelas=a.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien
                  AND f.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND g.id_pendaftaran=a.id_pendaftaran AND h.id_ruang=a.id_ruang_asal and g.status_antrian='0' and a.del_flag<>'1' and a.status_pendaftaran<>'2' and g.tgl_antrian=date(a.tgl_pendaftaran) " . $kondisi . " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'no_antrian' => $rec['no_antrian'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_tipe_pendaftaran' => $rec['id_tipe_pendaftaran'],
                    'tipe_pendaftaran' => $rec['tipe_pendaftaran'],
                    'asal_ruang' => $rec['ruang_asal'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'tgl_pendaftaran' => $this->formatDateDb($rec['tgl_pendaftaran']),
                    'jam_daftar' => $rec['jam_daftar'],
                    'biaya_pendaftaran' => $rec['biaya_pendaftaran'],
                    'status' => $rec['status_pendaftaran']
                );
                $jmlBiaya += $rec['biaya_pendaftaran'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_pasien":"Jumlah","biaya_pendaftaran":' . $jmlBiaya . '}]}';
        } else {
            return '{"total":0, "rows":[], "footer":[]}';
        }
    }

    public function getIdBiaya($tipe_pendaftaran, $id_pasien) {
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
                    if ($this->checkTipeAsuransi($id_pasien)) {
                        if ($this->checkJmlDaftar($id_pasien)) {
                            $result = mysql_result($result, 0, 'biaya');
                        } else {
                            $result = 0;
                        }
                    } else {
                        $result = mysql_result($result, 0, 'biaya');
                    }
                }
            }
            return $result;
        } else {
            return 'error';
        }
    }

    public function getDetailKamar($id_ruang, $id_kelas, $rows, $offset) {
        $query = "SELECT b.id_kamar, a.kamar, c.id_detail_kamar, c.bed, c.status FROM rm_kamar a, rm_kelas_kamar b, 
                  rm_detail_kamar c WHERE a.id_ruang='" . $id_ruang . "' and c.id_kamar=b.id_kamar
                  AND b.id_kelas='" . $id_kelas . "' AND a.id_kamar=b.id_kamar";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_kamar' => $rec['id_kamar'],
                    'kamar' => $rec['kamar'],
                    'id_detail_kamar' => $rec['id_detail_kamar'],
                    'bed' => $rec['bed'],
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
    }

    public function batalPendaftaran($no_pendaftaran) {
        if ($this->cekPendaftaran($no_pendaftaran)) {
            $query = "update rm_pendaftaran set del_flag='1' where id_pendaftaran='" . $no_pendaftaran . "'";
            $result = $this->runQuery($query);

            if ($result)
                return '1';
            else
                return '2';
        } else {
            return '0';
        }
    }

    public function cetakKarcis($idDaftar) {
        $query = "select * from rm_pendaftaran where id_pendaftaran='" . $idDaftar . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $date = explode(' ', @mysql_result($result, 0, 'tgl_pendaftaran'));
            $tanggal = $date[0];
            $jam = $date[1];
            $nama = $this->getPasienNama(@mysql_result($result, 0, 'id_pasien'));
            $addr = explode(';', $this->getPasienInfo(@mysql_result($result, 0, 'id_pasien')));
            $alamat = $addr[0];
            $kota = $this->getKota($addr[1]);
            $kecamatan = $this->getKecamatan($addr[2]);
            $kelurahan = $this->getKelurahan($addr[3]);
            $tipePasien = $this->getTipePasien($addr[4]);
            $noAntri = $this->getAntrian($idDaftar);
            $tglLahir = $this->getPasienLahir(@mysql_result($result, 0, 'id_pasien'));
            $file = fopen("../report/cetakKarcis.html", 'w');
            fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../printstyle.css'/>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
	    		   <style>
				  td {overflow:hidden;}
			   </style>
                           </head>");
            fwrite($file, "<body>");
            fwrite($file, "<div class='printArea'>");
            fwrite($file, "<table style=' font-family: serif; font-size: 13px; width: 18.96850412672em' cellpadding='0' cellspacing='1'>");
            fwrite($file, "<tr height='18px'><td colspan=2 align='center'>BUKTI PENDAFTARAN<br><u>RSUD Dr. SOEGIRI LAMONGAN</u><br><br></td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%' style='outline: solid; outline-width: 1px; outline-color: #000000;' align='center'>$noAntri</td><td align='right'>$tipePasien</td></tr>");
            //fwrite($file, "<tr height='18px'><td width='35%'>ID</td><td> : $idDaftar</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>No RM</td><td> : " . @mysql_result($result, 0, 'id_pasien') . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Tanggal</td><td> : " . $this->formatDateDb($tanggal) . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Nama</td><td> : " . $nama . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Alamat</td><td> : " . $alamat . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Kelurahan</td><td> : " . $kelurahan . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Kecamatan</td><td> : " . $kecamatan . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Kota</td><td> : " . $kota . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Jam</td><td> : " . $jam . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Umur</td><td> :  " . $this->getUmur($tglLahir) . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Layanan</td><td> : " . $this->getTipePendaftaran(@mysql_result($result, 0, 'id_tipe_pendaftaran')) . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Spesialis</td><td> : " . $this->getNamaRuang(@mysql_result($result, 0, 'id_ruang')) . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Dokter</td><td> : " . $this->getDokter(@mysql_result($result, 0, 'id_dokter')) . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Biaya Karcis</td><td> : Rp. " . @mysql_result($result, 0, 'biaya_pendaftaran') . "</td></tr>");
            fwrite($file, "<tr height='18px'><td width='35%'>Operator</td><td>" . $this->getPegawaiUser($_SESSION['nip'])  . "</td></tr>");
            fwrite($file, "</table></div></body></html>");
            fwrite($file, "<script language='javascript'>setTimeout('self.close();',5000)</script>");
            fclose($file);

            $qUpdate = "update rm_pendaftaran set status_cetak='1' where id_pendaftaran='" . $idDaftar . "'";
            $resUpdate = $this->runQuery($qUpdate);
            return '1';
        } else {
            return '0';
        }
    }

    public function cetakSJP($idDaftar) {
        $query = "select * from rm_pendaftaran where id_pendaftaran='" . $idDaftar . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $q_pasien = "select * from rm_pasien where id_pasien='" . @mysql_result($result, 0, 'id_pasien') . "'";
            $r_pasien = $this->runQuery($q_pasien);

            $ruang = $this->getRuang(@mysql_result($result, 0, 'id_ruang'));
            $tipe_ruang = $this->getTipeRuang(@mysql_result($result, 0, 'id_ruang'));
            $date = explode(' ', @mysql_result($result, 0, 'tgl_pendaftaran'));
            $tanggal = $date[0];
            $jam = $date[1];
            $nama = $this->getPasienNama(@mysql_result($result, 0, 'id_pasien'));
            $title = $this->getTitle(@mysql_result($r_pasien, 0, 'id_title'));
            $addr = explode(';', $this->getPasienInfo(@mysql_result($result, 0, 'id_pasien')));
            $alamat = $addr[0];
            $kota = $this->getKota($addr[1]);
            $kecamatan = $this->getKecamatan($addr[2]);
            $kelurahan = $this->getKelurahan($addr[3]);
            $tipePasien = $this->getTipePasien($addr[4]);
            $noAntri = $this->getAntrian($idDaftar);
            $tglLahir = $this->getPasienLahir(@mysql_result($result, 0, 'id_pasien'));
            $file = fopen("../report/cetakSJP.html", 'w');
            fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../printstyle.css'/>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
            fwrite($file, "<body>");
            fwrite($file, "<div class='printArea'>");
            fwrite($file, "<p align='center'><strong>FORMULIR VERIFIKASI DAN KENDALI PELAYANAN</p><hr>
                            <table style=' font-family: verdana; font-size: 10px;' width='100%' border='0' cellspacing='1' cellpadding='0'>
                              <tr height='17'>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>&nbsp;</td>
                                <td width='27%'>&nbsp;</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>Tipe Ruang</td>
                                <td width='27%'>: <b>" . $tipe_ruang . "</b></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>&nbsp;</td>
                                <td width='27%'>&nbsp;</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>Ruang</td>
                                <td width='27%'>: <b>" . $ruang . "</b></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>1</div></td>
                                <td width='20%'>Nama RS </td>
                                <td width='27%'>: <strong>RSUD dr. Soegiri Lamongan</strong></td>
                                <td width='3%'><div align='center'>2</div></td>
                                <td width='20%'>Nomor Kode RS</td>
                                <td width='27%'>: <strong>35.24.016</strong></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>3</div></td>
                                <td width='20%'>Kelas RS </td>
                                <td width='27%'>: <strong>B</strong> </td>
                                <td width='3%'><div align='center'>4</div></td>
                                <td width='20%'>Nomor Rekam Medis</td>
                                <td width='27%'>: <b>" . sprintf('%06d', @mysql_result($result, 0, 'id_pasien')) . "</b></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>5</div></td>
                                <td width='20%'>Nama Pasien </td>
                                <td width='27%'>: <b>" . @mysql_result($r_pasien, 0, 'prefix') . ". " . $nama . ", " . $title . "</b></td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>ID Pasien</td>
                                <td width='27%'>: <b>" . $idDaftar . "</b></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>7</div></td>
                                <td width='20%'>Jenis Perawat </td>
                                <td width='27%'>: <strong>IRD / IRJ / IRNA &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;</strong></td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>Tanggal Pelayanan</td>
                                <td width='27%'>: <b>" . $this->codeDate($tanggal) . "</b></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>9</div></td>
                                <td width='20%'>Kelas Perawatan </td>
                                <td width='27%'>:</td>
                                <td width='3%'><div align='center'>8</div></td>
                                <td width='20%'>Total Biaya</td>
                                <td width='27%'>:</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>11</div></td>
                                <td width='20%'>Tanggal MRS </td>
                                <td width='27%'><table style=' font-family: verdana; font-size: 10px;' width='100%' align='center' cellpadding='0' cellspacing='1'  bgcolor='#000000'>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'>/</div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'>/</div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                  </tr>
                                </table></td>
                                <td width='3%'><div align='center'>10</div></td>
                                <td width='20%'>Tanggal KRS</td>
                                <td width='27%'><table style=' font-family: verdana; font-size: 10px;' width='100%' align='center' cellpadding='0' cellspacing='1'  bgcolor='#000000'>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'>/</div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'>/</div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                    <td width='10%'><div align='center'></div></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>12</div></td>
                                <td width='20%'>Jumlah Hari Perawatan </td>
                                <td width='27%'>:</td>
                                <td width='3%'><div align='center'></div></td>
                                <td colspan='2'>( Tgl. Keluar &ndash; Tanggal. Masuk +1)</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>13</div></td>
                                <td width='20%'>Tanggal Lahir </td>
                                <td width='27%'>: <b>" . @mysql_result($r_pasien, 0, 'tmp_lahir') . ", " . $this->codeDate($tglLahir) . "</b></td>
                                <td width='3%'><div align='center'>14</div></td>
                                <td width='20%'>Usia Dalam Hari</td>
                                <td width='27%'>: <b>" . number_format($this->jmlHari($tglLahir, date('Y-m-d')), 0, ',', '.') . " hari</b></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>15</div></td>
                                <td width='20%'>Usia Dalam Tahun </td>
                                <td width='27%'>: <b>" . $this->getUmurTahun($tglLahir) . "</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>1. Laki-Laki</td>
                                <td width='27%'>&nbsp;</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>16</div></td>
                                <td width='20%'>Cara Pulang </td>
                                <td width='27%'>:</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>2. Perempuan</td>
                                <td width='27%'>&nbsp;</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>17</div></td>
                                <td width='20%'>Berat Lahir </td>
                                <td width='27%'>:</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>1. Sembuh</td>
                                <td width='27%'>4. Meninggal Dunia</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>18</div></td>
                                <td width='20%'>Kode Diagnosa Utama </td>
                                <td width='27%'>:</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>2. Rujuk</td>
                                <td width='27%'>5. Tidak Tahu</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>19</div></td>
                                <td width='20%'>Kode Diagnosa Sekunder </td>
                                <td width='27%'>:</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>3. Pulang</td>
                                <td width='27%'>&nbsp;</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>&nbsp;</td>
                                <td width='27%'>&nbsp;</td>
                                <td width='3%'><div align='center'>20</div></td>
                                <td width='20%'>Kode Tindakan</td>
                                <td width='27%'>&nbsp;</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'></div></td>
                                <td colspan='2'><table style=' font-family: verdana; font-size: 10px;' width='100%' cellspacing='1' cellpadding='0'  bgcolor='#000000'>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td width='7%'><div align='center'><strong>No</strong></div></td>
                                    <td width='36%'><div align='center'><strong>Kode</strong></div></td>
                                    <td width='57%'><div align='center'><strong>Diagnosa</strong></div></td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>1</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>2</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>3</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>4</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>5</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                </table></td>
                                <td width='3%'><div align='center'></div></td>
                                <td colspan='2'><table style=' font-family: verdana; font-size: 10px;' width='100%' cellspacing='1' cellpadding='0' bgcolor='#000000'>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td width='7%'><div align='center'><strong>No</strong></div></td>
                                    <td width='36%'><div align='center'><strong>Kode</strong></div></td>
                                    <td width='57%'><div align='center'><strong>Tindakan</strong></div></td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>1</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>2</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>3</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>4</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr height='17' bgcolor='#ffffff'>
                                    <td><div align='center'>5</div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>&nbsp;</td>
                                <td width='27%'>&nbsp;</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>&nbsp;</td>
                                <td width='27%'>&nbsp;</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'>21</div></td>
                                <td width='20%'>Jenis Pelayanan yang diterima</td>
                                <td width='27%'>&nbsp;</td>
                                <td width='3%'><div align='center'></div></td>
                                <td width='20%'>&nbsp;</td>
                                <td width='27%'>&nbsp;</td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'></div></td>
                                <td colspan='5'><div align='center'>
                                  <table style=' font-family: verdana; font-size: 10px;' width='100%' cellspacing='1' cellpadding='0' bgcolor='#000000'>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td width='4%'><div align='center'><strong>No</strong></div></td>
                                      <td width='16%'><div align='center'><strong>Hari/Tanggal</strong></div></td>
                                      <td width='23%'><div align='center'><strong>Unit Pelayanan</strong></div></td>
                                      <td width='14%'><div align='center'><strong>Jenis Pelayanan</strong></div></td>
                                      <td width='14%'><div align='center'><strong>Biaya</strong></div></td>
                                      <td width='14%'><div align='center'><strong>T.T.Dr/Prw</strong></div></td>
                                      <td width='15%'><div align='center'><strong>T.T.Px/Kel</strong></div></td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr height='17' bgcolor='#ffffff'>
                                      <td colspan='4'><div align='center'><strong>Total Biaya </strong></div></td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                              <tr height='17'>
                                <td width='3%'><div align='center'></div></td>
                                <td colspan='2'>Catatan:  Ditulis dengan huruf balok</td>
                                <td width='3%'><div align='center'></div></td>
                                <td colspan='2'><p align='center'>&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;<br />
                                  Penanggung Jawab</p>
                                  <p align='center'>&nbsp;</p>
                                  <p align='center'>(...........................................)</p>
                                  <div align='center'>NIP&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;...</div></td>
                              </tr>
                            </table>");
            fwrite($file, "</div></body></html>");
            //fwrite($file, "<script language='javascript'>setTimeout('self.close();',200000)</script>");	
            fclose($file);

            return '1';
        } else {
            return '0';
        }
    }

    public function cekPasienDaftar($id_pasien, $tipe_pendaftaran) {
        if ($tipe_pendaftaran != '6') {
            if ($this->checkJmlDaftar($id_pasien)) {
                return "FALSE";
            } else {
                return "TRUE";
            }
        } else {
            return "TRUE";
        }
    }

    public function setPerawatan($id_pendaftaran) {
        $this->setStatusDaftar($id_pendaftaran);
        return '1';
    }

    public function reClose($id_pendaftaran, $id_penggunaan) {
        $q_close = "UPDATE rm_penggunaan_kamar SET reopen = 0 WHERE id_penggunaan_kamar = " . $id_penggunaan . "";
        $r_close = $this->runQuery($q_close);

        if ($r_close) {
            $c_out = "SELECT id_pendaftaran from rm_pasien_keluar where id_pendaftaran=" . $id_pendaftaran . "";
            $r_out = $this->runQuery($c_out);
            if (@mysql_num_rows($r_out) > 0) {
                $this->generateJasaPoli($id_pendaftaran);
            }
            return '1';
        } else {
            return '0';
        }
    }

    public function bukaPindah($id_penggunaan) {
        $q_close = "UPDATE rm_penggunaan_kamar SET reopen = 1 WHERE id_penggunaan_kamar = " . $id_penggunaan . "";
        $r_close = $this->runQuery($q_close);

        if ($r_close) {
            return '1';
        } else {
            return '0';
        }
    }

}

?>
