<?php
include "db.php";
session_start();
 //$tip=$_SESSION['kullanici_tip'];
 //$mail=$_SESSION['kullanici_mail'];
 //$oTok=$_SESSION['kullanici_token'];
 
$oturumGuncelle=$db->prepare("UPDATE oturum SET cevrimiciMi=0 WHERE oturumToken=?");
$oturumGuncelle->execute(array($_SESSION['kullanici_token']));

session_destroy();
//oturum bilgi guncelle

//

header("refresh:0;url=/");
exit;
?>