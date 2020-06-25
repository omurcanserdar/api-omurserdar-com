<?php
include "../../db.php";
include "../../fonksiyonlar.php";

$jsonArray = array(); 
$jsonArray["hata"] = FALSE; 
 
$httpKOD = 200; 
$istekMOD = $_SERVER["REQUEST_METHOD"];

if($istekMOD=="POST") {
    if(isset($_POST["kid"])&&isset($_POST["ad"])){
 $tabekle=$db->prepare("INSERT INTO tabMenu set kurumsal_id=:pkid,ad=:pad");
 $tabekle->execute(array('pkid' => $_POST["kid"],"pad" => $_POST["ad"]));
 $tabsay=$tabekle->rowCount();
 		 if($tabsay==1){
 		     $httpKOD = 201; //CREATED
		     $jsonArray["eklenen_tab_id"]=$db->lastInsertId();
             $jsonArray["mesaj"] = "eklendi";
		 }
		 else{
		     $httpKOD = 400; //BAD Request
             $jsonArray["hata"] = TRUE;
             $jsonArray["mesaj"] = "eklenmedi";
		 }
    }
}


else if($istekMOD=="PUT") {
 $gelenler=json_decode(file_get_contents("php://input"));
    if($db->query("select * from tabMenu where id='$gelenler->id'")->rowCount()==0){
        $httpKOD = 400;
        $jsonArray["hata"] = TRUE;
        $jsonArray["hataMesaj"] = "tabMenu bulunamadı";
        }
    else{
        $tabguncelle=$db->prepare("UPDATE tabMenu SET ad=:pad WHERE id=:pid");
        $tabguncelle->execute(array("pad" => $gelenler->ad,"pid" => $gelenler->id));
            if($tabguncelle->rowCount()>0){
                 $httpKOD = 200;
                 $jsonArray["mesaj"] = "güncellendi";
            }
            else{
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "güncellenmedi";
                 }
        }
}

else if($istekMOD=="DELETE") {
 if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id=$_GET["id"];
 $tabVarMi = $db->query("select * from tabMenu where id=$id");
 if($tabVarMi->rowCount()==1){
  $tabsil = $db->query("delete from tabMenu where id=$id");
           if($tabsil->rowCount()==1){
            $httpKOD = 200;
            $jsonArray["mesaj"] = "silindi";
         }else{
         $httpKOD = 400;
         $jsonArray["hata"] = TRUE;
         $jsonArray["mesaj"] = "silinemedi";
         }
     }else {
     $httpKOD = 400; 
     $jsonArray["hata"] = TRUE;
     $jsonArray["mesaj"] = "kayıt bulunamadı";
     }
 }else {
 $httpKOD = 400;
 $jsonArray["hata"] = TRUE; 
 $jsonArray["mesaj"] = "tabMenu id değerini gönder";
 }
}


else if($istekMOD=="GET"){
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $tid=$_GET["id"];
         $tabVarMi = $db->query("select * from tabMenu where id='$tid'");
         if($tabVarMi->rowCount()>0){
             $httpKOD = 200;
             $jsonArray["tabMenuBilgi"]=$tabVarMi->fetch(PDO::FETCH_ASSOC);
             }else{
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "tabMenu bulunamadi";
                    }
    }
    else{
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "tabMenu id kontrol et";
 }
}





SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);







?>