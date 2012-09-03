<?php

ob_start();
session_start();

require_once("../common/function.php");

$func = new Fungsi();

$user = isset($_POST["loginUser"]) ? $_POST["loginUser"] : "";
$password = isset($_POST["loginPassword"]) ? $_POST["loginPassword"] : "";
//$jenis = isset($_POST["jenis"]) ? $_POST["jenis"] : "";

$login = $func->login($user, md5($password));
if (!empty($login)) {
    if ($_SESSION["jenis"] == 'pegawai') {
        if ($login == '9') {
            header("location:index.php?page=dftr");
        } else if ($login == '18') {
            header("location:index.php?page=lstobt");
        } else if ($login == '15') {
            header("location:index.php?page=lstblobt");
        } else if ($login == '48') {
            header("location:index.php?page=tghnrwtinp");
        } else if ($login == '19') {
            header("location:index.php?page=lstblobtfrm");
        } else if ($login == '36' || $login == '46' || $login == '47' || $login == '50') {
            header("location:index.php?page=pjlobt");
        } else if ($login == '14') {
            header("location:index.php?page=dftrtghnpx");
        } else if ($login == '17') {
            header("location:index.php?page=rawat");
 	} else if ($login == '55') {
            header("location:index.php?page=pux");
        } else {
            header("location:index.php");
        }
    } else if ($_SESSION["jenis"] == 'perawat') {
        if ($login == '24' || $login == '25' || $login == '26' || $login == '27' || $login == '28' || $login == '29' || $login == '30' || $login == '31' || $login == '32' || $login == '33' || $login == '44') {
            header("location:index.php?page=lstrawat");
        } else if ($login == '18') {
            header("location:index.php?page=lstrad");
        } else if ($login == '17') {
            header("location:index.php?page=lstlab");
        } else if ($login == '54'){
            header("location:index.php?page=recRm");
        } else {
            header("location:index.php?page=lstdftrpr");
        }
    } else if ($_SESSION["jenis"] == 'dokter') {
        header("location:index.php?page=rmpx");
    }
} else {
    header("location:login.php?act=failed");
}
?>
