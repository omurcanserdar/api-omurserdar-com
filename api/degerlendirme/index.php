<?php
include "../../db.php";
include "../../fonksiyonlar.php";
$jsonArray = array(); // son olarak json_encode işlemi yapılacak . 
$jsonArray["hata"] = FALSE; // Başlangıçta hata yok olarak kabul edelim. 
 
$httpKOD = 200; // HTTP Ok olarak durumu kabul edelim. 
$istekMOD = $_SERVER["REQUEST_METHOD"]; // istemciden gelen istek
//print_r(json_decode(file_get_contents("php://input"), true));

if($istekMOD=="POST") {

sesYoksaCik("kullanici_tip","bireysel");

    //daha önce değerlendirme yapılmışmı
    $sorgu=$db->prepare("SELECT COUNT(id) as varmi FROM degerlendirme where siparisKod=:psipkod");
    $sorgu->execute(array("psipkod" => $_POST["sipariskod"]));
    $cek=$sorgu->fetch(PDO::FETCH_ASSOC);
    
    //değerlendirme yoksa
    if($cek["varmi"]==0){
$degekle=$db->prepare("INSERT INTO degerlendirme set hiz=:phiz,lezzet=:plezzet,yorum=:pyorum,siparisKod=:psipkod");
 $degekle->execute(array('phiz' => $_POST["hiz"],"plezzet" => $_POST["lezzet"],
 "pyorum" => $_POST["yorum"],"psipkod"=>$_POST["sipariskod"]));

 $degsay=$degekle->rowCount();
		 if($degsay==1){
		     $httpKOD = 201;
             $jsonArray["mesaj"] = "eklendi";
		 }
		 else{
		     $httpKOD = 200;
             $jsonArray["hata"] = TRUE;
             $jsonArray["mesaj"] = "eklenemedi";
		 }
    }//kullanici dbde varsa
    else{
             $httpKOD = 200;
             $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
             $jsonArray["mesaj"] = "kullanicizatenmevcut";
    }
    
}

//GÜNCELLEME İSTEĞİ
else if($istekMOD=="PUT") {
    
    $gelenler=json_decode(file_get_contents("php://input"));
        if($db->query("select * from degerlendirme where id='$gelenler->id'")->rowCount()==0){
             $httpKOD = 400;
             $jsonArray["hata"] = TRUE;
             $jsonArray["mesaj"] = "id değerine sahip değerlendirme bulunamadı";
        }
        else{
              $deguncelle=$db->prepare("UPDATE degerlendirme SET hiz=:phiz,lezzet=:plezzet,yorum=:pyorum WHERE id=:pid");
                 $deguncelle->execute(array(
                 "phiz" => $gelenler->hiz,"plezzet" => $gelenler->lezzet,
                 "pyorum" => $gelenler->yorum,"pid" => $gelenler->id));
                 // güncelleme başarılı
                 if($deguncelle->rowCount()>0){
                     $httpKOD = 200;
                     $jsonArray["mesaj"] = "güncellendi";
                 }
                 else {
                     $httpKOD = 200;
                     $jsonArray["hata"] = TRUE;
                     $jsonArray["mesaj"] = "güncellenmedi";
                 }
        }

    
}

//SON GÜNCELLEME İSTEĞİ


//SİLME İSTEĞİ
else if($istekMOD=="DELETE") {
       
       sesYoksaCik("kullanici_tip","bireysel");
       // parse_str(file_get_contents("php://input"),$veriler);
       // $bid=$veriler["id"];
       
    if(isset($_GET["id"])){
        $id=strip_tags($_GET["id"]);
        $degVarMi = $db->query("select * from degerlendirme where id=$id");
        if($degVarMi->rowCount()==1){
            $degsil = $db->query("delete from degerlendirme where id=$id");
            if($degsil->rowCount()==1){
                $httpKOD = 200;
                $jsonArray["mesaj"] = "silindi";
            }else{
                $httpKOD = 200;
                $jsonArray["hata"] = TRUE;
                $jsonArray["mesaj"] = "silinemedi";
            }
        }
        else{
            $httpKOD = 200; 
            $jsonArray["hata"] = TRUE;
            $jsonArray["mesaj"] = "kayıt bulunamadı";
        }
    }

    else{
         $httpKOD = 400;
         $jsonArray["hata"] = TRUE; 
         $jsonArray["mesaj"] = "değerlendirme id gönder";
    }

}
//SON SİLME İSTEĞİ


//BİLGİ ÇEKME İSTEĞİ
else if($istekMOD=="GET"){
  

  if((isset($_GET["id"])&&!isset($_GET["sipariskod"])) or (!isset($_GET["id"]) && isset($_GET["sipariskod"]))){
      
      //yalnızca sipkod oldugunda sorguda id= degeri aramaya çalısıyor fakat bulamıyor, bu durumdan dolayı id='""' ile işlem yaptım
      
      $deg=$db->query("select * from degerlendirme where id='".$_GET["id"]."' or siparisKod='".$_GET["sipariskod"]."' ")->fetch(PDO::FETCH_ASSOC);
      if(empty($deg)){
        $httpKOD = 200;
        $jsonArray["hata"] = TRUE;
        $jsonArray["mesaj"] = "id ya da sipkod değerine ait değerlendirme yok";
        $jsonArray["degsay"] =0;
      }
      else{
        $httpKOD = 200;
        $jsonArray["hata"] = FALSE;
        $jsonArray["degerlendirme"] = $deg;
        $jsonArray["degsay"] =1 ;
      }
      
  }
  elseif(isset($_GET["id"])and isset($_GET["sipariskod"])){
    $httpKOD = 200;
    $jsonArray["hata"] = TRUE; 
    $jsonArray["mesaj"] = "id veya siparis kod değerlerinden yalnızca bir tanesini gönder";
  }
  else{
        $httpKOD = 200;
        $jsonArray["hata"] = TRUE;
        $jsonArray["mesaj"] = "değerlendirme id ya da siparis kod yok";
  }
  
}
//SON BİLGİ ÇEKME İSTEĞİ


baslikAyarlaJSONyaz($httpKOD,$jsonArray);
/*
SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);
*/


?>