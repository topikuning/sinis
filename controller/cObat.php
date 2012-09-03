<?php

session_start();
require_once '../../common/function.php';

class cObat extends fungsi {

    //put your code here
    public function getListObat(
    $kode_obat, $obat, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = '';
        if ($kode_obat != '')
            $kondisi .= " and b.kode_obat like '" . @mysql_escape_string($kode_obat) . "%'";
        if ($obat != '')
            $kondisi .= " and b.nama_obat like '" . @mysql_escape_string($obat) . "%'";
        if ($startDate != '') {
            if ($endDate != '')
                $kondisi .= " and a.tgl_kadaluarsa_baru between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and a.tgl_kadaluarsa_baru='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT b.kode_obat, nama_obat, a.id_penyimpanan, c.penyimpanan, stock, stock_limit, 
                  d.hpp, d.umum, d.askes, d.jps, a.tgl_kadaluarsa_baru FROM rm_stock_obat a, rm_obat b, rm_penyimpanan c, rm_tarif_obat d
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan AND d.id_obat=a.id_obat and b.del_flag<>1 " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query = "SELECT a.id_obat, b.kode_obat, nama_obat, a.id_penyimpanan, c.penyimpanan, stock, stock_limit, 
                  d.hpp, d.umum, d.askes, d.jps, a.tgl_kadaluarsa_baru FROM rm_stock_obat a, rm_obat b, rm_penyimpanan c, rm_tarif_obat d
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan AND d.id_obat=a.id_obat and b.del_flag<>1 
                  " . $kondisi . " ORDER BY b.kode_obat limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_obat' => $rec['id_obat'],
                    'id_penyimpanan' => $rec['id_penyimpanan'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'jumlah_stock' => $rec['stock'],
                    'stock_limit' => $rec['stock_limit'],
                    'hpp' => $rec['hpp'],
                    'umum' => $rec['umum'],
                    'askes' => $rec['askes'],
                    'jps' => $rec['jps'],
                    'tgl_kadaluarsa' => $this->formatDateDb($rec['tgl_kadaluarsa_baru'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function getListBarang(
    $id_barang, $jenis_barang, $rows, $offset
    ) {

        $kondisi = '';
        if ($id_barang != '')
            $kondisi .= " and a.id_barang='" . $id_barang . "'";
        if ($jenis_barang != '')
            $kondisi .= " and b.id_jenis_barang='" . $jenis_barang . "'";

        $query = "select a.id_barang, b.barang, c.jenis_barang, b.satuan, a.jumlah_stock, a.stock_limit 
                  from rm_stock_barang a, rm_barang b, rm_jenis_barang c where id_ruang='" . $_SESSION['level'] . "' 
                  and c.id_jenis_barang=b.id_jenis_barang and b.id_barang=a.id_barang" . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_barang' => $rec['id_barang'],
                    'nama_barang' => $rec['barang'],
                    'jenis_barang' => $rec['jenis_barang'],
                    'satuan' => $rec['satuan'],
                    'jumlah_stock' => $rec['jumlah_stock'],
                    'stock_limit' => $rec['stock_limit']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function getListObatApotik(
    $kode_obat, $obat, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = '';
        if ($kode_obat != '')
            $kondisi .= " and b.kode_obat like '" . $kode_obat . "%'";
        if ($obat != '')
            $kondisi .= " and b.nama_obat like '" . @mysql_escape_string($obat) . "%'";
        if ($startDate != '') {
            if ($endDate != '')
                $kondisi .= " and tgl_kadaluarsa between '" . $startDate . "' and '" . $endDate . "'";
            else
                $kondisi .= " and tgl_kadaluarsa='" . $startDate . "'";
        }

        $query = "SELECT b.kode_obat, nama_obat, a.id_penyimpanan, c.penyimpanan, stock, stock_limit, 
                  d.hpp, d.umum, d.askes, d.jps, tgl_kadaluarsa_baru FROM rm_stock_obat_apotik a, rm_obat b, rm_penyimpanan c, rm_tarif_obat d
                  WHERE b.id_obat = a.id_obat and a.id_ruang='" . $_SESSION['level'] . "' AND c.id_penyimpanan=a.id_penyimpanan AND d.id_obat=a.id_obat " . $kondisi . " ORDER BY b.kode_obat";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query = "SELECT a.id_obat, b.kode_obat, nama_obat, a.id_penyimpanan, c.penyimpanan, stock, stock_limit, 
                  d.hpp, d.umum, d.askes, d.jps, tgl_kadaluarsa_baru FROM rm_stock_obat_apotik a, rm_obat b, rm_penyimpanan c, rm_tarif_obat d
                  WHERE b.id_obat = a.id_obat and a.id_ruang='" . $_SESSION['level'] . "' AND c.id_penyimpanan=a.id_penyimpanan AND d.id_obat=a.id_obat 
                  " . $kondisi . " ORDER BY b.kode_obat LIMIT " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_obat' => $rec['id_obat'],
                    'id_penyimpanan' => $rec['id_penyimpanan'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'jumlah_stock' => $rec['stock'],
                    'stock_limit' => $rec['stock_limit'],
                    'hpp' => $rec['hpp'],
                    'umum' => $rec['umum'],
                    'askes' => $rec['askes'],
                    'jps' => $rec['jps'],
                    'tgl_kadaluarsa' => $this->formatDateDb($rec['tgl_kadaluarsa_baru'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getListObatRuang(
    $kode_obat, $obat, $startDate, $endDate, $rows, $offset
    ) {

        $kondisi = '';
        if ($kode_obat != '')
            $kondisi .= " and b.kode_obat='" . $kode_obat . "'";
        if ($obat != '')
            $kondisi .= " and b.nama_obat like '" . @mysql_escape_string($obat) . "%'";
        if ($startDate != '') {
            if ($endDate != '')
                $kondisi .= " and tgl_kadaluarsa between '" . $startDate . "' and '" . $endDate . "'";
            else
                $kondisi .= " and tgl_kadaluarsa='" . $startDate . "'";
        }

        $query = "SELECT b.kode_obat, nama_obat, a.id_penyimpanan, c.penyimpanan, stock, stock_limit, 
                  d.hpp, d.umum, d.askes, d.jps, tgl_kadaluarsa_baru FROM rm_stock_obat_ruang a, rm_obat b, rm_penyimpanan c, rm_tarif_obat d
                  WHERE b.id_obat = a.id_obat and a.id_ruang='" . $_SESSION['level'] . "' AND c.id_penyimpanan=a.id_penyimpanan AND d.id_obat=a.id_obat " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query = "SELECT a.id_obat, b.kode_obat, nama_obat, a.id_penyimpanan, c.penyimpanan, stock, stock_limit, 
                  d.hpp, d.umum, d.askes, d.jps, tgl_kadaluarsa_baru FROM rm_stock_obat_ruang a, rm_obat b, rm_penyimpanan c, rm_tarif_obat d
                  WHERE b.id_obat = a.id_obat and a.id_ruang='" . $_SESSION['level'] . "' AND c.id_penyimpanan=a.id_penyimpanan AND d.id_obat=a.id_obat 
                  " . $kondisi . " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_obat' => $rec['id_obat'],
                    'id_penyimpanan' => $rec['id_penyimpanan'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'jumlah_stock' => $rec['stock'],
                    'stock_limit' => $rec['stock_limit'],
                    'hpp' => $rec['hpp'],
                    'umum' => $rec['umum'],
                    'askes' => $rec['askes'],
                    'jps' => $rec['jps'],
                    'tgl_kadaluarsa' => $this->formatDateDb($rec['tgl_kadaluarsa_baru'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function cariDistribusiObatApotik(
    $rows, $offset
    ) {

        $query = "SELECT a.id_distribusi_obat, a.id_obat, b.kode_obat, b.nama_obat, a.stock, a.tgl_kadaluarsa, a.id_ruang_asal, date(a.date_update) as tgl_kirim
                  FROM rm_distribusi_obat a, rm_obat b
                  WHERE b.id_obat=a.id_obat and a.status='0' and a.id_ruang_tujuan='" . $_SESSION['level'] . "' AND a.del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
		if($rec['id_ruang_asal']==18)
			$ruang_asal = 35;
		else
			 $ruang_asal = $rec['id_ruang_asal'];
                $arr[] = array(
                    'id_distribusi_obat' => $rec['id_distribusi_obat'],
                    'id_obat' => $rec['id_obat'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'jumlah_stock' => $rec['stock'],
                    'asal_ruang' => $this->getRuang($ruang_asal),
                    'tgl_kirim' => $this->formatDateDb($rec['tgl_kirim']),
                    'tgl_kadaluarsa' => $this->formatDateDb($rec['tgl_kadaluarsa'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function cariDistribusiBarang(
    $rows, $offset
    ) {

        $query = "SELECT a.id_distribusi_barang, a.id_barang, b.barang, a.jumlah_stock
                  FROM rm_distribusi_barang a, rm_barang b
                  WHERE b.id_barang=a.id_barang and a.status='0' and a.id_ruang='" . $_SESSION['level'] . "'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_distribusi_barang' => $rec['id_distribusi_barang'],
                    'id_obat' => $rec['id_barang'],
                    'nama_barang' => $rec['barang'],
                    'jumlah_stock' => $rec['jumlah_stock']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getDetailObat($id_faktur, $rows, $offset) {
        if ($id_faktur > 0) {
            $query = "SELECT a.id_pembelian_obat, b.kode_obat, b.nama_obat, c.penyimpanan, qty, retur, harga, diskon, pajak, tgl_kadaluarsa
                  FROM rm_pembelian_obat a, rm_obat b, rm_penyimpanan c
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan AND a.id_faktur='" . $id_faktur . "' and a.del_flag<>'1'";

            $result = $this->runQuery($query);
            $jmlData = mysql_num_rows($result);

            $query = "SELECT a.id_pembelian_obat, a.id_obat, b.kode_obat, b.nama_obat, c.penyimpanan, qty, retur, harga, diskon, pajak, tgl_kadaluarsa
                  FROM rm_pembelian_obat a, rm_obat b, rm_penyimpanan c
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan AND a.id_faktur='" . $id_faktur . "' and a.del_flag<>'1' 
                  limit " . $offset . "," . $rows;

            $result = $this->runQuery($query);
            $jmlTarif = 0;
            $cek = 0;
            if ($jmlData > 0) {
                while ($rec = mysql_fetch_array($result)) {
                    $arr[] = array(
                        'id_pembelian_obat' => $rec['id_pembelian_obat'],
                        'id_obat' => $rec['id_obat'],
                        'kode_obat' => $rec['kode_obat'],
                        'nama_obat' => $rec['nama_obat'],
                        'penyimpanan' => $rec['penyimpanan'],
                        'qty' => $rec['qty'],
                        'retur' => $rec['retur'],
                        'harga' => $rec['harga'],
                        'pajak' => $rec['pajak'],
                        'diskon' => $rec['diskon'] / 100 * ($rec['harga'] * ($rec['qty'] - $rec['retur'])),
                        'tgl_kadaluarsa' => $this->formatDateDb($rec['tgl_kadaluarsa'])
                    );
                    $cek = (($rec['qty'] - $rec['retur']) * $rec['harga']);
                    $total = ( $cek - ($cek * ($rec['diskon'] / 100))) + $rec['pajak'];
                    $jmlTarif += $total;
                }
                return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"penyimpanan":"Total","harga":' . $jmlTarif . '}]}';
            }
        } else {
            return '{"total":"0", "rows":[], "footer":[]}';
        }
    }

    public function getFaktur($nama, $kode, $supplier, $start, $end, $rows, $offset) {
        $kondisi = "";
        if ($supplier != '')
            $kondisi .= " AND d.id_supplier=" . $supplier . " ";

        if ($nama != '')
            $kondisi .= " AND b.nama_obat like '" . $nama . "%' ";

        if ($kode != '')
            $kondisi .= " AND b.kode_obat like '" . $kode . "%' ";

        if ($start != '') {
            if ($end != '')
                $kondisi .= " and d.tgl_pembelian between '" . $this->formatDateDb($start) . "' and '" . $this->formatDateDb($end) . " 23:59:59'";
            else
                $kondisi .= " and d.tgl_pembelian between '" . $this->formatDateDb($start) . "' and '" . $this->formatDateDb($start) . " 23:59:59'";
        }

        $query = "SELECT a.id_faktur, d.no_faktur, d.tgl_pembelian, d.tgl_jatuh_tempo, d.id_supplier, e.supplier, b.kode_obat, b.nama_obat, c.penyimpanan, qty, retur, harga, diskon, pajak, tgl_kadaluarsa
                  FROM rm_pembelian_obat a, rm_obat b, rm_penyimpanan c, rm_faktur d, rm_supplier e
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan and d.id_faktur=a.id_faktur 
                  and d.status='0' and e.id_supplier=d.id_supplier and a.del_flag<>'1' and d.del_flag<>'1'" . $kondisi;
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $this->codeDate($rec['tgl_pembelian']),
                    'tgl_jatuh_tempo' => $this->codeDate($rec['tgl_jatuh_tempo']),
                    'batas_tempo' => $this->jmlHari(date('Y-m-d'), $rec['tgl_jatuh_tempo']),
                    'id_supplier' => $rec['id_supplier'],
                    'supplier' => $rec['supplier'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'qty' => $rec['qty'],
                    'retur' => $rec['retur'],
                    'harga' => $rec['harga'],
                    'pajak' => $rec['pajak'],
                    'diskon' => $rec['diskon'] / 100 * ($rec['harga'] * ($rec['qty'] - $rec['retur'])),
                    'tgl_kadaluarsa' => $this->formatDateDb($rec['tgl_kadaluarsa'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getFakturNotAssign($rows, $offset) {
        $query = "SELECT x.total, a.id_faktur, d.no_faktur, d.tgl_pembelian, d.id_supplier, e.supplier, b.kode_obat, b.nama_obat, c.penyimpanan, 
                  a.tgl_kadaluarsa, a.qty, a.retur, a.harga, a.diskon, a.pajak FROM rm_pembelian_obat a LEFT JOIN (SELECT SUM(ROUND(((qty*harga) - ((qty*harga) * 
                  (diskon/100))) + pajak,2)) as total, id_faktur FROM rm_pembelian_obat WHERE del_flag<>1 GROUP BY id_faktur) x ON 
                  (a.id_faktur=x.id_faktur), rm_obat b, rm_penyimpanan c, rm_faktur d, rm_supplier e WHERE b.id_obat = a.id_obat AND 
                  c.id_penyimpanan=a.id_penyimpanan and d.id_faktur=a.id_faktur and d.status_assign='0' and e.id_supplier=d.id_supplier 
                  and a.del_flag<>'1' and d.del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'id_supplier' => $rec['id_supplier'],
                    'supplier' => $rec['supplier'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'total' => $rec['total'],
                    'qty' => $rec['qty'],
                    'retur' => $rec['retur'],
                    'harga' => $rec['harga'],
                    'pajak' => $rec['pajak'],
                    'diskon' => $rec['diskon'] / 100 * (($rec['qty'] - $rec['retur']) * $rec['harga']),
                    'tgl_kadaluarsa' => $this->formatDateDb($rec['tgl_kadaluarsa'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getFakturBayar($rows, $offset) {
        $query = "SELECT a.id_pembayaran_faktur, a.id_faktur, b.no_faktur, c.id_supplier, c.supplier, a.bayar, b.tgl_pembelian, a.status
                  FROM rm_pembayaran_faktur a, rm_faktur b, rm_supplier c
                  WHERE a.del_flag<>'1' AND b.id_faktur=a.id_faktur AND c.id_supplier=b.id_supplier 
                  AND a.status='0'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $bayar = 0;
                $jmlBiaya = 0;
                $jmlPajak = 0;
                $jmlDiskon = 0;
                $q_beli = "select * from rm_pembelian_obat where id_faktur='" . $rec['id_faktur'] . "' and del_flag<>'1'";
                $r_beli = $this->runQuery($q_beli);

                if (@mysql_num_rows($r_beli) > 0) {
                    while ($rec_beli = mysql_fetch_array($r_beli)) {
                        $jmlBiaya += ( ($rec_beli['qty'] - $rec_beli['retur']) * $rec_beli['harga']);
                        $jmlPajak += $rec_beli['pajak'];
                        $jmlDiskon += $rec_beli['diskon'] / 100 * (($rec_beli['qty'] - $rec_beli['retur']) * $rec_beli['harga']);
                    }

                    $q_bayar = "select * from rm_pembayaran_faktur where id_faktur='" . $rec['id_faktur'] . "' and status='1'";
                    $r_bayar = $this->runQuery($q_bayar);

                    $i = 1;
                    if (@mysql_num_rows($r_bayar) > 0) {
                        while ($data = @mysql_fetch_array($r_bayar)) {
                            $bayar += $data['bayar'];
                            $i++;
                        }
                    }
                    $total = ($jmlBiaya + $jmlPajak - $jmlDiskon);
                }
                $arr[] = array(
                    'id_pembayaran_faktur' => $rec['id_pembayaran_faktur'],
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'id_supplier' => $rec['id_supplier'],
                    'supplier' => $rec['supplier'],
                    'total' => $total,
                    'sisa' => $total - $bayar,
                    'bayar' => $rec['bayar'],
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getFakturBayarKeuangan($rows, $offset) {
        $query = "SELECT a.id_pembayaran_faktur, a.id_faktur, b.no_faktur, c.id_supplier, c.supplier, a.bayar, b.tgl_pembelian, a.status
                  FROM rm_pembayaran_faktur a, rm_faktur b, rm_supplier c
                  WHERE a.del_flag<>'1' AND b.id_faktur=a.id_faktur AND c.id_supplier=b.id_supplier and a.status='0' ";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $bayar = 0;
                $jmlBiaya = 0;
                $jmlPajak = 0;
                $jmlDiskon = 0;
                $q_beli = "select * from rm_pembelian_obat where id_faktur='" . $rec['id_faktur'] . "'";
                $r_beli = $this->runQuery($q_beli);

                if (@mysql_num_rows($r_beli) > 0) {
                    while ($rec_beli = mysql_fetch_array($r_beli)) {
                        $jmlBiaya += ( ($rec_beli['qty'] - $rec_beli['retur']) * $rec_beli['harga']);
                        $jmlPajak += $rec_beli['pajak'];
                        $jmlDiskon += $rec_beli['diskon'];
                    }

                    $q_bayar = "select * from rm_pembayaran_faktur where id_faktur='" . $rec['id_faktur'] . "' and status='1'";
                    $r_bayar = $this->runQuery($q_bayar);

                    $i = 1;
                    if (@mysql_num_rows($r_bayar) > 0) {
                        while ($data = @mysql_fetch_array($r_bayar)) {
                            $bayar += $data['bayar'];
                            $i++;
                        }
                    }
                    $total = ($jmlBiaya + $jmlPajak - $jmlDiskon);
                }
                $arr[] = array(
                    'id_pembayaran_faktur' => $rec['id_pembayaran_faktur'],
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'id_supplier' => $rec['id_supplier'],
                    'supplier' => $rec['supplier'],
                    'total' => $total,
                    'sisa' => $total - $bayar,
                    'bayar' => $rec['bayar'],
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getListPembayaranFaktur($id_faktur, $rows, $offset) {
        $query = "SELECT a.id_pembayaran_faktur, a.bayar_ke, date(a.tgl_pembayaran) as tgl_pembayaran, a.id_faktur, b.no_faktur, c.id_supplier, c.supplier, a.bayar, b.tgl_pembelian, a.status
                  FROM rm_pembayaran_faktur a, rm_faktur b, rm_supplier c
                  WHERE a.del_flag<>'1' AND b.id_faktur=a.id_faktur AND c.id_supplier=b.id_supplier and a.status='1'
                  and a.id_faktur='" . $id_faktur . "' ";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $bayar = 0;
                $jmlBiaya = 0;
                $jmlPajak = 0;
                $jmlDiskon = 0;
                $q_beli = "select * from rm_pembelian_obat where id_faktur='" . $rec['id_faktur'] . "'";
                $r_beli = $this->runQuery($q_beli);

                if (@mysql_num_rows($r_beli) > 0) {
                    while ($rec_beli = mysql_fetch_array($r_beli)) {
                        $jmlBiaya += ( ($rec_beli['qty'] - $rec_beli['retur']) * $rec_beli['harga']);
                        $jmlPajak += $rec_beli['pajak'];
                        $jmlDiskon += $rec_beli['diskon'];
                    }

                    $q_bayar = "select * from rm_pembayaran_faktur where id_faktur='" . $rec['id_faktur'] . "' and status='1'";
                    $r_bayar = $this->runQuery($q_bayar);

                    $i = 1;
                    if (@mysql_num_rows($r_bayar) > 0) {
                        while ($data = @mysql_fetch_array($r_bayar)) {
                            $bayar += $data['bayar'];
                            $i++;
                        }
                    }
                    $total = ($jmlBiaya + $jmlPajak - $jmlDiskon);
                }
                $arr[] = array(
                    'id_pembayaran_faktur' => $rec['id_pembayaran_faktur'],
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'bayarKe' => $rec['bayar_ke'],
                    'supplier' => $rec['supplier'],
                    'tgl_pembayaran' => $rec['tgl_pembayaran'],
                    'bayar' => $rec['bayar'],
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getFakturId($no_faktur, $rows, $offset) {
        $query = "SELECT a.id_faktur, d.no_faktur, d.tgl_pembelian, d.id_supplier, e.supplier, b.kode_obat, b.nama_obat, c.penyimpanan, qty, retur, harga, diskon, pajak, tgl_kadaluarsa
                  FROM rm_pembelian_obat a, rm_obat b, rm_penyimpanan c, rm_faktur d, rm_supplier e
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan and d.id_faktur=a.id_faktur 
                  and e.id_supplier=d.id_supplier and d.no_faktur='" . $no_faktur . "' and d.status!='1' and a.del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        $jmlPajak = 0;
        $jmlDiskon = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'id_supplier' => $rec['id_supplier'],
                    'supplier' => $rec['supplier'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'qty' => $rec['qty'],
                    'retur' => $rec['retur'],
                    'harga' => $rec['harga'],
                    'pajak' => $rec['pajak'],
                    'diskon' => $rec['diskon'] / 100 * ($rec['harga'] * ($rec['qty'] - $rec['retur'])),
                    'tgl_kadaluarsa' => $rec['tgl_kadaluarsa']
                );
                $jmlBiaya += ( ($rec['qty'] - $rec['retur']) * $rec['harga']);
                $jmlPajak += $rec['pajak'];
                $jmlDiskon += $rec['diskon'] / 100 * ($rec['harga'] * ($rec['qty'] - $rec['retur']));
                $q_bayar = "select * from rm_pembayaran_faktur where id_faktur='" . $rec['id_faktur'] . "'";
                $r_bayar = $this->runQuery($q_bayar);

                $i = 1;
                $terbayar = 0;
                if (@mysql_num_rows($r_bayar) > 0) {
                    while ($data = @mysql_fetch_array($r_bayar)) {
                        $terbayar += $data['bayar'];
                        $i++;
                    }
                }
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"penyimpanan":"Total","harga":' . $jmlBiaya . ',"pajak":' . $jmlPajak . ',"diskon":' . $jmlDiskon . '},
                   {"penyimpanan":"Total Pembayaran","harga":' . ($jmlBiaya + $jmlPajak - $jmlDiskon) . '},
                   {"penyimpanan":"Terbayar","harga":' . ($terbayar) . '},
                   {"penyimpanan":"Kurang Bayar","harga":' . ($jmlBiaya + $jmlPajak - $jmlDiskon - $terbayar) . '}]}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getFakturKeuangan($rows, $offset) {
        $query = "SELECT d.id_faktur, d.no_faktur, d.tgl_pembelian, d.status, e.supplier, b.kode_obat, b.nama_obat, c.penyimpanan, qty, retur, harga, diskon, pajak, tgl_kadaluarsa
                  FROM rm_pembelian_obat a, rm_obat b, rm_penyimpanan c, rm_faktur d, rm_supplier e
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan and d.id_faktur=a.id_faktur 
                  and e.id_supplier=d.id_supplier and d.status=0 order by d.status asc, d.id_faktur desc ";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'supplier' => $rec['supplier'],
                    'status' => $rec['status'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'qty' => $rec['qty'],
                    'retur' => $rec['retur'],
                    'harga' => $rec['harga'],
                    'pajak' => $rec['pajak'],
                    'diskon' => $rec['diskon'],
                    'tgl_kadaluarsa' => $rec['tgl_kadaluarsa']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getFakturFarmasi($rows, $offset) {
        $query = "SELECT d.id_faktur, d.no_faktur, d.tgl_pembelian, d.status, e.supplier, b.kode_obat, b.nama_obat, c.penyimpanan, qty, retur, harga, diskon, pajak, tgl_kadaluarsa
                  FROM rm_pembelian_obat a, rm_obat b, rm_penyimpanan c, rm_faktur d, rm_supplier e
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan and d.id_faktur=a.id_faktur 
                  and e.id_supplier=d.id_supplier and d.status='1' order by d.status asc, d.id_faktur desc ";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'supplier' => $rec['supplier'],
                    'status' => $rec['status'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'penyimpanan' => $rec['penyimpanan'],
                    'qty' => $rec['qty'],
                    'retur' => $rec['retur'],
                    'harga' => $rec['harga'],
                    'pajak' => $rec['pajak'],
                    'diskon' => $rec['diskon'],
                    'tgl_kadaluarsa' => $rec['tgl_kadaluarsa']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getListHargaObat($nama_obat, $rows, $offset) {
        $query = "SELECT b.id_obat, b.kode_obat, b.nama_obat, a.hpp, a.umum, a.askes, a.jps
                  FROM rm_tarif_obat a, rm_obat b
                  WHERE a.del_flag<>'1' AND b.id_obat=a.id_obat AND b.nama_obat LIKE '%" . $nama_obat . "%' order by b.nama_obat ";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_obat' => $rec['id_obat'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'hpp' => $rec['hpp'],
                    'umum' => $rec['umum'],
                    'askes' => $rec['askes'],
                    'jps' => $rec['jps']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getMasterObat($nama_obat, $k_obat, $rows, $offset) {
        $kondisi = "";
        if ($k_obat != "")
            $kondisi .= " and kode_obat like '" . $k_obat . "%' ";
        if ($nama_obat != "")
            $kondisi .= " and nama_obat like '%" . $nama_obat . "%' ";
        $query = "SELECT * FROM rm_obat where del_flag<>'1' " . $kondisi . " order by nama_obat ";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_obat' => $rec['id_obat'],
                    'kode_obat' => $rec['kode_obat'],
                    'nama_obat' => $rec['nama_obat']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getDetailMasterObat($id_obat) {
        $query = "select * from rm_obat where id_obat='" . $id_obat . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "id_obat" => @mysql_result($result, 0, "id_obat"),
                "kode_obat" => @mysql_result($result, 0, "kode_obat"),
                "obat" => @mysql_result($result, 0, "nama_obat")
            );

            return $this->jEncode($return);
        }
    }

    public function getListFaktur($supplier, $tgl_beli, $rows, $offset) {
        $kondisi = "";

        if ($supplier != "")
            $kondisi .= " and a.id_supplier='" . $supplier . "'";
        if ($tgl_beli != "")
            $kondisi .= " and a.tgl_pembelian='" . $tgl_beli . "'";

        $query = "SELECT a.id_faktur, a.no_faktur, b.supplier, a.tgl_pembelian, a.status
                  FROM rm_faktur a, rm_supplier b WHERE a.del_flag<>'1' and a.status='0'
                  AND b.id_supplier=a.id_supplier " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlBiaya = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_faktur' => $rec['id_faktur'],
                    'no_faktur' => $rec['no_faktur'],
                    'tgl_pembelian' => $rec['tgl_pembelian'],
                    'supplier' => $rec['supplier'],
                    'status' => $rec['status']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function getDetailFaktur($id_faktur) {
        $query = "select * from rm_faktur where id_faktur='" . $id_faktur . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "id_faktur" => $id_faktur,
                "no_faktur" => @mysql_result($result, 0, "no_faktur"),
                "supplier" => @mysql_result($result, 0, "id_supplier"),
                "tgl_pembelian" => @mysql_result($result, 0, "tgl_pembelian")
            );

            return $this->jEncode($return);
        }
    }

    public function getDetailDistObat($id_distribusi_obat) {
        $query = "SELECT a.id_distribusi_obat, a.id_obat, b.kode_obat, b.nama_obat, a.stock, a.tgl_kadaluarsa
                  FROM rm_distribusi_obat a, rm_obat b
                  WHERE b.id_obat=a.id_obat and a.id_distribusi_obat='" . $id_distribusi_obat . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $q_penyimpanan = "select id_penyimpanan from rm_stock_obat_apotik where id_obat='" . @mysql_result($result, 0, "id_obat") . "'";
            $r_penyimpanan = $this->runQuery($q_penyimpanan);
            $id_penyimpanan = @mysql_result($r_penyimpanan, 0, "id_penyimpanan");
            $return = array(
                "id_distribusi_obat" => $id_distribusi_obat,
                "id_obat" => @mysql_result($result, 0, "id_obat"),
                "nama_obat" => @mysql_result($result, 0, "nama_obat"),
                "jumlah" => @mysql_result($result, 0, "stock"),
                "penyimpanan" => $id_penyimpanan,
                "tgl_kadaluarsa" => $this->formatDateDb(@mysql_result($result, 0, "tgl_kadaluarsa"))
            );

            return $this->jEncode($return);
        }
    }

    public function getDetailDistBarang($id_distribusi_barang) {
        $query = "SELECT a.id_distribusi_barang, a.id_barang, b.barang, a.jumlah_stock
                  FROM rm_distribusi_barang a, rm_barang b
                  WHERE b.id_barang=a.id_barang and a.id_distribusi_barang='" . $id_distribusi_barang . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "id_distribusi_barang" => $id_distribusi_barang,
                "id_barang" => @mysql_result($result, 0, "id_barang"),
                "nama_barang" => @mysql_result($result, 0, "barang"),
                "jumlah" => @mysql_result($result, 0, "jumlah_stock")
            );

            return $this->jEncode($return);
        }
    }

    public function getBayarFaktur($id_faktur) {
        $bayar = 0;
        $jmlBiaya = 0;
        $jmlPajak = 0;
        $jmlDiskon = 0;
        $query = "select * from rm_pembelian_obat where id_faktur='" . $id_faktur . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $jmlBiaya += ( ($rec['qty'] - $rec['retur']) * $rec['harga']);
                $jmlPajak += $rec['pajak'];
                $jmlDiskon += $rec['diskon'] / 100 * (($rec['qty'] - $rec['retur']) * $rec['harga']);
            }

            $q_bayar = "select * from rm_pembayaran_faktur where id_faktur='" . $id_faktur . "'";
            $r_bayar = $this->runQuery($q_bayar);

            $i = 1;
            if (@mysql_num_rows($r_bayar) > 0) {
                while ($data = @mysql_fetch_array($r_bayar)) {
                    $bayar += $data['bayar'];
                    $i++;
                }
            }
            $total = ($jmlBiaya + $jmlPajak - $jmlDiskon);
            $return = array(
                "bayarKe" => $i,
                "total_bayar" => "Rp. " . number_format($total, 1, ",", "."),
                "terbayar" => "Rp. " . number_format($bayar, 1, ",", "."),
                "sisa_bayar" => "Rp. " . number_format(($total - $bayar), 1, ",", "."),
                "kurang_bayar" => ($total - $bayar)
            );

            return $this->jEncode($return);
        }
    }

    public function generateHargaObat($id_pembelian_obat) {
        $query = "select * from rm_pembelian_obat where id_pembelian_obat='" . $id_pembelian_obat . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            $total = @mysql_result($result, 0, 'harga') * (@mysql_result($result, 0, 'qty') - @mysql_result($result, 0, 'retur'));
            $diskon = (@mysql_result($result, 0, 'diskon') / 100) * $total;
            $tagihan = ($total - $diskon + @mysql_result($result, 0, 'pajak')) / @mysql_result($result, 0, 'qty');
            $hpp = $tagihan;
            $standart = $hpp + ($hpp * 0.2);

            $q_harga = "SELECT a.id_obat, a.nama_obat, b.hpp, b.umum, b.askes, b.jps
                        FROM rm_obat a, rm_tarif_obat b
                        WHERE b.id_obat=a.id_obat AND a.id_obat='" . @mysql_result($result, 0, 'id_obat') . "'";
            $r_harga = $this->runQuery($q_harga);

            $return = array(
                "id_obat" => @mysql_result($result, 0, 'id_obat'),
                "nama" => @mysql_result($r_harga, 0, 'nama_obat'),
                "hpp_asli" => "Rp. " . number_format(@mysql_result($r_harga, 0, 'hpp'), 1, ",", "."),
                "umum_asli" => "Rp. " . number_format(@mysql_result($r_harga, 0, 'umum'), 1, ",", "."),
                "askes_asli" => "Rp. " . number_format(@mysql_result($r_harga, 0, 'askes'), 1, ",", "."),
                "jps_asli" => "Rp. " . number_format(@mysql_result($r_harga, 0, 'jps'), 1, ",", "."),
                "hpp" => $hpp,
                "umum" => $standart,
                "askes" => $standart,
                "jps" => $standart
            );

            return $this->jEncode($return);
        }
    }

    public function getIdFaktur($no_faktur) {
        $query = "select id_faktur from rm_faktur where no_faktur='" . $no_faktur . "' and del_flag<>'1'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            $return = array(
                "id_faktur" => @mysql_result($result, 0, 'id_faktur')
            );

            return $this->jEncode($return);
        }
    }

    public function getDetailObatFaktur($id_pembelian_obat) {
        $query = "SELECT a.id_obat, b.nama_obat, c.id_penyimpanan, c.penyimpanan, qty, retur, harga, diskon, pajak, tgl_kadaluarsa
                  FROM rm_pembelian_obat a, rm_obat b, rm_penyimpanan c
                  WHERE b.id_obat = a.id_obat AND c.id_penyimpanan=a.id_penyimpanan AND id_pembelian_obat='" . $id_pembelian_obat . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "id_pembelian_obat" => $id_pembelian_obat,
                "nama_obatBeli" => @mysql_result($result, 0, "nama_obat"),
                "nama_obatBeliId" => @mysql_result($result, 0, "id_obat"),
                "penyimpanan" => @mysql_result($result, 0, "id_penyimpanan"),
                "jumlah" => @mysql_result($result, 0, "qty"),
                "retur" => @mysql_result($result, 0, "retur"),
                "harga" => @mysql_result($result, 0, "harga"),
                "diskon" => @mysql_result($result, 0, "diskon"),
                "pajak" => @mysql_result($result, 0, "pajak"),
                "tgl_kadaluarsa" => @mysql_result($result, 0, "tgl_kadaluarsa")
            );
            return $this->jEncode($return);
        }
    }

    public function updateHargaObat($id_obat, $hpp, $umum, $askes, $jps) {
        $query = "update rm_tarif_obat set 
                      hpp='" . $hpp . "', 
                      umum='" . $umum . "',
                      askes='" . $askes . "',
                      jps='" . $jps . "'
                  where 
                      id_obat='" . $id_obat . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getLaporanPosisiStock(
    $id_obat, $id_obatS, $ruang, $startDate, $endDate
    ) {
        $kondisi = "";
        $obat = "";
        $tanggal = "";
        $nm_ruang = "";
        if ($id_obat != "") {
            if ($id_obatS)
                $obat = "Kode Obat <b>" . $id_obat . "</b> s/d <b>" . $id_obatS . "</b><br>";
            else
                $obat = "Kode Obat <b>" . $id_obat . "</b><br>";
        }
        if ($startDate != "") {
            if ($endDate != "")
                $tanggal = "Tanggal <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b> s/d <b>" . $this->codeDate($this->formatDateDb($endDate)) . "</b><br>";
            else
                $tanggal = "Tanggal <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b><br>";
        }
        if ($ruang != "")
            $nm_ruang = "Ruang: <b>" . $this->getRuang($ruang) . "</b><br>";

        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21' colspan='10'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21' colspan='10'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21' colspan='10'><br><u><b>Laporan Posisi Stock Obat</b></u><br><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21' colspan='10'>" . $obat . $tanggal . $nm_ruang . "</td>
                            </tr>";
        $html .="</table>";

        if ($id_obat != "") {
            if ($id_obatS != "")
                $kondisi .= " and kode_obat between '" . $id_obat . "' and '" . $id_obatS . "'";
            else
                $kondisi .= " and kode_obat='" . $id_obat . "'";
        }

        $query = "select * from rm_obat where del_flag<>'1'" . $kondisi . " order by kode_obat";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='2%' align='center' class='headerTagihan' rowspan='2'>No</td>";
            $html .= "<td width='8%' align='center' class='headerTagihan' rowspan='2'>Kode Obat</td>";
            $html .= "<td width='30%' align='center' class='headerTagihan' rowspan='2'>Nama Obat</td>";
            $html .= "<td width='15%' align='center' class='headerTagihan' rowspan='2'>Awal</td>";
            $html .= "<td class='headerTagihan' align='center' colspan='3'>Mutasi</td>";
            $html .= "<td width='15%' align='center' class='headerTagihan' rowspan='2'>Akhir</td>";
            $html .= "<td width='15%' align='center' class='headerTagihan' rowspan='2'>HPP</td>";
            $html .= "<td width='15%' align='center' class='headerTagihan' rowspan='2'>Total</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width='10%' align='center' class='headerTagihan'>Debit</td>";
            $html .= "<td width='10%' align='center' class='headerTagihan'>Kredit</td>";
            $html .= "<td width='10%' align='center' class='headerTagihan'>Deviasi</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $i = 1;
            if ($ruang == "37") {
                $kondisiTgl = "";
                $kondisiTgl2 = "";
                if ($startDate != "") {
                    if ($endDate != "") {
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    } else {
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                    }
                    $kondisiTgl50 .= " and tgl_pemakaian < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl0 .= " and date_update < '" . $this->formatDateDb($startDate) . "'";
                }
                //HPP
                $q_hpp = "SELECT a.id_obat,ifnull(round(b.hpp),0) as hpp from rm_obat a left join rm_tarif_obat b ON (a.id_obat=b.id_obat) 
                          where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_hpp = $this->runQuery($q_hpp);
                while ($d_hpp = @mysql_fetch_array($r_hpp)) {
                    $arrHpp[] = array('id_obat' => $d_hpp['id_obat'], 'hpp' => $d_hpp['hpp']);
                }
                //END HPP

                $q_defiasi = "select a.id_obat, ifnull(b.jumlah,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(jumlah) AS 
                            jumlah FROM rm_balance_gudang WHERE del_flag<>'1'" . $kondisiTgl5 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi = $this->runQuery($q_defiasi);
                while ($defiasi = @mysql_fetch_array($r_defiasi)) {
                    $arrDefiasi[] = array('id_obat' => $defiasi['id_obat'], 'jumlah' => $defiasi['jumlah']);
                }

                $q_defiasi_b = "select a.id_obat, ifnull(b.jumlah,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(jumlah) AS 
                            jumlah FROM rm_balance_gudang WHERE del_flag<>'1'" . $kondisiTgl50 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi_b = $this->runQuery($q_defiasi_b);
                while ($defiasi_b = @mysql_fetch_array($r_defiasi_b)) {
                    $arrDefiasi_b[] = array('id_obat' => $defiasi_b['id_obat'], 'jumlah' => $defiasi_b['jumlah']);
                }

                $q_keluar = "select a.id_obat, ifnull(b.jmlKeluar,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlKeluar FROM rm_distribusi_obat WHERE del_flag<>'1' AND id_ruang_asal=18" . $kondisiTgl . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar = $this->runQuery($q_keluar);
                while ($keluar = @mysql_fetch_array($r_keluar)) {
                    $arrKeluar[] = array('id_obat' => $keluar['id_obat'], 'jumlah' => $keluar['jumlah']);
                }

                $q_jml_masuk = "select a.id_obat, ifnull(b.jmlMasuk,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(qty) AS 
                            jmlMasuk FROM rm_penambahan_stock WHERE del_flag<>'1'" . $kondisiTgl . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_jml_masuk = $this->runQuery($q_jml_masuk);
                while ($masuk = @mysql_fetch_array($r_jml_masuk)) {
                    $arrMasuk[] = array('id_obat' => $masuk['id_obat'], 'jumlah' => $masuk['jumlah']);
                }

                $q_keluar_b = "select a.id_obat, ifnull(b.jmlKeluar,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlKeluar FROM rm_distribusi_obat WHERE del_flag<>'1' AND id_ruang_asal=18" . $kondisiTgl0 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar_b = $this->runQuery($q_keluar_b);
                while ($keluar_b = @mysql_fetch_array($r_keluar_b)) {
                    $arrKeluar_b[] = array('id_obat' => $keluar_b['id_obat'], 'jumlah' => $keluar_b['jumlah']);
                }

                $q_jml_masuk_b = "select a.id_obat, ifnull(b.jmlMasuk,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(qty) AS 
                            jmlMasuk FROM rm_penambahan_stock WHERE del_flag<>'1'" . $kondisiTgl0 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_jml_masuk_b = $this->runQuery($q_jml_masuk_b);
                while ($masuk_b = @mysql_fetch_array($r_jml_masuk_b)) {
                    $arrMasuk_b[] = array('id_obat' => $masuk_b['id_obat'], 'jumlah' => $masuk_b['jumlah']);
                }

                $g = 0;
                $tot = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $defia_b = $arrDefiasi_b[$g];
                    $defia = $arrDefiasi[$g];
                    $d_masuk = $arrMasuk[$g];
                    $d_masuk_b = $arrMasuk_b[$g];
                    $d_keluar = $arrKeluar[$g];
                    $d_keluar_b = $arrKeluar_b[$g];
                    $d_hpp = $arrHpp[$g];
                    $stock = (($d_masuk_b['jumlah'] - $d_keluar_b['jumlah']) + $defia_b['jumlah']);
                    $sa = $stock + $d_masuk['jumlah'] - $d_keluar['jumlah'] + $defia['jumlah'];
                    $jum = $d_hpp['hpp'] * $sa;
                    $html .= "<tr>";
                    $html .= "<td>" . $i . "</td>";
                    $html .= "<td>" . $data['kode_obat'] . "</td>";
                    $html .= "<td>" . $data['nama_obat'] . "</td>";
                    $html .= "<td align='right'>" . number_format($stock, 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($d_masuk['jumlah'], 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($d_keluar['jumlah'], 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($defia['jumlah'], 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($sa, 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($d_hpp['hpp'], 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($jum, 0, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $i++;
                    $g++;
                    $tot += $jum;
                }
                $html .= "<tr>";
                $html .= "<td class='subtotal' colspan='9'>TOTAL</td>";
                $html .= "<td align='right'>" . number_format($tot, 0, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $kondisiTgl = "";
                $kondisiTgl2 = "";
                $kondisiTgl3 = "";
                $kondisiTgl4 = "";
                if ($startDate != "") {
                    if ($endDate != "") {
                        $kondisiTgl4 .= " and tgl_retur between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl3 .= " and tgl_penjualan between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl2 .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    } else {
                        $kondisiTgl4 .= " and tgl_retur between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl3 .= " and tgl_penjualan between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl2 .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                    }
                    $kondisiTgl40 .= " and tgl_retur < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl30 .= " and tgl_penjualan < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl0 .= " and date_update < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl20 .= " and date_update < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl50 .= " and tgl_pemakaian < '" . $this->formatDateDb($startDate) . "'";
                }
                // PENAMBAHAN STOCK AWAL
                // MENCARI RETUR AWAL
                $jmlRetur_b = 0;
                $q_retur_b = "SELECT a.id_obat, ifnull(b.jumlah,0) as jmlRetur from rm_obat a LEFT JOIN (select id_obat, sum(jumlah) as jumlah from rm_retur_penjualan_obat d 
                            join rm_faktur_penjualan e ON (d.id_faktur_penjualan=e.id_faktur_penjualan and e.id_ruang=" . $ruang . " and e.del_flag<>1) 
                            WHERE d.del_flag<>'1' " . $kondisiTgl40 . " GROUP BY d.id_obat) b ON (b.id_obat = a.id_obat) 
                            where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_retur_b = $this->runQuery($q_retur_b);
                while ($retur_b = @mysql_fetch_array($r_retur_b)) {
                    $arrRetur_b[] = array('id_obat' => $retur_b['id_obat'], 'jumlah' => $retur_b['jmlRetur']);
                }
                // END RETUR
                // DISTRIBUSI AWAL
                $q_masuk_b = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_tujuan=" . $ruang . " and del_flag<>'1' and `status`=1  " . $kondisiTgl20 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_masuk_b = $this->runQuery($q_masuk_b);
                $jmlMasuk_b = 0;
                while ($masuk_b = @mysql_fetch_array($r_masuk_b)) {
                    $arrMasuk_b[] = array('id_obat' => $masuk_b['id_obat'], 'jumlah' => $masuk_b['jmlMasuk']);
                }
                // END
                // MENCARI PENGELUARAN AWAL
                $q_keluar_b = "SELECT a.id_obat,ifnull(b.jml,0) as jumlah from rm_obat a left join (SELECT id_obat, SUM(qty) as jml FROM rm_penjualan_obat a
                              JOIN rm_faktur_penjualan b ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1
                              and b.`status`<>0 " . $kondisiTgl30 . ") WHERE a.del_flag<>1 GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) 
                              where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar_b = $this->runQuery($q_keluar_b);
                while ($jual_b = @mysql_fetch_array($r_keluar_b)) {
                    $arrJual_b[] = array('id_obat' => $jual_b['id_obat'], 'jumlah' => $jual_b['jumlah']);
                }

                //RACIKAN AWAL
                $q_racikan_b = "SELECT a.id_obat,ifnull(b.jml,0) as jmlKeluar from rm_obat a left join (SELECT a.id_obat, SUM(a.qty) AS jml FROM 
                                rm_detail_racikan a JOIN (select id_racikan from rm_racikan a JOIN rm_faktur_penjualan b 
                               ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1 and b.`status`<>0 
                               " . $kondisiTgl30 . ") where a.del_flag<>1) b ON (a.id_racikan=b.id_racikan and a.del_flag<>1) 
                               GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_racikan_b = $this->runQuery($q_racikan_b);
                while ($racik_b = @mysql_fetch_array($r_racikan_b)) {
                    $arrRacik_b[] = array('id_obat' => $racik_b['id_obat'], 'jumlah' => $racik_b['jmlKeluar']);
                }
                
                // DISTRIBUSI KELUAR AWAL
                $q_dist_kel = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_asal=" . $ruang . " and del_flag<>'1' " . $kondisiTgl20 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_dist_kel = $this->runQuery($q_dist_kel);
                while ($dist_kel = @mysql_fetch_array($r_dist_kel)) {
                    $arrDistKel_b[] = array('id_obat' => $dist_kel['id_obat'], 'jumlah' => $dist_kel['jmlMasuk']);
                }
                // END
                
                //AKHIR PENGELUARAN AWAL
                //MENCARI DEFIASI AWAL
                $q_defiasi_b = "SELECT a.id_obat,ifnull(b.jumlah,0) as jumlah from rm_obat a left join (select sum(jumlah) as jumlah, id_obat from rm_obat_balance where 
                               id_ruang=" . $ruang . " and del_flag<>'1' " . $kondisiTgl50 . " GROUP BY id_obat) b ON (a.id_obat=b.id_obat) 
                               where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi_b = $this->runQuery($q_defiasi_b);

                $de = 0;
                $total_b = 0;
                while ($defiasi_b = @mysql_fetch_array($r_defiasi_b)) {
                    if ($de < @mysql_num_rows($r_defiasi_b)) {
                        $aR_b = $arrRetur_b[$de];
                        $aM_b = $arrMasuk_b[$de];
                        $aK_b = $arrDistKel_b[$de];
                        $aJ_b = $arrJual_b[$de];
                        $aC_b = $arrRacik_b[$de];
                        $total_b = $defiasi_b['jumlah'] + (($aR_b['jumlah'] + $aM_b['jumlah']) - ($aJ_b['jumlah'] + $aC_b['jumlah'] + $aK_b['jumlah']));
                        $arrSA_b[] = array('id_obat' => $defiasi_b['id_obat'], 'jumlah' => $total_b);
                    }
                    $de++;
                }

                ///////// END STOCK AWAL
                //MENCARI DINAMIS STOCK
                //MENCARI RETUR AKHIR
                $jmlRetur = 0;
                $q_retur = "SELECT a.id_obat, ifnull(b.jumlah,0) as jmlRetur from rm_obat a LEFT JOIN (select id_obat, sum(jumlah) as jumlah from rm_retur_penjualan_obat d 
                            join rm_faktur_penjualan e ON (d.id_faktur_penjualan=e.id_faktur_penjualan and e.id_ruang=" . $ruang . " and e.del_flag<>1) 
                            WHERE d.del_flag<>'1' " . $kondisiTgl4 . " GROUP BY d.id_obat) b ON (b.id_obat = a.id_obat) 
                            where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_retur = $this->runQuery($q_retur);
                while ($retur = @mysql_fetch_array($r_retur)) {
                    $arrRetur[] = array('id_obat' => $retur['id_obat'], 'jumlah' => $retur['jmlRetur']);
                }
                // END RETUR
                // DISTRIBUSI AKHIR
                $q_masuk = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_tujuan=" . $ruang . " and del_flag<>'1' and `status`=1  " . $kondisiTgl2 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_masuk = $this->runQuery($q_masuk);
                $jmlMasuk = 0;
                while ($masuk = @mysql_fetch_array($r_masuk)) {
                    $arrMasuk[] = array('id_obat' => $masuk['id_obat'], 'jumlah' => $masuk['jmlMasuk']);
                }
                // END
                // MENCARI PENGELUARAN AKHIR
                $q_keluar = "SELECT a.id_obat,ifnull(b.jml,0) as jumlah from rm_obat a left join (SELECT id_obat, SUM(qty) as jml FROM rm_penjualan_obat a
                              JOIN rm_faktur_penjualan b ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1
                              and b.`status`<>0 " . $kondisiTgl3 . ") WHERE a.del_flag<>1 GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) 
                              where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar = $this->runQuery($q_keluar);
                while ($jual = @mysql_fetch_array($r_keluar)) {
                    $arrJual[] = array('id_obat' => $jual['id_obat'], 'jumlah' => $jual['jumlah']);
                }

                //RACIKAN AKHIR
                $q_racikan = "SELECT a.id_obat,ifnull(b.jml,0) as jmlKeluar from rm_obat a left join (SELECT a.id_obat, SUM(a.qty) AS jml FROM 
                                rm_detail_racikan a JOIN (select id_racikan from rm_racikan a JOIN rm_faktur_penjualan b 
                               ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1 and b.`status`<>0 
                               " . $kondisiTgl3 . ") where a.del_flag<>1) b ON (a.id_racikan=b.id_racikan and a.del_flag<>1) 
                               GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_racikan = $this->runQuery($q_racikan);
                while ($racik = @mysql_fetch_array($r_racikan)) {
                    $arrRacik[] = array('id_obat' => $racik['id_obat'], 'jumlah' => $racik['jmlKeluar']);
                }
                
                // DISTRIBUSI KELUAR AKHIR
                $q_dist_keluar = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_asal=" . $ruang . " and del_flag<>'1' " . $kondisiTgl2 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_dist_keluar = $this->runQuery($q_dist_keluar);
                while ($distKeluar = @mysql_fetch_array($r_dist_keluar)) {
                    $arrDistKeluar[] = array('id_obat' => $distKeluar['id_obat'], 'jumlah' => $distKeluar['jmlMasuk']);
                }
                // END
                //AKHIR PENGELUARAN AKHIR
                //HPP
                $q_hpp = "SELECT a.id_obat,ifnull(round(b.hpp),0) as hpp from rm_obat a left join rm_tarif_obat b ON (a.id_obat=b.id_obat) 
                               where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_hpp = $this->runQuery($q_hpp);
                while ($d_hpp = @mysql_fetch_array($r_hpp)) {
                    $arrHpp[] = array('id_obat' => $d_hpp['id_obat'], 'hpp' => $d_hpp['hpp']);
                }
                //END HPP
                //MENCARI DEFIASI AKHIR
                $q_defiasi = "SELECT a.id_obat,ifnull(b.jumlah,0) as jumlah from rm_obat a left join (select sum(jumlah) as jumlah, id_obat from rm_obat_balance where 
                               id_ruang=" . $ruang . " and del_flag<>'1' " . $kondisiTgl5 . " GROUP BY id_obat) b ON (a.id_obat=b.id_obat) 
                               where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi = $this->runQuery($q_defiasi);

                while ($defiasi = @mysql_fetch_array($r_defiasi)) {
                    $total = 0;
                    $arrAkhir[] = array('id_obat' => $defiasi['id_obat'], 'jumlah' => $defiasi['jumlah']);
                }

                $z = 1;
                $i = 0;
                $tot = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $defia = $arrSA_b[$i];
                    $fRetur = $arrRetur[$i];
                    $fMasuk = $arrMasuk[$i];
                    $fDistKel = $arrDistKeluar[$i];
                    $fJual = $arrJual[$i];
                    $fRacik = $arrRacik[$i];
                    $debet = $fMasuk['jumlah'] + $fRetur['jumlah'];
                    $kredit = $fJual['jumlah'] + $fRacik['jumlah'] + $fDistKel['jumlah'];
                    $defiasi = $arrAkhir[$i];
                    $data_hpp = $arrHpp[$i];
                    $stock = $defia['jumlah'];
                    $sa = $stock + $debet - $kredit + $defiasi['jumlah'];
                    $jum = $data_hpp['hpp'] * $sa;
                    $html .= "<tr>";
                    $html .= "<td>" . $z . "</td>";
                    $html .= "<td>" . $data['kode_obat'] . "</td>";
                    $html .= "<td>" . $data['nama_obat'] . "</td>";
                    $html .= "<td align='right'>" . number_format($stock, 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($debet, 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($kredit, 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($defiasi['jumlah'], 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($sa, 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($data_hpp['hpp'], 0, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($jum, 0, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $z++;
                    $i++;
                    $tot += $jum;
                }
                $html .= "<tr>";
                $html .= "<td class='subtotal' colspan='9'>TOTAL</td>";
                $html .= "<td align='right'>" . number_format($tot, 0, ',', '.') . "</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html = "Data Tidak ditemukan.";
        }
        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

        public function setJumlahBalance($kd_obat, $ruang, $sistem, $real) {
        $q = "SELECT id_obat FROM rm_obat WHERE del_flag<>1 AND kode_obat='" . $kd_obat . "'";
        $r = $this->runQuery($q);
        $id_obat = @mysql_result($r, 0, 'id_obat');
        $jumlah = $real - $sistem;
	if($ruang == 37){
		$queryA = "UPDATE rm_stock_obat SET stock_lama=" . $sistem . ", stock_baru=0, stock=" . $sistem . " WHERE id_obat=" . $id_obat . "";
	} else {
		$queryA = "UPDATE rm_stock_obat_apotik SET stock_lama=" . $sistem . ", stock_baru=0, stock=" . $sistem . " WHERE id_obat=" . $id_obat . " AND id_ruang=" . $ruang . "";
	}
        if ($jumlah != 0) {
            if ($ruang == 37) {
                $qu = "SELECT id_balance as id, jumlah FROM rm_balance_gudang WHERE id_obat=" . $id_obat . " AND date(tgl_pemakaian)='" . date('Y-m-d') . "'";
                $re = $this->runQuery($qu);
                if (@mysql_num_rows($re) > 0) {
                    $jumlah = $jumlah + @mysql_result($re, 0, 'jumlah');
                    $query = "UPDATE rm_balance_gudang SET jumlah=" . $jumlah . " WHERE id_balance=" . @mysql_result($re, 0, 'id') . "";
                } else {
                    $query = "INSERT INTO rm_balance_gudang (id_obat,tgl_pemakaian,jumlah) VALUES (" . $id_obat . ",NOW()," . $jumlah . ")";
                }
                $result = $this->runQuery($query);
                if ($result) {
                    $query2 = "UPDATE rm_stock_obat SET stock_lama=" . $real . ", stock_baru=0, stock=" . $real . " WHERE id_obat=" . $id_obat . "";
                    $result2 = $this->runQuery($query2);
                }
            } else {
                $qu = "SELECT id_obat_balance as id, jumlah FROM rm_obat_balance WHERE id_obat=" . $id_obat . " AND id_ruang=" . $ruang . " AND date(tgl_pemakaian)='" . date('Y-m-d') . "'";
                $re = $this->runQuery($qu);
                if (@mysql_num_rows($re) > 0) {
                    $jumlah = $jumlah + @mysql_result($re, 0, 'jumlah');
                    $query = "UPDATE rm_obat_balance SET jumlah=" . $jumlah . " WHERE id_obat_balance=" . @mysql_result($re, 0, 'id') . "";
                } else {
                    $query = "INSERT INTO rm_obat_balance (id_obat,id_ruang,tgl_pemakaian,jumlah) VALUES (" . $id_obat . "," . $ruang . ",NOW()," . $jumlah . ")";
                }
                $result = $this->runQuery($query);
                if ($result) {
                    $query2 = "UPDATE rm_stock_obat_apotik SET stock_lama=" . $real . ", stock_baru=0, stock=" . $real . " WHERE id_obat=" . $id_obat . " AND id_ruang=" . $ruang . "";
                    $result2 = $this->runQuery($query2);
                }
            }
            if ($result2)
                return 'BERHASIL';
            else
                return 'GAGAL';
        } else {
            if($ruang == 37){
                $queryA = "UPDATE rm_stock_obat SET stock_lama=" . $sistem . ", stock_baru=0, stock=" . $sistem . " WHERE id_obat=" . $id_obat . "";
	    } else {
                $queryA = "UPDATE rm_stock_obat_apotik SET stock_lama=" . $sistem . ", stock_baru=0, stock=" . $sistem . " WHERE id_obat=" . $id_obat . " AND id_ruang=" . $ruang . "";
        }
	$resultA = $this->runQuery($queryA);
	if($resultA)
		return 'BERHASIL';
	else
		return 'GAGAL';
        }
    }

    public function getJumlahBalance($id_obat, $ruang) {
        $kondisi = "";
        $obat = "";
        $startDate = date('d-m-Y');
        $endDate = date('d-m-Y');
        $nm_ruang = "";
        if ($id_obat != "") {
            $kondisi .= " and kode_obat='" . $id_obat . "'";
        }

        $query = "select * from rm_obat where del_flag<>'1'" . $kondisi . " order by kode_obat";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            $i = 1;
            if ($ruang == "37") {
                $kondisiTgl = "";
                $kondisiTgl2 = "";
                if ($startDate != "") {
                    if ($endDate != "") {
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    } else {
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                    }
                    $kondisiTgl50 .= " and tgl_pemakaian < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl0 .= " and date_update < '" . $this->formatDateDb($startDate) . "'";
                }

                $q_defiasi = "select a.id_obat, ifnull(b.jumlah,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(jumlah) AS 
                            jumlah FROM rm_balance_gudang WHERE del_flag<>'1'" . $kondisiTgl5 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi = $this->runQuery($q_defiasi);
                while ($defiasi = @mysql_fetch_array($r_defiasi)) {
                    $arrDefiasi[] = array('id_obat' => $defiasi['id_obat'], 'jumlah' => $defiasi['jumlah']);
                }

                $q_defiasi_b = "select a.id_obat, ifnull(b.jumlah,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(jumlah) AS 
                            jumlah FROM rm_balance_gudang WHERE del_flag<>'1'" . $kondisiTgl50 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi_b = $this->runQuery($q_defiasi_b);
                while ($defiasi_b = @mysql_fetch_array($r_defiasi_b)) {
                    $arrDefiasi_b[] = array('id_obat' => $defiasi_b['id_obat'], 'jumlah' => $defiasi_b['jumlah']);
                }

                $q_keluar = "select a.id_obat, ifnull(b.jmlKeluar,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlKeluar FROM rm_distribusi_obat WHERE del_flag<>'1' AND id_ruang_asal=18" . $kondisiTgl . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar = $this->runQuery($q_keluar);
                while ($keluar = @mysql_fetch_array($r_keluar)) {
                    $arrKeluar[] = array('id_obat' => $keluar['id_obat'], 'jumlah' => $keluar['jumlah']);
                }

                $q_jml_masuk = "select a.id_obat, ifnull(b.jmlMasuk,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(qty) AS 
                            jmlMasuk FROM rm_penambahan_stock WHERE del_flag<>'1'" . $kondisiTgl . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_jml_masuk = $this->runQuery($q_jml_masuk);
                while ($masuk = @mysql_fetch_array($r_jml_masuk)) {
                    $arrMasuk[] = array('id_obat' => $masuk['id_obat'], 'jumlah' => $masuk['jumlah']);
                }

                $q_keluar_b = "select a.id_obat, ifnull(b.jmlKeluar,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlKeluar FROM rm_distribusi_obat WHERE del_flag<>'1' AND id_ruang_asal=18" . $kondisiTgl0 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar_b = $this->runQuery($q_keluar_b);
                while ($keluar_b = @mysql_fetch_array($r_keluar_b)) {
                    $arrKeluar_b[] = array('id_obat' => $keluar_b['id_obat'], 'jumlah' => $keluar_b['jumlah']);
                }

                $q_jml_masuk_b = "select a.id_obat, ifnull(b.jmlMasuk,0) as jumlah from rm_obat a LEFT JOIN (SELECT id_obat, SUM(qty) AS 
                            jmlMasuk FROM rm_penambahan_stock WHERE del_flag<>'1'" . $kondisiTgl0 . " GROUP BY id_obat) b 
                            ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_jml_masuk_b = $this->runQuery($q_jml_masuk_b);
                while ($masuk_b = @mysql_fetch_array($r_jml_masuk_b)) {
                    $arrMasuk_b[] = array('id_obat' => $masuk_b['id_obat'], 'jumlah' => $masuk_b['jumlah']);
                }

                $g = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $defia_b = $arrDefiasi_b[$g];
                    $defia = $arrDefiasi[$g];
                    $d_masuk = $arrMasuk[$g];
                    $d_masuk_b = $arrMasuk_b[$g];
                    $d_keluar = $arrKeluar[$g];
                    $d_keluar_b = $arrKeluar_b[$g];
                    $stock = (($d_masuk_b['jumlah'] - $d_keluar_b['jumlah']) + $defia_b['jumlah']);
                    $sa = $stock + $d_masuk['jumlah'] - $d_keluar['jumlah'] + $defia['jumlah'];
                    $i++;
                    $g++;
                }
            } else {
                $kondisiTgl = "";
                $kondisiTgl2 = "";
                $kondisiTgl3 = "";
                $kondisiTgl4 = "";
                if ($startDate != "") {
                    if ($endDate != "") {
                        $kondisiTgl4 .= " and tgl_retur between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl3 .= " and tgl_penjualan between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl2 .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . " 23:59:59'";
                    } else {
                        $kondisiTgl4 .= " and tgl_retur between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl3 .= " and tgl_penjualan between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl2 .= " and date_update between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                        $kondisiTgl5 .= " and tgl_pemakaian between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($startDate) . " 23:59:59'";
                    }
                    $kondisiTgl40 .= " and tgl_retur < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl30 .= " and tgl_penjualan < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl0 .= " and date_update < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl20 .= " and date_update < '" . $this->formatDateDb($startDate) . "'";
                    $kondisiTgl50 .= " and tgl_pemakaian < '" . $this->formatDateDb($startDate) . "'";
                }
                // PENAMBAHAN STOCK AWAL
                // MENCARI RETUR AWAL
                $jmlRetur_b = 0;
                $q_retur_b = "SELECT a.id_obat, ifnull(b.jumlah,0) as jmlRetur from rm_obat a LEFT JOIN (select id_obat, sum(jumlah) as jumlah from rm_retur_penjualan_obat d 
                            join rm_faktur_penjualan e ON (d.id_faktur_penjualan=e.id_faktur_penjualan and e.id_ruang=" . $ruang . " and e.del_flag<>1) 
                            WHERE d.del_flag<>'1' " . $kondisiTgl40 . " GROUP BY d.id_obat) b ON (b.id_obat = a.id_obat) 
                            where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_retur_b = $this->runQuery($q_retur_b);
                while ($retur_b = @mysql_fetch_array($r_retur_b)) {
                    $arrRetur_b[] = array('id_obat' => $retur_b['id_obat'], 'jumlah' => $retur_b['jmlRetur']);
                }
                // END RETUR
                // DISTRIBUSI AWAL
                $q_masuk_b = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_tujuan=" . $ruang . " and del_flag<>'1' and `status`=1  " . $kondisiTgl20 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_masuk_b = $this->runQuery($q_masuk_b);
                $jmlMasuk_b = 0;
                while ($masuk_b = @mysql_fetch_array($r_masuk_b)) {
                    $arrMasuk_b[] = array('id_obat' => $masuk_b['id_obat'], 'jumlah' => $masuk_b['jmlMasuk']);
                }
                // END
                // MENCARI PENGELUARAN AWAL
                $q_keluar_b = "SELECT a.id_obat,ifnull(b.jml,0) as jumlah from rm_obat a left join (SELECT id_obat, SUM(qty) as jml FROM rm_penjualan_obat a
                              JOIN rm_faktur_penjualan b ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1
                              and b.`status`<>0 " . $kondisiTgl30 . ") WHERE a.del_flag<>1 GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) 
                              where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar_b = $this->runQuery($q_keluar_b);
                while ($jual_b = @mysql_fetch_array($r_keluar_b)) {
                    $arrJual_b[] = array('id_obat' => $jual_b['id_obat'], 'jumlah' => $jual_b['jumlah']);
                }

                //RACIKAN AWAL
                $q_racikan_b = "SELECT a.id_obat,ifnull(b.jml,0) as jmlKeluar from rm_obat a left join (SELECT a.id_obat, SUM(a.qty) AS jml FROM 
                                rm_detail_racikan a JOIN (select id_racikan from rm_racikan a JOIN rm_faktur_penjualan b 
                               ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1 and b.`status`<>0 
                               " . $kondisiTgl30 . ") where a.del_flag<>1) b ON (a.id_racikan=b.id_racikan and a.del_flag<>1) 
                               GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_racikan_b = $this->runQuery($q_racikan_b);
                while ($racik_b = @mysql_fetch_array($r_racikan_b)) {
                    $arrRacik_b[] = array('id_obat' => $racik_b['id_obat'], 'jumlah' => $racik_b['jmlKeluar']);
                }

                // DISTRIBUSI KELUAR AWAL
                $q_dist_kel = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_asal=" . $ruang . " and del_flag<>'1' " . $kondisiTgl20 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_dist_kel = $this->runQuery($q_dist_kel);
                while ($dist_kel = @mysql_fetch_array($r_dist_kel)) {
                    $arrDistKel_b[] = array('id_obat' => $dist_kel['id_obat'], 'jumlah' => $dist_kel['jmlMasuk']);
                }
                // END
                //AKHIR PENGELUARAN AWAL
                //MENCARI DEFIASI AWAL
                $q_defiasi_b = "SELECT a.id_obat,ifnull(b.jumlah,0) as jumlah from rm_obat a left join (select sum(jumlah) as jumlah, id_obat from rm_obat_balance where 
                               id_ruang=" . $ruang . " and del_flag<>'1' " . $kondisiTgl50 . " GROUP BY id_obat) b ON (a.id_obat=b.id_obat) 
                               where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi_b = $this->runQuery($q_defiasi_b);

                $de = 0;
                $total_b = 0;
                while ($defiasi_b = @mysql_fetch_array($r_defiasi_b)) {
                    if ($de < @mysql_num_rows($r_defiasi_b)) {
                        $aR_b = $arrRetur_b[$de];
                        $aM_b = $arrMasuk_b[$de];
                        $aK_b = $arrDistKel_b[$de];
                        $aJ_b = $arrJual_b[$de];
                        $aC_b = $arrRacik_b[$de];
                        $total_b = $defiasi_b['jumlah'] + (($aR_b['jumlah'] + $aM_b['jumlah']) - ($aJ_b['jumlah'] + $aC_b['jumlah'] + $aK_b['jumlah']));
                        $arrSA_b[] = array('id_obat' => $defiasi_b['id_obat'], 'jumlah' => $total_b);
                    }
                    $de++;
                }

                ///////// END STOCK AWAL
                //MENCARI DINAMIS STOCK
                //MENCARI RETUR AKHIR
                $jmlRetur = 0;
                $q_retur = "SELECT a.id_obat, ifnull(b.jumlah,0) as jmlRetur from rm_obat a LEFT JOIN (select id_obat, sum(jumlah) as jumlah from rm_retur_penjualan_obat d 
                            join rm_faktur_penjualan e ON (d.id_faktur_penjualan=e.id_faktur_penjualan and e.id_ruang=" . $ruang . " and e.del_flag<>1) 
                            WHERE d.del_flag<>'1' " . $kondisiTgl4 . " GROUP BY d.id_obat) b ON (b.id_obat = a.id_obat) 
                            where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_retur = $this->runQuery($q_retur);
                while ($retur = @mysql_fetch_array($r_retur)) {
                    $arrRetur[] = array('id_obat' => $retur['id_obat'], 'jumlah' => $retur['jmlRetur']);
                }
                // END RETUR
                // DISTRIBUSI AKHIR
                $q_masuk = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_tujuan=" . $ruang . " and del_flag<>'1' and `status`=1  " . $kondisiTgl2 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_masuk = $this->runQuery($q_masuk);
                $jmlMasuk = 0;
                while ($masuk = @mysql_fetch_array($r_masuk)) {
                    $arrMasuk[] = array('id_obat' => $masuk['id_obat'], 'jumlah' => $masuk['jmlMasuk']);
                }
                // END
                // MENCARI PENGELUARAN AKHIR
                $q_keluar = "SELECT a.id_obat,ifnull(b.jml,0) as jumlah from rm_obat a left join (SELECT id_obat, SUM(qty) as jml FROM rm_penjualan_obat a
                              JOIN rm_faktur_penjualan b ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1
                              and b.`status`<>0 " . $kondisiTgl3 . ") WHERE a.del_flag<>1 GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) 
                              where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_keluar = $this->runQuery($q_keluar);
                while ($jual = @mysql_fetch_array($r_keluar)) {
                    $arrJual[] = array('id_obat' => $jual['id_obat'], 'jumlah' => $jual['jumlah']);
                }

                //RACIKAN AKHIR
                $q_racikan = "SELECT a.id_obat,ifnull(b.jml,0) as jmlKeluar from rm_obat a left join (SELECT a.id_obat, SUM(a.qty) AS jml FROM 
                                rm_detail_racikan a JOIN (select id_racikan from rm_racikan a JOIN rm_faktur_penjualan b 
                               ON (a.id_faktur_penjualan=b.id_faktur_penjualan and b.id_ruang=" . $ruang . " and b.del_flag<>1 and b.`status`<>0 
                               " . $kondisiTgl3 . ") where a.del_flag<>1) b ON (a.id_racikan=b.id_racikan and a.del_flag<>1) 
                               GROUP BY a.id_obat) b ON (a.id_obat=b.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_racikan = $this->runQuery($q_racikan);
                while ($racik = @mysql_fetch_array($r_racikan)) {
                    $arrRacik[] = array('id_obat' => $racik['id_obat'], 'jumlah' => $racik['jmlKeluar']);
                }

                // DISTRIBUSI KELUAR AKHIR
                $q_dist_keluar = "select a.id_obat, ifnull(b.jmlMasuk,0) as jmlMasuk from rm_obat a LEFT JOIN (SELECT id_obat, SUM(stock) AS 
                            jmlMasuk FROM rm_distribusi_obat WHERE id_ruang_asal=" . $ruang . " and del_flag<>'1' " . $kondisiTgl2 . "
                            GROUP BY id_obat) b ON (b.id_obat = a.id_obat) where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_dist_keluar = $this->runQuery($q_dist_keluar);
                while ($distKeluar = @mysql_fetch_array($r_dist_keluar)) {
                    $arrDistKeluar[] = array('id_obat' => $distKeluar['id_obat'], 'jumlah' => $distKeluar['jmlMasuk']);
                }
                // END
                //AKHIR PENGELUARAN AKHIR
                //MENCARI DEFIASI AKHIR
                $q_defiasi = "SELECT a.id_obat,ifnull(b.jumlah,0) as jumlah from rm_obat a left join (select sum(jumlah) as jumlah, id_obat from rm_obat_balance where 
                               id_ruang=" . $ruang . " and del_flag<>'1' " . $kondisiTgl5 . " GROUP BY id_obat) b ON (a.id_obat=b.id_obat) 
                               where a.del_flag<>'1' " . $kondisi . " order by a.kode_obat";
                $r_defiasi = $this->runQuery($q_defiasi);

                while ($defiasi = @mysql_fetch_array($r_defiasi)) {
                    $total = 0;
                    $arrAkhir[] = array('id_obat' => $defiasi['id_obat'], 'jumlah' => $defiasi['jumlah']);
                }

                $z = 1;
                $i = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $defia = $arrSA_b[$i];
                    $fRetur = $arrRetur[$i];
                    $fMasuk = $arrMasuk[$i];
                    $fDistKel = $arrDistKeluar[$i];
                    $fJual = $arrJual[$i];
                    $fRacik = $arrRacik[$i];
                    $debet = $fMasuk['jumlah'] + $fRetur['jumlah'];
                    $kredit = $fJual['jumlah'] + $fRacik['jumlah'] + $fDistKel['jumlah'];
                    $defiasi = $arrAkhir[$i];
                    $stock = $defia['jumlah'];
                    $sa = $stock + $debet - $kredit + $defiasi['jumlah'];
                    $z++;
                    $i++;
                }
            }
        } else {
            $sa = "NIHIL";
        }
        return $sa;
    }
    
    public function getLaporanPembelian(
    $id_obat, $id_obatS, $id_supplier, $startEntryDate, $endEntryDate, $startDate, $endDate, $tipeLaporan
    ) {
        $obat = "";
        $tglEntry = "";
        $tglBeli = "";
        $supplier = "";
        if ($tipeLaporan == '1') {
            $by = "By Obat";
            if ($id_obat != "") {
                if ($id_obatS)
                    $obat = "Kode Obat <b>" . $id_obat . "</b> s/d <b>" . $id_obatS . "</b><br>";
                else
                    $obat = "Kode Obat <b>" . $id_obat . "</b><br>";
            }
            if ($startEntryDate != "") {
                if ($endEntryDate != "")
                    $tglEntry = "Tanggal Entry <b>" . $this->codeDate($this->formatDateDb($startEntryDate)) . "</b> s/d <b>" . $this->codeDate($this->formatDateDb($endEntryDate)) . "</b><br>";
                else
                    $tglEntry = "Tanggal Entry <br>" . $this->codeDate($this->formatDateDb($startEntryDate)) . "</b><br>";
            }
            if ($startDate != "") {
                if ($endDate != "")
                    $tglBeli = "Tanggal Pembelian <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b> s/d <b>" . $this->codeDate($this->formatDateDb($endDate)) . "</b><br>";
                else
                    $tglBeli = "Tanggal Pembelian <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b><br>";
            }
            if ($id_supplier != "")
                $supplier = "Supplier <b>" . $this->getSupplier($id_supplier) . "</b><br>";
        } else if ($tipeLaporan == '2') {
            $by = "By Tanggal Entry";
            if ($startEntryDate != "") {
                if ($endEntryDate != "")
                    $tglEntry = "Tanggal Entry <b>" . $this->codeDate($this->formatDateDb($startEntryDate)) . "</b> s/d <b>" . $this->codeDate($this->formatDateDb($endEntryDate)) . "</b><br>";
                else
                    $tglEntry = "Tanggal Entry <b>" . $this->codeDate($this->formatDateDb($startEntryDate)) . "</b><br>";
            }
            if ($id_supplier != "")
                $supplier = "Supplier <b>" . $this->getSupplier($id_supplier) . "</b><br>";
        } else if ($tipeLaporan == '3') {
            $by = "By Tanggal Pembelian";
            if ($startDate != "") {
                if ($endDate != "")
                    $tglBeli = "Tanggal Pembelian <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b> s/d <b>" . $this->codeDate($this->formatDateDb($endDate)) . "</b><br>";
                else
                    $tglBeli = "Tanggal Pembelian <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b><br>";
            }
            if ($id_supplier != "")
                $supplier = "Supplier <b>" . $this->getSupplier($id_supplier) . "</b><br>";
        } else if ($tipeLaporan == '4') {
            $by = "By Supplier";
            if ($startEntryDate != "") {
                if ($endEntryDate != "")
                    $tglEntry = "Tanggal Entry <b>" . $this->codeDate($this->formatDateDb($startEntryDate)) . "</b> s/d <b>" . $this->codeDate($this->formatDateDb($endEntryDate)) . "</b><br>";
                else
                    $tglEntry = "Tanggal Entry <b>" . $this->codeDate($this->formatDateDb($startEntryDate)) . "</b><br>";
            }
            if ($startDate != "") {
                if ($endDate != "")
                    $tglBeli = "Tanggal Pembelian <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b> s/d <b>" . $this->codeDate($this->formatDateDb($endDate)) . "</b><br>";
                else
                    $tglBeli = "Tanggal Pembelian <b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b><br>";
            }
            if ($id_supplier != "")
                $supplier = "Supplier <b>" . $this->getSupplier($id_supplier) . "</b><br>";
        }

        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><br><u><b>Laporan Pembelian Obat " . $by . "</b></u><br><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'>" . $obat . $tglEntry . $tglBeli . $supplier . "</td>
                            </tr>";
        $html .="</table>";
        $kondisi = "";
        if ($tipeLaporan == '1') {
            if ($id_obat != "") {
                if ($id_obatS != "")
                    $kondisi .= " and d.kode_obat between '" . $id_obat . "' and '" . $id_obatS . "'";
                else
                    $kondisi .= " and d.kode_obat='" . $id_obat . "'";
            }
            if ($id_supplier != "")
                $kondisi .= " and a.id_supplier='" . $id_supplier . "'";
            if ($startDate != "") {
                if ($endDate != "")
                    $kondisi .= " and date(a.tgl_pembelian) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                else
                    $kondisi .= " and date(a.tgl_pembelian)='" . $this->formatDateDb($startDate) . "'";
            }

            $query = "SELECT a.no_faktur, d.kode_obat, d.nama_obat, a.tgl_pembelian, b.supplier, (SUM(c.qty)-sum(c.retur)) AS qty, harga, diskon
                      FROM rm_faktur a, rm_supplier b, rm_pembelian_obat c, rm_obat d
                      WHERE b.id_supplier=a.id_supplier AND c.id_faktur=a.id_faktur and a.del_flag<>1 and c.del_flag<>'1' AND d.id_obat=c.id_obat " . $kondisi . "
                      GROUP BY a.id_faktur, c.id_obat order by a.id_supplier";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='2%' class='headerTagihan'>No</td>";
                $html .= "<td width='5%' class='headerTagihan'>Kode Obat</td>";
                $html .= "<td width='15%' class='headerTagihan'>Nama Obat</td>";
                $html .= "<td width='8%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='10%' class='headerTagihan'>Supplier</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Total Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "<td width='10%' class='headerTagihan'>Pajak</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah Netto</td>";
                $html .= "<td width='5%' class='headerTagihan'>No Faktur</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $i = 1;
                $jmlTotal = 0;
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlPaj = 0;
                $jmlTot = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $total = ($data['qty'] * $data['harga']);
                    $diskon = ($data['diskon'] / 100 * $total);
                    $jumD = (($data['qty'] * $data['harga']) - $diskon);
                    $jumP = $jumD * 0.1;
                    $html .= "<tr>";
                    $html .= "<td>" . $i . "</td>";
                    $html .= "<td>" . $data['kode_obat'] . "</td>";
                    $html .= "<td>" . $data['nama_obat'] . "</td>";
                    $html .= "<td>" . $this->formatDateDb($data['tgl_pembelian']) . "</td>";
                    $html .= "<td>" . $data['supplier'] . "</td>";
                    $html .= "<td>" . $data['qty'] . "</td>";
                    $html .= "<td align='right'>" . number_format($data['harga'], 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format(($data['qty'] * $data['harga']), 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($diskon, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($jumD, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($jumP, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($jumD + $jumP, 2, ',', '.') . "</td>";
                    $html .= "<td>" . $data['no_faktur'] . "</td>";
                    $html .= "</tr>";
                    $i++;
                    $jmlQty += $data['qty'];
                    $jmlTotal += $jumD;
                    $jmlDisc += $diskon;
                    $jmlPaj += $jumP;
                    $jmlTot += $total;
                }
                $html .= "<tr>";
                $html .= "<td colspan='5' class='total'>Sub Total</td>";
                $html .= "<td align='right' class='total'>" . number_format($jmlQty, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>&nbsp;</td>";
                $html .= "<td align='right' class='total'>" . number_format($jmlTot, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($jmlPaj, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($jmlTotal + $jmlPaj, 2, ',', '.') . "</td>";
                $html .= "<td colspan='2' align='right' class='total'>&nbsp;</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</html>";
            } else {
                $html = "Data Tidak ditemukan.";
            }
        } else if ($tipeLaporan == '2') {
            if ($id_supplier != "")
                $kondisi .= " and a.id_supplier='" . $id_supplier . "'";
            if ($startEntryDate != "") {
                if ($endEntryDate != "")
                    $kondisi .= " and date(a.date_entry) between '" . $this->formatDateDb($startEntryDate) . "' and '" . $this->formatDateDb($endEntryDate) . "'";
                else
                    $kondisi .= " and date(a.date_entry)='" . $this->formatDateDb($startEntryDate) . "'";
            }

            $query = "SELECT a.id_faktur, a.no_faktur, b.supplier, date(a.tgl_pembelian) as tgl_pembelian 
                      FROM rm_faktur a, rm_supplier b WHERE b.id_supplier=a.id_supplier AND a.del_flag<>1" . $kondisi . "
                      GROUP BY a.id_faktur";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='2%' class='headerTagihan'>No</td>";
                $html .= "<td width='5%' class='headerTagihan'>No Faktur</td>";
                $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='13%' class='headerTagihan'>Supplier</td>";
                $html .= "<td width='14%' class='headerTagihan'>Total Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "<td width='10%' class='headerTagihan'>Pajak</td>";
                $html .= "<td width='12%' class='headerTagihan'>Jumlah Netto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $i = 1;
                $jmlTotal = 0;
                $jmlAll = 0;
                $jmlDisc = 0;
                $jmlPaj = 0;
                $jmlNet = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $diskon = 0;
                    $total = 0;
                    $netto = 0;
                    $pajak = 0;
                    $q_obat = "select qty, harga, diskon, pajak, retur from rm_pembelian_obat where id_faktur='" . $data['id_faktur'] . "' and del_flag<>'1'";
                    $r_obat = $this->runQuery($q_obat);
                    while ($rec = @mysql_fetch_array($r_obat)) {
                        $total += ( $rec['qty'] - $rec['retur']) * $rec['harga'];
                        $diskon += ( ($rec['qty'] - $rec['retur']) * $rec['harga']) * ($rec['diskon'] / 100);
                        $pajak += $rec['pajak'];
                    }
                    $html .= "<tr>";
                    $html .= "<td align='center'>" . $i . "</td>";
                    $html .= "<td>" . $data['no_faktur'] . "</td>";
                    $html .= "<td>" . $this->formatDateDb($data['tgl_pembelian']) . "</td>";
                    $html .= "<td>" . $data['supplier'] . "</td>";
                    $html .= "<td align='right'>" . number_format($total, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($diskon, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format(($total - $diskon), 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($pajak, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format(($total - $diskon) + $pajak, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $i++;
                    $jmlTotal += ( $total - $diskon);
                    $jmlAll += $total;
                    $jmlDisc += $diskon;
                    $jmlPaj += $pajak;
                    $jmlNet += ( $total - $diskon) + $pajak;
                }
                $html .= "<tr>";
                $html .= "<td colspan='4' class='total'>Sub Total</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlAll, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlPaj, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlNet, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak ditemukan.";
            }
        } else if ($tipeLaporan == '3') {
            if ($id_supplier != "")
                $kondisi .= " and a.id_supplier='" . $id_supplier . "'";
            if ($startDate != "") {
                if ($endDate != "")
                    $kondisi .= " and date(a.tgl_pembelian) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                else
                    $kondisi .= " and date(a.tgl_pembelian)='" . $this->formatDateDb($startDate) . "'";
            }

            $query = "SELECT a.id_faktur, a.no_faktur, b.supplier, date(a.tgl_pembelian) as tgl_pembelian 
                      FROM rm_faktur a, rm_supplier b WHERE b.id_supplier=a.id_supplier AND a.del_flag<>1" . $kondisi . "
                      GROUP BY a.id_faktur";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='2%' class='headerTagihan'>No</td>";
                $html .= "<td width='5%' class='headerTagihan'>No Faktur</td>";
                $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='12%' class='headerTagihan'>Supplier</td>";
                $html .= "<td width='12%' class='headerTagihan'>Total Harga</td>";
                $html .= "<td width='11%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='12%' class='headerTagihan'>Jumlah</td>";
                $html .= "<td width='10%' class='headerTagihan'>Pajak</td>";
                $html .= "<td width='12%' class='headerTagihan'>Jumlah Netto</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $i = 1;
                $jmlTotal = 0;
                $jmlAll = 0;
                $jmlDisc = 0;
                $jmlPaj = 0;
                $jmlNet = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $diskon = 0;
                    $total = 0;
                    $netto = 0;
                    $pajak = 0;
                    $q_obat = "select qty, harga, diskon, pajak, retur from rm_pembelian_obat where id_faktur='" . $data['id_faktur'] . "' and del_flag<>'1'";
                    $r_obat = $this->runQuery($q_obat);
                    while ($rec = @mysql_fetch_array($r_obat)) {
                        $total += ( $rec['qty'] - $rec['retur']) * $rec['harga'];
                        $diskon += ( ($rec['qty'] - $rec['retur']) * $rec['harga']) * ($rec['diskon'] / 100);
                        $pajak += $rec['pajak'];
                    }
                    $html .= "<tr>";
                    $html .= "<td align='center'>" . $i . "</td>";
                    $html .= "<td>" . $data['no_faktur'] . "</td>";
                    $html .= "<td>" . $this->codeDate($data['tgl_pembelian']) . "</td>";
                    $html .= "<td>" . $data['supplier'] . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format($diskon, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format(($total - $diskon), 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format($pajak, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format(($total - $diskon) + $pajak, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $i++;
                    $jmlTotal += ( $total - $diskon);
                    $jmlAll += $total;
                    $jmlDisc += $diskon;
                    $jmlPaj += $pajak;
                    $jmlNet += ( $total - $diskon) + $pajak;
                }
                $html .= "<tr>";
                $html .= "<td colspan='4' class='total'>Sub Total</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlAll, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlPaj, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlNet, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak ditemukan.";
            }
        } else if ($tipeLaporan == '4') {
            if ($id_supplier != "")
                $kondisi .= " and a.id_supplier='" . $id_supplier . "'";
            if ($startDate != "") {
                if ($endDate != "")
                    $kondisi .= " and date(a.tgl_pembelian) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
                else
                    $kondisi .= " and date(a.tgl_pembelian)='" . $this->formatDateDb($startDate) . "'";
            }
            if ($startEntryDate != "") {
                if ($endEntryDate != "")
                    $kondisi .= " and date(a.date_entry) between '" . $this->formatDateDb($startEntryDate) . "' and '" . $this->formatDateDb($endEntryDate) . "'";
                else
                    $kondisi .= " and date(a.date_entry)='" . $this->formatDateDb($startEntryDate) . "'";
            }

            $query = "SELECT a.id_faktur, a.no_faktur, b.supplier, date(a.tgl_pembelian) as tgl_pembelian 
                      FROM rm_faktur a, rm_supplier b WHERE b.id_supplier=a.id_supplier AND a.del_flag<>1 " . $kondisi . "
                      GROUP BY a.id_faktur";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table style='font-family: calibri;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='5%' class='headerTagihan' colspan='2'>No Faktur</td>";
                $html .= "<td width='20%' class='headerTagihan'>Supplier</td>";
                $html .= "<td width='10%' colspan='7' class='headerTagihan'>Tanggal</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $i = 1;
                $grandTotal = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $diskon = 0;
                    $total = 0;
                    $netto = 0;
                    $pajak = 0;
                    $jmlTotal = 0;
                    $html .= "<tr>";
                    $html .= "<td colspan='2'>" . $data['no_faktur'] . "</td>";
                    $html .= "<td>" . $data['supplier'] . "</td>";
                    $html .= "<td colspan='6'>" . $this->codeDate($data['tgl_pembelian']) . "</td>";
                    $html .= "</tr>";
                    $html .= "<tr>";
                    $html .= "<td width='2%' class='headerTagihan'>No</td>";
                    $html .= "<td width='5%' class='headerTagihan'>Kode Obat</td>";
                    $html .= "<td width='20%' class='headerTagihan'>Nama Obat</td>";
                    $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                    $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                    $html .= "<td width='12%' class='headerTagihan'>Total Harga</td>";
                    $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                    $html .= "<td width='15%' class='headerTagihan'>Jumlah</td>";
                    $html .= "<td width='10%' class='headerTagihan'>Pajak</td>";
                    $html .= "<td width='15%' class='headerTagihan'>Jumlah Netto</td>";
                    $html .= "</tr>";
                    $q_obat = "select b.kode_obat, b.nama_obat, qty, harga, diskon, pajak, retur from rm_pembelian_obat a, rm_obat b
                                   where id_faktur='" . $data['id_faktur'] . "' and b.id_obat=a.id_obat and a.del_flag<>'1'";
                    $r_obat = $this->runQuery($q_obat);
                    $subqty = 0;
                    $subth = 0;
                    $subdisk = 0;
                    $subjml = 0;
                    $subpajak = 0;
                    while ($rec = @mysql_fetch_array($r_obat)) {
                        $total = ( $rec['qty'] - $rec['retur']) * $rec['harga'];
                        $diskon = ( ($rec['qty'] - $rec['retur']) * $rec['harga']) * ($rec['diskon'] / 100);
                        $pajak = $rec['pajak'];
                        $jml = $total - $diskon;
                        $all = $jml + $pajak;
                        $html .= "<tr>";
                        $html .= "<td align='center'>" . $i . "</td>";
                        $html .= "<td>" . $rec['kode_obat'] . "</td>";
                        $html .= "<td>" . $rec['nama_obat'] . "</td>";
                        $html .= "<td align='right'>" . $rec['qty'] . "</td>";
                        $html .= "<td align='right'>" . number_format($rec['harga'], 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format($total, 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format($diskon, 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format($jml, 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format($pajak, 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . number_format($all, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $i++;
                        $subqty += $rec['qty'];
                        $subth += $total;
                        $subdisk += $diskon;
                        $subjml += $jml;
                        $subpajak += $pajak;
                        $jmlTotal += $all;
                        //
                        $grandDisk = 0;
                        $grandJml = 0;
                        $grandPaj = 0;
                        //
                    }
                    $html .= "<tr>";
                    $html .= "<td colspan='3' class='total'>Sub Total</td>";
                    $html .= "<td align='right' class='total'>" . number_format($subqty, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>&nbsp;</td>";
                    $html .= "<td align='right' class='total'>" . number_format($subth, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($subdisk, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($subjml, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($subpajak, 2, ',', '.') . "</td>";
                    $html .= "<td align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $grandTotal += $jmlTotal;
                    $grandth += $subth;
                    $grandqty += $subqty;
                    $grandDisk += $subdisk;
                    $grandJml += $subjml;
                    $grandPaj += $subpajak;
                }
                $html .= "<tr>";
                $html .= "<td colspan='3' class='total'>Grand Total</td>";
                $html .= "<td align='right' class='total'>" . number_format($grandqty, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>&nbsp;</td>";
                $html .= "<td align='right' class='total'>" . number_format($grandth, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($grandDisk, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($grandJml, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($grandPaj, 2, ',', '.') . "</td>";
                $html .= "<td align='right' class='total'>" . number_format($grandTotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data Tidak ditemukan.";
            }
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function bayarFaktur($id_faktur, $bayarKe, $bayar) {
        $jmlBiaya = 0;
        $jmlPajak = 0;
        $jmlDiskon = 0;
        $terbayar = 0;

        $q_check = "select status from rm_faktur where id_faktur='" . $id_faktur . "'";
        $r_check = $this->runQuery($q_check);
        if (@mysql_result($r_check, 0, 'status') == '0') {
            $query = "select * from rm_pembelian_obat where id_faktur='" . $id_faktur . "'";
            $result = $this->runQuery($query);

            if (@mysql_num_rows($result) > 0) {
                while ($rec = mysql_fetch_array($result)) {
                    $jmlBiaya += ( ($rec['qty'] - $rec['retur']) * $rec['harga']);
                    $jmlPajak += $rec['pajak'];
                    $jmlDiskon += $rec['diskon'];
                }

                $q_bayar = "select * from rm_pembayaran_faktur where id_faktur='" . $id_faktur . "'";
                $r_bayar = $this->runQuery($q_bayar);

                $i = 1;
                if (@mysql_num_rows($r_bayar) > 0) {
                    while ($data = @mysql_fetch_array($r_bayar)) {
                        $terbayar += $data['bayar'];
                        $i++;
                    }
                }
                $total = ($jmlBiaya + $jmlPajak - $jmlDiskon);

                if (($total - $terbayar - $bayar) <= 0) {
                    $query = "update rm_faktur set status='1' where id_faktur='" . $id_faktur . "'";
                    $result = $this->runQuery($query);
                }
                $q_pembayaran = "insert into rm_pembayaran_faktur (
                            id_faktur,
                            bayar_ke,
                            tgl_pembayaran,
                            bayar
                         ) values (
                            '" . $id_faktur . "',
                            '" . $bayarKe . "',
                            '" . date('Y-m-d') . "',
                            '" . $bayar . "'
                         )";
                $r_pembayaran = $this->runQuery($q_pembayaran);

                if ($r_pembayaran) {
                    $q = "select no_faktur, status_assign from rm_faktur where id_faktur='" . $id_faktur . "'";
                    $r = $this->runQuery($q);
                    $no_faktur = @mysql_result($r, 0, 'no_faktur');
//                    if(@mysql_result($r, 0, 'status_assign')=='0')
//                        $this->assignObatGudang ($id_faktur);

                    return $no_faktur;
                } else {
                    return '0';
                }
            }
        } else {
            return 'finish';
        }
    }

    public function approveFaktur($id_pembayaran_faktur) {
        $q_check = "select status from rm_pembayaran_faktur where id_pembayaran_faktur='" . $id_pembayaran_faktur . "'";
        $r_check = $this->runQuery($q_check);
        if (@mysql_result($r_check, 0, 'status') == '0') {
            $query = "update rm_pembayaran_faktur set status='1' where id_pembayaran_faktur='" . $id_pembayaran_faktur . "'";
            $result = $this->runQuery($query);

            if ($result) {
                return $id_pembayaran_faktur;
            } else {
                return '0';
            }
        }
    }

    public function assignObatGudang($id_faktur) {
        $success = 0;
        $q_check = "select * from rm_faktur where id_faktur='". $id_faktur ."' and del_flag<>1 AND status_assign='0'";
        $r_check = $this->runQuery($q_check);

        if (@mysql_num_rows($r_check) != 0) {
            $q_detail = "select * from rm_pembelian_obat where id_faktur='" . $id_faktur . "' and del_flag<>'1'";
            $r_detail = $this->runQuery($q_detail);

            while ($data = @mysql_fetch_array($r_detail)) {
                $q_stock = "select * from rm_stock_obat where id_obat='" . $data['id_obat'] . "' 
                            and id_penyimpanan='" . $data['id_penyimpanan'] . "'";
                $r_stock = $this->runQuery($q_stock);
                if (@mysql_num_rows($r_stock) > 0) {
                    $stock = ($data['qty'] - $data['retur']) + @mysql_result($r_stock, 0, 'stock_lama') + @mysql_result($r_stock, 0, 'stock_baru');
                    $stock_lama = @mysql_result($r_stock, 0, 'stock_lama') + @mysql_result($r_stock, 0, 'stock_baru');
                    $stock_baru = ($data['qty'] - $data['retur']);
                    $tgl_kadaluarsa_lama = @mysql_result($r_stock, 0, 'tgl_kadaluarsa_baru');
                    $tgl_kadaluarsa_baru = $data['tgl_kadaluarsa'];
                    $q = "update rm_stock_obat set 
                              stock_lama='" . $stock_lama . "', 
                              stock_baru='" . $stock_baru . "',
                              stock='" . $stock . "',
                              tgl_kadaluarsa_lama='" . $tgl_kadaluarsa_lama . "',
                              tgl_kadaluarsa_baru='" . $tgl_kadaluarsa_baru . "'
                          where 
                              id_obat='" . $data['id_obat'] . "' 
                              and id_penyimpanan='" . $data['id_penyimpanan'] . "'";
                } else {
                    $q = "insert into rm_stock_obat (
                              id_obat, 
                              id_penyimpanan,
                              stock_baru,
                              stock,
                              tgl_kadaluarsa_baru
                          ) values (
                              '" . $data['id_obat'] . "', 
                              '" . $data['id_penyimpanan'] . "',
                              '" . $data['qty'] . "', 
                              '" . $data['qty'] . "', 
                              '" . $data['tgl_kadaluarsa'] . "'
                          )";
                }
                $q_tmbh_stock = "insert into rm_penambahan_stock (id_obat, qty) values ('" . $data['id_obat'] . "', '" . ($data['qty'] - $data['retur']) . "')";
                $this->runQuery($q_tmbh_stock);

                $r = $this->runQuery($q);
                if ($r) {
                    $q_assign = "update rm_faktur set status_assign='1' where id_faktur='" . $id_faktur . "' and status_assign='0'";
                    $this->runQuery($q_assign);

                    $success++;
                }
            }
            if ($success > 0)
                return '1';
            else
                return '0';
        } else {
            return '2';
        }
    }

    public function simpanStockBarang($id_barang, $jmlBarang) {
        if (isset($_SESSION['level'])) {
            $q_barang = "select * from rm_stock_barang where id_ruang='" . $_SESSION['level'] . "' and id_barang='" . $id_barang . "'";
            $r_barang = $this->runQuery($q_barang);
            if (@mysql_num_rows($r_barang) > 0) {
                $query = "update rm_stock_barang set jumlah_stock='" . (@mysql_result($r_barang, 0, 'jumlah_stock') + $jmlBarang) . "'
                      where id_stock_barang='" . @mysql_result($r_barang, 0, 'id_stock_barang') . "'";
            } else {
                $query = "insert into rm_stock_barang (id_barang, id_ruang, jumlah_stock) values ('" . $id_barang . "', '" . $_SESSION['level'] . "', '" . $jmlBarang . "')";
            }

            $result = $this->runQuery($query);
            if ($result)
                return '1';
            else
                return '0';
        }
        return 'LOGIN';
    }

    public function simpanDistObat($id_obat, $id_penyimpanan, $ruangTujuan, $jmlObat, $tgl_kadaluarsa_baru) {
        if (isset($_SESSION['level'])) {
            $success = 0;

            if ($_SESSION['level'] == 18)
                $q_stock = "select * from rm_stock_obat where id_obat='" . $id_obat . "' and id_penyimpanan='" . $id_penyimpanan . "'";
            else
                $q_stock = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' and id_ruang =" . $_SESSION['level'] . " and id_penyimpanan='" . $id_penyimpanan . "'";

            $r_stock = $this->runQuery($q_stock);
            $gudang = @mysql_result($r_stock, 0, 'stock');
            if (($gudang >= $jmlObat) || (($gudang - $jmlObat) >= @mysql_result($r_stock, 0, 'stock_limit'))) {
                $q_distribusi = "insert into rm_distribusi_obat (
                    id_obat,
                    id_ruang_tujuan,
                    id_ruang_asal,
                    stock,
                    tgl_kadaluarsa,
                    pj
                 ) values (
                    '" . $id_obat . "',
                    '" . $ruangTujuan . "',
                    '" . $_SESSION['level'] . "',
                    '" . $jmlObat . "',
                    '" . $this->formatDateDb($tgl_kadaluarsa_baru) . "',
                    '" . $_SESSION['nip'] . "'
                 )";
                $r_distribusi = $this->runQuery($q_distribusi);

                $stock = @mysql_result($r_stock, 0, 'stock') - $jmlObat;

                if ($r_distribusi) {

                    if (@mysql_result($r_stock, 0, 'stock_lama') < $jmlObat) {
                        $stock_lama = 0;
                        $stock_baru = @mysql_result($r_stock, 0, 'stock_lama') - $jmlObat + @mysql_result($r_stock, 0, 'stock_baru');
                    } else {
                        $stock_lama = @mysql_result($r_stock, 0, 'stock_lama') - $jmlObat;
                        $stock_baru = @mysql_result($r_stock, 0, 'stock_baru');
                    }
                    $tgl_kadaluarsa_lama = @mysql_result($r_stock, 0, 'tgl_kadaluarsa_baru');
                    $tgl_kadaluarsa_baru = $tgl_kadaluarsa_baru;
                    if ($stock < 0) {
                        $return = "ERROR:Stock tidak mencukupi";
                    } else if ($stock == 0) {
                        if ($_SESSION['level'] == 18) {
                            $query = "update rm_stock_obat set
                                      stock_lama=" . $stock_lama . ", 
                                      stock_baru=" . $stock_baru . ",
                                      stock=" . $stock . "
                                      where
                                      id_obat=" . $id_obat . "
                                      and id_penyimpanan=" . $id_penyimpanan . "";
                        } else {
                            $query = "update rm_stock_obat_apotik set
                                      stock_lama=" . $stock_lama . ", 
                                      stock_baru=" . $stock_baru . ",
                                      stock=" . $stock . "
                                      where
                                      id_obat=" . $id_obat . "
                                      and id_ruang=" . $_SESSION['level'] . "
                                      and id_penyimpanan=" . $id_penyimpanan . "";
                        }
                        $result = $this->runQuery($query);

                        if ($result) {
                            $return = "TRUE";
                        } else {
                            $return = "ERROR:A";
                        }
                        $return = "WARNING:Stock Habis";
                    } else if ($stock <= @mysql_result($r_stock, 0, 'stock_limit')) {
                        if ($_SESSION['level'] == 18) {
                            $query = "update rm_stock_obat set
                                      stock_lama='" . $stock_lama . "', 
                                      stock_baru='" . $stock_baru . "',
                                      stock='" . $stock . "'
                                      where 
                                      id_obat='" . $id_obat . "' 
                                      and id_penyimpanan='" . $id_penyimpanan . "'";
                        } else {
                            $query = "update rm_stock_obat_apotik set
                                      stock_lama='" . $stock_lama . "', 
                                      stock_baru='" . $stock_baru . "',
                                      stock='" . $stock . "'
                                      where 
                                      id_obat='" . $id_obat . "' 
                                      and id_ruang=" . $_SESSION['level'] . "
                                      and id_penyimpanan='" . $id_penyimpanan . "'";
                        }
                        $result = $this->runQuery($query);

                        if ($result) {
                            $return = "TRUE";
                        } else {
                            $return = "ERROR:B";
                        }
                        $return = "WARNING:Stock mencapai limit";
                    } else {
                        if ($_SESSION['level'] == 18) {
                            $query = "update rm_stock_obat set
                                      stock_lama='" . $stock_lama . "', 
                                      stock_baru='" . $stock_baru . "',
                                      stock='" . $stock . "'
                                      where 
                                      id_obat='" . $id_obat . "' 
                                      and id_penyimpanan='" . $id_penyimpanan . "'";
                        } else {
                            $query = "update rm_stock_obat_apotik set
                                      stock_lama='" . $stock_lama . "', 
                                      stock_baru='" . $stock_baru . "',
                                      stock='" . $stock . "'
                                      where 
                                      id_obat='" . $id_obat . "' 
                                      and id_ruang=" . $_SESSION['level'] . "
                                      and id_penyimpanan='" . $id_penyimpanan . "'";
                        }
                        $result = $this->runQuery($query);

                        if ($result) {
                            $return = "TRUE";
                        } else {
                            $return = "ERROR:C";
                        }
                    }
                }
            } else {
                $return = "ERROR:D";
            }
            return $return;
        } else {
            return 'LOGIN';
        }
    }

    public function simpanDistBarang($id_barang, $ruangTujuan, $jmlBarang) {
        if (isset($_SESSION['level'])) {
            $success = 0;

            $q_distribusi = "insert into rm_distribusi_barang (
                    id_barang,
                    id_ruang,
                    jumlah_stock
                 ) values (
                    '" . $id_barang . "',
                    '" . $ruangTujuan . "',
                    '" . $jmlBarang . "'
                 )";
            $r_distribusi = $this->runQuery($q_distribusi);
            if ($r_distribusi) {
                $q_stock = "select * from rm_stock_barang where id_barang='" . $id_barang . "'";
                $r_stock = $this->runQuery($q_stock);

                $stock = @mysql_result($r_stock, 0, 'jumlah_stock') - $jmlBarang;
                if ($stock < 0) {
                    $return = "ERROR:Stock tidak mencukupi";
                } else if ($stock == 0) {
                    $query = "update rm_stock_barang set
                          jumlah_stock='" . $stock . "'
                      where
                          id_barang='" . $id_barang . "' 
                          and id_ruang='" . $_SESSION['level'] . "'";
                    $result = $this->runQuery($query);

                    if ($result) {
                        $return = "TRUE";
                    } else {
                        $return = "ERROR";
                    }
                    $return = "WARNING:Stock Habis";
                } else if ($stock <= @mysql_result($r_stock, 0, 'stock_limit')) {
                    $query = "update rm_stock_barang set
                          jumlah_stock='" . $stock . "'
                      where 
                          id_barang='" . $id_barang . "' 
                          and id_ruang='" . $_SESSION['level'] . "'";
                    $result = $this->runQuery($query);

                    if ($result) {
                        $return = "TRUE";
                    } else {
                        $return = "ERROR";
                    }
                    $return = "WARNING:Stock mencapai limit";
                } else {
                    $query = "update rm_stock_barang set
                          jumlah_stock='" . $stock . "'
                      where 
                          id_barang='" . $id_barang . "' 
                          and id_ruang='" . $_SESSION['level'] . "'";
                    $result = $this->runQuery($query);

                    if ($result) {
                        $return = "TRUE";
                    } else {
                        $return = "ERROR";
                    }
                }
            }
            return $return;
        }
        return 'LOGIN';
    }

    public function simpanDistObatApotik($id_distribusi_obat, $id_obat, $id_penyimpanan, $jmlObat, $tgl_kadaluarsa_baru, $semua) {
        if (isset($_SESSION['level'])) {
            $success = 0;
            $ruang = $_SESSION['level'];
            if ($semua == 1) {
                $q_detail = "SELECT * FROM rm_distribusi_obat where id_ruang_tujuan=" . $ruang . " and del_flag<>'1' and status=0";
                $r_detail = $this->runQuery($q_detail);

                while ($data = @mysql_fetch_array($r_detail)) {
                    $q_stock = "select * from rm_stock_obat_apotik where id_obat='" . $data['id_obat'] . "' 
                    and id_ruang=" . $ruang . "";
                    $r_stock = $this->runQuery($q_stock);
                    if (@mysql_num_rows($r_stock) > 0) {
                        $stock = $data['stock'] + @mysql_result($r_stock, 0, 'stock_lama') + @mysql_result($r_stock, 0, 'stock_baru');
                        $stock_lama = @mysql_result($r_stock, 0, 'stock_lama') + @mysql_result($r_stock, 0, 'stock_baru');
                        $stock_baru = $data['stock'];
                        $tgl_kadaluarsa_lama = @mysql_result($r_stock, 0, 'tgl_kadaluarsa_baru');
                        $tgl_kadaluarsa_baru = $data['tgl_kadaluarsa'];
                        $q = "update rm_stock_obat_apotik set 
                          stock_lama='" . $stock_lama . "', 
                          stock_baru='" . $stock_baru . "',
                          stock='" . $stock . "',
                          tgl_kadaluarsa_lama='" . $tgl_kadaluarsa_lama . "',
                          tgl_kadaluarsa_baru='" . $tgl_kadaluarsa_baru . "'
                      where 
                          id_obat='" . $data['id_obat'] . "'
                          and id_ruang=" . $ruang . "
                          and id_penyimpanan='1'";
                    } else {
                        $q = "insert into rm_stock_obat_apotik (
                            id_obat,
                            id_ruang,
                            id_penyimpanan,
                            stock_baru,
                            stock,
                            tgl_kadaluarsa_baru
                          ) values (
                            '" . $data['id_obat'] . "',
                            '" . $ruang . "',
                            '1',
                            '" . $data['stock'] . "', 
                            '" . $data['stock'] . "', 
                            '" . $tgl_kadaluarsa_baru . "'
                          )";
                    }
                    $r = $this->runQuery($q);

                    if ($r) {
                        $q_distribusi = "update rm_distribusi_obat set status='1' where id_distribusi_obat='" . $data['id_distribusi_obat'] . "'";
                        $r_distribusi = $this->runQuery($q_distribusi);

                        if ($r_distribusi) {
                            $return = "1";
                        } else {
                            $return = "2";
                        }
                    } else {
                        $return = "0";
                    }
                }
            } else {
                $q_stock = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' 
                    and id_ruang='" . $_SESSION['level'] . "' and id_penyimpanan='" . $id_penyimpanan . "'";
                $r_stock = $this->runQuery($q_stock);
                if (@mysql_num_rows($r_stock) > 0) {
                    $stock = $jmlObat + @mysql_result($r_stock, 0, 'stock_lama') + @mysql_result($r_stock, 0, 'stock_baru');
                    $stock_lama = @mysql_result($r_stock, 0, 'stock_lama') + @mysql_result($r_stock, 0, 'stock_baru');
                    $stock_baru = $jmlObat;
                    $tgl_kadaluarsa_lama = @mysql_result($r_stock, 0, 'tgl_kadaluarsa_baru');
                    $tgl_kadaluarsa_baru = $tgl_kadaluarsa_baru;
                    $q = "update rm_stock_obat_apotik set 
                      stock_lama='" . $stock_lama . "', 
                      stock_baru='" . $stock_baru . "',
                      stock='" . $stock . "',
                      tgl_kadaluarsa_lama='" . $tgl_kadaluarsa_lama . "',
                      tgl_kadaluarsa_baru='" . $this->formatDateDb($tgl_kadaluarsa_baru) . "'
                  where 
                      id_obat='" . $id_obat . "'
                      and id_ruang='" . $_SESSION['level'] . "'
                      and id_penyimpanan='" . $id_penyimpanan . "'";
                } else {
                    $q = "insert into rm_stock_obat_apotik (
                      id_obat, 
                      id_ruang, 
                      id_penyimpanan,
                      stock_baru,
                      stock,
                      tgl_kadaluarsa_baru
                  ) values (
                      '" . $id_obat . "',
                      '" . $_SESSION['level'] . "',
                      '" . $id_penyimpanan . "',
                      '" . $jmlObat . "', 
                      '" . $jmlObat . "', 
                      '" . $this->formatDateDb($tgl_kadaluarsa_baru) . "'
                  )";
                }

                $r = $this->runQuery($q);

                if ($r) {
                    $q_distribusi = "update rm_distribusi_obat set status='1' where id_distribusi_obat='" . $id_distribusi_obat . "'";
                    $r_distribusi = $this->runQuery($q_distribusi);

                    if ($r_distribusi) {
                        $return = "1";
                    } else {
                        $return = "2";
                    }
                } else {
                    $return = "0";
                }
            }
            return $return;
        } else {
            return 'LOGIN';
        }
    }

    public function cancelKirim($id_dist) {

        $q_stock = "UPDATE rm_distribusi_obat SET del_flag='1' WHERE id_distribusi_obat='" . $id_dist . "'";
        $r_stock = $this->runQuery($q_stock);

        if ($r_stock) {
            $return = "1";
        } else {
            $return = "0";
        }
        return $return;
    }

    public function hapusBalance($id_dist) {

        $q_stock = "UPDATE rm_obat_balance SET del_flag='1'";
        $r_stock = $this->runQuery($q_stock);

        if ($r_stock) {
            $return = "1";
        } else {
            $return = "0";
        }
        return $return;
    }

    public function hapusFakturObat($id_faktur) {
        $query = "select * from rm_faktur where id_faktur='" . $id_faktur . "' and status_assign!='1'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            $q_update = "update rm_faktur set del_flag='1' where id_faktur='" . $id_faktur . "'";
            $r_update = $this->runQuery($q_update);

            if ($r_update) {
                $q_all = "update rm_pembelian_obat set del_flag='1' where id_faktur='" . $id_faktur . "'";
                $this->runQuery($q_all);
                return '1';
            } else {
                return '0';
            }
        } else {
            return '2';
        }
    }

    public function hapusDetailObat($id_pembelian_obat) {
        $query = "select * from rm_faktur where id_faktur=(
                    select id_faktur from rm_pembelian_obat where id_pembelian_obat='" . $id_pembelian_obat . "'
                  ) and status_assign!='1'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            $q_all = "update rm_pembelian_obat set del_flag='1' where id_pembelian_obat='" . $id_pembelian_obat . "'";
            $r_all = $this->runQuery($q_all);

            if ($r_all) {
                return '1';
            } else {
                return '0';
            }
        } else {
            return '2';
        }
    }

    public function getHargaObat($id) {
        $query = "select hpp from rm_tarif_obat where id_obat='" . $id . "'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            return @mysql_result($result, 0, hpp);
        } else {
            return '0';
        }
    }

    public function simpanMasterObat($id_obat, $kode_obat, $nama_obat) {
        if ($id_obat == "") {
            $duplikat = "SELECT COUNT(*) as jml, nama_obat FROM rm_obat where kode_obat='" . $kode_obat . "' AND del_flag<>1";
            $run = $this->runQuery($duplikat);
            if (@mysql_result($run, 0, 'jml') == 0) {
                $query = "insert into rm_obat(kode_obat, nama_obat) values ('" . $kode_obat . "', '" . $nama_obat . "')";
            } else {
                return 'DUPLIKAT:' . @mysql_result($run, 0, 'nama_obat');
            }
        } else {
            $query = "update rm_obat set kode_obat='" . $kode_obat . "', nama_obat='" . $nama_obat . "' where id_obat='" . $id_obat . "'";
        }
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function hapusMasterObat($id_obat) {
        $query = "update rm_obat set del_flag='1' where id_obat='" . $id_obat . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function simpanFaktur(
    $no_faktur, $tgl_pembelian, $tgl_jatuh_tempo, $supplier
    ) {
        if (isset($_SESSION['nip'])) {
            $query = "insert into rm_faktur(
                    no_faktur,
                    id_supplier,
                    tgl_pembelian,
                    tgl_jatuh_tempo,
                    operator,
                    ip
                 ) values (
                    '" . $no_faktur . "',
                    '" . $supplier . "',
                    '" . $this->formatDateDb($tgl_pembelian) . "',
                    '" . $this->formatDateDb($tgl_jatuh_tempo) . "',
                    '" . $_SESSION['nip'] . "',
                    '" . $_SERVER['REMOTE_ADDR'] . "'
                 )";

            $result = $this->runQuery($query);

            if ($result) {
                $q_id = "select max(id_faktur) as id_faktur from rm_faktur";
                $r_id = $this->runQuery($q_id);
                return @mysql_result($r_id, 0, 'id_faktur');
            } else {
                return '0';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanBeliObat(
    $id_pembelian_obat, $id_faktur, $id_obat, $penyimpanan, $qty, $harga, $retur, $diskon, $pajak, $tgl_kadaluarsa
    ) {
        if (isset($_SESSION['nip'])) {
            if ($this->checkDistObat($id_faktur)) {
                if ($id_pembelian_obat == '') {
                    $duplikat = "SELECT COUNT(*) as jml FROM rm_pembelian_obat WHERE id_obat = " . $id_obat . " AND id_faktur=" . $id_faktur . " AND del_flag<>1";
                    $run = $this->runQuery($duplikat);
                    if (@mysql_result($run, 0, 'jml') == 0) {
                        $query = "insert into rm_pembelian_obat(
                            id_faktur,
                            id_obat,
                            id_penyimpanan,
                            harga,
                            qty,
                            retur,
                            diskon,
                            pajak,
                            tgl_kadaluarsa,
                            operator,
                            ip
                         ) values (
                            '" . $id_faktur . "',
                            '" . $id_obat . "',
                            '" . $penyimpanan . "',
                            '" . $harga . "',
                            '" . $qty . "',
                            '" . $retur . "',
                            '" . $diskon . "',
                            '" . $pajak . "',
                            '" . $this->formatDateDb($tgl_kadaluarsa) . "',
                            '" . $_SESSION['nip'] . "',
                            '" . $_SERVER['REMOTE_ADDR'] . "'
                         )";
                    } else {
                        return 'DUPLIKAT';
                    }
                } else {
                    $query = "update rm_pembelian_obat set
                            id_faktur='" . $id_faktur . "',
                            id_obat='" . $id_obat . "',
                            id_penyimpanan='" . $penyimpanan . "',
                            harga='" . $harga . "',
                            qty='" . $qty . "',
                            retur='" . $retur . "',
                            diskon='" . $diskon . "',
                            pajak='" . $pajak . "',
                            tgl_kadaluarsa='" . $this->formatDateDb($tgl_kadaluarsa) . "'
                         where id_pembelian_obat='" . $id_pembelian_obat . "'";
                }

                $result = $this->runQuery($query);

                if ($result) {
                    if ($id_pembelian_obat == '') {
                        $q_obat = "select max(id_pembelian_obat) as id_obat from rm_pembelian_obat where del_flag<>'1'";
                        $r_obat = $this->runQuery($q_obat);

                        $id_pembelian_obat = @mysql_result($r_obat, 0, 'id_obat');
                    }
                    return $id_pembelian_obat;
                } else {
                    return '0';
                }
            } else {
                return '-1';
            }
        } else {
            return 'LOGIN';
        }
    }

}

?>
