<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../controller/cPasien.php';

$pasien = new cPasien();

$task = $_POST['task'];

switch ($task){
    case 'pasienList':
        echo $pasien->listPasien($_POST['query']);
        break;
    case 'simpanPasien':
        $id_pasien=$_POST['pasienId'];
        $tipe=$_POST['tipe'];
        $id_agama=$_POST['agamaField'];
        $id_kota=$_POST['listKota'];
        $id_kecamatan=$_POST['listKecamatan'];
        $id_kelurahan=$_POST['listKelurahan'];
        $id_tipe_asuransi=$_POST['listTipeAsuransi'];
        $id_kelamin=$_POST['kelaminField'];
        $id_marital=$_POST['maritalField'];
        $id_pendidikan=$_POST['listPendidikan'];
        $id_tipe_pasien=$_POST['listTipePasien'];
        $id_gol_darah=$_POST['listGolDarah'];
        $prefix=$_POST['titleDepanField'];
        $nama_pasien=$_POST['namaPasienField'];
        $id_title=$_POST['gelarField'];
        $alamat=$_POST['alamatField'];
        $no_tlp=$_POST['telpField'];
        $no_hp=$_POST['hpField'];
        $tmp_lahir=$_POST['tmpLahirField'];
        $tgl_lahir=$_POST['tglLahirField'];
        $suku=$_POST['sukuField'];
        $kebangsaan=$_POST['kebangsaanField'];
        $id_jenis_identitas=$_POST['jenisIdField'];
        $no_identitas=$_POST['noIdField'];
        if($tipe=='Insert')
            echo    $pasien->createPasien(
                        $id_agama,
                        $id_kota,
                        $id_kecamatan,
                        $id_kelurahan,
                        $id_tipe_asuransi,
                        $id_kelamin,
                        $id_marital,
                        $id_pendidikan,
                        $id_tipe_pasien,
                        $id_gol_darah,
                        $prefix,
                        $nama_pasien,
                        $id_title,
                        $alamat,
                        $no_tlp,
                        $no_hp,
                        $tmp_lahir,
                        $tgl_lahir,
                        $suku,
                        $kebangsaan,
                        $id_jenis_identitas,
                        $no_identitas
                    );
        else if ($tipe=='Edit')
            echo    $pasien->updatePasien(
                        $id_pasien,
                        $id_agama,
                        $id_kota,
                        $id_kecamatan,
                        $id_kelurahan,
                        $id_tipe_asuransi,
                        $id_kelamin,
                        $id_marital,
                        $id_pendidikan,
                        $id_tipe_pasien,
                        $id_gol_darah,
                        $prefix,
                        $nama_pasien,
                        $id_title,
                        $alamat,
                        $no_tlp,
                        $no_hp,
                        $tmp_lahir,
                        $tgl_lahir,
                        $suku,
                        $kebangsaan,
                        $id_jenis_identitas,
                        $no_identitas
                    );
            
        break;
    default:
        break;
}
?>
