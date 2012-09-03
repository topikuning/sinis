<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cData.php';

$data = new cData();

$id     =   @$_POST['id'];
$params   =   @$_POST['data'];
$no_pendaftaran = @$_GET['no_pendaftaran'];

switch ($id){
    case 'radiologiField':
        echo $data->getListRadiologi($params);
        break;
    case 'kelompokPeriksa':
        echo $data->getListKelompokLab($params);
        break;
    case 'penyakitPrimer':
        echo $data->getListPenyakit($params);
        break;
    case 'penyakitSekunder':
        echo $data->getListPenyakit($params);
        break;
    case 'tindakan':
        echo $data->getTindakanPoli($params);
        break;
	case 'namaPasienField':
        echo $data->getPasienAlamat($params);
        break;
    case 'tindakanMedis':
        echo $data->getTindakanPoli($params);
        break;
    case 'tindakanF':
        echo $data->getFasilitasPoli($params);
        break;
    case 'bahan':
        echo $data->getBahan($params);
        break;
	case 'bahanBal':
        echo $data->getBahanBal($params);
        break;
    case 'obatBal':
        echo $data->getObat($params);
        break;
    case 'nama_obat':
        echo $data->getObat($params);
        break;
    case 'nama_obatBeli':
        echo $data->getObatBeli($params);
        break;
    case 'nama_obat_racikan':
        echo $data->getObat($params);
        break;
    case 'nama_obatJ':
        echo $data->getObatJual($params);
        break;
    case 'nama_obatSJ':
        echo $data->getObatJual($params);
        break;
    case 'namaBarang':
        echo $data->getBarang($params);
        break;
    case 'namaBarang1':
        echo $data->getBarang($params);
        break;
    default:
        break;
}
?>
