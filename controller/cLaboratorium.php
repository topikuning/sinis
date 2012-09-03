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
class cLaboratorium extends fungsi {

    //put your code here
    public function getDetailPemeriksaan($id_pendaftaran) {
        $query = "SELECT a.id_detail_laboratorium, b.kelompok_lab, c.laboratorium, c.metode, c.nilai_normal, 
                  a.hasil, a.tarif FROM rm_detail_laboratorium a, rm_kelompok_lab b, rm_laboratorium c
                  WHERE b.id_kelompok_lab=a.id_kelompok_lab AND c.id_laboratorium=a.id_laboratorium 
                  AND a.id_pendaftaran='" . $id_pendaftaran . "' and a.del_flag<>'1' ORDER BY a.id_detail_laboratorium";

        $result = $this->runQuery($query);

        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            $jmlTarif = 0;
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_detail_laboratorium' => $rec['id_detail_laboratorium'],
                    'kelompok_lab' => $rec['kelompok_lab'],
                    'laboratorium' => $rec['laboratorium'],
                    'metode' => $rec['metode'],
                    'nilai_normal' => $rec['nilai_normal'],
                    'hasil' => $rec['hasil'],
                    'tarif' => $rec['tarif']
                );
                $jmlTarif += $rec['tarif'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"kelompok_lab":"Jumlah","tarif":' . $jmlTarif . '}]}';
        }
    }

    public function getDetailPerawatanLab($id_pendaftaran) {
        $query = "select * from rm_pemeriksaan_lab where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "noPeriksa_edit" => @mysql_result($result, 0, "no_pemeriksaan"),
                "ambilSampel_edit" => $this->formatDateDb(@mysql_result($result, 0, "ambil")),
                "periksaSampel_edit" => $this->formatDateDb(@mysql_result($result, 0, "periksa")),
                "selesaiSampel_edit" => $this->formatDateDb(@mysql_result($result, 0, "selesai"))
            );

            return $this->jEncode($return);
        }
    }

    public function getPemeriksaanLab($id_pendaftaran) {
        $query = "select * from rm_pemeriksaan_lab where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "noPeriksa" => @mysql_result($result, 0, "no_pemeriksaan"),
                "ambilSampel" => $this->formatDateDb(@mysql_result($result, 0, "ambil")),
                "periksaSampel" => $this->formatDateDb(@mysql_result($result, 0, "periksa")),
                "selesaiSampel" => $this->formatDateDb(@mysql_result($result, 0, "selesai"))
            );

            return $this->jEncode($return);
        }
    }

    public function getInterHasil($id_pendaftaran) {
        $query = "select hasil_laboratorium from rm_hasil_laboratorium where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "interHasil" => @mysql_result($result, 0, "hasil_laboratorium")
            );

            return $this->jEncode($return);
        }
    }

    public function getJasaTindakanAnalis($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset) {
        $kondisi = "";

        if ($tipe_pasien != "")
            $kondisi .= " and b.id_tipe_pasien='" . $tipe_pasien . "'";

        if ($tgl_awal != "") {
            if ($tgl_akhir != "")
                $kondisi .= " and DATE(b.ambil) between '" . $this->formatDateDb($tgl_awal) . "' and '" . $this->formatDateDb($tgl_akhir) . "'";
            else
                $kondisi .= " and DATE(b.ambil)='" . $this->formatDateDb($tgl_awal) . "'";
        }

        $query = "SELECT
                      DATE(b.ambil) as tgl_tindakan,
                      a.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      f.ruang,
                      g.kelompok_lab,
                      i.nama_dokter,
                      a.tarif,
                      h.laboratorium,
                      a.jasa_perawat,
                      (a.jasa_perawat * 0.05) AS pajak
                    FROM
                      rm_jasa_tindakan_laboratorium a,
                      rm_pemeriksaan_lab b,
                      rm_pasien c,
                      rm_tipe_pasien d, 
                      rm_pendaftaran e,
                      rm_ruang f,
                      rm_kelompok_lab g,
                      rm_laboratorium h,
                      rm_dokter i
                    WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      b.id_pasien = a.id_pasien AND
                      c.id_pasien = a.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_pendaftaran = a.id_pendaftaran AND
                      f.id_ruang = e.id_ruang_asal AND
                      h.id_laboratorium = a.id_laboratorium AND
                      g.id_kelompok_lab = h.id_kelompok_lab AND
                      i.id_dokter = a.id_dokter " . $kondisi . "
                    GROUP BY
                      a.id_jasa_tindakan_laboratorium";
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
                    'kelompok_lab' => $rec['kelompok_lab'],
                    'laboratorium' => $rec['laboratorium'],
                    'dokter' => $rec['nama_dokter'],
                    'operator' => 'Analis',
                    'tarif' => $rec['tarif'],
                    'jasa_perawat' => $rec['jasa_perawat'],
                    'pajak' => $rec['pajak']
                );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_perawat'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"tipe_pasien":"Total","tarif":' . $jmlTarif . ',"pajak":' . $jmlPajak . ',"jasa_perawat":' . $jmlJasa . '}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getHapusanDarah($id_pendaftaran) {
        $query = "select * from rm_lab_darah where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "eritrosit" => @mysql_result($result, 0, "eritrosit"),
                "leukosit" => @mysql_result($result, 0, "leukosit"),
                "trombosit" => @mysql_result($result, 0, "trombosit")
            );

            return $this->jEncode($return);
        }
    }

    public function hapusLaboratorium($id_detail_laboratorium) {
        if ($this->checkStatusPembayaran($this->cekDaftarLaborat($id_detail_laboratorium))) {
            $query = "update rm_detail_laboratorium set del_flag='1' where id_detail_laboratorium='" . $id_detail_laboratorium . "'";
            $result = $this->runQuery($query);

            if ($result) {
                return '1';
            } else {
                return '0';
            }
        } else {
            return '2';
        }
    }

    public function getOwnInterHasil($id_pendaftaran) {
        $query = "select hasil_laboratorium from rm_hasil_laboratorium where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = @mysql_result($result, 0, "hasil_laboratorium");

            return $return;
        }
    }

    public function simpanInterHasil($id_pendaftaran, $hasil_laboratorium) {
        $query = "select * from rm_hasil_laboratorium where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $q_hasil = "update rm_hasil_laboratorium set hasil_laboratorium='" . @mysql_escape_string($hasil_laboratorium) . "'
                            where id_pendaftaran='" . $id_pendaftaran . "'";
        } else {
            $q_hasil = "insert into rm_hasil_laboratorium (
                                id_pendaftaran, 
                                hasil_laboratorium
                            ) values (
                                '" . $id_pendaftaran . "',
                                '" . @mysql_escape_string($hasil_laboratorium) . "'
                            )";
        }
        $r_hasil = $this->runQuery($q_hasil);
        if ($r_hasil)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function simpanHapusanDarah($id_pendaftaran, $eritrosit, $leukosit, $trombosit) {
        $query = "select * from rm_lab_darah where id_pendaftaran='" . $id_pendaftaran . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $q_hasil = "update rm_lab_darah set 
                                eritrosit='" . @mysql_escape_string($eritrosit) . "',
                                leukosit='" . @mysql_escape_string($leukosit) . "',
                                trombosit='" . @mysql_escape_string($trombosit) . "'
                            where id_pendaftaran='" . $id_pendaftaran . "'";
        } else {
            $q_hasil = "insert into rm_lab_darah (
                                id_pendaftaran, 
                                eritrosit,
                                leukosit,
                                trombosit
                            ) values (
                                '" . $id_pendaftaran . "',
                                '" . @mysql_escape_string($eritrosit) . "',
                                '" . @mysql_escape_string($leukosit) . "',
                                '" . @mysql_escape_string($trombosit) . "'
                            )";
        }
        $r_hasil = $this->runQuery($q_hasil);
        if ($r_hasil)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function getBahanRadiologi($id_pendaftaran, $rows, $offset) {
        $query = "select a.*, b.id_radiologi from rm_bahan_radiologi a, rm_detail_radiologi b where a.id_pendaftaran='" . $id_pendaftaran . "' and b.id_detail_radiologi=a.id_detail_radiologi";
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
        }
    }

    public function getIdLab($id_det_lab) {
        $query = "select id_laboratorium from rm_detail_laboratorium where id_detail_laboratorium='" . $id_det_lab . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'id_laboratorium');
    }

    public function getTarifLaboratorium($id_pendaftaran, $id_laboratorium) {
        $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);
        $query = "select tarif from rm_tarif_laboratorium where id_laboratorium='" . $id_laboratorium . "'
                    and id_kelas='" . $id_kelas . "'";
        $result = $this->runQuery($query);

        return @mysql_result($result, 0, 'tarif');
    }

    public function simpanPemeriksaanLaboratorium(
    $id_pendaftaran, $id_pasien, $no_pemeriksaan, $id_kelompok_lab, $ambil, $periksa, $selesai, $cito
    ) {
        if (isset($_SESSION['level'])) {
            $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);
            $q_check = "select status_pembayaran from rm_pendaftaran where id_pendaftaran='" . $id_pendaftaran . "'";
            $r_check = $this->runQuery($q_check);

            $tgl_ambil = explode(" ", $ambil);
            $tgl_periksa = explode(" ", $periksa);
            $tgl_selesai = explode(" ", $selesai);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            if (@mysql_result($r_check, 0, 'status_pembayaran') != "2") {
                $query = "insert into rm_pemeriksaan_lab(
                    no_pemeriksaan,
                    id_pendaftaran,
                    id_pasien,
                    id_kelompok_lab,
                    ambil,
                    periksa,
                    selesai,
                    cito,
                    level,
                    id_tipe_pasien
                ) values (
                    '" . $no_pemeriksaan . "',
                    '" . $id_pendaftaran . "',
                    '" . $id_pasien . "',
                    '" . $id_kelompok_lab . "',
                    '" . $this->formatDateDb($tgl_ambil[0]) . " " . $tgl_ambil[1] . "',
                    '" . $this->formatDateDb($tgl_periksa[0]) . " " . $tgl_periksa[1] . "',
                    '" . $this->formatDateDb($tgl_selesai[0]) . " " . $tgl_selesai[1] . "',
                    '" . $cito . "',
                    '" . $_SESSION['level'] . "',
                    '" . $tipe_pasien . "'
                )";

                $result = $this->runQuery($query);
                if ($result) {
                    $q_lab = "SELECT id_laboratorium from rm_laboratorium where id_kelompok_lab='" . $id_kelompok_lab . "' and kelompok='1'";
                    $r_lab = $this->runQuery($q_lab);
                    $sukses = 0;
                    $gagal = 0;
                    while ($lab = @mysql_fetch_array($r_lab)) {
                        $tarif = $this->getTarifLaboratorium($id_pendaftaran, $lab['id_laboratorium']);
                        if ($cito == '1')
                            $tarif = $tarif + ($tarif * 0.25);
                        $q_det = "insert into rm_detail_laboratorium (
                            id_pendaftaran,
                            id_pasien,
                            id_kelompok_lab,
                            id_laboratorium,
                            cito,
                            tarif,
                            level,
                            id_kelas,
                            id_tipe_pasien
                        ) values (
                            '" . $id_pendaftaran . "',
                            '" . $id_pasien . "',
                            '" . $id_kelompok_lab . "',
                            '" . $lab['id_laboratorium'] . "',
                            '" . $cito . "',
                            '" . $tarif . "',
                            '" . $_SESSION['level'] . "',
                            '" . $id_kelas . "',
                            '" . $tipe_pasien . "'
                        )";
                        $r_det = $this->runQuery($q_det);
                        if ($r_det) {
                            $sukses++;
                        } else {
                            $gagal++;
                        }
                    }
                    if ($sukses > 0) {
                        $return = 'TRUE:' . $sukses . ':' . $gagal;
                    } else {
                        $return = 'WARNING:Tipe Pemeriksaan gagal di tambahkan';
                    }
                    $this->setStatusDaftar($id_pendaftaran);
                } else {
                    $return = 'ERROR';
                }
            } else {
                $return = "LUNAS";
            }

            return $return;
        }
        return 'LOGIN';
    }

    public function simpanClosePemeriksaanLaboratorium(
    $id_pendaftaran, $ambil, $periksa, $selesai
    ) {

        $tgl_periksa = explode(" ", $periksa);
        $tgl_selesai = explode(" ", $selesai);
        $query = "update rm_pemeriksaan_lab set
                    periksa='" . $this->formatDateDb($tgl_periksa[0]) . " " . $tgl_periksa[1] . "',
                    selesai='" . $this->formatDateDb($tgl_selesai[0]) . " " . $tgl_selesai[1] . "'
                where id_pendaftaran='" . $id_pendaftaran . "'";

        $result = $this->runQuery($query);
        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function simpanPemeriksaanPlusLaboratorium(
    $id_pendaftaran, $id_pasien, $id_kelompok_lab, $cito, $periksa
    ) {
        if (isset($_SESSION['level'])) {
            if ($cito == 1)
                $tarif = $tarif + ($tarif * 0.25);

            $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            $q_check = "select status_pembayaran from rm_pendaftaran where id_pendaftaran='" . $id_pendaftaran . "'";
            $r_check = $this->runQuery($q_check);

            if (@mysql_result($r_check, 0, 'status_pembayaran') != "2") {
                $tarif = $this->getTarifLaboratorium($id_pendaftaran, $periksa);
                $q_det = "insert into rm_detail_laboratorium (
                    id_pendaftaran,
                    id_pasien,
                    id_kelompok_lab,
                    id_laboratorium,
                    cito,
                    tarif,
                    level,
                    id_kelas,
                    id_tipe_pasien
                ) values (
                    '" . $id_pendaftaran . "',
                    '" . $id_pasien . "',
                    '" . $id_kelompok_lab . "',
                    '" . $periksa . "',
                    '" . $cito . "',
                    '" . $tarif . "',
                    '" . $_SESSION['level'] . "',
                    '" . $id_kelas . "',
                    '" . $tipe_pasien . "'
                )";
                $r_det = $this->runQuery($q_det);
                if ($r_det) {
                    $return = 'TRUE';
                } else {
                    $return = 'ERROR';
                }
                $this->setStatusDaftar($id_pendaftaran);
            } else {
                $return = "LUNAS";
            }

            return $return;
        }
        return 'LOGIN';
    }

    public function simpanDetailPemeriksaanLaboratorium(
    $id_pendaftaran, $id_detail_laboratorium, $hasil
    ) {

        if ($hasil != '') {
            $cito = $this->getCitoLab($id_pendaftaran);
            $id_kelas = $this->getKelasPendaftaran($id_pendaftaran);
            $id_laboratorium = $this->getIdLab($id_detail_laboratorium);

            $tarif = $this->getTarifLaboratorium($id_pendaftaran, $id_laboratorium);
            if ($cito == '1')
                $tarif = $tarif + ($tarif * 0.25);

            $query = "update rm_detail_laboratorium set
                        hasil='" . @mysql_escape_string($hasil) . "',
                        tarif='" . $tarif . "'
                     where id_detail_laboratorium='" . $id_detail_laboratorium . "'";
            $result = $this->runQuery($query);
            if ($result)
                $return = '1';
        }
        return $return;
    }

    public function cetakLaboratorium($idDaftar) {
        $query = "select * from rm_pemeriksaan_lab where id_pendaftaran='" . $idDaftar . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $dateAmbil = explode(' ', @mysql_result($result, 0, 'ambil'));
            $datePeriksa = explode(' ', @mysql_result($result, 0, 'periksa'));
            $dateSelesai = explode(' ', @mysql_result($result, 0, 'selesai'));
            $tanggal = $dateAmbil[0];
            $jamAmbil = $dateAmbil[1];
            $jamPeriksa = $datePeriksa[1];
            $jamSelesai = $dateSelesai[1];
            $kelas = $this->getKelas($this->getKelasPendaftaran($idDaftar));
            $nama = $this->getPasienNama(@mysql_result($result, 0, 'id_pasien'));
            $tglLahir = $this->getPasienLahir(@mysql_result($result, 0, 'id_pasien'));
            $kelamin = $this->getKelaminPasien(@mysql_result($result, 0, 'id_pasien'));
            $addr = explode(';', $this->getPasienInfo(@mysql_result($result, 0, 'id_pasien')));
            $kota = $this->getKota($addr[1]);
            $kecamatan = $this->getKecamatan($addr[2]);
            $kelurahan = $this->getKelurahan($addr[3]);
            $alamat = $addr[0] . " " . $kelurahan . " " . $kecamatan . " " . $kota;
            $usia = $this->getUmur($tglLahir);
            $file = fopen("../report/cetakHasilLab.html", 'w');
            fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title>HASIL PEMERIKSAAN LAB</title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
            fwrite($file, "<body>");
            fwrite($file, "<div class='printArea'>");
            fwrite($file, "<p align='center'><strong><big><big><big>RSUD Dr. SOEGIRI</big></big></big><br><small>RUMAH SAKIT PEMERINTAH<br></small>KABUPATEN LAMONGAN</strong>");
            fwrite($file, "<small><br>Jl. Kusuma Bangsa No. 7 Lamongan, Telp. (0322) 321718, 322582<br>Email : rsud@lamongan.go.id , website : www.lamongan.go.id</small><br></p><hr>");
            fwrite($file, "<table style=' font-family: verdana; font-size: 10px;' width='100%' cellpadding='0' cellspacing='1'>");
            fwrite($file, "
                    <tr height='17'>
                        <td>
                            Nomor Pemeriksaan
                        </td>
                        <td>
                            : <b>" . @mysql_result($result, 0, 'no_pemeriksaan') . "</b>
                        </td>
                        <td>
                            Px Rujukan
                        </td>
                        <td>
                            : <b>" . @$this->getAsalRujukan($this->getRujukan($idDaftar, 'id_asal_rujukan')) . "</b>
                        </td>
                    </tr>
                    <tr height='17'>
                        <td>
                            Nomor Rekam Medis
                        </td>
                        <td>
                            : <b>" . sprintf('%06d', @mysql_result($result, 0, 'id_pasien')) . "</b>
                        </td>
                        <td>
                            Detail Perujuk
                        </td>
                        <td>
                            : <b>" . @$this->getPerujuk($this->getRujukan($idDaftar, 'id_perujuk')) . "</b>
                        </td>
                    </tr>
                    <tr height='17'>
                        <td>
                            Nama Pasien
                        </td>
                        <td>
                            : <b>" . $nama . "</b>
                        </td>
                        <td>
                            Dokter Pengirim
                        </td>
                        <td>
                            : <b>" . @$this->getDokter($this->getDokterPenanggungJawab($this->getAsalDaftar($idDaftar))) . "</b>
                        </td>
                    </tr>
                    <tr height='17'>
                        <td>
                            Umur
                        </td>
                        <td>
                            : <b>" . $usia . "</b>
                        </td>
                        <td>
                            Ruang
                        </td>
                        <td>
                            : <b>" . @$this->getRuang($this->getAsalRuang($idDaftar)) . "</b>
                        </td>
                        <td>
                            Ambil Sample
                        </td>
                        <td>
                            : <b>" . $jamAmbil . "</b>
                        </td>
                    </tr>
                    <tr height='17'>
                        <td>
                            Jenis Kelamin
                        </td>
                        <td>
                            : <b>" . $kelamin . "</b>
                        </td>
                        <td>
                            Kelas
                        </td>
                        <td>
                            : <b>" . $kelas . "</b>
                        </td>
                        <td>
                            Periksa Sample
                        </td>
                        <td>
                            : <b>" . $jamPeriksa . "</b>
                        </td>
                    </tr>
                    <tr height='17'>
                        <td>
                            Alamat
                        </td>
                        <td>
                            : <b>" . $alamat . "</b>
                        </td>
                        <td>
                            Tanggal Pemeriksaan
                        </td>
                        <td>
                            : <b>" . $this->formatDateDb($tanggal) . "</b>
                        </td>
                        <td>
                            Sample Selesai
                        </td>
                        <td>
                            : <b>$jamSelesai</b>
                        </td>
                    </tr>
                    <tr height='17'><td height='17'></td></tr>
                    <tr height='17'>
                        <td>Bahan:</td>
                        <td>[   ] Darah</td>
                        <td>[   ] Urine</td>
                        <td>[   ] Faeces</td>
                        <td>[   ] Cairan Dalam Tubuh</td>
                    </tr>
                ");
            fwrite($file, "</table>");
            fwrite($file, "<table border='1' style='border-collapse:collapse; font-family: verdana; font-size: 10px;' width='100%' cellpadding='0' cellspacing='1'>");
            fwrite($file, "
                    <tr class='hd'>
                        <td width='57' align='center' valign='middle'>No.</td>
                        <td width='204' align='center' valign='middle'>Jenis Periksa</td>
                        <td width='97' align='center' valign='middle'>Metode</td>
                        <td width='119' align='center' valign='middle'>Hasil Pemeriksaan</td>
                        <td width='214' align='center' valign='middle'>Normal</td>
                    </tr>
                ");
            $q_kel = "select distinct(id_kelompok_lab) as id_kelompok_lab from rm_detail_laboratorium where id_pendaftaran='" . $idDaftar . "' and del_flag<>1 order by id_kelompok_lab";
            $r_kel = $this->runQuery($q_kel);
            while ($data = mysql_fetch_array($r_kel)) {
                $kelompok_lab = $this->getKelLab($data['id_kelompok_lab']);
                fwrite($file, "
                    <tr height='17'>
                        <td colspan='5'><b>" . $kelompok_lab . "</b></td>
                    </tr>
                ");
                $q_detail = "SELECT a.id_detail_laboratorium, b.kelompok_lab, c.laboratorium, c.metode, c.nilai_normal, 
                             a.hasil, a.tarif FROM rm_detail_laboratorium a, rm_kelompok_lab b, rm_laboratorium c
                             WHERE b.id_kelompok_lab=a.id_kelompok_lab AND a.del_flag<>'1' and c.id_laboratorium=a.id_laboratorium AND a.del_flag<>1
                             AND a.id_pendaftaran='" . $idDaftar . "' and a.id_kelompok_lab='" . $data['id_kelompok_lab'] . "' AND c.cetak='1'
                             order by a.id_laboratorium";
                $r_detail = $this->runQuery($q_detail);
                $i = 1;
                while ($detail = mysql_fetch_array($r_detail)) {
                    fwrite($file, "
                            <tr height='17'>
                                <td width='57' align='center'>" . $i . "</td>
                                <td width='204'>&nbsp;&nbsp;&nbsp;" . $detail['laboratorium'] . "</td>
                                <td width='97'>&nbsp;&nbsp;&nbsp;" . $detail['metode'] . "</td>
                                <td width='119'>&nbsp;&nbsp;&nbsp;" . $detail['hasil'] . "</td>
                                <td width='214'>&nbsp;&nbsp;&nbsp;" . $detail['nilai_normal'] . "</td>
                            </tr>
                        ");
                    $i++;
                }
            }
            fwrite($file, "</table>");
            fwrite($file, "<table style=' font-family: verdana; font-size: 10px;' width='75%' cellpadding='0' cellspacing='1'>");
            fwrite($file, "
                    <tr height='17'>
                        <td valign='top' align='left'>Interpretasi Hasil:</td>
                    </tr>
                    <tr height='17'>
                        <td valign='top'><b>" . $this->getOwnInterHasil($idDaftar) . "</b></td>
                    </tr>
                ");
            fwrite($file, "</table>");
            $q_check = "select * from rm_lab_darah where id_pendaftaran='" . $idDaftar . "'";
            $r_check = $this->runQuery($q_check);
            if (mysql_num_rows($r_check) > 0) {
                fwrite($file, "<table style=' font-family: verdana; font-size: 10px;' width='75%' cellpadding='0' cellspacing='1'>");
                fwrite($file, "
                        <tr height='17'>
                            <td colspan='100%'>Evaluasi Hapusan Darah Tepi</td>
                        </tr>
                        <tr height='17'>
                            <td>Eritrosit</td>
                            <td><b>" . @mysql_result($r_check, 0, 'eritrosit') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td>Leukosit</td>
                            <td><b>" . @mysql_result($r_check, 0, 'leukosit') . "</b></td>
                        </tr>
                        <tr height='17'>
                            <td>Trombosit</td>
                            <td><b>" . @mysql_result($r_check, 0, 'trombosit') . "</b></td>
                        </tr>
                    ");
                fwrite($file, "</table>");
            }
            fwrite($file, "<br><br><table style=' font-family: verdana; font-size: 10px;' width='100%' cellpadding='0' cellspacing='1'>");
            fwrite($file, "
                    <tr height='17'>
                        <td align='center' width='50%'>
                                                Ka. Instalasi Laboratorium
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                                                <b><u>dr. Setijamurti Sp.PK</u></b>
                        </td>
                        <td align='center' width='50%'>
                                                Petugas
                            <br>
                            <br>
                            <br>
                            <br>
                            <b>_______________</b><br>
                        </td>
                    </tr>
                ");
            fwrite($file, "</table>");
            fwrite($file, "</div></body></html>");
            //fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");	
            fclose($file);

            $qUpdate = "update rm_pemeriksaan_lab set status_cetak='1' where id_pendaftaran='" . $idDaftar . "'";
            $resUpdate = $this->runQuery($qUpdate);
            return '1';
        } else {
            return '0';
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
            $this->generateJasaLaboratorium($id_pendaftaran);
            return '1';
        } else {
            return '0';
        }
    }

}

?>