<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cDiagnosa.php';

$diagnosa = new cDiagnosa();

$task = $_POST['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'simpanDiagnosa':
        $id_diagnosa = $_POST['id_diagnosa'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_pasien = $_POST['id_pasien'];
        $id_dokter = $_POST['id_dokter'];
        $diagnosa_primer = $_POST['diagnosa_primer'];
        $diagnosa_sekunder = $_POST['diagnosa_sekunder'];
	
        echo $diagnosa->saveDiagnosa($id_diagnosa, $id_pendaftaran, $id_pasien, $id_dokter, $diagnosa_primer, $diagnosa_sekunder);
        break;
    case 'hapusDiagnosa':
        $id_diagnosa = $_POST['id_diagnosa'];
	
        echo $diagnosa->hapusDiagnosa($id_diagnosa);
        break;
    case 'simpanDetailDiagnosa':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_detail_diagnosa = $_POST['id_detail_diagnosa'];
        $diagnosa_lain = $_POST['diagnosa_lain'];
        $keluhan_lain = $_POST['keluhan_lain'];
        $hasil_pemeriksaan = $_POST['hasil_pemeriksaan'];
        $terapi = $_POST['terapi'];
        $nadi = $_POST['nadi'];
        $tensi = $_POST['tensi'];
        $temperatur = $_POST['temperatur'];
        $nafas = $_POST['nafas'];
        $berat_badan = $_POST['berat_badan'];
        $tinggi_badan = $_POST['tinggi_badan'];
        $konsul = $_POST['jenis'];
        $id_ruang = $_POST['id_ruang'];
	
        echo $diagnosa->saveDetailDiagnosa(
                $id_pendaftaran,
                $id_detail_diagnosa,
                $diagnosa_lain,
                $keluhan_lain,
                $hasil_pemeriksaan,
                $terapi,
                $nadi,
                $tensi,
                $temperatur,
                $nafas,
                $berat_badan,
                $tinggi_badan,
                $konsul, 
                $id_ruang
             );
        break;
    default:
        break;
}
?>
