<?php

session_start();
require_once '../../common/function.php';

class cApotik extends fungsi {

    public function simpanFakturPenjualan(
    $no_resep, $jns_customer, $id_pasien, $id_ruang, $dokter, $nama_pasien, $alamat, $idp, $idr
    ) {
        if (isset($_SESSION['level'])) {
            $tipe_pasien = $this->getTipePasienId($id_pasien);
            $query = "insert into rm_faktur_penjualan(
                    id_ruang,
                    no_resep,
                    jns_customer,
                    id_pasien,
                    ruang,
                    id_dokter,
                    nama_pasien,
                    level,
                    alamat,
                    id_tipe_pasien,
                    id_ruang_px,
                    id_pendaftaran,
                    ip
                 ) values (
                    '" . $_SESSION['level'] . "',
                    '" . $no_resep . "',
                    '" . $jns_customer . "',
                    '" . $id_pasien . "',
                    '" . $id_ruang . "',
                    '" . $dokter . "',
                    '" . @mysql_escape_string($nama_pasien) . "',
                    '" . $_SESSION['nip'] . "',
                    '" . @mysql_escape_string($alamat) . "',
                    '" . $tipe_pasien . "',
                    '" . $idr . "',
                    '" . $idp . "',
                    '" . $_SERVER['REMOTE_ADDR'] . "'
                 )";

            $result = $this->runQuery($query);

            if ($result) {
                $q_id = "select max(id_faktur_penjualan) as id_faktur from rm_faktur_penjualan where id_pasien='" . $id_pasien . "'";
                $r_id = $this->runQuery($q_id);
                return @mysql_result($r_id, 0, 'id_faktur');
            } else {
                return '0';
            }
        }
        return 'LOGIN';
    }

    public function hapusPembayaranJualObat($id_faktur_penjualan) {
        $query = "UPDATE rm_faktur_penjualan SET status = '0' WHERE id_faktur_penjualan ='" . $id_faktur_penjualan . "'";
        $this->runQuery($query);

        $query = "UPDATE rm_pembayaran_obat SET del_flag = '1' WHERE id_faktur_penjualan ='" . $id_faktur_penjualan . "' AND tipe_pembayaran='Lunas'";
        $this->runQuery($query);
    }

    public function cetakKW($id_faktur_penjualan) {

        $q_cek = " SELECT id_faktur_penjualan as id FROM rm_faktur_penjualan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' 
                   AND id_ruang='" . $_SESSION['level'] . "' AND del_flag<>'1'";
        $r_cek = $this->runQuery($q_cek);

        if (@mysql_num_rows($r_cek) > 0) {
            $q_bayar = "select max(id_pembayaran_obat) as idBayar from rm_pembayaran_obat 
                        where id_faktur_penjualan='" . @mysql_result($r_cek, 0, 'id') . "' AND del_flag<>'1'";
            $r_bayar = $this->runQuery($q_bayar);

            if ($r_bayar)
                $return = @mysql_result($r_bayar, 0, 'idBayar');
            else
                $return = 'E';
        } else {
            $return = 'N';
        }
        return $return;
    }

    public function cetakKWAll($id_faktur_penjualan) {

        $q_cek = " SELECT id_faktur_penjualan as id FROM rm_faktur_penjualan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' 
                   AND del_flag<>'1'";
        $r_cek = $this->runQuery($q_cek);

        if (@mysql_num_rows($r_cek) > 0) {
            $q_bayar = "select max(id_pembayaran_obat) as idBayar from rm_pembayaran_obat 
                        where id_faktur_penjualan=" . @mysql_result($r_cek, 0, 'id') . " AND del_flag<>'1'";
            $r_bayar = $this->runQuery($q_bayar);

            if (@mysql_result($r_bayar, 0, 'idBayar') > 0)
                $return = @mysql_result($r_bayar, 0, 'idBayar');
            else
                $return = 'E';
        } else {
            $return = 'N';
        }
        return $return;
    }

    public function simpanDetailPenjualan(
    $id_faktur_penjualan, $id_obat, $qty, $harga, $r_code
    ) {
        if (isset($_SESSION['level'])) {
            $duplikat = "SELECT COUNT(*) as jml FROM rm_penjualan_obat where id_obat=" . $id_obat . " AND id_faktur_penjualan=" . $id_faktur_penjualan . " AND del_flag<>1";
            $run = $this->runQuery($duplikat);
            if (@mysql_result($run, 0, 'jml') == 0) {
                $q_cek = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' and id_ruang='" . $_SESSION['level'] . "'";
                $r_cek = $this->runQuery($q_cek);

                if (@mysql_num_rows($r_cek) > 0) {
                    $query = "insert into rm_penjualan_obat(
                        id_faktur_penjualan,
                        id_obat,
                        qty,
                        harga,
                        r_code,
                        ip
                     ) values (
                        '" . $id_faktur_penjualan . "',
                        '" . $id_obat . "',
                        '" . $qty . "',
                        '" . $harga . "',
                        '" . $r_code . "',
                        '" . $_SERVER['REMOTE_ADDR'] . "'
                     )";

                    $sisa = @mysql_result($r_cek, 0, 'stock');
                    $stok_lama = @mysql_result($r_cek, 0, 'stock_lama');
                    $stok_baru = @mysql_result($r_cek, 0, 'stock_baru');
                    $stok_limit = @mysql_result($r_cek, 0, 'stock_limit');
                    if ($stok_lama < $qty) {
                        $sisa = ($stok_lama + $stok_baru) - $qty;
                        $stok_lama = 0;
                        $stok_baru = $sisa;
                    } else if ($stok_lama == 0) {
                        $sisa = $stok_baru - $qty;
                        $stok_baru = $sisa;
                    } else {
                        $stok_lama = $stok_lama - $qty;
                        $sisa = $stok_baru + $stok_lama;
                    }
                    if ($sisa < 0) {
                        $return = "ERROR:Stock tidak mencukupi";
                    } else if ($sisa == 0) {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            $return = "WARNING:Stock Habis";
                        } else {
                            $return = "ERROR";
                        }
                    } else if ($sisa <= $stok_limit) {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            $return = "WARNING:Sisa stock " . $sisa . ", Stock mencapai limit";
                        } else {
                            $return = "ERROR";
                        }
                    } else {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            $return = "TRUE";
                        } else {
                            $return = "ERROR";
                        }
                    }
                } else {
                    $return = "ERROR:Stock Obat belum di distribusikan.";
                }

                return $return;
            } else {
                return 'DUPLIKAT';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanDetailPenjualanUpdate(
    $id_faktur_penjualan, $id_obat, $qty, $harga, $r_code, $id_penjualan_obat
    ) {
        if (isset($_SESSION['level'])) {
            $duplikat = "SELECT COUNT(*) as jml FROM rm_penjualan_obat where id_obat=" . $id_obat . " AND id_faktur_penjualan=" . $id_faktur_penjualan . " AND del_flag<>1";
            $run = $this->runQuery($duplikat);
            if (@mysql_result($run, 0, 'jml') == 0) {
                $q_cek = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' and id_ruang='" . $_SESSION['level'] . "'";
                $r_cek = $this->runQuery($q_cek);

                if (@mysql_num_rows($r_cek) > 0) {
                    $query = "insert into rm_penjualan_obat(
                        id_faktur_penjualan,
                        id_obat,
                        qty,
                        harga,
                        r_code,
                        ip
                     ) values (
                        '" . $id_faktur_penjualan . "',
                        '" . $id_obat . "',
                        '" . $qty . "',
                        '" . $harga . "',
                        '" . $r_code . "',
                        '" . $_SERVER['REMOTE_ADDR'] . "'
                     )";

                    $sisa = @mysql_result($r_cek, 0, 'stock');
                    $stok_lama = @mysql_result($r_cek, 0, 'stock_lama');
                    $stok_baru = @mysql_result($r_cek, 0, 'stock_baru');
                    $stok_limit = @mysql_result($r_cek, 0, 'stock_limit');
                    if ($stok_lama < $qty) {
                        $sisa = ($stok_lama + $stok_baru) - $qty;
                        $stok_lama = 0;
                        $stok_baru = $sisa;
                    } else if ($stok_lama == 0) {
                        $sisa = $stok_baru - $qty;
                        $stok_baru = $sisa;
                    } else {
                        $stok_lama = $stok_lama - $qty;
                        $sisa = $stok_baru + $stok_lama;
                    }
                    if ($sisa < 0) {
                        $return = "ERROR:Stock tidak mencukupi";
                    } else if ($sisa == 0) {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            if ($this->hapusDetailObat($id_penjualan_obat, $id_faktur_penjualan) == 1)
                                $return = "WARNING:Stock Habis";
                        } else {
                            $return = "ERROR";
                        }
                    } else if ($sisa <= $stok_limit) {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            if ($this->hapusDetailObat($id_penjualan_obat, $id_faktur_penjualan) == 1)
                                $return = "WARNING:Sisa stock " . $sisa . ", Stock mencapai limit";
                        } else {
                            $return = "ERROR";
                        }
                    } else {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            if ($this->hapusDetailObat($id_penjualan_obat, $id_faktur_penjualan) == 1)
                                $return = "TRUE";
                        } else {
                            $return = "ERROR";
                        }
                    }
                } else {
                    $return = "ERROR:Stock Obat belum di distribusikan.";
                }

                return $return;
            } else {
                return 'DUPLIKAT';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function updateDetailPenjualan(
    $id_faktur_penjualan, $id_penjualan_obat, $id_obat, $qty, $harga, $r_code
    ) {
        if (isset($_SESSION['level'])) {
            if ($this->checkBayarObat($id_faktur_penjualan)) {
                $q_obat = "select * from rm_penjualan_obat where id_penjualan_obat='" . $id_penjualan_obat . "'";
                $r_obat = $this->runQuery($q_obat);

                if (@mysql_num_rows($r_obat) > 0) {
                    if (@mysql_result($r_obat, 0, 'id_obat') != $id_obat) {
                        echo $this->simpanDetailPenjualanUpdate($id_faktur_penjualan, $id_obat, $qty, $harga, $r_code, $id_penjualan_obat);
                    } else {
                        if (@mysql_result($r_obat, 0, 'qty') >= $qty) {
                            $query = "update rm_penjualan_obat set
                                id_obat='" . $id_obat . "',
                                qty='" . $qty . "',
                                harga='" . $harga . "',
                                ip='" . $_SERVER['REMOTE_ADDR'] . "',
                                r_code='" . $r_code . "'
                             where id_penjualan_obat='" . $id_penjualan_obat . "'";
                            $result = $this->runQuery($query);
                            if ($result)
                                $return = "TRUE";
                            else
                                $return = "ERROR";
                        } else {
                            $q_cek = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' and id_ruang='" . $_SESSION['level'] . "'";
                            $r_cek = $this->runQuery($q_cek);

                            if (@mysql_result($r_cek, 0, 'stock') >= ($qty - @mysql_result($r_obat, 0, 'qty'))) {
                                $query = "update rm_penjualan_obat set
                                    id_obat='" . $id_obat . "',
                                    qty='" . $qty . "',
                                    harga='" . $harga . "',
                                    ip='" . $_SERVER['REMOTE_ADDR'] . "',
                                    r_code='" . $r_code . "'
                                 where id_penjualan_obat='" . $id_penjualan_obat . "'";
                                $result = $this->runQuery($query);
                                if ($result)
                                    $return = "TRUE";
                                else
                                    $return = "ERROR";
                            } else {
                                $return = "ERROR:Stock tidak mencukupi";
                            }
                        }
                    }
                } else {
                    $return = "ERROR";
                }
            } else {
                $return = "BAYAR";
            }

            return $return;
        } else {
            return 'LOGIN';
        }
    }

    public function simpanReturObat(
    $id_faktur_penjualan, $id_obat, $jns_retur, $pros_retur, $jumlah, $id_jual
    ) {
        if (isset($_SESSION['level'])) {
            $cf = "SELECT id_pendaftaran as idp FROM rm_faktur_penjualan WHERE del_flag<>1 AND id_faktur_penjualan=" . $id_faktur_penjualan . "";
            $rcf = $this->runQuery($cf);
            if ($this->checkStatusPembayaran(@mysql_result($rcf, 0, 'idp')) && @mysql_result($rcf, 0, 'idp') > 0)
                $rawat = 1;
            else
                $rawat = 0;

            $q_cek = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' AND id_ruang=" . $_SESSION['level'] . "";
            $r_cek = $this->runQuery($q_cek);

            $query = "insert into rm_retur_penjualan_obat (
                    id_faktur_penjualan,
                    id_penjualan_obat,
                    id_obat,
                    pros_retur,
                    jns_retur,
                    jumlah,
                    ip,
                    rawat
                 ) values (
                    '" . $id_faktur_penjualan . "',
                    '" . $id_jual . "',
                    '" . $id_obat . "',
                    '" . $pros_retur . "',
                    '" . $jns_retur . "',
                    '" . $jumlah . "',
                    '" . $_SERVER['REMOTE_ADDR'] . "',
                    '" . $rawat . "'
                 )";

            $sisa = @mysql_result($r_cek, 0, 'stock');
            $stok_baru = @mysql_result($r_cek, 0, 'stock_baru') + $jumlah;
            $stoka = $sisa + $jumlah;

            $result = $this->runQuery($query);
            $q = "update rm_stock_obat_apotik set
                  stock_baru='" . $stok_baru . "',
                  stock='" . $stoka . "'
              where 
                  id_obat='" . $id_obat . "'
                  and id_ruang='" . $_SESSION['level'] . "'";
            $r = $this->runQuery($q);

            if ($result) {
                $return = "1";
            } else {
                $return = "0";
            }

            return $return;
        } else {
            return 'LOGIN';
        }
    }

    public function cekKadaluarsa($id_obat) {

        $q_cek = "select date(tgl_kadaluarsa_baru) as tgl_kadaluarsa from rm_stock_obat_apotik where id_obat='" . $id_obat . "' and id_ruang='" . $_SESSION['level'] . "'";
        $r_cek = $this->runQuery($q_cek);

        if ($r_cek) {
            $jmlHari = $this->jmlHari(date('Y-m-d'), @mysql_result($r_cek, 0, 'tgl_kadaluarsa')) + 1;

            if ($jmlHari <= 7) {
                $return = "Tanggal Kadaluarsa Obat jatuh pada <b>" . $this->codeDate(@mysql_result($r_cek, 0, 'tgl_kadaluarsa')) . "</b>";
            } else {
                $return = "";
            }
        }

        return $return;
    }

    public function simpanDetailRacikan(
    $id_racikan, $id_obat, $qty, $harga, $r_code
    ) {
        if (isset($_SESSION['level'])) {
            $duplikat = "SELECT COUNT(*) as jml FROM rm_detail_racikan where id_obat=" . $id_obat . " AND id_racikan=" . $id_racikan . " AND del_flag<>1";
            $run = $this->runQuery($duplikat);
            if (@mysql_result($run, 0, 'jml') == 0) {
                $q_cek = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' and id_ruang='" . $_SESSION['level'] . "'";
                $r_cek = $this->runQuery($q_cek);

                if (@mysql_num_rows($r_cek) > 0) {
                    $query = "insert into rm_detail_racikan(
                        id_racikan,
                        id_obat,
                        qty,
                        harga,
                        r_code
                     ) values (
                        '" . $id_racikan . "',
                        '" . $id_obat . "',
                        '" . $qty . "',
                        '" . $harga . "',
                        '" . $r_code . "'
                     )";

                    $sisa = @mysql_result($r_cek, 0, 'stock');
                    $stok_lama = @mysql_result($r_cek, 0, 'stock_lama');
                    $stok_baru = @mysql_result($r_cek, 0, 'stock_baru');
                    $stok_limit = @mysql_result($r_cek, 0, 'stock_limit');
                    if ($stok_lama < $qty) {
                        $sisa = $stok_lama - $qty + $stok_baru;
                        $stok_lama = 0;
                        $stok_baru = $sisa;
                    } else if ($stok_lama == 0) {
                        $sisa = $stok_baru - $qty;
                        $stok_baru = $sisa;
                    } else {
                        $stok_lama = $stok_lama - $qty;
                        $sisa = $stok_baru + $stok_lama;
                    }
                    if ($sisa < 0) {
                        $return = "ERROR:Stock tidak mencukupi";
                    } else if ($sisa == 0) {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            $return = "WARNING:Stock Habis";
                        } else {
                            $return = "ERROR";
                        }
                    } else if ($sisa <= $stok_limit) {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            $return = "WARNING:Stock mencapai limit";
                        } else {
                            $return = "ERROR";
                        }
                    } else {
                        $result = $this->runQuery($query);
                        $q = "update rm_stock_obat_apotik set
                          stock_lama='" . $stok_lama . "', 
                          stock_baru='" . $stok_baru . "',
                          stock='" . $sisa . "'
                      where 
                          id_obat='" . $id_obat . "'
                          and id_ruang='" . $_SESSION['level'] . "'";
                        $r = $this->runQuery($q);

                        if ($r) {
                            $return = "TRUE";
                        } else {
                            $return = "ERROR";
                        }
                    }
                } else {
                    $return = "ERROR:Stock Obat belum di distribusikan.";
                }

                return $return;
            } else {
                return 'DUPLIKAT';
            }
        } else {
            return 'LOGIN';
        }
    }

    public function simpanPembayaranObat(
    $id_faktur_penjualan, $total, $diskon, $bayar, $asuransi, $sisa
    ) {
        if (isset($_SESSION['level'])) {
            $sisa = $total - $asuransi - $bayar - $diskon;
            $sisa = round($sisa);

            if ($sisa > 0)
                $tipe_pembayaran = 'Kredit';
            else
                $tipe_pembayaran = 'Lunas';

            if ($bayar > ($total - ($asuransi + $diskon))) {
                $kembali = $bayar - $total + $asuransi + $diskon;
                $bayar = $bayar - $kembali;
            } else {
                $kembali = 0;
            }

            if ($sisa < 0)
                $sisa = 0;

            $q_jml = "select count(*) as jmlBayar from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1' ";
            $r_jml = $this->runQuery($q_jml);
            $ke = @mysql_result($r_jml, 0, 'jmlBayar') + 1;

            if (($ke == 1) || ($ke == 2 && $tipe_pembayaran == 'Lunas')) {
                $query = "insert into rm_pembayaran_obat(
                    id_faktur_penjualan,
                    tipe_pembayaran,
                    pembayaran_ke,
                    total,
                    diskon,
                    bayar,
                    kembali,
                    asuransi,
                    level,
                    sisa,
                    id_ruang,
                    ip
                 ) values (
                    '" . $id_faktur_penjualan . "',
                    '" . $tipe_pembayaran . "',
                    '" . $ke . "',
                    '" . $total . "',
                    '" . $diskon . "',
                    '" . $bayar . "',
                    '" . $kembali . "',
                    '" . $asuransi . "',
                    '" . $_SESSION['nip'] . "',
                    '" . $sisa . "',
                    '" . $_SESSION['level'] . "',
                    '" . $_SERVER['REMOTE_ADDR'] . "'
                 )";
                $result = $this->runQuery($query);

                if ($result) {
                    if ($tipe_pembayaran == 'Lunas')
                        $status = '2';
                    else
                        $status = '1';

                    $q_faktur = "update rm_faktur_penjualan set status='" . $status . "' 
                         where id_faktur_penjualan='" . $id_faktur_penjualan . "'";
                    $r_faktur = $this->runQuery($q_faktur);

                    $q_bayar = "select max(id_pembayaran_obat) as idBayar from rm_pembayaran_obat 
                        where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
                    $r_bayar = $this->runQuery($q_bayar);

                    $this->setJumlahObat($id_faktur_penjualan);

                    if ($r_faktur)
                        $return = @mysql_result($r_bayar, 0, 'idBayar');
                    else
                        $return = '0';
                } else {
                    $return = '0';
                }
            } else {
                $return = 'KREDIT';
            }

            return $return;
        }
        return 'LOGIN';
    }

    public function simpanRacikan($id_faktur_penjualan, $racikan) {
        $query = "insert into rm_racikan (id_faktur_penjualan, racikan) values ('" . $id_faktur_penjualan . "', '" . @mysql_escape_string($racikan) . "')";
        $result = $this->runQuery($query);

        if ($result) {
            $q_racikan = "select max(id_racikan) as id_racikan from rm_racikan";
            $r_racikan = $this->runQuery($q_racikan);

            if ($r_racikan)
                $return = @mysql_result($r_racikan, 0, 'id_racikan');
            else
                $return = 0;
        } else {
            $return = 0;
        }

        return $return;
    }

    public function getHargaObat($id_obat, $ruang, $id_pasien) {
        if ($id_pasien == '') {
            $return = $this->getHargaObatField($id_obat, 'umum');
        } else {
            if ($ruang != "") {
                $tipe_tarif = $this->getTipePasienId($id_pasien);
                $field = $this->getTipeTarif($tipe_tarif);
                $return = $this->getHargaObatField($id_obat, $field);
            } else {
                $return = $this->getHargaObatField($id_obat, 'umum');
            }
        }

        return $return;
    }

    public function getStsk() {
        $query = "select sum(bayar) as bayar from rm_pembayaran_tagihan where level='" . $_SESSION['nip'] . "' and status='0' and del_flag<>1";
        $result = $this->runQuery($query);
        $totalTagihan = @mysql_result($result, 0, 'bayar');

        $query = "select sum(bayar) as bayar from rm_pembayaran_obat where level='" . $_SESSION['nip'] . "' and status='0' AND del_flag<>'1'";
        $result = $this->runQuery($query);
        $totalObat = @mysql_result($result, 0, 'bayar');

        $q_no_stsk = "select max(id_setoran) as id_setoran from rm_setoran_kasir";
        $r_no_stsk = $this->runQuery($q_no_stsk);
        $id_setoran = @mysql_result($r_no_stsk, 0, 'id_setoran') + 1;

        $html = '<table style="font-family: verdana;font-size: 10pt;" class="data" width="100%" bgcolor="#000000" cellspacing="1" cellpadding="2">
              <tr>
                <td width="30%" align="center" bgcolor="#FFFFFF"><b>STSK</b></td>
                <td align="center" bgcolor="#FFFFFF"><b>Surat Tanda Setoran Kasir</b></td>
              </tr>
              <tr>
                <td width="30%" valign="top" bgcolor="#FFFFFF">
                            No. : <b>' . $id_setoran . '</b><br />
                            Tgl. : <b>' . $this->codeDate(date('Y-m-d')) . '</b><br />
                            <br />
                            Diterima Dari Kasir :<b>' . $_SESSION['nama_pegawai'] . '</b><br />
                            <br />
                            Sejumlah :<br />
                            <b>Rp. ' . number_format(($totalObat + $totalTagihan), 2, ',', '.') . '</b><br />
                            <br />
                            Penyetor,
                            <br /><br /><br /><br />
                            <b><u>' . $_SESSION['nama_pegawai'] . '</u></b> <br />
                            Kasir
                    </td>
                <td valign="top" bgcolor="#FFFFFF"><table width="100%" border=0 cellspacing="1" cellpadding="3">
                  <tr>
                    <td width="20%">Nomor</td>
                    <td width="1%">:</td>
                    <td><b>' . $id_setoran . '</b></td>
                  </tr>
                  <tr>
                    <td width="20%">Tanggal</td>
                    <td width="1%">:</td>
                    <td><b>' . $this->codeDate(date('Y-m-d')) . '</b></td>
                  </tr>
                  <tr>
                    <td width="20%">Diterima dari Kasir </td>
                    <td width="1%">:</td>
                    <td><b>' . $_SESSION['nama_pegawai'] . '</b></td>
                  </tr>
                  <tr valign="top">
                    <td width="20%">Sejumlah</td>
                    <td width="1%">:</td>
                    <td><b>' . $this->pembilang(round(($totalObat + $totalTagihan))) . ' Rupiah</b></td>
                  </tr>
                  <tr>
                    <td width="20%">&nbsp;</td>
                    <td width="1%">&nbsp;</td>
                    <td><b>Rp. ' . number_format(($totalObat + $totalTagihan), 2, ',', '.') . '</b></td>
                  </tr>
                  <tr>
                    <td width="20%">
                            Penyetor
                            <br /><br /><br /><br />
                            <b><u>' . $_SESSION['nama_pegawai'] . '</u></b><br>Kasir
                            </td>
                    <td width="1%">&nbsp;</td>
                    <td>
                            Penerima
                            <br />
                            <br /><br /><br />
                            ___________________________<br>
                            Bendahara
                            </td>
                  </tr>
                </table></td>
              </tr>
            </table>';

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getLaporanPenjualanHarian($shift, $startDate, $tglAkhir) {
        //tunai
        $kondisi = "";
        $kondAsuransi = "";
        $kondisi_all = "";
        $kondisi_disc = "";
        if ($shift != "") {
            $q_shift = "select * from rm_shift where shift='" . $shift . "'";
            $r_shift = $this->runQuery($q_shift);
            if ($shift == "M") {
                $q_interval = "SELECT DATE_ADD('" . $this->formatDateDb($tglAkhir) . "', INTERVAL 1 DAY) as new";
                $r_int = $this->runQuery($q_interval);
                $endDate = $this->formatDateDb(@mysql_result($r_int, 0, 'new'));
            } else {
                $endDate = $tglAkhir;
            }
            $kondisi .= " AND tgl_penjualan BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
            $kondAsuransi .= " AND tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
            $kondisi_all .= " AND a.tgl_penjualan BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
            $kondisi_disc .= " AND tgl_create BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
            $kondisi_retur = " AND tgl_retur BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
        } else {
            $q_interval = "SELECT DATE_ADD('" . $this->formatDateDb($tglAkhir) . "', INTERVAL 1 DAY) as new";
            $r_int = $this->runQuery($q_interval);
            $endDate = $this->formatDateDb(@mysql_result($r_int, 0, 'new'));
            $kondisi .= " AND tgl_penjualan BETWEEN '" . $this->formatDateDb($startDate) . " 06:30:00' AND '" . $this->formatDateDb($endDate) . " 06:30:00'";
            $kondAsuransi .= " AND tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . " 06:30:00' AND '" . $this->formatDateDb($endDate) . " 06:30:00'";
            $kondisi_all .= " AND a.tgl_penjualan BETWEEN '" . $this->formatDateDb($startDate) . " 06:30:00' AND '" . $this->formatDateDb($endDate) . " 06:30:00'";
            $kondisi_disc .= " AND tgl_create BETWEEN '" . $this->formatDateDb($startDate) . " 06:30:00' AND '" . $this->formatDateDb($endDate) . " 06:30:00'";
            $kondisi_retur = " AND tgl_retur BETWEEN '" . $this->formatDateDb($startDate) . " 06:30:00' AND '" . $this->formatDateDb($endDate) . " 06:30:00'";
        }

        $q_jml_faktur = "SELECT COUNT(*) as jmlFaktur FROM rm_faktur_penjualan where del_flag<>'1' and id_faktur_penjualan in (select id_faktur_penjualan from rm_pembayaran_obat) and id_ruang='" . $_SESSION['level'] . "' " . $kondisi;
        $r_jml_faktur = $this->runQuery($q_jml_faktur);
        $jml_faktur = @mysql_result($r_jml_faktur, 0, 'jmlFaktur');
        $q_jml_obat = "SELECT COUNT(*) as jmlItem FROM rm_faktur_penjualan a, rm_penjualan_obat b 
                       where b.del_flag<>'1' and a.del_flag<>'1' and a.id_ruang='" . $_SESSION['level'] . "' and a.id_faktur_penjualan in (select id_faktur_penjualan from rm_pembayaran_obat) and b.id_faktur_penjualan=a.id_faktur_penjualan " . $kondisi;
        $r_jml_obat = $this->runQuery($q_jml_obat);
        $jml_obat = @mysql_result($r_jml_obat, 0, 'jmlItem');
        $q_jml_obat = "SELECT COUNT(b.id_obat) AS jmlItem FROM rm_faktur_penjualan a, rm_detail_racikan b, rm_racikan c
                       WHERE a.del_flag<>'1' AND b.del_flag<>'1' AND c.del_flag<>'1' and a.id_ruang='" . $_SESSION['level'] . "' AND b.id_racikan=c.id_racikan 
                       AND c.id_faktur_penjualan=a.id_faktur_penjualan " . $kondisi;
        $r_jml_obat = $this->runQuery($q_jml_obat);
        $jml_obat += @ mysql_result($r_jml_obat, 0, 'jmlItem');

        $q_status = "SELECT a.id_faktur_penjualan, SUM(b.sum) AS jml
                     FROM rm_faktur_penjualan a, rm_pembayaran_obat b
                     WHERE a.id_ruang='" . $_SESSION['level'] . "' AND b.id_faktur_penjualan=a.id_faktur_penjualan AND b.del_flag<>'1'" . $kondisi_all . "
                     GROUP BY a.id_faktur_penjualan";
        $r_status = $this->runQuery($q_status);
        $jmlRecord = @mysql_num_rows($r_status);
        $id_faktur_penjualan = "";
        $i = 1;
        $jmlAllFaktur = 0;
        $q_bayar = "select sum(bayar) as bayar, sum(diskon) as diskon from rm_faktur_penjualan a, rm_pembayaran_obat b 
                    where b.id_faktur_penjualan=a.id_faktur_penjualan and a.id_ruang='" . $_SESSION['level'] . "' AND b.del_flag<>'1' " . $kondAsuransi;
        $r_bayar = $this->runQuery($q_bayar);
        $setoran_tunai = round(@mysql_result($r_bayar, 0, 'bayar') + @mysql_result($r_bayar, 0, 'diskon'), 2);
        $q_retur = "SELECT a.id_obat, jumlah, pros_retur, harga, r_code, c.id_faktur_penjualan
                    FROM rm_retur_penjualan_obat a, rm_penjualan_obat b, rm_faktur_penjualan c
                    WHERE b.id_faktur_penjualan=a.id_faktur_penjualan and a.del_flag<>'1' and b.del_flag<>'1' and c.id_faktur_penjualan = a.id_faktur_penjualan
                    and c.id_ruang = '" . $_SESSION['level'] . "' and jns_retur='1' AND b.id_penjualan_obat=a.id_penjualan_obat " . $kondisi_retur . " group by a.id_retur_penjualan_obat";
        $r_retur = $this->runQuery($q_retur);
        $setoran_retur_tunai = 0;
        $diskon_retur_tunai = 0;
        while ($ret = @mysql_fetch_array($r_retur)) {
            $setoran_retur_tunai += ( $ret['jumlah'] * $ret['harga']);
            if ($ret['pros_retur'] <> 0)
                $diskon_retur_tunai += ( $ret['jumlah'] * $ret['harga']) * $ret['pros_retur'];
        }

        $diskon_tunai = 0;
        $q_diskon = "SELECT sum(diskon) as diskon FROM rm_diskon_obat WHERE id_faktur in (SELECT a.id_faktur_penjualan FROM rm_faktur_penjualan a,
                    rm_pembayaran_obat b WHERE a.id_ruang='" . $_SESSION['level'] . "' AND 
                    b.id_faktur_penjualan=a.id_faktur_penjualan AND b.del_flag<>'1' AND a.del_flag<>1 " . $kondisi_all . " GROUP BY a.id_faktur_penjualan) AND del_flag<>1 AND status='2' " . $kondisi_disc;
        $r_diskon = $this->runQuery($q_diskon);
        $diskon_tunai = @mysql_result($r_diskon, 0, 'diskon');
        $q_status = "SELECT id_faktur_penjualan from rm_faktur_penjualan where status='1' and id_ruang='" . $_SESSION['level'] . "'";
        $r_status = $this->runQuery($q_status);
        $jmlRecord = @mysql_num_rows($r_status);
        $id_faktur_penjualan = "";
        $i = 1;
        while ($rec = @mysql_fetch_array($r_status)) {
            $id_faktur_penjualan .= "'" . $rec['id_faktur_penjualan'] . "'";
            if ($i < $jmlRecord)
                $id_faktur_penjualan .= ", ";
            $i++;
        }
        $setoran_kredit = 0;
        $q_kredit = "select id_faktur_penjualan, id_pasien, nama_pasien, ruang, id_dokter, status
                  from rm_faktur_penjualan
                  where status=1 and del_flag<>'1' " . $kondisi . " and id_ruang='" . $_SESSION['level'] . "'
                  and id_faktur_penjualan not in (select id_faktur_penjualan from rm_pembayaran_obat 
                  where del_flag<>'1' " . $kondAsuransi . "  and tipe_pembayaran='Lunas')
                  group by id_faktur_penjualan";
        $r_kredit = $this->runQuery($q_kredit);
        while ($kredit = @mysql_fetch_array($r_kredit)) {
            $setoran_kredit += $this->getJumlahTagihanObat($kredit['id_faktur_penjualan']);
        }

        $q_retur = "SELECT a.id_obat, jumlah, pros_retur, harga, r_code, c.id_faktur_penjualan
                    FROM rm_retur_penjualan_obat a, rm_penjualan_obat b, rm_faktur_penjualan c
                    WHERE b.id_faktur_penjualan=a.id_faktur_penjualan and a.del_flag<>'1' and b.del_flag<>'1' and jns_retur='0' 
                    and c.id_faktur_penjualan = a.id_faktur_penjualan and c.id_ruang = '" . $_SESSION['level'] . "' 
                    AND b.id_penjualan_obat=a.id_penjualan_obat " . $kondisi_retur;
        $r_retur = $this->runQuery($q_retur);
        $setoran_retur_kredit = 0;
        $diskon_retur_kredit = 0;
        while ($ret = @mysql_fetch_array($r_retur)) {
            $setoran_retur_kredit += ( $ret['jumlah'] * $ret['harga']);
            if ($ret['pros_retur'] <> 0)
                $diskon_retur_kredit += ( $ret['jumlah'] * $ret['harga']) * $ret['pros_retur'];
        }

        $diskon_kredit = 0;
        $q_diskon = "SELECT sum(diskon) as diskon FROM rm_diskon_obat WHERE id_faktur in (SELECT a.id_faktur_penjualan FROM rm_faktur_penjualan a,
                    rm_pembayaran_obat b WHERE a.id_ruang='" . $_SESSION['level'] . "' AND 
                    b.id_faktur_penjualan=a.id_faktur_penjualan AND b.del_flag<>'1' AND a.del_flag<>1" . $kondisi_all . " GROUP BY a.id_faktur_penjualan) and del_flag<>1 and status<>'2'" . $kondisi_disc;
        $r_diskon = $this->runQuery($q_diskon);
        $diskon_kredit = @mysql_result($r_diskon, 0, 'diskon');
        if ($shift == "P")
            $nmShift = "Pagi";
        else if ($shift == "S")
            $nmShift = "Siang";
        else if ($shift == "M")
            $nmShift = "Malam";
        else
            $nmShift = "All";

        $html = "<i><u>Setoran Penjualan Harian</u></i>";
        $html .= "<table style='font-family: verdana;font-size: 10pt;' class='data' width='50%'>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Kasir</td>";
        $html .= "<td width='40%'><b>" . $_SESSION['nama_pegawai'] . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Tanggal</td>";
        $html .= "<td width='40%'><b>" . $this->codeDate($this->formatDateDb($startDate)) . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>s/d Tanggal</td>";
        $html .= "<td width='40%'><b>" . $this->codeDate($this->formatDateDb($endDate)) . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Shift</td>";
        $html .= "<td width='40%'><b>" . $nmShift . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%' class='headerTagihan'>Jenis Setoran</td>";
        $html .= "<td width='40%' class='headerTagihan'>Jumlah</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Setoran Tunai</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format($setoran_tunai, 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Setoran Retur</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format($setoran_retur_tunai, 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Discount Tunai</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format($diskon_tunai, 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Discount Retur</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format(($diskon_retur_tunai), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%' class='total'>Netto Tunai</td>";
        $html .= "<td width='40%' class='total' align='right'><b>Rp. " . number_format(($setoran_tunai - $diskon_tunai), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'><b>Netto Retur</b></td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format(($setoran_retur_tunai - $diskon_retur_tunai), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%' class='total'>Netto Tunai</td>";
        $html .= "<td width='40%' class='total' align='right'><b>Rp. " . number_format($tunai = (($setoran_tunai - $diskon_tunai) - ($setoran_retur_tunai - $diskon_retur_tunai)), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'> </td>";
        $html .= "<td width='40%' align='right'> </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Setoran Kredit</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format($setoran_kredit, 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Setoran Retur</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format($setoran_retur_kredit, 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Discount Kredit</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format($diskon_kredit, 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'>Discount Retur</td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format(($diskon_retur_kredit), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%' class='total'>Netto Kredit</td>";
        $html .= "<td width='40%' class='total' align='right'><b>Rp. " . number_format(($setoran_kredit - $diskon_kredit), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'><b>Netto Retur Kredit</b></td>";
        $html .= "<td width='40%' align='right'><b>Rp. " . number_format(($setoran_retur_kredit - $diskon_retur_kredit), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%' class='total'>Netto Kredit</td>";
        $html .= "<td width='40%' class='total' align='right'><b>Rp. " . number_format($kredit = (($setoran_kredit - $diskon_kredit) - ($setoran_retur_kredit - $diskon_retur_kredit)), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%' class='total'>Total Netto</td>";
        $html .= "<td width='40%' class='total' align='right'><b>Rp. " . number_format(($tunai + $kredit), 2, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'><b>R/Nota</b></td>";
        $html .= "<td width='40%' align='right'><b>" . number_format($jml_faktur, 0, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td width='60%'><b>R/Item</b></td>";
        $html .= "<td width='40%' align='right'><b>" . number_format($jml_obat, 0, ',', '.') . "</b></td>";
        $html .= "</tr>";
        $html .= "</table>";

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function diskonTemp($id_faktur, $diskon) {
        $qCek = "SELECT id_diskon FROM rm_diskon_temp WHERE del_flag<>1 AND id_faktur_penjualan=" . $id_faktur . "";
        $rCek = $this->runQuery($qCek);

        if (@mysql_result($rCek, 0, 'id_diskon') > 0) {
            $query = "UPDATE rm_diskon_temp SET jumlah=" . $diskon . " WHERE id_faktur_penjualan=" . $id_faktur . " AND del_flag<>1";
        } else {
            $query = "INSERT INTO rm_diskon_temp (id_faktur_penjualan,jumlah) VALUES ('" . $id_faktur . "','" . $diskon . "')";
        }

        $result = $this->runQuery($query);
        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getDiskonTemp($id_faktur) {
        $qCek = "SELECT jumlah FROM rm_diskon_temp WHERE del_flag<>1 AND id_faktur_penjualan=" . $id_faktur . "";
        $rCek = $this->runQuery($qCek);
        if (@mysql_result($rCek, 0, 'jumlah') > 0) {
            return @mysql_result($rCek, 0, 'jumlah');
        } else {
            return 'ERROR';
        }
    }

    public function getRekapResep(
    $jenis_perawatan, $tipe_pasien, $startDate, $endDate
    ) {
        $kondisi = "";

        if ($jenis_perawatan == "rawatJalan") {
            $kondisi .= " AND ruang IN (
                  SELECT ruang FROM rm_ruang WHERE id_tipe_ruang IN ('2', '3', '4', '9')
                  )";
            $rawat = "Rawat Jalan";
        } else if ($jenis_perawatan == "rawatInap") {
            $kondisi .= " AND ruang IN (
                  SELECT ruang FROM rm_ruang WHERE id_tipe_ruang='8'
                  )";
            $rawat = "Rawat Inap";
        }
        if ($tipe_pasien != "")
            $kondisi .= " and b.id_tipe_pasien='" . $tipe_pasien . "'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(tgl_penjualan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(tgl_penjualan)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT ruang FROM rm_faktur_penjualan a, rm_pasien b, rm_tipe_pasien c
                  WHERE a.del_flag<>'1' AND b.id_pasien=a.id_pasien AND c.id_tipe_pasien=b.id_tipe_pasien " . $kondisi . " 
                  GROUP BY ruang order by ruang";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            $html = "<table style='font-family: verdana;font-size: 10pt;' class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='10%' class='headerTagihan'>Rawat</td>";
            $html .= "<td width='30%' class='headerTagihan'>Ruang Asal</td>";
            $html .= "<td width='30%' class='headerTagihan'>Customer</td>";
            $html .= "<td width='30%' class='headerTagihan'>Jumlah Resep</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $jmlTotal = 0;
            while ($data = @mysql_fetch_array($result)) {
                $q_resep = "SELECT COUNT(*) AS jmlResep, ruang, c.tipe_pasien FROM rm_faktur_penjualan a, rm_pasien b, rm_tipe_pasien c
                                WHERE a.del_flag<>'1' AND b.id_pasien=a.id_pasien AND c.id_tipe_pasien=b.id_tipe_pasien " . $kondisi . " 
                                and ruang='" . $data['ruang'] . "' GROUP BY ruang, b.id_tipe_pasien order by ruang, b.id_tipe_pasien";
                $r_resep = $this->runQuery($q_resep);
                if (@mysql_num_rows($r_resep) > 0) {
                    $html .= "<tr>";
                    $html .= "<td width='10%'>" . $rawat . "</td>";
                    $html .= "<td width='30%' colspan='3'>" . $data['ruang'] . "</td>";
                    $html .= "</tr>";
                    $subTotal = 0;
                    while ($rec = @mysql_fetch_array($r_resep)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>&nbsp;</td>";
                        $html .= "<td width='30%'>&nbsp;</td>";
                        $html .= "<td width='30%'>" . $rec['tipe_pasien'] . "</td>";
                        $html .= "<td width='30%' align='right'>" . $rec['jmlResep'] . "</td>";
                        $html .= "</tr>";
                        $subTotal += $rec['jmlResep'];
                    }
                    $html .= "<tr>";
                    $html .= "<td width='10%' class='total' align='right' colspan='3'>Sub Total / " . $data['ruang'] . "</td>";
                    $html .= "<td width='30%' class='total' align='right'>" . $subTotal . "</td>";
                    $html .= "</tr>";
                    $jmlTotal += $subTotal;
                }
            }
            $html .= "<tr>";
            $html .= "<td width='10%' class='total' align='right' colspan='3'>Grand Total</td>";
            $html .= "<td width='30%' class='total' align='right'>" . $jmlTotal . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html = "Data tidak ditemukan.";
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getDistribusiObat(
    $id_obat, $id_obatS, $startDate, $endDate, $ruang, $asal_ruang
    ) {
        $kondisi = "";
        if ($endDate == "")
            $endDate = $startDate;

        if ($startDate != "") {
            $kondisi .= " and a.date_update BETWEEN '" . $this->formatDateDb($startDate) . "' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
        }

        if ($id_obat != "") {
            if ($id_obatS != "") {
                $kondisi .= " and b.kode_obat between '" . $id_obat . "' and '" . $id_obatS . "' ";
            } else {
                $kondisi .= " and b.kode_obat='" . $id_obat . "' ";
            }
        }

        if ($ruang != "") {
            $kondisi .= " and c.id_ruang=" . $ruang . " ";
        }

        if ($asal_ruang != "") {
            $kondisi .= " and a.id_ruang_asal=" . $asal_ruang . " ";
        }

        $query = "select a.id_ruang_asal as asal, cast(`a`.`date_update` as date) AS `Tanggal`, `c`.`ruang` AS `Ruang`, `b`.`kode_obat` AS `Kode Obat`, `b`.`nama_obat` AS `Nama Obat`, `a`.`stock` AS `Jumlah` from ((`rm_distribusi_obat` `a` join `rm_obat` `b` on((`a`.`id_obat` = `b`.`id_obat`))) join `rm_ruang` `c` on((`a`.`id_ruang_tujuan` = `c`.`id_ruang`))) where (`a`.`del_flag` <> 1)"
                . $kondisi . " ORDER BY `Tanggal`,`asal`,`Ruang`,`Kode Obat`";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='15%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Ruang Asal</td>";
            $html .= "<td width='10%' class='headerTagihan'>Kode</td>";
            $html .= "<td width='40%' class='headerTagihan'>Nama Obat</td>";
            $html .= "<td width='15%' class='headerTagihan'>Jumlah</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $tgl = "";
            $nRuang = "";
            $Asal = 0;
            $allQty = 0;
            while ($hasil = @mysql_fetch_array($result)) {
                if ($hasil['asal'] == 18)
                    $ra = 37;
                else
                    $ra = $hasil['asal'];

                $html .= "<tr valign='top'>";
                if ($tgl == $hasil['Tanggal'])
                    $html .= "<td width='15%'>&nbsp;</td>";
                else
                    $html .= "<td width='15%'>" . $this->formatDateDb($hasil['Tanggal']) . "</td>";

                if ($nRuang == $hasil['Ruang'])
                    $html .= "<td width='10%'>&nbsp;</td>";
                else
                    $html .= "<td width='10%'>" . $hasil['Ruang'] . "</td>";
                
                if ($asal == $ra)
                    $html .= "<td width='10%'>&nbsp;</td>";
                else
                    $html .= "<td width='10%'>" . $this->getRuang($ra) . "</td>";

                $html .= "<td width='10%'>" . $hasil['Kode Obat'] . "</td>";
                $html .= "<td width='40%'>" . $hasil['Nama Obat'] . "</td>";
                $html .= "<td width='15%' align='right'>" . number_format($hasil['Jumlah'], 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $tgl = $hasil['Tanggal'];
                $nRuang = $hasil['Ruang'];
                $asal = $ra;
                $allQty += $hasil['Jumlah'];
            }
            $html .= "<tr valign='top'>";
            $html .= "<td width='10%' colspan='5' class='total'>Total</td>";
            $html .= "<td width='10%' align='right' class='total'>" . number_format($allQty, 2, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html = "Data tidak ditemukan";
        }
        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getRekapPenjualanObat(
    $id_obat, $id_obatS, $status, $startDate, $endDate, $startHour, $endHour, $tipe_laporan, $ruang
    ) {

        if ($tipe_laporan == '1')
            $judul = "Rekap Penjualan Per Customer";
        else if ($tipe_laporan == '2')
            $judul = "Rekap Penjualan Per Obat";
        else if ($tipe_laporan == '3')
            $judul = "Rekap Penjualan Per Faktur/Nota";
        else if ($tipe_laporan == '4')
            $judul = "Rekap Penjualan Per Obat/Pasien";
        else if ($tipe_laporan == '5')
            $judul = "Rekap Penjualan Per Tanggal";
        else if ($tipe_laporan == '6')
            $judul = "Rekap Penjualan Per Ruang";
        else if ($tipe_laporan == '7')
            $judul = "Rekap Penjualan Per Dokter/Obat";
        else if ($tipe_laporan == '8')
            $judul = "Rekap Penjualan Per Obat/Dokter";

        $html = "<table class='data' cellspacing='0' cellpadding='0'>
            <tr height='21'>
            <td height='21' colspan='10'><b>RSUD Dr. SOEGIRI</b></td>
            </tr>
            <tr height='21'>
            <td height='21' colspan='10'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
            </tr>
            <tr height='21'>
            <td height='21' colspan='10'><u><b>" . $judul . "</b></u><br><br></td>
            </tr>";


        $kondisix = "";
        $kondisiy = "";
        $kondisiz = "";
        $jmlTotal = 0;
        $jmlDiskon = 0;
        if ($status == 0) {
            $kondisix .= " AND e.tipe_pembayaran='Kredit' ";
            $kondisiy .= " AND f.tipe_pembayaran='Kredit' ";
            $kondisiz .= " AND a.status<>'1' ";
            $nmStatus = "Kredit";
        } else if ($status == 2) {
            $kondisix .= " AND e.tipe_pembayaran='Lunas' and e.auto=0";
            $kondisiy .= " AND f.tipe_pembayaran='Lunas' and f.auto=0";
            $kondisiz .= " AND a.status='1' ";
            $nmStatus = "Tunai";
        } else {
            $nmStatus = "All";
        }
        if ($id_obat != "") {
            if ($id_obatS != "") {
                $html .="<tr height='21'>
            <td height='21' colspan='2'><b>Obat</b></td>
            <td height='21'><b>:</b></td>
            <td height='21' colspan='3'>" . $id_obat . "</td>
            <td height='21'><b>s/d</b></td>
            <td height='21' colspan='3'>" . $id_obatS . "</td>
            </tr>";
                $kondisix .= " and c.kode_obat between '" . $id_obat . "' and '" . $id_obatS . "' ";
                $kondisiy .= " and d.kode_obat between '" . $id_obat . "' and '" . $id_obatS . "' ";
                $kondisiz .= " and d.kode_obat between '" . $id_obat . "' and '" . $id_obatS . "' ";
            } else {
                $html .="<tr height='21'>
            <td height='21' colspan='2'><b>Obat</b></td>
            <td height='21'><b>:</b></td>
            <td height='21' colspan='7'>" . $id_obat . "</td>
            </tr>";
                $kondisix .= " and c.kode_obat='" . $id_obat . "' ";
                $kondisiy .= " and d.kode_obat='" . $id_obat . "' ";
                $kondisiz = " and d.kode_obat='" . $id_obat . "' ";
            }
        }
        if ($endDate == "")
            $endDate = $startDate;

        //if ($shift != "") {
        if ($startHour != "") {
            if ($endHour == "")
                $endHour = $startHour;
            //TANGGAL
            $kondisix .= " AND e.tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . " 00:00:00' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
            $kondisiy .= " AND f.tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . " 00:00:00' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
            $kondisiz .= " AND c.tgl_retur BETWEEN '" . $this->formatDateDb($startDate) . " 00:00:00' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";

            //JAM
            $kondisix .= " AND time(e.tgl_pembayaran) BETWEEN '" . $startHour . "' AND '" . $endHour . "'";
            $kondisiy .= " AND time(f.tgl_pembayaran) BETWEEN '" . $startHour . "' AND '" . $endHour . "'";
            $kondisiz .= " AND time(c.tgl_retur) BETWEEN '" . $startHour . "' AND '" . $endHour . "'";
        } else {
            $kondisix .= " AND e.tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . " 00:00:00' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
            $kondisiy .= " AND f.tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . " 00:00:00' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
            $kondisiz .= " AND c.tgl_retur BETWEEN '" . $this->formatDateDb($startDate) . " 00:00:00' AND '" . $this->formatDateDb($endDate) . " 23:59:59'";
        }
        $html .="<tr height='21'>
            <td height='21' colspan='2'><b>Tanggal</b></td>
            <td height='21'><b>:</b></td>
            <td height='21' colspan='3'>" . $this->codeDate($this->formatDateDb($startDate)) . "</td>
            <td height='21'><b> Hingga </b></td>
            <td height='21' colspan='3'>" . $this->codeDate($this->formatDateDb($endDate)) . "</td>
            </tr>";
        $html .="<tr height='21'>
            <td height='21' colspan='2'><b>Jam</b></td>
            <td height='21'><b>:</b></td>
            <td height='21' colspan='3'>" . $startHour . " - " . $endHour . "</td>
            <td height='21'><b> Status </b></td>
            <td height='21' colspan='3'>" . $nmStatus . "</td>
            </tr>";
        $html .="<tr height='21'>
            <td height='21' colspan='2'><b>Ruang</b></td>
            <td height='21'><b>:</b></td>
            <td height='21' colspan='7'>" . $this->getNamaRuang($ruang) . "</td>
            </tr>";
        $html .="</table>";
        $html .="<br>";
        if ($tipe_laporan == '1') {
	    $query = "select ifnull(g.nama_dokter,'Resep Bebas') as Dokter, `a`.`nama_pasien` AS `Nama Pasien`, a.alamat as Alamat, `c`.`kode_obat` AS `Kode Obat`, `c`.`nama_obat` AS `Nama Obat`, cast(`e`.`tgl_pembayaran` as date)
            AS `Tanggal Transaksi`, cast(`e`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `b`.`qty` AS `QTY`, round(`b`.`harga`) AS `Harga`,
            ifnull((`d`.`diskon` / `a`.`jml_obat`), 0) AS `Diskon`, (`b`.`qty` * round(`b`.`harga`)) AS `Jumlah`, `a`.`id_faktur_penjualan` AS `Faktur`,
            `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran` AS `Status` from (((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b`
            on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`)))
            left join `rm_diskon_obat` `d` on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f`
            on((`a`.`id_ruang` = `f`.`id_ruang`)) LEFT JOIN rm_dokter g on (a.id_dokter = g.id_dokter)) where (`a`.`del_flag` <> 1) AND a.id_ruang='" . $ruang . "' " . $kondisix .
                    " UNION ALL select ifnull(h.nama_dokter,'Resep Bebas') as Dokter, `a`.`nama_pasien` AS `Nama Pasien`, a.alamat AS Alamat, `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`f`.`tgl_pembayaran` as date)
            AS `Tanggal Transaksi`, cast(`f`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `c`.`qty` AS `QTY`, round(`c`.`harga`) AS `Harga`,
            ifnull((`e`.`diskon` / `a`.`jml_obat`), 0) AS `Diskon`, ((`c`.`qty` * round(`c`.`harga`)) + (((select count(0) AS `count(0)` from `rm_racikan`
            where ((`rm_racikan`.`del_flag` <> 1) and (`rm_racikan`.`id_faktur_penjualan` = `a`.`id_faktur_penjualan`)) group by
            `rm_racikan`.`id_faktur_penjualan`) * 500) / (select count(0) AS `count(0)` from `rm_detail_racikan` where
            ((`rm_detail_racikan`.`del_flag` <> 1) and `rm_detail_racikan`.`id_racikan` in (select `rm_racikan`.`id_racikan` AS `id_racikan`
            from `rm_racikan` where ((`rm_racikan`.`del_flag` <> 1) and (`rm_racikan`.`id_faktur_penjualan` = `a`.`id_faktur_penjualan`)))))))
            AS `Jumlah`, `a`.`id_faktur_penjualan` AS `Faktur`, `g`.`ruang` AS `Ruang`, `f`.`tipe_pembayaran` AS `Status`
            from ((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and
            (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c` on(((`b`.`id_racikan` = `c`.`id_racikan`) and (`c`.`del_flag` <> 1))))
            join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`))) left join `rm_diskon_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`)
            and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `f` on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and
            (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang` = `g`.`id_ruang`)) LEFT JOIN rm_dokter h on (a.id_dokter=h.id_dokter)) where (`a`.`del_flag` <> 1) AND a.id_ruang='" . $ruang . "' " . $kondisiy .
                    " UNION ALL select ifnull(g.nama_dokter,'Resep Bebas') as Dokter, `a`.`nama_pasien` AS `Nama Pasien`, a.alamat AS Alamat, `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`c`.`tgl_retur`
            as date) AS `Tanggal Transaksi`, cast(`c`.`tgl_retur` as time) AS `Jam Transaksi`, (`c`.`jumlah` * -(1)) AS `QTY`, round(`b`.`harga`) AS
            `Harga`, ((`c`.`pros_retur` * `b`.`harga`) * `c`.`jumlah`) AS `Diskon`, ((((1 - `c`.`pros_retur`) * round(`b`.`harga`)) * `c`.`jumlah`) * -(1))
            AS `Jumlah`, `c`.`id_faktur_penjualan` AS `Faktur`, `e`.`ruang` AS `Ruang`, (case when (`a`.`status` = '1') then 'Lunas' else 'Kredit' end)
            AS `Status` from ((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b`) join `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`)
            join `rm_ruang` `e`) left join rm_dokter g on(a.id_dokter=g.id_dokter) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` = `c`.`id_faktur_penjualan`) and
            (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` = `b`.`id_penjualan_obat`) and
            (`a`.`id_ruang` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and (`c`.`del_flag` <> '1') AND a.id_ruang='" . $ruang . "' " . $kondisiz . ")
            ORDER BY `Nama Pasien`, `Faktur`";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='10%' class='headerTagihan'>Customer</td>";
                $html .= "<td width='10%' class='headerTagihan'>Dokter</td>";
                $html .= "<td width='10%' class='headerTagihan'>Alamat</td>";
                $html .= "<td width='5%' class='headerTagihan'>Kode</td>";
                $html .= "<td width='15%' class='headerTagihan'>Nama Barang</td>";
                $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "<td width='5%' class='headerTagihan'>Faktur</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $nama = "";
                $nofaktur = 0;
                $alldisc = 0;
                $alljumlah = 0;
                while ($hasil = @mysql_fetch_array($result)) {
                    $html .= "<tr valign='top'>";
                    if ($nama == $hasil['Nama Pasien']) {
                        $html .= "<td width='15%'>&nbsp;</td>";
                        $html .= "<td width='10%'>&nbsp;</td>";
                        $html .= "<td width='10%'>&nbsp;</td>";
                    } else {
                        $html .= "<td width='15%'>" . $hasil['Nama Pasien'] . "</td>";
	   		$html .= "<td width='10%'>" . $hasil['Dokter'] . "</td>";
                        $html .= "<td width='10%'>" . $hasil['Alamat'] . "</td>";
                    }
                    $html .= "<td width='5%'>" . $hasil['Kode Obat'] . "</td>";
                    $html .= "<td width='30%'>" . $hasil['Nama Obat'] . "</td>";
                    $html .= "<td width='10%' align='center'>" . $this->formatDateDb($hasil['Tanggal Transaksi']) . "</td>";
                    $html .= "<td width='5%' align='right'>" . number_format($hasil['QTY'], 0, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($hasil['Harga'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($hasil['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($hasil['Jumlah'], 2, ',', '.') . "</td>";
                    if ($nofaktur == $hasil['Faktur'])
                        $html .= "<td width='10%'>&nbsp;
            </td>";
                    else
                        $html .= "<td width='10%' align='center'>" . $hasil['Faktur'] . "</td>";
                    $html .= "</tr>";
                    $nama = $hasil['Nama Pasien'];
                    $nofaktur = $hasil['Faktur'];
                    $alldisc += $hasil['Diskon'];
                    $alljumlah += $hasil['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='8' class='total'>Total</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($alldisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($alljumlah, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        } else if ($tipe_laporan == '2') {
            $query = "SELECT `Kode Obat`, `Nama Obat`, SUM(`QTY`) as QTY, `Harga`, SUM(`Diskon`) AS Diskon, SUM(`Jumlah`) AS Jumlah, `Tanggal Transaksi`, `Jam Transaksi`, `Ruang`, `Status` FROM (
            select `c`.`kode_obat` AS `Kode Obat`, `c`.`nama_obat` AS `Nama Obat`, (`b`.`qty`) AS `QTY`, round(`b`.`harga`) AS `Harga`,
            (ifnull((`d`.`diskon` / `a`.`jml_obat`), 0)) AS `Diskon`, ((`b`.`qty` * round(`b`.`harga`))) AS `Jumlah`, cast(`e`.`tgl_pembayaran` as date)
            AS `Tanggal Transaksi`, cast(`e`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran`
            AS `Status` from (((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`)
            and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`))) left join `rm_diskon_obat` `d`
            on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f`
            on((`a`.`id_ruang` = `f`.`id_ruang`))) where (`a`.`del_flag` <> 1) AND a.id_ruang='" . $ruang . "'" . $kondisix . "
            UNION
            select `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, (`c`.`qty`) AS `QTY`, round(`c`.`harga`) AS `Harga`,
            (ifnull((`e`.`diskon` / `a`.`jml_obat`), 0)) AS `Diskon`, (((`c`.`qty` * round(`c`.`harga`)) + ((`b`.`jml_racikan` * 500) / `b`.`jml_Obat`)))
            AS `Jumlah`, cast(`f`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`, cast(`f`.`tgl_pembayaran` as time) AS `Jam Transaksi`,
            `g`.`ruang` AS `Ruang`, `f`.`tipe_pembayaran` AS `Status` from ((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b`
            on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c`
            on(((`b`.`id_racikan` = `c`.`id_racikan`) and (`c`.`del_flag` <> 1)))) join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`)))
            left join `rm_diskon_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`) and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat`
            `f` on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang` =
            `g`.`id_ruang`))) where (`a`.`del_flag` <> 1) AND a.id_ruang='" . $ruang . "'" . $kondisiy . "
            UNION
            select `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, ((`c`.`jumlah` * -(1))) AS `QTY`, round(`b`.`harga`) AS `Harga`,
            (((`c`.`pros_retur` * `b`.`harga`) * `c`.`jumlah`)) AS `Diskon`, (((((1 - `c`.`pros_retur`) * round(`b`.`harga`)) * `c`.`jumlah`) * -(1))) AS `Jumlah`,
            cast(`c`.`tgl_retur` as date) AS `Tanggal Transaksi`, cast(`c`.`tgl_retur` as time) AS `Jam Transaksi`, `e`.`ruang` AS `Ruang`, (case when
            (`a`.`status` = '1') then 'Lunas' else 'Kredit' end) AS `Status` from ((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b`) join
            `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`) join `rm_ruang` `e`) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` =
            `c`.`id_faktur_penjualan`) and (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` = `b`.`id_penjualan_obat`)
            and (`a`.`id_ruang` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and (`c`.`del_flag` <> '1')
            AND a.id_ruang='" . $ruang . "')" . $kondisiz . "
            ) GABUNGAN GROUP BY `Kode Obat`";
            $result = $this->runQuery($query);

            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='10%' class='headerTagihan'>Kode</td>";
                $html .= "<td width='35%' class='headerTagihan'>Nama Barang</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='15%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='15%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='20%' class='headerTagihan'>Jumlah</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlTotal = 0;
                while ($data = @mysql_fetch_array($result)) {
                    $html .= "<tr valign='top'>";
                    $html .= "<td width='10%'>" . $data['Kode Obat'] . "</td>";
                    $html .= "<td width='35%'>" . $data['Nama Obat'] . "</td>";
                    $html .= "<td width='5%' align='right'>" . number_format($data['QTY'], 0, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>" . number_format($data['Harga'], 2, ',', '.') . "</td>";
                    $html .= "<td width='15%' align='right'>" . number_format($data['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td width='20%' align='right'>" . number_format($data['Jumlah'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $jmlQty += $data['QTY'];
                    $jmlDisc += $data['Diskon'];
                    $jmlTotal += $data['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='2' class='total'>Total</td>";
                $html .= "<td width='5%' align='right' class='total'>" . number_format($jmlQty, 0, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        } else if ($tipe_laporan == '3') {
            $query = "select `a`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`g`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `c`.`kode_obat` AS `Kode Obat`, `c`.`nama_obat` AS `Nama Obat`, cast(`e`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`,
            cast(`e`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `b`.`qty` AS `QTY`, round(`b`.`harga`) AS `Harga`, ifnull((`d`.`diskon` / `a`.`jml_obat`), 0)
            AS `Diskon`, (`b`.`qty` * round(`b`.`harga`)) AS `Jumlah`, `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran` AS `Status` from
            ((((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`)
            and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`))) left join `rm_diskon_obat` `d`
            on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f`
            on((`a`.`id_ruang` = `f`.`id_ruang`))) left join `rm_dokter` `g` on((`a`.`id_dokter` = `g`.`id_dokter`))) where
            (`a`.`del_flag` <> 1) AND a.id_ruang='" . $ruang . "'" . $kondisix . "
            UNION
            select `a`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`h`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`f`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`,
            cast(`f`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `c`.`qty` AS `QTY`, round(`c`.`harga`) AS `Harga`, ifnull((`e`.`diskon` / `a`.`jml_obat`), 0)
            AS `Diskon`, ((`c`.`qty` * round(`c`.`harga`)) + ((`b`.`jml_racikan` * 500) / `b`.`jml_Obat`)) AS `Jumlah`, `g`.`ruang` AS `Ruang`,
            `f`.`tipe_pembayaran` AS `Status` from (((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b` on(((`a`.`id_faktur_penjualan` =
            `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c` on(((`b`.`id_racikan` = `c`.`id_racikan`)
            and (`c`.`del_flag` <> 1)))) join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`))) left join `rm_diskon_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`) and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `f`
            on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang`
            = `g`.`id_ruang`))) left join `rm_dokter` `h` on((`a`.`id_dokter` = `h`.`id_dokter`))) where (`a`.`del_flag` <> 1)
            AND a.id_ruang='" . $ruang . "'" . $kondisiy . "
            UNION
            select `c`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`f`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`c`.`tgl_retur` as date) AS `Tanggal Transaksi`,
            cast(`c`.`tgl_retur` as time) AS `Jam Transaksi`, (`c`.`jumlah` * -(1)) AS `QTY`, round(`b`.`harga`) AS `Harga`, ((`c`.`pros_retur` *
            `b`.`harga`) * `c`.`jumlah`) AS `Diskon`, ((((1 - `c`.`pros_retur`) * round(`b`.`harga`)) * `c`.`jumlah`) * -(1)) AS `Jumlah`, `e`.`ruang`
            AS `Ruang`, (case when (`a`.`status` = '1') then 'Lunas' else 'Kredit' end) AS `Status` from (((((`rm_faktur_penjualan` `a` join
            `rm_penjualan_obat` `b`) join `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`) join `rm_ruang` `e`) left join `rm_dokter` `f`
            on((`a`.`id_dokter` = `f`.`id_dokter`))) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` =
            `c`.`id_faktur_penjualan`) and (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` =
            `b`.`id_penjualan_obat`) and (`a`.`id_ruang` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and
            (`c`.`del_flag` <> '1') AND a.id_ruang='" . $ruang . "')" . $kondisiz . " ORDER BY `Faktur`, `Dokter`";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='15%' class='headerTagihan'>Faktur Customer</td>";
                $html .= "<td width='10%' class='headerTagihan'>Dokter</td>";
                $html .= "<td width='5%' class='headerTagihan'>Kode</td>";
                $html .= "<td width='30%' class='headerTagihan'>Nama Barang</td>";
                $html .= "<td width='5%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $dkt = "";
                $faktur = "";
                $cek = "";
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlTotal = 0;
                $subQty = 0;
                $subDisc = 0;
                $subTotal = 0;
                while ($data = @mysql_fetch_array($result)) {
                    if ($faktur != "" && $faktur != $data['Faktur']) {
                        $html .= "<tr valign='top'>";
                        $html .= "<td width='10%' colspan='5' class='total'>Sub Total</td>";
                        $html .= "<td width='5%' align='right' class='total'>" . number_format($subQty, 0, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                        $html .= "<td width='10%' align='right' class='total'>" . number_format($subDisc, 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right' class='total'>" . number_format($subTotal, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $subQty = 0;
                        $subDisc = 0;
                        $subTotal = 0;
                    }
                    $html .= "<tr valign='top'>";
                    if ($faktur == $data['Faktur']) {
                        $html .= "<td width='20%'>&nbsp;
            </td>";
                    } else {
                        $html .= "<td width='20%'>" . $data['Faktur'] . " " . $data['Nama Pasien'] . "</td>";
                    }
                    if ($dkt == $data['Dokter'])
                        $html .= "<td width='20%'>&nbsp;
            </td>";
                    else
                        $html .= "<td width='20%'>" . $data['Dokter'] . "</td>";

                    $html .= "<td width='5%'>" . $data['Kode Obat'] . "</td>";
                    $html .= "<td width='30%'>" . $data['Nama Obat'] . "</td>";
                    $html .= "<td width='10%' align='center'>" . $this->formatDateDb($data['Tanggal Transaksi']) . "</td>";
                    $html .= "<td width='5%' align='right'>" . number_format($data['QTY'], 0, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Harga'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Jumlah'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $dkt = $data['Dokter'];
                    $faktur = $data['Faktur'];
                    $subQty += $data['QTY'];
                    $subDisc += $data['Diskon'];
                    $subTotal += $data['Jumlah'];
                    $jmlQty += $data['QTY'];
                    $jmlDisc += $data['Diskon'];
                    $jmlTotal += $data['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='5' class='total'>Total</td>";
                $html .= "<td width='5%' align='right' class='total'>" . number_format($jmlQty, 0, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        } else if ($tipe_laporan == '4') {
            $query = "select `c`.`kode_obat` AS `Kode Obat`, `c`.`nama_obat` AS `Nama Obat`, `a`.`nama_pasien` AS `Nama Pasien`, cast(`e`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`, cast(`e`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `b`.`qty` AS `QTY`, round(`b`.`harga`) AS `Harga`, ifnull((`d`.`diskon` / `a`.`jml_obat`), 0) AS `Diskon`, (`b`.`qty` * round(`b`.`harga`)) AS `Jumlah`, `a`.`id_faktur_penjualan` AS `Faktur`, `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran` AS `Status` from (((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`))) left join `rm_diskon_obat` `d` on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f` on((`a`.`id_ruang` = `f`.`id_ruang`))) where (`a`.`del_flag` <> 1)
            AND a.id_ruang='" . $ruang . "'" . $kondisix . "
            UNION
            select `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, `a`.`nama_pasien` AS `Nama Pasien`, cast(`f`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`, cast(`f`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `c`.`qty` AS `QTY`, round(`c`.`harga`) AS `Harga`, ifnull((`e`.`diskon` / `a`.`jml_obat`), 0) AS `Diskon`, ((`c`.`qty` * round(`c`.`harga`)) + (((select count(0) AS `count(0)` from `rm_racikan` where ((`rm_racikan`.`del_flag` <> 1) and (`rm_racikan`.`id_faktur_penjualan` = `a`.`id_faktur_penjualan`)) group by `rm_racikan`.`id_faktur_penjualan`) * 500) / (select count(0) AS `count(0)` from `rm_detail_racikan` where ((`rm_detail_racikan`.`del_flag` <> 1) and `rm_detail_racikan`.`id_racikan` in (select `rm_racikan`.`id_racikan` AS `id_racikan` from `rm_racikan` where ((`rm_racikan`.`del_flag` <> 1) and (`rm_racikan`.`id_faktur_penjualan` = `a`.`id_faktur_penjualan`))))))) AS `Jumlah`, `a`.`id_faktur_penjualan` AS `Faktur`, `g`.`ruang` AS `Ruang`, `f`.`tipe_pembayaran` AS `Status` from ((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c` on(((`b`.`id_racikan` = `c`.`id_racikan`) and (`c`.`del_flag` <> 1)))) join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`))) left join `rm_diskon_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`) and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `f` on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang` = `g`.`id_ruang`))) where (`a`.`del_flag` <> 1)
            AND a.id_ruang='" . $ruang . "'" . $kondisiy . "
            UNION
            select `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, `a`.`nama_pasien` AS `Nama Pasien`, cast(`c`.`tgl_retur` as date) AS `Tanggal Transaksi`, cast(`c`.`tgl_retur` as time) AS `Jam Transaksi`, (`c`.`jumlah` * -(1)) AS `QTY`, round(`b`.`harga`) AS `Harga`, ((`c`.`pros_retur` * round(`b`.`harga`)) * `c`.`jumlah`) AS `Diskon`, ((((1 - `c`.`pros_retur`) * `b`.`harga`) * `c`.`jumlah`) * -(1)) AS `Jumlah`, `c`.`id_faktur_penjualan` AS `Faktur`, `e`.`ruang` AS `Ruang`, (case when (`a`.`status` = '1') then 'Lunas' else 'Kredit' end) AS `Status` from ((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b`) join `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`) join `rm_ruang` `e`) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` = `c`.`id_faktur_penjualan`) and (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` = `b`.`id_penjualan_obat`) and (`a`.`id_ruang` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and (`c`.`del_flag` <> '1')
            AND a.id_ruang='" . $ruang . "')" . $kondisiz . " ORDER BY `Kode Obat`, `Tanggal Transaksi`";
            $result = $this->runQuery($query);

            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='5%' class='headerTagihan'>Kode</td>";
                $html .= "<td width='30%' class='headerTagihan'>Nama Barang</td>";
                $html .= "<td width='15%' class='headerTagihan'>Customer</td>";
                $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "<td width='5%' class='headerTagihan'>Faktur</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlTotal = 0;
                $kodeObat = "";
                while ($data = @mysql_fetch_array($result)) {
                    $html .= "<tr valign='top'>";
                    if ($kodeObat == $data['Kode Obat']) {
                        $html .= "<td width='5%'>&nbsp;
            </td>";
                        $html .= "<td width='30%'>&nbsp;
            </td>";
                    } else {
                        $html .= "<td width='5%'>" . $data['Kode Obat'] . "</td>";
                        $html .= "<td width='30%'>" . $data['Nama Obat'] . "</td>";
                    }
                    $html .= "<td width='15%'>" . $data['Nama Pasien'] . "</td>";
                    $html .= "<td width='10%' align='right'>" . $this->formatDateDb($data['Tanggal Transaksi']) . "</td>";
                    $html .= "<td width='5%' align='right'>" . number_format($data['QTY'], 0, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Harga'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Jumlah'], 2, ',', '.') . "</td>";
                    $html .= "<td width='5%'>" . $data['Faktur'] . "</td>";
                    $html .= "</tr>";
                    $kodeObat = $data['Kode Obat'];
                    $jmlQty += $data['QTY'];
                    $jmlDisc += $data['Diskon'];
                    $jmlTotal += $data['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='4' class='total'>Total</td>";
                $html .= "<td width='5%' align='right' class='total'>" . number_format($jmlQty, 0, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "<td width='5%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        } else if ($tipe_laporan == '5') {
            $query = "select `c`.`kode_obat` AS `Kode Obat`, `c`.`nama_obat` AS `Nama Obat`, `a`.`nama_pasien` AS `Nama Pasien`, cast(`e`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`, cast(`e`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `b`.`qty` AS `QTY`, round(`b`.`harga`) AS `Harga`, ifnull((`d`.`diskon` / `a`.`jml_obat`), 0) AS `Diskon`, (`b`.`qty` * round(`b`.`harga`)) AS `Jumlah`, `a`.`id_faktur_penjualan` AS `Faktur`, `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran` AS `Status` from (((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`))) left join `rm_diskon_obat` `d` on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f` on((`a`.`id_ruang` = `f`.`id_ruang`))) where (`a`.`del_flag` <> 1)
            AND a.id_ruang='" . $ruang . "'" . $kondisix . "
            UNION
            select `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, `a`.`nama_pasien` AS `Nama Pasien`, cast(`f`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`, cast(`f`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `c`.`qty` AS `QTY`, round(`c`.`harga`) AS `Harga`, ifnull((`e`.`diskon` / `a`.`jml_obat`), 0) AS `Diskon`, ((`c`.`qty` * round(`c`.`harga`)) + (((select count(0) AS `count(0)` from `rm_racikan` where ((`rm_racikan`.`del_flag` <> 1) and (`rm_racikan`.`id_faktur_penjualan` = `a`.`id_faktur_penjualan`)) group by `rm_racikan`.`id_faktur_penjualan`) * 500) / (select count(0) AS `count(0)` from `rm_detail_racikan` where ((`rm_detail_racikan`.`del_flag` <> 1) and `rm_detail_racikan`.`id_racikan` in (select `rm_racikan`.`id_racikan` AS `id_racikan` from `rm_racikan` where ((`rm_racikan`.`del_flag` <> 1) and (`rm_racikan`.`id_faktur_penjualan` = `a`.`id_faktur_penjualan`))))))) AS `Jumlah`, `a`.`id_faktur_penjualan` AS `Faktur`, `g`.`ruang` AS `Ruang`, `f`.`tipe_pembayaran` AS `Status` from ((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c` on(((`b`.`id_racikan` = `c`.`id_racikan`) and (`c`.`del_flag` <> 1)))) join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`))) left join `rm_diskon_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`) and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `f` on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang` = `g`.`id_ruang`))) where (`a`.`del_flag` <> 1)
            AND a.id_ruang='" . $ruang . "'" . $kondisiy . "
            UNION
            select `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, `a`.`nama_pasien` AS `Nama Pasien`, cast(`c`.`tgl_retur` as date) AS `Tanggal Transaksi`, cast(`c`.`tgl_retur` as time) AS `Jam Transaksi`, (`c`.`jumlah` * -(1)) AS `QTY`, round(`b`.`harga`) AS `Harga`, ((`c`.`pros_retur` * round(`b`.`harga`)) * `c`.`jumlah`) AS `Diskon`, ((((1 - `c`.`pros_retur`) * `b`.`harga`) * `c`.`jumlah`) * -(1)) AS `Jumlah`, `c`.`id_faktur_penjualan` AS `Faktur`, `e`.`ruang` AS `Ruang`, (case when (`a`.`status` = '1') then 'Lunas' else 'Kredit' end) AS `Status` from ((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b`) join `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`) join `rm_ruang` `e`) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` = `c`.`id_faktur_penjualan`) and (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` = `b`.`id_penjualan_obat`) and (`a`.`id_ruang` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and (`c`.`del_flag` <> '1')
            AND a.id_ruang='" . $ruang . "')" . $kondisiz . " ORDER BY `Tanggal Transaksi`, `Kode Obat`";
            $result = $this->runQuery($query);

            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='5%' class='headerTagihan'>Kode</td>";
                $html .= "<td width='15%' class='headerTagihan'>Nama Barang</td>";
                $html .= "<td width='30%' class='headerTagihan'>Customer</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "<td width='5%' class='headerTagihan'>Faktur</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlTotal = 0;
                $tglTrans = "";
                while ($data = @mysql_fetch_array($result)) {
                    $html .= "<tr valign='top'>";
                    if ($tglTrans == $data['Tanggal Transaksi']) {
                        $html .= "<td width='10%'>&nbsp;
            </td>";
                    } else {
                        $html .= "<td width='10%' align='right'>" . $this->formatDateDb($data['Tanggal Transaksi']) . "</td>";
                    }
                    $html .= "<td width='5%'>" . $data['Kode Obat'] . "</td>";
                    $html .= "<td width='15%'>" . $data['Nama Obat'] . "</td>";
                    $html .= "<td width='30%'>" . $data['Nama Pasien'] . "</td>";
                    $html .= "<td width='5%' align='right'>" . number_format($data['QTY'], 0, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Harga'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Jumlah'], 2, ',', '.') . "</td>";
                    $html .= "<td width='5%'>" . $data['Faktur'] . "</td>";
                    $html .= "</tr>";
                    $tglTrans = $data['Tanggal Transaksi'];
                    $jmlQty += $data['QTY'];
                    $jmlDisc += $data['Diskon'];
                    $jmlTotal += $data['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='4' class='total'>Total</td>";
                $html .= "<td width='5%' align='right' class='total'>" . number_format($jmlQty, 0, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "<td width='5%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        } else if ($tipe_laporan == '6') {
            $query = "SELECT Ruang, `Status`, `kelas`, `Tanggal Transaksi`, `Nama Pasien`, SUM(`Diskon`) as Diskon, SUM(`Jumlah`) as Jumlah FROM (
            select `a`.`nama_pasien` AS `Nama Pasien`, cast(`e`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`, round(ifnull((`d`.`diskon` / `a`.`jml_obat`), 0)) AS `Diskon`, round((`b`.`qty` * `b`.`harga`)) AS `Jumlah`, `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran` AS `Status`, h.kelas from (((((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`))) left join `rm_diskon_obat` `d` on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f` on((`a`.`id_ruang_px` = `f`.`id_ruang`))) join `rm_pendaftaran` `g` on((`a`.`id_pendaftaran` = `g`.`id_pendaftaran`))) join rm_kelas h on (h.id_kelas = g.id_kelas)) where (`a`.`del_flag` <> 1) and g.del_flag<>1
            AND a.id_ruang='" . $ruang . "'" . $kondisix . "
            UNION
            select `a`.`nama_pasien` AS `Nama Pasien`, cast(`f`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`, round(ifnull((`e`.`diskon` / `a`.`jml_obat`), 0)) AS `Diskon`, round(((`c`.`qty` * `c`.`harga`) + ((`b`.`jml_racikan` * 500) / `b`.`jml_Obat`))) AS `Jumlah`, `g`.`ruang` AS `Ruang`, `f`.`tipe_pembayaran` AS `Status`, i.kelas from ((((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c` on(((`b`.`id_racikan` = `c`.`id_racikan`) and (`c`.`del_flag` <> 1)))) join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`))) left join `rm_diskon_obat` `e` on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`) and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `f` on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang_px` = `g`.`id_ruang`))) join `rm_pendaftaran` `h` on((`a`.`id_pendaftaran` = `h`.`id_pendaftaran`))) join rm_kelas i on (h.id_kelas=i.id_kelas)) where (`a`.`del_flag` <> 1) and h.del_flag<>1
            AND a.id_ruang='" . $ruang . "'" . $kondisiy . "
            UNION
            select `a`.`nama_pasien` AS `Nama Pasien`, cast(`c`.`tgl_retur` as date) AS `Tanggal Transaksi`, round(((`c`.`pros_retur` * `b`.`harga`) * `c`.`jumlah`)) AS `Diskon`, round(((((1 - `c`.`pros_retur`) * `b`.`harga`) * `c`.`jumlah`) * -(1))) AS `Jumlah`, `e`.`ruang` AS `Ruang`, (case when (`a`.`status` = '1') then 'Lunas' else 'Kredit' end) AS `Status`, h.kelas from (((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b`) join `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`) join `rm_ruang` `e`) join `rm_pendaftaran` `f` on((`a`.`id_pendaftaran` = `f`.`id_pendaftaran`))) join rm_kelas h on (f.id_kelas=h.id_kelas) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` = `c`.`id_faktur_penjualan`) and (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` = `b`.`id_penjualan_obat`) and (`a`.`id_ruang_px` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and (`c`.`del_flag` <> '1') and f.del_flag<>1
            AND a.id_ruang='" . $ruang . "')" . $kondisiz . "
            ) GABUNGAN GROUP BY `Ruang`, `Tanggal Transaksi`, `Nama Pasien`, `Status`";
            $result = $this->runQuery($query);

            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
                $html .= "<td width='5%' class='headerTagihan'>Status</td>";
                $html .= "<td width='15%' class='headerTagihan'>Kamar/Kelas</td>";
                $html .= "<td width='15%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='25%' class='headerTagihan'>Customer</td>";
                $html .= "<td width='15%' class='headerTagihan'>Disc</td>";
                $html .= "<td width='15%' class='headerTagihan'>Total</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlTotal = 0;
                $kamar = "";
                while ($data = @mysql_fetch_array($result)) {
                    $html .= "<tr valign='top'>";
                    if ($kamar == $data['Ruang']) {
                        $html .= "<td width='10%'>&nbsp;
            </td>";
                    } else {
                        $html .= "<td width='10%'>" . $data['Ruang'] . "</td>";
                    }
                    $html .= "<td>" . $data['Status'] . "</td>";
                    $html .= "<td>" . $data['kelas'] . "</td>";
                    $html .= "<td>" . $this->formatDateDb($data['Tanggal Transaksi']) . "</td>";
                    $html .= "<td>" . $data['Nama Pasien'] . "</td>";
                    $html .= "<td align='right'>" . number_format($data['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>" . number_format($data['Jumlah'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $kamar = $data['Ruang'];
                    $jmlDisc += $data['Diskon'];
                    $jmlTotal += $data['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='5' class='total'>Total</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        } else if ($tipe_laporan == '7') {
            $query = "SELECT `Kode Obat`, `Nama Obat`, `Dokter`, `Tanggal Transaksi`, SUM(QTY) AS QTY, `Harga`, SUM(`Diskon`) AS `Diskon`, SUM(`Jumlah`) AS `Jumlah` FROM (
            select `a`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`g`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `c`.`kode_obat` AS `Kode Obat`, `c`.`nama_obat` AS `Nama Obat`, cast(`e`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`,
            cast(`e`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `b`.`qty` AS `QTY`, round(`b`.`harga`) AS `Harga`, ifnull((`d`.`diskon` / `a`.`jml_obat`), 0)
            AS `Diskon`, (`b`.`qty` * round(`b`.`harga`)) AS `Jumlah`, `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran` AS `Status` from
            ((((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`)
            and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`))) left join `rm_diskon_obat` `d`
            on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f`
            on((`a`.`id_ruang` = `f`.`id_ruang`))) left join `rm_dokter` `g` on((`a`.`id_dokter` = `g`.`id_dokter`))) where
            (`a`.`del_flag` <> 1) AND a.id_ruang='" . $ruang . "'" . $kondisix . "
            UNION
            select `a`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`h`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`f`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`,
            cast(`f`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `c`.`qty` AS `QTY`, round(`c`.`harga`) AS `Harga`, ifnull((`e`.`diskon` / `a`.`jml_obat`), 0)
            AS `Diskon`, ((`c`.`qty` * round(`c`.`harga`)) + ((`b`.`jml_racikan` * 500) / `b`.`jml_Obat`)) AS `Jumlah`, `g`.`ruang` AS `Ruang`,
            `f`.`tipe_pembayaran` AS `Status` from (((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b` on(((`a`.`id_faktur_penjualan` =
            `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c` on(((`b`.`id_racikan` = `c`.`id_racikan`)
            and (`c`.`del_flag` <> 1)))) join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`))) left join `rm_diskon_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`) and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `f`
            on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang`
            = `g`.`id_ruang`))) left join `rm_dokter` `h` on((`a`.`id_dokter` = `h`.`id_dokter`))) where (`a`.`del_flag` <> 1)
            AND a.id_ruang='" . $ruang . "'" . $kondisiy . "
            UNION
            select `c`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`f`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`c`.`tgl_retur` as date) AS `Tanggal Transaksi`,
            cast(`c`.`tgl_retur` as time) AS `Jam Transaksi`, (`c`.`jumlah` * -(1)) AS `QTY`, round(`b`.`harga`) AS `Harga`, ((`c`.`pros_retur` *
            `b`.`harga`) * `c`.`jumlah`) AS `Diskon`, ((((1 - `c`.`pros_retur`) * round(`b`.`harga`)) * `c`.`jumlah`) * -(1)) AS `Jumlah`, `e`.`ruang`
            AS `Ruang`, (case when (`a`.`status` = '1') then 'Lunas' else 'Kredit' end) AS `Status` from (((((`rm_faktur_penjualan` `a` join
            `rm_penjualan_obat` `b`) join `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`) join `rm_ruang` `e`) left join `rm_dokter` `f`
            on((`a`.`id_dokter` = `f`.`id_dokter`))) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` =
            `c`.`id_faktur_penjualan`) and (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` =
            `b`.`id_penjualan_obat`) and (`a`.`id_ruang` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and
            (`c`.`del_flag` <> '1') AND a.id_ruang='" . $ruang . "')" . $kondisiz . " ORDER BY `Faktur`, `Dokter`
            ) GABUNGAN GROUP BY `Dokter`, `Kode Obat`, `Tanggal Transaksi`";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='10%' class='headerTagihan'>Dokter</td>";
                $html .= "<td width='5%' class='headerTagihan'>Kode</td>";
                $html .= "<td width='30%' class='headerTagihan'>Nama Obat</td>";
                $html .= "<td width='5%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $dkt = "";
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlTotal = 0;
                $subQty = 0;
                $subDisc = 0;
                $subTotal = 0;
                while ($data = @mysql_fetch_array($result)) {
                    if ($dkt != "" && $dkt != $data['Dokter']) {
                        $html .= "<tr valign='top'>";
                        $html .= "<td width='10%' colspan='4' class='total'>Sub Total</td>";
                        $html .= "<td width='5%' align='right' class='total'>" . number_format($subQty, 0, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                        $html .= "<td width='10%' align='right' class='total'>" . number_format($subDisc, 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right' class='total'>" . number_format($subTotal, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $subQty = 0;
                        $subDisc = 0;
                        $subTotal = 0;
                    }
                    $html .= "<tr valign='top'>";
                    if ($dkt == $data['Dokter'])
                        $html .= "<td width='20%'>&nbsp;
            </td>";
                    else
                        $html .= "<td width='20%'>" . $data['Dokter'] . "</td>";

                    $html .= "<td width='5%'>" . $data['Kode Obat'] . "</td>";
                    $html .= "<td width='30%'>" . $data['Nama Obat'] . "</td>";
                    $html .= "<td width='10%' align='center'>" . $this->formatDateDb($data['Tanggal Transaksi']) . "</td>";
                    $html .= "<td width='5%' align='right'>" . number_format($data['QTY'], 0, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Harga'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Jumlah'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $dkt = $data['Dokter'];
                    $subQty += $data['QTY'];
                    $subDisc += $data['Diskon'];
                    $subTotal += $data['Jumlah'];
                    $jmlQty += $data['QTY'];
                    $jmlDisc += $data['Diskon'];
                    $jmlTotal += $data['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='4' class='total'>Total</td>";
                $html .= "<td width='5%' align='right' class='total'>" . number_format($jmlQty, 0, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        } else if ($tipe_laporan == '8') {
            $query = "SELECT `Kode Obat`, `Nama Obat`, `Dokter`, `Tanggal Transaksi`, SUM(QTY) AS QTY, `Harga`, SUM(`Diskon`) AS `Diskon`, SUM(`Jumlah`) AS `Jumlah` FROM (
            select `a`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`g`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `c`.`kode_obat` AS `Kode Obat`, `c`.`nama_obat` AS `Nama Obat`, cast(`e`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`,
            cast(`e`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `b`.`qty` AS `QTY`, round(`b`.`harga`) AS `Harga`, ifnull((`d`.`diskon` / `a`.`jml_obat`), 0)
            AS `Diskon`, (`b`.`qty` * round(`b`.`harga`)) AS `Jumlah`, `f`.`ruang` AS `Ruang`, `e`.`tipe_pembayaran` AS `Status` from
            ((((((`rm_faktur_penjualan` `a` join `rm_penjualan_obat` `b` on(((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`)
            and (`b`.`del_flag` <> 1)))) join `rm_obat` `c` on((`c`.`id_obat` = `b`.`id_obat`))) left join `rm_diskon_obat` `d`
            on(((`a`.`id_faktur_penjualan` = `d`.`id_faktur`) and (`d`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur_penjualan`) and (`e`.`del_flag` <> 1)))) join `rm_ruang` `f`
            on((`a`.`id_ruang` = `f`.`id_ruang`))) left join `rm_dokter` `g` on((`a`.`id_dokter` = `g`.`id_dokter`))) where
            (`a`.`del_flag` <> 1) AND a.id_ruang='" . $ruang . "'" . $kondisix . "
            UNION
            select `a`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`h`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`f`.`tgl_pembayaran` as date) AS `Tanggal Transaksi`,
            cast(`f`.`tgl_pembayaran` as time) AS `Jam Transaksi`, `c`.`qty` AS `QTY`, round(`c`.`harga`) AS `Harga`, ifnull((`e`.`diskon` / `a`.`jml_obat`), 0)
            AS `Diskon`, ((`c`.`qty` * round(`c`.`harga`)) + ((`b`.`jml_racikan` * 500) / `b`.`jml_Obat`)) AS `Jumlah`, `g`.`ruang` AS `Ruang`,
            `f`.`tipe_pembayaran` AS `Status` from (((((((`rm_faktur_penjualan` `a` join `rm_racikan` `b` on(((`a`.`id_faktur_penjualan` =
            `b`.`id_faktur_penjualan`) and (`b`.`del_flag` <> 1)))) join `rm_detail_racikan` `c` on(((`b`.`id_racikan` = `c`.`id_racikan`)
            and (`c`.`del_flag` <> 1)))) join `rm_obat` `d` on((`c`.`id_obat` = `d`.`id_obat`))) left join `rm_diskon_obat` `e`
            on(((`a`.`id_faktur_penjualan` = `e`.`id_faktur`) and (`e`.`del_flag` <> 1)))) join `rm_pembayaran_obat` `f`
            on(((`a`.`id_faktur_penjualan` = `f`.`id_faktur_penjualan`) and (`f`.`del_flag` <> 1)))) join `rm_ruang` `g` on((`a`.`id_ruang`
            = `g`.`id_ruang`))) left join `rm_dokter` `h` on((`a`.`id_dokter` = `h`.`id_dokter`))) where (`a`.`del_flag` <> 1)
            AND a.id_ruang='" . $ruang . "'" . $kondisiy . "
            UNION
            select `c`.`id_faktur_penjualan` AS `Faktur`, `a`.`nama_pasien` AS `Nama Pasien`, ifnull(`f`.`nama_dokter`, 'Resep Bebas') AS `Dokter`,
            `d`.`kode_obat` AS `Kode Obat`, `d`.`nama_obat` AS `Nama Obat`, cast(`c`.`tgl_retur` as date) AS `Tanggal Transaksi`,
            cast(`c`.`tgl_retur` as time) AS `Jam Transaksi`, (`c`.`jumlah` * -(1)) AS `QTY`, round(`b`.`harga`) AS `Harga`, ((`c`.`pros_retur` *
            `b`.`harga`) * `c`.`jumlah`) AS `Diskon`, ((((1 - `c`.`pros_retur`) * round(`b`.`harga`)) * `c`.`jumlah`) * -(1)) AS `Jumlah`, `e`.`ruang`
            AS `Ruang`, (case when (`a`.`status` = '1') then 'Lunas' else 'Kredit' end) AS `Status` from (((((`rm_faktur_penjualan` `a` join
            `rm_penjualan_obat` `b`) join `rm_retur_penjualan_obat` `c`) join `rm_obat` `d`) join `rm_ruang` `e`) left join `rm_dokter` `f`
            on((`a`.`id_dokter` = `f`.`id_dokter`))) where ((`d`.`id_obat` = `c`.`id_obat`) and (`a`.`id_faktur_penjualan` =
            `c`.`id_faktur_penjualan`) and (`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`) and (`c`.`id_penjualan_obat` =
            `b`.`id_penjualan_obat`) and (`a`.`id_ruang` = `e`.`id_ruang`) and (`a`.`del_flag` <> '1') and (`b`.`del_flag` <> '1') and
            (`c`.`del_flag` <> '1') AND a.id_ruang='" . $ruang . "')" . $kondisiz . " ORDER BY `Faktur`, `Dokter`
            ) GABUNGAN GROUP BY `Kode Obat`, `Dokter`, `Tanggal Transaksi`";
            $result = $this->runQuery($query);
            if (@mysql_num_rows($result) > 0) {
                $html .= "<table class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='5%' class='headerTagihan'>Kode</td>";
                $html .= "<td width='30%' class='headerTagihan'>Nama Obat</td>";
                $html .= "<td width='10%' class='headerTagihan'>Dokter</td>";
                $html .= "<td width='5%' class='headerTagihan'>Tanggal</td>";
                $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
                $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $obt = "";
                $jmlQty = 0;
                $jmlDisc = 0;
                $jmlTotal = 0;
                $subQty = 0;
                $subDisc = 0;
                $subTotal = 0;
                while ($data = @mysql_fetch_array($result)) {
                    if ($obt != "" && $obt != $data['Kode Obat']) {
                        $html .= "<tr valign='top'>";
                        $html .= "<td width='10%' colspan='4' class='total'>Sub Total</td>";
                        $html .= "<td width='5%' align='right' class='total'>" . number_format($subQty, 0, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                        $html .= "<td width='10%' align='right' class='total'>" . number_format($subDisc, 2, ',', '.') . "</td>";
                        $html .= "<td width='10%' align='right' class='total'>" . number_format($subTotal, 2, ',', '.') . "</td>";
                        $html .= "</tr>";
                        $subQty = 0;
                        $subDisc = 0;
                        $subTotal = 0;
                    }
                    $html .= "<tr valign='top'>";
                    if ($obt == $data['Kode Obat']) {
                        $html .= "<td width='20%'>&nbsp;
            </td>";
                        $html .= "<td width='30%'>&nbsp;
            </td>";
                    } else {
                        $html .= "<td width='5%'>" . $data['Kode Obat'] . "</td>";
                        $html .= "<td width='30%'>" . $data['Nama Obat'] . "</td>";
                    }
                    $html .= "<td width='20%'>" . $data['Dokter'] . "</td>";
                    $html .= "<td width='10%' align='center'>" . $this->formatDateDb($data['Tanggal Transaksi']) . "</td>";
                    $html .= "<td width='5%' align='right'>" . number_format($data['QTY'], 0, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Harga'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Diskon'], 2, ',', '.') . "</td>";
                    $html .= "<td width='10%' align='right'>" . number_format($data['Jumlah'], 2, ',', '.') . "</td>";
                    $html .= "</tr>";
                    $obt = $data['Kode Obat'];
                    $subQty += $data['QTY'];
                    $subDisc += $data['Diskon'];
                    $subTotal += $data['Jumlah'];
                    $jmlQty += $data['QTY'];
                    $jmlDisc += $data['Diskon'];
                    $jmlTotal += $data['Jumlah'];
                }
                $html .= "<tr valign='top'>";
                $html .= "<td width='10%' colspan='4' class='total'>Total</td>";
                $html .= "<td width='5%' align='right' class='total'>" . number_format($jmlQty, 0, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>&nbsp;
            </td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlDisc, 2, ',', '.') . "</td>";
                $html .= "<td width='10%' align='right' class='total'>" . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</table>";
            } else {
                $html = "Data tidak ditemukan";
            }
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function saveObatBal($id_obat_balance, $tipe, $id_obat, $jumlah) {
        if (isset($_SESSION['level'])) {
            $q_cek = "select * from rm_stock_obat_apotik where id_obat='" . $id_obat . "' and id_ruang='" . $_SESSION['level'] . "'";
            $r_cek = $this->runQuery($q_cek);

            if (@mysql_num_rows($r_cek) > 0) {
                $query = "insert into rm_obat_balance(
            id_obat,
            id_ruang,
            id_keperluan,
            jumlah
            ) values (
            '" . $id_obat . "',
            '" . $_SESSION['level'] . "',
            '" . $tipe . "',
            '" . $jumlah . "'
            )";

                $sisa = @mysql_result($r_cek, 0, 'stock');
                $stok_lama = @mysql_result($r_cek, 0, 'stock_lama');
                $stok_baru = @mysql_result($r_cek, 0, 'stock_baru');
                $stok_limit = @mysql_result($r_cek, 0, 'stock_limit');
                if ($stok_lama < $jumlah) {
                    $sisa = $stok_lama - $jumlah + $stok_baru;
                    $stok_lama = 0;
                    $stok_baru = $sisa;
                } else if ($stok_lama == 0) {
                    $sisa = $stok_baru - $jumlah;
                    $stok_baru = $sisa;
                } else {
                    $stok_lama = $stok_lama - $jumlah;
                    $sisa = $stok_baru + $stok_lama;
                }
                if ($sisa < 0) {
                    $return = "0";
                } else if ($sisa == 0) {
                    $result = $this->runQuery($query);
                    $q = "update rm_stock_obat_apotik set
            stock_lama='" . $stok_lama . "',
            stock_baru='" . $stok_baru . "',
            stock='" . $sisa . "'
            where
            id_obat='" . $id_obat . "'
            and id_ruang='" . $_SESSION['level'] . "'";
                    $r = $this->runQuery($q);

                    if ($r) {
                        $return = "2";
                    } else {
                        $return = "ERROR";
                    }
                } else if ($sisa <= $stok_limit) {
                    $result = $this->runQuery($query);
                    $q = "update rm_stock_obat_apotik set
            stock_lama='" . $stok_lama . "',
            stock_baru='" . $stok_baru . "',
            stock='" . $sisa . "'
            where
            id_obat='" . $id_obat . "'
            and id_ruang='" . $_SESSION['level'] . "'";
                    $r = $this->runQuery($q);

                    if ($r) {
                        $return = "1";
                    } else {
                        $return = "ERROR";
                    }
                } else {
                    $result = $this->runQuery($query);
                    $q = "update rm_stock_obat_apotik set
            stock_lama='" . $stok_lama . "',
            stock_baru='" . $stok_baru . "',
            stock='" . $sisa . "'
            where
            id_obat='" . $id_obat . "'
            and id_ruang='" . $_SESSION['level'] . "'";
                    $r = $this->runQuery($q);

                    if ($r) {
                        $return = "1";
                    } else {
                        $return = "ERROR";
                    }
                }
            } else {
                $return = "ERROR:Stock Obat belum di distribusikan.";
            }

            return $return;
        }
        return 'LOGIN';
    }

    public function getLaporanPenjualan(
    $shift, $status, $startDate
    ) {
        $kondisi = "";
        $kondisi1 = "";
        if ($shift != "") {
            $q_shift = "select * from rm_shift where shift='" . $shift . "'";
            $r_shift = $this->runQuery($q_shift);
            if ($shift == "M") {
                $q_interval = "SELECT DATE_ADD('" . $this->formatDateDb($startDate) . "', INTERVAL 1 DAY) as new";
                $r_int = $this->runQuery($q_interval);
                $endDate = $this->formatDateDb(@mysql_result($r_int, 0, 'new'));
            } else {
                $endDate = $startDate;
            }
            $kondisi .= " AND tgl_pembayaran BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
            $kondisi1 .= " AND tgl_penjualan BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
            $kondisi_retur = " AND tgl_retur BETWEEN '" . $this->formatDateDb($startDate) . " " . @mysql_result($r_shift, 0, 'jam_awal') . "' AND '" . $this->formatDateDb($endDate) . " " . @mysql_result($r_shift, 0, 'jam_akhir') . "'";
        }

        if ($shift == "") {
            $html = "Shift harus dipilih.";
        } else if ($status == "") {
            $html = "Status harus dipilih.";
        } else {
            if ($status == '0') {
                $query = "select a.id_faktur_penjualan, b.id_pasien, b.nama_pasien, b.ruang, b.id_dokter, b.status, sum(a.bayar) as jumlah
            from rm_pembayaran_obat a, rm_faktur_penjualan b WHERE a.del_flag<>'1' and b.del_flag<>'1' " . $kondisi . " and b.id_ruang='" . $_SESSION['level'] . "'
            and a.bayar > 0 and b.id_faktur_penjualan=a.id_faktur_penjualan group by a.id_faktur_penjualan";
                $nmstatus = "Lunas";
                $c_retur = "SELECT COUNT(*) FROM rm_retur_penjualan_obat a, rm_faktur_penjualan b WHERE b.id_ruang ='" . $_SESSION['level'] . "' and a.del_flag<>'1'
            and b.id_faktur_penjualan = a.id_faktur_penjualan AND a.jns_retur='1' " . $kondisi_retur . " group by a.id_retur_penjualan_obat";
            } else if ($status == "1") {
                $query = "select a.id_faktur_penjualan, b.id_pasien, b.nama_pasien, b.ruang, b.id_dokter, b.status, sum(a.sisa) as jumlah
            from rm_pembayaran_obat a, rm_faktur_penjualan b where a.del_flag<>'1' and b.del_flag<>'1' " . $kondisi . " and b.id_ruang='" . $_SESSION['level'] . "'
            and a.sisa > 0 and b.id_faktur_penjualan=a.id_faktur_penjualan AND a.del_flag<>'1' AND a.id_pembayaran_obat = (SELECT max(id_pembayaran_obat)
            FROM rm_pembayaran_obat WHERE a.id_faktur_penjualan = id_faktur_penjualan AND rm_pembayaran_obat.del_flag<>'1')group by a.id_faktur_penjualan";
                $nmstatus = "Kredit";
                $c_retur = "SELECT COUNT(*) FROM rm_retur_penjualan_obat a WHERE a.del_flag<>'1' AND a.jns_retur='0' " . $kondisi_retur . " group by a.id_retur_penjualan_obat";
            } else if ($status == '2') {
                $query = "select a.id_faktur_penjualan, b.id_pasien, b.nama_pasien, b.ruang, b.id_dokter, b.status, sum(a.asuransi) as jumlah
            from rm_pembayaran_obat a, rm_faktur_penjualan b where a.del_flag<>'1' and b.del_flag<>'1' " . $kondisi . " and b.id_ruang='" . $_SESSION['level'] . "'
            and a.asuransi > 0 and b.id_faktur_penjualan=a.id_faktur_penjualan AND a.del_flag<>'1' group by a.id_faktur_penjualan";
                $nmstatus = "Asuransi";
                $c_retur = "SELECT COUNT(*) FROM rm_retur_penjualan_obat a, rm_faktur_penjualan b WHERE a.del_flag<>'1' AND a.jns_retur='1' and
            b.id_ruang ='" . $_SESSION['level'] . "' and b.id_faktur_penjualan = a.id_faktur_penjualan " . $kondisi_retur . " group by a.id_retur_penjualan_obat";
            }
            $result = $this->runQuery($query);
            $r_cret = $this->runQuery($c_retur);
            if (@mysql_num_rows($result) > 0 || @mysql_num_rows($r_cret) > 0) {
                $html = "<table class='data' cellspacing='0' cellpadding='0'>
            <tr height='21'>
            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
            </tr>
            <tr height='21'>
            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
            </tr>
            <tr height='21'>
            <td height='21'><u><b>Laporan Penjualan Obat</b></u><br><br></td>
            </tr>";
                $html .="</table>";
                $html .="Tanggal : " . $this->codeDate($this->formatDateDb($startDate));
                $html .= "<table style='font-family: verdana;font-size: 10pt;' class='data' width='100%'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<td width='2%' class='headerTagihan'>No</td>";
                $html .= "<td width='8%' class='headerTagihan'>Shift</td>";
                $html .= "<td width='10%' class='headerTagihan'>Status</td>";
                $html .= "<td width='5%' class='headerTagihan'>No Faktur</td>";
                $html .= "<td width='5%' class='headerTagihan'>No RM</td>";
                $html .= "<td width='10%' class='headerTagihan'>Nama Pasien</td>";
                $html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
                $html .= "<td width='10%' class='headerTagihan'>Total</td>";
                $html .= "<td width='10%' class='headerTagihan'>Asal RSP</td>";
                $html .= "<td width='10%' class='headerTagihan'>Dokter</td>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $i = 1;
                $jmlTotal = 0;
                $jmlQty = 0;
                if ($shift == "P")
                    $nmShift = "Pagi";
                else if ($shift == "S")
                    $nmShift = "Siang";
                else if ($shift == "M")
                    $nmShift = "Malam";
                else
                    $nmShift = "All";
                while ($data = @mysql_fetch_array($result)) {
                    $bayar = 0;
                    $nama_dokter = $this->getDokter($data['id_dokter']);
                    $q_diskon = "SELECT diskon FROM rm_diskon_obat WHERE id_faktur='" . $data['id_faktur_penjualan'] . "' and del_flag<>1";
                    $r_diskon = $this->runQuery($q_diskon);
                    $diskon = @mysql_result($r_diskon, 0, 'diskon');
                    $jmlTagihan = $this->getJumlahTagihanObat($data['id_faktur_penjualan']);
                    if ($status == '0')
                        $bayar = $data['jumlah'];
                    else if ($status == '1')
                        $bayar = $data['jumlah'];
                    else if ($status == '2')
                        $bayar = $data['jumlah'];
                    $html .= "<tr>";
                    $html .= "<td>" . $i . "</td>";
                    $html .= "<td>" . $nmShift . "</td>";
                    $html .= "<td>" . $nmstatus . "</td>";
                    $html .= "<td>" . $data['id_faktur_penjualan'] . "</td>";
                    $html .= "<td>" . $data['id_pasien'] . "</td>";
                    $html .= "<td>" . $data['nama_pasien'] . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format($diskon, 2, ',', '.') . "</td>";
                    $html .= "<td align='right'>Rp. " . number_format($bayar, 2, ',', '.') . "</td>";
                    $html .= "<td>" . $data['ruang'] . "</td>";
                    $html .= "<td>" . $nama_dokter . "</td>";
                    $html .= "</tr>";
                    $i++;
                    $jmlTotal += $bayar;
                }

                if ($status != '2') {
                    if ($status == '0')
                        $kondStat = " and b.jns_retur='1'";
                    else
                        $kondStat = " and b.jns_retur='0'";
                    $q_retur = "SELECT `b`.`id_retur` AS `faktur`, a.id_dokter, `a`.`id_pasien` AS `id_pasien`, `a`.`nama_pasien` AS `nama_pasien`, sum(((`b`.`jumlah` * `c`.`harga`) - ((`b`.`jumlah` * `c`.`harga`) * (1 - `b`.`pros_retur`)))) AS `diskon`,
            sum((((`b`.`jumlah` * `c`.`harga`) * (1 - `b`.`pros_retur`)) * -(1))) AS `jumlah`, `a`.`ruang` AS `ruang`
            FROM ((`rm_faktur_penjualan` `a` join `rm_retur_penjualan_obat` `b` on((`a`.`id_faktur_penjualan` = `b`.`id_faktur_penjualan`))) join `rm_penjualan_obat` `c` on(((`b`.`id_faktur_penjualan` = `c`.`id_faktur_penjualan`) and (`b`.`id_penjualan_obat` = `c`.`id_penjualan_obat`))))
            WHERE (`b`.`del_flag` <> 1) AND (`c`.`del_flag` <> 1) AND (`a`.`del_flag` <> 1) AND a.id_ruang='" . $_SESSION['level'] . "' " . $kondStat . " " . $kondisi_retur . "
            GROUP BY `b`.`id_retur`";
                    $r_retur = $this->runQuery($q_retur);
                    if (@mysql_num_rows($r_retur) > 0) {
                        while ($retur = @mysql_fetch_array($r_retur)) {
                            $dokter = $this->getDokter($retur['id_dokter']);
                            $html .= "<tr>";
                            $html .= "<td>" . $i . "</td>";
                            $html .= "<td>" . $nmShift . "</td>";
                            $html .= "<td>" . $nmstatus . "</td>";
                            $html .= "<td>" . $retur['faktur'] . "</td>";
                            $html .= "<td>" . $retur['id_pasien'] . "</td>";
                            $html .= "<td>" . $retur['nama_pasien'] . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format($retur['diskon'], 2, ',', '.') . "</td>";
                            $html .= "<td align='right'>Rp. " . number_format($retur['jumlah'], 2, ',', '.') . "</td>";
                            $html .= "<td>" . $retur['ruang'] . "</td>";
                            $html .= "<td>" . $dokter . "</td>";
                            $html .= "</tr>";
                            $i++;
                            $jmlTotal += $retur['jumlah'];
                        }
                    }
                }
                $html .= "<tr>";
                $html .= "<td colspan='7' class='total'>Sub Total</td>";
                $html .= "<td align='right' class='total'>Rp. " . number_format($jmlTotal, 2, ',', '.') . "</td>";
                $html .= "<td colspan='2' align='right' class='total'>&nbsp;</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "</html>";
            } else {
                $html = "Data Tidak Ditemukan";
            }
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getLaporanObatPasien($id_pasien, $ruang, $status) {
        $kondisi = "";
        if ($status != 3)
            $kondisi .= " AND status = " . $status . "";
        if ($ruang != "")
            $kondisi .= " AND id_ruang = " . $ruang . "";
        $query = "SELECT id_ruang, id_faktur_penjualan, date(tgl_penjualan) as tgl_penjualan FROM rm_faktur_penjualan WHERE id_pasien='" . $id_pasien . "'
            AND jns_customer='Pasien' AND del_flag<>'1'" . $kondisi;
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            $html = "<table style='font-family: verdana;font-size: 10pt;' class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Kode Obat</td>";
            $html .= "<td width='20%' class='headerTagihan'>Nama Obat</td>";
            $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
            $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='5%' class='headerTagihan'>No Faktur</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $i = 1;
            $total = 0;
            while ($data = @mysql_fetch_array($result)) {
                $html .= "<tr>";
                $html .= "<td>" . $this->getNamaRuang($data['id_ruang']) . "</td>";
                $html .= "<td colspan='7'>" . $this->codeDate($data['tgl_penjualan']) . "</td>";
                $html .= "</tr>";
                $q_detail = "SELECT a.id_penjualan_obat, id_faktur_penjualan, b.kode_obat, a.id_obat, b.nama_obat, qty, harga, r_code
            FROM rm_penjualan_obat a, rm_obat b
            WHERE b.id_obat=a.id_obat AND id_faktur_penjualan='" . $data['id_faktur_penjualan'] . "' and a.del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                $jmlTotal = 0;
                if (@mysql_num_rows($r_detail) > 0) {
                    while ($rec = @mysql_fetch_array($r_detail)) {
                        $html .= "<tr>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td>" . $rec['kode_obat'] . "</td>";
                        $html .= "<td>" . $rec['nama_obat'] . "</td>";
                        $html .= "<td>" . $rec['qty'] . "</td>";
                        $html .= "<td align='right'>Rp. " . number_format($rec['harga'], 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>Rp. " . number_format(($rec['harga'] * $rec['qty']), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . $data['id_faktur_penjualan'] . "</td>";
                        $html .= "</tr>";
                        $jmlTotal += ( $rec['harga'] * $rec['qty']);
                    }
                    $html .= "<tr>";
                    $html .= "<td colspan='6' class='total'>Sub Total</td>";
                    $html .= "<td align='right' class='total'>Rp. " . number_format($jmlTotal, 2, ',', '.') . "</td>";
                    $html .= "<td colspan='2' align='right' class='total'>&nbsp;</td>";
                    $html .= "</tr>";
                }
                $total += $jmlTotal;
            }
            $html .= "<tr>";
            $html .= "<td colspan='6' class='total'>Grand Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td colspan='2' align='right' class='total'>&nbsp;</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</html>";
        } else {
            $html = "Data Tidak ditemukan.";
        }

        $arr[] = array('display' => $html);

        if ($arr) {
            return $this->jEncode($arr);
        }
    }

    public function getDetailpasien($id_pasien) {
        $q_check = "SELECT MAX(id_faktur_penjualan) as id_faktur_penjualan FROM rm_faktur_penjualan WHERE id_pasien='" . $id_pasien . "' and id_ruang='" . $_SESSION['level'] . "' AND status!='2' and del_flag<>'1'";
        $r_check = $this->runQuery($q_check);
        if (@mysql_num_rows($r_check) > 0) {
            $q_bayar = "select count(*) as jmlBayar from rm_pembayaran_obat where id_faktur_penjualan='" . @mysql_result($r_check, 0, 'id_faktur_penjualan') . "' AND del_flag<>'1'";
            $r_bayar = $this->runQuery($q_bayar);

            if (@mysql_result($r_bayar, 0, 'jmlBayar') <= 0)
                $id_faktur = @mysql_result($r_check, 0, 'id_faktur_penjualan');
            else
                $id_faktur = "";

            $query = "select a.id_pasien, a.id_tipe_asuransi, a.nama_pasien, a.alamat, a.id_tipe_pasien, b.tipe_pasien, a.tgl_lahir
            from rm_pasien a, rm_tipe_pasien b
            where b.id_tipe_pasien=a.id_tipe_pasien and a.id_pasien='" . $id_pasien . "'";
            $result = $this->runQuery($query);

            if ($result) {
                $q_pendaftaran = "SELECT MAX(a.id_pendaftaran) AS id_pendaftaran FROM rm_pendaftaran a
            WHERE a.id_pasien='" . $id_pasien . "' AND a.del_flag<>'1' and a.status_pembayaran<>'2' and a.id_asal_pendaftaran='0'";
                $r_pendaftaran = $this->runQuery($q_pendaftaran);

                $q_detail = "select a.id_ruang, a.id_dokter, a.id_tipe_pendaftaran, a.id_ruang, b.ruang from rm_pendaftaran a, rm_ruang b where b.id_ruang=a.id_ruang
            and a.id_pendaftaran='" . @mysql_result($r_pendaftaran, 0, 'id_pendaftaran') . "'";
                $r_detail = $this->runQuery($q_detail);

                $identitasPasien = $this->getNoIdentitasPasien($id_pasien);
                if ($this->checkIdentitasPasienFarmasi($identitasPasien)) {
                    $karyawan = 1;
                } else {
                    $karyawan = 0;
                }

                $return = @mysql_result($r_detail, 0, 'ruang') . "<>" . @mysql_result($result, 0, 'nama_pasien') . "<>" . @mysql_result($result, 0, 'alamat') . "<>" . @mysql_result($r_detail, 0, 'id_tipe_pendaftaran') . "<>" . @mysql_result($result, 0, 'tipe_pasien') . "<>" . @mysql_result($r_detail, 0, 'id_dokter') . "<>" . $karyawan . "<>" . @mysql_result($result, 0, 'id_tipe_asuransi') . "<>" . $id_faktur . "<>" . @mysql_result($r_detail, 0, 'id_ruang') . "<>" . @mysql_result($r_pendaftaran, 0, 'id_pendaftaran') . "<>" . $this->getUmurTahun(@mysql_result($result, 0, 'tgl_lahir'));
            } else {
                $return = '0';
            }
        } else {
            $return = '0';
        }
        return $return;
    }

    public function getFakturAll($id_faktur_penjualan) {
        $query = "select * from rm_faktur_penjualan where del_flag<>'1' and status!='2' and id_faktur_penjualan='" . $id_faktur_penjualan . "'";
        $result = $this->runQuery($query);

        if ($result) {
            $q_cb = "SELECT id_pembayaran_obat FROM rm_pembayaran_obat WHERE id_faktur_penjualan = '" . $id_faktur_penjualan . "' AND del_flag<>1";
            $r_cb = $this->runQuery($q_cb);
            if (@mysql_num_rows($r_cb) == 0) {
                $sudah = 0;
            } else {
                $sudah = 1;
            }

            if ($id_pasien == "")
                $id_pasien = @mysql_result($result, 0, 'id_pasien');
            $identitasPasien = $this->getNoIdentitasPasien(@mysql_result($result, 0, 'id_pasien'));
            if ($this->checkIdentitasPasienFarmasi($identitasPasien)) {
                $karyawan = 1;
            } else {
                $karyawan = 0;
            }

            if (@mysql_result($result, 0, 'jns_customer') != "Umum") {
                $id_tipe_asuransi = $this->getTipeAsuransiPasien(@mysql_result($result, 0, 'id_pasien'));
                $tipe_pasien = $this->getTipePasien($this->getTipePasienId(@mysql_result($result, 0, 'id_pasien')));
                $q_pendaftaran = "select id_pendaftaran from rm_pendaftaran a, rm_ruang b where a.id_pasien='" . $id_pasien . "'
            and a.id_ruang=b.id_ruang and b.ruang='" . @mysql_result($result, 0, 'ruang') . "'";
                $r_pendaftaran = $this->runQuery($q_pendaftaran);
                $id_pendaftaran = @mysql_result($r_pendaftaran, 0, 'id_pendaftaran');
                $tipe_pendaftaran = $this->getIdTipePendaftaran($id_pendaftaran);
            } else {
                $id_tipe_asuransi = "";
                $tipe_pasien = "";
                $id_pendaftaran = "";
                $tipe_pendaftaran = "";
            }

            $field = @mysql_result($result, 0, 'id_faktur_penjualan') . "<>" .
                    @mysql_result($result, 0, 'no_resep') . "<>" .
                    @mysql_result($result, 0, 'jns_customer') . "<>" .
                    @mysql_result($result, 0, 'id_dokter') . "<>" .
                    @mysql_result($result, 0, 'id_pasien') . "<>" .
                    @mysql_result($result, 0, 'ruang') . "<>" .
                    @mysql_result($result, 0, 'nama_pasien') . "<>" .
                    @mysql_result($result, 0, 'alamat') . "<>" .
                    $tipe_pasien . "<>" .
                    $id_tipe_asuransi . "<>" .
                    $karyawan . "<>" .
                    $tipe_pendaftaran . "<>";
            $html = "";
            $html .= "<p align='center' style=' font-family: verdana; font-size: 11px;' ><strong>KWITANSI PEMBAYARAN OBAT<br><u>RSUD Dr. SOEGIRI LAMONGAN</u></strong></p><hr>
            <table style=' font-family: verdana; font-size: 10px;' width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tr >
            <td width='19%'>No Faktur</td>
            <td width='30%'>: <b>" . @mysql_result($result, 0, 'id_faktur_penjualan') . "</b></td>
                                    <td width='2%'>&nbsp;</td>
            <td width='19%'>No RM</td>
            <td width='30%'>: <b>" . @mysql_result($result, 0, 'id_pasien') . "</b></td>
            </tr>
            <tr >
            <td width='19%'>Tanggal</td>
            <td width='30%'>: <b>" . @mysql_result($result, 0, 'tgl_penjualan') . "</b></td>
                                    <td width='2%'>&nbsp;</td>
            <td width='19%'>Nama Pasien</td>
            <td width='30%'>: <b>" . @mysql_result($result, 0, 'nama_pasien') . "</b></td>
            </tr>
            <tr >
            <td width='19%'>Jam</td>
            <td width='30%'>: <b>" . $jam . "</b></td>
                                    <td width='2%'>&nbsp;</td>
            <td width='19%'>Ruang</td>
            <td width='30%'>: <b>" . @mysql_result($result, 0, 'ruang') . "</b></td>
            </tr>
            </table>";

            $q_obat = "SELECT id_faktur_penjualan, a.id_obat, b.nama_obat, qty, round(harga) as harga, r_code 
            FROM rm_penjualan_obat a, rm_obat b
            WHERE b.id_obat=a.id_obat AND id_faktur_penjualan='" . $id_faktur_penjualan . "' AND a.del_flag<>'1'";

            $r_obat = $this->runQuery($q_obat);
            $html .= "<style> .listObat td,.listObat th{border: 1.8px solid #000000;}</style>";
            $html .= "<p style='font-family: verdana; font-size: 10px; text-align: left;'><b>Detail Obat</b></p><table class='listObat' style='border-collapse: collapse; font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#000000'>
                  <tr>
                        <th width='5%' align='center' bgcolor='#ffffff'>No</th>
                        <th width='45%' align='center' bgcolor='#ffffff'>Nama Obat </th>
                        <th width='10%' align='center' bgcolor='#ffffff'>Qty</th>
                        <th width='10%' align='center' bgcolor='#ffffff'>Kode R </th>
                        <th width='10%' align='center' bgcolor='#ffffff'>Harga</th>
                        <th width='20%' align='center' bgcolor='#ffffff'>Total</th>
                  </tr>";
            $i = 1;
            $totalAll = 0;
            $admin = 500;
            $totalObat = 0;
            while ($rec = mysql_fetch_array($r_obat)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total += 200;
                $html .= "<tr>
                        <td width='5%' bgcolor='#FFFFFF' align='center'>" . $i . "</td>
                        <td width='45%' bgcolor='#FFFFFF'>" . $rec['nama_obat'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . $rec['qty'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='center'>" . $rec['r_code'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . number_format($rec['harga'], 2, ',', '.') . "</td>
                        <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format($total, 2, ',', '.') . "</td>
                  </tr>";
                $totalObat += $total;
                $totalAll += $total;
                $i++;
            }
            $html .= "<tr>
                <td width='5%' bgcolor='#FFFFFF' align='right' colspan='5'><b>Sub Total</b></td>
                <td width='20%' bgcolor='#FFFFFF' align='right'><b>" . number_format($totalObat, 2, ',', '.') . "</b></td>
          </tr>";
            $html .= "</table>";

            $j = 1;
            $q_racikan = "select * from rm_racikan where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
            $r_racikan = $this->runQuery($q_racikan);
            if (@mysql_num_rows($r_racikan) > 0)
                $html .= "<span style=' font-family: verdana; font-size: 10px;'><b>Obat Racikan</b></span><table style=' font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='1' cellspacing='1' bgcolor='#000000'>";
            while ($racikan = mysql_fetch_array($r_racikan)) {
                $q_obat = "SELECT a.id_obat, b.nama_obat, qty, harga, r_code 
                              FROM rm_detail_racikan a, rm_obat b
                              WHERE b.id_obat=a.id_obat and a.id_racikan='" . $racikan['id_racikan'] . "'";

                $r_obat = $this->runQuery($q_obat);
                $html .= "<table class='listObat' style='border-collapse: collapse; font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#000000'>
                          <tr>
                                <th width='5%' align='center' bgcolor='#ffffff'>No</th>
                                <th width='45%' align='center' bgcolor='#ffffff'>Nama Obat </th>
                                <th width='10%' align='center' bgcolor='#ffffff'>Qty</th>
                                <th width='10%' align='center' bgcolor='#ffffff'>Kode R </th>
                                <th width='10%' align='center' bgcolor='#ffffff'>Harga</th>
                                <th width='20%' align='center' bgcolor='#ffffff'>Total</th>
                          </tr>";
                $k = 1;
                $totalObat = 0;
                while ($rec = mysql_fetch_array($r_obat)) {
                    $total = $rec['qty'] * $rec['harga'];
                    if ($rec['r_code'] == 'Ya')
                        $total += 200;
                    $html .= "<tr>
                                <td width='5%' bgcolor='#FFFFFF' align='center'>" . $k . "</td>
                                <td width='45%' bgcolor='#FFFFFF'>" . $rec['nama_obat'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='right'>" . $rec['qty'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='center'>" . $rec['r_code'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='right'>" . number_format($rec['harga'], 2, ',', '.') . "</td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format($total, 2, ',', '.') . "</td>
                          </tr>";
                    $totalObat += $total;
                    $totalAll += $total;
                    $k++;
                }
                $html .= "</td></tr><tr>
                                <td width='3%' bgcolor='#FFFFFF' align='right' colspan='5'>Biaya Racikan</td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format(500, 2, ',', '.') . "</td>
                          </tr>";
                $html .= "<tr>
                                <td width='3%' bgcolor='#FFFFFF' align='right' colspan='5'><b>Sub Total</b></td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'><b>" . number_format(($totalObat + 500), 2, ',', '.') . "</b></td>
                          </tr>";
                $totalAll += 500;
                $html .= "</table>";
                $j++;
            }
            $html .= "</table>";
            $q_pembayaran = "SELECT SUM(bayar) as pembayaran, SUM(asuransi) as asuransi from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' 
                             AND del_flag<>'1'";
            $r_pembayaran = $this->runQuery($q_pembayaran);

            $q_diskon = "select diskon from rm_diskon_obat where del_flag<>1 AND id_faktur='" . $id_faktur_penjualan . "' and del_flag<>1";
            $r_diskon = $this->runQuery($q_diskon);
            $terbayar = @mysql_result($r_pembayaran, 0, 'pembayaran');

            $html .= "<br><table style=' font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='0' cellspacing='0'>
                  <tr>
                        <td rowspan='7' width='60%' align='Left'>&nbsp;</td>
                        <td width='20%' align='right'><b>Total</b></td>
                        <td width='20%' align='right'><b>" . number_format($totalAll, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Admin Bank</b></td>
                        <td width='20%' align='right'><b>" . number_format(500, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Diskon</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($r_diskon, 0, 'diskon'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Asuransi</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($result, 0, 'asuransi'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Grand Total</b></td>
                        <td width='20%' align='right'><b>" . number_format((($totalAll - @mysql_result($r_diskon, 0, 'diskon')) - @mysql_result($result, 0, 'asuransi')) + $admin, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Terbayar</b></td>
                        <td width='20%' align='right'><b>" . number_format($terbayar, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Bayar</b></td>
                        <td width='20%' align='right'><b>" . number_format((@mysql_result($result, 0, 'bayar') + @mysql_result($result, 0, 'kembali')), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td rowspan='6' width='60%' align='center'><b>" . $status . "</b></td>
                        <td width='20%' align='right'><b>Kurang</b></td>
                        <td width='20%' align='right'><b>" . number_format($sisa, 2, ',', '.') . "</b></td>
                  </tr>
                  </table>";
            $return = $field . $html;
            $return .= "<>" . $totalAll . "<>" . $sudah;
        } else {
            $return = '0';
        }

        return $return;
    }

    public function getDetailFakturPenjualan($id_pasien, $id_faktur_penjualan, $nama_pasien) {
        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and id_pasien='" . $id_pasien . "'";
        if ($id_faktur_penjualan != "")
            $kondisi .= " and id_faktur_penjualan='" . $id_faktur_penjualan . "'";
        if ($nama_pasien != "")
            $kondisi .= " and nama_pasien like '%" . $nama_pasien . "%'";

        $query = "select * from rm_faktur_penjualan where del_flag<>'1' and status!='2' AND id_ruang='" . $_SESSION['level'] . "' " . $kondisi;
        $result = $this->runQuery($query);

        if ($result) {
            $q_cb = "SELECT id_pembayaran_obat FROM rm_pembayaran_obat WHERE id_faktur_penjualan = '" . $id_faktur_penjualan . "' AND del_flag<>1";
            $r_cb = $this->runQuery($q_cb);
            if (@mysql_num_rows($r_cb) == 0) {
                $sudah = 0;
            } else {
                $sudah = 1;
            }

            $operator = $this->getPegawaiNip(@mysql_result($result, 0, 'level'));

            if ($id_pasien == "")
                $id_pasien = @mysql_result($result, 0, 'id_pasien');
            $identitasPasien = $this->getNoIdentitasPasien(@mysql_result($result, 0, 'id_pasien'));
            if ($this->checkIdentitasPasienFarmasi($identitasPasien)) {
                $karyawan = 1;
            } else {
                $karyawan = 0;
            }

            if (@mysql_result($result, 0, 'jns_customer') != "Umum") {
                $id_tipe_asuransi = $this->getTipeAsuransiPasien(@mysql_result($result, 0, 'id_pasien'));
            } else {
                $id_tipe_asuransi = "";
            }
            $tipe_pasien = $this->getTipePasien($this->getTipePasienId(@mysql_result($result, 0, 'id_pasien')));
            $q_pendaftaran = "select id_pendaftaran from rm_pendaftaran a, rm_ruang b where a.id_pasien='" . $id_pasien . "' 
                              and a.id_ruang=b.id_ruang and b.ruang='" . @mysql_result($result, 0, 'ruang') . "'";
            $r_pendaftaran = $this->runQuery($q_pendaftaran);

            $id_pendaftaran = @mysql_result($r_pendaftaran, 0, 'id_pendaftaran');
            $tipe_pendaftaran = $this->getIdTipePendaftaran($id_pendaftaran);

            $field = @mysql_result($result, 0, 'id_faktur_penjualan') . "<>" .
                    @mysql_result($result, 0, 'no_resep') . "<>" .
                    @mysql_result($result, 0, 'jns_customer') . "<>" .
                    @mysql_result($result, 0, 'id_dokter') . "<>" .
                    @mysql_result($result, 0, 'id_pasien') . "<>" .
                    @mysql_result($result, 0, 'ruang') . "<>" .
                    @mysql_result($result, 0, 'nama_pasien') . "<>" .
                    @mysql_result($result, 0, 'alamat') . "<>" .
                    $tipe_pasien . "<>" .
                    $id_tipe_asuransi . "<>" .
                    $karyawan . "<>" .
                    $tipe_pendaftaran . "<>";
            $html = "";
            $return = $field . $html;
            $return .= "<>" . $totalAll . "<>" . $sudah . "<>" . $operator;
        } else {
            $return = '0';
        }

        return $return;
    }

    public function getDetailFakturPenjualanHapus($id_pasien, $id_faktur_penjualan, $nama_pasien) {
        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and id_pasien='" . $id_pasien . "'";
        if ($id_faktur_penjualan != "")
            $kondisi .= " and id_faktur_penjualan='" . $id_faktur_penjualan . "'";
        if ($nama_pasien != "")
            $kondisi .= " and nama_pasien like '%" . $nama_pasien . "%'";

        $query = "select * from rm_faktur_penjualan where del_flag<>'1' " . $kondisi;
        $result = $this->runQuery($query);

        if ($result) {
            if ($id_pasien == "")
                $id_pasien = @mysql_result($result, 0, 'id_pasien');
            $identitasPasien = $this->getNoIdentitasPasien(@mysql_result($result, 0, 'id_pasien'));
            if ($this->checkIdentitasPasienFarmasi($identitasPasien)) {
                $karyawan = 1;
            } else {
                $karyawan = 0;
            }

            if (@mysql_result($result, 0, 'jns_customer') != "Umum") {
                $id_tipe_asuransi = $this->getTipeAsuransiPasien(@mysql_result($result, 0, 'id_pasien'));
            } else {
                $id_tipe_asuransi = "";
            }
            $tipe_pasien = $this->getTipePasien($this->getTipePasienId(@mysql_result($result, 0, 'id_pasien')));
            $q_pendaftaran = "select id_pendaftaran from rm_pendaftaran a, rm_ruang b where a.id_pasien='" . $id_pasien . "' 
                              and a.id_ruang=b.id_ruang and b.ruang='" . @mysql_result($result, 0, 'ruang') . "'";
            $r_pendaftaran = $this->runQuery($q_pendaftaran);

            $id_pendaftaran = @mysql_result($r_pendaftaran, 0, 'id_pendaftaran');
            $tipe_pendaftaran = $this->getIdTipePendaftaran($id_pendaftaran);

            $field = @mysql_result($result, 0, 'id_faktur_penjualan') . "<>" .
                    @mysql_result($result, 0, 'no_resep') . "<>" .
                    @mysql_result($result, 0, 'jns_customer') . "<>" .
                    @mysql_result($result, 0, 'id_dokter') . "<>" .
                    @mysql_result($result, 0, 'id_pasien') . "<>" .
                    @mysql_result($result, 0, 'ruang') . "<>" .
                    @mysql_result($result, 0, 'nama_pasien') . "<>" .
                    @mysql_result($result, 0, 'alamat') . "<>" .
                    $tipe_pasien . "<>" .
                    $id_tipe_asuransi . "<>" .
                    $karyawan . "<>" .
                    $tipe_pendaftaran . "<>";
            //cok
            $q_obat = "SELECT id_faktur_penjualan, a.id_obat, b.nama_obat, qty, harga, r_code 
                      FROM rm_penjualan_obat a, rm_obat b 
                      WHERE b.id_obat=a.id_obat AND id_faktur_penjualan='" . @mysql_result($result, 0, 'id_faktur_penjualan') . "' and a.del_flag<>'1'";

            $r_obat = $this->runQuery($q_obat);
            $html = "<b>Detail Obat</b><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                  <tr>
                        <td width='5%' align='center' bgcolor='#999999'>No</td>
                        <td width='45%' align='center' bgcolor='#999999'>Nama Obat </td>
                        <td width='10%' align='center' bgcolor='#999999'>Qty</td>
                        <td width='10%' align='center' bgcolor='#999999'>Kode R </td>
                        <td width='10%' align='center' bgcolor='#999999'>Harga</td>
                        <td width='20%' align='center' bgcolor='#999999'>Total</td>
                  </tr>";
            $i = 1;
            $totalAll = 0;
            $totalObat = 0;
            while ($rec = mysql_fetch_array($r_obat)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total += 200;
                $html .= "<tr>
                        <td width='5%' bgcolor='#FFFFFF' align='center'>" . $i . "</td>
                        <td width='45%' bgcolor='#FFFFFF'>" . $rec['nama_obat'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . $rec['qty'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='center'>" . $rec['r_code'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . number_format($rec['harga'], 2, ',', '.') . "</td>
                        <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format($total, 2, ',', '.') . "</td>
                  </tr>";
                $totalObat += $total;
                $totalAll += $total;
                $i++;
            }
            $html .= "<tr>
                <td width='5%' bgcolor='#FFFFFF' align='right' colspan='5'><b>Sub Total</b></td>
                <td width='20%' bgcolor='#FFFFFF' align='right'><b>" . number_format($totalObat, 2, ',', '.') . "</b></td>
          </tr>";
            $html .= "</table>";

            $html .= "<b>Obat Racikan</b><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                  <tr>
                        <td width='5%' align='center' bgcolor='#999999'>No</td>
                        <td width='95%' align='center' bgcolor='#999999'>Nama Racikan </td>
                  </tr>";
            $j = 1;
            $q_racikan = "select * from rm_racikan where id_faktur_penjualan='" . @mysql_result($result, 0, 'id_faktur_penjualan') . "' and del_flag<>'1'";
            $r_racikan = $this->runQuery($q_racikan);
            while ($racikan = mysql_fetch_array($r_racikan)) {
                $html .= "<tr>
                        <td width='5%' align='center' bgcolor='#ffffff'>" . $j . "</td>
                        <td width='95%' bgcolor='#ffffff'>" . $racikan['racikan'] . "</td>
                      </tr>";
                $html .= "<table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                          <tr>
                                <td width='5%' align='center' bgcolor='#999999' colspan='2'>";
                $q_obat = "SELECT a.id_obat, b.nama_obat, qty, harga, r_code 
                              FROM rm_detail_racikan a, rm_obat b
                              WHERE b.id_obat=a.id_obat and a.id_racikan='" . $racikan['id_racikan'] . "' and a.del_flag<>'1'";

                $r_obat = $this->runQuery($q_obat);
                $html .= "<b>Detail Obat Racikan</b><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                          <tr>
                                <td width='10%' align='center' bgcolor='#999999'>No</td>
                                <td width='40%' align='center' bgcolor='#999999'>Nama Obat </td>
                                <td width='10%' align='center' bgcolor='#999999'>Qty</td>
                                <td width='10%' align='center' bgcolor='#999999'>Kode R </td>
                                <td width='10%' align='center' bgcolor='#999999'>Harga</td>
                                <td width='20%' align='center' bgcolor='#999999'>Total</td>
                          </tr>";
                $k = 1;
                $totalObat = 0;
                while ($rec = mysql_fetch_array($r_obat)) {
                    $total = $rec['qty'] * $rec['harga'];
                    if ($rec['r_code'] == 'Ya')
                        $total += 200;
                    $html .= "<tr>
                                <td width='3%' bgcolor='#FFFFFF' align='center'>" . $k . "</td>
                                <td width='47%' bgcolor='#FFFFFF'>" . $rec['nama_obat'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='right'>" . $rec['qty'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='center'>" . $rec['r_code'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='right'>" . number_format($rec['harga'], 2, ',', '.') . "</td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format($total, 2, ',', '.') . "</td>
                          </tr>";
                    $totalObat += $total;
                    $totalAll += $total;
                    $k++;
                }
                $html .= "<tr>
                                <td width='3%' bgcolor='#FFFFFF' align='right' colspan='5'>Biaya Racikan</td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format(500, 2, ',', '.') . "</td>
                          </tr>";
                $html .= "<tr>
                                <td width='3%' bgcolor='#FFFFFF' align='right' colspan='5'><b>Sub Total</b></td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'><b>" . number_format(($totalObat + 500), 2, ',', '.') . "</b></td>
                          </tr>";
                $totalAll += 500;
                $html .= "</table></td></tr>";
                $j++;
            }
            $html .= "</table>";

            $q_obat = "SELECT a.id_faktur_penjualan, c.id_obat, b.nama_obat, c.jumlah as qty, a.harga, a.r_code, c.pros_retur
                      FROM rm_penjualan_obat a, rm_obat b, rm_retur_penjualan_obat c 
                      WHERE a.id_penjualan_obat=c.id_penjualan_obat and b.id_obat=a.id_obat and a.id_faktur_penjualan=c.id_faktur_penjualan AND c.id_faktur_penjualan='" . @mysql_result($result, 0, 'id_faktur_penjualan') . "' and a.del_flag<>'1'";

            $r_obat = $this->runQuery($q_obat);
            $html .= "<b>Retur Obat</b><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                  <tr>
                        <td width='5%' align='center' bgcolor='#999999'>No</td>
                        <td width='45%' align='center' bgcolor='#999999'>Nama Obat </td>
                        <td width='10%' align='center' bgcolor='#999999'>Qty</td>
                        <td width='10%' align='center' bgcolor='#999999'>Kode R </td>
                        <td width='10%' align='center' bgcolor='#999999'>Harga</td>
                        <td width='20%' align='center' bgcolor='#999999'>Total</td>
                  </tr>";
            $i = 1;
            $totalObat = 0;
            $totalRetur = 0;
            while ($rec = mysql_fetch_array($r_obat)) {
                $total = $rec['qty'] * $rec['harga'] * (1 - $rec['pros_retur']);
                if ($rec['r_code'] == 'Ya')
                    $total += 200;
                $html .= "<tr>
                        <td width='5%' bgcolor='#FFFFFF' align='center'>" . $i . "</td>
                        <td width='45%' bgcolor='#FFFFFF'>" . $rec['nama_obat'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . $rec['qty'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='center'>" . $rec['r_code'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . number_format(($rec['harga'] * (1 - $rec['pros_retur'])), 2, ',', '.') . "</td>
                        <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format($total, 2, ',', '.') . "</td>
                  </tr>";
                $totalObat += $total;
                $totalRetur += $total;
                $i++;
            }
            $html .= "<tr>
                <td width='5%' bgcolor='#FFFFFF' align='right' colspan='5'><b>Sub Total</b></td>
                <td width='20%' bgcolor='#FFFFFF' align='right'><b>" . number_format($totalObat, 2, ',', '.') . "</b></td>
          </tr>";
            $html .= "</table>";

            $q_tagihan = "SELECT *, date(tgl_penjualan) as tgl_jual FROM rm_faktur_penjualan WHERE id_pasien='" . @mysql_result($result, 0, 'id_pasien') . "'
                          AND STATUS!='2' and id_faktur_penjualan!='" . @mysql_result($result, 0, 'id_faktur_penjualan') . "' and del_flag<>'1'";
            $r_tagihan = $this->runQuery($q_tagihan);
            if (@mysql_num_rows($r_tagihan) > 0) {
                $html .= "<b>Tagihan Belum Terbayar</b><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#000000'>
                      <tr>
                            <td width='5%' align='center' bgcolor='#999999'>No</td>
                            <td width='25%' align='center' bgcolor='#999999'>No Faktur</td>
                            <td width='25%' align='center' bgcolor='#999999'>Tanggal Faktur</td>
                            <td width='45%' align='center' bgcolor='#999999'>Jumlah</td>
                      </tr>";
                $k = 1;
                $totTagihan = 0;
                while ($rec_tagihan = @mysql_fetch_array($r_tagihan)) {
                    $q_t_ret = "SELECT a.id_faktur_penjualan, c.id_obat, b.nama_obat, c.jumlah as qty, a.harga, a.r_code, c.pros_retur 
                              FROM rm_penjualan_obat a, rm_obat b, rm_retur_penjualan_obat c 
                              WHERE a.id_penjualan_obat=c.id_penjualan_obat and b.id_obat=a.id_obat and a.id_faktur_penjualan=c.id_faktur_penjualan AND c.id_faktur_penjualan='" . $rec_tagihan['id_faktur_penjualan'] . "' and a.del_flag<>'1'";

                    $r_t_ret = $this->runQuery($q_t_ret);
                    $total = 0;
                    $retur = 0;
                    while ($rec = mysql_fetch_array($r_t_ret)) {
                        $retur = $rec['qty'] * $rec['harga'] * (1 - $rec['pros_retur']);
                        if ($rec['r_code'] == 'Ya')
                            $total += 200;
                        $total += $retur;
                    }
                    $t_obat = $this->getTotalTagihanObat($rec_tagihan['id_faktur_penjualan']) - $total;
                    $html .= "<tr>
                            <td width='5%' align='center' bgcolor='#FFFFFF'>" . $k . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $rec_tagihan['id_faktur_penjualan'] . "</td>
                            <td width='25%' align='left' bgcolor='#FFFFFF'>" . $this->codeDate($rec_tagihan['tgl_jual']) . "</td>
                            <td width='45%' align='right' bgcolor='#FFFFFF'>" . number_format($t_obat, 2, ',', '.') . "</td>
                      </tr>";
                    $totTagihan += $t_obat;
                    $k++;
                }
                $html .= "<tr>
                        <td width='5%' align='center' bgcolor='#FFFFFF' colspan='3'><b>Sub Total</b></td>
                        <td width='45%' align='right' bgcolor='#FFFFFF'><b>" . number_format($totTagihan, 2, ',', '.') . "</b></td>
                  </tr>";
            }

            $q_pembayaran = "select sum(bayar) as pembayaran, asuransi, sum(diskon) as diskon from rm_pembayaran_obat where id_faktur_penjualan='" . @mysql_result($result, 0, 'id_faktur_penjualan') . "' AND del_flag<>'1'";
            $r_pembayaran = $this->runQuery($q_pembayaran);

            $html .= "<br><table style=' font-family: verdana; font-size: 11px;' width='100%' border='0' cellpadding='3' cellspacing='1'>
                  <tr>
                        <td width='80%' align='right'><b>Grand Total</b></td>
                        <td width='20%' align='right'><b>" . number_format($totalAll, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='80%' align='right'><b>Retur</b></td>
                        <td width='20%' align='right'><b>" . number_format($totalRetur, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='80%' align='right'><b>Diskon</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($r_pembayaran, 0, 'diskon'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='80%' align='right'><b>Asuransi</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($r_pembayaran, 0, 'asuransi'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='80%' align='right'><b>Terbayar</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($r_pembayaran, 0, 'pembayaran'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='80%' align='right'><b>Kurang Bayar</b></td>
                        <td width='20%' align='right'><b>" . number_format(($totalAll - $totalRetur - (@mysql_result($r_pembayaran, 0, 'pembayaran') + @mysql_result($r_pembayaran, 0, 'diskon') + @mysql_result($r_pembayaran, 0, 'asuransi'))), 2, ',', '.') . "</b></td>
                  </tr>
                  </table>";
            $return = $field . $html;
            $return .= "<>" . $totalAll;
        } else {
            $return = '0';
        }

        return $return;
    }

    public function getDetailReturFakturPenjualan($id_pasien, $id_faktur_penjualan, $nama_pasien) {
        $kondisi = "";
        if ($id_pasien != "")
            $kondisi .= " and id_pasien='" . $id_pasien . "'";
        if ($id_faktur_penjualan != "")
            $kondisi .= " and id_faktur_penjualan='" . $id_faktur_penjualan . "'";
        if ($nama_pasien != "")
            $kondisi .= " and nama_pasien like '%" . $nama_pasien . "%'";

        $query = "select * from rm_faktur_penjualan where del_flag<>'1' and id_faktur_penjualan in 
                  (select id_faktur_penjualan from rm_pembayaran_obat where del_flag<>1) and id_ruang = " . $_SESSION['level'] . " " . $kondisi;
        $result = $this->runQuery($query);

        if ($result) {
            $identitasPasien = $this->getNoIdentitasPasien(@mysql_result($result, 0, 'id_pasien'));
            if ($this->checkIdentitasPasienFarmasi($identitasPasien)) {
                $karyawan = 1;
            } else {
                $karyawan = 0;
            }

            $id_tipe_asuransi = $this->getAsuransiPasien(@mysql_result($result, 0, 'id_pasien'));
            $tipe_pasien = $this->getTipePasien($this->getTipePasienId(@mysql_result($result, 0, 'id_pasien')));
            $q_pendaftaran = "select id_pendaftaran from rm_pendaftaran a, rm_ruang b where a.id_pasien='" . $id_pasien . "' 
                              and a.id_ruang=b.id_ruang and b.ruang='" . @mysql_result($result, 0, 'ruang') . "'";
            $r_pendaftaran = $this->runQuery($q_pendaftaran);

            $id_pendaftaran = @mysql_result($r_pendaftaran, 0, 'id_pendaftaran');
            $tipe_pendaftaran = $this->getIdTipePendaftaran($id_pendaftaran);

            $field = @mysql_result($result, 0, 'id_faktur_penjualan') . "<>" .
                    @mysql_result($result, 0, 'no_resep') . "<>" .
                    @mysql_result($result, 0, 'jns_customer') . "<>" .
                    @mysql_result($result, 0, 'id_dokter') . "<>" .
                    @mysql_result($result, 0, 'id_pasien') . "<>" .
                    @mysql_result($result, 0, 'ruang') . "<>" .
                    @mysql_result($result, 0, 'nama_pasien') . "<>" .
                    @mysql_result($result, 0, 'alamat') . "<>" .
                    $tipe_pasien . "<>" .
                    $id_tipe_asuransi . "<>" .
                    $karyawan . "<>" .
                    $tipe_pendaftaran . "<>" .
                    @mysql_result($result, 0, 'status') . "<>";
            //cok
            $return = $field;
        } else {
            $return = '0';
        }

        return $return;
    }

    public function getDetailPenjualanObat($id_faktur_penjualan, $rows, $offset) {
        $query = "SELECT a.id_penjualan_obat, id_faktur_penjualan, a.id_obat, b.nama_obat, qty, harga, r_code 
                  FROM rm_penjualan_obat a, rm_obat b 
                  WHERE b.id_obat=a.id_obat AND id_faktur_penjualan='" . $id_faktur_penjualan . "' and a.del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlTarif = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_ret = "select sum(jumlah) as jumlah from rm_retur_penjualan_obat where id_faktur_penjualan='" . $rec['id_faktur_penjualan'] . "' and id_obat='" . $rec['id_obat'] . "' and del_flag<>'1'";
                $r_ret = $this->runQuery($q_ret);
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;
                $arr[] = array(
                    'id_penjualan_obat' => $rec['id_penjualan_obat'],
                    'no_faktur' => $rec['id_faktur_penjualan'],
                    'id_obat' => $rec['id_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'qty' => $rec['qty'],
                    'jml_retur' => @mysql_result($r_ret, 0, 'jumlah'),
                    'harga' => "Rp. " . number_format($rec['harga'], 2, ',', '.'),
                    'total' => "Rp. " . number_format($total, 2, ',', '.'),
                    'rCode' => $rec['r_code']
                );
                $jmlTarif += $total;
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_obat":"Total","total":"Rp. ' . number_format($jmlTarif, 2, ',', '.') . '"}]}';
        } else {
            return '{"total":0, "rows":[],"footer":[]}';
        }
    }

    public function getDataFaktur($nm_obt, $kd_obt, $operator, $rows, $offset) {

        $kondisi = "";

        if (!empty($nm_obt)) {
            $kondisi .= " AND b.nama_obat LIKE '" . $nm_obt . "%'";
        }

        if (!empty($kd_obat))
            $kondisi .= " AND b.kode_obat LIKE '" . $kd_obt . "%'";

        if (!empty($operator))
            $kondisi .= " AND c.`level` = " . $operator . "";

        $query = "SELECT c.nama_pasien, c.id_faktur_penjualan, a.id_obat, b.nama_obat, a.qty, c.`level` FROM rm_faktur_penjualan c LEFT JOIN 
                  rm_penjualan_obat a ON (a.id_faktur_penjualan=c.id_faktur_penjualan) LEFT JOIN rm_obat b ON (b.id_obat=a.id_obat) 
                  WHERE c.id_ruang=" . $_SESSION['level'] . " and c.`status`=0 and c.del_flag<>1 and a.del_flag<>'1'" . $kondisi;
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlTarif = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'no_faktur' => $rec['id_faktur_penjualan'],
                    'nama_obat' => $rec['nama_obat'],
                    'qty' => $rec['qty'],
                    'px' => $rec['nama_pasien'],
                    'operator' => $this->getPegawaiNip($rec['level'])
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[]}';
        }
    }

    public function getListObatBalance($startDate, $endDate, $tipe_balance, $rows, $offset) {
        $kondisi = "";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(tgl_pemakaian) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            $kondisi .= " and date(tgl_pemakaian)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($tipe_balance != "")
            $kondisi .= " and id_keperluan='" . $tipe_balance . "'";

        $query = "SELECT a.id_obat_balance, b.nama_obat, c.stock, a.jumlah, d.keperluan from rm_obat_balance a, rm_obat b, rm_stock_obat_apotik c, rm_tipe_balance d
                  where a.del_flag<>'1' and b.id_obat=a.id_obat and c.id_obat=a.id_obat and c.id_ruang='" . $_SESSION['level'] . "' and d.id_keperluan=a.id_keperluan " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlTarif = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_obat_balance' => $rec['id_obat_balance'],
                    'nama_obat' => $rec['nama_obat'],
                    'jumlah' => $rec['jumlah'],
                    'stock' => $rec['stock'],
                    'keperluan' => $rec['keperluan']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":0, "rows":[],"footer":[]}';
        }
    }

    public function hapusDetailObat($id_penjualan_obat, $id_faktur_penjualan) {
        if ($this->checkBayarObat($id_faktur_penjualan)) {
            $query = "update rm_penjualan_obat set del_flag='1' where id_penjualan_obat='" . $id_penjualan_obat . "'";
            $result = $this->runQuery($query);

            if ($result)
                $return = '1';
            else
                $return = '0';
        } else {
            $return = '2';
        }

        return $return;
    }

    public function hapusFaktur($id_faktur_penjualan) {
        if ($this->checkBayarObat($id_faktur_penjualan)) {
            $query = "update rm_faktur_penjualan set del_flag='1' where id_faktur_penjualan='" . $id_faktur_penjualan . "'";
            $result = $this->runQuery($query);

            if ($result)
                $return = '1';
            else
                $return = '0';
        } else {
            $return = '2';
        }

        return $return;
    }

    public function hapusDetailRacikan($id_detail_racikan, $id_faktur_penjualan) {
        if ($this->checkBayarObat($id_faktur_penjualan)) {
            $query = "update rm_detail_racikan set del_flag='1' where id_detail_racikan='" . $id_detail_racikan . "'";
            $result = $this->runQuery($query);

            if ($result)
                $return = '1';
            else
                $return = '0';
        } else {
            $return = '2';
        }

        return $return;
    }

    public function hapusRacikan($id_racikan, $id_faktur_penjualan) {
        if ($this->checkBayarObat($id_faktur_penjualan)) {
            $query = "update rm_racikan set del_flag='1' where id_racikan='" . $id_racikan . "'";
            $result = $this->runQuery($query);

            if ($result)
                $return = '1';
            else
                $return = '0';
        } else {
            $return = '2';
        }

        return $return;
    }

    public function getDetailReturPenjualanObat($id_faktur_penjualan, $rows, $offset) {
        $query = "SELECT c.id_retur_penjualan_obat, a.id_faktur_penjualan, c.pros_retur, c.id_obat, b.nama_obat, c.jumlah, a.harga, a.r_code 
                  FROM rm_penjualan_obat a, rm_obat b, rm_retur_penjualan_obat c 
                  WHERE a.id_penjualan_obat=c.id_penjualan_obat and b.id_obat=a.id_obat and a.id_faktur_penjualan=c.id_faktur_penjualan AND c.id_faktur_penjualan='" . $id_faktur_penjualan . "' AND id_retur=0 and a.del_flag<>'1' and c.del_flag<>'1' group by c.id_retur_penjualan_obat";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlTarif = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['jumlah'] * ($rec['harga'] * (1 - $rec['pros_retur']));
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;
                $arr[] = array(
                    'id_retur_penjualan_obat' => $rec['id_retur_penjualan_obat'],
                    'no_faktur' => $rec['id_faktur_penjualan'],
                    'id_obat' => $rec['id_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'qty' => $rec['jumlah'],
                    'pros_retur' => ($rec['pros_retur'] * 100) . ' %',
                    'harga' => "Rp. " . number_format(($rec['harga'] * (1 - $rec['pros_retur'])), 2, ',', '.'),
                    'total' => "Rp. " . number_format($total, 2, ',', '.'),
                    'rCode' => $rec['r_code']
                );
                $jmlTarif += $total;
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_obat":"Total","total":"Rp. ' . number_format($jmlTarif, 2, ',', '.') . '"}]}';
        } else {
            return '{"total":0, "rows":[],"footer":[]}';
        }
    }

    public function getDetailObat($id_penjualan_obat) {
        $query = "SELECT a.id_Penjualan_obat, a.id_obat, b.nama_obat, a.qty, a.harga, a.r_code
                  FROM rm_penjualan_obat a, rm_obat b WHERE a.id_penjualan_obat='" . $id_penjualan_obat . "' AND b.id_obat=a.id_obat";
        $result = $this->runQuery($query);

        if ($result) {
            $return = array(
                "id_penjualan_obat" => @mysql_result($result, 0, "id_penjualan_obat"),
                "nama_obat" => @mysql_result($result, 0, "nama_obat"),
                "nama_obatId" => @mysql_result($result, 0, "id_obat"),
                "qty" => @mysql_result($result, 0, "qty"),
                "harga" => @mysql_result($result, 0, "harga"),
                "kode_r" => @mysql_result($result, 0, "r_code"),
            );
            return $this->jEncode($return);
            ;
        }
    }

    public function getRacikan($id_racikan) {
        $query = "SELECT * from rm_racikan where id_racikan='" . $id_racikan . "' AND del_flag<>'1' ";
        $result = $this->runQuery($query);

        if ($result) {
            $return = array(
                "id_racikan" => @mysql_result($result, 0, "id_racikan"),
                "racikan" => @mysql_result($result, 0, "racikan")
            );
            return $this->jEncode($return);
            ;
        }
    }

    public function getFakturPenjualan(
    $no_faktur, $nama_pasien, $startDate, $endDate, $status, $rows, $offset
    ) {
        $kondisi = "";

        if ($no_faktur != "")
            $kondisi .= " and id_faktur_penjualan='" . $no_faktur . "'";
        if ($nama_pasien != "")
            $kondisi .= " and nama_pasien like '%" . $nama_pasien . "%'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(tgl_penjualan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            $kondisi .= " and date(tgl_penjualan)='" . $this->formatDateDb($startDate) . "'";
        }
        if ($status != "")
            $kondisi .= " and status='" . $status . "'";

        $query = "SELECT *, date(tgl_penjualan) as tgl_penjualan FROM rm_faktur_penjualan WHERE del_flag<>'1' and id_ruang='" . $_SESSION['level'] . "' " . $kondisi;

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $sisa = 0;
        $terbayar = 0;
        $total = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_bayar = "select sum(bayar) as bayar from rm_pembayaran_obat where id_faktur_penjualan='" . $rec['id_faktur_penjualan'] . "' AND del_flag<>'1'";
                $r_bayar = $this->runQuery($q_bayar);
                if (@mysql_result($r_bayar, 0, 'bayar') > 0)
                    $bayar = @mysql_result($r_bayar, 0, 'bayar');
                else
                    $bayar = 0;
                $arr[] = array(
                    'id_faktur_penjualan' => $rec['id_faktur_penjualan'],
                    'jns_customer' => $rec['jns_customer'],
                    'tgl_penjualan' => $this->codeDate($rec['tgl_penjualan']),
                    'id_pasien' => $rec['id_pasien'],
                    'nama_pasien' => $rec['nama_pasien'],
                    'status' => $rec['status'],
                    'sisa' => $this->getTotalTagihanObat($rec['id_faktur_penjualan']),
                    'terbayar' => $bayar,
                    'total' => $this->getTotalTagihanObat($rec['id_faktur_penjualan']) + $bayar
                );
                $sisa += $this->getTotalTagihanObat($rec['id_faktur_penjualan']);
                $terbayar += $bayar;
                $total += ( $this->getTotalTagihanObat($rec['id_faktur_penjualan']) + $bayar);
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"jns_customer":"Total",
                    "sisa":' . $sisa . ', "terbayar":' . $terbayar . ', "total":' . $total . '}]}';
        } else {
            return '{"total":0, "rows":[],"footer":[]}';
        }
    }

    public function hapusReturObat($id_retur_penjualan_obat) {
        $query = "update rm_retur_penjualan_obat set del_flag='1' where id_retur_penjualan_obat='" . $id_retur_penjualan_obat . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function cekPembayaran($id_faktur_penjualan) {
        $query = "SELECT id_pembayaran_obat FROM rm_pembayaran_obat WHERE id_faktur_penjualan ='" . $id_faktur_penjualan . "' ";
        $result = $this->runQuery($query);

        if ($result) {
            return 'ADA';
        } else {
            return 'NIHIL';
        }
    }

    public function getTotalTagihanObat($id_faktur_penjualan) {
        $jmlTarif = 0;
        $query = "SELECT qty, harga, r_code FROM rm_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;

                $jmlTarif += $total;
            }
        }

        $query = "SELECT id_racikan FROM rm_racikan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_detail = "select qty, harga, r_code from rm_detail_racikan where id_racikan='" . $rec['id_racikan'] . "' and del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                while ($detail = mysql_fetch_array($r_detail)) {
                    $total = $detail['qty'] * $detail['harga'];
                    if ($detail['r_code'] == 'Ya')
                        $total = $total + 200;

                    $jmlTarif += $total;
                }
                $jmlTarif += 500;
            }
        }

        $q_pembayaran = "select sum(bayar) as pembayaran, asuransi, sum(diskon) as diskon from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
        $r_pembayaran = $this->runQuery($q_pembayaran);

        $jmlTarif = ($jmlTarif - round((@mysql_result($r_pembayaran, 0, 'pembayaran') + @mysql_result($r_pembayaran, 0, 'diskon') + @mysql_result($r_pembayaran, 0, 'asuransi')), 2));

        return $jmlTarif;
    }

    public function getTotalTagihanObatRetur($id_faktur_penjualan) {
        $jmlTarif = 0;
        $query = "SELECT qty, round(harga) as harga, r_code FROM rm_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;

                $jmlTarif += $total;
            }
        }

        $query = "SELECT id_racikan FROM rm_racikan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_detail = "select qty, harga, r_code from rm_detail_racikan where id_racikan='" . $rec['id_racikan'] . "' and del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                while ($detail = mysql_fetch_array($r_detail)) {
                    $total = $detail['qty'] * $detail['harga'];
                    if ($detail['r_code'] == 'Ya')
                        $total = $total + 200;

                    $jmlTarif += $total;
                }
                $jmlTarif += 500;
            }
        }

        $jumlahRetur = $this->getJumlahReturTagihanObat($id_faktur_penjualan);
        $q_pembayaran = "select sum(bayar) as pembayaran, asuransi, sum(diskon) as diskon from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
        $r_pembayaran = $this->runQuery($q_pembayaran);

        $jmlTarif = ($jmlTarif - round((@mysql_result($r_pembayaran, 0, 'pembayaran') + @mysql_result($r_pembayaran, 0, 'diskon') + @mysql_result($r_pembayaran, 0, 'asuransi')), 2)) - $jumlahRetur;

        return $jmlTarif;
    }

    public function getTotalTagihanObatDisc($id_faktur_penjualan) {
        $jmlTarif = 0;
        $query = "SELECT qty, harga, r_code FROM rm_penjualan_obat WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;

                $jmlTarif += $total;
            }
        }

        $query = "SELECT id_racikan FROM rm_racikan WHERE id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_detail = "select qty, harga, r_code from rm_detail_racikan where id_racikan='" . $rec['id_racikan'] . "' and del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                while ($detail = mysql_fetch_array($r_detail)) {
                    $total = $detail['qty'] * $detail['harga'];
                    if ($detail['r_code'] == 'Ya')
                        $total = $total + 200;

                    $jmlTarif += $total;
                }
                $jmlTarif += 500;
            }
        }

        $q_pembayaran = "select sum(bayar) as pembayaran, asuransi, sum(diskon) as diskon from rm_pembayaran_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' AND del_flag<>'1'";
        $r_pembayaran = $this->runQuery($q_pembayaran);

        $jmlTarif = $jmlTarif - round((@mysql_result($r_pembayaran, 0, 'pembayaran') + @mysql_result($r_pembayaran, 0, 'diskon') + @mysql_result($r_pembayaran, 0, 'asuransi')), 2);

        return $jmlTarif . ":" . @mysql_result($r_pembayaran, 0, 'diskon');
    }

    public function getDetailPenjualanRacikan($id_faktur_penjualan, $rows, $offset) {
        $query = "SELECT a.id_racikan, a.id_detail_racikan, c.id_faktur_penjualan, racikan, a.id_obat, b.nama_obat, qty, harga, r_code 
                  FROM rm_detail_racikan a, rm_obat b, rm_racikan c 
                  WHERE b.id_obat=a.id_obat and a.id_racikan=c.id_racikan AND c.id_faktur_penjualan='" . $id_faktur_penjualan . "' and c.del_flag<>'1' and a.del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlTarif = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;
                $arr[] = array(
                    'id_detail_racikan' => $rec['id_detail_racikan'],
                    'id_racikan' => $rec['id_racikan'],
                    'no_faktur' => $rec['id_faktur_penjualan'],
                    'racikan' => $rec['racikan'],
                    'id_obat' => $rec['id_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'qty' => $rec['qty'],
                    'harga' => "Rp. " . number_format($rec['harga'], 2, ',', '.'),
                    'total' => "Rp. " . number_format($total, 2, ',', '.'),
                    'rCode' => $rec['r_code']
                );
                $jmlTarif += $total;
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_obat":"Total","total":"Rp. ' . number_format(($jmlTarif + 500), 2, ',', '.') . '"}]}';
        } else {
            return '{"total":0, "rows":[],"footer":[]}';
        }
    }

    public function getDetailRacikan($id_racikan, $rows, $offset) {
        $query = "SELECT a.id_obat, a.id_detail_racikan, c.id_faktur_penjualan, b.nama_obat, qty, harga, r_code 
                  FROM rm_detail_racikan a, rm_obat b, rm_racikan c
                  WHERE b.id_obat=a.id_obat and a.id_racikan='" . $id_racikan . "' and c.id_racikan=a.id_racikan and a.del_flag<>'1' AND c.del_flag<>'1'";

        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= " limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        $jmlTarif = 0;
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total = $total + 200;
                $arr[] = array(
                    'id_detail_racikan' => $rec['id_detail_racikan'],
                    'no_faktur' => $rec['id_faktur_penjualan'],
                    'id_obat' => $rec['id_obat'],
                    'nama_obat' => $rec['nama_obat'],
                    'qty' => $rec['qty'],
                    'harga' => "Rp. " . number_format($rec['harga'], 2, ',', '.'),
                    'total' => "Rp. " . number_format($total, 2, ',', '.'),
                    'rCode' => $rec['r_code']
                );
                $jmlTarif += $total;
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . ',"footer":[{"nama_obat":"Total","total":"Rp. ' . number_format(($jmlTarif + 500), 2, ',', '.') . '"}]}';
        } else {
            return '{"total":0, "rows":[],"footer":[]}';
        }
    }

    public function cetakStruk($id_faktur_penjualan) {
        if ($id_faktur_penjualan > 0) {
            if ($_SESSION['level'] == 36)
                $ruang = 'Apotek Depan';
            if ($_SESSION['level'] == 46)
                $ruang = 'Apotek Belakang';
            if ($_SESSION['level'] == 47)
                $ruang = 'Apotek VIP';
            if ($_SESSION['level'] == 50)
                $ruang = 'Apotek IBS';
            $query = "select * from rm_faktur_penjualan where id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";
            $result = $this->runQuery($query);

            $set = "UPDATE rm_faktur_penjualan SET struk=1 WHERE id_faktur_penjualan=" . $id_faktur_penjualan . " AND del_flag<>1 AND `status`=0";
            $ok = $this->runQuery($set);

            $jumlah = 0;

            $qJual = "SELECT a.harga, a.qty, b.nama_obat FROM rm_penjualan_obat a, rm_obat b WHERE a.del_flag<>1 AND 
                  a.id_faktur_penjualan=" . $id_faktur_penjualan . " AND a.id_obat = b.id_obat AND b.del_flag<>1";
            $rJual = $this->runQuery($qJual);

            $qJR = "SELECT count(*) as hasil FROM rm_racikan WHERE del_flag<>1 AND id_faktur_penjualan=" . $id_faktur_penjualan . "";
            $rJR = $this->runQuery($qJR);
            $jumlah += ( @mysql_result($rJR, 0, 'hasil') * 500);

            if (@mysql_result($rJR, 0, 'hasil') > 0) {
                $qRacik = "SELECT a.harga, a.qty, c.nama_obat FROM rm_detail_racikan a JOIN rm_racikan b ON (a.id_racikan=b.id_racikan), rm_obat c WHERE a.del_flag<>1 AND 
                       b.del_flag<>1 AND b.id_faktur_penjualan=" . $id_faktur_penjualan . " AND c.id_obat = a.id_obat AND c.del_flag<>1";
                $rRacik = $this->runQuery($qRacik);
            }

            if (mysql_num_rows($result) > 0) {
                $date = explode(' ', @mysql_result($result, 0, 'tgl_penjualan'));
                if (@mysql_result($result, 0, 'id_ruang_px') != 0)
                    $tr = $this->getTipeRuangId(@mysql_result($result, 0, 'id_ruang_px'));
                else
                    $tr = 'UMUM';
                if ($tr == 8)
                    $jenis = 'Rawat Inap';
                else if ($tr == 'UMUM')
                    $jenis = 'UMUM';
                else
                    $jenis = 'Rawat Jalan';
                $tanggal = $date[0];
                $jam = $date[1];
                $nama = @mysql_result($result, 0, 'nama_pasien');
                $operator = $this->getPegawaiUser(@mysql_result($result, 0, 'level'));
                if (@mysql_result($result, 0, 'id_pasien') != 0) {
                    $dokter = $this->getDokter(@mysql_result($result, 0, 'id_dokter'));
                } else {
                    $dokter = "";
                }
                $html = "";
                $html .= "<style>
                            .data{
                                font-family: verdana;
                                font-size: 9pt;
                            }
                            .printArea{
                                font-family: verdana;
                                font-size: 9pt;
                            }
                            .headerTagihan{
                                font-weight: bold;
                                border-bottom: 1px solid #000000;
                                border-top: 1px solid #000000;
                            }
                            .footerB{
                                border-bottom: 1px solid #000000;
                            }
                            .footerA{
                                border-top: 1px solid #000000;
                            }
                            .total{
                                font-weight: bold;
                                border-top: 1px solid #000000;
                            }
                       </style>";
                $html .= "<table class='data' style=' font-family: serif; font-size: 13px; width: 18.96850412672em' cellpadding='0' cellspacing='1'>";
                $html .= "<tr height='18px'><td colspan=2 align='center'><b>STRUK PEMBAYARAN<br><u>RSUD Dr. SOEGIRI LAMONGAN</b></u><br><br></td></tr>";
                $html .= "<tr height='18px'><td width='35%' colspan='2' style='outline: solid; outline-width: 1px; outline-color: #000000;' align='center'><b>" . $id_faktur_penjualan . "</b></td></tr>";
                $html .= "<tr height='18px'><td width='85%' class='headerTagihan'><b>Item</b></td><td class='headerTagihan'><b>Jml</b></td></tr>";
                while ($jual = @mysql_fetch_array($rJual)) {
                    $jumlah += $jual['harga'] * $jual['qty'];
                    $html .= "<tr height='18px'><td width='85%'>" . $jual['nama_obat'] . "</td><td align='right'>" . $jual['qty'] . "</td></tr>";
                }
                if (@mysql_result($rJR, 0, 'hasil') > 0) {
                    while ($racik = @mysql_fetch_array($rRacik)) {
                        $jumlah += $racik['harga'] * $racik['qty'];
                        $html .= "<tr height='18px'><td width='85%'>" . $racik['nama_obat'] . "</td><td align='right'>" . $racik['qty'] . "</td></tr>";
                    }
                }

                $identitasPasien = $this->getNoIdentitasPasien(@mysql_result($result, 0, 'id_pasien'));
                if ($this->checkIdentitasPasienFarmasi($identitasPasien)) {
                    $diskon = $jumlah * 0.1;
                } else {
                    //$diskon = 0;
                    $qDisc = "SELECT jumlah FROM rm_diskon_temp WHERE id_faktur_penjualan=" . $id_faktur_penjualan . " AND del_flag<>1";
                    $rDisc = $this->runQuery($qDisc);
                    if ($rDisc)
                        $diskon = @mysql_result($rDisc, 0, 'jumlah');
                    else
                        $diskon = 0;
                }

                $html .= "<table style=' font-family: serif; font-size: 13px; width: 18.96850412672em' cellpadding='0' cellspacing='1'>";
                $html .= "<tr>
                            <td width='40%' class='footerA'>Total</td>
                            <td width='10%' class='footerA' align='right'>Rp. </td>
                            <td width='50%' class='footerA' align='right'>" . number_format($jumlah, 0) . "</td>
                          </tr>";
                $html .= "<tr height='18px'>
                            <td width='40%'>Diskon</td>
                            <td width='10%' align='right'>Rp. </td>
                            <td width='50%' align='right'>" . number_format($diskon, 0) . "</td>
                          </tr>";
                $html .= "<tr height='18px'>
                            <td width='40%' class='footerB'>Netto</td>
                            <td width='10%' class='footerB' align='right'>Rp. </td>
                            <td width='50%' class='footerB' align='right'>" . number_format(($jumlah - $diskon), 0) . "</td>
                          </tr>";
                $html .= "<tr height='18px'>
                            <td colspan='2'>" . $this->formatDateDb($tanggal) . " " . $jam . "</td>
                            <td width='60%'>" . $jenis . "</td>
                          </tr>";
                $html .= "<tr height='18px'>
                            <td colspan='2'>" . @mysql_result($result, 0, 'id_pasien') . "</td>
                            <td width='60%'>" . $nama . "</td>
                          </tr>";
                $html .= "<tr height='18px'>
                            <td colspan='2'>" . $ruang . "</td>
                            <td width='50%' align='center'><b>" . $operator . " </b></td>
                          </tr>";
                $html .= "<tr height='18px'>
                            <td colspan='3' width='50%' align='center'><b> TERIMA KASIH </b></td>
                          </tr>";
                $html .= "</table>";
                $html .= "</table>";
                $double = $html . "<br>" . $html;
                $arr[] = array('display' => $double);
                if ($arr) {
                    return $this->jEncode($arr);
                }
            } else {
                return '0';
            }
        } else {
            return '0';
        }
    }

    public function cetakBayarObat($id_pembayaran_obat) {
//        if ($_SESSION['level'] == 36)
//            $ruang = 'Apotek Depan';
//        if ($_SESSION['level'] == 46)
//            $ruang = 'Apotek Belakang';
//        if ($_SESSION['level'] == 47)
//            $ruang = 'Apotek VIP';
//        if ($_SESSION['level'] == 50)
//            $ruang = 'Apotek IBS';

        $query = "select * from rm_pembayaran_obat where id_pembayaran_obat='" . $id_pembayaran_obat . "' AND del_flag<>'1'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $q_faktur = "select * from rm_faktur_penjualan where id_faktur_penjualan='" . @mysql_result($result, 0, 'id_faktur_penjualan') . "'";
            $r_faktur = $this->runQuery($q_faktur);

            if (@mysql_result($r_faktur, 0, 'id_ruang') == 36)
                $ruang = 'Apotek Depan';
            if (@mysql_result($r_faktur, 0, 'id_ruang') == 46)
                $ruang = 'Apotek Belakang';
            if (@mysql_result($r_faktur, 0, 'id_ruang') == 47)
                $ruang = 'Apotek VIP';
            if (@mysql_result($r_faktur, 0, 'id_ruang') == 50)
                $ruang = 'Apotek IBS';

            $fakture = @mysql_result($r_faktur, 0, 'id_faktur_penjualan');

            $waktu = explode(" ", @mysql_result($result, 0, 'tgl_pembayaran'));
            $tanggal = $this->codeDate($waktu[0]);
            $jam = $waktu[1];

            $file = fopen("../report/bayarObat.html", 'w');
            fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../printstyle.css'/>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
						   <style>
							.listObat td,.listObat th{
								border: 1.8px solid #000000;
							}
						   </style>
                           </head>");
            fwrite($file, "<body>");
            fwrite($file, "<center><div class='printArea' style='width: 21.6cm; height: 14cm;'>");
            fwrite($file, "<p align='center' style=' font-family: verdana; font-size: 11px;' ><strong>KWITANSI PEMBAYARAN OBAT<br><u>RSUD Dr. SOEGIRI LAMONGAN</u></strong></p><hr>
                            <!--- <span style=' font-family: verdana; font-size: 11px;' >Pembayaran ke - " . @mysql_result($result, 0, 'pembayaran_ke') . "</span>--->
                            <b><p style=' font-family: verdana; font-size: 10px;text-align: left;' >" . $ruang . "</p></b>
                            <table style=' font-family: verdana; font-size: 10px;' width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr >
                                    <td width='19%'>No Faktur</td>
                                    <td width='30%'>: <b>" . $fakture . "</b></td>
                                    <td width='2%'>&nbsp;</td>
                                    <td width='19%'>No RM</td>
                                    <td width='30%'>: <b>" . @mysql_result($r_faktur, 0, 'id_pasien') . "</b></td>
                                </tr>
                                <tr >
                                    <td width='19%'>Tanggal</td>
                                    <td width='30%'>: <b>" . $tanggal . "</b></td>
                                    <td width='2%'>&nbsp;</td>
                                    <td width='19%'>Nama Pasien</td>
                                    <td width='30%'>: <b>" . @mysql_result($r_faktur, 0, 'nama_pasien') . "</b></td>
                                </tr>
                                <tr >
                                    <td width='19%'>Jam</td>
                                    <td width='30%'>: <b>" . $jam . "</b></td>
                                    <td width='2%'>&nbsp;</td>
                                    <td width='19%'>Ruang</td>
                                    <td width='30%'>: <b>" . @mysql_result($r_faktur, 0, 'ruang') . "</b></td>
                                </tr>
                            </table>");
            $q_obat = "SELECT id_faktur_penjualan, a.id_obat, b.nama_obat, qty, harga, r_code 
                      FROM rm_penjualan_obat a, rm_obat b 
                      WHERE b.id_obat=a.id_obat AND id_faktur_penjualan='" . $fakture . "' AND a.del_flag<>'1'";

            $r_obat = $this->runQuery($q_obat);
            $html = "<p style='font-family: verdana; font-size: 10px; text-align: left;'><b>Detail Obat</b></p><table class='listObat' style='border-collapse: collapse; font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#000000'>
                  <tr>
                        <th width='5%' align='center' bgcolor='#ffffff'>No</th>
                        <th width='45%' align='center' bgcolor='#ffffff'>Nama Obat </th>
                        <th width='10%' align='center' bgcolor='#ffffff'>Qty</th>
                        <th width='10%' align='center' bgcolor='#ffffff'>Kode R </th>
                        <th width='10%' align='center' bgcolor='#ffffff'>Harga</th>
                        <th width='20%' align='center' bgcolor='#ffffff'>Total</th>
                  </tr>";
            $i = 1;
            $totalAll = 0;
            $totalObat = 0;
            while ($rec = mysql_fetch_array($r_obat)) {
                $total = $rec['qty'] * $rec['harga'];
                if ($rec['r_code'] == 'Ya')
                    $total += 200;
                $html .= "<tr>
                        <td width='5%' bgcolor='#FFFFFF' align='center'>" . $i . "</td>
                        <td width='45%' bgcolor='#FFFFFF'>" . $rec['nama_obat'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . $rec['qty'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='center'>" . $rec['r_code'] . "</td>
                        <td width='10%' bgcolor='#FFFFFF' align='right'>" . number_format($rec['harga'], 2, ',', '.') . "</td>
                        <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format($total, 2, ',', '.') . "</td>
                  </tr>";
                $totalObat += $total;
                $totalAll += $total;
                $i++;
            }
            $html .= "<tr>
                <td width='5%' bgcolor='#FFFFFF' align='right' colspan='5'><b>Sub Total</b></td>
                <td width='20%' bgcolor='#FFFFFF' align='right'><b>" . number_format($totalObat, 2, ',', '.') . "</b></td>
          </tr>";
            $html .= "</table>";

            $j = 1;
            $q_racikan = "select * from rm_racikan where id_faktur_penjualan='" . $fakture . "' AND del_flag<>'1'";
            $r_racikan = $this->runQuery($q_racikan);
            if (@mysql_num_rows($r_racikan) > 0)
                $html .= "<span style=' font-family: verdana; font-size: 10px;'><b>Obat Racikan</b></span><table style=' font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='1' cellspacing='1' bgcolor='#000000'>";
            while ($racikan = mysql_fetch_array($r_racikan)) {
                $q_obat = "SELECT a.id_obat, b.nama_obat, qty, harga, r_code 
                              FROM rm_detail_racikan a, rm_obat b
                              WHERE b.id_obat=a.id_obat and a.id_racikan='" . $racikan['id_racikan'] . "'";

                $r_obat = $this->runQuery($q_obat);
                $html .= "<table class='listObat' style='border-collapse: collapse; font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#000000'>
                          <tr>
                                <th width='5%' align='center' bgcolor='#ffffff'>No</th>
                                <th width='45%' align='center' bgcolor='#ffffff'>Nama Obat </th>
                                <th width='10%' align='center' bgcolor='#ffffff'>Qty</th>
                                <th width='10%' align='center' bgcolor='#ffffff'>Kode R </th>
                                <th width='10%' align='center' bgcolor='#ffffff'>Harga</th>
                                <th width='20%' align='center' bgcolor='#ffffff'>Total</th>
                          </tr>";
                $k = 1;
                $totalObat = 0;
                while ($rec = mysql_fetch_array($r_obat)) {
                    $total = $rec['qty'] * $rec['harga'];
                    if ($rec['r_code'] == 'Ya')
                        $total += 200;
                    $html .= "<tr>
                                <td width='5%' bgcolor='#FFFFFF' align='center'>" . $k . "</td>
                                <td width='45%' bgcolor='#FFFFFF'>" . $rec['nama_obat'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='right'>" . $rec['qty'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='center'>" . $rec['r_code'] . "</td>
                                <td width='10%' bgcolor='#FFFFFF' align='right'>" . number_format($rec['harga'], 2, ',', '.') . "</td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format($total, 2, ',', '.') . "</td>
                          </tr>";
                    $totalObat += $total;
                    $totalAll += $total;
                    $k++;
                }
                $html .= "</td></tr><tr>
                                <td width='3%' bgcolor='#FFFFFF' align='right' colspan='5'>Biaya Racikan</td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'>" . number_format(500, 2, ',', '.') . "</td>
                          </tr>";
                $html .= "<tr>
                                <td width='3%' bgcolor='#FFFFFF' align='right' colspan='5'><b>Sub Total</b></td>
                                <td width='20%' bgcolor='#FFFFFF' align='right'><b>" . number_format(($totalObat + 500), 2, ',', '.') . "</b></td>
                          </tr>";
                $totalAll += 500;
                $html .= "</table>";
                $j++;
            }
            $html .= "</table>";
            $kembali = @mysql_result($result, 0, 'kembali');
            $q_pembayaran = "select (sum(bayar)+sum(kembali)) as pembayaran, sum(diskon) as diskon from rm_pembayaran_obat where id_faktur_penjualan='" . $fakture . "' and id_pembayaran_obat!='" . $id_pembayaran_obat . "' AND del_flag<>'1'";
            $r_pembayaran = $this->runQuery($q_pembayaran);

            $q_diskon = "select diskon from rm_diskon_obat where del_flag<>1 AND id_faktur='" . $fakture . "' and del_flag<>1";
            $r_diskon = $this->runQuery($q_diskon);

            $terbayar = @mysql_result($r_pembayaran, 0, 'pembayaran');
            $sisa = @mysql_result($result, 0, 'sisa');
            if ($sisa < 0)
                $sisa = 0;

            if ($sisa == 0)
                $status = 'LUNAS';
            else
                $status = 'KREDIT';

            $html .= "<table style=' font-family: verdana; font-size: 10px;' width='100%' border='0' cellpadding='0' cellspacing='0'>
                  <tr>
                        <td rowspan='6' width='60%' align='Left'><b>SEMOGA LEKAS SEMBUH</b></td>
                        <td width='20%' align='right'><b>Grand Total</b></td>
                        <td width='20%' align='right'><b>" . number_format($totalAll, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Admin Bank</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($result, 0, 'administrasi'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Diskon</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($r_diskon, 0, 'diskon'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Asuransi</b></td>
                        <td width='20%' align='right'><b>" . number_format(@mysql_result($result, 0, 'asuransi'), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Terbayar</b></td>
                        <td width='20%' align='right'><b>" . number_format($terbayar, 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td width='20%' align='right'><b>Bayar</b></td>
                        <td width='20%' align='right'><b>" . number_format((@mysql_result($result, 0, 'bayar') + @mysql_result($result, 0, 'kembali')), 2, ',', '.') . "</b></td>
                  </tr>
                  <tr>
                        <td rowspan='6' width='60%' align='center'><b>" . $status . "</b></td>
                        <td width='20%' align='right'><b>Kurang</b></td>
                        <td width='20%' align='right'><b>" . number_format($sisa, 2, ',', '.') . "</b></td>
                  </tr>
                  </table>";

            fwrite($file, $html);
            fwrite($file, "</div></center></body></html>");
            //fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");
            fclose($file);

            return '1';
        } else {
            return '0';
        }
    }

    public function cetakRekapResep(
    $jenis_perawatan, $tipe_pasien, $startDate, $endDate
    ) {

        $file = fopen("../report/rekapResep.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                       'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                       <html xmlns='http://www.w3.org/1999/xhtml'>
                       <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                       <title></title>
                       <script src='../js/jquery-1.4.4.min.js'></script>
                       <script src='../js/jquery.print.js'></script>
                       <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                       <style>
                            .data{
                                font-family: verdana;
                                font-size: 9pt;
                            }
                            .printArea{
                                font-family: verdana;
                                font-size: 9pt;
                            }
                            .headerTagihan{
                                font-weight: bold;
                                border-bottom: 1px solid #000000;
                                border-top: 1px solid #000000;
                            }
                            .total{
                                font-weight: bold;
                                border-top: 1px solid #000000;
                            }
                       </style>
                       <script>
                       $(function() {
                        $( '.printArea' ).print();
                       });
                       </script>
                       </head>");
        fwrite($file, "<body class='data' style='font-family:verdana; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Rekap Jumlah Lembar Resep</b></u><br><br></td>
                        </tr>";
        $html .="</table>";
        $html .="Tanggal : " . $this->codeDate($this->formatDateDb($startDate)) . " s/d " . $this->codeDate($this->formatDateDb($endDate));
        $kondisi = "";

        if ($jenis_perawatan == "rawatJalan") {
            $kondisi .= " AND ruang IN (
                  SELECT ruang FROM rm_ruang WHERE id_tipe_ruang IN ('2', '3', '4', '9')
                  )";
            $rawat = "Rawat Jalan";
        } else if ($jenis_perawatan == "rawatInap") {
            $kondisi .= " AND ruang IN (
                  SELECT ruang FROM rm_ruang WHERE id_tipe_ruang='8'
                  )";
            $rawat = "Rawat Inap";
        }
        if ($tipe_pasien != "")
            $kondisi .= " and b.id_tipe_pasien='" . $tipe_pasien . "'";
        if ($startDate != "") {
            if ($endDate != "")
                $kondisi .= " and date(tgl_penjualan) between '" . $this->formatDateDb($startDate) . "' and '" . $this->formatDateDb($endDate) . "'";
            else
                $kondisi .= " and date(tgl_penjualan)='" . $this->formatDateDb($startDate) . "'";
        }

        $query = "SELECT ruang FROM rm_faktur_penjualan a, rm_pasien b, rm_tipe_pasien c
                  WHERE a.del_flag<>'1' AND b.id_pasien=a.id_pasien AND c.id_tipe_pasien=b.id_tipe_pasien " . $kondisi . " 
                   and a.id_ruang='" . $_SESSION['level'] . "' GROUP BY ruang order by ruang";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            $html .= "<table class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='10%' class='headerTagihan'>Rawat</td>";
            $html .= "<td width='30%' class='headerTagihan'>Ruang Asal</td>";
            $html .= "<td width='30%' class='headerTagihan'>Customer</td>";
            $html .= "<td width='30%' class='headerTagihan'>Jumlah Resep</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $jmlTotal = 0;
            while ($data = @mysql_fetch_array($result)) {
                $q_resep = "SELECT COUNT(*) AS jmlResep, ruang, c.tipe_pasien FROM rm_faktur_penjualan a, rm_pasien b, rm_tipe_pasien c
                                WHERE a.del_flag<>'1' AND b.id_pasien=a.id_pasien AND c.id_tipe_pasien=b.id_tipe_pasien " . $kondisi . " 
                                and a.id_ruang='" . $_SESSION['level'] . "' and ruang='" . $data['ruang'] . "' GROUP BY ruang, b.id_tipe_pasien order by ruang, b.id_tipe_pasien";
                $r_resep = $this->runQuery($q_resep);
                if (@mysql_num_rows($r_resep) > 0) {
                    $html .= "<tr>";
                    $html .= "<td width='10%'>" . $rawat . "</td>";
                    $html .= "<td width='30%' colspan='3'>" . $data['ruang'] . "</td>";
                    $html .= "</tr>";
                    $subTotal = 0;
                    while ($rec = @mysql_fetch_array($r_resep)) {
                        $html .= "<tr>";
                        $html .= "<td width='10%'>&nbsp;</td>";
                        $html .= "<td width='30%'>&nbsp;</td>";
                        $html .= "<td width='30%'>" . $rec['tipe_pasien'] . "</td>";
                        $html .= "<td width='30%' align='right'>" . $rec['jmlResep'] . "</td>";
                        $html .= "</tr>";
                        $subTotal += $rec['jmlResep'];
                    }
                    $html .= "<tr>";
                    $html .= "<td width='10%' class='total' align='right' colspan='3'>Sub Total / " . $data['ruang'] . "</td>";
                    $html .= "<td width='30%' class='total' align='right'>" . $subTotal . "</td>";
                    $html .= "</tr>";
                    $jmlTotal += $subTotal;
                }
            }
            $html .= "<tr>";
            $html .= "<td width='10%' class='total' align='right' colspan='3'>Grand Total</td>";
            $html .= "<td width='30%' class='total' align='right'>" . $jmlTotal . "</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</table>";
        } else {
            $html .= "Data tidak ditemukan.";
        }
        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fclose($file);

        return '1';
    }

    public function cetakLaporanObatPasien($id_pasien) {

        $kondisi = "";
        $file = fopen("../report/cetakLaporanObatPasien.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                           <style>
                                .data{
                                    font-family: verdana;
                                    font-size: 11pt;
                                }
                                .printArea{
                                    font-family: verdana;
                                    font-size: 11pt;
                                }
                                .headerTagihan{
                                    font-weight: bold;
                                    border-bottom: 1px solid #000000;
                                    border-top: 1px solid #000000;
                                }
                                .total{
                                    font-weight: bold;
                                    border-top: 1px solid #000000;
                                }
                           </style>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
        fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                            <tr height='21'>
                                <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            </tr>
                            <tr height='21'>
                                <td height='21'><u><b>Laporan Penjualan Obat</b></u><br><br></td>
                            </tr>";
        $html .="</table>";
        $query = "SELECT id_faktur_penjualan, date(tgl_penjualan) as tgl_penjualan FROM rm_faktur_penjualan WHERE id_pasien='" . $id_pasien . "' AND jns_customer='Pasien' AND STATUS!='2' AND del_flag<>'1'";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            $html .= "<table class='data' width='100%'>
                    <tr height='25'>
                        <td width='19%'>Nomor RM</td>
                        <td width='30%'>: <b>" . $id_pasien . "</b></td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>&nbsp;</td>
                        <td width='30%'>&nbsp;</td>
                    </tr>
                    <tr height='25'>
                        <td width='19%'>Nama Pasien</td>
                        <td width='30%'>: <b>" . $this->getPasienNama($id_pasien) . "</b></td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Tgl. Lahir</td>
                        <td width='30%'>: <b>" . $this->codeDate($this->getPasienLahir($id_pasien)) . "</b></td>
                    </tr>
                    <tr height='25'>
                        <td width='19%'>Alamat</td>
                        <td width='30%'>: <b>" . $this->getAlamatPasien($id_pasien) . "</b></td>
                        <td width='2%'>&nbsp;</td>
                        <td width='19%'>Kelurahan</td>
                        <td width='30%'>: <b>" . $this->getTipePasien($this->getTipePasienId($id_pasien)) . "</b></td>
                    </tr>
                </table>";

            $html .= "<table style='font-family: verdana;font-size: 10pt;' class='data' width='100%'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td width='10%' class='headerTagihan'>Ruang</td>";
            $html .= "<td width='10%' class='headerTagihan'>Tanggal</td>";
            $html .= "<td width='5%' class='headerTagihan'>Kode Obat</td>";
            $html .= "<td width='20%' class='headerTagihan'>Nama Obat</td>";
            $html .= "<td width='5%' class='headerTagihan'>Qty</td>";
            //$html .= "<td width='10%' class='headerTagihan'>Diskon</td>";
            $html .= "<td width='10%' class='headerTagihan'>Harga</td>";
            $html .= "<td width='10%' class='headerTagihan'>Jumlah</td>";
            $html .= "<td width='5%' class='headerTagihan'>No Faktur</td>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $i = 1;
            $total = 0;
            while ($data = @mysql_fetch_array($result)) {
                $html .= "<tr>";
                $html .= "<td>" . $this->getNamaRuang($_SESSION['level']) . "</td>";
                $html .= "<td colspan='7'>" . $this->codeDate($data['tgl_penjualan']) . "</td>";
                $html .= "</tr>";
                $q_detail = "SELECT a.id_penjualan_obat, id_faktur_penjualan, b.kode_obat, a.id_obat, b.nama_obat, qty, harga, r_code 
                                     FROM rm_penjualan_obat a, rm_obat b 
                                     WHERE b.id_obat=a.id_obat AND id_faktur_penjualan='" . $data['id_faktur_penjualan'] . "' and a.del_flag<>'1'";
                $r_detail = $this->runQuery($q_detail);
                $jmlTotal = 0;
                if (@mysql_num_rows($r_detail) > 0) {
                    while ($rec = @mysql_fetch_array($r_detail)) {
                        $html .= "<tr>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td>" . $rec['kode_obat'] . "</td>";
                        $html .= "<td>" . $rec['nama_obat'] . "</td>";
                        $html .= "<td>" . $rec['qty'] . "</td>";
                        //$html .= "<td></td>";
                        $html .= "<td align='right'>Rp. " . number_format($rec['harga'], 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>Rp. " . number_format(($rec['harga'] * $rec['qty']), 2, ',', '.') . "</td>";
                        $html .= "<td align='right'>" . $data['id_faktur_penjualan'] . "</td>";
                        $html .= "</tr>";
                        $jmlTotal += ( $rec['harga'] * $rec['qty']);
                    }
                    $html .= "<tr>";
                    $html .= "<td colspan='6' class='total'>Sub Total</td>";
                    $html .= "<td align='right' class='total'>Rp. " . number_format($jmlTotal, 2, ',', '.') . "</td>";
                    $html .= "<td colspan='2' align='right' class='total'>&nbsp;</td>";
                    $html .= "</tr>";
                }
                $total += $jmlTotal;
            }
            $html .= "<tr>";
            $html .= "<td colspan='6' class='total'>Grand Total</td>";
            $html .= "<td align='right' class='total'>Rp. " . number_format($total, 2, ',', '.') . "</td>";
            $html .= "<td colspan='2' align='right' class='total'>&nbsp;</td>";
            $html .= "</tr>";
            $html .= "</tbody>";
            $html .= "</html>";
        } else {
            $html = "Data Tidak ditemukan.";
        }

        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fclose($file);

        return '1';
    }

    public function kreditkan() {

        $query = "SELECT id_faktur_penjualan as idf FROM rm_faktur_penjualan WHERE id_ruang='" . $_SESSION['level'] . "' AND id_faktur_penjualan NOT IN (SELECT id_faktur_penjualan FROM rm_pembayaran_obat WHERE del_flag<>'1') AND del_flag<>'1'";
        $result = $this->runQuery($query);

        if (@mysql_num_rows($result) > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $q_tag = "SELECT id_faktur_penjualan, sum(qty * harga) as total from rm_penjualan_obat where del_flag<>1 AND id_faktur_penjualan='" . $rec['idf'] . "' GROUP BY id_faktur_penjualan";
                $r_tag = $this->runQuery($q_tag);
                if (@mysql_num_rows($r_tag) > 0) {
                    while ($tag = mysql_fetch_array($r_tag)) {
                        $q_insert = "INSERT INTO rm_pembayaran_obat (id_faktur_penjualan,pembayaran_ke,tipe_pembayaran,total,sisa,level,id_ruang) 
                                    VALUES ('" . $tag['id_faktur_penjualan'] . "','1','Kredit','" . $tag['total'] . "','" . $tag['total'] . "','" . $_SESSION['nip'] . "','" . $_SESSION['level'] . "')";
                        $r_insert = $this->runQuery($q_insert);

                        if ($r_insert) {
                            $q_fak = "UPDATE rm_faktur_penjualan set status='1' where id_faktur_penjualan='" . $tag['id_faktur_penjualan'] . "' and id_ruang=" . $_SESSION['level'] . " ";
                            $r_fak = $this->runQuery($q_fak);
                        } else {
                            $return = "GAGAL UPDATE";
                        }
                    }
                    $return = "GAGAL INSERT";
                }
                $return = "DATA HARGA KOSONG";
            }
        } else {
            $return = "SUDAH SEMUA";
        }
        $return = "HOREEEE";
        return $return;
    }

    public function cetakStsk() {

        $file = fopen("../report/laporanPenjualanObat.html", 'w');
        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                       'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                       <html xmlns='http://www.w3.org/1999/xhtml'>
                       <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                       <title></title>
                       <script src='../js/jquery-1.4.4.min.js'></script>
                       <script src='../js/jquery.print.js'></script>
                       <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                       <script>
                       $(function() {
                        $( '.printArea' ).print();
                       });
                       </script>
                       </head>");
        fwrite($file, "<body style='font-family:verdana; font-size:9px;'>");
        fwrite($file, "<div class='printArea'>");
        $html = "<table class='data' cellspacing='0' cellpadding='0'>
                        <tr height='21'>
                            <td height='21'><b>RSUD Dr. SOEGIRI</b></td>
                        </tr>
                        <tr height='21'>
                            <td height='21'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                        </tr>";
        $html .="</table>";
        $query = "select sum(bayar) as bayar from rm_pembayaran_tagihan where level='" . $_SESSION['nip'] . "' and status='0' and del_flag<>1";
        $result = $this->runQuery($query);
        $totalTagihan = @mysql_result($result, 0, 'bayar');

        $query = "select sum(bayar) as bayar from rm_pembayaran_obat where level='" . $_SESSION['nip'] . "' and status='0' AND del_flag<>'1'";
        $result = $this->runQuery($query);
        $totalObat = @mysql_result($result, 0, 'bayar');

        $q_no_stsk = "select max(id_setoran) as id_setoran from rm_setoran_kasir";
        $r_no_stsk = $this->runQuery($q_no_stsk);
        $id_setoran = @mysql_result($r_no_stsk, 0, 'id_setoran') + 1;

        $html = '<table style="font-family: verdana;font-size: 10pt;" class="data" width="100%" bgcolor="#000000" cellspacing="1" cellpadding="2">
              <tr>
                <td width="30%" align="center" bgcolor="#FFFFFF"><b>STSK</b></td>
                <td align="center" bgcolor="#FFFFFF"><b>Surat Tanda Setoran Kasir</b></td>
              </tr>
              <tr>
                <td width="30%" valign="top" bgcolor="#FFFFFF">
                            No. : <b>' . $id_setoran . '</b><br />
                            Tgl. : <b>' . $this->codeDate(date('Y-m-d')) . '</b><br />
                            <br />
                            Diterima Dari Kasir :<b>' . $_SESSION['nama_pegawai'] . '</b><br />
                            <br />
                            Sejumlah :<br />
                            <b>Rp. ' . number_format(($totalObat + $totalTagihan), 2, ',', '.') . '</b><br />
                            <br />
                            Penyetor,
                            <br /><br /><br /><br />
                            <b><u>' . $_SESSION['nama_pegawai'] . '</u></b> <br />
                            Kasir
                    </td>
                <td valign="top" bgcolor="#FFFFFF"><table width="100%" border=0 cellspacing="1" cellpadding="3">
                  <tr>
                    <td width="20%">Nomor</td>
                    <td width="1%">:</td>
                    <td><b>' . $id_setoran . '</b></td>
                  </tr>
                  <tr>
                    <td width="20%">Tanggal</td>
                    <td width="1%">:</td>
                    <td><b>' . $this->codeDate(date('Y-m-d')) . '</b></td>
                  </tr>
                  <tr>
                    <td width="20%">Diterima dari Kasir </td>
                    <td width="1%">:</td>
                    <td><b>' . $_SESSION['nama_pegawai'] . '</b></td>
                  </tr>
                  <tr valign="top">
                    <td width="20%">Sejumlah</td>
                    <td width="1%">:</td>
                    <td><b>' . $this->pembilang(round(($totalObat + $totalTagihan))) . ' Rupiah</b></td>
                  </tr>
                  <tr>
                    <td width="20%">&nbsp;</td>
                    <td width="1%">&nbsp;</td>
                    <td><b>Rp. ' . number_format(($totalObat + $totalTagihan), 2, ',', '.') . '</b></td>
                  </tr>
                  <tr>
                    <td width="20%">
                            Penyetor
                            <br /><br /><br /><br />
                            <b><u>' . $_SESSION['nama_pegawai'] . '</u></b><br>Kasir
                            </td>
                    <td width="1%">&nbsp;</td>
                    <td>
                            Penerima
                            <br />
                            <br /><br /><br />
                            ___________________________<br>
                            Bendahara
                            </td>
                  </tr>
                </table></td>
              </tr>
            </table>';

        fwrite($file, $html);
        fwrite($file, "</div></body></html>");
        fclose($file);

        $q_stsk = "insert into rm_setoran_kasir(
                    jumlah,
                    level
                  ) values (
                    '" . ($totalObat + $totalTagihan) . "',
                    '" . $_SESSION['nip'] . "'
                  )";
        $r_stsk = $this->runQuery($q_stsk);

        return '1';
    }

    public function cetakKwitansiRetur($id_faktur_penjualan) {
        $q_insert = "INSERT INTO rm_retur (id_faktur_penjualan,ip) VALUES ('" . $id_faktur_penjualan . "','" . $_SERVER['REMOTE_ADDR'] . "')";
        $r_insert = $this->runQuery($q_insert);

        if ($r_insert) {
            $q_lastID = "SELECT MAX(id_retur) AS id_retur FROM rm_retur WHERE id_faktur_penjualan = '" . $id_faktur_penjualan . "'";
            $r_lastID = $this->runQuery($q_lastID);
            if ($r_lastID) {
                $id_retur = @mysql_result($r_lastID, 0, id_retur);
                $q_update = "UPDATE rm_retur_penjualan_obat SET id_retur = '" . $id_retur . "',ip='" . $_SERVER['REMOTE_ADDR'] . "' WHERE id_retur=0 AND 
                             id_faktur_penjualan='" . $id_faktur_penjualan . "'";
                $r_update = $this->runQuery($q_update);
                if ($r_update) {
                    $query = "select * from rm_retur_penjualan_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' and id_retur='" . $id_retur . "' and del_flag<>'1'";
                    $result = $this->runQuery($query);
                    if (@mysql_num_rows($result) > 0) {
                        $tgl = explode(" ", @mysql_result($result, 0, 'tgl_retur'));
                        $tanggal = $this->codeDate($tgl[0]);
                        $q_faktur = "select * from rm_faktur_penjualan where id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";
                        $r_faktur = $this->runQuery($q_faktur);
                        $nama = $this->getPasienNama(@mysql_result($r_faktur, 0, 'id_pasien'));
                        $file = fopen("../report/cetakKwitansiRetur.html", 'w');
                        fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                            <style>
                                .data{
                                    font-family: verdana;
                                    font-size: 9pt;
                                }
                                .printArea{
                                    font-family: verdana;
                                    font-size: 9pt;
                                }
                                .headerTagihan{
                                    font-weight: bold;
                                    border-bottom: 1px solid #000000;
                                    border-top: 1px solid #000000;
                                }
                                .total{
                                    font-weight: bold;
                                    border-top: 1px solid #000000;
                                }
                           </style>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
                        fwrite($file, "<body style='font-family:verdana; font-size:10px;'>");
                        fwrite($file, "<div class='printArea'>");
                        $nama_kasir = $_SESSION['nama_pegawai'];
                        $html = "<table class='data' cellspacing='0' cellpadding='0' width='100%'>
                            <tr height='17'>
                            <td height='17' colspan='5'><span style='font-family:verdana;font-size:12'><b>INSTALASI FARMASI RSUD Dr. SOEGIRI</b></span></td>
                            </tr>
                            <tr height='17'>
                            <td height='17' colspan='5'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            <tr height='17'>
                                <td width='10%'>Kwitansi No</td>
                                <td width='39%'>: <b>" . $id_retur . "</b></td>
                                <td width='2%'>&nbsp;</td>
                                <td width='10%'>No RM</td>
                                <td width='39%'>: <b>" . @mysql_result($r_faktur, 0, 'id_pasien') . "</b></td>
                            </tr>
                            <tr height='17'>
                                <td width='10%'>Tanggal</td>
                                <td width='39%'>: <b>" . $tanggal . "</b></td>
                                <td width='2%'>&nbsp;</td>
                                <td width='10%'>Nama PX</td>
                                <td width='39%'>: <b>" . @mysql_result($r_faktur, 0, 'nama_pasien') . "</b></td>
                            </tr>
                            <tr height='17'>
                                <td width='10%'>Jam</td>
                                <td width='39%'>: <b>" . $tgl[1] . "</b></td>
                                <td width='2%'>&nbsp;</td>
                                <td width='39%' colspan='2'><b>" . $_SESSION['nama_pegawai'] . "</b></td>
                            </tr>
                       </table>
                       <hr>
                       <table class='data' cellspacing='0' cellpadding='0' width='100%'>
                           <tr>
                               <td width='30%' class='headerTagihan'>Item</td>
                               <td width='10%' class='headerTagihan'>Qty</td>
                               <td width='10%' class='headerTagihan'>Pros</td>
                               <td width='10%' class='headerTagihan'>Jenis</td>
                               <td width='20%' class='headerTagihan'>Harga</td>
                               <td width='20%' class='headerTagihan'>Total</td>
                           </tr>";
                        $query = "select * from rm_retur_penjualan_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' and id_retur='" . $id_retur . "' and del_flag<>'1'";
                        $result = $this->runQuery($query);
                        $total = 0;
                        $diskon = 0;
                        while ($data = mysql_fetch_array($result)) {
                            if ($data['jns_retur'] == 1)
                                $jenis = 'Lunas';
                            if ($data['jns_retur'] == 0)
                                $jenis = 'Kredit';

                            $obat = $this->getNamaObat($data['id_obat']);
                            $q_obat = "select harga from rm_penjualan_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' and id_obat='" . $data['id_obat'] . "'";
                            $r_obat = $this->runQuery($q_obat);
                            $html .= "<tr>
                       <td width='30%'>" . $obat . "</td>
                       <td width='10%'>" . $data['jumlah'] . "</td>
                       <td width='10%'>" . ($data['pros_retur'] * 100) . " %</td>
                       <td width='10%'>" . $jenis . "</td>
                       <td width='20%' align='right'>" . number_format(@mysql_result($r_obat, 0, 'harga'), 2, ',', '.') . "</td>
                       <td width='20%' align='right'>- " . number_format((@mysql_result($r_obat, 0, 'harga') * $data['jumlah'] * (1 - $data['pros_retur'])), 2, ',', '.') . "</td>
                   </tr>";
                            $total += ( @mysql_result($r_obat, 0, 'harga') * $data['jumlah']);
                            $diskon += ( @mysql_result($r_obat, 0, 'harga') * $data['jumlah']) * (1 - $data['pros_retur']);
                        }
                        $html .= "<tr>
                       <td width='50%' colspan='5' align='right' class='total'>Total</td>
                       <td width='20%' class='total' align='right'>- " . number_format($total, 2, ',', '.') . "</td>
                   </tr>";
                        $html .= "<tr>
                       <td width='50%' colspan='5' align='right' >Disc</td>
                       <td width='20%' align='right'>- " . number_format(($total - $diskon), 2, ',', '.') . "</td>
                   </tr>";
                        $html .= "<tr>
                       <td width='50%' colspan='5' align='right' >Total</td>
                       <td width='20%' align='right'>- " . number_format(($diskon), 2, ',', '.') . "</td>
                   </tr>";
                        $html .= "</table>";
                        fwrite($file, $html);
                        fwrite($file, "</div></body></html>");
                        //fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");
                        fclose($file);

                        return '1';
                    } else {
                        return '0';
                    }
                }
            }
        }
    }

    public function cetakUlangRetur($id_retur) {

        $query = "select * from rm_retur_penjualan_obat where id_retur='" . $id_retur . "' and del_flag<>'1'";
        $result = $this->runQuery($query);
        if (@mysql_num_rows($result) > 0) {
            $id_faktur_penjualan = @mysql_result($result, 0, 'id_faktur_penjualan');
            $tgl = explode(" ", @mysql_result($result, 0, 'tgl_retur'));
            $tanggal = $this->codeDate($tgl[0]);
            $q_faktur = "select * from rm_faktur_penjualan where id_faktur_penjualan='" . $id_faktur_penjualan . "' and del_flag<>'1'";
            $r_faktur = $this->runQuery($q_faktur);
            $nama = $this->getPasienNama(@mysql_result($r_faktur, 0, 'id_pasien'));
            $file = fopen("../report/cetakKwitansiRetur.html", 'w');
            fwrite($file, "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 
                           'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                           <html xmlns='http://www.w3.org/1999/xhtml'>
                           <head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
                           <title></title>
                           <script src='../js/jquery-1.4.4.min.js'></script>
                           <script src='../js/jquery.print.js'></script>
                           <link rel='stylesheet' type='text/css' href='../style/style.css'/>
                            <style>
                                .data{
                                    font-family: verdana;
                                    font-size: 9pt;
                                }
                                .printArea{
                                    font-family: verdana;
                                    font-size: 9pt;
                                }
                                .headerTagihan{
                                    font-weight: bold;
                                    border-bottom: 1px solid #000000;
                                    border-top: 1px solid #000000;
                                }
                                .total{
                                    font-weight: bold;
                                    border-top: 1px solid #000000;
                                }
                           </style>
                           <script>
                           $(function() {
                            $( '.printArea' ).print();
                           });
                           </script>
                           </head>");
            fwrite($file, "<body style='font-family:verdana; font-size:10px;'>");
            fwrite($file, "<div class='printArea'>");
            $nama_kasir = $_SESSION['nama_pegawai'];
            $html = "<table class='data' cellspacing='0' cellpadding='0' width='100%'>
                            <tr height='17'>
                            <td height='17' colspan='5'><span style='font-family:verdana;font-size:12'><b>INSTALASI FARMASI RSUD Dr. SOEGIRI</b></span></td>
                            </tr>
                            <tr height='17'>
                            <td height='17' colspan='5'><u><b>Jl. Kusuma Bangsa No. 07 Lamongan, Telp. 0322-321718</b></u><br></td>
                            <tr height='17'>
                                <td width='10%'>Kwitansi No</td>
                                <td width='39%'>: <b>" . $id_retur . "</b></td>
                                <td width='2%'>&nbsp;</td>
                                <td width='10%'>No RM</td>
                                <td width='39%'>: <b>" . @mysql_result($r_faktur, 0, 'id_pasien') . "</b></td>
                            </tr>
                            <tr height='17'>
                                <td width='10%'>Tanggal</td>
                                <td width='39%'>: <b>" . $tanggal . "</b></td>
                                <td width='2%'>&nbsp;</td>
                                <td width='10%'>Nama PX</td>
                                <td width='39%'>: <b>" . @mysql_result($r_faktur, 0, 'nama_pasien') . "</b></td>
                            </tr>
                            <tr height='17'>
                                <td width='10%'>Jam</td>
                                <td width='39%'>: <b>" . $tgl[1] . "</b></td>
                                <td width='2%'>&nbsp;</td>
                                <td width='39%' colspan='2'><b>" . $_SESSION['nama_pegawai'] . "</b></td>
                            </tr>
                       </table>
                       <hr>
                       <table class='data' cellspacing='0' cellpadding='0' width='100%'>
                           <tr>
                               <td width='30%' class='headerTagihan'>Item</td>
                               <td width='10%' class='headerTagihan'>Qty</td>
                               <td width='10%' class='headerTagihan'>Pros</td>
                               <td width='10%' class='headerTagihan'>Jenis</td>
                               <td width='20%' class='headerTagihan'>Harga</td>
                               <td width='20%' class='headerTagihan'>Total</td>
                           </tr>";
            $query = "select * from rm_retur_penjualan_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' and id_retur='" . $id_retur . "' and del_flag<>'1'";
            $result = $this->runQuery($query);
            $total = 0;
            $diskon = 0;
            while ($data = mysql_fetch_array($result)) {
                if ($data['jns_retur'] == 1)
                    $jenis = 'Lunas';
                if ($data['jns_retur'] == 0)
                    $jenis = 'Kredit';

                $obat = $this->getNamaObat($data['id_obat']);
                $q_obat = "select harga from rm_penjualan_obat where id_faktur_penjualan='" . $id_faktur_penjualan . "' and id_obat='" . $data['id_obat'] . "'";
                $r_obat = $this->runQuery($q_obat);
                $html .= "<tr>
                       <td width='30%'>" . $obat . "</td>
                       <td width='10%'>" . $data['jumlah'] . "</td>
                       <td width='10%'>" . ($data['pros_retur'] * 100) . " %</td>
                       <td width='10%'>" . $jenis . "</td>
                       <td width='20%' align='right'>" . number_format(@mysql_result($r_obat, 0, 'harga'), 2, ',', '.') . "</td>
                       <td width='20%' align='right'>- " . number_format((@mysql_result($r_obat, 0, 'harga') * $data['jumlah'] * (1 - $data['pros_retur'])), 2, ',', '.') . "</td>
                   </tr>";
                $total += ( @mysql_result($r_obat, 0, 'harga') * $data['jumlah']);
                $diskon += ( @mysql_result($r_obat, 0, 'harga') * $data['jumlah']) * (1 - $data['pros_retur']);
            }
            $html .= "<tr>
                       <td width='50%' colspan='5' align='right' class='total'>Total</td>
                       <td width='20%' class='total' align='right'>- " . number_format($total, 2, ',', '.') . "</td>
                   </tr>";
            $html .= "<tr>
                       <td width='50%' colspan='5' align='right' >Disc</td>
                       <td width='20%' align='right'>- " . number_format(($total - $diskon), 2, ',', '.') . "</td>
                   </tr>";
            $html .= "<tr>
                       <td width='50%' colspan='5' align='right' >Total</td>
                       <td width='20%' align='right'>- " . number_format(($diskon), 2, ',', '.') . "</td>
                   </tr>";
            $html .= "</table>";
            fwrite($file, $html);
            fwrite($file, "</div></body></html>");
            //fwrite($file, "<script language='javascript'>setTimeout('self.close();',20000)</script>");
            fclose($file);

            return '1';
        } else {
            return '0';
        }
    }

    public function getMasterSupplier($nama_supplier, $rows, $offset) {
        $kondisi = "";
        if ($nama_supplier != "")
            $kondisi = " and supplier like '%" . $nama_supplier . "%'";
        $query = "SELECT * FROM rm_supplier where del_flag<>'1' " . $kondisi . " order by supplier ";
        $result = $this->runQuery($query);
        $jmlData = mysql_num_rows($result);

        $query .= "limit " . $offset . "," . $rows;

        $result = $this->runQuery($query);
        if ($jmlData > 0) {
            while ($rec = mysql_fetch_array($result)) {
                $arr[] = array(
                    'id_supplier' => $rec['id_supplier'],
                    'supplier' => $rec['supplier']
                );
            }
            return '{"total":' . $jmlData . ', "rows":' . $this->jEncode($arr) . '}';
        } else {
            return '{"total":"0", "rows":[]}';
        }
    }

    public function hapusMasterSupplier($id_supplier) {
        $query = "update rm_supplier set del_flag='1' where id_supplier='" . $id_supplier . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function simpanMasterSupplier($id_supplier, $supplier) {
        if ($id_supplier == "")
            $query = "insert into rm_supplier(supplier) values ('" . $supplier . "')";
        else
            $query = "update rm_supplier set supplier='" . $supplier . "' where id_supplier='" . $id_supplier . "'";
        $result = $this->runQuery($query);

        if ($result) {
            return '1';
        } else {
            return '0';
        }
    }

    public function getDetailMasterSupplier($id_supplier) {
        $query = "select * from rm_supplier where id_supplier='" . $id_supplier . "'";
        $result = $this->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            $return = array(
                "id_supplier" => @mysql_result($result, 0, "id_supplier"),
                "supplier" => @mysql_result($result, 0, "supplier")
            );

            return $this->jEncode($return);
        }
    }

    public function reStock($id_obat, $id_penjualan) {
        if (isset($_SESSION['level'])) {
            if ($id_penjualan == '') {
                $query = "UPDATE rm_penjualan_obat a, rm_faktur_penjualan b SET a.del_flag=1 WHERE a.id_obat=" . $id_obat . " AND a.id_faktur_penjualan 
                          = b.id_faktur_penjualan AND b.id_ruang = " . $_SESSION['level'] . " AND b.`status`=0 AND b.struk<>1 AND a.del_flag<>1";
            } else {
                $query = "UPDATE rm_penjualan_obat a, rm_faktur_penjualan b SET a.del_flag=1 WHERE a.id_obat=" . $id_obat . " AND a.id_faktur_penjualan 
                          = b.id_faktur_penjualan AND b.id_ruang = " . $_SESSION['level'] . " AND b.`status`=0 AND b.struk<>1 AND a.del_flag<>1 AND id_penjualan_obat<>" . $id_penjualan . "";
            }
            $result = $this->runQuery($query);

            if ($result)
                return '1';
            else
                return '0';
        } else {
            return 'LOGIN';
        }
    }

}

?>
