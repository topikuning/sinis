<?
require_once("../common/function.php");
$func=new Fungsi;
$func->logout();

header("location: login.php");
?>