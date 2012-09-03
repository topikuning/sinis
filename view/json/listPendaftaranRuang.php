<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cPendaftaran.php';

$daftar = new cPendaftaran();

$task = $_GET['task'];

switch ($task) {
    case 'cariPendaftaranRuang':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $perawatan = $_GET['perawatan'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $daftar->cariPendaftaranRuang(
                $id_pasien, $pasien, $perawatan, $startDate, $endDate, $rows, $offset
        );
        break;
    case 'cariIGDPulang':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $daftar->cariIGDPulang(
                $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
        );
        break;
    case 'cariPendaftaranRR':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $daftar->cariPendaftaranRR(
                $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
        );
        break;
    case 'cariPendaftaranRuangvk':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $daftar->cariPendaftaranRuangvk(
                $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
        );
        break;
    case 'cariPendaftaranRuang2':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $daftar->cariPendaftaranRuang2(
                $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
        );
        break;
    case 'cariKonsulRuang':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $daftar->cariKonsulRuang(
                $id_pasien, $pasien, $startDate, $endDate, $rows, $offset
        );
        break;
    default:
        break;
}
?>
