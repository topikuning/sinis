<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cDiagnosa.php';

$diagnosa = new cDiagnosa();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'cariDtlPasien':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $diagnosa->getDetailPasien($no_pendaftaran);
        break;
    case 'cariDtlDiagnosa':
        $no_pendaftaran = $_GET['no_pendaftaran'];
        echo $diagnosa->getDtlDiagnosa($no_pendaftaran);
        break;
    case 'cariDokterJb':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $diagnosa->getDokterJb($no_pendaftaran);
        break;
    case 'getDetailDiagnosa':
        $no_pendaftaran = $_GET['no_pendaftaran'];

        echo $diagnosa->getDetailDiagnosa($no_pendaftaran,$rows,$offset);
        break;
    case 'getDiagnosa':
        $id_diagnosa = $_GET['id_diagnosa'];

        echo $diagnosa->getDataDiagnosa($id_diagnosa);
        break;
    case 'getDetailDiagnosaLain':
        $no_pendaftaran = $_GET['no_pendaftaran'];

        echo $diagnosa->getDetailDiagnosaLain($no_pendaftaran,$rows,$offset);
        break;
    case 'getListDiagnosa':
        $srcdiagnosa = $_GET['srcDiagnosa'];
        $icd = $_GET['srcICD'];
	
        echo $diagnosa->getDataListDiagnosa($srcdiagnosa, $icd, $rows, $offset);
        break;
    default:
        break;
}
?>
