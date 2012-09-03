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
class cRadiologi extends fungsi {

    //put your code here
    public function getDetailRadiologi($id_pendaftaran, $rows, $offset) {
        $query = "select * from rm_detail_radiologi where id_pendaftaran='" . $id_pendaftaran . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            $jmlTarif = 0;
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_detail_radiologi' => $rec['id_detail_radiologi'],
                    'radiologi' => $this->getRadiologi($rec['id_radiologi']),
                    'cito' => $this->getFlag($rec['cito']),
                    'cito_bed' => $this->getFlag($rec['cito_bed']),
                    'keterangan' => $rec['keterangan'],
                    'tarif' => $rec['tarif']
                );
                $jmlTarif += $rec['tarif'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"radiologi":"Jumlah","tarif":' . $jmlTarif . '}]}';
        } else {
            return '{"total":"", "rows":[],"footer":[]}';
        }
    }

    public function getJasaTindakanRadiografer($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset) {
        $kondisi = "";

        if ($tipe_pasien != "")
            $kondisi .= " and b.id_tipe_pasien='" . $tipe_pasien . "'";

        if ($tgl_awal != "") {
            if ($tgl_akhir != "")
                $kondisi .= " and DATE(a.tgl_pemeriksaan) between '" . $this->formatDateDb($tgl_awal) . "' and '" . $this->formatDateDb($tgl_akhir) . "'";
            else
                $kondisi .= " and DATE(a.tgl_pemeriksaan)='" . $this->formatDateDb($tgl_awal) . "'";
        }

        $query = "SELECT
                      date(a.tgl_pemeriksaan) as tgl_tindakan,
                      b.id_pasien,
                      b.nama_pasien,
                      c.tipe_pasien,
                      e.ruang,
                      g.kelompok_radiologi,
                      f.radiologi,
                      h.nama_dokter,
                      i.tarif,
                      i.jasa_perawat,
                      (i.jasa_perawat * 0.05) as pajak
                  FROM
                      rm_detail_radiologi a,
                      rm_pasien b,
                      rm_tipe_pasien c,
                      rm_pendaftaran d,
                      rm_ruang e,
                      rm_radiologi f,
                      rm_kelompok_radiologi g,
                      rm_dokter h,
                      rm_jasa_tindakan_radiologi i
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
                      h.id_dokter = i.id_dokter " . $kondisi;
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
                    'tgl_tindakan' => $rec['tgl_tindakan'],
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'tipe_pasien' => $rec['tipe_pasien'],
                    'ruang' => $rec['ruang'],
                    'kelompok_rad' => $rec['kelompok_radiologi'],
                    'radiologi' => $rec['radiologi'],
                    'dokter' => $rec['nama_dokter'],
                    'operator' => 'Radiografer',
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

    public function getBahanRadiologi($id_pendaftaran, $rows, $offset) {
        $query = "select a.*, b.id_radiologi from rm_bahan_radiologi a, rm_detail_radiologi b where a.id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_radiologi=a.id_detail_radiologi and b.del_flag<>'1'";
        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);
        $query .= " limit " . $offset . "," . $rows;
        $result = $this->runQuery($query);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_bahan_radiologi' => $rec['id_bahan_radiologi'],
                    'radiologi' => $this->getRadiologi($rec['id_radiologi']),
                    'film' => $this->getFilm($rec['id_film']),
                    'jumlah' => $rec['jumlah']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"", "rows":[]}';
        }
    }

    public function getTarifRadiologi($id_pendaftaran, $id_radiologi) {
        $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);
        $query = "select tarif from rm_tarif_radiologi where id_radiologi='" . $id_radiologi . "'
                    and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function simpanPemeriksaanRadiologi(
    $id_pendaftaran, $id_pasien, $id_radiologi, $tarif, $ukuranA, $jumlahA, $ukuranB, $jumlahB, $ukuranC, $jumlahC, $ukuranD, $jumlahD, $cito, $cito_bed, $keterangan
    ) {
        if (isset($_SESSION['level'])) {
            $id_kelas = $this->getIdKelas($id_pendaftaran);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            if ($this->checkStatusPembayaran($id_pendaftaran)) {
                if ($cito_bed == '1')
                    $tarif = $this->getTarifRadiologi($id_pendaftaran, '62');

                if ($cito == '1')
                    $tarif = $tarif + ($tarif * 0.25);
                $totalBahan = 0;

                $query = "insert into rm_detail_radiologi(
                    id_pendaftaran,
                    id_pasien,
                    id_radiologi,
                    cito,
                    cito_bed,
                    keterangan,
                    tarif,
                    level,
                    id_kelas,
                    id_tipe_pasien
                ) values (
                    '" . $id_pendaftaran . "',
                    '" . $id_pasien . "',
                    '" . $id_radiologi . "',
                    '" . $cito . "',
                    '" . $cito_bed . "',
                    '" . @mysql_escape_string($keterangan) . "',
                    '" . $tarif . "',
                    '" . $_SESSION['level'] . "',
                    '" . $id_kelas . "',
                    '" . $tipe_pasien . "'
                )";

                $result = $this->runQuery($query);
                if ($result) {
                    $q_id = "SELECT max(id_detail_radiologi) as idakhir from rm_detail_radiologi";
                    $r_id = $this->runQuery($q_id);
                    $idAkhir = @mysql_result($r_id, 0, "idakhir");

                    for ($i = 1; $i <= 4; $i++) {
                        switch ($i) {
                            case 1:
                                $jumlah = $jumlahA;
                                break;
                            case 2:
                                $jumlah = $jumlahB;
                                break;
                            case 3:
                                $jumlah = $jumlahC;
                                break;
                            case 4:
                                $jumlah = $jumlahD;
                                break;
                            default:
                                break;
                        }
                        if ($jumlah > 0) {
                            $q_bahan = "insert into rm_bahan_radiologi (
                                id_pendaftaran,
                                id_detail_radiologi,
                                id_film,
                                jumlah
                            ) values (
                                '" . $id_pendaftaran . "',
                                '" . $idAkhir . "',
                                '" . $i . "',
                                '" . $jumlah . "'
                            )";

                            $r_bahan = $this->runQuery($q_bahan);
                            $totalBahan += $jumlah;
                        }
                    }
                    $this->setStatusDaftar($id_pendaftaran);
                    if ($totalBahan > 0)
                        $return = "TRUE";
                    else
                        $return = "WARNING:Tidak ada bahan yang di entry";
                } else {
                    $return = 'ERROR';
                }
            } else {
                $return = 'LUNAS';
            }

            return $return;
        }
        return 'LOGIN';
    }

    public function hapusRadiologi($id_detail_radiologi) {
        if ($this->checkStatusPembayaran($this->cekDaftarRadiologi($id_detail_radiologi))) {
            $query = "DELETE FROM rm_detail_radiologi where id_detail_radiologi='" . $id_detail_radiologi . "'";
            $result = $this->runQuery($query);
            if ($result)
                return '1';
            else
                return '0';
        }else {
            return '2';
        }
    }

    public function saveClosePemeriksaan($id_pendaftaran, $id_keadaan, $id_cara_keluar, $keterangan, $tgl_keluar) {
        $id_pasien = $this->getPasienIdDaftar($id_pendaftaran);
        $tipe_pasien = $this->getTipePasienId($id_pasien);
        $query = "insert into rm_pasien_keluar (
                        id_pendaftaran,
                        id_pasien,
                        id_keadaan,
                        id_cara_keluar,
                        keterangan,
                        tgl_keluar,
                        id_tipe_pasien
                    ) values (
                        '" . $id_pendaftaran . "',
                        '" . $id_pasien . "',
                        '" . $id_keadaan . "',
                        '" . $id_cara_keluar . "',
                        '" . @mysql_escape_string($keterangan) . "',
                        '" . $this->formatDateDb($tgl_keluar) . date(' H:i:s') . "',
                        '" . $tipe_pasien . "'
                    )";

        $result = $this->runQuery($query);

        if ($result) {
            $this->setCloseDaftar($id_pendaftaran);
            $this->generateJasaRadiologi($id_pendaftaran);
            return '1';
        } else {
            return '0';
        }
    }

}

?>
