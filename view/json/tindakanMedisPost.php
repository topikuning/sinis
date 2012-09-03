<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cTindakanMedis.php';

$tindakan = new cTindakanMedis();

$task = $_POST['task'];

switch ($task){
    case 'getTarif':
        $id_tindakan = $_POST['id_tindakan'];
        $no_pendaftaran = $_POST['no_pendaftaran'];
	
        echo $tindakan->getTarifTindakan($no_pendaftaran,$id_tindakan);
        break;
    case 'getTarifBahan':
        $id_barang = $_POST['id_barang'];
        $no_pendaftaran = $_POST['no_pendaftaran'];
	
        echo $tindakan->getTarifBahan($no_pendaftaran,$id_barang);
        break;
    case 'simpanKamar':
        $id_penggunaan_kamar = $_POST['id_penggunaan_kamar'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_detail_kamar = $_POST['id_detail_kamar'];
        $tgl_mulai = $_POST['tgl_mulai']; 
        $tgl_selesai = $_POST['tgl_selesai']; 
        $ket_selesai = $_POST['ket_selesai'];
        $tarif = $_POST['tarif'];

        echo $tindakan->saveKamar($id_penggunaan_kamar, $id_pendaftaran, $id_detail_kamar, $tgl_mulai, $tgl_selesai, $ket_selesai, $tarif);
        break;
    case 'hapusTindakanMedis':
        $id_tindakan = $_POST['id_tindakan'];

        echo $tindakan->hapusTindakanMedis($id_tindakan);
        break;
    case 'hapusFasilitas':
        $id_tindakan = $_POST['id_fasilitas'];

        echo $tindakan->hapusFasilitas($id_tindakan);
        break;
    case 'hapusKamar':
        $id_penggunaan_kamar = $_POST['id_penggunaan_kamar'];

        echo $tindakan->hapusKamar($id_penggunaan_kamar);
        break;
    case 'simpanFasilitas':
        $id_fasilitas_ruang = $_POST['id_fasilitas_ruang'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_tindakan = $_POST['id_tindakan'];
        $id_dokter = $_POST['id_dokter']; 
        $advice = $_POST['advice']; 
        $id_tarif = $_POST['id_tarif'];
        $id_operator = $_POST['id_operator'];
        $tarif = $_POST['tarifF'];
        $jumlah = $_POST['jumlah'];

        echo $tindakan->saveFasilitas($id_fasilitas_ruang, $id_pendaftaran, $id_tindakan, $id_dokter, $advice, $id_tarif, $id_operator, $tarif, $jumlah);
        break;
    case 'simpanTindakan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_tindakan_ruang_medis = $_POST['id_tindakan_ruang_medis'];
        $id_tindakan_medis = $_POST['id_tindakan_medis'];
        $dokter_operator = $_POST['dokter_operator'];
        $dokter_anestesi = $_POST['dokter_anestesi'];
        $cito = $_POST['cito']; 
        $tarif = $_POST['tarif']; 
        $tarifTambah = $_POST['tarifTambah']; 
        $advice = $_POST['advice'];
        $alat_tamu = $_POST['alat_tamu'];

        echo $tindakan->saveTindakan($id_tindakan_ruang_medis, $id_tindakan_medis, $id_pendaftaran, $dokter_operator, $dokter_anestesi, $cito, $tarif, $tarifTambah, $advice, $alat_tamu);
        break;
    case 'simpanBahan':
        $id_barang_tindakan = $_POST['id_barang_tindakan'];
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_barang = $_POST['id_barang'];
        $jumlah = $_POST['jumlah']; 
        $tarif = $_POST['tarifBahan'];

        echo $tindakan->saveBahan($id_barang_tindakan, $id_pendaftaran, $id_barang, $jumlah, $tarif);
        break;
    case 'simpanClosePemeriksaan':
        $id_pendaftaran = $_POST['id_pendaftaran'];
        $id_keadaan = $_POST['kondisi_keluar'];
        $keterangan = $_POST['keterangan_keluar'];
	
        echo $tindakan->saveClosePemeriksaan($id_pendaftaran, $id_keadaan, $keterangan);
        break;
    default:
        break;
}
?>
