<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cObat.php';

$obat = new cObat();

$task = $_GET['task'];

switch ($task){
    case 'cariObatGudang':
        $kode_obat = $_GET['kode_obat'];
        $nm_obat = $_GET['obat'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getListObat(
                $kode_obat,
                $nm_obat,
                $startDate,
                $endDate,
                $rows,
                $offset
            );
        break;
    case 'cariBarang':
        $id_barang = $_GET['id_barang'];
        $jenis_barang = $_GET['jenis_barang'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getListBarang(
                $id_barang,
                $jenis_barang,
                $rows,
                $offset
            );
        break;
    case 'cariObatApotik':
        $kode_obat = $_GET['kode_obat'];
        $nm_obat = $_GET['obat'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getListObatApotik(
                $kode_obat,
                $nm_obat,
                $startDate,
                $endDate,
                $rows,
                $offset
            );
        break;
    case 'cariObatRuang':
        $kode_obat = $_GET['kode_obat'];
        $nm_obat = $_GET['obat'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getListObatRuang(
                $kode_obat,
                $nm_obat,
                $startDate,
                $endDate,
                $rows,
                $offset
            );
        break;
    case 'cariDistribusiObatApotik':
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->cariDistribusiObatApotik(
                $rows,
                $offset
            );
        break;
    case 'cariDistribusiBarang':
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->cariDistribusiBarang(
                $rows,
                $offset
            );
        break;
    case 'getDetailFaktur':
        $id_faktur = $_GET['id_faktur'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getDetailObat($id_faktur, $rows, $offset);
        break;
    case 'getFaktur':
        $supplier = $_GET['ids'];
        $start = $_GET['startDate'];
        $end = $_GET['endDate'];
        $nama = $_GET['nama'];
        $kode = $_GET['kode'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
	
        echo $obat->getFaktur($nama, $kode, $supplier, $start, $end, $rows, $offset);
        break;
    case 'getFakturNotAssign':
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getFakturNotAssign($rows, $offset);
        break;
    case 'getFakturBayar':
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getFakturBayar($rows, $offset);
        break;
    case 'getFakturId':
        $no_faktur = $_GET['no_faktur'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getFakturId($no_faktur,$rows, $offset);
        break;
    case 'getLaporanPosisiStock':
        $id_obat = $_GET['id_obat'];
        $id_obatS = $_GET['id_obatS'];
        $ruang = $_GET['ruang'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $obat->getLaporanPosisiStock(
                $id_obat,
                $id_obatS,
                $ruang,
                $startDate,
                $endDate
             );
        break;
    case 'cetakLaporanPosisiStock':
        $id_obat = $_GET['id_obat'];
        $id_obatS = $_GET['id_obatS'];
        $ruang = $_GET['ruang'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $obat->cetakLaporanPosisiStock(
                $id_obat,
                $id_obatS,
                $ruang,
                $startDate,
                $endDate
             );
        break;
    case 'getLaporanPembelian':
        $id_obat = $_GET['id_obat'];
        $id_obatS = $_GET['id_obatS'];
        $id_supplier = $_GET['id_supplier'];
        $startEntryDate = $_GET['startEntryDate'];
        $endEntryDate = $_GET['endEntryDate'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $tipeLaporan = $_GET['tipeLaporan'];

        echo $obat->getLaporanPembelian(
                $id_obat,
                $id_obatS,
                $id_supplier,
                $startEntryDate,
                $endEntryDate,
                $startDate,
                $endDate,
                $tipeLaporan
             );
        break;
    case 'cetakLaporanPembelian':
        $id_obat = $_GET['id_obat'];
        $id_obatS = $_GET['id_obatS'];
        $id_supplier = $_GET['id_supplier'];
        $startEntryDate = $_GET['startEntryDate'];
        $endEntryDate = $_GET['endEntryDate'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $tipeLaporan = $_GET['tipeLaporan'];

        echo $obat->cetakLaporanPembelian(
                $id_obat,
                $id_obatS,
                $id_supplier,
                $startEntryDate,
                $endEntryDate,
                $startDate,
                $endDate,
                $tipeLaporan
            );
        break;
    case 'bayarFaktur':
        $id_faktur = $_GET['id_faktur'];
        $bayarKe = $_GET['bayarKe'];
        $bayar = $_GET['bayar'];

        echo $obat->bayarFaktur($id_faktur, $bayarKe, $bayar);
        break;
    case 'assignObatGudang':
        $id_faktur = $_GET['id_faktur'];

        echo $obat->assignObatGudang($id_faktur);
        break;
    case 'getFakturBayarKeuangan':
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getFakturBayarKeuangan($rows, $offset);
        break;
    case 'getListPembayaranFaktur':
        $id_faktur = $_GET['id_faktur'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getListPembayaranFaktur($id_faktur, $rows, $offset);
        break;
    case 'getListHargaObat':
        $nama_obat = $_GET['nama_obat'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getListHargaObat($nama_obat, $rows, $offset);
        break;
    case 'getMasterObat':
        $nama_obat = $_GET['nama_obat'];
        $k_obat = $_GET['k_obat'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getMasterObat($nama_obat, $k_obat, $rows, $offset);
        break;
    case 'getDetailMasterObat':
        $id_obat = $_GET['id_obat'];
	
        echo $obat->getDetailMasterObat($id_obat);
        break;
    case 'getListFaktur':
        $supplier = $_GET['supplier'];
        $tgl_beli = $_GET['tgl_beli'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	
        echo $obat->getListFaktur($supplier, $tgl_beli, $rows, $offset);
        break;
    case 'getIdFaktur':
        echo $obat->getIdFaktur($_GET['no_faktur']);
        break;
    case 'getBayarFaktur':
        echo $obat->getBayarfaktur($_GET['id_faktur']);
        break;
    case 'generateHargaObat':
        echo $obat->generateHargaObat($_GET['id_pembelian_obat']);
        break;
    case 'cariDtlFaktur':
        $id_faktur = $_GET['id_faktur'];
        echo $obat->getDetailFaktur($id_faktur);
        break;
    case 'getDetailDistObat':
        $id_distribusi_obat = $_GET['id_distribusi_obat'];
        echo $obat->getDetailDistObat($id_distribusi_obat);
        break;
    case 'getDetailDistBarang':
        $id_distribusi_barang = $_GET['id_distribusi_barang'];
        echo $obat->getDetailDistBarang($id_distribusi_barang);
        break;
    case 'cariDtlObat':
        $id_pembelian_obat = $_GET['id_pembelian_obat'];
        echo $obat->getDetailObatFaktur($id_pembelian_obat);
        break;
    case 'approveFaktur':
        $id_pembayaran_faktur = $_GET['id_pembayaran_faktur'];
        echo $obat->approveFaktur($id_pembayaran_faktur);
        break;
    case 'updateHarga':
        $id_obat = $_GET['id_obat'];
        $hpp = $_GET['hpp'];
        $umum = $_GET['umum'];
        $askes = $_GET['askes'];
        $jps = $_GET['jps'];

        echo $obat->updateHargaObat($id_obat, $hpp, $umum, $askes, $jps);
        break;
    case 'simpanFaktur':
        $no_faktur=$_GET["no_faktur"];
        $tgl_pembelian=$_GET["tgl_pembelian"];
        $tgl_jatuh_tempo=$_GET["tgl_jatuh_tempo"];
        $supplier=$_GET["supplier"];
        
        echo $obat->simpanFaktur(
                $no_faktur, 
                $tgl_pembelian, 
                $tgl_jatuh_tempo, 
                $supplier
              );

        break;
    case 'simpanMasterObat':
        $id_obat=$_GET["id_obat"];
        $kode_obat=$_GET["kode_obat"];
        $nama_obat=$_GET["nama_obat"];
        
        echo $obat->simpanMasterObat(
                $id_obat, 
                $kode_obat, 
                $nama_obat
              );

        break;
    case 'hapusMasterObat':
        $id_obat=$_GET["id_obat"];
        
        echo $obat->hapusMasterObat($id_obat);

        break;
    case 'simpanDistObat':
        $id_obat=$_GET['id_obat'];
        $id_penyimpanan=$_GET['id_penyimpanan'];
        $ruangTujuan=$_GET['ruangTujuan'];
        $jmlObat=$_GET['jmlObat'];
        $tgl_kadaluarsa_baru=$_GET['tgl_kadaluarsa_baru'];
        
        echo $obat->simpanDistObat(
                $id_obat, 
                $id_penyimpanan, 
                $ruangTujuan,
                $jmlObat,
                $tgl_kadaluarsa_baru
              );

        break;
    case 'simpanDistBarang':
        $id_barang=$_GET['id_barang'];
        $ruangTujuan=$_GET['ruangTujuan'];
        $jmlBarang=$_GET['jmlBarang'];
        
        echo $obat->simpanDistBarang(
                $id_barang, 
                $ruangTujuan,
                $jmlBarang
              );

        break;
    case 'simpanStockBarang':
        $id_barang=$_GET['id_barang'];
        $jmlBarang=$_GET['jmlBarang'];
        
        echo $obat->simpanStockBarang(
                $id_barang, 
                $jmlBarang
              );

        break;
    case 'cancelKirim':
        $id_dist=$_GET['id_dist'];
        echo $obat->cancelKirim($id_dist);
        break;
    case 'hapusBalance':
        $id_dist=$_GET['id_dist'];
        echo $obat->hapusBalance($id_dist);
        break;
    case 'simpanDistObatApotik':
        $id_obat=$_GET['id_obat'];
        $id_distribusi_obat=$_GET['id_distribusi_obat'];
        $id_penyimpanan=$_GET['id_penyimpanan'];
        $jmlObat=$_GET['jumlah'];
        $tgl_kadaluarsa_baru=$_GET['tgl_kadaluarsa_baru'];
        $semua=$_GET['allKirim'];
        
        echo $obat->simpanDistObatApotik(
                $id_distribusi_obat, 
                $id_obat, 
                $id_penyimpanan, 
                $jmlObat,
                $tgl_kadaluarsa_baru,
                $semua
              );

        break;
    case 'simpanDistObatRuang':
        $id_obat=$_GET['id_obat'];
        $id_distribusi_obat=$_GET['id_distribusi_obat'];
        $id_penyimpanan=$_GET['id_penyimpanan'];
        $jmlObat=$_GET['jumlah'];
        $tgl_kadaluarsa_baru=$_GET['tgl_kadaluarsa_baru'];
        
        echo $obat->simpanDistObatRuang(
                $id_distribusi_obat, 
                $id_obat, 
                $id_penyimpanan, 
                $jmlObat,
                $tgl_kadaluarsa_baru
              );

        break;
    case 'simpanDistBarangRuang':
        $id_barang=$_GET['id_barang'];
        $id_distribusi_barang=$_GET['id_distribusi_barang'];
        $jmlBarang=$_GET['jumlah_stock'];
        
        echo $obat->simpanDistBarangRuang(
                $id_distribusi_barang, 
                $id_barang,
                $jmlBarang
              );

        break;
    case 'hapusFakturObat':
        $id_faktur=$_GET['id_faktur'];
        
        echo $obat->hapusFakturObat($id_faktur);

        break;
    case 'hapusDetailObat':
        $id_pembelian_obat=$_GET['id_pembelian_obat'];
        
        echo $obat->hapusDetailObat($id_pembelian_obat);

        break;
    case 'getHargaObat':
        $id=$_GET['id'];
        
        echo $obat->getHargaObat($id);

        break;
    case 'getBalVal':
        $id_obat = $_GET['id_obat'];
        $ruang = $_GET['ruang'];
        echo $obat->getJumlahBalance($id_obat, $ruang);
        break;
    case 'setValBal':
        $kd_obat = $_GET['id_obat'];
        $ruang = $_GET['ruang'];
        $sistem = $_GET['sistem'];
        $real = $_GET['real'];
        echo $obat->setJumlahBalance($kd_obat, $ruang, $sistem, $real);
        break;
    case 'simpanBeliObat':
        $id_pembelian_obat=$_GET["id_pembelian_obat"];
        $id_faktur=$_GET["id_faktur"];
        $id_obat=$_GET["id_obat"];
        $penyimpanan=$_GET["penyimpanan"];
        $qty=$_GET["qty"];
        $harga=$_GET["harga"];
        $retur=$_GET["retur"];
        $diskon=$_GET["diskon"];
        $pajak=$_GET["pajak"];
        $tgl_kadaluarsa=$_GET["tgl_kadaluarsa"];
        
        echo $obat->simpanBeliObat(
                $id_pembelian_obat, 
                $id_faktur, 
                $id_obat, 
                $penyimpanan, 
                $qty, 
                $harga, 
                $retur, 
                $diskon, 
                $pajak, 
                $tgl_kadaluarsa
              );

        break;
    default:
        break;
}
?>
