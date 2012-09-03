<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cTindakan.php';

$tindakan = new cTindakan();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'getTindakanRuang':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getTindakanRuang($no_pendaftaran,$rows,$offset);
        break;
    case 'getFasilitasRuang':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getFasilitasRuang($no_pendaftaran,$rows,$offset);
        break;
    case 'getFasilitasRuangPux':
        $no_pendaftaran = $_GET['no_pendaftaran'];
	
        echo $tindakan->getFasilitasRuangPux($no_pendaftaran,$rows,$offset);
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
    case 'simpanDiskonTindakan':
        $id_pendaftaran = $_GET['id_pendaftaran'];
        $id_pasien = $_GET['id_pasien'];
        $diskon = $_GET['diskon'];
	
        echo $tindakan->simpanDiskonTindakan($id_pendaftaran, $id_pasien, $diskon);
        break;
    case 'getDtlTindakan':
        $id_tindakan_ruang = $_GET['id_tindakan_ruang'];
	
        echo $tindakan->getDetailTindakan($id_tindakan_ruang);
        break;
    case 'getTagihanTindakanPasien':
        $id_pendaftaran = $_GET['id_pendaftaran'];
	
        echo $tindakan->getTagihanTindakanPasien($id_pendaftaran);
        break;
    case 'getJasaTindakanPerawat':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_pasien = $_GET['tipe_pasien'];
	
        echo $tindakan->getJasaTindakanPerawat($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset);
        break;
    case 'getDtlFasilitas':
        $id_fasilitas_ruang = $_GET['id_fasilitas_ruang'];
	
        echo $tindakan->getDetailFasilitas($id_fasilitas_ruang);
        break;
    default:
        break;
	case 'getDtlBahan':
        $id_barang_tindakan = $_GET['id_barang_tindakan'];
	
        echo $tindakan->getDetailBahan($id_barang_tindakan);
        break;
    default:
        break;
}
?>
