<?php
ini_set("date.timezone", "Asia/Jakarta");
require_once '../../common/function.php';
$fungsi = new fungsi();
$id_pendaftaran = $_GET['id_pendaftaran'];
$id_tipe_pendaftaran = $_GET['id_tipe_pendaftaran'];
?>
<div class='printArea'>
    <p><b>DIAGNOSA</b></p>
    <table style=' font-family: verdana; font-size: 10px;' bgcolor="#0D8E13" width='100%' border='0' cellspacing='1' cellpadding='0'>
        <thead>
            <tr height='20' bgcolor="#54AF54" align='center' style="font-weight: bold;">
                <td width='5%'>No</td>
                <td width='20%'>Tanggal Diagnosa</td>
                <td width='40%'>Dx. Primer</td>
                <td width='35%'>Dx. Sekunder</td>
            </tr>
        </thead>
        <tbody>
            <?
            $q_diagnosa = "SELECT date(a.tgl_diagnosa) as tgl_diagnosa, b.nama_penyakit AS penyakit_primer, c.nama_penyakit AS penyakit_sekunder
                               FROM rm_diagnosa a, rm_penyakit b, rm_penyakit c
                               WHERE b.id_penyakit=a.penyakit_primer AND c.id_penyakit=a.penyakit_sekunder
                               AND a.id_pendaftaran='" . $id_pendaftaran . "'";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {

                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td width='5%'>$i</td>";
                    if ($tgl == $rec['tgl_diagnosa'])
                        echo "<td width='20%'>&nbsp</td>";
                    else
                        echo "<td width='20%'>" . $fungsi->codeDate($rec['tgl_diagnosa']) . "</td>";

                    if ($prim == $rec['penyakit_primer'])
                        echo "<td width='40%'>&nbsp</td>";
                    else
                        echo "<td width='40%'>" . $rec['penyakit_primer'] . "</td>";

                    echo "<td width='40%'>" . $rec['penyakit_sekunder'] . "</td>
                              </tr>";
                    $i++;
                    $tgl = $rec['tgl_diagnosa'];
                    $prim = $rec['penyakit_primer'];
                };
            }
            //if($id_tipe_pendaftaran=='6'){
            $q_diagnosa = "SELECT date(a.tgl_diagnosa) as tgl_diagnosa, c.ruang, b.nama_penyakit AS penyakit_primer
                                   FROM rm_diagnosa a, rm_penyakit b, rm_ruang c, rm_pendaftaran d
                                   WHERE b.id_penyakit=a.penyakit_primer and c.id_ruang=d.id_ruang and d.id_pendaftaran=a.id_pendaftaran
                                   AND a.id_pendaftaran in (select id_pendaftaran from rm_pendaftaran
                                   where id_asal_pendaftaran='" . $id_pendaftaran . "')";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                    <td width='5%'>$i</td>
                                    <td width='40%'>" . $fungsi->codeDate($rec['tgl_diagnosa']) . " (" . $rec['ruang'] . ")</td>
                                    <td width='55%'>" . $rec['penyakit_primer'] . "</td>
                                  </tr>";
                    $i++;
                };
            }
            //}
            if ($i == 1) {
                echo "<tr height='20' bgcolor='#99FF99'>
                            <td width='5%' colspan='3'>Data Kosong</td>
                          </tr>";
            }
            ?>
        </tbody>
    </table>
    <p><b>DETAIL DIAGNOSA</b></p>
    <table style=' font-family: verdana; font-size: 10px;' bgcolor="#0D8E13" width='100%' border='0' cellspacing='1' cellpadding='0'>
        <thead>
            <tr height='20' bgcolor="#54AF54" align='center' style="font-weight: bold;">
                <td width='5%'>No</td>
                <td width='40%'>Keluhan Utama</td>
                <td width='35%'>Terapi</td>
                <td width='20%'>Hasil Pemeriksaan</td>
            </tr>
        </thead>
        <tbody>
            <?
            $q_diagnosa = "SELECT keluhan, terapi, hasil_pemeriksaan FROM rm_detail_diagnosa WHERE id_pendaftaran='" . $id_pendaftaran . "' 
                           AND del_flag<>1";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {

                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td>$i</td>";
                    echo "<td>" . $rec['keluhan'] . "</td>";
                    echo "<td>" . $rec['terapi'] . "</td>";
                    echo "<td>" . $rec['hasil_pemeriksaan'] . "</td></tr>";
                    $i++;
                };
            } else {
                if ($i == 1) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                            <td width='5%' colspan='3'>Data Kosong</td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <p><b>TINDAKAN</b></p>
    <table style=' font-family: verdana; font-size: 10px;' bgcolor="#0D8E13" width='100%' border='0' cellspacing='1' cellpadding='0'>
        <thead>
            <tr height='20' bgcolor="#54AF54" align='center' style="font-weight: bold;">
                <td width='5%'>No</td>
                <td width='20%'>Tanggal Tindakan</td>
                <td width='75%'>Tindakan</td>
            </tr>
        </thead>
        <tbody>
            <?
            $q_diagnosa = "SELECT DATE(tgl_tindakan) AS tgl_tindakan, c.tindakan 
                               FROM rm_tindakan_ruang a, rm_detail_tindakan b, rm_tindakan c
                               WHERE b.id_detail_tindakan=a.id_detail_tindakan AND c.id_tindakan=b.id_tindakan
                               AND a.id_pendaftaran='" . $id_pendaftaran . "'";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td width='5%'>$i</td>
                                <td width='20%'>" . $fungsi->codeDate($rec['tgl_tindakan']) . "</td>
                                <td width='75%'>" . $rec['tindakan'] . "</td>
                              </tr>";
                    $i++;
                };
            }
            //if($id_tipe_pendaftaran=='6'){
            $q_diagnosa = "SELECT DATE(tgl_tindakan) AS tgl_tindakan, c.tindakan, f.ruang 
                                   FROM rm_tindakan_ruang a, rm_detail_tindakan b, rm_tindakan c, rm_ruang f, rm_pendaftaran e
                                   WHERE b.id_detail_tindakan=a.id_detail_tindakan AND c.id_tindakan=b.id_tindakan
                                   and e.id_pendaftaran=a.id_pendaftaran and f.id_ruang=e.id_ruang
                                   AND a.id_pendaftaran in (select id_pendaftaran from rm_pendaftaran
                                   where id_asal_pendaftaran='" . $id_pendaftaran . "')";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                    <td width='5%'>$i</td>
                                    <td width='40%'>" . $fungsi->codeDate($rec['tgl_tindakan']) . " (" . $rec['ruang'] . ")</td>
                                    <td width='55%'>" . $rec['tindakan'] . "</td>
                                  </tr>";
                    $i++;
                };
            }
            //}
            if ($i == 1) {
                echo "<tr height='20' bgcolor='#99FF99'>
                            <td width='5%' colspan='3'>Data Kosong</td>
                          </tr>";
            }
            ?>
        </tbody>
    </table>
    <p><b>TINDAKAN BEDAH</b></p>
    <table style=' font-family: verdana; font-size: 10px;' bgcolor="#0D8E13" width='100%' border='0' cellspacing='1' cellpadding='0'>
        <thead>
            <tr height='20' bgcolor="#54AF54" align='center' style="font-weight: bold;">
                <td width='5%'>No</td>
                <td width='20%'>Tanggal Tindakan</td>
                <td width='75%'>Tindakan</td>
            </tr>
        </thead>
        <tbody>
            <?
            $q_diagnosa = "SELECT DATE(tgl_tindakan) AS tgl_tindakan, c.tindakan 
                               FROM rm_tindakan_ruang_medis a, rm_detail_tindakan b, rm_tindakan c
                               WHERE b.id_detail_tindakan=a.id_tindakan_medis AND c.id_tindakan=b.id_tindakan
                               AND a.id_pendaftaran='" . $id_pendaftaran . "'";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td width='5%'>$i</td>
                                <td width='20%'>" . $fungsi->codeDate($rec['tgl_tindakan']) . "</td>
                                <td width='75%'>" . $rec['tindakan'] . "</td>
                              </tr>";
                    $i++;
                };
            }
            //if($id_tipe_pendaftaran=='6'){
            $q_diagnosa = "SELECT DATE(tgl_tindakan) AS tgl_tindakan, c.tindakan 
                                   FROM rm_tindakan_ruang_medis a, rm_detail_tindakan b, rm_tindakan c
                                   WHERE b.id_detail_tindakan=a.id_tindakan_medis AND c.id_tindakan=b.id_tindakan
                                   AND a.id_pendaftaran in (select id_pendaftaran from rm_pendaftaran
                                   where id_asal_pendaftaran='" . $id_pendaftaran . "')";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                    <td width='5%'>$i</td>
                                    <td width='20%'>" . $fungsi->codeDate($rec['tgl_tindakan']) . "</td>
                                    <td width='75%'>" . $rec['tindakan'] . "</td>
                                  </tr>";
                    $i++;
                };
            }
            //}
            if ($i == 1) {
                echo "<tr height='20' bgcolor='#99FF99'>
                            <td width='5%' colspan='3'>Data Kosong</td>
                          </tr>";
            }
            ?>
        </tbody>
    </table>
    <p><b>LABORATORIUM</b></p>
    <table style=' font-family: verdana; font-size: 10px;' bgcolor="#0D8E13" width='100%' border='0' cellspacing='1' cellpadding='0'>
        <thead>
            <tr height='20' bgcolor="#54AF54" align='center' style="font-weight: bold;">
                <td width='5%'>No</td>
                <td width='20%'>Tanggal Pemeriksaan</td>
                <td width='35%'>Pemeriksaan</td>
                <td width='20%'>Nilai Normal</td>
                <td width='20%'>Hasil Pemeriksaan</td>
            </tr>
        </thead>
        <tbody>
            <?
            $q_diagnosa = "SELECT DATE(a.tgl_pemeriksaan) AS tgl_periksa, b.laboratorium, b.nilai_normal, a.hasil FROM rm_detail_laboratorium a, rm_laboratorium b 
                               WHERE a.del_flag<>'1' AND b.id_laboratorium=a.id_laboratorium 
                               and a.id_pendaftaran='" . $id_pendaftaran . "'";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td width='5%'>$i</td>
                                <td width='20%'>" . $fungsi->codeDate($rec['tgl_periksa']) . "</td>
                                <td width='35%'>" . $rec['laboratorium'] . "</td>
                                <td width='20%'>" . $rec['nilai_normal'] . "</td>
                                <td width='20%'>" . $rec['hasil'] . "</td>
                              </tr>";
                    $i++;
                };
            }
            //if($id_tipe_pendaftaran=='6'){
            $q_diagnosa = "SELECT DATE(a.tgl_pemeriksaan) AS tgl_periksa, b.laboratorium, b.nilai_normal, a.hasil FROM rm_detail_laboratorium a, rm_laboratorium b 
                                   WHERE a.del_flag<>'1' AND b.id_laboratorium=a.id_laboratorium 
                                   and a.id_pendaftaran in (select id_pendaftaran from rm_pendaftaran
                                   where id_asal_pendaftaran='" . $id_pendaftaran . "')";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                    <td width='5%'>$i</td>
                                    <td width='20%'>" . $fungsi->codeDate($rec['tgl_periksa']) . "</td>
                                    <td width='35%'>" . $rec['laboratorium'] . "</td>
                                    <td width='20%'>" . $rec['nilai_normal'] . "</td>
                                    <td width='20%'>" . $rec['hasil'] . "</td>
                                  </tr>";
                    $i++;
                };
            }
            //}
            if ($i == 1) {
                echo "<tr height='20' bgcolor='#99FF99'>
                            <td width='5%' colspan='5'>Data Kosong</td>
                          </tr>";
            }
            ?>
        </tbody>
    </table>
    <p><b>RADIOLOGI</b></p>
    <table style=' font-family: verdana; font-size: 10px;' bgcolor="#0D8E13" width='100%' border='0' cellspacing='1' cellpadding='0'>
        <thead>
            <tr height='20' bgcolor="#54AF54" align='center' style="font-weight: bold;">
                <td width='5%'>No</td>
                <td width='20%'>Tanggal Pemeriksaaa</td>
                <td width='75%'>Pemeriksaan</td>
            </tr>
        </thead>
        <tbody>
            <?
            $q_diagnosa = "SELECT DATE(tgl_pemeriksaan) AS tgl_pemeriksaan, radiologi
                               FROM rm_detail_radiologi a, rm_radiologi b
                               WHERE b.id_radiologi=a.id_radiologi AND id_pendaftaran='" . $id_pendaftaran . "'";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $i = 1;
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                <td width='5%'>$i</td>
                                <td width='20%'>" . $fungsi->codeDate($rec['tgl_pemeriksaan']) . "</td>
                                <td width='75%'>" . $rec['radiologi'] . "</td>
                              </tr>";
                    $i++;
                };
            }
            //if($id_tipe_pendaftaran=='6'){
            $q_diagnosa = "SELECT DATE(tgl_pemeriksaan) AS tgl_pemeriksaan, radiologi
                                   FROM rm_detail_radiologi a, rm_radiologi b
                                   WHERE b.id_radiologi=a.id_radiologi AND id_pendaftaran in (select id_pendaftaran from rm_pendaftaran
                                   where id_asal_pendaftaran='" . $id_pendaftaran . "')";
            $r_diagnosa = $fungsi->runQuery($q_diagnosa);
            $jmlData = @mysql_num_rows($r_diagnosa);
            if ($jmlData > 0) {
                while ($rec = @mysql_fetch_array($r_diagnosa)) {
                    echo "<tr height='20' bgcolor='#99FF99'>
                                    <td width='5%'>$i</td>
                                    <td width='20%'>" . $fungsi->codeDate($rec['tgl_pemeriksaan']) . "</td>
                                    <td width='75%'>" . $rec['radiologi'] . "</td>
                                  </tr>";
                    $i++;
                };
            }
            //}
            if ($i == 1) {
                echo "<tr height='20' bgcolor='#99FF99'>
                            <td width='5%' colspan='3'>Data Kosong</td>
                          </tr>";
            }
            ?>
        </tbody>
    </table>
</div>