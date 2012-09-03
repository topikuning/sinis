<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cPendaftaran.php';

$daftar = new cPendaftaran();

$task = $_GET['task'];

switch ($task){
    case 'cariPendaftaran':
        $no_rm = $_GET['no_rm'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $status = $_GET['status'];
        $closed = $_GET['closed'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 25;
	$offset = ($page-1)*$rows;
	
        echo $daftar->cariPendaftaran(
                $no_rm,
                $pasien,
                $startDate,
                $endDate,
                $tipe_pasien,
                $status,
                $closed,
                $rows,
                $offset
            );
        break;
    case 'cariPendaftaranAll':
        $no_pendaftaran = $_GET['no_pendaftaran'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $daftar->cariPendaftaranAll(
                $no_pendaftaran,
                $pasien,
                $startDate,
                $endDate,
                $rows,
                $offset
            );
        break;
    case 'detailPendaftaran':
        $no_pendaftaran = $_GET['id_pendaftaran'];
	
        echo $daftar->detailPendaftaran($no_pendaftaran);
        break;
    case 'cariPendaftaranInformasi':
        $no_rm = $_GET['no_rm'];
        $pasien = $_GET['pasien'];
        $alamat = $_GET['alamat'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $status = $_GET['status'];
        $closed = $_GET['closed'];
        $ruangane = $_GET['ruangane'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 25;
	$offset = ($page-1)*$rows;
	
        echo $daftar->cariPendaftaranInformasi(
                $no_rm,
                $pasien,
                $alamat,
                $startDate,
                $endDate,
                $tipe_pasien,
                $status,
                $closed,
                $ruangane,
                $rows,
                $offset
            );
        break;
    case 'cariPasienKeluar':
        $no_rm = $_GET['no_rm'];
        $pasien = $_GET['pasien'];
        $alamat = $_GET['alamat'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $status = $_GET['status'];
        $id_ruang = $_GET['id_ruang'];
        echo $daftar->cariPasienKeluar(
                $no_rm,
                $pasien,
                $alamat,
                $startDate,
                $endDate,
                $tipe_pasien,
                $status,
                $id_ruang
            );
        break;
    case 'cariPasienPindah':
        $no_rm = $_GET['no_rm'];
        $pasien = $_GET['pasien'];
        $alamat = $_GET['alamat'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $id_ruang = $_GET['id_ruang'];
        echo $daftar->cariPasienPindah(
                $no_rm,
                $pasien,
                $alamat,
                $startDate,
                $endDate,
                $id_ruang
            );
        break;
    default:
        break;
}
?>
