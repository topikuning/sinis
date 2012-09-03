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
class cRekam extends fungsi{
    //put your code here    
    public function cariDtlPasien($id_pasien){
        $query = "select nama_pasien, tgl_lahir, id_kelamin, id_tipe_pasien from rm_pasien where id_pasien='".$id_pasien."'";
        $result = $this->runQuery($query);
        
        if(mysql_num_rows($result)>0){
            $return =   array(
                            "id_pasien"=>$id_pasien,
                            "pasien"=>@mysql_result($result, 0, "nama_pasien"),
                            "usia"=>$this->getUmur(@mysql_result($result, 0, "tgl_lahir")),
                            "jns_kelamin"=>$this->getKelamin(@mysql_result($result, 0, "id_kelamin")),
                            "jns_pasien"=>$this->getTipePasien(@mysql_result($result, 0, "id_tipe_pasien"))
                        );
            
            return $this->jEncode($return);
        }
    }
    
    public function getRekamMedisPasien(
                $id_pasien,
                $nama_pasien,
                $id_ruang,
                $startDate,
                $endDate,
                $rows,
                $offset
            ){
        $kondisi = "";
        if($id_pasien!="") $kondisi .= " and a.id_pasien='".$id_pasien."'";
        if($nama_pasien!="") $kondisi .= " and d.nama_pasien like '".@mysql_escape_string($nama_pasien)."%'";
        if($startDate!=""){
            if($endDate!="") $kondisi .= " and date(a.tgl_pendaftaran) between '".  $this->formatDateDb($startDate)."' and '".$this->formatDateDb($endDate)."'";
            else $kondisi .= " and date(a.tgl_pendaftaran)='".$this->formatDateDb($startDate)."'";
        }
        if($id_ruang!="") $kondisi .= " and a.id_ruang='".$id_ruang."'";
        
        
        $query = "SELECT id_pendaftaran, a.id_tipe_pendaftaran, DATE(tgl_pendaftaran) AS tgl_daftar, ruang, tipe_pendaftaran 
                  FROM rm_pendaftaran a, rm_ruang b, rm_tipe_pendaftaran c, rm_pasien d
                  WHERE b.id_ruang=a.id_ruang and id_asal_pendaftaran='0' and d.id_pasien=a.id_pasien and c.id_tipe_pendaftaran=a.id_tipe_pendaftaran AND a.del_flag<>'1' ".$kondisi." order by id_pendaftaran desc";
        $result = $this->runQuery($query);
        
        $jmlData = @mysql_num_rows($result);
        $query .= " limit ".$offset.",".$rows;
        $result = $this->runQuery($query);
        
        if ($jmlData>0){
            $i = 1;
            while($rec = mysql_fetch_array($result)){
                $arr[] = array(
                             'id_pendaftaran'=>$rec['id_pendaftaran'],
                             'id_tipe_pendaftaran'=>  $rec['id_tipe_pendaftaran'],
                             'tipe_pendaftaran'=>  $rec['tipe_pendaftaran'],
                             'tgl_pendaftaran'=>  $this->codeDate($rec['tgl_daftar']),
                             'ruang'=>$rec['ruang']
                         );
            }
            return $this->jEncode($arr);
        } else {
            return '[]';
        }
    }

}

?>
