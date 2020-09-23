<?php
include "db.php";
include "fonksiyonlar.php";
$btn=strip_tags($_POST['secim']);
if($btn=="btngirisyap"){
    try{
        
        $tip=strip_tags($_POST['tip']);
        $kullanici_mail=strip_tags($_POST['ad']);
        $kullanici_sifre=strip_tags($_POST['sifre']);
        
        $kullanicisor=$db->prepare("SELECT * FROM $tip where email=:mail and sifre=:password");
        $kullanicisor->execute(array(':mail' => $kullanici_mail, ':password' => md5($kullanici_sifre)));
        
        //calories < ? AND colour = ? execute(array($calories, $colour));
        
        $say=$kullanicisor->rowCount();
        //echo $say==1 ? 'bulundu' : 'bulunamadı';
        if ($say==1) {
            echo "bulundu";
            session_start();
            $_SESSION['kullanici_tip']=$tip;
            $_SESSION['kullanici_mail']=$kullanici_mail;
            $kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);
            $_SESSION['kullanici_id']=$kullanicicek["id"];
            $_SESSION['kullanici_token']=$kullanicicek["oturumToken"];
            //$_SESSION["sepet"]=array();
	        //header("Location:/");
	        
	        
	        
	           $oTok=$kullanicicek["oturumToken"];
    	       $cevrimDurum=1;
    	       $ip=getIP();
	         //burada eğer oturum tablosunda yoksa ekleme yapılacak
	         $oturumkayitVarMi=$db->prepare("SELECT oturumToken FROM oturum where oturumToken=?");
             $oturumkayitVarMi->execute(array($_SESSION['kullanici_token']));
             if($oturumkayitVarMi->rowCount()==0){
                //oturum ekleme
    	        //oturum vtye ekle
    	        $oturumEkle=$db->prepare("INSERT INTO oturum (oturumToken,cevrimiciMi,sonGirisIP) VALUES (?,?,?)");
                $oturumEkle->execute([$oTok, $cevrimDurum, $ip]);
    	        //son oturum vtye ekle
    	        //son oturum ekleme
             }
             elseif($oturumkayitVarMi->rowCount()==1){
                $oturumGuncelle=$db->prepare("UPDATE oturum SET cevrimiciMi=?,sonGirisIP=?,sonGirisTarih=? WHERE oturumToken=?");
                $oturumGuncelle->execute([$cevrimDurum,$ip,date("Y-m-d H:i:s"),$oTok]);
             }
	        
	       
	        
	        
	        
	        //eğer oturum tablosunda kayıt varsa bilgiler güncellenecek
	        //
	        
		exit;
	} 
	else{
	//	header("Location:/giris?durum=girisbasarisiz");
	    echo "bulunamadı";
		exit;
        }
    }
    catch(Exception $e){
    echo 'Yakalanan olağandışılık: ',  $e->getMessage(), "\n";
    }
}
?>