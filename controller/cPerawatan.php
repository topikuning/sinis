<?php

session_start();
require_once '../../common/function.php';

class cPerawatan extends fungsi {

    //put your code here    
    public function cariPerawatanRuang(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '%" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT a.id_pendaftaran, c.tipe_pasien, b.tgl_lahir, b.id_kelamin, g.id_penggunaan_kamar, date(g.tgl_masuk) as tgl_masuk, h.ruang, a.id_pasien, b.nama_pasien, b.id_tipe_pasien, h.ruang as ruang_asal, a.id_ruang, c.tipe_pasien, d.kamar, e.id_kamar, e.bed, g.id_detail_kamar, g.tarif, g.askep, g.id_kelas, f.kelas, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, g.status,
                  TIME(g.tgl_masuk) AS jam_daftar, b.alamat FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_kamar d, rm_detail_kamar e,
                  rm_kelas f, rm_penggunaan_kamar g, rm_ruang h WHERE g.status='1' AND a.del_flag<>'1' and b.id_pasien = a.id_pasien AND g.id_ruang='" . $_SESSION['level'] . "' AND f.id_kelas=g.id_kelas AND a.id_tipe_pasien=c.id_tipe_pasien
                  AND a.status_pendaftaran!='2' AND h.id_ruang=g.id_ruang_asal AND g.id_pendaftaran=a.id_pendaftaran AND e.id_detail_kamar=g.id_detail_kamar AND g.del_flag<>1 and a.del_flag<>1 and d.id_kamar=e.id_kamar " . $kondisi . " order by b.id_pasien";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $bed = $rec['bed'];
                $tarif = $rec['tarif'];
                $lama_perawatan = $this->jmlHari($rec['tgl_masuk'], date('Y-m-d')) + 1;
                $q_dok = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                $r_dok = $this->runQuery($q_dok);
                $arr[] = array(
                    'id_penggunaan_kamar' => $rec['id_penggunaan_kamar'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'ruang_asal' => $rec['ruang_asal'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_kamar' => $rec['id_kamar'],
                    'kamar' => $rec['kamar'],
                    'id_detail_kamar' => $rec['id_detail_kamar'],
                    'bed' => $bed,
                    'id_kelas' => $rec['id_kelas'],
                    'kelas' => $rec['kelas'],
                    'lama_perawatan' => $lama_perawatan . " Hari",
                    'tgl_pendaftaran_view' => $this->formatDateDb($rec['tgl_masuk']),
                    'tgl_pendaftaran' => $rec['tgl_masuk'],
                    'jam_daftar' => $rec['jam_daftar'],
                    'tarif' => $tarif,
                    'status' => $rec['status'],
                    "usia" => $this->getUmur($rec['tgl_lahir']),
                    "kelamin" => $this->getKelamin($rec['id_kelamin']),
                    "dokter" => @mysql_result($r_dok, 0, 'id_dokter'),
                    "alamat" => $rec['alamat'],
                    "askep" => $rec['askep']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function cariPerawatanRuangUlang(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and g.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '%" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(g.tgl_masuk) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(g.tgl_masuk)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT g.id_pendaftaran, g.lama_penggunaan, c.tipe_pasien, b.tgl_lahir, b.id_kelamin, g.id_penggunaan_kamar, date(g.tgl_masuk) as tgl_masuk, i.ruang, g.id_pasien, b.nama_pasien, b.id_tipe_pasien, h.ruang as ruang_asal, g.id_ruang, c.tipe_pasien, d.kamar, e.id_kamar, e.bed, g.id_detail_kamar, g.tarif, g.id_kelas, f.kelas, DATE(g.tgl_masuk) AS tgl_pendaftaran, g.keterangan_selesai,
                  TIME(g.tgl_masuk) AS jam_daftar FROM rm_pasien b, rm_tipe_pasien c, rm_kamar d, rm_detail_kamar e, rm_kelas f, rm_penggunaan_kamar g, 
                  rm_ruang h, rm_ruang i WHERE g.del_flag<>'1' and i.id_ruang=g.id_ruang and b.id_pasien = g.id_pasien AND g.id_ruang='" . $_SESSION['level'] . "' AND 
                  f.id_kelas=g.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien AND h.id_ruang=g.id_ruang_asal AND 
                  e.id_detail_kamar=g.id_detail_kamar AND d.id_kamar=e.id_kamar AND g.keterangan_selesai='Pindah Ruang' and g.reopen=1" . $kondisi . " 
                  order by b.id_pasien";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_dok = "select id_dokter from rm_dr_jb where id_pendaftaran='" . $rec['id_pendaftaran'] . "'";
                $r_dok = $this->runQuery($q_dok);
                $arr[] = array(
                    'id_penggunaan_kamar' => $rec['id_penggunaan_kamar'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'ruang_asal' => $rec['ruang_asal'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_kamar' => $rec['id_kamar'],
                    'kamar' => $rec['kamar'],
                    'id_detail_kamar' => $rec['id_detail_kamar'],
                    'bed' => $rec['bed'],
                    'id_kelas' => $rec['id_kelas'],
                    'kelas' => $rec['kelas'],
                    'lama_perawatan' => $rec['lama_penggunaan'] . " Hari",
                    'tgl_pendaftaran_view' => $this->formatDateDb($rec['tgl_masuk']),
                    'tgl_pendaftaran' => $rec['tgl_masuk'],
                    'jam_daftar' => $rec['jam_daftar'],
                    'tarif' => $rec['tarif'],
                    'status' => $rec['keterangan_selesai'],
                    "usia" => $this->getUmur($rec['tgl_lahir']),
                    "kelamin" => $this->getKelamin($rec['id_kelamin']),
                    "dokter" => @mysql_result($r_dok, 0, 'id_dokter')
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function cariPerawatanRuangDiet(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '%" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT a.id_pendaftaran, c.tipe_pasien, g.id_penggunaan_kamar, date(g.tgl_masuk) as tgl_masuk, i.ruang, a.id_pasien, b.nama_pasien, b.id_tipe_pasien, h.ruang as ruang_asal, a.id_ruang, c.tipe_pasien, d.kamar, e.id_kamar, e.bed, g.id_detail_kamar, g.tarif, g.id_kelas, f.kelas, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, g.status,
                  TIME(a.tgl_pendaftaran) AS jam_daftar FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_kamar d, rm_detail_kamar e,
                  rm_kelas f, rm_penggunaan_kamar g, rm_ruang h, rm_ruang i WHERE g.del_flag<>1 AND ( g.status = '1' OR date(g.tgl_keluar) = '" . date('Y-m-d') . "') AND g.status<>'2' AND a.del_flag<>'1' and i.id_ruang=g.id_ruang and b.id_pasien = a.id_pasien 
                  AND f.id_kelas=g.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien AND h.id_ruang=g.id_ruang_asal AND g.id_pendaftaran=a.id_pendaftaran AND e.id_detail_kamar=g.id_detail_kamar AND d.id_kamar=e.id_kamar " . $kondisi . " ORDER BY date(g.tgl_keluar), b.id_pasien";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $bed = $rec['bed'];
                $tarif = $rec['tarif'];
                $lama_perawatan = $this->jmlHari($rec['tgl_masuk'], date('Y-m-d')) + 1;
                $arr[] = array(
                    'id_penggunaan_kamar' => $rec['id_penggunaan_kamar'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'ruang_asal' => $rec['ruang_asal'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_kamar' => $rec['id_kamar'],
                    'kamar' => $rec['kamar'],
                    'id_detail_kamar' => $rec['id_detail_kamar'],
                    'bed' => $bed,
                    'id_kelas' => $rec['id_kelas'],
                    'kelas' => $rec['kelas'],
                    'lama_perawatan' => $lama_perawatan . " Hari",
                    'tgl_pendaftaran_view' => $this->codeDate($rec['tgl_masuk']),
                    'tgl_pendaftaran' => $rec['tgl_masuk'],
                    'jam_daftar' => $rec['jam_daftar'],
                    'tarif' => $tarif,
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function cariPerawatanRuangUtilitas(
    $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and a.id_pasien='" . $id_pasien . "'";
        if ($pasien != "")
            $kondisi .= " and b.nama_pasien like '%" . @mysql_escape_string($pasien) . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(a.tgl_pendaftaran) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(a.tgl_pendaftaran)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT a.id_pendaftaran, c.tipe_pasien, g.id_penggunaan_kamar, date(g.tgl_masuk) as tgl_masuk, i.ruang, a.id_pasien, b.nama_pasien, b.id_tipe_pasien, h.ruang as ruang_asal, a.id_ruang, c.tipe_pasien, d.kamar, e.id_kamar, e.bed, g.id_detail_kamar, g.tarif, g.id_kelas, f.kelas, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, g.status,
                  TIME(a.tgl_pendaftaran) AS jam_daftar, date(g.tgl_keluar) as sort FROM rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c, rm_kamar d, rm_detail_kamar e,
                  rm_kelas f, rm_penggunaan_kamar g, rm_ruang h, rm_ruang i WHERE g.del_flag<>1 AND g.status<>'2' AND a.del_flag<>'1' and i.id_ruang=g.id_ruang and b.id_pasien = a.id_pasien 
                  AND f.id_kelas=g.id_kelas AND b.id_tipe_pasien=c.id_tipe_pasien AND h.id_ruang=g.id_ruang_asal AND g.id_pendaftaran=a.id_pendaftaran AND e.id_detail_kamar=g.id_detail_kamar AND d.id_kamar=e.id_kamar " . $kondisi . "
UNION ALL
SELECT
a.id_pendaftaran, c.tipe_pasien, '-' as id_penggunaan_kamar, date(a.tgl_pendaftaran) as tgl_masuk, i.ruang, a.id_pasien, b.nama_pasien, b.id_tipe_pasien, h.ruang as ruang_asal, a.id_ruang, c.tipe_pasien, i.ruang as kamar, '-' as id_kamar, '-' as bed, '-' as id_detail_kamar, '-' as tarif, a.id_kelas, f.kelas, DATE(a.tgl_pendaftaran) AS tgl_pendaftaran, if(a.status_pendaftaran=2,3,1) as `status`,TIME(a.tgl_pendaftaran) AS jam_daftar, date(a.tgl_pendaftaran) as sort
FROM
rm_pendaftaran a, rm_pasien b, rm_tipe_pasien c,rm_kelas f, rm_ruang h, rm_ruang i
WHERE
a.del_flag<>'1' and b.id_pasien = a.id_pasien AND f.id_kelas=a.id_kelas AND a.id_tipe_pasien=c.id_tipe_pasien AND h.id_ruang=a.id_ruang_asal AND i.id_ruang=a.id_ruang and a.id_tipe_pendaftaran<>6 and a.id_asal_pendaftaran=0 and status_pembayaran<>2 ". $kondisi ." ORDER BY sort, id_pasien";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $bed = $rec['bed'];
                $tarif = $rec['tarif'];
                $lama_perawatan = $this->jmlHari($rec['tgl_masuk'], date('Y-m-d')) + 1;
                $arr[] = array(
                    'id_penggunaan_kamar' => $rec['id_penggunaan_kamar'],
                    'id_pendaftaran' => $rec['id_pendaftaran'],
                    'id_pasien' => $rec['id_pasien'],
                    'id_tipe_pasien' => $rec['id_tipe_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'id_ruang' => $rec['id_ruang'],
                    'ruang' => $rec['ruang'],
                    'ruang_asal' => $rec['ruang_asal'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'id_kamar' => $rec['id_kamar'],
                    'kamar' => $rec['kamar'],
                    'id_detail_kamar' => $rec['id_detail_kamar'],
                    'bed' => $bed,
                    'id_kelas' => $rec['id_kelas'],
                    'kelas' => $rec['kelas'],
                    'lama_perawatan' => $lama_perawatan . " Hari",
                    'tgl_pendaftaran_view' => $this->codeDate($rec['tgl_masuk']),
                    'tgl_pendaftaran' => $rec['tgl_masuk'],
                    'jam_daftar' => $rec['jam_daftar'],
                    'tarif' => $tarif,
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function simpanDetailDiet(
    $id_pendaftaran, $id_detail_diet, $id_pasien, $id_diet, $id_jenis_diet, $waktu_diet, $tgl_diet, $keterangan, $ruangan
    ) {
        if (isset($_SESSION['level'])) {
            if ($_SESSION['level'] == 45) {
                $id_ruang = $ruangan;
            } else {
                $id_ruang = $_SESSION['level'];
            }
            $q_check = "select * from rm_detail_diet where id_pendaftaran='" . $id_pendaftaran . "' 
                    and tgl_diet='" . $this->formatDateDb($tgl_diet) . "' and waktu_diet='" . $waktu_diet . "' and del_flag<>'1'";
            $r_check = $this->runQuery($q_check);
            if ((@mysql_num_rows($r_check) > 0)) {
                return '2';
            } else {
                if ($id_detail_diet == "") {
                    $query = "insert into rm_detail_diet (
                            id_pendaftaran,
                            id_pasien,
                            id_ruang,
                            id_detail_kamar,
                            id_diet,
                            id_jenis_diet,
                            tgl_diet,
                            waktu_diet,
                            keterangan
                         ) values (
                            '" . $id_pendaftaran . "',
                            '" . $id_pasien . "',
                            '" . $id_ruang . "',
                            '" . $this->getDetailKamarId($id_pendaftaran) . "',
                            '" . $id_diet . "',
                            '" . $id_jenis_diet . "',
                            '" . $this->formatDateDb($tgl_diet) . "',
                            '" . $waktu_diet . "',
                            '" . $keterangan . "'
                         )";
                } else {
                    $query = "update rm_detail_diet set
                            id_diet='" . $id_diet . "',
                            id_jenis_diet='" . $id_jenis_diet . "',
                            tgl_diet='" . $this->formatDateDb($tgl_diet) . "',
                            waktu_diet='" . $waktu_diet . "',
                            keterangan='" . $keterangan . "'
                            where id_detail_diet='" . $id_detail_diet . "'";
                }

                $result = $this->runQuery($query);

                if ($result) {
                    return '1';
                } else {
                    return '0';
                }
            }
        }
        return 'LOGIN';
    }

    public function simpanVisitDokter(
    $id_pendaftaran, $id_visit, $id_pasien, $id_dokter, $tgl_visit, $tarif
    ) {
        if (isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                $id_kelas = $this->getIdKelas($id_pendaftaran);
                $tipe_pasien = $this->getTipePasienId($id_pasien);
                if ($id_visit == "") {
                    $query = "insert into rm_visit (
                        id_pendaftaran,
                        id_pasien,
                        id_detail_kamar,
                        id_ruang,
                        id_dokter,
                        tgl_visit,
			id_kelas,
                        tarif,
                        id_tipe_pasien
                     ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $this->getDetailKamarId($id_pendaftaran) . "',
                        '" . $_SESSION['level'] . "',
                        '" . $id_dokter . "',
                        '" . $this->formatDateDb($tgl_visit) . "',
			'" . $id_kelas . "',
                        '" . $tarif . "',
                        '" . $tipe_pasien . "'
                     )";
                } else {
                    $query = "update rm_visit set
                        id_dokter='" . $id_dokter . "',
                        tgl_visit='" . $this->formatDateDb($tgl_visit) . "',
                        tarif='" . $tarif . "',
                            id_tipe_pasien='" . $tipe_pasien . "'
                     where id_visit='" . $id_visit . "'";
                }

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

    public function simpanPindahRuang(
    $id_pendaftaran, $doubleBed, $id_penggunaan_kamar, $id_pasien, $tgl_masuk, $tgl_pindah, $ruang_tujuan, $kelas_tujuan, $kamar_tujuan, $bed_tujuan, $jam_masuk, $jam_pindah
    ) {
        if (isset($_SESSION['level'])) {
            $lama_nginep = $this->jmlHari($tgl_masuk, $this->formatDateDb($tgl_pindah));
            $cek_lama = $this->jmlHari(date('Y-m-d'),$this->formatDateDb($tgl_pindah));
            if ($lama_nginep >= 0 && $cek_lama<=0) {
                if ($this->checkStatusPembayaran($id_pendaftaran)) {
                    $tarifKamar = $this->getTarifKamarInap($kamar_tujuan, $kelas_tujuan);
                    $askep = $this->getTarifAskepKamar($kamar_tujuan, $kelas_tujuan);
                    $tipe_pasien = $this->getTipePasienId($id_pasien);
                    if ($doubleBed == "2") {
                        $tgl_p = $this->formatDateDb($tgl_pindah) . " " . $jam_pindah;
                        $today = date('Y-m-d') . " 23:59:59";
                        if ($this->formatDateDb($tgl_pindah) == $tgl_masuk) {
                            $before = $tgl_masuk . " " . $jam_masuk;
                        } else {
                            $before = date('Y-m-d') . " 00:00:01";
                        }
                        $lama_pindah = $this->jmlJam($before, $tgl_p);
                        $lama_baru = $this->jmlJam($tgl_p, $today);
                        if ($lama_pindah > $lama_baru) {
                            $lama_perawatan = $lama_nginep + 1;
                            $q_interval = "SELECT DATE_ADD('" . $this->formatDateDb($tgl_pindah) . "', INTERVAL 1 DAY) as new";
                            $r_int = $this->runQuery($q_interval);
                            $waktune = @mysql_result($r_int, 0, 'new');
                        } else if ($lama_pindah <= $lama_baru) {
                            $lama_perawatan = $lama_nginep;
                            $waktune = $this->formatDateDb($tgl_pindah) . " " . $jam_pindah;
                        }
                        $query = "insert into rm_penggunaan_kamar (
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
                            '" . $_SESSION['level'] . "',
                            '" . $ruang_tujuan . "',
                            '" . $kelas_tujuan . "',
                            '" . $bed_tujuan . "',
                            '" . $waktune . "',
                            '" . $tarifKamar . "',
                            '" . $askep . "',
                            '" . $tipe_pasien . "',
                            '" . $_SERVER['REMOTE_ADDR'] . "'
                        )";
                    } else {
                        $query = "insert into rm_penggunaan_kamar_extra (
                            id_pendaftaran,
                            id_pasien,
                            id_ruang_asal,
                            id_ruang,
                            id_kelas,
                            id_detail_kamar,
                            tgl_masuk,
                            tarif
                        ) values (
                            '" . $id_pendaftaran . "',
                            '" . $id_pasien . "',
                            '" . $_SESSION['level'] . "',
                            '" . $ruang_tujuan . "',
                            '" . $kelas_tujuan . "',
                            '" . $bed_tujuan . "',
                            '" . $this->formatDateDb($tgl_pindah) . " " . $jam_pindah . "',
                            '" . $tarifKamar . "'
                        )";
                    }

                    $result = $this->runQuery($query);

                    if ($result) {
                        $q_daftar = "update rm_pendaftaran set id_kelas='" . $kelas_tujuan . "', id_ruang='" . $ruang_tujuan . "' where id_pendaftaran='" . $id_pendaftaran . "'";
                        $this->runQuery($q_daftar);
                        if ($doubleBed == "2") {

                            $q_update = "update rm_penggunaan_kamar set
                                tgl_keluar='" . $this->formatDateDb($tgl_pindah) . " " . $jam_pindah . "',
                                keterangan_selesai='Pindah Ruang',
                                lama_penggunaan='" . $lama_perawatan . "',
                                status='2',
                                ip='" . $_SERVER['REMOTE_ADDR'] . "'
                            where id_penggunaan_kamar='" . $id_penggunaan_kamar . "'";
                            $r_update = $this->runQuery($q_update);
                            if ($r_update) {
                                $q_kamar = "update rm_detail_kamar set status='0' WHERE id_detail_kamar IN (
                                SELECT id_detail_kamar FROM rm_penggunaan_kamar 
                                WHERE id_penggunaan_kamar='" . $id_penggunaan_kamar . "')";
                                $r_kamar = $this->runQuery($q_kamar);
                                $t_kamar = "update rm_detail_kamar set status='1' WHERE id_detail_kamar = '" . $bed_tujuan . "'";
                                $t_kamar = $this->runQuery($t_kamar);
                                return '1';
                            } else {
                                return '0';
                            }
                        } else {
                            return '1';
                        }
                    } else {
                        return '0';
                    }
                } else {
                    return 'LUNAS';
                }
            } else {
                return 'TANGGAL';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanPindahKamar(
    $id_penggunaan_kamar, $bed_tujuan
    ) {
        if (isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($this->cekDaftarKamar($id_penggunaan_kamar))) {
                $q_cek = "SELECT id_kelas FROM rm_penggunaan_kamar WHERE id_penggunaan_kamar='" . $id_penggunaan_kamar . "'";
                $r_cek = $this->runQuery($q_cek);
                $id_kelas = @mysql_result($r_cek, 0, 'id_kelas');
                $id_kamar = $this->getKamarDet($bed_tujuan);

                $q_kamar = "UPDATE rm_detail_kamar set status='0' WHERE id_detail_kamar IN (
                    SELECT id_detail_kamar FROM rm_penggunaan_kamar 
                    WHERE id_penggunaan_kamar='" . $id_penggunaan_kamar . "')";
                $r_kamar = $this->runQuery($q_kamar);

                $tarifKamar = $this->getTarifKamarInap($id_kamar, $id_kelas);
                $askep = $this->getTarifAskepKamar($id_kamar, $id_kelas);
                $query = "update rm_penggunaan_kamar set
                  tarif='" . $tarifKamar . "',
                  askep='" . $askep . "',
                  id_detail_kamar='" . $bed_tujuan . "',
                  ip='" . $_SERVER['REMOTE_ADDR'] . "'
                  where id_penggunaan_kamar='" . $id_penggunaan_kamar . "'";

                $result = $this->runQuery($query);

                if ($result) {
                    $q_kamar = "update rm_detail_kamar set status='1' WHERE id_detail_kamar='" . $bed_tujuan . "'";
                    $r_kamar = $this->runQuery($q_kamar);

                    return '1';
                } else {
                    return '0';
                }
            } else {
                return 'LUNAS';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanSurveyIGD(
    $id_survey, $id_pendaftaran, $id_pasien, $pekerjaan, $triage, $transportasi, $jTrans, $pengantar, $asuransi, $inform, $ic, $kasus, $jKasus, $emergency, $status, $lanjut, $alergi, $medikasi, $teratur, $rpd, $amenor, $jam_datang, $jam_periksa, $jam_terapi, $jam_lanjut, $bagian, $peristiwa, $jam_d, $jam_p, $jam_t, $jam_l, $saving
    ) {
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $cSurvey = "SELECT id_igd_survey FROM rm_igd_survey WHERE id_pendaftaran='" . $id_pendaftaran . "'";
        $jalankan = $this->runQuery($cSurvey);
        if ($id_survey == "")
            $id_survey = @mysql_result($jalankan, 0, 'id_igd_survey');
        if ($id_survey == "" && @mysql_num_rows($jalankan) == 0) {
            $query = "insert into rm_igd_survey (
                        id_pendaftaran,
                        id_pasien,
                        id_level,
                        pekerjaan,
                        triage,
                        transportasi,
                        jenis_transportasi,
                        pengantar,
                        id_tipe_asuransi,
                        inform,
                        ic,
                        kasus,
                        jenis_kasus,
                        emergency,
                        status,
                        datang,
                        periksa,
                        terapi,
                        tindak_lanjut,
                        waktu_tindak_lanjut,
                        alergi,
                        medikasi,
                        medikasi_teratur,
                        rpd,
                        amenorhae,
                        id_bagian,
                        id_peristiwa,
                        id_tipe_pasien,
                        saving
                    ) values (
                        '" . $id_pendaftaran . "', 
                        '" . $id_pasien . "', 
                        '" . $_SESSION['level'] . "',
                        '" . $pekerjaan . "',
                        '" . $triage . "',
                        '" . $transportasi . "',
                        '" . $jTrans . "',
                        '" . $pengantar . "',
                        '" . $asuransi . "',
                        '" . $inform . "',
                        '" . $ic . "',
                        '" . $kasus . "',
                        '" . $jKasus . "',
                        '" . $emergency . "',
                        '" . $status . "',
                        '" . $this->formatDateDb($jam_datang) . " " . $jam_d . "', 
                        '" . $this->formatDateDb($jam_periksa) . " " . $jam_p . "', 
                        '" . $this->formatDateDb($jam_terapi) . " " . $jam_t . "', 
                        '" . $lanjut . "',
                        '" . $this->formatDateDb($jam_lanjut) . " " . $jam_l . "',
                        '" . $alergi . "',
                        '" . $medikasi . "',
                        '" . $teratur . "',
                        '" . $rpd . "',
                        '" . $amenor . "',
                        '" . $bagian . "',
                        '" . $peristiwa . "',
                        '" . $tipe_pasien . "',
                        '" . $saving . "'
                    )";
        } else {
            $query = "UPDATE rm_igd_survey SET 
                        pekerjaan='" . $pekerjaan . "',
                        triage='" . $triage . "',
                        transportasi='" . $transportasi . "',
                        jenis_transportasi='" . $jTrans . "',
                        pengantar='" . $pengantar . "',
                        id_tipe_asuransi='" . $asuransi . "',
                        inform='" . $inform . "',
                        ic='" . $ic . "',
                        kasus='" . $kasus . "',
                        jenis_kasus='" . $jKasus . "',
                        emergency='" . $emergency . "',
                        status='" . $status . "',
                        datang='" . $this->formatDateDb($jam_datang) . " " . $jam_d . "',
                        periksa='" . $this->formatDateDb($jam_periksa) . " " . $jam_p . "',
                        terapi='" . $this->formatDateDb($jam_terapi) . " " . $jam_t . "',
                        tindak_lanjut='" . $lanjut . "',
                        waktu_tindak_lanjut='" . $this->formatDateDb($jam_lanjut) . " " . $jam_l . "',
                        alergi='" . $alergi . "',
                        medikasi='" . $medikasi . "',
                        medikasi_teratur='" . $teratur . "',
                        rpd='" . $rpd . "',
                        amenorhae='" . $amenor . "',
                        id_bagian= '" . $bagian . "',
                        id_peristiwa='" . $peristiwa . "',
                        id_tipe_pasien='" . $tipe_pasien . "',
                        saving='" . $saving . "'
                        WHERE id_igd_survey=" . $id_survey . "";
        }
        $result = $this->runQuery($query);
        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function simpanSummary(
    $id_pendaftaran, $id_pasien, $id_summary, $id_diag, $id_detD, $dokter, $keluhan, $lama, $penyakitLama, $obtAkhir, $etiologi, $tinggi_badan, $berat_badan, $nadi, $tekanan_darah, $temperatur, $nafas, $hasilLab, $hasilRad, $diagAkhir, $diagPa, $masalah, $konsul, $tindakan, $fasilitas, $perjalanan, $keadaan, $progno, $sebabMati, $usul, $penyakitPrimer, $penyakitPrimerId
    ) {
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        if ($id_diag == "") {
            $dq = "SELECT id_diagnosa FROM rm_diagnosa WHERE id_pendaftaran='" . $id_pendaftaran . "'";
            $rdq = $this->runQuery($dq);
            $id_diag = @mysql_result($rdq, 0, 'id_diagnosa');
        }
        if ($id_detD == "") {
            $deq = "SELECT id_detail_diagnosa FROM rm_detail_diagnosa WHERE id_pendaftaran='" . $id_pendaftaran . "'";
            $rdeq = $this->runQuery($deq);
            $id_detD = @mysql_result($rdeq, 0, 'id_detail_diagnosa');
        }

        $cSum = "SELECT id_summary FROM rm_summary WHERE id_pendaftaran='" . $id_pendaftaran . "'";
        $jalan = $this->runQuery($cSum);
        if ($id_summary == "")
            $id_summary = @mysql_result($jalan, 0, 'id_summary');

        if ($id_summary == "" && @mysql_num_rows($jalan) == 0) {
            $query = "insert into rm_summary (
                            id_pendaftaran,
                            id_pasien,
                            id_dokter,
                            keluhan_utama,
                            lama_penyakit,
                            penyakit_terdahulu,
                            pengobatan_terakhir,
                            faktor_etiologi,
                            tinggi_badan,
                            berat_badan,
                            nadi,
                            tensi,
                            temperatur,
                            nafas,
                            hasil_lab,
                            radiologi,
                            diagnosa_terakhir,
                            diagnosis_pa,
                            masalah,
                            konsultasi,
                            pengobatan,
                            fasilitas,
                            perjalanan_penyakit,
                            id_keadaan_kelaur,
                            prognosis,
                            id_sebab_meninggal,
                            usulan,
                            date_entry,
                            id_tipe_pasien
                        ) values (
                            '" . $id_pendaftaran . "',
                            '" . $id_pasien . "',
                            '" . $dokter . "',
                            '" . @mysql_escape_string($keluhan) . "',
                            '" . @mysql_escape_string($lama) . "',
                            '" . @mysql_escape_string($penyakitLama) . "',
                            '" . @mysql_escape_string($obtAkhir) . "',
                            '" . @mysql_escape_string($etiologi) . "',
                            '" . @mysql_escape_string($tinggi_badan) . "',
                            '" . @mysql_escape_string($berat_badan) . "',
                            '" . @mysql_escape_string($nadi) . "',
                            '" . @mysql_escape_string($tekanan_darah) . "',
                            '" . @mysql_escape_string($temperatur) . "',
                            '" . @mysql_escape_string($nafas) . "',
                            '" . @mysql_escape_string($hasilLab) . "',
                            '" . @mysql_escape_string($hasilRad) . "',
                            '" . @mysql_escape_string($penyakitPrimer) . "',
                            '" . @mysql_escape_string($diagPa) . "',
                            '" . @mysql_escape_string($masalah) . "',
                            '" . @mysql_escape_string($konsul) . "',
                            '" . @mysql_escape_string($tindakan) . "',
                            '" . @mysql_escape_string($fasilitas) . "',
                            '" . @mysql_escape_string($perjalanan) . "',
                            '" . $keadaan . "',
                            '" . @mysql_escape_string($progno) . "',
                            '" . $sebabMati . "',
                            '" . @mysql_escape_string($usul) . "',
                            '" . date('Y-m-d H:i:s') . "',
                            '" . $tipe_pasien . "'
                        )";
            //DIAGNOSA
            $qDiag = "INSERT INTO rm_diagnosa (tgl_diagnosa,id_pendaftaran,id_pasien,id_tipe_pasien,id_dokter,penyakit_primer) VALUES
                                              ('" . date('Y-m-d H:i:s') . "','" . $id_pendaftaran . "','" . $id_pasien . "','" . $tipe_pasien . "','" . $dokter . "','" . $penyakitPrimerId . "')";
            //DETIL DIAGNOSA
            $qDet = "INSERT INTO rm_detail_diagnosa (id_pendaftaran,id_pasien,id_tipe_pasien,tgl_diagnosa,keluhan,terapi,nadi,tensi,temp,nafas,berat_badan,tinggi_badan) VALUES 
                                                    ('" . $id_pendaftaran . "','" . $id_pasien . "','" . $tipe_pasien . "','" . date('Y-m-d H:i:s') . "','" . $keluhan . "','" . $obtAkhir . "','" . $nadi . "','" . $tekanan_darah . "','" . $temperatur . "','" . $nafas . "','" . $berat_badan . "','" . $tinggi_badan . "')";
        } else {
            $query = "update rm_summary set
                            id_dokter='" . $dokter . "',
                            keluhan_utama='" . @mysql_escape_string($keluhan) . "',
                            lama_penyakit='" . @mysql_escape_string($lama) . "',
                            penyakit_terdahulu='" . @mysql_escape_string($penyakitLama) . "',
                            pengobatan_terakhir='" . @mysql_escape_string($obtAkhir) . "',
                            faktor_etiologi='" . @mysql_escape_string($etiologi) . "',
                            tinggi_badan='" . @mysql_escape_string($tinggi_badan) . "',
                            berat_badan='" . @mysql_escape_string($berat_badan) . "',
                            nadi='" . @mysql_escape_string($nadi) . "',
                            tensi='" . @mysql_escape_string($tekanan_darah) . "',
                            temperatur='" . @mysql_escape_string($temperatur) . "',
                            nafas='" . @mysql_escape_string($nafas) . "',
                            hasil_lab='" . @mysql_escape_string($hasilLab) . "',
                            radiologi='" . @mysql_escape_string($hasilRad) . "',
                            diagnosa_terakhir='" . @mysql_escape_string($penyakitPrimer) . "',
                            diagnosis_pa='" . @mysql_escape_string($diagPa) . "',
                            masalah='" . @mysql_escape_string($masalah) . "',
                            konsultasi='" . @mysql_escape_string($konsul) . "',
                            pengobatan='" . @mysql_escape_string($tindakan) . "',
                            fasilitas='" . @mysql_escape_string($fasilitas) . "',
                            perjalanan_penyakit='" . @mysql_escape_string($perjalanan) . "',
                            id_keadaan_kelaur='" . @mysql_escape_string($keadaan) . "',
                            prognosis='" . @mysql_escape_string($progno) . "',
                            id_sebab_meninggal='" . @mysql_escape_string($sebabMati) . "',
                            usulan='" . @mysql_escape_string($usul) . "',
                            id_tipe_pasien='" . $tipe_pasien . "'
                      where id_summary='" . $id_summary . "'";
            //DIAGNOSA
            $qDiag = "UPDATE rm_diagnosa SET tgl_diagnosa='" . date('Y-m-d H:i:s') . "',
                            id_pendaftaran='" . $id_pendaftaran . "',
                            id_pasien='" . $id_pasien . "',
                            id_tipe_pasien='" . $tipe_pasien . "',
                            id_dokter='" . $dokter . "',
                            penyakit_primer='" . $penyakitPrimerId . "'
                            WHERE id_diagnosa='" . $id_diag . "'";
            //DETIL DIAGNOSA
            $qDet = "UPDATE rm_detail_diagnosa SET id_pendaftaran='" . $id_pendaftaran . "',
                                                   id_pasien='" . $id_pasien . "',
                                                   id_tipe_pasien='" . $tipe_pasien . "',
                                                   tgl_diagnosa='" . date('Y-m-d H:i:s') . "',
                                                   keluhan='" . $keluhan . "',
                                                   terapi='" . $obtAkhir . "',
                                                   nadi='" . $nadi . "',
                                                   tensi='" . $tekanan_darah . "',
                                                   temp='" . $temperatur . "',
                                                   nafas='" . $nafas . "',
                                                   berat_badan='" . $berat_badan . "',
                                                   tinggi_badan='" . $tinggi_badan . "'
                                                   WHERE id_detail_diagnosa='" . $id_detD . "'";
        }
        $doDiag = $this->runQuery($qDiag);
        $doDet = $this->runQuery($qDet);
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return $query;
        }
    }

    public function getSurvey($id_pendaftaran) {
        $query = "SELECT *,date(datang) as datang1, date(periksa) as periksa1, date(terapi) as terapi1, date(waktu_tindak_lanjut) as tlanjut
                ,time(datang) as datang2, time(periksa) as periksa2, time(terapi) as terapi2, time(waktu_tindak_lanjut) as wlanjut
                FROM rm_igd_survey where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            $return = array(
                "id_survey" => @mysql_result($result, 0, 'id_igd_survey'),
                "pekerjaan" => @mysql_result($result, 0, 'pekerjaan'),
                "triage" => @mysql_result($result, 0, 'triage'),
                "transportasi" => @mysql_result($result, 0, 'transportasi'),
                "jTrans" => @mysql_result($result, 0, 'jenis_transportasi'),
                "pengantar" => @mysql_result($result, 0, 'pengantar'),
                "asuransi" => @mysql_result($result, 0, 'id_tipe_asuransi'),
                "inform" => @mysql_result($result, 0, 'inform'),
                "ic" => @mysql_result($result, 0, 'ic'),
                "kasus" => @mysql_result($result, 0, 'kasus'),
                "jKasus" => @mysql_result($result, 0, 'jenis_kasus'),
                "emergency" => @mysql_result($result, 0, 'emergency'),
                "status" => @mysql_result($result, 0, 'status'),
                "lanjut" => @mysql_result($result, 0, 'tindak_lanjut'),
                "alergi" => @mysql_result($result, 0, 'alergi'),
                "medikasi" => @mysql_result($result, 0, 'medikasi'),
                "teratur" => @mysql_result($result, 0, 'medikasi_teratur'),
                "rpd" => @mysql_result($result, 0, 'rpd'),
                "amenor" => @mysql_result($result, 0, 'amenorhae'),
                "bagian" => @mysql_result($result, 0, 'id_bagian'),
                "peristiwa" => @mysql_result($result, 0, 'id_peristiwa'),
                "jam_datang" => $this->formatDateDb(@mysql_result($result, 0, 'datang1')),
                "jam_periksa" => $this->formatDateDb(@mysql_result($result, 0, 'periksa1')),
                "jam_terapi" => $this->formatDateDb(@mysql_result($result, 0, 'terapi1')),
                "jam_lanjut" => $this->formatDateDb(@mysql_result($result, 0, 'tlanjut')),
                "jam_d" => $this->formatDateDb(@mysql_result($result, 0, 'datang2')),
                "jam_p" => $this->formatDateDb(@mysql_result($result, 0, 'periksa2')),
                "jam_t" => $this->formatDateDb(@mysql_result($result, 0, 'terapi2')),
                "jam_l" => $this->formatDateDb(@mysql_result($result, 0, 'wlanjut')),
                "saving" => @mysql_result($result, 0, 'saving'),
            );
        }
        return $this->jEncode($return);
    }

    public function getSummary($id_pendaftaran) {
        $query = "select * from rm_summary where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dq = "SELECT id_diagnosa,penyakit_primer FROM rm_diagnosa WHERE id_pendaftaran='" . $id_pendaftaran . "'";
            $deq = "SELECT id_detail_diagnosa FROM rm_detail_diagnosa WHERE id_pendaftaran='" . $id_pendaftaran . "'";
            $rdq = $this->runQuery($dq);
            $rdeq = $this->runQuery($deq);
            $return = array(
                "id_summary" => @mysql_result($result, 0, 'id_summary'),
                "id_diag" => @mysql_result($rdq, 0, 'id_diagnosa'),
                "id_det" => @mysql_result($rdeq, 0, 'id_detail_diagnosa'),
                "dokter" => @mysql_result($result, 0, 'id_dokter'),
                "keluhan" => @mysql_result($result, 0, 'keluhan_utama'),
                "lama" => @mysql_result($result, 0, 'lama_penyakit'),
                "penyakitLama" => @mysql_result($result, 0, 'penyakit_terdahulu'),
                "obtAkhir" => @mysql_result($result, 0, 'pengobatan_terakhir'),
                "etiologi" => @mysql_result($result, 0, 'faktor_etiologi'),
                "tinggi_badan" => @mysql_result($result, 0, 'tinggi_badan'),
                "berat_badan" => @mysql_result($result, 0, 'berat_badan'),
                "nadi" => @mysql_result($result, 0, 'nadi'),
                "tekanan_darah" => @mysql_result($result, 0, 'tensi'),
                "temperatur" => @mysql_result($result, 0, 'temperatur'),
                "nafas" => @mysql_result($result, 0, 'nafas'),
                "hasilLab" => @mysql_result($result, 0, 'hasil_lab'),
                "hasilRad" => @mysql_result($result, 0, 'radiologi'),
                "penyakitPrimer" => @mysql_result($result, 0, 'diagnosa_terakhir'),
                "penyakitPrimerId" => @mysql_result($rdq, 0, 'penyakit_primer'),
                "diagPa" => @mysql_result($result, 0, 'diagnosis_pa'),
                "masalah" => @mysql_result($result, 0, 'masalah'),
                "konsul" => @mysql_result($result, 0, 'konsultasi'),
                "tindakan" => @mysql_result($result, 0, 'pengobatan'),
                "fasilitas" => @mysql_result($result, 0, 'fasilitas'),
                "perjalanan" => @mysql_result($result, 0, 'perjalanan_penyakit'),
                "keadaan" => @mysql_result($result, 0, 'id_keadaan_kelaur'),
                "progno" => @mysql_result($result, 0, 'prognosis'),
                "sebabMati" => @mysql_result($result, 0, 'id_sebab_meninggal'),
                "usul" => @mysql_result($result, 0, 'usulan'),
            );
        } else {
            $id_ruang = $this->getRuangDaftar($id_pendaftaran);
            $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
            // UNIT PENUNJANG
            $qUP = "SELECT id_pendaftaran FROM rm_pendaftaran WHERE id_asal_pendaftaran='" . $id_pendaftaran . "'";
            $rUP = $this->runQuery($qUP);
            if (@mysql_num_rows($rUP) > 0) {
                while ($up = mysql_fetch_array($rUP)) {
                    $qLAB = "SELECT id_kelompok_lab FROM rm_detail_laboratorium WHERE id_pendaftaran='" . $up['id_pendaftaran'] . "' GROUP BY id_kelompok_lab";
                    $rLab = $this->runQuery($qLAB);
                    if (@mysql_num_rows($rLab) > 0) {
                        while ($lab = mysql_fetch_array($rLab)) {
                            if ($hLab == "") {
                                $hLab .= $this->getKelLab($lab['id_kelompok_lab']);
                            } else {
                                $hLab .= ", " . $this->getKelLab($lab['id_kelompok_lab']);
                            }
                        }
                    }
                    $qRad = "SELECT id_radiologi FROM rm_detail_radiologi WHERE id_pendaftaran='" . $up['id_pendaftaran'] . "' GROUP BY id_radiologi";
                    $rRad = $this->runQuery($qRad);
                    if (@mysql_num_rows($rRad) > 0) {
                        while ($rad = mysql_fetch_array($rRad)) {
                            if ($hRad == "") {
                                $hRad .= $this->getRadiologi($rad['id_radiologi']);
                            } else {
                                $hRad .= ", " . $this->getRadiologi($rad['id_radiologi']);
                            }
                        }
                    }
                }
            }

            //DETAIL DIAGNOSA
            $cDet = "SELECT MAX(id_detail_diagnosa) as id FROM rm_detail_diagnosa WHERE id_pasien='" . $id_pasien . "'";
            $hDet = $this->runQuery($cDet);
            if (@mysql_num_rows($hDet) > 0) {
                $qDet = "SELECT terapi, nadi, tensi, temp, nafas, berat_badan, tinggi_badan, keluhan FROM rm_detail_diagnosa WHERE id_detail_diagnosa='" . @mysql_result($hDet, 0, 'id') . "'";
                $rDet = $this->runQuery($qDet);
            }

            //TINDAKAN
            $cTin = "SELECT id_detail_tindakan as idt FROM rm_tindakan_ruang WHERE id_pasien='" . $id_pasien . "' GROUP BY id_detail_tindakan";
            $hTin = $this->runQuery($cTin);
            if (@mysql_num_rows($hTin) > 0) {
                while ($tin = mysql_fetch_array($hTin)) {
                    $nTin = "SELECT a.tindakan as tind FROM rm_tindakan a, rm_detail_tindakan b WHERE b.id_detail_tindakan ='" . $tin['idt'] . "' AND a.id_tindakan=b.id_tindakan GROUP BY a.id_tindakan";
                    $sTin = $this->runQuery($nTin);
                    if ($tindakan == "") {
                        $tindakan = @mysql_result($sTin, 0, 'tind');
                    } else {
                        $tindakan .= ", " . @mysql_result($sTin, 0, 'tind');
                    }
                }
            }

            // FASILITAS
            $cFas = "SELECT id_detail_tindakan as idf FROM rm_fasilitas_ruang WHERE id_pasien='" . $id_pasien . "' GROUP BY id_detail_tindakan";
            $hFas = $this->runQuery($cFas);
            if (@mysql_num_rows($hFas) > 0) {
                while ($fas = mysql_fetch_array($hFas)) {
                    $nfas = "SELECT a.tindakan as util FROM rm_tindakan a, rm_detail_tindakan b WHERE b.id_detail_tindakan ='" . $fas['idf'] . "' AND a.id_tindakan=b.id_tindakan GROUP BY a.id_tindakan";
                    $sfas = $this->runQuery($nfas);
                    if ($utilitas == "") {
                        $utilitas = @mysql_result($sfas, 0, 'util');
                    } else {
                        $utilitas .= ", " . @mysql_result($sfas, 0, 'util');
                    }
                }
            }

            $return = array(
                "penyakitLama" => $this->getPenyakit($this->getDiagnosaAkhir($id_pendaftaran, $id_pasien, 'penyakit_primer')),
                "tglAkhir" => $this->getDiagnosaAkhir($id_pendaftaran, $id_pasien, 'tgl_diagnosa'),
                "hasilLab" => $hLab,
                "hasilRad" => $hRad,
                "dokter" => $this->getDokterPenanggungJawab($id_pendaftaran),
                "diagAkhir" => $this->getPenyakit($this->getDiagnosaAktif($id_pendaftaran, $id_pasien, 'penyakit_primer')),
                "tinggi_badan" => @mysql_result($rDet, 0, 'tinggi_badan'),
                "berat_badan" => @mysql_result($rDet, 0, 'berat_badan'),
                "nadi" => @mysql_result($rDet, 0, 'nadi'),
                "tekanan_darah" => @mysql_result($rDet, 0, 'tensi'),
                "temperatur" => @mysql_result($rDet, 0, 'temp'),
                "nafas" => @mysql_result($rDet, 0, 'nafas'),
                "keluhan" => @mysql_result($rDet, 0, 'keluhan'),
                "obtAkhir" => @mysql_result($rDet, 0, 'terapi'),
                "tindakan" => $tindakan,
                "fasilitas" => $utilitas,
                "konsul" => $this->getKonsulPasien($id_ruang, $id_pasien)
            );
        }

        return $this->jEncode($return);
    }

    public function getSurveyL($id_pasien, $rows, $offset) {
        $query = "SELECT date(a.datang) AS tgl, b.triage, c.Kasus, d.jkasus, e.Emergency, f.alergi, g.rpd, i.bagian, h.peristiwa FROM
                  rm_igd_survey AS a LEFT JOIN rm_triage AS b ON a.triage = b.id_triage LEFT JOIN rm_kasus AS c ON c.id_kasus = a.kasus
                  LEFT JOIN rm_jkasus AS d ON a.jenis_kasus = d.id_jkasus LEFT JOIN rm_emergency AS e ON a.emergency = e.id_emergency 
                  LEFT JOIN rm_alergi AS f ON a.alergi = f.id_alergi LEFT JOIN rm_rpd AS g ON g.id_rpd = a.rpd LEFT JOIN rm_peristiwa AS h 
                  ON h.id_peristiwa = a.id_peristiwa LEFT JOIN rm_bagian AS i ON a.id_bagian = i.id_bagian WHERE a.del_flag <> 1 and a.id_pasien = " . $id_pasien . " ORDER BY tgl DESC";
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " LIMIT " . $offset . "," . $rows;

        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'tgl' => $this->formatDateDb($rec['tgl']),
                    'triage' => $rec['triage'],
                    'kasus' => $rec['Kasus'],
                    'jkasus' => $rec['jkasus'],
                    'emergency' => $rec['Emergency'],
                    'alergi' => $rec['alergi'],
                    'rpd' => $rec['rpd'],
                    'peristiwa' => $rec['peristiwa'],
                    'bagian' => $rec['bagian']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
        return '{"total":0, "rows":[]}';
    }

    public function getDetailDiet($id_pendaftaran, $rows, $offset) {
        $query = "SELECT a.id_detail_diet, a.id_diet, b.diet, a.id_jenis_diet, c.jenis_diet, a.waktu_diet, a.tgl_diet, a.keterangan
                  FROM rm_detail_diet a, rm_diet b, rm_jenis_diet c
                  WHERE a.id_pendaftaran='" . $id_pendaftaran . "' AND b.id_diet=a.id_diet AND c.id_jenis_diet=a.id_jenis_diet and a.del_flag<>'1'";
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_detail_diet' => $rec['id_detail_diet'],
                    'id_diet' => $rec['id_diet'],
                    'diet' => $rec['diet'],
                    'id_jenis_diet' => $rec['id_jenis_diet'],
                    'jenis_diet' => $rec['jenis_diet'],
                    'waktu_diet' => $rec['waktu_diet'],
                    'tgl_diet' => $this->formatDateDb($rec['tgl_diet']),
                    'keterangan' => $rec['keterangan']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
        return '{"total":0, "rows":[]}';
    }

    public function getDetailVisit($id_pendaftaran, $rows, $offset) {
        $query = "SELECT a.id_visit, b.nama_dokter, c.jenis_dokter, tgl_visit, tarif, a.id_dokter
                  FROM rm_visit a, rm_dokter b, rm_jenis_dokter c
                  WHERE a.del_flag<>'1' AND id_ruang='" . $_SESSION['level'] . "' AND b.id_dokter=a.id_dokter AND c.id_jenis_dokter=b.id_jenis_dokter AND a.id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlTarif = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_visit' => $rec['id_visit'],
                    'dokter' => $rec['nama_dokter'],
                    'jenis_dokter' => $rec['jenis_dokter'],
                    'tgl_visit' => $this->codeDate($rec['tgl_visit']),
                    'id_dokter' => $rec['id_dokter'],
                    'tarif' => $rec['tarif']
                );
                $jmlTarif += $rec['tarif'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"dokter":"Total","tarif":' . $jmlTarif . '}]}';
        } else {
            return '{"total":"", "rows":[],"footer":[]}';
        }
    }

    public function getDiet($id_detail_diet) {
        $query = "SELECT * from rm_detail_diet where id_detail_diet='" . $id_detail_diet . "'";
        $result = $this->runQuery($query);
        $arr = array(
            'id_detail_diet' => @mysql_result($result, 0, 'id_detail_diet'),
            'diet' => @mysql_result($result, 0, 'id_diet'),
            'jns_diet' => @mysql_result($result, 0, 'id_jenis_diet'),
            'checkWaktu' => @mysql_result($result, 0, 'waktu_diet'),
            'tanggalDiet' => $this->formatDateDb(@mysql_result($result, 0, 'tgl_diet')),
            'keterangan' => @mysql_result($result, 0, 'keterangan')
        );
        return $this->jEncode($arr);
    }

    public function getVisit($id_visit) {
        $query = "SELECT * from rm_visit where id_visit='" . $id_visit . "'";
        $result = $this->runQuery($query);
        $arr = array(
            'visit' => @mysql_result($result, 0, 'id_visit'),
            'dokterVisite' => @mysql_result($result, 0, 'id_dokter'),
            'tglVisite' => $this->formatDateDb(@mysql_result($result, 0, 'tgl_visit')),
            'tglPemeriksaane' => $this->formatDateDb(@mysql_result($result, 0, 'tgl_visit')),
            'tarifVisite' => @mysql_result($result, 0, 'tarif'),
            'tarifPemeriksaane' => @mysql_result($result, 0, 'tarif')
        );
        return $this->jEncode($arr);
    }

    public function hapusDiet($id_detail_diet) {
        $query = "update rm_detail_diet set del_flag='1' where id_detail_diet='" . $id_detail_diet . "'";
        $result = $this->runQuery($query);

        if ($result)
            return '1';
        else
            return '0';
    }

    public function simpanPindahKelas($id_pendaftaran, $id_kelas, $id_ruang) {
        if (isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                $query = "update rm_pendaftaran set id_kelas='" . $id_kelas . "' where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $id_ruang . "'";
                $result = $this->runQuery($query);

                if ($result) {
                    $q_kamar = "update rm_penggunaan_kamar set id_kelas='" . $id_kelas . "', ip='" . $_SERVER['REMOTE_ADDR'] . "' where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $id_ruang . "' and status=1";
                    $r_kamar = $this->runQuery($q_kamar);
                    if ($r_kamar)
                        return '1';
                    else
                        return '0';
                } else {
                    return '0';
                }
            } else {
                return 'LUNAS';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanEditKelas(
    $tipe_edit, $id_pendaftaran, $id_pasien, $id_kelas, $tgl_ganti, $tipe_pasien, $tgl_masuke
    ) {
        if (isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                //Umum - ASURANSI
                if ($tipe_edit == "2") {

                    $q_pasien = "update rm_pasien set id_tipe_asuransi='1', id_tipe_pasien='" . $tipe_pasien . "' where id_pasien='" . $id_pasien . "'";
                    $r_pasien = $this->runQuery($q_pasien);

                    if ($r_pasien) {
                        $id_tipe_pasien = $this->getTipePasienId($id_pasien);
                        $query = "update rm_pendaftaran set id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $_SESSION['level'] . "'";
                        $result = $this->runQuery($query);
                        if ($result) {
                            $o_kamar = "SELECT 
                                a.id_pendaftaran,
                                a.id_pasien,
                                a.id_ruang_asal,
                                a.id_ruang,
                                a.id_kelas,
                                a.id_detail_kamar,
                                a.tgl_masuk,
                                a.tarif,
                                date(a.tgl_masuk) as masuk,
                                b.id_kamar
                                FROM rm_penggunaan_kamar a, rm_detail_kamar b 
                                WHERE a.status = 1 and id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_kamar=a.id_detail_kamar";
                            $h_kamar = $this->runQuery($o_kamar);
                            if ($h_kamar) {
                                $lama = $this->jmlHari($tgl_masuke, $this->formatDateDb($tgl_ganti));
                                $q_interval = "SELECT DATE_ADD('" . $this->formatDateDb($tgl_ganti) . "', INTERVAL -1 DAY) as new";
                                $r_int = $this->runQuery($q_interval);
                                $waktune = @mysql_result($r_int, 0, 'new');
                                $qKmr = "SELECT max(id_penggunaan_kamar) as idk FROM rm_penggunaan_kamar WHERE id_pendaftaran=".$id_pendaftaran."
                                                  AND del_flag<>1 and id_ruang=" . $_SESSION['level'] . " and `status`=1";
                                $rq = $this->runQuery($qKmr);
                                $id_penggunaan = @mysql_result($rq, 0, 'idk');
                                $q_kamar = "update rm_penggunaan_kamar set 
                                tgl_keluar='" . $waktune . ' ' . date('H:i:s') . "', 
                                keterangan_selesai='Pindah Asuransi', 
                                lama_penggunaan='" . $lama . "',
                                status='2',
                                ip='" . $_SERVER['REMOTE_ADDR'] . "'
                                where id_penggunaan_kamar=" . $id_penggunaan . "";
                                $r_kamar = $this->runQuery($q_kamar);
                                if ($r_kamar) {
                                    while ($old = @mysql_fetch_array($h_kamar)) {
                                        $tarif_kamar = $this->getTarifKamarInap($old['id_kamar'], $id_kelas);
                                        $askep = $this->getTarifAskepKamar($old['id_kamar'], $id_kelas);
                                        $baru = "INSERT INTO rm_penggunaan_kamar (
                                    id_pendaftaran,
                                    id_pasien,
                                    id_ruang_asal,
                                    id_ruang,
                                    id_kelas,
                                    id_detail_kamar,
                                    tgl_masuk,
                                    tarif,
                                    askep,
                                    id_tipe_pasien
                                    ) values (
                                    '" . $id_pendaftaran . "',
                                    '" . $old['id_pasien'] . "',
                                    '" . $old['id_ruang_asal'] . "',
                                    '" . $old['id_ruang'] . "',
                                    '" . $id_kelas . "',
                                    '" . $old['id_detail_kamar'] . "',
                                    '" . $this->formatDateDb($tgl_ganti) . " 00:00:00',
                                    '" . $tarif_kamar . "',
                                    '" . $askep . "',
                                    '" . $id_tipe_pasien . "'
                            )";
                                        $r_baru = $this->runQuery($baru);
                                        if ($r_baru)
                                            return '1';
                                        else
                                            return '0';
                                    }
                                } else {
                                    return '0';
                                }
                            } else {
                                return '0';
                            }
                        } else {
                            return '0';
                        }
                    } else {
                        return '0';
                    }
                } else if ($tipe_edit == "3") {
                    //ASURANSI - UMUM
                    $q_pasien = "update rm_pasien set id_tipe_asuransi='2', id_tipe_pasien='" . $tipe_pasien . "' where id_pasien='" . $id_pasien . "'";
                    $r_pasien = $this->runQuery($q_pasien);

                    if ($r_pasien) {
                        $id_tipe_pasien = $this->getTipePasienId($id_pasien);
                        $query = "update rm_pendaftaran set id_tipe_pasien='" . $id_tipe_pasien . "' where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $_SESSION['level'] . "'";
                        $result = $this->runQuery($query);
                        if ($result) {
                            $q_kamar = "update rm_penggunaan_kamar set 
                                id_tipe_pasien='" . $id_tipe_pasien . "', ip='" . $_SERVER['REMOTE_ADDR'] . "'
                                where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $_SESSION['level'] . "'";
                            $r_kamar = $this->runQuery($q_kamar);
                            if ($r_kamar)
                                return '1';
                            else
                                return '0';
                        } else {
                            return '0';
                        }
                    } else {
                        return '0';
                    }
                } else {
                    $q_pasien = "update rm_pasien set id_tipe_asuransi='2', id_tipe_pasien='1' where id_pasien='" . $id_pasien . "'";
                    $r_pasien = $this->runQuery($q_pasien);

                    if ($r_pasien) {
                        $id_tipe_pasien = $this->getTipePasienId($id_pasien);
                        $query = "update rm_pendaftaran set id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $_SESSION['level'] . "'";
                        $result = $this->runQuery($query);

                        if ($result) {
                            //$tarif_kamar = $this->getTarifKamarInap($_SESSION['level'], $id_kelas);
                            $q_kamar = "update rm_penggunaan_kamar set ip='" . $_SERVER['REMOTE_ADDR'] . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $_SESSION['level'] . "'";
                            $r_kamar = $this->runQuery($q_kamar);
                            if ($r_kamar) {
                                //Tindakan Ruang
                                $q_tindakan_ruang = "select a.id_tindakan_ruang, b.id_tindakan
                                             from rm_tindakan_ruang a, rm_detail_tindakan b 
                                             where a.id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_tindakan=a.id_detail_tindakan";
                                $r_tindakan_ruang = $this->runQuery($q_tindakan_ruang);
                                while ($tindakan = @mysql_fetch_array($r_tindakan_ruang)) {
                                    $tarif = $this->getTarifTindakanRuang($tindakan['id_tindakan'], $id_kelas);
                                    $q_update = "update rm_tindakan_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_tindakan_ruang='" . $tindakan['id_tindakan_ruang'] . "'";
                                    $this->runQuery($q_update);
                                }
                                //Fasilitas Ruang
                                $q_fasilitas_ruang = "select a.id_fasilitas_ruang, b.id_tindakan
                                             from rm_fasilitas_ruang a, rm_detail_tindakan b 
                                             where a.id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_tindakan=a.id_detail_tindakan";
                                $r_fasilitas_ruang = $this->runQuery($q_fasilitas_ruang);
                                while ($fasilitas = @mysql_fetch_array($r_fasilitas_ruang)) {
                                    $tarif = $this->getTarifTindakanRuang($fasilitas['id_tindakan'], $id_kelas);
                                    $q_update = "update rm_fasilitas_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_fasilitas_ruang='" . $fasilitas['id_fasilitas_ruang'] . "'";
                                    $this->runQuery($q_update);
                                }
                                //Visit
                                $q_visit = "select id_visit, id_dokter
                                    from rm_visit 
                                    where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $_SESSION['level'] . "'";
                                $r_visit = $this->runQuery($q_visit);
                                while ($visit = @mysql_fetch_array($r_visit)) {
                                    $tarif = $this->getTarifVisit($visit['id_dokter'], $id_kelas);
                                    $q_update = "update rm_visit set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_visit='" . $visit['id_visit'] . "'";
                                    $this->runQuery($q_update);
                                }
                                $q_daftar = "select id_pendaftaran, id_ruang from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
                                $r_daftar = $this->runQuery($q_daftar);
                                while ($daftar = @mysql_fetch_array($r_daftar)) {
                                    if ($daftar['id_ruang'] == '17') {
                                        $q_lab = "select id_detail_laboratorium, id_laboratorium from rm_detail_laboratorium where id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and del_flag<>'1'";
                                        $r_lab = $this->runQuery($q_lab);
                                        if ($id_kelas == 9 || $id_kelas == 14) {
                                            $kelase = 2;
                                        } else if ($id_kelas == 13 || $id_kelas == 15) {
                                            $kelase = 6;
                                        } else {
                                            $kelase = $id_kelas;
                                        }
                                        while ($lab = @mysql_fetch_array($r_lab)) {
                                            $tarif = $this->getTarifLaboratorium($lab['id_laboratorium'], $kelase);
                                            $q_update = "update rm_detail_laboratorium set tarif='" . $tarif . "', id_kelas='" . $kelase . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_detail_laboratorium='" . $lab['id_detail_laboratorium'] . "'";
                                            $this->runQuery($q_update);
                                        }
                                    } else if ($daftar['id_ruang'] == '18') {
                                        if ($id_kelas == 9 || $id_kelas == 14) {
                                            $kelase = 2;
                                        } else if ($id_kelas == 13 || $id_kelas == 15) {
                                            $kelase = 6;
                                        } else {
                                            $kelase = $id_kelas;
                                        }
                                        $q_lab = "select id_detail_radiologi, id_radiologi from rm_detail_radiologi where id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and del_flag<>'1'";
                                        $r_lab = $this->runQuery($q_lab);
                                        while ($lab = @mysql_fetch_array($r_lab)) {
                                            $tarif = $this->getTarifRadiologi($lab['id_radiologi'], $kelase);
                                            $q_update = "update rm_detail_radiologi set tarif='" . $tarif . "', id_kelas='" . $kelase . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_detail_radiologi='" . $lab['id_detail_radiologi'] . "'";
                                            $this->runQuery($q_update);
                                        }
                                    } else if ($daftar['id_ruang'] == '22') {
                                        //Tindakan Ruang
                                        $q_tindakan_ruang = "select a.id_tindakan_ruang_medis, b.id_tindakan
                                                     from rm_tindakan_ruang_medis a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_tindakan_medis";
                                        $r_tindakan_ruang = $this->runQuery($q_tindakan_ruang);
                                        if ($id_kelas == 9 || $id_kelas == 14) {
                                            $kelase = 2;
                                        } else if ($id_kelas == 13 || $id_kelas == 15) {
                                            $kelase = 6;
                                        } else {
                                            $kelase = $id_kelas;
                                        }
                                        while ($tindakan = @mysql_fetch_array($r_tindakan_ruang)) {
                                            $tarif = $this->getTarifTindakanRuang($tindakan['id_tindakan'], $kelase);
                                            $q_update = "update rm_tindakan_ruang_medis set tarif='" . $tarif . "', id_kelas='" . $kelase . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_tindakan_ruang_medis='" . $tindakan['id_tindakan_ruang_medis'] . "'";
                                            $this->runQuery($q_update);
                                        }
                                        //Fasilitas Ruang
                                        $q_fasilitas_ruang = "select a.id_fasilitas_ruang, b.id_tindakan
                                                     from rm_fasilitas_ruang a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_detail_tindakan and a.del_flag<>'1'";
                                        $r_fasilitas_ruang = $this->runQuery($q_fasilitas_ruang);
                                        while ($fasilitas = @mysql_fetch_array($r_fasilitas_ruang)) {
                                            $tarif = $this->getTarifTindakanRuang($fasilitas['id_tindakan'], $id_kelas);
                                            $q_update = "update rm_fasilitas_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_fasilitas_ruang='" . $fasilitas['id_fasilitas_ruang'] . "'";
                                            $this->runQuery($q_update);
                                        }
                                    } else {
                                        //Tindakan Ruang
                                        $q_tindakan_ruang = "select a.id_tindakan_ruang, b.id_tindakan
                                                     from rm_tindakan_ruang a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_detail_tindakan";
                                        $r_tindakan_ruang = $this->runQuery($q_tindakan_ruang);
                                        while ($tindakan = @mysql_fetch_array($r_tindakan_ruang)) {
                                            $tarif = $this->getTarifTindakanRuang($tindakan['id_tindakan'], $id_kelas);
                                            $q_update = "update rm_tindakan_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_tindakan_ruang='" . $tindakan['id_tindakan_ruang'] . "'";
                                            $this->runQuery($q_update);
                                        }
                                        //Fasilitas Ruang
                                        $q_fasilitas_ruang = "select a.id_fasilitas_ruang, b.id_tindakan
                                                     from rm_fasilitas_ruang a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_detail_tindakan and a.del_flag<>'1'";
                                        $r_fasilitas_ruang = $this->runQuery($q_fasilitas_ruang);
                                        while ($fasilitas = @mysql_fetch_array($r_fasilitas_ruang)) {
                                            $tarif = $this->getTarifTindakanRuang($fasilitas['id_tindakan'], $id_kelas);
                                            $q_update = "update rm_fasilitas_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_fasilitas_ruang='" . $fasilitas['id_fasilitas_ruang'] . "'";
                                            $this->runQuery($q_update);
                                        }
                                    }
                                }
                                //Kamar
                                $q_kmr = "select id_penggunaan_kamar, id_kamar from rm_penggunaan_kamar a, rm_detail_kamar b 
                                  where id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_kamar=a.id_detail_kamar and a.del_flag<>'1'";
                                $r_kamar = $this->runQuery($q_kmr);
                                while ($kamar = @mysql_fetch_array($r_kamar)) {
                                    $tarif = $this->getTarifKamarInap($kamar['id_kamar'], $id_kelas);
                                    $q_update = "update rm_penggunaan_kamar set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "', ip='" . $_SERVER['REMOTE_ADDR'] . "' where id_penggunaan_kamar='" . $kamar['id_penggunaan_kamar'] . "'";
                                    $this->runQuery($q_update);
                                }
                                return '1';
                            } else {
                                return '0';
                            }
                        } else {
                            return '0';
                        }
                    } else {
                        return '0';
                    }
                }
            } else {
                return 'LUNAS';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanEditHarga(
    $tipe_edit, $id_pendaftaran, $id_pasien, $id_kelas, $dari, $hingga
    ) {
        if (isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                $tAwal = $this->formatDateDb($dari);
                $tAkhir = $this->formatDateDb($hingga);
                $id_tipe_pasien = $this->getTipePasienId($id_pasien);

                if ($id_kelas == 9 || $id_kelas == 14) {
                    $kelase = 2;
                } else if ($id_kelas == 13 || $id_kelas == 15) {
                    $kelase = 6;
                } else {
                    $kelase = $id_kelas;
                }

                //Tindakan Ruang
                $q_tindakan_ruang = "select a.id_tindakan_ruang, b.id_tindakan
                                             from rm_tindakan_ruang a, rm_detail_tindakan b 
                                             where a.id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_tindakan=a.id_detail_tindakan
                                             and date(a.tgl_tindakan) between '" . $tAwal . "' and '" . $tAkhir . "'";
                $r_tindakan_ruang = $this->runQuery($q_tindakan_ruang);
                while ($tindakan = @mysql_fetch_array($r_tindakan_ruang)) {
                    $tarif = $this->getTarifTindakanRuang($tindakan['id_tindakan'], $id_kelas);
                    $q_update = "update rm_tindakan_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_tindakan_ruang='" . $tindakan['id_tindakan_ruang'] . "'";
                    $this->runQuery($q_update);
                }
                //Fasilitas Ruang
                $q_fasilitas_ruang = "select a.id_fasilitas_ruang, b.id_tindakan
                                             from rm_fasilitas_ruang a, rm_detail_tindakan b 
                                             where a.id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_tindakan=a.id_detail_tindakan
                                             and date(a.tgl_tindakan) between '" . $tAwal . "' and '" . $tAkhir . "'";
                $r_fasilitas_ruang = $this->runQuery($q_fasilitas_ruang);
                while ($fasilitas = @mysql_fetch_array($r_fasilitas_ruang)) {
                    $tarif = $this->getTarifTindakanRuang($fasilitas['id_tindakan'], $id_kelas);
                    $q_update = "update rm_fasilitas_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_fasilitas_ruang='" . $fasilitas['id_fasilitas_ruang'] . "'";
                    $this->runQuery($q_update);
                }
                //Visit
                $q_visit = "select id_visit, id_dokter
                                    from rm_visit 
                                    where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang='" . $_SESSION['level'] . "'
                                    and date(tgl_visit) between '" . $tAwal . "' and '" . $tAkhir . "'";
                $r_visit = $this->runQuery($q_visit);
                while ($visit = @mysql_fetch_array($r_visit)) {
                    $tarif = $this->getTarifVisit($visit['id_dokter'], $id_kelas);
                    $q_update = "update rm_visit set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_visit='" . $visit['id_visit'] . "'";
                    $this->runQuery($q_update);
                }
                $q_daftar = "select id_pendaftaran, id_ruang from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1' and date(tgl_pendaftaran) between '" . $tAwal . "' and '" . $tAkhir . "'";
                $r_daftar = $this->runQuery($q_daftar);
                while ($daftar = @mysql_fetch_array($r_daftar)) {
                    if ($daftar['id_ruang'] == '17') {
                        $q_lab = "select id_detail_laboratorium, id_laboratorium from rm_detail_laboratorium where id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and del_flag<>'1'";
                        $r_lab = $this->runQuery($q_lab);
                        while ($lab = @mysql_fetch_array($r_lab)) {
                            $tarif = $this->getTarifLaboratorium($lab['id_laboratorium'], $kelase);
                            $q_update = "update rm_detail_laboratorium set tarif='" . $tarif . "', id_kelas='" . $kelase . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_detail_laboratorium='" . $lab['id_detail_laboratorium'] . "'";
                            $this->runQuery($q_update);
                        }
                    } else if ($daftar['id_ruang'] == '18') {
                        $q_lab = "select id_detail_radiologi, id_radiologi from rm_detail_radiologi where id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and del_flag<>'1'";
                        $r_lab = $this->runQuery($q_lab);
                        while ($lab = @mysql_fetch_array($r_lab)) {
                            $tarif = $this->getTarifRadiologi($lab['id_radiologi'], $kelase);
                            $q_update = "update rm_detail_radiologi set tarif='" . $tarif . "', id_kelas='" . $kelase . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_detail_radiologi='" . $lab['id_detail_radiologi'] . "'";
                            $this->runQuery($q_update);
                        }
                    } else if ($daftar['id_ruang'] == '22') {
                        //Tindakan Ruang
                        $q_tindakan_ruang = "select a.id_tindakan_ruang_medis, b.id_tindakan
                                                     from rm_tindakan_ruang_medis a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_tindakan_medis";
                        $r_tindakan_ruang = $this->runQuery($q_tindakan_ruang);
                        while ($tindakan = @mysql_fetch_array($r_tindakan_ruang)) {
                            $tarif = $this->getTarifTindakanRuang($tindakan['id_tindakan'], $kelase);
                            $q_update = "update rm_tindakan_ruang_medis set tarif='" . $tarif . "', id_kelas='" . $kelase . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_tindakan_ruang_medis='" . $tindakan['id_tindakan_ruang_medis'] . "'";
                            $this->runQuery($q_update);
                        }
                        //Fasilitas Ruang
                        $q_fasilitas_ruang = "select a.id_fasilitas_ruang, b.id_tindakan
                                                     from rm_fasilitas_ruang a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_detail_tindakan and a.del_flag<>'1'";
                        $r_fasilitas_ruang = $this->runQuery($q_fasilitas_ruang);
                        while ($fasilitas = @mysql_fetch_array($r_fasilitas_ruang)) {
                            $tarif = $this->getTarifTindakanRuang($fasilitas['id_tindakan'], $id_kelas);
                            $q_update = "update rm_fasilitas_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_fasilitas_ruang='" . $fasilitas['id_fasilitas_ruang'] . "'";
                            $this->runQuery($q_update);
                        }
                    } else {
                        //Tindakan Ruang
                        $q_tindakan_ruang = "select a.id_tindakan_ruang, b.id_tindakan
                                                     from rm_tindakan_ruang a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_detail_tindakan";
                        $r_tindakan_ruang = $this->runQuery($q_tindakan_ruang);
                        while ($tindakan = @mysql_fetch_array($r_tindakan_ruang)) {
                            $tarif = $this->getTarifTindakanRuang($tindakan['id_tindakan'], $id_kelas);
                            $q_update = "update rm_tindakan_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_tindakan_ruang='" . $tindakan['id_tindakan_ruang'] . "'";
                            $this->runQuery($q_update);
                        }
                        //Fasilitas Ruang
                        $q_fasilitas_ruang = "select a.id_fasilitas_ruang, b.id_tindakan
                                                     from rm_fasilitas_ruang a, rm_detail_tindakan b 
                                                     where a.id_pendaftaran='" . $daftar['id_pendaftaran'] . "' and b.id_detail_tindakan=a.id_detail_tindakan and a.del_flag<>'1'";
                        $r_fasilitas_ruang = $this->runQuery($q_fasilitas_ruang);
                        while ($fasilitas = @mysql_fetch_array($r_fasilitas_ruang)) {
                            $tarif = $this->getTarifTindakanRuang($fasilitas['id_tindakan'], $id_kelas);
                            $q_update = "update rm_fasilitas_ruang set tarif='" . $tarif . "', id_kelas='" . $id_kelas . "', id_tipe_pasien='" . $id_tipe_pasien . "' where id_fasilitas_ruang='" . $fasilitas['id_fasilitas_ruang'] . "'";
                            $this->runQuery($q_update);
                        }
                    }
                }
                return '1';
            } else {
                return 'LUNAS';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function hapusVisit($id_visit) {
        if ($this->checkStatusPembayaran($this->cekDaftarVisit($id_visit))) {
            $query = "update rm_visit set del_flag='1' where id_visit='" . $id_visit . "'";
            $result = $this->runQuery($query);

            if ($result)
                return '1';
            else
                return '0';
        } else {
            return '2';
        }
    }

    public function cetakSummary($id_pendaftaran) {
//        $query = "SELECT a.*, b.nama_pasien, c.nama_dokter, d.keadaan, e.sebab_mati
//                 FROM rm_summary a, rm_pasien b, rm_dokter c, rm_keadaan d, rm_sebab_mati e
//                 WHERE b.id_pasien=a.id_pasien AND c.id_dokter=a.id_dokter AND d.id_keadaan=a.id_keadaan_kelaur 
//                 AND e.id_sebab_mati=a.id_sebab_meninggal AND a.id_pendaftaran='" . $id_pendaftaran . "'";
        $query = "SELECT a.*, b.nama_pasien, c.nama_dokter
                 FROM rm_summary a, rm_pasien b, rm_dokter c
                 WHERE b.id_pasien=a.id_pasien AND c.id_dokter=a.id_dokter AND a.id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $file = fopen("../report/cetakSummary.html", 'w');
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
            fwrite($file, "
                            <table width='100%' class='data'>
                                <tr>
                                    <td width='20%'>
                                        <label>Nama Pasien</label>
                                    </td>
                                    <td width='30%'>
                                        : <b>" . @mysql_result($result, 0, 'nama_pasien') . "</b>
                                    </td>
                                    <td width='20%'>
                                        <label>No Rekam Medis</label>
                                    </td>
                                    <td width='30%'>
                                        : <b>" . @mysql_result($result, 0, 'id_pasien') . "</b>
                                    </td>
                                </tr>
                            </table>
                          ");
            fwrite($file, "
                            <table width='100%' class='data' border='1' style='border-collapse: collapse;'>
                                <tr>
                                    <td>
                                        <label>Dokter</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'nama_dokter') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Keluhan Utama</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'keluhan_utama') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Lama Penyakit</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'lama_penyakit') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Penyakit Terdahulu</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'penyakit_terdahulu') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Obat Terakhir</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'pengobatan_terakhir') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Faktor Etiologi</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'faktor_etiologi') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Tinggi Badan</label>
                                    </td>
                                    <td>
                                        <b>" . @mysql_result($result, 0, 'tinggi_badan') . " cm</b>
                                    </td>
                                    <td>
                                        <label>Berat Badan</label>
                                    </td>
                                    <td>
                                        <b>" . @mysql_result($result, 0, 'berat_badan') . " kg</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Nadi</label>
                                    </td>
                                    <td>
                                        <b>" . @mysql_result($result, 0, 'nadi') . "</b>
                                    </td>
                                    <td>
                                        <label>Tekanan Darah</label>
                                    </td>
                                    <td>
                                        <b>" . @mysql_result($result, 0, 'tensi') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Temperatur</label>
                                    </td>
                                    <td>
                                        <b>" . @mysql_result($result, 0, 'temperatur') . "</b>
                                    </td>
                                    <td>
                                        <label>Nafas</label>
                                    </td>
                                    <td>
                                        <b>" . @mysql_result($result, 0, 'nafas') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Hasil Lab</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'hasil_lab') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Radiologi</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'radiologi') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Diagnosis Akhir</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'diagnosa_terakhir') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Diagnosis PA</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'diagnosis_pa') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Masalah Yang Dihadapi</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'masalah') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Konsultasi</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'konsultasi') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Pengobatan/Tindakan</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'pengobatan') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Fasilitas</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'fasilitas') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Perjalanan Penyakit</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'perjalanan_penyakit') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Keadaan Waktu Keluar RS</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'keadaan') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Prognosis</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'prognosis') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Sebab Meninggal</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'sebab_mati') . "</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Usul Tindak Lanjut</label>
                                    </td>
                                    <td colspan='3'>
                                        <b>" . @mysql_result($result, 0, 'usul') . "</b>
                                    </td>
                                </tr>
                            </table>
                          ");
            fwrite($file, "
                            <table width='100%' class='data'>
                                <tr>
                                    <td width='50%'>
                                        &nbsp;
                                    </td>
                                    <td align='center'>
                                        Lamongan, ....................<br>
                                        Dokter yg merawat<br><br><br>
                                        <b><u>" . @mysql_result($result, 0, 'nama_dokter') . "</u></b>
                                    </td>
                                </tr>
                            </table>
                          ");
            fwrite($file, "</div></body></html>");
            fwrite($file, "<script language='javascript'>setTimeout('self.close();',50000)</script>");
            fclose($file);

            return '1';
        } else {
            return '0';
        }
    }

}

?>
