<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cRadiologi.php';

$radiologi = new cRadiologi();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'getDetailPemeriksaan':
        $no_pendaftaran = $_GET['no_pendaftaran'];

        echo $radiologi->getDetailRadiologi($no_pendaftaran,$rows,$offset);
        break;
    case 'getBahanPemeriksaan':
        $no_pendaftaran = $_GET['no_pendaftaran'];

        echo $radiologi->getBahanRadiologi($no_pendaftaran,$rows,$offset);
        break;
    case 'getJasaTindakanRadiografer':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $radiologi->getJasaTindakanRadiografer($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    default:
        break;
}
?>
