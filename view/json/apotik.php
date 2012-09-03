<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cApotik.php';

$apotik = new cApotik();

$task = $_GET['task'];

switch ($task) {
    case 'simpanFakturPenjualan':
        $no_resep = $_GET['no_resep'];
        $jns_customer = $_GET['jns_customer'];
        $id_pasien = $_GET['id_pasien'];
        $id_ruang = $_GET['id_ruang'];
        $dokter = $_GET['dokter'];
        $nama_pasien = $_GET['nama_pasien'];
        $alamat = $_GET['alamat'];
        $idp = $_GET['idp'];
        $idr = $_GET['idr'];

        echo $apotik->simpanFakturPenjualan(
                $no_resep, $jns_customer, $id_pasien, $id_ruang, $dokter, $nama_pasien, $alamat, $idp, $idr
        );
        break;
    case 'simpanDetailObat':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $id_penjualan_obat = $_GET['id_penjualan_obat'];
        $id_obat = $_GET['id_obat'];
        $qty = $_GET['qty'];
        $harga = $_GET['harga'];
        $r_code = $_GET['r_code'];

        if ($id_penjualan_obat == "")
            echo $apotik->simpanDetailPenjualan(
                    $id_faktur_penjualan, $id_obat, $qty, $harga, $r_code
            );
        else
            echo $apotik->updateDetailPenjualan(
                    $id_faktur_penjualan, $id_penjualan_obat, $id_obat, $qty, $harga, $r_code
            );

        break;
    case 'simpanReturObat':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $id_obat = $_GET['id_obat'];
        $jns_retur = $_GET['jns_retur'];
        $pros_retur = $_GET['pros_retur'];
        $jumlah = $_GET['jumlah'];
        $id_jual = $_GET['id_penjualan'];

        echo $apotik->simpanReturObat($id_faktur_penjualan, $id_obat, $jns_retur, $pros_retur, $jumlah, $id_jual);
        break;
    case 'cekKadaluarsa':
        $id_obat = $_GET['id_obat'];

        echo $apotik->cekKadaluarsa($id_obat);
        break;
    case 'simpanDetailRacikan':
        $id_racikan = $_GET['id_racikan'];
        $id_obat = $_GET['id_obat'];
        $qty = $_GET['qty'];
        $harga = $_GET['harga'];
        $r_code = $_GET['r_code'];

        echo $apotik->simpanDetailRacikan(
                $id_racikan, $id_obat, $qty, $harga, $r_code
        );
        break;
    case 'simpanPembayaran':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $total = $_GET['total'];
        $diskon = $_GET['diskonObat'];
        $bayar = $_GET['bayar'];
        $asuransi = $_GET['asuransi'];
        $sisa = $_GET['sisa'];

        echo $apotik->simpanPembayaranObat(
                $id_faktur_penjualan, $total, $diskon, $bayar, $asuransi, $sisa
        );
        break;
    case 'simpanDiskonTemp':
        $id_faktur = $_GET['faktur'];
        $diskon = $_GET['diskon'];
        echo $apotik->diskonTemp($id_faktur, $diskon);
        break;
    case 'getDiskonTemp':
        $id_faktur = $_GET['faktur'];
        echo $apotik->getDiskonTemp($id_faktur);
        break;
    case 'cetakBayarObat':
        echo $apotik->cetakBayarObat($_GET['id_pembayaran_obat']);
        break;
    case 'cetakKwitansi':
        echo $apotik->cetakKwitansi($_GET['id_faktur']);
        break;
    case 'cetakKwitansiRetur':
        echo $apotik->cetakKwitansiRetur($_GET['id_faktur_penjualan']);
        break;
    case 'cetakUlangRetur':
        echo $apotik->cetakUlangRetur($_GET['id_retur']);
        break;
    case 'simpanRacikan':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $racikan = $_GET['racikan'];

        echo $apotik->simpanRacikan(
                $id_faktur_penjualan, $racikan
        );
        break;
    case 'getHargaObat':
        $id_obat = $_GET['id_obat'];
        $ruang = $_GET['ruang'];
        $id_pasien = $_GET['id_pasien'];

        echo $apotik->getHargaObat(
                $id_obat, $ruang, $id_pasien
        );
        break;
    case 'getLaporanPenjualan':
        $shift = $_GET['shift'];
        $status = $_GET['status'];
        $startDate = $_GET['startDate'];

        echo $apotik->getLaporanPenjualan(
                $shift, $status, $startDate
        );
        break;
    case 'stockUlang':
        $id_obat = $_GET['id_obat'];
        $id_penjualan = $_GET['id_penjualan'];
        echo $apotik->reStock($id_obat, $id_penjualan);
        break;
    case 'getRekapResep':
        $jenis_perawatan = $_GET['jenis_perawatan'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $apotik->getRekapResep(
                $jenis_perawatan, $tipe_pasien, $startDate, $endDate
        );
        break;
    case 'simpanObatBal':
        $id_obat_balance = $_GET['id_obat_balance'];
        $tipe = $_GET['tipe'];
        $id_obat = $_GET['id_obat'];
        $jumlah = $_GET['jumlah'];

        echo $apotik->saveObatBal($id_obat_balance, $tipe, $id_obat, $jumlah);
        break;
    case 'getRekapPenjualanObat':
        $id_obat = $_GET['id_obat'];
        $id_obatS = $_GET['id_obatS'];
//        $shift = $_GET['shift'];
        
        $status = $_GET['status'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $startHour = $_GET['startHour'];
        $endHour = $_GET['endHour'];
        $tipe_laporan = $_GET['tipeLaporan'];
        $ruang = $_GET['ruang'];

        echo $apotik->getRekapPenjualanObat(
                $id_obat, $id_obatS, $status, $startDate, $endDate, $startHour, $endHour, $tipe_laporan, $ruang
        );
        break;
    case 'getDistribusiObat':
        $id_obat = $_GET['id_obat'];
        $id_obatS = $_GET['id_obatS'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $ruang = $_GET['ruang'];
        $asal_ruang = $_GET['asal_ruang'];
        echo $apotik->getDistribusiObat(
                $id_obat, $id_obatS, $startDate, $endDate, $ruang, $asal_ruang
        );
        break;
    case 'cetakRekapPenjualanObat':
        $id_obat = $_GET['id_obat'];
        $id_obatS = $_GET['id_obatS'];
        $shift = $_GET['shift'];
        $status = $_GET['status'];
        $startDate = $_GET['startDate'];
        $tipe_laporan = $_GET['tipeLaporan'];

        echo $apotik->cetakRekapPenjualanObat(
                $id_obat, $id_obatS, $shift, $status, $startDate, $tipe_laporan
        );
        break;
    case 'cetakRekapResep':
        $jenis_perawatan = $_GET['jenis_perawatan'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $apotik->cetakRekapResep(
                $jenis_perawatan, $tipe_pasien, $startDate, $endDate
        );
        break;
    case 'getLaporanPenjualanHarian':
        $shift = $_GET['shift'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $apotik->getLaporanPenjualanHarian(
                $shift, $startDate, $endDate
        );
        break;

    case 'getStruk':
        $id_faktur_penjualan = $_GET['idf'];
        echo $apotik->cetakStruk($id_faktur_penjualan);
        break;

    case 'getLaporanObatPasien':
        $id_pasien = $_GET['id_pasien'];
        $ruang = $_GET['ruang'];
        $status = $_GET['status'];

        echo $apotik->getLaporanObatPasien($id_pasien, $ruang, $status);
        break;

    case 'getStsk':

        echo $apotik->getStsk();
        break;

    case 'cetakStsk':
        echo $apotik->cetakStsk();
        break;

    case 'kreditkan':
        echo $apotik->kreditkan();
        break;

    case 'cetakLaporanPenjualan':
        $shift = $_GET['shift'];
        $status = $_GET['status'];
        $startDate = $_GET['startDate'];

        echo $apotik->cetakLaporanPenjualan(
                $shift, $status, $startDate
        );
        break;
    case 'cetakLaporanPenjualanHarian':
        $shift = $_GET['shift'];
        $startDate = $_GET['startDate'];

        echo $apotik->cetakLaporanPenjualanHarian(
                $shift, $startDate
        );
        break;
    case 'cetakLaporanObatPasien':
        $id_pasien = $_GET['id_pasien'];

        echo $apotik->cetakLaporanObatPasien($id_pasien);
        break;
    case 'getDetailPasien':
        $id_pasien = $_GET['id_pasien'];

        echo $apotik->getDetailPasien($id_pasien);
        break;
    case 'getFakturAll':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        echo $apotik->getFakturAll($id_faktur_penjualan);
        break;
    case 'getDetailFakturPenjualan':
        $id_pasien = $_GET['id_pasien'];
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $nama_pasien = $_GET['nama_pasien'];
        echo $apotik->getDetailFakturPenjualan($id_pasien, $id_faktur_penjualan, $nama_pasien);
        break;
    case 'getDetailFakturPenjualanHapus':
        $id_pasien = $_GET['id_pasien'];
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $nama_pasien = $_GET['nama_pasien'];
        echo $apotik->getDetailFakturPenjualanHapus($id_pasien, $id_faktur_penjualan, $nama_pasien);
        break;
    case 'getDetailReturFakturPenjualan':
        $id_pasien = $_GET['id_pasien'];
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $nama_pasien = $_GET['nama_pasien'];

        echo $apotik->getDetailReturFakturPenjualan($id_pasien, $id_faktur_penjualan, $nama_pasien);
        break;
    case 'getDetailPenjualanObat':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        echo $apotik->getDetailPenjualanObat($id_faktur_penjualan, $rows, $offset);
        break;
    case 'getDataFaktur':
        $nm_obt = $_GET['obat'];
        $kd_obt = $_GET['kode_obat'];
        $operator = $_GET['operator'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $apotik->getDataFaktur($nm_obt, $kd_obt, $operator, $rows, $offset);
        break;
    case 'getObatBal':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $tipe_balance = $_GET['tipe_balance'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $apotik->getListObatBalance($startDate, $endDate, $tipe_balance, $rows, $offset);
        break;
    case 'hapusFaktur':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        echo $apotik->hapusFaktur($id_faktur_penjualan);
        break;
    case 'hapusDetailObat':
        $id_penjualan_obat = $_GET['id_penjualan_obat'];
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        echo $apotik->hapusDetailObat($id_penjualan_obat, $id_faktur_penjualan);
        break;
    case 'hapusDetailRacikan':
        $id_detail_racikan = $_GET['id_detail_racikan'];
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];

        echo $apotik->hapusDetailRacikan($id_detail_racikan, $id_faktur_penjualan);
        break;
    case 'hapusRacikan':
        $id_racikan = $_GET['id_racikan'];
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];

        echo $apotik->hapusRacikan($id_racikan, $id_faktur_penjualan);
        break;
    case 'getDetailObat':
        $id_penjualan_obat = $_GET['id_penjualan_obat'];

        echo $apotik->getDetailObat($id_penjualan_obat);
        break;
    case 'getRacikan':
        $id_racikan = $_GET['id_racikan'];

        echo $apotik->getRacikan($id_racikan);
        break;
    case 'getFakturPenjualan':
        $no_faktur = $_GET['no_faktur'];
        $nama_pasien = $_GET['nama_pasien'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $status = $_GET['status'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $apotik->getFakturPenjualan(
                $no_faktur, $nama_pasien, $startDate, $endDate, $status, $rows, $offset
        );
        break;
    case 'hapusReturObat':
        $id_retur_penjualan_obat = $_GET['id_retur_penjualan_obat'];

        echo $apotik->hapusReturObat($id_retur_penjualan_obat);
        break;
    case 'getDetailReturPenjualanObat':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $apotik->getDetailReturPenjualanObat($id_faktur_penjualan, $rows, $offset);
        break;
    case 'getTotalTagihanObat':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];

        echo $apotik->getTotalTagihanObat($id_faktur_penjualan);
        break;
    case 'cekPembayaran':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];

        echo $apotik->cekPembayaran($id_faktur_penjualan);
        break;
    case 'getTotalTagihanObatRetur':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];

        echo $apotik->getTotalTagihanObatRetur($id_faktur_penjualan);
        break;
    case 'getTotalTagihanObatDisc':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];

        echo $apotik->getTotalTagihanObatDisc($id_faktur_penjualan);
        break;
    case 'getDetailRacikanObat':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $apotik->getDetailPenjualanRacikan($id_faktur_penjualan, $rows, $offset);
        break;
    case 'getDetailRacikan':
        $id_racikan = $_GET['id_racikan'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $apotik->getDetailRacikan($id_racikan, $rows, $offset);
        break;
    default:
        break;
    case 'hapusPembayaranJualObat':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        echo $apotik->hapusPembayaranJualObat($id_faktur_penjualan);
        break;
    case 'cetakKW':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        echo $apotik->cetakKW($id_faktur_penjualan);
        break;
    case 'cetakKWAll':
        $id_faktur_penjualan = $_GET['id_faktur_penjualan'];
        echo $apotik->cetakKWAll($id_faktur_penjualan);
        break;
    case 'getMasterSupplier':
        $nama_supplier = $_GET['nama_supplier'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        echo $apotik->getMasterSupplier($nama_supplier, $rows, $offset);
        break;
    case 'hapusMasterSupplier':
        $id_supplier = $_GET["id_supplier"];
        echo $apotik->hapusMasterSupplier($id_supplier);
        break;
    case 'simpanMasterSupplier':
        $id_supplier = $_GET["id_supplier"];
        $supplier = $_GET["supplier"];
        echo $apotik->simpanMasterSupplier($id_supplier, $supplier);
        break;
    case 'getDetailMasterSupplier':
        $id_supplier = $_GET['id_supplier'];
        echo $apotik->getDetailMasterSupplier($id_supplier);
        break;
}
?>
