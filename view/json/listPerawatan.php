<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cPerawatan.php';

$rawat = new cPerawatan();

$task = $_GET['task'];

switch ($task){
    case 'cariPerawatan':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 25;
	$offset = ($page-1)*$rows;
	
        echo $rawat->cariPerawatanRuang(
                $id_pasien,
                $pasien,
                $startDate,
                $endDate,
                $rows,
                $offset
            );
        break;
    case 'cariPerawatanDiet':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 25;
	$offset = ($page-1)*$rows;
	
        echo $rawat->cariPerawatanRuangDiet(
                $id_pasien,
                $pasien,
                $startDate,
                $endDate,
                $rows,
                $offset
            );
        break;
    case 'cariPerawatanUtilitas':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 25;
	$offset = ($page-1)*$rows;
	
        echo $rawat->cariPerawatanRuangUtilitas(
                $id_pasien,
                $pasien,
                $startDate,
                $endDate,
                $rows,
                $offset
            );
        break;
    case 'cariPerawatanUlang':
        $id_pasien = $_GET['id_pasien'];
        $pasien = $_GET['pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 25;
	$offset = ($page-1)*$rows;
	
        echo $rawat->cariPerawatanRuangUlang(
                $id_pasien,
                $pasien,
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
