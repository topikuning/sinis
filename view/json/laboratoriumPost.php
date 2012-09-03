<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cLaboratorium.php';

$laboratorium = new cLaboratorium();

$task = $_POST['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'simpanPemeriksaan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_pasien = $_POST['id_pasien'];
        $no_pemeriksaan = $_POST['noPeriksa'];
        $id_kelompok_lab = $_POST['kelompokPeriksaId'];
        $ambil = $_POST['ambilSampel'];
        $periksa = $_POST['periksaSampel'];
        $selesai = $_POST['selesaiSampel'];
        $cito = $_POST['cito'];
	
        echo $laboratorium->simpanPemeriksaanLaboratorium(
                $id_pendaftaran,
                $id_pasien,
                $no_pemeriksaan,
                $id_kelompok_lab,
                $ambil,
                $periksa,
                $selesai,
                $cito
             );
        break;
    case 'simpanClosePemeriksaan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $ambil = $_POST['ambilSampel'];
        $periksa = $_POST['periksaSampel'];
        $selesai = $_POST['selesaiSampel'];
	
        echo $laboratorium->simpanClosePemeriksaanLaboratorium(
                $id_pendaftaran,
                $ambil,
                $periksa,
                $selesai
             );
        break;
    case 'simpanPemeriksaanPlus':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_pasien = $_POST['id_pasien'];
        $id_kelompok_lab = $_POST['kelompokPeriksaId'];
        $cito = $_POST['citoPlus'];
        $periksa = $_POST['periksa'];
	
        echo $laboratorium->simpanPemeriksaanPlusLaboratorium(
                $id_pendaftaran,
                $id_pasien,
                $id_kelompok_lab,
                $cito,
                $periksa
             );
        break;
    case 'simpanDetailPemeriksaan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_detail_laboratorium = $_POST['id_detail_laboratorium'];
        $hasil = $_POST['hasil'];
	
        echo $laboratorium->simpanDetailPemeriksaanLaboratorium(
                $id_pendaftaran,
                $id_detail_laboratorium,
                $hasil
             );
        break;
    case 'simpanInterHasil':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $hasil_laboratorium = $_POST['interHasil'];
	
        echo $laboratorium->simpanInterHasil(
                $id_pendaftaran,
                $hasil_laboratorium
             );
        break;
    case 'simpanHapusanDarah':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $eritrosit = $_POST['eritrosit'];
        $leukosit = $_POST['leukosit'];
        $trombosit = $_POST['trombosit'];
	
        echo $laboratorium->simpanHapusanDarah(
                $id_pendaftaran,
                $eritrosit,
                $leukosit,
                $trombosit
             );
        break;
    case 'cetakLaboratorium':
        $id_pendaftaran = $_POST['id_pendaftaran'];
	
        echo $laboratorium->cetakLaboratorium(
                $id_pendaftaran
             );
        break;
    case 'simpanClosePerawatan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_keadaan = $_POST['kondisi_keluar'];
        $id_cara_keluar = $_POST['cara_keluar'];
        $keterangan = $_POST['keterangan_keluar'];
        $tgl_keluar = $_POST['tgl_out'];
	
        echo $laboratorium->saveClosePemeriksaan($id_pendaftaran, $id_keadaan, $id_cara_keluar, $keterangan, $tgl_keluar);
        break;
    default:
        break;
}
?>
