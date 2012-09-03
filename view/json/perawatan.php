<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../controller/cPerawatan.php';

$perawatan = new cPerawatan();

$task = $_GET['task'];

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

switch ($task){
    case 'simpanDetailDiet':
        $id_pendaftaran=$_GET['id_pendaftaran'];
        $id_detail_diet = $_GET['id_detail_diet']; 
        $id_pasien = $_GET['id_pasien']; 
        $id_diet = $_GET['id_diet']; 
        $id_jenis_diet = $_GET['id_jenis_diet']; 
        $waktu_diet = $_GET['waktu_diet'];
        $tgl_diet = $_GET['tgl_diet']; 
        $keterangan = $_GET['keterangan'];
        $ruangan = $_GET['ruangan'];
	
        echo $perawatan->simpanDetailDiet(
                $id_pendaftaran, 
                $id_detail_diet, 
                $id_pasien, 
                $id_diet, 
                $id_jenis_diet, 
                $waktu_diet, 
                $tgl_diet, 
                $keterangan,
                $ruangan
             );
        break;
    case 'simpanVisitDokter':
        $id_pendaftaran=$_GET['id_pendaftaran'];
        $id_visit = $_GET['id_visit']; 
        $id_pasien = $_GET['id_pasien']; 
        $id_dokter = $_GET['id_dokter']; 
        $tgl_visit = $_GET['tgl_visit'];
        $tarif = $_GET['tarif']; 
	
        echo $perawatan->simpanVisitDokter(
                $id_pendaftaran, 
                $id_visit, 
                $id_pasien, 
                $id_dokter, 
                $tgl_visit, 
                $tarif
             );
        break;
    case 'simpanPindahRuang':
        $id_pendaftaran=$_GET['id_pendaftaran'];
        $doubleBed = $_GET['doubleBed']; 
        $id_penggunaan_kamar = $_GET['id_penggunaan_kamar']; 
        $id_pasien = $_GET['id_pasien']; 
        $tgl_masuk = $_GET['tgl_masuk']; 
        $tgl_pindah = $_GET['tgl_pindah']; 
        $ruang_tujuan = $_GET['ruang_tujuan']; 
        $kelas_tujuan = $_GET['kelas_tujuan'];
        $kamar_tujuan = $_GET['kamar_tujuan']; 
        $bed_tujuan = $_GET['bed_tujuan'];
        $jam_masuk = $_GET['jam_masuk'];
        $jam_pindah = $_GET['jam_pindah'];
	
        echo $perawatan->simpanPindahRuang(
                $id_pendaftaran, 
                $doubleBed, 
                $id_penggunaan_kamar, 
                $id_pasien, 
                $tgl_masuk, 
                $tgl_pindah, 
                $ruang_tujuan, 
                $kelas_tujuan, 
                $kamar_tujuan,
                $bed_tujuan,
                $jam_masuk,
                $jam_pindah
             );
        break;
    case 'simpanPindahKamar':
        $id_penggunaan_kamar = $_GET['id_penggunaan_kamar']; 
        $bed_tujuan = $_GET['bed_pindah']; 
	
        echo $perawatan->simpanPindahKamar(
                $id_penggunaan_kamar, 
                $bed_tujuan
             );
        break;
    case 'simpanSurveyIGD':
        $id_survey=$_GET['id_survey'];
        $id_pendaftaran=$_GET['id_pendaftaran'];
        $id_pasien=$_GET["id_pasien"];
        $pekerjaan=$_GET["pekerjaan"];
        $triage=$_GET["triage"];
        $transportasi=$_GET["transportasi"];
        $jTrans=$_GET["jTrans"];
        $pengantar=$_GET["pengantar"];
        $asuransi=$_GET["asuransi"];
        $inform=$_GET["inform"];
        $ic=$_GET["ic"];
        $kasus=$_GET["kasus"];
        $jKasus=$_GET["jKasus"];
        $emergency=$_GET["emergency"];
        $status=$_GET["status"];
        $lanjut=$_GET["lanjut"];
        $alergi=$_GET["alergi"];
        $medikasi=$_GET["medikasi"];
        $teratur=$_GET["teratur"];
        $rpd=$_GET["rpd"];
        $amenor=$_GET["amenor"];
        $jam_datang=$_GET["jam_datang"]; 
        $jam_periksa=$_GET["jam_periksa"]; 
        $jam_terapi=$_GET["jam_terapi"]; 
        $jam_lanjut=$_GET["jam_lanjut"];
        $bagian=$_GET["bagian"];
        $peristiwa=$_GET["peristiwa"];
        $jam_d=$_GET["jam_d"];
        $jam_p=$_GET["jam_p"];
        $jam_t=$_GET["jam_t"];
        $jam_l=$_GET["jam_l"];
        $saving=$_GET["saving"];
	
        echo $perawatan->simpanSurveyIGD(
                $id_survey,
                $id_pendaftaran, 
                $id_pasien, 
                $pekerjaan,
                $triage,
                $transportasi,
                $jTrans,
                $pengantar,
                $asuransi,
                $inform,
                $ic,
                $kasus,
                $jKasus,
                $emergency,
                $status,
                $lanjut,
                $alergi,
                $medikasi,
                $teratur,
                $rpd,
                $amenor,
                $jam_datang, 
                $jam_periksa, 
                $jam_terapi, 
                $jam_lanjut,
                $bagian,
                $peristiwa,
                $jam_d,
                $jam_p,
                $jam_t,
                $jam_l,
                $saving
             );
        break;
    case 'simpanSummary':
        $id_pendaftaran=$_GET["id_pendaftaran"];
        $id_pasien=$_GET["id_pasien"];
        $id_summary=$_GET["id_summary"];
        $id_diag=$_GET["id_diagnosa"];
        $id_detD=$_GET["id_detailD"];
        $dokter=$_GET["dokter"];
        $keluhan=$_GET["keluhan"];
        $lama=$_GET["lama"];
        $penyakitLama=$_GET["penyakitLama"];
        $obtAkhir=$_GET["obtAkhir"];
        $etiologi=$_GET["etiologi"];
        $tinggi_badan=$_GET["tinggi_badan"];
        $berat_badan=$_GET["berat_badan"];
        $nadi=$_GET["nadi"];
        $tekanan_darah=$_GET["tekanan_darah"];
        $temperatur=$_GET["temperatur"];
        $nafas=$_GET["nafas"];
        $hasilLab=$_GET["hasilLab"];
        $hasilRad=$_GET["hasilRad"];
        $diagAkhir=$_GET["diagAkhir"];
        $diagPa=$_GET["diagPa"];
        $masalah=$_GET["masalah"];
        $konsul=$_GET["konsul"];
        $tindakan=$_GET["tindakan"];
        $fasilitas=$_GET["fasilitas"];
        $perjalanan=$_GET["perjalanan"];
        $keadaan=$_GET["keadaan"];
        $progno=$_GET["progno"];
        $sebabMati=$_GET["sebabMati"];
        $usul=$_GET["usul"];
        $penyakitPrimer=$_GET["penyakitPrimer"];
        $penyakitPrimerId=$_GET["penyakitPrimerId"];
        
	
        echo $perawatan->simpanSummary(
                $id_pendaftaran,
                $id_pasien,
                $id_summary,
                $id_diag,
                $id_detD,
                $dokter,
                $keluhan,
                $lama,
                $penyakitLama,
                $obtAkhir,
                $etiologi,
                $tinggi_badan,
                $berat_badan,
                $nadi,
                $tekanan_darah,
                $temperatur,
                $nafas,
                $hasilLab,
                $hasilRad,
                $diagAkhir,
                $diagPa,
                $masalah,
                $konsul,
                $tindakan,
                $fasilitas,
                $perjalanan,
                $keadaan,
                $progno,
                $sebabMati,
                $usul,
                $penyakitPrimer,
                $penyakitPrimerId
             );
        break;
    case 'getDetailDiet':
        $id_pendaftaran=$_GET['no_pendaftaran'];
	
        echo $perawatan->getDetailDiet($id_pendaftaran, $rows, $offset);
        break;
    case 'getSurveyL':
        $id_pasien=$_GET['id_pasien'];
	
        echo $perawatan->getSurveyL($id_pasien, $rows, $offset);
        break;
    case 'getSummary':
        $id_pendaftaran=$_GET['no_pendaftaran'];
	
        echo $perawatan->getSummary($id_pendaftaran);
        break;
    case 'getSurvey':
        $id_pendaftaran=$_GET['no_pendaftaran'];
	
        echo $perawatan->getSurvey($id_pendaftaran);
        break;
    case 'cetakSummary':
        $id_pendaftaran=$_GET['id_pendaftaran'];
	
        echo $perawatan->cetakSummary($id_pendaftaran);
        break;
    case 'getVisitDokter':
        $id_pendaftaran=$_GET['id_pendaftaran'];
	
        echo $perawatan->getDetailVisit($id_pendaftaran, $rows, $offset);
        break;
    case 'getDiet':
        $id_detail_diet=$_GET['id_detail_diet'];
	
        echo $perawatan->getDiet($id_detail_diet);
        break;
    case 'getVisit':
        $id_visit=$_GET['id_visit'];
	
        echo $perawatan->getVisit($id_visit);
        break;
    case 'hapusDiet':
        $id_detail_diet=$_GET['id_detail_diet'];
	
        echo $perawatan->hapusDiet($id_detail_diet);
        break;
    case 'simpanPindahKelas':
        $id_pendaftaran=$_GET['id_pendaftaran'];
        $id_kelas=$_GET['id_kelas'];
        $id_ruang=$_GET['id_ruang'];
	
        echo $perawatan->simpanPindahKelas($id_pendaftaran, $id_kelas, $id_ruang);
        break;
    case 'simpanEditKelas':
        $tipe_edit=$_GET['tipe_edit'];
        $id_pendaftaran=$_GET['id_pendaftaran'];
        $id_pasien=$_GET['id_pasien'];
        $id_kelas=$_GET['id_kelas'];
        $tgl_ganti=$_GET['tgl_ganti'];
        $tipe_px=$_GET['tipe_px'];
        $tgl_masuke=$_GET['tgl_masuke'];
	
        echo $perawatan->simpanEditKelas(
                $tipe_edit,
                $id_pendaftaran,
                $id_pasien,
                $id_kelas,
                $tgl_ganti,
                $tipe_px,
                $tgl_masuke
                
             );
        break;
    case 'simpanEditHarga':
        $tipe_edit=$_GET['tipe_edit'];
        $id_pendaftaran=$_GET['id_pendaftaran'];
        $id_pasien=$_GET['id_pasien'];
        $id_kelas=$_GET['id_kelas'];
        $dari=$_GET['dari'];
        $hingga=$_GET['hingga'];
	
        echo $perawatan->simpanEditHarga(
                $tipe_edit,
                $id_pendaftaran,
                $id_pasien,
                $id_kelas,
                $dari,
                $hingga
             );
        break;
    case 'hapusVisit':
        $id_visit=$_GET['id_visit'];
	
        echo $perawatan->hapusVisit($id_visit);
        break;
    default:
        break;
}
?>
