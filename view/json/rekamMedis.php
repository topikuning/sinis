<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cRekam.php';

$rekam = new cRekam();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'cariDtlPasien':
        $id_pasien = $_GET['id_pasien'];
	
        echo $rekam->cariDtlPasien($id_pasien);
        break;
    case 'getRekamMedisPasien':
        $id_pasien = $_GET['id_pasien'];
        $nama_pasien = $_GET['nama_pasien'];
        $id_ruang = $_GET['id_ruang'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        echo $rekam->getRekamMedisPasien(
                $id_pasien,
                $nama_pasien,
                $id_ruang,
                $startDate,
                $endDate,
                $rows,
                $offset
             );
        break;
    default:
        break;
}
?>
