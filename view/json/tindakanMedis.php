<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cTindakanMedis.php';

$tindakan = new cTindakanMedis();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'getPenggunaanKamar':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getPenggunaanKamar($no_pendaftaran);
        break;
    case 'getTindakanRuang':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getTindakanRuang($no_pendaftaran,$rows,$offset);
        break;
    case 'getFasilitasRuang':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getFasilitasRuang($no_pendaftaran,$rows,$offset);
        break;
    case 'getBarangTindakan':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getBarangTindakan($no_pendaftaran,$rows,$offset);
        break;
    case 'getListTindakan':
        $srcTindakan = $_GET['srcTindakan'];
        $jns_tindakan = $_GET['idTindakan'];
        $ruang = $_SESSION['level'];
	
        echo $tindakan->getDataListTindakan($srcTindakan, $ruang, $jns_tindakan, $rows, $offset);
        break;
    case 'getDtlTindakan':
        $id_tindakan_ruang_medis = $_GET['id_tindakan_ruang_medis'];
	
        echo $tindakan->getDetailTindakanMedis($id_tindakan_ruang_medis);
        break;
    case 'getDtlFasilitas':
        $id_fasilitas_ruang = $_GET['id_fasilitas_ruang'];
	
        echo $tindakan->getDetailFasilitas($id_fasilitas_ruang);
        break;
    case 'cariDtlKamar':
        $id_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getDetailKamar($id_pendaftaran);
        break;
    default:
        break;
}
?>
