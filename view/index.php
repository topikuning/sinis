<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ob_start();
session_start();

$httpHost = "http://".$_SERVER['HTTP_HOST'];
$docRoot = str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']);
$dir = str_replace("\\","/",dirname(__FILE__));
$baseUrl = str_replace($docRoot,$httpHost,$dir);

require_once($docRoot."/produksi/common/function.php");

$func=new fungsi();
if($func->isAuthorized())
	require_once 'home.php';
else
	require_once("login.php");
?>
