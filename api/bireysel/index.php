<?php
include $_SERVER["DOCUMENT_ROOT"]."/db.php";
include $_SERVER["DOCUMENT_ROOT"]."/fonksiyonlar.php";

beginAPIHeader(); //başlıklar ayarlandı

$jsonArray = array(); // array değişkenimiz bunu en alta json objesine çevireceğiz. 
$jsonArray["hata"] = false; // Başlangıçta hata yok 
 
$httpKOD = 200; // HTTP Ok olarak durumu kabul edelim. 
$istekMOD = $_SERVER["REQUEST_METHOD"]; // client tarafından bize gelen method
//print_r(json_decode(file_get_contents("php://input"), true));

if($istekMOD=="POST"){
   
    //kullanici adi veya email kayıtlarda varsa kullaniciVar mesajı dönsün ve işlem yapılsın
    $sorgu=$db->prepare("SELECT COUNT(id) as varmi FROM bireysel where email=:pmail or kullaniciadi=:pkul");
    $sorgu->execute(array("pmail" => $_POST["email"],"pkul" => $_POST["kullaniciadi"]));
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
             $jsonArray["hata"] = true; //hata olduğu bildirilsin.
             $jsonArray["mesaj"] = "eklenmedi";
		 }
    } //kullanici dbde varsa
    else{
             $httpKOD = 200;
             $jsonArray["hata"] = true;
             $jsonArray["mesaj"] = "kullanicizatenmevcut";
    }
    
}

//İSTEK GÜNCELLE
else if($istekMOD=="PUT") {
     $gelenler=json_decode(file_get_contents("php://input"));
        if($db->query("select * from bireysel where id='$gelenler->id' or email='$gelenler->email'")->rowCount()==0){
             $httpKOD = 400;
             $jsonArray["hata"] = true;
             $jsonArray["hataMesaj"] = "bireysel bulunamadı";
             }else{
                 
                 //EĞER ŞİFRE GÜNCELLENECEK İSE
                 if(isset($gelenler->sifre)){
                     
                    $sifre=generateRandomKey();
                     
                    $db->beginTransaction();
                    
                    $birguncelle=$db->prepare("UPDATE bireysel SET sifre=:psifre WHERE email=:pmail");
                    $birguncelle->execute(array("psifre"=>md5($sifre),
                 "pmail" => $gelenler->email));
                 
                    if($birguncelle) //SİFRE GUNCELLENDİ İSE 
                        $emailGonderilecekMi=true; //EMAİL GÖNDERİM İŞLEM YAKALAMA İÇİN
                 }
                 
                 //SON EĞER ŞİFRE GÜNCELLENECEK İSE
                 
                 
                 //ŞİFRE GÜNCELLENMEYECEK İSE (örneğin bireysel giriş-bilgilerim tab üzerinde düzenleme)
                 else{
                 $birguncelle=$db->prepare("UPDATE bireysel SET ad=:pad,soyad=:psoyad,email=:pmail,sifre=:psifre WHERE id=:pid");
                 $birguncelle->execute(array(
                 "pad" => $gelenler->ad,
                 "psoyad" => $gelenler->soyad,
                 "pmail" => $gelenler->email,
                 "psifre" => $gelenler->sifre,
                 "pid" => $gelenler->id 
                 ));
                 
             }
             //SON ŞİFRE GÜNCELLENMEYECEK İSE
             
             //her iki durumda da güncelleme başarılı
                 if($birguncelle->rowCount()>0){
                     
                     //ŞİFRE GÜNCELLENDİĞİNDE EMAİL GÖNDERİM İÇİN                 
                    if($emailGonderilecekMi==true){
                        
                    //bilgileri al
                        $url = "https://api.omurserdar.com/api/bireysel?email=$gelenler->email";
                        $json = file_get_contents($url);
                        $jsonverilerim = json_decode($json, true);   
                        $tblbirkume=$jsonverilerim["bireyselbilgileri"];
                    //bilgi al son
                        
                        $mesaj='<h3> Merhaba, '.$tblbirkume["ad"]." ".$tblbirkume["soyad"].' </h3> <br> Yeni Şifreniz: '.$sifre;
                        if(!mailGonder("sifre@api.omurserdar.com","AP!",$gelenler->email,"sifre",$mesaj,"altbas")){
                            
                            $db->rollBack();
                            echo "<br> ROLLBACK ÇALIŞTI<br>";
                            exit;
                             
                        }
                        else{
                        //demekki mail gönderildi, değişiklikleri uygula
                        $db->commit();
                        }
                    //SON ŞİFRE GÜNCELLENDİĞİNDE EMAİL GÖNDERİM İÇİN  
                 
                    }
                 
                     $httpKOD = 200;
                     $jsonArray["hata"] = FALSE;
                     $jsonArray["mesaj"] = "güncellendi";
                 
                }//SON HER İKİ GÜNCELLEME BAŞARILI İSE
                 
                else{ //HER İKİ güncelleme başarısız İSE
                    $httpKOD = 400;
                    $jsonArray["hata"] = TRUE;
                    $jsonArray["mesaj"] = "güncellenmedi";
                }
    }//kurumsalbulunduise
}
//SON GÜNCELLEME İSTEĞİ

//İSTEK DELETE
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
//SON İSTEK DELETE

//İSTEK BİLGİ ÇEKME
else if($istekMOD=="GET"){
    

      // parse_str(file_get_contents("php://input"),$veriler);
     //  $kullad=$veriler["kullaniciadi"];
    if((isset($_GET["id"]) && !empty(trim($_GET["id"]))) || isset($_GET["email"]) && !empty(trim($_GET["email"]))){
        
        
        if(isset($_GET["id"]) && isset($_GET["email"])){
            $httpKOD = 400;
            $jsonArray["hata"] = TRUE; 
            $jsonArray["hataMesaj"] = "tek değer ver iki değer verdin";
            
            SetHeader($httpKOD);
            $jsonArray[$httpKOD] = HttpStatus($httpKOD);
            echo json_encode($jsonArray);
            
            exit;
        }
         
        if(isset($_GET["id"]))
            $bid=$_GET["id"];
        else
            $bemail=$_GET["email"];
            
         $birVarMi = $db->query("select * from bireysel where id='$bid' or email='$bemail'");
         //$birVarMi->debugDumpParams();
         if($birVarMi->rowCount()>0){
             //$birbilgiler = $db->query("select * from  bireysel where id='$bid' ")->fetch(PDO::FETCH_ASSOC);
             $birbilgiler = $db->query("SELECT bireysel.*,il.il_adi,ilce.ilce_adi FROM bireysel,il,ilce where bireysel.il_id=il.id 
and bireysel.ilce_id=ilce.id
and ilce.il_id=il.id 
and bireysel.id='$bid' or email='$bemail'")->fetch(PDO::FETCH_ASSOC);
             $jsonArray["bireyselbilgileri"] = $birbilgiler;
             unset($jsonArray["bireyselbilgileri"]["sifre"]);
            // $jsonArray["sepet"]=null;
             $httpKOD = 200;
             }else {
                 $httpKOD = 200;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "Üye bulunamadı";
                    }
    }
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "Bireysel id ya da email gönder";
 }
}
//SON İSTEK BİLGİ ÇEKME


baslikAyarlaJSONyaz($httpKOD,$jsonArray);

/*
SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);
*/
?>