<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cDokter.php';

$dokter = new cDokter();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'getJasaTindakanDokter':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaTindakanDokter($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaTindakanMnj':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $id_dokter = $_GET['dokter'];
        $ruang = $_GET['ruang'];
	
        echo $dokter->getJasaTindakanMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $ruang, $rows, $offset);
        break;
    case 'getJasaPendaftaranDokter':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaPendaftaranDokter($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaPendaftaranMnj':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $id_dokter = $_GET['dokter'];
	
        echo $dokter->getJasaPendaftaranMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset);
        break;
    case 'getJasaPendaftaran':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaPendaftaran($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaPemeriksaanDokter':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaPemeriksaanDokter($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaVisit':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaVisit($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaPerawatanMnj':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $id_ruang = $_GET['ruang'];
	
        echo $dokter->getJasaPerawatanMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_ruang, $rows, $offset);
        break;
    case 'getJasaPerawatan':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaPerawatan($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaVisitMnj':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $id_dokter = $_GET['dokter'];
	
        echo $dokter->getJasaVisitMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset);
        break;
    case 'getJasaTindakanDokterLab':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaTindakanDokterLab($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaLabMnj':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $id_dokter = $_GET['dokter'];
	
        echo $dokter->getJasaLabMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset);
        break;
    case 'getJasaTindakanDokterRadiologi':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaTindakanDokterRadiologi($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaRadMnj':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $id_dokter = $_GET['dokter'];
	
        echo $dokter->getJasaRadMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset);
        break;
    case 'getJasaTindakanDokterBedah':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $dokter->getJasaTindakanDokterBedah($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getJasaBedahMnj':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $id_dokter = $_GET['dokter'];
	
        echo $dokter->getJasaBedahMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset);
        break;
    default:
        break;
}
?>
