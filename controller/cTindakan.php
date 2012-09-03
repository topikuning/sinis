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
class cTindakan extends fungsi {

    //put your code here    
    public function getTindakanRuang($id_pendaftaran, $rows, $offset) {
        $query = "select *,date(tgl_tindakan) as tgl_tindakan from rm_tindakan_ruang where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang = '" . $_SESSION['level'] . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        $jmlTarif = 0;

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_tindakan_ruang' => $rec['id_tindakan_ruang'],
                    'tindakan' => $this->getTindakan($rec['id_detail_tindakan'], 'tindakan'),
                    'icd' => $this->getTindakan($rec['id_detail_tindakan'], 'icd'),
                    'dokter' => $this->getDokter($rec['id_dokter']),
                    'operator' => $this->getOperator($rec['id_pelaku_tindakan']),
                    'advice' => $rec['advice'],
                    'tanggal' => $this->codeDate($rec['tgl_tindakan']),
                    'tarif' => $rec['tarif']
                );

                $jmlTarif += $rec['tarif'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"tindakan":"Jumlah","tarif":' . $jmlTarif . '}]}';
        } else {
            return '{"total":"", "rows":[],"footer":[]}';
        }
    }

    public function getFasilitasRuang($id_pendaftaran, $rows, $offset) {
        $query = "select * from rm_fasilitas_ruang where id_pendaftaran='" . $id_pendaftaran . "' and id_ruang = '" . $_SESSION['level'] . "'";
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
                    'dokter' => $this->getPegawai($rec['id_dokter']),
                    'operator' => $this->getOperator($rec['id_pelaku_tindakan']),
                    'advice' => $rec['advice'],
                    'tarif' => $rec['tarif'],
                    'id_dokter' => $rec['id_dokter'],
                    'total' => $rec['tarif'] * $rec['jumlah']
                );

                $jmlTarif += $rec['tarif'] * $rec['jumlah'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"tindakan":"Jumlah","total":' . $jmlTarif . '}]}';
        } else {
            return '{"total":"", "rows":[],"footer":[]}';
        }
    }

    public function getFasilitasRuangPux($id_pendaftaran, $rows, $offset) {
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
                    'dokter' => $this->getPegawai($rec['id_dokter']),
                    'operator' => $this->getOperator($rec['id_pelaku_tindakan']),
                    'advice' => $rec['advice'],
                    'tarif' => $rec['tarif'],
                    'id_dokter' => $rec['id_dokter'],
                    'total' => $rec['tarif'] * $rec['jumlah']
                );

                $jmlTarif += $rec['tarif'] * $rec['jumlah'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"tindakan":"Jumlah","total":' . $jmlTarif . '}]}';
        } else {
            return '{"total":"", "rows":[],"footer":[]}';
        }
    }

    public function getBarangTindakan($id_pendaftaran, $rows, $offset) {
        $query = "SELECT a.id_barang_tindakan, b.barang, c.jumlah_stock, a.jumlah, b.satuan
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
                    'satuan' => $rec['satuan']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function getDataListTindakan($tindakan, $ruang, $jns_tindakan, $rows, $offset) {
        $kondisi = "";

        if ($tindakan != '') {
            if ($jns_tindakan != '') {
                if ($ruang != '' && ($ruang == 20 || $ruang == 31 || $ruang == 32)) {
                    $kondisi = " and b.tindakan like '" . @mysql_escape_string($tindakan) . "%' and b.id_jenis_tindakan='" . $jns_tindakan . "' and a.id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = " and b.tindakan like '" . @mysql_escape_string($tindakan) . "%' and b.id_jenis_tindakan='" . $jns_tindakan . "' and a.id_ruang='0'";
                }
            } else {
                if ($ruang != '' && ($ruang == 20 || $ruang == 31 || $ruang == 32)) {
                    $kondisi = " and b.tindakan like '" . @mysql_escape_string($tindakan) . "%' and a.id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = " and b.tindakan like '" . @mysql_escape_string($tindakan) . "%' and a.id_ruang='0'";
                }
            }
        } else {
            if ($jns_tindakan != '') {
                if ($ruang != '' && ($ruang == 20 || $ruang == 31 || $ruang == 32)) {
                    $kondisi = " and b.id_jenis_tindakan='" . $jns_tindakan . "' and a.id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = " and b.id_jenis_tindakan='" . $jns_tindakan . "' and a.id_ruang='0'";
                }
            } else {
                if ($ruang != '') {
                    $kondisi = " and a.id_ruang='" . $ruang . "'";
                } else {
                    $kondisi = " and a.id_ruang='0'";
                }
            }
        }

        $query = "select a.id_detail_tindakan, b.tindakan, b.icd from rm_detail_tindakan a, rm_tindakan b 
                  where a.del_flag<>'1' and b.id_tindakan=a.id_tindakan " . $kondisi;

        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;
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
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function getTarifTindakan($no_pendaftaran, $id_detail_tindakan) {
        $id_kelas = $this->getIdKelas($no_pendaftaran);

        $q_tindakan = "select id_tindakan from rm_detail_tindakan where id_detail_tindakan='" . $id_detail_tindakan . "'";
        $r_tindakan = $this->runQuery($q_tindakan);

        $query = "select a.id_tarif_tindakan, a.tarif, b.id_jenis_tindakan from rm_tarif_tindakan a, rm_tindakan b where a.id_tindakan='" . @mysql_result($r_tindakan, 0, 'id_tindakan') . "' and a.id_kelas='" . $id_kelas . "' and a.del_flag<>'1' and b.id_tindakan=a.id_tindakan";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'id_tarif_tindakan') . ":" . @mysql_result($result, 0, 'tarif') . ":" . @mysql_result($result, 0, 'id_jenis_tindakan');
        }
    }

    public function getTarifTindakanPindah($id_kelas, $id_detail_tindakan) {
        $q_tindakan = "select id_tindakan from rm_detail_tindakan where id_detail_tindakan='" . $id_detail_tindakan . "'";
        $r_tindakan = $this->runQuery($q_tindakan);

        $query = "select a.id_tarif_tindakan, a.tarif, b.id_jenis_tindakan from rm_tarif_tindakan a, rm_tindakan b where a.id_tindakan='" . @mysql_result($r_tindakan, 0, 'id_tindakan') . "' and a.id_kelas='" . $id_kelas . "' and a.del_flag<>'1' and b.id_tindakan=a.id_tindakan";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'id_tarif_tindakan') . ":" . @mysql_result($result, 0, 'tarif') . ":" . @mysql_result($result, 0, 'id_jenis_tindakan');
        }
    }

    public function getTarifFasilitas($no_pendaftaran, $id_detail_tindakan, $id_kamar) {
        $id_kelas = $this->getIdKelasKeluar($no_pendaftaran, $id_kamar);

        $q_tindakan = "select id_tindakan from rm_detail_tindakan where id_detail_tindakan='" . $id_detail_tindakan . "'";
        $r_tindakan = $this->runQuery($q_tindakan);

        $query = "select a.id_tarif_tindakan, a.tarif, b.id_jenis_tindakan from rm_tarif_tindakan a, rm_tindakan b where a.id_tindakan='" . @mysql_result($r_tindakan, 0, 'id_tindakan') . "' and a.id_kelas='" . $id_kelas . "' and a.del_flag<>'1' and b.id_tindakan=a.id_tindakan";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'id_tarif_tindakan') . ":" . @mysql_result($result, 0, 'tarif') . ":" . @mysql_result($result, 0, 'id_jenis_tindakan');
        }
    }

    public function getTarifBahan($no_pendaftaran, $id_barang) {
        $id_kelas = $this->getIdKelas($no_pendaftaran);

        $query = "SELECT a.satuan, b.jumlah_stock FROM rm_barang a, rm_stock_barang b WHERE b.id_barang=a.id_barang AND a.id_barang='" . $id_barang . "' and a.del_flag<>'1'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'jumlah_stock') . ":" . @mysql_result($result, 0, 'satuan'); //.":".@mysql_result($result, 0, 'tarif');
        }
    }

    public function saveTindakan($id_tindakan_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $tarif, $cito, $id_tarif, $id_operator, $tglInput, $kelase) {
        if (isset($_SESSION['level'])) {
            if ($kelase > 0)
                $id_kelas = $kelase;
            else
                $id_kelas = $this->getIdKelas($id_pendaftaran);

            $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            $id_tindakanA = $this->getIdTindakan($id_tindakan);
            $tarif = $this->getTarifTindakanRuang($id_tindakanA, $id_kelas);

            $q_check = "select status_pembayaran from rm_pendaftaran where id_pendaftaran='" . $id_pendaftaran . "'";
            $r_check = $this->runQuery($q_check);

            if ($cito == '1')
                $tarif = $tarif + (0.25 * $tarif);

            if (@mysql_result($r_check, 0, 'status_pembayaran') != '2') {
                if ($id_tindakan_ruang == '') {
                    $query = "insert into rm_tindakan_ruang (
                                id_pelaku_tindakan,
                                id_detail_tindakan,
                                id_dokter,
                                id_ruang,
                                id_kelas,
                                id_pendaftaran,
                                id_tarif_tindakan,
                                tarif,
                                advice,
                                id_pasien,
                                id_tipe_pasien,
                                tgl_tindakan,
                                ip
                            ) values (
                                '" . $id_operator . "',
                                '" . $id_tindakan . "',
                                '" . $id_dokter . "',
                                '" . $_SESSION['level'] . "',
                                '" . $id_kelas . "',
                                '" . $id_pendaftaran . "',
                                '" . $id_tarif . "',
                                '" . $tarif . "',
                                '" . @mysql_escape_string($advice) . "',
                                '" . $id_pasien . "',
                                '" . $tipe_pasien . "',
                                '" . $this->formatDateDb($tglInput) . " " . date('H:i:s') . "',
                                '" . $_SERVER['REMOTE_ADDR'] . "'
                            )";
                } else {
                    $query = "update rm_tindakan_ruang set
                                id_pelaku_tindakan='" . $id_operator . "',
                                id_detail_tindakan='" . $id_tindakan . "',
                                id_dokter='" . $id_dokter . "',
                                id_ruang='" . $_SESSION['level'] . "',
                                id_kelas='" . $id_kelas . "',
                                id_pendaftaran='" . $id_pendaftaran . "',
                                id_tarif_tindakan='" . $id_tarif . "',
                                tarif='" . $tarif . "',
                                tgl_tindakan='" . $this->formatDateDb($tglInput) . " " . date('H:i:s') . "',
                                advice='" . @mysql_escape_string($advice) . "',
                                ip='" . $_SERVER['REMOTE_ADDR'] . "',
                                id_tipe_pasien='" . $tipe_pasien . "'
                                where id_tindakan_ruang='" . $id_tindakan_ruang . "'";
                }
                $result = $this->runQuery($query);

                if ($result) {
                    $this->setStatusDaftar($id_pendaftaran);
                    $return = '1';
                } else {
                    $return = '0';
                }
            } else {
                $return = '2';
            }

            return $return;
        }
        return 'LOGIN';
    }

    public function hapusTindakan($id_tindakan) {
        if ($this->checkStatusPembayaran($this->cekDaftarTindakan($id_tindakan))) {
            $query = "delete from rm_tindakan_ruang where id_tindakan_ruang='" . $id_tindakan . "'";
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
        if ($this->checkStatusPembayaran($this->cekDaftarFasilitas($id_tindakan))) {
            $query = "delete from rm_fasilitas_ruang where id_fasilitas_ruang='" . $id_tindakan . "'";
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

    public function hapusBahan($id_barang_tindakan) {
        $query = "delete from rm_barang_tindakan where id_barang_tindakan='" . $id_barang_tindakan . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return $query;
        }
    }

    public function saveFasilitas($id_fasilitas_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $id_tarif, $tarif, $jumlah) {
        if (isset($_SESSION['level'])) {
            $id_kelas = $this->getIdKelas($id_pendaftaran);
            $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            $id_tindakanA = $this->getIdTindakan($id_tindakan);
            $tarif = $this->getTarifTindakanRuang($id_tindakanA, $id_kelas);
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                if ($id_fasilitas_ruang == '') {
                    $query = "insert into rm_fasilitas_ruang (
                            id_detail_tindakan,
                            id_dokter,
                            id_ruang,
                            id_kelas,
                            id_pendaftaran,
                            id_tarif_tindakan,
                            advice,
                            tarif,
                            jumlah,
                            id_pasien,
                            id_tipe_pasien,
                            ip
                        ) values (
                            '" . $id_tindakan . "',
                            '" . $id_dokter . "',
                            '" . $_SESSION['level'] . "',
                            '" . $id_kelas . "',
                            '" . $id_pendaftaran . "',
                            '" . $id_tarif . "',
                            '" . @mysql_escape_string($advice) . "',
                            '" . $tarif . "',
                            '" . $jumlah . "',
                            '" . $id_pasien . "',
                            '" . $tipe_pasien . "',
                            '" . $_SERVER['REMOTE_ADDR'] . "'
                        )";
                } else {
                    $query = "update rm_fasilitas_ruang set
                            id_detail_tindakan='" . $id_tindakan . "',
                            id_dokter='" . $id_dokter . "',
                            id_ruang='" . $_SESSION['level'] . "',
                            id_kelas='" . $id_kelas . "',
                            id_pendaftaran='" . $id_pendaftaran . "',
                            id_tarif_tindakan='" . $id_tarif . "',
                            advice='" . @mysql_escape_string($advice) . "',
                            tarif='" . $tarif . "',
                            jumlah='" . $jumlah . "',
                            ip='" . $_SERVER['REMOTE_ADDR'] . "',
                            id_tipe_pasien='" . $tipe_pasien . "'
                       where id_fasilitas_ruang='" . $id_fasilitas_ruang . "'";
                }
                $result = $this->runQuery($query);

                if ($result) {
                    return '1';
                } else {
                    return $query;
                }
            } else {
                return '2';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function saveFasilitasPux($id_fasilitas_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $id_tarif, $tarif, $jumlah, $id_kamar) {
        $id_kelas = $this->getIdKelasKeluar($id_pendaftaran, $id_kamar);
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $id_ruang = $this->getRuangDaftar($id_pendaftaran);

        if ($id_fasilitas_ruang == '') {
            $query = "insert into rm_fasilitas_ruang (
                            id_detail_tindakan,
                            id_dokter,
                            id_ruang,
                            id_kelas,
                            id_pendaftaran,
                            id_tarif_tindakan,
                            advice,
                            tarif,
                            jumlah,
                            id_pasien,
                            id_tipe_pasien,
			    ip
                        ) values (
                            '" . $id_tindakan . "',
                            '" . $id_dokter . "',
                            '" . $id_ruang . "',
                            '" . $id_kelas . "',
                            '" . $id_pendaftaran . "',
                            '" . $id_tarif . "',
                            '" . @mysql_escape_string($advice) . "',
                            '" . $tarif . "',
                            '" . $jumlah . "',
                            '" . $id_pasien . "',
                            '" . $tipe_pasien . "',
			    '" . $_SERVER['REMOTE_ADDR'] . "'
                        )";
        } else {
            $query = "update rm_fasilitas_ruang set
                            id_detail_tindakan='" . $id_tindakan . "',
                            id_dokter='" . $id_dokter . "',
                            id_ruang='" . $id_ruang . "',
                            id_kelas='" . $id_kelas . "',
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

    public function saveBahan($id_barang_tindakan, $id_pendaftaran, $id_barang, $jumlah) {//, $tarif){
        if ($id_barang_tindakan == '') {
            $query = "insert into rm_barang_tindakan (
                            id_pendaftaran,
                            id_barang,
                            jumlah
                        ) values (
                            '" . $id_pendaftaran . "',
                            '" . $id_barang . "',
                            '" . $jumlah . "'
                        )";
        } else {
            $query = "update rm_barang_tindakan set
                            id_barang='" . $id_barang . "',
                            jumlah='" . $jumlah . "'
                       where id_barang_tindakan='" . $id_barang_tindakan . "'";
        }
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function saveBahanBal($id_barang_tindakan, $tipe, $id_barang, $jumlah) {
        $s_query = " SELECT jumlah_stock FROM rm_stock_barang WHERE id_ruang='" . $_SESSION['level'] . "' AND id_barang = '" . $id_barang . "'";
        $h_stock = $this->runQuery($s_query);
        while ($rec = mysql_fetch_array($h_stock)) {
            $stock = $rec['jumlah_stock'];
        }

        $c_query = " SELECT jumlah FROM rm_barang_tindakan WHERE id_ruang='" . $_SESSION['level'] . "' AND id_barang_tindakan = '" . $id_barang_tindakan . "'";
        $h_cstock = $this->runQuery($c_query);
        while ($cek = mysql_fetch_array($h_cstock)) {
            $jumlahAwal = $cek['jumlah'];
        }

        if ($id_barang_tindakan == '') {
            $query = "insert into rm_barang_tindakan (
                            id_keperluan,
                            id_barang,
							id_ruang,
                            jumlah
                        ) values (
                            '" . $tipe . "',
                            '" . $id_barang . "',
							'" . $_SESSION['level'] . "',
                            '" . $jumlah . "'
                        )";
        } else {
            $query = "UPDATE rm_barang_tindakan set
                            id_barang='" . $id_barang . "',
                            jumlah='" . $jumlah . "',
                                id_keperluan='" . $tipe . "',
                       WHERE id_barang_tindakan='" . $id_barang_tindakan . "' and id_ruang='" . $_SESSION['level'] . "'";
        }
        if ($stock < $jumlah || $stock == 0) {
            return '0';
        } else if ($stock == $jumlah) {
            $result = $this->runQuery($query);
            return '2';
        } else {
            $result = $this->runQuery($query);
            return '1';
        }
    }

    public function simpanDiskonTindakan($id_pendaftaran, $id_pasien, $diskon) {
        if (isset($_SESSION['level'])) {
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                $query = "insert into rm_diskon_tindakan (
                        id_pendaftaran,
                        id_pasien,
                        id_ruang,
                        pemberi_diskon,
                        diskon
                    ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $_SESSION['level'] . "',
                        'Perawat',
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

    public function getDetailTindakan($id_tindakan_ruang) {
        $query = "select * from rm_tindakan_ruang where id_tindakan_ruang='" . $id_tindakan_ruang . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_tindakan = "select b.tindakan as tindakan from rm_detail_tindakan a, rm_tindakan b where b.id_tindakan=a.id_tindakan and b.id_jenis_tindakan='1' and a.id_detail_tindakan='" . $rec['id_detail_tindakan'] . "'";
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

    public function getJasaTindakanPerawat($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset) {
        $kondisi = "";

        if ($tipe_pasien != "")
            $kondisi .= " and d.id_tipe_pasien='" . $tipe_pasien . "'";

        if ($tgl_awal != "") {
            if ($tgl_akhir != "")
                $kondisi .= " and DATE(b.tgl_tindakan) between '" . $this->formatDateDb($tgl_awal) . "' and '" . $this->formatDateDb($tgl_akhir) . "'";
            else
                $kondisi .= " and DATE(b.tgl_tindakan)='" . $this->formatDateDb($tgl_awal) . "'";
        }

        $query = "SELECT
                    DATE(b.tgl_tindakan) AS tgl_tindakan,
                    a.id_pasien,
                    c.nama_pasien,
                    d.tipe_pasien,
                    e.nama_dokter,
                    f.nama_pelaku,
                    a.tarif,
                    a.jasa_perawat,
                    (0.05*a.jasa_perawat) AS pajak,
                    h.tindakan
                  FROM
                    rm_jasa_tindakan_poli a,
                    rm_tindakan_ruang b,
                    rm_pasien c,
                    rm_tipe_pasien d,
                    rm_dokter e,
                    rm_pelaku_tindakan f,
                    rm_detail_tindakan g,
                    rm_tindakan h
                  WHERE
                    a.id_ruang = '" . $_SESSION['level'] . "' AND
                    b.id_pendaftaran = a.id_pendaftaran AND
                    c.id_pasien = a.id_pasien AND
                    d.id_tipe_pasien = c.id_tipe_pasien AND
                    e.id_dokter = a.id_dokter AND
                    f.id_pelaku_tindakan = a.id_pelaku_tindakan AND
                    h.id_tindakan = g.id_tindakan AND
                    a.id_detail_tindakan = g.id_detail_tindakan " . $kondisi . " 
                  GROUP BY
                    a.id_jasa_pendaftaran ";
        $result = $this->runQuery($query);

        $jmlData = @mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'tgl_tindakan' => $this->codeDate($rec['tgl_tindakan']),
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'tindakan' => $rec['tindakan'],
                    'dokter' => $rec['nama_dokter'],
                    'operator' => $rec['nama_pelaku'],
                    'tarif' => $rec['tarif'],
                    'jasa_perawat' => $rec['jasa_perawat'],
                    'pajak' => $rec['pajak']
                );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_perawat'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"tipe_pasien":"Total","tarif":' . $jmlTarif . ',"jasa_perawat":' . $jmlJasa . ',"pajak":' . $jmlPajak . '}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getTagihanTindakanPasien($id_pendaftaran) {
        $jmlTarif = 0;
        $bayar = 0;
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
                  select id_pendaftaran from rm_pendaftaran where id_asal_pendaftaran='" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "SELECT SUM(tarif) AS tarif FROM rm_detail_radiologi
                  WHERE id_pendaftaran in (select id_pendaftaran from rm_pendaftaran 
                  where id_asal_pendaftaran='" . $id_pendaftaran . "')";
        $result = $this->runQuery($query);

        $jmlTarif += @ mysql_result($result, 0, 'tarif');

        $query = "select sum(diskon) as diskon from rm_diskon_tindakan where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
        $result = $this->runQuery($query);

        $diskon = @mysql_result($result, 0, 'diskon');

        $query = "select sum(bayar) as bayar from rm_pembayaran_tagihan where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>1";
        $result = $this->runQuery($query);

        $bayar += @ mysql_result($result, 0, 'bayar');

        $arr = array(
            'total' => "Rp. " . number_format($jmlTarif, 2, ',', '.'),
            'terbayar' => "Rp. " . number_format($bayar, 2, ',', '.'),
            'diskon_all' => "Rp. " . number_format($diskon, 2, ',', '.'),
            'kurang_bayar' => "Rp. " . number_format(($jmlTarif - $bayar - $diskon), 2, ',', '.'),
            'kurang' => ($jmlTarif - $bayar - $diskon)
        );


        return $this->jEncode($arr);
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

    public function getDetailBahan($id_barang_tindakan) {
        $query = "select * from rm_barang_tindakan WHERE id_barang_tindakan='" . $id_barang_tindakan . "'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_bahan = "SELECT a.barang, a.satuan, b.jumlah_stock FROM rm_barang a, rm_stock_barang b WHERE a.id_barang = '" . $rec['id_barang'] . "' AND b.id_barang = '" . $rec['id_barang'] . "'";
                $r_bahan = $this->runQuery($q_bahan);
                $arr = array(
                    'id_barang_tindakan' => $rec['id_barang_tindakan'],
                    'bahan' => @mysql_result($r_bahan, 0, 'barang'),
                    'bahanBal' => @mysql_result($r_bahan, 0, 'barang'),
                    'bahanId' => $rec['id_barang'],
                    'jumlahBarang' => $rec['jumlah'],
                    'stock' => @mysql_result($r_bahan, 0, 'jumlah_stock'),
                    'satuan' => @mysql_result($r_bahan, 0, 'satuan')
                );
            }
            return $this->jEncode($arr);
        }
    }

    public function getBahanRuang($id_barang) {
        $query = "SELECT a.satuan, b.jumlah_stock FROM rm_barang a, rm_stock_barang b WHERE b.id_barang=a.id_barang AND a.id_barang='" . $id_barang . "' and b.id_ruang='" . $_SESSION['level'] . "' and a.del_flag<>'1'";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, 'jumlah_stock') . ":" . @mysql_result($result, 0, 'satuan');
        }
    }

}

?>
