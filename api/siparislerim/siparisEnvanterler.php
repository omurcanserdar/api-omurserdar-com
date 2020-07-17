<?php
include "../../db.php";
include "../../fonksiyonlar.php";
$jsonArray = array(); 
$jsonArray["hata"] = FALSE; 
 
$httpKOD = 200; 
$istekMOD = $_SERVER["REQUEST_METHOD"];
//print_r(json_decode(file_get_contents("php://input"), true));


if($istekMOD=="PUT") {
   //  $gelenler=json_decode(file_get_contents("php://input"));

}
else if($istekMOD=="DELETE") {
       // parse_str(file_get_contents("php://input"),$veriler);
       // $bid=$veriler["id"];
}


else if($istekMOD=="GET"){
    // parse_str(file_get_contents("php://input"),$veriler);
       
       $sipKod=$_GET["siparisKod"];
    if(isset($sipKod) && !empty(trim($sipKod))){
         $sipVarMi = $db->query("SELECT siparis.siparisKod,bireysel.ad as bad,kurumsal.ad as kad,envanter.ad as ead,siparisDetay.adet,siparisDetay.tutar 
FROM siparis,siparisDetay,bireysel,kurumsal,envanter WHERE
siparis.siparisKod=siparisDetay.siparisKod AND
bireysel.id=siparis.bireysel_id AND
kurumsal.id=siparis.kurumsal_id AND
siparisDetay.envanter_id=envanter.id AND
siparis.siparisKod='$sipKod'");
         if($sipVarMi->rowCount()>0){
             $jsonArray["EnvnaterlerVeSiparisler"]=array();
             while($cek=$sipVarMi->fetch(PDO::FETCH_ASSOC)){
             $a=array("bireysel_ad"=>$cek["bad"],
             "kurumsal_ad"=>$cek["kad"],
             "envanter_ad"=>$cek["ead"],
             "adet"=>$cek["adet"],
             "tutar"=>$cek["tutar"]);
             array_push($jsonArray["EnvnaterlerVeSiparisler"],$a);
             }
             $httpKOD = 200;
             $jsonArray["hata"] = false;
             $jsonArray["sayi"] = $sipVarMi->rowCount();
             }else {
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "sip kod db de yok";
                    }
    }
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "sip id gönder";
 }
}





SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);
