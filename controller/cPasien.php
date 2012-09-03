<?php

require_once '../../common/function.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cPasien
 *
 * @author SAP
 */
class cPasien extends fungsi {

    //put your code here
    public function cariPasienID($id) {
        $query = "SELECT * from rm_pasien where del_flag<>'1' AND id_pasien = '" . $id . "'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {

            $arr[] = array("name" => "No Rekam Medis", "value" => sprintf("%06d", mysql_result($result, 0, 'id_pasien')));
            $arr[] = array("name" => "Nama Pasien", "value" => mysql_result($result, 0, 'nama_pasien'));
            $arr[] = array("name" => "Jenis Kelamin", "value" => $this->getKelamin(mysql_result($result, 0, 'id_kelamin')));
            $arr[] = array("name" => "Tempat Lahir", "value" => mysql_result($result, 0, 'tmp_lahir'));
            $arr[] = array("name" => "Tanggal Lahir", "value" => $this->codeDate(mysql_result($result, 0, 'tgl_lahir')));
            $arr[] = array("name" => "Alamat", "value" => mysql_result($result, 0, 'alamat'));
            $arr[] = array("name" => "Kelurahan", "value" => $this->getKelurahan(mysql_result($result, 0, 'id_kelurahan')));
            $arr[] = array("name" => "Kecamatan", "value" => $this->getKecamatan(mysql_result($result, 0, 'id_kecamatan')));
            $arr[] = array("name" => "Kota", "value" => $this->getKota(mysql_result($result, 0, 'id_kota')));
            $arr[] = array("name" => "Tipe Asuransi", "value" => $this->getTipeAsuransi(mysql_result($result, 0, 'id_tipe_asuransi')));
            $arr[] = array("name" => "Tipe Pasien", "value" => $this->getTipePasien(mysql_result($result, 0, 'id_tipe_pasien')));
            $arr[] = array("name" => "Panggilan", "value" => mysql_result($result, 0, 'prefix'));
            $arr[] = array("name" => "Gelar", "value" => $this->getTitle(mysql_result($result, 0, 'id_title')));
            $arr[] = array("name" => "Status Pernikahan", "value" => $this->getMarital(mysql_result($result, 0, 'id_marital')));
            $arr[] = array("name" => "Agama", "value" => $this->getAgama(mysql_result($result, 0, 'id_agama')));
            $arr[] = array("name" => "Pendidikan", "value" => $this->getPendidikan(mysql_result($result, 0, 'id_pendidikan')));
            $arr[] = array("name" => "Jenis Identitas", "value" => $this->getJenisIdentitas(mysql_result($result, 0, 'id_pasien')));
            $arr[] = array("name" => "Nomor Identitas", "value" => $this->getNoIdentitasPasien(mysql_result($result, 0, 'id_pasien')));
            $arr[] = array("name" => "No Telp", "value" => mysql_result($result, 0, 'no_tlp'));
            $arr[] = array("name" => "No HP", "value" => mysql_result($result, 0, 'no_hp'));
            $arr[] = array("name" => "Suku", "value" => mysql_result($result, 0, 'suku'));
            $arr[] = array("name" => "Kebangsaan", "value" => mysql_result($result, 0, 'kebangsaan'));
            $arr[] = array("name" => "Golongan Darah", "value" => $this->getGolDarah(mysql_result($result, 0, 'id_gol_darah')));


            return '{"total":22,"rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":22,"rows":[]}';
        }
    }

    public function cariPasienDetail($id) {
        $query = "select * FROM rm_pasien WHERE del_flag<>'1' 
                  AND id_pasien = '" . $id . "'";

        $result = $this->runQuery($query);
        if (mysql_num_rows($result) > 0) {
            $return = array(
                "pasienId" => @mysql_result($result, 0, "id_pasien"),
                "agamaField" => @mysql_result($result, 0, "id_agama"),
                "listKota" => @mysql_result($result, 0, "id_kota"),
                "listKecamatan" => @mysql_result($result, 0, "id_kecamatan"),
                "listKelurahan" => @mysql_result($result, 0, "id_kelurahan"),
                "listTipeAsuransi" => @mysql_result($result, 0, "id_tipe_asuransi"),
                "kelaminField" => @mysql_result($result, 0, "id_kelamin"),
                "maritalField" => @mysql_result($result, 0, "id_marital"),
                "listPendidikan" => @mysql_result($result, 0, "id_pendidikan"),
                "listTipePasien" => @mysql_result($result, 0, "id_tipe_pasien"),
                "listGolDarah" => @mysql_result($result, 0, "id_gol_darah"),
                "titleDepanField" => @mysql_result($result, 0, "prefix"),
                "namaPasienField" => @mysql_result($result, 0, "nama_pasien"),
                "gelarField" => @mysql_result($result, 0, "id_title"),
                "alamatField" => @mysql_result($result, 0, "alamat"),
                "telpField" => @mysql_result($result, 0, "no_tlp"),
                "hpField" => @mysql_result($result, 0, "no_hp"),
                "tmpLahirField" => @mysql_result($result, 0, "tmp_lahir"),
                "tglLahirField" => $this->formatDateDb(@mysql_result($result, 0, "tgl_lahir")),
                "sukuField" => @mysql_result($result, 0, "suku"),
                "kebangsaanField" => @mysql_result($result, 0, "kebangsaan"),
                "jenisIdField" => @mysql_result($result, 0, "id_jenis_identitas"),
                "noIdField" => $this->getNoIdentitasPasien(@mysql_result($result, 0, "id_pasien"))
            );

            return $this->jEncode($return);
            ;
        }
    }

    public function createPasien(
    $id_agama, $id_kota, $id_kecamatan, $id_kelurahan, $id_tipe_asuransi, $id_kelamin, $id_marital, $id_pendidikan, $id_tipe_pasien, $id_gol_darah, $prefix, $nama_pasien, $id_title, $alamat, $no_tlp, $no_hp, $tmp_lahir, $tgl_lahir, $suku, $kebangsaan, $id_jenis_identitas, $no_identitas
    ) {
        $query = "insert into rm_pasien (
                id_agama,
                id_kota,
                id_kecamatan,
                id_kelurahan,
                id_tipe_asuransi,
                id_kelamin,
                id_marital,
                id_pendidikan,
                id_tipe_pasien,
                id_gol_darah,
                prefix,
                nama_pasien,
                id_title,
                alamat,
                no_tlp,
                no_hp,
                tmp_lahir,
                tgl_lahir,
                suku,
                kebangsaan
            ) values (
                '" . $id_agama . "', 
                '" . $id_kota . "', 
                '" . $id_kecamatan . "', 
                '" . $id_kelurahan . "', 
                '" . $id_tipe_asuransi . "', 
                '" . $id_kelamin . "', 
                '" . $id_marital . "', 
                '" . $id_pendidikan . "', 
                '" . $id_tipe_pasien . "', 
                '" . $id_gol_darah . "',
                '" . $prefix . "',
                '" . mysql_escape_string($nama_pasien) . "',
                '" . $id_title . "',
                '" . @mysql_escape_string($alamat) . "', 
                '" . @mysql_escape_string($no_tlp) . "', 
                '" . @mysql_escape_string($no_hp) . "', 
                '" . @mysql_escape_string($tmp_lahir) . "', 
                '" . $this->formatDateDb($tgl_lahir) . "', 
                '" . @mysql_escape_string($suku) . "', 
                '" . @mysql_escape_string($kebangsaan) . "' 
            )";
        $result = $this->runQuery($query);
        if ($result) {
            $q_pasien_id = "SELECT max(id_pasien) as idakhir from rm_pasien";
            $r_pasien_id = $this->runQuery($q_pasien_id);
            $idAkhir = @mysql_result($r_pasien_id, 0, "idakhir");
            $q_identitas = "insert into rm_det_identitas (id_pasien, id_jenis_identitas, no_identitas) 
                            values ('" . $idAkhir . "','" . $id_jenis_identitas . "','" . $no_identitas . "')";
            $r_identitas = $this->runQuery($q_identitas);
            if ($r_identitas) {
                $return = 'TRUE:' . $idAkhir;
            } else {
                $return = 'WARNING:Gagal Menyimpan Identitas.';
            }
        } else {
            $return = 'ERROR:Gagal Menyimpan Pasien.';
        }

        return $return;
    }

    public function updatePasien(
    $id_pasien, $id_agama, $id_kota, $id_kecamatan, $id_kelurahan, $id_tipe_asuransi, $id_kelamin, $id_marital, $id_pendidikan, $id_tipe_pasien, $id_gol_darah, $prefix, $nama_pasien, $id_title, $alamat, $no_tlp, $no_hp, $tmp_lahir, $tgl_lahir, $suku, $kebangsaan, $id_jenis_identitas, $no_identitas
    ) {
        $query = "update rm_pasien set
                id_agama='" . $this->cekAgama($id_agama) . "',
                id_kota='" . $this->cekKota($id_kota) . "',
                id_kecamatan='" . $this->cekKecamatan($id_kecamatan) . "',
                id_kelurahan='" . $this->cekKelurahan($id_kelurahan) . "',
                id_tipe_asuransi='" . $this->cekTipeAsuransi($id_tipe_asuransi) . "',
                id_kelamin='" . $this->cekKelamin($id_kelamin) . "',
                id_marital='" . $this->cekMarital($id_marital) . "',
                id_pendidikan='" . $this->cekPendidikan($id_pendidikan) . "',
                id_tipe_pasien='" . $this->cekTipePasien($id_tipe_pasien) . "',
                id_gol_darah='" . $this->cekGolDarah($id_gol_darah) . "',
                prefix='" . $prefix . "',
                nama_pasien='" . @mysql_escape_string($nama_pasien) . "',
                id_title='" . $this->cekTitle($id_title) . "',
                alamat='" . @mysql_escape_string($alamat) . "',
                no_tlp='" . @mysql_escape_string($no_tlp) . "',
                no_hp='" . @mysql_escape_string($no_hp) . "',
                tmp_lahir='" . @mysql_escape_string($tmp_lahir) . "',
                tgl_lahir='" . $this->formatDateDb($tgl_lahir) . "',
                suku='" . @mysql_escape_string($suku) . "',
                kebangsaan='" . @mysql_escape_string($kebangsaan) . "'
                where id_pasien='" . $id_pasien . "'";
        $result = $this->runQuery($query);
        if ($result) {
            $q_identitas = "update rm_det_identitas set id_jenis_identitas='" . $this->cekJenisIdentitas($id_jenis_identitas) . "', 
                            no_identitas='" . $no_identitas . "' where id_pasien='" . $id_pasien . "'";
            $r_identitas = $this->runQuery($q_identitas);
            if ($r_identitas) {
                $return = 'TRUE:' . $id_pasien;
            } else {
                $return = 'WARNING:Gagal Menyimpan Identitas.';
            }
        } else {
            $return = 'ERROR:Gagal Menyimpan Pasien.';
        }

        return $return;
    }

    public function listPasien($nmPasien) {
        $query = "SELECT id_pasien, nama_pasien FROM rm_pasien where nama_pasien like '%" . @mysql_escape_string($_POST['$nmPasien']) . "%'";
        $result = mysql_query($query);

        $result = mysql_query($query);
        $nbrows = mysql_num_rows($result);
        $start = (integer) (isset($_POST['start']) ? $_POST['start'] : $_GET['start']);
        $end = (integer) (isset($_POST['limit']) ? $_POST['limit'] : $_GET['limit']);
        $limit = $query . " LIMIT " . $start . "," . $end;
        $result = mysql_query($limit);

        while ($rec = mysql_fetch_array($result)) {
            $arr[] = array("id_pasien" => $rec['id_pasien'], "nama_pasien" => $rec['nama_pasien']);
        }

        $jsonresult = $this->JEncode($arr);

        return '({"total":"' . $nbrows . '","results":' . $jsonresult . '})';
    }

}

?>
