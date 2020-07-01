<?php
include "../../db.php";
include "../../fonksiyonlar.php";
$jsonArray = array(); // array değişkenimiz bunu en alta json objesine çevireceğiz. 
$jsonArray["hata"] = FALSE; // Başlangıçta hata yok olarak kabul edelim. 
 
$httpKOD = 200; // HTTP Ok olarak durumu kabul edelim. 
$istekMOD = $_SERVER["REQUEST_METHOD"]; // client tarafından bize gelen method
//print_r(json_decode(file_get_contents("php://input"), true));

if($istekMOD=="POST"){
   
    //kullanici adi veya email kayıtlarda varsa kullaniciVar mesajı dönsün ve işlem yapılsın
    $sorgu=$db->prepare("SELECT COUNT(id) as varmi FROM bireysel where email=:pmail or kullaniciadi=:pkul");
    $sorgu->execute(array("pmail" => strip_tags($_POST["email"]),"pkul" => $_POST["kullaniciadi"]));
    $cek=$sorgu->fetch(PDO::FETCH_ASSOC);
    //kullanici db de yoksa
    if($cek["varmi"]==0){
         $birekle=$db->prepare("INSERT INTO bireysel set il_id=:pil,ilce_id=:pilce,ad=:pad,soyad=:psoyad,kullaniciadi=:pkulad,email=:pmail,sifre=:psif");
 $birekle->execute(array('pil' => $_POST["il"],"pilce" => $_POST["ilce"],
 "pad" => $_POST["ad"],"psoyad" => $_POST["soyad"],"pkulad" => $_POST["kullaniciadi"],
 "pmail" => $_POST["email"],"psif" => md5($_POST["sifre"])));

 $birsay=$birekle->rowCount();
 //echo $birsay;
 //print_r($birekle->errorInfo());
		 if($birsay==1){
		     $httpKOD = 201;
             $jsonArray["mesaj"] = "eklendi";
		 }
		 else{
		     $httpKOD = 400;
             $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
             $jsonArray["mesaj"] = "eklenmedi";
		 }
    } //kullanici dbde varsa
    else{
             $httpKOD = 200;
             $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
             $jsonArray["mesaj"] = "kullanicizatenmevcut";
    }
    
}
else if($istekMOD=="PUT") {
     $gelenler=json_decode(file_get_contents("php://input"));
        if($db->query("select * from bireysel where id='$gelenler->id'")->rowCount()==0){
             $httpKOD = 400;
             $jsonArray["hata"] = TRUE;
             $jsonArray["hataMesaj"] = "kayıt yok";
             }else{
                 $birguncelle=$db->prepare("UPDATE bireysel SET ad=:pad,soyad=:psoyad,email=:pmail,sifre=:psifre WHERE id=:pid");
                 $birguncelle->execute(array(
                 "pad" => $gelenler->ad,
                 "psoyad" => $gelenler->soyad,
                 "pmail" => $gelenler->email,
                 "psifre" => $gelenler->sifre,
                 "pid" => $gelenler->id 
                 ));
                 // güncelleme başarılı ise bilgi veriyoruz. 
                 if($birguncelle->rowCount()>0){
                     echo "güncellendi";
                 $httpKOD = 200;
                 $jsonArray["mesaj"] = "Güncelleme Başarılı";
                 }
                 else {
                 // güncelleme başarısız ise bilgi veriyoruz. 
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "Sistemde Hata Meydana Geldi";
                 }
 }
}

else if($istekMOD=="DELETE") {
        parse_str(file_get_contents("php://input"),$veriler);
    if(isset($veriler["id"]) && !empty(trim($veriler["id"]))) {
        $bid=$veriler["id"];
 $birVarMi = $db->query("select * from bireysel where id='$bid'");
 if($birVarMi->rowCount()==1){
  $birsil = $db->query("delete from bireysel where id='$bid'");
           if($birsil->rowCount()==1){
               echo "silindi";
            $httpKOD = 200;
            $jsonArray["mesaj"] = "Bireysel üye silindi";
         }else {
         $httpKOD = 400;
         $jsonArray["hata"] = TRUE;
         $jsonArray["hataMesaj"] = "Sistemde hata oluştu";
         }
     }
     else {
     $httpKOD = 400; 
     $jsonArray["hata"] = TRUE;
     $jsonArray["hataMesaj"] = "kayıt bulunamadı";
     }
 }
 else {
 $httpKOD = 400;
 $jsonArray["hata"] = TRUE; //
 $jsonArray["hataMesaj"] = "bireysel id değerini gönder";
 }
}

else if($istekMOD=="GET"){
      // parse_str(file_get_contents("php://input"),$veriler);
     //  $kullad=$veriler["kullaniciadi"];
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
         $bid=$_GET["id"];
         $birVarMi = $db->query("select * from bireysel where id='$bid'");
         //$birVarMi->debugDumpParams();
         if($birVarMi->rowCount()>0){
             //$birbilgiler = $db->query("select * from  bireysel where id='$bid' ")->fetch(PDO::FETCH_ASSOC);
             $birbilgiler = $db->query("SELECT bireysel.*,il.il_adi,ilce.ilce_adi FROM bireysel,il,ilce where bireysel.il_id=il.id 
and bireysel.ilce_id=ilce.id
and ilce.il_id=il.id 
and bireysel.id='$bid'")->fetch(PDO::FETCH_ASSOC);
             $jsonArray["bireyselbilgileri"] = $birbilgiler;
             unset($jsonArray["bireyselbilgileri"]["sifre"]);
            // $jsonArray["sepet"]=null;
             $httpKOD = 200;
             }else {
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "Üye bulunamadı";
                    }
    }
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "bireysel id gönder";
 }
}


SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);
?>