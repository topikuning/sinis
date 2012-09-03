<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cTindakan.php';

$tindakan = new cTindakan();

$task = $_POST['task'];

switch ($task){
    case 'getTarif':
        $id_detail_tindakan = $_POST['id_detail_tindakan'];
        $no_pendaftaran = $_POST['no_pendaftaran'];
	
        echo $tindakan->getTarifTindakan($no_pendaftaran,$id_detail_tindakan);
        break;
    case 'getTarifPindah':
        $id_detail_tindakan = $_POST['id_detail_tindakan'];
        $id_kelas = $_POST['kelas'];
	
        echo $tindakan->getTarifTindakanPindah($id_kelas,$id_detail_tindakan);
        break;
    case 'getTarifPux':
        $id_detail_tindakan = $_POST['id_detail_tindakan'];
        $no_pendaftaran = $_POST['no_pendaftaran'];
        $id_kamar = $_POST['id_kamar'];
	
        echo $tindakan->getTarifFasilitas($no_pendaftaran,$id_detail_tindakan, $id_kamar);
        break;
    case 'getTarifBahan':
        $id_barang = $_POST['id_barang'];
        $no_pendaftaran = $_POST['no_pendaftaran'];
	
        echo $tindakan->getTarifBahan($no_pendaftaran,$id_barang);
        break;
	case 'getBahanRuang':
        $id_barang = $_POST['id_barang'];
		
        echo $tindakan->getBahanRuang($id_barang);
        break;
    case 'simpanTindakan':
        $id_tindakan_ruang = $_POST['id_tindakan_ruang'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_tindakan = $_POST['id_tindakan'];
        $id_dokter = $_POST['id_dokter']; 
        $advice = $_POST['advice']; 
        $tarif = $_POST['tarif'];
        $cito = $_POST['cito'];
        $id_tarif = $_POST['id_tarif'];
        $id_operator = $_POST['id_operator'];
        $tglInput = $_POST['tglInput'];
        $kelase = $_POST['kelase'];

        echo $tindakan->saveTindakan($id_tindakan_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $tarif, $cito, $id_tarif, $id_operator, $tglInput, $kelase);
        break;
    case 'hapusTindakan':
        $id_tindakan = $_POST['id_tindakan'];

        echo $tindakan->hapusTindakan($id_tindakan);
        break;
    case 'hapusFasilitas':
        $id_tindakan = $_POST['id_fasilitas'];

        echo $tindakan->hapusFasilitas($id_tindakan);
        break;
    case 'hapusBahan':
        $id_barang_tindakan = $_POST['id_barang_tindakan'];

        echo $tindakan->hapusBahan($id_barang_tindakan);
        break;
    case 'simpanFasilitas':
        $id_fasilitas_ruang = $_POST['id_fasilitas_ruang'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_tindakan = $_POST['id_tindakan'];
        $id_dokter = $_POST['id_dokter']; 
        $advice = $_POST['advice']; 
        $id_tarif = $_POST['id_tarif'];
        $tarif = $_POST['tarifF'];
        $jumlah = $_POST['jumlah'];

        echo $tindakan->saveFasilitas($id_fasilitas_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $id_tarif, $tarif, $jumlah);
        break;
    case 'simpanFasilitasPux':
        $id_fasilitas_ruang = $_POST['id_fasilitas_ruang'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_tindakan = $_POST['id_tindakan'];
        $id_dokter = $_POST['id_dokter']; 
        $advice = $_POST['advice']; 
        $id_tarif = $_POST['id_tarif'];
        $tarif = $_POST['tarifF'];
        $jumlah = $_POST['jumlah'];
        $id_kamar = $_POST['id_kamar'];

        echo $tindakan->saveFasilitasPux($id_fasilitas_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $id_tarif, $tarif, $jumlah, $id_kamar);
        break;
    case 'simpanBahan':
        $id_barang_tindakan = $_POST['id_barang_tindakan'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_barang = $_POST['id_barang'];
        $jumlah = $_POST['jumlah']; 
        //$tarif = $_POST['tarifBahan'];

        echo $tindakan->saveBahan($id_barang_tindakan, $tipe, $id_barang, $jumlah);//, $tarif);
        break;
	case 'simpanBahanBal':
        $id_barang_tindakan = $_POST['id_barang_tindakan'];
        $tipe = $_POST['tipe'];
        $id_barang = $_POST['id_barang'];
        $jumlah = $_POST['jumlah']; 

        echo $tindakan->saveBahanBal($id_barang_tindakan, $tipe, $id_barang, $jumlah);
        break;
    default:
        break;
}
?>