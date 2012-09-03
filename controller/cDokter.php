<?php
session_start();
require_once '../../common/function.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cPendaftaran
 *
 * @author SAP
 */
class cDokter extends fungsi{
    //put your code here    
    public function getJasaTindakanDokter($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and d.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.tgl_tindakan) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.tgl_tindakan)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      DATE(b.tgl_tindakan) AS tgl_tindakan,
                      a.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      e.nama_dokter,
                      f.nama_pelaku,
                      a.tarif,
                      (j.pajak*a.jasa_dokter) AS pajak,
                      h.tindakan,
                      a.jasa_dokter,
                      i.ruang,
                      a.jasa_perawat
                  FROM
                      rm_jasa_tindakan_poli a,
                      rm_tindakan_ruang b,
                      rm_pasien c,
                      rm_tipe_pasien d,
                      rm_dokter e,
                      rm_pelaku_tindakan f,
                      rm_detail_tindakan g,
                      rm_tindakan h,
                      rm_ruang i,
                      rm_golongan j
                  WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      c.id_pasien = a.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_dokter = a.id_dokter AND
                      a.id_dokter = '".$_SESSION['id']."' AND
                      f.id_pelaku_tindakan = a.id_pelaku_tindakan AND
                      h.id_tindakan = g.id_tindakan AND
                      a.id_detail_tindakan = g.id_detail_tindakan AND
                      i.id_ruang = g.id_ruang AND
                      j.id_golongan = e.id_golongan AND
                      j.id_golongan = e.id_golongan ".$kondisi."
                  GROUP BY
                      a.id_jasa_pendaftaran";
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>  $this->codeDate($rec['tgl_tindakan']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tindakan'=>$rec['tindakan'],
                             'dokter'=>$rec['nama_dokter'],
                             'operator'=>$rec['nama_pelaku'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_perawat'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaTindakanMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $ruang, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and d.id_tipe_pasien='".$tipe_pasien."'";
        if($id_dokter!="") $kondisi .= " AND a.id_dokter = '".$id_dokter."'";
        if($ruang!="") $kondisi .= " AND i.id_ruang = '".$ruang."'";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.tgl_tindakan) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.tgl_tindakan)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      DATE(b.tgl_tindakan) AS tgl_tindakan,
                      a.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      e.nama_dokter,
                      f.nama_pelaku,
                      a.tarif,
                      h.tindakan,
                      a.jasa_layanan,
                      a.jasa_sarana,
                      a.jasa_unit_penghasil,
                      a.jasa_direksi,
                      a.jasa_remunerasi,
                      a.jasa_dokter,
                      a.jasa_perawat,
                      (j.pajak * a.jasa_dokter) AS pajakDokter,
                      (0.05 * a.jasa_perawat) AS pajakPerawat,
                      i.ruang
                  FROM
                      rm_jasa_tindakan_poli a,
                      rm_tindakan_ruang b,
                      rm_pasien c,
                      rm_tipe_pasien d,
                      rm_dokter e,
                      rm_pelaku_tindakan f,
                      rm_detail_tindakan g,
                      rm_tindakan h,
                      rm_ruang i,
                      rm_golongan j
                  WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      c.id_pasien = a.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_dokter = a.id_dokter AND
                      f.id_pelaku_tindakan = a.id_pelaku_tindakan AND
                      h.id_tindakan = g.id_tindakan AND
                      a.id_detail_tindakan = g.id_detail_tindakan AND
                      i.id_ruang = g.id_ruang AND
                      j.id_golongan = e.id_golongan AND
                      j.id_golongan = e.id_golongan ".$kondisi."
                  GROUP BY
                      a.id_jasa_pendaftaran";
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasaPelayanan = 0;
        $jmlJasaSarana = 0;
        $jmlJasaUnitPenghasil = 0;
        $jmlJasaRemunerasi = 0;
        $jmlJasaDireksi = 0;
        $jmlJasaDokter = 0;
        $jmlJasaPerawat = 0;
        $jmlPajakDokter = 0;
        $jmlPajakPerawat = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>  $this->codeDate($rec['tgl_tindakan']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tindakan'=>$rec['tindakan'],
                             'dokter'=>$rec['nama_dokter'],
                             'ruang'=>$rec['ruang'],
                             'operator'=>$rec['nama_pelaku'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_pelayanan'=>"Rp. ".number_format($rec['jasa_layanan'],2,',','.'),
                             'jasa_sarana'=>"Rp. ".number_format($rec['jasa_sarana'],2,',','.'),
                             'jasa_unit_penghasil'=>"Rp. ".number_format($rec['jasa_unit_penghasil'],2,',','.'),
                             'jasa_remunerasi'=>"Rp. ".number_format($rec['jasa_remunerasi'],2,',','.'),
                             'jasa_direksi'=>"Rp. ".number_format($rec['jasa_direksi'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'jasa_perawat'=>"Rp. ".number_format($rec['jasa_perawat'],2,',','.'),
                             'pajakDokter'=>"Rp. ".number_format($rec['pajakDokter'],2,',','.'),
                             'pajakPerawat'=>"Rp. ".number_format($rec['pajakPerawat'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasaPelayanan += $rec['jasa_layanan'];
                $jmlJasaSarana += $rec['jasa_sarana'];
                $jmlJasaUnitPenghasil += $rec['jasa_unit_penghasil'];
                $jmlJasaRemunerasi += $rec['jasa_remunerasi'];
                $jmlJasaDireksi += $rec['jasa_direksi'];
                $jmlJasaDokter += $rec['jasa_dokter'];
                $jmlJasaPerawat += $rec['jasa_perawat'];
                $jmlPajakDokter += $rec['pajakDokter'];
                $jmlPajakPerawat += $rec['pajakPerawat'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_pelayanan":"Rp. '.number_format($jmlJasaPelayanan,2,',','.').'",
                    "jasa_sarana":"Rp. '.number_format($jmlJasaSarana,2,',','.').'",
                    "jasa_unit_penghasil":"Rp. '.number_format($jmlJasaUnitPenghasil,2,',','.').'",
                    "jasa_remunerasi":"Rp. '.number_format($jmlJasaRemunerasi,2,',','.').'",
                    "jasa_direksi":"Rp. '.number_format($jmlJasaDireksi,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasaDokter,2,',','.').'",
                    "jasa_perawat":"Rp. '.number_format($jmlJasaPerawat,2,',','.').'",
                    "pajakDokter":"Rp. '.number_format($jmlPajakDokter,2,',','.').'",
                    "pajakPerawat":"Rp. '.number_format($jmlPajakPerawat,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaPendaftaranDokter($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and d.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.tgl_pendaftaran) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.tgl_pendaftaran)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      DATE(b.tgl_pendaftaran) AS tgl_daftar,
                      b.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      f.tipe_pendaftaran,
                      e.ruang,
                      g.nama_dokter,
                      b.biaya_pendaftaran,
                      a.jasa_dokter,
                      (h.pajak * a.jasa_dokter) AS pajak
                    FROM
                      rm_jasa_pendaftaran a,
                      rm_pendaftaran b,
                      rm_pasien c,
                      rm_tipe_pasien d,
                      rm_ruang e,
                      rm_tipe_pendaftaran f,
                      rm_dokter g,
                      rm_golongan h
                    WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      c.id_pasien = a.id_pasien AND
                      a.id_pasien = b.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_ruang = a.id_ruang AND
                      f.id_tipe_pendaftaran = b.id_tipe_pendaftaran AND
                      g.id_dokter = b.id_dokter AND
                      b.id_dokter = ".$_SESSION['id']." and
                      f.id_tipe_pendaftaran!='6' and    
                      h.id_golongan = g.id_golongan ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_daftar'=>  $this->codeDate($rec['tgl_daftar']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tipe_pendaftaran'=>$rec['tipe_pendaftaran'],
                             'ruang'=>$rec['ruang'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['biaya_pendaftaran'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['biaya_pendaftaran'];
                $jmlJasa += $rec['jasa_dokter'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaPendaftaranMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and c.id_tipe_pasien='".$tipe_pasien."'";
        if($id_dokter!="") $kondisi .= " AND b.id_dokter = '".$id_dokter."'";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.tgl_pendaftaran) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.tgl_pendaftaran)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      DATE(b.tgl_pendaftaran) AS tgl_daftar,
                      b.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      f.tipe_pendaftaran,
                      e.ruang,
                      g.nama_dokter,
                      b.biaya_pendaftaran,
                      a.jasa_layanan,
                      a.jasa_sarana,
                      a.jasa_unit_penghasil,
                      a.jasa_direksi,
                      a.jasa_remunerasi,
                      a.jasa_dokter,
                      a.jasa_perawat,
                      (h.pajak * a.jasa_dokter) AS pajakDokter,
                      (0.05 * a.jasa_perawat) AS pajakPerawat
                    FROM
                      rm_jasa_pendaftaran a,
                      rm_pendaftaran b,
                      rm_pasien c,
                      rm_tipe_pasien d,
                      rm_ruang e,
                      rm_tipe_pendaftaran f,
                      rm_dokter g,
                      rm_golongan h
                    WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      c.id_pasien = a.id_pasien AND
                      a.id_pasien = b.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_ruang = a.id_ruang AND
                      f.id_tipe_pendaftaran = b.id_tipe_pendaftaran AND
                      g.id_dokter = b.id_dokter and
                      f.id_tipe_pendaftaran!='6' and
                      b.biaya_pendaftaran > 0 and
                      h.id_golongan = g.id_golongan ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasaPelayanan = 0;
        $jmlJasaSarana = 0;
        $jmlJasaUnitPenghasil = 0;
        $jmlJasaRemunerasi = 0;
        $jmlJasaDireksi = 0;
        $jmlJasaDokter = 0;
        $jmlJasaPerawat = 0;
        $jmlPajakDokter = 0;
        $jmlPajakPerawat = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_daftar'=>  $this->codeDate($rec['tgl_daftar']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tipe_pendaftaran'=>$rec['tipe_pendaftaran'],
                             'ruang'=>$rec['ruang'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['biaya_pendaftaran'],2,',','.'),
                             'jasa_pelayanan'=>"Rp. ".number_format($rec['jasa_layanan'],2,',','.'),
                             'jasa_sarana'=>"Rp. ".number_format($rec['jasa_sarana'],2,',','.'),
                             'jasa_unit_penghasil'=>"Rp. ".number_format($rec['jasa_unit_penghasil'],2,',','.'),
                             'jasa_remunerasi'=>"Rp. ".number_format($rec['jasa_remunerasi'],2,',','.'),
                             'jasa_direksi'=>"Rp. ".number_format($rec['jasa_direksi'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'jasa_perawat'=>"Rp. ".number_format($rec['jasa_perawat'],2,',','.'),
                             'pajakDokter'=>"Rp. ".number_format($rec['pajakDokter'],2,',','.'),
                             'pajakPerawat'=>"Rp. ".number_format($rec['pajakPerawat'],2,',','.')
                         );
                $jmlTarif += $rec['biaya_pendaftaran'];
                $jmlJasaPelayanan += $rec['jasa_layanan'];
                $jmlJasaSarana += $rec['jasa_sarana'];
                $jmlJasaUnitPenghasil += $rec['jasa_unit_penghasil'];
                $jmlJasaRemunerasi += $rec['jasa_remunerasi'];
                $jmlJasaDireksi += $rec['jasa_direksi'];
                $jmlJasaDokter += $rec['jasa_dokter'];
                $jmlJasaPerawat += $rec['jasa_perawat'];
                $jmlPajakDokter += $rec['pajakDokter'];
                $jmlPajakPerawat += $rec['pajakPerawat'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_pelayanan":"Rp. '.number_format($jmlJasaPelayanan,2,',','.').'",
                    "jasa_sarana":"Rp. '.number_format($jmlJasaSarana,2,',','.').'",
                    "jasa_unit_penghasil":"Rp. '.number_format($jmlJasaUnitPenghasil,2,',','.').'",
                    "jasa_remunerasi":"Rp. '.number_format($jmlJasaRemunerasi,2,',','.').'",
                    "jasa_direksi":"Rp. '.number_format($jmlJasaDireksi,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasaDokter,2,',','.').'",
                    "jasa_perawat":"Rp. '.number_format($jmlJasaPerawat,2,',','.').'",
                    "pajakDokter":"Rp. '.number_format($jmlPajakDokter,2,',','.').'",
                    "pajakPerawat":"Rp. '.number_format($jmlPajakPerawat,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaPendaftaran($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and b.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and b.tgl_pendaftaran between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)." 23:59:59'";
            else $kondisi .= " and b.tgl_pendaftaran between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_awal)." 23:59:59'";
        }
        
        $query = "SELECT
                      DATE(b.tgl_pendaftaran) AS tgl_daftar,
                      b.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      f.tipe_pendaftaran,
                      e.ruang,
                      g.nama_dokter,
                      b.biaya_pendaftaran,
                      a.jasa_perawat,
                      (0.05 * a.jasa_perawat) AS pajak,
                      a.jasa_perawat - (0.05 * a.jasa_perawat) AS total
                    FROM
                      rm_jasa_pendaftaran a,
                      rm_pendaftaran b,
                      rm_pasien c,
                      rm_tipe_pasien d,
                      rm_ruang e,
                      rm_tipe_pendaftaran f,
                      rm_dokter g
                    WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      c.id_pasien = a.id_pasien AND
                      a.id_pasien = b.id_pasien AND
                      d.id_tipe_pasien = b.id_tipe_pasien AND
                      e.id_ruang = a.id_ruang AND
                      a.id_ruang = ".$_SESSION['level']." AND
                      f.id_tipe_pendaftaran = b.id_tipe_pendaftaran AND
                      g.id_dokter = b.id_dokter AND
                      b.id_asal_pendaftaran = 0 AND
                      f.id_tipe_pendaftaran!='6' ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_daftar'=>  $this->formatDateDb($rec['tgl_daftar']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tipe_pendaftaran'=>$rec['tipe_pendaftaran'],
                             'ruang'=>$rec['ruang'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['biaya_pendaftaran'],2,',','.'),
                             'jasa_perawat'=>"Rp. ".number_format($rec['jasa_perawat'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.'),
                             'total'=>"Rp. ".number_format($rec['total'],2,',','.')
                         );
                $jmlTarif += $rec['biaya_pendaftaran'];
                $jmlJasa += $rec['jasa_perawat'];
                $jmlPajak += $rec['pajak'];
                $total += $rec['total'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_perawat":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'",
                    "total":"Rp. '.number_format($total,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[],"footer":[]}';
        }
    }

    public function getJasaPemeriksaanDokter($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and d.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.tgl_pendaftaran) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.tgl_pendaftaran)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      date(b.tgl_pendaftaran) as tgl_daftar,
                      b.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      f.tipe_pendaftaran,
                      e.ruang,
                      g.nama_dokter,
                      a.tarif,
                      a.jasa_dokter,
                      (h.pajak * a.jasa_dokter) AS pajak
                    FROM
                      rm_jasa_pemeriksaan_dokter a,
                      rm_pendaftaran b,
                      rm_pasien c,
                      rm_tipe_pasien d,
                      rm_ruang e,
                      rm_tipe_pendaftaran f,
                      rm_dokter g,
                      rm_golongan h
                    WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      c.id_pasien = a.id_pasien AND
                      a.id_pasien = b.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_ruang = a.id_ruang AND
                      b.id_dokter=".$_SESSION['id']." and
                      f.id_tipe_pendaftaran = b.id_tipe_pendaftaran AND
                      g.id_dokter = b.id_dokter AND
                      h.id_golongan = g.id_golongan ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_daftar'=>  $this->codeDate($rec['tgl_daftar']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tipe_pendaftaran'=>$rec['tipe_pendaftaran'],
                             'ruang'=>$rec['ruang'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_dokter'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaVisit($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and b.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(a.tgl_visit) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(a.tgl_visit)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      a.tgl_visit,
                      b.id_pasien,
                      b.nama_pasien,
                      c.tipe_pasien,
                      f.ruang,
                      d.nama_dokter,
                      a.tarif,
                      (e.pajak * a.tarif) AS pajak
                    FROM
                      rm_visit a,
                      rm_pasien b,
                      rm_tipe_pasien c,
                      rm_dokter d,
                      rm_golongan e,
                      rm_ruang f
                    WHERE
                      b.id_pasien = a.id_pasien AND
                      a.del_flag<>'1' and
                      c.id_tipe_pasien = b.id_tipe_pasien AND
                      d.id_dokter = a.id_dokter AND
                      a.id_dokter = ".$_SESSION['id']." and
                      e.id_golongan = d.id_golongan AND
                      f.id_ruang=a.id_ruang AND a.id_ruang!='20' ".$kondisi."
                    GROUP BY
                      a.id_visit";
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_visit'=>  $this->codeDate($rec['tgl_visit']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_dokter'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaVisitMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and b.id_tipe_pasien='".$tipe_pasien."'";
        if($id_dokter!="") $kondisi .= " AND a.id_dokter = '".$id_dokter."'";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(a.tgl_visit) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(a.tgl_visit)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      a.tgl_visit,
                      b.id_pasien,
                      b.nama_pasien,
                      c.tipe_pasien,
                      f.ruang,
                      d.nama_dokter,
                      a.tarif,
                      (e.pajak * a.tarif) AS pajak
                    FROM
                      rm_visit a,
                      rm_pasien b,
                      rm_tipe_pasien c,
                      rm_dokter d,
                      rm_golongan e,
                      rm_ruang f
                    WHERE
                      b.id_pasien = a.id_pasien AND
                      a.del_flag<>'1' and
                      c.id_tipe_pasien = b.id_tipe_pasien AND
                      d.id_dokter = a.id_dokter AND
                      e.id_golongan = d.id_golongan AND
                      f.id_ruang=a.id_ruang ".$kondisi."
                    GROUP BY
                      a.id_visit";
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_visit'=>  $this->codeDate($rec['tgl_visit']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['tarif'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaPerawatanMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_ruang, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and b.id_tipe_pasien='".$tipe_pasien."'";
        if($id_ruang!="") $kondisi .= " AND a.id_ruang = '".$id_ruang."'";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(a.tgl_keluar) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(a.tgl_keluar)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      date(a.tgl_keluar) as tgl_keluar,
                      a.tarif,
                      a.lama_penggunaan,
                      a.lama_penggunaan,
                      a.id_pasien,
                      b.nama_pasien,
                      c.tipe_pasien,
                      d.ruang,
                      f.kamar,
                      (g.jasa_perawat * a.lama_penggunaan) as jasa_perawat,
                      (0.05 * (g.jasa_perawat * a.lama_penggunaan)) as pajak
                  FROM
                      rm_penggunaan_kamar a,
                      rm_pasien b,
                      rm_tipe_pasien c,
                      rm_ruang d,
                      rm_detail_kamar e,
                      rm_kamar f,
                      rm_tarif_kamar g
                  WHERE
                      b.id_pasien = a.id_pasien AND
                      c.id_tipe_pasien = b.id_tipe_pasien AND
                      d.id_ruang = a.id_ruang AND
                      e.id_detail_kamar = a.id_detail_kamar AND
                      f.id_kamar = e.id_kamar AND
                      f.id_kamar = g.id_kamar AND
                      g.id_kelas = a.id_kelas and a.del_flag<>'1'
                      and a.tgl_keluar!='' ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_keluar'=>  $this->codeDate($rec['tgl_keluar']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'kamar'=>$rec['kamar'],
                             'lama_penggunaan'=>number_format($rec['lama_penggunaan'],0,',','.')." Hari",
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_perawat'=>"Rp. ".number_format($rec['jasa_perawat'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_perawat'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_perawat":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function getJasaPerawatan($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and a.id_tipe_pasien='".$tipe_pasien."'";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and a.tgl_keluar BETWEEN '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)." 23:59:59'";
            else $kondisi .= " and a.tgl_keluar BETWEEN '".$this->formatDateDb($tgl_awal)."' AND '".$this->formatDateDb($tgl_awal)." 23:59:59'";
        }
        
        $query = "SELECT
                      date(a.tgl_keluar) as tgl_keluar,
                      a.tarif,
                      a.lama_penggunaan,
                      a.id_pasien,
                      b.nama_pasien,
                      c.tipe_pasien,
                      d.ruang,
                      f.kamar,
                      (a.askep * a.lama_penggunaan) as jasa_perawat,
                      (0.05 * (a.askep * a.lama_penggunaan)) as pajak,
                      (a.askep * a.lama_penggunaan) - (0.05 * (a.askep * a.lama_penggunaan)) as jumlah
                  FROM
                      rm_penggunaan_kamar a,
                      rm_pasien b,
                      rm_tipe_pasien c,
                      rm_ruang d,
                      rm_detail_kamar e,
                      rm_kamar f
                  WHERE
                      b.id_pasien = a.id_pasien AND
                      a.lama_penggunaan <> 0 AND
                      c.id_tipe_pasien = b.id_tipe_pasien AND
                      d.id_ruang = a.id_ruang AND
                      e.id_detail_kamar = a.id_detail_kamar AND
                      f.id_kamar = e.id_kamar AND
                      a.id_ruang = '".$_SESSION['level']."' and
                      a.del_flag<>'1'
                      and a.tgl_keluar!='' ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_keluar'=>  $this->codeDate($rec['tgl_keluar']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'kamar'=>$rec['kamar'],
                             'lama_penggunaan'=>number_format($rec['lama_penggunaan'],0,',','.')." Hari",
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_perawat'=>"Rp. ".number_format($rec['jasa_perawat'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.'),
                             'jumlah'=>"Rp. ".number_format($rec['jumlah'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_perawat'];
                $jmlPajak += $rec['pajak'];
                $jumlah += $rec['jumlah'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_perawat":"Rp. '.number_format($jmlJasa,2,',','.').'",
                    "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'",
                    "jumlah":"Rp. '.number_format($jumlah,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function getJasaTindakanDokterLab($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and c.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.ambil) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.ambil)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      DATE(b.ambil) as tgl_tindakan,
                      a.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      f.ruang,
                      g.kelompok_lab,
                      i.nama_dokter,
                      a.tarif,
                      h.laboratorium,
                      a.jasa_dokter,
                      (j.pajak * a.jasa_dokter) as pajak
                    FROM
                      rm_jasa_tindakan_laboratorium a,
                      rm_pemeriksaan_lab b,
                      rm_pasien c,
                      rm_tipe_pasien d, 
                      rm_pendaftaran e,
                      rm_ruang f,
                      rm_kelompok_lab g,
                      rm_laboratorium h,
                      rm_dokter i,
                      rm_golongan j
                    WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      b.id_pasien = a.id_pasien AND
                      c.id_pasien = a.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_pendaftaran = a.id_pendaftaran AND
                      f.id_ruang = e.id_ruang_asal AND
                      h.id_laboratorium = a.id_laboratorium AND
                      g.id_kelompok_lab = h.id_kelompok_lab AND
                      i.id_dokter = '".$_SESSION['id']."' and
                      j.id_golongan = i.id_golongan and
                      i.id_dokter = a.id_dokter ".$kondisi."
                    GROUP BY
                      a.id_jasa_tindakan_laboratorium";
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>  $this->codeDate($rec['tgl_tindakan']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'kelompok_lab'=>$rec['kelompok_lab'],
                             'laboratorium'=>$rec['laboratorium'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_dokter'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',"footer":[{"tipe_pasien":"Total",
                     "tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                     "jasa_dokter":"Rp. '.number_format($jmlJasa,2,',','.').'",
                     "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function getJasaLabMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and c.id_tipe_pasien='".$tipe_pasien."'";
        if($id_dokter!="") $kondisi .= " AND a.id_dokter = '".$id_dokter."'";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.ambil) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.ambil)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      DATE(b.ambil) as tgl_tindakan,
                      a.id_pasien,
                      c.nama_pasien,
                      d.tipe_pasien,
                      f.ruang,
                      g.kelompok_lab,
                      i.nama_dokter,
                      a.tarif,
                      h.laboratorium,
                      a.jasa_layanan,
                      a.jasa_sarana,
                      a.jasa_unit_penghasil,
                      a.jasa_direksi,
                      a.jasa_remunerasi,
                      a.jasa_dokter,
                      a.jasa_perawat,
                      (j.pajak * a.jasa_dokter) AS pajakDokter,
                      (0.05 * a.jasa_perawat) AS pajakPerawat
                    FROM
                      rm_jasa_tindakan_laboratorium a,
                      rm_pemeriksaan_lab b,
                      rm_pasien c,
                      rm_tipe_pasien d, 
                      rm_pendaftaran e,
                      rm_ruang f,
                      rm_kelompok_lab g,
                      rm_laboratorium h,
                      rm_dokter i,
                      rm_golongan j
                    WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      b.id_pasien = a.id_pasien AND
                      c.id_pasien = a.id_pasien AND
                      d.id_tipe_pasien = c.id_tipe_pasien AND
                      e.id_pendaftaran = a.id_pendaftaran AND
                      f.id_ruang = e.id_ruang_asal AND
                      h.id_laboratorium = a.id_laboratorium AND
                      g.id_kelompok_lab = h.id_kelompok_lab AND
                      j.id_golongan = i.id_golongan and
                      i.id_dokter = a.id_dokter ".$kondisi."
                    GROUP BY
                      a.id_jasa_tindakan_laboratorium";
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasaPelayanan = 0;
        $jmlJasaSarana = 0;
        $jmlJasaUnitPenghasil = 0;
        $jmlJasaRemunerasi = 0;
        $jmlJasaDireksi = 0;
        $jmlJasaDokter = 0;
        $jmlJasaPerawat = 0;
        $jmlPajakDokter = 0;
        $jmlPajakPerawat = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>  $this->codeDate($rec['tgl_tindakan']),
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'kelompok_lab'=>$rec['kelompok_lab'],
                             'laboratorium'=>$rec['laboratorium'],
                             'dokter'=>$rec['nama_dokter'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_pelayanan'=>"Rp. ".number_format($rec['jasa_layanan'],2,',','.'),
                             'jasa_sarana'=>"Rp. ".number_format($rec['jasa_sarana'],2,',','.'),
                             'jasa_unit_penghasil'=>"Rp. ".number_format($rec['jasa_unit_penghasil'],2,',','.'),
                             'jasa_remunerasi'=>"Rp. ".number_format($rec['jasa_remunerasi'],2,',','.'),
                             'jasa_direksi'=>"Rp. ".number_format($rec['jasa_direksi'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'jasa_perawat'=>"Rp. ".number_format($rec['jasa_perawat'],2,',','.'),
                             'pajakDokter'=>"Rp. ".number_format($rec['pajakDokter'],2,',','.'),
                             'pajakPerawat'=>"Rp. ".number_format($rec['pajakPerawat'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasaPelayanan += $rec['jasa_layanan'];
                $jmlJasaSarana += $rec['jasa_sarana'];
                $jmlJasaUnitPenghasil += $rec['jasa_unit_penghasil'];
                $jmlJasaRemunerasi += $rec['jasa_remunerasi'];
                $jmlJasaDireksi += $rec['jasa_direksi'];
                $jmlJasaDokter += $rec['jasa_dokter'];
                $jmlJasaPerawat += $rec['jasa_perawat'];
                $jmlPajakDokter += $rec['pajakDokter'];
                $jmlPajakPerawat += $rec['pajakPerawat'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_pelayanan":"Rp. '.number_format($jmlJasaPelayanan,2,',','.').'",
                    "jasa_sarana":"Rp. '.number_format($jmlJasaSarana,2,',','.').'",
                    "jasa_unit_penghasil":"Rp. '.number_format($jmlJasaUnitPenghasil,2,',','.').'",
                    "jasa_remunerasi":"Rp. '.number_format($jmlJasaRemunerasi,2,',','.').'",
                    "jasa_direksi":"Rp. '.number_format($jmlJasaDireksi,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasaDokter,2,',','.').'",
                    "jasa_perawat":"Rp. '.number_format($jmlJasaPerawat,2,',','.').'",
                    "pajakDokter":"Rp. '.number_format($jmlPajakDokter,2,',','.').'",
                    "pajakPerawat":"Rp. '.number_format($jmlPajakPerawat,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function getJasaTindakanDokterRadiologi($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and b.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(a.tgl_pemeriksaan) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(a.tgl_pemeriksaan)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      date(a.tgl_pemeriksaan) as tgl_tindakan,
                      b.id_pasien,
                      b.nama_pasien,
                      c.tipe_pasien,
                      e.ruang,
                      g.kelompok_radiologi,
                      f.radiologi,
                      h.nama_dokter,
                      i.tarif,
                      i.jasa_dokter,
                      (j.pajak * i.jasa_dokter) as pajak
                  FROM
                      rm_detail_radiologi a,
                      rm_pasien b,
                      rm_tipe_pasien c,
                      rm_pendaftaran d,
                      rm_ruang e,
                      rm_radiologi f,
                      rm_kelompok_radiologi g,
                      rm_dokter h,
                      rm_jasa_tindakan_radiologi i,
                      rm_golongan j
                  WHERE
                      b.id_pasien = a.id_pasien AND
                      c.id_tipe_pasien = b.id_tipe_pasien AND
                      d.id_pendaftaran = a.id_pendaftaran AND
                      e.id_ruang = d.id_ruang_asal AND
                      f.id_radiologi = a.id_radiologi AND
                      f.id_radiologi = i.id_radiologi AND
                      g.id_kelompok_radiologi = f.id_kelompok_radiologi AND
                      i.id_pendaftaran = a.id_pendaftaran AND
                      i.id_pasien = a.id_pasien AND
                      i.id_dokter = '".$_SESSION['id']."' and
                      j.id_golongan = h.id_golongan and
                      h.id_dokter = i.id_dokter ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasa = 0;
        $jmlPajak = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>$rec['tgl_tindakan'],
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'kelompok_rad'=>$rec['kelompok_radiologi'],
                             'radiologi'=>$rec['radiologi'],
                             'dokter'=>$rec['nama_dokter'],
                             'operator'=>'Radiografer',
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'pajak'=>"Rp. ".number_format($rec['pajak'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasa += $rec['jasa_dokter'];
                $jmlPajak += $rec['pajak'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',"footer":[{"tipe_pasien":"Total",
                     "tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                     "jasa_dokter":"Rp. '.number_format($jmlJasa,2,',','.').'",
                     "pajak":"Rp. '.number_format($jmlPajak,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function getJasaRadMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and b.id_tipe_pasien='".$tipe_pasien."'";
        if($id_dokter!="") $kondisi .= " AND i.id_dokter = '".$id_dokter."'";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(a.tgl_pemeriksaan) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(a.tgl_pemeriksaan)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      date(a.tgl_pemeriksaan) as tgl_tindakan,
                      b.id_pasien,
                      b.nama_pasien,
                      c.tipe_pasien,
                      e.ruang,
                      g.kelompok_radiologi,
                      f.radiologi,
                      h.nama_dokter,
                      i.tarif,
                      i.jasa_layanan,
                      i.jasa_sarana,
                      i.jasa_unit_penghasil,
                      i.jasa_direksi,
                      i.jasa_remunerasi,
                      i.jasa_dokter,
                      i.jasa_perawat,
                      (j.pajak * i.jasa_dokter) AS pajakDokter,
                      (0.05 * i.jasa_perawat) AS pajakPerawat
                  FROM
                      rm_detail_radiologi a,
                      rm_pasien b,
                      rm_tipe_pasien c,
                      rm_pendaftaran d,
                      rm_ruang e,
                      rm_radiologi f,
                      rm_kelompok_radiologi g,
                      rm_dokter h,
                      rm_jasa_tindakan_radiologi i,
                      rm_golongan j
                  WHERE
                      b.id_pasien = a.id_pasien AND
                      c.id_tipe_pasien = b.id_tipe_pasien AND
                      d.id_pendaftaran = a.id_pendaftaran AND
                      e.id_ruang = d.id_ruang_asal AND
                      f.id_radiologi = a.id_radiologi AND
                      f.id_radiologi = i.id_radiologi AND
                      g.id_kelompok_radiologi = f.id_kelompok_radiologi AND
                      i.id_pendaftaran = a.id_pendaftaran AND
                      i.id_pasien = a.id_pasien AND
                      j.id_golongan = h.id_golongan and
                      h.id_dokter = i.id_dokter ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasaPelayanan = 0;
        $jmlJasaSarana = 0;
        $jmlJasaUnitPenghasil = 0;
        $jmlJasaRemunerasi = 0;
        $jmlJasaDireksi = 0;
        $jmlJasaDokter = 0;
        $jmlJasaPerawat = 0;
        $jmlPajakDokter = 0;
        $jmlPajakPerawat = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>$rec['tgl_tindakan'],
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'ruang'=>$rec['ruang'],
                             'kelompok_rad'=>$rec['kelompok_radiologi'],
                             'radiologi'=>$rec['radiologi'],
                             'dokter'=>$rec['nama_dokter'],
                             'operator'=>'Radiografer',
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_pelayanan'=>"Rp. ".number_format($rec['jasa_layanan'],2,',','.'),
                             'jasa_sarana'=>"Rp. ".number_format($rec['jasa_sarana'],2,',','.'),
                             'jasa_unit_penghasil'=>"Rp. ".number_format($rec['jasa_unit_penghasil'],2,',','.'),
                             'jasa_remunerasi'=>"Rp. ".number_format($rec['jasa_remunerasi'],2,',','.'),
                             'jasa_direksi'=>"Rp. ".number_format($rec['jasa_direksi'],2,',','.'),
                             'jasa_dokter'=>"Rp. ".number_format($rec['jasa_dokter'],2,',','.'),
                             'jasa_perawat'=>"Rp. ".number_format($rec['jasa_perawat'],2,',','.'),
                             'pajakDokter'=>"Rp. ".number_format($rec['pajakDokter'],2,',','.'),
                             'pajakPerawat'=>"Rp. ".number_format($rec['pajakPerawat'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasaPelayanan += $rec['jasa_layanan'];
                $jmlJasaSarana += $rec['jasa_sarana'];
                $jmlJasaUnitPenghasil += $rec['jasa_unit_penghasil'];
                $jmlJasaRemunerasi += $rec['jasa_remunerasi'];
                $jmlJasaDireksi += $rec['jasa_direksi'];
                $jmlJasaDokter += $rec['jasa_dokter'];
                $jmlJasaPerawat += $rec['jasa_perawat'];
                $jmlPajakDokter += $rec['pajakDokter'];
                $jmlPajakPerawat += $rec['pajakPerawat'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',
                    "footer":[{"tipe_pasien":"Total","tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_pelayanan":"Rp. '.number_format($jmlJasaPelayanan,2,',','.').'",
                    "jasa_sarana":"Rp. '.number_format($jmlJasaSarana,2,',','.').'",
                    "jasa_unit_penghasil":"Rp. '.number_format($jmlJasaUnitPenghasil,2,',','.').'",
                    "jasa_remunerasi":"Rp. '.number_format($jmlJasaRemunerasi,2,',','.').'",
                    "jasa_direksi":"Rp. '.number_format($jmlJasaDireksi,2,',','.').'",
                    "jasa_dokter":"Rp. '.number_format($jmlJasaDokter,2,',','.').'",
                    "jasa_perawat":"Rp. '.number_format($jmlJasaPerawat,2,',','.').'",
                    "pajakDokter":"Rp. '.number_format($jmlPajakDokter,2,',','.').'",
                    "pajakPerawat":"Rp. '.number_format($jmlPajakPerawat,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function getJasaTindakanDokterBedah($tgl_awal, $tgl_akhir, $tipe_pasien, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and b.id_tipe_pasien='".$tipe_pasien."'";
        
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and a.tgl_entry between '".$this->formatDateDb($tgl_awal)." 00:00:00' and '".$this->formatDateDb($tgl_akhir)." 23:59:59'";
            else $kondisi .= " and a.tgl_entry between '".$this->formatDateDb($tgl_awal)." 00:00:00' AND '".$this->formatDateDb($tgl_awal)." 23:59:59'";
        }
        
        $query = "SELECT
                      date(b.tgl_tindakan) as tgl_tindakan,
                      a.id_pasien,
                      c.nama_pasien,
                      g.tipe_pasien,
                      d.tindakan,
                      e.nama_dokter AS dokter_operator,
                      f.nama_dokter AS dokter_anastesi,
                      (b.tarif + b.penambahan_tarif) AS tarif,
                      a.tim_operator,
                      a.ass_tim_operator,
                      a.tim_anastesi,
                      a.ass_tim_anastesi
                  FROM
                      rm_jasa_tindakan_bedah a,
                      rm_tindakan_ruang_medis b,
                      rm_pasien c,
                      rm_tipe_pasien g,
                      rm_tindakan d,
                      rm_dokter e,
                      rm_dokter f,
					  rm_detail_tindakan h
                  WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      a.id_tindakan_medis = b.id_tindakan_medis AND
                      a.id_pasien = c.id_pasien AND
                      c.id_tipe_pasien = g.id_tipe_pasien AND
                      h.id_detail_tindakan = a.id_tindakan_medis AND
					  h.id_tindakan = d.id_tindakan AND
                   
                      b.dokter_operator = e.id_dokter AND
                      b.dokter_anastesi = f.id_dokter ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlOperator = 0;
        $jmlAssOperator = 0;
        $jmlAnastesi = 0;
        $jmlAssAnastesi = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>$rec['tgl_tindakan'],
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tindakan_medis'=>$rec['tindakan'],
                             'dokter_operator'=>$rec['dokter_operator'],
                             'dokter_anastesi'=>$rec['dokter_anastesi'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'tim_operator'=>"Rp. ".number_format($rec['tim_operator'],2,',','.'),
                             'ass_tim_operator'=>"Rp. ".number_format($rec['ass_tim_operator'],2,',','.'),
                             'tim_anastesi'=>"Rp. ".number_format($rec['tim_anastesi'],2,',','.'),
                             'ass_tim_anastesi'=>"Rp. ".number_format($rec['ass_tim_anastesi'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlOperator += $rec['tim_operator'];
                $jmlAssOperator += $rec['ass_tim_operator'];
                $jmlAnastesi += $rec['tim_anastesi'];
                $jmlAssAnastesi += $rec['ass_tim_anastesi'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',"footer":[{"tipe_pasien":"Total",
                     "tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                     "tim_operator":"Rp. '.number_format($jmlOperator,2,',','.').'",
                     "ass_tim_operator":"Rp. '.number_format($jmlAssOperator,2,',','.').'",
                     "tim_anastesi":"Rp. '.number_format($jmlAnastesi,2,',','.').'",
                     "ass_tim_anastesi":"Rp. '.number_format($jmlAssAnastesi,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getJasaBedahMnj($tgl_awal, $tgl_akhir, $tipe_pasien, $id_dokter, $rows, $offset){
        $kondisi = "";
        
        if($tipe_pasien!="") $kondisi .= " and c.id_tipe_pasien='".$tipe_pasien."'";
        if($id_dokter!="") $kondisi .= " AND (b.dokter_anastesi = '".$id_dokter."' or b.dokter_operator = '".$id_dokter."')";
        if($tgl_awal!=""){
            if($tgl_akhir!="") $kondisi .= " and DATE(b.tgl_tindakan) between '".$this->formatDateDb($tgl_awal)."' and '".$this->formatDateDb($tgl_akhir)."'";
            else $kondisi .= " and DATE(b.tgl_tindakan)='".$this->formatDateDb($tgl_awal)."'";
        }
        
        $query = "SELECT
                      date(b.tgl_tindakan) as tgl_tindakan,
                      a.id_pasien,
                      c.nama_pasien,
                      g.tipe_pasien,
                      d.tindakan,
                      e.nama_dokter AS dokter_operator,
                      f.nama_dokter AS dokter_anastesi,
                      (b.tarif + b.penambahan_tarif) AS tarif,
                      jasa_sarana,
                      jasa_pelayanan,
                      unit_penghasil,
                      direksi,
                      remunerasi,
                      a.jasa_pelayanan,
                      a.jasa_sarana,
                      a.unit_penghasil,
                      a.direksi,
                      a.remunerasi,
                      a.tim_operator,
                      a.ass_tim_operator,
                      a.tim_anastesi,
                      a.ass_tim_anastesi
                  FROM
                      rm_jasa_tindakan_bedah a,
                      rm_tindakan_ruang_medis b,
                      rm_pasien c,
                      rm_tipe_pasien g,
                      rm_tindakan d,
                      rm_detail_tindakan h,
                      rm_dokter e,
                      rm_dokter f
                  WHERE
                      b.id_pendaftaran = a.id_pendaftaran AND
                      a.id_tindakan_medis = b.id_tindakan_medis AND
                      a.id_pasien = c.id_pasien AND
                      c.id_tipe_pasien = g.id_tipe_pasien AND
                      h.id_detail_tindakan = a.id_tindakan_medis AND
                      d.id_tindakan = h.id_tindakan AND
                      b.dokter_operator = e.id_dokter AND
                      b.dokter_anastesi = f.id_dokter ".$kondisi;
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        $jmlTarif = 0;
        $jmlJasaPelayanan = 0;
        $jmlJasaSarana = 0;
        $jmlJasaUnitPenghasil = 0;
        $jmlJasaRemunerasi = 0;
        $jmlJasaDireksi = 0;
        $jmlOperator = 0;
        $jmlAssOperator = 0;
        $jmlAnastesi = 0;
        $jmlAssAnastesi = 0;
        if ($jmlData>0){
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'tgl_tindakan'=>$rec['tgl_tindakan'],
                             'id_pasien'=>$rec['id_pasien'],
                             'nama_pasien'=>$rec['nama_pasien'],
                             'tipe_pasien'=>$rec['tipe_pasien'],
                             'tindakan'=>$rec['tindakan'],
                             'dokter_operator'=>$rec['dokter_operator'],
                             'dokter_anastesi'=>$rec['dokter_anastesi'],
                             'tarif'=>"Rp. ".number_format($rec['tarif'],2,',','.'),
                             'jasa_pelayanan'=>"Rp. ".number_format($rec['jasa_pelayanan'],2,',','.'),
                             'jasa_sarana'=>"Rp. ".number_format($rec['jasa_sarana'],2,',','.'),
                             'jasa_unit_penghasil'=>"Rp. ".number_format($rec['unit_penghasil'],2,',','.'),
                             'jasa_remunerasi'=>"Rp. ".number_format($rec['remunerasi'],2,',','.'),
                             'jasa_direksi'=>"Rp. ".number_format($rec['direksi'],2,',','.'),
                             'tim_operator'=>"Rp. ".number_format($rec['tim_operator'],2,',','.'),
                             'ass_tim_operator'=>"Rp. ".number_format($rec['ass_tim_operator'],2,',','.'),
                             'tim_anastesi'=>"Rp. ".number_format($rec['tim_anastesi'],2,',','.'),
                             'ass_tim_anastesi'=>"Rp. ".number_format($rec['ass_tim_anastesi'],2,',','.')
                         );
                $jmlTarif += $rec['tarif'];
                $jmlJasaPelayanan += $rec['jasa_pelayanan'];
                $jmlJasaSarana += $rec['jasa_sarana'];
                $jmlJasaUnitPenghasil += $rec['unit_penghasil'];
                $jmlJasaRemunerasi += $rec['remunerasi'];
                $jmlJasaDireksi += $rec['direksi'];
                $jmlOperator += $rec['tim_operator'];
                $jmlAssOperator += $rec['ass_tim_operator'];
                $jmlAnastesi += $rec['tim_anastesi'];
                $jmlAssAnastesi += $rec['ass_tim_anastesi'];
            }
            return '{"total":'.$jmlData.', "rows":'.$this->jEncode($arr).',"footer":[{"tipe_pasien":"Total",
                     "tarif":"Rp. '.number_format($jmlTarif,2,',','.').'",
                    "jasa_pelayanan":"Rp. '.number_format($jmlJasaPelayanan,2,',','.').'",
                    "jasa_sarana":"Rp. '.number_format($jmlJasaSarana,2,',','.').'",
                    "jasa_unit_penghasil":"Rp. '.number_format($jmlJasaUnitPenghasil,2,',','.').'",
                    "jasa_remunerasi":"Rp. '.number_format($jmlJasaRemunerasi,2,',','.').'",
                    "jasa_direksi":"Rp. '.number_format($jmlJasaDireksi,2,',','.').'",
                     "tim_operator":"Rp. '.number_format($jmlOperator,2,',','.').'",
                     "ass_tim_operator":"Rp. '.number_format($jmlAssOperator,2,',','.').'",
                     "tim_anastesi":"Rp. '.number_format($jmlAnastesi,2,',','.').'",
                     "ass_tim_anastesi":"Rp. '.number_format($jmlAssAnastesi,2,',','.').'"}]}';
        } else {
            return '{"total":"0", "rows":[],"footer":[]}';
        }
    }

}

?>
