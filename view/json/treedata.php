<?
ob_start();
session_start();

require_once("../../common/function.php");

$func=new Fungsi();

$list=$func->menu($_SESSION['jenis'],$_SESSION['level'] );

echo json_encode($list);
?>