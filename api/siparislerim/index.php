<?php
include "../../db.php";
include "../../fonksiyonlar.php";
$jsonArray = array(); 
$jsonArray["hata"] = FALSE; 
 
$httpKOD = 200; 
$istekMOD = $_SERVER["REQUEST_METHOD"];
//print_r(json_decode(file_get_contents("php://input"), true));

if($istekMOD=="PUT"){
   $gelenler=json_decode(file_get_contents("php://input"));
        if($db->query("select * from siparis where siparisKod='$gelenler->sipkod'")->rowCount()==0){
             $httpKOD = 400;
             $jsonArray["hata"] = TRUE;
             $jsonArray["hataMesaj"] = "sipariş bulunamadı";
             }else{
                 $envguncelle=$db->prepare("update siparis,siparisDurum,kurumsal,bireysel SET siparis.durum_id=:pdurumid
WHERE siparisDurum.id=siparis.durum_id
AND siparis.kurumsal_id=kurumsal.id
AND siparis.bireysel_id=bireysel.id
AND siparis.siparisKod=:psipkod");
                 $envguncelle->execute(array("pdurumid" => $gelenler->durumid,"psipkod" => $gelenler->sipkod));
                 // güncelleme başarılı
                 if($envguncelle->rowCount()>0){
                 $httpKOD = 200;
                 $jsonArray["mesaj"] = "güncellendi";
                 }
                 else {
                 // güncelleme başarısız
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["mesaj"] = "güncellenmedi";
                 }
            }
}
else if($istekMOD=="DELETE") {
       // parse_str(file_get_contents("php://input"),$veriler);
       // $bid=$veriler["id"];
}


else if($istekMOD=="GET"){
    // parse_str(file_get_contents("php://input"),$veriler);
       
       $id=$_GET["id"];
    if(!isset($_GET["kid"]) && isset($id) && !empty(trim($id))){
         $sipVarMi = $db->query("SELECT DISTINCT siparis.siparisKod,kurumsal.ad,siparisDurum.tanim,siparis.siparisTarih,siparis.toplamTutar FROM siparis,siparisDetay,siparisDurum,kurumsal,bireysel
WHERE siparis.durum_id=siparisDurum.id 
AND siparis.bireysel_id=bireysel.id
AND siparis.kurumsal_id=kurumsal.id
AND siparis.bireysel_id='$id'
ORDER BY siparis.siparisTarih DESC");
         if($sipVarMi->rowCount()>0){
             $jsonArray["siparislerKume"]=array();
             while($cek=$sipVarMi->fetch(PDO::FETCH_ASSOC)){
              $a=array("siparisKod"=>$cek["siparisKod"],
              "ad"=>$cek["ad"],
              "sipTarih"=>$cek["siparisTarih"],
              "tanim"=>$cek["tanim"],
              "toplamTutar"=>$cek["toplamTutar"]);
             array_push($jsonArray["siparislerKume"],$a);
             }
             $httpKOD = 200;
             $jsonArray["sayi"] = $sipVarMi->rowCount();
             }else {
                 $httpKOD = 200;
                 $jsonArray["sayi"] = $sipVarMi->rowCount();
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "sip bulunamadi";
                    }
    }
    
    elseif(!isset($_GET["id"]) && isset($_GET["kid"]) && !empty(trim($_GET["kid"]))){
        $kid=$_GET["kid"];
 $sipVarMi = $db->query("SELECT DISTINCT siparis.siparisKod,bireysel.ad,siparisDurum.id as sipdurumid,siparisDurum.tanim,siparis.siparisTarih,siparis.toplamTutar FROM siparis,siparisDetay,siparisDurum,kurumsal,bireysel
WHERE siparis.durum_id=siparisDurum.id AND
siparis.siparisKod=siparisDetay.siparisKod AND
siparis.kurumsal_id=kurumsal.id AND
siparis.bireysel_id=bireysel.id AND
siparis.kurumsal_id='$kid'
ORDER BY siparis.siparisTarih DESC");
         if($sipVarMi->rowCount()>0){
             $jsonArray["siparislerKume"]=array();
             while($cek=$sipVarMi->fetch(PDO::FETCH_ASSOC)){
              $a=array("siparisKod"=>$cek["siparisKod"],
              "ad"=>$cek["ad"],
              "sipTarih"=>$cek["siparisTarih"],
              "sipdurumid"=>$cek["sipdurumid"],
              "tanim"=>$cek["tanim"],
              "toplamTutar"=>$cek["toplamTutar"]);
             array_push($jsonArray["siparislerKume"],$a);
             }
             $httpKOD = 200;
             $jsonArray["sayi"] = $sipVarMi->rowCount();
             }else {
                 $httpKOD = 200;
                 $jsonArray["sayi"] = $sipVarMi->rowCount();
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "sip bulunamadi";
                    }
    }
    elseif(isset($_GET["id"])&&isset($_GET["kid"])){
        $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "id gönder";
    }
    
    
    
    
    
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "id gönder";
 }
}





SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);
