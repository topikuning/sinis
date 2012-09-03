<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../controller/cPendaftaran.php';

$pendaftaran = new cPendaftaran();

$task = $_GET['task'];
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;


switch ($task){
    case 'getKamar':
        echo $pendaftaran->getDetailKamar($_GET['id_ruang'], $_GET['id_kelas'], $rows, $offset);
        break;
    
    default:
        break;
}
?>
