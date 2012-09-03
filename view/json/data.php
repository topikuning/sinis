<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../controller/cData.php';

$data = new cData();

$task = $_GET['task'];

switch ($task) {
    case 'dTindakan':
        echo $data->dTindakan();
        break;
    case 'dObat':
        echo $data->dObat();
        break;
    case 'dObat2':
        echo $data->dObat2();
        break;
    case 'dRadiologi':
        echo $data->dRadiologi();
        break;
    case 'periksa':
        echo $data->listDiagnosa();
        break;
    case 'dFasilitas':
        echo $data->dFasilitas();
        break;
    case 'listGolDarah':
        echo $data->cariGolDarah();
        break;
    case 'listTipeAsuransi':
        echo $data->cariTipeAsuransi();
        break;
    case 'listTipePasien':
        echo $data->cariTipePasien($_GET['id_tipe_asuransi']);
        break;
    case 'listPendidikan':
        echo $data->cariPendidikan();
        break;
    case 'listKelurahan':
        echo $data->cariKelurahan($_GET['id_kecamatan']);
        break;
    case 'listKecamatan':
        echo $data->cariKecamatan($_GET['id_kota']);
        break;
    case 'listKota':
        echo $data->cariKota();
        break;
    case 'listGelar':
        echo $data->cariGelar();
        break;
    case 'listAgama':
        echo $data->cariAgama();
    case 'listIdentitas':
        echo $data->cariIdentitas();
        break;
    case 'listTipeRuang':
        echo $data->getListTipeRuang();
        break;
    case 'listRuang':
        echo $data->getListRuang($_GET['id_tipe_pendaftaran'], $_GET['id_pasien']);
        break;
    case 'listRuangDaftar':
        echo $data->getListRuangDaftar($_GET['id_tipe_pendaftaran'], $_GET['id_pasien']);
        break;
    case 'listRuangDistribusi':
        echo $data->getListRuangDistribusi($_GET['kode_obat']);
        break;
    case 'listRuangDistribusiBarang':
        echo $data->getListRuangDistribusiBarang();
        break;
    case 'listKelas':
        echo $data->getListKelasRuang($_GET['id_ruang'], $_GET['id_pasien']);
        break;
    case 'listKelasJPS':
        echo $data->getListKelasJPS();
        break;
    case 'listKelasUmum':
        echo $data->getListKelasUmum();
        break;
    case 'listKelasHarga':
        echo $data->getListKelasHarga();
        break;
    case 'listTipePx':
        echo $data->getListTipePasien($_GET['tipe']);
        break;
    case 'listDokter':
        echo $data->getListDokterRuang($_GET['id_ruang']);
        break;
    case 'listPerujuk':
        echo $data->getListPerujuk($_GET['asal_rujukan']);
        break;
    case 'listKamar':
        echo $data->getListKamar($_GET['id_ruang'], $_GET['id_kelas']);
        break;
    case 'listKamarAll':
        echo $data->getListKamarAll($_GET['id_ruang']);
        break;
    case 'listBed':
        echo $data->getListBed($_GET['id_kamar']);
        break;
    case 'listBedAll':
        echo $data->getListBedAll($_GET['id_kamar']);
        break;
    case 'listTipePendaftaran':
        echo $data->getListTipePendaftaran();
        break;
    case 'listLaboratorium':
        echo $data->getListLaboratorium($_GET['id_kelompok_lab']);
        break;
    case 'getBiayaPendaftaran':
        echo $data->getBiayaPendaftaran($_GET['tipe_pendaftaran'], $_GET['id_pasien']);
        break;
    case 'getPendaftaran':
        echo $data->getPendaftaran($_GET['id_pasien']);
        break;
    case 'getLaporanTagihanRawatInap':
        $tipe_pasien = $_GET['tipe_pasien'];
        $status = $_GET['status'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $data->getLaporanTagihanRawatInap(
                $tipe_pasien, $status, $startDate, $endDate
        );
        break;
    case 'getLaporanTagihanRawatJalan':
        $tipe_pasien = $_GET['tipe_pasien'];
        $status = $_GET['status'];
        $rawat = $_GET['rawat'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $data->getLaporanTagihanRawatJalan(
                $tipe_pasien, $status, $rawat, $startDate, $endDate
        );
        break;
    case 'cetakLaporanTagihanRawatJalan':
        $tipe_pasien = $_GET['tipe_pasien'];
        $status = $_GET['status'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $data->cetakLaporanTagihanRawatJalan(
                $tipe_pasien, $status, $startDate, $endDate
        );
        break;
    case 'getRekapPendapatan':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $data->getRekapPendapatan(
                $startDate, $endDate
        );
        break;
    case 'getRekapJasa':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $id_dokter = $_GET['id_dokter'];
        $id_ruang = $_GET['id_ruang'];
        //$tipe_laporan = $_GET['tipe_laporan'];

        echo $data->getRekapJasa(
                $startDate, $endDate, $id_dokter, $id_ruang
        );
        break;

    case 'getRekapKeuangan':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $id_tipe_pasien = $_GET['tipe_pasien'];
        $id_ruang = $_GET['ruang'];
        $id_dokter = $_GET['dokter'];
        $id_kelas = $_GET['kelas'];
        $tipe_perawatan = $_GET['tipePerawatan'];
        $tipe_laporan = $_GET['tipeLaporan'];

        echo $data->getRekapKeuangan(
                $startDate, $endDate, $id_tipe_pasien, $id_ruang, $id_dokter, $id_kelas, $tipe_perawatan, $tipe_laporan
        );
        break;
    case 'getLaporanKasir':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $startHour = $_GET['startHour'];
        $endHour = $_GET['endHour'];
        $kasir = $_GET['kasir'];
        echo $data->getPendapatanBank($startHour, $endHour, $startDate, $endDate, $kasir);
        break;
    case 'cetakRekapKeuangan':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $id_tipe_pasien = $_GET['tipe_pasien'];
        $id_ruang = $_GET['ruang'];
        $id_dokter = $_GET['dokter'];
        $id_kelas = $_GET['kelas'];
        $tipe_perawatan = $_GET['tipePerawatan'];
        $tipe_laporan = $_GET['tipeLaporan'];

        echo $data->cetakRekapKeuangan(
                $startDate, $endDate, $id_tipe_pasien, $id_ruang, $id_dokter, $id_kelas, $tipe_perawatan, $tipe_laporan
        );
        break;
    case 'cetakRekapJasa':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $id_dokter = $_GET['id_dokter'];
        $id_ruang = $_GET['id_ruang'];

        echo $data->cetakRekapJasa(
                $startDate, $endDate, $id_dokter, $id_ruang
        );
        break;
    case 'cetakRekapPendapatan':
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $data->cetakRekapPendapatan(
                $startDate, $endDate
        );
        break;
    case 'cetakLaporanTagihanRawatInap':
        $tipe_pasien = $_GET['tipe_pasien'];
        $status = $_GET['status'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        echo $data->cetakLaporanTagihanRawatInap(
                $tipe_pasien, $status, $startDate, $endDate
        );
        break;
    case 'cetakLaporanTagihan':
        $id_pasien = $_GET['id_pasien'];
        echo $data->cetakLaporanTagihan($id_pasien);
        break;
    case 'cetakLaporanTagihanBanding':
        $id_pasien = $_GET['id_pasien'];
        $id_kelas = $_GET['id_kelas'];

        echo $data->cetakLaporanTagihanBanding($id_pasien, $id_kelas);
        break;
    case 'checkOutPasien':
        $id_pasien = $_GET['id_pasien'];
        $id_keadaan = $_GET['kondisi_keluar'];
        $id_cara_keluar = $_GET['cara_keluar'];
        $keterangan = $_GET['keterangan_keluar'];
        $tgl_keluar = $_GET['tgl_out'];

        echo $data->checkOutPasien($id_pasien, $id_keadaan, $id_cara_keluar, $keterangan, $tgl_keluar);
        break;
    case 'getBiayaVisit':
        echo $data->getBiayaVisit($_GET['id_dokter'], $_GET['id_pendaftaran']);
        break;
    case 'getTarifTindakanMedis':
        echo $data->getTarifTindakanMedis($_GET['id_tindakan_medis'], $_GET['id_pendaftaran'], $_GET['dokter_operator'], $_GET['cito'], $_GET['alat_tamu']);
        break;
    case 'getTarifKamar':
        echo $data->getTarifKamar($_GET['id_kamar']);
        break;
    case 'cariDtlPasienTagih':
        $id_pasien = $_GET['id_pasien'];

        echo $data->getDetailPasienTagih($id_pasien);
        break;
    case 'getTagihanPasien':
        echo $data->getTagihanPasien($_GET['id_pendaftaran'], $_GET['id_pasien']);
        break;
    case 'getTagihanPasienKeluar':
        echo $data->getTagihanPasienKeluar($_GET['id_pendaftaran']);
        break;
    case 'getTagihanPasienBanding':
        echo $data->getTagihanPasienBanding($_GET['id_pendaftaran'], $_GET['id_pasien'], $_GET['id_kelas']);
        break;
    case 'cetakKwitansiTagihan':
        echo $data->cetakKwitansiTagihan($_GET['id_pembayaran']);
        break;
    case 'cetakKwitansiLunas':
        echo $data->cetakKwitansiLunas($_GET['id_pasien'], $_GET['id_pembayaran']);
        break;
    case 'cetakKwitansiBebas':
        echo $data->cetakKwitansiBebas($_GET['id_pasien'], $_GET['pilihan']);
        break;
    case 'getListRuang':
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $data->getDataListRuang($_GET['tipe_ruang'], $rows, $offset);
        break;
    case 'getDataListPasien':
        $nama_pasien = $_GET['nama_pasien'];
        $alamat = $_GET['alamat'];
        $tgl_lahir = $_GET['tgl_lahir'];
        $tgl_lahir_to = $_GET['tgl_lahir_to'];
        $kecamatan = $_GET['kecamatan'];
        $kelurahan = $_GET['kelurahan'];
        $asuransi = $_GET['asuransi'];
        $tipe_pasien = $_GET['tipe_pasien'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $data->getDataListPasien(
                $nama_pasien, $alamat, $tgl_lahir, $tgl_lahir_to, $kecamatan, $kelurahan, $asuransi, $tipe_pasien, $rows, $offset
        );
        break;
    case 'simpanClosePerawatan':
        $id_pendaftaran = $_GET['id_pendaftaran'];
        $id_keadaan = $_GET['kondisi_keluar'];
        $id_cara_keluar = $_GET['cara_keluar'];
        $tgl_keluar = $_GET['tgl_out'];
        $keterangan = $_GET['keterangan_keluar'];

        echo $data->saveClosePerawatan($id_pendaftaran, $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan);
        break;
    case 'simpanClosePerawatanMedis':
        $id_pendaftaran = $_GET['id_pendaftaran'];
        $id_keadaan = $_GET['kondisi_keluar'];
        $id_cara_keluar = $_GET['cara_keluar'];
        $tgl_keluar = $_GET['tgl_out'];
        $keterangan = $_GET['keterangan_keluar'];

        echo $data->saveClosePerawatanMedis($id_pendaftaran, $id_keadaan, $id_cara_keluar, $tgl_keluar, $keterangan);
        break;
    case 'simpanPembayaranTagihan':
        $id_pendaftaran = $_GET['id_pendaftaran'];
        $id_pasien = $_GET['id_pasien'];
        $status = $_GET['status'];
        $asuransi = $_GET['asuransi'];
        $bayar = $_GET['bayar'];

        echo $data->simpanPembayaranTagihan($id_pendaftaran, $id_pasien, $status, $asuransi, $bayar);
        break;
    case 'simpanDiskonDokter':
        $id_pendaftaran = $_GET['id_pendaftaran'];
        $id_pasien = $_GET['id_pasien'];
        $diskon = $_GET['diskon'];
        $level = $_GET['level'];

        echo $data->simpanDiskonDokter($id_pendaftaran, $id_pasien, $diskon, $level);
        break;
    case 'getResumeTagihanPasien':
        $id_pasien = $_GET['id_pasien'];

        echo $data->getResumeTagihanPasien($id_pasien);
        break;
    case 'getBarangBal':
        $tgl_awal = $_GET['tgl_awal'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $tipe_balance = $_GET['tipe_balance'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        echo $data->getBarangBal($tgl_awal, $tgl_akhir, $tipe_balance, $rows, $offset);
        break;
    default :
        break;
}
?>
