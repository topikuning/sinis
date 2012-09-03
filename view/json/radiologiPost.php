<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cRadiologi.php';

$radiologi = new cRadiologi();

$task = $_POST['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'simpanPemeriksaan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_pasien = $_POST['id_pasien'];
        $id_radiologi = $_POST['radiologiFieldId'];
        $tarif = $_POST['tarif'];
        $ukuranA = $_POST['ukuranA'];
        $jumlahA = $_POST['jumlahA'];
        $ukuranB = $_POST['ukuranB'];
        $jumlahB = $_POST['jumlahB'];
        $ukuranC = $_POST['ukuranC'];
        $jumlahC = $_POST['jumlahC'];
        $ukuranD = $_POST['ukuranD'];
        $jumlahD = $_POST['jumlahD'];
        $cito = $_POST['cito'];
        $cito_bed = $_POST['citoBed'];
        $keterangan = $_POST['keterangan'];
	
        echo $radiologi->simpanPemeriksaanRadiologi(
                $id_pendaftaran,
                $id_pasien,
                $id_radiologi,
                $tarif,
                $ukuranA,
                $jumlahA,
                $ukuranB,
                $jumlahB,
                $ukuranC,
                $jumlahC,
                $ukuranD,
                $jumlahD,
                $cito,
                $cito_bed,
                $keterangan
             );
        break;
    case 'getHarga':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_radiologi = $_POST['id_radiologi'];
	
        echo $radiologi->getTarifRadiologi($id_pendaftaran,$id_radiologi);
        break;
    case 'simpanClosePemeriksaan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_keadaan = $_POST['kondisi_keluar'];
        $id_cara_keluar = $_POST['cara_keluar'];
        $keterangan = $_POST['keterangan_keluar'];
        $tgl_keluar = $_POST['tgl_out'];
	
        echo $radiologi->saveClosePemeriksaan($id_pendaftaran, $id_keadaan, $id_cara_keluar, $keterangan, $tgl_keluar);
        break;
    case 'hapusRadiologi':
        $id_detail_radiologi = $_POST['id_detail_radiologi'];
	
        echo $radiologi->hapusRadiologi($id_detail_radiologi);
        break;
    default:
        break;
}
?>
