<?php
include "db.php";
$btn=$_POST['secim'];
        if ($btn=="btngirisyap") { 
         try{
    $tip=$_POST['tip'];
	$kullanici_mail=$_POST['ad'];
	$kullanici_sifre=$_POST['sifre'];

	$kullanicisor=$db->prepare("SELECT * FROM $tip where email=:mail and sifre=:password");
	$kullanicisor->execute(array(
		'mail' => $kullanici_mail,
		'password' => md5($kullanici_sifre)
		));

	 $say=$kullanicisor->rowCount();
    echo $say==1 ? 'bulundu' : 'bulunamadı';
  
	if ($say==1) {
   session_start();
       	$_SESSION['kullanici_tip']=$tip;
		$_SESSION['kullanici_mail']=$kullanici_mail;
		$kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);
	    $_SESSION['kullanici_id']=$kullanicicek["id"];
	    $_SESSION["sepet"]=array();
	    //header("Location:/");
		exit;
	} else {
	//	header("Location:/giris?durum=girisbasarisiz");
		exit;
}

}
catch (Exception $e) {
    echo 'Yakalanan olağandışılık: ',  $e->getMessage(), "\n";
}
}
?>