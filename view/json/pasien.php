<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../controller/cPasien.php';

$pasien = new cPasien();

$task = $_GET['task'];

switch ($task){
    case 'cariPasienID':
        echo $pasien->cariPasienID($_GET['id']);
        break;
    case 'getPasienDetail':
        echo $pasien->cariPasienDetail($_GET['id_pasien']);
        break;
    default:
        break;
}
?>
