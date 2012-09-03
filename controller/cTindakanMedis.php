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
class cTindakanMedis extends fungsi {

    //put your code here    
    public function getPenggunaanKamar($id_pendaftaran) {
        $query = "select * from rm_penggunaan_kamar where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_penggunaan_kamar' => $rec['id_penggunaan_kamar'],
                    'no_bed' => $this->getBed($rec['id_detail_kamar']),
                    'tgl_masuk' => $rec['tgl_masuk'],
                    'tgl_keluar' => $rec['tgl_keluar'],
                    'ket_selesai' => $rec['keterangan_selesai'],
                    'lama' => $rec['lama_penggunaan'],
                    'tarif' => $rec['tarif'],
                    'total' => ($rec['lama_penggunaan'] * $rec['tarif'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
    }

    public function getTindakanRuang($id_pendaftaran, $rows, $offset) {
        $query = "select * from rm_tindakan_ruang_medis where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        $jmlTarif = 0;

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_tindakan_ruang_medis' => $rec['id_tindakan_ruang_medis'],
                    'tindakan' => $this->getTindakan($rec['id_tindakan_medis'], 'tindakan'),
                    'dokter_operator' => $this->getDokter($rec['dokter_operator']),
                    'dokter_anastesi' => $this->getDokter($rec['dokter_anastesi']),
                    'advice' => $rec['advice'],
                    'cito' => $rec['cito'],
                    'tarif' => $rec['tarif'] + $rec['penambahan_tarif']
                );

                $jmlTarif += $rec['tarif'] + $rec['penambahan_tarif'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"tindakan":"Jumlah","tarif":' . $jmlTarif . '}]}';
        } else {
            return '{"total":"", "rows":[],"footer":[]}';
        }
    }

    public function getFasilitasRuang($id_pendaftaran, $rows, $offset) {
        $query = "select * from rm_fasilitas_ruang where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        $jmlTarif = 0;

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_fasilitas_ruang' => $rec['id_fasilitas_ruang'],
                    'tindakan' => $this->getTindakan($rec['id_detail_tindakan'], 'tindakan'),
                    'jumlah' => $rec['jumlah'],
                    'dokter' => $this->getDokter($rec['id_dokter']),
                    'operator' => $this->getOperator($rec['id_pelaku_tindakan']),
                    'advice' => $rec['advice'],
                    'tarif' => $rec['tarif']
                );

                $jmlTarif += $rec['tarif'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"tindakan":"Jumlah","tarif":' . $jmlTarif . '}]}';
        }
    }

    public function getBarangTindakan($id_pendaftaran, $rows, $offset) {
        $query = "SELECT a.id_barang_tindakan, b.barang, c.jumlah_stock, a.jumlah, b.satuan, a.tarif, (a.jumlah * a.tarif) AS total
                    FROM rm_barang_tindakan a, rm_barang b, rm_stock_barang c
                    WHERE b.id_barang=a.id_barang AND c.id_barang=a.id_barang AND a.id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        $jmlTarif = 0;

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_barang_tindakan' => $rec['id_barang_tindakan'],
                    'barang' => $rec['barang'],
                    'stock' => $rec['jumlah_stock'],
                    'jumlah' => $rec['jumlah'],
                    'satuan' => $rec['satuan'],
                    'tarif' => $rec['tarif'],
                    'total' => $rec['total']
                );

                $jmlTarif += $rec['total'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"barang":"Jumlah","total":' . $jmlTarif . '}]}';
        }
    }

    public function getDataListTindakan($tindakan, $ruang, $jns_tindakan, $rows, $offset) {
        $kondisi = "";

        if ($tindakan != '') {
            if ($jns_tindakan != '') {
                if ($ruang != '') {
                    $kondisi = " and tindakan like '" . @mysql_escape_string($tindakan) . "%' and id_jenis_tindakan='" . $jns_tindakan . "' and id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = " and tindakan like '" . @mysql_escape_string($tindakan) . "%' and id_jenis_tindakan='" . $jns_tindakan . "'";
                }
            } else {
                if ($ruang != '') {
                    $kondisi = " and tindakan like '" . @mysql_escape_string($tindakan) . "%' and id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = " and tindakan like '" . @mysql_escape_string($tindakan) . "%'";
                }
            }
        } else {
            if ($jns_tindakan != '') {
                if ($ruang != '') {
                    $kondisi = " and id_jenis_tindakan='" . $jns_tindakan . "' and id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = " and id_jenis_tindakan='" . $jns_tindakan . "'";
                }
            } else {
                if ($ruang != '') {
                    $kondisi = " and id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = "";
                }
            }
        }

        $query = "select count(*) as jml from rm_tindakan where del_flag<>'1' " . $kondisi;
        $result = $this->runQuery($query);

        $jmlData = mysql_result($result, 0, 'jml');

        $query = "select * from rm_tindakan where del_flag<>'1' " . $kondisi . " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_tindakan' => $rec['id_detail_tindakan'],
                    'tindakan' => $this->replaceString($rec['tindakan']),
                    'icd' => $rec['icd']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        }
    }

    public function getTarifTindakan($no_pendaftaran, $id_tindakan) {
        $id_kelas = $this->getIdKelas($no_pendaftaran);

        $query = "select a.id_tarif_tindakan, a.tarif, b.id_jenis_tindakan from rm_tarif_tindakan a, rm_tindakan b where a.id_tindakan='" . $id_tindakan . "' and a.id_kelas='" . $id_kelas . "' and a.del_flag<>'1' and b.id_tindakan=a.id_tindakan";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'id_tarif_tindakan') . ":" . @mysql_result($result, 0, 'tarif') . ":" . @mysql_result($result, 0, 'id_jenis_tindakan');
        }
    }

    public function getTarifBahan($no_pendaftaran, $id_barang) {
        $id_kelas = $this->getIdKelas($no_pendaftaran);

        $query = "SELECT a.tarif, a.satuan, b.jumlah_stock FROM rm_barang a, rm_stock_barang b WHERE b.id_barang=a.id_barang AND a.id_barang='" . $id_barang . "' and a.del_flag<>'1'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'jumlah_stock') . ":" . @mysql_result($result, 0, 'satuan') . ":" . @mysql_result($result, 0, 'tarif');
        }
    }

    public function saveTindakan($id_tindakan_ruang_medis, $id_tindakan_medis, $id_pendaftaran, $dokter_operator, $dokter_anestesi, $cito, $tarif, $tarifTambah, $advice, $alat_tamu) {
        if (isset($_SESSION['level'])) {
            $id_kelas = $this->getIdKelas($id_pendaftaran);
            $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                if ($id_tindakan_ruang_medis == '') {
                    $query = "insert into rm_tindakan_ruang_medis (
                            id_tindakan_medis,
                            id_pendaftaran,
                            dokter_operator,
                            dokter_anastesi,
                            advice,
                            cito,
                            id_kelas,
                            alat_tamu,
                            tarif,
                            penambahan_tarif,
                            id_pasien,
                            id_tipe_pasien
                        ) values (
                            '" . $id_tindakan_medis . "',
                            '" . $id_pendaftaran . "',
                            '" . $dokter_operator . "',
                            '" . $dokter_anestesi . "',
                            '" . @mysql_escape_string($advice) . "',
                            '" . $cito . "',
							'" . $id_kelas . "',
                            '" . $alat_tamu . "',
                            '" . $tarif . "',
                            '" . $tarifTambah . "',
                            '" . $id_pasien . "',
                            '" . $tipe_pasien . "'
                        )";
                } else {
                    $query = "update rm_tindakan_ruang_medis set
                            id_tindakan_medis='" . $id_tindakan_medis . "',
                            id_pendaftaran='" . $id_pendaftaran . "',
                            dokter_operator='" . $dokter_operator . "',
                            dokter_anastesi='" . $dokter_anestesi . "',
                            advice='" . @mysql_escape_string($advice) . "',
                            cito='" . $cito . "',
                            id_kelas='" . $id_kelas . "',
                            alat_tamu='" . $alat_tamu . "',
                            tarif='" . $tarif . "',
                            penambahan_tarif='" . $tarifTambah . "',
                            id_tipe_pasien='" . $tipe_pasien . "'
                        where id_tindakan_ruang_medis='" . $id_tindakan_ruang_medis . "'";
                }
                $result = $this->runQuery($query);

                if ($result) {
                    $this->setStatusDaftar($id_pendaftaran);
                    return '1';
                } else {
                    return $query;
                }
            } else {
                return '2';
            }
        }
        return 'LOGIN';
    }

    public function saveKamar($id_penggunaan_kamar, $id_pendaftaran, $id_detail_kamar, $tgl_mulai, $tgl_selesai, $ket_selesai, $tarif) {
        $tgl1 = explode(' ', $tgl_mulai);
        $tgl2 = explode(' ', $tgl_selesai);
        if ($tgl2[0] != "")
            $lama = $this->jmlHari($tgl1[0], $tgl2[0]) + 1;
        else
            $lama = 1;
        if ($tgl_mulai != "") {
            $tgl_mulai = $this->formatDateDb($tgl1[0]) . " " . $tgl1[1];
        }
        if ($tgl_selesai != "") {
            $tgl_selesai = $this->formatDateDb($tgl2[0]) . " " . $tgl2[1];
        }

        $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);
        if ($id_penggunaan_kamar == '') {
            $query = "insert into rm_penggunaan_kamar (
                            id_pendaftaran,
                            id_detail_kamar,
                            id_kelas,
                            tgl_masuk,
                            tgl_keluar,
                            id_ruang,
                            keterangan_selesai,
                            lama_penggunaan,
                            tarif
                        ) values (
                            '" . $id_pendaftaran . "',
                            '" . $id_detail_kamar . "',
                            '" . $id_kelas . "',
                            '" . $tgl_mulai . "',
                            '" . $tgl_selesai . "',
                            '" . $_SESSION['level'] . "',
                            '" . $ket_selesai . "',
                            '" . $lama . "',
                            '" . $tarif . "'
                        )";
        } else {
            $query = "update rm_penggunaan_kamar set
                            id_pendaftaran='" . $id_pendaftaran . "',
                            id_detail_kamar='" . $id_detail_kamar . "',
                            tgl_masuk='" . $tgl_mulai . "',
                            tgl_keluar='" . $tgl_selesai . "',
                            id_ruang='" . $_SESSION['level'] . "',
                            keterangan_selesai='" . $ket_selesai . "',
                            lama_penggunaan='" . $lama . "',
                            tarif='" . $tarif . "'
                        where id_penggunaan_kamar='" . $id_penggunaan_kamar . "'";
        }
        $result = $this->runQuery($query);

        if ($result) {
            $this->setStatusKamar($id_detail_kamar);
            return '1';
        } else {
            return $query;
        }
    }

    public function hapusTindakanMedis($id_tindakan) {
        if ($this->checkStatusPembayaran($this->cekDaftarOK($id_tindakan))) {
            $query = "delete from rm_tindakan_ruang_medis where id_tindakan_ruang_medis='" . $id_tindakan . "'";
            $result = $this->runQuery($query);

            if ($result) {
                return '1';
            } else {
                return $query;
            }
        } else {
            return '2';
        }
    }

    public function hapusFasilitas($id_tindakan) {
        $query = "delete from rm_fasilitas_ruang where id_fasilitas_ruang='" . $id_tindakan . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return $query;
        }
    }

    public function hapusKamar($id_penggunaan_kamar) {
        $query = "select id_detail_kamar from rm_penggunaan_kamar where id_penggunaan_kamar='" . $id_penggunaan_kamar . "'";
        $result = $this->runQuery($query);

        $query = "update rm_detail_kamar set status='0' where id_detail_kamar='" . @mysql_result($result, 0, 'id_detail_kamar') . "'";
        $result = $this->runQuery($query);

        $query = "update rm_penggunaan_kamar set del_flag='1' where id_penggunaan_kamar='" . $id_penggunaan_kamar . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return $query;
        }
    }

    public function saveFasilitas($id_fasilitas_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $id_tarif, $id_operator, $tarif, $jumlah) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        if ($id_fasilitas_ruang == '') {
            $query = "insert into rm_fasilitas_ruang (
                            id_pelaku_tindakan,
                            id_detail_tindakan,
                            id_dokter,
                            id_pendaftaran,
                            id_tarif_tindakan,
                            advice,
                            tarif,
                            jumlah,
                            id_pasien,
                            id_tipe_pasien
                        ) values (
                            '" . $id_operator . "',
                            '" . $id_tindakan . "',
                            '" . $id_dokter . "',
                            '" . $id_pendaftaran . "',
                            '" . $id_tarif . "',
                            '" . @mysql_escape_string($advice) . "',
                            '" . $tarif . "',
                            '" . $jumlah . "',
                            '" . $id_pasien . "',
                            '" . $tipe_pasien . "'
                        )";
        } else {
            $query = "update rm_fasilitas_ruang set
                            id_pelaku_tindakan='" . $id_operator . "',
                            id_detail_tindakan='" . $id_tindakan . "',
                            id_dokter='" . $id_dokter . "',
                            id_pendaftaran='" . $id_pendaftaran . "',
                            id_tarif_tindakan='" . $id_tarif . "',
                            advice='" . @mysql_escape_string($advice) . "',
                            tarif='" . $tarif . "',
                            jumlah='" . $jumlah . "',
                            id_tipe_pasien='" . $tipe_pasien . "'
                       where id_fasilitas_ruang='" . $id_fasilitas_ruang . "'";
        }
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return $query;
        }
    }

    public function saveBahan($id_barang_tindakan, $id_pendaftaran, $id_barang, $jumlah, $tarif) {
        if ($id_barang_tindakan == '') {
            $query = "insert into rm_barang_tindakan (
                            id_pendaftaran,
                            id_barang,
                            tarif,
                            jumlah
                        ) values (
                            '" . $id_pendaftaran . "',
                            '" . $id_barang . "',
                            '" . $tarif . "',
                            '" . $jumlah . "'
                        )";
        } else {
            $query = "update rm_barang_tindakan set
                            id_barang='" . $id_barang . "',
                            tarif='" . $tarif . "',
                            jumlah='" . $jumlah . "'
                       where id_barang_tindakan='" . $id_barang_tindakan . "'";
        }
        $result = $this->runQuery($query);

        if ($result) {
            $q_check_stock = "SELECT jumlah_stock FROM rm_stock_barang WHERE id_barang='" . $id_barang . "'";
            $r_check = $this->runQuery($q_check_stock);
            $stock = @mysql_result($r_check, 0, 'jumlah_stock');
            $newStock = $stock - $jumlah;

            $q_stock = "update rm_stock_barang set jumlah_stock='" . $newStock . "' where id_barang='" . $id_barang . "'";
            $this->runQuery($q_stock);
            return '1';
        } else {
            return '0';
        }
    }

    public function getDetailTindakan($id_tindakan_ruang) {
        $query = "select * from rm_tindakan_ruang where id_tindakan_ruang='" . $id_tindakan_ruang . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_tindakan = "select b.tindakan as tindakan from rm_detail_tindakan a, rm_tindakan b where b.id_tindakan=a.id_tindakan and a.id_detail_tindakan='" . $rec['id_detail_tindakan'] . "'";
                $r_tindakan = $this->runQuery($q_tindakan);
                $arr = array(
                    'id_tindakan_ruang' => $rec['id_tindakan_ruang'],
                    'tindakan' => @mysql_result($r_tindakan, 0, 'tindakan'),
                    'tindakanId' => $rec['id_detail_tindakan'],
                    'dokter' => $rec['id_dokter'],
                    'operator' => $rec['id_pelaku_tindakan'],
                    'advice' => $rec['advice'],
                    'id_tarif' => $rec['id_tarif_tindakan'],
                    'tarif' => $rec['tarif']
                );
            }
            return $this->jEncode($arr);
        }
    }

    public function getDetailTindakanMedis($id_tindakan_ruang_medis) {
        $query = "select * from rm_tindakan_ruang_medis where id_tindakan_ruang_medis='" . $id_tindakan_ruang_medis . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr = array(
                    'id_tindakan_ruang_medis' => $rec['id_tindakan_ruang_medis'],
                    'tindakanMedis' => $this->getTindakan($rec['id_tindakan_medis'], 'tindakan'),
                    'tindakanMedisId' => $rec['id_tindakan_medis'],
                    'cekCitoTindakan' => $rec['cito'],
                    'cekAlatTamu' => $rec['alat_tamu'],
                    'dokter_operator' => $rec['dokter_operator'],
                    'dokter_anastesi' => $rec['dokter_anastesi'],
                    'advice' => $rec['advice'],
                    'tarif' => $rec['tarif'],
                    'tarifTambah' => $rec['penambahan_tarif']
                );
            }
            return $this->jEncode($arr);
        }
    }

    public function getDetailFasilitas($id_fasilitas_ruang) {
        $query = "select * from rm_fasilitas_ruang where id_fasilitas_ruang='" . $id_fasilitas_ruang . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_tindakan = "select b.tindakan as tindakan from rm_detail_tindakan a, rm_tindakan b where b.id_tindakan=a.id_tindakan and a.id_detail_tindakan='" . $rec['id_detail_tindakan'] . "'";
                $r_tindakan = $this->runQuery($q_tindakan);
                $arr = array(
                    'id_fasilitas_ruang' => $rec['id_fasilitas_ruang'],
                    'tindakanF' => @mysql_result($r_tindakan, 0, 'tindakan'),
                    'tindakanFId' => $rec['id_detail_tindakan'],
                    'dokterF' => $rec['id_dokter'],
                    'operatorF' => $rec['id_pelaku_tindakan'],
                    'adviceF' => $rec['advice'],
                    'id_tarifF' => $rec['id_tarif_tindakan'],
                    'jumlah' => $rec['jumlah'],
                    'tarifF' => $rec['tarif']
                );
            }
            return $this->jEncode($arr);
        }
    }

    public function getDetailKamar($id_pendaftaran) {
        $query = "select * from rm_penggunaan_kamar where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_kamar = "select id_kamar from rm_detail_kamar where id_detail_kamar='" . $rec['id_detail_kamar'] . "'";
                $r_kamar = $this->runQuery($q_kamar);
                $arr = array(
                    'id_penggunaan_kamar' => $rec['id_penggunaan_kamar'],
                    'kamar' => @mysql_result($r_kamar, 0, 'id_kamar'),
                    'bed' => $rec['id_detail_kamar'],
                    'waktuMulai' => $rec['tgl_masuk'],
                    'waktuUsai' => $rec['tgl_keluar'],
                    'ket_selesai' => $rec['keterangan_selesai'],
                    'tarifKamar' => $rec['tarif']
                );
            }
            return $this->jEncode($arr);
        }
    }

    public function saveClosePemeriksaan($id_pendaftaran, $id_keadaan, $keterangan, $tgl_keluar) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $query = "insert into rm_pasien_keluar (
                        id_pendaftaran,
                        id_pasien,
                        id_keadaan,
                        keterangan,
                        tgl_keluar,
                        id_tipe_pasien
                    ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $id_keadaan . "',
                        '" . @mysql_escape_string($keterangan) . "',
                        '" . $this->formatDateDb($tgl_keluar) . date(' H:i:s') . "',
                        '" . $tipe_pasien . "'
                    )";

        $result = $this->runQuery($query);

        if ($result) {
            $this->setCloseDaftar($id_pendaftaran);
            if ($this->generateJasaBedah($id_pendaftaran) > 0) {
                return '1';
            } else {
                return '0';
            }
        } else {
            return '0';
        }
    }

}

?>
