<?php
session_start();
include "db.php";
include "fonksiyonlar.php";
if($_SESSION["kullanici_tip"]!="bireysel"){
    exit;
}

if(isset($_POST["secim"]) && !empty($_POST["secim"])){
    
//$sipKod=uniqid("S-");
$sipKod=sipKodUret();
$birid=$_SESSION["kullanici_id"];
 
//An array of arrays, containing the rows that we want to insert.
$eklenceklerDizi=array();
$sepetsor=$db->prepare("select * from sepet where bireysel_id=:pbid");
$sepetsor->execute(array('pbid'=>$birid));

$sepetkurcek=$db->prepare("select kurumsal_id from sepet where bireysel_id=:pbid GROUP BY kurumsal_id");
$sepetkurcek->execute(array("pbid"=>$birid));
$sckid=$sepetkurcek->fetch();
$kid=$sckid["kurumsal_id"];



//SİPARİŞ VER BASILDIKTAN SONRA MİNİMUM TUTAR KONTORLÜ İÇİN Bilgi 
           $url1 = "https://api.omurserdar.com/api/kurumsal/index.php?id=$kid";
           $json1 = file_get_contents($url1);
           $jsonverilerim1 = json_decode($json1, true);   
           $kurumsalminalim=$jsonverilerim1["kurumsalbilgileri"]["minAlimTutar"];
           
//siparisDetay tablosuna çoklu ekleme yapılacak
while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)){
$envid=$sepetcek['envanter_id'];
$url = "https://api.omurserdar.com/api/envanter/index.php?id=$envid";
$json = file_get_contents($url);
$jsonverilerim = json_decode($json, true);   
$envBirimFiyat=$jsonverilerim["envanterBilgi"]["fiyat"];
$adet=$sepetcek["adet"];
$eklencek=array("siparisKod"=>$sipKod,
                "envanter_id"=>$envid,
                "adet"=>$adet,
                "tutar"=>$adet*$envBirimFiyat);
array_push($eklenceklerDizi,$eklencek);
    }
        /*
        $kiddizi=array();
        foreach($_SESSION["sepet"] as $k=>$v){
            array_push($kiddizi,$v["k_id"]);
        }
        */
        
if($_POST["toplamtutar"]>=$kurumsalminalim){
    $db->beginTransaction();
    
    
    if(pdoMultiInsert('siparisDetay', $eklenceklerDizi, $db)){
        $durum="eklendi";
         //siparisDetay a her bir env eklendi
         
         //simdi siparis tablosuna tek kayıt(sonuc) eklenecek
        $query = $db->prepare("INSERT INTO siparis SET siparisKod = ?,bireysel_id = ?,
        kurumsal_id = ?,durum_id = ?,toplamTutar=?");
        $insert = $query->execute(array($sipKod,$birid,$kid,1,$_POST["toplamtutar"]));
        //eklendi
            /*
            if ($insert){
                //$last_id = $db->lastInsertId();
                $query = $db->prepare("INSERT INTO siparisDetay SET
            }
            */
        //simdi sepet tablosundaki siparis verilen ürünler silinecek
        $silsorgu=$db->prepare("DELETE FROM sepet WHERE bireysel_id=:pbid");
        $silsorgu->execute(array("pbid"=>$birid));
        $db->commit();
    }
    else{
        $durum="hata";
        $db->rollBack();
    }
  }
  else
    $durum="minhata";
    
$dizim=array();
$dizim["eklencekler"]=$eklenceklerDizi;
$dizim["cevap"]=$durum;
if($durum!="minhata"&&$durum!="hata")
    $dizim["sipKod"]=$sipKod;
echo json_encode($dizim);
}
else{
    exit;
}
//if($btn=="btnSiparisVer"){}