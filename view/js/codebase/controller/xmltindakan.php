<?php

require_once("../../../../common/koneksi.php");
$res = mysql_connect($mysql_host, $mysql_user, $mysql_passwd);
mysql_select_db($mysql_db);

require("../connector/combo_connector.php");
$combo = new ComboConnector($res);
//$combo->enable_log("temp.log");
//$combo->render_sql("SELECT a.id_detail_tindakan, b.tindakan from rm_detail_tindakan a, rm_tindakan b where b.del_flag<>'1' and a.del_flag<>'1' and a.id_tindakan=b.id_tindakan and a.id_ruang='" . $_SESSION['level'] . "' and b.id_jenis_tindakan='1' ORDER BY b.tindakan", "id_detail_tindakan", "tindakan");
$combo->render_table("rm_tindakan","id_tindakan","tindakan");
?>