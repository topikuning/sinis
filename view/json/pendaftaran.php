<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cPendaftaran.php';

$daftar = new cPendaftaran();

$task = $_POST['task'];

switch ($task) {
    case 'simpanPendaftaran':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_pasien = $_POST['id_pasien'];
        $tgl_pendaftaran = $_POST['tgl_pendaftaran'];
        if (isset($_POST['jadwal']))
            $jadwal = $_POST['jadwal'];
        else
            $jadwal = date('d-m-Y');
        if (isset($_POST['waktu']))
            $waktu = $_POST['waktu'];
        else
            $waktu = date('H:i:s');
        $tipe_pendaftaran = $_POST['id_tipe_pendaftaran'];
        $ruang_asal = $_POST['id_ruang_asal'];
        $ruang = $_POST['id_ruang'];
        $kelas = $_POST['id_kelas'];
        $id_kamar = $_POST['id_kamar'];
        $id_detail_kamar = $_POST['id_detail_kamar'];
        $dokter = $_POST['id_dokter'];
        $biaya_pendaftaran = $_POST['biaya_pendaftaran'];
        $asal_rujukan = $_POST['asal_rujukan'];
        $id_perujuk = $_POST['perujuk'];
        $alasan_rujukan = $_POST['alasan_rujuk'];
        echo $daftar->simpanPendaftaran(
                $id_pendaftaran, $id_pasien, $tgl_pendaftaran, $jadwal, $waktu, $tipe_pendaftaran, $ruang_asal, $ruang, $kelas, $id_kamar, $id_detail_kamar, $dokter, $biaya_pendaftaran, $id_perujuk, $asal_rujukan, $alasan_rujukan
        );
        break;
    case 'simpanPendaftaranKonsul':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_pasien = $_POST['id_pasien'];
        $tipe_pendaftaran = $_POST['id_tipe_pendaftaran'];
        $ruang_asal = $_POST['id_ruang_asal'];
        $kelas = $_POST['id_kelas'];
        $biaya_pendaftaran = $_POST['biaya_pendaftaran'];
        echo $daftar->simpanPendaftaranKonsul(
                $id_pendaftaran, $id_pasien, $tipe_pendaftaran, $ruang_asal, $kelas, $biaya_pendaftaran
        );
        break;
    case 'bukaUlang':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        echo $daftar->bukaUlang($id_pendaftaran);
        break;
    case 'updatePendaftaran':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_pasien = $_POST['id_pasien'];
        $tgl_pendaftaran = $_POST['tgl_pendaftaran'];
        $tipe_pendaftaran = $_POST['id_tipe_pendaftaran'];
        $ruang_asal = $_POST['id_ruang_asal'];
        $ruang = $_POST['id_ruang'];
        $id_kamar = $_POST['id_kamar'];
        $id_detail_kamar = $_POST['id_detail_kamar'];
        $kelas = $_POST['id_kelas'];
        $dokter = $_POST['id_dokter'];
        $biaya_pendaftaran = $_POST['biaya_pendaftaran'];
        $asal_rujukan = $_POST['asal_rujukan'];
        $id_perujuk = $_POST['perujuk'];
        $alasan_rujukan = $_POST['alasan_rujuk'];
        echo $daftar->updatePendaftaran(
                $id_pendaftaran, $id_pasien, $tgl_pendaftaran, $tipe_pendaftaran, $ruang_asal, $ruang, $kelas, $id_kamar, $id_detail_kamar, $dokter, $biaya_pendaftaran, $id_perujuk, $asal_rujukan, $alasan_rujukan
        );
        break;
    case 'cetakKarcis':
        echo $daftar->cetakKarcis($_POST['idDaftar']);
        break;
    case 'cetakSJP':
        echo $daftar->cetakSJP($_POST['idDaftar']);
        break;
    case 'batalDaftar':
        $no_pendaftaran = $_POST['id_pendaftaran'];
        echo $daftar->batalPendaftaran($no_pendaftaran);
        break;
    case 'cekPasienDaftar':
        $id_pasien = $_POST['id_pasien'];
        $tipe_pendaftaran = $_POST['tipe_pendaftaran'];
        echo $daftar->cekPasienDaftar($id_pasien, $tipe_pendaftaran);
        break;
    case 'setPerawatan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        echo $daftar->setPerawatan($id_pendaftaran);
        break;
    case 'reClose':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_penggunaan = $_POST['id_penggunaan'];
        echo $daftar->reClose($id_pendaftaran, $id_penggunaan);
        break;
    case 'bukaPindah':
        $id_penggunaan = $_POST['id_penggunaan'];
        echo $daftar->bukaPindah($id_penggunaan);
        break;
    default:
        break;
}
?>
