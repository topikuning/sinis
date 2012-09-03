<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cLaboratorium.php';

$laboratorium = new cLaboratorium();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'getDetailPemeriksaan':
        $no_pendaftaran = $_GET['no_pendaftaran'];

        echo $laboratorium->getDetailPemeriksaan($no_pendaftaran);
        break;
    case 'getDetailPerawatanLab':
        $id_pendaftaran = $_GET['id_pendaftaran'];

        echo $laboratorium->getDetailPerawatanLab($id_pendaftaran);
        break;
    case 'getPemeriksaanLab':
        $id_pendaftaran = $_GET['id_pendaftaran'];

        echo $laboratorium->getPemeriksaanLab($id_pendaftaran);
        break;
    case 'cariInterHasil':
        $no_pendaftaran = $_GET['no_pendaftaran'];

        echo $laboratorium->getInterHasil($no_pendaftaran);
        break;
    case 'cariHapusanDarah':
        $no_pendaftaran = $_GET['no_pendaftaran'];

        echo $laboratorium->getHapusanDarah($no_pendaftaran);
        break;
    case 'hapusLaboratorium':
        $id_detail_laboratorium = $_GET['id_detail_laboratorium'];

        echo $laboratorium->hapusLaboratorium($id_detail_laboratorium);
        break;
    case 'getJasaTindakanAnalis':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $laboratorium->getJasaTindakanAnalis($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    default:
        break;
}
?>
