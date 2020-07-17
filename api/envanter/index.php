<?php
include "../../db.php";
include "../../fonksiyonlar.php";

//eger session["kullanicitip"] tanımlı değil yada farklı yada boş ise oturum hata mesajı ver ve exit de
//sesYoksaCik("kullanici_tip","kurumsal");
//eger burada kalsaydı GET işlemini yapamazdım o yüzden örnek olarak delete requestinde kullandım

$jsonArray = array(); 
$jsonArray["hata"] = FALSE; 

$httpKOD = 200; 
$istekMOD = $_SERVER["REQUEST_METHOD"];
//print_r(json_decode(file_get_contents("php://input"), true));


if($istekMOD=="POST") {
 $envekle=$db->prepare("INSERT INTO envanter set tabMenu_id=:ptid,ad=:pad,tanim=:ptanim,fiyat=:pfiyat");
 $envekle->execute(array("ptid" => strip_tags($_POST["tid"]),"pad" => strip_tags($_POST["ad"]),
 "ptanim" => strip_tags($_POST["tanim"]),"pfiyat" => strip_tags($_POST["fiyat"])));
 $envsay=$envekle->rowCount();
 //print_r($birekle->errorInfo());
 
		 if($envsay==1){
		     $jsonArray["eklenen_env_id"]=$db->lastInsertId();
		     $httpKOD = 201; //CREATED
             $jsonArray["mesaj"] = "eklendi";
		 }
		 
		 else{
		     $httpKOD = 400; //BAD REQ
             $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
             $jsonArray["mesaj"] = "eklenmedi";
		 }

}

else if($istekMOD=="PUT") {
    
    sesYoksaCik("kullanici_tip","kurumsal");
    
     $gelenler=json_decode(strip_tags(file_get_contents("php://input")));
     
     if(isset($gelenler->id)&&!empty($gelenler->id)&&!isset($gelenler->topguncid)){
        if($db->query("select * from envanter where id='$gelenler->id'")->rowCount()==0){
             $httpKOD = 400;
             $jsonArray["hata"] = TRUE;
             $jsonArray["hataMesaj"] = "envanter bulunamadı";
             }else{
                 $envguncelle=$db->prepare("UPDATE envanter SET tabMenu_id=:ptid,ad=:pad,tanim=:ptanim,fiyat=:pfiyat,alinabilirMi=:palim WHERE id=:pid");
                 $envguncelle->execute(array(
                 "pad" => $gelenler->ad,"ptanim" => $gelenler->tanim,
                 "pfiyat" => $gelenler->fiyat,"palim" => $gelenler->alim,
                 "pid" => $gelenler->id,"ptid" => $gelenler->tid
                 ));
                 // güncelleme başarılı
                 if($envguncelle->rowCount()>0){
                     
                     //ürün güncellendi, eğer sepette bu envantere ait ürün var ise
                     //session(ürünid)(birim_fiyat) = db yeni ürün fiyat
                     
                      /*aynı envid sepette varsa
	    if(ara($_SESSION["sepet"], 'id', $gelenler->id)>0){
	        $sayac=0;
    	      foreach($_SESSION["sepet"] as $a=>$v){
    	        if($v["id"]==intval($gelenler->id)){
                      $_SESSION["sepet"][$sayac]["fiyat"]=floatval($gelenler->fiyat)*$_SESSION["sepet"][$sayac]["adet"];
    	        }
    	        else{
    	            $sayac++;
    	        }
    	      }
        	}               
         *///son aynı envid sepette varsa
                     
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
 else if(isset($gelenler->topguncid)&&!empty($gelenler->topguncid)&&!isset($gelenler->id)){
	 
     if($gelenler->secim=="alinabiliryap")
        $neolacak=1;
        else
        $neolacak=0;
     
     $db->beginTransaction();
    if (!is_array($gelenler->topguncid))
        $gelenler->topguncid = array($gelenler->topguncid);
   
   $envanterler=implode(',', $gelenler->topguncid);
   $guncsorgu=$db->prepare("UPDATE envanter SET alinabilirMi=$neolacak WHERE id IN ($envanterler)");
   $sonuc=$guncsorgu->execute();
   if($sonuc){
       $db->commit();
       $httpKOD = 200;
       $jsonArray["mesaj"]="güncellendi";
   }
   else{
       $db->rollBack();
       $httpKOD = 200;
       $jsonArray["mesaj"]="güncellenemedi,rollback";
   }
     
 }
}

else if($istekMOD=="DELETE"){
    
sesYoksaCik("kullanici_tip","kurumsal");
       // parse_str(file_get_contents("php://input"),$veriler);
       // $bid=$veriler["id"];
       
    if(isset($_GET["id"]) && !empty(trim($_GET["id"])) &&!isset($_GET["silid"])){
        $id=strip_tags($_GET["id"]);
        $envVarMi = $db->query("select * from envanter where id=$id");
        if($envVarMi->rowCount()==1){
            $envsil = $db->query("delete from envanter where id=$id");
            if($envsil->rowCount()==1){
                $httpKOD = 200;
                $jsonArray["mesaj"] = "silindi";
            }else{
                $httpKOD = 400;
                $jsonArray["hata"] = TRUE;
                $jsonArray["hataMesaj"] = "silinemedi";
            }
        }
        else{
            $httpKOD = 400; 
            $jsonArray["hata"] = TRUE;
            $jsonArray["hataMesaj"] = "kayıt bulunamadı";
        }
    }
 
    else if(!isset($_GET["id"]) && isset($_GET["silid"]) && !empty($_GET["silid"])){

        $db->beginTransaction();
        if (!is_array($_GET["silid"]))
            $_GET["silid"] = array($_GET["silid"]); // if it is just one id not in an array, put it in an array so the rest of the code work for all cases
   
        $envanterler=implode(',', $_GET["silid"]);
        $silsorgu=$db->prepare("DELETE FROM envanter WHERE id IN ($envanterler)");
        $sonuc=$silsorgu->execute();
        if($sonuc){
           $db->commit();
           $httpKOD = 200;
           $jsonArray["mesaj"]="silindi";
        }
        else{
           $db->rollBack();
           $httpKOD = 200;
           $jsonArray["mesaj"]="silinemedi,rollback";
        }
    }

    else{
         $httpKOD = 400;
         $jsonArray["hata"] = TRUE; 
         $jsonArray["hataMesaj"] = "envanter id gönder";
    }
}

else if($istekMOD=="GET"){
    // parse_str(file_get_contents("php://input"),$veriler);
       
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
	    $eid=strip_tags($_GET["id"]);
         $enVarMi = $db->query("select * from envanter where id='$eid'");
         if($enVarMi->rowCount()>0){
             $jsonArray["envanterBilgi"]=$enVarMi->fetch(PDO::FETCH_ASSOC);
             
           $tabid=$jsonArray["envanterBilgi"]["tabMenu_id"];
             
           $url = "https://api.omurserdar.com/api/tabmenu/index.php?id=$tabid";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
           $menuAd=$jsonverilerim["tabMenuBilgi"]["ad"];
           //$tmkurumsalid=$jsonverilerim["tabMenuBilgi"]["kurumsal_id"];
           
           $jsonArray["envanterBilgi"]["bulunduguMenu"]=$menuAd;
           $jsonArray["envanterBilgi"]["kurumsal_id"]=$jsonverilerim["tabMenuBilgi"]["kurumsal_id"];
           
             $httpKOD = 200;
             }else {
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "env bulunamadi";
                    }
    }
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "env id gönder";
 }
}

SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);

?>
